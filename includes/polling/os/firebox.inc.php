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

if (preg_match('/WatchGuard (\w+) v?(?<version>[\d\.]+)/', $poll_device['sysDescr'], $matches))
{
  //WatchGuard OS 5.2.0
  //WatchGuard Fireware v10.2
  //WatchGuard Fireware v11.3.8
  $version  = $matches['version'];
} else {
  $hardware = $poll_device['sysDescr'];
}

// EOF
