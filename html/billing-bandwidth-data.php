<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage webinterface
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

$start        = $vars['from'];
$end          = $vars['to'];
$xsize        = (is_numeric($vars['x']) ? $vars['x'] : "800" );
$ysize        = (is_numeric($vars['y']) ? $vars['y'] : "250" );
//$count        = (is_numeric($_GET['count']) ? $_GET['count'] : "0" );
//$type         = (isset($_GET['type']) ? $_GET['type'] : "date" );
//$dur          = $end - $start;
//$datefrom     = date('Ymthis', $start);
//$dateto       = date('Ymthis', $end);
$imgtype      = (isset($vars['type'])    ? $vars['type'] : "historical" );
$imgbill      = (isset($vars['imgbill']) ? $vars['imgbill'] : false);
$yaxistitle   = "Bytes";

$in_data      = array();
$out_data     = array();
$tot_data     = array();
$allow_data   = array();
$ave_data     = array();
$overuse_data = array();
$ticklabels   = array();
$timestamps   = array();

if ($imgtype == "historical")
{
  $i                   = "0";

  foreach (dbFetchRows("SELECT * FROM `bill_history` WHERE `bill_id` = ? ORDER BY `bill_datefrom` DESC LIMIT 12", array($bill_id)) as $data)
  {
    $datefrom            = strftime("%e %b %Y", strtotime($data['bill_datefrom']));
    $dateto              = strftime("%e %b %Y", strtotime($data['bill_dateto']));
    $datelabel           = strftime("%e-%b-%Y", strtotime($data['bill_datefrom'])).' - '.strftime("%e-%b-%Y", strtotime($data['bill_dateto']));
    $traf['in']          = $data['traf_in'];
    $traf['out']         = $data['traf_out'];
    $traf['total']       = $data['traf_total'];

    if ($data['bill_type'] == "Quota")
    {
      $traf['allowed'] = $data['bill_allowed'];
      $traf['overuse'] = $data['bill_overuse'];
    } else {
      $traf['allowed'] = "0";
      $traf['overuse'] = "0";
    }

    array_push($ticklabels, $datelabel);
    array_push($in_data, $traf['in']);
    array_push($out_data, $traf['out']);
    array_push($tot_data, $traf['total']);
    array_push($allow_data, $traf['allowed']);
    array_push($overuse_data, $traf['overuse']);
    $rows[] = array('timestamp' => $data['timestamp'], label => $datelabel, 'in' => $data['traf_in'], 'out' => $data['traf_out'], 'total' => $data['traf_total']);
    $i++;
    //print_vars($data);
  }

  if ($i < 12)
  {
    $y = 12 - $i;
    for ($x=0;$x<$y;$x++)
    {
      $allowed = (($x == "0") ? $traf['allowed'] : "0" );
      array_push($in_data, "0");
      array_push($out_data, "0");
      array_push($tot_data, "0");
      array_push($allow_data, $allowed);
      array_push($overuse_data, "0");
      array_push($ticklabels, "");
      // $rows[] = array('timestamp' => $data['timestamp'], label => $datelabel, 'in' => $data['traf_in'], 'out' => $data['traf_out'], 'total' => $data['traf_total']);
    }
  }
  $yaxistitle = "Gigabytes";
  $graph_name = "Historical bandwidth over the last 12 billing periods";

} else {
  $data       = array();
  $average    = 0;
  if ($imgtype == "day")
  {
    foreach (dbFetch("SELECT DISTINCT UNIX_TIMESTAMP(timestamp) as timestamp, SUM(delta) as traf_total, SUM(in_delta) as traf_in, SUM(out_delta) as traf_out FROM bill_data WHERE `bill_id` = ? AND `timestamp` >= FROM_UNIXTIME(?) AND `timestamp` <= FROM_UNIXTIME(?) GROUP BY DATE(timestamp) ORDER BY timestamp ASC", array($bill_id, $start, $end)) as $data)
    {
      $traf['in']    = (isset($data['traf_in']) ? $data['traf_in'] : 0);
      $traf['out']   = (isset($data['traf_out']) ? $data['traf_out'] : 0);
      $traf['total'] = (isset($data['traf_total']) ? $data['traf_total'] : 0);
      $datelabel     = strftime("%e/%m", $data['timestamp']);
      array_push($ticklabels, $datelabel);
      array_push($in_data,  $traf['in']);
      array_push($out_data, $traf['out']);
      array_push($tot_data, $traf['total']);
      $average += $data['traf_total'];
      $rows[] = array('timestamp' => $data['timestamp'], label => $datelabel, 'in' => $data['traf_in'], 'out' => $data['traf_out'], 'total' => $data['traf_total']);
    }
    $ave_count = count($tot_data);
    if ($imgbill != false)
    {
      $days = strftime("%e", date($end - $start)) - $ave_count - 1;
      for ($x=0;$x<$days;$x++)
      {
        array_push($ticklabels, "");
        array_push($in_data, 0);
        array_push($out_data, 0);
        array_push($tot_data, 0);
        $rows[] = array('timestamp' => $data['timestamp'], label => $datelabel, 'in' => $data['traf_in'], 'out' => $data['traf_out'], 'total' => $data['traf_total']);
      }
    }
  } elseif ($imgtype == "hour")
  {
    foreach (dbFetch("SELECT DISTINCT UNIX_TIMESTAMP(timestamp) as timestamp, SUM(delta) as traf_total, SUM(in_delta) as traf_in, SUM(out_delta) as traf_out FROM bill_data WHERE `bill_id` = ? AND `timestamp` >= FROM_UNIXTIME(?) AND `timestamp` <= FROM_UNIXTIME(?) GROUP BY HOUR(timestamp) ORDER BY timestamp ASC", array($bill_id, $start, $end)) as $data)
    {
      $traf['in']    = (isset($data['traf_in']) ? $data['traf_in'] : 0);
      $traf['out']   = (isset($data['traf_out']) ? $data['traf_out'] : 0);
      $traf['total'] = (isset($data['traf_total']) ? $data['traf_total'] : 0);
      $datelabel     = strftime("%H:%M", $data['timestamp']);
      array_push($ticklabels, $datelabel);
      array_push($timestamps, $data['timestamp']);
      array_push($in_data, $traf['in']);
      array_push($out_data, $traf['out']);
      array_push($tot_data, $traf['total']);
      $average += $data['traf_total'];
      $average_out += $data['traf_in'];
      $average_in += $data['traf_out'];

      $rows[] = array('timestamp' => $data['timestamp'], label => $datelabel, 'in' => $data['traf_in'], 'out' => $data['traf_out'], 'total' => $data['traf_total']);
    }
    $ave_count       = count($tot_data);
  }


  $decimal    = 0;
  $average    = $average / $ave_count;
  $average_in    = $average_in / $ave_count;
  $average_out    = $average_out / $ave_count;

  for ($x = 0; $x <= count($tot_data); $x++)
  {
    array_push($ave_data, $average);
  }
  $graph_name        = date('M j g:ia', $start)." - ".date('M j g:ia', $end);

  foreach ($rows as $key => $row)
  {
    $rows[$key]['average'] = $average;
    $rows[$key]['average_in'] = $average_in;
    $rows[$key]['average_out'] = $average_out;

  }


}

//for ($x = 0; $x <= count($tot_data); $x++)
//{
//  echo $timestamps[$x].','.$in_data[$x].','.$out_data[$x]."\n";
//}

//foreach ($rows as $key > $row)
//{
//  $rows[$key]['average'] = $average;
//}


echo json_encode($rows);
