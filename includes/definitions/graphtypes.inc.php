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

// Graph sections is used to categorize /device/graphs/

$config['graph_sections'] = array(
  'general', 'system', 'firewall', 'bng', 'netstats', 'wireless', 'storage', 'vpdn', 'appliance',
  'poller', 'netapp', 'netscaler_tcp', 'netscaler_ssl', 'netscaler_http', 'netscaler_comp',
  'loadbalancer', 'cgn', 'l2tp', 'storage', 'proxysg', 'license', 'authentication', 'ospf',
  'f5_ssl', 'dhcp', 'pcoip'
);

// Graph types
$config['graph_types']['port']['bits']       = array('name' => 'Bits',              'descr' => "Traffic in bits/sec");
$config['graph_types']['port']['upkts']      = array('name' => 'Ucast Pkts',        'descr' => "Unicast packets/sec");
$config['graph_types']['port']['nupkts']     = array('name' => 'NU Pkts',           'descr' => "Non-unicast packets/sec");
$config['graph_types']['port']['pktsize']    = array('name' => 'Pkt Size',          'descr' => "Average packet size");
$config['graph_types']['port']['percent']    = array('name' => 'Percent',           'descr' => "Percent utilization");
$config['graph_types']['port']['errors']     = array('name' => 'Errors',            'descr' => "Errors/sec");
$config['graph_types']['port']['discards']   = array('name' => 'Discards',          'descr' => "Discards/sec");
$config['graph_types']['port']['etherlike']  = array('name' => 'Ethernet Errors',   'descr' => "Detailed Errors/sec for Ethernet-like interfaces");
$config['graph_types']['port']['fdb_count']  = array('name' => 'FDB counts',        'descr' => "FDB usage");

$config['graph_types']['oid_entry']['graph'] = array('name' => 'OID Graph',         'descr' => 'Custom OID Graph');

$config['graph_types']['p2pradio']['capacity'] = array('name' => 'Link Capacity',          'descr' => "Current capacity of the radio link in Mbps");
$config['graph_types']['p2pradio']['power']    = array('name' => 'Transmit Power',         'descr' => "Current transmit power of the radio link in dBm");
$config['graph_types']['p2pradio']['rxlevel']    = array('name' => 'Receive Power',          'descr' => "Current received power level in dBm");
$config['graph_types']['p2pradio']['rmse']     = array('name' => 'Radial MSE',             'descr' => "Current radial MSE of the link");
$config['graph_types']['p2pradio']['gain']     = array('name' => 'Automatic Gain Control', 'descr' => "Current AGM(automatic gain control) gain on the link");
$config['graph_types']['p2pradio']['symbol_rates']     = array('name' => 'Symbol Rates',             'descr' => "Current symbol rates of the link");

$config['graph_types']['storage']['usage']   = array('name' => 'Usage',             'descr' => "Storage Usage");
$config['graph_types']['mempool']['usage']   = array('name' => 'Usage',             'descr' => "Memory Usage");
$config['graph_types']['processor']['usage'] = array('name' => 'Usage',             'descr' => "Processor Usage");
$config['graph_types']['status']['graph']    = array('name' => 'Historical Status', 'descr' => "Historical Status");
$config['graph_types']['sensor']['graph']    = array('name' => 'Historical Values', 'descr' => "Historical Values");

$config['graph_types']['device']['wifi_clients']['section'] = 'wireless';
$config['graph_types']['device']['wifi_clients']['order'] = '0';
$config['graph_types']['device']['wifi_clients']['descr'] = 'Wireless Clients';

// NetApp graphs
$config['graph_types']['device']['netapp_ops']     = array('section' => 'netapp', 'descr' => 'NetApp Operations', 'order' => '0');
$config['graph_types']['device']['netapp_net_io']  = array('section' => 'netapp', 'descr' => 'NetApp Network I/O', 'order' => '1');
$config['graph_types']['device']['netapp_disk_io'] = array('section' => 'netapp', 'descr' => 'NetApp Disk I/O', 'order' => '2');
$config['graph_types']['device']['netapp_tape_io'] = array('section' => 'netapp', 'descr' => 'NetApp Tape I/O', 'order' => '3');
$config['graph_types']['device']['netapp_cp_ops']  = array('section' => 'netapp', 'descr' => 'NetApp Checkpoint Operations', 'order' => '4');

$config['graph_types']['device']['NETAPP-MIB_net_io']  = array('section' => 'netapp', 'descr' => 'NetApp Network I/O', 'order' => '1');
$config['graph_types']['device']['NETAPP-MIB_disk_io'] = array('section' => 'netapp', 'descr' => 'NetApp Disk I/O', 'order' => '2');
$config['graph_types']['device']['NETAPP-MIB_tape_io'] = array('section' => 'netapp', 'descr' => 'NetApp Tape I/O', 'order' => '3');

$config['graph_types']['device']['NETAPP-MIB_cache_age'] = array(
  'file'      => 'netapp-mib_misc.rrd',
  'descr'     => 'Netapp Cache Age',
  'section'   => 'storage',
  'unit_text' => 'Seconds',
  'colours'   => 'mixed-5',
  'ds'        => array(
    'CacheAge' => array('label' => 'Cache Age'),
  )
);

$config['graph_types']['device']['NETAPP-MIB_cp_ops'] = array(
  'file'      => 'netapp-mib_cp.rrd',
  'descr'     => 'NetApp Checkpoint Operations',
  'section'   => 'storage',
  'unit_text' => 'Operations/s',
  'colours'   => 'mixed-q12',
  'ds'        => array(
    'cpFromTimerOps'      => array('label' => 'Timer'),
    'cpFromSnapshotOps'   => array('label' => 'Snapshot'),
    'cpFromLowWaterOps'   => array('label' => 'Low Water'),
    'cpFromHighWaterOps'  => array('label' => 'High Water'),
    'cpFromLogFullOps'    => array('label' => 'NV Log Full'),
    'cpFromCpOps'         => array('label' => 'Back to Back CPs'),
    'cpFromFlushOps'      => array('label' => 'Write Flush'),
    'cpFromSyncOps'       => array('label' => 'Sync'),
    'cpFromLowVbufOps'    => array('label' => 'Low Virtual Buffers'),
    'cpFromCpDeferredOps' => array('label' => 'Deferred CPs'),
    'cpFromLowDatavecsOp' => array('label' => 'Low Datavecs'),
  )
);

$config['graph_types']['device']['NETAPP-MIB_misc_ops'] = array(
  'file'      => 'netapp-mib_misc.rrd',
  'ds'        => array(
    'NfsOps'  => array('label' => 'CHANGE_ME'),
    'CifsOps' => array('label' => 'CHANGE_ME'),
    'HttpOps' => array('label' => 'CHANGE_ME'),

  )
);

// Poller graphs
$config['graph_types']['poller']['wrapper_threads'] = array(
  'section'   => 'poller',
  'descr'     => 'Poller Devices/Threads',
  'file'      => 'poller-wrapper.rrd',
  'rra_max'   => FALSE,
  'scale_min' => '-1',
  'num_fmt'   => '5.0',
  'no_mag'    => TRUE,
  'unit_text' => 'Count',
  'ds'        => array(
    'devices'   => array('label' => 'Devices', 'draw' => 'AREA', 'line' => TRUE, 'colour' => '3ca3c1', 'rra_min' => 0),
    'threads'   => array('label' => 'Threads', 'draw' => 'AREA', 'line' => TRUE, 'colour' => 'f9a022', 'rra_min' => 0),
    'wrapper_count' => array('label' => 'Wrapper Processes', 'draw' => 'AREA', 'line' => TRUE, 'colour' => 'c5c5c5', 'rra_min' => 0, 'ds_max' => 4, 'file' => 'poller-wrapper_count.rrd'),
    //'totaltime' => array('label' => 'Total time', 'line' => TRUE, 'colour' => 'c5c5c5', 'rra_min' => 0),
  )
);
//$config['graph_types']['poller']['wrapper_count'] = array(
//  'section'   => 'poller',
//  'descr'     => 'Poller Count',
//  'file'      => 'poller-wrapper_count.rrd',
//  'rra_max'   => FALSE,
//  'scale_min' => '-1',
//  'num_fmt'   => '5.0',
//  'no_mag'    => TRUE,
//  'unit_text' => 'Count',
//  'ds'        => array(
//    'wrapper_count'   => array('label' => 'Wrapper', 'draw' => 'AREA', 'line' => TRUE, 'colour' => '3ca3c1', 'rra_min' => 0,  'ds_max' => 4),
//    //'threads'   => array('label' => 'Threads', 'draw' => 'AREA', 'line' => TRUE, 'colour' => 'f9a022', 'rra_min' => 0),
//    //'totaltime' => array('label' => 'Total time', 'line' => TRUE, 'colour' => 'c5c5c5', 'rra_min' => 0),
//  )
//);
$config['graph_types']['poller']['wrapper_times'] = array(
  'section'   => 'poller',
  'descr'     => 'Poller Total time',
  'file'      => 'poller-wrapper.rrd',
  'rra_max'   => FALSE,
  'scale_min' => '0',
  'num_fmt'   => '6.1',
  'unit_text' => 'Seconds',
  'ds'        => array(
    'totaltime' => array('label' => 'Total time', 'draw' => 'AREA', 'line' => TRUE, 'colour' => 'c5c5c5', 'rra_min' => 0),
  )
);

// OSPF module graphs

$config['graph_types']['device']['ospf_neighbours']    = array(
  'section'   => 'ospf',
  'descr'     => 'OSPF Neighbour Count',
  'file'      => 'ospf-statistics.rrd',
  'scale_min' => '0',
  'colours'   => 'mixed-10',
  'unit_text' => '',
  'ds'        => array (
    'neighbours'   => array ('label' => 'Neighbours', 'draw' => 'AREA', 'line' => TRUE)
  )
);

$config['graph_types']['device']['ospf_ports']    = array(
  'section'   => 'ospf',
  'descr'     => 'OSPF Port Count',
  'file'      => 'ospf-statistics.rrd',
  'scale_min' => '0',
  'colours'   => 'mixed-10',
  'unit_text' => '',
  'colour_offset' => 2,
  'ds'        => array (
    'ports'   => array ('label' => 'Ports', 'draw' => 'AREA', 'line' => TRUE)
  )
);

$config['graph_types']['device']['ospf_areas']    = array(
  'section'   => 'ospf',
  'descr'     => 'OSPF Area Count',
  'file'      => 'ospf-statistics.rrd',
  'scale_min' => '0',
  'colours'   => 'mixed-10',
  'unit_text' => '',
  'colour_offset' => 6,
  'ds'        => array (
    'areas'   => array ('label' => 'Areas', 'draw' => 'AREA', 'line' => TRUE)
  )
);

// Device graphs
$config['graph_types']['device']['poller_perf']    = array(
  'section'   => 'poller',
  'descr'     => 'Poller Duration',
  'file'      => 'perf-poller.rrd',
  'scale_min' => '0',
  'colours'   => 'greens',
  'unit_text' => ' ',
  'ds'        => array (
    'val'   => array ('label' => 'Seconds', 'draw' => 'AREA', 'line' => TRUE)
  )
);

$config['graph_types']['device']['pollermodule_perf']    = array(
  'section'   => 'poller',
  'descr'     => 'Poller Duration',
  'file'      => 'perf-pollermodule-index.rrd',
  'index'     => 'module',
  'scale_min' => '0',
  'colours'   => 'greens',
  'unit_text' => ' ',
  'ds'        => array (
    'val'   => array ('label' => 'Seconds', 'draw' => 'AREA', 'line' => TRUE)
  )
);

$config['graph_types']['device']['ping'] = array(
  'section'   => 'poller',
  'descr'     => 'Ping Response',
  'file'      => 'ping.rrd',
  'colours'   => 'reds',
  'unit_text' => 'Milliseconds',
  'ds'        => array(
    'ping' => array('label' => 'Ping', 'draw' => 'AREA', 'line' => TRUE)
  )
);

$config['graph_types']['device']['ping_snmp'] = array(
  'section'   => 'poller',
  'descr'     => 'SNMP Response',
  'file'      => 'ping_snmp.rrd',
  'colours'   => 'blues',
  'unit_text' => 'Milliseconds',
  'ds'        => array(
    'ping_snmp' => array('label' => 'SNMP', 'draw' => 'AREA', 'line' => TRUE)
  )
);

$config['graph_types']['device']['agent'] = array(
  'section'   => 'poller',
  'descr'     => 'Agent Execution Time',
  'file'      => 'agent.rrd',
  'colours'   => 'oranges',
  'unit_text' => 'Milliseconds',
  'ds'        => array(
    'time' => array('label' => '', 'draw' => 'AREA', 'line' => TRUE, 'ds_min' => '0')
  )
);


$config['graph_types']['device']['netstat_arista_sw_ip'] = array(
  'section' => 'netstats',
  'order' => '0',
  'descr' => "Software forwarded IPv4 Statistics"
);
$config['graph_types']['device']['netstat_arista_sw_ip_frag'] = array(
  'section' => 'netstats',
  'order' => '0',
  'descr' => "Software forwarded IPv4 Fragmentation Statistics"
);
$config['graph_types']['device']['netstat_arista_sw_ip6'] = array(
  'section' => 'netstats',
  'order' => '0',
  'descr' => "Software forwarded IPv6 Statistics"
);
$config['graph_types']['device']['netstat_arista_sw_ip6_frag'] = array(
  'section' => 'netstats',
  'order' => '0',
  'descr' => "Software forwarded IPv6 Fragmentation Statistics"
);

$config['graph_types']['device']['cipsec_flow_bits']['section'] = 'firewall';
$config['graph_types']['device']['cipsec_flow_bits']['order'] = '0';
$config['graph_types']['device']['cipsec_flow_bits']['descr'] = 'IPSec Tunnel Traffic Volume';
$config['graph_types']['device']['cipsec_flow_pkts']['section'] = 'firewall';
$config['graph_types']['device']['cipsec_flow_pkts']['order'] = '0';
$config['graph_types']['device']['cipsec_flow_pkts']['descr'] = 'IPSec Tunnel Traffic Packets';
$config['graph_types']['device']['cipsec_flow_stats']['section'] = 'firewall';
$config['graph_types']['device']['cipsec_flow_stats']['order'] = '0';
$config['graph_types']['device']['cipsec_flow_stats']['descr'] = 'IPSec Tunnel Statistics';
$config['graph_types']['device']['cipsec_flow_tunnels']['section'] = 'firewall';
$config['graph_types']['device']['cipsec_flow_tunnels']['order'] = '0';
$config['graph_types']['device']['cipsec_flow_tunnels']['descr'] = 'IPSec Active Tunnels';
$config['graph_types']['device']['cras_sessions']['section'] = 'firewall';
$config['graph_types']['device']['cras_sessions']['order'] = '0';
$config['graph_types']['device']['cras_sessions']['descr'] = 'Remote Access Sessions';
$config['graph_types']['device']['fortigate_sessions']['section'] = 'firewall';
$config['graph_types']['device']['fortigate_sessions']['order'] = '0';
$config['graph_types']['device']['fortigate_sessions']['descr'] = 'Active Sessions';
$config['graph_types']['device']['fortigate_cpu']['section'] = 'system';
$config['graph_types']['device']['fortigate_cpu']['order'] = '0';
$config['graph_types']['device']['fortigate_cpu']['descr'] = 'CPU';
$config['graph_types']['device']['screenos_sessions']['section'] = 'firewall';
$config['graph_types']['device']['screenos_sessions']['order'] = '0';
$config['graph_types']['device']['screenos_sessions']['descr'] = 'Active Sessions';
$config['graph_types']['device']['panos_sessions']['section'] = 'firewall';
$config['graph_types']['device']['panos_sessions']['order'] = '0';
$config['graph_types']['device']['panos_sessions']['descr'] = 'Active Sessions';
$config['graph_types']['device']['panos_gptunnels']['section'] = 'firewall';
$config['graph_types']['device']['panos_gptunnels']['order'] = '0';
$config['graph_types']['device']['panos_gptunnels']['descr'] = 'Active GlobalProtect Tunnels';

// Cisco Graphs

$config['graph_types']['device']['casnActive-sessions'] = array(
  'section'   => 'authentication',
  'descr'     => 'Active AAA Sessions',
  'unit_text' => 'Sessions',
  'file'      => 'cisco-aaa-session-mib_casnactive.rrd',
  'colour_offset'   => '1',
  'ds'        => array(
    'ActiveTableEntries' => array('label' => 'Active Sessions'),
  )
);

$config['graph_types']['device']['casnGeneral-total'] = array(
  'section'   => 'authentication',
  'descr'     => 'Total AAA Sesssions',
  'file'      => 'cisco-aaa-session-mib_casngeneral.rrd',
  'unit_text' => 'Sessions/sec',
  'colour_offset'   => '2',
  'ds'        => array(
    'TotalSessions' => array('label' => 'New Sessions'),
  )
);

$config['graph_types']['device']['casnGeneral-disconnected'] = array(
  'section'   => 'authentication',
  'descr'     => 'Disconnected AAA Sesssions',
  'unit_text' => 'Sessions/sec',
  'file'      => 'cisco-aaa-session-mib_casngeneral.rrd',
  'colour_offset'   => '3',
  'ds'        => array(
    'DisconnectedSession' => array('label' => 'Disconnected Sessions'),
  )
);

// CHECKPOINT-MIB

$config['graph_types']['device']['checkpoint_connections'] = array(
  'section'   => 'firewall',
  'descr'     => 'Concurrent Connections',
  'file'      => 'checkpoint-mib_fw.rrd',
  'scale_min' => '0',
  'colours'   => 'mixed',
  'unit_text' => 'Concurrent Connections',
  'ds'        => array(
    'NumConn'     => array('label' => 'Current', 'draw' => 'LINE'),
    'PeakNumConn' => array('label' => 'Peak',    'draw' => 'LINE'),
  )
);

$config['graph_types']['device']['checkpoint_packets']    = array(
  'section'   => 'firewall',
  'descr'     => 'Packets',
  'file'      => 'checkpoint-mib_fw.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Packets',
  'ds'        => array(
    'Accepted'   => array('label' => 'Accepted', 'draw' => 'LINE'),
    'Rejected'   => array('label' => 'Rejected', 'draw' => 'LINE'),
    'Dropped'    => array('label' => 'Dropped',  'draw' => 'LINE'),
    'Logged'     => array('label' => 'Logged',   'draw' => 'LINE')
  )
);

// Not enabled
$config['graph_types']['device']['checkpoint_packets_rate'] = array(
  'section'   => 'firewall',
  'descr'     => 'Packets Rate',
  'file'      => 'checkpoint-mib_fw.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Packets/s',
  'ds'        => array(
    'PacketsRate'       => array('label' => 'Packets',        'draw' => 'LINE'),
    'AcceptedBytesRate' => array('label' => 'Accepted Bytes', 'draw' => 'LINE'),
    'DroppedBytesRate'  => array('label' => 'Dropped Bytes',  'draw' => 'LINE'),
    'DroppedRate'       => array('label' => 'Dropped Total',  'draw' => 'LINE'),
  )
);

$config['graph_types']['device']['checkpoint_vpn_sa']    = array(
  'section'   => 'firewall',
  'descr'     => 'VPN IKE/IPSec SAs',
  //'file'      => 'checkpoint-mib_cpvikeglobals.rrd',
  'scale_min' => '0',
  'colours'   => 'mixed',
  'unit_text' => 'IKE/IPSec SAs',
  'ds'        => array(
    'IKECurrSAs'     => array('label' => 'IKE SAs',       'draw' => 'LINE', 'file' => 'checkpoint-mib_cpvikeglobals.rrd'),
    'CurrEspSAsIn'   => array('label' => 'IPSec SAs in',  'draw' => 'LINE', 'file' => 'checkpoint-mib_cpvsastatistics.rrd'),
    'CurrEspSAsOut'  => array('label' => 'IPSec SAs out', 'draw' => 'LINE', 'file' => 'checkpoint-mib_cpvsastatistics.rrd')
  )
);

$config['graph_types']['device']['checkpoint_vpn_packets']    = array(
  'section'   => 'firewall',
  'descr'     => 'VPN Packets',
  'file'      => 'checkpoint-mib_cpvgeneral.rrd',
  'scale_min' => '0',
  'colours'   => 'mixed',
  'unit_text' => 'Packets/s',
  'ds'        => array(
    'EncPackets' => array('label' => 'Encrypted', 'draw' => 'LINE'),
    'DecPackets' => array('label' => 'Decrypted', 'draw' => 'LINE')
  )
);

$config['graph_types']['device']['checkpoint_memory']    = array(
  'section'   => 'firewall',
  'descr'     => 'Kernel / Hash memory',
  'file'      => 'checkpoint-mib_fwkmem.rrd',
  'scale_min' => '0',
  'colours'   => 'mixed',
  'unit_text' => 'Bytes',
  'ds'        => array(
    'Kmem-bytes-used'   => array('label' => 'Kmem used',   'draw' => 'LINE'),
    'Kmem-bytes-unused' => array('label' => 'Kmem unused', 'draw' => 'LINE'),
    'Kmem-bytes-peak'   => array('label' => 'Kmem peak',   'draw' => 'LINE'),
    'Hmem-bytes-used'   => array('label' => 'Hmem used',   'draw' => 'LINE', 'file' => 'checkpoint-mib_fwhmem.rrd'),
    'Hmem-bytes-unused' => array('label' => 'Hmem unused', 'draw' => 'LINE', 'file' => 'checkpoint-mib_fwhmem.rrd'),
    'Hmem-bytes-peak'   => array('label' => 'Hmem peak',   'draw' => 'LINE', 'file' => 'checkpoint-mib_fwhmem.rrd')
  )
);

$config['graph_types']['device']['checkpoint_memory_operations']    = array(
  'section'   => 'firewall',
  'descr'     => 'Kernel / Hash memory operations',
  'file'      => 'checkpoint-mib_fwkmem.rrd',
  'scale_min' => '0',
  'colours'   => 'mixed',
  'unit_text' => 'Operations/s',
  'ds'        => array(
    'Kmem-alc-operations'  => array('label' => 'Kmem alloc',         'draw' => 'LINE'),
    'Kmem-free-operation'  => array('label' => 'Kmem free',          'draw' => 'LINE'),
    'Kmem-failed-alc'      => array('label' => 'Kmem failed alloc',  'draw' => 'LINE'),
    'Kmem-failed-free'     => array('label' => 'Kmem failed free',   'draw' => 'LINE'),
    'Hmem-alc-operations'  => array('label' => 'Hmem alloc',         'draw' => 'LINE', 'file' => 'checkpoint-mib_fwhmem.rrd'),
    'Hmem-free-operation'  => array('label' => 'Hmem free',          'draw' => 'LINE', 'file' => 'checkpoint-mib_fwhmem.rrd'),
    'Hmem-failed-alc'      => array('label' => 'Hmem failed alloc',  'draw' => 'LINE', 'file' => 'checkpoint-mib_fwhmem.rrd'),
    'Hmem-failed-free'     => array('label' => 'Hmem failed free',   'draw' => 'LINE', 'file' => 'checkpoint-mib_fwhmem.rrd')
  )
);

// A10-AX-MIB

// 'axAppGlobalCurConns', 'axAppGlobalTotConns', 'axAppTotL7Requests', 'axAppGlobalBuffers'

$config['graph_types']['device']['axAppGlobalCurConns'] = array(
  'section'   => 'loadbalancer',
  'descr'     => 'Current Connections',
  'file'      => 'a10-ax-mib_axappglobals.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-10',
  'colour_offset' => 2,
  'unit_text' => 'Connections',
  'ds'        => array(
    'TotalCurrentConns' => array('label' => 'Total Connections', 'draw' => 'AREA', 'line' => TRUE),

  )
);

$config['graph_types']['device']['axAppGlobalTotConns'] = array(
  'section'   => 'loadbalancer',
  'descr'     => 'Connection Rates',
  'file'      => 'a10-ax-mib_axappglobals.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-10',
  'unit_text' => 'Conns/sec',
  'ds'        => array(
    'TotalNewConns'      => array('label' => 'All', 'draw' => 'AREA', 'stack' => FALSE),
    'TotalNewL4Conns'    => array('label' => 'Layer 4', 'draw' => 'LINE1'),
    'TotalNewL7Conns'    => array('label' => 'Layer 7'),
    'TotalNewIPNatConns' => array('label' => 'IP NAT'),
    'TotalSSLConns'      => array('label' => 'SSL'),
  )
);

$config['graph_types']['device']['axAppTotL7Requests'] = array(
  'section'   => 'loadbalancer',
  'descr'     => 'Layer 7 Requests',
  'file'      => 'a10-ax-mib_axappglobals.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-10',
  'colour_offset' => 3,
  'unit_text' => 'Requests',
  'ds'        => array(
    'TotalL7Requests' => array('label' => 'L7 Requests', 'draw' => 'AREA', 'line' => TRUE),

  )
);

$config['graph_types']['device']['axAppGlobalBuffers'] = array(
  'section'   => 'loadbalancer',
  'descr'     => 'Buffer Usage',
  'file'      => 'a10-ax-mib_axappglobals.rrd',
  'scale_min' => '0',
  'num_fmt'   => '6.0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-q12',
  'unit_text' => 'Buffers',
  'colour_offset' => 5,
  'ds'        => array(
    'BufferCurrentUsage' => array('label' => 'Used', 'draw' => 'AREA', 'line' => TRUE),
  )
);

//A10-AX-CGN-MIB

$config['graph_types']['device']['axIpNatLsnTotalUserQuotaSessions'] = array(
  'section'   => 'cgn',
  'descr'     => 'Current Connections',
  'file'      => 'a10-ax-cgn-mib_axipnatlsnglobalstats.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-10',
  'colour_offset' => 2,
  'unit_text' => 'Connections',
  'ds'        => array(
    'TotUserQuotaSe' => array('label' => 'Total Connections', 'draw' => 'AREA', 'line' => TRUE),

  )
);

$config['graph_types']['device']['axIpNatLsnTotalIpAddrTranslated'] = array(
  'section'   => 'cgn',
  'descr'     => 'Total Addresses Translated',
  'file'      => 'a10-ax-cgn-mib_axipnatlsnglobalstats.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-10',
  'colour_offset' => 2,
  'unit_text' => 'Connections',
  'ds'        => array(
    'TotIpAddrTranslated' => array('label' => 'Total Addr Translated', 'draw' => 'AREA', 'line' => TRUE),

  )
);

$config['graph_types']['device']['axIpNatLsnTotalFullConeSessions'] = array(
  'section'   => 'cgn',
  'descr'     => 'Total Full Cone Sessions',
  'file'      => 'a10-ax-cgn-mib_axipnatlsnglobalstats.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-10',
  'colour_offset' => 2,
  'unit_text' => 'Connections',
  'ds'        => array(
    'TotFCSe' => array('label' => 'Total Full Cone Sessions', 'draw' => 'AREA', 'line' => TRUE),

  )
);

$config['graph_types']['device']['axIpNatLsnTrafficStatsFC'] = array(
  'section'   => 'cgn',
  'descr'     => 'Full Cone Sessions',
  'file'      => 'a10-ax-cgn-mib_axipnatlsnglobalstats.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-10',
  'colour_offset' => 2,
  'unit_text' => 'Connections',
  'ds'        => array(
    'TrFCSeCreated' => array('label' => 'created', 'draw' => 'LINE', 'line' => TRUE),
    'TrFCSeFreed' => array('label' => 'freed', 'draw' => 'LINE', 'line' => TRUE),
    'TrFailsInFCSeCreati' => array('label' => 'creation fails', 'draw' => 'LINE', 'line' => TRUE),
  )
);

$config['graph_types']['device']['axIpNatLsnTrafficStatsHP'] = array(
  'section'   => 'cgn',
  'descr'     => 'Hairpin',
  'file'      => 'a10-ax-cgn-mib_axipnatlsnglobalstats.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-10',
  'colour_offset' => 2,
  'unit_text' => 'Connections',
  'ds'        => array(
    'TrHairpinSeCreated' => array('label' => 'HP sesscions created', 'draw' => 'LINE', 'line' => TRUE),
  )
);

$config['graph_types']['device']['axIpNatLsnTrafficStatsEPI'] = array(
  'section'   => 'cgn',
  'descr'     => 'Endpoint Independent',
  'file'      => 'a10-ax-cgn-mib_axipnatlsnglobalstats.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-10',
  'colour_offset' => 2,
  'unit_text' => 'Connections',
  'ds'        => array(
    'TrEpIndepMapM' => array('label' => 'match', 'draw' => 'LINE', 'line' => TRUE),
    'TrEpIndepFilterM' => array('label' => 'filter match', 'draw' => 'LINE', 'line' => TRUE),
  )
);

$config['graph_types']['device']['axIpNatLsnTrafficStatsUQ'] = array(
  'section'   => 'cgn',
  'descr'     => 'User Quota',
  'file'      => 'a10-ax-cgn-mib_axipnatlsnglobalstats.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-q12',
  'colour_offset' => 0,
  'unit_text' => 'Connections',
  'ds'        => array(
    'TrUQCreated' => array('label' => 'UQ created', 'draw' => 'LINE', 'line' => TRUE),
    'TrUQFreed' => array('label' => 'UQ freed', 'draw' => 'LINE', 'line' => TRUE),
    'TrExUQM' => array('label' => 'Ext UQ match', 'draw' => 'LINE', 'line' => TRUE),
  )
);

$config['graph_types']['device']['axIpNatLsnTrafficStatsUQex'] = array(
  'section'   => 'cgn',
  'descr'     => 'User Quota Violations',
  'file'      => 'a10-ax-cgn-mib_axipnatlsnglobalstats.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'colours'   => 'mixed-q12',
  'colour_offset' => 0,
  'unit_text' => 'Connections',
  'ds'        => array(
    'TrFailsInUQCreation' => array('label' => 'UQ creation fails', 'draw' => 'LINE', 'line' => TRUE),
    'TrIcmpUQExceeded' => array('label' => 'UQ ICMP exceeded', 'draw' => 'LINE', 'line' => TRUE),
    'TrUdpUQExceeded' => array('label' => 'UQ UDP exceeded', 'draw' => 'LINE', 'line' => TRUE),
    'TrTcpUQExceeded' => array('label' => 'UQ TCP exceeded', 'draw' => 'LINE', 'line' => TRUE),
    'TrExUQExceeded' => array('label' => 'Ext UQ match exceeded', 'draw' => 'LINE', 'line' => TRUE),
    'TrNatPortUnavailabl' => array('label' => 'NAT port unavail', 'draw' => 'LINE', 'line' => TRUE),
    'TrNewUserResourceUn' => array('label' => 'New user resource unavail', 'draw' => 'LINE', 'line' => TRUE),
  )
);
  
$config['graph_types']['device']['axIpNatLsnNatPortUsageStats'] = array(
  'section'   => 'cgn',
  'descr'     => 'Pool Port Usage',
  'file'      => 'a10-ax-cgn-mib_axipnatlsnglobalstats.rrd',
  'scale_min' => '0',
  'no_mag'    => FALSE,
  'colours'   => 'mixed-q12',
  'colour_offset' => 0,
  'unit_text' => 'Ports',
  'ds'        => array(
    'NatPortTcpNPUsed' => array('label' => 'TCP ports used', 'draw' => 'LINE', 'line' => TRUE),
    'NatPortTcpNPFree' => array('label' => 'TCP ports free', 'draw' => 'LINE', 'line' => TRUE),
    'NatPortUdpNPUsed' => array('label' => 'UDP ports used', 'draw' => 'LINE', 'line' => TRUE),
    'NatPortUdpNPFree' => array('label' => 'TCP ports free', 'draw' => 'LINE', 'line' => TRUE),
  )
);



// FireBrick

$config['graph_types']['device']['fbL2tpTunnelStats'] = array(
  'section'   => 'l2tp',
  'descr'     => 'Tunnel Statistics',
  'file'      => 'firebrick-mib_fbl2tptunnelstats.rrd',
//  'scale_min' => '0.001',
  'no_mag'    => TRUE,
  'num_fmt'   => '6.0',
  'colours'   => 'mixed-6',
  'unit_text' => 'Tunnels',
  'log_y'     => TRUE,
  'ds'        => array(
    'Free'     => array('label' => 'Free'),
    'Opening'  => array('label' => 'Opening'),
    'Live'     => array('label' => 'Live'),
    'Closing'  => array('label' => 'Closing'),
    'Failed'   => array('label' => 'Failed'),
    'Closed'   => array('label' => 'Closed'),
  )
);

$config['graph_types']['device']['fbL2tpSessionStats'] = array(
  'section'   => 'l2tp',
  'descr'     => 'Session Statistics',
  'file'      => 'firebrick-mib_fbl2tpsessionstats.rrd',
//  'scale_min' => '0.001',
  'no_mag'    => TRUE,
  'num_fmt'   => '6.0',
  'colours'   => 'mixed-10',
  'unit_text' => 'Sessions',
  'log_y'     => TRUE,
  'ds'        => array(
    'Free'        => array('label' => 'Free'),
    'Waiting'     => array('label' => 'Waiting'),
    'Opening'     => array('label' => 'Opening'),
    'Negotiating' => array('label' => 'Negotiating'),
    'AuthPending' => array('label' => 'Auth-Pending'),
    'Started'     => array('label' => 'Started'),
    'Live'        => array('label' => 'Live'),
    'AcctPending' => array('label' => 'Acct-Pending'),
    'Closing'     => array('label' => 'Closing'),
    'Closed'      => array('label' => 'Closed'),

  )
);

// Redback

$config['graph_types']['device']['rbnSubsEncapsCount'] = array(
  'file'      => 'rbn-subscriber-active-mib_rbnsubsencapscount.rrd',
  'descr'     => 'Subscriber Encapsulation Count',
  'section'   => 'system',
  'unit_text' => 'Sessions',
  'ds'        => array(
    'ppp' => array('label' => 'ppp'),
    'pppoe' => array('label' => 'pppoe'),
    'bridged1483' => array('label' => 'bridged1483'),
    'routed1483' => array('label' => 'routed1483'),
    'multi1483' => array('label' => 'multi1483'),
    'dot1q1483' => array('label' => 'dot1q1483'),
    'dot1qEnet' => array('label' => 'dot1qEnet'),
    'clips' => array('label' => 'clips'),
    'other' => array('label' => 'other'),
  )
);


// GTA GB-OS

$config['graph_types']['device']['gbStatistics-conns'] = array(
  'file'      => 'gbos-mib_gbstatistics.rrd',
  'descr'     => 'Total Connections',
  'section'   => 'firewall',
  'colours'   => 'mixed-10b',
  'unit_text' => 'Connctions',
  'ds'        => array(
    'CurConns' => array('label' => 'Total', 'draw' => 'AREA'),
  )
);

$config['graph_types']['device']['gbStatistics-conns-inout'] = array(
  'file'      => 'gbos-mib_gbstatistics.rrd',
  'descr'     => 'Connections',
  'section'   => 'firewall',
  'colours'   => 'mixed-10b',
  'colour_offset'   => '3',
  'ds'        => array(
    'CurInConns' => array('label' => 'Inbound', 'draw' => 'AREA'),
    'CurOutConns' => array('label' => 'Outbound', 'invert' => TRUE, 'draw' => 'AREA'),
  )
);

// Mitel
$config['graph_types']['device']['mitelIpera-UsrLic'] = array(
  'file'      => 'mitel-iperavoicelan-mib_mitelipera3000syscapdisplay.rrd',
  'descr'     => 'User licenses',
  'section'   => 'license',
  'colours'   => 'mixed-10b',
  'scale_min' => '-0.1',
  'num_fmt'   => '6.0',
  'ds'        => array(
    'UsrLicPurchased' => array('label' => 'Purchased user license'),
    'UsrLicUsed'      => array('label' => 'Used user license', 'draw' => 'AREA'),
  )
);

$config['graph_types']['device']['mitelIpera-DevLic'] = array(
  'file'      => 'mitel-iperavoicelan-mib_mitelipera3000syscapdisplay.rrd',
  'descr'     => 'Device licenses',
  'section'   => 'license',
  'colours'   => 'mixed-10b',
  'scale_min' => '-0.1',
  'num_fmt'   => '6.0',
  'ds'        => array(
    'DevLicPurchased' => array('label' => 'Purchased device license'),
    'DevLicUsed'      => array('label' => 'Used device license', 'draw' => 'AREA'),
  )
);

// Juniper SRX 5000 SPU Monitoring

$config['graph_types']['device']['jnxJsSPUMonitoringFlowSessions'] = array(
  'file'      => 'juniper-srx5000-spu-monitoring-mib_jnxjsspumonitoringobjectstable.rrd',
  'descr'     => 'Flow Sessions',
  'section'   => 'firewall',
  'log_y'     => TRUE,
  'colours'   => 'mixed-5',
  'unit_text' => 'Sessions',
  'ds'        => array(
    'CurrentFlowSession' => array('label' => 'Flow Sessions', 'DRAW' => 'AREA'),
    'MaxFlowSession'     => array('label' => 'Max Flow Sessions'),
  )
);

$config['graph_types']['device']['jnxJsSPUMonitoringCPSessions'] = array(
  'file'      => 'juniper-srx5000-spu-monitoring-mib_jnxjsspumonitoringobjectstable.rrd',
  'descr'     => 'CP Sessions',
  'unit_text' => 'Sessions',
  'section'   => 'firewall',
  'log_y'     => TRUE,
  'colours'   => 'mixed-5',
  'colour_offset' => 3,
  'ds'        => array(
    'CurrentCPSession' => array('label' => 'CP Sessions', 'DRAW' => 'AREA'),
    'MaxCPSession'     => array('label' => 'Max CP Sessions'),
  )
);

// MSERIES

$config['graph_types']['device']['mseries_alarms'] = array(
  'section'   => 'system',
  'descr'     => 'Alarms',
  'file'      => 'MSERIES-ALARM-MIB-alarmGeneral.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'num_fmt'   => '6.0',
  'colours'   => 'mixed',
  'unit_text' => 'Alarms',
  'ds'        => array(
    'active_alarms' => array('label' => 'Active Alarms'),
    'logged_alarms' => array('label' => 'Logged Alarms'),
  )
);

// SONICWALL-FIREWALL-IP-STATISTICS-MIB
$config['graph_types']['device']['sonicwall_sessions'] = array(
  'section'   => 'firewall',
  'descr'     => 'Number of connection cache entries through the firewall',
  'file'      => 'sonicwall-firewall-ip-statistics-mib_sonicwallfwstats.rrd',
  'scale_min' => '0',
  'no_mag'    => TRUE,
  'num_fmt'   => '6.0',
  'colours'   => 'mixed',
  'unit_text' => 'Entries',
  'ds'        => array(
    'MaxConnCache'     => array('label' => 'Maximum connection'),
    'CurrentConnCache' => array('label' => 'Active connection'),
  )
);

$config['graph_types']['device']['sonicwall_sa_byte'] = array(
  'section'   => 'firewall',
  'descr'     => 'Encrypted/decrypted bytes count',
  'file'      => 'sonicwall-firewall-ip-statistics-mib_sonicsastattable.rrd',
  'scale_min' => '0',
  'colours'   => 'mixed',
  'unit_text' => 'Byte/s',
  'ds'        => array(
    'EncryptByteCount'  => array('label' => 'Encrypted'),
    'DecryptByteCount'  => array('label' => 'Decrypted'),
  )
);

$config['graph_types']['device']['sonicwall_sa_pkt'] = array(
  'section'   => 'firewall',
  'descr'     => 'Encrypted/decrypted packets count',
  'file'      => 'sonicwall-firewall-ip-statistics-mib_sonicsastattable.rrd',
  'scale_min' => '0',
  'colours'   => 'mixed',
  'unit_text' => 'Packet/s',
  'ds'        => array(
    'EncryptPktCount'   => array('label' => 'Encrypted'),
    'DecryptPktCount'   => array('label' => 'Decrypted'),
    'InFragPktCount'    => array('label' => 'Incoming Fragmented'),
    'OutFragPktCount'   => array('label' => 'Outgoing Fragmented'),
  )
);

$config['graph_types']['device']['juniperive_users']['section'] = 'appliance';
$config['graph_types']['device']['juniperive_users']['order'] = '0';
$config['graph_types']['device']['juniperive_users']['descr'] = 'Concurrent Users';
$config['graph_types']['device']['juniperive_meetings']['section'] = 'appliance';
$config['graph_types']['device']['juniperive_meetings']['order'] = '0';
$config['graph_types']['device']['juniperive_meetings']['descr'] = 'Meetings';
$config['graph_types']['device']['juniperive_connections']['section'] = 'appliance';
$config['graph_types']['device']['juniperive_connections']['order'] = '0';
$config['graph_types']['device']['juniperive_connections']['descr'] = 'Connections';
$config['graph_types']['device']['juniperive_storage']['section'] = 'appliance';
$config['graph_types']['device']['juniperive_storage']['order'] = '0';
$config['graph_types']['device']['juniperive_storage']['descr'] = 'Storage';

// Device - Ports section
$config['graph_types']['device']['bits']['section'] = 'ports';
$config['graph_types']['device']['bits']['descr']   = 'Traffic';

// Device - Netstat section
$config['graph_types']['device']['ipsystemstats_ipv4']['section'] = 'netstats';
$config['graph_types']['device']['ipsystemstats_ipv4']['order'] = '0';
$config['graph_types']['device']['ipsystemstats_ipv4']['descr'] = 'IPv4 Packet Statistics';
$config['graph_types']['device']['ipsystemstats_ipv4_frag']['section'] = 'netstats';
$config['graph_types']['device']['ipsystemstats_ipv4_frag']['order'] = '0';
$config['graph_types']['device']['ipsystemstats_ipv4_frag']['descr'] = 'IPv4 Fragmentation Statistics';
$config['graph_types']['device']['ipsystemstats_ipv6']['section'] = 'netstats';
$config['graph_types']['device']['ipsystemstats_ipv6']['order'] = '0';
$config['graph_types']['device']['ipsystemstats_ipv6']['descr'] = 'IPv6 Packet Statistics';
$config['graph_types']['device']['ipsystemstats_ipv6_frag']['section'] = 'netstats';
$config['graph_types']['device']['ipsystemstats_ipv6_frag']['order'] = '0';
$config['graph_types']['device']['ipsystemstats_ipv6_frag']['descr'] = 'IPv6 Fragmentation Statistics';
$config['graph_types']['device']['netstat_icmp_info']['section'] = 'netstats';
$config['graph_types']['device']['netstat_icmp_info']['order'] = '0';
$config['graph_types']['device']['netstat_icmp_info']['descr'] = 'ICMP Informational Statistics';
$config['graph_types']['device']['netstat_icmp']['section'] = 'netstats';
$config['graph_types']['device']['netstat_icmp']['order'] = '0';
$config['graph_types']['device']['netstat_icmp']['descr'] = 'ICMP Statistics';
$config['graph_types']['device']['netstat_ip']['section'] = 'netstats';
$config['graph_types']['device']['netstat_ip']['order'] = '0';
$config['graph_types']['device']['netstat_ip']['descr'] = 'IP Statistics';
$config['graph_types']['device']['netstat_ip_frag']['section'] = 'netstats';
$config['graph_types']['device']['netstat_ip_frag']['order'] = '0';
$config['graph_types']['device']['netstat_ip_frag']['descr'] = 'IP Fragmentation Statistics';

$config['graph_types']['device']['netstat_snmp_stats']           = array(
                                                                'section' => 'netstats',
                                                                'order'   => '0',
                                                                'descr'   => 'SNMP Statistics');

$config['graph_types']['device']['netstat_snmp_packets']    = array(
                                                                'section' => 'netstats',
                                                                'order'   => '0',
                                                                'descr'   => 'SNMP Packets');

$config['graph_types']['device']['netstat_tcp_stats']            = array(
                                                                'section' => 'netstats',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP Statistics');

$config['graph_types']['device']['netstat_tcp_currestab']       = array(
                                                                'section' => 'netstats',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP Established Connections');

$config['graph_types']['device']['netstat_tcp_segments']    = array(
                                                                'section' => 'netstats',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP Segments');

$config['graph_types']['device']['netstat_udp_errors']         = array(
                                                                'section' => 'netstats',
                                                                'order'   => '0',
                                                                'descr'   => 'UDP Errors');

$config['graph_types']['device']['netstat_udp_datagrams']    = array(
                                                                'section' => 'netstats',
                                                                'order'   => '0',
                                                                'descr'   => 'UDP Datagrams');

// Device - System section

$config['graph_types']['device']['fdb_count']  = array('name' => 'FDB counts',        'descr' => "FDB usage",
  'section'   => 'system',
  'file'      => 'fdb_count.rrd',
  'unit_text' => '',
  'order'     => '1',
  'colours'   => 'oranges',
  'ds'        => array(
    'value' => array('label' => 'FDB Entries', 'draw' => 'AREA', 'line' => TRUE, 'rra_min' => '0', 'rra_max' => FALSE)
  )
);

$config['graph_types']['device']['hr_processes'] = array(
  'section'   => 'system',
  'descr'     => 'Running Processes',
  'file'      => 'hr_processes.rrd',
  'colours'   => 'pinks',
  'unit_text' => ' ',
  'ds'        => array(
    'procs' => array('label' => 'Processes', 'draw' => 'AREA', 'line' => TRUE)
  )
);

$config['graph_types']['device']['hr_users'] = array(
  'section'   => 'system',
  'descr'     => 'Users Logged In',
  'file'      => 'hr_users.rrd',
  'colours'   => 'greens',
  'unit_text' => ' ',
  'ds'        => array(
    'users' => array('label' => 'Users', 'draw' => 'AREA', 'line' => TRUE)
  )
);

$config['graph_types']['device']['mempool']['section'] = 'system';
$config['graph_types']['device']['mempool']['order'] = '0';
$config['graph_types']['device']['mempool']['descr'] = 'Memory Usage';
$config['graph_types']['device']['processor']['section'] = 'system';
$config['graph_types']['device']['processor']['order'] = '0';
$config['graph_types']['device']['processor']['descr'] = 'Processors';
$config['graph_types']['device']['processor']['long'] = 'This is an aggregate graph of all processors in the system.';

$config['graph_types']['alert']['status'] = array(
  'descr'     => 'Historical Status'
);

$config['graph_types']['device']['storage']['section'] = 'system';
$config['graph_types']['device']['storage']['order'] = '0';
$config['graph_types']['device']['storage']['descr'] = 'Filesystem Usage';

$config['graph_types']['device']['ucd_cpu']['section'] = 'system';
$config['graph_types']['device']['ucd_cpu']['order'] = '0';
$config['graph_types']['device']['ucd_cpu']['descr'] = 'Detailed Processor Utilisation';

$config['graph_types']['device']['ucd_ss_cpu']['section'] = 'system';
$config['graph_types']['device']['ucd_ss_cpu']['order'] = '0';
$config['graph_types']['device']['ucd_ss_cpu']['descr'] = 'Extended Processor Utilisation';

$config['graph_types']['device']['ucd_load'] = array(
  'section'   => 'system',
  'descr'     => 'Load Averages',
  'file'      => 'ucd_load.rrd',
  'unit_text' => 'Load Average',
  'no_mag'    => TRUE,
  'num_fmt'   => '5.2',
  'ds'        => array(
    '1min'   => array('label' => '1 Min',  'colour' => 'c5aa00', 'cdef' => '1min,100,/'),
    '5min'   => array('label' => '5 Min',  'colour' => 'ea8f00', 'cdef' => '5min,100,/'),
    '15min'  => array('label' => '15 Min', 'colour' => 'cc0000', 'cdef' => '15min,100,/')
  )
);

$config['graph_types']['device']['ucd_memory']['section'] = 'system';
$config['graph_types']['device']['ucd_memory']['order'] = '0';
$config['graph_types']['device']['ucd_memory']['descr'] = 'Detailed Memory';
$config['graph_types']['device']['ucd_swap_io']['section'] = 'system';
$config['graph_types']['device']['ucd_swap_io']['order'] = '0';
$config['graph_types']['device']['ucd_swap_io']['descr'] = 'Swap I/O Activity';
$config['graph_types']['device']['ucd_io']['section'] = 'system';
$config['graph_types']['device']['ucd_io']['order'] = '0';
$config['graph_types']['device']['ucd_io']['descr'] = 'System I/O Activity';

$config['graph_types']['device']['ucd_contexts'] = array(
  'section'   => 'system',
  'descr'     => 'Context Switches',
  'file'      => 'ucd_ssRawContexts.rrd',
  'colours'   => 'blues',
  'unit_text' => ' ',
  'ds'        => array(
    'value' => array('label' => 'Switches/s', 'draw' => 'AREA', 'line' => TRUE)
  )
);

$config['graph_types']['device']['ucd_interrupts'] = array(
  'section'   => 'system',
  'descr'     => 'System Interrupts',
  'file'      => 'ucd_ssRawInterrupts.rrd',
  'colours'   => 'reds',
  'unit_text' => ' ',
  'ds'        => array(
    'value' => array('label' => 'Interrupts/s', 'draw' => 'AREA', 'line' => TRUE)
  )
);

$config['graph_types']['device']['uptime'] = array(
  'section'   => 'system',
  'descr'     => 'Device Uptime',
  'file'      => 'uptime.rrd',
  'unit_text' => ' ',
  'ds'        => array(
    'uptime' => array('label' => 'Days Uptime', 'draw' => 'AREA', 'line' => TRUE, 'colour' => 'c5c5c5', 'cdef' => 'uptime,86400,/', 'rra_min' => FALSE, 'rra_max' => FALSE)
  )
);

$config['graph_types']['device']['ksm_pages']['section']           = 'system';
$config['graph_types']['device']['ksm_pages']['order']             = '0';
$config['graph_types']['device']['ksm_pages']['descr']             = 'KSM Shared Pages';

$config['graph_types']['device']['iostat_util']['section']         = 'system';
$config['graph_types']['device']['iostat_util']['order']           = '0';
$config['graph_types']['device']['iostat_util']['descr']           = 'Disk I/O Utilisation';

$config['graph_types']['device']['vpdn_sessions_l2tp']['section']  = 'vpdn';
$config['graph_types']['device']['vpdn_sessions_l2tp']['order']    = '0';
$config['graph_types']['device']['vpdn_sessions_l2tp']['descr']    = 'VPDN L2TP Sessions';

$config['graph_types']['device']['vpdn_tunnels_l2tp']['section']   = 'vpdn';
$config['graph_types']['device']['vpdn_tunnels_l2tp']['order']     = '0';
$config['graph_types']['device']['vpdn_tunnels_l2tp']['descr']     = 'VPDN L2TP Tunnels';

// ALVARION-DOT11-WLAN-MIB

$config['graph_types']['device']['alvarion_events'] = array(
  'section'   => 'wireless',
  'file'      => 'alvarion-events.rrd',
  'descr'     => 'Network events',
  'colours'   => 'mixed',
  'unit_text' => 'Events/s',
  'ds'        => array(
    'TotalTxEvents'      => array('label' => 'Total TX',      'draw' => 'LINE'),
    'TotalRxEvents'      => array('label' => 'Total RX',      'draw' => 'LINE'),
    'OthersTxEvents'     => array('label' => 'Other TX',      'draw' => 'LINE'),
    'RxDecryptEvents'    => array('label' => 'Decrypt RX',    'draw' => 'LINE'),
    'OverrunEvents'      => array('label' => 'Overrun',       'draw' => 'LINE'),
    'UnderrunEvents'     => array('label' => 'Underrun',      'draw' => 'LINE'),
    'DroppedFrameEvents' => array('label' => 'Dropped Frame', 'draw' => 'LINE'),
  )
);

$config['graph_types']['device']['alvarion_frames_errors'] = array(
  'section'   => 'wireless',
  'file'      => 'alvarion-frames-errors.rrd',
  'descr'     => 'Other frames errors',
  'colours'   => 'mixed',
  'unit_text' => 'Frames/s',
  'ds'        => array(
    'FramesDelayedDueToS' => array('label' => 'Delayed Due To Sw Retry',     'draw' => 'LINE'),
    'FramesDropped'       => array('label' => 'Dropped Frames',              'draw' => 'LINE'),
    'RecievedBadFrames'   => array('label' => 'Recieved Bad Frames',         'draw' => 'LINE'),
    'NoOfDuplicateFrames' => array('label' => 'Discarded Duplicate Frames',  'draw' => 'LINE'),
    'NoOfInternallyDisca' => array('label' => 'Internally Discarded MirCir', 'draw' => 'LINE'),
  )
);

$config['graph_types']['device']['alvarion_errors'] = array(
  'section'   => 'wireless',
  'file'      => 'alvarion-errors.rrd',
  'descr'     => 'Unidentified signals and CRC errors',
  'colours'   => 'mixed',
  'unit_text' => 'Frames/s',
  'ds'        => array(
    'PhyErrors' => array('label' => 'Phy Errors', 'draw' => 'LINE'),
    'CRCErrors' => array('label' => 'CRC Errors', 'draw' => 'LINE'),
  )
);


$config['graph_types']['netscalervsvr']['bits']       = array('name' => 'Bits',              'descr' => "Traffic in Bits/sec");
$config['graph_types']['netscalervsvr']['pkts']       = array('name' => 'Ucast Pkts',        'descr' => "Packets/sec");
$config['graph_types']['netscalervsvr']['conns']      = array('name' => 'NU Pkts',           'descr' => "Client and Server Connections");
$config['graph_types']['netscalervsvr']['reqs']       = array('name' => 'Pkt Size',          'descr' => "Requests and Responses");
$config['graph_types']['netscalervsvr']['hitmiss']    = array('name' => 'Percent',           'descr' => "Hit/Miss");

$config['graph_types']['netscalersvc']['bits']       = array('name' => 'Bits',              'descr' => "Traffic in Bits/sec");
$config['graph_types']['netscalersvc']['pkts']       = array('name' => 'Ucast Pkts',        'descr' => "Packets/sec");
$config['graph_types']['netscalersvc']['conns']      = array('name' => 'NU Pkts',           'descr' => "Client and Server Connections");
$config['graph_types']['netscalersvc']['reqs']       = array('name' => 'Packet Size',          'descr' => "Requests and Responses");
$config['graph_types']['netscalersvc']['ttfb']       = array('name' => 'Time to First Byte',           'descr' => "Time to First Byte");


$config['graph_types']['netscalersvcgrpmem']['bits']       = array('name' => 'Bits',              'descr' => "Traffic in Bits/sec");
$config['graph_types']['netscalersvcgrpmem']['pkts']       = array('name' => 'Ucast Pkts',        'descr' => "Packets/sec");
$config['graph_types']['netscalersvcgrpmem']['conns']      = array('name' => 'NU Pkts',           'descr' => "Client and Server Connections");
$config['graph_types']['netscalersvcgrpmem']['reqs']       = array('name' => 'Packet Size',          'descr' => "Requests and Responses");
$config['graph_types']['netscalersvcgrpmem']['ttfb']       = array('name' => 'Time to First Byte',           'descr' => "Time to First Byte");


$config['graph_types']['device']['netscaler_tcp_conn']['section']  = 'netscaler_tcp';
$config['graph_types']['device']['netscaler_tcp_conn']['order']    = '0';
$config['graph_types']['device']['netscaler_tcp_conn']['descr']    = 'TCP Connections';

$config['graph_types']['device']['netscaler_tcp_bits']['section']  = 'netscaler_tcp';
$config['graph_types']['device']['netscaler_tcp_bits']['order']    = '0';
$config['graph_types']['device']['netscaler_tcp_bits']['descr']    = 'TCP Traffic';

$config['graph_types']['device']['netscaler_tcp_pkts']['section']  = 'netscaler_tcp';
$config['graph_types']['device']['netscaler_tcp_pkts']['order']    = '0';
$config['graph_types']['device']['netscaler_tcp_pkts']['descr']    = 'TCP Packets';

$config['graph_types']['device']['netscaler_common_errors']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Common Errors');

$config['graph_types']['device']['netscaler_conn_client']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Client Connections');

$config['graph_types']['device']['netscaler_conn_clientserver']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Client and Server Connections');

$config['graph_types']['device']['netscaler_conn_current']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Current Connections');

$config['graph_types']['device']['netscaler_conn_server']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Server Connections');

$config['graph_types']['device']['netscaler_conn_spare']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Spare Connections');

$config['graph_types']['device']['netscaler_conn_zombie_flushed']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Zombie Flushed Connections');

$config['graph_types']['device']['netscaler_conn_zombie_halfclosed']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Zombie Half-Closed Connections');

$config['graph_types']['device']['netscaler_conn_zombie_halfopen']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Zombie Half-Open Connections');

$config['graph_types']['device']['netscaler_conn_zombie_packets']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Zombie Connection Packets');

$config['graph_types']['device']['netscaler_cookie_rejected']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Cookie Rejections');

$config['graph_types']['device']['netscaler_data_errors']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Data Errors');

$config['graph_types']['device']['netscaler_out_of_order']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Out Of Order');

$config['graph_types']['device']['netscaler_retransmission_error']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Retransmission Errors');

$config['graph_types']['device']['netscaler_retransmit_err']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'Retransmit Errors');

$config['graph_types']['device']['netscaler_rst_errors']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP RST Errors');

$config['graph_types']['device']['netscaler_syn_errors']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP SYN Errors');

$config['graph_types']['device']['netscaler_syn_stats']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP SYN Statistics');

$config['graph_types']['device']['netscaler_tcp_errretransmit']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP Error Retransmits');

$config['graph_types']['device']['netscaler_tcp_errfullretransmit']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP Error Full Retransmits');

$config['graph_types']['device']['netscaler_tcp_errpartialretransmit']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP Error Partial Retransmits');

$config['graph_types']['device']['netscaler_tcp_errretransmitgiveup']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP Error Retransmission Give Up');

$config['graph_types']['device']['netscaler_tcp_errfastretransmissions']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP Error Fast Retransmissions');

$config['graph_types']['device']['netscaler_tcp_errxretransmissions']    = array(
                                                                'section' => 'netscaler_tcp',
                                                                'order'   => '0',
                                                                'descr'   => 'TCP Error Retransmit Count');

$config['graph_types']['device']['nsHttpRequests'] = array(
  'section'   => 'netscaler_http',
  'file'      => 'nsHttpStatsGroup.rrd',
  'descr'     => 'HTTP Request Types',
  'colours'   => 'mixed',
  'unit_text' => 'Requests/s',
  'log_y'     => TRUE,
  'ds'        => array(
    'TotGets'   => array('label' => 'GETs',   'draw' => 'AREASTACK'),
    'TotPosts'  => array('label' => 'POSTs',  'draw' => 'AREASTACK'),
    'TotOthers' => array('label' => 'Others', 'draw' => 'AREASTACK'),
  )
);

$config['graph_types']['device']['nsHttpReqResp'] = array(
  'section'   => 'netscaler_http',
  'file'      => 'nsHttpStatsGroup.rrd',
  'descr'     => 'HTTP Requests and Responses',
  'colours'   => 'mixed',
  'unit_text' => 'Per second',
  'log_y'     => TRUE,
  'ds'        => array(
    'TotRequestsRate'  => array('label' => 'Requests',   'draw' => 'AREASTACK'),
    'TotResponsesRate' => array('label' => 'Responses', 'draw' => 'AREASTACK', 'invert' => TRUE),
  )
);


$config['graph_types']['device']['nsHttpBytes'] = array(
  'section'   => 'netscaler_http',
  'file'      => 'nsHttpStatsGroup.rrd',
  'descr'     => 'HTTP Traffic',
  'colours'   => 'mixed',
  'ds'        => array(
    'TotRxResponseBytes' => array('label' => 'Response In',  'cdef' => 'TotRxResponseBytes,8,*', 'draw' => 'AREA'),
    'TotTxResponseBytes' => array('label' => 'Response Out', 'cdef' => 'TotRxResponseBytes,8,*', 'invert' => TRUE, 'draw' => 'AREA'),
    'TotRxRequestBytes'  => array('label' => 'Request  In',  'cdef' => 'TotRxRequestBytes,8,*'),
    'TotTxRequestBytes'  => array('label' => 'Request  Out', 'cdef' => 'TotTxRequestBytes,8,*', 'invert' => TRUE),
  )
);

$config['graph_types']['device']['nsHttpSPDY'] = array(
  'section'   => 'netscaler_http',
  'descr'     => 'SPDY Requests',
  'file'      => 'nsHttpStatsGroup.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Requests/s',
  'log_y'     => TRUE,
  'ds'        => array(
    'spdyTotStreams' => array('label' => 'All SPDY Streams', 'draw' => 'AREA'),
    'spdyv2TotStreams' => array('label' => 'SPDYv2 Streams', 'draw' => 'AREA'),
    'spdyv3TotStreams' => array('label' => 'SPDYv3 Streams', 'draw' => 'AREASTACK'),

  )
);

$config['graph_types']['device']['nsCompHttpSaving'] = array(
  'section'   => 'netscaler_comp',
  'descr'     => 'Bandwidth saving from TCP compression',
  'file'      => 'nsCompressionStatsGroup.rrd',
  'scale_min' => '0',
  'scale_max' => '100',
  'colours'   => 'greens',
  'unit_text' => 'Percent',
  'ds'        => array(
    'compHttpBandwidthS' => array ('label' => 'Saving', 'draw' => 'AREA'),
  )
);

$config['graph_types']['device']['nsSslTransactions'] = array(
  'section'   => 'netscaler_ssl',
  'descr'     => 'SSL Transactions',
  'file'      => 'netscaler-SslStats.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Transactions/s',
  'log_y'     => TRUE,
  'ds'        => array(
    'Transactions'      => array('label' => 'Total', 'draw' => 'AREA', 'colour' => 'B0B0B0'),
    'SSLv2Transactions' => array('label' => 'SSLv2'),
    'SSLv3Transactions' => array('label' => 'SSLv3'),
    'TLSv1Transactions' => array('label' => 'TLSv1'),
    'TLSv11Transactions' => array('label' => 'TLSv1.1'),
    'TLSv12Transactions' => array('label' => 'TLSv1.2')

  )
);

$config['graph_types']['device']['nsSslHandshakes'] = array(
  'section'   => 'netscaler_ssl',
  'descr'     => 'SSL Handshakes',
  'file'      => 'netscaler-SslStats.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Handshakes/s',
  'log_y'     => TRUE,
  'ds'        => array(
    'SSLv2Handshakes' => array('label' => 'SSLv2'),
    'SSLv3Handshakes' => array('label' => 'SSLv3'),
    'TLSv1Handshakes' => array('label' => 'TLSv1'),
    'TLSv11Handshakes' => array('label' => 'TLSv1.1'),
    'TLSv12Handshakes' => array('label' => 'TLSv1.2')

  )
);

$config['graph_types']['device']['nsSslSessions'] = array(
  'section'   => 'netscaler_ssl',
  'descr'     => 'SSL Sessions',
  'file'      => 'netscaler-SslStats.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Sesss/s',
  'log_y'     => TRUE,
  'ds'        => array(
    'SSLv2Sesss' => array('label' => 'SSLv2'),
    'SSLv3Sesss' => array('label' => 'SSLv3'),
    'TLSv1Sesss' => array('label' => 'TLSv1'),
    'TLSv11Sesss' => array('label' => 'TLSv1.1'),
    'TLSv12Sesss' => array('label' => 'TLSv1.2')

  )
);

$config['graph_types']['device']['nsSsl'] = array(
  'section'   => 'netscaler_ssl',
  'descr'     => 'SSL Table Dump',
  'file'      => 'netscaler-SslStats.rrd',
  'ds'        => array(
    'CardStatus' => array('label' => 'CardStatus'),
    'EngineStatus' => array('label' => 'EngineStatus'),
    'SesssPerSec' => array('label' => 'SesssPerSec'),
    'Transactions' => array('label' => 'Transactions'),
    'SSLv2Transactions' => array('label' => 'SSLv2Transactions'),
    'SSLv3Transactions' => array('label' => 'SSLv3Transactions'),
    'TLSv1Transactions' => array('label' => 'TLSv1Transactions'),
    'Sesss' => array('label' => 'Sesss'),
    'SSLv2Sesss' => array('label' => 'SSLv2Sesss'),
    'SSLv3Sesss' => array('label' => 'SSLv3Sesss'),
    'TLSv1Sesss' => array('label' => 'TLSv1Sesss'),
    'ExpiredSesss' => array('label' => 'ExpiredSesss'),
    'NewSesss' => array('label' => 'NewSesss'),
    'SessHits' => array('label' => 'SessHits'),
    'SessMiss' => array('label' => 'SessMiss'),
    'RenegSesss' => array('label' => 'RenegSesss'),
    'SSLv3RenegSesss' => array('label' => 'SSLv3RenegSesss'),
    'TLSv1RenegSesss' => array('label' => 'TLSv1RenegSesss'),
    'SSLv2Handshakes' => array('label' => 'SSLv2Handshakes'),
    'SSLv3Handshakes' => array('label' => 'SSLv3Handshakes'),
    'TLSv1Handshakes' => array('label' => 'TLSv1Handshakes'),
    'SSLv2ClientAuths' => array('label' => 'SSLv2ClientAuths'),
    'SSLv3ClientAuths' => array('label' => 'SSLv3ClientAuths'),
    'TLSv1ClientAuths' => array('label' => 'TLSv1ClientAuths'),
    'RSA512keyExch' => array('label' => 'RSA512keyExch'),
    'RSA1024keyExch' => array('label' => 'RSA1024keyExch'),
    'RSA2048keyExch' => array('label' => 'RSA2048keyExch'),
    'DH512keyExch' => array('label' => 'DH512keyExch'),
    'DH1024keyExch' => array('label' => 'DH1024keyExch'),
    'DH2048keyExch' => array('label' => 'DH2048keyExch'),
    'RSAAuths' => array('label' => 'RSAAuths'),
    'DHAuths' => array('label' => 'DHAuths'),
    'DSSAuths' => array('label' => 'DSSAuths'),
    'NULLAuths' => array('label' => 'NULLAuths'),
    '40BitRC4Ciphs' => array('label' => '40BitRC4Ciphs'),
    '56BitRC4Ciphs' => array('label' => '56BitRC4Ciphs'),
    '64BitRC4Ciphs' => array('label' => '64BitRC4Ciphs'),
    '128BitRC4Ciphs' => array('label' => '128BitRC4Ciphs'),
    '40BitDESCiphs' => array('label' => '40BitDESCiphs'),
    '56BitDESCiphs' => array('label' => '56BitDESCiphs'),
    '168Bit3DESCiphs' => array('label' => '168Bit3DESCiphs'),
    '40BitRC2Ciphs' => array('label' => '40BitRC2Ciphs'),
    '56BitRC2Ciphs' => array('label' => '56BitRC2Ciphs'),
    '128BitRC2Ciphs' => array('label' => '128BitRC2Ciphs'),
    '128BitIDEACiphs' => array('label' => '128BitIDEACiphs'),
    'NULLCiphs' => array('label' => 'NULLCiphs'),
    'MD5Mac' => array('label' => 'MD5Mac'),
    'SHAMac' => array('label' => 'SHAMac'),
    'OffloadBulkDES' => array('label' => 'OffloadBulkDES'),
    'OffloadRSAKeyExch' => array('label' => 'OffloadRSAKeyExch'),
    'OffloadDHKeyExch' => array('label' => 'OffloadDHKeyExch'),
    'OffloadSignRSA' => array('label' => 'OffloadSignRSA'),
    'BeSesss' => array('label' => 'BeSesss'),
    'BeSSLv3Sesss' => array('label' => 'BeSSLv3Sesss'),
    'BeTLSv1Sesss' => array('label' => 'BeTLSv1Sesss'),
    'BeExpiredSesss' => array('label' => 'BeExpiredSesss'),
    'BeSessMplxAtts' => array('label' => 'BeSessMplxAtts'),
    'BeSessMplxAttSucc' => array('label' => 'BeSessMplxAttSucc'),
    'BeSessMplxAttFails' => array('label' => 'BeSessMplxAttFails'),
    'BeMaxMplxedSesss' => array('label' => 'BeMaxMplxedSesss'),
    'BeSSLv3Handshakes' => array('label' => 'BeSSLv3Handshakes'),
    'BeTLSv1Handshakes' => array('label' => 'BeTLSv1Handshakes'),
    'BeSSLv3ClientAuths' => array('label' => 'BeSSLv3ClientAuths'),
    'BeTLSv1ClientAuths' => array('label' => 'BeTLSv1ClientAuths'),
    'BeRSA512keyExch' => array('label' => 'BeRSA512keyExch'),
    'BeRSA1024keyExch' => array('label' => 'BeRSA1024keyExch'),
    'BeRSA2048keyExch' => array('label' => 'BeRSA2048keyExch'),
    'BeDH512keyExch' => array('label' => 'BeDH512keyExch'),
    'BeDH1024keyExch' => array('label' => 'BeDH1024keyExch'),
    'BeDH2048keyExch' => array('label' => 'BeDH2048keyExch'),
    'BeRSAAuths' => array('label' => 'BeRSAAuths'),
    'BeDHAuths' => array('label' => 'BeDHAuths'),
    'BeDSSAuths' => array('label' => 'BeDSSAuths'),
    'BeNULLAuths' => array('label' => 'BeNULLAuths'),
    'Be40BitRC4Ciphs' => array('label' => 'Be40BitRC4Ciphs'),
    'Be56BitRC4Ciphs' => array('label' => 'Be56BitRC4Ciphs'),
    'Be64BitRC4Ciphs' => array('label' => 'Be64BitRC4Ciphs'),
    'Be128BitRC4Ciphs' => array('label' => 'Be128BitRC4Ciphs'),
    'Be40BitDESCiphs' => array('label' => 'Be40BitDESCiphs'),
    'Be56BitDESCiphs' => array('label' => 'Be56BitDESCiphs'),
    'Be168Bit3DESCiphs' => array('label' => 'Be168Bit3DESCiphs'),
    'Be40BitRC2Ciphs' => array('label' => 'Be40BitRC2Ciphs'),
    'Be56BitRC2Ciphs' => array('label' => 'Be56BitRC2Ciphs'),
    'Be128BitRC2Ciphs' => array('label' => 'Be128BitRC2Ciphs'),
    'Be128BitIDEACiphs' => array('label' => 'Be128BitIDEACiphs'),
    'BeNULLCiphs' => array('label' => 'BeNULLCiphs'),
    'BeMD5Mac' => array('label' => 'BeMD5Mac'),
    'BeSHAMac' => array('label' => 'BeSHAMac'),
    'CurSesss' => array('label' => 'CurSesss'),
    'OffloadBulkAES' => array('label' => 'OffloadBulkAES'),
    'OffloadBulkRC4' => array('label' => 'OffloadBulkRC4'),
    'NumCardsUP' => array('label' => 'NumCardsUP'),
    'Cards' => array('label' => 'Cards'),
    'BkendSessReNeg' => array('label' => 'BkendSessReNeg'),
    'CipherAES128' => array('label' => 'CipherAES128'),
    'BkendSslV3Renego' => array('label' => 'BkendSslV3Renego'),
    'BkendTlSvlRenego' => array('label' => 'BkendTlSvlRenego'),
    'CipherAES256' => array('label' => 'CipherAES256'),
    'BkendCipherAES128' => array('label' => 'BkendCipherAES128'),
    'BkendCipherAES256' => array('label' => 'BkendCipherAES256'),
    'HwEncBE' => array('label' => 'HwEncBE'),
    'Dec' => array('label' => 'Dec'),
    'SwEncFE' => array('label' => 'SwEncFE'),
    'EncFE' => array('label' => 'EncFE'),
    'Enc' => array('label' => 'Enc'),
    'DecHw' => array('label' => 'DecHw'),
    'SwDecBE' => array('label' => 'SwDecBE'),
    'HwDecFE' => array('label' => 'HwDecFE'),
    'EncHw' => array('label' => 'EncHw'),
    'DecSw' => array('label' => 'DecSw'),
    'SwEncBE' => array('label' => 'SwEncBE'),
    'EncSw' => array('label' => 'EncSw'),
    'SwDecFE' => array('label' => 'SwDecFE'),
    'EncBE' => array('label' => 'EncBE'),
    'DecBE' => array('label' => 'DecBE'),
    'HwDecBE' => array('label' => 'HwDecBE'),
    'DecFE' => array('label' => 'DecFE'),
    'HwEncFE' => array('label' => 'HwEncFE'),
    'RSA4096keyExch' => array('label' => 'RSA4096keyExch'),
    'CurQSize' => array('label' => 'CurQSize'),
    'ChipReinitCount' => array('label' => 'ChipReinitCount'),
    'ECDHE224keyExch' => array('label' => 'ECDHE224keyExch'),
    'ECDHE256keyExch' => array('label' => 'ECDHE256keyExch'),
    'ECDHE384keyExch' => array('label' => 'ECDHE384keyExch'),
    'ECDHE521keyExch' => array('label' => 'ECDHE521keyExch'),
    'TransactionsRate' => array('label' => 'TransactionsRate'),
    'SSLv2TransactionsRa' => array('label' => 'SSLv2TransactionsRa'),
    'SSLv3TransactionsRa' => array('label' => 'SSLv3TransactionsRa'),
    'TLSv1TransactionsRa' => array('label' => 'TLSv1TransactionsRa'),
    'BeEcdheCurve521' => array('label' => 'BeEcdheCurve521'),
    'BeEcdheCurve384' => array('label' => 'BeEcdheCurve384'),
    'BeEcdheCurve256' => array('label' => 'BeEcdheCurve256'),
    'BeEcdheCurve224' => array('label' => 'BeEcdheCurve224'),
    'TLSv11Handshakes' => array('label' => 'TLSv11Handshakes'),
    'TLSv12Handshakes' => array('label' => 'TLSv12Handshakes'),
    'TLSv11Transactions' => array('label' => 'TLSv11Transactions'),
    'TLSv12Transactions' => array('label' => 'TLSv12Transactions'),
    'TLSv11Sesss' => array('label' => 'TLSv11Sesss'),
    'TLSv12Sesss' => array('label' => 'TLSv12Sesss'),
    'TLSv11RenegSesss' => array('label' => 'TLSv11RenegSesss'),
    'TLSv12RenegSesss' => array('label' => 'TLSv12RenegSesss'),
    'TLSv11ClientAuths' => array('label' => 'TLSv11ClientAuths'),
    'TLSv12ClientAuths' => array('label' => 'TLSv12ClientAuths'),
    'TLSv11TransactionRa' => array('label' => 'TLSv11TransactionRa'),
    'TLSv12TransactionRa' => array('label' => 'TLSv12TransactionRa'),
    '128BitAESGCMCiphs' => array('label' => '128BitAESGCMCiphs'),
    '256BitAESGCMCiphs' => array('label' => '256BitAESGCMCiphs'),
    'OffloadBulkAESGCM12' => array('label' => 'OffloadBulkAESGCM12'),
    'OffloadBulkAESGCM25' => array('label' => 'OffloadBulkAESGCM25'),
    'BeTLSv11Sesss' => array('label' => 'BeTLSv11Sesss'),
    'BeTLSv12Sesss' => array('label' => 'BeTLSv12Sesss'),
    'BeTLSv11Handshakes' => array('label' => 'BeTLSv11Handshakes'),
    'BeTLSv12Handshakes' => array('label' => 'BeTLSv12Handshakes'),
    'BeTLSv11ClientAuths' => array('label' => 'BeTLSv11ClientAuths'),
    'BeTLSv12ClientAuths' => array('label' => 'BeTLSv12ClientAuths'),
    'BkendTlSv11Renego' => array('label' => 'BkendTlSv11Renego'),
    'BkendTlSv12Renego' => array('label' => 'BkendTlSv12Renego'),
    'CryptoUtilization' => array('label' => 'CryptoUtilization'),
  )
);



$config['graph_types']['device']['netscalersvc_bits']['descr']     = 'Aggregate Service Traffic';
$config['graph_types']['device']['netscalersvc_pkts']['descr']     = 'Aggregate Service Packets';
$config['graph_types']['device']['netscalersvc_conns']['descr']    = 'Aggregate Service Connections';
$config['graph_types']['device']['netscalersvc_reqs']['descr']     = 'Aggregate Service Requests';

$config['graph_types']['device']['netscalervsvr_bits']['descr']    = 'Aggregate vServer Traffic';
$config['graph_types']['device']['netscalervsvr_pkts']['descr']    = 'Aggregate vServer Packets';
$config['graph_types']['device']['netscalervsvr_conns']['descr']   = 'Aggregate vServer Connections';
$config['graph_types']['device']['netscalervsvr_reqs']['descr']    = 'Aggregate vServer Requests';
$config['graph_types']['device']['netscalervsvr_hitmiss']['descr'] = 'Aggregate vServer Hits/Misses';

$config['graph_types']['device']['asyncos_workq']['section'] = 'appliance';
$config['graph_types']['device']['asyncos_workq']['order'] = '0';
$config['graph_types']['device']['asyncos_workq']['descr'] = 'Work Queue Messages';

$config['graph_types']['device']['smokeping_in_all'] = 'This is an aggregate graph of the incoming smokeping tests to this host. The line corresponds to the average RTT. The shaded area around each line denotes the standard deviation.';

$config['graph_types']['application']['unbound_queries']['long'] = 'DNS queries to the recursive resolver. The unwanted replies could be innocent duplicate packets, late replies, or spoof threats.';
$config['graph_types']['application']['unbound_queue']['long']   = 'The queries that did not hit the cache and need recursion service take up space in the requestlist. If there are too many queries, first queries get overwritten, and at last resort dropped.';
$config['graph_types']['application']['unbound_memory']['long']  = 'The memory used by unbound.';
$config['graph_types']['application']['unbound_qtype']['long']   = 'Queries by DNS RR type queried for.';
$config['graph_types']['application']['unbound_class']['long']   = 'Queries by DNS RR class queried for.';
$config['graph_types']['application']['unbound_opcode']['long']  = 'Queries by DNS opcode in the query packet.';
$config['graph_types']['application']['unbound_rcode']['long']   = 'Answers sorted by return value. RRSets bogus is the number of RRSets marked bogus per second by the validator.';
$config['graph_types']['application']['unbound_flags']['long']   = 'This graphs plots the flags inside incoming queries. For example, if QR, AA, TC, RA, Z flags are set, the query can be rejected. RD, AD, CD and DO are legitimately set by some software.';

$config['graph_types']['application']['bind_answers']['descr'] = 'BIND Received Answers';
$config['graph_types']['application']['bind_query_in']['descr'] = 'BIND Incoming Queries';
$config['graph_types']['application']['bind_query_out']['descr'] = 'BIND Outgoing Queries';
$config['graph_types']['application']['bind_query_rejected']['descr'] = 'BIND Rejected Queries';
$config['graph_types']['application']['bind_req_in']['descr'] = 'BIND Incoming Requests';
$config['graph_types']['application']['bind_req_proto']['descr'] = 'BIND Request Protocol Details';
$config['graph_types']['application']['bind_resolv_dnssec']['descr'] = 'BIND DNSSEC Validation';
$config['graph_types']['application']['bind_resolv_errors']['descr'] = 'BIND Errors while Resolving';
$config['graph_types']['application']['bind_resolv_queries']['descr'] = 'BIND Resolving Queries';
$config['graph_types']['application']['bind_resolv_rtt']['descr'] = 'BIND Resolving RTT';
$config['graph_types']['application']['bind_updates']['descr'] = 'BIND Dynamic Updates';
$config['graph_types']['application']['bind_zone_maint']['descr'] = 'BIND Zone Maintenance';

$config['graph_types']['application']['openvpn_nclients']['descr'] = 'Connected Clients';
$config['graph_types']['application']['openvpn_bits']['descr'] = 'VPN Traffic';

// Generic Firewall Graphs

$config['graph_types']['device']['firewall_sessions_ipv4']['section']  = 'firewall';
$config['graph_types']['device']['firewall_sessions_ipv4']['order']    = '0';
$config['graph_types']['device']['firewall_sessions_ipv4']['descr']    = 'Firewall Sessions (IPv4)';

// IOS-XR BNG Subscribers

$config['graph_types']['device']['bng_active_sessions']['section']  = 'bng';
$config['graph_types']['device']['bng_active_sessions']['order']    = '0';
$config['graph_types']['device']['bng_active_sessions']['descr']    = 'BNG Active Subscribers';

// Bluecoat CAS Graphs
$config['graph_types']['device']['cas_files_scanned'] = array(
  'section'   => 'appliance',
  'descr'     => 'Files Scanned',
  'file'      => 'cas.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Files',
  'ds'        => array(
    'FilesScanned'        => array('descr' => 'Files Scanned', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['cas_virus_detected'] = array(
  'section'   => 'appliance',
  'descr'     => 'Viruses Detected',
  'file'      => 'cas.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Viruses',
  'ds'        => array(
    'VirusesDetected'     => array('descr' => 'Viruses Detected', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['cas_slow_icap'] = array(
  'section'   => 'appliance',
  'descr'     => 'Slow ICAP Connections',
  'file'      => 'cas.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Conns',
  'ds'        => array(
    'SlowICAPConnections' => array('descr' => 'Slow ICAP Connections', 'ds_type' => 'GAUGE', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['cas_icap_scanned'] = array(
  'section'   => 'appliance',
  'descr'     => 'ICAP Files Scanned',
  'file'      => 'cas.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Files',
  'ds'        => array(
    'ICAPFilesScanned' => array('descr' => 'ICAP Files Scanned', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['cas_icap_virus'] = array(
  'section'   => 'appliance',
  'descr'     => 'ICAP Viruses Detected',
  'file'      => 'cas.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Viruses',
  'ds'        => array(
    'ICAPVirusesDetected' => array('descr' => 'ICAP Viruses Detected', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['cas_sicap_scanned'] = array(
  'section'   => 'appliance',
  'descr'     => 'Secure ICAP Files Scanned',
  'file'      => 'cas.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Files',
  'ds'        => array(
    'SecureICAPFilesScan' => array('descr' => 'Secure ICAP Files Scanned', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['cas_sicap_virus'] = array(
  'section'   => 'appliance',
  'descr'     => 'Secure ICAP Viruses Detected',
  'file'      => 'cas.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Viruses',
  'ds'        => array(
    'SecureICAPVirusesDe' => array('descr' => 'Secure ICAP Viruses Detected', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

// Bluecoat ProxyAV Graphs
$config['graph_types']['device']['files_scanned'] = array(
  'section'   => 'appliance',
  'descr'     => 'Files Scanned',
  'file'      => 'proxyav.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Files',
  'ds'        => array(
    'FilesScanned'        => array('descr' => 'Files Scanned', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['virus_detected'] = array(
  'section'   => 'appliance',
  'descr'     => 'Viruses Detected',
  'file'      => 'proxyav.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Viruses',
  'ds'        => array(
    'VirusesDetected'     => array('descr' => 'Viruses Detected', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['slow_icap'] = array(
  'section'   => 'appliance',
  'descr'     => 'Slow ICAP Connections',
  'file'      => 'proxyav.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Conns',
  'ds'        => array(
    'SlowICAPConnections' => array('descr' => 'Slow ICAP Connections', 'ds_type' => 'GAUGE', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['icap_scanned'] = array(
  'section'   => 'appliance',
  'descr'     => 'ICAP Files Scanned',
  'file'      => 'proxyav.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Files',
  'ds'        => array(
    'ICAPFilesScanned' => array('descr' => 'ICAP Files Scanned', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['icap_virus'] = array(
  'section'   => 'appliance',
  'descr'     => 'ICAP Viruses Detected',
  'file'      => 'proxyav.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Viruses',
  'ds'        => array(
    'ICAPVirusesDetected' => array('descr' => 'ICAP Viruses Detected', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['sicap_scanned'] = array(
  'section'   => 'appliance',
  'descr'     => 'Secure ICAP Files Scanned',
  'file'      => 'proxyav.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Files',
  'ds'        => array(
    'SecureICAPFilesScan' => array('descr' => 'Secure ICAP Files Scanned', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

$config['graph_types']['device']['sicap_virus'] = array(
  'section'   => 'appliance',
  'descr'     => 'Secure ICAP Viruses Detected',
  'file'      => 'proxyav.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Viruses',
  'ds'        => array(
    'SecureICAPVirusesDe' => array('descr' => 'Secure ICAP Viruses Detected', 'ds_type' => 'COUNTER', 'ds_min' => '0'),
  )
);

// Blue Coat ProxySG graphs
$config['graph_types']['device']['bluecoat_http_client']['section']  = 'proxysg';
$config['graph_types']['device']['bluecoat_http_client']['order']    = '0';
$config['graph_types']['device']['bluecoat_http_client']['descr']    = 'HTTP Client Connections';
$config['graph_types']['device']['bluecoat_http_server']['section']  = 'proxysg';
$config['graph_types']['device']['bluecoat_http_server']['order']    = '0';
$config['graph_types']['device']['bluecoat_http_server']['descr']    = 'HTTP Server Connections';
$config['graph_types']['device']['bluecoat_cache']['section']  = 'proxysg';
$config['graph_types']['device']['bluecoat_cache']['order']    = '0';
$config['graph_types']['device']['bluecoat_cache']['descr']    = 'HTTP Cache Stats';
$config['graph_types']['device']['bluecoat_server']['section']  = 'proxysg';
$config['graph_types']['device']['bluecoat_server']['order']    = '0';
$config['graph_types']['device']['bluecoat_server']['descr']    = 'Server Stats';
$config['graph_types']['device']['bluecoat_tcp']['section']  = 'proxysg';
$config['graph_types']['device']['bluecoat_tcp']['order']    = '0';
$config['graph_types']['device']['bluecoat_tcp']['descr']    = 'TCP Connections';
$config['graph_types']['device']['bluecoat_tcp_est']['section']  = 'proxysg';
$config['graph_types']['device']['bluecoat_tcp_est']['order']    = '0';
$config['graph_types']['device']['bluecoat_tcp_est']['descr']    = 'TCP Established Sessions';

// EDAC agent script
$config['graph_types']['device']['edac_errors']['section'] = 'system';
$config['graph_types']['device']['edac_errors']['order']   = '0';
$config['graph_types']['device']['edac_errors']['descr']   = 'EDAC Memory Errors';
$config['graph_types']['device']['edac_errors']['long']    = 'This graphs plots the number of errors (corrected and uncorrected) detected by the memory controller since the system startup.';

//FIXME. Sensors descriptions same as in nicecase(), but nicecase loads after definitions
// Device - Sensors section
$config['graph_types']['device']['temperature']['descr']     = 'Temperature';
$config['graph_types']['device']['humidity']['descr']        = 'Humidity';
$config['graph_types']['device']['fanspeed']['descr']        = 'Fanspeed';
$config['graph_types']['device']['airflow']['descr']         = 'Airflow';
$config['graph_types']['device']['waterflow']['descr']       = 'Waterflow';
$config['graph_types']['device']['voltage']['descr']         = 'Voltage';
$config['graph_types']['device']['current']['descr']         = 'Current';
$config['graph_types']['device']['power']['descr']           = 'Power';
$config['graph_types']['device']['apower']['descr']          = 'Apparent Power';
$config['graph_types']['device']['rpower']['descr']          = 'Reactive Power';
$config['graph_types']['device']['impedance']['descr']       = 'Impedance';
$config['graph_types']['device']['frequency']['descr']       = 'Frequency';
$config['graph_types']['device']['dbm']['descr']             = 'Signal dBm';
$config['graph_types']['device']['snr']['descr']             = 'Signal-to-Noise Ratio';
$config['graph_types']['device']['capacity']['descr']        = 'Capacity';
$config['graph_types']['device']['load']['descr']            = 'Load';
$config['graph_types']['device']['runtime']['descr']         = 'Runtime';
$config['graph_types']['device']['resistance']['descr']      = 'Resistance';
$config['graph_types']['device']['printersupplies']['descr'] = 'Printer Supplies';
$config['graph_types']['device']['status']['descr']          = 'Status Indicators';

$config['graph_types']['device']['arubacontroller_numaps']['descr']   = 'Number of APs';
$config['graph_types']['device']['arubacontroller_numaps']['section'] = 'wireless';
$config['graph_types']['device']['arubacontroller_numclients']['descr']   = 'Wireless clients';
$config['graph_types']['device']['arubacontroller_numclients']['section'] = 'wireless';

// Fireeye Active VMs
$config['graph_types']['device']['fe_active_vms'] = array(
  'section'   => 'appliance',
  'descr'     => 'Active VMs',
  'file'      => 'fireeye_activevms.rrd',
  'colours'   => 'blues',
  'unit_text' => 'VMs',
  'ds'        => array(
    'vms'     => array('label' => 'Current', 'draw' => 'LINE'),
  )
);

// F5 Client SSL Graphs
$config['graph_types']['device']['f5_clientssl_conns'] = array(
  'section'   => 'f5_ssl',
  'descr'     => 'Current ClientSSL Connections',
  'file'      => 'clientssl.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Connections',
  'ds'        => array(
    //'CurConns'       => array('label' => 'Current', 'draw' => 'LINE'),
    'TotNativeConns' => array('label' => 'Native', 'draw' => 'LINE'),
    'TotCompatConns' => array('label' => 'Compat', 'draw' => 'LINE'),
  )
);

$config['graph_types']['device']['f5_clientssl_vers'] = array(
  'section'   => 'f5_ssl',
  'descr'     => 'ClientSSL Versions per Second',
  'file'      => 'clientssl.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Versions',
  'ds'        => array(
    'Sslv2'  => array('label' => 'SSLv2', 'draw' => 'LINE'),
    'Sslv3'  => array('label' => 'SSLv3', 'draw' => 'LINE'),
    'Tlsv1'  => array('label' => 'TLSv1', 'draw' => 'LINE'),
    'Tlsv11' => array('label' => 'TLSv1.1', 'draw' => 'LINE'),
    'Tlsv12' => array('label' => 'TLSv1.2', 'draw' => 'LINE'),
    'Dtlsv1' => array('label' => 'DTLSv1', 'draw' => 'LINE'),
  )
);

// MIKROTIK-MIB

$config['graph_types']['device']['dhcp_leases'] = array(
  'section'   => 'dhcp',
  'descr'     => 'DHCP Leases',
  'file'      => 'mtxrDHCP.rrd',
  'colours'   => 'mixed',
  'unit_text' => 'Leases',
  'scale_min' =>  0,
  'ds'        => array(
    'mtxrDHCPLeaseCount' => array('label' => 'DHCP leases', 'draw' => 'AREA', 'line' => TRUE),
  )
);

// Indexed graphs

// BGP

$config['graph_types']['bgp']['prefixes'] = array(
  'descr'     => 'Prefixes',
  'file'      => 'cbgp-index.rrd',
  'index'     => TRUE,
  'colours'   => 'blues',
  'scale_min' => -0.1,
  'num_fmt'   => '6.0',
  //'no_mag'    => TRUE,
  'unit_text' => 'Prefixes', // unit_text and other variables sets in graphs/device/definition.inc.php
  'ds'        => array(
    'AcceptedPrefixes'    => array('label' => 'Accepted',   'draw' => 'AREA', 'line' => TRUE, 'colour' => '00CC0010'),
    'DeniedPrefixes'      => array('label' => 'Denied',     'draw' => 'LINE1.25', 'colour' => 'FF0000'),
    'AdvertisedPrefixes'  => array('label' => 'Advertised', 'draw' => 'LINE1.25'),
    'SuppressedPrefixes'  => array('label' => 'Suppressed', 'draw' => 'LINE1.25'),
    'WithdrawnPrefixes'   => array('label' => 'Withdrawn',  'draw' => 'LINE1.25'),
  )
);

// Generic Graph

$config['graph_types']['sla']['graph'] = array(
  //'section'   => 'sla',
  'descr'     => 'SLA');

// SLAs
$config['graph_types']['sla']['echo'] = array(
  //'section'   => 'sla',
  'descr'     => 'SLA',
  'file'      => 'sla-index.rrd',
  'index'     => TRUE,
  'colours'   => 'greens',
  'scale_min' => -0.5,
  'no_mag'    => TRUE,
  //'unit_text' => 'SLA', // unit_text and other variables sets in graphs/device/definition.inc.php
  'ds'        => array(
    'rtt'       => array('label' => 'Median RTT:', 'unit' => 'ms', 'num_fmt' => 4.1, 'draw' => 'LINE2'),
  )
);

/// FIXME. This is too hard graph, I left graph file include for this type (graphs/device/sla_jitter.inc.php)
/*
$config['graph_types']['sla']['jitter'] = array(
  //'section'   => 'sla',
  'descr'     => 'SLA Jitter',
  'file'      => 'sla_jitter-index.rrd',
  'index'     => TRUE,
  'colours'   => 'blues',
  'scale_min' => -0.5,
  'no_mag'    => TRUE,
  //'unit_text' => 'SLA', // unit_text and other variables sets in graphs/device/definition.inc.php
  'ds'        => array(
    'rtt'         => array('label' => 'Median RTT:', 'unit' => 'ms', 'num_fmt' => 4.1, 'draw' => 'LINE2'),
    'rtt_count'   => array('descr' => 'Complete packets count', 'graph' => FALSE, 'cdef' => 'rtt_success,rtt_loss,+'),
    'ploss'       => array('descr' => 'Percent of lost packets', 'graph' => FALSE, 'cdef' => 'rtt_loss,UNKN,EQ,1,rtt_loss,IF,rtt_count,/,100,*,CEIL'),
    //'rtt'       => array('label' => 'Median RTT:', 'unit' => 'ms', 'num_fmt' => 4.1, 'draw' => 'LINE2'),
    'rtt_minimum' => array('label' => 'RTT minimal', 'cdef' => 'rtt_minimum,rtt,-', 'draw' => 'AREASTACK', 'rra_min' => FALSE, 'rra_max' => FALSE),
    'rtt_maximum' => array('label' => 'RTT maximal', 'cdef' => 'rtt_maximum,rtt,-', 'draw' => 'AREASTACK', 'rra_min' => FALSE, 'rra_max' => FALSE),
    'rtt_success' => array('label' => 'RTT success', 'draw' => 'LINE1.5'),
    'rtt_loss'    => array('label' => 'RTT loss',    'draw' => 'LINE1.5'),
  )
);
*/

$config['graph_types']['pseudowire']['uptime'] = array(
  //'section'   => 'system',
  'descr'     => 'Pseudowire Uptime',
  'file'      => 'pseudowire-index.rrd',
  'index'     => TRUE,
  'unit_text' => ' ',
  'ds'        => array(
    'Uptime' => array('label' => 'Days Uptime', 'draw' => 'AREA', 'line' => TRUE, 'colour' => 'c5c5c5', 'cdef' => 'Uptime,86400,/', 'rra_min' => FALSE, 'rra_max' => FALSE)
  )
);

$config['graph_types']['device']['pcoip-net-packets'] = array(
  'section'=>'pcoip',
  'descr'     => 'Teradici PCoIP Network Statistics',
  'file'      => 'teradici-pcoipv2-mib_pcoipgenstatstable.rrd',
  'unit_text' => '',
  'ds'        => array(
    'PacketsSent' => array('label' => 'Packets sent'),
    'BytesSent' => array('label' => 'Bytes sent'),
    'PacketsReceived' => array('label' => 'Packets received'),
    'BytesReceived' => array('label' => 'Bytes received'),
    'TxPacketsLost' => array('label' => 'Packets lost'),
  )
);

$config['graph_types']['device']['pcoip-net-latency'] = array(
  'section'=>'pcoip',
  'descr'     => 'Teradici PCoIP Network Latency',
  'file'      => 'teradici-pcoipv2-mib_pcoipnetstatstable.rrd',
  'unit_text' => 'ms',
  'ds'        => array(
    'RoundTripLatencyMs' => array('label' => 'latency', 'ds_type' => 'GAUGE'),
  )
);

$config['graph_types']['device']['pcoip-net-bits'] = array(
  'section'=>'pcoip',
  'descr'     => 'Teradici PCoIP Network Packets',
  'file'      => 'teradici-pcoipv2-mib_pcoipnetstatstable.rrd',
  'unit_text' => 'kbit/s',
  'ds'        => array(
    'RXBWkbitPersec' => array('label' => 'received'),
    'TXBWkbitPersec' => array('label' => 'transmitted'),
      )
);

$config['graph_types']['device']['pcoip-image-quality'] = array(
  'section'=>'pcoip',
  'descr'     => 'Teradici PCoIP Image Quality',
  'file'      => 'teradici-pcoipv2-mib_pcoipimagingstatstable.rrd',
  'unit_text' => ' ',
  'ds'        => array(
    'ActiveMinimumQualit' => array('label' => 'Active Quality', 'ds_type' => 'GAUGE', 'ds_max'=>'100'),
  )
);

$config['graph_types']['device']['pcoip-image-fps'] = array(
  'section'=>'pcoip',
  'descr'     => 'Teradici PCoIP FPS',
  'file'      => 'teradici-pcoipv2-mib_pcoipimagingstatstable.rrd',
  'unit_text' => ' ',
  'ds'        => array(
    'EncodedFramesPersec' => array('label' => 'fps', 'ds_type' => 'GAUGE', 'ds_max'=>'2400'),
  )
);

$config['graph_types']['device']['pcoip-image-pipeline'] = array(
  'section'=>'pcoip',
  'descr'     => 'Teradici PCoIP Pipeline usage',
  'file'      => 'teradici-pcoipv2-mib_pcoipimagingstatstable.rrd',
  'unit_text' => 'Percent',
  'ds'        => array(
    'PipelineProcRate' => array('label' => 'utilization', 'ds_type' => 'GAUGE', 'ds_max'=>'300', 'ds_min'=>0),
  )
);

$config['graph_types']['device']['pcoip-audio-stats'] = array(
  'section'=>'pcoip',
  'descr'     => 'Teradici PCoIP Audio Statistics',
  'unit_text' => 'kbit/s',
  'file'      => 'teradici-pcoipv2-mib_pcoipaudiostatstable.rrd',
  'ds'        => array(
    'RXBWkbitPersec' => array('label' => 'received'),
    'TXBWkbitPersec' => array('label' => 'transmitted'),
  )
);



// EOF
