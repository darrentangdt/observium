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

if (strstr($poll_device['sysObjectID'], '.1.3.6.1.4.1.9304.200.'))
{
  $hardware = $poll_device['sysName'];
  if (preg_match('/^(?<hw>[\w\-]+ )?version (?<version>\d[\w\.]+)/', $poll_device['sysDescr'], $matches))
  {
    // VRGIII-31412SFP-CW-N-DR version 1.04.4A
    // version 1.01.20
    if ($matches['hw'])
    {
      $hardware = trim($matches['hw']);
    }
    $version  = $matches['version'];
  }

  if (stristr($hardware, 'SIP'))
  {
    $type = 'voip';
  }
//} else if (strstr($poll_device['sysObjectID'], '.1.3.6.1.4.1.9304.100.')) {
} else {
  if (preg_match('/^(?<hw>[\w\-]+ )?version (?<version>\d[\w\.]+)/i', $poll_device['sysDescr'], $matches))
  {
    // HET-2106TP Version 1.03.2G
    // FOS-3126-PLUS Version 1.08.02
    if ($matches['hw'])
    {
      $hardware = trim($matches['hw']);
    }
    $version  = $matches['version'];
  }
}

// EOF
