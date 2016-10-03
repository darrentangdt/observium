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

// ProxyAV devices hide their CPUs/Memory/Interfaces in here

$av_array = snmpwalk_cache_oid($device, 'deviceUsage', array(), 'BLUECOAT-SG-USAGE-MIB');

$mempool['perc'] = $av_array[$mempool['mempool_index']]['deviceUsagePercent'];;

unset ($av_array);

// EOF
