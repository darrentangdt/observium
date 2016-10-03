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

// EtherLike-MIB functions

// Process in main ports loop
function process_port_etherlike(&$this_port, $device)
{
  // Used to loop below for StatsD
  $etherlike_oids = array(
    'dot3StatsAlignmentErrors', 'dot3StatsFCSErrors', 'dot3StatsSingleCollisionFrames', 'dot3StatsMultipleCollisionFrames',
    'dot3StatsSQETestErrors', 'dot3StatsDeferredTransmissions', 'dot3StatsLateCollisions', 'dot3StatsExcessiveCollisions',
    'dot3StatsInternalMacTransmitErrors', 'dot3StatsCarrierSenseErrors', 'dot3StatsFrameTooLongs', 'dot3StatsInternalMacReceiveErrors',
    'dot3StatsSymbolErrors'
  );

  // Overwrite ifDuplex with dot3StatsDuplexStatus if it exists
  if (isset($this_port['dot3StatsDuplexStatus']))
  {
    // echo("dot3Duplex, ");
    $this_port['ifDuplex'] = $this_port['dot3StatsDuplexStatus'];
  }

  if ($this_port['ifType'] == "ethernetCsmacd" && isset($this_port['dot3StatsIndex']))
  { // Check to make sure Port data is cached.

    rrdtool_update_ng($device, 'port-dot3', array(
      'dot3StatsAlignmentErrors'           => $this_port['dot3StatsAlignmentErrors'],
      'dot3StatsFCSErrors'                 => $this_port['dot3StatsFCSErrors'],
      'dot3StatsSingleCollisionFrames'     => $this_port['dot3StatsSingleCollisionFrames'],
      'dot3StatsMultipleCollisionFrames'   => $this_port['dot3StatsMultipleCollisionFrames'],
      'dot3StatsSQETestErrors'             => $this_port['dot3StatsSQETestErrors'],
      'dot3StatsDeferredTransmissions'     => $this_port['dot3StatsDeferredTransmissions'],
      'dot3StatsLateCollisions'            => $this_port['dot3StatsLateCollisions'],
      'dot3StatsExcessiveCollisions'       => $this_port['dot3StatsExcessiveCollisions'],
      'dot3StatsInternalMacTransmitErrors' => $this_port['dot3StatsInternalMacTransmitErrors'],
      'dot3StatsCarrierSenseErrors'        => $this_port['dot3StatsCarrierSenseErrors'],
      'dot3StatsFrameTooLongs'             => $this_port['dot3StatsFrameTooLongs'],
      'dot3StatsInternalMacReceiveErrors'  => $this_port['dot3StatsInternalMacReceiveErrors'],
      'dot3StatsSymbolErrors'              => $this_port['dot3StatsSymbolErrors'],
    ), get_port_rrdindex($this_port));

    if ($GLOBALS['config']['statsd']['enable'] == TRUE)
    {
      foreach ($etherlike_oids as $oid)
      {
        // Update StatsD/Carbon
        StatsD::gauge(str_replace(".", "_", $device['hostname']).'.'.'port'.'.'.$this_port['ifIndex'].'.'.$oid, $this_port[$oid]);
      }
    }


  }
}

// EOF
