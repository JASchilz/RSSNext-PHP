[![Build Status](https://travis-ci.org/JASchilz/RSSNext-PHP.svg?branch=master)](https://travis-ci.org/JASchilz/RSSNext-PHP)


RSSNext-PHP
===========

RSSNext is a very simple RSS reader: add feeds, then click a button to be taken to your next unread item. I've written this to demonstrate the flavor of my work in HTML, CSS, AJAX, PHP, MySQL and social login over OAuth 2.0. See it in action at [http://php.rssnext.net](http://php.rssnext.net) and read the discussion below.

Features
--------

### HTML, CSS, and Bootstrap

See [public_html/index.php](public_html/index.php) for examples of hand coded user-interfaces. Responsive styling is provided by the Bootstrap framework.

### Javascript, JQuery, and AJAX

The user control panel is populated by asynchronous javascript calls to the server's PHP AJAX handler.

### Modern PHP

RSSNext uses several modern features of PHP development, including:

  * Package management via Composer
  * Namespacing to avoid name collisions
  * Oject-oriented design to encapsulate database calls and provide an effective type system
  * PHPDoc type hinting for code completion in the IDE

### User Acceptance Testing

A comprehensive set of user acceptance tests allows the programmer to aggressively add features and refactor, without fear of introducing undetected bugs.

### Code Style Tests

Code style tests on every push encourage high, uniform quality on each merge into production. This project is held to the PSR2 by PHPCodeSniffer.

### Continuous Integration

Travis-CI provides this project with comprehensive tests on each push, allowing continuous merges into the production branch. This means that features can be rapidly added to the code base.


Things I Would Do Differently
-----------------------------

See the [issue tracker](https://github.com/UWEnrollmentManagement/Framework/issues/) for things that I would like to do differently, including closed issues that I have acted to close.

Installation
------------

Clone RSSNext-php onto your server:

    $ git clone https://github.com/JASchilz/rssnext-php.git
    
Create a MySQL table `rssnext`.
    
Create a `secret_settings.php` in the same directory as `settings.php`:

```
    // Example secret_settings.php
    
    $db_password = 'yourownsecretdatabaseuserpassword';
    $facebook_secret = 'yourownsecretfacebookapplicationpassword';
```
    
Install composer and use it to download the project requirements:

```
    $ curl -sS https://getcomposer.org/installer | php
    $ php composer.phar install
```

Point your web server at the public_html directory.


Requirements
------------

* php 5.6, 7.0
* MySQL 5


Getting Involved
----------------

Feel free to open pull requests or issues. [GitHub](https://github.com/UWEnrollmentManagement/Framework) is the canonical location of this project.

Here's the general sequence of events for code contribution:

1. Open an issue in the [issue tracker](https://github.com/UWEnrollmentManagement/Framework/issues/).
2. In any order:
  * Submit a pull request with a **failing** test that demonstrates the issue/feature.
  * Get acknowledgement/concurrence.
3. Revise your pull request to pass the test in (2). Include documentation, if appropriate.

[PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) compliance is enforced by [CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) in Travis.