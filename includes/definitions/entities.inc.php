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

/////////////////////////////////////////////////////////
//  NO CHANGES TO THIS FILE, IT IS NOT USER-EDITABLE   //
/////////////////////////////////////////////////////////
//               YES, THAT MEANS YOU                   //
/////////////////////////////////////////////////////////

/**
 * Notes about 'entities' definitions.
 *
 * $entity - is main Entity name.
 *
 * $config['entities'][$entity]['table']                         (string) main table name
 * $config['entities'][$entity]['table_fields']                  (array)  Fields name and definitions for main table:
 *                                                                 keys:  'id', 'device_id', 'index', 'mib',
 *                                                                        'name', 'shortname', 'descr',
 *                                                                        'ignore', 'disable', 'deleted',
 *                                                                        'limit_high', 'limit_high_warn', 'limit_low', 'limit_low_warn'
 *   $config['entities'][$entity]['table_fields']['id']          (string) ID column
 *   $config['entities'][$entity]['table_fields']['device_id']   (string) Device ID column (if it exist for table)
 *   $config['entities'][$entity]['table_fields']['index']       (string) SNMP index column
 *   $config['entities'][$entity]['table_fields']['mib']         (string) SNMP MIB column
 *   $config['entities'][$entity]['table_fields']['name']        (string) Entity name column
 *   $config['entities'][$entity]['table_fields']['shortname']   (string) Entity short name column
 *   $config['entities'][$entity]['table_fields']['descr']       (string) Entity description column
 *   $config['entities'][$entity]['table_fields']['ignore']      (string) Column with Ignore entity bit
 *   $config['entities'][$entity]['table_fields']['disable']     (string) Column with Disabled entity bit
 *   $config['entities'][$entity]['table_fields']['deleted']     (string) Column with Deleted entity bit
 *   $config['entities'][$entity]['table_fields']['limit_high']  (string) Column with High limit for entity value
 *   $config['entities'][$entity]['table_fields']['limit_high_warn'] (string) Column with High warning limit for entity value
 *   $config['entities'][$entity]['table_fields']['limit_low']   (string) Column with Low limit for entity value
 *   $config['entities'][$entity]['table_fields']['limit_low_warn']  (string) Column with Low warning limit for entity value
 *
 * $config['entities'][$entity]['state_table']                   (string) states table name
 * $config['entities'][$entity]['state_fields']                  (array)  Fields name and definitions for state table:
 *                                                                 keys:  'value', 'status', 'event', 'uptime', 'last_change'
 *   $config['entities'][$entity]['state_fields']['value']       (string) Column with value
 *   $config['entities'][$entity]['state_fields']['status']      (string) Column with entity status (mib/os specific)
 *   $config['entities'][$entity]['state_fields']['event']       (string) Column with standard event ('ok', 'warning', 'alert', 'ignore')
 *   $config['entities'][$entity]['state_fields']['uptime']      (string) Column with entity uptime
 *   $config['entities'][$entity]['state_fields']['last_change'] (string) Column with last changed time (unixtime)
 *
 * $config['entities'][$entity]['hide']                          (bool)   These are invisible, only exist to allow us to get fancy links and popups
 * $config['entities'][$entity]['humanize_function']             (string) name of defined humanize function
 * $config['entities'][$entity]['parent_type']                   (string) FIXME
 * $config['entities'][$entity]['where']                         (string) FIXME
 * $config['entities'][$entity]['icon']                          (string) default icon name
 * $config['entities'][$entity]['graph']                         (array)  default graph type and id param for use in graph popups and list
 * $config['entities'][$entity]['agg_graphs']                    (array)  FIXME
 * $config['entities'][$entity]['metric_graphs']                 (array)  FIXME
 *
 */

// Tables to clean up when deleting a entities
$config['entity_tables'] = array('entity_permissions', 'entity_attribs', 'bill_entities');

$config['entity_default']['icon'] = $config['icon']['shutdown'];

// Common (known) entity events
$config['entity_events'] = array(
  'ok'      => array('event_descr'  => 'Normal state',
                     //'event_icon'   => '',
                     'event_class'  => 'label label-success',
                     'row_class'    => 'up',
                     'severity'     => 'notification'),
  'warning' => array('event_descr'  => 'Warning state',
                     //'event_icon'   => '',
                     'event_class'  => 'label label-warning',
                     'row_class'    => 'warning',
                     'severity'     => 'warning'),
  'alert'   => array('event_descr'  => 'Critical state',
                     //'event_icon'   => '',
                     'event_class'  => 'label label-important',
                     'row_class'    => 'error',
                     'severity'     => 'error'),
  'ignore'  => array('event_descr'  => 'Abnormal state (ignored)',
                     //'event_icon'   => '',
                     'event_class'  => 'label',
                     'row_class'    => 'disabled',
                     'severity'     => 'warning'),
  'exclude' => array('event_descr'  => 'Excluded',
                     //'event_icon'   => '',
                     'event_class'  => 'label label-suppressed',
                     'row_class'    => 'ignore',
                     'severity'     => 'debug'),
);
//FIXME, somewere still used 'up' event
$config['entity_events']['up'] = $config['entity_events']['ok'];

// Definitions related to various entities known by Observium

$entity = 'device';
// Main table & field
$config['entities'][$entity]['table']                           = "devices";
$config['entities'][$entity]['table_fields']['id']              = "device_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['name']            = "hostname";
$config['entities'][$entity]['table_fields']['ignore']          = "ignore";
$config['entities'][$entity]['table_fields']['disable']         = "disabled";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['device'];
$config['entities'][$entity]['graph']                           = array('type' => 'device_ping', 'device' => '@device_id'); // Graph should be done per metric

$config['entities'][$entity]['attribs']['hostname']             = array('label' => 'Hostname',		    'descr' => 'Device Hostname',		'type' => 'string');
$config['entities'][$entity]['attribs']['os']                   = array('label' => 'Operating System',	'descr' => 'Device Operating System',	'type' => 'string', 'values' => 'os');
$config['entities'][$entity]['attribs']['type']                 = array('label' => 'Type',		        'descr' => 'Device Type', 		'type' => 'string', 'values' => 'device_type');
$config['entities'][$entity]['attribs']['hardware']             = array('label' => 'Hardware',		'descr' => 'Device hardware string', 	'type' => 'string');
$config['entities'][$entity]['attribs']['serial']               = array('label' => 'Serial', 		'descr' => 'Device serial number', 	'type' => 'string');
$config['entities'][$entity]['attribs']['purpose']              = array('label' => 'Purpose', 		'descr' => 'Device purpose', 		'type' => 'string');
$config['entities'][$entity]['attribs']['sysname']              = array('label' => 'sysName', 		'descr' => 'Device SNMP sysName', 	'type' => 'string');
$config['entities'][$entity]['attribs']['sysdescr']             = array('label' => 'sysDescr', 		'descr' => 'Device SNMP sysDescr',	'type' => 'string');
$config['entities'][$entity]['attribs']['sysobjectid']          = array('label' => 'sysObjectID', 	'descr' => 'Device sysObjectID', 	'type' => 'string');
$config['entities'][$entity]['attribs']['syscontact']           = array('label' => 'sysContact', 	'descr' => 'Device SNMP sysContact', 	'type' => 'string');
$config['entities'][$entity]['attribs']['group_id']             = array('label' => 'Group', 		'descr' => 'Group Membership', 		'type' => 'string', 'values' => 'group');
$config['entities'][$entity]['attribs']['location_id']          = array('label' => 'Location', 		'descr' => 'Location', 			'type' => 'string', 'values' => 'location');
$config['entities'][$entity]['attribs']['location']             = array('label' => 'Location (Text)', 	'descr' => 'Location', 			'type' => 'string');

$config['entities'][$entity]['metrics']['device_status']        = array('label' => 'Device Status',        'type' => 'boolean');
$config['entities'][$entity]['metrics']['device_status_type']   = array('label' => 'Device Status Type',   'type' => 'string');
$config['entities'][$entity]['metrics']['device_ping']          = array('label' => 'Device PING RTT',      'type' => 'integer');
$config['entities'][$entity]['metrics']['device_status']        = array('label' => 'Device SNMP RTT',      'type' => 'integer');
$config['entities'][$entity]['metrics']['device_uptime']        = array('label' => 'Device Uptime',        'type' => 'integer');
$config['entities'][$entity]['metrics']['device_rebooted']      = array('label' => 'Device Rebooted',      'type' => 'boolean');
$config['entities'][$entity]['metrics']['device_duration_poll'] = array('label' => 'Device Poll Duration', 'type' => 'integer');

$entity = 'mempool';
// Main table & field
$config['entities'][$entity]['table']                           = "mempools";
$config['entities'][$entity]['table_fields']['id']              = "mempool_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "mempool_index";
$config['entities'][$entity]['table_fields']['mib']             = "mempool_mib";
$config['entities'][$entity]['table_fields']['name']            = "mempool_descr";
$config['entities'][$entity]['table_fields']['ignore']          = "mempool_ignore";
$config['entities'][$entity]['table_fields']['deleted']         = "mempool_deleted";
$config['entities'][$entity]['table_fields']['limit_high']      = "mempool_crit_limit";
$config['entities'][$entity]['table_fields']['limit_high_warn'] = "mempool_warn_limit";
// State table & fields
//$config['entities'][$entity]['state_table']                     = "mempools-state";
$config['entities'][$entity]['state_fields']['value']           = "mempool_perc";
//$config['entities'][$entity]['state_fields']['status']          = "mempool_status";
//$config['entities'][$entity]['state_fields']['event']           = "mempool_event";
//$config['entities'][$entity]['state_fields']['uptime']          = "mempool_unixtime";
//$config['entities'][$entity]['state_fields']['last_change']     = "mempool_last_change";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['mempool'];
$config['entities'][$entity]['graph']                           = array('type' => 'mempool_usage', 'id' => '@mempool_id');
$config['entities'][$entity]['agg_graphs']['usage']             = array('name' => 'Usage');

$config['entities'][$entity]['attribs']['mempool_descr']	    = array('label' => 'Description',   'descr' => 'Memory Pool Description',	'type' => 'string');
$config['entities'][$entity]['attribs']['mempool_mib']		    = array('label' => 'MIB',           'descr' => 'Memory Pool MIB',		    'type' => 'string');

$config['entities'][$entity]['metrics']['mempool_free']      = array('label' => 'Memory Free (B)',      'type' => 'integer');
$config['entities'][$entity]['metrics']['mempool_used']      = array('label' => 'Memory Used (B)',      'type' => 'integer');
$config['entities'][$entity]['metrics']['mempool_perc']      = array('label' => 'Memory Percent Used',  'type' => 'integer');


$entity = 'p2pradio';
// Main table & field
$config['entities'][$entity]['table']                           = "p2p_radios";
$config['entities'][$entity]['table_fields']['id']              = "radio_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "radio_index";
$config['entities'][$entity]['table_fields']['mib']             = "radio_mib";
$config['entities'][$entity]['table_fields']['name']            = "radio_name";
//$config['entities'][$entity]['table_fields']['ignore']          = "radio_ignore"; // not exist
$config['entities'][$entity]['table_fields']['deleted']         = "deleted";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['p2pradio'];



$entity = 'sensor';
// Main table & field
$config['entities'][$entity]['table']                           = "sensors";
$config['entities'][$entity]['table_fields']['id']              = "sensor_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "sensor_index";
$config['entities'][$entity]['table_fields']['mib']             = "sensor_mib";
$config['entities'][$entity]['table_fields']['name']            = "sensor_descr";
$config['entities'][$entity]['table_fields']['ignore']          = "sensor_ignore";
$config['entities'][$entity]['table_fields']['disable']         = "sensor_disable";
$config['entities'][$entity]['table_fields']['deleted']         = "sensor_deleted";
$config['entities'][$entity]['table_fields']['limit_high']      = "sensor_limit";
$config['entities'][$entity]['table_fields']['limit_high_warn'] = "sensor_limit_warn";
$config['entities'][$entity]['table_fields']['limit_low']       = "sensor_limit_low";
$config['entities'][$entity]['table_fields']['limit_low_warn']  = "sensor_limit_low_warn";
$config['entities'][$entity]['table_fields']['value']           = "sensor_value";
$config['entities'][$entity]['table_fields']['status']          = "sensor_status";
$config['entities'][$entity]['table_fields']['event']           = "sensor_event";
$config['entities'][$entity]['state_fields']['uptime']          = "sensor_polled";
$config['entities'][$entity]['state_fields']['last_change']     = "sensor_last_change";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['sensor'];
$config['entities'][$entity]['graph']                           = array('type' => 'sensor_graph', 'id' => '@sensor_id');
$config['entities'][$entity]['agg_graphs']['graph']             = array('name' => 'Line Graph');
$config['entities'][$entity]['agg_graphs']['stacked']           = array('name' => 'Stacked Graph');
// Attributes
$config['entities'][$entity]['attribs']['sensor_descr']         = array('label' => 'Description',       'descr' => 'Sensor Description',   'type' => 'string');
$config['entities'][$entity]['attribs']['sensor_class']         = array('label' => 'Class',             'descr' => 'Sensor Class',         'type' => 'string', 'values' => 'sensor_class');
$config['entities'][$entity]['attribs']['sensor_type']          = array('label' => 'Type',              'descr' => 'Sensor Type',   'type' => 'string');
$config['entities'][$entity]['attribs']['sensor_index']         = array('label' => 'Index',		'descr' => 'Sensor Index',   'type' => 'string');
$config['entities'][$entity]['attribs']['sensor_oid']           = array('label' => 'OID',		'descr' => 'Sensor OID',   'type' => 'string');
$config['entities'][$entity]['attribs']['poller_type']          = array('label' => 'Poller Type',       'descr' => 'Sensor Poller',   'type' => 'string', 'values' => array('snmp', 'agent', 'ipmi'));

$config['entities'][$entity]['metrics']['sensor_value']         = array('label' => 'Value',  'type' => 'integer');
$config['entities'][$entity]['metrics']['sensor_event']         = array('label' => 'Status Event',  'type' => 'string', 'values' => array ('ok', 'warning', 'alert', 'ignore'));


$entity = 'status';
// Main table & field
$config['entities'][$entity]['table']                           = "status";
$config['entities'][$entity]['table_fields']['id']              = "status_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "sensor_index";
$config['entities'][$entity]['table_fields']['mib']             = "sensor_mib";
$config['entities'][$entity]['table_fields']['name']            = "status_descr";
$config['entities'][$entity]['table_fields']['ignore']          = "status_ignore";
$config['entities'][$entity]['table_fields']['disable']         = "status_disable";
$config['entities'][$entity]['table_fields']['deleted']         = "status_deleted";
// State table & fields
//$config['entities'][$entity]['state_table']                     = "status-state";
$config['entities'][$entity]['state_fields']['value']           = "status_value";
$config['entities'][$entity]['state_fields']['status']          = "status_name";
$config['entities'][$entity]['state_fields']['event']           = "status_event";
//$config['entities'][$entity]['state_fields']['uptime']          = "status_polled";
$config['entities'][$entity]['state_fields']['last_change']     = "status_last_change";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['status'];;
$config['entities'][$entity]['graph']                           = array('type' => 'status_graph', 'id' => '@status_id');
$config['entities'][$entity]['agg_graphs']['graph']             = array('name' => 'Graph');
// Attributes
$config['entities'][$entity]['attribs']['status_descr']         = array('label' => 'Description',      'descr' => 'Status Description',      'type' => 'string');
$config['entities'][$entity]['attribs']['status_type']          = array('label' => 'Type',             'descr' => 'Status Type',             'type' => 'string');
$config['entities'][$entity]['attribs']['status_index']         = array('label' => 'Index',		       'descr' => 'Status Index',            'type' => 'string');
$config['entities'][$entity]['attribs']['entPhysicalClass']     = array('label' => 'entPhysicalClass', 'descr' => 'Status entPhysicalClass', 'type' => 'string');
$config['entities'][$entity]['attribs']['poller_type']          = array('label' => 'Poller Type',      'descr' => 'Status Poller',           'type' => 'string',  'values' => array('snmp', 'agent', 'ipmi'));
// Metrics
$config['entities'][$entity]['metrics']['status_name']          = array('label' => 'Name',          'type' => 'string');
$config['entities'][$entity]['metrics']['status_name_uptime']   = array('label' => 'Last Changed',  'type' => 'integer');
$config['entities'][$entity]['metrics']['status_event']         = array('label' => 'Event',         'type' => 'string', 'values' => array ('ok', 'warning', 'alert', 'ignore'));

$entity = 'storage';
// Main table & field
$config['entities'][$entity]['table']                           = "storage";
$config['entities'][$entity]['table_fields']['id']              = "storage_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "storage_index";
$config['entities'][$entity]['table_fields']['mib']             = "storage_mib";
$config['entities'][$entity]['table_fields']['name']            = "storage_descr";
$config['entities'][$entity]['table_fields']['ignore']          = "storage_ignore";
$config['entities'][$entity]['table_fields']['deleted']         = "storage_deleted";
$config['entities'][$entity]['table_fields']['limit_high']      = "storage_crit_limit";
$config['entities'][$entity]['table_fields']['limit_high_warn'] = "storage_warn_limit";
// State table & fields
//$config['entities'][$entity]['state_table']                     = "storage-state";
$config['entities'][$entity]['state_fields']['value']           = "storage_perc";
//$config['entities'][$entity]['state_fields']['status']          = "storage_status";
//$config['entities'][$entity]['state_fields']['event']           = "storage_event";
//$config['entities'][$entity]['state_fields']['uptime']          = "storage_polled";
//$config['entities'][$entity]['state_fields']['last_change']     = "storage_last_change";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['storage'];
$config['entities'][$entity]['graph']                           = array('type' => 'storage_usage', 'id' => '@storage_id');
$config['entities'][$entity]['agg_graphs']['usage']             = array('name' => 'Usage');

$config['entities'][$entity]['attribs']['storage_descr']	    = array('label' => 'Description',   'descr' => 'Storage Description',	'type' => 'string');
$config['entities'][$entity]['attribs']['storage_type']		    = array('label' => 'Type',          'descr' => 'Storage Type',		    'type' => 'string');
$config['entities'][$entity]['attribs']['storage_mib']		    = array('label' => 'MIB',           'descr' => 'Storage MIB',		    'type' => 'string');
$config['entities'][$entity]['attribs']['storage_index']		= array('label' => 'OID',           'descr' => 'Storage Index',		    'type' => 'string');

$config['entities'][$entity]['metrics']['storage_free']      = array('label' => 'Storage Free (B)',      'type' => 'integer');
$config['entities'][$entity]['metrics']['storage_used']      = array('label' => 'Storage Used (B)',      'type' => 'integer');
$config['entities'][$entity]['metrics']['storage_perc']      = array('label' => 'Storage Percent Used',  'type' => 'integer');

$entity = 'processor';
// Main table & field
$config['entities'][$entity]['table']                           = "processors";
$config['entities'][$entity]['table_fields']['id']              = "processor_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "processor_index";
//$config['entities'][$entity]['table_fields']['mib']             = "processor_type";
$config['entities'][$entity]['table_fields']['name']            = "processor_descr";
$config['entities'][$entity]['table_fields']['ignore']          = "processor_ignore";
//$config['entities'][$entity]['table_fields']['deleted']         = "processor_deleted";
$config['entities'][$entity]['table_fields']['limit_high']      = "processor_crit_limit";
$config['entities'][$entity]['table_fields']['limit_high_warn'] = "processor_warn_limit";
// State table & fields
//$config['entities'][$entity]['state_table']                     = "processors-state";
$config['entities'][$entity]['state_fields']['value']           = "processor_usage";
//$config['entities'][$entity]['state_fields']['status']          = "processor_status";
//$config['entities'][$entity]['state_fields']['event']           = "processor_event";
//$config['entities'][$entity]['state_fields']['uptime']          = "processor_polled";
//$config['entities'][$entity]['state_fields']['last_change']     = "processor_last_change";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['processor'];
$config['entities'][$entity]['graph']                           = array('type' => 'processor_usage', 'id' => '@processor_id');
$config['entities'][$entity]['agg_graphs']['usage']             = array('name' => 'Usage');

$config['entities'][$entity]['attribs']['processor_descr']	    = array('label' => 'Description',    'descr' => 'Processor Description',	'type' => 'string');
$config['entities'][$entity]['attribs']['processor_type']		= array('label' => 'Type',           'descr' => 'Processor Type',		    'type' => 'string');
$config['entities'][$entity]['attribs']['processor_oid']		= array('label' => 'OID',           'descr' => 'Processor OID',		    'type' => 'string');

$config['entities'][$entity]['metrics']['processor_usage']      = array('label' => 'Processor Usage (%)',      'type' => 'integer');


$entity = 'bgp_peer';
// Main table & field
$config['entities'][$entity]['table']                           = "bgpPeers";
$config['entities'][$entity]['table_fields']['id']              = "bgpPeer_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "bgpPeerRemoteAddr";
//$config['entities'][$entity]['table_fields']['mib']             = "bgpPeer_mib";
$config['entities'][$entity]['table_fields']['name']            = "bgpPeerRemoteAddr";
$config['entities'][$entity]['table_fields']['descr']           = "reverse_dns";
//$config['entities'][$entity]['table_fields']['ignore']          = "bgpPeer_ignore"; // not exist
// State table & fields
//$config['entities'][$entity]['state_table']                     = "bgpPeers-state";
//$config['entities'][$entity]['state_fields']['value']           = "bgpPeer_value";
//$config['entities'][$entity]['state_fields']['status']          = "bgpPeer_status";
//$config['entities'][$entity]['state_fields']['event']           = "bgpPeer_event";
//$config['entities'][$entity]['state_fields']['uptime']          = "bgpPeer_polled";
//$config['entities'][$entity]['state_fields']['last_change']     = "bgpPeer_last_change";
// Icon & graph
$config['entities'][$entity]['humanize_function']               = "humanize_bgp";
$config['entities'][$entity]['icon']                            = $config['icon']['bgp'];
$config['entities'][$entity]['graph']                           = array('type' => 'bgp_updates', 'id' => '@bgpPeer_id');

$config['entities'][$entity]['attribs']['as_text']	          = array('label' => 'Peer ASN Description',  'descr' => 'Peer ASN Description',	'type' => 'string');
$config['entities'][$entity]['attribs']['bgpPeerRemoteAs']	  = array('label' => 'Peer ASN',              'descr' => 'Peer ASN',	            'type' => 'string');
$config['entities'][$entity]['attribs']['bgpPeerRemoteAddr']  = array('label' => 'Peer Address',          'descr' => 'Peer Address',            'type' => 'string');
$config['entities'][$entity]['attribs']['bgpPeerIdentifier']  = array('label' => 'Peer BGP ID',           'descr' => 'Peer BGP ID',	            'type' => 'string');
$config['entities'][$entity]['attribs']['bgpPeerLocalAddr']   = array('label' => 'Session Local Address', 'descr' => 'Session Local Address',	'type' => 'string');
$config['entities'][$entity]['attribs']['peer_device_id']	  = array('label' => 'Peer device_id',        'descr' => 'Peer device_id',          'type' => 'integer');

$config['entities'][$entity]['metrics']['bgpPeerState']      = array('label' => 'Session State',          'type' => 'string', 'values' => array('idle', 'connect', 'active', 'opensent', 'openconfirm', 'established'));
$config['entities'][$entity]['metrics']['bgpPeerAdminStatus'] = array('label' => 'Session Admin Status',  'type' => 'string', 'values' => array('start', 'stop'));
$config['entities'][$entity]['metrics']['bgpPeerFsmEstablishedTime'] = array('label' => 'Session Uptime', 'type' => 'integer');


/// F5

$entity = 'f5-pool';
// Main table & field
$config['entities'][$entity]['table']                           = "lb_pools";
$config['entities'][$entity]['table_fields']['id']              = "pool_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['name']            = "pool_name";
//$config['entities'][$entity]['table_fields']['ignore']          = "pool_ignore";
$config['entities'][$entity]['icon']                            = $config['icon']['vserver'];
$config['entities'][$entity]['graph']                           = array('type' => 'lbpool_bits', 'id' => '@pool_id');

$config['entities'][$entity]['attribs']['pool_name']            = array('label' => 'Name',  'descr' => 'Pool Name', 'type' => 'string');
$config['entities'][$entity]['attribs']['pool_lb']              = array('label' => 'Type',  'descr' => 'Pool LB Type', 'type' => 'string');

//$config['entities'][$entity]['metrics']['pool_bps_in']          = array('label' => 'Incoming Traffic (bps)',  'type' => 'integer');
//$config['entities'][$entity]['metrics']['pool_bps_out']         = array('label' => 'Outgoing Traffic (bps)',  'type' => 'integer');
//$config['entities'][$entity]['metrics']['pool_health']          = array('label' => 'Active Pool Members (%)', 'type' => 'integer');

$entity = 'f5-virtual';
// Main table & field
$config['entities'][$entity]['table']                           = "lb_virtuals";
$config['entities'][$entity]['table_fields']['id']              = "virt_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['name']            = "virt_name";
$config['entities'][$entity]['icon']                            = $config['icon']['vserver'];
$config['entities'][$entity]['graph']                           = array('type' => 'lbvirt_bits', 'id' => '@virt_id');

$config['entities'][$entity]['attribs']['virt_name']            = array('label' => 'Name',  'descr' => 'Virtual Name', 'type' => 'string');
$config['entities'][$entity]['attribs']['virt_type']            = array('label' => 'Type',  'descr' => 'Virtual Type', 'type' => 'string');


/// Netscaler VServers
///
$entity = 'netscalervsvr';
// Main table & field
$config['entities'][$entity]['table']                           = "netscaler_vservers";
$config['entities'][$entity]['table_fields']['id']              = "vsvr_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
//$config['entities'][$entity]['table_fields']['index']           = "";
$config['entities'][$entity]['table_fields']['name']            = "vsvr_label";
$config['entities'][$entity]['table_fields']['descr']           = "vsvr_fullname";
$config['entities'][$entity]['table_fields']['ignore']          = "vsvr_ignore";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['vserver'];
$config['entities'][$entity]['graph']                           = array('type' => 'netscalervsvr_bits', 'id' => '@vsvr_id');

$config['entities'][$entity]['metrics']['vsvr_state']           = array('label' => 'State',              'type' => 'string', 'values' => array('up', 'down', 'outOfService'));
$config['entities'][$entity]['metrics']['vsvr_conn_client']     = array('label' => 'Client Connections', 'type' => 'integer');
$config['entities'][$entity]['metrics']['vsvr_conn_server']     = array('label' => 'Server Connections', 'type' => 'integer');
$config['entities'][$entity]['metrics']['vsvr_bps_in']          = array('label' => 'Incoming Traffic (bps)', 'type' => 'integer');
$config['entities'][$entity]['metrics']['vsvr_bps_out']         = array('label' => 'Outgoing Traffic (bps)', 'type' => 'integer');
$config['entities'][$entity]['metrics']['vsvr_health']          = array('label' => 'Services UP (%)', 'type' => 'integer');

$config['entities'][$entity]['attribs']['vsvr_name']       = array('label' => 'Name',  'descr' => 'VServer Name (deprecated)', 'type' => 'string');
$config['entities'][$entity]['attribs']['vsvr_fullname']   = array('label' => 'Full Name',  'descr' => 'VServer Full Name', 'type' => 'string');
$config['entities'][$entity]['attribs']['vsvr_label']      = array('label' => 'Label',  'descr' => 'VServer Label', 'type' => 'string');
$config['entities'][$entity]['attribs']['vsvr_ip']         = array('label' => 'IP Address',  'descr' => 'VServer IP Address', 'type' => 'string');
$config['entities'][$entity]['attribs']['vsvr_ipv6']       = array('label' => 'IPv6 Address',  'descr' => 'VServer IPv6 Address', 'type' => 'string');
$config['entities'][$entity]['attribs']['vsvr_port']       = array('label' => 'Port',  'descr' => 'VServer Port', 'type' => 'string');
$config['entities'][$entity]['attribs']['vsvr_type']       = array('label' => 'Type',  'descr' => 'VServer Type', 'type' => 'string');
$config['entities'][$entity]['attribs']['vsvr_entitytype'] = array('label' => 'Entity Type',  'descr' => 'VServer Entity Type', 'type' => 'string');



/// Netscaler Services
///
$entity = 'netscalersvc';
// Main table & field
$config['entities'][$entity]['table']                           = "netscaler_services";
$config['entities'][$entity]['table_fields']['id']              = "svc_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
//$config['entities'][$entity]['table_fields']['index']           = "";
$config['entities'][$entity]['table_fields']['name']            = "svc_label";
$config['entities'][$entity]['table_fields']['descr']           = "svc_fullname";
$config['entities'][$entity]['table_fields']['ignore']          = "svc_ignore";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['service'];
$config['entities'][$entity]['graph']                           = array('type' => 'netscalersvc_bits', 'id' => '@svc_id');

$config['entities'][$entity]['metrics']['svc_state']           = array('label' => 'State',              'type' => 'string', 'values' => array('up', 'down', 'outOfService'));
$config['entities'][$entity]['metrics']['svc_bps_in']          = array('label' => 'Incoming Traffic (bps)', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_bps_out']         = array('label' => 'Outgoing Traffic (bps)', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_conn_active']     = array('label' => 'Active Connections', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_trans_active']    = array('label' => 'Active Transactions', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_trans_avgtime']   = array('label' => 'Average Transaction Time in ms', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_svr_avgttfb']     = array('label' => 'Average Time-To-First-Byte in ms', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_conn_client']     = array('label' => 'Active Client Connections', 'type' => 'integer');

$config['entities'][$entity]['attribs']['svc_name']       = array('label' => 'Name',       'descr' => 'Service Name (deprecated)', 'type' => 'string');
$config['entities'][$entity]['attribs']['svc_fullname']   = array('label' => 'Full Name',  'descr' => 'Service Full Name',         'type' => 'string');
$config['entities'][$entity]['attribs']['svc_label']      = array('label' => 'Label',      'descr' => 'Service Label',             'type' => 'string');
$config['entities'][$entity]['attribs']['svc_ip']         = array('label' => 'IP Address', 'descr' => 'Service IP Address',        'type' => 'string');
$config['entities'][$entity]['attribs']['svc_port']       = array('label' => 'Port',       'descr' => 'Service Port',              'type' => 'string');
$config['entities'][$entity]['attribs']['svc_type']       = array('label' => 'Type',       'descr' => 'Service Type',              'type' => 'string');

/// Netscaler Service Group Members
///
$entity = 'netscalersvcgrpmem';
// Main table & field
$config['entities'][$entity]['table']                           = "netscaler_servicegroupmembers";
$config['entities'][$entity]['table_fields']['id']              = "svc_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
//$config['entities'][$entity]['table_fields']['index']           = "";
$config['entities'][$entity]['table_fields']['name']            = "svc_label";
$config['entities'][$entity]['table_fields']['descr']           = "svc_fullname";
$config['entities'][$entity]['table_fields']['ignore']          = "svc_ignore";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['servicegroup'];
$config['entities'][$entity]['graph']                           = array('type' => 'netscalersvcgrpmem_bits', 'id' => '@svc_id');

$config['entities'][$entity]['metric_graphs']['svc_conn_active']     = array('type' => 'netscalersvcgrpmem_conns', 'id' => '@svc_id');
$config['entities'][$entity]['metric_graphs']['svc_svr_avgttfb']     = array('type' => 'netscalersvcgrpmem_ttfb', 'id' => '@svc_id');
$config['entities'][$entity]['metric_graphs']['svc_conn_client']     = array('type' => 'netscalersvcgrpmem_conns', 'id' => '@svc_id');

$config['entities'][$entity]['metrics']['svc_state']           = array('label' => 'State',              'type' => 'string', 'values' => array('up', 'down', 'outOfService'));
$config['entities'][$entity]['metrics']['svc_bps_in']          = array('label' => 'Incoming Traffic (bps)', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_bps_out']         = array('label' => 'Outgoing Traffic (bps)', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_conn_active']     = array('label' => 'Active Connections', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_trans_active']    = array('label' => 'Active Transactions', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_trans_avgtime']   = array('label' => 'Average Transaction Time in ms', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_svr_avgttfb']     = array('label' => 'Average Time-To-First-Byte in ms', 'type' => 'integer');
$config['entities'][$entity]['metrics']['svc_conn_client']     = array('label' => 'Active Client Connections', 'type' => 'integer');

$config['entities'][$entity]['attribs']['svc_name']       = array('label' => 'Name',       'descr' => 'Service Group Member Name (deprecated)', 'type' => 'string');
$config['entities'][$entity]['attribs']['svc_fullname']   = array('label' => 'Full Name',  'descr' => 'Service Group Member Full Name',         'type' => 'string');
$config['entities'][$entity]['attribs']['svc_label']      = array('label' => 'Label',      'descr' => 'Service Group Member Label',             'type' => 'string');
$config['entities'][$entity]['attribs']['svc_ip']         = array('label' => 'IP Address', 'descr' => 'Service Group Member IP Address',        'type' => 'string');
$config['entities'][$entity]['attribs']['svc_port']       = array('label' => 'Port',       'descr' => 'Service Group Member Port',              'type' => 'string');
$config['entities'][$entity]['attribs']['svc_type']       = array('label' => 'Type',       'descr' => 'Service Group Member Type',              'type' => 'string');

$entity = 'printersupply';
// Main table & field
$config['entities'][$entity]['table']                           = "printersupplies";
$config['entities'][$entity]['table_fields']['id']              = "supply_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "supply_index";
$config['entities'][$entity]['table_fields']['mib']             = "supply_mib";
$config['entities'][$entity]['table_fields']['name']            = "supply_descr";
//$config['entities'][$entity]['table_fields']['ignore']          = "supply_ignore"; // not exist
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['printersupply'];
$config['entities'][$entity]['graph']                           = array('type' => 'printersupply_usage', 'id' => '@supply_id');

$config['entities'][$entity]['attribs']['supply_descr']      = array('label' => 'Name',   'descr' => 'Supply Name/Description',	'type' => 'string');
$config['entities'][$entity]['attribs']['supply_mib']        = array('label' => 'MIB',    'descr' => 'Supply MIB',	            'type' => 'string');
$config['entities'][$entity]['attribs']['supply_colour']     = array('label' => 'Colour', 'descr' => 'Supply Colour',	        'type' => 'string');
$config['entities'][$entity]['attribs']['supply_type']       = array('label' => 'Type',   'descr' => 'Supply Type',	            'type' => 'string');
$config['entities'][$entity]['attribs']['supply_oid']        = array('label' => 'OID',    'descr' => 'Supply OID',	        'type' => 'string');

$config['entities'][$entity]['metrics']['supply_value']      = array('label' => 'Supply Level (%)',      'type' => 'integer');

/// Ports
///
$entity = 'port';
// Main table & field
$config['entities'][$entity]['table']                           = "ports";
$config['entities'][$entity]['table_fields']['id']              = "port_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "ifIndex";
//$config['entities'][$entity]['table_fields']['mib']             = "port_mib";
$config['entities'][$entity]['table_fields']['name']            = "port_label";
$config['entities'][$entity]['table_fields']['shortname']       = "port_label_short";
$config['entities'][$entity]['table_fields']['descr']           = "ifAlias";
$config['entities'][$entity]['table_fields']['ignore']          = "ignore";
$config['entities'][$entity]['table_fields']['disable']         = "disable";
$config['entities'][$entity]['table_fields']['deleted']         = "deleted";
// State table & fields
//$config['entities'][$entity]['state_table']                     = "ports-state";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['port'];
$config['entities'][$entity]['graph']                           = array('type' => 'port_bits', 'id' => '@port_id');
$config['entities'][$entity]['agg_graphs']['bits']              = array('name' => 'Bits');
$config['entities'][$entity]['agg_graphs']['upkts']             = array('name' => 'Unicast Packets');
$config['entities'][$entity]['agg_graphs']['mcastpkts']         = array('name' => 'Multicast Packets');
$config['entities'][$entity]['agg_graphs']['bcastpkts']         = array('name' => 'Broadcast Packets');
$config['entities'][$entity]['agg_graphs']['errors']            = array('name' => 'Errors');
$config['entities'][$entity]['agg_graphs']['discards']          = array('name' => 'Discards');

$config['entities'][$entity]['metric_graphs']['ifInOctets_perc']     = array('type' => 'port_percent', 'id' => '@port_id');
$config['entities'][$entity]['metric_graphs']['ifOutOctets_perc']    = array('type' => 'port_percent', 'id' => '@port_id');
$config['entities'][$entity]['metric_graphs']['ifInErrors_rate']     = array('type' => 'port_errors',  'id' => '@port_id');
$config['entities'][$entity]['metric_graphs']['ifOutErrors_rate']    = array('type' => 'port_errors',  'id' => '@port_id');
$config['entities'][$entity]['metric_graphs']['ifInUcastPkts_rate']  = array('type' => 'port_pkts',    'id' => '@port_id');
$config['entities'][$entity]['metric_graphs']['ifOutUcastPkts_rate'] = array('type' => 'port_pkts',    'id' => '@port_id');
$config['entities'][$entity]['metric_graphs']['rx_ave_pktsize']      = array('type' => 'port_pktsize', 'id' => '@port_id');
$config['entities'][$entity]['metric_graphs']['tx_ave_pktsize']      = array('type' => 'port_pktsize', 'id' => '@port_id');


$config['entities'][$entity]['attribs']['ifSpeed']        = array('label' => 'ifSpeed',    'descr' => 'Port Speed (bits)',  'type' => 'integer');
$config['entities'][$entity]['attribs']['ifAlias']        = array('label' => 'ifAlias',    'descr' => 'port ifAlias',  'type' => 'string');
$config['entities'][$entity]['attribs']['ifDescr']        = array('label' => 'ifDescr',    'descr' => 'Port ifDescr',  'type' => 'string');
$config['entities'][$entity]['attribs']['ifName']         = array('label' => 'ifName',     'descr' => 'Port ifName',  'type' => 'string');
$config['entities'][$entity]['attribs']['ifType']         = array('label' => 'ifType',     'descr' => 'Port ifType',  'type' => 'string'); // FIXME -- ADD OPTIONS
$config['entities'][$entity]['attribs']['ifPhysAddress']  = array('label' => 'ifPhysAddress', 'descr' => 'Port Physical (MAC) Addrass',  'type' => 'string');

$config['entities'][$entity]['attribs']['port_label']        = array('label' => 'Label',         'descr' => 'Port Label',          'type' => 'string');
$config['entities'][$entity]['attribs']['port_short_label']  = array('label' => 'Short Label',   'descr' => 'Port Label (short)',  'type' => 'string');
$config['entities'][$entity]['attribs']['port_label_base']   = array('label' => 'Label Base',    'descr' => 'Port Label Base',     'type' => 'string');
$config['entities'][$entity]['attribs']['port_label_num']    = array('label' => 'Label Numeric', 'descr' => 'Port Label Numeric',  'type' => 'string');
$config['entities'][$entity]['attribs']['port_descr_type']   = array('label' => 'Parsed Type',   'descr' => 'Port Type from Parser',  'type' => 'string');
$config['entities'][$entity]['attribs']['port_descr_descr']  = array('label' => 'Parsed Descr',  'descr' => 'Port Descr from Parser',  'type' => 'string');
$config['entities'][$entity]['attribs']['port_descr_speed']  = array('label' => 'Parsed Speed',  'descr' => 'Port Speed from Parser',  'type' => 'string');
$config['entities'][$entity]['attribs']['port_descr_circuit'] = array('label' => 'Parsed Circuit ID', 'descr' => 'Port Circuit ID from Parser',  'type' => 'string');
$config['entities'][$entity]['attribs']['port_descr_notes']  = array('label' => 'Parsed Note', 'descr' => 'Port Note from Descr',  'type' => 'string');
$config['entities'][$entity]['attribs']['port_mcbc']         = array('label' => 'Port has MC/BC', 'descr' => 'Port Has MC/BC counters',  'type' => 'boolean');



$config['entities'][$entity]['metrics']['ifInOctets_rate']           = array('label' => 'Ingress Rate (Bps)',           'type' => 'integer');
$config['entities'][$entity]['metrics']['ifOutOctets_rate']          = array('label' => 'Egress Rate (Bps)',            'type' => 'integer');
$config['entities'][$entity]['metrics']['ifInOctets_perc']           = array('label' => 'Ingress Load (%)',             'type' => 'integer');
$config['entities'][$entity]['metrics']['ifOutOctets_perc']          = array('label' => 'Egress Load (%)',              'type' => 'integer');
$config['entities'][$entity]['metrics']['ifInUcastPkts_rate']        = array('label' => 'Ingress Unicast PPS',          'type' => 'integer');
$config['entities'][$entity]['metrics']['ifOutUcastPkts_rate']       = array('label' => 'Egress Unicast PPS',           'type' => 'integer');
$config['entities'][$entity]['metrics']['ifInNUcastPkts_rate']       = array('label' => 'Ingress Non-Unicast PPS',      'type' => 'integer');
$config['entities'][$entity]['metrics']['ifOutNUcastPkts_rate']      = array('label' => 'Egress Non-Unicast PPS',       'type' => 'integer');
$config['entities'][$entity]['metrics']['ifInBroadcastPkts_rate']    = array('label' => 'Ingress Broadcast PPS',        'type' => 'integer');
$config['entities'][$entity]['metrics']['ifOutBroadcastPkts_rate']   = array('label' => 'Egress Broadcast PPS',         'type' => 'integer');
$config['entities'][$entity]['metrics']['ifInMulticastPkts_rate']    = array('label' => 'Ingress Broadcast PPS',        'type' => 'integer');
$config['entities'][$entity]['metrics']['ifOutMulticastPkts_rate']   = array('label' => 'Egress Broadcast PPS',         'type' => 'integer');
$config['entities'][$entity]['metrics']['ifInErrors_rate']           = array('label' => 'Ingress Errors/sec',           'type' => 'integer');
$config['entities'][$entity]['metrics']['ifOutErrors_rate']          = array('label' => 'Egress Errors/sec',            'type' => 'integer');
$config['entities'][$entity]['metrics']['ifInDiscards_rate']         = array('label' => 'Ingress Errors/sec',           'type' => 'integer');
$config['entities'][$entity]['metrics']['ifOutDiscards_rate']        = array('label' => 'Egress Errors/sec',            'type' => 'integer');
$config['entities'][$entity]['metrics']['ifUcastPkts_rate']          = array('label' => 'Total Unicast Pkt/s',          'type' => 'integer');
$config['entities'][$entity]['metrics']['ifNUcastPkts_rate']         = array('label' => 'Total Non-Unicast Pkt/s',      'type' => 'integer');
$config['entities'][$entity]['metrics']['ifBroadcastPkts_rate']      = array('label' => 'Total Broadcast Pkt/s',        'type' => 'integer');
$config['entities'][$entity]['metrics']['ifMulticastPkts_rate']      = array('label' => 'Total Broadcast Pkt/s',        'type' => 'integer');
$config['entities'][$entity]['metrics']['ifErrors_rate']             = array('label' => 'Total Errors/sec',             'type' => 'integer');
$config['entities'][$entity]['metrics']['ifDiscards_rate']           = array('label' => 'Total Errors/sec',             'type' => 'integer');
$config['entities'][$entity]['metrics']['rx_ave_pktsize']            = array('label' => 'Ingress Packet Size',          'type' => 'integer');
$config['entities'][$entity]['metrics']['tx_ave_pktsize']            = array('label' => 'Egress Packet Size',           'type' => 'integer');
$config['entities'][$entity]['metrics']['ifOperStatus']              = array('label' => 'Operational Status',           'type' => 'string');
$config['entities'][$entity]['metrics']['ifAdminStatus']             = array('label' => 'Administrative Status',        'type' => 'string');
$config['entities'][$entity]['metrics']['ifMtu']                     = array('label' => 'MTU',                          'type' => 'integer');
$config['entities'][$entity]['metrics']['ifSpeed']                   = array('label' => 'Speed',                        'type' => 'integer');
$config['entities'][$entity]['metrics']['ifDuplex']                  = array('label' => 'Duplex',                       'type' => 'string');


/// Wireless WLANs
///
$entity = 'wifi_wlan';
// Main table & field
$config['entities'][$entity]['table']                           = "wifi_wlans";
$config['entities'][$entity]['table_fields']['id']              = "wlan_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "wlan_index";
//$config['entities'][$entity]['table_fields']['mib']             = "";
$config['entities'][$entity]['table_fields']['name']            = "wlan_name";
//$config['entities'][$entity]['table_fields']['ignore']          = "wlan_ignore";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['wifi'];
$config['entities'][$entity]['graph']                           = NULL;
$config['entities'][$entity]['params']                          = array('wlan_admin_status', 'wlan_beacon_period', 'wlan_bssid',
                                                                        'wlan_bss_type', 'wlan_channel', 'wlan_dtim_period', 'wlan_frag_thresh',
                                                                        'wlan_index', 'wlan_igmp_snoop', 'wlan_name', 'wlan_prot_mode',
                                                                        'wlan_radio_mode', 'wlan_rts_thresh', 'wlan_ssid', 'wlan_ssid_bcast', 'wlan_vlan_id');

/// Wireless Radios
///
$entity = 'wifi_radio';
// Main table & field
$config['entities'][$entity]['table']                           = "wifi_radios";
$config['entities'][$entity]['table_fields']['id']              = "wifi_radio_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = array('radio_ap','radio_number');
//$config['entities'][$entity]['table_fields']['index']           = "radio_index";
$config['entities'][$entity]['table_fields']['mib']             = "radio_mib";
$config['entities'][$entity]['table_fields']['name']            = "radio_number";
//$config['entities'][$entity]['table_fields']['ignore']          = "radio_ignore";
// State table & fields
//$config['entities'][$entity]['state_table']                     = "wifi_radios-state";
// Icon & graph
$config['entities'][$entity]['params']                          = array('radio_ap', 'radio_mib','radio_number', 'radio_util', 'radio_type',
                                                                        'radio_status', 'radio_clients', 'radio_txpower', 'radio_channel',
                                                                        'radio_mac', 'radio_protection', 'radio_bsstype');
$config['entities'][$entity]['icon']                            = $config['icon']['wifi'];
$config['entities'][$entity]['graph']                           = NULL;


/// Wireless Access Points
///
$entity = 'wifi_ap';
// Main table & field
$config['entities'][$entity]['table']                           = "wifi_aps";
$config['entities'][$entity]['table_fields']['id']              = "wifi_ap_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "ap_index";
$config['entities'][$entity]['table_fields']['mib']             = "ap_mib";
$config['entities'][$entity]['table_fields']['name']            = "ap_name";
$config['entities'][$entity]['table_fields']['deleted']         = "deleted";
$config['entities'][$entity]['icon']                            = $config['icon']['wifi'];
$config['entities'][$entity]['graph']                           = NULL;
$config['entities'][$entity]['params']                          = array('ap_index', 'ap_number', 'ap_name', 'ap_serial', 'ap_model', 'ap_location',
                                                                        'ap_fingerprint', 'ap_status');

/// SLA / RTTs
///
$entity = 'sla';
// Main table & field
$config['entities'][$entity]['table']                           = "slas";
$config['entities'][$entity]['table_fields']['id']              = "sla_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "sla_index";
$config['entities'][$entity]['table_fields']['mib']             = "sla_mib";
$config['entities'][$entity]['table_fields']['name']            = "sla_tag";
//$config['entities'][$entity]['table_fields']['ignore']          = "sla_ignore"; // not exist
$config['entities'][$entity]['table_fields']['deleted']         = "deleted";
$config['entities'][$entity]['table_fields']['limit_high']      = "sla_limit_high";
$config['entities'][$entity]['table_fields']['limit_high_warn'] = "sla_limit_high_warn";
// State table & fields
$config['entities'][$entity]['state_fields']['value']           = "rtt_value";
$config['entities'][$entity]['state_fields']['status']          = "rtt_sense";
$config['entities'][$entity]['state_fields']['event']           = "rtt_event";
$config['entities'][$entity]['state_fields']['uptime']          = "rtt_unixtime";
$config['entities'][$entity]['state_fields']['last_change']     = "rtt_last_change";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['sla'];
$config['entities'][$entity]['graph']                           = array('type' => 'sla_graph', 'id' => '@sla_id');

$config['entities'][$entity]['attribs']['sla_index']	    = array('label' => 'Index',        'descr' => 'SLA Index',          'type' => 'string');
$config['entities'][$entity]['attribs']['sla_owner']	    = array('label' => 'Owner',        'descr' => 'SLA Owner',          'type' => 'string');
$config['entities'][$entity]['attribs']['sla_tag']	        = array('label' => 'Tag',          'descr' => 'SLA Tag',            'type' => 'string');
$config['entities'][$entity]['attribs']['sla_graph']	    = array('label' => 'Graph',        'descr' => 'SLA Graph',          'type' => 'string', 'values' => array('echo', 'jitter'));
$config['entities'][$entity]['attribs']['rtt_type']	        = array('label' => 'Type',         'descr' => 'SLA Type',           'type' => 'string');

$config['entities'][$entity]['metrics']['rtt_value']        = array('label' => 'RTT Time (ms)',               'type' => 'integer');
$config['entities'][$entity]['metrics']['rtt_sense']        = array('label' => 'RTT Raw Code',      'type' => 'string');
$config['entities'][$entity]['metrics']['rtt_sense_uptime'] = array('label' => 'Time Since Changed', 'type' => 'integer');
$config['entities'][$entity]['metrics']['rtt_event']        = array('label' => 'Status Event',               'type' => 'string', 'values' => array('ok', 'warning', 'alert'));
$config['entities'][$entity]['metrics']['rtt_minimum']      = array('label' => 'RTT Minimum (Jitter)', 'type' => 'integer');
$config['entities'][$entity]['metrics']['rtt_maximum']      = array('label' => 'RTT Maximum (Jitter)', 'type' => 'integer');
$config['entities'][$entity]['metrics']['rtt_success']      = array('label' => 'RTT Success Count (Jitter)', 'type' => 'integer');
$config['entities'][$entity]['metrics']['rtt_loss']         = array('label' => 'RTT Loss Count (Jitter)', 'type' => 'integer');

/// Pseudowires
///
$entity = 'pseudowire';
// Main table & field
$config['entities'][$entity]['table']                           = "pseudowires";
$config['entities'][$entity]['table_fields']['id']              = "pseudowire_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['index']           = "pwIndex";
$config['entities'][$entity]['table_fields']['mib']             = "mib";
$config['entities'][$entity]['table_fields']['name']            = "pwID";
//$config['entities'][$entity]['table_fields']['ignore']          = "pw_ignore"; // not exist
//$config['entities'][$entity]['table_fields']['deleted']         = "pw_deleted"; // not exist
// State table & fields
//$config['entities'][$entity]['state_table']                     = "pseudowires-state";
//$config['entities'][$entity]['state_fields']['value']           = ""; // not exist
$config['entities'][$entity]['state_fields']['status']          = "pwOperStatus";
$config['entities'][$entity]['state_fields']['event']           = "event";
$config['entities'][$entity]['state_fields']['uptime']          = "pwUptime";
$config['entities'][$entity]['state_fields']['last_change']     = "last_change";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['pseudowire'];
$config['entities'][$entity]['graph']                           = array('type' => 'pseudowire_bits', 'id' => '@pseudowire_id');

$config['entities'][$entity]['attribs']['mib']	                = array('label' => 'MIB',              'descr' => 'Pseudowire Source MIB',          'type' => 'string');
$config['entities'][$entity]['attribs']['pwOutboundLabel']	    = array('label' => 'Outbound Label',   'descr' => 'Pseudowire Outbound Label',      'type' => 'integer');
$config['entities'][$entity]['attribs']['pwInboundLabel']	    = array('label' => 'Inbound Label',    'descr' => 'Pseudowire Inbound Label',       'type' => 'integer');
$config['entities'][$entity]['attribs']['peer_addr']	        = array('label' => 'Peer Address',     'descr' => 'Pseudowire Peer Address',        'type' => 'string');
$config['entities'][$entity]['attribs']['peer_device_id']	    = array('label' => 'Peer device_id',   'descr' => 'Pseudowire Peer device_id',      'type' => 'integer');
$config['entities'][$entity]['attribs']['pwType']	            = array('label' => 'Type',             'descr' => 'Pseudowire Type',                'type' => 'string');
$config['entities'][$entity]['attribs']['pwPsnType']	        = array('label' => 'PSN Type',         'descr' => 'Pseudowire PSN Type',            'type' => 'string');
$config['entities'][$entity]['attribs']['pwDescr']	            = array('label' => 'Description',      'descr' => 'Pseudowire Description',         'type' => 'string');
$config['entities'][$entity]['attribs']['pwRemoteIfString']	    = array('label' => 'Remote Port Name', 'descr' => 'Pseudowire Remote Port Name',    'type' => 'string');

$config['entities'][$entity]['metrics']['pwOperStatus']         = array('label' => 'Operational Status', 'type' => 'string', 'values' => array('up', 'down', 'unknown'));
$config['entities'][$entity]['metrics']['pwRemoteStatus']       = array('label' => 'Remote Status',      'type' => 'string', 'values' => array('up', 'down', 'unknown'));
$config['entities'][$entity]['metrics']['pwLocalStatus']        = array('label' => 'Local Status',       'type' => 'string', 'values' => array('up', 'down', 'unknown'));
$config['entities'][$entity]['metrics']['event']                = array('label' => 'Interpreted Status', 'type' => 'string', 'values' => array('ok', 'alert', 'warn', 'ignore'));
$config['entities'][$entity]['metrics']['pwUptime']             = array('label' => 'Uptime',             'type' => 'integer');
$config['entities'][$entity]['metrics']['last_change']          = array('label' => 'Last Changed',       'type' => 'integer');

/// Virtual Machines
///
$entity = 'virtualmachine';
// Main table & field
$config['entities'][$entity]['table']                           = "vminfo";
$config['entities'][$entity]['table_fields']['id']              = "vm_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
//$config['entities'][$entity]['table_fields']['index']           = "";
//$config['entities'][$entity]['table_fields']['mib']             = "";
$config['entities'][$entity]['table_fields']['name']            = "vm_name";
//$config['entities'][$entity]['table_fields']['ignore']          = ""; // not exist
//$config['entities'][$entity]['table_fields']['disable']         = ""; // not exist
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['virtual-machine'];
$config['entities'][$entity]['hide']                            = TRUE; // FIXME hidden right now, we can't alert on VMs yet as we don't do polling of their status
$config['entities'][$entity]['graph']                           = NULL;


/// Custom OID
///
$entity = 'oid_entry';
// Main table & field
$config['entities'][$entity]['table']                           = "oids_entries";
$config['entities'][$entity]['table_fields']['id']              = "oid_entry_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['name']            = "oid_name";
$config['entities'][$entity]['parent_table']                    = "oids";
$config['entities'][$entity]['parent_id_field']                 = "oid_id";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['customoid'];
$config['entities'][$entity]['graph']                           = array('type' => 'customoid_graph', 'id' => '@oid_entry_id');

/// FIXME. Added in r7022, but I not found where it used
$config['default']['icon']                                      = $config['icon']['shutdown'];

// These are invisible, only exist to allow us to get fancy links and popups

$entity = 'alert_entry';
// Main table & field
$config['entities'][$entity]['table']                           = "alert_table";
$config['entities'][$entity]['table_fields']['id']              = "alert_table_id";
$config['entities'][$entity]['table_fields']['device_id']       = "device_id";
$config['entities'][$entity]['table_fields']['name']            = "alert_table_id";
// Icon & graph
$config['entities'][$entity]['icon']                            = $config['icon']['alert'];
$config['entities'][$entity]['hide']                            = TRUE;
$config['entities'][$entity]['graph']                           = array('type' => 'alert_status', 'id' => '@alert_table_id');

$entity = 'alert_checker';
// Main table & field
$config['entities'][$entity]['table']                           = "alert_tests";
$config['entities'][$entity]['table_fields']['id']              = "alert_test_id";
//$config['entities'][$entity]['table_fields']['device_id']       = "";           // not exist
$config['entities'][$entity]['table_fields']['name']            = "alert_name";
$config['entities'][$entity]['table_fields']['descr']           = "alert_message";
// Icon
$config['entities'][$entity]['icon']                            = $config['icon']['alert-rules'];
$config['entities'][$entity]['hide']                            = TRUE;

$entity = 'maintenance';
// Main table & field
$config['entities'][$entity]['table']                           = "alerts_maint";
$config['entities'][$entity]['table_fields']['id']              = "maint_id";
//$config['entities'][$entity]['table_fields']['device_id']       = "";           // not exist
$config['entities'][$entity]['table_fields']['name']            = "maint_name";
$config['entities'][$entity]['table_fields']['descr']           = "maint_descr";
// Icon
$config['entities'][$entity]['icon']                            = $config['icon']['scheduled-maintenance'];
$config['entities'][$entity]['hide']                            = TRUE;

$entity = 'group';
// Main table & field
$config['entities'][$entity]['table']                           = "groups";
$config['entities'][$entity]['table_fields']['id']              = "group_id";
//$config['entities'][$entity]['table_fields']['device_id']       = "";           // not exist
$config['entities'][$entity]['table_fields']['name']            = "group_name";
$config['entities'][$entity]['table_fields']['descr']           = "group_descr";
// Icon
$config['entities'][$entity]['icon']                            = $config['icon']['groups'];
$config['entities'][$entity]['hide']                            = TRUE;

// EOF
