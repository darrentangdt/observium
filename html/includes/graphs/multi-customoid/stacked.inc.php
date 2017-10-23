<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage graphs
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

$units = '';
$unit_text = $oid['oid_unit'];
$total_units = '';

$i = 1;

$rrd_list = array();

foreach ($devices as $device_id => $device)
{

  $rrd_file   = get_rrd_path($device, "oid-" . $oid['oid'] . "-" . $oid['oid_type'] . ".rrd");

  if (is_file($rrd_file))
  {
    $rrd_list[$i]['filename'] = $rrd_file;
    $rrd_list[$i]['descr'] = $device['hostname'];
    $rrd_list[$i]['ds'] = 'value';
    $i++;
  }

}

$colours='mixed';

$scale_min = "0";
$nototal = 1;
$show_aggregate = TRUE;

include($config['html_dir']."/includes/graphs/generic_multi_simplex_separated.inc.php");
