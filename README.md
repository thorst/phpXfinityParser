phpXfinityParser
================
Better sorting Xfinity movie list

###Purpose
I started this project to simply see a list of the new movies added, and when movies were going to expire.

###TODO
* create simple login
 * logins can save a watchlist
 * daily summary emails
* sort by
 * date added
 * date expire
 * pay toggled off by default
* Store date expired
* Images
* Load past week at a time

###Instructions

1. Create database
2. Execute sql from sql directory
3. Modify config
4. Upload 
5. Run util/parser.php
6. Configure cron to run the parser at whatever interval you would like (once daily recommended)
 a. Example: "php -f /home/username/public_html/util/parser.php"

###Links
####General
* Hosting:    http://cpanel.1freehosting.com/
* Example:	http://xfinity.pixub.com/

####Client:
* Bootstrap:http://getbootstrap.com/
* jQuery:http://jquery.com/
* jsRender:http://www.jsviews.com/

####Service:
* Php:http://php.net/   
* PHP Simple HTML DOM Parser:http://simplehtmldom.sourceforge.net/

