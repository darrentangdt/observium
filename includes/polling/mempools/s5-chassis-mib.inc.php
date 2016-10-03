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

$cache_mempool = snmpwalk_cache_multi_oid($device, 's5ChasUtilMemoryTotalMB',     $cache_mempool, 'S5-CHASSIS-MIB');
$cache_mempool = snmpwalk_cache_multi_oid($device, 's5ChasUtilMemoryAvailableMB', $cache_mempool, 'S5-CHASSIS-MIB');

$mempool['total'] = $cache_mempool[$index]['s5ChasUtilMemoryTotalMB'];
$mempool['free']  = $cache_mempool[$index]['s5ChasUtilMemoryAvailableMB'];
$mempool['used']  = $mempool['total'] - $mempool['free'];

// EOF
