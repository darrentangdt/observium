<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

#NS-ROOT-MIB::resMemUsage.0 = Gauge32: 29
#NS-ROOT-MIB::memSizeMB.0 = INTEGER: 815

$total   = snmp_get($device, 'memSizeMB.0',   '-OvQ', $mib);
$percent = snmp_get($device, 'resMemUsage.0', '-OvQ', $mib);

if (is_numeric($total) && is_numeric($percent))
{
  $precision = 1024 * 1024;
  //$total    *= $precision;
  $used      = $total * $percent / 100;
  discover_mempool($valid['mempool'], $device, 0, 'NS-ROOT-MIB', 'Memory', $precision, $total, $used);
}

unset($precision, $total, $used, $percent);

// EOF
