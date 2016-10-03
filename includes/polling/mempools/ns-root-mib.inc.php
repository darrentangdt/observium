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

$mempool['total'] = snmp_get($device, 'memSizeMB.0',   '-OvQ', 'NS-ROOT-MIB');
$mempool['perc']  = snmp_get($device, 'resMemUsage.0', '-OvQ', 'NS-ROOT-MIB');

// EOF
