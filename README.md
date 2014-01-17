phpXfinityParser
================
Better sorting Xfinity movie list

###Purpose
I started this project to see a list of the new movies added to Xfinity each day, and when movies on my watchlist were going to expire. These are two basic functions that the site should/could have but for whatever reason they don't.

This is a list of the movies they offer. They link directly back to Xfinity when you click on them.

###Theory
* Index.php - renders contents of database
* sql.txt - only needed to create the tables
* svc/movies.get.php - gets a load of movies to be rendered
* util/parser.php - retrieves information from Xfinity. Due to how intensive this can be, the initial load does not retrieve the expire dates of the movies. Subsequent loads will retrieve the expire dates
* util/example.config.php - an example implementation of config.php
* util/expiredates.php - loops over initial load to retreive thier expire dates

###TODO
* create simple login
 * daily summary emails of what will be expiring
 * after parser completes email the log
 * possibly attach that log to the watchlist expiration email
 * add how do i register -> havent decided it it will be open or invite account creation, 
  * if it is invite, add request invite
 * search 
		http://xfinitytv.comcast.net/search?query=rug&resources=odol //will search movies online for rub
		http://xfinitytv.comcast.net/api/v3/entity/search/completion?query=ru&limit=7&type=json
* for initial load, say x out of y



*if expires never then set to some large number to float
* if only 2 lists, and the wan tto move, just move it to the othe rlist
*put movie title in dialog when adding to wishlist
*make buttons disabled for ajax calls blocking for load more and add to watchlist
 * add sort by expired
 * when its the end of the first load, hide button

###Instructions

1. Create database
2. Execute sql.txt
3. Modify config to match database settings and rename
4. Upload files
5. Run util/parser.php
6. Run util/releasedates.php
6. Configure cron to run "util/parser.php" at whatever interval you would like (1-2 times daily recommended)

###Compatibility
1. Written on 5.4.12
2. Deployed to 5.4.17 & 5.3

###Links
####Hosting          
 * http://cpanel.1freehosting.com/ ex. http://xfinity.pixub.com/ 
  * Cron: [php -f /home/username/public_html/util/parser.php] 
  * Issues: 
    * php 5.3
    * This site had trouble loading on an iPhone
    * The parser was saying too many db executions (although I have since changed how the parser works)
 * http://elementfx.com/ ex. http://xfinity.elementfx.com/
  * Cron: [/usr/local/bin/php /home/username/public_html/util/parser.php]
  * Issues:
    * Took a while for the default page to point to index. No action was taken, it fixed itself.
	* must be in a different region, movie list is 300 some short 
 * http://members.000webhost.com/login.php ex. xfinity.comlu.com
	* issues php version 5.2.17
	*also doesnt load on iphone
	* movie list was 7 short, so it must be close as far as region is concerened
	* cron public_html/util/parser.php
 
####Development
* Markdown Editor: http://dillinger.io/
* Source Control: http://windows.github.com/ & http://mac.github.com/
* Local Debug: http://www.wampserver.com/en/ & http://www.mamp.info/en/index.html

####Client
* Bootstrap: http://getbootstrap.com/
* jQuery: http://jquery.com/
* jsRender: http://www.jsviews.com/
* Moment.js: http://momentjs.com/
* Lo-Dash: http://lodash.com/

####Service
* Php: http://php.net/   
* PHP Simple HTML DOM Parser: http://simplehtmldom.sourceforge.net/

###Bandwidth:
Because this is hosted on a free server I've had to implement a couple ways to save space
* CDN js and css
* Hotlink images
* Service only returns needed info in object, and smallest list of objects possible
* Template html, json data = less transfered from server to client
