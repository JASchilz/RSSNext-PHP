<?php

require_once dirname(__FILE__) . '/../setup.php';

use RSSNext\Util\Util;
use RSSNext\Connection\Connection;

$userId = Util::initOrBump();


// Find the next unread item for this user in the database
$query = <<<EOT
SELECT Min(item_id),
       link,
       item.feed_id
FROM   (SELECT *
        FROM   user_to_feed
        WHERE  user_id = '$userId') AS user_to_feed
       LEFT JOIN (SELECT *
                  FROM   item
                  WHERE  item.feed_id IN (SELECT feed_id
                                          FROM   user_to_feed
                                          WHERE  user_id = '$userId')) AS item
              ON user_to_feed.feed_id = item.feed_id
WHERE  item.item_id > user_to_feed.item_id_last_read
EOT;

$result = mysqli_query(Connection::getConnection(), $query);


$newItemFound = false;
if ($result->num_rows == 1) {
    $row = mysqli_fetch_array($result);

    if ($row['MIN(item_id)']) {

        $newItemFound = true;

        $link = $row['link'];

        $query = <<<EOT
UPDATE user_to_feed
SET    item_id_last_read='{$row['min(item_id)']}'
WHERE  user_id='$userId'
AND    feed_id='{$row['feed_id']}'
EOT;


        $result = mysqli_query(Connection::getConnection(), $query);

        $extraHead = "<meta http-equiv='refresh' content='0;url=$link'>";

        $bodyContent = "<p>We are automatically redirecting you to your next item.
                         Any delay here is due to your feed publisher's server.</p>
                         <p>You may also try clicking directly on this <a href='$link'>link</a>.</p>";

    }
}

if (!$newItemFound) {
    $bodyContent = "<p>You have no more items to read. Feeds are updated about once an hour.</p>";
}

?>
<html><head><link rel="shortcut icon" href="includes/media/favicon.ico?" />
    <?php echo $extraHead; ?>
</head><body>

<?php echo $bodyContent; ?>
</body></html>