<?php
/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package        observium
 * @subpackage     definitions
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

/////////////////////////////////////////////////////////
//  NO CHANGES TO THIS FILE, IT IS NOT USER-EDITABLE   //
/////////////////////////////////////////////////////////
//               YES, THAT MEANS YOU                   //
/////////////////////////////////////////////////////////

/*
 * Notes about 'mibs' definitions.
 *
 * BACKEND:
 * $config['mibs'][$mib]['enable']                          (integer) 1 to enable, should always be 1 here - overwritable in configuration
 * $config['mibs'][$mib]['identity_num']                    (string)  MODULE-IDENTITY numeric OID for this MIB. Leave out if MIB has no such attribute.
 *                                                                    Make sure to add the leading dot!
 * $config['mibs'][$mib]['mib_dir']                         (string)  MIB subdirectory to use for this MIB
 * $config['mibs'][$mib]['descr']                           (string)  Description of the MIB
 *
 * $config['mibs'][$mib]['serial']                          (array)   Array of possible OIDs names containing the device's serial number, first match wins
 *   ['oid']                                                (string)  Textual OID for attribute
 *   ['oid_num']                                            (string)  Numeric OID for attribute (takes precedence over textual OID)
 *   ['transformations']                                    (array)   Array of string transformations to be done, in order of definition.
 *                                                                    See string_transform() documentation for possible transformations and parameters.
 *
 * $config['mibs'][$mib]['version']                         (array)   Array of possible OIDs names containing the device's version number, first match wins
 *                                                                    See 'serial' for array entries supported.
 *
 * $config['mibs'][$mib]['hardware']                        (array)   Array of possible OIDs names containing the device's hardware description, first match wins
 *                                                                    See 'serial' for array entries supported.
 *
 * $config['mibs'][$mib]['features']                        (array)   Array of possible OIDs names containing the device's feature set, first match wins
 *                                                                    See 'serial' for array entries supported.
 *
 * $config['mibs'][$mib]['asset_tag']                       (array)   Array of possible OIDs names containing the device's asset tag, first match wins
 *                                                                    See 'serial' for array entries supported.
 *
 * $config['mibs'][$mib]['ra_url_http']                     (array)   Array of possible OIDs names containing the device's HTTP management URL, first match wins
 *                                                                    See 'serial' for array entries supported.
 *
 * $config['mibs'][$mib]['sysdescr']                        (array)   Array of possible OIDs names containing the device's sysDescr, first match wins
 *                                                                    See 'serial' for array entries supported.
 *
 * $config['mibs'][$mib]['syslocation']                     (array)   Array of possible OIDs names containing the device's sysLocation, first match wins
 *                                                                    See 'serial' for array entries supported.
 *
 * $config['mibs'][$mib]['syscontact']                      (array)   Array of possible OIDs names containing the device's sysContact, first match wins
 *                                                                    See 'serial' for array entries supported.
 *
 * $config['mibs'][$mib]['sysname']                         (array)   Array of possible OIDs names containing the device's sysName, first match wins
 *                                                                    See 'serial' for array entries supported.
 *
 * $config['mibs'][$mib]['sysuptime']                       (array)   Array of possible OIDs names containing the device's sysUptime, first match wins
 *                                                                    See 'serial' for array entries supported.
 *
 * $config['mibs'][$mib]['processor']                       (array)   Automatic definition-based discovery of processors in this device
 *   [$tablename]['descr']                                  (string)  Description of processor (hard-coded)
 *   [$tablename]['type']                                   (string)  Processor type ('static' for single OID, 'table' for table walk)
 *   [$tablename]['idle']                                   (bool)    TRUE if Idle value is returned in OID instead of Used value
 *   [$tablename]['table']                                  (string)  Textual OID name of table to walk in case 'type' is set to 'table'
 *   [$tablename]['oid_descr']                              (string)  Textual OID name of table to walk/get for descriptions
 *   [$tablename]['oid']                                    (string)  Textual OID for processor value
 *   [$tablename]['oid_num']                                (string)  Numeric OID for processor value (snmptranslated from 'oid' if not set)
 *   [$tablename]['oid_count']                              (string)  FIXME undocumented? "processor count"
 *   [$tablename]['scale']                                  (float)   Processor scale multiplier
 *   [$tablename]['rename_rrd']                             (string)  Rename 'this' RRD file to the new naming convention
 *   [$tablename]['skip_if_valid_exist']                    (bool)    FIXME
 *
 * $config['mibs'][$mib]['mempool']                         (array)   Automatic definition-based discovery of memory in this device
 *   [$tablename]['descr']                                  (string)  Description of memory pool (hard-coded)
 *   [$tablename]['type']                                   (string)  Processor type ('static' for single OID, 'table' for table walk) FIXME only static for now
 *   [$tablename]['oid_descr']                              (string)  Textual OID name of table to walk/get for descriptions
 *   [$tablename]['oid_free']                               (string)  Textual OID for memory free value
 *   [$tablename]['oid_free_num']                           (string)  Numeric OID for memory free value (snmptranslated from 'oid' if not set)
 *   [$tablename]['oid_used']                               (string)  Textual OID for memory used value
 *   [$tablename]['oid_used_num']                           (string)  Numeric OID for memory used value (snmptranslated from 'oid' if not set)
 *   [$tablename]['total']                                  (int)     Hardcoded memory total
 *   [$tablename]['oid_total']                              (string)  Textual OID for memory total
 *   [$tablename]['oid_total_num']                          (string)  Numeric OID for memory total value (snmptranslated from 'oid' if not set)
 *   [$tablename]['oid_perc']                               (string)  Textual OID for memory used percentage value
 *   [$tablename]['oid_perc_num']                           (string)  Numeric OID for memory used percentage value (snmptranslated from 'oid' if not set)
 *   [$tablename]['scale']                                  (float)   Mempool default scale multiplier
 *   [$tablename]['scale_total']                            (float)   Mempool total scale multiplier (if not passed, use default)
 *   [$tablename]['scale_used']                             (float)   Mempool used scale multiplier (if not passed, use default)
 *   [$tablename]['scale_free']                             (float)   Mempool free scale multiplier (if not passed, use default)
 *   [$tablename]['rename_rrd']                             (string)  Rename 'this' RRD file to the new naming convention
 *
 * $config['mibs'][$mib]['sensor']                          (array)   Automatic definition-based discovery of sensors in this device
 *   [$tablename]['indexes'][$index]['descr']               (string)  Description of sensor (hard-coded). Key %oid_descr% replaced with value from 'oid_descr'
 *   [$tablename]['indexes'][$index]['oid_descr']           (string)  Numeric OID for description of sensor (dynamic)
 *   [$tablename]['indexes'][$index]['class']               (string)  Sensor class (temperature, fanspeed, voltage, ...)
 *   [$tablename]['indexes'][$index]['measured']            (string)  Measured entity (device, port, ...)
 *   [$tablename]['indexes'][$index]['oid']                 (string)  Textual OID for sensor value
 *   [$tablename]['indexes'][$index]['oid_num']             (string)  Numeric OID for sensor value (snmptranslated from 'oid' if not set)
 *   [$tablename]['indexes'][$index]['oid_limit_low']       (string)  Textual OID for low critical sensor limit
 *   [$tablename]['indexes'][$index]['oid_limit_low_warn']  (string)  Textual OID for low warning sensor limit
 *   [$tablename]['indexes'][$index]['oid_limit_high_warn'] (string)  Textual OID for high warning sensor limit
 *   [$tablename]['indexes'][$index]['oid_limit_high']      (string)  Textual OID for high critical sensor limit
 *   [$tablename]['indexes'][$index]['oid_limit_nominal']   (string)  Textual OID with nominal sensor limit value (used with oid_limit_delta and oid_limit_delta_warn)
 *   [$tablename]['indexes'][$index]['oid_limit_delta']     (string)  Textual OID with limit delta for calculate limit_low and limit_high from nominal (used with oid_limit_nominal)
 *   [$tablename]['indexes'][$index]['oid_limit_delta_warn'] (string) Textual OID with limit delta warn for calculate limit_low_warn and limit_high_warn from nominal (used with oid_limit_nominal)
 *   [$tablename]['indexes'][$index]['limit_scale']         (float)   Scale multiplier for limits from Numeric OIDs (oid_limit_*)
 *   [$tablename]['indexes'][$index]['limit_low']           (numeric) Numeric value for low critical sensor limit
 *   [$tablename]['indexes'][$index]['limit_low_warn']      (numeric) Numeric value for low warning sensor limit
 *   [$tablename]['indexes'][$index]['limit_high_warn']     (numeric) Numeric value for high warning sensor limit
 *   [$tablename]['indexes'][$index]['limit_high']          (numeric) Numeric value for high critical sensor limit
 *   [$tablename]['indexes'][$index]['min']                 (numeric) Minimal value for the sensor value to be considered valid (= 'sensor present')
 *   [$tablename]['indexes'][$index]['max']                 (numeric) Maximal value for the sensor value to be considered valid (= 'sensor present')
 *   [$tablename]['indexes'][$index]['scale']               (float)   Sensor scale multiplier
 *   [$tablename]['indexes'][$index]['skip_if_valid_exist'] (bool)    FIXME
 *   [$tablename]['indexes'][$index]['rename_rrd']          (string)  Rename 'this' RRD file to the new naming convention
 *   [$tablename]['indexes'][$index]['rename_rrd_array']    (array)   Rename old RRD file to the new naming convention, used array of discover_sensor() params:
 *     ['descr']                                            (string)  Old sensor name
 *     ['type']                                             (string)  Old sensor type
 *     ['index']                                            (string)  Old sensor index
 * Additional params for table walk:
 *   [$tablename]['tables'][]['table']                      (string)  Textual OID with table name (if not set, used $tablename from key)
 *   [$tablename]['tables'][]['table_walk']                 (boolean) If set to FALSE, walked separate OIDs from definition. By default walked whole table
 *   [$tablename]['tables'][]['descr']                      (string)  Description of sensor. Keys replaced: %index% - index from walk, %i% - per-class counter started from 1, %class% - sensor class
 *                                                                    Multipart indexes (ie: 0.1) can used as keys with part number: %index0%, %index1%, ...
 *   [$tablename]['tables'][]['oid_class']                  (string)  OID that determines the sensor class, in conjunction with map_class. Only used when 'class' is not set.
 *   [$tablename]['tables'][]['map_class']                  (array)   Array map that maps oid_class values to a specific class (i.e. 1 => 'temperature', 2 = 'humidity'). Only used when 'class' is not set.
 *                                                                    If no class is mapped to the value, the sensor is skipped.

 * $config['mibs'][$mib]['status']                          (array)   Automatic definition-based discovery of status sensors in this device
 *   [$tablename]['indexes'][$index]['descr']               (string)  Description of sensor (hard-coded). Key %oid_descr% replaced with value from 'oid_descr'
 *   [$tablename]['indexes'][$index]['oid_descr']           (string)  Numeric OID for description of sensor (dynamic)
 *   [$tablename]['indexes'][$index]['type']                (string)  Status sensor type (name as used in MIB)
 *   [$tablename]['indexes'][$index]['oid']                 (string)  Textual OID for status value
 *   [$tablename]['indexes'][$index]['oid_num']             (string)  Numeric OID for status value (snmptranslated from 'oid' if not set)
 *   [$tablename]['indexes'][$index]['measured']            (string)  Measured entity (device, port, ...)
 *   [$tablename]['indexes'][$index]['rename_rrd_array']    (array)   Rename old RRD file to the new naming convention, used array of discover_status() params:
 *     ['descr']                                            (string)  Old status name
 *     ['type']                                             (string)  Old status type
 *     ['index']                                            (string)  Old status index
 * Additional params for table walk:
 *   [$tablename]['tables'][]['table']                      (string)  Textual OID with table name (if not set, used $tablename from key)
 *   [$tablename]['tables'][]['table_walk']                 (boolean) If set to FALSE, walked separate OIDs from definition. By default walked whole table
 *   [$tablename]['tables'][]['descr']                      (string)  Description of sensor. Keys replaced: %index% - index from walk, %i% - counter started from 1
 *                                                                    Multipart indexes (ie: 0.1) can used as keys with part number: %index0%, %index1%, ...
 *
 */

// RFC and NET-SNMP MIBs, leave first

// SNMPv2-MIB

$mib = 'SNMPv2-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.6.3.1',     // SNMPv2-MIB::snmpMIB    (MODULE-IDENTITY)
  '.1.3.6.1.2.1.1',     // SNMPv2-MIB::system     (additional sysORID)
  '.1.3.6.1.2.1.1.9.1', // SNMPv2-MIB::sysOREntry (additional sysORID)
  '.1.3.6.1.2.1.11');   // SNMPv2-MIB::snmp       (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// Common states from SNMPv2-TC
$type = 'TruthValue'; // $config['mibs']['SNMPv2-MIB']['states']['TruthValue']
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'true', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'false', 'event' => 'alert');

$type = 'RowStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'active', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'notInService', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'notReady', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'createAndGo', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'createAndWait', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'destroy', 'event' => 'exclude');

$mib = 'INET-ADDRESS-MIB';
$config['mibs'][$mib]['enable'] = 0; // This MIB is disabled, because used only for translations (in BGP for example)
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.76';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

$type = 'InetAddressType';
$config['mibs'][$mib]['rewrite'][$type][0] = 'unknown';
$config['mibs'][$mib]['rewrite'][$type][1] = 'ipv4';
$config['mibs'][$mib]['rewrite'][$type][2] = 'ipv6';
$config['mibs'][$mib]['rewrite'][$type][3] = 'ipv4z';
$config['mibs'][$mib]['rewrite'][$type][4] = 'ipv6z';
$config['mibs'][$mib]['rewrite'][$type][16] = 'dns';

$mib = 'ADSL-LINE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.10.94';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

$mib = 'DISMAN-PING-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.80';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = 'Round Trip Time (RTT) monitoring of a list of targets';
$config['mibs'][$mib]['sla_states'] = array(
  'responseReceived'          => array('num' => '1', 'event' => 'ok'),
  'unknown'                   => array('num' => '2', 'event' => 'exclude'),
  'internalError'             => array('num' => '3', 'event' => 'warning'),
  'requestTimedOut'           => array('num' => '4', 'event' => 'alert'),
  'unknownDestinationAddress' => array('num' => '5', 'event' => 'alert'),
  'noRouteToTarget'           => array('num' => '6', 'event' => 'alert'),
  'interfaceInactiveToTarget' => array('num' => '7', 'event' => 'warning'),
  'arpFailure'                => array('num' => '8', 'event' => 'alert'),
  'maxConcurrentLimitReached' => array('num' => '9', 'event' => 'warning'),
  'unableToResolveDnsName'    => array('num' => '10', 'event' => 'alert'),
  'invalidHostAddress'        => array('num' => '11', 'event' => 'warning'),
);

// IF-MIB

$mib = 'IF-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.2.1.31', // IF-MIB::ifMIB      (MODULE-IDENTITY)
  '.1.3.6.1.2.1.2'); // IF-MIB::interfaces (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// OSPF-MIB

$mib = 'OSPF-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.14';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// IP-MIB

$mib = 'IP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.2.1.48', // IP-MIB::ipMIB (MODULE-IDENTITY)
  '.1.3.6.1.2.1.4',  // IP-MIB::ip    (additional sysORID)
  '.1.3.6.1.2.1.5'); // IP-MIB::icmp  (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// TCP-MIB

$mib = 'TCP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.2.1.49', // TCP-MIB::tcpMIB (MODULE-IDENTITY)
  '.1.3.6.1.2.1.6'); // TCP-MIB::tcp    (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// UDP-MIB

$mib = 'UDP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.2.1.50', // UDP-MIB::udpMIB (MODULE-IDENTITY)
  '.1.3.6.1.2.1.7'); // UDP-MIB::udp    (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// IPV6-MIB

$mib = 'IPV6-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.55';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// BGP4-MIB

$mib = 'BGP4-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.15';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// PW-MPLS-STD-MIB

$mib = 'PW-MPLS-STD-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.181';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// ENTITY-MIB

$mib = 'ENTITY-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.47';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// ENTITY-SENSOR-MIB

$mib = 'ENTITY-SENSOR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.99';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// MPLS-L3VPN-STD-MIB

$mib = 'MPLS-L3VPN-STD-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.10.166.11';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// MPLS-VPN-MIB

$mib = 'MPLS-VPN-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.3.118';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// LLDP-MIB

$mib = 'LLDP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.0.8802.1.1.2';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// BRIDGE-MIB

$mib = 'BRIDGE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.17';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// HOST-RESOURCES-MIB

$mib = 'HOST-RESOURCES-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.2.1.25.7.1', // HOST-RESOURCES-MIB::hostResourcesMibModule (MODULE-IDENTITY)
  '.1.3.6.1.2.1.25');    // HOST-RESOURCES-MIB::host                   (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// RADIUS-ACC-CLIENT-MIB

$mib = 'RADIUS-ACC-CLIENT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.67.2.2';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// RADIUS-AUTH-CLIENT-MIB

$mib = 'RADIUS-AUTH-CLIENT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.67.1.2';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// Q-BRIDGE-MIB

$mib = 'Q-BRIDGE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.2.1.17.7',      // Q-BRIDGE-MIB::qBridgeMIB (MODULE-IDENTITY)
  '.1.3.6.1.2.1.17.7.1.2'); // Q-BRIDGE-MIB::dot1qTp    (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// P-BRIDGE-MIB

$mib = 'P-BRIDGE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.17.6';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

$mib = 'EtherLike-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.2.1.35',      // EtherLike-MIB::etherMIB       (MODULE-IDENTITY)
  '.1.3.6.1.2.1.10.7',    // EtherLike-MIB::dot3           (additional sysORID)
  '.1.3.6.1.2.1.10.7.2'); // EtherLike-MIB::dot3StatsTable (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

$mib = 'IEEE802dot11-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.2.840.10036';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

$mib = 'RMON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.16.20.8';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

$mib = 'PW-STD-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.10.246';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['pseudowire']['oids'] = array(
  'Uptime'       => array('oid' => 'pwUpTime', 'oid_num' => '.1.3.6.1.2.1.10.246.1.2.1.35', 'type' => 'timeticks'),
  'OperStatus'   => array('oid' => 'pwOperStatus', 'oid_num' => '.1.3.6.1.2.1.10.246.1.2.1.38'),
  'RemoteStatus' => array('oid' => 'pwRemoteStatus', 'oid_num' => '.1.3.6.1.2.1.10.246.1.2.1.41'),
  'LocalStatus'  => array('oid' => 'pwLocalStatus', 'oid_num' => '.1.3.6.1.2.1.10.246.1.2.1.39'),

  // FIXME. This OIDs not exist for this MIB.
  // Has 'pwPerf1DayInterval', 'pwPerfInterval' and 'pwPerfCurrent', but we not know how use it because here used specific intervals
  //'InPkts'      => array('oid' => 'pwPerfTotalInHCPackets',  'oid_num' => ''),
  //'OutPkts'     => array('oid' => 'pwPerfTotalOutHCPackets', 'oid_num' => ''),
  //'InOctets'    => array('oid' => 'pwPerfTotalInHCBytes',    'oid_num' => ''),
  //'OutOctets'   => array('oid' => 'pwPerfTotalOutHCBytes',   'oid_num' => ''),
);
$config['mibs'][$mib]['pseudowire']['states'] = array(
  'up'             => array('num' => '1', 'event' => 'ok'),
  'down'           => array('num' => '2', 'event' => 'alert'),
  'testing'        => array('num' => '3', 'event' => 'ok'),
  'dormant'        => array('num' => '4', 'event' => 'ok'),
  'notPresent'     => array('num' => '5', 'event' => 'exclude'),
  'lowerLayerDown' => array('num' => '6', 'event' => 'alert'),
);

$mib = 'POWER-ETHERNET-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.105';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

$type = 'power-ethernet-mib-pse-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'on', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'off', 'event' => 'warning'); // or ignore?
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'faulty', 'event' => 'alert');

$mib = 'Printer-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.43';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'prtGeneralSerialNumber.1');
$config['mibs'][$mib]['features'][] = array('oid' => 'prtMarkerMarkTech.1.1'); // FIXME. Need rewrite function. -- Mike 03/2013
//
// PrtMarkerMarkTech ::= DESCRIPTION "The type of marking technology used for this marking sub-unit"
//other(1),
//unknown(2),
//electrophotographicLED(3),
//electrophotographicLaser(4),
//electrophotographicOther(5),
//impactMovingHeadDotMatrix9pin(6),
//impactMovingHeadDotMatrix24pin(7),
//impactMovingHeadDotMatrixOther(8),
//impactMovingHeadFullyFormed(9),
//impactBand(10),
//impactOther(11),
//inkjetAqueous(12),
//inkjetSolid(13),
//inkjetOther(14),
//pen(15),
//thermalTransfer(16),
//thermalSensitive(17),
//thermalDiffusion(18),
//thermalOther(19),
//electroerosion(20),
//electrostatic(21),
//photographicMicrofiche(22),
//photographicImagesetter(23),
//photographicOther(24),
//ionDeposition(25),
//eBeam(26),
//typesetter(27)

// SNMP-FRAMEWORK-MIB

$mib = 'SNMP-FRAMEWORK-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.6.3.10',        // SNMP-FRAMEWORK-MIB::snmpFrameworkMIB           (MODULE-IDENTITY)
  '.1.3.6.1.6.3.10.2.1',    // SNMP-FRAMEWORK-MIB::snmpEngine                 (additional sysORID)
  '.1.3.6.1.6.3.10.3.1.1'); // SNMP-FRAMEWORK-MIB::snmpFrameworkMIBCompliance (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

$mib = 'UPS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.33';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'upsIdentAgentSoftwareVersion.0', 'transformations' => array(array('action' => 'regex_replace', 'from' => '/CS1\d1\-SNMP V(\d\S+).*/', 'to' => '$1'))); // CS141-SNMP V1.46.82 161207

$type = 'ups-mib-output-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'other', 'event' => 'exclude');
/* none(2) indicates that there is no source of output power (and therefore no output power),
for example, the system has opened the output breaker */
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'none', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'bypass', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'battery', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'booster', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'reducer', 'event' => 'warning');

$type = 'ups-mib-battery-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'batteryNormal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'batteryLow', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'batteryDepleted', 'event' => 'alert');

$type = 'ups-mib-test-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'donePass', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'doneWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'doneError', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'aborted', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'inProgress', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'noTestsInitiated', 'event' => 'exclude');

// SMON-MIB

$mib = 'SMON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.2.1.16.22';
$config['mibs'][$mib]['mib_dir'] = 'rfc';
$config['mibs'][$mib]['descr'] = '';

// SWRAID-MIB - MIB for monitoring MDRAID

$mib = 'SWRAID-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2021.13.18';
$config['mibs'][$mib]['mib_dir'] = 'net-snmp';
$config['mibs'][$mib]['descr'] = '';

$type = 'swRaidStatus'; // RaidStatusTC
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'inactive', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'active', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'faulty', 'event' => 'alert');

// UCD-SNMP-MIB

$mib = 'UCD-SNMP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2021';
$config['mibs'][$mib]['mib_dir'] = 'net-snmp';
$config['mibs'][$mib]['descr'] = '';

// UCD-DISKIO-MIB

$mib = 'UCD-DISKIO-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2021.13.15';
$config['mibs'][$mib]['mib_dir'] = 'net-snmp';
$config['mibs'][$mib]['descr'] = '';

$mib = 'LM-SENSORS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2021.13.16.1';
$config['mibs'][$mib]['mib_dir'] = 'net-snmp';
$config['mibs'][$mib]['descr'] = '';

/// All other MIBs

// Accedian Networks

$mib = 'ACD-DESC-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.22420.1.1';
$config['mibs'][$mib]['mib_dir'] = 'accedian';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'acdDescSerialNumber.0'); // ACD-DESC-MIB::acdDescSerialNumber.0 = STRING: G080-0157
$config['mibs'][$mib]['hardware'][] = array('oid' => 'acdDescCommercialName.0'); // ACD-DESC-MIB::acdDescCommercialName.0 = STRING: AMO-10000-NE

//$config['mibs'][$mib]['processor']['acdDescCpuUsageCurrent']   = array('type' => 'static', 'descr' => 'System CPU', 'oid' => 'acdDescCpuUsageCurrent.0', 'oid_num' => '.1.3.6.1.4.1.22420.1.1.20.0');
$config['mibs'][$mib]['processor']['acdDescCpuUsageAverage60'] = array('type' => 'static', 'descr' => 'System CPU', 'oid' => 'acdDescCpuUsageAverage60.0', 'oid_num' => '.1.3.6.1.4.1.22420.1.1.23.0');
$config['mibs'][$mib]['sensor']['acdDescTsTable']['tables'][] = array(
  'table'               => 'acdDescTsTable',
  'class'               => 'temperature',
  'descr'               => 'Sensor',
  'oid'                 => 'acdDescTsCurrentTemp',
  'oid_num'             => '.1.3.6.1.4.1.22420.1.1.12.1.2',
  'min'                 => 0,
  'scale'               => 1,
  'oid_limit_high_warn' => 'acdDescTsFirstThres',
  'oid_limit_high'      => 'acdDescTsSecondThres'
);

// Ruckus

$mib = 'RUCKUS-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25053.1.1.11.1';
$config['mibs'][$mib]['mib_dir'] = 'ruckus';
$config['mibs'][$mib]['descr'] = 'Ruckus Wireless system MIB containing resource utilisation and system configuration.';
$config['mibs'][$mib]['processor']['ruckusSystemCPUUtil'] = array('type' => 'static', 'descr' => 'System CPU', 'oid' => 'ruckusSystemCPUUtil.0', 'oid_num' => '.1.3.6.1.4.1.25053.1.1.11.1.1.1.1.0');

// RUCKUS-ZD-SYSTEM-MIB

$mib = 'RUCKUS-ZD-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25053.1.2.1.1';
$config['mibs'][$mib]['mib_dir'] = 'ruckus';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'ruckusZDSystemSerialNumber.0'); // RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemSerialNumber.0 = <removed>
$config['mibs'][$mib]['version'][] = array('oid' => 'ruckusZDSystemVersion.0'); // RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemVersion.0 = 9.8.1.0 build 101
$config['mibs'][$mib]['hardware'][] = array('oid' => 'ruckusZDSystemModel.0'); // RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemModel.0 = zd1112

/*
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemName.0 = <removed>
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemIPAddr.0 = 192.168.x.x
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemMacAddr.0 = 8c:c:90:xx:xx:xx
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemUptime.0 = 46:2:37:16.77
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemLicensedAPs.0 = 12
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemMaxSta.0 = 1250
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemCountryCode.0 = "US"
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemAdminName.0 = ********
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemAdminPassword.0 = ********
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemStatus.0 = noredundancy
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemPeerConnectedStatus.0 = disconnected
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemNEId.0 =
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemManufacturer.0 = Ruckus Wireless
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemSoftwareName.0 = zd3k_9.8.1.0 build 101.img
RUCKUS-ZD-SYSTEM-MIB::ruckusZDSystemMgmtVlanID.0 = 1
*/

// Redback

$mib = 'RBN-SUBSCRIBER-ACTIVE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2352.2.27';
$config['mibs'][$mib]['mib_dir'] = 'redback';
$config['mibs'][$mib]['descr'] = 'Redback SUBSCRIBER MIB for active subscribers';

// SAF

$mib = 'SAF-IPRADIO';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'saf';
$config['mibs'][$mib]['descr'] = 'SAF Tehnika P2P IP Radios';
$config['mibs'][$mib]['hardware'][] = array('oid' => 'product.0'); // SAF-IPRADIO::product.0 = STRING: "CFIP Lumina FODU"

$config['mibs'][$mib]['sensor']['sysTemperature']['indexes'][0] = array('descr' => 'System Temperature', 'class' => 'temperature', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.7571.100.1.1.5.1.1.1.5.0');

// Eltek

$mib = 'ELTEK-DISTRIBUTED-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.12148';
$config['mibs'][$mib]['mib_dir'] = 'eltek';
$config['mibs'][$mib]['descr'] = 'Eltek MIB';
$config['mibs'][$mib]['serial'][] = array('oid' => 'systemSiteInfoSystemSeriaNum.0');

$config['mibs'][$mib]['sensor']['batteryVoltage']['indexes'][0] = array('descr' => 'Battery Voltage', 'class' => 'voltage', 'measured' => 'battery', 'scale' => 0.01, 'oid_num' => '.1.3.6.1.4.1.12148.9.3.2.0');
$config['mibs'][$mib]['sensor']['batteryCurrent']['indexes'][0] = array('descr' => 'Battery Current', 'class' => 'current', 'measured' => 'battery', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.12148.9.3.3.0');
$config['mibs'][$mib]['sensor']['batteryTemp']['indexes'][0] = array('descr' => 'Battery Temperature', 'class' => 'temperature', 'measured' => 'battery', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.12148.9.3.4.0');

$config['mibs'][$mib]['status']['batteryBreakerStatus']['indexes'][0] = array('descr' => 'Battery Breaker Status', 'measured' => 'battery', 'type' => 'batteryBreakerStatus', 'oid_num' => '.1.3.6.1.4.1.12148.9.3.5.0');

$type = 'batteryBreakerStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'alarm', 'event' => 'alert');

//$type = 'rectifierStatusStatus';
$type = 'eltek-distributed-mib_rectifierStatusStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'notPresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'alarm', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'notUsed', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'disabled', 'event' => 'exclude');

// Eltex

$mib = 'ELTEX-OMS';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.35265.4';
$config['mibs'][$mib]['mib_dir'] = 'eltex';
$config['mibs'][$mib]['descr'] = 'Mib for eltex devices, that support OMS';
$config['mibs'][$mib]['serial'][] = array('oid' => 'omsSerialNumber.0');
//ELTEX-OMS::omsFwRev.0 = STRING: Eltex LTP-8X:rev.C software version 3.26.1 build 1383 on 31.01.2017 11:35
$config['mibs'][$mib]['hardware'][] = array('oid' => 'omsFwRev.0', 'transformations' => array(array('action' => 'regex_replace', 'from' => '/^Eltex +([\w\-\:\.]+) .* version ([\d\.]+) .*/', 'to' => '$1')));
$config['mibs'][$mib]['version'][] = array('oid' => 'omsFwRev.0', 'transformations' => array(array('action' => 'regex_replace', 'from' => '/^Eltex +([\w\-\:\.]+) .* version ([\d\.]+) .*/', 'to' => '$2')));

$mib = 'ELTEX-LTP8X';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.35265.1.22';
$config['mibs'][$mib]['mib_dir'] = 'eltex';
$config['mibs'][$mib]['descr'] = 'Mib for eltex GPON devices';

$config['mibs'][$mib]['sensor']['ltp8xPONChannelSFPTemperature']['tables'][] = array(
  'table'   => 'ltp8xPONChannelSFPTemperature',
  'class'   => 'temperature',
  'descr'   => 'SFP',
  'oid'     => 'ltp8xPONChannelSFPTemperature',
  'oid_num' => '.1.3.6.1.4.1.35265.1.22.2.1.1.10',
  'min'     => 0,
  'max'     => 300,
  'scale'   => 1,
);
$config['mibs'][$mib]['sensor']['ltp8xPONChannelSFPVoltage']['tables'][] = array(
  'table'   => 'ltp8xPONChannelSFPVoltage',
  'class'   => 'voltage',
  'descr'   => 'SFP',
  'oid'     => 'ltp8xPONChannelSFPVoltage',
  'oid_num' => '.1.3.6.1.4.1.35265.1.22.2.1.1.11',
  'min'     => 32767,
  'scale'   => 0.000001, // in uV
);
$config['mibs'][$mib]['sensor']['ltp8xPONChannelSFPTxBiasCurrent']['tables'][] = array(
  'table'   => 'ltp8xPONChannelSFPTxBiasCurrent',
  'class'   => 'current',
  'descr'   => 'SFP',
  'oid'     => 'ltp8xPONChannelSFPTxBiasCurrent',
  'oid_num' => '.1.3.6.1.4.1.35265.1.22.2.1.1.12',
  'max'     => 32765,
  'scale'   => 0.000001, // in uA
);
$config['mibs'][$mib]['sensor']['ltp8xPONChannelTxPower']['tables'][] = array(
  'table'   => 'ltp8xPONChannelTxPower',
  'class'   => 'dbm',
  'descr'   => 'SFP',
  'oid'     => 'ltp8xPONChannelTxPower',
  'oid_num' => '.1.3.6.1.4.1.35265.1.22.2.1.1.9',
  'max'     => 32765,
  'scale'   => 0.001 // in dBm * 1000
);

$mib = 'ELTEX-LTP8X-STANDALONE';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.35265.1.22.1';
$config['mibs'][$mib]['mib_dir'] = 'eltex';
$config['mibs'][$mib]['descr'] = 'Mib for eltex GPON devices STANDALONE';

$config['mibs'][$mib]['processor']['ltp8xCPULoadAverage5Minutes'] = array('type'      => 'static',
                                                                          'oid_descr' => 'ltp8xCPULoadAverage5Minutes',
                                                                          'oid'       => 'ltp8xCPULoadAverage5Minutes.0',
                                                                          'oid_num'   => '.1.3.6.1.4.1.35265.1.22.1.10.4.0',
                                                                          'scale'     => 0.01);
$config['mibs'][$mib]['mempool']['ltp8xRAM'] = array('type'     => 'static', 'descr' => 'RAM', 'scale' => 1,
                                                     'oid_free' => 'ltp8xRAMFree.0', 'oid_free_num' => '.1.3.6.1.4.1.35265.1.22.1.10.2.0', //ELTEX-LTP8X-STANDALONE::ltp8xDiskFreeSpace.0 = Gauge32: 15923
                                                     'total'    => 519045120, // 495*1024*1024 - From CLI: Free RAM/Total RAM (Mbytes):      269/495
);

//ELTEX-LTP8X-STANDALONE::ltp8xSensor1Temperature.0 = Gauge32: 32
//ELTEX-LTP8X-STANDALONE::ltp8xSensor1TemperatureExt.0 = INTEGER: 32
$config['mibs'][$mib]['sensor']['ltp8xSensorTemperature']['indexes'][1] = array('descr' => 'Sensor 1', 'class' => 'temperature', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.35265.1.22.1.10.10.0');
//ELTEX-LTP8X-STANDALONE::ltp8xSensor2Temperature.0 = Gauge32: 44
//ELTEX-LTP8X-STANDALONE::ltp8xSensor2TemperatureExt.0 = INTEGER: 44
$config['mibs'][$mib]['sensor']['ltp8xSensorTemperature']['indexes'][2] = array('descr' => 'Sensor 2', 'class' => 'temperature', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.35265.1.22.1.10.11.0');

$config['mibs'][$mib]['sensor']['ltp8xFanRPM']['indexes'][0] = array('descr' => 'Fan 0', 'class' => 'fanspeed', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.35265.1.22.1.10.7.0', 'oid_limit_low' => '.1.3.6.1.4.1.35265.1.22.1.10.20.0', 'oid_limit_high' => '.1.3.6.1.4.1.35265.1.22.1.10.21.0');
$config['mibs'][$mib]['sensor']['ltp8xFanRPM']['indexes'][1] = array('descr' => 'Fan 1', 'class' => 'fanspeed', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.35265.1.22.1.10.9.0', 'oid_limit_low' => '.1.3.6.1.4.1.35265.1.22.1.10.20.0', 'oid_limit_high' => '.1.3.6.1.4.1.35265.1.22.1.10.21.0');


//ELTEX-LTP8X-STANDALONE::ltp8xFan0Active.0 = INTEGER: true(1)
//ELTEX-LTP8X-STANDALONE::ltp8xFan0RPM.0 = Gauge32: 6540
//ELTEX-LTP8X-STANDALONE::ltp8xFan1Active.0 = INTEGER: true(1)
//ELTEX-LTP8X-STANDALONE::ltp8xFan1RPM.0 = Gauge32: 6480
//ELTEX-LTP8X-STANDALONE::ltp8xFanMinRPM.0 = Gauge32: 2000
//ELTEX-LTP8X-STANDALONE::ltp8xFanMaxRPM.0 = Gauge32: 12000


$mib = 'ELTEX-LTE8ST';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.35265.1.21';
$config['mibs'][$mib]['mib_dir'] = 'eltex';
$config['mibs'][$mib]['descr'] = 'Mib for eltex GEPON devices';
$config['mibs'][$mib]['processor']['lte8stSystemCPULoadAverage5Minutes'] = array('type'      => 'static',
                                                                                 'oid_descr' => 'lte8stSystemCPULoadAverage5Minutes',
                                                                                 'oid'       => 'lte8stSystemCPULoadAverage5Minutes.0',
                                                                                 'oid_num'   => '.1.3.6.1.4.1.35265.1.21.1.33.0');
$config['mibs'][$mib]['mempool']['lte8stSystemRAM'] = array('type'     => 'static', 'descr' => 'RAM', 'scale' => 1,
                                                            'oid_free' => 'lte8stSystemRAMFree.0', 'oid_free_num' => '.1.3.6.1.4.1.35265.1.21.1.31.0', // ELTEX-LTE8ST::lte8stSystemRAMFree.0 = Gauge32: 136638464
                                                            'total'    => 254803968, // From CLI: Free RAM/Total RAM (Mbytes): 125/243
);

// ELTEX-LTE8ST::lte8stSystemPonPorts.0 = INTEGER: 2
$config['mibs'][$mib]['sensor']['lte8stSystemPonPorts']['indexes'][0] = array('descr' => 'PON ports', 'oid' => 'lte8stSystemPonPorts.0', 'min' => 0, 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.35265.1.21.1.8.0', 'measured' => 'device');
// ELTEX-LTE8ST::lte8stSystemUplinkPorts.0 = INTEGER: 4
$config['mibs'][$mib]['sensor']['lte8stSystemUplinkPorts']['indexes'][0] = array('descr' => 'Uplink ports', 'oid' => 'lte8stSystemUplinkPorts.0', 'min' => 0, 'scale' => 1);

// ELTEX-LTE8ST::lte8stSystemP2PEnabled.0 = INTEGER: false(0)
$config['mibs'][$mib]['status']['lte8stSystemP2PEnabled']['indexes'][0] = array('descr' => 'P2P Enabled', 'oid' => 'lte8stSystemP2PEnabled.0', 'measured' => 'device', 'type' => 'BoolValue', 'oid_num' => '.1.3.6.1.4.1.35265.1.21.1.15.0');

$config['mibs'][$mib]['states']['BoolValue'][0] = array('name' => 'false', 'event' => 'ok');
$config['mibs'][$mib]['states']['BoolValue'][1] = array('name' => 'true', 'event' => 'ok');


// Accuview

$mib = 'ACCUENERGY-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.39604';
$config['mibs'][$mib]['mib_dir'] = 'accuenergy';
$config['mibs'][$mib]['descr'] = 'AccuEnergy Accuvim II';
$config['mibs'][$mib]['sensor']['phaseVoltageA']['indexes'][0] = array('descr' => 'Phase A Voltage', 'class' => 'voltage', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.1.1.0');
$config['mibs'][$mib]['sensor']['phaseVoltageB']['indexes'][0] = array('descr' => 'Phase B Voltage', 'class' => 'voltage', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.1.2.0');
$config['mibs'][$mib]['sensor']['phaseVoltageC']['indexes'][0] = array('descr' => 'Phase C Voltage', 'class' => 'voltage', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.1.3.0');
$config['mibs'][$mib]['sensor']['averagePhaseVoltage']['indexes'][0] = array('descr' => 'Average Phase Voltage', 'class' => 'voltage', 'measured' => 'system', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.1.4.0');
$config['mibs'][$mib]['sensor']['lineVoltageAB']['indexes'][0] = array('descr' => 'Line Voltage AB', 'class' => 'voltage', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.2.1.0');
$config['mibs'][$mib]['sensor']['lineVoltageBC']['indexes'][0] = array('descr' => 'Line Voltage BC', 'class' => 'voltage', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.2.2.0');
$config['mibs'][$mib]['sensor']['lineVoltageCA']['indexes'][0] = array('descr' => 'Line Voltage CA', 'class' => 'voltage', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.2.3.0');
$config['mibs'][$mib]['sensor']['averageLineVoltage']['indexes'][0] = array('descr' => 'Average Line Voltage', 'class' => 'voltage', 'measured' => 'system', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.2.4.0');
$config['mibs'][$mib]['sensor']['phaseCurrentA']['indexes'][0] = array('descr' => 'Phase A Current', 'class' => 'current', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.3.1.0');
$config['mibs'][$mib]['sensor']['phaseCurrentB']['indexes'][0] = array('descr' => 'Phase B Current', 'class' => 'current', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.3.2.0');
$config['mibs'][$mib]['sensor']['phaseCurrentC']['indexes'][0] = array('descr' => 'Phase C Current', 'class' => 'current', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.3.3.0');
$config['mibs'][$mib]['sensor']['averageCurrent']['indexes'][0] = array('descr' => 'Average Current', 'class' => 'current', 'measured' => 'system', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.3.4.0');
$config['mibs'][$mib]['sensor']['neutralCurrent']['indexes'][0] = array('descr' => 'Neutral Current', 'class' => 'current', 'measured' => 'system', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.3.5.0');
$config['mibs'][$mib]['sensor']['phaseAPower']['indexes'][0] = array('descr' => 'Phase A Power', 'class' => 'power', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.4.1.0');
$config['mibs'][$mib]['sensor']['phaseBPower']['indexes'][0] = array('descr' => 'Phase B Power', 'class' => 'power', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.4.2.0');
$config['mibs'][$mib]['sensor']['phaseCPower']['indexes'][0] = array('descr' => 'Phase C Power', 'class' => 'power', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.4.3.0');
$config['mibs'][$mib]['sensor']['systemPower']['indexes'][0] = array('descr' => 'System Power', 'class' => 'power', 'measured' => 'system', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.4.4.0');
$config['mibs'][$mib]['sensor']['phaseAReactivePower']['indexes'][0] = array('descr' => 'Phase A Reactive Power', 'class' => 'rpower', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.5.1.0');
$config['mibs'][$mib]['sensor']['phaseBReactivePower']['indexes'][0] = array('descr' => 'Phase B Reactive Power', 'class' => 'rpower', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.5.2.0');
$config['mibs'][$mib]['sensor']['phaseCReactivePower']['indexes'][0] = array('descr' => 'Phase C Reactive Power', 'class' => 'rpower', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.5.3.0');
$config['mibs'][$mib]['sensor']['systemReactivePower']['indexes'][0] = array('descr' => 'System Reactive Power', 'class' => 'rpower', 'measured' => 'system', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.5.4.0');
$config['mibs'][$mib]['sensor']['phaseAApparentPower']['indexes'][0] = array('descr' => 'Phase A Apparent Power', 'class' => 'apower', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.6.1.0');
$config['mibs'][$mib]['sensor']['phaseBApparentPower']['indexes'][0] = array('descr' => 'Phase B Apparent Power', 'class' => 'apower', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.6.2.0');
$config['mibs'][$mib]['sensor']['phaseCApparentPower']['indexes'][0] = array('descr' => 'Phase C Apparent Power', 'class' => 'apower', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.6.3.0');
$config['mibs'][$mib]['sensor']['systemApparentPower']['indexes'][0] = array('descr' => 'System Apparent Power', 'class' => 'apower', 'measured' => 'system', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.6.4.0');
$config['mibs'][$mib]['sensor']['phaseAPowerFactor']['indexes'][0] = array('descr' => 'Phase A Power Factor', 'class' => 'powerfactor', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.7.1.0');
$config['mibs'][$mib]['sensor']['phaseBPowerFactor']['indexes'][0] = array('descr' => 'Phase B Power Factor', 'class' => 'powerfactor', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.7.2.0');
$config['mibs'][$mib]['sensor']['phaseCPowerFactor']['indexes'][0] = array('descr' => 'Phase C Power Factor', 'class' => 'powerfactor', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.7.3.0');
$config['mibs'][$mib]['sensor']['systemPowerFactor']['indexes'][0] = array('descr' => 'System Power Factor', 'class' => 'powerfactor', 'measured' => 'system', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.7.4.0');
$config['mibs'][$mib]['sensor']['powerDemand']['indexes'][0] = array('descr' => 'Power Demand', 'class' => 'power', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.8.1.0');
$config['mibs'][$mib]['sensor']['reactivePowerDemand']['indexes'][0] = array('descr' => 'Reactive Power Demand', 'class' => 'rpower', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.8.2.0');
$config['mibs'][$mib]['sensor']['apparentPowerDemand']['indexes'][0] = array('descr' => 'Apparent Power Demand', 'class' => 'apower', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.8.3.0');
$config['mibs'][$mib]['sensor']['phaseACurrentDemand']['indexes'][0] = array('descr' => 'Phase A Current Demand', 'class' => 'current', 'measured' => 'system', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.8.4.0');
$config['mibs'][$mib]['sensor']['phaseBCurrentDemand']['indexes'][0] = array('descr' => 'Phase B Current Demand', 'class' => 'current', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.8.5.0');
$config['mibs'][$mib]['sensor']['phaseCCurrentDemand']['indexes'][0] = array('descr' => 'Phase C Current Demand', 'class' => 'current', 'measured' => 'system', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.8.6.0');
$config['mibs'][$mib]['sensor']['frequency']['indexes'][0] = array('descr' => 'Frequency', 'class' => 'frequency', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.9.0');
//$config['mibs'][$mib]['sensor']['loadFeature']['indexes'][0]       = array('descr' => 'Load Characteristic', 'class' => 'frequency', 'measured' => 'phase', 'scale' => 161616, 'oid_num' => '.1.3.6.1.4.1.39604.1.1.1.1.1.1.10.0');


// EPPC UPS

$mib = 'EPPC-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.935';
$config['mibs'][$mib]['mib_dir'] = 'eppc';
$config['mibs'][$mib]['descr'] = 'EPPC UPS devices';
$config['mibs'][$mib]['serial'][] = array('oid' => 'upsEIndentityUPSSerialNumber.0');

// FireBrick

$mib = 'FIREBRICK-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.24693';
$config['mibs'][$mib]['mib_dir'] = 'firebrick';
$config['mibs'][$mib]['descr'] = 'Firebrick devices';
$config['mibs'][$mib]['sensor_walk_exclude'] = TRUE; // Disable sensors snmpwalk caching for this MIB, this produce device hang
$config['mibs'][$mib]['sensor']['fbSensorVoltage']['indexes'][1] = array('descr' => '+12v Supply A', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.1.1');
$config['mibs'][$mib]['sensor']['fbSensorVoltage']['indexes'][2] = array('descr' => '+12v Supply B', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.1.2');
$config['mibs'][$mib]['sensor']['fbSensorVoltage']['indexes'][3] = array('descr' => '+12v Combined ', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.1.3');
$config['mibs'][$mib]['sensor']['fbSensorVoltage']['indexes'][4] = array('descr' => '+3.3v Reference', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.1.4');
$config['mibs'][$mib]['sensor']['fbSensorVoltage']['indexes'][5] = array('descr' => '+1.8v Reference', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.1.5');
$config['mibs'][$mib]['sensor']['fbSensorVoltage']['indexes'][6] = array('descr' => '+1.2v Reference', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.1.6');
$config['mibs'][$mib]['sensor']['fbSensorVoltage']['indexes'][7] = array('descr' => '+1.1v Reference', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.1.7');
$config['mibs'][$mib]['sensor']['fbSensorVoltage']['indexes'][8] = array('descr' => '+3.3v Fan Supply', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.1.8');
$config['mibs'][$mib]['sensor']['fbSensorVoltage']['indexes'][9] = array('descr' => '+1.2v Fan Supply', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.1.9');
$config['mibs'][$mib]['sensor']['fbSensorTemperature']['indexes'][1] = array('descr' => 'Fan Controller', 'class' => 'temperature', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.2.1');
$config['mibs'][$mib]['sensor']['fbSensorTemperature']['indexes'][2] = array('descr' => 'Processor', 'class' => 'temperature', 'measured' => 'processor', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.2.2');
$config['mibs'][$mib]['sensor']['fbSensorTemperature']['indexes'][3] = array('descr' => 'Realtime Clock', 'class' => 'temperature', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.24693.1.2.3');
$config['mibs'][$mib]['sensor']['fbSensorFanspeed']['indexes'][1] = array('descr' => 'Fan 1', 'class' => 'fanspeed', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.24693.1.3.1');
$config['mibs'][$mib]['sensor']['fbSensorFanspeed']['indexes'][2] = array('descr' => 'Fan 2', 'class' => 'fanspeed', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.24693.1.3.2');

$mib = 'DASAN-SWITCH-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6296.9.1';
$config['mibs'][$mib]['mib_dir'] = 'dasan';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'dsSerialNumber.0');    // DASAN-SWITCH-MIB::dsSerialNumber.0 = STRING: MB7R0QD212A1249
$config['mibs'][$mib]['version'][] = array('oid' => 'dsOsVersion.0', 'transformations' => array(array('action' => 'regex_replace', 'from' => '/^(\d[\w\.\-]+).*/', 'to' => '$1'))); // DASAN-SWITCH-MIB::dsOsVersion.0 = STRING: 5.09 #2105
//$config['mibs'][$mib]['version'][]    = array('oid' => 'dsFirmwareVersion.0'); // DASAN-SWITCH-MIB::dsFirmwareVersion.0 = STRING: 6.16
$config['mibs'][$mib]['hardware'][] = array('oid' => 'dsHardwareVersion.0'); // DASAN-SWITCH-MIB::dsHardwareVersion.0 = STRING: DS-VD-23N-B0

$config['mibs'][$mib]['processor']['dsCpuLoad1m'] = array('type' => 'static', 'descr' => 'System CPU', 'oid' => 'dsCpuLoad1m.0');
//$config['mibs'][$mib]['processor']['dsCpuLoad10m'] = array('type' => 'static', 'descr' => 'System CPU', 'oid' => 'dsCpuLoad10m.0');
$config['mibs'][$mib]['mempool']['dsSwitchSystem'] = array('type'      => 'static', 'descr' => 'Memory',
                                                           'oid_total' => 'dsTotalMem.0', 'oid_total_num' => '.1.3.6.1.4.1.6296.9.1.1.1.14.0', // DASAN-SWITCH-MIB::dsTotalMem.0 = INTEGER: 508465152
                                                           'oid_free'  => 'dsFreeMem.0', 'oid_free_num' => '.1.3.6.1.4.1.6296.9.1.1.1.16.0', // DASAN-SWITCH-MIB::dsFreeMem.0 = INTEGER: 437878784
                                                           'oid_used'  => 'dsUsedMem.0', 'oid_used_num' => '.1.3.6.1.4.1.6296.9.1.1.1.15.0', // DASAN-SWITCH-MIB::dsUsedMem.0 = INTEGER: 70586368
);

$mib = 'DELL-RAC-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = 'Dell iDRAC v6 and lower devices';
$config['mibs'][$mib]['serial'][] = array('oid' => 'drsSystemServiceTag.0'); // DELL-RAC-MIB::drsSystemServiceTag.0 = STRING: "CGJ2H5J"
$config['mibs'][$mib]['version'][] = array('oid' => 'drsFirmwareVersion.0'); // DELL-RAC-MIB::drsFirmwareVersion.0 = STRING: "1.23.23"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'drsProductShortName.0'); // DELL-RAC-MIB::drsProductShortName.0 = STRING: "iDRAC7"
$config['mibs'][$mib]['asset_tag'][] = array('oid' => 'drsProductChassisAssetTag.0');
$config['mibs'][$mib]['ra_url_http'][] = array('oid' => 'drsProductURL.0'); // DELL-RAC-MIB::drsProductURL.0 = STRING: "https://192.168.2.1:443"

$config['mibs'][$mib]['status']['drsGlobalSystemStatus']['indexes'][0] = array('descr' => 'Overall System Status', 'measured' => 'device', 'type' => 'DellStatus', 'oid_num' => '.1.3.6.1.4.1.674.10892.2.2.1.0');
$config['mibs'][$mib]['status']['drsGlobalCurrStatus']['indexes'][0] = array('descr' => 'Chassis Status', 'measured' => 'chassis', 'type' => 'DellStatus', 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.1.0');
$config['mibs'][$mib]['status']['drsIOMCurrStatus']['indexes'][0] = array('descr' => 'IOM Status', 'measured' => 'device', 'type' => 'DellStatus', 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.2.0');
$config['mibs'][$mib]['status']['drsKVMCurrStatus']['indexes'][0] = array('descr' => 'iKVM Status', 'measured' => 'device', 'type' => 'DellStatus', 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.3.0');
$config['mibs'][$mib]['status']['drsRedCurrStatus']['indexes'][0] = array('descr' => 'Redundancy Status', 'measured' => 'device', 'type' => 'DellStatus', 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.4.0');
$config['mibs'][$mib]['status']['drsPowerCurrStatus']['indexes'][0] = array('descr' => 'Power Status', 'measured' => 'power', 'type' => 'DellStatus', 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.5.0');
$config['mibs'][$mib]['status']['drsFanCurrStatus']['indexes'][0] = array('descr' => 'Fan Status', 'measured' => 'fan', 'type' => 'DellStatus', 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.6.0');
$config['mibs'][$mib]['status']['drsBladeCurrStatus']['indexes'][0] = array('descr' => 'Blade Status', 'measured' => 'device', 'type' => 'DellStatus', 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.7.0');
$config['mibs'][$mib]['status']['drsTempCurrStatus']['indexes'][0] = array('descr' => 'Temperature Status', 'measured' => 'device', 'type' => 'DellStatus', 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.8.0');
$config['mibs'][$mib]['status']['drsCMCCurrStatus']['indexes'][0] = array('descr' => 'CMC Status', 'measured' => 'device', 'type' => 'DellStatus', 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.9.0');

$config['mibs'][$mib]['states']['DellStatus'][1] = array('name' => 'other', 'event' => 'exclude'); // the status of the object is not one of the following:
$config['mibs'][$mib]['states']['DellStatus'][2] = array('name' => 'unknown', 'event' => 'ignore');  // the status of the object is unknown (not known or monitored)
$config['mibs'][$mib]['states']['DellStatus'][3] = array('name' => 'ok', 'event' => 'ok');      // the status of the object is ok
$config['mibs'][$mib]['states']['DellStatus'][4] = array('name' => 'nonCritical', 'event' => 'warning'); // the status of the object is warning, non-critical
$config['mibs'][$mib]['states']['DellStatus'][5] = array('name' => 'critical', 'event' => 'alert');   // the status of the object is critical (failure)
$config['mibs'][$mib]['states']['DellStatus'][6] = array('name' => 'nonRecoverable', 'event' => 'alert');   // the status of the object is non-recoverable (dead)

$config['mibs'][$mib]['states']['dell-rac-mib-slot-state'][1] = array('name' => 'absent', 'event' => 'ok');
$config['mibs'][$mib]['states']['dell-rac-mib-slot-state'][2] = array('name' => 'none', 'event' => 'ok');
$config['mibs'][$mib]['states']['dell-rac-mib-slot-state'][3] = array('name' => 'basic', 'event' => 'ok');
$config['mibs'][$mib]['states']['dell-rac-mib-slot-state'][4] = array('name' => 'off', 'event' => 'ok');

$config['mibs'][$mib]['sensor']['drsChassisFrontPanelAmbientTemperature']['indexes'][0] = array('descr'      => 'Chassis Front Panel Temperature', 'class' => 'temperature', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.10.0',
                                                                                                'rename_rrd' => 'dell-rac-front');      // old rrd index
$config['mibs'][$mib]['sensor']['drsCMCAmbientTemperature']['indexes'][0] = array('descr'      => 'CMC Ambient Temperature', 'class' => 'temperature', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.11.0',
                                                                                  'rename_rrd' => 'dell-rac-cmcambient'); // old rrd index
$config['mibs'][$mib]['sensor']['drsCMCProcessorTemperature']['indexes'][0] = array('descr'      => 'CMC Processor Temperature', 'class' => 'temperature', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.674.10892.2.3.1.12.0',
                                                                                    'rename_rrd' => 'dell-rac-cmccpu');     // old rrd index

$mib = 'DGPPDU-MIB';
$config['mibs'][$mib]['enable'] = 1;
//$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'digipower';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'devVersion.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'pdu01ModelNo.0');
$config['mibs'][$mib]['sensor']['pdu01Value']['indexes'][0] = array('descr' => 'Input', 'class' => 'current', 'measured' => 'device', 'scale' => 0.1, 'min' => 0);
$config['mibs'][$mib]['sensor']['pdu01Voltage']['indexes'][0] = array('descr' => 'Input', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.1, 'min' => 0);

$config['mibs'][$mib]['sensor']['devTemperature']['indexes'][0] = array('descr' => 'Temperature', 'class' => 'temperature', 'measured' => 'device', 'scale' => 0.1, 'min' => 0);
$config['mibs'][$mib]['sensor']['devHumidity']['indexes'][0] = array('descr' => 'Humidity', 'class' => 'humidity', 'measured' => 'device', 'scale' => 1, 'min' => 0);

$mib = 'DGPUPS-MIB';
$config['mibs'][$mib]['enable'] = 1;
//$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'digipower';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['sensor']['upsEnvTemperature']['indexes'][0] = array('descr'          => 'Temperature', 'class' => 'temperature', 'measured' => 'device', 'scale' => 0.1, 'min' => 0,
                                                                           'oid_num'        => '.1.3.6.1.4.1.17420.1.1.2.9.1.1.0',
                                                                           'limit_scale'    => 0.1,
                                                                           'oid_limit_low'  => 'upsEnvUnderTemperature.0',
                                                                           'oid_limit_high' => 'upsEnvOverTemperature.0');
$config['mibs'][$mib]['sensor']['upsEnvHumidity']['indexes'][0] = array('descr'          => 'Humidity', 'class' => 'humidity', 'measured' => 'device', 'scale' => 1, 'min' => 0,
                                                                        'oid_num'        => '.1.3.6.1.4.1.17420.1.1.2.9.1.2.0',
                                                                        'oid_limit_low'  => 'upsEnvUnderHumidity.0',
                                                                        'oid_limit_high' => 'upsEnvOverHumidity.0');


$mib = 'DPS-MIB-V38';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2682';
$config['mibs'][$mib]['mib_dir'] = 'dps';
$config['mibs'][$mib]['descr'] = '';

/* NOT correct, will fixed after I get access or debug
$config['mibs'][$mib]['states']['dpsRTUAState'][0] = array('name' => 'Clear', 'event' => 'ok');
$config['mibs'][$mib]['states']['dpsRTUAState'][1] = array('name' => 'Alarm', 'event' => 'alert');

$config['mibs'][$mib]['states']['thresholds'][0] = array('name' => 'noAlarms', 'event' => 'ok');
$config['mibs'][$mib]['states']['thresholds'][1] = array('name' => 'minorUnder', 'event' => 'alert');
$config['mibs'][$mib]['states']['thresholds'][2] = array('name' => 'minorOver', 'event' => 'alert');
$config['mibs'][$mib]['states']['thresholds'][3] = array('name' => 'majorUnder', 'event' => 'alert');
$config['mibs'][$mib]['states']['thresholds'][4] = array('name' => 'majorOver', 'event' => 'alert');
$config['mibs'][$mib]['states']['thresholds'][5] = array('name' => 'notDetected', 'event' => 'alert');
*/

// Ceragon

$mib = 'CERAGON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2281';
$config['mibs'][$mib]['descr'] = 'Ceragon FibeAir.';
$config['mibs'][$mib]['mib_dir'] = 'ceragon';

// C-data
$mib = 'FD-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
//$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3459.1.3.1';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['mib_dir'] = 'cdata';
$config['mibs'][$mib]['serial'][] = array('oid' => 'chassisFactorySerial.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'mainCardSWVersion.0'); //FD-SYSTEM-MIB::mainCardSWVersion.0 = STRING: OLT-SWE-2.0.31
$config['mibs'][$mib]['hardware'][] = array('oid' => 'mainCardHWRevision.0'); //FD-SYSTEM-MIB::mainCardHWRevision.0 = STRING: OLT-4.1
$config['mibs'][$mib]['features'][] = array('oid' => 'chassisType.0'); //FD-SYSTEM-MIB::chassisType.0 = INTEGER: CHASSIS(16843009)

$config['mibs'][$mib]['sensor']['chassisTemperature']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Chassis Temperature', 'oid' => 'chassisTemperature.0', 'min' => 0, 'scale' => 1); //FD-SYSTEM-MIB::chassisTemperature.0

$config['mibs'][$mib]['status']['sysMajAlarmLed']['indexes'][0] = array('descr' => 'Status of main card MAJ led', 'oid' => 'sysMajAlarmLed.0', 'measured' => '', 'type' => 'LedStatus', 'oid_num' => '.1.3.6.1.4.1.34592.1.3.1.1.5.0');
$config['mibs'][$mib]['status']['sysCriAlarmLed']['indexes'][0] = array('descr' => 'Status of main card CRJ led', 'oid' => 'sysMajAlarmLed.0', 'measured' => '', 'type' => 'LedStatus', 'oid_num' => '.1.3.6.1.4.1.34592.1.3.1.1.6.0');

$config['mibs'][$mib]['status']['PowerStatusBit']['indexes'][0] = array('class' => 'power', 'descr' => 'Power Statuses', 'oid' => 'PowerStatusBit.0', 'measured' => '', 'type' => 'powerStatusBit', 'oid_num' => '.1.3.6.1.4.1.34592.1.3.1.3.5.0');
$config['mibs'][$mib]['status']['fanStatusBit']['indexes'][0] = array('descr' => 'Fan Statuses', 'oid' => 'fanStatusBit.0', 'measured' => 'fan', 'type' => 'fanStatusBit', 'oid_num' => '.1.3.6.1.4.1.34592.1.3.1.3.6.0');

$config['mibs'][$mib]['states']['LedStatus'][1] = array('name' => 'on', 'event' => 'alert');
$config['mibs'][$mib]['states']['LedStatus'][2] = array('name' => 'off', 'event' => 'ok');
$config['mibs'][$mib]['states']['LedStatus'][3] = array('name' => 'blink', 'event' => 'warning');

$config['mibs'][$mib]['states']['powerStatusBit'][0] = array('name' => 'Power off', 'event' => 'alert');
$config['mibs'][$mib]['states']['powerStatusBit'][1] = array('name' => 'Power B off', 'event' => 'warning');
$config['mibs'][$mib]['states']['powerStatusBit'][2] = array('name' => 'Power A off', 'event' => 'warning');
$config['mibs'][$mib]['states']['powerStatusBit'][3] = array('name' => 'ok', 'event' => 'ok');

$config['mibs'][$mib]['states']['fanStatusBit'][0] = array('name' => 'Fans 1-4 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][1] = array('name' => 'Fans 2-4 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][2] = array('name' => 'Fans 1,3,4 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][3] = array('name' => 'Fans 3,4 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][4] = array('name' => 'Fans 1,2,4 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][5] = array('name' => 'Fans 2,4 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][6] = array('name' => 'Fans 1,4 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][7] = array('name' => 'Fans 4 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][8] = array('name' => 'Fans 1-3 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][9] = array('name' => 'Fans 2,3 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][10] = array('name' => 'Fans 1,3 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][11] = array('name' => 'Fans 3 off', 'event' => 'warning');
$config['mibs'][$mib]['states']['fanStatusBit'][12] = array('name' => 'Fans 1,2 off', 'event' => 'alert');
$config['mibs'][$mib]['states']['fanStatusBit'][13] = array('name' => 'Fans 2 off', 'event' => 'warning');
$config['mibs'][$mib]['states']['fanStatusBit'][14] = array('name' => 'Fans 1 off', 'event' => 'warning');
$config['mibs'][$mib]['states']['fanStatusBit'][15] = array('name' => 'All Fans on', 'event' => 'ok');

$mib = 'FD-SWITCH-MIB';
$config['mibs'][$mib]['enable'] = 1;
//$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3459.1.3.2';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['mib_dir'] = 'cdata';

$config['mibs'][$mib]['status']['switchMode']['indexes'][0] = array('descr' => 'Device function operation switch type', 'measured' => 'device', 'type' => 'switchMode', 'oid_num' => '.1.3.6.1.4.1.34592.1.3.2.1.1.0'); // switchMode.0 = INTEGER: normal(3)
$config['mibs'][$mib]['status']['vlanMode']['indexes'][0] = array('descr' => 'vlan mode', 'measured' => 'device', 'type' => 'OperSwitch', 'oid_num' => '.1.3.6.1.4.1.34592.1.3.2.4.4.1.0'); // vlanMode.0 = INTEGER: enable(1)
$config['mibs'][$mib]['status']['trunkBlance']['indexes'][0] = array('descr' => 'Balance Mode in Trunks', 'measured' => 'device', 'type' => 'trunkBlance', 'oid_num' => '.1.3.6.1.4.1.34592.1.3.2.5.1.1.0'); // trunkBlance.0 = INTEGER: balanceMac(1)
$config['mibs'][$mib]['status']['rstpEnable']['indexes'][0] = array('descr' => 'RSTP status', 'measured' => 'device', 'type' => 'OperSwitch', 'oid_num' => '.1.3.6.1.4.1.34592.1.3.2.6.1.1.0'); // rstpEnable.0 = INTEGER: disable(2)
$config['mibs'][$mib]['status']['igmpsnoopingAdmin']['indexes'][0] = array('descr' => 'IGMP snooping status', 'measured' => 'device', 'type' => 'OperSwitch', 'oid_num' => '1.3.6.1.4.1.34592.1.3.2.8.1'); // igmpsnoopingAdmin.0 = INTEGER: enable(1)

$config['mibs'][$mib]['states']['switchMode'][1] = array('name' => 'sniDestinated', 'event' => 'ok');  // -- It is normal
$config['mibs'][$mib]['states']['switchMode'][2] = array('name' => 'transparent', 'event' => 'ok');  // -- It is normal too
$config['mibs'][$mib]['states']['switchMode'][3] = array('name' => 'normal', 'event' => 'ok');  // -- It is normal too

$config['mibs'][$mib]['states']['OperSwitch'][1] = array('name' => 'enable', 'event' => 'ok');  // -- It is normal
$config['mibs'][$mib]['states']['OperSwitch'][2] = array('name' => 'disable', 'event' => 'ok');  // -- It is normal too

$config['mibs'][$mib]['states']['trunkBlance'][1] = array('name' => 'balanceMac', 'event' => 'warning');  // -- It is bad idea to use balanceMac
$config['mibs'][$mib]['states']['trunkBlance'][2] = array('name' => 'balanceIp', 'event' => 'ok');  // -- not bad
$config['mibs'][$mib]['states']['trunkBlance'][3] = array('name' => 'balanceL4Port', 'event' => 'ok');  // -- not bad
$config['mibs'][$mib]['states']['trunkBlance'][4] = array('name' => 'balanceIpMac', 'event' => 'ok');  // -- better than not bad
$config['mibs'][$mib]['states']['trunkBlance'][5] = array('name' => 'balanceL4PortMac', 'event' => 'ok');  // -- The best
$config['mibs'][$mib]['states']['trunkBlance'][6] = array('name' => 'balanceInL2If', 'event' => 'unknown');  // -- unknown feature


// Dell IDRAC-MIB-SMIv2

$mib = 'IDRAC-MIB-SMIv2';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.674.10892.5';
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = 'Dell iDRAC v7 devices';
$config['mibs'][$mib]['ra_url_http'][] = array('oid' => 'racURL.0');

$config['mibs'][$mib]['status']['globalSystemStatus']['indexes'][0] = array('descr' => 'Overall System Status', 'measured' => 'device', 'type' => 'ObjectStatusEnum', 'oid_num' => '.1.3.6.1.4.1.674.10892.5.2.1.0');
$config['mibs'][$mib]['status']['systemLCDStatus']['indexes'][0] = array('descr' => 'System LCD Status', 'measured' => 'device', 'type' => 'ObjectStatusEnum', 'oid_num' => '.1.3.6.1.4.1.674.10892.5.2.2.0');
$config['mibs'][$mib]['status']['globalStorageStatus']['indexes'][0] = array('descr' => 'Global Storage Status', 'measured' => 'device', 'type' => 'ObjectStatusEnum', 'oid_num' => '.1.3.6.1.4.1.674.10892.5.2.3.0');
$config['mibs'][$mib]['status']['systemPowerState']['indexes'][0] = array('descr' => 'System Power State', 'measured' => 'device', 'type' => 'PowerStateStatusEnum', 'oid_num' => '.1.3.6.1.4.1.674.10892.5.2.4.0');

$config['mibs'][$mib]['states']['VoltageDiscreteReadingEnum'][1] = array('name' => 'voltageIsGood', 'event' => 'ok');
$config['mibs'][$mib]['states']['VoltageDiscreteReadingEnum'][2] = array('name' => 'voltageIsBad', 'event' => 'alert');

$config['mibs'][$mib]['states']['StatusProbeEnum'][1] = array('name' => 'other', 'event' => 'exclude'); // (1),  -- probe status is not one of the following:
$config['mibs'][$mib]['states']['StatusProbeEnum'][2] = array('name' => 'unknown', 'event' => 'ignore');  // (2),  -- probe status is unknown (not known or monitored)
$config['mibs'][$mib]['states']['StatusProbeEnum'][3] = array('name' => 'ok', 'event' => 'ok');      // (3),  -- probe is reporting a value within the thresholds
$config['mibs'][$mib]['states']['StatusProbeEnum'][4] = array('name' => 'nonCriticalUpper', 'event' => 'warning'); // (4),  -- probe has crossed upper noncritical threshold
$config['mibs'][$mib]['states']['StatusProbeEnum'][5] = array('name' => 'criticalUpper', 'event' => 'alert');   // (5),  -- probe has crossed upper critical threshold
$config['mibs'][$mib]['states']['StatusProbeEnum'][6] = array('name' => 'nonRecoverableUpper', 'event' => 'alert');   // (6),  -- probe has crossed upper non-recoverable threshold
$config['mibs'][$mib]['states']['StatusProbeEnum'][7] = array('name' => 'nonCriticalLower', 'event' => 'warning'); // (7),  -- probe has crossed lower noncritical threshold
$config['mibs'][$mib]['states']['StatusProbeEnum'][8] = array('name' => 'criticalLower', 'event' => 'alert');   // (8),  -- probe has crossed lower critical threshold
$config['mibs'][$mib]['states']['StatusProbeEnum'][9] = array('name' => 'nonRecoverableLower', 'event' => 'alert');   // (9),  -- probe has crossed lower non-recoverable threshold
$config['mibs'][$mib]['states']['StatusProbeEnum'][10] = array('name' => 'failed', 'event' => 'alert');   // (10)  -- probe is not functional

$config['mibs'][$mib]['states']['ObjectStatusEnum'][1] = array('name' => 'other', 'event' => 'exclude'); // -- the status of the object is not one of the following:
$config['mibs'][$mib]['states']['ObjectStatusEnum'][2] = array('name' => 'unknown', 'event' => 'ignore');  // the status of the object is unknown (not known or monitored)
$config['mibs'][$mib]['states']['ObjectStatusEnum'][3] = array('name' => 'ok', 'event' => 'ok');      // the status of the object is ok
$config['mibs'][$mib]['states']['ObjectStatusEnum'][4] = array('name' => 'nonCritical', 'event' => 'warning'); // the status of the object is warning, non-critical
$config['mibs'][$mib]['states']['ObjectStatusEnum'][5] = array('name' => 'critical', 'event' => 'alert');   // the status of the object is critical (failure)
$config['mibs'][$mib]['states']['ObjectStatusEnum'][6] = array('name' => 'nonRecoverable', 'event' => 'alert');   // the status of the object is non-recoverable (dead)

$config['mibs'][$mib]['states']['PowerStateStatusEnum'][1] = array('name' => 'other', 'event' => 'exclude');
$config['mibs'][$mib]['states']['PowerStateStatusEnum'][2] = array('name' => 'unknown', 'event' => 'ignore');
$config['mibs'][$mib]['states']['PowerStateStatusEnum'][3] = array('name' => 'off', 'event' => 'warning');
$config['mibs'][$mib]['states']['PowerStateStatusEnum'][4] = array('name' => 'on', 'event' => 'ok');

//$config['mibs'][$mib]['status']['voltageProbeStatus']['states']         = 'StatusProbeEnum';
//$config['mibs'][$mib]['status']['voltageProbeDiscreteStatus']['states'] = 'VoltageDiscreteReadingEnum';
//$config['mibs'][$mib]['status']['temperatureProbeStatus']['states']     = 'StatusProbeEnum';

// C&C Power

$mib = 'CCPOWER-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'ccpower';
$config['mibs'][$mib]['descr'] = 'C&C Power Control Commander Plus';
$config['mibs'][$mib]['sensor']['rectifierFloatVoltage']['indexes'][0] = array('descr' => 'Rectifier Float Voltage', 'class' => 'voltage', 'measured' => 'rectifier', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.18642.1.2.1.1.0');
$config['mibs'][$mib]['sensor']['rectifierLoadCurrent']['indexes'][0] = array('descr' => 'Rectifier Load Current', 'class' => 'current', 'measured' => 'rectifier', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.18642.1.2.1.2.0');
$config['mibs'][$mib]['sensor']['batteryCurrent']['indexes'][0] = array('descr' => 'Battery Current', 'class' => 'current', 'measured' => 'battery', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.18642.1.2.2.1.0');
$config['mibs'][$mib]['sensor']['batteryTemperature']['indexes'][0] = array('descr' => 'Battery Temperature', 'class' => 'temperature', 'measured' => 'battery', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.18642.1.2.2.2.0');
$config['mibs'][$mib]['sensor']['batteryResistanceReading']['indexes'][0] = array('descr' => 'Battery Resistance', 'class' => 'resistance', 'measured' => 'battery', 'scale' => 0.0001, 'oid_num' => '.1.3.6.1.4.1.18642.1.2.2.4.0');

$config['mibs'][$mib]['status']['highVoltageAlarmStatus']['indexes'][0] = array('descr' => 'High Voltage Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.1.0');
$config['mibs'][$mib]['status']['lowVoltageAlarmStatus']['indexes'][0] = array('descr' => 'Low Voltage Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.2.0');
$config['mibs'][$mib]['status']['overloadAlarmStatus']['indexes'][0] = array('descr' => 'Overload Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.3.0');
$config['mibs'][$mib]['status']['breakerAlarmStatus']['indexes'][0] = array('descr' => 'Breaker Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.4.0');
$config['mibs'][$mib]['status']['acFailureAlarmStatus']['indexes'][0] = array('descr' => 'AC Failure Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.5.0');
$config['mibs'][$mib]['status']['fanFailureAlarmStatus']['indexes'][0] = array('descr' => 'Fan Failure Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.6.0');
$config['mibs'][$mib]['status']['rectifierFailureAlarmStatus']['indexes'][0] = array('descr' => 'Rectifier Failure Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.7.0');
$config['mibs'][$mib]['status']['majorAlarmStatus']['indexes'][0] = array('descr' => 'Major Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.8.0');
$config['mibs'][$mib]['status']['lowVoltageDisconnect1TemperatureAlarmStatus']['indexes'][0] = array('descr' => 'LVD1 Temperature Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.9.0');
$config['mibs'][$mib]['status']['lowVoltageDisconnect2TemperatureAlarmStatus']['indexes'][0] = array('descr' => 'LVD2 Temperature Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.10.0');
$config['mibs'][$mib]['status']['lowVoltageDisconnect3TemperatureAlarmStatus']['indexes'][0] = array('descr' => 'LVD3 Temperature Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.11.0');
$config['mibs'][$mib]['status']['lowVoltageDisconnect1VoltageAlarmStatus']['indexes'][0] = array('descr' => 'LVD1 Voltage Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.12.0');
$config['mibs'][$mib]['status']['lowVoltageDisconnect2VoltageAlarmStatus']['indexes'][0] = array('descr' => 'LVD2 Voltage Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.13.0');
$config['mibs'][$mib]['status']['lowVoltageDisconnect3VoltageAlarmStatus']['indexes'][0] = array('descr' => 'LVD3 Voltage Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.14.0');
$config['mibs'][$mib]['status']['batteryResistanceAlarmStatus']['indexes'][0] = array('descr' => 'Battery Resistance Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.15.0');
$config['mibs'][$mib]['status']['batteryCurrentAlarmStatus']['indexes'][0] = array('descr' => 'Battery Current Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.16.0');
$config['mibs'][$mib]['status']['batteryTestAbortCondition1AlarmStatus']['indexes'][0] = array('descr' => 'Battery Test Abort 1 Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.17.0');
$config['mibs'][$mib]['status']['batteryTestAbortCondition2AlarmStatus']['indexes'][0] = array('descr' => 'Battery Test Abort 2 Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.18.0');
$config['mibs'][$mib]['status']['batteryTestAbortCondition3AlarmStatus']['indexes'][0] = array('descr' => 'Battery Test Abort 3 Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.19.0');
$config['mibs'][$mib]['status']['batteryDisconnectAlarmStatus']['indexes'][0] = array('descr' => 'Battery Disconnect Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.20.0');
$config['mibs'][$mib]['status']['fuseAlarmStatus']['indexes'][0] = array('descr' => 'Fuse Alarm', 'measured' => '', 'type' => 'ccpower-mib-alarmstatus', 'oid_num' => '.1.3.6.1.4.1.18642.1.2.4.21.0');

$config['mibs'][$mib]['states']['ccpower-mib-alarmstatus'][1] = array('name' => 'inactive', 'event' => 'ok');
$config['mibs'][$mib]['states']['ccpower-mib-alarmstatus'][2] = array('name' => 'active', 'event' => 'alert');

// Cisco / Scientific Atlanta DMN

$mib = 'CISCO-DMN-DSG-DL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1429.2.2.5.1'; // ciscoDSGUtilities.1
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = 'Cisco DMN DSG';
$config['mibs'][$mib]['serial'][] = array('oid' => 'dlAboutTrackingId.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'dlAboutProductId.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'dlAboutCurrentVer.0');

// atmedia GmbH

$mib = 'ATMEDIA-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.13458';
$config['mibs'][$mib]['mib_dir'] = 'atmedia';
$config['mibs'][$mib]['descr'] = 'ATMedia Encryptor MIB';
$config['mibs'][$mib]['hardware'][] = array('oid' => 'acDescr.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'acSoftVersion.0');
$config['mibs'][$mib]['serial'][] = array('oid' => 'acSerialNumber.0');

// General Electric UPS

$mib = 'GEPARALLELUPS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.818';
$config['mibs'][$mib]['mib_dir'] = 'ge';
$config['mibs'][$mib]['descr'] = 'General Electric UPS';
$config['mibs'][$mib]['serial'][] = array('oid' => 'upsIdentUPSSerialNumber.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'upsIdentUPSSoftwareVersion.0');

// Input
$config['mibs'][$mib]['sensor']['upsInputFrequency']['indexes'][1] = array('descr' => 'Input Frequency', 'class' => 'frequency', 'measured' => 'input', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.3.3.1.2.1');
$config['mibs'][$mib]['sensor']['upsInputVoltage']['indexes'][1] = array('descr' => 'Input Voltage Phase 1', 'class' => 'voltage', 'measured' => 'input', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.3.3.1.3.1');
$config['mibs'][$mib]['sensor']['upsInputVoltage']['indexes'][2] = array('descr' => 'Input Voltage Phase 2', 'class' => 'voltage', 'measured' => 'input', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.3.3.1.3.2');
$config['mibs'][$mib]['sensor']['upsInputVoltage']['indexes'][3] = array('descr' => 'Input Voltage Phase 3', 'class' => 'voltage', 'measured' => 'input', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.3.3.1.3.3');
// Output
$config['mibs'][$mib]['sensor']['upsOutputFrequency']['indexes'][0] = array('descr' => 'Output Frequency', 'class' => 'frequency', 'measured' => 'output', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.2.0');
$config['mibs'][$mib]['sensor']['upsOutputVoltage']['indexes'][1] = array('descr' => 'Output Voltage Phase 1', 'class' => 'voltage', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.2.1');
$config['mibs'][$mib]['sensor']['upsOutputVoltage']['indexes'][2] = array('descr' => 'Output Voltage Phase 2', 'class' => 'voltage', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.2.2');
$config['mibs'][$mib]['sensor']['upsOutputVoltage']['indexes'][3] = array('descr' => 'Output Voltage Phase 3', 'class' => 'voltage', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.2.3');
$config['mibs'][$mib]['sensor']['upsOutputCurrent']['indexes'][1] = array('descr' => 'Output Current Phase 1', 'class' => 'current', 'measured' => 'output', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.3.1');
$config['mibs'][$mib]['sensor']['upsOutputCurrent']['indexes'][2] = array('descr' => 'Output Current Phase 2', 'class' => 'current', 'measured' => 'output', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.3.2');
$config['mibs'][$mib]['sensor']['upsOutputCurrent']['indexes'][3] = array('descr' => 'Output Current Phase 3', 'class' => 'current', 'measured' => 'output', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.3.3');
$config['mibs'][$mib]['sensor']['upsOutputPower']['indexes'][1] = array('descr' => 'Output Power Phase 1', 'class' => 'power', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.4.1');
$config['mibs'][$mib]['sensor']['upsOutputPower']['indexes'][2] = array('descr' => 'Output Power Phase 2', 'class' => 'power', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.4.2');
$config['mibs'][$mib]['sensor']['upsOutputPower']['indexes'][3] = array('descr' => 'Output Power Phase 3', 'class' => 'power', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.4.3');
$config['mibs'][$mib]['sensor']['upsOutputPercentLoad']['indexes'][1] = array('descr' => 'Output Load Phase 1', 'class' => 'load', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.5.1');
$config['mibs'][$mib]['sensor']['upsOutputPercentLoad']['indexes'][2] = array('descr' => 'Output Load Phase 2', 'class' => 'load', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.5.2');
$config['mibs'][$mib]['sensor']['upsOutputPercentLoad']['indexes'][3] = array('descr' => 'Output Load Phase 3', 'class' => 'load', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.5.3');
$config['mibs'][$mib]['sensor']['upsOutputPowerFactor']['indexes'][1] = array('descr' => 'Output Power Factor Phase 1', 'class' => 'powerfactor', 'measured' => 'output', 'scale' => 0.01, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.6.1');
$config['mibs'][$mib]['sensor']['upsOutputPowerFactor']['indexes'][2] = array('descr' => 'Output Power Factor Phase 2', 'class' => 'powerfactor', 'measured' => 'output', 'scale' => 0.01, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.6.2');
$config['mibs'][$mib]['sensor']['upsOutputPowerFactor']['indexes'][3] = array('descr' => 'Output Power Factor Phase 3', 'class' => 'powerfactor', 'measured' => 'output', 'scale' => 0.01, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.4.1.6.3');
// Bypass
$config['mibs'][$mib]['sensor']['upsBypassFrequency']['indexes'][0] = array('descr' => 'Bypass Frequency', 'class' => 'frequency', 'measured' => 'bypass', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.5.1.0');
$config['mibs'][$mib]['sensor']['upsBypassVoltage']['indexes'][1] = array('descr' => 'Bypass Voltage Phase 1', 'class' => 'voltage', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.5.3.1.2.1');
$config['mibs'][$mib]['sensor']['upsBypassVoltage']['indexes'][2] = array('descr' => 'Bypass Voltage Phase 2', 'class' => 'voltage', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.5.3.1.2.2');
$config['mibs'][$mib]['sensor']['upsBypassVoltage']['indexes'][3] = array('descr' => 'Bypass Voltage Phase 3', 'class' => 'voltage', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.5.3.1.2.3');
$config['mibs'][$mib]['sensor']['upsBypassCurrent']['indexes'][1] = array('descr' => 'Bypass Current Phase 1', 'class' => 'current', 'measured' => 'bypass', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.5.3.1.3.1', 'min' => 0);
$config['mibs'][$mib]['sensor']['upsBypassCurrent']['indexes'][2] = array('descr' => 'Bypass Current Phase 2', 'class' => 'current', 'measured' => 'bypass', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.5.3.1.3.2', 'min' => 0);
$config['mibs'][$mib]['sensor']['upsBypassCurrent']['indexes'][3] = array('descr' => 'Bypass Current Phase 3', 'class' => 'current', 'measured' => 'bypass', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.5.3.1.3.3', 'min' => 0);
$config['mibs'][$mib]['sensor']['upsBypassPower']['indexes'][1] = array('descr' => 'Bypass Power Phase 1', 'class' => 'power', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.5.3.1.4.1');
$config['mibs'][$mib]['sensor']['upsBypassPower']['indexes'][2] = array('descr' => 'Bypass Power Phase 2', 'class' => 'power', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.5.3.1.4.2');
$config['mibs'][$mib]['sensor']['upsBypassPower']['indexes'][3] = array('descr' => 'Bypass Power Phase 3', 'class' => 'power', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.5.3.1.4.3');
// Battery
$config['mibs'][$mib]['sensor']['upsEstimatedMinutesRemaining']['indexes'][0] = array('descr' => 'Battery Estimated Runtime', 'class' => 'runtime', 'measured' => 'battery', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.2.3.0', 'oid_limit_low' => 'upsConfigLowBattTime.0');
$config['mibs'][$mib]['sensor']['upsEstimatedChargeRemaining']['indexes'][0] = array('descr' => 'Battery Charge Remaining', 'class' => 'capacity', 'measured' => 'battery', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.2.4.0');
$config['mibs'][$mib]['sensor']['upsBatteryVoltage']['indexes'][0] = array('descr' => 'Battery Voltage', 'class' => 'voltage', 'measured' => 'battery', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.2.5.0');
$config['mibs'][$mib]['sensor']['upsBatteryCurrent']['indexes'][0] = array('descr' => 'Battery Current', 'class' => 'current', 'measured' => 'battery', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.2.6.0');
$config['mibs'][$mib]['sensor']['upsBatteryTemperature']['indexes'][0] = array('descr' => 'Battery Temperature', 'class' => 'temperature', 'measured' => 'battery', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.2.7.0');

$config['mibs'][$mib]['sensor']['upsAlarmsPresent']['indexes'][0] = array('descr' => 'UPS Alarms Present', 'class' => 'count', 'measured' => 'device', 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.6.1.0', 'limit_high' => 1);

// Statuses
$config['mibs'][$mib]['status']['upsBatteryStatus']['indexes'][0] = array('descr' => 'Battery Status', 'measured' => 'battery', 'type' => 'upsBatteryStatus', 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.2.1.0');
$config['mibs'][$mib]['status']['upsOutputSource']['indexes'][0] = array('descr' => 'Output Source', 'measured' => 'output', 'type' => 'upsOutputSource', 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.4.1.0');
$config['mibs'][$mib]['status']['upsLoadSource']['indexes'][0] = array('descr' => 'Load Source', 'measured' => 'device', 'type' => 'upsLoadSource', 'oid_num' => '.1.3.6.1.4.1.818.1.1.10.8.13.0');

$type = 'upsBatteryStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'batteryNormal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'batteryLow', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'batteryDepleted', 'event' => 'alert');
$type = 'upsOutputSource';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'other', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'none', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'bypass', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'battery', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'booster', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'reducer', 'event' => 'warning');
$type = 'upsLoadSource';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'onbypass', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'onInverter', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'onDetour', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'loadOff', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'other', 'event' => 'warning');

// HP Colubris

$mib = 'COLUBRIS-USAGE-INFORMATION-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.8744.5.21';
$config['mibs'][$mib]['mib_dir'] = 'colubris';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['processor']['coUsInfoCpuUseNow'] = array('type' => 'static', 'descr' => 'System CPU', 'oid' => 'coUsInfoCpuUseNow.0', 'oid_num' => '.1.3.6.1.4.1.8744.5.21.1.1.8.0');
$config['mibs'][$mib]['mempool']['coUsageInformationGroup'] = array('type'      => 'static', 'descr' => 'Memory', 'scale' => 1 / 1024 / 1024,
                                                                    'oid_total' => 'coUsInfoRamTotal.0', 'oid_total_num' => '.1.3.6.1.4.1.8744.5.21.1.1.9.0',  // COLUBRIS-USAGE-INFORMATION-MIB::coUsInfoRamTotal.0 = Gauge32: 130588672 Kb
                                                                    'oid_free'  => 'coUsInfoRamFree.0', 'oid_free_num' => '.1.3.6.1.4.1.8744.5.21.1.1.10.0', // COLUBRIS-USAGE-INFORMATION-MIB::coUsInfoRamFree.0 = Gauge32: 4788224 Kb
);

$mib = 'COLUBRIS-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.8744.5.6';
$config['mibs'][$mib]['mib_dir'] = 'colubris';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'systemSerialNumber.0'); // COLUBRIS-SYSTEM-MIB::systemSerialNumber.0 = STRING: SG038xxxx
$config['mibs'][$mib]['version'][] = array('oid' => 'systemFirmwareRevision.0'); // COLUBRIS-SYSTEM-MIB::systemBootRevision.0 = STRING: Boot 11.28 (Dec 17 2009 - 18:58:53)
$config['mibs'][$mib]['hardware'][] = array('oid' => 'systemProductName.0'); // COLUBRIS-SYSTEM-MIB::systemProductName.0 = STRING: MSM410
$config['mibs'][$mib]['features'][] = array('oid' => 'systemBootRevision.0'); // COLUBRIS-SYSTEM-MIB::systemBootRevision.0 = STRING: Boot 11.28 (Dec 17 2009 - 18:58:53)

/*
COLUBRIS-SYSTEM-MIB::systemHardwareRevision.0 = STRING: 50-00-1036-02.
COLUBRIS-SYSTEM-MIB::systemConfigurationVersion.0 = STRING: not configured
COLUBRIS-SYSTEM-MIB::systemUpTime.0 = Counter32: 1448531 seconds
COLUBRIS-SYSTEM-MIB::systemProductFlavor.0 = STRING: DEFAULT
COLUBRIS-SYSTEM-MIB::systemDeviceIdentification.0 = STRING: 0:24:a8:xx:xx:xx
COLUBRIS-SYSTEM-MIB::systemFirmwareBuildDate.0 = STRING: "2010/06/23"
*/

// Lenovo / IBM

$mib = 'GPFS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2.6.212';
$config['mibs'][$mib]['descr'] = 'Status monitoring for IBM GPFS cluster file system.';
$config['mibs'][$mib]['mib_dir'] = 'ibm';

$mib = 'IMM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2.3.51.3';
$config['mibs'][$mib]['mib_dir'] = 'lenovo';
$config['mibs'][$mib]['descr'] = 'Lenovo/IBM Integrated Management Module MIB';

$mib = 'IBM-AIX-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2.6.191';
$config['mibs'][$mib]['mib_dir'] = 'ibm';
$config['mibs'][$mib]['descr'] = '';

$mib = 'SNIA-SML-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'ibm';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'chassis-SerialNumber.0'); // SNIA-SML-MIB::chassis-SerialNumber.0 = STRING: "7823156"
$config['mibs'][$mib]['version'][] = array('oid' => 'product-Version.0'); // SNIA-SML-MIB::product-Version.0 = STRING: "8870"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'chassis-Model.0'); // SNIA-SML-MIB::chassis-Model.0 = STRING: "3584"

/*
SNIA-SML-MIB::product-Name.0 = STRING: "IBM System Storage TS3500 Tape Library"
SNIA-SML-MIB::product-IdentifyingNumber.0 = STRING: "7823156"
SNIA-SML-MIB::product-Vendor.0 = STRING: "International Business Machines"
SNIA-SML-MIB::product-ElementName.0 = STRING: "IBM System Storage TS3500 Tape Library 7823156"
SNIA-SML-MIB::chassis-Manufacturer.0 = STRING: "International Business Machines"
SNIA-SML-MIB::chassis-LockPresent.0 = INTEGER: true(1)
SNIA-SML-MIB::chassis-SecurityBreach.0 = INTEGER: 0
SNIA-SML-MIB::chassis-IsLocked.0 = INTEGER: true(1)
SNIA-SML-MIB::chassis-Tag.0 = STRING: "International Business Machines 3584 7823156"
SNIA-SML-MIB::chassis-ElementName.0 = STRING: "IBM System Storage TS3500 Tape Library        "
*/

// Mitel

$mib = 'MITEL-IperaVoiceLAN-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'mitel';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['status']['mitelIpera3000AlmLevel']['indexes'][0] = array('descr' => 'System Alarm', 'measured' => 'device', 'type' => 'mitelIpera3000AlmLevel', 'oid_num' => '.1.3.6.1.4.1.1027.4.1.1.2.2.1.0');

$type = 'mitelIpera3000AlmLevel';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'almClear', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'almMinor', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'almMajor', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'almCritical', 'event' => 'alert');

// Mikrotik RouterOS

$mib = 'MIKROTIK-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.4.1.14988.1', // MIKROTIK-MIB::mikrotikExperimentalModule (MODULE-IDENTITY)
  '.1.3.6.1.4.1.14988');  // MIKROTIK-MIB::mikrotik                   (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'mikrotik';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'mtxrSerialNumber.0');
$config['mibs'][$mib]['serial'][] = array('oid' => 'mtxrLicSoftwareId.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'mtxrLicVersion.0');
$config['mibs'][$mib]['features'][] = array('oid' => 'mtxrLicLevel.0', 'transformations' => array(array('action' => 'prepend', 'string' => 'Level ')));

$config['mibs'][$mib]['sensor']['mtxrHlCoreVoltage']['indexes'][0] = array('descr' => 'Core', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.1.0');
$config['mibs'][$mib]['sensor']['mtxrHlThreeDotThreeVoltage']['indexes'][0] = array('descr' => '3.3V', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.2.0');
$config['mibs'][$mib]['sensor']['mtxrHlFiveVoltage']['indexes'][0] = array('descr' => '5V', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.3.0');
$config['mibs'][$mib]['sensor']['mtxrHlTwelveVoltage']['indexes'][0] = array('descr' => '12V', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.4.0');
$config['mibs'][$mib]['sensor']['mtxrHlSensorTemperature']['indexes'][0] = array('descr' => 'System', 'class' => 'temperature', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.5.0');
$config['mibs'][$mib]['sensor']['mtxrHlCpuTemperature']['indexes'][0] = array('descr' => 'System', 'class' => 'temperature', 'measured' => 'cpu', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.6.0');
$config['mibs'][$mib]['sensor']['mtxrHlBoardTemperature']['indexes'][0] = array('descr' => 'System', 'class' => 'temperature', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.7.0');
$config['mibs'][$mib]['sensor']['mtxrHlVoltage']['indexes'][0] = array('descr' => 'System', 'class' => 'voltage', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.8.0');
$config['mibs'][$mib]['sensor']['mtxrHlTemperature']['indexes'][0] = array('descr' => 'System', 'class' => 'temperature', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.10.0');
$config['mibs'][$mib]['sensor']['mtxrHlProcessorTemperature']['indexes'][0] = array('descr' => 'Processor', 'class' => 'temperature', 'measured' => 'cpu', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.11.0');
$config['mibs'][$mib]['sensor']['mtxrHlPower']['indexes'][0] = array('descr' => 'System', 'class' => 'power', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.12.0');
$config['mibs'][$mib]['sensor']['mtxrHlCurrent']['indexes'][0] = array('descr' => 'System', 'class' => 'current', 'measured' => 'device', 'scale' => 0.001, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.13.0');
$config['mibs'][$mib]['sensor']['mtxrHlProcessorFrequency']['indexes'][0] = array('descr' => 'Processor', 'class' => 'frequency', 'measured' => 'cpu', 'scale' => 1000000, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.14.0');
$config['mibs'][$mib]['sensor']['mtxrHlFanSpeed1']['indexes'][0] = array('descr' => 'Fan 1', 'class' => 'fanspeed', 'measured' => 'fan', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.17.0');
$config['mibs'][$mib]['sensor']['mtxrHlFanSpeed2']['indexes'][0] = array('descr' => 'Fan 2', 'class' => 'fanspeed', 'measured' => 'fan', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.18.0');

$config['mibs'][$mib]['status']['mtxrHlPowerSupplyState']['indexes'][0] = array('descr' => 'Primary Power Supply', 'measured' => 'device', 'type' => 'mtxrHlPowerSupplyState', 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.15.0');
$config['mibs'][$mib]['status']['mtxrHlBackupPowerSupplyState']['indexes'][0] = array('descr' => 'Backup Power Supply', 'measured' => 'device', 'type' => 'mtxrHlPowerSupplyState', 'oid_num' => '.1.3.6.1.4.1.14988.1.1.3.16.0');

$type = 'mtxrHlPowerSupplyState';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'error', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');

$mib = 'MOXA-NP6000-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.8691.2.8';
$config['mibs'][$mib]['mib_dir'] = 'moxa';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'serialNumber.0'); // MOXA-NP6000-MIB::serialNumber.0 = INTEGER: 4509
$config['mibs'][$mib]['version'][] = array('oid' => 'firmwareVersion.0', 'transformations' => array(array('action' => 'explode', 'index' => 'first'))); // MOXA-NP6000-MIB::firmwareVersion.0 = STRING: 1.8 Build 10081211
$config['mibs'][$mib]['hardware'][] = array('oid' => 'modelName.0');    // MOXA-NP6000-MIB::modelName.0 = STRING: NP6150

$mib = 'MOXA-W2x50A-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.8691.2.13';
$config['mibs'][$mib]['mib_dir'] = 'moxa';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'serialNumber.0'); // MOXA-W2x50A-MIB::serialNumber.0 = INTEGER: 4630
$config['mibs'][$mib]['version'][] = array('oid' => 'firmwareVersion.0', 'transformations' => array(array('action' => 'explode', 'index' => 'first'))); // MOXA-W2x50A-MIB::firmwareVersion.0 = STRING: 1.6 Build 13120415
$config['mibs'][$mib]['hardware'][] = array('oid' => 'modelName.0');    // MOXA-W2x50A-MIB::modelName.0 = STRING: NPortW2150A

$mib = 'TMESNMP2-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'papouch';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['sensor']['int_temperature']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.18248.1.1.3.0', 'class' => 'temperature', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.18248.1.1.1.0');

// Papouch 2TH ETH

$mib = 'papago_temp_V02-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'papouch';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['sensor']['channelTable']['tables'][0] = array(
  'table'     => 'channelTable',
  'oid_class' => 'inChType', // 0 = not used, 1 = temperature, 2 = humidity, 3 = dew point
  'map_class' => array(1 => 'temperature', 2 => 'humidity', 3 => 'dewpoint'),
  'scale'     => 0.1,
  'oid'       => 'inChValue',
  'descr'     => '%class% %i%',
  'oid_num'   => '.1.3.6.1.4.1.18248.31.1.2.1.1.3');
// FIXME "inChUnits" specifies unit for each sensor above (0=C, 1=F, 2=K, 0=%) - could associate index to type and unit this way, note 0 can be C or % depending on class. (see below as well)
// Please configure your device to report in Celsius.

// Papouch TH2E

$mib = 'the_v01-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'papouch';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['sensor']['inChValue']['indexes'][1] = array('descr' => 'Temperature', 'class' => 'temperature', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.18248.20.1.2.1.1.2.1', 'oid_limit_low' => '.1.3.6.1.4.1.18248.20.1.3.1.1.3.1', 'oid_limit_high' => '.1.3.6.1.4.1.18248.20.1.3.1.1.2.1');
$config['mibs'][$mib]['sensor']['inChValue']['indexes'][2] = array('descr' => 'Humidity', 'class' => 'humidity', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.18248.20.1.2.1.1.2.2', 'oid_limit_low' => '.1.3.6.1.4.1.18248.20.1.3.1.1.3.2', 'oid_limit_high' => '.1.3.6.1.4.1.18248.20.1.3.1.1.2.2');
$config['mibs'][$mib]['sensor']['inChValue']['indexes'][3] = array('descr' => 'Dew Point', 'class' => 'temperature', 'measured' => 'device', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.18248.20.1.2.1.1.2.3', 'oid_limit_low' => '.1.3.6.1.4.1.18248.20.1.3.1.1.3.3', 'oid_limit_high' => '.1.3.6.1.4.1.18248.20.1.3.1.1.2.3');
// FIXME "inChUnits" specifies unit for each sensor above (0=C, 1=F, 2=K, 3=%) - could associate index to type and unit this way, if ever necessary. (see above as well)
// Please configure your device to report in Celsius.

// A10

$mib = 'A10-AX-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.22610.2.4';
$config['mibs'][$mib]['mib_dir'] = 'a10';
$config['mibs'][$mib]['descr'] = 'A10 application acceleration appliance';
$config['mibs'][$mib]['serial'][] = array('oid' => 'axSysSerialNumber.0'); // axSysSerialNumber.0 = STRING: "AX10A3xxxxxxxx"

$config['mibs'][$mib]['processor']['axSysCpuTable'] = array('type' => 'table', 'table' => 'axSysCpuTable', 'oid' => 'axSysCpuUsageValue', 'oid_num' => '.1.3.6.1.4.1.22610.2.4.1.3.2.1.3');
$config['mibs'][$mib]['mempool']['axSysMemory'] = array('type'      => 'static', 'descr' => 'Host Memory', 'scale' => 1024,
                                                        'oid_total' => 'axSysMemoryTotal.0',
                                                        'oid_used'  => 'axSysMemoryUsage.0'
);

$config['mibs'][$mib]['sensor']['axSysHwPhySystemTemp']['indexes'][0] = array('descr' => 'System Temperature', 'class' => 'temperature', 'measured' => 'device', 'oid' => 'axSysHwPhySystemTemp.0', 'oid_num' => '.1.3.6.1.4.1.22610.2.4.1.5.1.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['axSysHwFan1Speed']['indexes'][0] = array('descr' => 'System Fan 1', 'class' => 'fanspeed', 'measured' => 'device', 'oid' => 'axSysHwFan1Speed.0', 'oid_num' => '.1.3.6.1.4.1.22610.2.4.1.5.2.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['axSysHwFan2Speed']['indexes'][0] = array('descr' => 'System Fan 2', 'class' => 'fanspeed', 'measured' => 'device', 'oid' => 'axSysHwFan2Speed.0', 'oid_num' => '.1.3.6.1.4.1.22610.2.4.1.5.3.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['axSysHwFan3Speed']['indexes'][0] = array('descr' => 'System Fan 3', 'class' => 'fanspeed', 'measured' => 'device', 'oid' => 'axSysHwFan3Speed.0', 'oid_num' => '.1.3.6.1.4.1.22610.2.4.1.5.4.0', 'min' => 0);

$config['mibs'][$mib]['status']['axSysPowerSupplyStatusEntry']['tables'][] = array(
  'table'     => 'axSysPowerSupplyStatusEntry',
  'type'      => 'axPowerSupplyStatus',
  'descr'     => 'Power Supply',
  'oid_descr' => 'axPowerSupplyName',
  'oid'       => 'axPowerSupplyStatus',
  'oid_num'   => '.1.3.6.1.4.1.22610.2.4.1.5.12.1.3',
  'measured'  => 'powersupply'
);
$type = 'axPowerSupplyStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'off', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'on', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'absent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][-1] = array('name' => 'unknown', 'event' => 'exclude');

$config['mibs'][$mib]['status']['axPowerSupplyVoltageEntry']['tables'][] = array(
  'table'     => 'axPowerSupplyVoltageEntry',
  'type'      => 'axPowerSupplyVoltageStatus',
  //'descr'              => 'Power Supply Voltage',
  'oid_descr' => 'axPowerSupplyVoltageDescription',
  'oid'       => 'axPowerSupplyVoltageStatus',
  'oid_num'   => '.1.3.6.1.4.1.22610.2.4.1.5.11.1.2',
  'measured'  => 'powersupply'
);
$type = 'axPowerSupplyVoltageStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'invalid', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'unknown', 'event' => 'exclude');


$mib = 'ONEACCESS-SYS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.13191.1.100.671';
$config['mibs'][$mib]['mib_dir'] = 'oneaccess';
$config['mibs'][$mib]['descr'] = 'OneOS system Management objects';
$config['mibs'][$mib]['serial'][] = array('oid' => 'oacExpIMSysHwcSerialNumber.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'acSysIMSysMainIdentifier.0', 'transformations' => array(array('action' => 'replace', 'from' => 'oac', 'to' => '')));

$config['mibs'][$mib]['processor']['oacSysCpuUsedCoresTable'] = array('type'          => 'table',
                                                                      'table'         => 'oacSysCpuUsedCoresTable',
                                                                      'oid_descr'     => 'oacSysCpuUsedCoreType',
                                                                      'oid'           => 'oacSysCpuUsedOneMinuteValue',
                                                                      'oid_num'       => '.1.3.6.1.4.1.13191.10.3.3.1.2.3.1.4',
                                                                      'stop_if_found' => TRUE);
$config['mibs'][$mib]['processor']['oacSysCpuStatistics'] = array('type'      => 'static',
                                                                  'oid_descr' => 'oacSysIMSysMainCPU.0',
                                                                  'oid'       => 'oacSysCpuUsed.0',
                                                                  'oid_num'   => '.1.3.6.1.4.1.13191.10.3.3.1.2.1.0');

// Avaya/Nortel

$mib = 'S5-CHASSIS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.45.1.6.3.10';
$config['mibs'][$mib]['mib_dir'] = 'nortel';
$config['mibs'][$mib]['descr'] = '5000 Chassis MIB';
$config['mibs'][$mib]['processor']['s5ChasUtil'] = array('type'    => 'table',
                                                         'table'   => 's5ChasUtilCPUUsageLast1Minute',
                                                         'descr'   => 'Processor Unit %i%',
                                                         'oid'     => 's5ChasUtilCPUUsageLast1Minute',
                                                         'oid_num' => '.1.3.6.1.4.1.45.1.6.3.8.1.1.5');

$mib = 'G700-MG-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6889.2.9';
$config['mibs'][$mib]['mib_dir'] = 'avaya';
$config['mibs'][$mib]['descr'] = 'Avaya G700 Media Gateway MIB';
$config['mibs'][$mib]['serial'][] = array('oid' => 'cmgSerialNumber.0');
// $config['mibs'][$mib]['hardware'][]     = array('oid' => 'cmgModelNumber.0'); // G700
$config['mibs'][$mib]['hardware'][] = array('oid' => 'cmgDescription.0'); // Avaya G700 Media Gateway
$config['mibs'][$mib]['sensor']['cmgCpuTemp']['indexes'][0] = array('descr' => 'CPU Temperature', 'class' => 'temperature', 'measured' => 'device', 'oid' => 'cmgCpuTemp.0', 'oid_num' => '.1.3.6.1.4.1.6889.2.9.1.1.10.1.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['cmgDspTemp']['indexes'][0] = array('descr' => 'DSP Temperature', 'class' => 'temperature', 'measured' => 'device', 'oid' => 'cmgDspTemp.0', 'oid_num' => '.1.3.6.1.4.1.6889.2.9.1.1.10.4.0', 'min' => 0);

// Peplink

$mib = 'AP-SYSTEM-BASIC';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.27662.200.1.1.1';
$config['mibs'][$mib]['mib_dir'] = 'peplink';
$config['mibs'][$mib]['descr'] = 'Basic System MIB for PEPWAVE Enterprise WiFi AP';
$config['mibs'][$mib]['serial'][] = array('oid' => 'apSerialNumber.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'apSoftwareVerstion.0');

$mib = 'PEPLINK-BALANCE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.23695.1';
$config['mibs'][$mib]['mib_dir'] = 'peplink';
$config['mibs'][$mib]['descr'] = 'MIB for Peplink Balance';
$config['mibs'][$mib]['serial'][] = array('oid' => 'balSerialNumber.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'balFirmware.0');


// RAPID-CITY

$mib = 'RAPID-CITY';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.4.1.2272',     // RAPID-CITY::rapidCity (MODULE-IDENTITY)
  '.1.3.6.1.4.1.2272.34'); // RAPID-CITY::rcA8603   (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'nortel';
$config['mibs'][$mib]['descr'] = 'Enterprise MIB for the Accelar product family.';
$config['mibs'][$mib]['serial'][] = array('oid' => 'rcChasSerialNumber.0');

$config['mibs'][$mib]['processor']['rcSys'] = array('type'    => 'static',
                                                    'descr'   => 'System CPU',
                                                    'oid'     => 'rcSysCpuUtil.0',
                                                    'oid_num' => '.1.3.6.1.4.1.2272.1.1.20.0');
$config['mibs'][$mib]['mempool']['rcSystem'] = array('type'      => 'static', 'descr' => 'System Memory', 'scale' => 1024, 'scale_total' => 1048576,
                                                     'oid_total' => 'rcSysDramSize.0', 'oid_total_num' => '.1.3.6.1.4.1.2272.1.1.46.0', // RAPID-CITY::rcSysDramSize.0 = INTEGER: 256    // in MB!
                                                     'oid_free'  => 'rcSysDramFree.0', 'oid_free_num' => '.1.3.6.1.4.1.2272.1.1.48.0', // RAPID-CITY::rcSysDramFree.0 = INTEGER: 184274 // in KB!
  //'oid_perc'   => 'rcSysDramUsed.0', 'oid_perc_num'  => '.1.3.6.1.4.1.2272.1.1.47.0', // RAPID-CITY::rcSysDramUsed.0 = Gauge32: 29     // Percent!
);
// Unused: RAPID-CITY::rcSysDramMaxBlockFree.0 = INTEGER: 122820

// SNR (shop.nag.ru)

$mib = 'SNR-SWITCH-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.40418.7';
$config['mibs'][$mib]['mib_dir'] = 'snr';
$config['mibs'][$mib]['descr'] = 'SNR Switch MIB';
$config['mibs'][$mib]['version'][] = array('oid' => 'sysSoftwareVersion.0', 'transformations' => array(array('action' => 'explode', 'delimiter' => '(', 'index' => 'first'))); // SNR-SWITCH-MIB::sysSoftwareVersion.0 = STRING: "7.0.3.1(R0244.0117)"
$config['mibs'][$mib]['features'][] = array('oid' => 'sysHardwareVersion.0'); // SNR-SWITCH-MIB::sysHardwareVersion.0 = STRING: "1.0.2"
$config['mibs'][$mib]['processor']['switchCpuUsage'] = array(
  'type'    => 'static',
  'descr'   => 'System CPU',
  'oid'     => 'switchCpuUsage.0',
  'oid_num' => '.1.3.6.1.4.1.40418.7.100.1.11.10.0' // switchCpuUsage.0 = INTEGER: 14
);

$config['mibs'][$mib]['mempool']['switchMemorySize'] = array(
  'type'          => 'static',
  'descr'         => 'Memory',
  'scale'         => 1,
  'oid_total'     => 'switchMemorySize.0',
  'oid_total_num' => '.1.3.6.1.4.1.40418.7.100.1.11.6.0', // switchMemorySize.0 = INTEGER: 536870912
  'oid_used'      => 'switchMemoryBusy.0',
  'oid_used_num'  => '.1.3.6.1.4.1.40418.7.100.1.11.7.0', // switchMemoryBusy.0 = INTEGER: 305364992
);

$config['mibs'][$mib]['status']['sysFanTable']['tables'][] = array(
  'table'    => 'sysFanTable',
  'type'     => 'sysFanStatus',
  'descr'    => 'Fan',
  'oid'      => 'sysFanStatus',
  'oid_num'  => '.1.3.6.1.4.1.40418.7.100.1.12.1.3',
  'measured' => 'fan'
);

$type = 'sysFanStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'abnormal', 'event' => 'alert');

$config['mibs'][$mib]['status']['sysFanTable']['tables'][] = array(
  'table'    => 'sysFanTable',
  'type'     => 'sysFanSpeed',
  'descr'    => 'Fan %index% Speed',
  'oid'      => 'sysFanSpeed',
  'oid_num'  => '.1.3.6.1.4.1.40418.7.100.1.12.1.4',
  'measured' => 'fan'
);

$type = 'sysFanSpeed';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'none', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'low', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'medium-low', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'medium', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'medium-high', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'high', 'event' => 'alert');

$config['mibs'][$mib]['sensor']['switchTemperature']['indexes'][0] = array(
  'descr'    => 'Switch Temperature',
  'class'    => 'temperature',
  'measured' => 'device',
  'scale'    => '1',
  'oid_num'  => '.1.3.6.1.4.1.40418.7.100.1.11.9.0'
);

$mib = 'SNR-ERD-2';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.40418.2.2';
$config['mibs'][$mib]['mib_dir'] = 'snr';
$config['mibs'][$mib]['descr'] = 'SNR Ethernet Remote Device v2';

//$config['mibs'][$mib]['sensor']['temperatureSensor']['indexes'][0] = array('descr' => 'Temperature', 'class' => 'temperature', 'measured' => 'device', 'scale' => '1',     'oid_num' => '.1.3.6.1.4.1.40418.2.2.4.1.0');

// Statuses
$config['mibs'][$mib]['status']['monitorAlarmSignalContact3']['indexes'][0] = array(
  'descr'            => 'ALARM', // Fallback
  'oid_descr'        => 'alarmSenseName.0',
  'type'             => 'monitorAlarmSignalContact2',
  'measured'         => 'other',
  'rename_rrd_array' => array('type' => 'monitorAlarmSignalContact'), // old discovery params
  'oid_num'          => '.1.3.6.1.4.1.40418.2.2.3.1.0'
);

$type = 'monitorAlarmSignalContact2';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'sensorOff', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'doorIsClose', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'doorIsOpen', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'sensorOn', 'event' => 'exclude');

$config['mibs'][$mib]['status']['monitorAnySensorSignal1contact4']['indexes'][0] = array(
  'descr'            => '1st sensor', // Fallback
  'oid_descr'        => 'userSense1Name.0',
  'type'             => 'monitorAnySensorSignal2',
  'measured'         => 'other',
  'rename_rrd_array' => array('type' => 'monitorAnySensorSignalcontact'), // old discovery params
  'oid_num'          => '.1.3.6.1.4.1.40418.2.2.3.3.0'
);
$config['mibs'][$mib]['status']['monitorAnySensorSignal2contact7']['indexes'][0] = array(
  'descr'            => '2nd sensor', // Fallback
  'oid_descr'        => 'userSense2Name.0',
  'measured'         => 'other',
  'type'             => 'monitorAnySensorSignal2',
  'rename_rrd_array' => array('type' => 'monitorAnySensorSignalcontact'), // old discovery params
  'oid_num'          => '.1.3.6.1.4.1.40418.2.2.3.4.0'
);
$config['mibs'][$mib]['status']['monitorAnySensorSignal3contact9']['indexes'][0] = array(
  'descr'            => '3rd sensor', // Fallback
  'oid_descr'        => 'userSense3Name.0',
  'measured'         => 'other',
  'type'             => 'monitorAnySensorSignal2',
  'rename_rrd_array' => array('type' => 'monitorAnySensorSignalcontact'), // old discovery params
  'oid_num'          => '.1.3.6.1.4.1.40418.2.2.3.5.0'
);

$type = 'monitorAnySensorSignal2';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'sensorOff', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'sensorIs0', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'sensorIs1', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'sensorOn', 'event' => 'exclude');

$config['mibs'][$mib]['status']['monitorVoltageSignal']['indexes'][0] = array(
  'descr'    => 'voltage on sensor',
  'measured' => 'power',
  'type'     => 'monitorVoltageSignal',
  'oid_num'  => '.1.3.6.1.4.1.40418.2.2.3.6.0'
);

$type = 'monitorVoltageSignal';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'sensorOff', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'voltageIsNo', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'voltageIsYes', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'sensorOn', 'event' => 'exclude');

$config['mibs'][$mib]['status']['remoteControlContact8']['indexes'][0] = array(
  'descr'    => 'SMART switch', // FIXME. WAT?
  'measured' => 'other',
  'type'     => 'remoteControlContact',
  'oid_num'  => '.1.3.6.1.4.1.40418.2.2.2.3.0'
);

$type = 'remoteControlContact';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'manON', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'manOFF', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'manualSetON', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'termostatSetON', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'switch', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'autoON', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'autoOFF', 'event' => 'ok');

$mib = 'SNR-ERD-4';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.40418.2.6';
$config['mibs'][$mib]['mib_dir'] = 'snr';
$config['mibs'][$mib]['descr'] = 'SNR Ethernet Remote Device v4';

// Cisco

// CISCO-PAGP-MIB

$mib = 'CISCO-PAGP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.98';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

// CISCO-VLAN-MEMBERSHIP-MIB

$mib = 'CISCO-VLAN-MEMBERSHIP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.68';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

// CISCO-ENTITY-ASSET-MIB

$mib = 'CISCO-ENTITY-ASSET-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.92';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'ceAssetSerialNumber.1');

// CISCO-AAA-SESSION-MIB

$mib = 'CISCO-AAA-SESSION-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.150';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = 'Cisco AAA Statistics';

// CISCO-CLASS-BASED-QOS-MIB

$mib = 'CISCO-CLASS-BASED-QOS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.166';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

// CISCO-RTTMON-MIB

$mib = 'CISCO-RTTMON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.42';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = 'Round Trip Time (RTT) monitoring of a list of targets';
$config['mibs'][$mib]['sla_states'] = array(
  'other'                      => array('num' => '0', 'event' => 'warning'),
  'ok'                         => array('num' => '1', 'event' => 'ok'),
  'disconnected'               => array('num' => '2', 'event' => 'alert'),
  'overThreshold'              => array('num' => '3', 'event' => 'warning'),
  'timeout'                    => array('num' => '4', 'event' => 'alert'),
  'busy'                       => array('num' => '5', 'event' => 'warning'),
  'notConnected'               => array('num' => '6', 'event' => 'alert'),
  'dropped'                    => array('num' => '7', 'event' => 'alert'),
  'sequenceError'              => array('num' => '8', 'event' => 'alert'),
  'verifyError'                => array('num' => '9', 'event' => 'alert'),
  'applicationSpecific'        => array('num' => '10', 'event' => 'alert'),
  'dnsServerTimeout'           => array('num' => '11', 'event' => 'alert'),
  'tcpConnectTimeout'          => array('num' => '12', 'event' => 'alert'),
  'httpTransactionTimeout'     => array('num' => '13', 'event' => 'alert'),
  'dnsQueryError'              => array('num' => '14', 'event' => 'alert'),
  'httpError'                  => array('num' => '15', 'event' => 'alert'),
  'error'                      => array('num' => '16', 'event' => 'alert'),
  'mplsLspEchoTxError'         => array('num' => '17', 'event' => 'alert'),
  'mplsLspUnreachable'         => array('num' => '18', 'event' => 'alert'),
  'mplsLspMalformedReq'        => array('num' => '19', 'event' => 'alert'),
  'mplsLspReachButNotFEC'      => array('num' => '20', 'event' => 'warning'),
  'enableOk'                   => array('num' => '21', 'event' => 'ok'),
  'enableNoConnect'            => array('num' => '22', 'event' => 'alert'),
  'enableVersionFail'          => array('num' => '23', 'event' => 'alert'),
  'enableInternalError'        => array('num' => '24', 'event' => 'alert'),
  'enableAbort'                => array('num' => '25', 'event' => 'warning'),
  'enableFail'                 => array('num' => '26', 'event' => 'alert'),
  'enableAuthFail'             => array('num' => '27', 'event' => 'alert'),
  'enableFormatError'          => array('num' => '28', 'event' => 'alert'),
  'enablePortInUse'            => array('num' => '29', 'event' => 'warning'),
  'statsRetrieveOk'            => array('num' => '30', 'event' => 'ok'),
  'statsRetrieveNoConnect'     => array('num' => '31', 'event' => 'alert'),
  'statsRetrieveVersionFail'   => array('num' => '32', 'event' => 'alert'),
  'statsRetrieveInternalError' => array('num' => '33', 'event' => 'alert'),
  'statsRetrieveAbort'         => array('num' => '34', 'event' => 'alert'),
  'statsRetrieveFail'          => array('num' => '35', 'event' => 'alert'),
  'statsRetrieveAuthFail'      => array('num' => '36', 'event' => 'alert'),
  'statsRetrieveFormatError'   => array('num' => '37', 'event' => 'alert'),
  'statsRetrievePortInUse'     => array('num' => '38', 'event' => 'warning'),
);

$mib = 'CISCO-RTTMON-IP-EXT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.572';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-RTTMON-ICMP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.486';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

// JUNIPER-MAC-MIB

$mib = 'JUNIPER-MAC-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.3.23';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

// JUNIPER-WX-GLOBAL-REG

$mib = 'JUNIPER-WX-GLOBAL-REG';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'jnxWxSysSerialNumber.0'); // JUNIPER-WX-COMMON-MIB::jnxWxSysSerialNumber.0 = STRING: 0060000604
$config['mibs'][$mib]['version'][] = array('oid' => 'jnxWxSysSwVersion.0'); // JUNIPER-WX-COMMON-MIB::jnxWxSysSwVersion.0 = STRING: 5.6.2.0

$mib = 'EX2500-BASE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1411';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

// CISCO-IETF-IP-MIB

$mib = 'CISCO-IETF-IP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.10.86';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

// BGP4-V2-MIB-JUNIPER

$mib = 'BGP4-V2-MIB-JUNIPER';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.5.1.1';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

// FORCE10-BGP4-V2-MIB

$mib = 'FORCE10-BGP4-V2-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6027.20.1';
$config['mibs'][$mib]['mib_dir'] = 'force10';
$config['mibs'][$mib]['descr'] = '';

// CISCO-BGP4-MIB

$mib = 'CISCO-BGP4-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.187';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

// CISCO-CEF-MIB

$mib = 'CISCO-CEF-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.492';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

// CISCO-IETF-PW-MPLS-MIB

$mib = 'CISCO-IETF-PW-MPLS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.10.107';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

// VMWARE-VMINFO-MIB

$mib = 'VMWARE-VMINFO-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6876.2.10';
$config['mibs'][$mib]['mib_dir'] = 'vmware';
$config['mibs'][$mib]['descr'] = '';

// VMWARE-SYSTEM-MIB

$mib = 'VMWARE-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6876.1.10';
$config['mibs'][$mib]['mib_dir'] = 'vmware';
$config['mibs'][$mib]['descr'] = '';

$mib = 'AC-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.5003.9.10.10';
$config['mibs'][$mib]['mib_dir'] = 'audiocodes';
$config['mibs'][$mib]['descr'] = 'Audiocodes System';
$config['mibs'][$mib]['serial'][] = array('oid' => 'acSysIdSerialNumber.0'); // AC-SYSTEM-MIB::acSysIdSerialNumber.0 = Wrong Type (should be Gauge32 or Unsigned32): INTEGER: 2182014
$config['mibs'][$mib]['version'][] = array('oid' => 'acSysVersionSoftware.0'); // AC-SYSTEM-MIB::acSysVersionSoftware.0 = STRING: 4.80A.014.006
$config['mibs'][$mib]['hardware'][] = array('oid' => 'acSysIdName.0'); // AC-SYSTEM-MIB::acSysIdName.0 = STRING: MP-118 FXS

$config['mibs'][$mib]['states']['ac-system-fan-state'][0] = array('name' => 'cleared', 'event' => 'ok');
$config['mibs'][$mib]['states']['ac-system-fan-state'][1] = array('name' => 'indeterminate', 'event' => 'exclude');
$config['mibs'][$mib]['states']['ac-system-fan-state'][2] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states']['ac-system-fan-state'][3] = array('name' => 'minor', 'event' => 'ok');
$config['mibs'][$mib]['states']['ac-system-fan-state'][4] = array('name' => 'major', 'event' => 'warning');
$config['mibs'][$mib]['states']['ac-system-fan-state'][5] = array('name' => 'critical', 'event' => 'alert');

$config['mibs'][$mib]['states']['ac-system-power-state'][1] = array('name' => 'cleared', 'event' => 'ok');
$config['mibs'][$mib]['states']['ac-system-power-state'][2] = array('name' => 'indeterminate', 'event' => 'exclude');
$config['mibs'][$mib]['states']['ac-system-power-state'][3] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states']['ac-system-power-state'][4] = array('name' => 'minor', 'event' => 'ok');
$config['mibs'][$mib]['states']['ac-system-power-state'][5] = array('name' => 'major', 'event' => 'warning');
$config['mibs'][$mib]['states']['ac-system-power-state'][6] = array('name' => 'critical', 'event' => 'alert');

$mib = 'ACMEPACKET-ENTITY-VENDORTYPE-OID-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9148.6.1';
$config['mibs'][$mib]['mib_dir'] = 'acme';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ACMEPACKET-ENVMON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9148.3.3';
$config['mibs'][$mib]['mib_dir'] = 'acme';
$config['mibs'][$mib]['descr'] = 'Acme Packet Environmental Monitoring';

$type = 'acme-env-state'; // ApEnvMonState
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'initial', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'minor', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'major', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'critical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'shutdown', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'notPresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'notFunctioning', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'unknown', 'event' => 'exclude');

$mib = 'ACS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.10418.16';
$config['mibs'][$mib]['mib_dir'] = 'cyclades';
$config['mibs'][$mib]['descr'] = 'Avocent and Cyclades PDU and Power Management';
$config['mibs'][$mib]['serial'][] = array('oid' => 'acsSerialNumber.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'acsFirmwareVersion.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'acsProductModel.0');

$mib = 'ADTRAN-AOSCPU';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.664.6.10000.4';
$config['mibs'][$mib]['mib_dir'] = 'adtran';
$config['mibs'][$mib]['descr'] = 'Adtran AOS CPU utilization, Memory usage and System process status';
$config['mibs'][$mib]['processor']['adGenAOS5MinCpuUtil'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'adGenAOS5MinCpuUtil.0', 'oid_num' => '.1.3.6.1.4.1.664.5.53.1.4.4.0');
$config['mibs'][$mib]['mempool']['adGenAOSCpuUtil'] = array('type'      => 'static', 'descr' => 'Heap',
                                                            'oid_free'  => 'adGenAOSHeapFree.0', 'oid_free_num' => '.1.3.6.1.4.1.664.5.53.1.4.8.0', // ADTRAN-AOSCPU::adGenAOSHeapFree.0 = Gauge32: 81300464
                                                            'oid_total' => 'adGenAOSHeapSize.0', 'oid_total_num' => '.1.3.6.1.4.1.664.5.53.1.4.7.0', // ADTRAN-AOSCPU::adGenAOSHeapSize.0 = Gauge32: 103795696
);
// Unused: ADTRAN-AOSCPU::adGenAOSMemPool.0 = Gauge32: 134217727

// ADTRAN-AOSUNIT

$mib = 'ADTRAN-AOSUNIT';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'adtran';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'adAOSDeviceSerialNumber.0'); // ADTRAN-AOSUNIT::adAOSDeviceSerialNumber.0 = STRING: CFG034348
$config['mibs'][$mib]['version'][] = array('oid' => 'adAOSDeviceVersion.0'); // ADTRAN-AOSUNIT::adAOSDeviceVersion.0 = STRING: A2.06.00.E
$config['mibs'][$mib]['hardware'][] = array('oid' => 'adAOSDeviceProductName.0'); // ADTRAN-AOSUNIT::adAOSDeviceProductName.0 = STRING: Total Access 908e (2nd Gen)

$mib = 'AETHRA-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.7745.5';
$config['mibs'][$mib]['mib_dir'] = 'aethra';
$config['mibs'][$mib]['descr'] = 'Aethra Telecommunications Enterprise MIB';
$config['mibs'][$mib]['processor']['performanceCpuAvg5min'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'performanceCpuAvg5min.0', 'oid_num' => '.1.3.6.1.4.1.7745.5.2.19.2.0');
$config['mibs'][$mib]['mempool']['performance'] = array('type'      => 'static', 'descr' => 'Memory', 'scale' => 1024,
                                                        'oid_free'  => 'performanceDynMemFree.0', 'oid_free_num' => '.1.3.6.1.4.1.7745.5.2.19.6.0', // AETHRA-MIB::performanceDynMemFree.0 = INTEGER: 17504
                                                        'oid_total' => 'performanceDynMemTotal.0', 'oid_total_num' => '.1.3.6.1.4.1.7745.5.2.19.5.0', // AETHRA-MIB::performanceDynMemTotal.0 = INTEGER: 57840
);

$mib = 'AGENT-GENERAL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.171.12.1';
$config['mibs'][$mib]['mib_dir'] = 'd-link';
$config['mibs'][$mib]['descr'] = 'D-Link General System MIB';
$config['mibs'][$mib]['serial'][] = array('oid' => 'agentSerialNumber.0'); // AGENT-GENERAL-MIB::agentSerialNumber.0 = STRING: "PL5T2A1000668"
// agentCPUutilizationIn5min.0 = INTEGER: 25
$config['mibs'][$mib]['processor']['agentCPUutilizationIn5min'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'agentCPUutilizationIn5min.0', 'oid_num' => '.1.3.6.1.4.1.171.12.1.1.6.3.0');

$mib = 'ZONE-DEFENSE-MGMT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.171.12.92';
$config['mibs'][$mib]['mib_dir'] = 'd-link';
$config['mibs'][$mib]['descr'] = '';

$mib = 'DLINK-3100-POE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.171.10.94.89.89.108';
$config['mibs'][$mib]['mib_dir'] = 'd-link';
$config['mibs'][$mib]['descr'] = '';

$mib = 'AIRESPACE-SWITCHING-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14179.1';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'agentInventorySerialNumber.0'); // AIRESPACE-SWITCHING-MIB::agentInventorySerialNumber.0 = STRING: FCW1546L0D6

$config['mibs'][$mib]['processor']['agentCurrentCPUUtilization'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'agentCurrentCPUUtilization.0', 'oid_num' => '.1.3.6.1.4.1.14179.1.1.5.1.0'); // AIRESPACE-SWITCHING-MIB::agentCurrentCPUUtilization.0 = 0
$config['mibs'][$mib]['mempool']['agentResourceInfoGroup'] = array('type'      => 'static', 'descr' => 'Memory', 'scale' => 1024,
                                                                   'oid_total' => 'agentTotalMemory.0', 'oid_total_num' => '.1.3.6.1.4.1.14179.1.1.5.2.0', // AIRESPACE-SWITCHING-MIB::agentTotalMemory.0 = 1000952
                                                                   'oid_free'  => 'agentFreeMemory.0', 'oid_free_num' => '.1.3.6.1.4.1.14179.1.1.5.3.0', // AIRESPACE-SWITCHING-MIB::agentFreeMemory.0 = 466732
);

$mib = 'AIRESPACE-WIRELESS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14179.2';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'AIRPORT-BASESTATION-3-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.63.501.3';
$config['mibs'][$mib]['mib_dir'] = 'apple';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'sysConfFirmwareVersion.0');

$mib = 'ALCATEL-IND1-CHASSIS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6486.800.1.1.1.3.1';
$config['mibs'][$mib]['mib_dir'] = 'aos';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ALCATEL-IND1-HEALTH-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6486.800.1.2.1.16.1';
$config['mibs'][$mib]['mib_dir'] = 'aos';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ALCATEL-IND1-INTERSWITCH-PROTOCOL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6486.800.1.2.1.9.1';
$config['mibs'][$mib]['mib_dir'] = 'aos';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ALTIGA-HARDWARE-STATS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3076.1.1.27.2';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'alHardwareSerialNumber.0');
$config['mibs'][$mib]['sensor']['alHardwareCpuVoltage']['indexes'][0] = array('descr' => 'CPU Voltage', 'class' => 'voltage', 'measured' => 'processor', 'scale' => '0.01');
$config['mibs'][$mib]['sensor']['alHardwareBoardVoltage3v']['indexes'][0] = array('descr' => 'Board 3V Voltage', 'class' => 'voltage', 'measured' => 'other', 'scale' => '0.01');
$config['mibs'][$mib]['sensor']['alHardwareBoardVoltage5v']['indexes'][0] = array('descr' => 'Board 5V Voltage', 'class' => 'voltage', 'measured' => 'other', 'scale' => '0.01');
$config['mibs'][$mib]['sensor']['alHardwareCpuTemp']['indexes'][0] = array('descr' => 'CPU Temperature', 'class' => 'temperature', 'measured' => 'processor', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.3076.2.1.2.22.1.29.0');
$config['mibs'][$mib]['sensor']['alHardwareCageTemp']['indexes'][0] = array('descr' => 'Cage Temperature', 'class' => 'temperature', 'measured' => 'other', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.3076.2.1.2.22.1.33.0');
$config['mibs'][$mib]['sensor']['alHardwareFan1Rpm']['indexes'][0] = array('descr' => 'Fan 1', 'class' => 'fanspeed', 'measured' => 'fan', 'scale' => 1, 'min' => 0, 'oid_num' => '.1.3.6.1.4.1.3076.2.1.2.22.1.37.0');
$config['mibs'][$mib]['sensor']['alHardwareFan2Rpm']['indexes'][0] = array('descr' => 'Fan 2', 'class' => 'fanspeed', 'measured' => 'fan', 'scale' => 1, 'min' => 0, 'oid_num' => '.1.3.6.1.4.1.3076.2.1.2.22.1.41.0');
$config['mibs'][$mib]['sensor']['alHardwareFan3Rpm']['indexes'][0] = array('descr' => 'Fan 3', 'class' => 'fanspeed', 'measured' => 'fan', 'scale' => 1, 'min' => 0, 'oid_num' => '.1.3.6.1.4.1.3076.2.1.2.22.1.45.0');

// ALTIGA-VERSION-STATS-MIB

$mib = 'ALTIGA-VERSION-STATS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3076.1.1.6.2';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'alVersionString.0', 'transformations' => array(array('action' => 'replace', 'from' => '.Rel', 'to' => '')));

// ALTIGA-SSL-STATS-MIB

$mib = 'ALTIGA-SSL-STATS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

// ALVARION-DOT11-WLAN-TST-MIB

$mib = 'ALVARION-DOT11-WLAN-TST-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'alvarion';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['hardware'][] = array('oid' => 'brzLighteOemProjectNameString.0'); // ALVARION-DOT11-WLAN-TST-MIB::brzLighteOemProjectNameString.0 = STRING: "BreezeACCESS VL"

$mib = 'ALVARION-DOT11-WLAN-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.12394.1.1';
$config['mibs'][$mib]['mib_dir'] = 'alvarion';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'brzaccVLMainVersionNumber.0'); // ALVARION-DOT11-WLAN-MIB::brzaccVLMainVersionNumber.0 = STRING: "6.6.2"

$mib = 'APNL-MODULAR-PDU-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.29640';
$config['mibs'][$mib]['mib_dir'] = 'apnl';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'apnlModules.pdu.pduSerialNumber.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'apnlModules.pdu.pduSoftwareVersion.0'); // APNL-MODULAR-PDU-MIB::apnlModules.pdu.pduSoftwareVersion.0 = STRING: V2.23

$mib = 'APSYSMGMT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9148.3.2';
$config['mibs'][$mib]['mib_dir'] = 'acme';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['processor']['apSysCPUUtil'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'apSysCPUUtil.0', 'oid_num' => '.1.3.6.1.4.1.9148.3.2.1.1.1.0');
$config['mibs'][$mib]['mempool']['apSysMemoryUtil'] = array('type' => 'static', 'descr' => 'Memory', 'oid_perc' => 'apSysMemoryUtil.0', 'oid_perc_num' => '.1.3.6.1.4.1.9148.3.2.1.1.2.0');

$mib = 'ARECA-SNMP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'areca';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'siSerial.0'); // New SAS-controller MIB
$config['mibs'][$mib]['serial'][] = array('oid_num' => '.1.3.6.1.4.1.18928.1.1.1.3.0'); // Old SATA-controller MIB; ARECA-SNMP-MIB::ArecaGroup1.1.1.3.0 because they re-used the MIB name with other OIDs
$config['mibs'][$mib]['version'][] = array('oid' => 'siFirmVer.0'); // New SAS-controller MIB
$config['mibs'][$mib]['version'][] = array('oid_num' => '.1.3.6.1.4.1.18928.1.1.1.4.0'); // Old SATA-controller MIB; ARECA-SNMP-MIB::ArecaGroup1.1.1.4.0 because they re-used the MIB name with other OIDs
$config['mibs'][$mib]['hardware'][] = array('oid' => 'siVendor.0'); // New SAS-controller MIB
$config['mibs'][$mib]['hardware'][] = array('oid_num' => '.1.3.6.1.4.1.18928.1.2.1.1.0'); // Old SATA-controller MIB; ARECA-SNMP-MIB::ArecaGroup1.2.1.1.0 because they re-used the MIB name with other OIDs

$config['mibs'][$mib]['states']['areca-power-state'][0] = array('name' => 'Failed', 'event' => 'alert');
$config['mibs'][$mib]['states']['areca-power-state'][1] = array('name' => 'Ok', 'event' => 'ok');

// ARISTA-SW-IP-FORWARDING-MIB

$mib = 'ARISTA-SW-IP-FORWARDING-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.30065.3.1';
$config['mibs'][$mib]['mib_dir'] = 'arista';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ARISTA-ENTITY-SENSOR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.30065.3.12';
$config['mibs'][$mib]['mib_dir'] = 'arista';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ASYNCOS-MAIL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.15497.1.1.1';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['processor']['perCentCPUUtilization'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'perCentCPUUtilization.0', 'oid_num' => '.1.3.6.1.4.1.15497.1.1.1.2.0');
$config['mibs'][$mib]['mempool']['perCentMemoryUtilization'] = array('type' => 'static', 'descr' => 'Memory', 'oid_perc' => 'perCentMemoryUtilization.0', 'oid_perc_num' => '.1.3.6.1.4.1.15497.1.1.1.1.0');

$mib = 'AT-SETUP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.207.8.4.4.4.500';
$config['mibs'][$mib]['mib_dir'] = 'allied';
$config['mibs'][$mib]['descr'] = '';

$mib = 'AT-SYSINFO-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.207.8.4.4.3';
$config['mibs'][$mib]['mib_dir'] = 'allied';
$config['mibs'][$mib]['descr'] = '';
// AT-SYSINFO-MIB::cpuUtilisationMax.0 = INTEGER: 43
// AT-SYSINFO-MIB::cpuUtilisationAvg.0 = INTEGER: 4
// AT-SYSINFO-MIB::cpuUtilisationAvgLastMinute.0 = INTEGER: 3
// AT-SYSINFO-MIB::cpuUtilisationAvgLast10Seconds.0 = INTEGER: 6
// AT-SYSINFO-MIB::cpuUtilisationAvgLastSecond.0 = INTEGER: 3
// AT-SYSINFO-MIB::cpuUtilisationMaxLast5Minutes.0 = INTEGER: 45
// AT-SYSINFO-MIB::cpuUtilisationAvgLast5Minutes.0 = INTEGER: 6
$config['mibs'][$mib]['processor']['cpuUtilisationAvgLast5Minutes'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'cpuUtilisationAvgLast5Minutes.0', 'oid_num' => '.1.3.6.1.4.1.207.8.4.4.3.3.7.0');

$mib = 'ATEN-PE-CFG';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.21317';
$config['mibs'][$mib]['mib_dir'] = 'aten';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'deviceFWversion.0'); // ATEN-PE-CFG::deviceFWversion.0 = STRING: "1.5.148"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'modelName.0'); // ATEN-PE-CFG::modelName.0 = STRING: "PE8108G"

$mib = 'ATTO6500N-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4547.2.3';
$config['mibs'][$mib]['mib_dir'] = 'atto';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ATTOBRIDGE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4547.1.2';
$config['mibs'][$mib]['mib_dir'] = 'atto';
$config['mibs'][$mib]['descr'] = '';

$mib = 'AXIS-VIDEO-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.368.4';
$config['mibs'][$mib]['mib_dir'] = 'axis';
$config['mibs'][$mib]['descr'] = '';

$type = 'axisStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'failure', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'outOfBoundary', 'event' => 'alert');

$mib = 'B100-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4547.1.2';
$config['mibs'][$mib]['mib_dir'] = 'kemp';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'version.0');

$config['mibs'][$mib]['status']['hAstate']['indexes'][0] = array('descr'    => 'HA status',
                                                                 'measured' => 'device',
                                                                 'type'     => 'kemp-hAstate');

$type = 'kemp-hAstate';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'none', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'master', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'standby', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'passive', 'event' => 'ok');

$mib = 'Baytech-MIB-403-1';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'baytech';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'sBTAIdentSerialNumber.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'sBTAIdentFirmwareRev.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'sBTAModulesRPCFirmwareVersion.1');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'sBTAIdentUnitName.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'sBTAModulesRPCName.1');

$mib = 'BETTER-NETWORKS-ETHERNETBOX-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14848.2.1.1';
$config['mibs'][$mib]['mib_dir'] = 'messpc';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['sysdescr'][] = array('oid' => 'version.0');  // BETTER-NETWORKS-ETHERNETBOX-MIB::version.0 = STRING: "Version 1.62"
$config['mibs'][$mib]['syslocation'][] = array('oid' => 'location.0'); // BETTER-NETWORKS-ETHERNETBOX-MIB::location.0 = STRING: "Nadlinger"
$config['mibs'][$mib]['sysuptime'][] = array('oid' => 'uptime.0', 'transformations' => array(array('action' => 'timeticks'))); // BETTER-NETWORKS-ETHERNETBOX-MIB::uptime.0 = Timeticks: (1326603950) 153 days, 13:00:39.50

$mib = 'BIANCA-BRICK-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.272.250';
$config['mibs'][$mib]['mib_dir'] = 'bintec';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'biboAdmSystemId.0'); // BIANCA-BRICK-MIB::biboAdmSystemId.0 = STRING: SX3200208233880
$config['mibs'][$mib]['hardware'][] = array('oid' => 'biboAdmLocalPPPIdent.0'); // BIANCA-BRICK-MIB::biboAdmLocalPPPIdent.0 = STRING: x2301w
$config['mibs'][$mib]['features'][] = array('oid' => 'biboABrdPartNo.0.0.0'); // BIANCA-BRICK-MIB::biboABrdPartNo.0.0.0 = STRING: Business Livebox 100

/*
BIANCA-BRICK-MIB::biboABrdType.0.0.0 = STRING: Livebox 100 (4/16 MB)
BIANCA-BRICK-MIB::biboABrdHWRelease.0.0.0 = STRING: 2.0
BIANCA-BRICK-MIB::biboABrdFWRelease.0.0.0 = STRING: 1.1
BIANCA-BRICK-MIB::biboABrdSerialNo.0.0.0 = STRING: SX3200208233880
*/

$mib = 'BIANCA-BRICK-MIBRES-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.272.4.17.4.255';
$config['mibs'][$mib]['mib_dir'] = 'bintec';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['processor']['cpuTable'] = array('type'    => 'table',
                                                       'table'   => 'cpuTable',
                                                       'idle'    => TRUE,
                                                       'descr'   => 'Processor %i%',
                                                       'oid'     => 'cpuLoadIdle60s',
                                                       'oid_num' => '.1.3.6.1.4.1.272.4.17.4.1.1.18');

$mib = 'BLUECOAT-HOST-RESOURCES-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'bluecoat';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'bchrSerial.0');

$mib = 'BLUECOAT-AV-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3417.2.10';
$config['mibs'][$mib]['mib_dir'] = 'bluecoat';
$config['mibs'][$mib]['descr'] = '';

$mib = 'BLUECOAT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3417';
$config['mibs'][$mib]['mib_dir'] = 'bluecoat';
$config['mibs'][$mib]['descr'] = '';

$mib = 'BLUECOAT-CAS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14501.8';
$config['mibs'][$mib]['mib_dir'] = 'bluecoat';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'bchrSerial.0');

$mib = 'BLUECOAT-SG-PROXY-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3417.2.11';
$config['mibs'][$mib]['mib_dir'] = 'bluecoat';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'sgProxySerialNumber.0');

$mib = 'BLUECOAT-SG-SENSOR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3417.2.1';
$config['mibs'][$mib]['mib_dir'] = 'bluecoat';
$config['mibs'][$mib]['descr'] = '';

$mib = 'BLUECOAT-SG-USAGE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3417.2.4';
$config['mibs'][$mib]['mib_dir'] = 'bluecoat';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CAMBIUM-PTP800-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.17713';
$config['mibs'][$mib]['mib_dir'] = 'cambium';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'rFUSerial.0');

$mib = 'CAREL-ug40cdz-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9839.2.1';
$config['mibs'][$mib]['mib_dir'] = 'carel';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['sensor']['roomRH']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Room Relative Humidity', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.6.0', 'min' => 0, 'scale' => 0.1, 'oid_limit_high' => '.1.3.6.1.4.1.9839.2.1.3.14.0', 'oid_limit_low' => '.1.3.6.1.4.1.9839.2.1.3.15.0'); // hhAlarmThrsh, lhAlarmThrsh
$config['mibs'][$mib]['sensor']['dehumPband']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Dehumidification Prop. Band', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.12.0', 'min' => 0, 'scale' => 1);
$config['mibs'][$mib]['sensor']['humidPband']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Humidification Prop. Band', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.13.0', 'min' => 0, 'scale' => 1);
$config['mibs'][$mib]['sensor']['dehumSetp']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Dehumidification Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.16.0', 'min' => 0, 'scale' => 1);
$config['mibs'][$mib]['sensor']['humidSetp']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Humidification Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.17.0', 'min' => 0, 'scale' => 1);
$config['mibs'][$mib]['sensor']['roomTemp']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Room Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.1.0', 'min' => 0, 'scale' => 0.1, 'oid_limit_high' => '.1.3.6.1.4.1.9839.2.1.2.26.0', 'oid_limit_low' => '.1.3.6.1.4.1.9839.2.1.2.27.0'); // thrsHT, thrsLT
$config['mibs'][$mib]['sensor']['outdoorTemp']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Ambient Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.2.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['deliveryTemp']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Delivery Air Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.3.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['cwTemp']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Chilled Water Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.4.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['hwTemp']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Hot Water Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.5.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['cwoTemp']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Chilled Water Outlet Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.7.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['suctTemp1']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Circuit 1 Suction Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.10.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['suctTemp2']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Circuit 2 Suction Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.11.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['evapTemp1']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Circuit 1 Evap. Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.12.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['evapTemp2']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Circuit 2 Evap. Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.13.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['ssh1']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Circuit 1 Superheat', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.14.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['ssh2']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Circuit 2 Superheat', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.15.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['coolSetP']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Cooling Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.20.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['coolDiff']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Cooling Prop. Band', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.21.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['cool2SetP']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Cooling 2nd Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.22.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['heatSetP']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Heating Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.23.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['heat2SetP']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Heating 2nd Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.24.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['heatDiff']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Heating Prop. Band', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.25.0', 'min' => 0, 'scale' => 0.1);

/* Currently unused CAREL-ug40cdz-MIB sensor OIDs:
 * smCoolSetp.0 = INTEGER: 280 degrees C
 * smHeatSetp.0 = INTEGER: 160 degrees C
 * cwDehumSetp.0 = INTEGER: 70 degrees C
 * cwHtThrsh.0 = INTEGER: 150 degrees C
 * cwModeSetp.0 = INTEGER: 70 degrees C
 * radcoolSpES.0 = INTEGER: 80 degrees C
 * radcoolSpDX.0 = INTEGER: 280 degrees C
 * delTempLimit.0 = INTEGER: 14 degrees C x10
 * dtAutChgMLT.0 = INTEGER: 20 degrees C

 * evapPress1.0 = INTEGER: 98 bar
 * evapPress2.0 = INTEGER: 0 bar

 * coolRamp.0 = INTEGER: 0 %
 * heatRamp.0 = INTEGER: 0 %
 * fanSpeed.0 = INTEGER: 0 %

 * filterWorkH.0 = INTEGER: 27195 h
 * unitWorkH.0 = INTEGER: 27195 h
 * compr1WorkH.0 = INTEGER: 7483 h
 * compr2WorkH.0 = INTEGER: 7344 h
 * compr3WorkH.0 = INTEGER: 0 h
 * compr4WorkH.0 = INTEGER: 0 h
 * heat1WorkH.0 = INTEGER: 0 h
 * heat2WorkH.0 = INTEGER: 0 h
 * humiWorkH.0 = INTEGER: 220 h
 */

// UNCDZ-MIB

$mib = 'UNCDZ-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9839.2.1';
$config['mibs'][$mib]['mib_dir'] = 'carel';
$config['mibs'][$mib]['descr'] = '';

$type = 'uncdz-mib-okfail';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'fail', 'event' => 'alert');

$type = 'uncdz-mib-okok';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'off', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'on', 'event' => 'ok');

$config['mibs'][$mib]['sensor']['temp-amb']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Room Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.1.0', 'min' => 0, 'scale' => 0.1, 'oid_limit_high' => '.1.3.6.1.4.1.9839.2.1.2.11.0', 'oid_limit_low' => '.1.3.6.1.4.1.9839.2.1.2.12.0');
$config['mibs'][$mib]['sensor']['temp-ext']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Outdoor Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.2.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['temp-mand']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Delivery Air Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.3.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['temp-circ']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Closed Circuit (or Chilled) Water Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.4.0', 'min' => 0, 'scale' => 0.1, 'oid_limit_high' => '.1.3.6.1.4.1.9839.2.1.2.16'); // htset-cw
$config['mibs'][$mib]['sensor']['temp-ac']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Hot Water Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.5.0', 'min' => 0, 'scale' => 0.1);

$config['mibs'][$mib]['sensor']['umid-amb']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Room Relative Humidity', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.6.0', 'min' => 0, 'scale' => 0.1, 'oid_limit_high' => '.1.3.6.1.4.1.9839.2.1.3.14.0', 'oid_limit_low' => '.1.3.6.1.4.1.9839.2.1.3.15.0'); // hh-set, lh-set
$config['mibs'][$mib]['sensor']['hdiff']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Dehumidification Proportional Band', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.12.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['hu-diff']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Humidification Proportional Band', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.13.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['hset']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Dehumidification Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.16.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['hset-sm']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Setback Mode Dehumidification Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.17.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['t-set-sm']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Setback Mode Cooling Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.13.0', 'min' => 0);

$config['mibs'][$mib]['sensor']['t-set-c-sm']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Setback Mode Cooling Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.14.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['t-cw-dh']['indexes'][0] = array('class' => 'temperature', 'descr' => 'CW Set Point to Start Dehumidification Cycle', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.15.0', 'min' => 0, 'scale' => 0.1);

$config['mibs'][$mib]['sensor']['t-set-cw']['indexes'][0] = array('class' => 'temperature', 'descr' => 'CW Set Point to Start WC Operating Mode', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.17.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['t-rc-es']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Rad-cooler Set Point in ES Mode', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.18.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['t-rc-est']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Rad-cooler Set Point in DX Mode', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.19.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['t-set-lm']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Delivery Air Temperature Limit Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.23.0', 'min' => 0, 'scale' => 0.1);

$config['mibs'][$mib]['status']['t-set']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Cooling Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.7.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['status']['t-diff']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Cooling Proportional Band', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.8.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['status']['t-set-c']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Heating Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.9.0', 'scale' => 0.1);
$config['mibs'][$mib]['status']['t-diff-c']['indexes'][0] = array('class' => 'temperature', 'descr' => 'Heating Proportional Band', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.10.0', 'min' => 0, 'scale' => 0.1);

$config['mibs'][$mib]['sensor']['hu-set']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Humidification Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.18.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['hu-set-sm']['indexes'][0] = array('class' => 'humidity', 'descr' => 'Setback Mode Humidification Set Point', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.19.0', 'min' => 0, 'scale' => 0.1);

$config['mibs'][$mib]['status']['vent-on']['indexes'][0] = array('descr' => 'System Fan', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.1.0', 'type' => 'uncdz-mib-okok');
$config['mibs'][$mib]['status']['compressore1']['indexes'][0] = array('descr' => 'Compressor 1', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.2.0', 'type' => 'uncdz-mib-okok');
$config['mibs'][$mib]['status']['compressore2']['indexes'][0] = array('descr' => 'Compressor 2', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.3.0', 'type' => 'uncdz-mib-okok');
$config['mibs'][$mib]['status']['compressore3']['indexes'][0] = array('descr' => 'Compressor 3', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.4.0', 'type' => 'uncdz-mib-okok');
$config['mibs'][$mib]['status']['compressore4']['indexes'][0] = array('descr' => 'Compressor 4', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.5.0', 'type' => 'uncdz-mib-okok');
$config['mibs'][$mib]['status']['out-h1']['indexes'][0] = array('descr' => 'Heating 1', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.6.0', 'type' => 'uncdz-mib-okok');
$config['mibs'][$mib]['status']['out-h2']['indexes'][0] = array('descr' => 'Heating 2', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.7.0', 'type' => 'uncdz-mib-okok');
$config['mibs'][$mib]['status']['out-h3']['indexes'][0] = array('descr' => 'Heating 3', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.8.0', 'type' => 'uncdz-mib-okok');
$config['mibs'][$mib]['status']['gas-caldo-on']['indexes'][0] = array('descr' => 'Hot Gas Coil', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.9.0', 'type' => 'uncdz-mib-okok');
$config['mibs'][$mib]['status']['on-deum']['indexes'][0] = array('descr' => 'Dehumidification', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.10.0', 'type' => 'uncdz-mib-okok');
$config['mibs'][$mib]['status']['power']['indexes'][0] = array('descr' => 'Humidification', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.11.0', 'type' => 'uncdz-mib-okok');
$config['mibs'][$mib]['status']['mal-access']['indexes'][0] = array('descr' => 'Tampering', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.12.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-ata']['indexes'][0] = array('descr' => 'Room High Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.13.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-bta']['indexes'][0] = array('descr' => 'Room Low Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.14.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-aua']['indexes'][0] = array('descr' => 'Room High Humidity', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.15.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-bua']['indexes'][0] = array('descr' => 'Room Low Humidity', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.16.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-eap']['indexes'][0] = array('descr' => 'External Room Temperature/Humidity', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.17.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-filter']['indexes'][0] = array('descr' => 'Clogged Filter', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.18.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-flood']['indexes'][0] = array('descr' => 'Water Leakage', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.19.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-flux']['indexes'][0] = array('descr' => 'Loss of Air Flow', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.20.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-heater']['indexes'][0] = array('descr' => 'Heater Overheating', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.21.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-hp1']['indexes'][0] = array('descr' => 'High Pressure 1', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.22.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-hp2']['indexes'][0] = array('descr' => 'High Pressure 2', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.23.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-lp1']['indexes'][0] = array('descr' => 'Low Pressure 1', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.24.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-lp2']['indexes'][0] = array('descr' => 'Low Pressure 2', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.25.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-phase']['indexes'][0] = array('descr' => 'Wrong Phase Sequence', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.26.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-smoke']['indexes'][0] = array('descr' => 'Smoke Detection', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.27.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-lan']['indexes'][0] = array('descr' => 'LAN Interrupted', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.28.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-hcurr']['indexes'][0] = array('descr' => 'Humidifier High Current', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.29.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-nopower']['indexes'][0] = array('descr' => 'Humidifier Power Loss ', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.30.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-nowater']['indexes'][0] = array('descr' => 'Humidifier Water Loss', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.31.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-cw-dh']['indexes'][0] = array('descr' => 'Chilled Water Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.32.0', 'type' => 'uncdz-mib-okfail');  // Chilled water temp too high for dehum
$config['mibs'][$mib]['status']['mal-tc-cw']['indexes'][0] = array('descr' => 'CW Valve or Water Flow Failure', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.33.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-wflow']['indexes'][0] = array('descr' => 'Loss of Water Flow', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.34.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-wht']['indexes'][0] = array('descr' => 'High Chilled Water Temperature', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.35.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-sonda-ta']['indexes'][0] = array('descr' => 'Room Air Sensor Failure', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.36.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-sonda-tac']['indexes'][0] = array('descr' => 'Hot Water Sensor Failure', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.37.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-sonda-tc']['indexes'][0] = array('descr' => 'Condensing Water Sensor Failure', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.38.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-sonda-te']['indexes'][0] = array('descr' => 'Outdoor Temperature Sensor Failure', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.39.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-sonda-tm']['indexes'][0] = array('descr' => 'Delivery Temperature Sensor Failure', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.40.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-sonda-ua']['indexes'][0] = array('descr' => 'Relative Humidity Sensor Failure', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.41.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-ore-compr1']['indexes'][0] = array('descr' => 'Compressor 1 Hour Counter Service Threshold', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.42.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-ore-compr2']['indexes'][0] = array('descr' => 'Compressor 2 Hour Counter Service Threshold', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.43.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-ore-compr3']['indexes'][0] = array('descr' => 'Compressor 3 Hour Counter Service Threshold', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.44.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-ore-compr4']['indexes'][0] = array('descr' => 'Compressor 4 Hour Counter Service Threshold', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.45.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-ore-filtro']['indexes'][0] = array('descr' => 'Air Filter Hour Counter Service Threshold', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.46.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-ore-risc1']['indexes'][0] = array('descr' => 'Heater 1 Hour Counter Service Threshold', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.47.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-ore-risc2']['indexes'][0] = array('descr' => 'Heater 2 Hour Counter Service Threshold', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.48.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-ore-umid']['indexes'][0] = array('descr' => 'Humidifier Hour Counter Service Threshold', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.49.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['mal-ore-unit']['indexes'][0] = array('descr' => 'Unit Hour Counter Service Threshold', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.50.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['glb-al']['indexes'][0] = array('descr' => 'Global Alarm', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.51.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['or-al-2lev']['indexes'][0] = array('descr' => 'Second Level Alarm', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.52.0', 'type' => 'uncdz-mib-okfail');
$config['mibs'][$mib]['status']['umid-al']['indexes'][0] = array('descr' => 'Humidifier General Alarm', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.57.0', 'type' => 'uncdz-mib-okfail');

$config['mibs'][$mib]['status']['emerg']['indexes'][0] = array('descr' => 'Unit in Emergency Operation', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.1.67.0', 'type' => 'uncdz-mib-okfail');

// FIXME currently no kg/h sensor.
// $config['mibs'][$mib]['sensor']['steam-production']['indexes'][0] = array('descr' => 'Humidifier Steam Capacity', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.2.22.0', 'scale' => 0.1);   // 400 kg/h x 10

// Hour counters
// $config['mibs'][$mib]['counter']['ore-filtro']['indexes'][0] = array('class' => 'counter', 'descr' => 'Air Filter Working Hours',   'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.1.0');
// $config['mibs'][$mib]['counter']['ore-unit']['indexes'][0]   = array('class' => 'counter', 'descr' => 'Unit Working Hours',         'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.2.0');
// $config['mibs'][$mib]['counter']['ore-compr1']['indexes'][0] = array('class' => 'counter', 'descr' => 'Compressor & Working Hours', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.3.0');
// $config['mibs'][$mib]['counter']['ore-compr2']['indexes'][0] = array('class' => 'counter', 'descr' => 'Compressor 2 Working Hours', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.4.0');
// $config['mibs'][$mib]['counter']['ore-compr3']['indexes'][0] = array('class' => 'counter', 'descr' => 'Compressor 3 Working Hours', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.5.0');
// $config['mibs'][$mib]['counter']['ore-compr4']['indexes'][0] = array('class' => 'counter', 'descr' => 'Compressor 4 Working Hours', 'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.6.0');
// $config['mibs'][$mib]['counter']['ore-heat1']['indexes'][0]  = array('class' => 'counter', 'descr' => 'Heater 1 Working Hours',     'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.7.0');
// $config['mibs'][$mib]['counter']['ore-heat2']['indexes'][0]  = array('class' => 'counter', 'descr' => 'Heater 2 Working Hours',     'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.8.0');
// $config['mibs'][$mib]['counter']['ore-umid']['indexes'][0]   = array('class' => 'counter', 'descr' => 'Humidifier Working Hours',   'oid_num' => '.1.3.6.1.4.1.9839.2.1.3.9.0');

$mib = 'CHECKPOINT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2620';
$config['mibs'][$mib]['mib_dir'] = 'checkpoint';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'svnApplianceSerialNumber.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'svnVersion.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'svnApplianceProductName.0');
$config['mibs'][$mib]['features'][] = array('oid' => 'haState.0');

$config['mibs'][$mib]['states']['checkpoint-ha-state'][0] = array('name' => 'OK', 'event' => 'ok');
$config['mibs'][$mib]['states']['checkpoint-ha-state'][1] = array('name' => 'WARNING', 'event' => 'warning');
$config['mibs'][$mib]['states']['checkpoint-ha-state'][2] = array('name' => 'CRITICAL', 'event' => 'alert');
$config['mibs'][$mib]['states']['checkpoint-ha-state'][3] = array('name' => 'UNKNOWN', 'event' => 'warning');

// CISCO-CONTENT-ENGINE-MIB

$mib = 'CISCO-CONTENT-ENGINE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.178';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-CAT6K-CROSSBAR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.217';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-CDP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.23';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-CONFIG-MAN-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.43';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-DOT11-ASSOCIATION-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.273';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-EIGRP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.449';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-ENHANCED-MEMPOOL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.221';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-ENHANCED-SLB-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.470';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-ENTITY-PFE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.265';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-ENTITY-QFP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.715';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-ENTITY-SENSOR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.91';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$type = 'cisco-entity-state';
$config['mibs'][$mib]['states'][$type] = $config['mibs']['SNMPv2-MIB']['states']['TruthValue'];

$mib = 'CISCO-ENTITY-VENDORTYPE-OID-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.12.3';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-ENVMON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.13';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['cisco-envmon-state'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-envmon-state'][2] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states']['cisco-envmon-state'][3] = array('name' => 'critical', 'event' => 'alert');
$config['mibs'][$mib]['states']['cisco-envmon-state'][4] = array('name' => 'shutdown', 'event' => 'ignore');
$config['mibs'][$mib]['states']['cisco-envmon-state'][5] = array('name' => 'notPresent', 'event' => 'exclude');
$config['mibs'][$mib]['states']['cisco-envmon-state'][6] = array('name' => 'notFunctioning', 'event' => 'ignore');

$mib = 'CISCO-ENTITY-FRU-CONTROL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.117';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$type = 'PowerOperType';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'offEnvOther', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'on', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'offAdmin', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'offDenied', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'offEnvPower', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'offEnvTemp', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'offEnvFan', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'onButFanFail', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'offCooling', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'offConnectorRating', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][12] = array('name' => 'onButInlinePowerFail', 'event' => 'warning');

$type = 'cefcFanTrayOperStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'up', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'down', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'warning', 'event' => 'warning');

// CISCO-FIREWALL-MIB

$mib = 'CISCO-FIREWALL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.147';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['cisco-firewall-hardware-primary-state'][1] = array('name' => 'other', 'event' => 'warning');
$config['mibs'][$mib]['states']['cisco-firewall-hardware-primary-state'][2] = array('name' => 'up', 'event' => 'warning');
$config['mibs'][$mib]['states']['cisco-firewall-hardware-primary-state'][3] = array('name' => 'down', 'event' => 'warning');
$config['mibs'][$mib]['states']['cisco-firewall-hardware-primary-state'][4] = array('name' => 'error', 'event' => 'alert');
$config['mibs'][$mib]['states']['cisco-firewall-hardware-primary-state'][5] = array('name' => 'overTemp', 'event' => 'warning');
$config['mibs'][$mib]['states']['cisco-firewall-hardware-primary-state'][6] = array('name' => 'busy', 'event' => 'warning');
$config['mibs'][$mib]['states']['cisco-firewall-hardware-primary-state'][7] = array('name' => 'noMedia', 'event' => 'warning');
$config['mibs'][$mib]['states']['cisco-firewall-hardware-primary-state'][8] = array('name' => 'backup', 'event' => 'warning');
$config['mibs'][$mib]['states']['cisco-firewall-hardware-primary-state'][9] = array('name' => 'active', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-firewall-hardware-primary-state'][10] = array('name' => 'standby', 'event' => 'warning');

$config['mibs'][$mib]['states']['cisco-firewall-hardware-secondary-state'] = $config['mibs'][$mib]['states']['cisco-firewall-hardware-primary-state'];
$config['mibs'][$mib]['states']['cisco-firewall-hardware-secondary-state'][9] = array('name' => 'active', 'event' => 'warning');
$config['mibs'][$mib]['states']['cisco-firewall-hardware-secondary-state'][10] = array('name' => 'standby', 'event' => 'ok');

// CISCO-FLASH-MIB

$mib = 'CISCO-FLASH-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.10';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

// CISCO-IETF-PW-MIB

$mib = 'CISCO-IETF-PW-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.10.106';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['pseudowire']['oids'] = array(
  'Uptime' => array('oid' => 'cpwVcUpTime', 'oid_num' => '.1.3.6.1.4.1.9.10.106.1.2.1.24', 'type' => 'timeticks'),

  'OperStatus'   => array('oid' => 'cpwVcOperStatus', 'oid_num' => '.1.3.6.1.4.1.9.10.106.1.2.1.26'),
  'RemoteStatus' => array('oid' => 'cpwVcInboundOperStatus', 'oid_num' => '.1.3.6.1.4.1.9.10.106.1.2.1.27'),
  'LocalStatus'  => array('oid' => 'cpwVcOutboundOperStatus', 'oid_num' => '.1.3.6.1.4.1.9.10.106.1.2.1.28'),

  'InPkts'    => array('oid' => 'cpwVcPerfTotalInHCPackets', 'oid_num' => '.1.3.6.1.4.1.9.10.106.1.5.1.1'),
  'OutPkts'   => array('oid' => 'cpwVcPerfTotalOutHCPackets', 'oid_num' => '.1.3.6.1.4.1.9.10.106.1.5.1.3'),
  'InOctets'  => array('oid' => 'cpwVcPerfTotalInHCBytes', 'oid_num' => '.1.3.6.1.4.1.9.10.106.1.5.1.2'),
  'OutOctets' => array('oid' => 'cpwVcPerfTotalOutHCBytes', 'oid_num' => '.1.3.6.1.4.1.9.10.106.1.5.1.4'),
);

$config['mibs'][$mib]['pseudowire']['states'] = array(
  'up'             => array('num' => '1', 'event' => 'ok'),
  'down'           => array('num' => '2', 'event' => 'alert'),
  'testing'        => array('num' => '3', 'event' => 'ok'),
  'unknown'        => array('num' => '4', 'event' => 'ignore'),
  'dormant'        => array('num' => '5', 'event' => 'alert'),
  'notPresent'     => array('num' => '6', 'event' => 'exclude'),
  'lowerLayerDown' => array('num' => '7', 'event' => 'alert'),
);

// CISCO-IP-STAT-MIB

$mib = 'CISCO-IP-STAT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.84';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-IPSEC-FLOW-MONITOR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.171';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-LWAPP-SYS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.618';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-MEMORY-POOL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.48';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-POWER-ETHERNET-EXT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.402';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-PROCESS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.109';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-REMOTE-ACCESS-MONITOR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.392';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-SLB-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.161';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-STACK-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.5.1';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-STACKWISE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.500';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['cisco-stackwise-member-state'][1] = array('name' => 'master', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-stackwise-member-state'][2] = array('name' => 'member', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-stackwise-member-state'][3] = array('name' => 'notMember', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-stackwise-member-state'][4] = array('name' => 'standby', 'event' => 'ok');

$config['mibs'][$mib]['states']['cisco-stackwise-port-oper-state'][1] = array('name' => 'up', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-stackwise-port-oper-state'][2] = array('name' => 'down', 'event' => 'warning');
$config['mibs'][$mib]['states']['cisco-stackwise-port-oper-state'][3] = array('name' => 'forcedDown', 'event' => 'ok');

$config['mibs'][$mib]['states']['cisco-stackwise-switch-state'][1] = array('name' => 'waiting', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-stackwise-switch-state'][2] = array('name' => 'progressing', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-stackwise-switch-state'][3] = array('name' => 'added', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-stackwise-switch-state'][4] = array('name' => 'ready', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-stackwise-switch-state'][5] = array('name' => 'sdmMismatch', 'event' => 'alert');
$config['mibs'][$mib]['states']['cisco-stackwise-switch-state'][6] = array('name' => 'verMismatch', 'event' => 'alert');
$config['mibs'][$mib]['states']['cisco-stackwise-switch-state'][7] = array('name' => 'featureMismatch', 'event' => 'alert');
$config['mibs'][$mib]['states']['cisco-stackwise-switch-state'][8] = array('name' => 'newMasterInit', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-stackwise-switch-state'][9] = array('name' => 'provisioned', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-stackwise-switch-state'][10] = array('name' => 'invalid', 'event' => 'alert');
$config['mibs'][$mib]['states']['cisco-stackwise-switch-state'][11] = array('name' => 'removed', 'event' => 'warning');

$config['mibs'][$mib]['states']['cisco-stackwise-redundant-state'][1] = array('name' => 'true', 'event' => 'ok');
$config['mibs'][$mib]['states']['cisco-stackwise-redundant-state'][2] = array('name' => 'false', 'event' => 'warning');

$mib = 'CISCO-SUBSCRIBER-SESSION-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.786';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = 'This MIB defines objects describing subscriber sessions, or more specifically, subscriber sessions terminated by a RAS.';

$mib = 'CISCO-TRUSTSEC-INTERFACE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.740';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-UNIFIED-COMPUTING-COMPUTE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.719.1.9';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['hardware'][] = array('oid' => 'cucsComputeRackUnitName.1');
$config['mibs'][$mib]['serial'][] = array('oid' => 'cucsComputeBoardSerial.1');

$config['mibs'][$mib]['sensor']['cucsComputeMbPowerStatsConsumedPower']['indexes'][1] = array(
  'class'            => 'power',
  'descr'            => 'System Board Input',
  'oid_num'          => '.1.3.6.1.4.1.9.9.719.1.9.14.1.4.1',
  'rename_rrd_array' => array('index' => 'mbinputpower', 'type' => 'cimc'), // old sensor discovery params
  'min'              => 0,
  'scale'            => 1
);
$config['mibs'][$mib]['sensor']['cucsComputeMbPowerStatsInputCurrent']['indexes'][1] = array(
  'class'            => 'current',
  'descr'            => 'System Board Input',
  'oid_num'          => '.1.3.6.1.4.1.9.9.719.1.9.14.1.8.1',
  'rename_rrd_array' => array('index' => 'mbinputcurrent', 'type' => 'cimc'), // old sensor discovery params
  'min'              => 0,
  'scale'            => 1
);
$config['mibs'][$mib]['sensor']['cucsComputeMbPowerStatsInputVoltage']['indexes'][1] = array(
  'class'            => 'voltage',
  'descr'            => 'System Board Input',
  'oid_num'          => '.1.3.6.1.4.1.9.9.719.1.9.14.1.12.1',
  'rename_rrd_array' => array('index' => 'mbinputvoltage', 'type' => 'cimc'), // old sensor discovery params
  'min'              => 0,
  'scale'            => 1
);

$config['mibs'][$mib]['sensor']['cucsComputeRackUnitMbTempStatsAmbientTemp']['indexes'][1] = array(
  'class'            => 'temperature',
  'descr'            => 'Ambient Temperature',
  'oid_num'          => '.1.3.6.1.4.1.9.9.719.1.9.44.1.4.1',
  'rename_rrd_array' => array('index' => 'ambienttemp', 'type' => 'cimc'), // old sensor discovery params
  'min'              => 0,
  'scale'            => 1
);
$config['mibs'][$mib]['sensor']['cucsComputeRackUnitMbTempStatsFrontTemp']['indexes'][1] = array(
  'class'            => 'temperature',
  'descr'            => 'Front Temperature',
  'oid_num'          => '.1.3.6.1.4.1.9.9.719.1.9.44.1.8.1',
  'rename_rrd_array' => array('index' => 'fronttemp', 'type' => 'cimc'), // old sensor discovery params
  'min'              => 0,
  'scale'            => 1
);
$config['mibs'][$mib]['sensor']['cucsComputeRackUnitMbTempStatsIoh1Temp']['indexes'][1] = array(
  'class'            => 'temperature',
  'descr'            => 'IO Hub Temperature',
  'oid_num'          => '.1.3.6.1.4.1.9.9.719.1.9.44.1.13.1',
  'rename_rrd_array' => array('index' => 'iohubtemp', 'type' => 'cimc'), // old sensor discovery params
  'min'              => 0,
  'scale'            => 1
);
$config['mibs'][$mib]['sensor']['cucsComputeRackUnitMbTempStatsIoh2Temp']['indexes'][1] = array(
  'class'   => 'temperature',
  'descr'   => 'IO Hub 2 Temperature',
  'oid_num' => '.1.3.6.1.4.1.9.9.719.1.9.44.1.17.1',
  'min'     => 0,
  'scale'   => 1
);
$config['mibs'][$mib]['sensor']['cucsComputeRackUnitMbTempStatsRearTemp']['indexes'][1] = array(
  'class'            => 'temperature',
  'descr'            => 'Rear Temperature',
  'oid_num'          => '.1.3.6.1.4.1.9.9.719.1.9.44.1.21.1',
  'rename_rrd_array' => array('index' => 'reartemp', 'type' => 'cimc'), // old sensor discovery params
  'min'              => 0,
  'scale'            => 1
);

$config['mibs'][$mib]['status']['cucsComputeRackUnitOperState']['indexes'][1] = array(
  'type'     => 'CucsLsOperState',
  'descr'    => 'Rack Unit',
  //'oid'                   => 'cucsComputeRackUnitOperState.1',
  'oid_num'  => '.1.3.6.1.4.1.9.9.719.1.9.35.1.42.1',
  'measured' => 'device'
);

// This states defined in CISCO-UNIFIED-COMPUTING-TC-MIB, no more descriptions in mib, I'm not sure about events. --mike
$type = 'CucsLsOperState';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'indeterminate', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'unassociated', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'discovery', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][12] = array('name' => 'config', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][13] = array('name' => 'unconfig', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][14] = array('name' => 'powerOff', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][15] = array('name' => 'restart', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][20] = array('name' => 'maintenance', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][21] = array('name' => 'test', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][29] = array('name' => 'computeMismatch', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][30] = array('name' => 'computeFailed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][31] = array('name' => 'degraded', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][32] = array('name' => 'discoveryFailed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][33] = array('name' => 'configFailure', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][34] = array('name' => 'unconfigFailed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][35] = array('name' => 'testFailed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][36] = array('name' => 'maintenanceFailed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][40] = array('name' => 'removed', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][41] = array('name' => 'disabled', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][50] = array('name' => 'inaccessible', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][60] = array('name' => 'thermalProblem', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][61] = array('name' => 'powerProblem', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][62] = array('name' => 'voltageProblem', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][63] = array('name' => 'inoperable', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][101] = array('name' => 'decomissioning', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][201] = array('name' => 'biosRestore', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][202] = array('name' => 'cmosReset', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][203] = array('name' => 'diagnostics', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][204] = array('name' => 'diagnosticsFailed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][210] = array('name' => 'pendingReboot', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][211] = array('name' => 'pendingReassociation', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][212] = array('name' => 'svnicNotPresent', 'event' => 'warning');

$mib = 'CISCO-UNIFIED-COMPUTING-EQUIPMENT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.719.1.15';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['status']['cucsEquipmentFanTable']['tables'][] = array(
  'table'      => 'cucsEquipmentFanTable',
  'table_walk' => FALSE, // too big table, walk by OIDs
  'type'       => 'CucsEquipmentOperability',
  'descr'      => 'Fan',
  'oid'        => 'cucsEquipmentFanOperState',
  'oid_num'    => '.1.3.6.1.4.1.9.9.719.1.15.12.1.9',
  'measured'   => 'fan'
);

$config['mibs'][$mib]['status']['cucsEquipmentPsuTable']['tables'][] = array(
  'table'    => 'cucsEquipmentPsuTable',
  'type'     => 'CucsEquipmentOperability',
  'descr'    => 'Power Supply',
  'oid'      => 'cucsEquipmentPsuOperState',
  'oid_num'  => '.1.3.6.1.4.1.9.9.719.1.15.56.1.7',
  'measured' => 'powersupply'
);

$type = 'CucsEquipmentOperability';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'operable', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'inoperable', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'degraded', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'poweredOff', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'powerProblem', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'removed', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'voltageProblem', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'thermalProblem', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'performanceProblem', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'accessibilityProblem', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'identityUnestablishable', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][12] = array('name' => 'biosPostTimeout', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][13] = array('name' => 'disabled', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][14] = array('name' => 'malformedFru', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][51] = array('name' => 'fabricConnProblem', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][52] = array('name' => 'fabricUnsupportedConn', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][81] = array('name' => 'config', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][82] = array('name' => 'equipmentProblem', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][83] = array('name' => 'decomissioning', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][84] = array('name' => 'chassisLimitExceeded', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][100] = array('name' => 'notSupported', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][101] = array('name' => 'discovery', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][102] = array('name' => 'discoveryFailed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][103] = array('name' => 'identify', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][104] = array('name' => 'postFailure', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][105] = array('name' => 'upgradeProblem', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][106] = array('name' => 'peerCommProblem', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][107] = array('name' => 'autoUpgrade', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][108] = array('name' => 'linkActivateBlocked', 'event' => 'warning');

$config['mibs'][$mib]['status']['cucsEquipmentPsuTable']['tables'][] = array(
  'table'    => 'cucsEquipmentPsuTable',
  'type'     => 'CucsEquipmentPowerState',
  'descr'    => 'Power Supply %index% State',
  'oid'      => 'cucsEquipmentPsuPower',
  'oid_num'  => '.1.3.6.1.4.1.9.9.719.1.15.56.1.10',
  'measured' => 'powersupply'
);

$type = 'CucsEquipmentPowerState';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'on', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'test', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'off', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'online', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'offline', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'offduty', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'degraded', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'powerSave', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'error', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][100] = array('name' => 'notSupported', 'event' => 'exclude');

$mib = 'CISCO-UNIFIED-COMPUTING-PROCESSOR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.719.1.41';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['sensor']['cucsProcessorEnvStatsTable']['tables'][] = array(
  'table'            => 'cucsProcessorEnvStatsTable',
  'table_walk'       => FALSE, // too big table, walk by OIDs
  'class'            => 'temperature',
  'descr'            => 'CPU', // Fallback if oid_descr empty
  'oid'              => 'cucsProcessorEnvStatsTemperature',
  'oid_descr'        => 'cucsProcessorUnitSocketDesignation',
  'oid_num'          => '.1.3.6.1.4.1.9.9.719.1.41.2.1.10',
  'rename_rrd_array' => array('index' => 'cpu%index%', 'descr' => 'CPU %index% Temperature', 'type' => 'cimc'), // old sensor discovery params
  'min'              => 0,
  'scale'            => 1
);

$config['mibs'][$mib]['status']['cucsProcessorUnitTable']['tables'][] = array(
  'table'      => 'cucsProcessorUnitTable',
  'table_walk' => FALSE, // too big table, walk by OIDs
  'type'       => 'CucsEquipmentSensorThresholdStatus',
  'descr'      => 'CPU %index% Performance',
  'oid'        => 'cucsProcessorUnitPerf',
  'oid_num'    => '.1.3.6.1.4.1.9.9.719.1.41.9.1.11',
  'measured'   => 'processor'
);

$type = 'CucsEquipmentSensorThresholdStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'upperNonRecoverable', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'upperCritical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'upperNonCritical', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'lowerNonCritical', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'lowerCritical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'lowerNonRecoverable', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][100] = array('name' => 'notSupported', 'event' => 'exclude');

$mib = 'CISCO-UNIFIED-COMPUTING-MEMORY-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.719.1.30';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['sensor']['cucsMemoryUnitEnvStatsTable']['tables'][] = array(
  'table'      => 'cucsMemoryUnitEnvStatsTable',
  'table_walk' => FALSE, // too big table, walk by OIDs
  'class'      => 'temperature',
  'descr'      => 'Memory', // Fallback if oid_descr empty
  'oid'        => 'cucsMemoryUnitEnvStatsTemperature',
  //'oid_descr'             => 'cucsMemoryUnitEnvStatsDn',
  'oid_num'    => '.1.3.6.1.4.1.9.9.719.1.30.12.1.6',
  'min'        => 0,
  'scale'      => 1
);

$mib = 'CISCO-UNIFIED-COMPUTING-STORAGE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.719.1.45';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['status']['cucsStorageFlexFlashCardTable']['tables'][] = array(
  'table'    => 'cucsStorageFlexFlashCardTable',
  'type'     => 'CucsStorageFFCardHealth',
  'descr'    => 'Flash Card %index% Health',
  'oid'      => 'cucsStorageFlexFlashCardCardHealth',
  'oid_num'  => '.1.3.6.1.4.1.9.9.719.1.45.34.1.5',
  'measured' => 'storage'
);

$type = 'CucsStorageFFCardHealth';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'ffPhyHealthNa', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'ffPhyHealthOk', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'ffPhyUnhealthyRaid', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'ffPhyUnhealthyOther', 'event' => 'alert');

$config['mibs'][$mib]['status']['cucsStorageFlexFlashCardTable']['tables'][] = array(
  'table'    => 'cucsStorageFlexFlashCardTable',
  'type'     => 'CucsStorageFFCardMode',
  'descr'    => 'Flash Card %index% Mode',
  'oid'      => 'cucsStorageFlexFlashCardCardMode',
  'oid_num'  => '.1.3.6.1.4.1.9.9.719.1.45.34.1.6',
  'measured' => 'storage'
);

$type = 'CucsStorageFFCardMode';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'ffPhyDriveUnpairedPrimary', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'ffPhyDrivePrimary', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'ffPhyDriveSecondaryAct', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'ffPhyDriveSecondaryUnhealthy', 'event' => 'alert');

$config['mibs'][$mib]['status']['cucsStorageFlexFlashControllerTable']['tables'][] = array(
  'table'      => 'cucsStorageFlexFlashControllerTable',
  'table_walk' => FALSE, // too big table, walk by OIDs
  'type'       => 'CucsStorageFFControllerHealth',
  'descr'      => 'Flash Controller %index% Health',
  'oid'        => 'cucsStorageFlexFlashControllerControllerHealth',
  'oid_num'    => '.1.3.6.1.4.1.9.9.719.1.45.35.1.4',
  'measured'   => 'storage'
);

$type = 'CucsStorageFFControllerHealth';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'ffchOk', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'ffchMetadataFailure', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'ffchErrorCardsAccessError', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'ffchErrorOldFirmwareRunning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'ffchErrorMediaWriteProtected', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'ffchErrorInvalidSize', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'ffchErrorCardSizeMismatch', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'ffchInconsistentMetadata', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'ffchErrorSecondaryUnhealthyCard', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'ffchErrorSdCardNotConfigured', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'ffchErrorInconsistantMetadataIgnored', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'ffchErrorSd253WithUnOrSd247', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][12] = array('name' => 'ffchErrorRebootedDuringRebuild', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][13] = array('name' => 'ffchErrorSd247CardDetected', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][14] = array('name' => 'ffchFlexdErrorSdCardOpModeMismatch', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][15] = array('name' => 'ffchFlexdErrorSdOpModeMismatchWithUn', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][16] = array('name' => 'ffchFlexdErrorImSdUnhealthySdUnIgnored', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][17] = array('name' => 'ffchFlexdErrorImSdHealthySdUnIgnored', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][18] = array('name' => 'ffchFlexdErrorImSdCardsOpModeMismatch', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][19] = array('name' => 'ffchFlexdErrorSdCard0UnhealthyOpModeMismatch', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][20] = array('name' => 'ffchFlexdErrorSdCard0HealthyOpModeMismatch', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][21] = array('name' => 'ffchFlexdErrorSdCard1UnhealthyOpModeMismatch', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][22] = array('name' => 'ffchFlexdErrorSdCard1HealthyOpModeMismatch', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][23] = array('name' => 'ffchFlexdErrorImSd0IgnoredSd1', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][24] = array('name' => 'ffchFlexdErrorImSd0Sd1Ignored', 'event' => 'alert');

$config['mibs'][$mib]['status']['cucsStorageFlexFlashDriveTable']['tables'][] = array(
  'table'      => 'cucsStorageFlexFlashDriveTable',
  'table_walk' => FALSE, // too big table, walk by OIDs
  'type'       => 'CucsStorageOperationStateType',
  'descr'      => 'Flash Partition %index% State',
  'oid'        => 'cucsStorageFlexFlashDriveOperationState',
  'oid_num'    => '.1.3.6.1.4.1.9.9.719.1.45.36.1.25',
  'measured'   => 'storage'
);

$type = 'CucsStorageOperationStateType';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'partitionNonMirrored', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'partitionMirrored', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'partitionMirroredSyncing', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'partitionMirroredErasing', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'partitionMirroredUpdating', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'partitionNonMirroredUpdating', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'partitionNonMirroredErasing', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'partitionMirroredSyncingFail', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'partitionMirroredErasingFail', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'partitionMirroredUpdatingFail', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'partitionNonMirroredUpdatingFail', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'partitionNonMirroredErasingFail', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][12] = array('name' => 'partitionMirroredSyncingSuccess', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][13] = array('name' => 'partitionMirroredErasingSuccess', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][14] = array('name' => 'partitionMirroredUpdatingSuccess', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][15] = array('name' => 'partitionNonMirroredUpdatingSuccess', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][16] = array('name' => 'partitionNonMirroredErasingSuccess', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][17] = array('name' => 'unknown', 'event' => 'exclude');

$config['mibs'][$mib]['status']['cucsStorageControllerTable']['tables'][] = array(
  'table'      => 'cucsStorageControllerTable',
  'table_walk' => FALSE, // too big table, walk by OIDs
  'type'       => 'CucsStorageOperability',         // really CucsEquipmentOperability, FIXME need ability for allow same type for different MIBs
  'descr'      => 'Storage Controller %index%',
  'oid_descr'  => 'cucsStorageControllerModel',
  'oid'        => 'cucsStorageControllerOperState',
  'oid_num'    => '.1.3.6.1.4.1.9.9.719.1.45.1.1.6',
  'measured'   => 'storage'
);

$type = 'CucsStorageOperability';
$config['mibs'][$mib]['states'][$type] = $config['mibs']['CISCO-UNIFIED-COMPUTING-EQUIPMENT-MIB']['states']['CucsEquipmentOperability'];

$config['mibs'][$mib]['status']['cucsStorageLocalDiskTable']['tables'][] = array(
  'table'    => 'cucsStorageLocalDiskTable',
  'type'     => 'CucsStorageOperability',
  'descr'    => 'Disk %index%',
  'oid'      => 'cucsStorageLocalDiskOperability',
  'oid_num'  => '.1.3.6.1.4.1.9.9.719.1.45.4.1.9',
  'measured' => 'storage'
);

$config['mibs'][$mib]['status']['cucsStorageLocalDiskTable']['tables'][] = array(
  'table'    => 'cucsStorageLocalDiskTable',
  'type'     => 'CucsStoragePDriveStatus',
  'descr'    => 'Disk %index% State',
  'oid'      => 'cucsStorageLocalDiskDiskState',
  'oid_num'  => '.1.3.6.1.4.1.9.9.719.1.45.4.1.18',
  'measured' => 'storage'
);

$type = 'CucsStoragePDriveStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'online', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'unconfiguredGood', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'globalHotSpare', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'dedicatedHotSpare', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'jbod', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'offline', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'rebuilding', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'copyback', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'unconfiguredBad', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'predictiveFailure', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][12] = array('name' => 'disabledForRemoval', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][13] = array('name' => 'foreignConfiguration', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][14] = array('name' => 'zeroing', 'event' => 'warning');

$config['mibs'][$mib]['status']['cucsStorageLocalLunTable']['tables'][] = array(
  'table'    => 'cucsStorageLocalLunTable',
  'type'     => 'CucsStorageOperability',         // really CucsEquipmentOperability, FIXME need ability for allow same type for different MIBs
  'descr'    => 'Storage Lun %index%',
  'oid'      => 'cucsStorageLocalLunOperability',
  'oid_num'  => '.1.3.6.1.4.1.9.9.719.1.45.8.1.9',
  'measured' => 'storage'
);

$config['mibs'][$mib]['status']['cucsStorageLocalLunTable']['tables'][] = array(
  'table'    => 'cucsStorageLocalLunTable',
  'type'     => 'CucsStorageLunType',
  'descr'    => 'Storage Lun %index% Type',
  'oid'      => 'cucsStorageLocalLunType',
  'oid_num'  => '.1.3.6.1.4.1.9.9.719.1.45.8.1.14',
  'measured' => 'storage'
);

$type = 'CucsStorageLunType';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'unspecified', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'simple', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'mirror', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'stripe', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'raid', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'stripeParity', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'stripeDualParity', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'mirrorStripe', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'stripeParityStripe', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'stripeDualParityStripe', 'event' => 'ok');

$mib = 'CISCO-VPDN-MGMT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.10.24';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCO-VTP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.9.46';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCOSB-rndMng';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.89.1';
$config['mibs'][$mib]['mib_dir'] = 'ciscosb';
$config['mibs'][$mib]['descr'] = '';

// CISCOSB-Physicaldescription-MIB

$mib = 'CISCOSB-Physicaldescription-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'ciscosb';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'rlPhdUnitGenParamSerialNum.1'); // CISCOSB-Physicaldescription-MIB::rlPhdUnitGenParamSerialNum.1 = STRING: PSZ165003M9
$config['mibs'][$mib]['asset_tag'][] = array('oid' => 'rlPhdUnitGenParamAssetTag.1'); // CISCOSB-Physicaldescription-MIB::rlPhdUnitGenParamAssetTag.1 = STRING:

$mib = 'CISCOSB-IPv6';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.6.1.101.129'; // CISCOSB-IPv6::rlIPv6
$config['mibs'][$mib]['mib_dir'] = 'ciscosb';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCOSB-POE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.6.1.101.108'; // CISCOSB-POE-MIB::rlPoe
$config['mibs'][$mib]['mib_dir'] = 'ciscosb';
$config['mibs'][$mib]['descr'] = '';

$mib = 'CISCOSB-PHY-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9.6.1.101.90';  // CISCOSB-PHY-MIB::rlPhy
$config['mibs'][$mib]['mib_dir'] = 'ciscosb';
$config['mibs'][$mib]['descr'] = '';

$mib = 'COMPELLENT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.16139.1';
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'productIDVersion.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'scEnclModel.1', 'transformations' => array(array('action' => 'prepend', 'string' => 'Compellent '), array('action' => 'trim', 'characters' => 'EN-')));

$config['mibs'][$mib]['states']['compellent-mib-state'][1] = array('name' => 'up', 'event' => 'ok');
$config['mibs'][$mib]['states']['compellent-mib-state'][2] = array('name' => 'down', 'event' => 'alert');
$config['mibs'][$mib]['states']['compellent-mib-state'][3] = array('name' => 'degraded', 'event' => 'warning');

$config['mibs'][$mib]['states']['compellent-mib-cache-state'][0] = array('name' => 'noBattery', 'event' => 'alert');
$config['mibs'][$mib]['states']['compellent-mib-cache-state'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['compellent-mib-cache-state'][2] = array('name' => 'expirationPending', 'event' => 'warning');
$config['mibs'][$mib]['states']['compellent-mib-cache-state'][3] = array('name' => 'expired', 'event' => 'warning');

// CLAVISTER-MIB

$mib = 'CLAVISTER-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'clavister';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['processor']['clvSysCpuLoad'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'clvSysCpuLoad.0', 'oid_num' => '.1.3.6.1.4.1.3224.16.1.3.0');
$config['mibs'][$mib]['mempool']['clvSysMemUsage'] = array('type' => 'static', 'descr' => 'Memory', 'scale' => 1, 'oid_perc' => 'clvSysMemUsage.0', 'oid_used_num' => '.1.3.6.1.4.1.3224.16.2.1.0'); // CLAVISTER-MIB::clvSysMemUsage.0 = Gauge32: 53


// CPQSINFO-MIB

$mib = 'CPQSINFO-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'cpqSiSysSerialNum.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'cpqSiProductName.0', 'transformations' => array(array('action' => 'prepend', 'string' => 'HP ')));
$config['mibs'][$mib]['asset_tag'][] = array('oid' => 'cpqSiAssetTag.0');

$mib = 'CPQHLTH-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['cpqhlth-state'][1] = array('name' => 'other', 'event' => 'exclude');
$config['mibs'][$mib]['states']['cpqhlth-state'][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqhlth-state'][3] = array('name' => 'degraded', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqhlth-state'][4] = array('name' => 'failed', 'event' => 'alert');

$type = 'cpqHeResMem2ModuleStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'other', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'notPresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'present', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'good', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'add', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'upgrade', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'missing', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'doesNotMatch', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'notSupported', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'badConfig', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'degraded', 'event' => 'alert');

$type = 'cpqHeResMem2ModuleCondition';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'other', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'degraded', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'degradedModuleIndexUnknown', 'event' => 'alert');

$mib = 'CPQIDA-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';


$config['mibs'][$mib]['states']['cpqDaCntlrBoardStatus'][1] = array('name' => 'other', 'event' => 'exclude');
$config['mibs'][$mib]['states']['cpqDaCntlrBoardStatus'][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqDaCntlrBoardStatus'][3] = array('name' => 'generalFailure', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqDaCntlrBoardStatus'][4] = array('name' => 'cableProblem', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqDaCntlrBoardStatus'][5] = array('name' => 'poweredOff', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqDaCntlrBoardStatus'][5] = array('name' => 'cacheModuleMissing', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqida-cntrl-state'] = $config['mibs'][$mib]['states']['cpqDaCntlrBoardStatus'];

$config['mibs'][$mib]['states']['cpqDaCntlrCondition'][1] = array('name' => 'other', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqDaCntlrCondition'][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqDaCntlrCondition'][3] = array('name' => 'degraded', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqDaCntlrCondition'][4] = array('name' => 'failed', 'event' => 'alert');

$config['mibs'][$mib]['states']['cpqida-smart-state'][1] = array('name' => 'other', 'event' => 'exclude');
$config['mibs'][$mib]['states']['cpqida-smart-state'][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqida-smart-state'][3] = array('name' => 'replaceDrive', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqida-smart-state'][4] = array('name' => 'replaceDriveSSDWearOut', 'event' => 'warning');

$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][1] = array('name' => 'other', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][3] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][4] = array('name' => 'unconfigured', 'event' => 'ignore');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][5] = array('name' => 'recovering', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][6] = array('name' => 'readyForRebuild', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][7] = array('name' => 'rebuilding', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][8] = array('name' => 'wrongDrive', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][9] = array('name' => 'badConnect', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][10] = array('name' => 'overheating', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][11] = array('name' => 'shutdown', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][12] = array('name' => 'expanding', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][13] = array('name' => 'notAvailable', 'event' => 'ignore');
$config['mibs'][$mib]['states']['cpqDaLogDrvStatus'][14] = array('name' => 'queuedForExpansion', 'event' => 'ok');

$config['mibs'][$mib]['states']['cpqDaLogDrvCondition'][1] = array('name' => 'other', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqDaLogDrvCondition'][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqDaLogDrvCondition'][3] = array('name' => 'degraded', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqDaLogDrvCondition'][4] = array('name' => 'failed', 'event' => 'alert');

$config['mibs'][$mib]['states']['cpqDaPhyDrvStatus'][1] = array('name' => 'other', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqDaPhyDrvStatus'][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqDaPhyDrvStatus'][3] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqDaPhyDrvStatus'][4] = array('name' => 'predictiveFailure', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqDaPhyDrvStatus'][5] = array('name' => 'erasing', 'event' => 'ignore');
$config['mibs'][$mib]['states']['cpqDaPhyDrvStatus'][6] = array('name' => 'erasingDone', 'event' => 'ignore');
$config['mibs'][$mib]['states']['cpqDaPhyDrvStatus'][7] = array('name' => 'eraseQueued', 'event' => 'ignore');
$config['mibs'][$mib]['states']['cpqDaPhyDrvStatus'][8] = array('name' => 'ssdWearOut', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqDaPhyDrvStatus'][9] = array('name' => 'notAuthenticated', 'event' => 'warning');

$config['mibs'][$mib]['states']['cpqDaPhyDrvCondition'] = $config['mibs'][$mib]['states']['cpqDaLogDrvCondition'];

// CPQPOWER-MIB

$mib = 'CPQPOWER-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'deviceSerialNumber.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'upsIdentSoftwareVersions.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'upsIdentModel.0'); // FIXME prepend upsIdentManufacturer.0

$config['mibs'][$mib]['states']['cpqpower-pdu-status'][1] = array('name' => 'other', 'event' => 'exclude');
$config['mibs'][$mib]['states']['cpqpower-pdu-status'][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqpower-pdu-status'][3] = array('name' => 'degraded', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqpower-pdu-status'][4] = array('name' => 'failed', 'event' => 'alert');

$config['mibs'][$mib]['states']['cpqpower-pdu-breaker-status'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqpower-pdu-breaker-status'][2] = array('name' => 'overloadWarning', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqpower-pdu-breaker-status'][3] = array('name' => 'overloadCritical', 'event' => 'alert');
$config['mibs'][$mib]['states']['cpqpower-pdu-breaker-status'][4] = array('name' => 'voltageRangeWarning', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqpower-pdu-breaker-status'][5] = array('name' => 'voltageRangeCritical', 'event' => 'alert');

$mib = 'CPQRACK-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'cpqRackCommonEnclosureFWRev.1');

$config['mibs'][$mib]['states']['cpqrack-mib-slot-state'][1] = array('name' => 'other', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqrack-mib-slot-state'][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['cpqrack-mib-slot-state'][3] = array('name' => 'degraded', 'event' => 'warning');
$config['mibs'][$mib]['states']['cpqrack-mib-slot-state'][4] = array('name' => 'failed', 'event' => 'alert');

$type = 'cpqRackCommonEnclosureCondition';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'other', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'degraded', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'failed', 'event' => 'alert');

$type = 'cpqRackPowerSupplyStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'noError', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'generalFailure', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'bistFailure', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'fanFailure', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'tempFailure', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'interlockOpen', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'epromFailed', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'vrefFailed', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'dacFailed', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'ramTestFailed', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'voltageChannelFailed', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][12] = array('name' => 'orringdiodeFailed', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][13] = array('name' => 'brownOut', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][14] = array('name' => 'giveupOnStartup', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][15] = array('name' => 'nvramInvalid', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][16] = array('name' => 'calibrationTableInvalid', 'event' => 'warning');

$type = 'cpqRackPowerSupplyInputLineStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'noError', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'lineOverVoltage', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'lineUnderVoltage', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'lineHit', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'brownOut', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'linePowerLoss', 'event' => 'alert');

$mib = 'CYAN-CEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.28533.5.30.50';
$config['mibs'][$mib]['mib_dir'] = 'cyan';
$config['mibs'][$mib]['descr'] = 'Cyan Common Equipment Module MIB';

$mib = 'CYAN-NODE-MIB';
$config['mibs'][$mib]['mib_dir'] = 'cyan';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.28533.5.30.10';
$config['mibs'][$mib]['descr'] = 'Cyan Node MIB';
$config['mibs'][$mib]['serial'][] = array('oid' => 'cyanNodeMfgSerialNumber.0'); // CYAN-NODE-MIB::cyanNodeMfgSerialNumber.0 = STRING: FX50141xxxxxx

/*
Other CYAN-NODE-MIB fields:

CYAN-NODE-MIB::cyanNodeAdminState.0 = INTEGER: adminunlocked(1)
CYAN-NODE-MIB::cyanNodeAssetTag.0 = STRING: Z33
CYAN-NODE-MIB::cyanNodeBaseMacAddress.0 = STRING: 00:1D:99:xx:xx:xx
CYAN-NODE-MIB::cyanNodeBay.0 = STRING:
CYAN-NODE-MIB::cyanNodeCity.0 = STRING:
CYAN-NODE-MIB::cyanNodeCountry.0 = STRING:
CYAN-NODE-MIB::cyanNodeCurrentTimeZone.0 = INTEGER: utc(0)
CYAN-NODE-MIB::cyanNodeDescription.0 = STRING: Z33
CYAN-NODE-MIB::cyanNodeDhcpOnConsolePort.0 = INTEGER: enabled(1)
CYAN-NODE-MIB::cyanNodeIdentifier.0 = STRING: ROOT
CYAN-NODE-MIB::cyanNodeLatitude.0 = INTEGER: 0
CYAN-NODE-MIB::cyanNodeLongitude.0 = INTEGER: 0
CYAN-NODE-MIB::cyanNodeMacBlockSize.0 = Gauge32: 4
CYAN-NODE-MIB::cyanNodeMfgCleiCode.0 = STRING:
CYAN-NODE-MIB::cyanNodeMfgEciCode.0 = STRING:
CYAN-NODE-MIB::cyanNodeMfgModuleId.0 = Gauge32: 0
CYAN-NODE-MIB::cyanNodeMfgPartNumber.0 = STRING: 910-0005-01-01
CYAN-NODE-MIB::cyanNodeMfgRevision.0 = STRING: 26
CYAN-NODE-MIB::cyanNodeMfgSerialNumber.0 = STRING: FX50141xxxxxx
CYAN-NODE-MIB::cyanNodeName.0 = STRING: Z33
CYAN-NODE-MIB::cyanNodeNationalization.0 = INTEGER: ansi(2)
CYAN-NODE-MIB::cyanNodeNodeId.0 = Gauge32: 701600xxx
CYAN-NODE-MIB::cyanNodeOidClass.0 = STRING: OID_CLASS_NODE
CYAN-NODE-MIB::cyanNodeOperState.0 = INTEGER: is(1)
CYAN-NODE-MIB::cyanNodeOperStateQual.0 = INTEGER: anr(7)
CYAN-NODE-MIB::cyanNodeOssLabel.0 = STRING: Z33
CYAN-NODE-MIB::cyanNodeOwner.0 = STRING:
CYAN-NODE-MIB::cyanNodePartNumber.0 = STRING: 910-0005-01
CYAN-NODE-MIB::cyanNodePostalCode.0 = STRING:
CYAN-NODE-MIB::cyanNodeRackUnits.0 = STRING:
CYAN-NODE-MIB::cyanNodeRegion.0 = STRING:
CYAN-NODE-MIB::cyanNodeRelayRack.0 = STRING:
CYAN-NODE-MIB::cyanNodeSecServState.0 = BITS: 00 20 00 20 04 flt(10) sgeo(26) 37
CYAN-NODE-MIB::cyanNodeStreet.0 = STRING:
CYAN-NODE-MIB::cyanNodeType.0 = INTEGER: cyanShelf8(10)
*/

$mib = 'CYAN-SHELF-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.28533.5.30.20';
$config['mibs'][$mib]['mib_dir'] = 'cyan';
$config['mibs'][$mib]['descr'] = 'Cyan Shelf MIB';

$mib = 'CYAN-GEPORT-MIB';
$config['mibs'][$mib]['mib_dir'] = 'cyan';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.28533.5.30.160';
$config['mibs'][$mib]['descr'] = 'Cyan Gigabit Ethernet MIB';

$mib = 'CYAN-TENGPORT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.28533.5.30.150';
$config['mibs'][$mib]['mib_dir'] = 'cyan';
$config['mibs'][$mib]['descr'] = 'Cyan Ten Gigabit Ethernet MIB';

$mib = 'CYAN-XCVR-MIB';
$config['mibs'][$mib]['mib_dir'] = 'cyan';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.28533.5.30.140';
$config['mibs'][$mib]['descr'] = 'Cyan Transciever MIB';

$mib = 'DATA-DOMAIN-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.19746';
$config['mibs'][$mib]['mib_dir'] = 'datadomain';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'systemSerialNumber.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'systemModelNumber.0');

$config['mibs'][$mib]['states']['data-domain-mib-disk-state'][1] = array('name' => 'Ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['data-domain-mib-disk-state'][2] = array('name' => 'Unknown', 'event' => 'warning');
$config['mibs'][$mib]['states']['data-domain-mib-disk-state'][3] = array('name' => 'Absent', 'event' => 'exclude');
$config['mibs'][$mib]['states']['data-domain-mib-disk-state'][4] = array('name' => 'Failed', 'event' => 'alert');

$config['mibs'][$mib]['states']['data-domain-mib-pwr-state'][0] = array('name' => 'Absent', 'event' => 'exclude');
$config['mibs'][$mib]['states']['data-domain-mib-pwr-state'][1] = array('name' => 'Ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['data-domain-mib-pwr-state'][2] = array('name' => 'Failed', 'event' => 'alert');
$config['mibs'][$mib]['states']['data-domain-mib-pwr-state'][3] = array('name' => 'Faulty', 'event' => 'alert');
$config['mibs'][$mib]['states']['data-domain-mib-pwr-state'][4] = array('name' => 'Acnone', 'event' => 'exclude');
$config['mibs'][$mib]['states']['data-domain-mib-pwr-state'][99] = array('name' => 'Unknown', 'event' => 'warning');

$config['mibs'][$mib]['states']['data-domain-mib-fan-state'][0] = array('name' => 'Notfound', 'event' => 'exclude');
$config['mibs'][$mib]['states']['data-domain-mib-fan-state'][1] = array('name' => 'Ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['data-domain-mib-fan-state'][2] = array('name' => 'Fail', 'event' => 'alert');

$mib = 'DEV-CFG-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.629.1.50';
$config['mibs'][$mib]['mib_dir'] = 'mrv';
$config['mibs'][$mib]['descr'] = '';

$type = 'nbsDevOperStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'none', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'active', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'notActive', 'event' => 'alert');

$type = 'nbsDevTemperatureMode';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'none', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'high', 'event' => 'alert');

$config['mibs'][$mib]['status']['nbsDevTemperatureMode']['indexes'][0] = array('descr' => 'System Temperature', 'measured' => 'device', 'type' => 'nbsDevTemperatureMode', 'oid_num' => '.1.3.6.1.4.1.629.1.50.11.1.6.0');

$mib = 'DEV-ID-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.629.1.50.16';
$config['mibs'][$mib]['mib_dir'] = 'mrv';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'nbDevIdHardwareSerialBoard.0'); // DEV-ID-MIB::nbDevIdHardwareSerialBoard.0 = STRING: 1517100668
$config['mibs'][$mib]['version'][] = array('oid' => 'nbDevIdSoftwareMasterOSVers.0', 'transformations' => array('action' => 'replace', 'from' => '_', 'to' => '.')); // DEV-ID-MIB::nbDevIdSoftwareMasterOSVers.0 = STRING: 2_1_7L
$config['mibs'][$mib]['hardware'][] = array('oid' => 'nbDevIdTypeName.0'); // DEV-ID-MIB::nbDevIdTypeName.0 = STRING: OptiSwitch 904

/*
DEV-ID-MIB::nbDevIdSysName.0 = STRING: OS904
DEV-ID-MIB::nbDevIdHardwareSerialUnit.0 = STRING: 1525100300
*/

$mib = 'DKSF-48-4-X-X-1';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25728.48';
$config['mibs'][$mib]['mib_dir'] = 'netping';
$config['mibs'][$mib]['descr'] = 'NetPing PWR';

$mib = 'DKSF-50-11-X-X-X';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25728.50';
$config['mibs'][$mib]['mib_dir'] = 'netping';
$config['mibs'][$mib]['descr'] = 'UniPing Server Solution MIB';

$mib = 'DKSF-60-4-X-X-X';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25728.50';
$config['mibs'][$mib]['mib_dir'] = 'netping';
$config['mibs'][$mib]['descr'] = 'UniPing v3 MIB';

$mib = 'DKSF-70-5-X-X-1';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25728.70';
$config['mibs'][$mib]['mib_dir'] = 'netping';
$config['mibs'][$mib]['descr'] = 'UniPing Server Solution v3/SMS MIB';

// Note, this statuses used in multiple MIBs: DKSF-60-*, DKSF-70-*
$config['mibs'][$mib]['states']['dskf-mib-hum-state'][0] = array('name' => 'error', 'event' => 'alert');
$config['mibs'][$mib]['states']['dskf-mib-hum-state'][1] = array('name' => 'ok', 'event' => 'ok');

$config['mibs'][$mib]['states']['dskf-mib-smoke-state'][0] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['dskf-mib-smoke-state'][1] = array('name' => 'alarm', 'event' => 'alert');
$config['mibs'][$mib]['states']['dskf-mib-smoke-state'][4] = array('name' => 'off', 'event' => 'exclude');
$config['mibs'][$mib]['states']['dskf-mib-smoke-state'][5] = array('name' => 'failed', 'event' => 'warning');

$config['mibs'][$mib]['states']['dskf-mib-loop-state'][0] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['dskf-mib-loop-state'][1] = array('name' => 'alert', 'event' => 'alert');
$config['mibs'][$mib]['states']['dskf-mib-loop-state'][2] = array('name' => 'cut', 'event' => 'warning');
$config['mibs'][$mib]['states']['dskf-mib-loop-state'][3] = array('name' => 'short', 'event' => 'warning');
$config['mibs'][$mib]['states']['dskf-mib-loop-state'][4] = array('name' => 'notPowered', 'event' => 'exclude');

$mib = 'DELL-NETWORKING-CHASSIS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6027.3.26';
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = '';

$type = 'dellNetStackUnitStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'unsupported', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'codeMismatch', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'configMismatch', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'unitDown', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'notPresent', 'event' => 'exclude');

$type = 'dellNetOperStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'up', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'down', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'absent', 'event' => 'exclude');

$mib = 'DELL-TL4000-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'libraryFwLevel.1');
$config['mibs'][$mib]['ra_url_http'][] = array('oid' => 'TL4000IdURL.0');

$config['mibs'][$mib]['states']['dell-tl4000-status-state'][1] = array('name' => 'other', 'event' => 'warning');
$config['mibs'][$mib]['states']['dell-tl4000-status-state'][2] = array('name' => 'unknown', 'event' => 'warning');
$config['mibs'][$mib]['states']['dell-tl4000-status-state'][3] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['dell-tl4000-status-state'][4] = array('name' => 'non-critical', 'event' => 'warning');
$config['mibs'][$mib]['states']['dell-tl4000-status-state'][5] = array('name' => 'critical', 'event' => 'alert');
$config['mibs'][$mib]['states']['dell-tl4000-status-state'][6] = array('name' => 'non-Recoverable', 'event' => 'alert');

$mib = 'Dell-POE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = ''; // Do not set identity, it same as in reference MARVELL-POE-MIB
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = '';

$mib = 'Dell-Vendor-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.674.10895.3000';
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'productIdentificationVersion.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'productIdentificationDisplayName.0', 'transformations' => array(array('action' => 'prepend', 'string' => 'Dell ')));
$config['mibs'][$mib]['features'][] = array('oid' => 'productIdentificationDescription.0');

$type = 'dell-vendor-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'critical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'shutdown', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'notPresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'notFunctioning', 'event' => 'exclude');

// FASTPATH family MIBs (some MIBs changed from original, for split same MIB names)

$mib = 'BROADCOM-POWER-ETHERNET-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4413.1.1.15';
$config['mibs'][$mib]['mib_dir'] = 'broadcom';
$config['mibs'][$mib]['descr'] = '';

$mib = 'FASTPATH-BOXSERVICES-PRIVATE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4413.1.1.43';
$config['mibs'][$mib]['mib_dir'] = 'broadcom';
$config['mibs'][$mib]['descr'] = '';

$type = 'boxServicesItemState';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'notpresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'operational', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'failed', 'event' => 'alert');

$type = 'boxServicesTempSensorState';
//$config['mibs'][$mib]['states'][$type][0] = array('name' => 'low',            'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'critical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'shutdown', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'notpresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'notoperational', 'event' => 'exclude');

$mib = 'FASTPATH-INVENTORY-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'broadcom';
$config['mibs'][$mib]['descr'] = '';

$mib = 'FASTPATH-ISDP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4413.1.1.39';
$config['mibs'][$mib]['mib_dir'] = 'broadcom';
$config['mibs'][$mib]['descr'] = '';

$mib = 'FASTPATH-SWITCHING-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4413.1.1.1';
$config['mibs'][$mib]['mib_dir'] = 'broadcom';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'agentInventorySerialNumber.0'); // FASTPATH-SWITCHING-MIB::agentInventorySerialNumber.0 = STRING: "QTFCRW3390510"
$config['mibs'][$mib]['version'][] = array('oid' => 'agentInventorySoftwareVersion.0'); // FASTPATH-SWITCHING-MIB::agentInventorySoftwareVersion.0 = STRING: "1.2.0.18"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'agentInventoryMachineType.0'); // FASTPATH-SWITCHING-MIB::agentInventoryMachineType.0 = STRING: "Quanta LB6M"
$config['mibs'][$mib]['features'][] = array('oid' => 'agentInventoryAdditionalPackages.0'); // FASTPATH-SWITCHING-MIB::agentInventoryAdditionalPackages.0 = STRING: "QOS"

/*
FASTPATH-SWITCHING-MIB::agentInventorySysDescription.0 = STRING: "Quanta LB6M, 1.2.0.18, Linux 2.6.21.7"
FASTPATH-SWITCHING-MIB::agentInventoryMachineModel.0 = STRING: "LB6M"
FASTPATH-SWITCHING-MIB::agentInventoryFRUNumber.0 = STRING: "1LB6BZZ0STJ"
FASTPATH-SWITCHING-MIB::agentInventoryMaintenanceLevel.0 = STRING: "A"
FASTPATH-SWITCHING-MIB::agentInventoryPartNumber.0 = STRING: "BCM56820"
FASTPATH-SWITCHING-MIB::agentInventoryManufacturer.0 = STRING: "0xbc00"
FASTPATH-SWITCHING-MIB::agentInventoryBurnedInMacAddress.0 = Hex-STRING: 08 9E 01 EA B7 27
FASTPATH-SWITCHING-MIB::agentInventoryOperatingSystem.0 = STRING: "Linux 2.6.21.7"
FASTPATH-SWITCHING-MIB::agentInventoryNetworkProcessingDevice.0 = STRING: "BCM56820_B0"

FASTPATH-SWITCHING-MIB::agentInventorySysDescription.0 = STRING: "FASTPATH Switching"
FASTPATH-SWITCHING-MIB::agentInventoryMachineModel.0 = STRING: "LB4M"
FASTPATH-SWITCHING-MIB::agentInventoryFRUNumber.0 = STRING: "1"
FASTPATH-SWITCHING-MIB::agentInventoryMaintenanceLevel.0 = STRING: "A"
FASTPATH-SWITCHING-MIB::agentInventoryPartNumber.0 = STRING: "BCM56514"
FASTPATH-SWITCHING-MIB::agentInventoryManufacturer.0 = STRING: "0xbc00"
FASTPATH-SWITCHING-MIB::agentInventoryBurnedInMacAddress.0 = Hex-STRING: C8 0A A9 9E 59 E9
FASTPATH-SWITCHING-MIB::agentInventoryOperatingSystem.0 = STRING: "VxWorks5.5.1"
FASTPATH-SWITCHING-MIB::agentInventoryNetworkProcessingDevice.0 = STRING: "BCM56514_A0"
*/

// agentSwitchCpuProcessTotalUtilization.0 = STRING: "5 Sec (6.99%),    1 Min (6.72%),   5 Min (9.06%)"
$config['mibs'][$mib]['processor']['agentSwitchCpuProcessTotalUtilization'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'agentSwitchCpuProcessTotalUtilization.0', 'oid_num' => '.1.3.6.1.4.1.4413.1.1.1.1.4.4.0');

// WARNING, OID tree same as in FASTPATH-*, but EdgeSwitch-* use new tree
// Do not use identity_num here, it detected in discovery os module
// YES, I use EdgeSwitch-* mibs as NEW version of FASTPATH, since no ways for get latest MIBs from broadcom (mike)
$mib = 'EdgeSwitch-BOXSERVICES-PRIVATE-MIB';
$config['mibs'][$mib]['enable'] = 1;
//$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4413.1.1.43';
$config['mibs'][$mib]['mib_dir'] = 'ubiquiti';
$config['mibs'][$mib]['descr'] = '';

$type = 'edgeswitch-boxServicesItemState';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'notpresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'operational', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'powering', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'nopower', 'event' => 'ignore');   // item is present but no AC is connected
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'notpowering', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'incompatible', 'event' => 'exclude'); // state is possible on boards capable of pluggable Power supplies

$type = 'edgeswitch-boxServicesTempSensorState';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'low', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'critical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'shutdown', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'notpresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'notoperational', 'event' => 'exclude');

// EdgeSwitch-POWER-ETHERNET-MIB

$mib = 'EdgeSwitch-POWER-ETHERNET-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4413.1.1.15';
$config['mibs'][$mib]['mib_dir'] = 'ubiquiti';
$config['mibs'][$mib]['descr'] = '';

$mib = 'EdgeSwitch-ISDP-MIB';
$config['mibs'][$mib]['enable'] = 1;
//$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4413.1.1.39';
$config['mibs'][$mib]['mib_dir'] = 'ubiquiti';
$config['mibs'][$mib]['descr'] = '';

$mib = 'EdgeSwitch-SWITCHING-MIB';
$config['mibs'][$mib]['enable'] = 1;
//$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4413.1.1.1';
$config['mibs'][$mib]['mib_dir'] = 'ubiquiti';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'agentInventorySerialNumber.0'); // EdgeSwitch-SWITCHING-MIB::agentInventorySerialNumber.0 = STRING: "0418d6f0f928"
$config['mibs'][$mib]['version'][] = array('oid' => 'agentInventorySoftwareVersion.0'); // EdgeSwitch-SWITCHING-MIB::agentInventorySoftwareVersion.0 = STRING: "1.1.2.4767216"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'agentInventoryMachineType.0'); // EdgeSwitch-SWITCHING-MIB::agentInventoryMachineType.0 = STRING: "USW-48P-500"
$config['mibs'][$mib]['features'][] = array('oid' => 'agentInventoryAdditionalPackages.0'); // EdgeSwitch-SWITCHING-MIB::agentInventoryAdditionalPackages.0 = STRING: " QOS"

/*
EdgeSwitch-SWITCHING-MIB::agentInventorySysDescription.0 = STRING: "EdgeSwitch 24-Port 250W, 1.1.2.4767216, Linux 3.6.5-f4a26ed5"
EdgeSwitch-SWITCHING-MIB::agentInventoryMachineModel.0 = STRING: "ES-24-250W"
EdgeSwitch-SWITCHING-MIB::agentInventoryMaintenanceLevel.0 = STRING: "A"
EdgeSwitch-SWITCHING-MIB::agentInventoryPartNumber.0 = STRING: "BCM53344"
EdgeSwitch-SWITCHING-MIB::agentInventoryManufacturer.0 = STRING: "0xbc00"
EdgeSwitch-SWITCHING-MIB::agentInventoryBurnedInMacAddress.0 = Hex-STRING: 44 D9 E7 05 24 D9
EdgeSwitch-SWITCHING-MIB::agentInventoryOperatingSystem.0 = STRING: "Linux 3.6.5-f4a26ed5"
EdgeSwitch-SWITCHING-MIB::agentInventoryNetworkProcessingDevice.0 = STRING: "BCM53344_A0"

EdgeSwitch-SWITCHING-MIB::agentInventorySysDescription.0 = STRING: "USW-48P-500, 3.3.5.3734, Linux 3.6.5"
EdgeSwitch-SWITCHING-MIB::agentInventoryMachineModel.0 = STRING: "US48P500"
EdgeSwitch-SWITCHING-MIB::agentInventoryMaintenanceLevel.0 = STRING: "A"
EdgeSwitch-SWITCHING-MIB::agentInventoryPartNumber.0 = STRING: "BCM53344"
EdgeSwitch-SWITCHING-MIB::agentInventoryManufacturer.0 = STRING: "0xbc00"
EdgeSwitch-SWITCHING-MIB::agentInventoryBurnedInMacAddress.0 = Hex-STRING: 04 18 D6 F0 F9 28
EdgeSwitch-SWITCHING-MIB::agentInventoryOperatingSystem.0 = STRING: "Linux 3.6.5"
EdgeSwitch-SWITCHING-MIB::agentInventoryNetworkProcessingDevice.0 = STRING: "BCM53344_A0"
*/

$config['mibs'][$mib]['mempool']['agentSwitchCpuProcessGroup'] = array('type'      => 'static', 'descr' => 'Memory', 'scale' => 1024,
                                                                       'oid_total' => 'agentSwitchCpuProcessMemAvailable.0', 'oid_total_num' => '.1.3.6.1.4.1.4413.1.1.1.1.4.2.0', // EdgeSwitch-SWITCHING-MIB::agentSwitchCpuProcessMemAvailable.0 = INTEGER: 256608 KBytes
                                                                       'oid_free'  => 'agentSwitchCpuProcessMemFree.0', 'oid_free_num' => '.1.3.6.1.4.1.4413.1.1.1.1.4.1.0', // EdgeSwitch-SWITCHING-MIB::agentSwitchCpuProcessMemFree.0 = INTEGER: 163812 KBytes
);

$mib = 'DNOS-BOXSERVICES-PRIVATE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.674.10895.5000.2.6132.1.1.43';
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['dnos-boxservices-state'][1] = array('name' => 'notpresent', 'event' => 'exclude');
$config['mibs'][$mib]['states']['dnos-boxservices-state'][2] = array('name' => 'operational', 'event' => 'ok');
$config['mibs'][$mib]['states']['dnos-boxservices-state'][3] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states']['dnos-boxservices-state'][4] = array('name' => 'powering', 'event' => 'exclude');
$config['mibs'][$mib]['states']['dnos-boxservices-state'][5] = array('name' => 'nopower', 'event' => 'alert');
$config['mibs'][$mib]['states']['dnos-boxservices-state'][6] = array('name' => 'notpowering', 'event' => 'alert');
$config['mibs'][$mib]['states']['dnos-boxservices-state'][7] = array('name' => 'incompatible', 'event' => 'exclude');

$config['mibs'][$mib]['states']['dnos-boxservices-temp-state'][0] = array('name' => 'low', 'event' => 'ok');
$config['mibs'][$mib]['states']['dnos-boxservices-temp-state'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['dnos-boxservices-temp-state'][2] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states']['dnos-boxservices-temp-state'][3] = array('name' => 'critical', 'event' => 'alert');
$config['mibs'][$mib]['states']['dnos-boxservices-temp-state'][4] = array('name' => 'shutdown', 'event' => 'ignore');
$config['mibs'][$mib]['states']['dnos-boxservices-temp-state'][5] = array('name' => 'notpresent', 'event' => 'exclude');
$config['mibs'][$mib]['states']['dnos-boxservices-temp-state'][6] = array('name' => 'notoperational', 'event' => 'exclude');

$mib = 'OLD-DNOS-BOXSERVICES-PRIVATE-MIB';
$config['mibs'][$mib]['enable'] = 1;
//$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.674.10895.5000.2.6132.1.1.43';
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = '';

// Copied from FASTPATH-
$config['mibs'][$mib]['states']['fastpath-boxservices-private-state'] = $config['mibs']['FASTPATH-BOXSERVICES-PRIVATE-MIB']['states']['boxServicesItemState'];
$config['mibs'][$mib]['states']['fastpath-boxservices-private-temp-state'] = $config['mibs']['FASTPATH-BOXSERVICES-PRIVATE-MIB']['states']['boxServicesTempSensorState'];

$mib = 'DNOS-ISDP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.674.10895.5000.2.6132.1.1.39';
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = '';

// DNOS-SWITCHING-MIB

$mib = 'DNOS-SWITCHING-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.674.10895.5000.2.6132.1.1.1';
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['mempool']['agentSwitchCpuProcessGroup'] = array('type'       => 'static', 'descr' => 'System Memory', 'scale' => 1024,
                                                                       'oid_total'  => 'agentSwitchCpuProcessMemAvailable.0', 'oid_total_num' => '.1.3.6.1.4.1.674.10895.5000.2.6132.1.1.1.1.4.2.0', // DNOS-SWITCHING-MIB::agentSwitchCpuProcessMemAvailable.0 = INTEGER: 1034740
                                                                       'oid_free'   => 'agentSwitchCpuProcessMemFree.0', 'oid_free_num' => '.1.3.6.1.4.1.674.10895.5000.2.6132.1.1.1.1.4.1.0', // DNOS-SWITCHING-MIB::agentSwitchCpuProcessMemFree.0 = INTEGER: 343320
                                                                       'rename_rrd' => 'mempool-dell-vendor-mib-0', // CLEANME can go in r9000
);

$mib = 'DNOS-POWER-ETHERNET-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.674.10895.5000.2.6132.1.1.15';
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = '';

$mib = 'NETGEAR-POWER-ETHERNET-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4526.10.15';
$config['mibs'][$mib]['mib_dir'] = 'netgear';
$config['mibs'][$mib]['descr'] = '';

$mib = 'NETGEAR-BOXSERVICES-PRIVATE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4526.10.43';
$config['mibs'][$mib]['mib_dir'] = 'netgear';
$config['mibs'][$mib]['descr'] = '';

$mib = 'NETGEAR-ISDP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4526.10.39';
$config['mibs'][$mib]['mib_dir'] = 'netgear';
$config['mibs'][$mib]['descr'] = '';

$mib = 'NETGEAR-SWITCHING-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4526.10.1';
$config['mibs'][$mib]['mib_dir'] = 'netgear';
$config['mibs'][$mib]['descr'] = '';

// END FASTPATH FAMILY

$mib = 'DeltaUPS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'socomec';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'dupsIdentUPSSoftwareVersion.0'); // DeltaUPS-MIB::dupsIdentUPSSoftwareVersion.0 = STRING: "0.4"

// FIXME - This only discovers a single phase - probably needs more values above? ie dupsBypassVoltage1.0 is polled, dupsBypassVoltage2.0 and 3.0 aren't, etc.
$config['mibs'][$mib]['sensor']['dupsBatteryCurrent']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.7.7.0', 'scale' => 1, 'class' => 'current', 'descr' => 'Battery');
$config['mibs'][$mib]['sensor']['dupsOutputCurrent1']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.5.5.0', 'scale' => 0.1, 'class' => 'current', 'descr' => 'Output');
$config['mibs'][$mib]['sensor']['dupsInputCurrent1']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.4.4.0', 'scale' => 0.1, 'class' => 'current', 'descr' => 'Input');
$config['mibs'][$mib]['sensor']['dupsBypassCurrent1']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.6.4.0', 'scale' => 0.1, 'class' => 'current', 'descr' => 'Bypass');
$config['mibs'][$mib]['sensor']['dupsBatteryCapacity']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.7.8.0', 'scale' => 1, 'class' => 'capacity', 'descr' => 'Battery Capacity');
$config['mibs'][$mib]['sensor']['dupsBatteryEstimatedTime']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.7.5.0', 'scale' => 1, 'class' => 'runtime', 'descr' => 'Battery Runtime Remaining');
$config['mibs'][$mib]['sensor']['dupsOutputLoad1']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.5.7.0', 'scale' => 1, 'class' => 'capacity', 'descr' => 'Output Load');
$config['mibs'][$mib]['sensor']['dupsOutputFrequency']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.5.2.0', 'scale' => 0.1, 'class' => 'frequency', 'descr' => 'Output');
$config['mibs'][$mib]['sensor']['dupsInputFrequency']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.4.2.0', 'scale' => 0.1, 'class' => 'frequency', 'descr' => 'Input');
$config['mibs'][$mib]['sensor']['dupsEnvHumidity']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.10.2.0', 'scale' => 1, 'class' => 'humidity', 'descr' => 'Environment');
$config['mibs'][$mib]['sensor']['dupsEnvTemperature']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.10.1.0', 'scale' => 1, 'class' => 'temperature', 'descr' => 'Environment');
$config['mibs'][$mib]['sensor']['dupsTemperature']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.7.9.0', 'scale' => 1, 'class' => 'temperature', 'descr' => 'Battery');
$config['mibs'][$mib]['sensor']['dupsBatteryVoltage']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.7.6.0', 'scale' => 0.1, 'class' => 'voltage', 'descr' => 'Battery');
$config['mibs'][$mib]['sensor']['dupsOutputVoltage1']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.5.4.0', 'scale' => 0.1, 'class' => 'voltage', 'descr' => 'Output');
$config['mibs'][$mib]['sensor']['dupsInputVoltage1']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.4.3.0', 'scale' => 0.1, 'class' => 'voltage', 'descr' => 'Input');
$config['mibs'][$mib]['sensor']['dupsBypassVoltage1']['indexes'][0] = array('oid_num' => '1.3.6.1.4.1.2254.2.4.6.3.0', 'scale' => 0.1, 'class' => 'voltage', 'descr' => 'Bypass');

$mib = 'E7-Calix-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6321.1.2.2.1.1';
$config['mibs'][$mib]['mib_dir'] = 'calix';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'e7SystemChassisSerialNumber.0'); // E7-Calix-MIB::e7SystemChassisSerialNumber.0 = Wrong Type (should be OCTET STRING): Counter64: 71308303059

$mib = 'EDS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.31440.1.6';
$config['mibs'][$mib]['mib_dir'] = 'eds';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'eFirmwareVersion.0'); // EDS-MIB::eFirmwareVersion.0 = STRING: "1.253"

// EMBEDDED-NGX-MIB

$mib = 'EMBEDDED-NGX-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'checkpoint';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['mempool']['swMemRAM'] = array('type'      => 'static', 'descr' => 'Memory', 'scale' => 1024,
                                                     'oid_total' => 'swMemRamTotal.0', 'oid_total_num' => '.1.3.6.1.4.1.6983.1.5.1.2.0', // EMBEDDED-NGX-MIB::swMemRamTotal.0 = INTEGER: 49380
                                                     'oid_free'  => 'swMemRamFree.0', 'oid_free_num' => '.1.3.6.1.4.1.6983.1.5.1.1.0', // EMBEDDED-NGX-MIB::swMemRamFree.0 = INTEGER: 24328
);

$mib = 'ENGENIUS-MESH-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14125.1';
$config['mibs'][$mib]['mib_dir'] = 'engenius';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ENGENIUS-PRIVATE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14125.2';
$config['mibs'][$mib]['mib_dir'] = 'engenius';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ENTERASYS-POWER-ETHERNET-EXT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.5624.1.2.50';
$config['mibs'][$mib]['mib_dir'] = 'enterasys';
$config['mibs'][$mib]['descr'] = '';

$mib = 'EQLDISK-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.12740.3';
$config['mibs'][$mib]['mib_dir'] = 'equallogic';
$config['mibs'][$mib]['descr'] = '';

$type = 'eql-disk-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'on-line', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'spare', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'off-line', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'alt-sig', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'too-small', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'history-of-failures', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'unsupported-version', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'unhealthy', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'replacement', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'encrypted', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][12] = array('name' => 'notApproved', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][13] = array('name' => 'preempt-failed', 'event' => 'exclude');

$mib = 'EQLMEMBER-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.12740.2';
$config['mibs'][$mib]['mib_dir'] = 'equallogic';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ES-RACKTIVITY-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.34097.9';
$config['mibs'][$mib]['mib_dir'] = 'racktivity';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'mFirmwareVersion.1'); // ES-RACKTIVITY-MIB::mFirmwareVersion.1.0 = STRING: 3.1.1.0

/*
// mHardwareVersion.1.0 = STRING: 1.0.0.0
// mDeviceVersion.1.0 = STRING: 1.0.0.0
*/

$mib = 'EXTREME-BASE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1916';
$config['mibs'][$mib]['mib_dir'] = 'extreme';
$config['mibs'][$mib]['descr'] = '';

$mib = 'EXTREME-POE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1916.1.27';
$config['mibs'][$mib]['mib_dir'] = 'extreme';
$config['mibs'][$mib]['descr'] = '';

$mib = 'EXTREME-SOFTWARE-MONITOR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1916.1.32';
$config['mibs'][$mib]['mib_dir'] = 'extreme';
$config['mibs'][$mib]['descr'] = '';

$mib = 'EXTREME-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1916.1.1';
$config['mibs'][$mib]['mib_dir'] = 'extreme';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'extremeSystemID.0');

// Temperature
$config['mibs'][$mib]['sensor']['extremeCurrentTemperature']['indexes'][0] = array('descr' => 'System Temperature', 'class' => 'temperature', 'measured' => 'device', 'oid' => 'extremeCurrentTemperature.0', 'oid_num' => '.1.3.6.1.4.1.1916.1.1.1.8.0');

$config['mibs'][$mib]['status']['extremePrimaryPowerOperational']['indexes'][0] = array('descr' => 'Primary Power', 'measured' => 'power', 'type' => 'extremeTruthValue', 'oid' => 'extremePrimaryPowerOperational.0', 'oid_num' => '.1.3.6.1.4.1.1916.1.1.1.10.0');
$config['mibs'][$mib]['status']['extremeRedundantPowerStatus']['indexes'][0] = array('descr' => 'Redundant Power', 'measured' => 'power', 'type' => 'extremeRedundantPowerStatus', 'oid' => 'extremeRedundantPowerStatus.0', 'oid_num' => '.1.3.6.1.4.1.1916.1.1.1.11.0');

$type = 'extremeTruthValue';
$config['mibs'][$mib]['states'][$type] = $config['mibs']['SNMPv2-MIB']['states']['TruthValue'];

$type = 'extremeRedundantPowerStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'notPresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'presentOK', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'presentNotOK', 'event' => 'alert');

$type = 'extremePowerSupplyStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'notPresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'presentOK', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'presentNotOK', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'presentPowerOff', 'event' => 'ok');

$mib = 'ExaltComProducts';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25651.1';
$config['mibs'][$mib]['mib_dir'] = 'exalt';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'serialNumber.0');

$config['mibs'][$mib]['states']['exaltcomproducts-state'][0] = array('name' => 'almNORMAL', 'event' => 'ok');
$config['mibs'][$mib]['states']['exaltcomproducts-state'][1] = array('name' => 'almMINOR', 'event' => 'warning');
$config['mibs'][$mib]['states']['exaltcomproducts-state'][2] = array('name' => 'almMAJOR', 'event' => 'alert');

$mib = 'F10-C-SERIES-CHASSIS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6027.3.8';
$config['mibs'][$mib]['mib_dir'] = 'force10';
$config['mibs'][$mib]['descr'] = '';

$mib = 'F10-CHASSIS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6027.3.1';
$config['mibs'][$mib]['mib_dir'] = 'force10';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['f10-chassis-state'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['f10-chassis-state'][2] = array('name' => 'down', 'event' => 'alert');

$mib = 'F10-M-SERIES-CHASSIS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6027.3.19';
$config['mibs'][$mib]['mib_dir'] = 'force10';
$config['mibs'][$mib]['descr'] = '';

$mib = 'F10-S-SERIES-CHASSIS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6027.3.10';
$config['mibs'][$mib]['mib_dir'] = 'force10';
$config['mibs'][$mib]['descr'] = '';

$type = 'chStackUnitStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'unsupported', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'codeMismatch', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'configMismatch', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'unitDown', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'notPresent', 'event' => 'exclude');

$type = 'chSysOperStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'up', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'down', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'absent', 'event' => 'exclude');

$mib = 'F5-BIGIP-APM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3375.2.6';
$config['mibs'][$mib]['mib_dir'] = 'f5';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['states']['f5-apm-sync-state'][0] = array('name' => 'inSync', 'event' => 'ok');
$config['mibs'][$mib]['states']['f5-apm-sync-state'][1] = array('name' => 'localModified', 'event' => 'warning');
$config['mibs'][$mib]['states']['f5-apm-sync-state'][2] = array('name' => 'peerModified', 'event' => 'warning');
$config['mibs'][$mib]['states']['f5-apm-sync-state'][3] = array('name' => 'bothModified', 'event' => 'alert');

$mib = 'F5-BIGIP-GLOBAL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3375.2.3';
$config['mibs'][$mib]['mib_dir'] = 'f5';
$config['mibs'][$mib]['descr'] = '';

$mib = 'F5-BIGIP-LOCAL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3375.2.2';
$config['mibs'][$mib]['mib_dir'] = 'f5';
$config['mibs'][$mib]['descr'] = '';

$mib = 'F5-BIGIP-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3375.2.1';
$config['mibs'][$mib]['mib_dir'] = 'f5';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'sysGeneralChassisSerialNum.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'sysPlatformInfoMarketingName.0'); // F5-BIGIP-SYSTEM-MIB::sysPlatformInfoMarketingName.0 = STRING: BIG-IP 4000

$config['mibs'][$mib]['ports']['oids'] = array(
  'ifName'               => array('oid' => 'sysIfxStatName'),
  'ifAlias'              => array('oid' => 'sysIfxStatAlias'),
  'ifHighSpeed'          => array('oid' => 'sysIfxStatHighSpeed'),
  'ifConnectorPresent'   => array('oid' => 'sysIfxStatConnectorPresent', 'rewrite' => array('1' => 'true',
                                                                                            '2' => 'false')),

  // Counters
  'ifInMulticastPkts'    => array('oid' => 'sysIfxStatInMulticastPkts'),
  'ifInBroadcastPkts'    => array('oid' => 'sysIfxStatInBroadcastPkts'),
  'ifOutMulticastPkts'   => array('oid' => 'sysIfxStatOutMulticastPkts'),
  'ifOutBroadcastPkts'   => array('oid' => 'sysIfxStatOutBroadcastPkts'),

  // HC counters
  'ifHCInOctets'         => array('oid' => 'sysIfxStatHcInOctets'),
  'ifHCInUcastPkts'      => array('oid' => 'sysIfxStatHcInUcastPkts'),
  'ifHCInMulticastPkts'  => array('oid' => 'sysIfxStatHcInMulticastPkts'),
  'ifHCInBroadcastPkts'  => array('oid' => 'sysIfxStatHcInBroadcastPkts'),
  'ifHCOutOctets'        => array('oid' => 'sysIfxStatHcOutOctets'),
  'ifHCOutUcastPkts'     => array('oid' => 'sysIfxStatHcOutUcastPkts'),
  'ifHCOutMulticastPkts' => array('oid' => 'sysIfxStatHcOutMulticastPkts'),
  'ifHCOutBroadcastPkts' => array('oid' => 'sysIfxStatHcOutBroadcastPkts'),
);

$config['mibs'][$mib]['sensor']['sysChassisFanTable']['tables'][] = array(
  'table'            => 'sysChassisFanTable',
  'class'            => 'fanspeed',
  'descr'            => 'Chassis Fan',
  'oid'              => 'sysChassisFanSpeed',
  'oid_num'          => '.1.3.6.1.4.1.3375.2.1.3.2.1.2.1.3',
  'rename_rrd_array' => array('type' => 'f5-bigip-system', 'index' => 'sysChassisFanSpeed.%index%'), // old discovery params
  'min'              => 0,
  'scale'            => 1
);
$config['mibs'][$mib]['sensor']['sysChassisTempTable']['tables'][] = array(
  'table'            => 'sysChassisTempTable',
  'class'            => 'temperature',
  'descr'            => 'Chassis Temperature',
  'oid'              => 'sysChassisTempTemperature',
  'oid_num'          => '.1.3.6.1.4.1.3375.2.1.3.2.3.2.1.2',
  'rename_rrd_array' => array('type' => 'f5-bigip-system', 'index' => 'sysChassisTempTemperature.%index%'), // old discovery params
  'min'              => 0,
  'scale'            => 1
);

$config['mibs'][$mib]['sensor']['sysCpuSensorTable']['tables'][] = array(
  'table'            => 'sysCpuSensorTable',
  'class'            => 'fanspeed',
  'descr'            => 'Slot %index0% CPU %index1%',
  //'oid_descr'             => 'sysCpuSensorName',
  'oid'              => 'sysCpuSensorFanSpeed',
  'oid_num'          => '.1.3.6.1.4.1.3375.2.1.3.6.2.1.3',
  'rename_rrd_array' => array('type' => 'f5-bigip-system', 'index' => 'sysCpuSensorFanSpeed.%index%'), // old discovery params
  //'min'                   => 0,
  'scale'            => 1
);
$config['mibs'][$mib]['sensor']['sysCpuSensorTable']['tables'][] = array(
  'table'            => 'sysCpuSensorTable',
  'class'            => 'temperature',
  'descr'            => 'Slot %index0% CPU %index1%',
  //'oid_descr'             => 'sysCpuSensorName',
  'oid'              => 'sysCpuSensorTemperature',
  'oid_num'          => '.1.3.6.1.4.1.3375.2.1.3.6.2.1.2',
  'rename_rrd_array' => array('type' => 'f5-bigip-system', 'index' => 'sysCpuSensorTemperature.%index%'), // old discovery params
  'min'              => 0,
  'scale'            => 1
);

$config['mibs'][$mib]['sensor']['sysBladeVoltageTable']['tables'][] = array(
  'table'            => 'sysBladeVoltageTable',
  'class'            => 'voltage',
  'descr'            => 'Blade',
  'oid_descr'        => 'sysBladeVoltageIndex',
  'oid'              => 'sysBladeVoltageVoltage',
  'oid_num'          => '.1.3.6.1.4.1.3375.2.1.3.2.5.2.1.2',
  'rename_rrd_array' => array('type' => 'f5-bigip-system'), // old discovery params
  //'min'                   => 0,
  'scale'            => 0.001
);

$config['mibs'][$mib]['sensor']['sysBladeTempTable']['tables'][] = array(
  'table'            => 'sysBladeTempTable',
  'class'            => 'temperature',
  'descr'            => 'Blade',
  'oid_descr'        => 'sysBladeTempLocation',
  'oid'              => 'sysBladeTempTemperature',
  'oid_num'          => '.1.3.6.1.4.1.3375.2.1.3.2.4.2.1.2',
  'rename_rrd_array' => array('type' => 'f5-bigip-system', 'index' => '%descr%'), // old discovery params
  'min'              => 0,
  'scale'            => 1
);

$config['mibs'][$mib]['status']['sysChassisFanTable']['tables'][] = array(
  'table'            => 'sysChassisFanTable',
  'descr'            => 'Chassis Fan',
  'oid'              => 'sysChassisFanStatus',
  'oid_num'          => '.1.3.6.1.4.1.3375.2.1.3.2.1.2.1.2',
  'measured'         => 'fan',
  'type'             => 'F5sysChassisStatus',
  'rename_rrd_array' => array('type' => 'f5-bigip-state', 'index' => 'sysChassisFanStatus.%index%'), // old discovery params
);

$config['mibs'][$mib]['status']['sysChassisPowerSupplyTable']['tables'][] = array(
  'table'            => 'sysChassisPowerSupplyTable',
  'descr'            => 'Chassis Power Supply',
  'oid'              => 'sysChassisPowerSupplyStatus',
  'oid_num'          => '.1.3.6.1.4.1.3375.2.1.3.2.2.2.1.2',
  'measured'         => 'powersupply',
  'type'             => 'F5sysChassisStatus',
  'rename_rrd_array' => array('type' => 'f5-bigip-state', 'index' => 'sysChassisPowerSupplyStatus.%index%'), // old discovery params
);

$type = 'F5sysChassisStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'bad', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'good', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'notpresent', 'event' => 'exclude');

$config['mibs'][$mib]['states']['f5-bigip-state'] = $config['mibs'][$mib]['states'][$type]; // CLEANME. Remove after r9000 (no CE waiting)

$config['mibs'][$mib]['status']['sysCmSyncStatusId']['indexes'][0] = array(
  'descr'            => 'Config Sync',
  'measured'         => 'other',
  'type'             => 'sysCmSyncStatusId',
  'rename_rrd_array' => array('index' => 'sysCmSyncStatusId', 'type' => 'f5-config-sync-state'), // old discovery params
  'oid_num'          => '.1.3.6.1.4.1.3375.2.1.14.1.1.0'
);
$type = 'sysCmSyncStatusId';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'unknown', 'event' => 'warning'); // ignore?
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'syncing', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'needManualSync', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'inSync', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'syncFailed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'syncDisconnected', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'standalone', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'awaitingInitialSync', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'incompatibleVersion', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'partialSync', 'event' => 'alert');

$config['mibs'][$mib]['states']['f5-config-sync-state'] = $config['mibs'][$mib]['states'][$type]; // CLEANME. Remove after r9000 (no CE waiting)

$config['mibs'][$mib]['status']['sysCmFailoverStatusId']['indexes'][0] = array(
  'descr'            => 'HA State',
  'measured'         => 'other',
  'type'             => 'sysCmFailoverStatusId',
  'rename_rrd_array' => array('index' => 'sysCmFailoverStatusId', 'type' => 'f5-ha-state'), // old discovery params
  'oid_num'          => '.1.3.6.1.4.1.3375.2.1.14.3.1.0'
);
$type = 'sysCmFailoverStatusId';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'unknown', 'event' => 'ok'); // ignore?
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'offline', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'forcedOffline', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'standby', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'active', 'event' => 'ok');

$config['mibs'][$mib]['states']['f5-ha-state'] = $config['mibs'][$mib]['states'][$type]; // CLEANME. Remove after r9000 (no CE waiting)

$mib = 'FE-FIREEYE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25597.20.1';
$config['mibs'][$mib]['mib_dir'] = 'fireeye';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'feSerialNumber.0'); // FE-FIREEYE-MIB::feSerialNumber.0 = STRING: "FM1419CA02Y"
$config['mibs'][$mib]['version'][] = array('oid' => 'feSystemImageVersionCurrent.0'); // FE-FIREEYE-MIB::feSystemImageVersionCurrent.0 = STRING: "7.2.0"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'feHardwareModel.0'); //F E-FIREEYE-MIB::feHardwareModel.0 = STRING: "FireEyeCMS4400"

// FORTINET-CORE-MIB

$mib = 'FORTINET-CORE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'fortinet';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'fnSysSerial.0'); // FORTINET-CORE-MIB::fmlSysSerial.0 = STRING: FEVM040000058143

$mib = 'FORTINET-FORTIGATE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.12356.101';
$config['mibs'][$mib]['mib_dir'] = 'fortinet';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['processor']['fgProcessorTable'] = array('type' => 'table', 'table' => 'fgProcessorTable', 'oid' => 'fgProcessorUsage', 'oid_num' => '.1.3.6.1.4.1.12356.101.4.4.2.1.2'); // FORTINET-FORTIGATE-MIB::fgProcessorUsage.1 = Gauge32: 0 %
$config['mibs'][$mib]['processor']['fgSysCpuUsage'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'fgSysCpuUsage.0', 'oid_num' => '.1.3.6.1.4.1.12356.101.4.1.3.0', 'skip_if_valid_exist' => 'fgProcessorTable->fgProcessorUsage.1'); // FORTINET-FORTIGATE-MIB::fgSysCpuUsage.0 = Gauge32: 0

$config['mibs'][$mib]['mempool']['fgSystemInfo'] = array('type'      => 'static', 'descr' => 'Memory',
                                                         'oid_perc'  => 'fgSysMemUsage.0', 'oid_perc_num' => '.1.3.6.1.4.1.12356.101.4.1.4.0', // FORTINET-FORTIGATE-MIB::fgSysMemUsage.0 = Gauge32: 59
                                                         'oid_total' => 'fgSysMemCapacity.0', 'oid_total_num' => '.1.3.6.1.4.1.12356.101.4.1.5.0', // FORTINET-FORTIGATE-MIB::fgSysMemCapacity.0 = Gauge32: 514924
);

$mib = 'FORTINET-FORTIMAIL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.12356.105';
$config['mibs'][$mib]['mib_dir'] = 'fortinet';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'fmlSysVersion.0'); // FORTINET-FORTIMAIL-MIB::fmlSysVersion.0 = STRING: v5.3,build599,160527 (5.3.3 GA)
$config['mibs'][$mib]['hardware'][] = array('oid' => 'fmlSysModel.0'); // FORTINET-FORTIMAIL-MIB::fmlSysModel.0 = STRING: FortiMail-VM-HV
$config['mibs'][$mib]['processor']['fmlSysCpuUsage'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'fmlSysCpuUsage.0', 'oid_num' => '.1.3.6.1.4.1.12356.105.1.6.0');

/*
FORTINET-FORTIMAIL-MIB::fmlSysVersionAv.0 = STRING: 39.497(09/20/2016 01:11)
*/

$mib = 'FA-EXT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1588.2.1.1.1.28';
$config['mibs'][$mib]['mib_dir'] = 'brocade';
$config['mibs'][$mib]['descr'] = '';

$mib = 'FCMGMT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = ''; // .1.3.6.1.3.94
$config['mibs'][$mib]['mib_dir'] = 'brocade';
$config['mibs'][$mib]['descr'] = '';

// Note this is custom changed MIB, not really exist, adopted from Draft BGP4V2-MIB
$mib = 'FOUNDRY-BGP4V2-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1991.3.5.1';
$config['mibs'][$mib]['mib_dir'] = 'brocade';
$config['mibs'][$mib]['descr'] = '';

$mib = 'FOUNDRY-POE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1991.1.1.2.14';
$config['mibs'][$mib]['mib_dir'] = 'brocade';
$config['mibs'][$mib]['descr'] = '';

$mib = 'FOUNDRY-SN-AGENT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1991.4';
$config['mibs'][$mib]['mib_dir'] = 'brocade';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'snChasSerNum.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'snAgImgVer.0');

$type = 'foundry-sn-agent-oper-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'other', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'failure', 'event' => 'alert');

$type = 'snAgentBrdModuleStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'moduleEmpty', 'event' => 'exclude');  // The slot of the chassis is empty
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'moduleGoingDown', 'event' => 'ignore');    // The module is going down
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'moduleRejected', 'event' => 'alert');   // The module is being rejected due to wrong configuration
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'moduleBad', 'event' => 'alert');   // The module Hardware is bad
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'moduleConfigured', 'event' => 'ok');      // The module is configured (stacking)
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'moduleComingUp', 'event' => 'ok');      // The module is in power-up cycle.
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'moduleRunning', 'event' => 'ok');      // The module is running.
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'moduleBlocked', 'event' => 'warning'); // The module is blocked, for full height card

$type = 'snAgentBrdRedundantStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'other', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'active', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'standby', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'crashed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'comingUp', 'event' => 'warning');

$mib = 'FOUNDRY-SN-SWITCH-GROUP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1991.1.1.3';
$config['mibs'][$mib]['mib_dir'] = 'brocade';
$config['mibs'][$mib]['descr'] = '';

$mib = 'FROGFOOT-RESOURCES-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.4.1.10002.1.1.1',     // FROGFOOT-RESOURCES-MIB::resources (MODULE-IDENTITY)
  '.1.3.6.1.4.1.10002.1.1.1.31'); // FROGFOOT-RESOURCES-MIB::resMIB    (additional sysORID)
$config['mibs'][$mib]['mib_dir'] = 'ubiquiti'; // This is not really Ubiquiti MIB, but currently stored in this dir
$config['mibs'][$mib]['descr'] = '';
// loadValue.2 = Gauge32: 0
$config['mibs'][$mib]['processor']['loadValue'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'loadValue.2', 'oid_num' => '.1.3.6.1.4.1.10002.1.1.1.4.2.1.3.2');
// FIXME ^ because 'processor' definition currently doesn't support indexes, it gets indexed as index 0.
$config['mibs'][$mib]['mempool']['memory'] = array('type'      => 'static', 'descr' => 'Memory', 'scale' => 1024,
                                                   'oid_free'  => 'memFree.0', 'oid_free_num' => '.1.3.6.1.4.1.10002.1.1.1.1.2.0', // FROGFOOT-RESOURCES-MIB::memFree.0 = Gauge32: 4584
                                                   'oid_total' => 'memTotal.0', 'oid_total_num' => '.1.3.6.1.4.1.10002.1.1.1.1.1.0', // FROGFOOT-RESOURCES-MIB::memTotal.0 = Gauge32: 29524
);
// Unused: FROGFOOT-RESOURCES-MIB::memBuffer.0 = Gauge32: 3584

$mib = 'FspR7-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2544.1.11.2';
$config['mibs'][$mib]['mib_dir'] = 'adva';
$config['mibs'][$mib]['descr'] = '';

$mib = 'G3-AVAYA-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'avaya';
$config['mibs'][$mib]['descr'] = '';

$mib = 'G6-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3181.10.6.1';
$config['mibs'][$mib]['mib_dir'] = 'microsens-g6';
$config['mibs'][$mib]['descr'] = '';

// G6-FACTORY-MIB

$mib = 'G6-FACTORY-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'microsens-g6';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'factorySerialNumber.0'); // G6-FACTORY-MIB::factorySerialNumber.0; Value (OctetString): <removed>
$config['mibs'][$mib]['version'][] = array('oid' => 'firmwareRunningVersion.0'); // G6-SYSTEM-MIB::firmwareRunningVersion.0; Value (OctetString): 10.5.4a
$config['mibs'][$mib]['hardware'][] = array('oid' => 'factoryArticleNumber.0'); // G6-FACTORY-MIB::factoryArticleNumber.0; Value (OctetString): MS440210M-G6+

/*
G6-FACTORY-MIB::factoryWebDescription.0; Value (OctetString): Micro Switch 6xGBE, Mgmt, MicroSD card, internal memory, Vert., 4xRJ-45, EEE,  Up: ST MM 850nm, Down: RJ-45, Pwr: AC, 10W
*/

$mib = 'GAMATRONIC-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6050';
$config['mibs'][$mib]['mib_dir'] = 'gamatronic';
$config['mibs'][$mib]['descr'] = '';

$mib = 'GBOS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.13559';
$config['mibs'][$mib]['mib_dir'] = 'gta';
$config['mibs'][$mib]['descr'] = '';

$mib = 'GEIST-IMD-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.21239.5.2';
$config['mibs'][$mib]['mib_dir'] = 'geist';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'productVersion.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'productTitle.0');
$config['mibs'][$mib]['serial'][] = array('oid' => 'pduMainSerial.1');
$config['mibs'][$mib]['ra_url_http'][] = array('oid' => 'productUrl.0', 'transformations' => array(array('action' => 'prepend', 'string' => 'http://'), array('action' => 'replace', 'from' => 'http://http://', 'to' => 'http://')));

//GEIST-IMD-MIB::pduTotalName.1 = STRING: Total
//GEIST-IMD-MIB::pduTotalLabel.1 = STRING: Total
//GEIST-IMD-MIB::pduTotalRealPower.1 = Gauge32: 740 watts
//GEIST-IMD-MIB::pduTotalApparentPower.1 = Gauge32: 851 volt-amps
//GEIST-IMD-MIB::pduTotalPowerFactor.1 = Gauge32: 86 %
//GEIST-IMD-MIB::pduTotalEnergy.1 = Gauge32: 1488027 watt-hours
$config['mibs'][$mib]['sensor']['pduTotalRealPower']['indexes'][1] = array(
  'descr'    => 'Total Power',
  'class'    => 'power',
  'measured' => 'device',
  'scale'    => 1,
  'oid'      => 'pduTotalRealPower.1',
  'oid_num'  => '.1.3.6.1.4.1.21239.5.2.3.1.1.9.1'
);
$config['mibs'][$mib]['sensor']['pduTotalApparentPower']['indexes'][1] = array(
  'descr'    => 'Total Apparent Power',
  'class'    => 'apower',
  'measured' => 'device',
  'scale'    => 1,
  'oid'      => 'pduTotalApparentPower.1',
  'oid_num'  => '.1.3.6.1.4.1.21239.5.2.3.1.1.10.1'
);
$config['mibs'][$mib]['sensor']['pduTotalPowerFactor']['indexes'][1] = array(
  'descr'    => 'Total Power Factor',
  'class'    => 'powerfactor',
  'measured' => 'device',
  'scale'    => 0.01,
  'oid'      => 'pduTotalPowerFactor.1',
  'oid_num'  => '.1.3.6.1.4.1.21239.5.2.3.1.1.11.1'
);

//GEIST-IMD-MIB::pduPhaseIndex.1 = INTEGER: 1
//GEIST-IMD-MIB::pduPhaseName.1 = STRING: Phase A
//GEIST-IMD-MIB::pduPhaseLabel.1 = STRING: Phase A
//GEIST-IMD-MIB::pduPhaseVoltage.1 = Gauge32: 2275 decivolts (rms)
//GEIST-IMD-MIB::pduPhaseVoltageMax.1 = Gauge32: 2294 decivolts (rms)
//GEIST-IMD-MIB::pduPhaseVoltageMin.1 = Gauge32: 2269 decivolts (rms)
//GEIST-IMD-MIB::pduPhaseVoltagePeak.1 = Gauge32: 3295 decivolts
//GEIST-IMD-MIB::pduPhaseCurrent.1 = Gauge32: 374 centiamps (rms)
//GEIST-IMD-MIB::pduPhaseCurrentMax.1 = Gauge32: 870 centiamps (rms)
//GEIST-IMD-MIB::pduPhaseCurrentMin.1 = Gauge32: 160 centiamps (rms)
//GEIST-IMD-MIB::pduPhaseCurrentPeak.1 = Gauge32: 14789 centiamps (rms)
//GEIST-IMD-MIB::pduPhaseRealPower.1 = Gauge32: 739 watts
//GEIST-IMD-MIB::pduPhaseApparentPower.1 = Gauge32: 850 volt-amps
//GEIST-IMD-MIB::pduPhasePowerFactor.1 = Gauge32: 86 %
//GEIST-IMD-MIB::pduPhaseEnergy.1 = Gauge32: 1488028 watt-hours
$config['mibs'][$mib]['sensor']['pduPhaseEntry']['tables'][] = array(
  'table'   => 'pduPhaseEntry',
  'class'   => 'voltage',
  'descr'   => 'Phase %index% Voltage',
  'oid'     => 'pduPhaseVoltage',
  'oid_num' => '.1.3.6.1.4.1.21239.5.2.3.2.1.4',
  'scale'   => 0.1
);
$config['mibs'][$mib]['sensor']['pduPhaseEntry']['tables'][] = array(
  'table'   => 'pduPhaseEntry',
  'class'   => 'current',
  'descr'   => 'Phase %index% Current',
  'oid'     => 'pduPhaseCurrent',
  'oid_num' => '.1.3.6.1.4.1.21239.5.2.3.2.1.8',
  'scale'   => 0.01
);
$config['mibs'][$mib]['sensor']['pduPhaseEntry']['tables'][] = array(
  'table'   => 'pduPhaseEntry',
  'class'   => 'power',
  'descr'   => 'Phase %index% Power',
  'oid'     => 'pduPhaseRealPower',
  'oid_num' => '.1.3.6.1.4.1.21239.5.2.3.2.1.12',
  'scale'   => 1
);
$config['mibs'][$mib]['sensor']['pduPhaseEntry']['tables'][] = array(
  'table'   => 'pduPhaseEntry',
  'class'   => 'apower',
  'descr'   => 'Phase %index% Apparent Power',
  'oid'     => 'pduPhaseApparentPower',
  'oid_num' => '.1.3.6.1.4.1.21239.5.2.3.2.1.13',
  'scale'   => 1
);
$config['mibs'][$mib]['sensor']['pduPhaseEntry']['tables'][] = array(
  'table'   => 'pduPhaseEntry',
  'class'   => 'powerfactor',
  'descr'   => 'Phase %index% Power Factor',
  'oid'     => 'pduPhasePowerFactor',
  'oid_num' => '.1.3.6.1.4.1.21239.5.2.3.2.1.14',
  'scale'   => 0.01
);

//GEIST-IMD-MIB::pduBreakerIndex.1 = INTEGER: 1
//GEIST-IMD-MIB::pduBreakerIndex.2 = INTEGER: 2
//GEIST-IMD-MIB::pduBreakerName.1 = STRING: Circuit 1
//GEIST-IMD-MIB::pduBreakerName.2 = STRING: Circuit 2
//GEIST-IMD-MIB::pduBreakerLabel.1 = STRING: Circuit 1
//GEIST-IMD-MIB::pduBreakerLabel.2 = STRING: Circuit 2
//GEIST-IMD-MIB::pduBreakerCurrent.1 = Gauge32: 215 centiamps (rms)
//GEIST-IMD-MIB::pduBreakerCurrent.2 = Gauge32: 155 centiamps (rms)
//GEIST-IMD-MIB::pduBreakerCurrentMax.1 = Gauge32: 321 centiamps (rms)
//GEIST-IMD-MIB::pduBreakerCurrentMax.2 = Gauge32: 292 centiamps (rms)
//GEIST-IMD-MIB::pduBreakerCurrentMin.1 = Gauge32: 73 centiamps (rms)
//GEIST-IMD-MIB::pduBreakerCurrentMin.2 = Gauge32: 37 centiamps (rms)
//GEIST-IMD-MIB::pduBreakerCurrentPeak.1 = Gauge32: 2904 centiamps (rms)
//GEIST-IMD-MIB::pduBreakerCurrentPeak.2 = Gauge32: 2896 centiamps (rms)
$config['mibs'][$mib]['sensor']['pduBreakerEntry']['tables'][] = array(
  'table'     => 'pduBreakerEntry',
  'class'     => 'current',
  'descr'     => '%oid_descr% Current',
  'oid_descr' => 'pduBreakerName',
  'oid'       => 'pduBreakerCurrent',
  'oid_num'   => '.1.3.6.1.4.1.21239.5.2.3.3.1.4',
  'scale'     => 0.01
);

$mib = 'GEIST-MIB-V3';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.21239.2';
$config['mibs'][$mib]['mib_dir'] = 'geist';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'productVersion.0');
// productUrl.0 can contain either IP or http://url.here, hacky transformation to prepend http:// if not there
$config['mibs'][$mib]['ra_url_http'][] = array('oid' => 'productUrl.0', 'transformations' => array(array('action' => 'prepend', 'string' => 'http://'), array('action' => 'replace', 'from' => 'http://http://', 'to' => 'http://')));

$config['mibs'][$mib]['states']['geist-mib-v3-door-state'][1] = array('name' => 'closed', 'event' => 'ok');
$config['mibs'][$mib]['states']['geist-mib-v3-door-state'][99] = array('name' => 'open', 'event' => 'alert');

$config['mibs'][$mib]['states']['geist-mib-v3-digital-state'][1] = array('name' => 'off', 'event' => 'alert');
$config['mibs'][$mib]['states']['geist-mib-v3-digital-state'][99] = array('name' => 'on', 'event' => 'ok');

$config['mibs'][$mib]['states']['geist-mib-v3-smokealarm-state'][1] = array('name' => 'clear', 'event' => 'ok');
$config['mibs'][$mib]['states']['geist-mib-v3-smokealarm-state'][99] = array('name' => 'smoky', 'event' => 'alert');

$config['mibs'][$mib]['states']['geist-mib-v3-climateio-state'][0] = array('name' => '0V', 'event' => 'ok');
$config['mibs'][$mib]['states']['geist-mib-v3-climateio-state'][99] = array('name' => '5V', 'event' => 'ok');
$config['mibs'][$mib]['states']['geist-mib-v3-climateio-state'][100] = array('name' => '5V', 'event' => 'ok');

$config['mibs'][$mib]['states']['geist-mib-v3-relay-state'][0] = array('name' => 'off', 'event' => 'ok');
$config['mibs'][$mib]['states']['geist-mib-v3-relay-state'][1] = array('name' => 'on', 'event' => 'ok');

$mib = 'GEIST-V4-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.21239.5.1';
$config['mibs'][$mib]['mib_dir'] = 'geist';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'productVersion.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'productTitle.0');
// productUrl.0 can contain either IP or http://url.here, hacky transformation to prepend http:// if not there
$config['mibs'][$mib]['ra_url_http'][] = array('oid' => 'productUrl.0', 'transformations' => array(array('action' => 'prepend', 'string' => 'http://'), array('action' => 'replace', 'from' => 'http://http://', 'to' => 'http://')));

$config['mibs'][$mib]['states']['geist-v4-mib-io-state'][0] = array('name' => '0V', 'event' => 'ok');
$config['mibs'][$mib]['states']['geist-v4-mib-io-state'][100] = array('name' => '5V', 'event' => 'ok');

$mib = 'GUDEADS-EPC2X6-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.28507';
$config['mibs'][$mib]['mib_dir'] = 'gude';
$config['mibs'][$mib]['descr'] = '';

$mib = 'GUDEADS-EPC8X-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.28507';
$config['mibs'][$mib]['mib_dir'] = 'gude';
$config['mibs'][$mib]['descr'] = '';

$mib = 'GUDEADS-PDU8110-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.28507';
$config['mibs'][$mib]['mib_dir'] = 'gude';
$config['mibs'][$mib]['descr'] = '';

$mib = 'GUDEADS-PDU8310-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.28507';
$config['mibs'][$mib]['mib_dir'] = 'gude';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HALON-SP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.33234.1.1';
$config['mibs'][$mib]['mib_dir'] = 'bsd';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'serialNumber.0'); // HALON-SP-MIB::serialNumber.0 = STRING: "10005619"

$mib = 'HIK-DEVICE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'hikvision';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['hardware'][] = array('oid' => 'deviceType.0'); // HIK-DEVICE-MIB::deviceType.0 = STRING: "DS-2CD2332-I"
$config['mibs'][$mib]['processor']['cpuPercent'] = array('type' => 'static', 'descr' => 'System CPU', 'oid' => 'cpuPercent.0', 'oid_num' => '.1.3.6.1.4.1.39165.1.7.0');

$mib = 'HM2-DEVMGMT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.248.11.10';
$config['mibs'][$mib]['mib_dir'] = 'hirschmann';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'hm2DevMgmtSerialNumber.0'); // HM2-DEVMGMT-MIB::hm2DevMgmtSerialNumber.0 = STRING: 942053999030902476
$config['mibs'][$mib]['version'][] = array('oid' => 'hm2DevMgmtSwVersion.ram.firmware.1', 'transformations' => array(array('action' => 'regex_replace', 'from' => '/^.*?-0?([\d\.]+) .*/', 'to' => '$1'))); // HM2-DEVMGMT-MIB::hm2DevMgmtSwVersion.ram.firmware.1 = STRING: HiOS-3S-06.0.01 2016-06-15 18:04
$config['mibs'][$mib]['hardware'][] = array('oid' => 'hm2DevMgmtProductDescr.0', 'transformations' => array(array('action' => 'regex_replace', 'from' => '/^([^\-\s]+).*/', 'to' => '$1'))); // HM2-DEVMGMT-MIB::hm2DevMgmtProductDescr.0 = STRING: RSP35-08033O6ZT-SCCZ9HPE3S

$mib = 'HM2-PWRMGMT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.248.11.11';
$config['mibs'][$mib]['mib_dir'] = 'hirschmann';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['status']['hm2PSEntry']['tables'][] = array(
  'table'    => 'hm2PSEntry',
  'type'     => 'hm2PSState',
  'descr'    => 'Power Supply',
  'oid'      => 'hm2PSState',
  'measured' => 'powersupply'
);

$type = 'hm2PSState';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'present', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'defective', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'notInstalled', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'unknown', 'event' => 'exclude');

$mib = 'HM2-DIAGNOSTIC-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.248.11.22';
$config['mibs'][$mib]['mib_dir'] = 'hirschmann';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['processor']['hm2DiagCpuResourcesGroup'] = array('type' => 'static', 'descr' => 'System CPU',
                                                                       'oid'  => 'hm2DiagCpuUtilization.0', 'oid_num' => '.1.3.6.1.4.1.248.11.22.1.8.10.1.0'
);
$config['mibs'][$mib]['processor']['hm2DiagNetworkResourcesGroup'] = array('type' => 'static', 'descr' => 'Interface CPU',
                                                                           'oid'  => 'hm2DiagNetworkCpuIfUtilization.0', 'oid_num' => '.1.3.6.1.4.1.248.11.22.1.8.12.1.0'
);
$config['mibs'][$mib]['mempool']['hm2DiagMemoryResourcesGroup'] = array('type'     => 'static', 'descr' => 'Memory', 'scale' => 1024,
                                                                        'oid_used' => 'hm2DiagMemoryRamAllocated.0', 'oid_used_num' => '.1.3.6.1.4.1.248.11.22.1.8.11.1.0',
                                                                        'oid_free' => 'hm2DiagMemoryRamFree.0', 'oid_free_num' => '.1.3.6.1.4.1.248.11.22.1.8.11.2.0',
);

$mib = 'HMPRIV-MGMT-SNMP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.248.14';
$config['mibs'][$mib]['mib_dir'] = 'hirschmann';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'hmSysVersion.0', 'transformations' => array(array('action' => 'regex_replace', 'from' => '/^.*SW\: ([\d\.\-]+) .*/', 'to' => '$1'))); // HMPRIV-MGMT-SNMP-MIB::hmSysVersion.0 = STRING: SW: 5.07 CH: 1.00 BP: 000
$config['mibs'][$mib]['hardware'][] = array('oid' => 'hmSysProduct.0'); // HMPRIV-MGMT-SNMP-MIB::hmSysProduct.0 = INTEGER: ms2108-2(20)
$config['mibs'][$mib]['processor']['hmCpuResources'] = array('type' => 'static', 'descr' => 'System CPU',
                                                             'oid'  => 'hmCpuUtilization.0', 'oid_num' => '.1.3.6.1.4.1.248.14.2.15.2.1.0'
);
$config['mibs'][$mib]['mempool']['hmMemoryResources'] = array('type'     => 'static', 'descr' => 'Memory', 'scale' => 1024,
                                                              'oid_used' => 'hmMemoryAllocated.0', 'oid_used_num' => '.1.3.6.1.4.1.248.14.2.15.3.1.0',
                                                              'oid_free' => 'hmMemoryFree.0', 'oid_free_num' => '.1.3.6.1.4.1.248.14.2.15.3.2.0',
);

$config['mibs'][$mib]['sensor']['hmTemperature']['indexes'][0] = array('descr'          => 'Internal Temperature',
                                                                       'class'          => 'temperature',
                                                                       'measured'       => 'device',
                                                                       'scale'          => 1,
                                                                       'oid'            => 'hmTemperature.0',
                                                                       'oid_limit_low'  => 'hmTempLwrLimit.0',
                                                                       'oid_limit_high' => 'hmTempUprLimit.0',
                                                                       'min'            => 0);

$config['mibs'][$mib]['status']['hmPSEntry']['tables'][] = array(
  'table'    => 'hmPSEntry',
  'type'     => 'hmState',
  'descr'    => 'Power Supply',
  'oid'      => 'hmPSState',
  'measured' => 'powersupply'
);

$config['mibs'][$mib]['status']['hmFanEntry']['tables'][] = array(
  'table'    => 'hmFanEntry',
  'type'     => 'hmState',
  'descr'    => 'Fan',
  'oid'      => 'hmFanState',
  'measured' => 'fan'
);

$type = 'hmState';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'notInstalled', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'unknown', 'event' => 'exclude');

$mib = 'H3C-ENTITY-VENDORTYPE-OID-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2011.10.3';
$config['mibs'][$mib]['mib_dir'] = 'h3c';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HH3C-ENTITY-EXT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25506.2.6';
$config['mibs'][$mib]['mib_dir'] = 'hh3c';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'hh3cEntityExtManuSerialNum.1');

$mib = 'HH3C-ENTITY-VENDORTYPE-OID-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25506.3';
$config['mibs'][$mib]['mib_dir'] = 'hh3c';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HH3C-NQA-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25506.8.3';
$config['mibs'][$mib]['mib_dir'] = 'hh3c';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HH3C-POWER-ETH-EXT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25506.2.14';
$config['mibs'][$mib]['mib_dir'] = 'hh3c';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HH3C-TRANSCEIVER-INFO-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25506.2.70';
$config['mibs'][$mib]['mib_dir'] = 'hh3c';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HH3C-STACK-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25506.2.91';
$config['mibs'][$mib]['mib_dir'] = 'hh3c';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['hh3c-stack-board-status'][1] = array('name' => 'slave', 'event' => 'ok');
$config['mibs'][$mib]['states']['hh3c-stack-board-status'][2] = array('name' => 'master', 'event' => 'ok');
$config['mibs'][$mib]['states']['hh3c-stack-board-status'][3] = array('name' => 'loading', 'event' => 'warning');
$config['mibs'][$mib]['states']['hh3c-stack-board-status'][4] = array('name' => 'other', 'event' => 'warning');

$mib = 'HPN-ICF-DOT11-ACMT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HPN-ICF-ENTITY-EXT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.11.2.14.11.15.2.6';
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'hpnicfEntityExtManuSerialNum.1');

$mib = 'HPN-ICF-ENTITY-VENDORTYPE-OID-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.11.2.14.11.15.3';
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HPVC-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.11.5.7.5.2.1';
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['hardware'][] = array('oid' => 'vcModuleProductName.101');  // HPVC-MIB::vcModuleProductName.101 = STRING: HP VC Flex-10/10D Module
$config['mibs'][$mib]['version'][] = array('oid'             => 'vcModuleFwRev.101',         // HPVC-MIB::vcModuleFwRev.101 = STRING: 4.45 2015-07-21T00:14:47Z
                                           'transformations' => array(array('action' => 'regex_replace', 'from' => '/^(\d[\d\.\-]+).*/', 'to' => '$1')));
$config['mibs'][$mib]['serial'][] = array('oid' => 'vcModuleSerialNumber.101'); // HPVC-MIB::vcModuleSerialNumber.101 = STRING: 3C423800JK

$mib = 'HP-SN-AGENT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'snAgImgVer.0');

$mib = 'HP-LASERJET-COMMON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.11.2.3.9.4.2';
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['sensor']['total-engine-page-count']['indexes'][0] = array('descr'               => 'Total Printed Pages',
                                                                                 'class'               => 'counter',
                                                                                 'measured'            => 'printersupply',
                                                                                 'scale'               => 1,
                                                                                 'min'                 => 0,
                                                                                 'oid_num'             => '.1.3.6.1.4.1.11.2.3.9.4.2.1.4.1.2.5.0',
  // Skip sensor if exist $valid['sensor']['counter']['Printer-MIB-prtMarkerLifeCount']
                                                                                 'skip_if_valid_exist' => 'counter->Printer-MIB-prtMarkerLifeCount');

// SEMI-MIB

$mib = 'SEMI-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.11.2.36.1';
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'hpHttpMgSerialNumber.0');

$mib = 'HP-ICF-CHASSIS';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.11.2.14.10.2.3';
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';

$type = 'hp-icf-chassis-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'unknown', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'bad', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'good', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'notPresent', 'event' => 'exclude');

$mib = 'HP-ICF-POE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.11.2.14.11.1.9.1';
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HUAWEI-ENERGYMNGT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2011.6.157';
$config['mibs'][$mib]['mib_dir'] = 'huawei';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HUAWEI-ENTITY-EXTENT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2011.5.25.31';
$config['mibs'][$mib]['mib_dir'] = 'huawei';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['hardware'][] = array('oid' => 'hwEntitySystemModel.0'); // HUAWEI-ENTITY-EXTENT-MIB::hwEntitySystemModel.0 = STRING: S5700-52P-PWR-LI-AC

$type = 'huawei-entity-ext-mib-fan-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'abnormal', 'event' => 'alert');

$mib = 'HUAWEI-POE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2011.5.25.195';
$config['mibs'][$mib]['mib_dir'] = 'huawei';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HUAWEI-TC-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2011.20021210';
$config['mibs'][$mib]['mib_dir'] = 'huawei';
$config['mibs'][$mib]['descr'] = '';

$mib = 'HWG-PWR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'hwgroup';
$config['mibs'][$mib]['descr'] = '';

$type = 'mtvalAlarmState';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'invalid', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'alarm', 'event' => 'alert');

$mib = 'IB-DHCPONE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.7779.3.1.1.4.1';
$config['mibs'][$mib]['mib_dir'] = 'infoblox';
$config['mibs'][$mib]['descr'] = '';

$mib = 'IB-DNSONE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.7779.3.1.1.3.1';
$config['mibs'][$mib]['mib_dir'] = 'infoblox';
$config['mibs'][$mib]['descr'] = '';

$mib = 'IB-PLATFORMONE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.7779.3.1.1.2.1';
$config['mibs'][$mib]['mib_dir'] = 'infoblox';
$config['mibs'][$mib]['descr'] = '';

$mib = 'INFRATEC-RMS-MIB'; // FIXME This MIB is missing from mibs/ ! - committed in r4992. MIB file not found back then, code uses raw OIDs.
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = ''; // FIXME OID
$config['mibs'][$mib]['mib_dir'] = '';
$config['mibs'][$mib]['descr'] = '';

$mib = 'IPOMANII-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'ingrasys';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ISPRO-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'jacarta';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['hardware'][] = array('oid' => 'isIdentModel.0'); // ISPRO-MIB::isIdentModel.0 = STRING: "interSeptor Pro"

$type = 'ispro-mib-trigger-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'triggered', 'event' => 'alert');

$type = 'ispro-mib-threshold-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'unknown', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'disable', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'below-low-warning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'below-low-critical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'above-high-warning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'above-high-critical', 'event' => 'alert');

$mib = 'ISILON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.12124';
$config['mibs'][$mib]['mib_dir'] = 'isilon';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'chassisSerialNumber.1'); // ISILON-MIB::chassisSerialNumber.1 = STRING: JAMER153800294

$type = 'isilonHealth';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'attn', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'down', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'invalid', 'event' => 'alert'); // or ignore?

$config['mibs'][$mib]['status']['clusterHealth']['indexes'][0] = array('descr' => 'Cluster Health', 'measured' => 'other', 'type' => 'isilonHealth', 'oid_num' => '.1.3.6.1.4.1.12124.1.1.2.0');
$config['mibs'][$mib]['status']['nodeHealth']['indexes'][0] = array('descr' => 'Node Health', 'measured' => 'device', 'type' => 'isilonHealth', 'oid_num' => '.1.3.6.1.4.1.12124.2.1.2.0');

$config['mibs'][$mib]['sensor']['fanTable']['tables'][] = array(
  'table'     => 'fanTable',
  'class'     => 'fanspeed',
  //'descr'                 => 'Fan',
  'oid'       => 'fanSpeed',
  'oid_descr' => 'fanDescription',
  //'oid_num'               => '',
  //'min'                   => 0,
  'scale'     => 1
);
$config['mibs'][$mib]['sensor']['tempSensorTable']['tables'][] = array(
  'table'     => 'tempSensorTable',
  'class'     => 'temperature',
  //'descr'               => 'Temp',
  'oid'       => 'tempSensorValue',
  'oid_descr' => 'tempSensorDescription',
  //'oid_num'               => '',
  //'min'                   => 0,
  'scale'     => 1
);
$config['mibs'][$mib]['sensor']['powerSensorTable']['tables'][] = array(
  'table'     => 'powerSensorTable',
  'class'     => 'voltage',
  //'descr'                 => 'Voltage',
  'oid'       => 'powerSensorValue',
  'oid_descr' => 'powerSensorDescription',
  //'oid_num'               => '',
  //'min'                   => 0,
  'scale'     => 1
);

$mib = 'IT-WATCHDOGS-MIB-V3';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.17373';
$config['mibs'][$mib]['mib_dir'] = 'itwatchdogs';
$config['mibs'][$mib]['descr'] = '';

$mib = 'IT-WATCHDOGS-V4-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.17373';
$config['mibs'][$mib]['mib_dir'] = 'itwatchdogs';
$config['mibs'][$mib]['descr'] = '';

$mib = 'JANITZA-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'janitza';
$config['mibs'][$mib]['descr'] = '';
// Frequency
$config['mibs'][$mib]['sensor']['frequenz']['indexes'][0] = array('descr' => 'Frequency', 'class' => 'frequency', 'measured' => 'device', 'oid' => 'frequenz.0', 'oid_num' => '.1.3.6.1.4.1.34278.8.1.0', 'min' => 0, 'scale' => 0.01);
// Voltage
$config['mibs'][$mib]['sensor']['uLN1']['indexes'][0] = array('descr' => 'Phase L1', 'class' => 'voltage', 'measured' => 'device', 'oid' => 'uLN1.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.1.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['uLN2']['indexes'][0] = array('descr' => 'Phase L2', 'class' => 'voltage', 'measured' => 'device', 'oid' => 'uLN2.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.2.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['uLN3']['indexes'][0] = array('descr' => 'Phase L3', 'class' => 'voltage', 'measured' => 'device', 'oid' => 'uLN3.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.3.0', 'min' => 0, 'scale' => 0.1);
$config['mibs'][$mib]['sensor']['uLN4']['indexes'][0] = array('descr' => 'Phase L4', 'class' => 'voltage', 'measured' => 'device', 'oid' => 'uLN4.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.4.0', 'min' => 0, 'scale' => 0.1);
// Current
$config['mibs'][$mib]['sensor']['iL1']['indexes'][0] = array('descr' => 'Phase L1', 'class' => 'current', 'measured' => 'device', 'oid' => 'iL1.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.8.0', 'min' => 0, 'scale' => 0.001);
$config['mibs'][$mib]['sensor']['iL2']['indexes'][0] = array('descr' => 'Phase L2', 'class' => 'current', 'measured' => 'device', 'oid' => 'iL2.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.9.0', 'min' => 0, 'scale' => 0.001);
$config['mibs'][$mib]['sensor']['iL3']['indexes'][0] = array('descr' => 'Phase L3', 'class' => 'current', 'measured' => 'device', 'oid' => 'iL3.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.10.0', 'min' => 0, 'scale' => 0.001);
$config['mibs'][$mib]['sensor']['iL4']['indexes'][0] = array('descr' => 'Phase L4', 'class' => 'current', 'measured' => 'device', 'oid' => 'iL4.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.11.0', 'min' => 0, 'scale' => 0.001);
// Real Power
$config['mibs'][$mib]['sensor']['pL1']['indexes'][0] = array('descr' => 'Real Power Phase L1', 'class' => 'power', 'measured' => 'device', 'oid' => 'pL1.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.12.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['pL2']['indexes'][0] = array('descr' => 'Real Power Phase L2', 'class' => 'power', 'measured' => 'device', 'oid' => 'pL2.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.13.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['pL3']['indexes'][0] = array('descr' => 'Real Power Phase L3', 'class' => 'power', 'measured' => 'device', 'oid' => 'pL3.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.14.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['pL4']['indexes'][0] = array('descr' => 'Real Power Phase L4', 'class' => 'power', 'measured' => 'device', 'oid' => 'pL4.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.15.0', 'min' => 0);
// Reactive Power
$config['mibs'][$mib]['sensor']['qL1']['indexes'][0] = array('descr' => 'Reactive Power Phase L1', 'class' => 'rpower', 'measured' => 'device', 'oid' => 'qL1.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.16.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['qL2']['indexes'][0] = array('descr' => 'Reactive Power Phase L2', 'class' => 'rpower', 'measured' => 'device', 'oid' => 'qL2.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.17.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['qL3']['indexes'][0] = array('descr' => 'Reactive Power Phase L3', 'class' => 'rpower', 'measured' => 'device', 'oid' => 'qL3.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.18.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['qL4']['indexes'][0] = array('descr' => 'Reactive Power Phase L4', 'class' => 'rpower', 'measured' => 'device', 'oid' => 'qL4.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.19.0', 'min' => 0);
// Apparent Power
$config['mibs'][$mib]['sensor']['sL1']['indexes'][0] = array('descr' => 'Apparent Power Phase L1', 'class' => 'apower', 'measured' => 'device', 'oid' => 'sL1.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.20.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['sL2']['indexes'][0] = array('descr' => 'Apparent Power Phase L2', 'class' => 'apower', 'measured' => 'device', 'oid' => 'sL2.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.21.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['sL3']['indexes'][0] = array('descr' => 'Apparent Power Phase L3', 'class' => 'apower', 'measured' => 'device', 'oid' => 'sL3.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.22.0', 'min' => 0);
$config['mibs'][$mib]['sensor']['sL4']['indexes'][0] = array('descr' => 'Apparent Power Phase L4', 'class' => 'apower', 'measured' => 'device', 'oid' => 'sL4.0', 'oid_num' => '.1.3.6.1.4.1.34278.1.23.0', 'min' => 0);

$mib = 'JETNEXUS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.38370';
$config['mibs'][$mib]['mib_dir'] = 'jetnexus';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'jetnexusVersionInfo.0'); // JETNEXUS-MIB::jetnexusVersionInfo.0 = STRING: "4.1.2 (Build 1644) "

// Juniper-UNI-ATM-MIB

$mib = 'Juniper-UNI-ATM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4874.2.2.8';
$config['mibs'][$mib]['mib_dir'] = 'junose';
$config['mibs'][$mib]['descr'] = '';

// JUNIPER-IFOTN-MIB

$mib = 'JUNIPER-IFOTN-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.3.70.1';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

$mib = 'JUNIPER-ALARM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.3.4';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['juniper-alarm-state'][1] = array('name' => 'other', 'event' => 'warning');
$config['mibs'][$mib]['states']['juniper-alarm-state'][2] = array('name' => 'off', 'event' => 'ok');
$config['mibs'][$mib]['states']['juniper-alarm-state'][3] = array('name' => 'on', 'event' => 'alert');

$config['mibs'][$mib]['states']['juniper-yellow-state'][1] = array('name' => 'other', 'event' => 'warning');
$config['mibs'][$mib]['states']['juniper-yellow-state'][2] = array('name' => 'off', 'event' => 'ok');
$config['mibs'][$mib]['states']['juniper-yellow-state'][3] = array('name' => 'on', 'event' => 'warning');

$mib = 'JUNIPER-DOM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.3.60.1';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

$mib = 'JUNIPER-COS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.3.15';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

$mib = 'JUNIPER-QOS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4874.2.2.57';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

$mib = 'JUNIPER-IFOPTICS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.3.71.1';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

$mib = 'PULSESECURE-PSG-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.12532';
$config['mibs'][$mib]['mib_dir'] = 'pulsesecure';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'productVersion.0', 'transformations' => array(array('action' => 'explode', 'index' => 'first'))); // PULSESECURE-PSG-MIB::productVersion.0 = STRING: "8.1R5 (build 38093)"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'productName.0', 'transformations' => array(array('action' => 'explode', 'delimeter' => ',', 'index' => 'last'))); // PULSESECURE-PSG-MIB::productName.0 = STRING: "Pulse Connect Secure,MAG-2600"
$config['mibs'][$mib]['processor']['iveCpuUtil'] = array('type' => 'static', 'descr' => 'CPU Utilization', 'oid' => 'iveCpuUtil.0', 'oid_num' => '.1.3.6.1.4.1.12532.10.0');      // PULSESECURE-PSG-MIB::iveCpuUtil.0 = Gauge32: 6
$config['mibs'][$mib]['mempool']['iveMemoryUtil'] = array('type' => 'static', 'descr' => 'Memory', 'oid_perc' => 'iveMemoryUtil.0', 'oid_perc_num' => '.1.3.6.1.4.1.12532.11.0'); // PULSESECURE-PSG-MIB::iveMemoryUtil.0 = Gauge32: 91

$mib = 'JUNIPER-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.3.1';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'jnxBoxSerialNo.0');

$type = 'jnxOperatingState';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'running', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'ready', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'reset', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'runningAtFullSpeed', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'down', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'standby', 'event' => 'ok');

$mib = 'JUNIPER-PING-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.3.7';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

$mib = 'JUNIPER-SRX5000-SPU-MONITORING-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.3.39.1.12.1';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

$mib = 'JUNIPER-VLAN-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.3.40.1.5.1';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';

$mib = 'JUNIPER-VPN-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2636.3.26';
$config['mibs'][$mib]['mib_dir'] = 'juniper';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['pseudowire']['oids'] = array(
  //'Uptime'      => array('oid' => 'jnxVpnPwTimeUp',           'oid_num' => '.1.3.6.1.4.1.2636.3.26.1.4.1.18', 'type' => 'timeticks'), // This is uptime since created
  'Uptime' => array('oid' => 'jnxVpnPwLastTransition', 'oid_num' => '.1.3.6.1.4.1.2636.3.26.1.4.1.20', 'type' => 'timeticks'),

  'OperStatus'   => array('oid' => 'jnxVpnPwStatus', 'oid_num' => '.1.3.6.1.4.1.2636.3.26.1.4.1.15'),
  'RemoteStatus' => array('oid' => 'jnxVpnPwRemoteSiteStatus', 'oid_num' => '.1.3.6.1.4.1.2636.3.26.1.4.1.17'),
  'LocalStatus'  => array('oid' => 'jnxVpnPwTunnelStatus', 'oid_num' => '.1.3.6.1.4.1.2636.3.26.1.4.1.16'),

  'InPkts'    => array('oid' => 'jnxVpnPwPacketsReceived', 'oid_num' => '.1.3.6.1.4.1.2636.3.26.1.4.1.23'),
  'OutPkts'   => array('oid' => 'jnxVpnPwPacketsSent', 'oid_num' => '.1.3.6.1.4.1.2636.3.26.1.4.1.21'),
  'InOctets'  => array('oid' => 'jnxVpnPwOctetsReceived', 'oid_num' => '.1.3.6.1.4.1.2636.3.26.1.4.1.24'),
  'OutOctets' => array('oid' => 'jnxVpnPwOctetsSent', 'oid_num' => '.1.3.6.1.4.1.2636.3.26.1.4.1.22'),
);
$config['mibs'][$mib]['pseudowire']['states'] = array(
  'unknown' => array('num' => '0', 'event' => 'warning'),
  'down'    => array('num' => '1', 'event' => 'alert'),
  'up'      => array('num' => '3', 'event' => 'ok'),
);

$mib = 'Juniper-System-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4874.2.2.2';
$config['mibs'][$mib]['mib_dir'] = 'junose';
$config['mibs'][$mib]['descr'] = '';

$mib = 'LCOS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2356.11';
$config['mibs'][$mib]['mib_dir'] = 'lancom';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'lcsFirmwareVersionTableEntrySerialNumber.eIfc'); // LCOS-MIB::lcsFirmwareVersionTableEntrySerialNumber.eIfc = STRING: 4003xxxxxxxxxxxx
$config['mibs'][$mib]['hardware'][] = array('oid' => 'lcsFirmwareVersionTableEntryModule.eIfc'); // LCOS-MIB::lcsFirmwareVersionTableEntryModule.eIfc = STRING: LANCOM L-321agn Wireless

/*
LIEBERT-GP-AGENT-MIB::lgpAgentIdentManufacturer.0 = STRING: Liebert Corporation
LIEBERT-GP-AGENT-MIB::lgpAgentIdentModel.0 = STRING: OpenComms Web Card
LIEBERT-GP-AGENT-MIB::lgpAgentIdentFirmwareVersion.0 = STRING: 2.110.0
LIEBERT-GP-AGENT-MIB::lgpAgentIdentSerialNumber.0 = STRING: 416701G204T2005******
LIEBERT-GP-AGENT-MIB::lgpAgentDeviceIndex.1 = INTEGER: 1
LIEBERT-GP-AGENT-MIB::lgpAgentDeviceId.1 = OID: LIEBERT-GP-REGISTRATION-MIB::lgpNX
LIEBERT-GP-AGENT-MIB::lgpAgentDeviceManufacturer.1 = STRING: Liebert Corporation
LIEBERT-GP-AGENT-MIB::lgpAgentDeviceModel.1 = STRING: Liebert NX
LIEBERT-GP-AGENT-MIB::lgpAgentDeviceFirmwareVersion.1 = STRING: I180R190M250
LIEBERT-GP-AGENT-MIB::lgpAgentDeviceUnitNumber.1 = INTEGER: 1
LIEBERT-GP-AGENT-MIB::lgpAgentReboot.0 = INTEGER: 0
*/

$mib = 'LIEBERT-GP-AGENT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.476.1.42.1.2.1';
$config['mibs'][$mib]['mib_dir'] = 'liebert';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'lgpAgentDeviceFirmwareVersion.1');
$config['mibs'][$mib]['serial'][] = array('oid' => 'lgpAgentDeviceSerialNumber.1');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'lgpAgentDeviceModel.1');

$mib = 'LIEBERT-GP-ENVIRONMENTAL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.476.1.42.1.5.1';
$config['mibs'][$mib]['mib_dir'] = 'liebert';
$config['mibs'][$mib]['descr'] = '';

$type = 'liebert-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'on', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'off', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'standby', 'event' => 'warning');

$mib = 'LIEBERT-GP-POWER-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.476.1.42.1.6.1';
$config['mibs'][$mib]['mib_dir'] = 'liebert';
$config['mibs'][$mib]['descr'] = '';

$mib = 'LIEBERT-GP-PDU-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.476.1.42.1.9.1';
$config['mibs'][$mib]['mib_dir'] = 'liebert';
$config['mibs'][$mib]['descr'] = '';

$type = 'lgpPduRcpEntryPwrState';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'unknown', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'on', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'off', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'off-pending-on-delay', 'event' => 'warning');

$type = 'lgpPduRcpEntryOperationCondition';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normalOperation', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'normalWithWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'normalWithAlarm', 'event' => 'alarm');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'abnormal', 'event' => 'alarm');

$mib = 'LSI-MegaRAID-SAS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'lsi';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][0] = array('name' => 'unconfigured-good', 'event' => 'warning');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][1] = array('name' => 'unconfigured-bad', 'event' => 'alert');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][2] = array('name' => 'hot-spare', 'event' => 'ok');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][16] = array('name' => 'offline', 'event' => 'alert');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][17] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][20] = array('name' => 'rebuild', 'event' => 'warning');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][24] = array('name' => 'online', 'event' => 'ok');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][32] = array('name' => 'copyback', 'event' => 'alert');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][64] = array('name' => 'system', 'event' => 'ok');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][128] = array('name' => 'unconfigured-shielded', 'event' => 'warning');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][130] = array('name' => 'hotspare-shielded', 'event' => 'ok');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-pd-state'][144] = array('name' => 'configured-shielded', 'event' => 'ok');

$config['mibs'][$mib]['states']['lsi-megaraid-sas-sensor-state'][1] = array('name' => 'invalid', 'event' => 'alert');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-sensor-state'][2] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-sensor-state'][3] = array('name' => 'critical', 'event' => 'alert');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-sensor-state'][4] = array('name' => 'nonCritical', 'event' => 'warning');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-sensor-state'][5] = array('name' => 'unrecoverable', 'event' => 'alert');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-sensor-state'][6] = array('name' => 'not-installed', 'event' => 'ok');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-sensor-state'][7] = array('name' => 'unknown', 'event' => 'warning');
$config['mibs'][$mib]['states']['lsi-megaraid-sas-sensor-state'][8] = array('name' => 'not-available', 'event' => 'alert');

$mib = 'MBG-SNMP-LT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.5597.3';
$config['mibs'][$mib]['mib_dir'] = 'meinberg';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'mbgLtFirmwareVersion.0', 'transformations' => array(array('action' => 'regex_replace', 'from' => '/^.+ V(\d[\d\.\-]+\w*).*/', 'to' => '$1'))); // MBG-SNMP-LT-MIB::mbgLtFirmwareVersion.0 = STRING: ID: lantime  GPS170 M3x  V5.28g
$config['mibs'][$mib]['hardware'][] = array('oid' => 'mbgLtRefClockType.0', 'transformations' => array(array('action' => 'regex_replace', 'from' => '/^.+: (.+)/', 'to' => '$1'))); // MBG-SNMP-LT-MIB::mbgLtRefClockType.0 = STRING: Clock Type: GPS170 M3x

//$config['mibs'][$mib]['processor']['dsCpuLoad1m'] = array('type' => 'static', 'descr' => 'System CPU', 'oid' => 'dsCpuLoad1m.0');

$mib = 'MBG-SNMP-LTNG-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.5597.30';
$config['mibs'][$mib]['mib_dir'] = 'meinberg';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'mbgLtNgSerialNumber.0'); // MBG-SNMP-LTNG-MIB::mbgLtNgSerialNumber.0 = STRING: 030111113130
$config['mibs'][$mib]['version'][] = array('oid' => 'mbgLtNgFirmwareVersion.0'); // MBG-SNMP-LTNG-MIB::mbgLtNgFirmwareVersion.0 = STRING: 6.20.014
$config['mibs'][$mib]['hardware'][] = array('oid' => 'mbgLtNgNtpRefclockName.0'); // MBG-SNMP-LTNG-MIB::mbgLtNgNtpRefclockName.0 = STRING: MRS

$config['mibs'][$mib]['sensor']['mbgLtNgSysTempCelsius']['indexes'][0] = array('descr'    => 'System Temperature',
                                                                               'class'    => 'temperature',
                                                                               'measured' => 'device',
                                                                               'scale'    => 1,
                                                                               'oid'      => 'mbgLtNgSysTempCelsius.0');

$mib = 'MERAKI-CLOUD-CONTROLLER-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.29671.1';
$config['mibs'][$mib]['mib_dir'] = 'cisco';
$config['mibs'][$mib]['descr'] = '';

$mib = 'MG-SNMP-UPS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'eaton';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'upsmgIdentSerialNumber.0'); // MG-SNMP-UPS-MIB::upsmgIdentSerialNumber.0 = STRING: "AQ1H01024"
$config['mibs'][$mib]['version'][] = array('oid' => 'upsmgIdentFirmwareVersion.0');

$type = 'mge-status-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'Yes', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'No', 'event' => 'ok');

$type = 'mge-status-inverter';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'Yes', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'No', 'event' => 'alert');

$mib = 'MIB-Dell-10892';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'dell';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'chassisServiceTagName.1');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'chassisModelName.1', 'transformations' => array(array('action' => 'prepend', 'string' => 'Dell ')));
$config['mibs'][$mib]['asset_tag'][] = array('oid' => 'chassisAssetTagName.1');

$mib = 'MIMOSA-NETWORKS-BFIVE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.43356.2.4.1';
$config['mibs'][$mib]['mib_dir'] = 'mimosa';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'mimosaSerialNumber.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'mimosaFirmwareVersion.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'mimosaDeviceName.0'); // Not sure, seems this is sysName

$config['mibs'][$mib]['sensor']['mimosaInternalTemp']['indexes'][0] = array('descr' => 'Internal Temperature', 'class' => 'temperature', 'measured' => 'device', 'scale' => 0.1, 'oid' => 'mimosaInternalTemp.0', 'oid_num' => '.1.3.6.1.4.1.43356.2.1.2.1.8.0');

$mib = 'MOTOROLA-PTP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'cambium';
$config['mibs'][$mib]['descr'] = '';

$mib = 'MS-SWITCH30-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3181.10.3';
$config['mibs'][$mib]['mib_dir'] = '';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'deviceSerNo.0'); // MS-SWITCH30-MIB::deviceSerNo.0 = STRING: "001146758"
$config['mibs'][$mib]['version'][] = array('oid' => 'agentFirmware.0'); // MS-SWITCH30-MIB::agentFirmware.0 = STRING: 5331V8.4.8.p
$config['mibs'][$mib]['hardware'][] = array('oid' => 'deviceArtNo.0'); // MS-SWITCH30-MIB::deviceArtNo = STRING: "MS450860M-G5"

$mib = 'MSERIES-ALARM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.30826.1.1';
$config['mibs'][$mib]['mib_dir'] = 'smartoptics';
$config['mibs'][$mib]['descr'] = '';

$mib = 'MSERIES-ENVMON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.30826.1.4';
$config['mibs'][$mib]['mib_dir'] = 'smartoptics';
$config['mibs'][$mib]['descr'] = '';

$mib = 'MSERIES-PORT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.30826.1.3';
$config['mibs'][$mib]['mib_dir'] = 'smartoptics';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['mseries-port-status-state'][1] = array('name' => 'idle', 'event' => 'exclude');
$config['mibs'][$mib]['states']['mseries-port-status-state'][2] = array('name' => 'down', 'event' => 'alert');
$config['mibs'][$mib]['states']['mseries-port-status-state'][3] = array('name' => 'up', 'event' => 'ok');
$config['mibs'][$mib]['states']['mseries-port-status-state'][4] = array('name' => 'high', 'event' => 'warning');
$config['mibs'][$mib]['states']['mseries-port-status-state'][5] = array('name' => 'low', 'event' => 'warning');
$config['mibs'][$mib]['states']['mseries-port-status-state'][6] = array('name' => 'eyeSafety', 'event' => 'alert');
$config['mibs'][$mib]['states']['mseries-port-status-state'][7] = array('name' => 'cd', 'event' => 'alert');
$config['mibs'][$mib]['states']['mseries-port-status-state'][8] = array('name' => 'ncd', 'event' => 'alert');

$mib = 'NAS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = ''; // d-link, qnap. Totally different!
$config['mibs'][$mib]['descr'] = '';

// Note. Qnap HdStatus
$config['mibs'][$mib]['states']['nas-mib-hd-state'][0] = array('name' => 'ready', 'event' => 'ok');
$config['mibs'][$mib]['states']['nas-mib-hd-state'][-5] = array('name' => 'noDisk', 'event' => 'exclude');
$config['mibs'][$mib]['states']['nas-mib-hd-state'][-6] = array('name' => 'invalid', 'event' => 'alert');
$config['mibs'][$mib]['states']['nas-mib-hd-state'][-9] = array('name' => 'rwError', 'event' => 'alert');
$config['mibs'][$mib]['states']['nas-mib-hd-state'][-4] = array('name' => 'unknown', 'event' => 'warning');

$mib = 'NBS-CMMC-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.629.200';
$config['mibs'][$mib]['mib_dir'] = 'mrv';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'nbsCmmcSysFwVers.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'nbsCmmcChassisModel.1'); // NBS-CMMC-MIB::nbsCmmcChassisModel.1 = STRING: NC316BU-16/15AC

$mib = 'NETAPP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.6.3.789';
$config['mibs'][$mib]['mib_dir'] = 'netapp';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'productSerialNum.0'); // NETAPP-MIB::productSerialNum.0 = XXXXXXXXXXX
$config['mibs'][$mib]['hardware'][] = array('oid' => 'productModel.0'); // NETAPP-MIB::productModel.0 = FAS3020
$config['mibs'][$mib]['features'][] = array('oid' => 'productCPUArch.0'); // NETAPP-MIB::productCPUArch.0 = x86

// Processors
$config['mibs'][$mib]['processor']['cpuBusyTimePerCent'] = array(
  'type'      => 'static',
  'oid_count' => 'cpuCount.0',
  'oid'       => 'cpuBusyTimePerCent.0',
  'oid_num'   => '.1.3.6.1.4.1.789.1.2.1.3.0'
);
// Port table translations
$config['mibs'][$mib]['ports']['oids'] = array(
  'ifDescr'       => array('oid' => 'netifDescr'),
  'ifMtu'         => array('oid' => 'netportMtu'),
  'ifAdminStatus' => array('oid' => 'netportUpAdmin', 'rewrite' => array('true'  => 'up',
                                                                         'false' => 'down')),
  'ifOperStatus'  => array('oid' => 'netportLinkState', 'rewrite' => array('undef' => 'notPresent',
                                                                           'off'   => 'lowerLayerDown')), // ?
  'ifType'        => array('oid' => 'netportType', 'rewrite' => array('undef'    => 'other',
                                                                      'physical' => 'ethernetCsmacd',
                                                                      'if-group' => 'ieee8023adLag', // ?
                                                                      'vlan'     => 'propVirtual')), // ? l2vlan
  'ifHighSpeed'   => array('oid' => 'netportSpeed'),
  'ifVlan'        => array('oid' => 'netportVlanTag'),
  'ifPhysAddress' => array('oid' => 'netportMac'),
  'ifDuplex'      => array('oid' => 'netportDuplexOper', 'rewrite' => array('undef' => 'unknown',
                                                                            'auto'  => 'unknown',
                                                                            'half'  => 'halfDuplex',
                                                                            'full'  => 'fullDuplex')),
);

// Netbotz

$mib = 'NETBOTZV2-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'apc';
$config['mibs'][$mib]['descr'] = '';

$mib = 'NETBOTZ410-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'apc';
$config['mibs'][$mib]['descr'] = '';

$mib = 'NETSCREEN-RESOURCE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3224.16.0';
$config['mibs'][$mib]['mib_dir'] = 'netscreen';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['processor']['nsResCpuLast5Min'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'nsResCpuLast5Min.0', 'oid_num' => '.1.3.6.1.4.1.3224.16.1.3.0');
$config['mibs'][$mib]['mempool']['nsResMem'] = array('type'     => 'static', 'descr' => 'Memory', 'scale' => 1,
                                                     'oid_used' => 'nsResMemAllocate.0', 'oid_used_num' => '.1.3.6.1.4.1.3224.16.2.1.0', // NETSCREEN-RESOURCE-MIB::nsResMemAllocate.0 = INTEGER: 81629456
                                                     'oid_free' => 'nsResMemLeft.0', 'oid_free_num' => '.1.3.6.1.4.1.3224.16.2.2.0', // NETSCREEN-RESOURCE-MIB::nsResMemLeft.0 = INTEGER: 9869952
);

$mib = 'NETSWITCH-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'hpSwitchOsVersion.0');

$mib = 'NOKIA-IPSO-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.94.1.21.1';
$config['mibs'][$mib]['mib_dir'] = 'checkpoint';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'ipsoChassisSerialNumber.0');

$config['mibs'][$mib]['states']['ipso-temperature-state'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['ipso-temperature-state'][2] = array('name' => 'overTemperature', 'event' => 'alert');

$config['mibs'][$mib]['states']['ipso-sensor-state'][1] = array('name' => 'running', 'event' => 'ok');
$config['mibs'][$mib]['states']['ipso-sensor-state'][2] = array('name' => 'notRunning', 'event' => 'alert');

$mib = 'NIMBLE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.37447';
$config['mibs'][$mib]['mib_dir'] = 'nimble';
$config['mibs'][$mib]['descr'] = '';

$mib = 'NS-ROOT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.5951';
$config['mibs'][$mib]['mib_dir'] = 'citrix';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'sysHardwareSerialNumber.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'sysHardwareVersionDesc.0'); // NS-ROOT-MIB::sysHardwareVersionDesc.0 = STRING: "7000 v1 6*EZ+2*EM"
$config['mibs'][$mib]['processor']['sslCryptoUtilization'] = array('type' => 'static', 'descr' => 'Crypto Engine', 'oid' => 'sslCryptoUtilization.0', 'oid_num' => '1.3.6.1.4.1.5951.4.1.1.47.365.0');
$config['mibs'][$mib]['mempool']['nsResourceGroup'] = array('type'      => 'static', 'descr' => 'Memory', 'scale' => 1024 * 1024,
                                                            'oid_total' => 'memSizeMB.0', 'oid_total_num' => '.1.3.6.1.4.1.5951.4.1.1.41.4.0', // NS-ROOT-MIB::memSizeMB.0 = INTEGER: 815
                                                            'oid_perc'  => 'resMemUsage.0', 'oid_perc_num' => '.1.3.6.1.4.1.5951.4.1.1.41.2.0', // NS-ROOT-MIB::resMemUsage.0 = Gauge32: 29
);

$config['mibs'][$mib]['states']['netscaler-state'][0] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['netscaler-state'][1] = array('name' => 'failed', 'event' => 'alert');

$config['mibs'][$mib]['states']['netscaler-ha-mode'][0] = array('name' => 'standalone', 'event' => 'ok');
$config['mibs'][$mib]['states']['netscaler-ha-mode'][1] = array('name' => 'primary', 'event' => 'ok');
$config['mibs'][$mib]['states']['netscaler-ha-mode'][2] = array('name' => 'secondary', 'event' => 'ok');
$config['mibs'][$mib]['states']['netscaler-ha-mode'][3] = array('name' => 'unknown', 'event' => 'warning');

$config['mibs'][$mib]['states']['netscaler-ha-state'][0] = array('name' => 'unknown', 'event' => 'alert');
$config['mibs'][$mib]['states']['netscaler-ha-state'][1] = array('name' => 'init', 'event' => 'warning');
$config['mibs'][$mib]['states']['netscaler-ha-state'][2] = array('name' => 'down', 'event' => 'alert');
$config['mibs'][$mib]['states']['netscaler-ha-state'][3] = array('name' => 'up', 'event' => 'ok');
$config['mibs'][$mib]['states']['netscaler-ha-state'][4] = array('name' => 'partialFail', 'event' => 'alert');
$config['mibs'][$mib]['states']['netscaler-ha-state'][5] = array('name' => 'monitorFail', 'event' => 'alert');
$config['mibs'][$mib]['states']['netscaler-ha-state'][6] = array('name' => 'monitorOk', 'event' => 'ok');
$config['mibs'][$mib]['states']['netscaler-ha-state'][7] = array('name' => 'completeFail', 'event' => 'alert');
$config['mibs'][$mib]['states']['netscaler-ha-state'][8] = array('name' => 'dumb', 'event' => 'warning');
$config['mibs'][$mib]['states']['netscaler-ha-state'][9] = array('name' => 'disabled', 'event' => 'warning');
$config['mibs'][$mib]['states']['netscaler-ha-state'][10] = array('name' => 'partialFailSsl', 'event' => 'alert');
$config['mibs'][$mib]['states']['netscaler-ha-state'][11] = array('name' => 'routemonitorFail', 'event' => 'alert');

$mib = 'NSCRTV-ROOT';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = '';
$config['mibs'][$mib]['descr'] = '';

$mib = 'OA-SFP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6926.1.18';
$config['mibs'][$mib]['mib_dir'] = 'mrv';
$config['mibs'][$mib]['descr'] = '';

$mib = 'OADWDM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'mrv';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'oaLdCardBackplaneSN.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'oaLdSoftVersString.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'oaLdCardBackplanePN.0');

$config['mibs'][$mib]['states']['oadwdm-fan-state'][1] = array('name' => 'none', 'event' => 'exclude');
$config['mibs'][$mib]['states']['oadwdm-fan-state'][2] = array('name' => 'active', 'event' => 'ok');
$config['mibs'][$mib]['states']['oadwdm-fan-state'][3] = array('name' => 'notActive', 'event' => 'warning');
$config['mibs'][$mib]['states']['oadwdm-fan-state'][4] = array('name' => 'fail', 'event' => 'alert');

$config['mibs'][$mib]['states']['oadwdm-powersupply-state'][1] = array('name' => 'none', 'event' => 'exclude');
$config['mibs'][$mib]['states']['oadwdm-powersupply-state'][2] = array('name' => 'active', 'event' => 'ok');
$config['mibs'][$mib]['states']['oadwdm-powersupply-state'][3] = array('name' => 'notActive', 'event' => 'warning');
$config['mibs'][$mib]['states']['oadwdm-powersupply-state'][4] = array('name' => 'fail', 'event' => 'alert');

$mib = 'OG-STATUS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25049.16';
$config['mibs'][$mib]['mib_dir'] = 'opengear';
$config['mibs'][$mib]['descr'] = '';

$mib = 'OMNITRON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.7342.3';
$config['mibs'][$mib]['mib_dir'] = 'omnitron';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'serialnum.1.1');   // OMNITRON-MIB::serialnum.1.1 = STRING: 00716236
//$config['mibs'][$mib]['version'][]    = array('oid' => 'softrev.1.1');     // OMNITRON-MIB::softrev.1.1 = INTEGER: 52
$config['mibs'][$mib]['hardware'][] = array('oid' => 'chassisname.1.1'); // OMNITRON-MIB::chassisname.1.1 = STRING: GM4-PoE

$mib = 'OMNITRON-POE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.7342.15';
$config['mibs'][$mib]['mib_dir'] = 'omnitron';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['sensor']['ostPoeGlobalCfgTotalPwr']['indexes'][0] = array('descr' => 'Total PoE Power', 'class' => 'power', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.7342.15.1.2.0', 'min' => 0);

$type = 'ostPoePortPseStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'notApplicable', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'pdNormal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'pdOverCurrent', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'pdBrownOut', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'pdInsufficientPower', 'event' => 'alert');

$mib = 'OPENBSD-SENSORS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.30155.2';
$config['mibs'][$mib]['mib_dir'] = 'bsd';
$config['mibs'][$mib]['descr'] = '';

$mib = 'P8510-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = '';
$config['mibs'][$mib]['descr'] = '';

$mib = 'PACKETFLUX-MIB'; // This MIB is missing from mibs/. As I understand it, it does not actually exist. (http://manuals.packetflux.com/index.php?page=sitemonitor-configuration-objects)
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.32050.2.1';
$config['mibs'][$mib]['mib_dir'] = 'packetflux';
$config['mibs'][$mib]['descr'] = '';

$mib = 'PACKETFLUX-STANDBYPOWER';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.32050.2.2';
$config['mibs'][$mib]['mib_dir'] = 'packetflux';
$config['mibs'][$mib]['descr'] = '';

$mib = 'KYOCERA-Private-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'kyocera';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid_num' => '.1.3.6.1.4.1.1347.43.5.1.1.28.1'); // SNMPv2-SMI::enterprises.1347.43.5.1.1.28.1 = STRING: "QUV9600664"
$config['mibs'][$mib]['version'][] = array('oid' => 'kcprtFirmwareVersion.1.1'); // KYOCERA-Private-MIB::kcprtFirmwareVersion.1.1 = STRING: "2H9_2F00.002.002"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'kcprtGeneralModelName.1'); // KYOCERA-Private-MIB::kcprtGeneralModelName.1 = STRING: "FS-1028MFP"

// ^ Some useful OIDs at http://www.kyoceramita.be/en/index/kyoware_solutions/system_management/kyocount_3_01.-contextmargin-65897-files-62084-File.cpsdownload.tmp/Models.xml

// PAN-COMMON-MIB

$mib = 'PAN-COMMON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25461.1.1.3';
$config['mibs'][$mib]['mib_dir'] = 'paloalto';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'panSysSerialNumber.0'); // PAN-COMMON-MIB::panSysSerialNumber.0 = STRING: 0004C10xxxx
$config['mibs'][$mib]['version'][] = array('oid' => 'panSysSwVersion.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'panChassisType.0');
$config['mibs'][$mib]['features'][] = array('oid' => 'panSysHwVersion.0');

/*
PAN-COMMON-MIB::panSysSwVersion.0 = STRING: 3.1.10
PAN-COMMON-MIB::panSysHwVersion.0 = STRING: 2.0
PAN-COMMON-MIB::panSysTimeZoneOffset.0 = INTEGER: 32400
PAN-COMMON-MIB::panSysDaylightSaving.0 = INTEGER: 0
PAN-COMMON-MIB::panSysVpnClientVersion.0 = STRING: 0.0.0
PAN-COMMON-MIB::panSysAppVersion.0 = STRING: 430-2169
PAN-COMMON-MIB::panSysAvVersion.0 = STRING: 1151-1607
PAN-COMMON-MIB::panSysThreatVersion.0 = STRING: 405-2020
*/

$mib = 'EMD-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.13742.8';
$config['mibs'][$mib]['mib_dir'] = 'raritan';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'firmwareVersion.0'); // EMD-MIB::firmwareVersion.0 = STRING: 3.2.30.5-43188
$config['mibs'][$mib]['hardware'][] = array('oid' => 'model.0'); // EMD-MIB::model.0 = STRING: EMX2-888

$type = 'emdSensorStateEnumeration';
$config['mibs'][$mib]['states'][$type][-1] = array('name' => 'unavailable', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'open', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'closed', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'belowLowerCritical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'belowLowerWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'aboveUpperWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'aboveUpperCritical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'on', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'off', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'detected', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'notDetected', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'alarmed', 'event' => 'alert');

$mib = 'PDU-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.13742.4';
$config['mibs'][$mib]['mib_dir'] = 'raritan';
$config['mibs'][$mib]['descr'] = 'This mib describes the SNMP functions of the Dominion PX Power Distribution Unit by Raritan Computer.';
$config['mibs'][$mib]['serial'][] = array('oid' => 'serialNumber.0'); // PDU-MIB::serialNumber.0 = STRING: AEQ0900002
$config['mibs'][$mib]['version'][] = array('oid' => 'firmwareVersion.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'objectName.0');

$mib = 'PDU2-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'raritan';
$config['mibs'][$mib]['descr'] = 'This MIB describes the SNMP functions of the Dominion PX G2 Power Distribution Unit by Raritan Computer.';
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.13742.6';
$config['mibs'][$mib]['serial'][] = array('oid' => 'pduSerialNumber.1'); // PDU2-MIB::pduSerialNumber.1 = STRING: QRN4850046
$config['mibs'][$mib]['version'][] = array('oid' => 'boardFirmwareVersion.1.mainController.1'); // PDU2-MIB::boardFirmwareVersion.1.mainController.1 = STRING: 3.1.0.5-42165
$config['mibs'][$mib]['hardware'][] = array('oid' => 'pduModel.1'); // PDU2-MIB::pduModel.1 = STRING: PX2-5486

$type = 'pdu2-sensorstate'; // SensorStateEnumeration
$config['mibs'][$mib]['states'][$type][-1] = array('name' => 'unavailable', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'open', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'closed', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'belowLowerCritical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'belowLowerWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'aboveUpperWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'aboveUpperCritical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'on', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'off', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'detected', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'notDetected', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'alarmed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][12] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][13] = array('name' => 'marginal', 'event' => 'warning'); // not known
$config['mibs'][$mib]['states'][$type][14] = array('name' => 'fail', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][15] = array('name' => 'yes', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][16] = array('name' => 'no', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][17] = array('name' => 'standby', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][18] = array('name' => 'one', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][19] = array('name' => 'two', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][20] = array('name' => 'inSync', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][21] = array('name' => 'outOfSync', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][22] = array('name' => 'i1OpenFault', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][23] = array('name' => 'i1ShortFault', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][24] = array('name' => 'i2OpenFault', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][25] = array('name' => 'i2ShortFault', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][26] = array('name' => 'fault', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][27] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][28] = array('name' => 'critical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][29] = array('name' => 'selfTest', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][30] = array('name' => 'nonRedundant', 'event' => 'ok');

$mib = 'PEAKFLOW-SP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.9694.1.4';
$config['mibs'][$mib]['mib_dir'] = 'arbor';
$config['mibs'][$mib]['descr'] = '';
// deviceCpuLoadAvg5min.0 = INTEGER: 11
$config['mibs'][$mib]['processor']['deviceCpuLoadAvg5min'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'deviceCpuLoadAvg5min.0', 'oid_num' => '.1.3.6.1.4.1.9694.1.4.2.1.2.0');

$mib = 'POSEIDON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'hwgroup';
$config['mibs'][$mib]['descr'] = '';

$mib = 'Papouch-SMI';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.18248';
$config['mibs'][$mib]['mib_dir'] = 'papouch';
$config['mibs'][$mib]['descr'] = '';

$mib = 'PowerNet-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'apc';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'upsAdvIdentSerialNumber.0');        // UPS
$config['mibs'][$mib]['serial'][] = array('oid' => 'atsIdentSerialNumber.0');           // ATS
$config['mibs'][$mib]['serial'][] = array('oid' => 'rPDUIdentSerialNumber.0');          // PDU
$config['mibs'][$mib]['serial'][] = array('oid' => 'rPDU2IdentSerialNumber.0');         // PDU
$config['mibs'][$mib]['serial'][] = array('oid' => 'sPDUIdentSerialNumber.0');          // Masterswitch/AP9606
$config['mibs'][$mib]['serial'][] = array('oid' => 'emsIdentSerialNumber.0');           // NetBotz 200
$config['mibs'][$mib]['serial'][] = array('oid' => 'airIRRCUnitIdentSerialNumber.0');   // In-Row Chiller
$config['mibs'][$mib]['serial'][] = array('oid' => 'airPASerialNumber.0');              // A/C
$config['mibs'][$mib]['serial'][] = array('oid' => 'xPDUIdentSerialNumber.0');          // PDU
$config['mibs'][$mib]['serial'][] = array('oid' => 'xATSIdentSerialNumber.0');          // ATS
$config['mibs'][$mib]['serial'][] = array('oid' => 'isxModularPduIdentSerialNumber.0'); // Modular PDU

$config['mibs'][$mib]['states']['powernet-status-state'][1] = array('name' => 'fail', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-status-state'][2] = array('name' => 'ok', 'event' => 'ok');

$config['mibs'][$mib]['states']['powernet-sync-state'][1] = array('name' => 'inSync', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-sync-state'][2] = array('name' => 'outOfSync', 'event' => 'alert');

$config['mibs'][$mib]['states']['powernet-mupscontact-state'][1] = array('name' => 'unknown', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-mupscontact-state'][2] = array('name' => 'noFault', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-mupscontact-state'][3] = array('name' => 'fault', 'event' => 'alert');

$config['mibs'][$mib]['states']['powernet-rpdusupply1-state'][1] = array('name' => 'powerSupplyOneOk', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-rpdusupply1-state'][2] = array('name' => 'powerSupplyOneFailed', 'event' => 'alert');

$config['mibs'][$mib]['states']['powernet-rpdusupply2-state'][1] = array('name' => 'powerSupplyTwoOk', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-rpdusupply2-state'][2] = array('name' => 'powerSupplyTwoFailed', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-rpdusupply2-state'][3] = array('name' => 'powerSupplyTwoNotPresent', 'event' => 'exclude');

$config['mibs'][$mib]['states']['powernet-rpdu2supply-state'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-rpdu2supply-state'][2] = array('name' => 'alarm', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-rpdu2supply-state'][3] = array('name' => 'notInstalled', 'event' => 'exclude');

$config['mibs'][$mib]['states']['powernet-rpdu2supplyalarm-state'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-rpdu2supplyalarm-state'][2] = array('name' => 'alarm', 'event' => 'alert');

$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][1] = array('name' => 'unknown', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][2] = array('name' => 'onLine', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][3] = array('name' => 'onBattery', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][4] = array('name' => 'onSmartBoost', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][5] = array('name' => 'timedSleeping', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][6] = array('name' => 'softwareBypass', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][7] = array('name' => 'off', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][8] = array('name' => 'rebooting', '             event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][9] = array('name' => 'switchedBypass', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][10] = array('name' => 'hardwareFailureBypass', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][11] = array('name' => 'sleepingUntilPowerReturn', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][12] = array('name' => 'onSmartTrim', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][13] = array('name' => 'ecoMode', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][14] = array('name' => 'hotStandby', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][15] = array('name' => 'onBatteryTest', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][16] = array('name' => 'emergencyStaticBypass', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][17] = array('name' => 'staticBypassStandby', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][18] = array('name' => 'powerSavingMode', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][19] = array('name' => 'spotMode', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsbasicoutput-state'][20] = array('name' => 'eConversion', 'event' => 'ok');

$config['mibs'][$mib]['states']['powernet-upsadvinputfail-state'][1] = array('name' => 'noTransfer', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-upsadvinputfail-state'][2] = array('name' => 'highLineVoltage', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsadvinputfail-state'][3] = array('name' => 'brownout', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsadvinputfail-state'][4] = array('name' => 'blackout', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsadvinputfail-state'][5] = array('name' => 'smallMomentarySag', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsadvinputfail-state'][6] = array('name' => 'deepMomentarySag', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsadvinputfail-state'][7] = array('name' => 'smallMomentarySpike', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsadvinputfail-state'][8] = array('name' => 'largeMomentarySpike', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upsadvinputfail-state'][9] = array('name' => 'selfTest', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-upsadvinputfail-state'][10] = array('name' => 'rateOfVoltageChange', 'event' => 'warning');

$config['mibs'][$mib]['states']['powernet-upsbatteryreplace-state'][1] = array('name' => 'noBatteryNeedsReplacing', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-upsbatteryreplace-state'][2] = array('name' => 'batteryNeedsReplacing', 'event' => 'alert');

$config['mibs'][$mib]['states']['powernet-upstest-state'][1] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-upstest-state'][2] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-upstest-state'][3] = array('name' => 'invalidTest', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-upstest-state'][4] = array('name' => 'testInProgress', 'event' => 'ok');

$config['mibs'][$mib]['states']['powernet-cooling-input-state'][0] = array('name' => 'Open', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-cooling-input-state'][1] = array('name' => 'Closed', 'event' => 'alert');

$config['mibs'][$mib]['states']['powernet-cooling-output-state'][0] = array('name' => 'Abnormal', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-cooling-output-state'][1] = array('name' => 'Normal', 'event' => 'ok');

$config['mibs'][$mib]['states']['powernet-cooling-powersource-state'][0] = array('name' => 'Primary', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-cooling-powersource-state'][1] = array('name' => 'Secondary', 'event' => 'warning');

$config['mibs'][$mib]['states']['powernet-cooling-unittype-state'][0] = array('name' => 'Undefined', 'event' => 'exclude');
$config['mibs'][$mib]['states']['powernet-cooling-unittype-state'][1] = array('name' => 'Standard', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-cooling-unittype-state'][2] = array('name' => 'HighTemp', 'event' => 'ok');

$config['mibs'][$mib]['states']['powernet-cooling-opmode-state'][0] = array('name' => 'Standby', 'event' => 'exclude');
$config['mibs'][$mib]['states']['powernet-cooling-opmode-state'][1] = array('name' => 'On', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-cooling-opmode-state'][2] = array('name' => 'Idle', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-cooling-opmode-state'][3] = array('name' => 'Maintenance', 'event' => 'warning');

$config['mibs'][$mib]['states']['powernet-cooling-flowcontrol-state'][0] = array('name' => 'Under', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-cooling-flowcontrol-state'][1] = array('name' => 'Okay', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-cooling-flowcontrol-state'][2] = array('name' => 'Over', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-cooling-flowcontrol-state'][3] = array('name' => 'NA', 'event' => 'exclude');

$config['mibs'][$mib]['states']['powernet-door-lock-state'][1] = array('name' => 'unlocked', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-door-lock-state'][2] = array('name' => 'locked', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-door-lock-state'][3] = array('name' => 'notInstalled', 'event' => 'exclude');
$config['mibs'][$mib]['states']['powernet-door-lock-state'][4] = array('name' => 'disconnected', 'event' => 'alert');

$config['mibs'][$mib]['states']['powernet-door-state'][1] = array('name' => 'open', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-door-state'][2] = array('name' => 'closed', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-door-state'][3] = array('name' => 'notInstalled', 'event' => 'exclude');
$config['mibs'][$mib]['states']['powernet-door-state'][4] = array('name' => 'disconnected', 'event' => 'alert');

$config['mibs'][$mib]['states']['powernet-door-alarm-state'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-door-alarm-state'][2] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-door-alarm-state'][3] = array('name' => 'critical', 'event' => 'alert');
$config['mibs'][$mib]['states']['powernet-door-alarm-state'][4] = array('name' => 'notInstalled', 'event' => 'exclude');

$config['mibs'][$mib]['states']['powernet-accesspx-state'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['powernet-accesspx-state'][2] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states']['powernet-accesspx-state'][3] = array('name' => 'critical', 'event' => 'alert');

$mib = 'MARVELL-POE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.89.108';
$config['mibs'][$mib]['mib_dir'] = 'radlan';
$config['mibs'][$mib]['descr'] = '';

$mib = 'RADLAN-HWENVIROMENT';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.89.83';
$config['mibs'][$mib]['mib_dir'] = 'radlan';
$config['mibs'][$mib]['descr'] = '';

$type = 'radlan-hwenvironment-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'critical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'shutdown', 'event' => 'ignore');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'notPresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'notFunctioning', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'restore', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'notAvailable', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'backingUp', 'event' => 'ok');

$mib = 'RADLAN-DEVICEPARAMS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.89.2';
$config['mibs'][$mib]['mib_dir'] = 'radlan';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['features'][] = array('oid' => 'rndBaseBootVersion.00');
$config['mibs'][$mib]['version'][] = array('oid' => 'rndBrgVersion.0'); // RADLAN-DEVICEPARAMS-MIB::rndBrgVersion.0 = STRING: 1.1.40

/*
RADLAN-DEVICEPARAMS-MIB::rndBrgFeatures.0 = Hex-STRING: 10 02 90 02 01 00 00 04 00 60 00 00 00 00 00 00 00 00 00 00
RADLAN-DEVICEPARAMS-MIB::rndIpHostManagementSupported.0 = INTEGER: false(2)
RADLAN-DEVICEPARAMS-MIB::rndManagedTime.0 = STRING: 081002
RADLAN-DEVICEPARAMS-MIB::rndManagedDate.0 = STRING: 230916
RADLAN-DEVICEPARAMS-MIB::rndBaseBootVersion.0 = STRING: 0.0.0.3
RADLAN-DEVICEPARAMS-MIB::genGroupHWVersion.0 = STRING: 01.03
RADLAN-DEVICEPARAMS-MIB::rndBasePhysicalAddress.0 = STRING: a8:f9:4b:7c:b:40
RADLAN-DEVICEPARAMS-MIB::rndUnitNumber.1 = INTEGER: 1
RADLAN-DEVICEPARAMS-MIB::rndActiveSoftwareFile.1 = INTEGER: image1(1)
RADLAN-DEVICEPARAMS-MIB::rndActiveSoftwareFileAfterReset.1 = INTEGER: image1(1)
RADLAN-DEVICEPARAMS-MIB::rlResetStatus.0 = BITS: 00
RADLAN-DEVICEPARAMS-MIB::rndBackupConfigurationEnabled.0 = INTEGER: false(2)
RADLAN-DEVICEPARAMS-MIB::rndStackUnitNumber.1 = INTEGER: 1
RADLAN-DEVICEPARAMS-MIB::rndImage1Name.1 = STRING: image-1
RADLAN-DEVICEPARAMS-MIB::rndImage2Name.1 = STRING: image-2
RADLAN-DEVICEPARAMS-MIB::rndImage1Version.1 = STRING: 1.1.40
RADLAN-DEVICEPARAMS-MIB::rndImage2Version.1 = STRING: 1.1.40
RADLAN-DEVICEPARAMS-MIB::rndImage1Date.1 = STRING:  20-Jul-2015
RADLAN-DEVICEPARAMS-MIB::rndImage2Date.1 = STRING:  20-Jul-2015
RADLAN-DEVICEPARAMS-MIB::rndImage1Time.1 = STRING:  14:44:31
RADLAN-DEVICEPARAMS-MIB::rndImage2Time.1 = STRING:  14:44:31
*/

// Pls note, we have additional pseudo mib named RADLAN-Physicaldescription-old-MIB,
// see in discovery sensors
$mib = 'RADLAN-Physicaldescription-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.89.53';
$config['mibs'][$mib]['mib_dir'] = 'radlan';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'rlPhdUnitGenParamSerialNum.1'); // RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamSerialNum.1 = STRING: ES2B000166
$config['mibs'][$mib]['version'][] = array('oid' => 'rlPhdUnitGenParamSoftwareVersion.1'); // RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamSoftwareVersion.1 = STRING: 1.1.40
$config['mibs'][$mib]['hardware'][] = array('oid' => 'rlPhdUnitGenParamModelName.1'); // RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamModelName.1 = STRING: MES1124MB

/*
RADLAN-Physicaldescription-MIB::rlPhdNumberOfUnits.0 = INTEGER: 1
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamStackUnit.1 = INTEGER: 1
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamFirmwareVersion.1 = STRING: 0.0.0.3
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamHardwareVersion.1 = STRING: 01.03
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamAssetTag.1 = STRING:
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamServiceTag.1 = STRING: 24 + 4 combo ports
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamSoftwareDate.1 = STRING:  20-Jul-2015
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamFirmwareDate.1 = STRING:  23-Feb-2011
RADLAN-Physicaldescription-MIB::rlPhdUnitGenParamManufacturer.1 = STRING:
*/

$type = 'RlEnvMonState';
$config['mibs'][$mib]['states'][$type] = $config['mibs']['RADLAN-HWENVIROMENT']['states']['radlan-hwenvironment-state'];

$mib = 'RADLAN-rndMng';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.89.1';
$config['mibs'][$mib]['mib_dir'] = 'radlan';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['processor']['rlCpuUtilDuringLast5Minutes'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'rlCpuUtilDuringLast5Minutes.0', 'oid_num' => '.1.3.6.1.4.1.89.1.9.0');

$mib = 'RADWARE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'radware';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'rndBrgVersion.0'); // RADWARE-MIB::rndBrgVersion.0 = STRING: "6.09.01"

// RBN-CPU-METER-MIB

$mib = 'RBN-CPU-METER-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2352.2.6';
$config['mibs'][$mib]['mib_dir'] = 'redback';
$config['mibs'][$mib]['descr'] = '';
// rbnCpuMeterFiveMinuteAvg.0
$config['mibs'][$mib]['processor']['rbnCpuMeterFiveMinuteAvg'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'rbnCpuMeterFiveMinuteAvg.0', 'oid_num' => '.1.3.6.1.4.1.2352.2.6.1.3.0');

// RBN-ENVMON-MIB

$mib = 'RBN-ENVMON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2352.2.4';
$config['mibs'][$mib]['mib_dir'] = 'redback';
$config['mibs'][$mib]['descr'] = '';

// RBN-MEMORY-MIB

$mib = 'RBN-MEMORY-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2352.2.16';
$config['mibs'][$mib]['mib_dir'] = 'redback';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['mempool']['RbnMemoryEntry'] = array('type'     => 'static', 'descr' => 'Memory', 'scale' => 1024,
                                                           'oid_free' => 'rbnMemoryFreeKBytes.1', 'oid_free_num' => '.1.3.6.1.4.1.2352.2.16.1.2.1.3.1', // RBN-MEMORY-MIB::rbnMemoryFreeKBytes.1
                                                           'oid_used' => 'rbnMemoryKBytesInUse.1', 'oid_used_num' => '.1.3.6.1.4.1.2352.2.16.1.2.1.4.1', // RBN-MEMORY-MIB::rbnMemoryKBytesInUse.1
);
// FIXME ^ because 'mempool' definition currently doesn't support indexes, it gets indexed as index 0.

// RITTAL-CMC-III-MIB

$mib = 'RITTAL-CMC-III-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2606.7';
$config['mibs'][$mib]['mib_dir'] = 'rittal';
$config['mibs'][$mib]['descr'] = '';

// RITTAL-CMC-TC-MIB

$mib = 'RITTAL-CMC-TC-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.2606.4';
$config['mibs'][$mib]['mib_dir'] = 'rittal';
$config['mibs'][$mib]['descr'] = '';

$type = 'rittal-cmc-tc-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'notAvail', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'lost', 'event' => 'error');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'changed', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'off', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'on', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'tooLow', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'tooHigh', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'error', 'event' => 'error');

// ROOMALERT24E-MIB

$mib = 'ROOMALERT24E-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'avtech';
$config['mibs'][$mib]['descr'] = '';

// ROOMALERT12E-MIB

$mib = 'ROOMALERT12E-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'avtech';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['sensor']['internal-sen-1']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.1.6.0', # digital-sen1-6
                                                                        'class'     => 'temperature',
                                                                        'measured'  => 'device',
                                                                        'scale'     => 0.01,
                                                                        'oid'       => 'internal-sen-1.0',
                                                                        'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.1.1.0');
$config['mibs'][$mib]['sensor']['internal-sen-3']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.1.6.0', # digital-sen1-6
                                                                        'class'     => 'humidity',
                                                                        'measured'  => 'device',
                                                                        'scale'     => 0.01, // FIXME untested, might be 1
                                                                        'oid'       => 'internal-sen-3.0',
                                                                        'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.1.3.0');
$config['mibs'][$mib]['sensor']['digital-sen1-1']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.2.6.0', # digital-sen1-6
                                                                        'class'     => 'temperature',
                                                                        'measured'  => 'device',
                                                                        'scale'     => 0.01,
                                                                        'oid'       => 'digital-sen1-1.0',
                                                                        'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.2.1.0');
$config['mibs'][$mib]['sensor']['digital-sen1-3']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.2.6.0', # digital-sen1-6
                                                                        'class'     => 'humidity',
                                                                        'measured'  => 'device',
                                                                        'scale'     => 0.01, // FIXME untested, might be 1
                                                                        'oid'       => 'digital-sen1-3.0',
                                                                        'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.2.3.0');
$config['mibs'][$mib]['sensor']['digital-sen2-1']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.3.6.0', # digital-sen2-6
                                                                        'class'     => 'temperature',
                                                                        'measured'  => 'device',
                                                                        'scale'     => 0.01,
                                                                        'oid'       => 'digital-sen2-1.0',
                                                                        'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.3.1.0');
$config['mibs'][$mib]['sensor']['digital-sen2-3']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.3.6.0', # digital-sen2-6
                                                                        'class'     => 'humidity',
                                                                        'measured'  => 'device',
                                                                        'scale'     => 0.01, // FIXME untested, might be 1
                                                                        'oid'       => 'digital-sen2-3.0',
                                                                        'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.3.3.0');
$config['mibs'][$mib]['sensor']['digital-sen3-1']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.4.6.0', # digital-sen3-6
                                                                        'class'     => 'temperature',
                                                                        'measured'  => 'device',
                                                                        'scale'     => 0.01,
                                                                        'oid'       => 'digital-sen3-1.0',
                                                                        'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.4.1.0');
$config['mibs'][$mib]['sensor']['digital-sen3-3']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.4.6.0', # digital-sen3-6
                                                                        'class'     => 'humidity',
                                                                        'measured'  => 'device',
                                                                        'scale'     => 0.01, // FIXME untested, might be 1
                                                                        'oid'       => 'digital-sen3-3.0',
                                                                        'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.4.3.0');

$config['mibs'][$mib]['states']['switch-state'][0] = array('name' => 'open', 'event' => 'ok');
$config['mibs'][$mib]['states']['switch-state'][1] = array('name' => 'closed', 'event' => 'ok');

$config['mibs'][$mib]['status']['switch-sen1-1']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.5.2.0', # switch-sen1-2
                                                                       'type'      => 'switch-state',
                                                                       'oid'       => 'switch-sen1-1.0',
                                                                       'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.5.1.0');
$config['mibs'][$mib]['status']['switch-sen2-1']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.6.2.0', # switch-sen2-2
                                                                       'type'      => 'switch-state',
                                                                       'oid'       => 'switch-sen2-1.0',
                                                                       'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.6.1.0');
$config['mibs'][$mib]['status']['switch-sen3-1']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.7.2.0', # switch-sen3-2
                                                                       'type'      => 'switch-state',
                                                                       'oid'       => 'switch-sen3-1.0',
                                                                       'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.7.1.0');
$config['mibs'][$mib]['status']['switch-sen4-1']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.8.2.0', # switch-sen4-2
                                                                       'type'      => 'switch-state',
                                                                       'oid'       => 'switch-sen4-1.0',
                                                                       'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.8.1.0');

$config['mibs'][$mib]['states']['relay-state'][0] = array('name' => 'off', 'event' => 'ok');
$config['mibs'][$mib]['states']['relay-state'][1] = array('name' => 'on', 'event' => 'ok');

$config['mibs'][$mib]['status']['relay-1']['indexes'][0] = array('oid_descr' => '.1.3.6.1.4.1.20916.1.10.1.10.2.0', # relay-2.0
                                                                 'type'      => 'relay-state',
                                                                 'oid'       => 'relay-1.0',
                                                                 'oid_num'   => '.1.3.6.1.4.1.20916.1.10.1.10.1.0');

// ROOMALERT4E-MIB

$mib = 'ROOMALERT4E-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'avtech';
$config['mibs'][$mib]['descr'] = '';

// RMS-MIB

$mib = 'RMS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'knuerr';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'systemVersion.0', 'transformations' => array(array('action' => 'regex_replace', 'from' => '/.*Version (\d[\w\.\-]+).*/', 'to' => '$1'))); // RMS Version 1.4b3 H2-14 (1999/03/16-15:43)
$config['mibs'][$mib]['sensor']['tempEntry']['tables'][] = array(
  'table'     => 'tempEntry',
  'class'     => 'temperature',
  //'descr'                 => 'Temperature',
  'oid'       => 'tempValue',
  'oid_descr' => 'tempDescr',
  //'oid_num'               => '',
  'min'       => 0,
  'max'       => 655,
  'scale'     => 1
);
$config['mibs'][$mib]['sensor']['humidEntry']['tables'][] = array(
  'table'     => 'humidEntry',
  'class'     => 'humidity',
  //'descr'                 => 'Humidity',
  'oid'       => 'humidValue',
  'oid_descr' => 'humidDescr',
  //'oid_num'               => '',
  'min'       => 0,
  'max'       => 101,
  'scale'     => 1
);
$config['mibs'][$mib]['sensor']['mainsEntry']['tables'][] = array(
  'table'     => 'mainsEntry',
  'class'     => 'voltage',
  //'descr'                 => 'Voltage',
  'oid'       => 'mainsValue',
  'oid_descr' => 'mainsDescr',
  //'oid_num'               => '',
  'min'       => 0,
  'max'       => 255,
  'scale'     => 1
);

$mib = 'RPS-SC200-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = array('.1.3.6.1.4.1.1918.2.13.1',
  '.1.3.6.1.4.1.1918');
$config['mibs'][$mib]['mib_dir'] = 'eaton';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'system-Serial-Number.0');

$mib = 'RUCKUS-DEVICE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25053.1.1.4.1';
$config['mibs'][$mib]['mib_dir'] = 'ruckus';
$config['mibs'][$mib]['descr'] = '';

$mib = 'RUCKUS-HWINFO-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25053.1.1.2.1';
$config['mibs'][$mib]['mib_dir'] = 'ruckus';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'ruckusHwInfoSerialNumber.0'); // RUCKUS-HWINFO-MIB::ruckusHwInfoSerialNumber.0 = STRING: <removed>
$config['mibs'][$mib]['hardware'][] = array('oid' => 'ruckusHwInfoModelNumber.0'); // RUCKUS-HWINFO-MIB::ruckusHwInfoModelNumber.0 = STRING: ZF7982

$mib = 'RUCKUS-RADIO-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25053.1.1.12.1';
$config['mibs'][$mib]['mib_dir'] = 'ruckus';
$config['mibs'][$mib]['descr'] = '';

$mib = 'RUCKUS-SWINFO-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25053.1.1.3.1';
$config['mibs'][$mib]['mib_dir'] = 'ruckus';
$config['mibs'][$mib]['descr'] = '';

$mib = 'RUCKUS-WLAN-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25053.1.1.6.1';
$config['mibs'][$mib]['mib_dir'] = 'ruckus';
$config['mibs'][$mib]['descr'] = '';

$mib = 'SAF-ALARM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.7571.100.118';
$config['mibs'][$mib]['mib_dir'] = 'saf';
$config['mibs'][$mib]['descr'] = '';

$mib = 'SAF-ENTERPRISE';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.7571';
$config['mibs'][$mib]['mib_dir'] = 'saf';
$config['mibs'][$mib]['descr'] = '';

$mib = 'SAF-IPADDONS';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'saf';
$config['mibs'][$mib]['descr'] = '';

$mib = 'SENAO-ENTERPRISE-INDOOR-AP-CB-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14125';
$config['mibs'][$mib]['mib_dir'] = 'senao';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'entSN.0');

// ServersCheck

$mib = 'ServersCheck';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'serverscheck';
$config['mibs'][$mib]['descr'] = '';

// SFA-INFO

$mib = 'SFA-INFO';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'ddn';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['sfa-disk-state'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['sfa-disk-state'][2] = array('name' => 'failed', 'event' => 'alert');
$config['mibs'][$mib]['states']['sfa-disk-state'][3] = array('name' => 'predictedfailure', 'event' => 'warning');
$config['mibs'][$mib]['states']['sfa-disk-state'][4] = array('name' => 'unkown', 'event' => 'exclude');

$config['mibs'][$mib]['states']['sfa-power-state'][1] = array('name' => 'healthy', 'event' => 'ok');
$config['mibs'][$mib]['states']['sfa-power-state'][2] = array('name' => 'failure', 'event' => 'alert');

$config['mibs'][$mib]['states']['sfa-fan-state'][1] = array('name' => 'healthy', 'event' => 'ok');
$config['mibs'][$mib]['states']['sfa-fan-state'][2] = array('name' => 'failure', 'event' => 'alert');

$config['mibs'][$mib]['states']['sfa-temp-state'][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['sfa-temp-state'][2] = array('name' => 'warning', 'event' => 'warning');
$config['mibs'][$mib]['states']['sfa-temp-state'][3] = array('name' => 'critical', 'event' => 'alert');

// SMARTNODE-MIB

$mib = 'SMARTNODE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1768.100';
$config['mibs'][$mib]['mib_dir'] = 'patton';
$config['mibs'][$mib]['descr'] = '';

// SNWL-SSLVPN-MIB

$mib = 'SNWL-SSLVPN-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.8741.6';
$config['mibs'][$mib]['mib_dir'] = 'sonicwall';
$config['mibs'][$mib]['descr'] = '';

// SNWL-COMMON-MIB

$mib = 'SNWL-COMMON-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.8741.2.1';
$config['mibs'][$mib]['mib_dir'] = 'sonicwall';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'snwlSysSerialNumber.0'); // SNWL-COMMON-MIB::snwlSysSerialNumber.0 = STRING: 0017C54525DC
$config['mibs'][$mib]['hardware'][] = array('oid' => 'snwlSysModel.0'); // SNWL-COMMON-MIB::snwlSysModel.0 = STRING: TZ 210
$config['mibs'][$mib]['version'][] = array('oid' => 'snwlSysFirmwareVersion.0', 'transformations' => array(array('action' => 'ireplace', 'from' => array('SonicOS ', 'Enhanced '), 'to' => ''))); // SNWL-COMMON-MIB::snwlSysFirmwareVersion.0 = STRING: SonicOS Enhanced 5.8.1.9-58o

// SNWL-COMMON-MIB::snwlSysROMVersion.0 = STRING: 5.0.2.11
// NOTE: snwlSysROMVersion is simply the version contained in ROM when the device was created, and will never change. It is not the version run by the OS.

// SOCOMECUPS-MIB

$mib = 'SOCOMECUPS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'socomec';
$config['mibs'][$mib]['descr'] = '';

// SONICWALL-FIREWALL-IP-STATISTICS-MIB

$mib = 'SONICWALL-FIREWALL-IP-STATISTICS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.8741.1.3';
$config['mibs'][$mib]['mib_dir'] = 'sonicwall';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['processor']['sonicCurrentCPUUtil'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'sonicCurrentCPUUtil.0', 'oid_num' => '.1.3.6.1.4.1.8741.1.3.1.3.0'); // SONICWALL-FIREWALL-IP-STATISTICS-MIB::sonicCurrentCPUUtil.0 = Wrong Type (should be Gauge32 or Unsigned32): Counter32: 2
$config['mibs'][$mib]['mempool']['sonicCurrentRAMUtil'] = array('type' => 'static', 'descr' => 'Memory', 'oid_perc' => 'sonicCurrentRAMUtil.0', 'oid_perc_num' => '.1.3.6.1.4.1.8741.1.3.1.4.0'); // SONICWALL-FIREWALL-IP-STATISTICS-MIB::sonicCurrentRAMUtil.0 = Wrong Type (should be Gauge32 or Unsigned32): Counter32: 98

// SPAGENT-MIB

$mib = 'SPAGENT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'akcp';
$config['mibs'][$mib]['descr'] = '';

$type = 'spagent-state';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'noStatus', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'highCritical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'lowCritical', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'sensorError', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'relayOn', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'relayOff', 'event' => 'ok');

$mib = 'STATISTICS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'hp';
$config['mibs'][$mib]['descr'] = '';
// STATISTICS-MIB::hpSwitchCpuStat.0 = INTEGER: 10
$config['mibs'][$mib]['processor']['hpSwitchCpuStat'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'hpSwitchCpuStat.0', 'oid_num' => '.1.3.6.1.4.1.11.2.14.11.5.1.9.6.1.0');

$mib = 'STE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.21796.4.1';
$config['mibs'][$mib]['mib_dir'] = 'hwgroup';
$config['mibs'][$mib]['descr'] = '';

$type = 'ste-SensorState';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'invalid', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'outofrangelo', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'outofrangehi', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'alarmlo', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'alarmhi', 'event' => 'alert');

$mib = 'STE2-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.21796.4.9';
$config['mibs'][$mib]['mib_dir'] = 'hwgroup';
$config['mibs'][$mib]['descr'] = '';

$type = 'ste2-SensorState';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'invalid', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'outofrangelo', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'outofrangehi', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'alarmlo', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'alarmhi', 'event' => 'alert');

$mib = 'STEELHEAD-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.17163.1.1';
$config['mibs'][$mib]['mib_dir'] = 'riverbed';
$config['mibs'][$mib]['descr'] = '';

$type = 'steelhead-system-state';
$config['mibs'][$mib]['states'][$type][10000] = array('name' => 'healthy', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][30000] = array('name' => 'degraded', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][31000] = array('name' => 'admissionControl', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][50000] = array('name' => 'critical', 'event' => 'alert');

$type = 'steelhead-service-state';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'none', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'unmanaged', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'running', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'sentCom1', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'sentTerm1', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'sentTerm2', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'sentTerm3', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'pending', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'stopped', 'event' => 'alert');

$mib = 'SUB10SYSTEMS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.39003';
$config['mibs'][$mib]['mib_dir'] = 'fastback';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'sub10UnitLclHWSerialNumber.0'); // SUB10SYSTEMS-MIB::sub10UnitLclHWSerialNumber.0 = STRING: "S1000653B201504504"
$config['mibs'][$mib]['version'][] = array('oid' => 'sub10UnitLclFirmwareVersion.0'); // SUB10SYSTEMS-MIB::sub10UnitLclFirmwareVersion.0 = STRING: "02.01.03.16"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'sub10UnitLclUnitType.0'); // SUB10SYSTEMS-MIB::sub10UnitLclUnitType.0 = INTEGER: v1000ROWB(9)
$config['mibs'][$mib]['features'][] = array('oid' => 'sub10UnitLclTerminalType.0'); // SUB10SYSTEMS-MIB::sub10UnitLclTerminalType.0 = INTEGER: terminalB(1)

/*
SUB10SYSTEMS-MIB::sub10UnitLclDescription.0 = STRING: Sub10 Systems - Wireless Ethernet Bridge Liberator V1000
*/

$config['mibs'][$mib]['sensor']['sub10UnitLclMWUTemperature']['indexes'][0] = array('descr'    => 'Internal Temperature',
                                                                                    'class'    => 'temperature',
                                                                                    'measured' => 'device',
                                                                                    'scale'    => 1,
                                                                                    'oid'      => 'sub10UnitLclMWUTemperature.0',
                                                                                    'oid_num'  => '.1.3.6.1.4.1.39003.3.1.1.13.0');

$mib = 'SUN-ILOM-CONTROL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.42.2.175.102';
$config['mibs'][$mib]['mib_dir'] = 'oracle';
$config['mibs'][$mib]['descr'] = '';

$mib = 'SUN-PLATFORM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.42.2.70.101';
$config['mibs'][$mib]['mib_dir'] = 'oracle';
$config['mibs'][$mib]['descr'] = '';

$mib = 'SUPERMICRO-HEALTH-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.10876.2.1';
$config['mibs'][$mib]['mib_dir'] = 'supermicro';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['supermicro-state'][0] = array('name' => 'Good', 'event' => 'ok');
$config['mibs'][$mib]['states']['supermicro-state'][1] = array('name' => 'Bad', 'event' => 'alert');

$mib = 'SW-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1588.3.1.3';
$config['mibs'][$mib]['mib_dir'] = 'brocade';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'swFirmwareVersion.0', 'transformations' => array(array('action' => 'ltrim', 'characters' => 'v')));
$config['mibs'][$mib]['processor']['swCpuUsage'] = array('type' => 'static', 'descr' => 'CPU', 'oid' => 'swCpuUsage.0', 'oid_num' => '.1.3.6.1.4.1.1588.2.1.1.1.26.1.0', 'rename_rrd' => 'nos-0');
$config['mibs'][$mib]['mempool']['swMemUsage'] = array('type'     => 'static', 'descr' => 'Memory',
                                                       'total'    => 2147483648, // Hardcoded for VDX switches that has 2GB of RAM includes all the current models.
                                                       'oid_perc' => 'swMemUsage.0', 'oid_perc_num' => '.1.3.6.1.4.1.1588.2.1.1.1.26.6.0', // SW-MIB::swMemUsage.0 = INTEGER: 40
);

// FIXME. Incorrect OID walked, should be swSensorStatus
$config['mibs'][$mib]['states']['sw-mib'][1] = array('name' => 'normal', 'event' => 'ok');

$config['mibs'][$mib]['states']['swSensorStatus'][1] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states']['swSensorStatus'][2] = array('name' => 'faulty', 'event' => 'alert');
$config['mibs'][$mib]['states']['swSensorStatus'][3] = array('name' => 'below-min', 'event' => 'warning');
$config['mibs'][$mib]['states']['swSensorStatus'][4] = array('name' => 'nominal', 'event' => 'ok');
$config['mibs'][$mib]['states']['swSensorStatus'][5] = array('name' => 'above-max', 'event' => 'warning');
$config['mibs'][$mib]['states']['swSensorStatus'][6] = array('name' => 'absent', 'event' => 'exclude');

// SYNOLOGY-DISK-MIB

$mib = 'SYNOLOGY-DISK-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6574.2';
$config['mibs'][$mib]['mib_dir'] = 'synology';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['states']['synology-disk-state'][1] = array('name' => 'Normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['synology-disk-state'][2] = array('name' => 'Initialized', 'event' => 'warning');
$config['mibs'][$mib]['states']['synology-disk-state'][3] = array('name' => 'NotInitialized', 'event' => 'warning');
$config['mibs'][$mib]['states']['synology-disk-state'][4] = array('name' => 'SystemPartitionFailed', 'event' => 'alert');
$config['mibs'][$mib]['states']['synology-disk-state'][5] = array('name' => 'Crashed', 'event' => 'alert');

// SYNOLOGY-SYSTEM-MIB

$mib = 'SYNOLOGY-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6574.1';
$config['mibs'][$mib]['mib_dir'] = 'synology';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'serialNumber.0'); // SYNOLOGY-SYSTEM-MIB::serialNumber.0 = STRING: "13A0LNN000123"
$config['mibs'][$mib]['version'][] = array('oid' => 'version.0', 'transformations' => array(array('action' => 'replace', 'from' => 'DSM', 'to' => ''))); // SYNOLOGY-SYSTEM-MIB::version.0 = STRING: "DSM 5.0-4458"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'modelName.0'); // SYNOLOGY-SYSTEM-MIB::modelName.0 = STRING: "RT1900ac"

$config['mibs'][$mib]['sensor']['temperature']['indexes'][0] = array('descr'            => 'System Temperature', 'class' => 'temperature', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.6574.1.2.0',
                                                                     'rename_rrd_array' => array('type' => 'synology-system-mib', 'index' => 'temperature.0')); // SYNOLOGY-SYSTEM-MIB::temperature.0 = INTEGER: 31

$config['mibs'][$mib]['status']['systemStatus']['indexes'][0] = array('descr' => 'System Status', 'type' => 'synology-status-state', 'oid_num' => '.1.3.6.1.4.1.6574.1.1.0');   // SYNOLOGY-SYSTEM-MIB::systemStatus.0 = INTEGER: 1
$config['mibs'][$mib]['status']['powerStatus']['indexes'][0] = array('descr' => 'Power Status', 'type' => 'synology-status-state', 'oid_num' => '.1.3.6.1.4.1.6574.1.3.0');   // SYNOLOGY-SYSTEM-MIB::powerStatus.0 = INTEGER: 1
$config['mibs'][$mib]['status']['systemFanStatus']['indexes'][0] = array('descr' => 'System Fan Status', 'type' => 'synology-status-state', 'oid_num' => '.1.3.6.1.4.1.6574.1.4.1.0'); // SYNOLOGY-SYSTEM-MIB::systemFanStatus.0 = INTEGER: 1
$config['mibs'][$mib]['status']['cpuFanStatus']['indexes'][0] = array('descr' => 'CPU Fan Status', 'type' => 'synology-status-state', 'oid_num' => '.1.3.6.1.4.1.6574.1.4.2.0'); // SYNOLOGY-SYSTEM-MIB::cpuFanStatus.0 = INTEGER: 1

$config['mibs'][$mib]['states']['synology-status-state'][1] = array('name' => 'Normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['synology-status-state'][2] = array('name' => 'Failed', 'event' => 'alert');

/*
Currently not monitored: upgradeAvailable OBJECT-TYPE
    "This oid is for checking whether there is a latest DSM can be upgraded.
         Available(1): There is version ready for download.
         Unavailable(2): The DSM is latest version.
         Connecting(3): Checking for the latest DSM.
         Disconnected(4): Failed to connect to server.
         Others(5): If DSM is upgrading or downloading, the status will show others."
*/

// Sentry3-MIB

$mib = 'Sentry3-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1718.3';
$config['mibs'][$mib]['mib_dir'] = 'sentry';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'towerProductSN.1'); // Sentry3-MIB::towerProductSN.1 = STRING: "ABEF0001561"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'towerModelNumber.1'); // Sentry3-MIB::towerModelNumber = STRING: "CW-24V2-L30M"

$mib = 'Sentry4-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1718.4';
$config['mibs'][$mib]['mib_dir'] = 'sentry';
$config['mibs'][$mib]['descr'] = '';

$mib = 'TERACOM-MIB';
$config['mibs'][$mib]['enable'] = 1;
#$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.6.3.1';
$config['mibs'][$mib]['mib_dir'] = 'teracom';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'deviceID.0'); // TERACOM-MIB::deviceID.0 = Hex-STRING: D8 80 39 28 BE 87
$config['mibs'][$mib]['version'][] = array('oid' => 'version.0'); // TERACOM-MIB::version.0 = STRING: "v1.14"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'name.0', 'transformations' => array(array('action' => 'replace', 'from' => 'SNMP Agent', 'to' => 'I/O Controller'))); // TERACOM-MIB::name.0 = STRING: "TCW240B SNMP Agent"

/*
TERACOM-MIB::dateTime.0 = Hex-STRING: 07 DF 07 16 10 37 2E 00
TERACOM-MIB::hostName.0 = STRING: "TCW240B        "
*/

$config['mibs'][$mib]['states']['teracom-digitalin-state'][0] = array('name' => 'closed', 'event' => 'ok');
$config['mibs'][$mib]['states']['teracom-digitalin-state'][1] = array('name' => 'open', 'event' => 'ok');

$config['mibs'][$mib]['states']['teracom-relay-state'][0] = array('name' => 'off', 'event' => 'ok');
$config['mibs'][$mib]['states']['teracom-relay-state'][1] = array('name' => 'on', 'event' => 'ok');

$config['mibs'][$mib]['states']['teracom-alarm-state'][0] = array('name' => 'noErr', 'event' => 'ok');
$config['mibs'][$mib]['states']['teracom-alarm-state'][1] = array('name' => 'owErr', 'event' => 'alert');
$config['mibs'][$mib]['states']['teracom-alarm-state'][2] = array('name' => 'hwErr', 'event' => 'alert');

$mib = 'TERADICI-PCOIP-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25071';
$config['mibs'][$mib]['mib_dir'] = 'teradici';
$config['mibs'][$mib]['descr'] = '';

$mib = 'TERADICI-PCOIPv2-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25071';
$config['mibs'][$mib]['mib_dir'] = 'teradici';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'pcoipGenDevicesSerialNumber.1'); // TERADICI-PCOIPv2-MIB::pcoipGenDevicesSerialNumber.1 = ""
$config['mibs'][$mib]['version'][] = array('oid' => 'pcoipGenDevicesFirmwareVersion.1'); // TERADICI-PCOIPv2-MIB::pcoipGenDevicesFirmwareVersion.1 = STRING: "4.0.2"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'pcoipGenDevicesPartNumber.1'); // TERADICI-PCOIPv2-MIB::pcoipGenDevicesPartNumber.1 = STRING: "TERA1100 revision 1.0 (128 MB)"
$config['mibs'][$mib]['features'][] = array('oid' => 'pcoipGenDevicesFwPartNumber.1'); // TERADICI-PCOIPv2-MIB::pcoipGenDevicesFwPartNumber.1 = STRING: "Samsung 22 Rev 2 Display"

/*
TERADICI-PCOIPv2-MIB::pcoipGenDevicesSessionNumber.1 = INTEGER: 1
TERADICI-PCOIPv2-MIB::pcoipGenDevicesName.1 = STRING: "pcoip-portal-0012fbeb931e"
TERADICI-PCOIPv2-MIB::pcoipGenDevicesDescription.1 = ""
TERADICI-PCOIPv2-MIB::pcoipGenDevicesGenericTag.1 = ""
TERADICI-PCOIPv2-MIB::pcoipGenDevicesHardwareVersion.1 = ""
TERADICI-PCOIPv2-MIB::pcoipGenDevicesUniqueID.1 = STRING: "00-12-FB-EB-93-1E"
TERADICI-PCOIPv2-MIB::pcoipGenDevicesMAC.1 = STRING: "00-12-FB-EB-93-1E"
TERADICI-PCOIPv2-MIB::pcoipGenDevicesUptime.1 = Counter64: 18813
*/

// TPT-TPA-HARDWARE-MIB

$mib = 'TPT-TPA-HARDWARE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.10734.3.3.2.3';
$config['mibs'][$mib]['mib_dir'] = 'trendmicro';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'hw-serialNumber.0');

// TPT-HEALTH-MIB

$mib = 'TPT-HEALTH-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.10734.3.3.2.13';
$config['mibs'][$mib]['mib_dir'] = 'trendmicro';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['sensor']['healthTempEntry']['tables'][] = array(
  'table'               => 'healthTempEntry',
  'class'               => 'temperature',
  'oid'                 => 'healthTempValue',
  'oid_descr'           => 'healthTempChannel',
  //'oid_num'               => '',
  'oid_limit_high_warn' => 'healthTempMajor',
  'oid_limit_high'      => 'healthTempCritical',
  'min'                 => 0,
  'scale'               => 1
);
$config['mibs'][$mib]['sensor']['healthFanEntry']['tables'][] = array(
  'table'              => 'healthFanEntry',
  'class'              => 'fanspeed',
  'oid'                => 'healthFanValue',
  'oid_descr'          => 'healthFanChannel',
  //'oid_num'               => '',
  'oid_limit_low_warn' => 'healthFanMajor',
  'oid_limit_low'      => 'healthFanCritical',
  'min'                => 0,
  'scale'              => 1
);
$config['mibs'][$mib]['sensor']['healthVoltageEntry']['tables'][] = array(
  'table'                => 'healthVoltageEntry',
  'class'                => 'voltage',
  'oid'                  => 'healthVoltageValue',
  'oid_descr'            => 'healthVoltageChannel',
  //'oid_num'               => '',
  // this sensor used +- delta from nominal,
  // ie low/high warn: healthVoltageNominal +- healthVoltageMajor
  'oid_limit_nominal'    => 'healthVoltageNominal',
  'oid_limit_delta_warn' => 'healthVoltageMajor',
  'oid_limit_delta'      => 'healthVoltageCritical',
  'limit_scale'          => 0.001,
  'min'                  => 0,
  'scale'                => 0.001
);

// TPT-RESOURCE-MIB

$mib = 'TPT-RESOURCE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.10734.3.3.2.5';
$config['mibs'][$mib]['mib_dir'] = 'trendmicro';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'resourceVersion.0', 'transformations' => array(array('action' => 'regex_replace', 'from' => '/.* Build ([\d\.\-]+).*/', 'to' => '$1'))); // Rev 5.  Build 3.8.3.4494, May 26 2016, 13:13:24
$config['mibs'][$mib]['processor']['resourceHPCPUBusyPercent'] = array('type'                => 'static',
                                                                       'descr'               => 'Host Processor',
                                                                       'oid'                 => 'resourceHPCPUBusyPercent.0',
                                                                       'oid_num'             => '.1.3.6.1.4.1.10734.3.3.2.5.4.1.0',
                                                                       'oid_limit_high_warn' => 'resourceHPCPUThresholdMaj.0',
                                                                       'oid_limit_high'      => 'resourceHPCPUThresholdCrit.0');
$config['mibs'][$mib]['processor']['resourceNPCPUBusyPercentA'] = array('type' => 'static', 'descr' => 'Total Utilization of XLR A', 'oid' => 'resourceNPCPUBusyPercentA.0', 'oid_num' => '.1.3.6.1.4.1.10734.3.3.2.5.4.6.0');
$config['mibs'][$mib]['processor']['resourceNPCPUBusyPercentB'] = array('type' => 'static', 'descr' => 'Total Utilization of XLR B', 'oid' => 'resourceNPCPUBusyPercentB.0', 'oid_num' => '.1.3.6.1.4.1.10734.3.3.2.5.4.10.0');
$config['mibs'][$mib]['processor']['resourceNPCPUBusyPercentC'] = array('type' => 'static', 'descr' => 'Total Utilization of XLR C', 'oid' => 'resourceNPCPUBusyPercentC.0', 'oid_num' => '.1.3.6.1.4.1.10734.3.3.2.5.4.14.0');

$config['mibs'][$mib]['mempool']['resourceHPMemoryObjs'] = array('type'                => 'static', 'descr' => 'Host Memory',
                                                                 'oid_total'           => 'resourceHPMemoryTotal.0',         // TPT-RESOURCE-MIB::resourceHPMemoryTotal.0 = Gauge32: 3083776704
                                                                 'oid_perc'            => 'resourceHPMemoryInUsePercent.0',  // TPT-RESOURCE-MIB::resourceHPMemoryInUsePercent.0 = INTEGER: 41
                                                                 'oid_limit_high_warn' => 'resourceHPMemoryThresholdMaj.0',  // TPT-RESOURCE-MIB::resourceHPMemoryThresholdMaj.0 = INTEGER: 90
                                                                 'oid_limit_high'      => 'resourceHPMemoryThresholdCrit.0', // TPT-RESOURCE-MIB::resourceHPMemoryThresholdCrit.0 = INTEGER: 95
);

$config['mibs'][$mib]['sensor']['resourceChassisTempObjs']['indexes'][0] = array('descr'               => 'Chassis Temperature',
                                                                                 'class'               => 'temperature',
                                                                                 'measured'            => 'device',
                                                                                 'oid'                 => 'resourceChassisTempDegreesC.0',
                                                                                 'oid_num'             => '.1.3.6.1.4.1.10734.3.3.2.5.5.1.0',
                                                                                 'oid_limit_high_warn' => 'resourceChassisTempThresholdMaj.0',
                                                                                 'oid_limit_high'      => 'resourceChassisTempThresholdCrit.0',
                                                                                 'min'                 => 0,
                                                                                 'scale'               => 1,
  // Skip sensor if exist $valid['sensor']['temperature']['TPT-HEALTH-MIB-healthTempEntry']
                                                                                 'skip_if_valid_exist' => 'temperature->TPT-HEALTH-MIB-healthTempEntry');

$config['mibs'][$mib]['status']['resourcePowerSupplyEntry']['tables'][] = array(
  'table'    => 'resourcePowerSupplyEntry',
  'type'     => 'tptResourceState',
  'descr'    => 'Power Supply',
  'oid'      => 'powerSupplyStatus',
  'oid_num'  => '.1.3.6.1.4.1.10734.3.3.2.5.9.4.1.2',
  'measured' => 'powersupply'
);

$type = 'tptResourceState';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'red', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'yellow', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'green', 'event' => 'ok');

// TIMETRA-CHASSIS-MIB

$mib = 'TIMETRA-CHASSIS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6527.1.1.3.2';
$config['mibs'][$mib]['mib_dir'] = 'aos';
$config['mibs'][$mib]['descr'] = 'Alcatel-Lucent SROS Chassis MIB Module';

$config['mibs'][$mib]['states']['timetra-chassis-state'][1] = array('name' => 'deviceStateUnknown', 'event' => 'exclude');
$config['mibs'][$mib]['states']['timetra-chassis-state'][2] = array('name' => 'deviceNotEquipped', 'event' => 'exclude');
$config['mibs'][$mib]['states']['timetra-chassis-state'][3] = array('name' => 'deviceStateOk', 'event' => 'ok');
$config['mibs'][$mib]['states']['timetra-chassis-state'][4] = array('name' => 'deviceStateFailed', 'event' => 'alert');
$config['mibs'][$mib]['states']['timetra-chassis-state'][5] = array('name' => 'deviceStateOutOfService', 'event' => 'exclude');

// TIMETRA-PORT-MIB

$mib = 'TIMETRA-PORT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6527.1.1.3.25';
$config['mibs'][$mib]['mib_dir'] = 'aos';
$config['mibs'][$mib]['descr'] = 'Alcatel-Lucent SROS Port MIB Module';


// TIMETRA-SYSTEM-MIB

$mib = 'TIMETRA-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6527.1.1.3.1';
$config['mibs'][$mib]['mib_dir'] = 'aos';
$config['mibs'][$mib]['descr'] = 'Alcatel-Lucent SROS System MIB Module';
$config['mibs'][$mib]['processor']['sgiCpuUsage'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'sgiCpuUsage.0', 'oid_num' => '.1.3.6.1.4.1.6527.3.1.2.1.1.1.0'); // sgiCpuUsage.0 = Gauge32: 42 percent

$mib = 'TRANGO-APEX-GIGE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'trango';
$config['mibs'][$mib]['descr'] = '';

$mib = 'TRANGO-APEX-MODEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'trango';
$config['mibs'][$mib]['descr'] = '';

$mib = 'TRANGO-APEX-RF-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'trango';
$config['mibs'][$mib]['descr'] = '';

$mib = 'TRANGO-APEX-SYS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'trango';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'sysSerialID.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'sysFWVer.0');
$config['mibs'][$mib]['features'][] = array('oid' => 'sysOSVer.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'sysModel.0');

$mib = 'TRAPEZE-NETWORKS-ROOT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14525';
$config['mibs'][$mib]['mib_dir'] = 'trapeze';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'trpzSerialNumber.0'); // TRAPEZE-NETWORKS-ROOT-MIB::trpzSerialNumber.0 = STRING: "JJ01234567"
$config['mibs'][$mib]['version'][] = array('oid' => 'trpzVersionString.0'); // TRAPEZE-NETWORKS-ROOT-MIB::trpzVersionString.0 = STRING: "8.0.3.15.0"

$mib = 'TRAPEZE-NETWORKS-AP-CONFIG-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14525.4.14';
$config['mibs'][$mib]['mib_dir'] = 'trapeze';
$config['mibs'][$mib]['descr'] = '';

$mib = 'TRAPEZE-NETWORKS-AP-STATUS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14525.4.5';
$config['mibs'][$mib]['mib_dir'] = 'trapeze';
$config['mibs'][$mib]['descr'] = '';

$mib = 'TRAPEZE-NETWORKS-CLIENT-SESSION-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14525.4.4';
$config['mibs'][$mib]['mib_dir'] = 'trapeze';
$config['mibs'][$mib]['descr'] = '';

$mib = 'TRAPEZE-NETWORKS-SYSTEM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14525.4.8';
$config['mibs'][$mib]['mib_dir'] = 'trapeze';
$config['mibs'][$mib]['descr'] = '';
// trpzSysCpuLastMinuteLoad.0 = COUNTER: 100
$config['mibs'][$mib]['processor']['trpzSysCpuLastMinuteLoad'] = array('type' => 'static', 'descr' => 'Processor', 'oid' => 'trpzSysCpuLastMinuteLoad.0', 'oid_num' => '.1.3.6.1.4.1.14525.4.8.1.1.11.2.0');
$config['mibs'][$mib]['mempool']['trpzSysDataObjects'] = array('type'      => 'static', 'descr' => 'Memory', 'scale' => 1024,
                                                               'oid_total' => 'trpzSysCpuMemorySize.0', 'oid_total_num' => '.1.3.6.1.4.1.14525.4.8.1.1.6.0',    // TRAPEZE-NETWORKS-SYSTEM-MIB::trpzSysCpuMemorySize.0 = Gauge32: 1048576
                                                               'oid_used'  => 'trpzSysCpuMemoryLast5MinutesUsage.0', 'oid_used_num' => '.1.3.6.1.4.1.14525.4.8.1.1.12.3.0', // TRAPEZE-NETWORKS-SYSTEM-MIB::trpzSysCpuMemoryLast5MinutesUsage.0 = Gauge32: 495440
);

$config['mibs'][$mib]['states']['trapeze-state'][1] = array('name' => 'other', 'event' => 'warning');
$config['mibs'][$mib]['states']['trapeze-state'][2] = array('name' => 'unknown', 'event' => 'warning');
$config['mibs'][$mib]['states']['trapeze-state'][3] = array('name' => 'ac-failed', 'event' => 'alert');
$config['mibs'][$mib]['states']['trapeze-state'][4] = array('name' => 'dc-failed', 'event' => 'alert');
$config['mibs'][$mib]['states']['trapeze-state'][5] = array('name' => 'ac-ok-dc-ok', 'event' => 'ok');

$mib = 'TRIPPLITE-12X';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.850.90';
$config['mibs'][$mib]['mib_dir'] = 'tripplite';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'tlUpsSnmpCardSerialNum.0'); // tlUpsSnmpCardSerialNum.0 = STRING: "9942AY0AC796000912"

$config['mibs'][$mib]['sensor']['tlEnvTemperatureF']['indexes'][0] = array('descr' => 'Temperature', 'class' => 'temperature', 'measured' => 'device', 'scale' => 1, 'unit' => 'F', 'oid_num' => '.1.3.6.1.4.1.850.101.1.1.2.0', 'oid_limit_low' => '.1.3.6.1.4.1.850.101.1.1.3.0', 'oid_limit_high' => '.1.3.6.1.4.1.850.101.1.1.4.0');
$config['mibs'][$mib]['sensor']['tlEnvHumidity']['indexes'][0] = array('descr' => 'Humidity', 'class' => 'humidity', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.850.101.1.2.1.0', 'oid_limit_low' => '.1.3.6.1.4.1.850.101.1.2.2.0', 'oid_limit_high' => '.1.3.6.1.4.1.850.101.1.2.3.0');

$mib = 'UBNT-AirFIBER-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.41112.1.3';
$config['mibs'][$mib]['mib_dir'] = 'ubiquiti';
$config['mibs'][$mib]['descr'] = '';

$mib = 'UBNT-AirMAX-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.41112.1.4';
$config['mibs'][$mib]['mib_dir'] = 'ubiquiti';
$config['mibs'][$mib]['descr'] = '';

$mib = 'UBNT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.41112.1';
$config['mibs'][$mib]['mib_dir'] = 'ubiquiti';
$config['mibs'][$mib]['descr'] = '';

$mib = 'UBNT-UniFi-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.41112.1.2.5';
$config['mibs'][$mib]['mib_dir'] = 'ubiquiti';
$config['mibs'][$mib]['descr'] = '';

$mib = 'WATCHGUARD-SYSTEM-STATISTICS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3097.6';
$config['mibs'][$mib]['mib_dir'] = 'watchguard';
$config['mibs'][$mib]['descr'] = '';
// WATCHGUARD-SYSTEM-STATISTICS-MIB::wgSystemCpuUtil5.0 = COUNTER: 123
$config['mibs'][$mib]['processor']['wgSystemCpuUtil5'] = array('type' => 'static', 'descr' => 'Processor', 'scale' => 0.01, 'oid' => 'wgSystemCpuUtil5.0', 'oid_num' => '.1.3.6.1.4.1.3097.6.3.78.0');

// WHISP-BOX-MIBV2-MIB

$mib = 'WHISP-BOX-MIBV2-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'cambium';
$config['mibs'][$mib]['descr'] = '';

$mib = 'WHISP-APS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.161.19.1.1.12';
$config['mibs'][$mib]['mib_dir'] = 'cambium';
$config['mibs'][$mib]['descr'] = '';

$mib = 'WOWZA-STREAMING-ENGINE-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.46706.100';
$config['mibs'][$mib]['mib_dir'] = 'wowza';
$config['mibs'][$mib]['descr'] = '';
//WOWZA-STREAMING-ENGINE-MIB::serverCounterGetVersion.1 = STRING: Wowza Streaming Engine 4 Monthly Edition 4.5.0.01 build18956

$mib = 'WWP-LEOS-CHASSIS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6141.2.60.11';
$config['mibs'][$mib]['mib_dir'] = 'wwp';
$config['mibs'][$mib]['descr'] = '';

$mib = 'WWP-LEOS-PORT-XCVR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6141.2.60.4';
$config['mibs'][$mib]['mib_dir'] = 'wwp';
$config['mibs'][$mib]['descr'] = '';

// WWP-LEOS-SYSTEM-CONFIG-MIB

$mib = 'WWP-LEOS-SYSTEM-CONFIG-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6141.2.60.12';
$config['mibs'][$mib]['mib_dir'] = 'wwp';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['mempool']['WwpLeosSystemMemoryUsageEntry'] = array('type'       => 'static', 'descr' => 'Memory',
                                                                          'oid_total'  => 'wwpLeosSystemMemoryUsageMemoryTotal.global-heap', 'oid_total_num' => '.1.3.6.1.4.1.6141.2.60.12.1.9.1.1.2.2', // WWP-LEOS-SYSTEM-CONFIG-MIB::wwpLeosSystemMemoryUsageMemoryTotal.global-heap = Gauge32: 1023918080 bytes
                                                                          'oid_used'   => 'wwpLeosSystemMemoryUsageMemoryAvailable.global-heap', 'oid_used_num' => '.1.3.6.1.4.1.6141.2.60.12.1.9.1.1.7.2', // WWP-LEOS-SYSTEM-CONFIG-MIB::wwpLeosSystemMemoryUsageMemoryAvailable.global-heap = Gauge32: 738725888 bytes
                                                                          'oid_free'   => 'wwpLeosSystemMemoryUsageMemoryUsed.global-heap', 'oid_free_num' => '.1.3.6.1.4.1.6141.2.60.12.1.9.1.1.6.2', // WWP-LEOS-SYSTEM-CONFIG-MIB::wwpLeosSystemMemoryUsageMemoryUsed.global-heap = Gauge32: 285184000 bytes
                                                                          'rename_rrd' => 'ciena-topsecret-mib-0',
);

// Ciena

$mib = 'CIENA-WS-XCVR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'ciena';
$config['mibs'][$mib]['descr'] = '';

$mib = 'WebGraph-8xThermometer-US-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'webgraph';
$config['mibs'][$mib]['descr'] = '';

$mib = 'WebGraph-Thermometer-PT-US-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'webgraph';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['sensor']['wtWebioAn1GraphPtBinaryTempValueTable']['tables'][] = array(
  'table'      => 'wtWebioAn1GraphPtBinaryTempValueTable',
  'table_walk' => FALSE, // too big table, walk by OIDs
  'class'      => 'temperature',
  'oid'        => 'wtWebioAn1GraphPtBinaryTempValue',
  'oid_num'    => '.1.3.6.1.4.1.5040.1.2.17.1.4.1.1',
  'oid_descr'  => 'wtWebioAn1GraphPtPortName',
  'scale'      => 0.1,
  'min'        => 0,
  'max'        => 2000, // 0x7FFF indicates an invalid measured value
);

$mib = 'WebGraph-OLD-Thermo-Hygrometer-US-MIB'; // NOTE, this is old version of WebGraph-Thermo-Hygrometer-US-MIB with different oid tree
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.5040.1.2.9'; // WebGraph-Therm-Hygrometer-US-MIB::wtWebGraphThermHygro
$config['mibs'][$mib]['mib_dir'] = 'webgraph';
$config['mibs'][$mib]['descr'] = '';

$mib = 'WebGraph-Thermo-Hygrometer-US-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.5040.1.2.42';
$config['mibs'][$mib]['mib_dir'] = 'webgraph';
$config['mibs'][$mib]['descr'] = '';

$mib = 'XUPS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'eaton';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.534.1';
$config['mibs'][$mib]['version'][] = array('oid' => 'xupsIdentSoftwareVersion.0'); // XUPS-MIB::xupsIdentSoftwareVersion.0 = STRING: " FP:  2.01  INV:  2.01  NET: 3.60 "

$type = 'xupsInputSource';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'other', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'none', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'primaryUtility', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'bypassFeed', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'secondaryUtility', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'generator', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'flywheel', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'fuelcell', 'event' => 'warning');

$type = 'xupsOutputSource';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'other', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'none', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'bypass', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'battery', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'booster', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'reducer', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'parallelCapacity', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'parallelRedundant', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][10] = array('name' => 'highEfficiencyMode', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][11] = array('name' => 'maintenanceBypass', 'event' => 'ok');

$type = 'xupsBatteryAbmStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'batteryCharging', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'batteryDischarging', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'batteryFloating', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'batteryResting', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'unknown', 'event' => 'warning');

$mib = 'EATON-PDU-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.534.6.6.4';
$config['mibs'][$mib]['mib_dir'] = 'eaton';
$config['mibs'][$mib]['descr'] = 'The MIB module for Eaton PDUs (Power Distribution Units)';

$mib = 'EATON-EPDU-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.534.6.6.7';
$config['mibs'][$mib]['mib_dir'] = 'eaton';
$config['mibs'][$mib]['descr'] = 'The MIB module for Eaton ePDUs (Enclosure Power Distribution Units)';
$config['mibs'][$mib]['serial'][] = array('oid' => 'serialNumber.0'); // EATON-EPDU-MIB::serialNumber.0 = STRING: "B6xxxxx180"
$config['mibs'][$mib]['version'][] = array('oid' => 'firmwareVersion.0'); // EATON-EPDU-MIB::firmwareVersion.0 = STRING: "02.00.0041"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'partNumber.0'); // EATON-EPDU-MIB::partNumber.0 = STRING: "EMI315-10"

/*
EATON-EPDU-MIB::productName.0 = STRING: "EPDU MI 40U-A IN: CS8365 35A 3P OUT: 42XC13"
EATON-EPDU-MIB::unitName.0 = STRING: "PDU"
EATON-EPDU-MIB::lcdControl.0 = INTEGER: notApplicable(0)
EATON-EPDU-MIB::clockValue.0 = STRING: 2016-7-9,12:56:56.0,+0:00
EATON-EPDU-MIB::temperatureScale.0 = INTEGER: celsius(0)
*/

$config['mibs'][$mib]['status']['communicationStatus']['indexes'][0] = array('descr' => 'Communication Status', 'measured' => 'device', 'type' => 'communicationStatus', 'oid_num' => '.1.3.6.1.4.1.534.6.6.7.1.2.1.30.0');
$config['mibs'][$mib]['status']['internalStatus']['indexes'][0] = array('descr' => 'Internal Status', 'measured' => 'device', 'type' => 'internalStatus', 'oid_num' => '.1.3.6.1.4.1.534.6.6.7.1.2.1.31.0');
$config['mibs'][$mib]['status']['strappingStatus']['indexes'][0] = array('descr' => 'Strapping Status', 'measured' => 'device', 'type' => 'strappingStatus', 'oid_num' => '.1.3.6.1.4.1.534.6.6.7.1.2.1.32.0');

$type = 'communicationStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'good', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'communicationLost', 'event' => 'alert');

$type = 'internalStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'good', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'internalFailure', 'event' => 'alert');

$type = 'strappingStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'good', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'communicationLost', 'event' => 'alert');

$type = 'inputFrequencyStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'good', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'outOfRange', 'event' => 'alert');

$type = 'inputFrequencyStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'good', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'outOfRange', 'event' => 'alert');

$type = 'inputVoltageThStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'good', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'lowWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'lowCritical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'highWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'highCritical', 'event' => 'alert');

$type = 'outletCurrentThStatus';
$config['mibs'][$mib]['states'][$type] = $config['mibs'][$mib]['states']['inputVoltageThStatus'];

$type = 'inputCurrentThStatus';
$config['mibs'][$mib]['states'][$type] = $config['mibs'][$mib]['states']['inputVoltageThStatus'];

$mib = 'EATON-EPDU-MA-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.534.6.6.6';
$config['mibs'][$mib]['mib_dir'] = 'eaton';
$config['mibs'][$mib]['descr'] = 'The MIB module for old Eaton PDUs (Power Distribution Units)';
$config['mibs'][$mib]['serial'][] = array('oid' => 'serialNumber.0'); // EATON-EPDU-MA-MIB::serialNumber.0 = STRING: ADZC050100
$config['mibs'][$mib]['version'][] = array('oid' => 'firmwareVersion.0'); // EATON-EPDU-MA-MIB::firmwareVersion.0 = STRING: 01.01.01
$config['mibs'][$mib]['hardware'][] = array('oid' => 'objectName.0');  // EATON-EPDU-MA-MIB::objectName.0 = STRING: PW104MA1UB44

/*
EATON-EPDU-MA-MIB::hardwareRev.0 = INTEGER: 26
EATON-EPDU-MA-MIB::objectInstance.0 = STRING: Master_Switch_2
*/

$mib = 'ENLOGIC-PDU-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.38446.1';
$config['mibs'][$mib]['mib_dir'] = 'enlogic';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'pduNamePlateSerialNumber.1');    // ENLOGIC-PDU-MIB::pduNamePlateSerialNumber.1 = STRING: A4FC0356
$config['mibs'][$mib]['version'][] = array('oid' => 'pduNamePlateFirmwareVersion.1'); // ENLOGIC-PDU-MIB::pduNamePlateFirmwareVersion.1 = STRING: 7.09
$config['mibs'][$mib]['hardware'][] = array('oid' => 'pduNamePlateModelNumber.1');     // ENLOGIC-PDU-MIB::pduNamePlateModelNumber.1 = STRING: EN211

$type = 'pduUnitStatusState';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'upperCritical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'upperWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'lowerWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'lowerCritical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'off', 'event' => 'exclude');

$type = 'pduExternalSensorStatusState';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'notPresent', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'alarmed', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'belowLowerCritical', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'belowLowerWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'aboveUpperWarning', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'aboveUpperCritical', 'event' => 'alert');

$mib = 'ZHNSYSTEM';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.5504.2.5.2';
$config['mibs'][$mib]['mib_dir'] = 'zhone';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'sysFirmwareVersion.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'modelNumber.0');

$mib = 'ZHONE-CARD-RESOURCES-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.5504.6.4';
$config['mibs'][$mib]['mib_dir'] = 'zhone';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'cardMfgSerialNumber.1.1', 'transformations' => array(array('action' => 'ltrim', 'characters' => '0'))); // ZHONE-CARD-RESOURCES-MIB::cardMfgSerialNumber.1.1 = STRING: 0000000000011568280
$config['mibs'][$mib]['version'][] = array('oid' => 'cardSwRunningVers.1.1'); // ZHONE-CARD-RESOURCES-MIB::cardSwRunningVers.1.1 = STRING: MX 2.4.1.209
$config['mibs'][$mib]['hardware'][] = array('oid' => 'cardIdentification.1.1'); // ZHONE-CARD-RESOURCES-MIB::cardIdentification.1.1 = STRING: zhone-mxk-198-10GE

/*
ZHONE-CARD-RESOURCES-MIB::cardZhoneType.1.1 = INTEGER: mx1U19xFamily(10500)
ZHONE-CARD-RESOURCES-MIB::cardMfgCLEICode.1.1 = STRING: No CLEI
ZHONE-CARD-RESOURCES-MIB::cardMfgRevisionCode.1.1 = STRING: Unknown.
ZHONE-CARD-RESOURCES-MIB::cardMfgBootRevision.1.1 = STRING: MX 2.2.1.211
ZHONE-CARD-RESOURCES-MIB::cardUpTime.1.1 = Timeticks: (244202581) 28 days, 6:20:25.81
ZHONE-CARD-RESOURCES-MIB::cardInterfaceType.1.1 = INTEGER: mx1U198-10GE(10504)
*/

$mib = 'ZHONE-SHELF-MONITOR-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.5504.6.7';
$config['mibs'][$mib]['mib_dir'] = 'zhone';
$config['mibs'][$mib]['descr'] = '';

$type = 'shelfPowerStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'powerOk', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'powerNotOk', 'event' => 'alert');

$type = 'shelfTemperatureStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'aboveNormal', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'belowNormal', 'event' => 'warning');

$type = 'shelfFanTrayStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'operational', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'partiallyOperational', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'notOperational', 'event' => 'alert');

$mib = 'ZXR10-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3902.3';
$config['mibs'][$mib]['mib_dir'] = 'zte';
$config['mibs'][$mib]['descr'] = '';

$mib = 'ZXR10OPTICALMIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3902.3.103.11';
$config['mibs'][$mib]['mib_dir'] = 'zte';
$config['mibs'][$mib]['descr'] = '';

$mib = 'SWITCHENVIRONG';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.3902.3.202.3';
$config['mibs'][$mib]['mib_dir'] = 'zte';
$config['mibs'][$mib]['descr'] = '';

$config['mibs'][$mib]['sensor']['value']['indexes'][0] = array('descr' => 'Temperature', 'class' => 'temperature', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.3902.3.202.3.3.1.0', 'oid_limit_high' => '.1.3.6.1.4.1.3902.3.202.3.3.2.0');

$mib = 'ZYXEL-AS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'zyxel';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'accessSwitchFWVersion.0');

$mib = 'GBNPlatformOAM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.13464.1.2.1.1';
$config['mibs'][$mib]['mib_dir'] = 'gcom';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'prodSerialNo.0'); // GBNPlatformOAM-MIB::prodSerialNo.0 = STRING: 012200040000xxxxxxxxxxxxxx
$config['mibs'][$mib]['hardware'][] = array('oid' => 'productName.0', 'transformations' => array(array('action' => 'replace', 'from' => ' Product', 'to' => '')));
$config['mibs'][$mib]['processor']['cpuIdle'] = array('type' => 'static', 'descr' => 'System CPU', 'oid' => 'cpuIdle.0', 'oid_num' => '.1.3.6.1.4.1.13464.1.2.1.1.2.5.0', 'idle' => TRUE, 'oid_descr' => 'cpuDescription.0');
$config['mibs'][$mib]['mempool']['gbnPlatformOAMSystem'] = array('type'      => 'static', 'descr' => 'Memory', 'scale' => 1024 * 1024,
                                                                 'oid_total' => 'memorySize.0', 'oid_total_num' => '.1.3.6.1.4.1.13464.1.2.1.1.2.12.0', // GBNPlatformOAM-MIB::memorySize.0 = INTEGER: 128
                                                                 'oid_free'  => 'memoryIdle.0', 'oid_free_num' => '.1.3.6.1.4.1.13464.1.2.1.1.2.13.0', // GBNPlatformOAM-MIB::memoryIdle.0 = INTEGER: 51
);

// LANCOM-L54-dual-MIB

$mib = 'LANCOM-L54-dual-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'lancom';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'firVerSer.ifc'); // LANCOM-L54-dual-MIB::firVerSer.ifc = STRING: "104671800120"
$config['mibs'][$mib]['hardware'][] = array('oid' => 'firVerMod.ifc'); // LANCOM-L54-dual-MIB::firVerMod.ifc = STRING: "LANCOM L-54 dual Wireless"

// LANCOM-L310-MIB

$mib = 'LANCOM-L310-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'lancom';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'firVerSer.ifc');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'firVerMod.ifc');

// ARISTA-BGP4V2-MIB

$mib = 'ARISTA-BGP4V2-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.30065.4.1';
$config['mibs'][$mib]['mib_dir'] = 'arista';
$config['mibs'][$mib]['descr'] = '';

// READYDATAOS-MIB

$mib = 'READYDATAOS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'netgear';
$config['mibs'][$mib]['descr'] = '';

/// FIXME. WTF, indexes should be numeric, I think this states not work...
$config['mibs'][$mib]['states']['readydataos-mib_diskState']['"ONLINE"'] = array('name' => 'Online', 'event' => 'ok');
$config['mibs'][$mib]['states']['readydataos-mib_diskState']['"OFFLINE"'] = array('name' => 'Offline', 'event' => 'alert');

$config['mibs'][$mib]['states']['readydataos-mib_fanStatus']['"ok"'] = array('name' => 'Ok', 'event' => 'ok');

$mib = 'READYNAS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'netgear';
$config['mibs'][$mib]['descr'] = '';

// NETGEAR-POWER-ETHERNET-MIB

$mib = 'NETGEAR-POWER-ETHERNET-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.4526.10.15';
$config['mibs'][$mib]['mib_dir'] = 'netgear';
$config['mibs'][$mib]['descr'] = '';

// WLSX-SWITCH-MIB

$mib = 'WLSX-SWITCH-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.14823.2.2.1.1';
$config['mibs'][$mib]['mib_dir'] = 'aruba';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['hardware'][] = array('oid' => 'wlsxModelName.0');                 // WLSX-SWITCH-MIB::wlsxModelName.0 = STRING: OAW-6000
$config['mibs'][$mib]['serial'][] = array('oid' => 'wlsxSwitchLicenseSerialNumber.0'); // WLSX-SWITCH-MIB::wlsxSwitchLicenseSerialNumber.0 = STRING: FE0000902
$config['mibs'][$mib]['mempool']['WlsxSysXMemoryEntry'] = array('type'      => 'static', 'descr' => 'Memory', 'scale' => 1024,
                                                                'oid_total' => 'sysXMemorySize.1', 'oid_total_num' => '.1.3.6.1.4.1.14823.2.2.1.1.1.11.1.2.1', // WLSX-SWITCH-MIB::sysXMemorySize.1 = INTEGER: 1535900
                                                                'oid_free'  => 'sysXMemoryFree.1', 'oid_free_num' => '.1.3.6.1.4.1.14823.2.2.1.1.1.11.1.4.1', // WLSX-SWITCH-MIB::sysXMemoryFree.1 = INTEGER: 1123840
                                                                'oid_used'  => 'sysXMemoryUsed.1', 'oid_used_num' => '.1.3.6.1.4.1.14823.2.2.1.1.1.11.1.3.1', // WLSX-SWITCH-MIB::sysXMemoryUsed.1 = INTEGER: 412060
);
// FIXME ^ because 'mempool' definition currently doesn't support indexes, it gets indexed as index 0.

// WLSX-WLAN-MIB

$mib = 'WLSX-WLAN-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'aruba';
$config['mibs'][$mib]['descr'] = '';

// XPPC-MIB

$mib = 'XPPC-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.935';
$config['mibs'][$mib]['mib_dir'] = 'megatec';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'upsSmartIdentUpsSerialNumber.0');
$config['mibs'][$mib]['hardware'][] = array('oid' => 'upsBaseIdentModel.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'upsSmartIdentAgentFirmwareRevision.0');

// Three phase
$config['mibs'][$mib]['sensor']['upsThreePhaseBatteryTemperature']['indexes'][0] = array('descr'    => 'Battery Temperature',
                                                                                         'class'    => 'temperature',
                                                                                         'measured' => 'battery',
                                                                                         'scale'    => 0.1,
                                                                                         'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseBatteryCapacityPercentage']['indexes'][0] = array('descr'          => 'Battery Capacity',
                                                                                                'class'          => 'capacity',
                                                                                                'measured'       => 'battery',
                                                                                                'scale'          => 1,
                                                                                                'limit_low'      => 10,
                                                                                                'limit_low_warn' => 25,
                                                                                                'min'            => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseBatteryVoltage']['indexes'][0] = array('descr'    => 'Battery Voltage',
                                                                                     'class'    => 'voltage',
                                                                                     'measured' => 'battery',
                                                                                     'scale'    => 0.1,
                                                                                     'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseBatteryCurrent']['indexes'][0] = array('descr'    => 'Battery Load', // Yes, this is load: expressed in percent of maximum current
                                                                                     'class'    => 'load',
                                                                                     'measured' => 'battery',
                                                                                     'scale'    => 1,
                                                                                     'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseBatteryTimeRemain']['indexes'][0] = array('descr'          => 'Battery Runtime',
                                                                                        'class'          => 'runtime',
                                                                                        'measured'       => 'battery',
                                                                                        'scale'          => 1,
                                                                                        'limit_low'      => 3,
                                                                                        'limit_low_warn' => 10,
                                                                                        'min'            => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseInputFrequency']['indexes'][0] = array('descr'    => 'Input Frequency',
                                                                                     'class'    => 'frequency',
                                                                                     'measured' => 'other',
                                                                                     'scale'    => 0.1,
                                                                                     'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseInputVoltageR']['indexes'][0] = array('descr'    => 'Input Voltage R',
                                                                                    'class'    => 'voltage',
                                                                                    'measured' => 'other',
                                                                                    'scale'    => 0.1,
                                                                                    'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseInputVoltageS']['indexes'][0] = array('descr'    => 'Input Voltage S',
                                                                                    'class'    => 'voltage',
                                                                                    'measured' => 'other',
                                                                                    'scale'    => 0.1,
                                                                                    'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseInputVoltageT']['indexes'][0] = array('descr'    => 'Input Voltage T',
                                                                                    'class'    => 'voltage',
                                                                                    'measured' => 'other',
                                                                                    'scale'    => 0.1,
                                                                                    'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseOutputFrequency']['indexes'][0] = array('descr'    => 'Output Frequency',
                                                                                      'class'    => 'frequency',
                                                                                      'measured' => 'other',
                                                                                      'scale'    => 0.1,
                                                                                      'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseOutputVoltageR']['indexes'][0] = array('descr'    => 'Output Voltage R',
                                                                                     'class'    => 'voltage',
                                                                                     'measured' => 'other',
                                                                                     'scale'    => 0.1,
                                                                                     'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseOutputVoltageS']['indexes'][0] = array('descr'    => 'Output Voltage S',
                                                                                     'class'    => 'voltage',
                                                                                     'measured' => 'other',
                                                                                     'scale'    => 0.1,
                                                                                     'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseOutputVoltageT']['indexes'][0] = array('descr'    => 'Output Voltage T',
                                                                                     'class'    => 'voltage',
                                                                                     'measured' => 'other',
                                                                                     'scale'    => 0.1,
                                                                                     'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseOutputLoadPercentageR']['indexes'][0] = array('descr'           => 'Output Load R',
                                                                                            'class'           => 'load',
                                                                                            'measured'        => 'other',
                                                                                            'scale'           => 0.1,
                                                                                            'limit_high'      => 95,
                                                                                            'limit_high_warn' => 80,
                                                                                            'min'             => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseOutputLoadPercentageS']['indexes'][0] = array('descr'           => 'Output Load S',
                                                                                            'class'           => 'load',
                                                                                            'measured'        => 'other',
                                                                                            'scale'           => 0.1,
                                                                                            'limit_high'      => 95,
                                                                                            'limit_high_warn' => 80,
                                                                                            'min'             => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseOutputLoadPercentageT']['indexes'][0] = array('descr'           => 'Output Load T',
                                                                                            'class'           => 'load',
                                                                                            'measured'        => 'other',
                                                                                            'scale'           => 0.1,
                                                                                            'limit_high'      => 95,
                                                                                            'limit_high_warn' => 80,
                                                                                            'min'             => 0);

$config['mibs'][$mib]['sensor']['upsThreePhaseBypassSourceFrequency']['indexes'][0] = array('descr'    => 'Bypass Frequency',
                                                                                            'class'    => 'frequency',
                                                                                            'measured' => 'other',
                                                                                            'scale'    => 0.1,
                                                                                            'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseBypassSourceVoltageR']['indexes'][0] = array('descr'    => 'Bypass Voltage R',
                                                                                           'class'    => 'voltage',
                                                                                           'measured' => 'other',
                                                                                           'scale'    => 0.1,
                                                                                           'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseBypassSourceVoltageS']['indexes'][0] = array('descr'    => 'Bypass Voltage S',
                                                                                           'class'    => 'voltage',
                                                                                           'measured' => 'other',
                                                                                           'scale'    => 0.1,
                                                                                           'min'      => 0);
$config['mibs'][$mib]['sensor']['upsThreePhaseBypassSourceVoltageT']['indexes'][0] = array('descr'    => 'Bypass Voltage T',
                                                                                           'class'    => 'voltage',
                                                                                           'measured' => 'other',
                                                                                           'scale'    => 0.1,
                                                                                           'min'      => 0);

// Single phase (keep this after Three phase due to use of skip_if_valid_exist!)
$config['mibs'][$mib]['sensor']['upsSmartBatteryTemperature']['indexes'][0] = array('descr'               => 'Battery Temperature',
                                                                                    'class'               => 'temperature',
                                                                                    'measured'            => 'battery',
                                                                                    'scale'               => 0.1,
                                                                                    'min'                 => 0,
  // Skip sensor if exist $valid['sensor']['temperature']['XPPC-MIB-upsThreePhaseBatteryTemperature']
                                                                                    'skip_if_valid_exist' => 'temperature->XPPC-MIB-upsThreePhaseBatteryTemperature');
$config['mibs'][$mib]['sensor']['upsSmartBatteryCapacity']['indexes'][0] = array('descr'               => 'Battery Capacity',
                                                                                 'class'               => 'capacity',
                                                                                 'measured'            => 'battery',
                                                                                 'scale'               => 1,
                                                                                 'limit_low'           => 10,
                                                                                 'limit_low_warn'      => 25,
                                                                                 'min'                 => 0,
  // Skip sensor if exist $valid['sensor']['capacity']['XPPC-MIB-upsThreePhaseBatteryCapacityPercentage']
                                                                                 'skip_if_valid_exist' => 'capacity->XPPC-MIB-upsThreePhaseBatteryCapacityPercentage');
$config['mibs'][$mib]['sensor']['upsSmartBatteryVoltage']['indexes'][0] = array('descr'               => 'Battery Voltage',
                                                                                'class'               => 'voltage',
                                                                                'measured'            => 'battery',
                                                                                'scale'               => 1, // Not sure, should be 0.1
                                                                                'min'                 => 0,
  // Skip sensor if exist $valid['sensor']['voltage']['XPPC-MIB-upsThreePhaseBatteryVoltage']
                                                                                'skip_if_valid_exist' => 'voltage->XPPC-MIB-upsThreePhaseBatteryVoltage');
$config['mibs'][$mib]['sensor']['upsSmartBatteryCurrent']['indexes'][0] = array('descr'               => 'Battery Load', // Yes, this is load: expressed in percent of maximum current
                                                                                'class'               => 'load',
                                                                                'measured'            => 'battery',
                                                                                'scale'               => 1,
                                                                                'min'                 => 0,
  // Skip sensor if exist $valid['sensor']['load']['XPPC-MIB-upsThreePhaseBatteryCurrent']
                                                                                'skip_if_valid_exist' => 'load->XPPC-MIB-upsThreePhaseBatteryCurrent');
$config['mibs'][$mib]['sensor']['upsSmartBatteryRunTimeRemaining']['indexes'][0] = array('descr'               => 'Battery Runtime',
                                                                                         'class'               => 'runtime',
                                                                                         'measured'            => 'battery',
                                                                                         'scale'               => 1 / 60, // seconds to min
                                                                                         'limit_low'           => 3,
                                                                                         'limit_low_warn'      => 10,
                                                                                         'min'                 => 0,
  // Skip sensor if exist $valid['sensor']['runtime']['XPPC-MIB-upsThreePhaseBatteryTimeRemain']
                                                                                         'skip_if_valid_exist' => 'runtime->XPPC-MIB-upsThreePhaseBatteryTimeRemain');
$config['mibs'][$mib]['sensor']['upsSmartInputFrequency']['indexes'][0] = array('descr'               => 'Input Frequency',
                                                                                'class'               => 'frequency',
                                                                                'measured'            => 'other',
                                                                                'scale'               => 0.1,
                                                                                'min'                 => 0,
  // Skip sensor if exist $valid['sensor']['frequency']['XPPC-MIB-upsThreePhaseInputFrequency']
                                                                                'skip_if_valid_exist' => 'frequency->XPPC-MIB-upsThreePhaseInputFrequency');
$config['mibs'][$mib]['sensor']['upsSmartInputLineVoltage']['indexes'][0] = array('descr'               => 'Input Voltage',
                                                                                  'class'               => 'voltage',
                                                                                  'measured'            => 'other',
                                                                                  'scale'               => 0.1,
                                                                                  'min'                 => 0,
  // Skip sensor if exist $valid['sensor']['voltage']['XPPC-MIB-upsThreePhaseInputVoltageR']
                                                                                  'skip_if_valid_exist' => 'voltage->XPPC-MIB-upsThreePhaseInputVoltageR');
$config['mibs'][$mib]['sensor']['upsSmartOutputFrequency']['indexes'][0] = array('descr'               => 'Output Frequency',
                                                                                 'class'               => 'frequency',
                                                                                 'measured'            => 'other',
                                                                                 'scale'               => 0.1,
                                                                                 'min'                 => 0,
  // Skip sensor if exist $valid['sensor']['frequency']['XPPC-MIB-upsThreePhaseOutputFrequency']
                                                                                 'skip_if_valid_exist' => 'frequency->XPPC-MIB-upsThreePhaseOutputFrequency');
$config['mibs'][$mib]['sensor']['upsSmartOutputVoltage']['indexes'][0] = array('descr'               => 'Output Voltage',
                                                                               'class'               => 'voltage',
                                                                               'measured'            => 'other',
                                                                               'scale'               => 0.1,
                                                                               'min'                 => 0,
  // Skip sensor if exist $valid['sensor']['voltage']['XPPC-MIB-upsThreePhaseOutputVoltageR']
                                                                               'skip_if_valid_exist' => 'voltage->XPPC-MIB-upsThreePhaseOutputVoltageR');

$config['mibs'][$mib]['sensor']['upsSmartOutputLoad']['indexes'][0] = array('descr'               => 'Output Load',
                                                                            'class'               => 'load',
                                                                            'measured'            => 'other',
                                                                            'scale'               => 1,
                                                                            'limit_high'          => 95,
                                                                            'limit_high_warn'     => 80,
                                                                            'min'                 => 0,
  // Skip sensor if exist $valid['sensor']['load']['XPPC-MIB-upsThreePhaseOutputLoadPercentageR']
                                                                            'skip_if_valid_exist' => 'load->XPPC-MIB-upsThreePhaseOutputLoadPercentageR');

$config['mibs'][$mib]['sensor']['upsEnvTemperature']['indexes'][0] = array('descr'          => 'Sensor Temperature',
                                                                           'class'          => 'temperature',
                                                                           'measured'       => 'other',
                                                                           'oid_limit_high' => 'upsEnvOverTemperature.0',
                                                                           'oid_limit_low'  => 'upsEnvUnderTemperature.0',
                                                                           'limit_scale'    => 0.1,
                                                                           'scale'          => 0.1,
                                                                           'min'            => 0);
$config['mibs'][$mib]['sensor']['upsEnvHumidity']['indexes'][0] = array('descr'          => 'Sensor Humidity',
                                                                        'class'          => 'humidity',
                                                                        'measured'       => 'other',
                                                                        'oid_limit_high' => 'upsEnvOverHumidity.0',
                                                                        'oid_limit_low'  => 'upsEnvUnderHumidity.0',
  //'limit_scale'     => 1,
                                                                        'scale'          => 1,
                                                                        'min'            => 0);

$config['mibs'][$mib]['status']['upsBaseBatteryStatus']['indexes'][0] = array('descr'    => 'Battery Status',
                                                                              'measured' => 'battery',
                                                                              'type'     => 'upsBaseBatteryStatus');
$config['mibs'][$mib]['status']['upsSmartInputLineFailCause']['indexes'][0] = array('descr'    => 'Last Input Fail Cause',
                                                                                    'measured' => 'other',
                                                                                    'type'     => 'upsSmartInputLineFailCause');
$config['mibs'][$mib]['status']['upsBaseOutputStatus']['indexes'][0] = array('descr'    => 'Output Status',
                                                                             'measured' => 'other',
                                                                             'type'     => 'upsBaseOutputStatus');
$config['mibs'][$mib]['status']['upsEnvWater']['indexes'][0] = array('descr'    => 'Sensor Water',
                                                                     'measured' => 'other',
                                                                     'type'     => 'upsEnv');
$config['mibs'][$mib]['status']['upsEnvSmoke']['indexes'][0] = array('descr'    => 'Sensor Smoke',
                                                                     'measured' => 'other',
                                                                     'type'     => 'upsEnv');
$type = 'upsBaseBatteryStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'batteryNormal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'batteryLow', 'event' => 'alert');
$type = 'upsSmartInputLineFailCause'; // mostly same as powernet-upsadvinputfail-state
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'noTransfer', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'highLineVoltage', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'brownout', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'blackout', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'smallMomentarySag', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'deepMomentarySag', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'smallMomentarySpike', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'largeMomentarySpike', 'event' => 'warning');
//$config['mibs'][$mib]['states'][$type][9]  = array('name' => 'selfTest',            'event' => 'ok');
//$config['mibs'][$mib]['states'][$type][10] = array('name' => 'rateOfVoltageChange', 'event' => 'warning');
$type = 'upsBaseOutputStatus';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'unknown', 'event' => 'exclude');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'onLine', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'onBattery', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][4] = array('name' => 'onBoost', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][5] = array('name' => 'sleeping', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][6] = array('name' => 'onBypass', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][7] = array('name' => 'rebooting', 'event' => 'warning');
$config['mibs'][$mib]['states'][$type][8] = array('name' => 'standBy', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][9] = array('name' => 'onBuck', 'event' => 'warning'); // not sure what is this :)
$type = 'upsEnv';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'abnormal', 'event' => 'alert');

// XXX-MIB

$mib = 'XXX-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6688';
$config['mibs'][$mib]['mib_dir'] = 'fiberroad';
$config['mibs'][$mib]['descr'] = 'Media Converter NMS';
$config['mibs'][$mib]['status']['psuA']['indexes']['master'] = array('descr' => 'Power Supply A', 'measured' => 'powerSupply', 'type' => 'FiberRoad-on', 'oid_num' => '.1.3.6.1.4.1.6688.1.1.1.2.1.2.1');
$config['mibs'][$mib]['status']['psuB']['indexes']['master'] = array('descr' => 'Power Supply B', 'measured' => 'powerSupply', 'type' => 'FiberRoad-on', 'oid_num' => '.1.3.6.1.4.1.6688.1.1.1.2.1.3.1');
$config['mibs'][$mib]['status']['volA']['indexes']['master'] = array('descr' => 'Voltage of Power Supply A', 'measured' => 'powerSupply', 'type' => 'FiberRoad-normal', 'oid_num' => '.1.3.6.1.4.1.6688.1.1.1.2.1.4.1');
$config['mibs'][$mib]['status']['volB']['indexes']['master'] = array('descr' => 'Voltage of Power Supply B', 'measured' => 'powerSupply', 'type' => 'FiberRoad-normal', 'oid_num' => '.1.3.6.1.4.1.6688.1.1.1.2.1.5.1');
$config['mibs'][$mib]['status']['fan']['indexes']['master'] = array('descr' => 'Fan', 'measured' => 'fan', 'type' => 'FiberRoad-on', 'oid_num' => '.1.3.6.1.4.1.6688.1.1.1.2.1.6.1');
$config['mibs'][$mib]['sensor']['temperature']['indexes']['master'] = array('descr' => 'Temperature', 'class' => 'temperature', 'measured' => 'device', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.6688.1.1.1.2.1.7.1');

$type = 'FiberRoad-on';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'on', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'off', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'nc', 'event' => 'exclude');

$type = 'FiberRoad-normal';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'abnormal', 'event' => 'alert');
$config['mibs'][$mib]['states'][$type][3] = array('name' => 'nc', 'event' => 'exclude');

// TSL-MIB

$mib = 'TSL-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.6853';
$config['mibs'][$mib]['mib_dir'] = 'tsl';
$config['mibs'][$mib]['descr'] = '';

// OG-STATUSv2-MIB

$mib = 'OG-STATUSv2-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.25049.17';
$config['mibs'][$mib]['mib_dir'] = 'opengear';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'ogSerialNumber'); // OG-STATUSv2-MIB::ogSerialNumber = STRING: "55020456371432"

// SYMBOL-CC-WS2000-MIB

$mib = 'SYMBOL-CC-WS2000-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = '';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['serial'][] = array('oid' => 'ccInfoSerialNumber.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'ccIdFwVersion.0');

// ZXTM-MIB

$mib = 'ZXTM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'riverbed';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'version.0'); // ZXTM-MIB::version.0 = STRING: "9.1"

$mib = 'ZXTM-MIB-SMIv2';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '';
$config['mibs'][$mib]['mib_dir'] = 'riverbed';
$config['mibs'][$mib]['descr'] = '';
$config['mibs'][$mib]['version'][] = array('oid' => 'version.0'); // ZXTM-MIB::version.0 = STRING: "9.1"

// Deliberant

// DLB-802DOT11-EXT-MIB

$mib = 'DLB-802DOT11-EXT-MIB';
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.32761.3.5';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'deliberant';
$config['mibs'][$mib]['descr'] = 'Deliberant 802.11 Extension MIB';

// DLB-RADIO3-DRV-MIB

$mib = 'DLB-RADIO3-DRV-MIB';
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.32761.3.8';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'deliberant';
$config['mibs'][$mib]['descr'] = 'Deliberant 3 series radio driver MIB';


// IGNITENET-MIB

$mib = 'IGNITENET-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.47307.1';
$config['mibs'][$mib]['mib_dir'] = 'ignitenet';
$config['mibs'][$mib]['descr'] = '';

// MITSUBISHI-UPS-MIB

$mib = 'MITSUBISHI-UPS-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'mitsubishi';
$config['mibs'][$mib]['descr'] = 'Mitsubishi UPS';
$config['mibs'][$mib]['version'][] = array('oid' => 'upsIdentUPSSoftwareVersion.0');

// Input
$config['mibs'][$mib]['sensor']['upsInputFrequency']['indexes'][1] = array('descr' => 'Input Frequency', 'class' => 'frequency', 'measured' => 'input', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.3.3.1.2.1');
$config['mibs'][$mib]['sensor']['upsInputVoltage']['indexes'][1] = array('descr' => 'Input Voltage Phase 1', 'class' => 'voltage', 'measured' => 'input', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.3.3.1.3.1');
$config['mibs'][$mib]['sensor']['upsInputVoltage']['indexes'][2] = array('descr' => 'Input Voltage Phase 2', 'class' => 'voltage', 'measured' => 'input', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.3.3.1.3.2');
$config['mibs'][$mib]['sensor']['upsInputVoltage']['indexes'][3] = array('descr' => 'Input Voltage Phase 3', 'class' => 'voltage', 'measured' => 'input', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.3.3.1.3.3');
$config['mibs'][$mib]['sensor']['upsInputVoltage']['indexes'][4] = array('descr' => 'Input Voltage Phase 4', 'class' => 'voltage', 'measured' => 'input', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.3.3.1.3.4');

// Output
$config['mibs'][$mib]['sensor']['upsOutputFrequency']['indexes'][0] = array('descr' => 'Output Frequency', 'class' => 'frequency', 'measured' => 'output', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.2.0');
$config['mibs'][$mib]['sensor']['upsOutputVoltage']['indexes'][1] = array('descr' => 'Output Voltage Phase 1', 'class' => 'voltage', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.2.1');
$config['mibs'][$mib]['sensor']['upsOutputVoltage']['indexes'][2] = array('descr' => 'Output Voltage Phase 2', 'class' => 'voltage', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.2.2');
$config['mibs'][$mib]['sensor']['upsOutputVoltage']['indexes'][3] = array('descr' => 'Output Voltage Phase 3', 'class' => 'voltage', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.2.3');
$config['mibs'][$mib]['sensor']['upsOutputVoltage']['indexes'][4] = array('descr' => 'Output Voltage Phase 4', 'class' => 'voltage', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.2.4');
$config['mibs'][$mib]['sensor']['upsOutputCurrent']['indexes'][1] = array('descr' => 'Output Current Phase 1', 'class' => 'current', 'measured' => 'output', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.3.1');
$config['mibs'][$mib]['sensor']['upsOutputCurrent']['indexes'][2] = array('descr' => 'Output Current Phase 2', 'class' => 'current', 'measured' => 'output', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.3.2');
$config['mibs'][$mib]['sensor']['upsOutputCurrent']['indexes'][3] = array('descr' => 'Output Current Phase 3', 'class' => 'current', 'measured' => 'output', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.3.3');
$config['mibs'][$mib]['sensor']['upsOutputCurrent']['indexes'][4] = array('descr' => 'Output Current Phase 4', 'class' => 'current', 'measured' => 'output', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.3.4');
$config['mibs'][$mib]['sensor']['upsOutputPower']['indexes'][1] = array('descr' => 'Output Power Phase 1', 'class' => 'power', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.4.1');
$config['mibs'][$mib]['sensor']['upsOutputPower']['indexes'][2] = array('descr' => 'Output Power Phase 2', 'class' => 'power', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.4.2');
$config['mibs'][$mib]['sensor']['upsOutputPower']['indexes'][3] = array('descr' => 'Output Power Phase 3', 'class' => 'power', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.4.3');
$config['mibs'][$mib]['sensor']['upsOutputPower']['indexes'][4] = array('descr' => 'Output Power Phase 4', 'class' => 'power', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.4.4');
$config['mibs'][$mib]['sensor']['upsOutputPercentLoad']['indexes'][1] = array('descr' => 'Output Load Phase 1', 'class' => 'load', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.5.1');
$config['mibs'][$mib]['sensor']['upsOutputPercentLoad']['indexes'][2] = array('descr' => 'Output Load Phase 2', 'class' => 'load', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.5.2');
$config['mibs'][$mib]['sensor']['upsOutputPercentLoad']['indexes'][3] = array('descr' => 'Output Load Phase 3', 'class' => 'load', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.5.3');
$config['mibs'][$mib]['sensor']['upsOutputPercentLoad']['indexes'][4] = array('descr' => 'Output Load Phase 4', 'class' => 'load', 'measured' => 'output', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.4.4.1.5.4');

// Bypass
$config['mibs'][$mib]['sensor']['upsBypassFrequency']['indexes'][0] = array('descr' => 'Bypass Frequency', 'class' => 'frequency', 'measured' => 'bypass', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.1.0');
$config['mibs'][$mib]['sensor']['upsBypassVoltage']['indexes'][1] = array('descr' => 'Bypass Voltage Phase 1', 'class' => 'voltage', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.2.1');
$config['mibs'][$mib]['sensor']['upsBypassVoltage']['indexes'][2] = array('descr' => 'Bypass Voltage Phase 2', 'class' => 'voltage', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.2.2');
$config['mibs'][$mib]['sensor']['upsBypassVoltage']['indexes'][3] = array('descr' => 'Bypass Voltage Phase 3', 'class' => 'voltage', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.2.3');
$config['mibs'][$mib]['sensor']['upsBypassVoltage']['indexes'][4] = array('descr' => 'Bypass Voltage Phase 4', 'class' => 'voltage', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.2.4');
$config['mibs'][$mib]['sensor']['upsBypassCurrent']['indexes'][1] = array('descr' => 'Bypass Current Phase 1', 'class' => 'current', 'measured' => 'bypass', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.3.1', 'min' => 0);
$config['mibs'][$mib]['sensor']['upsBypassCurrent']['indexes'][2] = array('descr' => 'Bypass Current Phase 2', 'class' => 'current', 'measured' => 'bypass', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.3.2', 'min' => 0);
$config['mibs'][$mib]['sensor']['upsBypassCurrent']['indexes'][3] = array('descr' => 'Bypass Current Phase 3', 'class' => 'current', 'measured' => 'bypass', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.3.3', 'min' => 0);
$config['mibs'][$mib]['sensor']['upsBypassCurrent']['indexes'][4] = array('descr' => 'Bypass Current Phase 4', 'class' => 'current', 'measured' => 'bypass', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.3.4', 'min' => 0);
$config['mibs'][$mib]['sensor']['upsBypassPower']['indexes'][1] = array('descr' => 'Bypass Power Phase 1', 'class' => 'power', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.4.1');
$config['mibs'][$mib]['sensor']['upsBypassPower']['indexes'][2] = array('descr' => 'Bypass Power Phase 2', 'class' => 'power', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.4.2');
$config['mibs'][$mib]['sensor']['upsBypassPower']['indexes'][3] = array('descr' => 'Bypass Power Phase 3', 'class' => 'power', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.4.3');
$config['mibs'][$mib]['sensor']['upsBypassPower']['indexes'][4] = array('descr' => 'Bypass Power Phase 4', 'class' => 'power', 'measured' => 'bypass', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.5.3.1.4.4');

// Battery
$config['mibs'][$mib]['sensor']['upsEstimatedMinutesRemaining']['indexes'][0] = array('descr' => 'Battery Estimated Runtime', 'class' => 'runtime', 'measured' => 'battery', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.2.3.0', 'oid_limit_low' => 'upsConfigLowBattTime.0');
$config['mibs'][$mib]['sensor']['upsEstimatedChargeRemaining']['indexes'][0] = array('descr' => 'Battery Charge Remaining', 'class' => 'capacity', 'measured' => 'battery', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.2.4.0');
$config['mibs'][$mib]['sensor']['upsBatteryVoltage']['indexes'][0] = array('descr' => 'Battery Voltage', 'class' => 'voltage', 'measured' => 'battery', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.2.5.0');
$config['mibs'][$mib]['sensor']['upsBatteryCurrent']['indexes'][0] = array('descr' => 'Battery Current', 'class' => 'current', 'measured' => 'battery', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.13891.101.2.6.0');
$config['mibs'][$mib]['sensor']['upsBatteryTemperature']['indexes'][0] = array('descr' => 'Battery Temperature', 'class' => 'temperature', 'measured' => 'battery', 'scale' => 1, 'oid_num' => '.1.3.6.1.4.1.13891.101.2.7.0');

// Statuses
$config['mibs'][$mib]['status']['upsBatteryStatus']['indexes'][0] = array('descr' => 'Battery Status', 'measured' => 'battery', 'type' => 'upsBatteryStatus', 'oid_num' => '.1.3.6.1.4.1.13891.101.2.1.0');
$config['mibs'][$mib]['status']['upsOutputSource']['indexes'][0] = array('descr' => 'Output Source', 'measured' => 'output', 'type' => 'upsOutputSource', 'oid_num' => '.1.3.6.1.4.1.13891.101.4.1.0');

// ICT-MIB

$mib = 'ICT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'ict';
$config['mibs'][$mib]['descr'] = 'ICT DC Distribution Panel';


// Perle MCT
$mib = 'PERLE-MCR-MGT-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['identity_num'] = '.1.3.6.1.4.1.1966.20.1.1';
$config['mibs'][$mib]['mib_dir'] = 'perle';
$config['mibs'][$mib]['descr'] = 'Perle MCR MGMT MIB';


// System
$config['mibs'][$mib]['sensor']['systemVoltage']['indexes'][1] = array('descr' => 'System Voltage', 'class' => 'voltage', 'measured' => 'system', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.39145.10.6.0');
$config['mibs'][$mib]['sensor']['systemCurrent']['indexes'][1] = array('descr' => 'System Current', 'class' => 'current', 'measured' => 'system', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.39145.10.7.0');

// Statuses
$config['mibs'][$mib]['status']['outputEnable']['indexes'][0] = array('descr' => 'Output #1', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.0');
$config['mibs'][$mib]['status']['outputEnable']['indexes'][1] = array('descr' => 'Output #2', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.1');
$config['mibs'][$mib]['status']['outputEnable']['indexes'][2] = array('descr' => 'Output #3', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.2');
$config['mibs'][$mib]['status']['outputEnable']['indexes'][3] = array('descr' => 'Output #4', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.3');
$config['mibs'][$mib]['status']['outputEnable']['indexes'][4] = array('descr' => 'Output #5', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.4');
$config['mibs'][$mib]['status']['outputEnable']['indexes'][5] = array('descr' => 'Output #6', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.5');
$config['mibs'][$mib]['status']['outputEnable']['indexes'][6] = array('descr' => 'Output #7', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.6');
$config['mibs'][$mib]['status']['outputEnable']['indexes'][7] = array('descr' => 'Output #8', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.7');
$config['mibs'][$mib]['status']['outputEnable']['indexes'][8] = array('descr' => 'Output #9', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.8');
$config['mibs'][$mib]['status']['outputEnable']['indexes'][9] = array('descr' => 'Output #10', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.9');
$config['mibs'][$mib]['status']['outputEnable']['indexes'][10] = array('descr' => 'Output #11', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.10');
$config['mibs'][$mib]['status']['outputEnable']['indexes'][11] = array('descr' => 'Output #12', 'measured' => 'output', 'type' => 'outputEnable', 'oid_num' => '.1.3.6.1.4.1.39145.10.8.1.5.11');

$type = 'outputEnable';
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'ENABLED', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][2] = array('name' => 'DISABLED', 'event' => 'warning');

// WTI-RSM-TSM-MIB

$mib = 'WTI-RSM-TSM-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'wti';
$config['mibs'][$mib]['descr'] = 'Western Telematic RSM/TSM MIB';
$config['mibs'][$mib]['hardware'][] = array('oid' => 'environmentUnitName.1');

// AlphaPowerSystem-MIB

/*
.1.3.6.1.4.1.7309.4.1.2.1.0 = STRING: "rectifier.domain.com"           dcPwrSysSiteName
.1.3.6.1.4.1.7309.4.1.2.2.0 = STRING: "Lps Angeles"                    dcPwrSysSiteCity
.1.3.6.1.4.1.7309.4.1.2.3.0 = STRING: "CA"                             dcPwrSysSiteRegion
.1.3.6.1.4.1.7309.4.1.2.4.0 = STRING: "USA"                            dcPwrSysSiteCountry
.1.3.6.1.4.1.7309.4.1.2.5.0 = STRING: "support@domain.net"             dcPwrSysContactName
.1.3.6.1.4.1.7309.4.1.2.6.0 = STRING: "555-555-1234"                   dcPwrSysPhoneNumber
.1.3.6.1.4.1.7309.4.1.2.7.0 = STRING: "123456789"                      dcPwrSysSiteNumber
.1.3.6.1.4.1.7309.4.1.2.8.0 = STRING: "CXC"                            dcPwrSysSystemType
.1.3.6.1.4.1.7309.4.1.2.9.0 = STRING: "AA90286-10-001"                 dcPwrSysSystemSerial
.1.3.6.1.4.1.7309.4.1.2.10.0 = STRING: "0540571-001"                   dcPwrSysSystemNumber
.1.3.6.1.4.1.7309.4.1.2.11.0 = STRING: "2.27"                          dcPwrSysSoftwareVersion
.1.3.6.1.4.1.7309.4.1.2.12.0 = STRING: "2015/09/25 11:38:26"           dcPwrSysSoftwareTimestamp
*/

$mib = 'AlphaPowerSystem-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'alpha';
$config['mibs'][$mib]['descr'] = 'Alpha Technology CXC';

$config['mibs'][$mib]['states']['alpha-power-state'][0] = array('name' => 'normal', 'event' => 'ok');
$config['mibs'][$mib]['states']['alpha-power-state'][1] = array('name' => 'alarm', 'event' => 'alert');

$config['mibs'][$mib]['hardware'][] = array('oid' => 'dcPwrSysSystemType.0');
$config['mibs'][$mib]['serial'][] = array('oid' => 'dcPwrSysSystemSerial.0');
$config['mibs'][$mib]['version'][] = array('oid' => 'dcPwrSysSoftwareVersion.0');

$config['mibs'][$mib]['sensor']['dcPwrSysChargeVolts']['indexes'][0] = array('descr'    => 'Charge Voltage',
                                                                             'class'    => 'voltage',
                                                                             'measured' => 'system',
                                                                             'scale'    => 0.01,
                                                                             'oid_num'  => '.1.3.6.1.4.1.7309.4.1.1.1.0');

$config['mibs'][$mib]['sensor']['dcPwrSysDischargeVolts']['indexes'][0] = array('descr'    => 'Load Voltage',
                                                                                'class'    => 'voltage',
                                                                                'measured' => 'system',
                                                                                'scale'    => 0.01,
                                                                                'oid_num'  => '.1.3.6.1.4.1.7309.4.1.1.2.0');

$config['mibs'][$mib]['sensor']['dcPwrSysChargeAmps']['indexes'][0] = array('descr'    => 'Charge Current',
                                                                            'class'    => 'current',
                                                                            'measured' => 'system',
                                                                            'scale'    => 0.01,
                                                                            'oid_num'  => '.1.3.6.1.4.1.7309.4.1.1.3.0');

$config['mibs'][$mib]['sensor']['dcPwrSysDischargeAmps']['indexes'][0] = array('descr'    => 'Load Current',
                                                                               'class'    => 'current',
                                                                               'measured' => 'system',
                                                                               'scale'    => 0.01,
                                                                               'oid_num'  => '.1.3.6.1.4.1.7309.4.1.1.4.0');

$config['mibs'][$mib]['status']['dcPwrSysMajorAlarm']['indexes'][0] = array('descr'    => 'Major Alarm',
                                                                            'measured' => 'system',
                                                                            'type'     => 'upsBatteryStatus',
                                                                            'oid_num'  => '.1.3.6.1.4.1.7309.4.1.1.5.0');

$config['mibs'][$mib]['status']['dcPwrSysMinorAlarm']['indexes'][0] = array('descr'    => 'Minor Alarm',
                                                                            'measured' => 'system',
                                                                            'type'     => 'upsOutputSource',
                                                                            'oid_num'  => '.1.3.6.1.4.1.7309.4.1.1.6.0');

$mib = 'Argus-Power-System-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'alpha';
$config['mibs'][$mib]['descr'] = 'Alpha Technologies CXC RMU MIB';

/*
 * Argus-Power-System-MIB::upsIdentManufacturer.0 = STRING: "Alpha Technologies ."
 * Argus-Power-System-MIB::upsIdentProductCode.0 = STRING: "FXM1100    "
 * Argus-Power-System-MIB::upsIdentModel.0 = STRING: "03508401"
 * Argus-Power-System-MIB::upsIdentUPSSoftwareVersion.0 = STRING: "1.08.01"
 * Argus-Power-System-MIB::upsIdentAgentSoftwareVersion.0 = STRING: "1.0780"
 * Argus-Power-System-MIB::upsIdentName.0 = ""
 * Argus-Power-System-MIB::upsIdentAttachedDevices.0 = STRING: "RMU"
 * Argus-Power-System-MIB::upsIdentSiteName.0 = STRING: "Site"
 * Argus-Power-System-MIB::upsIdentSiteCity.0 = STRING: "City"
 * Argus-Power-System-MIB::upsIdentSiteRegion.0 = STRING: "State"
 * Argus-Power-System-MIB::upsIdentSiteCountry.0 = STRING: "USA"
 * Argus-Power-System-MIB::upsIdentContactName.0 = STRING: "Derp LLC"
 * Argus-Power-System-MIB::upsIdentPhoneNumber.0 = STRING: "509-999-9999"
 * Argus-Power-System-MIB::upsIdentDate.0 = STRING: "2017-09-19"
 * Argus-Power-System-MIB::upsIdentTime.0 = STRING: "20:53:02"
 */

$config['mibs'][$mib]['hardware'][] = array('oid' => 'upsIdentProductCode.0');
$config['mibs'][$mib]['serial'][]   = array('oid' => 'upsIdentModel.0');
$config['mibs'][$mib]['version'][]  = array('oid' => 'upsIdentUPSSoftwareVersion.0');

/*
 * Argus-Power-System-MIB::upsBatteryStatus.0 = batteryNormal
 * Argus-Power-System-MIB::upsMinutesOnBattery.0 = 5992
 * Argus-Power-System-MIB::upsBatteryVoltage.0 = 544
 * Argus-Power-System-MIB::upsBatteryChargingCurrent.0 = 0
 * Argus-Power-System-MIB::upsBatteryCapacity.0 = 0
 * Argus-Power-System-MIB::upsBatteryTemperature.0 = 8
 * Argus-Power-System-MIB::upsBatteryLowWarning.0 = 40
 */

$config['mibs'][$mib]['sensor']['upsBatteryVoltage']['indexes'][0] = array('descr'    => 'UPS Battery Voltage',
                                                                             'class'    => 'voltage',
                                                                             'measured' => 'battery',
                                                                             'scale'    => 0.1,
                                                                             'oid_num'  => '.1.3.6.1.4.1.7309.6.1.2.3.0');

$config['mibs'][$mib]['sensor']['upsBatteryChargingCurrent']['indexes'][0] = array('descr'    => 'UPS Battery Charging Current',
                                                                           'class'    => 'current',
                                                                           'measured' => 'battery',
                                                                           'scale'    => 0.1,
                                                                           'oid_num'  => '.1.3.6.1.4.1.7309.6.1.2.4.0');

$config['mibs'][$mib]['sensor']['upsBatteryCapacity']['indexes'][0] = array('descr'    => 'UPS Battery Capacity',
                                                                                   'class'    => 'current',
                                                                                   'measured' => 'battery',
                                                                                   'scale'    => 0.1,
                                                                                   'oid_num'  => '.1.3.6.1.4.1.7309.6.1.2.5.0');

$config['mibs'][$mib]['sensor']['upsBatteryTemperature']['indexes'][0] = array('descr'    => 'UPS Battery',
                                                                                   'class'    => 'temperature',
                                                                                   'measured' => 'battery',
                                                                                   'scale'    => 1,
                                                                                     'oid_num'  => '.1.3.6.1.4.1.7309.6.1.2.6.0');
/*
enterprises.argus.upsPower.upsDevice.upsInput.upsInputTable.upsInputEntry.upsInputFrequency.1 = INTEGER: 59 Hertz
enterprises.argus.upsPower.upsDevice.upsInput.upsInputTable.upsInputEntry.upsInputVoltage.1 = INTEGER: 1190 0.1 RMS Volts
*/

$config['mibs'][$mib]['sensor']['upsInputTable']['tables'][] = array(
  'scale'  => 0.1, 'oid'  => 'upsInputVoltage', 'descr'  => 'UPS Input %i%', 'class' => 'voltage', 'oid_num' => '.1.3.6.1.4.1.7309.6.1.3.2.1.3');

$config['mibs'][$mib]['sensor']['upsInputTable']['tables'][] = array(
  'oid'  => 'upsInputFrequency', 'descr' => 'UPS Input %i%', 'class' => 'frequency', 'oid_num'   => '.1.3.6.1.4.1.7309.6.1.3.2.1.2');

/*
enterprises.argus.upsPower.upsDevice.upsOutput.upsOutputFrequency.0 = INTEGER: 600 0.1 Hertz
enterprises.argus.upsPower.upsDevice.upsOutput.upsOutputNumLines.0 = INTEGER: 2
enterprises.argus.upsPower.upsDevice.upsOutput.upsOutputTable.upsOutputEntry.upsOutputVoltage.1 = INTEGER: 1160 0.1 RMS Volts
enterprises.argus.upsPower.upsDevice.upsOutput.upsOutputTable.upsOutputEntry.upsOutputCurrent.1 = INTEGER: 14 0.1 RMS Amp
enterprises.argus.upsPower.upsDevice.upsOutput.upsOutputTable.upsOutputEntry.upsOutputPowerVA.1 = INTEGER: 162 VA
enterprises.argus.upsPower.upsDevice.upsOutput.upsOutputTable.upsOutputEntry.upsOutputPowerWatt.1 = INTEGER: 150 Watts
enterprises.argus.upsPower.upsDevice.upsOutput.upsOutputTable.upsOutputEntry.upsPowerFactor.1 = INTEGER: 86 percent
enterprises.argus.upsPower.upsDevice.upsOutput.upsOutputTable.upsOutputEntry.upsOutputPercentLoad.1 = INTEGER: 0 percent
*/

$config['mibs'][$mib]['sensor']['upsOutputFrequency']['indexes'][0] = array(
  'descr' => 'UPS Output Frequency', 'class' => 'frequency', 'measured' => 'system', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.7309.6.1.4.2.0' );

$config['mibs'][$mib]['sensor']['upsOutputTable']['tables'][] = array(
  'scale'  => 0.1, 'oid'  => 'upsOutputVoltage', 'descr'  => 'UPS Output %i%', 'class' => 'voltage', 'oid_num' => '.1.3.6.1.4.1.7309.6.1.4.4.1.2');

$config['mibs'][$mib]['sensor']['upsOutputTable']['tables'][] = array(
  'scale'  => 0.1, 'oid'  => 'upsOutputCurrent', 'descr'  => 'UPS Output %i%', 'class' => 'current', 'oid_num' => '.1.3.6.1.4.1.7309.6.1.4.4.1.3');

$config['mibs'][$mib]['sensor']['upsOutputTable']['tables'][] = array(
  'oid'  => 'upsOutputPowerVA', 'descr'  => 'UPS Output %i%', 'class' => 'apower', 'oid_num' => '.1.3.6.1.4.1.7309.6.1.4.4.1.4');

$config['mibs'][$mib]['sensor']['upsOutputTable']['tables'][] = array(
  'oid'  => 'upsOutputPowerWatt', 'descr'  => 'UPS Output %i%', 'class' => 'power', 'oid_num' => '.1.3.6.1.4.1.7309.6.1.4.4.1.5');

$config['mibs'][$mib]['sensor']['upsOutputTable']['tables'][] = array(
  'scale' => 0.01, 'oid'  => 'upsPowerFactor', 'descr'  => 'UPS Output %i%', 'class' => 'powerfactor', 'oid_num' => '.1.3.6.1.4.1.7309.6.1.4.4.1.6');

$config['mibs'][$mib]['sensor']['upsOutputTable']['tables'][] = array(
  'oid'  => 'upsOutputPercentLoad', 'descr'  => 'UPS Output %i%', 'class' => 'load', 'oid_num' => '.1.3.6.1.4.1.7309.6.1.4.4.1.7');


$config['mibs'][$mib]['status']['upsAlarmTable']['tables'][] = array(
  'oid_descr'        => 'upsAlarmDescr',
  'oid'              => 'upsAlarmStatus',
  'oid_num'          => '.1.3.6.1.4.1.7309.6.1.5.2.1.3',
  'measured'         => 'system',
  'type'             => 'upsAlarmStatus'
);

$type = 'upsAlarmStatus';
$config['mibs'][$mib]['states'][$type][0] = array('name' => 'ok', 'event' => 'ok');
$config['mibs'][$mib]['states'][$type][1] = array('name' => 'alarm', 'event' => 'alert');

// Teltonika

$mib = 'TELTONIKA-MIB';
$config['mibs'][$mib]['enable'] = 1;
$config['mibs'][$mib]['mib_dir'] = 'teltonika';
$config['mibs'][$mib]['descr'] = 'Teltonika LTE/3G router MIB';

/*
TELTONIKA-MIB::ModemImei.0 = STRING: 351622070326448
TELTONIKA-MIB::ModemModel.0 = STRING: LE910-EU V2
TELTONIKA-MIB::ModemManufacturer.0 = STRING: Telit
TELTONIKA-MIB::ModemRevision.0 = STRING: 20.00.402
TELTONIKA-MIB::ModemSerial.0 = STRING: 0001032643
TELTONIKA-MIB::Imsi.0 = STRING: 246020101190188
TELTONIKA-MIB::RouterName.0 = STRING: RUT950
TELTONIKA-MIB::ProductCode.0 = STRING: RUT950GG12C0
TELTONIKA-MIB::BatchNumber.0 = STRING: 0030
TELTONIKA-MIB::HardwareRevision.0 = STRING: 0808
TELTONIKA-MIB::SimState.0 = STRING: inserted
TELTONIKA-MIB::PinState.0 = STRING: READY
TELTONIKA-MIB::NetState.0 = STRING: registered (home)
 */

$config['mibs'][$mib]['hardware'][] = array('oid' => 'ProductCode.0');
$config['mibs'][$mib]['version'][]  = array('oid' => 'FirmwareVersion.0');
$config['mibs'][$mib]['features'][]  = array('oid' => 'ModemModel.0');

$config['mibs'][$mib]['sensor']['Temperature']['indexes'][0] = array(
  'descr' => 'Temperature', 'class' => 'temperature', 'measured' => 'system', 'scale' => 0.1, 'oid_num' => '.1.3.6.1.4.1.48690.2.9.0' );

$config['mibs'][$mib]['sensor']['Signal']['indexes'][0] = array(
  'descr' => 'Signal Level', 'class' => 'dbm', 'measured' => 'modem', 'oid_num' => '.1.3.6.1.4.1.48690.2.4.0' );