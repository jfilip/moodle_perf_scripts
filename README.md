# moodle_perf_scripts

Selenium scripts for performance testing of Moodle sites

## Requirements

*	Moodle -- [download.moodle.org](http://download.moodle.org/)
*	Firefox -- [getfirefox.com](http://getfirefox.com/)
*	Selenium IDE Firefox plugin -- [docs.seleniumhq.org/download/](http://docs.seleniumhq.org/download/)

## Usage

You will need to modify the test scripts to use relevant ID numbers for your own system as well as valid
usernames for the users logging in with each set of tests before these will work in other places.

1.	Copy the `test_cachepurge.php` and `test_login.html` files into the root of your Moodle site
2.	Edit the `test_login.html` file so that the `action="/login/index.php"` piece points to the
	login script on your Moodle site (assuming that /login/index.php doesn't already)
3.	Disable HTTP caching in your browser:

	1.	Type `about:config` into the address bar
	2.	Search for `network.http.use-cache`
	3.	Double click the value to set it to *false*

3.	Open the Selenium IDE plugin Firefox and load the `perftest.selenium.html` as a test suite
4.	Edit the base URL in the Selenium IDE to point to the root of your Moodle site
5.	Edit the `config.php` file on your Moodle site to add the following lines somewhere before
	the require for setup.php:

		define('MDL_PERF', true);
		define('MDL_PERFTOLOG', true);

6.	(*Optional*) Empty / erase the error log file for the web server that is running your Moodle site
7.	Hit the *Play entire test suite* button
8.	When the test suite has finished running, you can run the included `perf_log_analysis.php` script
	on your web server's error log file to print out some formatted analysis output as well as a CSV
	file containing the raw data results which can be imported into any spreadsheet program for
	formatting and generating graphs.

## Reference

See my original blog posts about performance testing Moodle:

*	[Moodle 2.4.5 vs. 2.5.1 performance and MUC APC cache store](http://jfilip.ca/2013/08/20/moodle-2-4-5-vs-2-5-1-performance-and-muc-apc-cache-store/)
*	[Moodle performance analysis revisted (now with MariaDB)](http://jfilip.ca/2013/09/24/moodle-performance-analysis-revisted-now-with-mariadb/)
*	[Moodle performance testing — 2.4.6 vs. 2.5.2 vs. 2.6dev](http://jfilip.ca/2013/09/25/moodle-performance-testing-2-4-6-vs-2-5-2-vs-2-6dev/)
