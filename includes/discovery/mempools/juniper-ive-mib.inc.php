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

$percent = snmp_get($device, 'iveMemoryUtil.0', '-OvQ', $mib);

if (is_numeric($percent))
{
  discover_mempool($valid['mempool'], $device, 0, 'JUNIPER-IVE-MIB', 'Memory', 1, 100, $percent);
}

unset($percent);

// EOF
