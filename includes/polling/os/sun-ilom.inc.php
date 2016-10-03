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

if (preg_match('/^(?<hardware>SUN .*?), ILOM v(?<version>.+?),/', $poll_device['sysDescr'], $matches))
{
  // SUN BLADE 6000 MODULAR SYSTEM, ILOM v3.0.12.11.d, r71974
  // SUN FIRE X4140, ILOM v3.0.6.16.a, r70915

  $hardware = $matches['hardware'];
  $version  = $matches['version'];
} else {
  // FIXME. Use snmp here
}

unset($matches);

// EOF
