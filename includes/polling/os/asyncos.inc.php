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

if (preg_match('/^Cisco(?: IronPort)? Model (?<hardware>[\w\-]+),.*AsyncOS Version: (?<version>[\d\.\-]+),.*Serial #: (?<serial>[\w\-]+)/', $poll_device['sysDescr'], $matches))
{
  // Cisco IronPort Model C160, AsyncOS Version: 7.6.2-014, Build Date: 2012-11-02, Serial #: 99999AAA9AA9-99AAAA9
  // Cisco Model S380, AsyncOS Version: 8.8.0-085, Build Date: 2015-07-02, Serial #: 99999AAA9AA9-99AAAA9
  $hardware = $matches['hardware'];
  $version  = $matches['version'];
  $serial   = $matches['serial'];
}

// FIXME. Move to polling graphs
$workq_depth = snmp_get($device, 'workQueueMessages.0', '-Ovq', 'ASYNCOS-MAIL-MIB');
if (is_numeric($workq_depth))
{
  rrdtool_update_ng($device, 'asyncos-workq', array('DEPTH' => $workq_depth));
  //echo("Work Queue: $workq_depth\n");
  $graphs['asyncos_workq'] = TRUE;
}

// EOF
