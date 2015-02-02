<?php

require_once dirname(__FILE__) . '/../utils/sessions.php';
$uid = initOrBump();

require_once dirname(__FILE__) . '/../utils/connection.php';

// Find the next unread item for this user in the database
$query = "SELECT MIN(item_id), link, item.feed_id FROM (SELECT * FROM user_to_feed WHERE user_id='$uid') AS user_to_feed LEFT JOIN (SELECT * FROM item WHERE item.feed_id IN (SELECT feed_id FROM user_to_feed WHERE user_id = '$uid')) AS item ON user_to_feed.feed_id = item.feed_id WHERE item.item_id > user_to_feed.item_id_last_read";
$result = mysqli_query(getConnection(), $query);


$newItemFound = false;
if ($result->num_rows == 1)
{
    $row = mysqli_fetch_array($result);

    if($row['MIN(item_id)']) {

        $newItemFound = true;

        $link = $row['link'];

        $query = "UPDATE user_to_feed SET item_id_last_read='" . $row['MIN(item_id)'] . "' WHERE user_id='$uid' AND feed_id='" . $row['feed_id'] . "'";

        $result = mysqli_query(getConnection(), $query);

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
<html><head><link rel="shortcut icon" href="/favicon.ico?" />
    <?php echo $extraHead; ?>
</head><body>

<?php echo $bodyContent; ?>
</body></html>