<?php

require_once dirname(__FILE__) . '/../setup.php';

use RSSNext\Util\Util;

$uid = Util::initOrBump();

?>
<!DOCTYPE html>
<html>
<head>
    <title>RSSNext Home</title>
    <link rel="icon" href="includes/media/favicon.ico" type="image/x-icon" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- JQuery -->
    <script type="text/javascript" src='//code.jquery.com/jquery-2.1.1.min.js'></script>

    <!-- Bootstrap -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src='//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js'></script>

    <!-- Custom Bootstrap -->
    <link rel="stylesheet" type="text/css" href="includes/css/custom-bootstrap.min.css" >

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="includes/css/control-panel.css">

    <!-- Custom js -->
    <script type="application/x-javascript" src="includes/js/control_panel_init.js"></script>
    <script type="application/x-javascript" src="includes/js/actions.js"></script>
    <script type="application/x-javascript" src="includes/js/ajax.js"></script>
    <script type="application/x-javascript" src="includes/js/utils.js"></script>
    <script type="application/x-javascript">
        window.onload = controlPanelInit;
    </script>
</head>

<body>
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button"
                    class="navbar-toggle collapsed"
                    data-toggle="collapse"
                    data-target="#navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">RSSNext</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="#" data-toggle="modal" data-target="#instructions-modal">How To Use</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a id="account-dropdown"
                       href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                       aria-expanded="false">Account <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a id="logout" href="/logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="container">

            <form id="add-feed-form" class="form-inline" role="form">
                <input id="url-input" type="text" class="form-control" placeholder="Feed URL">
                <button class="btn btn-default" onclick="handleURLSubmit();return false;">Add Subscription</button>
            </form>

            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title">Your subscriptions</h3>
                </div>
                <div class="panel-body">
                    <table id="your-feeds" class="table">
                        <tr>
                            <th id="url-table-head">Feed URL</th>
                            <th>Remove</th>
                        </tr>
                    </table>
                </div>
            </div>

        <p>To use, drag this link (<a class="rssnext-link" href="/next.php">RSSNext</a>) into your
            bookmarks toolbar. Then click the link to visit your next unread item.</p></div>

    <!-- Instructions Modal -->
    <div class="modal fade" id="instructions-modal" role="dialog" aria-labelledby="my-modal-label"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="my-modal-label">How To Use RSSNext</h4>
                </div>
                <div class="modal-body">
                    <h4>Reading Your Feeds</h4>
                    <p>To use, drag this link (<a href="/next.php" class="rssnext-link">RSSNext</a>) into your
                        bookmarks toolbar. Then click the link to visit your next unread item. When you're done,
                        you can click again to read another item.</p>

                    <p>Mobile apps and browser plugins are coming.</p>
                    <br style="line-height:30px">
                    <h4>Adding Subscriptions</h4>
                    <p>To add a feed, you must know the feed's rss/atom url. You can often find this by finding
                        a link on the page that says 'rss', or a feedburner subscribe link, or by examining the
                        page's source code.</p>
                    <p>We are developing a search tool that you can use to search for a feed by name.</p>
                    <p>If you have a list of feeds that you would like to import, please contact support@rssnext.net.

                </div>
            </div>
        </div>
    </div>

</div>


</body>
</html>
