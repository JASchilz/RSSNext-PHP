<?php

require_once dirname(__FILE__) . '/../utils/connection.php';
require_once dirname(__FILE__) . '/../utils/make_list.php';

class NotFoundException extends Exception {}


class Feed {

    var $url;
    var $feedId;
    var $lastItemId;

    private function __construct($url, $feedId, $lastItemId=null)
    {
        $this->url = $url;
        $this->feedId = $feedId;
        $this->lastItemId = $lastItemId;
    }

    public static function fromUrl($urlDirty) {
        // Provide a feed object, given a url
        $con = getConnection();

        $url = mysqli_real_escape_string($con, $urlDirty);

        // First check if the url is among the feeds in our database
        $query = "SELECT MAX(`item_id`), `feed_id` FROM `item` WHERE `feed_id` = (SELECT `feed_id` FROM `feed` WHERE `url`='$url') HAVING MAX(`item_id`) is not null";
        $result = mysqli_query($con, $query);

        if ($result->num_rows != 0) {
            // If the feed is among our database
            $row = mysqli_fetch_array($result);
            $feedId = $row['feed_id'];
            $lastItemId = $row['MAX(`item_id`)'];

            return new self($url, $feedId, $lastItemId);
        } else {

            // First, test whether there is a feed at the given url
            try {
                $contents = file_get_contents($url);
                $xmlContents = new SimpleXmlElement($contents);
            } catch (Exception $e) {
                throw new NotFoundException();
            }
            if (!property_exists($xmlContents, 'channel') &&
                !property_exists($xmlContents, 'item') &&
                !property_exists($xmlContents, 'entry')) {
                throw new NotFoundException();
            }

            // Given that there is a feed at the given url, add it to the database
            $query = "INSERT INTO `feed` (`url`) VALUES ('$url') ON DUPLICATE KEY UPDATE feed_id=LAST_INSERT_ID(feed_id)";

            mysqli_query($con, $query);
            if (mysqli_errno($con)) {
                return False;
            }

            $feedId = mysqli_insert_id($con);

            $thisFeed = new self($url, $feedId);
            $thisFeed->update();
            return $thisFeed;

        }

    }

    public static function fromRow($row) {
        // Provide a feed object, given a database row
        return new self($row['url'], $row['feed_id']);
    }

    public function update() {
        // Query the feed uri, find new feed items, and record them in the database.

        $con = getConnection();

        $feedContents = file_get_contents($this->url);
        $xmlFeedContents = new SimpleXmlElement($feedContents);

        $items = array();

        if (property_exists($xmlFeedContents, 'channel')) {
            $xmlFeedContents = $xmlFeedContents->channel;
        }

        // We'll record as many as ten items; $i is our counter
        $i = 0;
        foreach($xmlFeedContents->item as $entry) {
            $items[] = makeList(array_values(self::parseItem($entry, $this->feedId)));

            if (++$i>10) { break; }
        }

        foreach($xmlFeedContents->entry as $entry) {
            //$entry = mysql_real_escape_string($entry);
            $items[] = makeList(array_values(self::parseItem($entry, $this->feedId)));
            if (++$i>10) {break;}
        }

        $items = makeList(array_values(array_reverse($items)));
        $items = trim($items, '() ');
        $items = '(' . $items . ')';

        $query = "INSERT INTO `item` (`feed_id`, `link`, `guid`) VALUES $items ON DUPLICATE KEY UPDATE item_id=LAST_INSERT_ID(item_id)";
        mysqli_query($con, $query);

        $this->lastItemId = $con->insert_id + $con->affected_rows - 1;
    }

    private static function parseItem($entry, $feedId) {
        // Helper function that takes an xml feed entry item and returns an object

        $con = getConnection();

        $item = array();

        $item[] = $feedId;

        $item['link'] = mysqli_real_escape_string($con, isset($entry->link['href']) ? $entry->link['href'] : $entry->link);

        $item['guid'] = property_exists($entry, 'guid') ?
            mysqli_real_escape_string($con, $entry->guid) : $item['link'] . mysqli_real_escape_string($con, $entry->title);


        $item['link'] = "'" . $item['link'] . "'";
        $item['guid'] = "'" . $item['guid'] . "'";

        return $item;

    }
}

