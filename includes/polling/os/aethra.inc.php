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

if (preg_match('/Aethra\s+(?<hardware>[\w\-]+).+?Hardware Version:.+?Software Release:\s+(?<version>[\d\.]+\w*)/', $poll_device['sysDescr'], $matches))
{
  // Aethra BG1220 - Hardware Version: 2361A - Aethra Telecomunications Operating System - Software Release: 5.2.0.0 - Copyright (c) 2010 by A TLC Srl
  // Aethra SV6044EVXW - Hardware Version: 2440A - Aethra Telecomunications Operating System - Software Release: 6.1.9.3 - Copyright (c) 2010 by A TLC Srl
  $hardware = $matches['hardware'];
  $version  = $matches['version'];
}
else if (preg_match('/(?<hardware>[\w\-]+)\s+Aethra DSL Device Release:\s+(?<version>[\d\.]+\w*)/', $poll_device['sysDescr'], $matches))
{
  // FS5104 Aethra DSL Device Release: 3.4.25
  // MY2441 Aethra DSL Device Release: 4.0.16C1
  $hardware = $matches['hardware'];
  $version  = $matches['version'];
}
else if (preg_match('/(?<hardware>[\w\-]+)\s+Aethra Video Communication System/', $poll_device['sysDescr'], $matches))
{
  // VegaProS1500 Aethra Video Communication System.
  // AVC8500_Series_3 Aethra Video Communication System.
  // VegaX3Series3 Aethra Video Communication System.
  $hardware = $matches['hardware'];
  //$version  = $matches['version'];
}

/*
Aethra DSL Device
Aethra StarVoice - ADSL Integrated Access Device.
*/

// EOF
