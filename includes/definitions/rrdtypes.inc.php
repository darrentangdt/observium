<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage definitions
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

// This file contains definitions for our RRD files.
// There is no real intersection with the graph_types definitions, because multiple graph types can 
// reference the same physical file, and the same DSes can sometimes be drawn in different ways.

// Available "ds" attributes: type (GAUGE/COUNTER/DERIVE, default COUNTER), heartbeat (default step*2), min (default U), max (default U)

// FIXME the "graphs" (table-based) poller (collect_table) has duplication of both the definition array and the RRD creation code, but it's heavily intertwined
// with SNMP polling code, OID renames, etc. Will be separated out a bit so RRD functions are in rrdtool_* functions only.
// RRD data structure from collect_table should be passed as array (structured like below) when calling rrdtool_update_ng() 
// Advanced RRD functionality from collect_table should be merged into rrdtool_create_ng().

// Do *NOT* change RRD DS names after the fact, that will break all existing RRD files' graphs.

// Generic poller RRDs

$config['rrd_types']['status'] = array(
  'file'  => 'status.rrd',
  'ds'    => array(
    'status' => array('type' => 'GAUGE', 'min' => 0, 'max' => 1),
  ),
);

$config['rrd_types']['ping'] = array(
  'file'  => 'ping.rrd',
  'ds'    => array(
    'ping' => array('type' => 'GAUGE', 'min' => 0, 'max' => 65535),
  ),
);

$config['rrd_types']['ping_snmp'] = array(
  'file'  => 'ping_snmp.rrd',
  'ds'    => array(
    'ping_snmp' => array('type' => 'GAUGE', 'min' => 0, 'max' => 65535),
  ),
);

$config['rrd_types']['perf-poller'] = array(
  'file'  => 'perf-poller.rrd',
  'ds'    => array(
    'val' => array('type' => 'GAUGE', 'min' => 0, 'max' => 38400),
  ),
);

$config['rrd_types']['perf-pollermodule'] = array(
  'file'  => 'perf-pollermodule-%index%.rrd',
  'ds'    => array(
    'val' => array('type' => 'GAUGE', 'min' => 0, 'max' => 38400),
  ),
);

$config['rrd_types']['uptime'] = array(
  'file'  => 'uptime.rrd',
  'ds'    => array(
    'uptime' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

$config['rrd_types']['agent'] = array(
  'file'  => 'agent.rrd',
  'ds'    => array(
    'time' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

// Alerting

$config['rrd_types']['alert'] = array(
  'file'  => 'alert-%index%.rrd',
  'ds'    => array(
    'status' => array('type' => 'GAUGE', 'min' => 0,  'max' => 1),
    'code'   => array('type' => 'GAUGE', 'min' => -7, 'max' => 7),
  ),
);

// FDB Count

$config['rrd_types']['fdb_count'] = array(
  'file'  => 'fdb_count.rrd',
  'ds'    => array(
    'value' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

// Custom OIDs

$config['rrd_types']['customoid-gauge'] = array(
  'file'  => 'oid-%index%-GAUGE.rrd',
  'ds'    => array(
    'value' => array('type' => 'GAUGE'),
  ),
);

$config['rrd_types']['customoid-counter'] = array(
  'file'  => 'oid-%index%-COUNTER.rrd',
  'ds'    => array(
    'value' => array('type' => 'COUNTER'),
  ),
);

// HOST-RESOURCES-MIB

$config['rrd_types']['hr_processes'] = array(
  'file'  => 'hr_processes.rrd',
  'ds'    => array(
    'procs' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

$config['rrd_types']['hr_users'] = array(
  'file'  => 'hr_users.rrd',
  'ds'    => array(
    'users' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

// Sensors

// FIXME - see includes/polling/functions.inc.php

// Mempools

$config['rrd_types']['mempool'] = array(
  'file'  => 'mempool-%index%.rrd',
  'ds'    => array(
    'used' => array('type' => 'GAUGE', 'min' => 0),
    'free' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

// Ports

$config['rrd_types']['port'] = array(
  'file'  => 'port-%index%.rrd',
  'ds'    => array(
    'INOCTETS'         => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'OUTOCTETS'        => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'INERRORS'         => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'OUTERRORS'        => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'INUCASTPKTS'      => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'OUTUCASTPKTS'     => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'INNUCASTPKTS'     => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'OUTNUCASTPKTS'    => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'INDISCARDS'       => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'OUTDISCARDS'      => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'INUNKNOWNPROTOS'  => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'INBROADCASTPKTS'  => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'OUTBROADCASTPKTS' => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'INMULTICASTPKTS'  => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
    'OUTMULTICASTPKTS' => array('type' => 'DERIVE', 'min' => 0, 'max' => $config['max_port_speed']),
  ),
);

$config['rrd_types']['port-dot3'] = array(
  'file'  => 'port-%index%-dot3.rrd',
  'ds'    => array(
    'AlignmentErrors'           => array('type' => 'COUNTER', 'max' => 100000000000),
    'FCSErrors'                 => array('type' => 'COUNTER', 'max' => 100000000000),
    'SingleCollisionFram'     => array('type' => 'COUNTER', 'max' => 100000000000),
    'MultipleCollisionFr'   => array('type' => 'COUNTER', 'max' => 100000000000),
    'SQETestErrors'             => array('type' => 'COUNTER', 'max' => 100000000000),
    'DeferredTransmissio'     => array('type' => 'COUNTER', 'max' => 100000000000),
    'LateCollisions'            => array('type' => 'COUNTER', 'max' => 100000000000),
    'ExcessiveCollisions'       => array('type' => 'COUNTER', 'max' => 100000000000),
    'InternalMacTransmit' => array('type' => 'COUNTER', 'max' => 100000000000),
    'CarrierSenseErrors'        => array('type' => 'COUNTER', 'max' => 100000000000),
    'FrameTooLongs'             => array('type' => 'COUNTER', 'max' => 100000000000),
    'InternalMacReceiveE'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'SymbolErrors'              => array('type' => 'COUNTER', 'max' => 100000000000),
  ),
);

$config['rrd_types']['port-fdbcount'] = array(
  'file'  => 'port-%index%-fdbcount.rrd',
  'ds'    => array(
    'value' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

$config['rrd_types']['port-adsl'] = array(
  'file'  => 'port-%index%-adsl.rrd',
  'ds'    => array(
    'AtucCurrSnrMgn'      => array('type' => 'GAUGE',   'min' => 0, 'max' => 635),
    'AtucCurrAtn'         => array('type' => 'GAUGE',   'min' => 0, 'max' => 635),
    'AtucCurrOutputPwr'   => array('type' => 'GAUGE', '  min' => 0, 'max' => 635),
    'AtucCurrAttainableR' => array('type' => 'GAUGE',   'min' => 0),
    'AtucChanCurrTxRate'  => array('type' => 'GAUGE',   'min' => 0),
    'AturCurrSnrMgn'      => array('type' => 'GAUGE',   'min' => 0, 'max' => 635),
    'AturCurrAtn'         => array('type' => 'GAUGE',   'min' => 0, 'max' => 635),
    'AturCurrOutputPwr'   => array('type' => 'GAUGE',   'min' => 0, 'max' => 635),
    'AturCurrAttainableR' => array('type' => 'GAUGE',   'min' => 0),
    'AturChanCurrTxRate'  => array('type' => 'GAUGE',   'min' => 0),
    'AtucPerfLofs'        => array('type' => 'COUNTER', 'max' => 100000000000),
    'AtucPerfLoss'        => array('type' => 'COUNTER', 'max' => 100000000000),
    'AtucPerfLprs'        => array('type' => 'COUNTER', 'max' => 100000000000),
    'AtucPerfESs'         => array('type' => 'COUNTER', 'max' => 100000000000),
    'AtucPerfInits'       => array('type' => 'COUNTER', 'max' => 100000000000),
    'AturPerfLofs'        => array('type' => 'COUNTER', 'max' => 100000000000),
    'AturPerfLoss'        => array('type' => 'COUNTER', 'max' => 100000000000),
    'AturPerfLprs'        => array('type' => 'COUNTER', 'max' => 100000000000),
    'AturPerfESs'         => array('type' => 'COUNTER', 'max' => 100000000000),
    'AtucChanCorrectedBl' => array('type' => 'COUNTER', 'max' => 100000000000),
    'AtucChanUncorrectBl' => array('type' => 'COUNTER', 'max' => 100000000000),
    'AturChanCorrectedBl' => array('type' => 'COUNTER', 'max' => 100000000000),
    'AturChanUncorrectBl' => array('type' => 'COUNTER', 'max' => 100000000000),
  ),
);

$config['rrd_types']['port-jnx_cos_qstat'] = array(
  'file'  => 'port-%index%-jnx_cos_qstat.rrd',
  'ds'    => array(
    'QedPkts'           => array('type' => 'COUNTER', 'max' => 100000000000),
    'QedBytes'          => array('type' => 'COUNTER', 'max' => 100000000000),
    'TxedPkts'          => array('type' => 'COUNTER', 'max' => 100000000000),
    'TxedBytes'         => array('type' => 'COUNTER', 'max' => 100000000000),
    'TailDropPkts'      => array('type' => 'COUNTER', 'max' => 100000000000),
    'TotalRedDropPkts'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'TotalRedDropBytes' => array('type' => 'COUNTER', 'max' => 100000000000),
  ),
);

$config['rrd_types']['port-poe'] = array(
  'file'  => 'port-%index%-poe.rrd',
  'ds'    => array(
    'PortPwrAllocated' => array('type' => 'GAUGE',  'min' => 0),
    'PortPwrAvailable' => array('type' => 'GAUGE',  'min' => 0),
    'PortConsumption'  => array('type' => 'DERIVE', 'min' => 0),
    'PortMaxPwrDrawn'  => array('type' => 'GAUGE',  'min' => 0),
  ),
);

// Arista

$config['rrd_types']['arista-netstats-sw-ipv4'] = array(
  'file'  => 'arista-netstats-sw-ip.rrd',
  'ds'    => array(
    'InReceives'       => array('type' => 'COUNTER', 'max' => 100000000000),
    'InHdrErrors'      => array('type' => 'COUNTER', 'max' => 100000000000),
    'InNoRoutes'       => array('type' => 'COUNTER', 'max' => 100000000000),
    'InAddrErrors'     => array('type' => 'COUNTER', 'max' => 100000000000),
    'InUnknownProtos'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'InTruncatedPkts'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'InForwDatagrams'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'ReasmReqds'       => array('type' => 'COUNTER', 'max' => 100000000000),
    'ReasmOKs'         => array('type' => 'COUNTER', 'max' => 100000000000),
    'ReasmFails'       => array('type' => 'COUNTER', 'max' => 100000000000),
    'OutNoRoutes'      => array('type' => 'COUNTER', 'max' => 100000000000),
    'OutForwDatagrams' => array('type' => 'COUNTER', 'max' => 100000000000),
    'OutDiscards'      => array('type' => 'COUNTER', 'max' => 100000000000),
    'OutFragReqds'     => array('type' => 'COUNTER', 'max' => 100000000000),
    'OutFragOKs'       => array('type' => 'COUNTER', 'max' => 100000000000),
    'OutFragFails'     => array('type' => 'COUNTER', 'max' => 100000000000),
    'OutFragCreates'   => array('type' => 'COUNTER', 'max' => 100000000000),
    'OutTransmits'     => array('type' => 'COUNTER', 'max' => 100000000000),
  ),
);

$config['rrd_types']['arista-netstats-sw-ipv6'] = array(
  'file'  => 'arista-netstats-sw-ip6.rrd',
  'ds'    => $config['rrd_types']['arista-netstats-sw-ipv4']['ds'],
);

// Alcatel

$config['rrd_types']['port-sros_egress_qstat'] = array(
  'file'  => 'port-%index%-sros_egress_qstat.rrd',
  'ds'    => array(
    'FwdInProfPkts'   => array('type' => 'COUNTER', 'max' => 100000000000),
    'FwdOutProfPkts'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'FwdInProfOcts'   => array('type' => 'COUNTER', 'max' => 100000000000),
    'FwdOutProfOcts'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'DroInProfPkts'   => array('type' => 'COUNTER', 'max' => 100000000000),
    'DroOutProfPkts'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'DroInProfOcts'   => array('type' => 'COUNTER', 'max' => 100000000000),
    'DroOutProfOcts'  => array('type' => 'COUNTER', 'max' => 100000000000),
  ),
);

$config['rrd_types']['port-sros_ingress_qstat'] = array(
  'file'  => 'port-%index%-sros_ingress_qstat.rrd',
  'ds'    => array(
    'FwdInProfPkts'   => array('type' => 'COUNTER', 'max' => 100000000000),
    'FwdOutProfPkts'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'FwdInProfOcts'   => array('type' => 'COUNTER', 'max' => 100000000000),
    'FwdOutProfOcts'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'DroInProfPkts'   => array('type' => 'COUNTER', 'max' => 100000000000),
    'DroOutProfPkts'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'DroInProfOcts'   => array('type' => 'COUNTER', 'max' => 100000000000),
    'DroOutProfOcts'  => array('type' => 'COUNTER', 'max' => 100000000000),
  ),
);

// Juniper

$config['rrd_types']['screenos-sessions'] = array(
  'file'  => 'screenos_sessions.rrd',
  'ds'    => array(
    'allocate' => array('type' => 'GAUGE', 'min' => 0, 'max' => 3000000),
    'max'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 3000000),
    'failed'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000),
  ),
);

$config['rrd_types']['junos-atm-vp'] = array(
  'file'  => 'vp-%index%.rrd',
  'ds'    => array(
    'incells'         => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'outcells'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'inpackets'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'outpackets'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'inpacketoctets'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'outpacketoctets' => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'inpacketerrors'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'outpacketerrors' => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['juniperive-users'] = array(
  'file'  => 'juniperive_users.rrd',
  'ds'    => array(
    'clusterusers' => array('type' => 'GAUGE', 'min' => 0, 'max' => 3000000),
    'iveusers'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 3000000),
  ),
);

$config['rrd_types']['juniperive-meetings'] = array(
  'file'  => 'juniperive_meetings.rrd',
  'ds'    => array(
    'meetingusers' => array('type' => 'GAUGE', 'min' => 0, 'max' => 3000000),
    'meetings'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 3000000),
  ),
);

$config['rrd_types']['juniperive-connections'] = array(
  'file'  => 'juniperive_connections.rrd',
  'ds'    => array(
    'webusers'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 3000000),
    'mailusers' => array('type' => 'GAUGE', 'min' => 0, 'max' => 3000000),
  ),
);

$config['rrd_types']['juniperive-storage'] = array(
  'file'  => 'juniperive_storage.rrd',
  'ds'    => array(
    'diskpercent' => array('type' => 'GAUGE', 'min' => 0, 'max' => 3000000),
    'logpercent'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 3000000),
  ),
);

// Fortigate

$config['rrd_types']['fortigate-sessions'] = array(
  'file'  => 'fortigate_sessions.rrd',
  'ds'    => array(
    'sessions' => array('type' => 'GAUGE',  'min' => 0, 'max' => 3000000),
  ),
);

// PanOS

$config['rrd_types']['panos-sessions'] = array(
  'file'  => 'panos-sessions.rrd',
  'ds'    => array(
    'sessions' => array('type' => 'GAUGE',  'min' => 0, 'max' => 100000000),
  ),
);

// AsyncOS

$config['rrd_types']['asyncos-workq'] = array(
  'file'  => 'asyncos_workq.rrd',
  'ds'    => array(
    'DEPTH' => array('type' => 'ABSOLUTE',  'min' => 0),
  ),
);

// MAC Accounting

$config['rrd_types']['mac_acc'] = array(
  'file'  => 'mac_acc-%index%.rrd',
  'ds'    => array(
    'IN'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 12500000000),
    'OUT'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 12500000000),
    'PIN'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 12500000000),
    'POUT' => array('type' => 'COUNTER', 'min' => 0, 'max' => 12500000000),
  ),
);

// Storage

$config['rrd_types']['storage'] = array(
  'file'  => 'storage-%index%.rrd',
  'ds'    => array(
    'used' => array('type' => 'GAUGE', 'min' => 0),
    'free' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

// Processors

$config['rrd_types']['processor'] = array(
  'file'  => 'processor-%index%.rrd',
  'ds'    => array(
    'usage' => array('type' => 'GAUGE', 'max' => 1000),
  ),
);

// Toner

$config['rrd_types']['toner'] = array(
  'file'  => 'toner-%index%.rrd',
  'ds'    => array(
    'level' => array('type' => 'GAUGE', 'max' => 20000),
  ),
);

$config['rrd_types']['pagecount'] = array(
  'file'  => 'pagecount.rrd',
  'ds'    => array(
    'pagecount' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

// BGP

$config['rrd_types']['cbgp'] = array(
  'file'  => 'cbgp-%index%.rrd',
  'ds'    => array(
    'AcceptedPrefixes'   => array('type' => 'GAUGE'),
    'DeniedPrefixes'     => array('type' => 'GAUGE'),
    'AdvertisedPrefixes' => array('type' => 'GAUGE'),
    'SuppressedPrefixes' => array('type' => 'GAUGE'),
    'WithdrawnPrefixes'  => array('type' => 'GAUGE'),
  ),
);

$config['rrd_types']['bgp'] = array(
  'file'  => 'bgp-%index%.rrd',
  'ds'    => array(
    'bgpPeerOutUpdates'  => array('type' => 'COUNTER'),
    'bgpPeerInUpdates'   => array('type' => 'COUNTER'),
    'bgpPeerOutTotal'    => array('type' => 'COUNTER'),
    'bgpPeerInTotal'     => array('type' => 'COUNTER'),
    'bgpPeerEstablished' => array('type' => 'GAUGE'),
  ),
);

// OSPF

$config['rrd_types']['ospf-statistics'] = array(
  'file'  => 'ospf-statistics.rrd',
  'ds'    => array(
    'instances'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'areas'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'ports'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'neighbours' => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
  ),
);

// Wifi

$config['rrd_types']['wificlients'] = array(
  'file'  => 'wificlients-%index%.rrd',
  'ds'    => array(
    'wificlients' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

// P2P Radio

$config['rrd_types']['p2p_radio'] = array(
  'file'  => 'p2p_radio-%index%.rrd',
  'ds'    => array(
    'tx_power'     => array('type' => 'GAUGE'),
    'rx_level'     => array('type' => 'GAUGE'),
    'rmse'         => array('type' => 'GAUGE'),
    'agc_gain'     => array('type' => 'GAUGE'),
    'cur_capacity' => array('type' => 'GAUGE'),
    'sym_rate_tx'  => array('type' => 'GAUGE'),
    'sym_rate_rx'  => array('type' => 'GAUGE'),
  ),
);

// Aruba

$config['rrd_types']['aruba-controller'] = array(
  'file'  => 'aruba-controller.rrd',
  'ds'    => array(
    'NUMAPS'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 12500000000),
    'NUMCLIENTS' => array('type' => 'GAUGE', 'min' => 0, 'max' => 12500000000),
  ),
);

$config['rrd_types']['aruba-ap'] = array(
  'file'  => 'arubaap-%index%.rrd',
  'ds'    => array(
    'channel'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 200),
    'txpow'         => array('type' => 'GAUGE', 'min' => 0, 'max' => 200),
    'radioutil'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 100),
    'nummonclients' => array('type' => 'GAUGE', 'min' => 0, 'max' => 500),
    'nummonbssid'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 200),
    'numasoclients' => array('type' => 'GAUGE', 'min' => 0, 'max' => 500),
    'interference'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 2000),
  ),
);

// Altiga SSL

$config['rrd_types']['altiga-ssl'] = array(
  'file'  => 'altiga-ssl.rrd',
  'ds'    => array(
    'TotalSessions'     => array('type' => 'COUNTER', 'max' => 100000),
    'ActiveSessions'    => array('type' => 'GAUGE',   'min' => 0),
    'MaxSessions'       => array('type' => 'GAUGE',   'min' => 0),
    'PreDecryptOctets'  => array('type' => 'COUNTER', 'max' => 100000000000), 
    'PostDecryptOctets' => array('type' => 'COUNTER', 'max' => 100000000000),
    'PreEncryptOctets'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'PostEncryptOctets' => array('type' => 'COUNTER', 'max' => 100000000000),
  ),
);

// Cisco

$config['rrd_types']['cisco-eigrp'] = array(
  'file'  => 'eigrp_port-%index%.rrd',
  'ds'    => array(
     'MeanSrtt'       => array('type' => 'GAUGE',   'min' => 0, 'max' => 10000),
     'UMcasts'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 10000000000),
     'RMcasts'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 10000000000),
     'UUcasts'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 10000000000),
     'RUcasts'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 10000000000),
     'McastExcepts'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 10000000000),
     'CRpkts'         => array('type' => 'COUNTER', 'min' => 0, 'max' => 10000000000),
     'AcksSuppressed' => array('type' => 'COUNTER', 'min' => 0, 'max' => 10000000000),
     'RetransSent'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 10000000000),
     'OOSrvcd'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 10000000000),
  ),
);

$config['rrd_types']['cisco-cbqos'] = array(
  'file'  => 'cbqos-%index%.rrd',
  'ds'    => array(
    'PrePolicyPkt'   => array('type' => 'COUNTER', 'max' => 100000000000),
    'PrePolicyByte'  => array('type' => 'COUNTER', 'max' => 100000000000),
    'PostPolicyByte' => array('type' => 'COUNTER', 'max' => 100000000000),
    'DropPkt'        => array('type' => 'COUNTER', 'max' => 100000000000),
    'DropByte'       => array('type' => 'COUNTER', 'max' => 100000000000),
    'NoBufDropPkt'   => array('type' => 'COUNTER', 'max' => 100000000000),
  ),
);

$config['rrd_types']['cisco-cef-pfx'] = array(
  'file'  => 'cef-pfx-%index%.rrd',
  'ds'    => array(
    'pfx' => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000000),
  ),
);

$config['rrd_types']['cisco-cras-sessions'] = array(
  'file'  => 'cras_sessions.rrd',
  'ds'    => array(
    'email'  => array('type' => 'GAUGE', 'min' => 0),
    'ipsec'  => array('type' => 'GAUGE', 'min' => 0),
    'l2l'    => array('type' => 'GAUGE', 'min' => 0),
    'lb'     => array('type' => 'GAUGE', 'min' => 0),
    'svc'    => array('type' => 'GAUGE', 'min' => 0),
    'webvpn' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

$config['rrd_types']['cisco-cef-switching'] = array(
  'file'  => 'cefswitching-%index%.rrd',
  'ds'    => array(
    'drop'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 1000000),
    'punt'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 1000000),
    'hostpunt' => array('type' => 'DERIVE', 'min' => 0, 'max' => 1000000),
  ),
);

$config['rrd_types']['cipsec-flow'] = array(
  'file'  => 'cipsec_flow.rrd',
  'ds'    => array(
    'Tunnels'          => array('type' => 'GAUGE',   'min' => 0),
    'InOctets'         => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'OutOctets'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'InDecompOctets'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'OutUncompOctets'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'InPkts'           => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'OutPkts'          => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'InDrops'          => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'InReplayDrops'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'OutDrops'         => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'InAuths'          => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'OutAuths'         => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'InAuthFails'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'OutAuthFails'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'InDencrypts'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'OutEncrypts'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'InDecryptFails'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'OutEncryptFails'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'ProtocolUseFails' => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'NoSaFails'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'SysCapFails'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
  ),
);

$config['rrd_types']['cipsec-tunnels'] = array(
  'file'  => 'ipsectunnel-%index%.rrd',
  'ds'    => array(
    'TunInOctets'         => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunInDecompOctets'   => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunInPkts'           => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunInDropPkts'       => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunInReplayDropPkts' => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunInAuths'          => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunInAuthFails'      => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunInDecrypts'       => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunInDecryptFails'   => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunOutOctets'        => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunOutUncompOctets'  => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunOutPkts'          => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunOutDropPkts'      => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunOutAuths'         => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunOutAuthFails'     => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunOutEncrypts'      => array('type' => 'COUNTER', 'max' => 1000000000),
    'TunOutEncryptFails'  => array('type' => 'COUNTER', 'max' => 1000000000),
  ),
);

$config['rrd_types']['c6kxbar'] = array(
  'file'  => 'c6kxbar-%index%.rrd',
  'ds'    => array(
    'inutil'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 100),
    'oututil'    => array('type' => 'GAUGE',  'min' => 0, 'max' => 100),
    'outdropped' => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'outerrors'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'inerrors'   => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['cisco-vpdn'] = array(
  'file'  => 'vpdn-%index%.rrd',
  'ds'    => array(
    'tunnels'  => array('type' => 'GAUGE',   'min' => 0),
    'sessions' => array('type' => 'GAUGE',   'min' => 0),
    'denied'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000),
  ),
);

////////////// Unix Agent apps

// KSM pages

$config['rrd_types']['ksm-pages'] = array(
  'file'  => 'ksm-pages.rrd',
  'ds'    => array(
    'pagesShared'   => array('type' => 'GAUGE'),
    'pagesSharing'  => array('type' => 'GAUGE'),
    'pagesUnshared' => array('type' => 'GAUGE'),
  ),
);

// EDAC errors

$config['rrd_types']['edac-errors'] = array(
  'file'  => 'edac-errors-%index%.rrd',
  'ds'    => array(
    'errors' => array('type' => 'GAUGE'),
  ),
);

// Diskstat

$config['rrd_types']['diskstat'] = array(
  'file'  => 'diskstat-%index%.rrd',
  'ds'    => array(
    'readcount'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'readcount_merged'   => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'readcount_sectors'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'time_reading'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'writecount'         => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'writecount_merged'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'writecount_sectors' => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'time_writing'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'pending_ios'        => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'time_io'            => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'time_wio'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
  ),
);

// BIND

$config['rrd_types']['bind-req-in'] = array(
  'file'  => 'app-bind-%index%-req-in.rrd',
  'ds'    => array(
    'query'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'status' => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'notify' => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'update' => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
  ),
);

// DHCPKit

$config['rrd_types']['dhcpkit-stats'] = array(
  'file'  => 'app-dhcpkit-%index%-stats.rrd',
  'ds'    => array(
    'incoming_packets'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'outgoing_packets'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'unparsable_packets'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'handling_errors'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'for_other_server'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'do_not_respond'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'use_multicast'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'msg_in_solicit'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'msg_in_request'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'msg_in_confirm'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'msg_in_renew'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'msg_in_rebind'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'msg_in_release'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'msg_in_decline'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'msg_in_inf_req'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'msg_out_advertise'   => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'msg_out_reply'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
    'msg_out_reconfigure' => array('type' => 'DERIVE', 'min' => 0, 'max' => 7500000),
  ),
);

// Dovecot

$config['rrd_types']['dovecot'] = array(
  'file'  => 'app-dovecot.rrd',
  'ds'    => array(
	  'num_logins' => array('type' => 'COUNTER'),
	  'num_cmds' => array('type' => 'COUNTER'),
	  'num_connected_sess' => array('type' => 'GAUGE'),
	  'auth_successes' => array('type' => 'COUNTER'),
	  'auth_master_success' => array('type' => 'COUNTER'),
	  'auth_failures' => array('type' => 'COUNTER'),
	  'auth_db_tempfails' => array('type' => 'COUNTER'),
	  'auth_cache_hits' => array('type' => 'COUNTER'),
	  'auth_cache_misses' => array('type' => 'COUNTER'),
	  'user_cpu' => array('type' => 'COUNTER'),
	  'sys_cpu' => array('type' => 'COUNTER'),
	  'clock_time' => array('type' => 'COUNTER'),
	  'min_faults' => array('type' => 'COUNTER'),
	  'maj_faults' => array('type' => 'COUNTER'),
	  'vol_cs' => array('type' => 'COUNTER'),
	  'invol_cs' => array('type' => 'COUNTER'),
	  'disk_input' => array('type' => 'COUNTER'),
	  'disk_output' => array('type' => 'COUNTER'),
	  'read_count' => array('type' => 'COUNTER'),
	  'read_bytes' => array('type' => 'COUNTER'),
	  'write_count' => array('type' => 'COUNTER'),
	  'write_bytes' => array('type' => 'COUNTER'),
	  'mail_lookup_path' => array('type' => 'COUNTER'),
	  'mail_lookup_attr' => array('type' => 'COUNTER'),
	  'mail_read_count' => array('type' => 'COUNTER'),
	  'mail_read_bytes' => array('type' => 'COUNTER'),
	  'mail_cache_hits' => array('type' => 'COUNTER'),
  ),
);

// Exchange

$config['rrd_types']['exchange-as'] = array(
  'file'  => 'wmi-app-exchange-as.rrd',
  'ds'    => array(
    'synccommandspending' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'pingcommandspending' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'currentrequests'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['exchange-auto'] = array(
  'file'  => 'wmi-app-exchange-auto.rrd',
  'ds'    => array(
    'totalrequests'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'errorresponses' => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['exchange-oab'] = array(
  'file'  => 'wmi-app-exchange-oab.rrd',
  'ds'    => array(
    'dltasksqueued'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'dltaskscompleted' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['exchange-owa'] = array(
  'file'  => 'wmi-app-exchange-owa.rrd',
  'ds'    => array(
    'currentuniqueusers' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'avgresponsetime'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'avgsearchtime'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['exchange-tqs'] = array(
  'file'  => 'wmi-app-exchange-tqs.rrd',
  'ds'    => array(
    'aggregatequeue'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'deliveryqpersec' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'mbdeliverqueue'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'submissionqueue' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['exchange-smtp'] = array(
  'file'  => 'wmi-app-exchange-smtp.rrd',
  'ds'    => array(
    'currentconnections' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'msgsentpersec'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['exchange-is'] = array(
  'file'  => 'wmi-app-exchange-is.rrd',
  'ds'    => array(
    'activeconcount'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'usercount'         => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'rpcrequests'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'rpcavglatency'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'clientrpcfailbusy' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['exchange-mailbox'] = array(
  'file'  => 'wmi-app-exchange-mailbox.rrd',
  'ds'    => array(
    'rpcavglatency' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'msgqueued'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'msgsentsec'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'msgdeliversec' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'msgsubmitsec'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

// MS SQL

$config['rrd_types']['mssql-stats'] = array(
  'file'  => 'wmi-app-mssql_%index%-stats.rrd',
  'ds'    => array(
    'userconnections' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['mssql-memory'] = array(
  'file'  => 'wmi-app-mssql_%index%-memory.rrd',
  'ds'    => array(
    'totalmemory'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'targetmemory'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'cachememory'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'grantsoutstanding' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'grantspending'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['mssql-buffer'] = array(
  'file'  => 'wmi-app-mssql_%index%-buffer.rrd',
  'ds'    => array(
    'pagelifeexpect' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'pagelookupssec' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'pagereadssec'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'pagewritessec'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'freeliststalls' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['mssql-cpu'] = array(
  'file'  => 'wmi-app-mssql_%index%-cpu.rrd',
  'ds'    => array(
    'percproctime' => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'threads'      => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'lastpoll'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
  ),
);

// JVM over JMX

$config['rrd_types']['jvmoverjmx'] = array(
  'file'  => 'app-jvmoverjmx-%index%.rrd',
  'ds'    => array(
    'UpTime'             => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'HeapMemoryMaxUsage' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'HeapMemoryUsed'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'NonHeapMemoryMax'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'NonHeapMemoryUsed'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'EdenSpaceMax'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'EdenSpaceUsed'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'PermGenMax'         => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'PermGenUsed'        => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'OldGenMax'          => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'OldGenUsed'         => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'DaemonThreads'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'TotalThreads'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'LoadedClassCount'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'UnloadedClassCount' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'G1OldGenCount'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'G1OldGenTime'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'G1YoungGenCount'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'G1YoungGenTime'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'CMSCount'           => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'CMSTime'            => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'ParNewCount'        => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'ParNewTime'         => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'CopyCount'          => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'CopyTime'           => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'PSMarkSweepCount'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'PSMarkSweepTime'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'PSScavengeCount'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'PSScavengeTime'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

// Unbound

$config['rrd_types']['unbound-thread'] = array(
  'file'  => 'app-unbound-%index%.rrd',
  'ds'    => array(
    'numQueries'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'cacheHits'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'cacheMiss'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'prefetch'            => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'recursiveReplies'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'reqListAvg'          => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'reqListMax'          => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'reqListOverwritten'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'reqListExceeded'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'reqListCurrentAll'   => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'reqListCurrentUser'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'recursionTimeAvg'    => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'recursionTimeMedian' => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['unbound-memory'] = array(
  'file'  => 'app-unbound-%index%-memory.rrd',
  'ds'    => array(
    'memTotal'        => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'memCacheRRset'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'memCacheMessage' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'memModIterator'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'memModValidator' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['unbound-queries'] = array(
  'file'  => 'app-unbound-%index%-queries.rrd',
  'ds'    => array(
    'qTypeA'           => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeA6'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeAAAA'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeAFSDB'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeANY'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeAPL'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeATMA'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeAXFR'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeCERT'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeCNAME'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeDHCID'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeDLV'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeDNAME'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeDNSKEY'      => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeDS'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeEID'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeGID'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeGPOS'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeHINFO'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeIPSECKEY'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeISDN'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeIXFR'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeKEY'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeKX'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeLOC'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeMAILA'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeMAILB'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeMB'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeMD'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeMF'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeMG'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeMINFO'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeMR'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeMX'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeNAPTR'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeNIMLOC'      => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeNS'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeNSAP'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeNSAP_PTR'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeNSEC'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeNSEC3'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeNSEC3PARAMS' => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeNULL'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeNXT'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeOPT'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypePTR'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypePX'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeRP'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeRRSIG'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeRT'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeSIG'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeSINK'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeSOA'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeSRV'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeSSHFP'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeTSIG'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeTXT'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeUID'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeUINFO'       => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeUNSPEC'      => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeWKS'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'qTypeX25'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'classANY'         => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'classCH'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'classHS'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'classIN'          => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'classNONE'        => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'rcodeFORMERR'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'rcodeNOERROR'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'rcodeNOTAUTH'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'rcodeNOTIMPL'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'rcodeNOTZONE'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'rcodeNXDOMAIN'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'rcodeNXRRSET'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'rcodeREFUSED'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'rcodeSERVFAIL'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'rcodeYXDOMAIN'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'rcodeYXRRSET'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'rcodenodata'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'flagQR'           => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'flagAA'           => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'flagTC'           => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'flagRD'           => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'flagRA'           => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'flagZ'            => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'flagAD'           => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'flagCD'           => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'opcodeQUERY'      => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'opcodeIQUERY'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'opcodeSTATUS'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'opcodeNOTIFY'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'opcodeUPDATE'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'numQueryTCP'      => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'numQueryIPv6'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'numQueryUnwanted' => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'numReplyUnwanted' => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'numAnswerSecure'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'numAnswerBogus'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'numRRSetBogus'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'ednsPresent'      => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'ednsDO'           => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
  ),
);

// Memcached

$config['rrd_types']['memcached'] = array(
  'file'  => 'app-memcached-%index%.rrd',
  'ds'    => array(
    'uptime'            => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'threads'           => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'rusage_user'       => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'rusage_system'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'curr_items'        => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'total_items'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'limit_maxbytes'    => array('type' => 'GAUGE',  'min' => 0),
    'curr_connections'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'total_connections' => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'conn_structures'   => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'bytes'             => array('type' => 'GAUGE',  'min' => 0),
    'cmd_get'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'cmd_set'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'cmd_flush'         => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'get_hits'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'get_misses'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'evictions'         => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'bytes_read'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'bytes_written'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
  ),
);

// Kamailio

$config['rrd_types']['kamailio'] = array(
  'file'  => 'app-kamailio-%index%.rrd',
  'ds'    => array(
    'corebadURIsrcvd'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'corebadmsghdr'       => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'coredropreplies'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'coredroprequests'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'coreerrreplies'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'coreerrrequests'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'corefwdreplies'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'corefwdrequests'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'corercvreplies'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'corercvrequests'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'coreunsupportedmeth' => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'dnsfaileddnsrequest' => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'mysqldrivererrors'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'registraraccregs'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'registrardefexpire'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'registrardefexpirer' => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'registrarmaxcontact' => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'registrarmaxexpires' => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'registrarrejregs'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'shmemfragments'      => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'shmemfreesize'       => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'shmemmaxusedsize'    => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'shmemrealusedsize'   => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'shmemtotalsize'      => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'shmemusedsize'       => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'siptracetracedrepl'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'siptracetracedreq'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl1xxreplies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl200replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl202replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl2xxreplies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl300replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl301replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl302replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl3xxreplies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl400replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl401replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl403replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl404replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl407replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl408replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl483replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl4xxreplies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl500replies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl5xxreplies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'sl6xxreplies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'slfailures'          => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'slreceivedACKs'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'slsenterrreplies'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'slsentreplies'       => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'slxxxreplies'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'tcpconreset'         => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tcpcontimeout'       => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tcpconnectfailed'    => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tcpconnectsuccess'   => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tcpcurrentopenedcon' => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tcpcurrentwrqsize'   => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tcpestablished'      => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tcplocalreject'      => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tcppassiveopen'      => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tcpsendtimeout'      => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tcpsendqfull'        => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tmx2xxtransactions'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'tmx3xxtransactions'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'tmx4xxtransactions'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'tmx5xxtransactions'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'tmx6xxtransactions'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'tmxUACtransactions'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'tmxUAStransactions'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'tmxinusetransaction' => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'tmxlocalreplies'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'usrlocloccontacts'   => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'usrloclocexpires'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'usrloclocusers'      => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'usrlocregusers'      => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
  ),
);

// MySQL

$config['rrd_types']['mysql'] = array(
  'file'  => 'app-mysql-%index%.rrd',
  'ds'    => array(
    'IDBLBSe' => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'IBLFh'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBLWn'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'SRows'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'SRange'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'SMPs'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'SScan'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBIRd'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBIWr'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBILg'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBIFSc'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IDBRDd'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IDBRId'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IDBRRd'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IDBRUd'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBRd'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBCd'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBWr'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'TLIe'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'TLWd'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBPse'   => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'IBPDBp'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'IBPFe'   => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'IBPMps'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'TOC'     => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'OFs'     => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'OTs'     => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'OdTs'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'IBSRs'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBSWs'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBOWs'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'QCs'     => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'QCeFy'   => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'MaCs'    => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'MUCs'    => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'ACs'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'AdCs'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'TCd'     => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'Cs'      => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBTNx'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'KRRs'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'KRs'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'KWR'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'KWs'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'QCQICe'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'QCHs'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'QCIs'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'QCNCd'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'QCLMPs'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CTMPDTs' => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CTMPTs'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CTMPFs'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBIIs'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBIMRd'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBIMs'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBILog'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBISc'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBIFLg'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBFBl'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBIIAo'  => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBIAd'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'IBIAe'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'SFJn'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'SFRJn'   => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'SRe'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'SRCk'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'SSn'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'SQs'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'BRd'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'BSt'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CDe'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CIt'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CISt'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CLd'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CRe'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CRSt'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CSt'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CUe'     => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
    'CUMi'    => array('type' => 'DERIVE',  'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['mysql-status'] = array(
  'file'  => 'app-mysql-%index%-status.rrd',
  'ds'    => array(
    'd2' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'd3' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'd4' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'd5' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'd6' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'd7' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'd8' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'd9' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'da' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'db' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'dc' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'dd' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'de' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'df' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'dg' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'dh' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

// PostgreSQL

$config['rrd_types']['postgresql'] = array(
  'file'  => 'app-postgresql-%index%.rrd',
  'ds'    => array(
    'cCount'        => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'tDbs'          => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'tUsr'          => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'tHst'          => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'idle'          => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'select'        => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'update'        => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'delete'        => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'other'         => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'xact_commit'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'xact_rollback' => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000),
    'blks_read'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 1000000000000000),
    'blks_hit'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 1000000000000000),
    'tup_returned'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000000),
    'tup_fetched'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000000),
    'tup_inserted'  => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000000),
    'tup_updated'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000000),
    'tup_deleted'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 100000000000000),
  ),
);

// PowerDNS auth

$config['rrd_types']['powerdns'] = array(
  'file'  => 'app-powerdns-%index%.rrd',
  'ds'    => array(
    'corruptPackets'   => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'def_cacheInserts' => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'def_cacheLookup'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'latency'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'pc_hit'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'pc_miss'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'pc_size'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'qsize'            => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'qc_hit'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'qc_miss'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'rec_answers'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'rec_questions'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'servfailPackets'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'q_tcpAnswers'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'q_tcpQueries'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'q_timedout'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'q_udpAnswers'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'q_udpQueries'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'q_udp4Answers'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'q_udp4Queries'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'q_udp6Answers'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'q_udp6Queries'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
  ),
);

// PowerDNS recursor

$config['rrd_types']['powerdns-recursor'] = array(
  'file'  => 'app-powerdns-recursor-%index%.rrd',
  'ds'    => array(
    'outQ_all'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'outQ_dont'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'outQ_tcp'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'outQ_throttled'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'outQ_ipv6'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'outQ_noEDNS'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'outQ_noPing'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'drop_reqDlgOnly'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'drop_overCap'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'timeoutOutgoing'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'unreachables'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'answers_1s'         => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'answers_1ms'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'answers_10ms'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'answers_100ms'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'answers_1000ms'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'answers_noerror'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'answers_nxdomain'   => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'answers_servfail'   => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'caseMismatch'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'chainResends'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'clientParseErrors'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'ednsPingMatch'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'ednsPingMismatch'   => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'noPacketError'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'nssetInvalidations' => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'qaLatency'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'questions'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'resourceLimits'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'serverParseErrors'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'spoofPrevents'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'tcpClientOverflow'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'tcpQuestions'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'tcpUnauthorized'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'udpUnauthorized'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'cacheEntries'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'cacheHits'          => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'cacheMisses'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'negcacheEntries'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'nsSpeedsEntries'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'packetCacheEntries' => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'packetCacheHits'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'packetCacheMisses'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'unexpectedPkts'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'concurrentQueries'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'tcpClients'         => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'throttleEntries'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'uptime'             => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'cpuTimeSys'         => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'cpuTimeUser'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
  ),
);

// OpenVPN

$config['rrd_types']['openvpn'] = array(
  'file'  => 'app-openvpn-%index%.rrd',
  'ds'    => array(
    'nclients' => array('type' => 'GAUGE',  'min' => 0),
    'bytesin'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'bytesout' => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
  ),
);

// Apache

$config['rrd_types']['apache'] = array(
  'file'  => 'app-apache-%index%.rrd',
  'ds'    => array(
    'access'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'kbyte'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'cpu'          => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'uptime'       => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'reqpersec'    => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'bytespersec'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'byesperreq'   => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'busyworkers'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'idleworkers'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'sb_wait'      => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'sb_start'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'sb_reading'   => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'sb_writing'   => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'sb_keepalive' => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'sb_dns'       => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'sb_closing'   => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'sb_logging'   => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'sb_graceful'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'sb_idle'      => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'sb_open'      => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
  ),
);

// Exim

$config['rrd_types']['exim-mailqueue'] = array(
  'file'  => 'app-exim-mailqueue-%index%.rrd',
  'ds'    => array(
    'frozen'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'bounces' => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'total'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'active'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
  ),
);

// Postfix

$config['rrd_types']['postfix-mailgraph'] = array(
  'file'  => 'app-postfix-mailgraph.rrd',
  'ds'    => array(
    'sent'       => array('type' => 'COUNTER', 'min' => 0, 'max' => 1000000),
    'received'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 1000000),
    'bounced'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 1000000),
    'rejected'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 1000000),
    'virus'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 1000000),
    'spam'       => array('type' => 'COUNTER', 'min' => 0, 'max' => 1000000),
    'greylisted' => array('type' => 'COUNTER', 'min' => 0, 'max' => 1000000),
    'delayed'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 1000000),
  ),
);

$config['rrd_types']['postfix-qshape'] = array(
  'file'  => 'app-postfix-qshape.rrd',
  'ds'    => array(
    'incoming' => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'active'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'deferred' => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'hold'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
  ),
);

// NTP

$config['rrd_types']['ntpd-server'] = array(
  'file'  => 'app-ntpd-server-%index%.rrd',
  'ds'    => array(
    'stratum'        => array('type' => 'GAUGE',  'min' => -1000, 'max' => 1000),
    'offset'         => array('type' => 'GAUGE',  'min' => -1000, 'max' => 1000),
    'frequency'      => array('type' => 'GAUGE',  'min' => -1000, 'max' => 1000),
    'jitter'         => array('type' => 'GAUGE',  'min' => -1000, 'max' => 1000),
    'noise'          => array('type' => 'GAUGE',  'min' => -1000, 'max' => 1000),
    'stability'      => array('type' => 'GAUGE',  'min' => -1000, 'max' => 1000),
    'uptime'         => array('type' => 'GAUGE',  'min' => 0,     'max' => 125000000000),
    'buffer_recv'    => array('type' => 'GAUGE',  'min' => 0,     'max' => 100000),
    'buffer_free'    => array('type' => 'GAUGE',  'min' => 0,     'max' => 100000),
    'buffer_used'    => array('type' => 'GAUGE',  'min' => 0,     'max' => 100000),
    'packets_drop'   => array('type' => 'DERIVE', 'min' => 0,     'max' => 125000000000),
    'packets_ignore' => array('type' => 'DERIVE', 'min' => 0,     'max' => 125000000000),
    'packets_recv'   => array('type' => 'DERIVE', 'min' => 0,     'max' => 125000000000),
    'packets_sent'   => array('type' => 'DERIVE', 'min' => 0,     'max' => 125000000000),
  ),
);

$config['rrd_types']['ntpd-client'] = array(
  'file'  => 'app-ntpd-client-%index%.rrd',
  'ds'    => array(
    'offset'    => array('type' => 'GAUGE', 'min' => -1000, 'max' => 1000),
    'frequency' => array('type' => 'GAUGE', 'min' => -1000, 'max' => 1000),
    'jitter'    => array('type' => 'GAUGE', 'min' => -1000, 'max' => 1000),
    'noise'     => array('type' => 'GAUGE', 'min' => -1000, 'max' => 1000),
    'stability' => array('type' => 'GAUGE', 'min' => -1000, 'max' => 1000),
  ),
);

// NFS

$config['rrd_types']['nfsd'] = array(
  'file'  => 'app-nfsd-%index%.rrd',
  'ds'    => array(
    'rcretrans'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'rcmiss'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'rcnocache'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'ior_bytes'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'iow_bytes'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'netn_count'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'netu_count'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'nett_data'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'nett_conn'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'rpccalls'         => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'rpcbadcalls'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'rpcbadclnt'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'rpcxdrcall'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3null'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3getattr'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3setattr'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3lookup'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3access'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3readlink'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3read'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3write'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3create'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3mkdir'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3symlink'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3mknod'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3remove'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3rmdir'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3rename'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3link'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3readdr'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3readdirplus' => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3fsstat'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3fsinfo'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3pathconf'    => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
    'proc3commit'      => array('type' => 'DERIVE', 'min' => 0, 'max' => 12500000),
  ),
);

// Lighttpd

$config['rrd_types']['lighttpd'] = array(
  'file'  => 'app-lighttpd-%index%.rrd',
  'ds'    => array(
    'totalaccesses' => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'totalkbytes'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'uptime'        => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'busyservers'   => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'idleservers'   => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionsp'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionsC'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionsE'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionsk'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionsr'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionsR'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionsW'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionsh'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionsq'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionsQ'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionss'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
    'connectionsS'  => array('type' => 'GAUGE',   'min' => 0, 'max' => 125000000000),
  ),
);

// DRBD

$config['rrd_types']['drbd'] = array(
  'file'  => 'app-drbd-%index%.rrd',
  'ds'    => array(
    'ns'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'nr'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'dw'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'dr'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'al'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'bm'  => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'lo'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'pe'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'ua'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'ap'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'oos' => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
  ),
);

// DRBD

$config['rrd_types']['ioping'] = array(
  'file'  => 'app-ioping-%index%.rrd',
  'ds'    => array(
    'reqps'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'tfspeed'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'minreqtime' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'avgreqtime' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'maxreqtime' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'reqstddev'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
 ),
);


// Asterisk

$config['rrd_types']['asterisk'] = array(
  'file'  => 'app-asterisk-%index%.rrd',
  'ds'    => array(
    'activechan'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'activecall'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'iaxchannels'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'sipchannels'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'sippeers'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'sippeersonline' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'iaxpeers'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'iaxpeersonline' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

// Shoutcast

$config['rrd_types']['shoutcast'] = array(
  'file'  => 'app-shoutcast-%index%.rrd',
  'ds'    => array(
    'bitrate'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'traf_in'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'traf_out' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'current'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'status'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'peak'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'max'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'unique'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

// Varnish

$config['rrd_types']['varnish'] = array(
  'file'  => 'app-varnish-%index%.rrd',
  'ds'    => array(
    'backend_req'       => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'backend_unhealthy' => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'backend_busy'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'backend_fail'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'backend_reuse'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'backend_toolate'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'backend_recycle'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'backend_retry'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'cache_hitpass'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'cache_hit'         => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'cache_miss'        => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'lru_nuked'         => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'lru_moved'         => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
  ),
);

// Nginx

$config['rrd_types']['nginx'] = array(
  'file'  => 'app-nginx-%index%.rrd',
  'ds'    => array(
    'Requests' => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'Active'   => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'Reading'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'Writing'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'Waiting'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
  ),
);

// CrashPlan

$config['rrd_types']['crashplan'] = array(
  'file'  => 'app-crashplan-%index%.rrd',
  'ds'    => array(
    'totalBytes'          => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'usedBytes'           => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'usedPercentage'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 100),
    'freeBytes'           => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'freePercentage'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 100),
    'coldBytes'           => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'coldPctOfUsed'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 100),
    'coldPctOfTotal'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 100),
    'archiveBytes'        => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'selectedBytes'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'remainingBytes'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'inboundBandwidth'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'outboundBandwidth'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'orgCount'            => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'userCount'           => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'computerCount'       => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'onlineComputerCount' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'backupSessionCount'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

// FreeRADIUS

$config['rrd_types']['freeradius'] = array(
  'file'  => 'app-freeradius-%index%.rrd',
  'ds'    => array(
    'AccessAccepts'       => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AccessChallenges'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AccessRejects'       => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AccessReqs'          => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AccountingReqs'      => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AccountingResponses' => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AcctDroppedReqs'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AcctDuplicateReqs'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AcctInvalidReqs'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AcctMalformedReqs'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AcctUnknownTypes'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AuthDroppedReqs'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AuthDuplicateReqs'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AuthInvalidReqs'     => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AuthMalformedReqs'   => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AuthResponses'       => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
    'AuthUnknownTypes'    => array('type' => 'COUNTER', 'min' => 0, 'max' => 125000000000),
  ),
);

// VMWare

$config['rrd_types']['vmwaretools'] = array(
  'file'  => 'app-vmwaretools-%index%.rrd',
  'ds'    => array(
    'vmtotalmem' => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'vmswap'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'vmballoon'  => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'vmmemres'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'vmmemlimit' => array('type' => 'GAUGE', 'min' => 0, 'max' => 1000000),
    'vmspeed'    => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000000000 ),
    'vmcpulimit' => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000000000),
    'vmcpures'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000000000),
  ),
);

// Zimbra

$config['rrd_types']['zimbra-mtaqueue'] = array(
  'file'  => 'app-zimbra-mtaqueue.rrd',
  'ds'    => array(
    'kBytes'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'requests' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['zimbra-fd'] = array(
  'file'  => 'app-zimbra-fd.rrd',
  'ds'    => array(
    'fdSystem'   => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
    'fdMailboxd' => array('type' => 'GAUGE', 'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['zimbra-threads'] = array(
  'file'  => 'app-zimbra-threads.rrd',
  'ds'    => array(
    'AnonymousIoService' => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'CloudRoutingReader' => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'GC'                 => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'ImapSSLServer'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'ImapServer'         => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'LmtpServer'         => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'Pop3SSLServer'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'Pop3Server'         => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'ScheduledTask'      => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'SocketAcceptor'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'Thread'             => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'Timer'              => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'btpool'             => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'pool'               => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'other'              => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
    'total'              => array('type' => 'GAUGE', 'min' => 0, 'max' => 10000),
  ),
);

$config['rrd_types']['zimbra-mailboxd'] = array(
  'file'  => 'app-zimbra-mailboxd.rrd',
  'ds'    => array(
    'lmtpRcvdMsgs'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'lmtpRcvdBytes'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'lmtpRcvdRcpt'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'lmtpDlvdMsgs'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'lmtpDlvdBytes'       => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'dbConnCount'         => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'dbConnMsAvg'         => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'ldapDcCount'         => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'ldapDcMsAvg'         => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mboxAddMsgCount'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'mboxAddMsgMsAvg'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mboxGetCount'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'mboxGetMsAvg'        => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mboxCache'           => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mboxMsgCache'        => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mboxItemCache'       => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'soapCount'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'soapMsAvg'           => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'imapCount'           => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'imapMsAvg'           => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'popCount'            => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'popMsAvg'            => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'idxWrtAvg'           => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'idxWrtOpened'        => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'idxWrtOpenedCacheHt' => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'calcacheHit'         => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'calcacheMemHit'      => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'calcacheLruSize'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'idxBytesWritten'     => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'idxBytesWrittenAvg'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'idxBytesRead'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'idxBytesReadAvg'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'bisRead'             => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'bisSeekRate'         => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'dbPoolSize'          => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'innodbBpHitRate'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'lmtpConn'            => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'lmtpThreads'         => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'popConn'             => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'popThreads'          => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'popSslConn'          => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'popSslThreads'       => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'imapConn'            => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'imapThreads'         => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'imapSslConn'         => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'imapSslThreads'      => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'httpIdleThreads'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'httpThreads'         => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'soapSessions'        => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mboxCacheSize'       => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'msgCacheSize'        => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'fdCacheSize'         => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'fdCacheHitRate'      => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'aclCacheHitRate'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'accountCacheSize'    => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'accountCacheHitRate' => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'cosCacheSize'        => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'cosCacheHitRate'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'domainCacheSize'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'domainCacheHitRate'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'serverCacheSize'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'serverCacheHitRate'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'ucsvcCacheSize'      => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'ucsvcCacheHitRate'   => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'zimletCacheSize'     => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'zimletCacheHitRate'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'groupCacheSize'      => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'groupCacheHitRate'   => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'xmppCacheSize'       => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'xmppCacheHitRate'    => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'gcParnewCount'       => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'gcParnewMs'          => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'gcConcmarksweepCnt'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'gcConcmarksweepMs'   => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'gcMinorCount'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'gcMinorMs'           => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'gcMajorCount'        => array('type' => 'DERIVE', 'min' => 0, 'max' => 125000000000),
    'gcMajorMs'           => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mpoolCodeCacheUsed'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mpoolCodeCacheFree'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mpoolParEdenSpcUsed' => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mpoolParEdenSpcFree' => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mpoolParSurvSpcUsed' => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mpoolParSurvSpcFree' => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mpoolCmsOldGenUsed'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mpoolCmsOldGenFree'  => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mpoolCmsPermGenUsed' => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'mpoolCmsPermGenFree' => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'heapUsed'            => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
    'heapFree'            => array('type' => 'GAUGE',  'min' => 0, 'max' => 125000000000),
  ),
);

$config['rrd_types']['zimbra-proc'] = array(
  'file'  => 'app-zimbra-proc-%index%.rrd',
  'ds'    => array(
    'totalCPU'     => array('type' => 'GAUGE', 'min' => 0, 'max' => 100),
    'utime'        => array('type' => 'GAUGE', 'min' => 0),
    'stime'        => array('type' => 'GAUGE', 'min' => 0),
    'totalMB'      => array('type' => 'GAUGE', 'min' => 0),
    'rssMB'        => array('type' => 'GAUGE', 'min' => 0),
    'sharedMB'     => array('type' => 'GAUGE', 'min' => 0),
    'processCount' => array('type' => 'GAUGE', 'min' => 0),
  ),
);

// EOF
