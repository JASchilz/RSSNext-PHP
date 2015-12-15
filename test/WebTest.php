<?php

require_once dirname(__FILE__) . '/../setup.php';

use RSSNext\Connection\Connection;

function randomChars($numChars) {
    return substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", (int)$numChars/10 + 1)), 0, $numChars);
}

class WebTest extends PHPUnit_Extensions_Selenium2TestCase
{
    protected $username;
    protected $password;
    
    protected $baseUrl = "http://localhost:8001";

    protected $feed1 = "http://rss.cnn.com/rss/cnn_topstories.rss";
    protected $feed2 = "http://rss.cnn.com/rss/cnn_us.rss";

    protected function setUp()
    {
        $this->setBrowser('firefox');
        $this->setBrowserUrl($this->baseUrl);

        $this->username = randomChars(10)."@example.com";
        $this->password = randomChars(10);
    }

    protected function tearDown()
    {

        $query = "DELETE FROM `user` WHERE `login`='$this->username'";
        mysqli_query(Connection::getConnection(), $query);
    }

    protected function createUser() {
        $this->url($this->baseUrl);
        $this->assertEquals('RSSNext - One Click Takes You To Your Next Unread Item', $this->title());

        $this->byCssSelector('#signup-form #id_username')->click();
        $this->keys($this->username);

        $this->byCssSelector('#signup-form #id_password')->click();
        $this->keys($this->password);

        $this->byCssSelector('#signup-form #id_password_confirm')->click();
        $this->keys($this->password);

        $this->byCssSelector('#signup-form button[type="submit"]')->click();
    }

    public function testTitle()
    {
        $this->url($this->baseUrl);
        $this->assertEquals('RSSNext - One Click Takes You To Your Next Unread Item', $this->title());
    }

    public function testAccountCreationAndLogoutAndLogin()
    {
        $this->createUser();
        $this->assertEquals('RSSNext Home', $this->title());

        // Logout
        $this->byId("account-dropdown")->click();
        $this->byId("logout")->click();
        sleep(2);
        $this->assertEquals('RSSNext - One Click Takes You To Your Next Unread Item', $this->title());

        // Login
        $this->byCssSelector('#login-form #id_username')->click();
        $this->keys($this->username);

        $this->byCssSelector('#login-form #id_password')->click();
        $this->keys($this->password);

        $this->byCssSelector('#login-form button[type="submit"]')->click();
        $this->assertEquals('RSSNext Home', $this->title());

    }

    public function testAddFeed()
    {
        $this->createUser();
        $this->assertEquals('RSSNext Home', $this->title());

        // Assert that the feed is not yet added
        $this->assertNotContains( $this->feed1, $this->byId('your-feeds')->text());

        $this->byId("url-input")->click();
        $this->keys($this->feed1);
        $this->byCssSelector('#add-feed-form button')->click();

        sleep(3);

        // Assert that the feed has been added
        $this->assertContains($this->feed1, $this->byId('your-feeds')->text());

        // And that it's still there after a refresh
        $this->refresh();
        $this->assertContains($this->feed1, $this->byId('your-feeds')->text());
    }

    public function testRemoveFeed()
    {
        $this->createUser();
        $this->assertEquals('RSSNext Home', $this->title());

        // Add the feed
        $this->byId("url-input")->click();
        $this->keys($this->feed1);
        $this->byCssSelector('#add-feed-form button')->click();

        sleep(3);

        // Assert that the feed has been added
        $this->assertContains($this->feed1, $this->byId('your-feeds')->text());

        // Click the remove button
        $this->byCssSelector(".remove[data-for-feed-url='{$this->feed1}']")->click();
        $this->acceptAlert();
        sleep(2);

        // Assert that the feed has been removed
        $this->assertNotContains($this->feed1, $this->byId('your-feeds')->text());

        // Assert that it is still removed after refresh
        $this->refresh();
        $this->assertNotContains($this->feed1, $this->byId('your-feeds')->text());
    }

    public function testGetFeeds()
    {
        $this->createUser();
        $this->assertEquals('RSSNext Home', $this->title());

        // Add the feeds
        $this->byId("url-input")->click();
        $this->keys($this->feed1);
        $this->byCssSelector('#add-feed-form button')->click();
        sleep(3);
        $this->byId("url-input")->click();
        $this->keys($this->feed2);
        $this->byCssSelector('#add-feed-form button')->click();
        sleep(3);

        // Refresh to force a get user feeds
        $this->refresh();

        // Assert that the control panel lists the feeds
        $this->assertContains($this->feed1, $this->byId('your-feeds')->text());
        $this->assertContains($this->feed2, $this->byId('your-feeds')->text());
    }

}