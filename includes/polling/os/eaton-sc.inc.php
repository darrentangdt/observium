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

if (preg_match('/^(?<hardware>SC\d+) .+?version (?<version>[\d\.]+)/', $poll_device['sysDescr'], $matches))
{
  // SC200 Controller - software version 2.02
  // SC200 Controller - software version 4.04
  // SC200 Supervisory Module - software version 1.01
  $hardware = $matches['hardware'];
  $version  = $matches['version'];
}

$serial = snmp_get($device, 'system-Serial-Number.0', '-Oqv', 'RPS-SC200-MIB');

// EOF
