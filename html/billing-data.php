<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage billing
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

ini_set('allow_url_fopen', 0);

include_once("../includes/sql-config.inc.php");

include($config['html_dir'] . "/includes/functions.inc.php");
include($config['html_dir'] . "/includes/authenticate.inc.php");

if ($_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR']) { if (!$_SESSION['authenticated']) { echo("unauthenticated"); exit; } }

$vars = get_vars('GET');

if (is_numeric($vars['bill_id']))
{
  if ($_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR'])
  {
    if (bill_permitted($vars['bill_id']))
    {
      $bill_id = $vars['bill_id'];
    } else {
      echo("Unauthorised Access Prohibited.");
      exit;
    }
  } else {
    $bill_id = $vars['bill_id'];
  }
} else {
  echo("Unauthorised Access Prohibited.");
  exit;
}

$start = $vars['from'];
$end =   $vars['to'];
$count = $vars['count'];
$count = $count + 0;
$iter = 1;

if ($vars['type']) { $type = $vars['type']; } else { $type = "date"; }

$dur = $end - $start;

$datefrom = date('Ymthis', $start);
$dateto = date('Ymthis',   $end);

$rate_data = dbFetchRow("SELECT * from `bills` WHERE `bill_id`= ? LIMIT 1", array($bill_id));
$rate_95th = $rate_data['rate_95th'];
$rate_average = $rate_data['rate_average'];

$bill_name = $rate_data['bill_name'];

$dur = $end - $start;

$counttot = dbFetchCell("SELECT count(`delta`) FROM `bill_data` WHERE `bill_id` = ? AND `timestamp` >= FROM_UNIXTIME( ? ) AND `timestamp` <= FROM_UNIXTIME( ? )", array($bill_id, $start, $end));

//$count = round($dur / 300 / (900 * 3), 0);
//if ($count <= 1) { $count = 2; }

//$count = round($dur / 300 / ((1200) * 3), 0);
//if ($count <= 1) { $count = 2; }

//$count = 1;

$i = '0';

foreach (dbFetch("SELECT *, UNIX_TIMESTAMP(timestamp) AS formatted_date FROM bill_data WHERE bill_id = ? AND `timestamp` >= FROM_UNIXTIME( ? ) AND `timestamp` <= FROM_UNIXTIME( ? ) ORDER BY timestamp ASC", array($bill_id, $start, $end)) as $row)
{
  @$timestamp = $row['formatted_date'];
  if (!$first) { $first = $timestamp; }
  @$delta = $row['delta'];
  @$period = $row['period'];
  @$in_delta = $row['in_delta'];
  @$out_delta = $row['out_delta'];
  @$in_value = round($in_delta * 8 / $period, 2);
  @$out_value = round($out_delta * 8 / $period, 2);

  @$last = $timestamp;

  $iter_in      += $in_delta;
  $iter_out     += $out_delta;
  $iter_period  += $period;

  if ($in_value  > $u_in)  { $u_in  = $in_value; }
  if ($out_value > $u_out) { $u_out = $out_value; }

  if ($in_value  < $l_in || !isset($l_in))  { $l_in  = $in_value; }
  if ($out_value < $l_out || !isset($l_out)) { $l_out = $out_value; }


  if ($iter == $count || TRUE)
  {

    $out     = round($iter_out * 8 / $iter_period, 2);
    $out_inv = $out * -1;
    $in      = round($iter_in * 8 / $iter_period, 2);
    $tot     = $out + $in;
    $tot_inv = $tot * -1;

    if ($tot_data[$i] > $max_value) { $max_value = $tot_data[$i]; }

    $ticks[$i]      = $timestamp;
    $per_data[$i]   = $rate_95th;
    $ave_data[$i]   = $rate_average;


    $output['in'][] = array('date' => date('Y-m-d\TH:i:s', $timestamp),
                    'value' =>  $in,
                    'l' => $l_in,
                    'u' => $u_in);

    $output['out'][] = array('date' => date('Y/m/d H:i:s', $timestamp),
                    'value' =>  $out *-1,
                    'l' => $l_out * -1,
                    'u' => $u_out * -1);

    $output['csv'] .= date('Y-m-d\TH:i:s', $timestamp).','.$in.','.($out*-1)."\n";


    $iter       = "1";
    $i++;
    unset($iter_out, $iter_in, $iter_period, $l_out, $u_out, $l_in, $u_in);
  }

  $iter++;
}

//$graph_name = date('M j g:ia', $start) . " - " . date('M j g:ia', $last);

//$n = count($ticks);
//$xmin = $ticks[0];
//$xmax = $ticks[$n-1];

//echo json_encode($output['in']);
//echo json_encode($output['out']);

//echo json_encode(array($output['out'],$output['in']));

echo($output['csv']);

//echo $csv;
