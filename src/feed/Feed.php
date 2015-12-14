<?php

namespace RSSNext\Feed;

use RSSNext\Connection\Connection;
use RSSNext\Exception\NotFoundException;
use RSSNext\Util\Util;

class Feed
{

    protected $url;
    protected $feedId;
    protected $lastItemId;

    protected function __construct($url, $feedId, $lastItemId = 0)
    {
        $this->url = $url;
        $this->feedId = $feedId;
        $this->lastItemId = $lastItemId;
    }

    public function getFeedId()
    {
        return $this->feedId;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getLastItemId()
    {
        return $this->lastItemId;
    }

    public static function fromUrl($urlDirty)
    {
        // Provide a feed object, given a url
        $con = Connection::getConnection();

        $url = mysqli_real_escape_string($con, $urlDirty);

        // First check if the url is among the feeds in our database
        $query = <<<EOT
SELECT Max(`item_id`),
       `feed_id`
FROM   `item`
WHERE  `feed_id` = (SELECT `feed_id`
                    FROM   `feed`
                    WHERE  `url` = '$url')
HAVING Max(`item_id`) IS NOT NULL
EOT;

        $result = mysqli_query($con, $query);

        if ($result->num_rows != 0) {
            // If the feed is among our database
            $row = mysqli_fetch_array($result);
            $feedId = $row['feed_id'];
            $lastItemId = array_key_exists('MAX(`item_id`)', $row) ? $row['MAX(`item_id`)'] : 1;

            return new self($url, $feedId, $lastItemId);
        } else {

            // First, test whether there is a feed at the given url
            try {
                $contents = static::getFeedContents($url);
                $xmlContents = new \SimpleXmlElement($contents);
            } catch (\Exception $e) {
                throw new NotFoundException();
            }
            if (!property_exists($xmlContents, 'channel') &&
                !property_exists($xmlContents, 'item') &&
                !property_exists($xmlContents, 'entry')) {
                throw new NotFoundException();
            }

            // Given that there is a feed at the given url, add it to the database
            $query = <<<EOT
INSERT INTO `feed`
            (
                        `url`
            )
            VALUES
            (
                        '$url'
            )
ON DUPLICATE KEY UPDATE feed_id=LAST_INSERT_ID(feed_id)
EOT;

            mysqli_query($con, $query);
            if (mysqli_errno($con)) {
                return false;
            }

            $feedId = mysqli_insert_id($con);

            $thisFeed = new self($url, $feedId);
            $thisFeed->update();
            return $thisFeed;

        }

    }

    public static function fromRow($row)
    {
        // Provide a feed object, given a database row
        return new self($row['url'], $row['feed_id']);
    }

    protected static function getFeedContents($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function update()
    {
        // Query the feed uri, find new feed items, and record them in the database.

        $con = Connection::getConnection();

        $feedContents = $this->getFeedContents($this->url);
        $xmlFeedContents = new \SimpleXmlElement($feedContents);

        $items = array();

        if (property_exists($xmlFeedContents, 'channel')) {
            $xmlFeedContents = $xmlFeedContents->channel;
        }

        // We'll record as many as ten items; $i is our counter
        $i = 0;
        foreach ($xmlFeedContents->item as $entry) {
            $items[] = UtiL::makeList(array_values(self::parseItem($entry, $this->feedId)));

            if (++$i>10) {
                break;
            }
        }

        foreach ($xmlFeedContents->entry as $entry) {
            //$entry = mysql_real_escape_string($entry);
            $items[] = Util::makeList(array_values(self::parseItem($entry, $this->feedId)));
            if (++$i>10) {
                break;
            }
        }

        $items = Util::makeList(array_values(array_reverse($items)));
        $items = trim($items, '() ');
        $items = '(' . $items . ')';

        $query = <<<EOT
INSERT INTO `item`
            (
                        `feed_id`,
                        `link`,
                        `guid`
            )
            VALUES $items
ON duplicate KEYUPDATE item_id=last_insert_id(item_id)
EOT;

        mysqli_query($con, $query);

        $this->lastItemId = $con->insert_id + $con->affected_rows - 1;
    }

    protected static function parseItem($entry, $feedId)
    {
        // Helper function that takes an xml feed entry item and returns an object

        $con = Connection::getConnection();

        $item = array();

        $item[] = $feedId;

        $item['link'] = mysqli_real_escape_string(
            $con,
            isset($entry->link['href']) ? $entry->link['href'] : $entry->link
        );

        $item['guid'] = property_exists($entry, 'guid') ?
            mysqli_real_escape_string($con, $entry->guid) :
            $item['link'] . mysqli_real_escape_string($con, $entry->title);


        $item['link'] = "'" . $item['link'] . "'";
        $item['guid'] = "'" . $item['guid'] . "'";

        return $item;

    }
}
