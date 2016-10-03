<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage poller
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

$mempool['perc']  = snmp_get($device, 'fgSysMemUsage.0',    '-OvQ', 'FORTINET-FORTIGATE-MIB');
$mempool['total'] = snmp_get($device, 'fgSysMemCapacity.0', '-OvQ', 'FORTINET-FORTIGATE-MIB');

// EOF
