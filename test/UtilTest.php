<?php

require_once dirname(__FILE__) . '/../setup.php';

use RSSNext\Mock\MockUtil;

class UtilTest extends PHPUnit_Framework_TestCase
{

    public function testMakeList()
    {
        $array = [1, 2, 3, 4, 5];

        $list = MockUtil::makeList($array);

        $this->assertEquals('(1, 2, 3, 4, 5)', $list);
    }

    public function testGetUserId()
    {
        $userId = "u" . (string)rand();
        $_SESSION["userId"] = $userId;

        $this->assertEquals($userId, MockUtil::getUserId());
    }

    public function testInitSession()
    {
        $userId = "u" . (string)rand();
        $_SESSION["userId"] = $userId;

        MockUtil::$sessionStarted = false;

        $this->assertEquals($userId, MockUtil::initSession());
        $this->assertTrue(MockUtil::$sessionStarted);
    }

    public function testInitOrBump()
    {
        $_SESSION["userId"] = null;

        MockUtil::initOrBump();

        $this->assertContains('msg=expired_login', MockUtil::$headers[0]);
    }

    public function testGetAlertMessage()
    {
        // No alert message set
        $this->assertEquals(["hidden", ""], MockUtil::getAlertMessage());

        // Unrecognized alert message set
        $_GET['msg'] = (string)rand();
        $this->assertEquals(["hidden", ""], MockUtil::getAlertMessage());

        // Recognized alert message set
        $msgs = ALERT_MESSAGES;
        $msg = array_keys($msgs)[0];
        $_GET['msg'] = $msg;

        $this->assertEquals($msgs[$msg], MockUtil::getAlertMessage());
    }

}