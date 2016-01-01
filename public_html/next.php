<?php

require_once dirname(__FILE__) . '/../setup.php';

use RSSNext\Util\Util;
use RSSNext\User\User;

$userId = Util::initOrBump();

$user = User::fromSession();
$link = $user->nextLink();

if ($link) {
    $extraHead = "<meta http-equiv='refresh' content='0;url=$link'>";

    $bodyContent = "<p>We are automatically redirecting you to your next item.
                     Any delay here is due to your feed publisher's server.</p>
                     <p>You may also try clicking directly on this <a href='$link'>link</a>.</p>";
} else {
    $bodyContent = "<p>You have no more items to read. Feeds are updated about once an hour.</p>";
}

?>
<html><head><link rel="shortcut icon" href="includes/media/favicon.ico?" />
    <?php echo $extraHead; ?>
</head><body>

<?php echo $bodyContent; ?>
</body></html>