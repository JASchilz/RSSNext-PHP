<?php

require_once dirname(__FILE__) . '/../utils/sessions.php';
require_once dirname(__FILE__) . '/../utils/connection.php';
require_once dirname(__FILE__).'/../feed/feed.php';

class DuplicateUsernameException extends Exception {}
class UsernameOrPasswordInvalidException extends Exception {}


class User {

    var $uid;

    function __construct($uid)
    {
        $this->uid = $uid;
    }

    public static function fromFacebookId($fbid) {

        $con = getConnection();

        // Check if this facebook_id is already in our database of facebook users
        $query = "SELECT * FROM `facebook_to_user` WHERE `facebook_id`='$fbid'";
        $result = mysqli_query($con, $query);

        // If it is, return the associated rssnext user
        if ($result->num_rows == 1) {
            return new self(mysqli_fetch_array($result)['user_id']);
        }

        // If it isn't, create an rssnext user, associate them with the facebook id, and return the rssnext user
        $query = "INSERT INTO `user` (`login`, `password`) VALUES ('', '')";
        mysqli_query($con, $query);
        $uid = mysqli_insert_id($con);

        $query = "INSERT INTO `facebook_to_user` (`user_id`, `facebook_id`) VALUES ('$uid', '$fbid')";
        mysqli_query($con, $query);

        return new self($uid);
    }

    public static function create($usernameDirty, $passwordDirty) {

        $con = getConnection();

        $username = mysqli_real_escape_string($con, $usernameDirty);
        $hash = password_hash($passwordDirty, PASSWORD_DEFAULT);

        $query = "INSERT INTO `user` (`login`, `password`) VALUES ('$username', '$hash')";

        mysqli_query($con, $query);

        return new self(mysqli_insert_id($con));
    }

    public static function validate($usernameDirty, $passwordDirty) {

        $con = getConnection();

        $username = mysqli_real_escape_string($con, $usernameDirty);

        // First, try the 'user' table.
        $query = "SELECT * FROM `user` WHERE `login`='$username'";
        $result = mysqli_query($con, $query);

        // Check if we found the username in the database
        if ($result->num_rows == 1) {
            $row = mysqli_fetch_array($result);

            // Check if the password matched
            if (password_verify($passwordDirty, $row['password'])) {
                return new self($row['user_id']);
            }
        }

        throw new UsernameOrPasswordInvalidException();
    }

    public function getFeeds() {

        $query = "SELECT `url`, `feed_id` FROM `feed` WHERE `feed_id` IN (SELECT `feed_id` FROM `user_to_feed` WHERE `user_id`='$this->uid');";

        $result = mysqli_query(getConnection(), $query);

        $feeds = [];
        while($row = mysqli_fetch_array($result)) {
            $feeds[] = Feed::fromRow($row);
        }

        return $feeds;
    }

    public function removeFeed($feedId) {

        $con = getConnection();

        $query = "DELETE FROM `user_to_feed` WHERE `user_id` = '$this->uid' AND `feed_id` = '$feedId'";
        $con->query($query);

        if ($err = $con->error) {
            return False;
        }
        return True;
    }

    public function addFeed($feed) {

        $con = getConnection();

        $last_item_id = $feed->last_item_id - 1;
        $query = "INSERT INTO `user_to_feed` (`user_id`, `feed_id`, `item_id_last_read`) VALUES ('$this->uid', '$feed->feedId', '$last_item_id')";

        mysqli_query($con, $query);

        if ($con->affected_rows == 1) {
            return $feed->feedId;
        }
        return false;
    }
}

function getCurrentRSSNextUser() {
    return new User(gedUid());
}