<?php

/**
 * Parse the Moodle performance log information as written out to the error log via MDL_PERFTOLOG
 * and record some culumative statistics based on the data reported for each individual PHP script
 * that is requested.
 *
 * @author     Remote-Learner.net Inc
 * @copyright  (C) 2013 and onwards Remote Learner.net Inc http://www.remote-learner.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function print_usage() {
    die("\n\tUsage: {$argv[0]} [path_to_browser_log_file]\n\n");
}

if ($argc != 2)  {
    print_usage();
}

if (!is_file($argv[1])) {
    print_usage();
}

if (!$fh = fopen($argv[1], 'r')) {
    die("\n\tError: could not open file {$argv[1]} for reading!\n\n");
}

$results = array();

$req     = 0;    // Total number of PHP script requests.
$time    = 0.0;  // Total amount of time (in seconds).
$mem     = 0.0;  // Total amount of memory used (in MB).
$dbread  = 0;    // Total number of DB reads.
$dbwrite = 0;    // Total number of DB writes.

// Pull out the values we care about from each line of the error log that contains peformance data (i.e. a PHP script request).
while (!feof($fh) && $line = fgets($fh)) {
    // This will match the page that we are loading with the URL parameter specified
    $regex1 = '/^.+PERF: .+PERFTEST=(\d\.\d) time: (\d+\.\d+)s.+memory_peak: \d+B \((\d+(\.\d|))MB\).+db reads\/writes: (\d+)\/(\d+).+/';

    // This will match any requests that are made from that page
    $regex2 = '/^.+PERF: .+time: (\d+\.\d+)s.+memory_peak: \d+B \((\d+(\.\d|))MB\).+db reads\/writes: (\d+)\/(\d+).+referer: .+PERFTEST=(\d\.\d)/';

    if (preg_match($regex1, $line, $matches)) {
        $i_testnum = 1;
        $i_time    = 2;
        $i_mem     = 3;
        $i_dbread  = 5;
        $i_dbwrite = 6;

    } else if (preg_match($regex2, $line, $matches)) {
        $i_testnum = 6;
        $i_time    = 1;
        $i_mem     = 2;
        $i_dbread  = 4;
        $i_dbwrite = 5;
    } else {
        continue;
    }

    $testnum = $matches[$i_testnum];

    if (!isset($results[$testnum]['req'])) {
        $results[$testnum]['req'] = 0;
    }
    if (!isset($results[$testnum]['time'])) {
        $results[$testnum]['time'] = 0;
    }
    if (!isset($results[$testnum]['mem'])) {
        $results[$testnum]['mem'] = 0;
    }
    if (!isset($results[$testnum]['dbread'])) {
        $results[$testnum]['dbread'] = 0;
    }
    if (!isset($results[$testnum]['dbwrite'])) {
        $results[$testnum]['dbwrite'] = 0;
    }

    $results[$testnum]['req']++;
    $results[$testnum]['time']    += $matches[$i_time];
    $results[$testnum]['mem']     += $matches[$i_mem];
    $results[$testnum]['dbread']  += $matches[$i_dbread];
    $results[$testnum]['dbwrite'] += $matches[$i_dbwrite];
}

fclose($fh);

$filename = 'perf_results_'.date('Ymd-H:i').'.csv';

if (!$fh = fopen($filename, 'w')) {
    die("\n\tError: could not open file $filename for writing!\n\n");
}

fwrite($fh, "Test number,Total requests,Execution time (s),Peak memory (MB),DB read operations,DB write operations\n");

foreach ($results as $testnum => $totals) {
    echo "\n\tTest #: $testnum\n\n\t\tTotal # of requests: {$totals['req']}\n\t\tTotal time: {$totals['time']}s\n\t\t".
            "Total memory peak: {$totals['mem']}MB\n\t\tTotal DB reads: {$totals['dbread']}\n\t\tTotal DB writes: {$totals['dbwrite']}\n\n";
    fwrite($fh, "{$testnum},{$totals['req']},{$totals['time']},{$totals['mem']},{$totals['dbread']},{$totals['dbwrite']}\n");
}

fclose($fh);
