#!/usr/bin/env php
<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage syslog
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

chdir(dirname($argv[0]));
$scriptname = basename($argv[0]);

//$options['d'] = array(TRUE, TRUE);
include_once("includes/sql-config.inc.php");
include_once("html/includes/functions.inc.php");

// Disable sql profiling, this is a background process without any way to display it
$config['profile_sql'] = FALSE;

$rules = cache_syslog_rules();
$device_rules = cache_syslog_rules_assoc();
$maint = cache_alert_maintenance();

$i = 1;

if (isset($config['syslog']['fifo']) && $config['syslog']['fifo'] !== FALSE)
{
  // FIFO configured, try to grab logs from it
  #echo 'Opening FIFO: '.$config['syslog']['fifo'].PHP_EOL; //No any echo to STDOUT/STDERR!
  $s = fopen($config['syslog']['fifo'], 'r');
} else {
  // No FIFO configured, take logs from stdin
  #echo 'Opening STDIN'.PHP_EOL;                            //No any echo to STDOUT/STDERR!
  $s = fopen('php://stdin', 'r');
}

while ($line = fgets($s))
{
  if (isset($config['syslog']['debug']) && $config['syslog']['debug'])
  {
    // Store RAW syslog line into debug.log
    logfile('debug.log', $line);
  }

  // Update syslog ruleset if they've changed. (this query should be cheap).
  $new_rules = get_obs_attrib('syslog_rules_changed');
  // Also detect if MySQL server has gone away
  if (empty($new_rules))
  {
    if (dbErrorNo() === 2006 && function_exists('dbPing') && dbPing() === FALSE)
    {
      // MySQL server has gone away
      print_error('MySQL server has gone away. Need restart for script syslog.php');
      exit(1);
    }
  }
  if ($new_rules > $cur_rules)
  {
    $cur_rules = $new_rules;
    $rules = cache_syslog_rules();
    $device_rules = cache_syslog_rules_assoc();
    $maint = cache_alert_maintenance();

    // logfile('debug.log', "Rules updated: ".$new_rules);
  }


  // host || facility || priority || level || tag || timestamp || msg || program
  $entry = array(); // Init!!!
  list($entry['host'], $entry['facility'], $entry['priority'], $entry['level'], $entry['tag'], $entry['timestamp'], $entry['msg'], $entry['program']) = explode("||", trim($line));
  process_syslog($entry, 1);
  unset($entry, $line);

  $i++;

  if($i > 10)
  {
    $i=1;
  }
}

// EOF
