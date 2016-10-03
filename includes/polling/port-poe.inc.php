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

// This code is currently not used.

if (isset($port_stats[$port['ifIndex']]) && $port['ifType'] == "ethernetCsmacd")
{ // Check to make sure Port data is cached.

    $this_port = &$port_stats[$port['ifIndex']];

    if ($config['statsd']['enable'] == TRUE)
    {
      foreach (array('cpeExtPsePortPwrAllocated', 'cpeExtPsePortPwrAvailable', 'cpeExtPsePortPwrConsumption', 'cpeExtPsePortMaxPwrDrawn') as $oid)
      {
        // Update StatsD/Carbon
        StatsD::gauge(str_replace(".", "_", $device['hostname']).'.'.'port'.'.'.$port['ifIndex'].'.'.$oid, $this_port[$oid]);
      }
    }

    rrdtool_update_ng($device, 'port-poe', array(
      'PortPwrAllocated' => $port['cpeExtPsePortPwrAllocated'],
      'PortPwrAvailable' => $port['cpeExtPsePortPwrAvailable'],
      'PortConsumption'  => $port['cpeExtPsePortPwrConsumption'],
      'PortMaxPwrDrawn'  => $port['cpeExtPsePortMaxPwrDrawn'],
    ), get_port_rrdindex($port));

    echo("PoE ");
  }

// EOF
