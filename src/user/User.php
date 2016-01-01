<?php

namespace RSSNext\User;

use RSSNext\Util\Util;
use RSSNext\Connection\Connection;
use RSSNext\Feed\Feed;
use RSSNext\Exception\UsernameOrPasswordInvalidException;

/**
 * Class User encapsulates an account-holding visitor the site
 * @package RSSNext\User
 */
class User
{

    /** @var string */
    public $userId;

    /**
     * Instantiate a user from a user-id.
     *
     * @param string $uid
     */
    protected function __construct($uid)
    {
        $this->userId = $uid;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Instantiate a user from the user id in the current session.
     *
     * @return User
     */
    public static function fromSession()
    {
        return new self(Util::getUserId());
    }

    /**
     * Instantiate a user from their facebook system id.
     *
     * @param string $facebookId The user's facebook system id.
     * @return User
     */
    public static function fromFacebookId($facebookId)
    {

        $con = Connection::getConnection();

        // Check if this facebook_id is already in our database of facebook users
        $query = "SELECT * FROM `facebook_to_user` WHERE `facebook_id`='$facebookId'";
        $result = mysqli_query($con, $query);

        // If it is, return the associated rssnext user
        if ($result->num_rows == 1) {
            return new self(mysqli_fetch_array($result)['user_id']);
        }

        // If it isn't, create an rssnext user, associate them with the facebook id, and return the rssnext user
        $query = "INSERT INTO `user` (`login`, `password`) VALUES ('', '')";
        mysqli_query($con, $query);
        $uid = mysqli_insert_id($con);

        $query = "INSERT INTO `facebook_to_user` (`user_id`, `facebook_id`) VALUES ('$uid', '$facebookId')";
        mysqli_query($con, $query);

        return new self($uid);
    }

    /**
     * Create a new user and insert them into the database.
     *
     * @param string $usernameDirty
     * @param string $passwordDirty
     * @return User
     */
    public static function create($usernameDirty, $passwordDirty)
    {

        $con = Connection::getConnection();

        $username = mysqli_real_escape_string($con, $usernameDirty);
        $hash = password_hash($passwordDirty, PASSWORD_DEFAULT);

        $query = "INSERT INTO `user` (`login`, `password`) VALUES ('$username', '$hash')";

        mysqli_query($con, $query);

        return new self(mysqli_insert_id($con));
    }

    /**
     * Instantiate the user with the given username/password.
     *
     * @param string $usernameDirty
     * @param string $passwordDirty
     * @return User
     * @throws UsernameOrPasswordInvalidException If the user has provided an invalid username and/or password.
     */
    public static function validate($usernameDirty, $passwordDirty)
    {

        $con = Connection::getConnection();

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

    /**
     * Instantiate the feeds associated with the user.
     *
     * @return Feed[]
     */
    public function getFeeds()
    {

        $query = <<<EOT
SELECT `url`,
       `feed_id`
FROM   `feed`
WHERE  `feed_id` IN (SELECT `feed_id`
                     FROM   `user_to_feed`
                     WHERE  `user_id` = '{$this->userId}')
EOT;

        $result = mysqli_query(Connection::getConnection(), $query);

        $feeds = [];
        while ($row = mysqli_fetch_array($result)) {
            $feeds[] = Feed::fromRow($row);
        }

        return $feeds;
    }

    /**
     * Dissociate a feed from a user.
     *
     * @param integer $feedId
     * @return boolean
     */
    public function removeFeed($feedId)
    {

        $con = Connection::getConnection();

        $query = "DELETE FROM `user_to_feed` WHERE `user_id` = '$this->userId' AND `feed_id` = '$feedId'";
        $con->query($query);

        if ($err = $con->error) {
            return false;
        }
        return true;
    }

    /**
     * Associate a feed with a user.
     *
     * @param Feed $feed
     * @return boolean
     */
    public function addFeed(Feed $feed)
    {
        $con = Connection::getConnection();

        $lastItemId = $feed->getLastItemId() - 1;

        $query = <<<EOT
INSERT INTO `user_to_feed`
            (`user_id`,
             `feed_id`,
             `item_id_last_read`)
VALUES      ('{$this->userId}',
             '{$feed->getFeedId()}',
             '{$lastItemId}')
EOT;

        mysqli_query($con, $query);
        if ($con->affected_rows == 1) {
            return $feed->getFeedId();
        }
        return false;
    }

    /**
     * Get the next unread link for this user, and mark it as read.
     *
     * @return string
     */
    public function nextLink()
    {
        $query = <<<EOT
SELECT MIN(item_id),
       link,
       item.feed_id
FROM   (SELECT *
        FROM   user_to_feed
        WHERE  user_id = '{$this->getUserId()}') AS user_to_feed
       LEFT JOIN (SELECT *
                  FROM   item
                  WHERE  item.feed_id IN (SELECT feed_id
                                          FROM   user_to_feed
                                          WHERE  user_id = '{$this->getUserId()}')) AS item
              ON user_to_feed.feed_id = item.feed_id
WHERE  item.item_id > user_to_feed.item_id_last_read
EOT;

        $result = mysqli_query(Connection::getConnection(), $query);

        $link = "";
        if ($result->num_rows == 1) {
            $row = mysqli_fetch_array($result);

            if ($row['MIN(item_id)']) {

                $link = $row['link'];

                $query = <<<EOT
UPDATE user_to_feed
SET    item_id_last_read='{$row['MIN(item_id)']}'
WHERE  user_id='{$this->getUserId()}'
AND    feed_id='{$row['feed_id']}'
EOT;
                $result = mysqli_query(Connection::getConnection(), $query);

            }
        }
        return $link;
    }
}
