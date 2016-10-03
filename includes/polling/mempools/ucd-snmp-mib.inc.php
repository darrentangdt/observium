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

    //$data = snmpwalk_cache_oid($device, "mem", array(), "UCD-SNMP-MIB");
    $data = snmpget_cache_multi($device, 'memTotalReal.0 memAvailReal.0', array(), 'UCD-SNMP-MIB');
    $data = $data[0];

    $mempool['total'] = $data['memTotalReal'] * 1024;
    $mempool['free']  = $data['memAvailReal'] * 1024;
    //$mempool['free']  = ($data['memTotalFree'] + ($data['memBuffer'] + $data['memCached'])) * 1024;
    $mempool['used']  = $mempool['total'] - $mempool['free'];
    $mempool['perc']  = $mempool['free'] / $mempool['total'] * 100;
