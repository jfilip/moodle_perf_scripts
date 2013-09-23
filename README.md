# moodle_perf_scripts

Selenium scripts for performance testing of Moodle sites

## Requirements

*	Moodle -- [download.moodle.org](http://download.moodle.org/)
*	Firefox -- [getfirefox.com](http://getfirefox.com/)
*	Selenium IDE Firefox plugin -- [docs.seleniumhq.org/download/](http://docs.seleniumhq.org/download/)

## Usage

1.	Copy the `test_cachepurge.php` and `test_login.html` files into the root of your Moodle site
2.	Edit the `test_login.html` script so that the `action="/login/index.php"` piece points to the
	login script on your Moodle site (assuming that /login/index.php doesn't already)
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

See my original blog post about performance testing Moodle -- [Moodle 2.4.5 vs. 2.5.1 performance and
MUC APC cache store](http://bit.ly/1aiUJaz).
