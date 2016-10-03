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

$mempool['perc'] = snmp_get($device, "perCentMemoryUtilization.0", "-OvQ", 'ASYNCOS-MAIL-MIB');

// EOF
