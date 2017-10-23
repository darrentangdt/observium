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

if (preg_match('/^RouterOS (.*)/', $poll_device['sysDescr'], $matches))
{
  // RouterOS RB450G
  // RouterOS CCR1036-12G-4S
  // RouterOS x86
  $hardware = $matches[1];
}
else if ($poll_device['sysDescr'] != 'router')
{
  // RB260GS
  $hardware = $poll_device['sysDescr'];
}

// EOF
