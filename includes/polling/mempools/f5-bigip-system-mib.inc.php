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

$tmm_mempool = snmpwalk_cache_multi_oid($device, 'sysTmmStatMemoryUsed', array(), 'F5-BIGIP-SYSTEM-MIB');
$tmm_mempool = snmpwalk_cache_multi_oid($device, 'sysTmmStatMemoryTotal', $tmm_mempool, 'F5-BIGIP-SYSTEM-MIB');

$index            = $mempool['mempool_index'];
$mempool['total'] = $tmm_mempool[$index]['sysTmmStatMemoryTotal'];
$mempool['used']  = $tmm_mempool[$index]['sysTmmStatMemoryUsed'];

// EOF
