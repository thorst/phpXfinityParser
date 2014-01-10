phpXfinityParser
================
Better sorting Xfinity movie list

###Purpose
I started this project to simply see a list of the new movies added, and when movies were going to expire.

###TODO
* create simple login
 * logins can save a watchlist
 * daily summary emails of what will be expiring

* add sort by expired
* Ability to load another week
* github to footer
* run cron every 12 hours

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
* Hosting:      http://cpanel.1freehosting.com/
* Example:      
 * http://xfinity.pixub.com/ [php -f /home/username/public_html/util/parser.php]
 * http://xfinity.elementfx.com/ [/usr/bin/php -q /home/cpanel_username/dir/cron.php]
* Markdown:     http://dillinger.io/
* github client:
* wampserver

####Client:
* Bootstrap:http://getbootstrap.com/
* jQuery:http://jquery.com/
* jsRender:http://www.jsviews.com/
* Moment.js:http://momentjs.com/
* Lo-Dash:http://lodash.com/

####Service:
* Php:http://php.net/   
* PHP Simple HTML DOM Parser:http://simplehtmldom.sourceforge.net/

###Bandwidth:
Because this is hosted on a free server I've had to implement with a couple ways to save space
* CDN js and css
* Hotlink images
* service only returns needed info
* Template html, json data = less transfered from server to client
