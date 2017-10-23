<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage graphs
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

$scale_min = 0;

include_once($config['html_dir']."/includes/graphs/common.inc.php");

$rrd_filename   = get_rrd_path($device, "oid-" . $oid['oid'] . "-" . $oid['oid_type'] . ".rrd");

if (!is_file($rrd_filename))
{
  unset ($rrd_filename);
}

$ds = "value";

$colour_area = "EEEEEE";
$colour_line = "36393D";

$colour_area_max = "FFEE99";

$graph_max = 1;


$unit_text = $oid['oid_unit'];
$line_text = $oid['oid_name'];

include($config['html_dir']."/includes/graphs/generic_simplex.inc.php");

// EOF
