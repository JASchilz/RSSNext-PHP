<?php
// Handles all feed adding, feed removing, feed retrieving functions, via XML over AJAX.

require_once dirname(__FILE__) . '/../utils/sessions.php';
$uid = initOrBump();

require_once dirname(__FILE__).'/../feed/feed.php';
require_once dirname(__FILE__) . '/../user/user.php';


$currentUser = getCurrentRSSNextUser();

// Decode the incoming xml message
$msg = urldecode(file_get_contents('php://input'));
try {
	$msg = new SimpleXMLElement($msg);
	$requestType = $msg->request['context'];
}
catch (Exception $e) {
	error_log("Exception: $e \n --- \n making xml element from: \n $msg");
	$requestType = "decode error";
}

// Begin the outgoing xml message
$xml = new XMLWriter();
$xml->openURI('php://output');
$xml->startDocument('1.0', 'UTF-8');
$xml->setIndent(4);
$xml->startElement('comm');

switch ($requestType)
{
	case "decode error":
		$xml->writeElement("error", "Error decoding xml input");
		break;

	case "get user feeds":

	    $feeds = $currentUser->getFeeds();

		foreach ($feeds as $feed) {
			$xml->startElement('feed');
			$xml->writeAttribute('id', $feed->feedId);
			$xml->text($feed->url);
			$xml->endElement();
		}

		break;
		
	case "remove feed from user":
		$feedId = $msg->request->feed['id'];

		$result = $currentUser->removeFeed($feedId);

		if (!$result) {
			$xml->writeElement("error", "Error removing feed from user.");
		}
		else {
			$xml->startElement('feed');
			$xml->writeAttribute('id', $feedId);
		    $xml->endElement();
		}

		break;
		
	case "add feed to user":
	    $urlDirty = $msg->request->feed;

		$feed = Feed::fromUrl($urlDirty);

		$result = $currentUser->addFeed($feed);
	    
	    if ($result === False) {
	        $xml->writeElement("error", "This feed is already associated with you.");
	    } else {
	        $xml->startElement('feed');
			$xml->writeAttribute('id', $result);
		    $xml->text($urlDirty);
		    $xml->endElement();
	    }
	    
	    break;
		
}

while ($xml->endElement() !== false) {continue; }

$xml->endDocument();
$xml->flush();
