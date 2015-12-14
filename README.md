rssnext-php
=============

RSSNext is a very simple RSS reader: add feeds, then click a button to be taken to your next unread item.
Click through your items until you've read everything in your queue.
RSSNext searches your feeds hourly for new, unread items to serve you.

RSSNext lives at https://www.rssnext.net.

The live version of RSSNext is programmed in Django, a Python framework.
This project is an implementation of RSSNext in php, which I have published to demonstrate my abilities in that language.

RSSNext-php demonstrates my use of php, MySQL, AJAX, CSS, HTML, and social login over OAuth 2.0.
This project also demonstrates the user-acceptance testing methodology.
I am currently hosting RSSNext-php at https://rssnext-php.schilz.org


Installation
===============

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


Compatibility
=============

* php 5.5+
