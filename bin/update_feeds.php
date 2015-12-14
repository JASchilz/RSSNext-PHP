<?php
// updateFeeds() will reach out to all of the feed uris in our database, find
// new items published under these feeds, and insert them into the database.
//
// updateFeeds() is not called by the web php. Instead, the update_feeds.php
// file should be called by the command line php, using an hourly scheduled
// chron job. In this way, RSSNext seeks out feed updates every hour.

require_once dirname(__FILE__) . '/../setup.php';

use RSSNext\Connection\Feed;


function updateFeeds() {

    $query = "SELECT `url`, `feed_id` FROM `feed`";
	$result = mysqli_query(getConnection(), $query);
	
    while($row = mysqli_fetch_array($result))
    {
        Feed::fromRow($row)->update();
        sleep(.2);
    }

}

updateFeeds();