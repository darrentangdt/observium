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

$cache_mempool = snmpwalk_cache_multi_oid($device, 'hwEntityMemUsage', $cache_mempool, 'HUAWEI-ENTITY-EXTENT-MIB');
$cache_mempool = snmpwalk_cache_multi_oid($device, 'hwEntityMemSize',  $cache_mempool, 'HUAWEI-ENTITY-EXTENT-MIB');

$index            = $mempool['mempool_index'];
$mempool['total'] = $cache_mempool[$index]['hwEntityMemSize'];
$mempool['perc']  = $cache_mempool[$index]['hwEntityMemUsage'];

// EOF
