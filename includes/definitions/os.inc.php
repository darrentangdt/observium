<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage definitions
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

/////////////////////////////////////////////////////////
//  NO CHANGES TO THIS FILE, IT IS NOT USER-EDITABLE   //
/////////////////////////////////////////////////////////
//               YES, THAT MEANS YOU                   //
/////////////////////////////////////////////////////////

/*
 * Notes about 'os' definitions.
 *
 * $os - is main OS name. Used for per 'os' purposes in poller/discovery/web
 * $os_group - same as $os, but uses common options for this group
 *
 * BACKEND:
 * $config['os'][$os]['vendor']               (string) vendor name for this os/group
 * $config['os'][$os]['group']                (string) sets os_group for this os
 * $config['os'][$os]['type']                 (string) sets type for this os. Must be one of specified in $config['device_types']
 * $config['os'][$os]['model']                (string) link to per hw/model specific definitions and hardware rewrites based on sysObjectID (in definitions/models.inc.php)
 * $config['os'][$os]['mibs']                 (array)  list of supported MIBs
 * $config['os'][$os]['mib_blacklist']        (array)  list of blacklisted MIBs (exclude default and group mibs)
 * $config['os'][$os]['snmp']['noincrease']   (bool)   do not check returned OIDs are increasing in snmpwalk if the OS does not support it correctly
 * $config['os'][$os]['snmp']['nobulk']       (bool)   turn off bulkwalk if the OS does not support it correctly
 * $config['os'][$os]['snmp']['max-rep']      (int)    sets maximum repetitions in SNMP getbulk PDU
 * $config['os'][$os]['sysObjectID']          (array)  list of sysObjectIDs matching this device, for OS discovery.
 * $config['os'][$os]['sysDescr']             (array)  regexp list of sysDescr matching this device, for OS discovery.
 * $config['os'][$os]['discovery']            (array)  list of complex arrays for Detect OS with set of combination OIDs,
 *                                                     should match all OIDs in set (but each single OID can be array too),
 *                                                     see examples here: http://docs.observium.org/developing/add_os/
 * \-> ['sysDescr']                           (string,array) sysObjectIDs matching this device.
 * \-> ['sysObjectID']                        (string,array) regexp list of sysDescr matching this device.
 * \-> ['sysName']                            (string,array) regexp list of sysName matching this device.
 * \-> ['NAME-MIB::Oid.X']                    (string,array) regexp list of any custom MIB and OID matching this device. NOTE: MIB should be defined in MIBs definitions!
 * \-> ['.X.X.X.X.X.X']                       (string,array) regexp list of any custom NUMERIC OID matching this device.
 * $config['os'][$os]['sysDescr_regex']       (array)  array of regex to match hardware/serial/... to match against sysDescr
 * $config['os'][$os]['snmpable']             (array)  list of numeric OIDs which must check if os not have sysObjectID.0 and sysUpTime.0
 * $config['os'][$os]['discovery_order']      (array)  list of discovery modules with required order
 *                                                     (key: module name, value: name of module after which should run or special words - first, last)
 *                                                     note: first/last orders if faster!
 * $config['os'][$os]['poller_blacklist']     (array)  list of blacklisted poller modules
 * $config['os'][$os]['discovery_blacklist']  (array)  list of blacklisted discovery modules
 * $config['os'][$os]['discovery_os']         (string) this is used only for detect OS in get_device_os().
 *                                                     Used when OS discovered by filename does not match includes/discovery/os/$os.inc.php
 * $config['os'][$os]['port_label']           (array)  regexp list with ports ifDescr (port_label) processing (replace text with first founded subpattern)
 * $config['os'][$os]['ifAlias_ifDescr']      (bool)   FIXME, not used anymore? copies ifDescr to ifAlias if ifAlias isn't the same as ifName
 * $config['os'][$os]['ifType_ifDescr']       (bool)   Generate ifDescr based on ifType and ifIndex if ifDescr empty
 * $config['os'][$os]['ifname']               (bool)   use ifName instead of ifDescr as a port name
 * $config['os'][$os]['ports_skip_ifType']    (bool)   skip check empty ifType for ports discovery, see: is_port_valid()
 * $config['os'][$os]['ports_unignore_descr'] (bool)   enable to show all ports on the "Device Traffic" graph bypassing ignores.
 * $config['os'][$os]['ports_separate_walk']  (bool)   force use separate ports polling feature (currently only if ports count >10)
 * $config['os'][$os]['ipmi']                 (bool)   indicates possible support of IPMI protocol
 * $config['os'][$os]['uptime_max']           (array)  additional (known) uptime rollover counter in seconds,
 *                                                     this uptimes skipped in detect reboot alert (device_rebooted)
 *                                                     (for 'sysUpTime', 'hrSystemUptime', 'snmpEngineTime')
 *
 * WEB:
 * $config['os'][$os]['text']                 (string) is OS name displayed on web pages
 * $config['os'][$os]['icon']                 (string) icon name displayed for os
 * $config['os'][$os]['icons']                (array)  list if possible alternative icons, selectable in the web interface or settable by code
 * $config['os'][$os]['graphs']               (array)  this is displaying options for a web pages;
 * \-> $config['os'][$os]['graphs'][x]        (string) sets the graph type.
 * $config['os'][$os]['processor_stacked']    (bool)   use stacked processor graph
 * $config['os'][$os]['realtime']             (int)    default interval setting (in seconds) for the realtime graph page
 * $config['os'][$os]['comments']             (string) Regexp! Here regular expression for ignore device comments on show device config page
 * $config['os'][$os]['remote_access']        (array)  possible remote access methods for this device ('telnet', 'ssh', 'scp', 'http')
 *
 */

// This is pseudo-group, used when nothing set for os
$config['os_group']['default']['graphs'][]          = "device_bits";
$config['os_group']['default']['graphs'][]          = "device_uptime";
$config['os_group']['default']['graphs'][]          = "device_ping";
$config['os_group']['default']['graphs'][]          = "device_poller_perf";
$config['os_group']['default']['comments']          = "/^\s*#/"; // rancid/config comments
// MIBs enabled for any os (except blacklisted mibs)
$config['os_group']['default']['mibs'][]            = "SNMPv2-MIB";
$config['os_group']['default']['mibs'][]            = "SNMP-FRAMEWORK-MIB";
$config['os_group']['default']['mibs'][]            = "ADSL-LINE-MIB";  // in ports module
$config['os_group']['default']['mibs'][]            = "EtherLike-MIB";  // in ports module
$config['os_group']['default']['mibs'][]            = "ENTITY-MIB";
$config['os_group']['default']['mibs'][]            = "ENTITY-SENSOR-MIB";
$config['os_group']['default']['mibs'][]            = "CISCO-ENTITY-VENDORTYPE-OID-MIB"; // Inventory module
$config['os_group']['default']['mibs'][]            = "UCD-SNMP-MIB";   // Should be before HOST-RESOURCES-MIB (in storage)
$config['os_group']['default']['mibs'][]            = "HOST-RESOURCES-MIB";
$config['os_group']['default']['mibs'][]            = "Q-BRIDGE-MIB";
$config['os_group']['default']['mibs'][]            = "LLDP-MIB";       // Should be before CISCO-CDP-MIB, but I not know why (in neighbours)
$config['os_group']['default']['mibs'][]            = "CISCO-CDP-MIB";
$config['os_group']['default']['mibs'][]            = "PW-STD-MIB";     // Pseudowires. FIXME, possible more os specific?
$config['os_group']['default']['mibs'][]            = "DISMAN-PING-MIB";// RFC4560, SLA
$config['os_group']['default']['mibs'][]            = "BGP4-MIB";
$config['os_group']['default']['mibs'][]            = "OSPF-MIB";
$config['os_group']['default']['mibs'][]            = "IP-MIB";
$config['os_group']['default']['mibs'][]            = "IPV6-MIB";

// Group definitions

$os_group = "unix";
$config['os_group'][$os_group]['type']              = "server";
$config['os_group'][$os_group]['processor_stacked'] = 1;
$config['os_group'][$os_group]['graphs'][]          = "device_processor";
$config['os_group'][$os_group]['graphs'][]          = "device_ucd_memory";
$config['os_group'][$os_group]['remote_access']     = array('telnet', 'ssh', 'scp', 'http');
$config['os_group'][$os_group]['mibs'][]            = "MIB-Dell-10892";        // Dell OpenManage agent MIB
$config['os_group'][$os_group]['mibs'][]            = "CPQSINFO-MIB";          // HP/Compaq agent MIB
$config['os_group'][$os_group]['mibs'][]            = "SUPERMICRO-HEALTH-MIB"; // Supermicro agent MIB
$config['os_group'][$os_group]['mibs'][]            = "LSI-MegaRAID-SAS-MIB";  // LSI/Intel/... agent MIB
$config['os_group'][$os_group]['mibs'][]            = "UCD-SNMP-MIB";          // Should be before HOST-RESOURCES-MIB (in storage)
$config['os_group'][$os_group]['mibs'][]            = "HOST-RESOURCES-MIB";    // There duplicate entry as in default, for correct order!
$config['os_group'][$os_group]['ipmi']              = TRUE;

$os_group = "printer";
$config['os_group'][$os_group]['type']              = "printer";
$config['os_group'][$os_group]['graphs'][]          = "device_printersupplies";
$config['os_group'][$os_group]['remote_access']     = array('http');
$config['os_group'][$os_group]['mibs'][]            = "Printer-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "CISCO-CDP-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "PW-STD-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "BGP4-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "OSPF-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "Q-BRIDGE-MIB";
$config['os_group'][$os_group]['discovery_blacklist'][] = "cisco-cbqos";
$config['os_group'][$os_group]['discovery_blacklist'][] = "cisco-vrf";

$os_group = "environment";
$config['os_group'][$os_group]['type']              = "environment";
$config['os_group'][$os_group]['remote_access']     = array('http');
$config['os_group'][$os_group]['mib_blacklist'][]   = "BGP4-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "OSPF-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "PW-STD-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "Q-BRIDGE-MIB";
$config['os_group'][$os_group]['discovery_blacklist'][] = "cisco-cbqos";
$config['os_group'][$os_group]['discovery_blacklist'][] = "cisco-vrf";

$os_group = "sensorprobe";
$config['os_group'][$os_group]['type']              = "environment";
$config['os_group'][$os_group]['graphs'][]          = "device_temperature";
$config['os_group'][$os_group]['graphs'][]          = "device_humidity";
$config['os_group'][$os_group]['remote_access']     = array('http');
$config['os_group'][$os_group]['mibs'][]            = "SPAGENT-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "BGP4-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "OSPF-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "PW-STD-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "Q-BRIDGE-MIB";

$os_group = "ups";
$config['os_group'][$os_group]['type']              = "power";
$config['os_group'][$os_group]['remote_access']     = array('http');
$config['os_group'][$os_group]['mib_blacklist'][]   = "BGP4-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "OSPF-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "PW-STD-MIB";
$config['os_group'][$os_group]['discovery_blacklist'][] = "cisco-cbqos";
$config['os_group'][$os_group]['discovery_blacklist'][] = "cisco-vrf";

$os_group = "pdu";
$config['os_group'][$os_group]['type']              = "power";
$config['os_group'][$os_group]['remote_access']     = array('http');
$config['os_group'][$os_group]['mib_blacklist'][]   = "BGP4-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "OSPF-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "PW-STD-MIB";
$config['os_group'][$os_group]['discovery_blacklist'][] = "cisco-cbqos";
$config['os_group'][$os_group]['discovery_blacklist'][] = "cisco-vrf";

$os_group = "cisco";
$config['os_group'][$os_group]['vendor']            = "Cisco";
$config['os_group'][$os_group]['type']              = "network";
$config['os_group'][$os_group]['graphs'][]          = "device_bits";
$config['os_group'][$os_group]['graphs'][]          = "device_processor";
$config['os_group'][$os_group]['graphs'][]          = "device_mempool";
$config['os_group'][$os_group]['comments']          = "/^\s*!/";
$config['os_group'][$os_group]['remote_access']     = array('telnet', 'ssh', 'scp', 'http');
$config['os_group'][$os_group]['discovery_order']   = array('storage' => 'last'); // Run storage module as last, because on cisco it freeze device for long time
$config['os_group'][$os_group]['mibs'][]            = "CISCO-ENTITY-SENSOR-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-ENTITY-QFP-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-ENTITY-PFE-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-VTP-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-ENVMON-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-ENTITY-FRU-CONTROL-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-IP-STAT-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-IPSEC-FLOW-MONITOR-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-REMOTE-ACCESS-MONITOR-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-VPDN-MGMT-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-ENHANCED-MEMPOOL-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-MEMORY-POOL-MIB"; // Keep this below CISCO-ENHANCED-MEMPOOL-MIB, checks for duplicates.
$config['os_group'][$os_group]['mibs'][]            = "CISCO-PROCESS-MIB";     // Goes after "CISCO-MEMORY-POOL-MIB" and "CISCO-ENHANCED-MEMPOOL-MIB" cos Cisco suck.
$config['os_group'][$os_group]['mibs'][]            = "CISCO-EIGRP-MIB";       // FIXME. Seems this MIB supported only in IOS Catalyst. See ftp://ftp.cisco.com/pub/mibs/supportlists/
$config['os_group'][$os_group]['mibs'][]            = "CISCO-CEF-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-IETF-PW-MIB";     // Pseudowires
$config['os_group'][$os_group]['mibs'][]            = "CISCO-BGP4-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-RTTMON-MIB";      // SLA
$config['os_group'][$os_group]['mibs'][]            = "CISCO-FLASH-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-POWER-ETHERNET-EXT-MIB";
$config['os_group'][$os_group]['mibs'][]            = "CISCO-AAA-SESSION-MIB";
$config['os_group'][$os_group]['mibs'][]            = "POWER-ETHERNET-MIB";
$config['os_group'][$os_group]['mibs'][]            = "MPLS-L3VPN-STD-MIB";    // VRF
$config['os_group'][$os_group]['mibs'][]            = "MPLS-VPN-MIB";          // VRF
$config['os_group'][$os_group]['mibs'][]            = "SMON-MIB";              // Monitoring ([e|r]span) ports
$config['os_group'][$os_group]['mibs'][]            = "CISCO-TRUSTSEC-INTERFACE-MIB"; // TrustSec port status
$config['os_group'][$os_group]['mibs'][]            = "CISCO-IETF-IP-MIB";     // IPv6 addresses
$config['os_group'][$os_group]['mib_blacklist'][]   = "PW-STD-MIB";            // Exclude standart pseudowires
$config['os_group'][$os_group]['mib_blacklist'][]   = "DISMAN-PING-MIB";       // Exclude RFC PING-MIB
$config['os_group'][$os_group]['mib_blacklist'][]   = "IPV6-MIB";

$os_group = "radlan";
$config['os_group'][$os_group]['type']              = "network";
$config['os_group'][$os_group]['ifname']            = 1;
//$config['os_group'][$os_group]['snmp']['max-rep']   = 100;
//$config['os_group'][$os_group]['ports_separate_walk'] = 1; // Force use separate ports polling feature
$config['os_group'][$os_group]['mibs'][]            = "RADLAN-HWENVIROMENT";
$config['os_group'][$os_group]['mibs'][]            = "RADLAN-Physicaldescription-MIB";
$config['os_group'][$os_group]['mibs'][]            = "RADLAN-rndMng";
$config['os_group'][$os_group]['mibs'][]            = "POWER-ETHERNET-MIB";
$config['os_group'][$os_group]['mibs'][]            = "MARVELL-POE-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "HOST-RESOURCES-MIB";

$os_group = "fastpath";
$config['os_group'][$os_group]['type']              = "network";
$config['os_group'][$os_group]['ifname']            = 1;
//$config['os_group'][$os_group]['ports_separate_walk'] = 1; // Force use separate ports polling feature
//$config['os_group'][$os_group]['mibs'][]            = "FASTPATH-BOXSERVICES-PRIVATE-MIB";
$config['os_group'][$os_group]['mibs'][]            = "POWER-ETHERNET-MIB";
//$config['os_group'][$os_group]['mibs'][]            = "BROADCOM-POWER-ETHERNET-MIB";
//$config['os_group'][$os_group]['mibs'][]            = "FASTPATH-SWITCHING-MIB";
//$config['os_group'][$os_group]['mibs'][]            = "FASTPATH-ISDP-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "HOST-RESOURCES-MIB";

// Devices which not support anything except own enterprise tree MIBs
$os_group = "enterprise_tree_only";
//$config['os_group'][$os_group]['mib_blacklist'][]         = "SNMPv2-MIB";
//$config['os_group'][$os_group]['mib_blacklist'][]         = "HOST-RESOURCES-MIB";
//$config['os_group'][$os_group]['mib_blacklist'][]         = "ENTITY-MIB";
//$config['os_group'][$os_group]['mib_blacklist'][]         = "UCD-SNMP-MIB";
$config['os_group'][$os_group]['mib_blacklist']           = $config['os_group']['default']['mibs']; // Exclude all default MIBs
//$config['os_group'][$os_group]['poller_blacklist'][]      = "ports";
$config['os_group'][$os_group]['poller_blacklist'][]      = "fdb-table";
$config['os_group'][$os_group]['discovery_blacklist'][]   = "ports-stack";
$config['os_group'][$os_group]['discovery_blacklist'][]   = "inventory";
$config['os_group'][$os_group]['discovery_blacklist'][]   = "neighbours";
$config['os_group'][$os_group]['discovery_blacklist'][]   = "bgp-peers";
$config['os_group'][$os_group]['discovery_blacklist'][]   = "pseudowires";
$config['os_group'][$os_group]['discovery_blacklist'][]   = "ucd-diskio";

// Angstrem Telecom

$os = "topaz-switch";
$config['os'][$os]['text']                  = "Topaz Switch";
$config['os'][$os]['icon']                  = "angstrem";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.38838.1";
//$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "radlan";

// Generic (unknown) device OS
$os = "generic";
$config['os'][$os]['text']                  = "Generic Device";
$config['os'][$os]['group']                 = "unix"; // Try detect generic device as generic Unix
$config['os'][$os]['snmpable']              = array();

// Linux-based OSes here please.

$os = "linux";
$config['os'][$os]['text']                  = "Linux";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['graphs'][]              = "device_storage";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['mibs'][]                = "LM-SENSORS-MIB";
$config['os'][$os]['mibs'][]                = "CPQHLTH-MIB";
$config['os'][$os]['mibs'][]                = "CPQIDA-MIB";
$config['os'][$os]['mibs'][]                = "SWRAID-MIB";
$config['os'][$os]['realtime']              = 15;

$os = "vmware";
$config['os'][$os]['text']                  = "VMware";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = '.1.3.6.1.4.1.6876.4.1';
$config['os'][$os]['port_label'][]          = '/Device (.+) at .*/';                       // Device vmnic7 at 08:00.1 bnx2
$config['os'][$os]['port_label'][]          = '/Traditional Virtual VMware switch: (.+)/'; // Traditional Virtual VMware switch: vSwitchISCSI
$config['os'][$os]['port_label'][]          = '/Virtual interface: (.+) on /';             // Virtual interface: vmk2 on vswitch vSwitchISCSI portgroup: iSCSI0
$config['os'][$os]['port_label'][]          = '/(Link Aggregation .+) on /';               // Link Aggregation VM_iSCSI on switch: vSwitchISCSI, load balancing algorithm: source port id hash

$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['mibs'][]                = "LM-SENSORS-MIB";
$config['os'][$os]['mibs'][]                = "VMWARE-VMINFO-MIB";
//$config['os'][$os]['mib_blacklist'][]       = "BGP4-MIB";

$os = "qnap";
$config['os'][$os]['text']                  = "QNAP TurboNAS";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysDescr'][]            = "/QNAP/";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^Linux [A-Z]{2}\-\w+ \d[\d\.]+/',
  //'sysObjectID'                             => '.1.3.6.1.4.1.24681', // This OS not have sysObjectID!
  'ENTITY-MIB::entPhysicalMfgName.1'        => '/QNAP/',
);
$config['os'][$os]['realtime']              = 15;
$config['os'][$os]['mibs'][]                = "NAS-MIB";

$os = "dss";
$config['os'][$os]['text']                  = "Open-E DSS";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "open-e";
$config['os'][$os]['realtime']              = 15;
$config['os'][$os]['sysDescr'][]            = "/Open-E/";

$os = "vyatta";
$config['os'][$os]['text']                  = "Vyatta Core";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
//Vyatta VC6.2-2011.02.09
//Vyatta Vyatta Core 6.0 Beta 2010.02.19
$config['os'][$os]['sysDescr_regex'][]      = '/Vyatta (?:[a-z ]+)(?<version>[\d\.]+)/i';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.30803";
$config['os'][$os]['sysDescr'][]            = "/^Vyatta (?!VyOS)/";

$os = "vyos";
$config['os'][$os]['text']                  = "VyOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
//Vyatta VyOS 1.1.3
//VyOS 1.2.0
//VyOS 999.lithium.06101842
$config['os'][$os]['sysDescr_regex'][]      = '/VyOS (?<version>\d[\w\.]+)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.44641";
$config['os'][$os]['sysDescr'][]            = "/^(Vyatta )*VyOS/i";

$os = "endian";
$config['os'][$os]['text']                  = "Endian Firewall";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^Linux \S+ .*endian/',
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
);
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";

$os = "openwrt";
$config['os'][$os]['text']                  = "OpenWrt";
$config['os'][$os]['type']                  = "network"; /// Or wireless, or firewall?
//$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysDescr'][]            = '/OpenWrt/';
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^Linux /',
  //'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysName'                                 => '/OpenWrt|rt\-is\-prober|HeartOfGold/',
);
//$config['os'][$os]['discovery'][]           = array(
//  'sysDescr'                                => '/^Linux /',
//  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
//  'sysName'                                 => '/OpenWrt|rt\-is\-prober|HeartOfGold/',
//);
//$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";

$os = "ddwrt";
$config['os'][$os]['text']                  = "DD-WRT";
$config['os'][$os]['type']                  = "network"; /// Or wireless, or firewall?
//$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['group']                 = "unix";
//$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['sysDescr'][]            = '/DD-WRT/i';
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^Linux /',
  //'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysName'                                 => '/dd\-?wrt/i',
);
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";

$os = "wut";
$config['os'][$os]['text']                  = "Web-Thermograph";
$config['os'][$os]['vendor']                = "W&T";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5040.1";
$config['os'][$os]['mibs'][]                = "WebGraph-8xThermometer-US-MIB";
$config['os'][$os]['mibs'][]                = "WebGraph-OLD-Thermo-Hygrometer-US-MIB";
$config['os'][$os]['mibs'][]                = "WebGraph-Thermometer-PT-US-MIB";
//$config['os'][$os]['mibs'][]                = "WebGraph-Thermo-Hygrometer-US-MIB";

// BUFFALO

$os = "buffalo-bs";
$config['os'][$os]['text']                  = "BUFFALO Switch";
$config['os'][$os]['type']                  = "network";
//$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['vendor']                = "BUFFALO";
//BUFFALO BS-G2008MR
//BUFFALO BS-POE-G2108M
//BUFFALO BSL-WS-G2016MR
$config['os'][$os]['sysDescr_regex'][]      = '/BUFFALO (?<hardware>BS[\w-]+)/';
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5227.";
$config['os'][$os]['sysDescr'][]            = "/^BUFFALO BS/";

$os = "terastation";
$config['os'][$os]['text']                  = "BUFFALO TeraStation";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['vendor']                = "BUFFALO";
//BUFFALO TeraStation TS-XL Ver.1.62 (2013/11/18 14:18:07)
$config['os'][$os]['sysDescr_regex'][]      = '/(?:BUFFALO TeraStation\ )(?<hardware>[\w-]+) (?:Ver.)(?<version>[\d.]+)/';
$config['os'][$os]['sysDescr'][]            = "/^BUFFALO TeraStation/";

$os = "cumulus-os";
$config['os'][$os]['text']                  = "Cumulus Linux";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "cumulus";
//$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['group']                 = "unix";
//$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.40310";
$config['os'][$os]['mibs'][]                = "LM-SENSORS-MIB";

// Nimble

$os = "nimble-os";
$config['os'][$os]['text']                  = "Nimble Storage";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['icon']                  = "nimble";
$config['os'][$os]['sysDescr'][]            = "/^Nimble Storage/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.37447";
//Nimble Storage XXX-XX running software version 3.4.1.0-382414-opt
$config['os'][$os]['sysDescr_regex'][]      = '/version (?<version>[\d\.]+)/';
$config['os'][$os]['mibs'][]                = "NIMBLE-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

// Firebrick

$os = "firebrick";
$config['os'][$os]['text']                  = "Firebrick";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifdescr']               = 1;
//$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.24693.1";
//FB2700 Orlando+ (V1.36.053C56 2015-09-07T17:17:13)
//TEST Taupi+ (V1.41.009 2016-07-01T17:20:31)
//FB6202 Mercury (V1.34.000 2014-10-24T14:10:58)
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>[\w]+) (?:[\w\+]+) \(V(?<version>\d[\d\.]+)/';
$config['os'][$os]['mibs'][]                = "FIREBRICK-MIB";

// Fireeye
$os = "fireeye";
$config['os'][$os]['text']                  = "Fireeye";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.25597.1',
  'sysDescr'                                => '/^Linux/',
  // FE-FIREEYE-MIB::feHardwareModel.0 = STRING: "FireEyeCMS4400"
  //'FE-FIREEYE-MIB::feHardwareModel.0'       => '/.+/', // non empty
);
$config['os'][$os]['mibs'][]                = "FE-FIREEYE-MIB";

// Check Point

$os = "ipso";
$config['os'][$os]['text']                  = "Check Point IPSO"; // Old vendor NOKIA
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "checkpoint";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.94.1.21.2.1";
$config['os'][$os]['mibs'][]                = "CHECKPOINT-MIB";
$config['os'][$os]['mibs'][]                = "NOKIA-IPSO-SYSTEM-MIB";

$os = "sofaware";
$config['os'][$os]['text']                  = "Check Point Embedded NGX";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "checkpoint";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6983.1";
$config['os'][$os]['sysDescr'][]            = "/^SofaWare Embedded/";
$config['os'][$os]['mibs'][]                = "CHECKPOINT-MIB";
$config['os'][$os]['mibs'][]                = "EMBEDDED-NGX-MIB";

$os = "infoblox";
$config['os'][$os]['text']                  = "Infoblox";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "infoblox";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.7779.1";
$config['os'][$os]['mibs'][]                = "IB-DNSONE-MIB";
$config['os'][$os]['mibs'][]                = "IB-DHCPONE-MIB";
$config['os'][$os]['mibs'][]                = "IB-PLATFORMONE-MIB";

$os = "splat";
$config['os'][$os]['text']                  = "Check Point SecurePlatform";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "checkpoint";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.2620',
  // CHECKPOINT-MIB::osName.0 = STRING: SecurePlatform
  'CHECKPOINT-MIB::osName.0'                => '/SecurePlatform/',
);
$config['os'][$os]['mibs'][]                = "CHECKPOINT-MIB";

$os = "gaia";
$config['os'][$os]['text']                  = "Check Point GAiA";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "checkpoint";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.2620',
  // CHECKPOINT-MIB::osName.0 = STRING: Gaia
  'CHECKPOINT-MIB::osName.0'                => '/Gaia/',
);
$config['os'][$os]['mibs'][]                = "CHECKPOINT-MIB";

$os = "infratec-rms";
$config['os'][$os]['text']                  = "Infratec RMS";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1909.10";
$config['os'][$os]['mibs'][]                = "INFRATEC-RMS-MIB";

$os = "sensatronics";
$config['os'][$os]['text']                  = "Sensatronics";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['icon']                  = "sensatronics";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.16174.1";

// Other Unix-based OSes here please.

$os = "ibmi";
$config['os'][$os]['text']                  = "IBM System i";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2.6.11";

$os = "freebsd";
$config['os'][$os]['text']                  = "FreeBSD";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['discovery_os']          = "freebsd";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8072.3.2.8"; // Do not use both discovery_os and sysObjectID (sysObjectID always wins)

$os = "openbsd";
$config['os'][$os]['text']                  = "OpenBSD";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.30155.23.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8072.3.2.12"; // Net-SNMP
$config['os'][$os]['sysDescr'][]            = "/^OpenBSD/";
$config['os'][$os]['mibs'][]                = "OPENBSD-SENSORS-MIB";

$os = "netbsd";
$config['os'][$os]['text']                  = "NetBSD";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysDescr'][]            = "/^NetBSD/";

$os = "dragonfly"; // FIXME. Not have any sysDescr/sysObjectID or file for detect os
$config['os'][$os]['text']                  = "DragonflyBSD";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";

$os = "netware";
$config['os'][$os]['text']                  = "Novell Netware";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['icon']                  = "novell";
$config['os'][$os]['sysDescr'][]            = "/Novell NetWare/";

$os = "darwin";
$config['os'][$os]['text']                  = "Mac OS X";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysDescr'][]            = "/Darwin Kernel Version/";
$config['os'][$os]['mibs'][]                = "LM-SENSORS-MIB";

$os = "monowall";
$config['os'][$os]['text']                  = "m0n0wall";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['sysDescr'][]            = "/m0n0wall/";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";

$os = "pfsense";
$config['os'][$os]['text']                  = "pfSense";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "firewall";
//(pre 2.1) - sysDescr.0 = pfsense.localdomain 3255662572 FreeBSD 8.1-RELEASE-p13
//(2.1+)    - sysDescr.0 = pfSense pfsense.localdomain 2.1-RELEASE pfSense FreeBSD 8.1-RELEASE-p13 i386
$config['os'][$os]['sysDescr'][]            = "/pfSense/i";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/pfSense/i',
  'sysObjectID'                             => '.1.3.6.1.4.1.12325.1.1.2.1.1',
);
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";

$os = "opnsense";
$config['os'][$os]['text']                  = "OPNsense";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['sysDescr'][]            = "/OPNsense/i";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/OPNsense/i',
  'sysObjectID'                             => '.1.3.6.1.4.1.12325.1.1.2.1.1',
);
//OPNsense firewall.domain.com 17.1.6-683208263 OPNsense FreeBSD 11.0-RELEASE-p8 amd64
$config['os'][$os]['sysDescr_regex'][]      = '/(?<version>\d+[\w\.]+)\-\d+ OPNsense (?<kernel>FreeBSD \d[\S]+)\s(?<arch>\S+)/';
//$config['os'][$os]['sysDescr_regex'][]      = '[\w\-]+ [\w\.]+ (?<version>\d+[\w\.]+\-\d+) OPNsense (?<kernel>[\S]+ [\S]+) (?<arch>[\S]+)';

$os = "freenas";
$config['os'][$os]['text']                  = "FreeNAS";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['graphs'][]              = "device_storage";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.12325.1.1.2.1.1',
  // UCD-SNMP-MIB::extOutput.0 = FreeBSD freenas.local 7.3-RELEASE-p3 FreeBSD 7.3-RELEASE-p3 #0: Tue Nov  2 22:41:50 CET 2010     root@dev.freenas.org:/usr/obj/freenas/usr/src/sys/FREENAS-amd64  amd64
  'UCD-SNMP-MIB::extOutput.0'               => '/freenas/i',
);

$os = "nas4free";
$config['os'][$os]['text']                  = "NAS4Free";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['graphs'][]              = "device_storage";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.12325.1.1.2.1.1',
  // UCD-SNMP-MIB::extOutput.0 = FreeBSD nas.local 9.1-RELEASE FreeBSD 9.1-RELEASE #0 r244224M: Fri Dec 14 19:53:16 JST 2012     aoyama@nas4free.local:/usr/obj/nas4free/usr/src/sys/NAS4FREE-i386  i386
  'UCD-SNMP-MIB::extOutput.0'               => '/nas4free/i',
);

$os = "halon-mail";
$config['os'][$os]['text']                  = "Halon Mail Gateway";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['vendor']                = "Halon";
$config['os'][$os]['sysDescr'][]            = "/^Halon /";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^Halon/',
  'sysObjectID'                             => array('.1.3.6.1.4.1.30155.23.1',
                                                     '.1.3.6.1.4.1.8072.3.2.8'),
);
//Halon SR 3.6-nova
//Halon 4.1-teamy-amd64
$config['os'][$os]['sysDescr_regex'][]      = '/^Halon (?:\w+ )?(?<version>\d[\d\.]+)/';
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['mibs'][]                = "HALON-SP-MIB";

$os = "ecos";
$config['os'][$os]['text']                  = "eCos";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "server";
//FIXME. experimental reorder for discovery os
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/eCos/',
  'sysObjectID'                             => array('.1.3.6.1.4.1.2021.250.255',
                                                     '.1.3.6.1.4.1.33763.250.255'),
);
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";

$os = "solaris";
$config['os'][$os]['text']                  = "Sun Solaris";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['discovery_os']          = "solaris";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.42.2.1.1";

$os = "opensolaris";
$config['os'][$os]['text']                  = "Sun OpenSolaris";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['discovery_os']          = "solaris";

$os = "openindiana";
$config['os'][$os]['text']                  = "OpenIndiana";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['discovery_os']          = "solaris";

$os = "nexenta";
$config['os'][$os]['text']                  = "NexentaOS";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
//SunOS NexentaStor 5.11 NexentaOS_4:8bf8c3630a i86pc
$config['os'][$os]['sysDescr'][]            = "/NexentaOS/";

$os = "sun-ilom";
$config['os'][$os]['text']                  = "Sun ILOM";
//$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['icon']                  = "sun_oracle";
//SUN BLADE 6000 MODULAR SYSTEM, ILOM v3.0.12.11.d, r71974
//SUN FIRE X4140, ILOM v3.0.6.16.a, r70915
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>(SUN|SPARC) .*?), ILOM v(?<version>.+?),/';
$config['os'][$os]['sysDescr'][]            = "/^S\w+ .*?, ILOM/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.42.2.200";
$config['os'][$os]['mibs'][]                = "SUN-PLATFORM-MIB";
//$config['os'][$os]['mibs'][]                = "SUN-ILOM-CONTROL-MIB";
$config['os'][$os]['ipmi']                  = TRUE;

$os = "nestos";
$config['os'][$os]['text']                  = "Nexsan NST";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['icon']                  = "nexsan";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.7247.1.1";
$config['os'][$os]['mibs'][]                = "LM-SENSORS-MIB";

$os = "datadomain";
$config['os'][$os]['text']                  = "DD OS";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['icon']                  = "datadomain";
$config['os'][$os]['graphs'][0]             = "device_bits";
$config['os'][$os]['graphs'][1]             = "device_processor";
$config['os'][$os]['graphs'][2]             = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.19746.3.1";
//Data Domain OS 5.4.5.0-477080
$config['os'][$os]['sysDescr_regex'][]      = '/ OS (?<version>\d[\d\.]+)/';
$config['os'][$os]['mibs'][]                = "DATA-DOMAIN-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";
$config['os'][$os]['mib_blacklist'][]       = "LSI-MegaRAID-SAS-MIB";

$os = "aix";
$config['os'][$os]['text']                  = "AIX";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['port_label'][]          = '/(.+?);/'; // en0; Product: 2-Port 10/100/1000 Base-TX PCI-X Adapter Manufacturer: not available! Part Number: not available! FRU Number: not available!
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['graphs'][]              = "device_storage";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysDescr'][]            = "/^AIX /";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2.3.1.2.1.1.2";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2.3.1.2.1.1.3";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8072.3.2.15"; // Intersected with Omnitronics IPR! sysDescr instead
//$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "alteon-ad";
$config['os'][$os]['text']                  = "Alteon Application Director";
$config['os'][$os]['type']                  = "loadbalancer";
$config['os'][$os]['icon']                  = "radware";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1872.1.13.";

$os = "adva";
$config['os'][$os]['text']                  = "Adva Optical";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1671";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2544";
$config['os'][$os]['mibs'][]                = "FspR7-MIB";
//$config['os'][$os]['mibs'][]                = "ADVA-MIB";

$os = "adva-fsp150";
$config['os'][$os]['text']                  = "Adva Optical";
$config['os'][$os]['icon']                  = "adva";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.18022";
//$config['os'][$os]['mibs'][]                = "FspR7-MIB";
//$config['os'][$os]['mibs'][]                = "ADVA-MIB";

$os = "equallogic";
$config['os'][$os]['text']                  = "Storage Array Firmware";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "dell";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12740.17.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12740.12.1.1.0";
$config['os'][$os]['mibs'][]                = "EQLMEMBER-MIB";
$config['os'][$os]['mibs'][]                = "EQLDISK-MIB";
$config['os'][$os]['mib_blacklist'][]       = "BGP4-MIB";

// AdTran

$os = "adtran-aos";
$config['os'][$os]['text']                  = "ADTRAN AOS";
$config['os'][$os]['group']                 = "adtran-aos";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['icon']                  = "adtran";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.664.1";
$config['os'][$os]['mibs'][]                = "ADTRAN-AOSCPU";
$config['os'][$os]['mibs'][]                = "ADTRAN-AOSUNIT";

// Alcatel

$os = "aos";
$config['os'][$os]['text']                  = "Alcatel-Lucent OS";
$config['os'][$os]['group']                 = "aos";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['icon']                  = "alcatellucent";
$config['os'][$os]['model']                 = "alcatellucent";
//6.3.1.1176.R01 Service Release, August 31, 2009.
//5.1.5.125.R02 GA, July 29, 2004.
//Alcatel-Lucent 6450 24 PORT COPPER GE 6.6.3.439.R01 GA, November 12, 2012.
//Alcatel-Lucent OS6850-24 6.4.4.669.R01 Service Release, February 20, 2014.
$config['os'][$os]['sysDescr_regex'][]      = '/(?<version>\d[\d\.]+)\.R\d+/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6486.800.1.1.2.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6486.800.1.1.2.2";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6486.800.1.1.2.2.2"; // AOS-W
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6486.800.1.1.2.2.4"; // Omnistack
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6486.801.1.1.2.1";
$config['os'][$os]['mibs'][]                = "ALCATEL-IND1-HEALTH-MIB";
$config['os'][$os]['mibs'][]                = "ALCATEL-IND1-CHASSIS-MIB";
$config['os'][$os]['mibs'][]                = "ALCATEL-IND1-INTERSWITCH-PROTOCOL-MIB";
$config['os'][$os]['mib_blacklist'][]       = "CISCO-CDP-MIB";

$os = "omnistack";                          // Alcatel Omnistack is RADLAN
$config['os'][$os]['text']                  = "Alcatel-Lucent Omnistack";
$config['os'][$os]['group']                 = "radlan";
$config['os'][$os]['type']                  = "network";
//$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['icon']                  = "alcatellucent";
$config['os'][$os]['model']                 = "alcatellucent";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6486.800.1.1.2.2.4";
$config['os'][$os]['mib_blacklist'][]       = "CISCO-CDP-MIB";

$os = "aosw";
$config['os'][$os]['text']                  = "Alcatel-Lucent AOS-W";
$config['os'][$os]['group']                 = "aos";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_arubacontroller_numaps";
$config['os'][$os]['graphs'][]              = "device_arubacontroller_numclients";
$config['os'][$os]['icon']                  = "alcatellucent";
$config['os'][$os]['model']                 = "alcatellucent";
//AOS-W (MODEL: OAW-6000), Version 5.0.2.0-cuc (28337)
//AOS-W Version 6.4.4.3-4.2.2.1
$config['os'][$os]['sysDescr_regex'][]      = '/Version (?<version>[\d\.]+)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6486.800.1.1.2.2.2.";
$config['os'][$os]['mibs'][]                = "WLSX-SWITCH-MIB";
$config['os'][$os]['mibs'][]                = "WLSX-WLAN-MIB";

$os = "timos";
$config['os'][$os]['text']                  = "Nokia Networks SROS";
$config['os'][$os]['group']                 = "timos";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['icon']                  = "nokia";
//TiMOS-B-6.0.R6 both/mpc ALCATEL SAS-M 24F 2XFP 7210 Copyright (c) 2000-2014 Alcatel-Lucent.
//TiMOS-B-4.0.R11 both/hops ALCATEL SR 7750 Copyright (c) 2000-2007 Alcatel-Lucent.
$config['os'][$os]['sysDescr_regex'][]      = '/TiMOS\-\w\-(?<version>[\w\.]+) .+?ALCATEL (?<hardware1>.+?) (?<hardware>\d+) Copyright/';
//TiMOS-C-14.0.R5 cpm/hops64 Nokia 7750 SR Copyright (c) 2000-2016 Nokia.
$config['os'][$os]['sysDescr_regex'][]      = '/TiMOS\-\w\-(?<version>[\w\.]+) .+?(?:Nokia|ALCATEL) (?<hardware>\d[\w\ ]+?) Copyright/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6527.";
$config['os'][$os]['mibs'][]                = "TIMETRA-SYSTEM-MIB";
$config['os'][$os]['mibs'][]                = "TIMETRA-CHASSIS-MIB";
$config['os'][$os]['mibs'][]                = "TIMETRA-PORT-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

// BDCOM

$os = "bdcom-ios";
$config['os'][$os]['text']                  = "BDCOM";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "bdcom";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3320.";
//BDCOM(tm) S2228-POE Software, Version 2.0.2B Compiled: 2010-7-13 12:1:29 by WANGRENLEI ROM: System Bootstrap,Version 0.3.6,Serial num:2407xxxx
//BDCOM(tm) 2605 Software, Version 5.0.1C (BASE) Copyright by Shanghai Baud Data Communication CO. LTD. Compiled: 2011-12-26 16:51:48 by SYS_1718, Image text-base: 0x10000 ROM: System Bootstrap, Version 0.4.7,Serial num:RU220xxx System image file is "Router
//BDCOM Internetwork Operating System Software I-8006 Series Software, Version 5.1.1C (FULL), RELEASE SOFTWARE Copyright (c) 2010 by Shanghai Baud Data Communication CO.LTD Compiled: 2011-04-14 13:42:21 by SYS_1239, Image text-base: 0x108000 ROM: System Boo
//BDCOM(tm) S3424F Software, Version 2.1.1A Build 13295 Compiled: 2013-6-5 17:37:3 by SYS ROM: System Bootstrap,Version 0.4.4,Serial num:3502xxxx
//Techroutes-BDCOM Network Pvt. Ltd Internetwork Operating System Software TR 2611 Series Software, Version 1.3.3G (MIDDLE), RELEASE SOFTWARE Copyright (c) 2005 by Techroutes-BDCOM Network Pvt. Ltd Compiled: 2008-11-03 18:10:40 by system, Image text-base: 0
//BDCOM(tm) 7208 Software, Version 3.0.0P (BASE) Copyright by Shanghai Baud Data Communication CO. LTD. Compiled: 2010-04-27 10:23:38 by system, Image text-base: 0x10000 ROM: System Bootstrap, Version 0.4.2,Serial num:RG000xxx System image file is "Router.b
$config['os'][$os]['sysDescr_regex'][]      = '/BDCOM(?:\(tm\)|.*Internetwork Operating System Software)? (?<hardware>(?:\w+ )?\S+) (?:Series )?Software, Version (?<version>\d\S+) (?:.*Serial num:(?<serial>\S+))?/';

// Billion

$os = "billion";
$config['os'][$os]['text']                  = "Billion";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "billion";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.17453.";

// BridgeWave
$os = "bridgewave";
$config['os'][$os]['text']                  = "BridgeWave";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "bridgewave";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6080.3.1.9";

// CASA

$os = "casa-dcts";
$config['os'][$os]['text']                  = "CASA DCTS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "casa";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.20858.2";
//$config['os'][$os]['snmp']['max-rep']       = 2000; // Yep! This devices may have over 1000 ports. All tested devices support such max-rep value and huge improved polling time
//$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";  // ENTITY-MIB is huge here

// C-Data
$os = "cdata-swe-pon";
$config['os'][$os]['vendor']                = "cdata";
$config['os'][$os]['text']                  = "CDATA";
$config['os'][$os]['type']                  = "network";
//$config['os'][$os]['snmpable'][]            = ".1.3.6.1.4.1.34592.1.3.1.1.2.0";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.0.0',
//  'sysDescr'                                => '/^$/', // empty
  'FD-SYSTEM-MIB::sysDesc.0'   => '/.+/', // non empty
);
$config['os'][$os]['mibs'][]                = "FD-SYSTEM-MIB";
$config['os'][$os]['mibs'][]                = "FD-SWITCH-MIB";

// Cisco

$os = "iosxr";
$config['os'][$os]['text']                  = "Cisco IOS-XR";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['sysDescr'][]            = "/IOS XR/";
$config['os'][$os]['icon']                  = "cisco";
//$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['realtime']              = 30; // Yes it's really minimal interval when counters changed in IOS-XR
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['graphs'][]              = "device_uptime";
$config['os'][$os]['model']                 = "cisco";              // Per-HW hardware name, type and MIBs
$config['os'][$os]['mib_blacklist'][]       = "CISCO-EIGRP-MIB";    // Not supported, timeout
$config['os'][$os]['mib_blacklist'][]       = "UCD-SNMP-MIB";       // Not supported
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "iosxe";
$config['os'][$os]['text']                  = "Cisco IOS-XE";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['sysDescr'][]            = "/IOS-XE/";
$config['os'][$os]['sysDescr'][]            = "/Cisco IOS Software.*LINUX_IOSD/";
#$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['realtime']              = 10;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['model']                 = "cisco";              // Per-HW hardware name, type and MIBs
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['mibs'][]                = "CISCO-CONFIG-MAN-MIB";
$config['os'][$os]['mib_blacklist'][]       = "CISCO-EIGRP-MIB"; // Not supported, timeout
$config['os'][$os]['mib_blacklist'][]       = "UCD-SNMP-MIB";    // Not supported
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "ios";
$config['os'][$os]['text']                  = "Cisco IOS";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['sysDescr'][]            = "/Cisco Internetwork Operating System Software/";
$config['os'][$os]['sysDescr'][]            = "/Cisco IOS Software(?!.*LINUX_IOSD)/"; // Exclude IOS-XE
$config['os'][$os]['sysDescr'][]            = "/Software(?!.*LINUX_IOSD) \(.*?, Version .*?, RELEASE SOFTWARE .*?www.cisco.com/"; // Sometime not have "IOS" string
$config['os'][$os]['sysDescr'][]            = "/IOS \(tm\)/";
$config['os'][$os]['sysDescr'][]            = "/Global Site Selector/";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['realtime']              = 10;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['graphs'][]              = "device_fdb_count";
$config['os'][$os]['graphs'][]              = "device_poller_perf";
$config['os'][$os]['model']                 = "cisco";              // Per-HW hardware name, type and MIBs
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['mibs'][]                = "CISCO-CONFIG-MAN-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-CAT6K-CROSSBAR-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-DOT11-ASSOCIATION-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-STACKWISE-MIB";
$config['os'][$os]['mib_blacklist'][]       = "UCD-SNMP-MIB";    // Not supported
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "acsw";
$config['os'][$os]['text']                  = "Cisco ACE";
#$config['os'][$os]['group']                = "cisco";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['type']                  = "loadbalancer";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['ports_unignore_descr']  = 'vlan';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.729";  // ACE 4G in Cat6500
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.730";  // ACE in Cat6500
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.824";  // ACE 4710
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1231"; // ACE in Cat6500
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1291"; // ACE in Cat6500
$config['os'][$os]['sysDescr'][]            = "/^ACE /";
$config['os'][$os]['sysDescr'][]            = "/(Cisco )?Application Control (Software|Engine)/";
$config['os'][$os]['mibs'][]                = "CISCO-PROCESS-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-SLB-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-ENHANCED-SLB-MIB";
$config['os'][$os]['mib_blacklist'][]       = "UCD-SNMP-MIB";    // Not supported
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "asa";
$config['os'][$os]['text']                  = "Cisco ASA";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['sysDescr'][]            = "/Cisco Adaptive Security Appliance/";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['mibs'][]                = "CISCO-FIREWALL-MIB";

$os = "fwsm";
$config['os'][$os]['text']                  = "Cisco Firewall Service Module";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['sysDescr'][]            = "/Cisco Firewall Services Module/";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";

$os = "pixos";
$config['os'][$os]['text']                  = "Cisco PIX-OS";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['sysDescr'][]            = "/Cisco PIX/";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";


$os = "nxos";
$config['os'][$os]['text']                  = "Cisco NX-OS";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "cisco";
//$config['os'][$os]['snmp']['max-rep']       = 100; // issues apparent
//$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysDescr'][]            = "/Cisco NX-OS/";

$os = "sanos";
$config['os'][$os]['text']                  = "Cisco SAN-OS";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysDescr'][]            = "/Cisco SAN-OS/";

$os = "catos";
$config['os'][$os]['text']                  = "Cisco CatOS";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "cisco-old";
$config['os'][$os]['snmp']['max-rep']       = 20;
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysDescr'][]            = "/Cisco (Catalyst Operating System Software|Systems Catalyst 1900)/";
//Cisco Systems WS-C5500 Cisco Catalyst Operating System Software, Version 5.5(18) Copyright (c) 1995-2002 by Cisco Systems
//Cisco Systems, Inc. WS-C2948 Cisco Catalyst Operating System Software, Version 4.5(9) Copyright (c) 1995-2000 by Cisco Systems, Inc.
//Cisco Systems, Inc. WS-C2948 Cisco Catalyst Operating System Software, Version 8.4(11)GLX Copyright (c) 1995-2006 by Cisco Systems, Inc.
//Cisco Systems, Inc. WS-C2948G-GE-TX Cisco Catalyst Operating System Software, Version 8.4(11)GLX Copyright (c) 1995-2006 by Cisco Systems, Inc.
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>\S+) Cisco Catalyst Operating System .+? Version (?<version>\d\S+)/';
//Cisco Systems Catalyst 1900,V9.00.04
//Cisco Systems Catalyst 1900,V9.00.05 system.
$config['os'][$os]['sysDescr_regex'][]      = '/Cisco Systems Catalyst (?<hardware>\S+?),V(?<version>\d\S+)/';
$config['os'][$os]['mibs'][]                = "CISCO-STACK-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";
$config['os'][$os]['mib_blacklist'][]       = "SMON-MIB";
$config['os'][$os]['mib_blacklist'][]       = "EtherLike-MIB";
$config['os'][$os]['mib_blacklist'][]       = "CISCO-TRUSTSEC-INTERFACE-MIB";

$os = "cisco-prime";
$config['os'][$os]['text']                  = "Cisco Prime";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1422"; // Cisco Prime Collaboration Manager
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2307"; // Cisco Prime Infrastructure Appliance
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2387"; // Cisco Prime NAM Appliance 2404

$os = "wlc";
$config['os'][$os]['text']                  = "Cisco WLC";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.828";  // ciscoAirWlc2106K9
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.926";  // cisco520WirelessController
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1069"; // cisco5500Wlc
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1279"; // ciscoAirCt2504K9
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1293"; // ciscoWiSM2
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1295"; // cisco7500Wlc
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1615"; // cisco8500WLC
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1631"; // ciscoVirtualWlc
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1645"; // cisco5760wlc
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2026"; // catAIRCT57006
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2170"; // cisco5520WLC
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2171"; // cisco8540Wlc
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14179";
$config['os'][$os]['sysDescr'][]            = "/^Cisco Controller$/";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";
$config['os'][$os]['mibs'][]                = "AIRESPACE-WIRELESS-MIB";
$config['os'][$os]['mibs'][]                = "AIRESPACE-SWITCHING-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-LWAPP-SYS-MIB";

$os = "cisco-ons";
$config['os'][$os]['text']                  = "Cisco Cerent ONS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Cisco";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3607.";
// MIBs disabled until not implemented
//$config['os'][$os]['mibs'][]                = "CERENT-ENVMON-MIB";
//$config['os'][$os]['mibs'][]                = "CERENT-OPTICAL-MONITOR-MIB";

$os = "cisco-acs";
$config['os'][$os]['text']                  = "Cisco Secure ACS";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['sysDescr'][]            = "/Cisco Secure (ACS|Access Control System)/";

$os = "cisco-lms";
$config['os'][$os]['text']                  = "Cisco Prime LMS";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.10.56"; // This sysObjectId intersects with Cisco ACS

// Cisco ISE

$os = "cisco-ise";
$config['os'][$os]['text']                  = "Cisco Identity Services Engine";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1423"; // ISE 3315
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1424"; // ISE 3395
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1425"; // ISE 3355
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1426"; // ISE VM
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2139"; // SNS 3495
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2140"; // SNS 3415
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2265"; // SNS 3515
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2266"; // SNS 3595
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";

$os = "cisco-ade";
$config['os'][$os]['text']                  = "Cisco ADE";
$config['os'][$os]['vendor']                = "cisco";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysDescr'][]            = "/Cisco Application Deployment Engine/";

$os = "cisco-acns";
$config['os'][$os]['text']                  = "Cisco ACNS";
$config['os'][$os]['vendor']                = "Cisco";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysDescr'][]            = "/Cisco Content Engine/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.409"; // ciscoCe507
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.410"; // ciscoCe560
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.411"; // ciscoCe590
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.412"; // ciscoCe7320
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.432"; // ciscoCe507AV
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.433"; // ciscoCe560AV
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.454"; // ciscoCe2636
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.455"; // ciscoDwCE
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.490"; // ciscoCe508
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.491"; // ciscoCe565
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.492"; // ciscoCe7325
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.504"; // ciscoCe7305
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.505"; // ciscoCe510
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.595"; // ciscoCe511K9
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.596"; // ciscoCe566K9
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.600"; // ciscoCe7306K9
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.601"; // ciscoCe7326K0
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.612"; // ciscoCe501K9
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.708"; // ciscoCe611K9
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.759"; // ciscoCe512K9
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.761"; // ciscoCe612K9
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.982"; // ciscoCe7341
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.983"; // ciscoCe7371
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.996"; // ciscoCe574
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1112"; // ciscoCeVirtualBlade
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['mibs'][]                = "CISCO-CONTENT-ENGINE-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-ENTITY-ASSET-MIB";

$os = "cisco-tp";
$config['os'][$os]['text']                  = "Cisco TelePresence";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "communication";
$config['os'][$os]['vendor']                = "Cisco";
$config['os'][$os]['sysDescr'][]            = "/Cisco TelePresence/";
$config['os'][$os]['sysDescr'][]            = "/^TANDBERG/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5596";     // Old TANDBERG
$config['os'][$os]['sysObjectID'][]         = ".1.2.826.0.1.4616240";  // Old Codian
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.800";  // ciscoTSPri
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.801";  // ciscoTSSec
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1003"; // ciscoTSPriG2
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1433"; // ciscoTsCodecG2
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1434"; // ciscoTsCodecG2C
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1435"; // ciscoTSCodecG2RC
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1436"; // ciscoTSCodecG2R
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1540"; // ciscoTelePresenceMCU5310
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1541"; // ciscoTelePresenceMCU5320
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2161"; // ciscoTSCodecG3
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";

$os = "cisco-uc";
$config['os'][$os]['text']                  = "Cisco Unified Communications";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['type']                  = "communication";
$config['os'][$os]['vendor']                = "Cisco";
$config['os'][$os]['sysDescr'][]            = "/Software:UCOS/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1348"; // ciscoUCVirtualMachine
//$config['os'][$os]['graphs'][]              = "device_bits";
//$config['os'][$os]['graphs'][]              = "device_processor";
//$config['os'][$os]['graphs'][]              = "device_mempool";

$os = "cisco-acano";
$config['os'][$os]['text']                  = "Cisco Acano";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "communication";
$config['os'][$os]['vendor']                = "Cisco";
$config['os'][$os]['sysDescr'][]            = "/^Acano /";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";

$os = "cisco-altiga";
$config['os'][$os]['text']                  = "Cisco VPN Concentrator";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['vendor']                = "Cisco";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3076.1.2.1.1.2.1";  // Cisco VPN3005
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3076.1.2.1.1.1.1";  // Cisco/Altiga C10/15/20/30/50/60
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3076.1.2.1.1.1.2";  // Cisco VPN3015/3030/3060
$config['os'][$os]['sysDescr'][]            = "/VPN 3000 Concentrator/";
//Cisco Systems, Inc./VPN 3000 Concentrator Series Version 2.5.Rel Jun 21 2000 18:57:52
//Cisco Systems, Inc./VPN 3000 Concentrator Version 4.0.1.Rel built by vmurphy on May 06 2003 13:13:03
$config['os'][$os]['sysDescr_regex'][]      = '/Version (?<version>\d[\w\.]+)\.Rel/';
//Cisco Systems, Inc./VPN 3000 Concentrator Version 4.1.7.F built by vmurphy on May 17 2005 02:38:46
$config['os'][$os]['sysDescr_regex'][]      = '/Version (?<version>\d[\w\.]+)/';
$config['os'][$os]['mibs'][]                = "CISCO-IPSEC-FLOW-MONITOR-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-GLOBAL-REG";
$config['os'][$os]['mibs'][]                = "ALTIGA-VERSION-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-PPTP-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-EVENT-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-PPP-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-HTTP-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-IP-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-FILTER-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-FTP-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-L2TP-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-SESSION-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-ADDRESS-STATS-MIB";
$config['os'][$os]['mibs'][]                = "ALTIGA-HARDWARE-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-GENERAL-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-SSL-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-LBSSF-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-DHCP-SERVER-STATS-MIB";
//$config['os'][$os]['mibs'][]                = "ALTIGA-MIB";

$os = "meraki";
$config['os'][$os]['text']                  = "Cisco Meraki";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['icon']                  = "meraki";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.29671.1"; // Cloud controller
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.29671.2.";
$config['os'][$os]['mibs'][]                = "IEEE802dot11-MIB";
$config['os'][$os]['mibs'][]                = "MERAKI-CLOUD-CONTROLLER-MIB";

// Cisco UCS CIMC

$os = "cimc";
$config['os'][$os]['text']                  = "Cisco Integrated Management Controller";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['sysDescr'][]            = "/Cisco Integrated Management /";
$config['os'][$os]['sysDescr_regex'][]      = '/Version (?<version>[^,]+) Copyright/';
// IMC separate controller
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1634"; // ciscoE160DP
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1635"; // ciscoE160D
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1636"; // ciscoE140DP
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1637"; // ciscoE140D
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1638"; // ciscoE140S
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2183"; // ciscoUCSE160DM2K9
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2184"; // ciscoUCSE180DM2K9
// UCS Servers integrated controller
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1512"; // ciscoUcsC200
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1513"; // ciscoUcsC210
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1514"; // ciscoUcsC250
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1515"; // ciscoUcsC260
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1516"; // ciscoUcsC460
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1682"; // ciscoUcsC220
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1683"; // ciscoUcsC240
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1684"; // ciscoUcsC22
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1685"; // ciscoUcsC24
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1817"; // ciscoUCSC460M4Rackserver
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1864"; // ciscoUcsE140S
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1931"; // ciscoUcsEN120S
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2149"; // ciscoUCSEN120E54
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2151"; // ciscoUCSEN120E108
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2154"; // ciscoUCSEN120E208
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2178"; // ciscoUCSC220M4
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2179"; // ciscoUCSC240M4
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2180"; // ciscoUCSC3160
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2182"; // ciscoUCSC3260
$config['os'][$os]['mibs'][]                = "CISCO-UNIFIED-COMPUTING-COMPUTE-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-UNIFIED-COMPUTING-PROCESSOR-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-UNIFIED-COMPUTING-MEMORY-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-UNIFIED-COMPUTING-STORAGE-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-UNIFIED-COMPUTING-EQUIPMENT-MIB";

// Cisco IronPort

$os = "asyncos";
$config['os'][$os]['text']                  = "Cisco IronPort";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.15497.1.2";
$config['os'][$os]['sysDescr'][]            = "/Cisco .* AsyncOS/";
//Cisco IronPort Model C160, AsyncOS Version: 7.6.2-014, Build Date: 2012-11-02, Serial #: 99999AAA9AA9-99AAAA9
//Cisco Model S380, AsyncOS Version: 8.8.0-085, Build Date: 2015-07-02, Serial #: 99999AAA9AA9-99AAAA9
$config['os'][$os]['sysDescr_regex'][]      = '/Model (?<hardware>\w[^,]+?) *, AsyncOS Version: (?<version>\d[\w\.]+).+, Serial #: (?<serial>\S+)/';
$config['os'][$os]['mibs'][]                = "ASYNCOS-MAIL-MIB";

// Cisco Service Control OS / SCE

$os = "ciscoscos";
$config['os'][$os]['text']                  = "Cisco Service Control Engine";
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['sysDescr'][]            = "/Cisco Service Control/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.682";  // ciscoSCEDispatcher
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.683";  // ciscoSCE1000
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.684";  // ciscoSCE2000
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.913";  // ciscoSce8000
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.2096"; // ciscoSCE10000
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";

// Cisco Small Business (Linksys)

$os = "cisco-spa";
$config['os'][$os]['text']                  = "Cisco SPA";
$config['os'][$os]['type']                  = "voip";
//$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['vendor']                = "Cisco Small Business";
$config['os'][$os]['icon']                  = "ciscosb";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.23.1.1";
$config['os'][$os]['mibs'][]                = "CISCO-PROCESS-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-MEMORY-POOL-MIB";

$os = "cisco-srp";
$config['os'][$os]['text']                  = "Cisco SRP";
$config['os'][$os]['type']                  = "network";
//$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['vendor']                = "Cisco Small Business";
$config['os'][$os]['icon']                  = "ciscosb";
//SRP541W, GE WAN, 802.11n ETSI, 4FXS/1FXO
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>[^,]+), [^,]+, (?<features>.+)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.1.1157";
$config['os'][$os]['mibs'][]                = "CISCO-PROCESS-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-MEMORY-POOL-MIB";

$os = "ciscosb";
#$config['os'][$os]['group']                 = "cisco"; // Cisco SB is not Cisco! --mike
$config['os'][$os]['text']                  = "Cisco Small Business";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "ciscosb";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.";    // Common if not detected by other
/*
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.70.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.71.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.72.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.73.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.80.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.81.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.82.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.83.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.85.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.87."; // SF200-48
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.88."; // SG200-50
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.89.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.91."; // SG350XG
*/
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3955.";     // Common if not detected by other
$config['os'][$os]['mibs'][]                = "POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "CISCOSB-POE-MIB";
$config['os'][$os]['mibs'][]                = "CISCOSB-rndMng";
$config['os'][$os]['mibs'][]                = "CISCOSB-IPv6";     // IPv6 addresses
$config['os'][$os]['mibs'][]                = "CISCOSB-PHY-MIB";  // DOM
$config['os'][$os]['mibs'][]                = "CISCOSB-Physicaldescription-MIB"; // Serial, version, etc.

$os = "ciscosb-rv";
$config['os'][$os]['text']                  = "Cisco (Linksys) Router";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['icon']                  = "ciscosb";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['sysDescr'][]            = "/^Linux( \d[\w\.\-]+)?, Cisco( Small Business)? RV/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.11.";     // RV routers
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.23.1.11"; // RV routers
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.23.3.";   // RV routers
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.23.3.13"; // RV320
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.23.3.17"; // RV325
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.23.3.21"; // CVR328W
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3955.1.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3955.6.11";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3955.250.10";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3955.1000.20";

$os = "ciscosb-wl";
$config['os'][$os]['text']                  = "Cisco (Linksys) Wireless";
$config['os'][$os]['type']                  = "wireless";
//$config['os'][$os]['group']                 = "unix"; // Not sure about this group - UCD-SNMP-MIB, HOST-RESOURCES-MIB complete not supported
$config['os'][$os]['group']                 = "cisco";
$config['os'][$os]['vendor']                = "Cisco";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysDescr'][]            = "/^Linux( \d[\w\.\-]+)?, Cisco.*? WAP/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.104.1";     // WAP121
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.32.4410.1"; // WAP4410N-A
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.32.321.1";  // WAP321
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.34.371.1";  // WAP371
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3955.2.2.1";      // BEFDSR41W

$os = "ciscosb-nss";
$config['os'][$os]['text']                  = "Cisco SB Storage";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['icon']                  = "ciscosb";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9.6.1.103.";

// Cyan

$os = "cyan";
$config['os'][$os]['text']                  = "Cyan";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "cyan";
$config['os'][$os]['snmp']['nobulk']        = TRUE;
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['snmp']['max-rep']       = 200;
$config['os'][$os]['graphs'][0]             = "device_bits";
$config['os'][$os]['graphs'][1]             = "device_processor";
$config['os'][$os]['graphs'][2]             = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.28533.1";
$config['os'][$os]['mibs'][]                = "CYAN-NODE-MIB";
$config['os'][$os]['mibs'][]                = "CYAN-SHELF-MIB";
$config['os'][$os]['mibs'][]                = "CYAN-CEM-MIB";
$config['os'][$os]['mibs'][]                = "CYAN-XCVR-MIB";
$config['os'][$os]['mibs'][]                = "CYAN-GEPORT-MIB";
$config['os'][$os]['mibs'][]                = "CYAN-TENGPORT-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";

// Fastback

$os = "liberator";
$config['os'][$os]['text']                  = "Fastback Wireless";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['vendor']                = "Fastback";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.39003";
$config['os'][$os]['mibs'][]                = "SUB10SYSTEMS-MIB";
//$config['os'][$os]['mibs'][]                = "V320-MIB";

// Huawei

$os_group = 'huawei';
$config['os_group'][$os_group]['vendor']      = "Huawei";
$config['os_group'][$os_group]['icon']        = "huawei";
//$config['os_group'][$os_group]['graphs'][]    = "device_bits";
$config['os_group'][$os_group]['mibs'][]      = "HUAWEI-TC-MIB"; // Inventory entPhysicalVendorType
$config['os_group'][$os_group]['mibs'][]      = "HUAWEI-ENTITY-EXTENT-MIB";
$config['os_group'][$os_group]['mibs'][]      = "POWER-ETHERNET-MIB";
$config['os_group'][$os_group]['mibs'][]      = "HUAWEI-POE-MIB";
//$config['os_group'][$os_group]['mib_blacklist'][] = "HOST-RESOURCES-MIB";
//$config['os_group'][$os_group]['mib_blacklist'][] = "CISCO-CDP-MIB";

$os = "vrp";
$config['os'][$os]['text']                  = "Huawei VRP";
$config['os'][$os]['group']                 = "huawei";
$config['os'][$os]['type']                  = "network";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.1.";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.6.";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.10.";
//$config['os'][$os]['sysDescr'][]            = "/Huawei(-3Com)? Versatile Routing Platform Software/";
//EXPERIMENTAL. reorder for more complex discovery os
$config['os'][$os]['discovery'][]           = array(
  // DESC: required to match both - sysDescr and any of sysObjectID from list
  'sysDescr'                                => '/Versatile Routing Platform Software/',
  'sysObjectID'                             => array(".1.3.6.1.4.1.2011.1.",
                                                     ".1.3.6.1.4.1.2011.2.",
                                                     ".1.3.6.1.4.1.2011.6.",
                                                     ".1.3.6.1.4.1.2011.10."),
);
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/Huawei(-3Com)? Versatile Routing Platform Software/',
);

$config['os'][$os]['mibs'][]                = "HUAWEI-ENERGYMNGT-MIB";

$os = "huawei-vsp";
$config['os'][$os]['text']                  = "Huawei VSP";
$config['os'][$os]['group']                 = "huawei";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.77";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.159";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.122";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.125";
$config['os'][$os]['sysDescr'][]            = "/Huawei Versatile Security Platform Software/";
$config['os'][$os]['mibs'][]                = "HUAWEI-ENTITY-EXTENT-MIB";

$os = "huawei-ias";
$config['os'][$os]['text']                  = "Huawei IAS";
$config['os'][$os]['group']                 = "huawei";
$config['os'][$os]['type']                  = "network";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.27";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.78";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.80";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.109"; // MA5606T
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.115"; // MA5680T
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.128";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.132"; // MA5626E
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.133"; // MA5683T
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.134"; // MA5620G
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.167"; // MA5610
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.169"; // MA5616
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.184";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.185";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.186";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.206";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.214";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.216";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.248";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.262";
$config['os'][$os]['sysDescr'][]            = "/Huawei Integrated Access Software/";

$os = "huawei-vp";
$config['os'][$os]['text']                  = "Huawei ViewPoint";
$config['os'][$os]['group']                 = "huawei";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.14.101";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.14.6";
$config['os'][$os]['sysDescr'][]            = "/^ViewPoint$/";

$os = "huawei-ism";
$config['os'][$os]['text']                  = "Huawei Storage";
$config['os'][$os]['group']                 = "huawei";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.91";
$config['os'][$os]['sysDescr'][]            = "/^HUAWEI ISM SNMP Agent/";

$os = "huawei-wl";
$config['os'][$os]['text']                  = "Huawei Wireless";
$config['os'][$os]['group']                 = "huawei";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.39";
$config['os'][$os]['sysDescr'][]            = "/^Huawei Enterprise AP/";

$os = "huawei-imana";
$config['os'][$os]['text']                  = "Huawei iMana";
$config['os'][$os]['group']                 = "huawei";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.2.235";
$config['os'][$os]['sysDescr'][]            = "/^Hardware management system$/";
$config['os'][$os]['ipmi']                  = TRUE;

$os = "huawei-ups";
$config['os'][$os]['text']                  = "Huawei UPS";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^Linux \w+/',
  'UPS-MIB::upsIdentManufacturer.0'         => '/HUAWEI/i',
);
$config['os'][$os]['mibs'][]                = "UPS-MIB";
$config['os'][$os]['mibs'][]                = "HUAWEI-TC-MIB"; // Inventory entPhysicalVendorType
$config['os'][$os]['mibs'][]                = "HUAWEI-ENTITY-EXTENT-MIB";

// ZTE

$os_group = "zte";
$config['os_group'][$os_group]['vendor']    = "ZTE";
$config['os_group'][$os_group]['mibs'][]    = "ZXR10-MIB";
$config['os_group'][$os_group]['mibs'][]    = "ZXR10OPTICALMIB";
$config['os_group'][$os_group]['mibs'][]    = "SWITCHENVIRONG";
$config['os_group'][$os_group]['mibs'][]    = "MPLS-L3VPN-STD-MIB";
$config['os_group'][$os_group]['mibs'][]    = "MPLS-VPN-MIB";

// ZX ROS
$os = "zxr10";
$config['os'][$os]['text']                  = "ZTE ZXR10";
$config['os'][$os]['group']                 = "zte";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysDescr'][]            = "/^(ZTE )?ZXR10/";
$config['os'][$os]['sysDescr'][]            = "/^ZXR10 ROS/";
$config['os'][$os]['sysDescr'][]            = "/^ZTE Ethernet Switch/";
//$config['os'][$os]['sysDescr'][]            = "/^(ZTE )?ZXUN/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.3."; // ZTE ROS
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.9";  // ZXSS10/ZXUN
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.15"; // ZTE Ethernet Switch

// DSL/gPON/ePON/CPE
$os = "zxa10";
$config['os'][$os]['text']                  = "ZTE ZXA10";
$config['os'][$os]['group']                 = "zte";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysDescr'][]            = "/^(ZTE )?ZXA10/";
$config['os'][$os]['sysDescr'][]            = "/^ZTE DSLAM/";
$config['os'][$os]['sysDescr'][]            = "/ZXDSL/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.701";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.1004";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.1008";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.1011"; // ZXMSG
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.1015"; // ZXAN
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.1082"; // C300/C300M/C320

$os = "zxv10";
$config['os'][$os]['text']                  = "ZTE ZXV10";
$config['os'][$os]['group']                 = "zte";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysDescr'][]            = "/^(ZTE )?ZXV10/";
$config['os'][$os]['sysDescr'][]            = "/ZTE\-Access Controller/";
$config['os'][$os]['sysDescr'][]            = "/^Broadband Home Gateway$/";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902"; // required only exactly match
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.3"; // required only exactly match

$os = "zxip10";
$config['os'][$os]['text']                  = "ZTE ZXIP10";
$config['os'][$os]['group']                 = "zte";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysDescr'][]            = "/^(ZTE )?ZXIP10/";
$config['os'][$os]['sysDescr'][]            = "/^ZTE IP Express/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.1.1.0";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3902.1.1.10";

/* NOT SURE
$os = "zxun";
$config['os'][$os]['text']                  = "ZTE ZXUN";
$config['os'][$os]['group']                 = "zte";
$config['os'][$os]['type']                  = "security";
//$config['os'][$os]['ifname']                = 1;
//$config['os'][$os]['sysDescr'][]            = "/^(ZTE )?ZXR10/";
$config['os'][$os]['sysDescr'][]            = "/ZXUN/";
*/

// Netgear

$os = "netgear";
$config['os'][$os]['text']                  = "Netgear OS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Netgear";
$config['os'][$os]['group']                 = "fastpath";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4526.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12622.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.89.1.1.1.";
//GS752TXS ProSafe 52-Port Gigabit Stackable Smart Switch with 10G uplinks, 6.1.0.27, B5.2.0.1
//M4100-50G-POE+ ProSafe 48-port Gigabit L2+ Intelligent Edge PoE Managed Switch, 10.0.1.27, B1.0.0.9
$config['os'][$os]['sysDescr_regex'][]      = "/^(?<hardware>[A-Za-z][\w\-\+]+) .+?, (?<version>\d[\d\.]+)/";
//XSM7224S - 24-Port 10G SFP+ Layer 2 Stackable Managed Switch with four 10G combo ports
//GSM7328Sv2 - 24-Port Gigabit Layer 3 Stackable Managed Switch with 2 10G SFP+ ports
//GSM7328FS - NetGear GSM7328FS - 24 GE, 4 TENGIG
$config['os'][$os]['sysDescr_regex'][]      = "/^(?<hardware>[A-Za-z][\w\-\+]+) - /";
//FSM7226RS ProSafe 24-port 10/100 L2 Managed Stackable Switch with Static Routing
//GS724Tv3 Switch
//WC7600 ProSafe Wireless LAN Controller
//FSM7326P L3 Fast Ethernet PoE
$config['os'][$os]['sysDescr_regex'][]      = "/^(?<hardware>[A-Za-z][\w\-\+]+) .*?(?:Switch|LAN|Ethernet)/";
//Netgear ProSafe VPN Firewall FVS318v3
$config['os'][$os]['sysDescr_regex'][]      = "/Firewall (?<hardware>[A-Za-z][\w\-\+]+)$/";
//ProSafe 802.11b/g Wireless Access Point -WG102 V5.2.8
//NETGEAR WG302 3.0.4 (Nov 15 2004)
$config['os'][$os]['sysDescr_regex'][]      = "/(?: |\-)(?<hardware>WG[\w\-\+]+) [Vv]?(?<version>\d[\d\.]+)/";
//FS728TPv2
//WG102-Outdoor-500mWv2
//WG102-500 v3
$config['os'][$os]['sysDescr_regex'][]      = "/^(?<hardware>[A-Za-z][\w\-\+]+(?: v\d+)?)$/";

//$config['os'][$os]['mibs'][]                = "UCD-SNMP-MIB";
$config['os'][$os]['mibs'][]                = "NETGEAR-BOXSERVICES-PRIVATE-MIB";
$config['os'][$os]['mibs'][]                = "NETGEAR-POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "NETGEAR-SWITCHING-MIB";
$config['os'][$os]['mibs'][]                = "NETGEAR-ISDP-MIB"; // Neighbours discovery

$os = "netgear-readyos";
$config['os'][$os]['text']                  = "Netgear ReadyOS";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['vendor']                = "Netgear";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_storage";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4526.100.16.";
$config['os'][$os]['sysDescr'][]            = "/Ready(DATA|NAS) OS/";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux/',
  // READYNAS-MIB::nasMgrSoftwareVersion.0 = STRING: "5.3.12"
  'READYNAS-MIB::nasMgrSoftwareVersion.0'   => '/.+/', // non empty
);
$config['os'][$os]['mibs'][]                = "UCD-SNMP-MIB";
$config['os'][$os]['mibs'][]                = "READYDATAOS-MIB";
$config['os'][$os]['mibs'][]                = "READYNAS-MIB";

// Net Insight Nimbra

$os = "nimbra";
$config['os'][$os]['text']                  = "Net Insight Nimbra";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "netinsight";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2928.1.";
$config['os'][$os]['graphs'][]              = "device_bits";

// Korenix

$os = "korenix-jetnet";
$config['os'][$os]['text']                  = "Korenix Jetnet";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "korenix";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.24062.2.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.24062.2.2";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.24062.2.3";

// Supermicro Switch

$os = "supermicro-switch";
$config['os'][$os]['text']                  = "Supermicro Switch";
$config['os'][$os]['group']                 = "supermicro";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "supermicro";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysDescr'][]            = "/^Supermicro Switch/";
$config['os'][$os]['sysDescr'][]            = "/^(SSE|SBM)-/";

// SNR (shop.nag.ru)

$os = "snr-switch";
$config['os'][$os]['text']                  = "SNR";
$config['os'][$os]['icon']                  = "snr";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.40418.7";
//SNR-S3750G-48S-E Device, Compiled Oct 26 10:19:23 2015 SoftWare Version 7.0.3.1(R0244.0079) BootRom Version 7.1.107 HardWare Version 1.0.2 Device serial number SW032810D610000084 Copyright (C) 2015 NAG LLC All rights reserved
$config['os'][$os]['sysDescr_regex'][]      = "/SNR-(?<hardware>\S+)[^,]*, .*SoftWare Version (?<version>\d[\w\.\-]+).* serial number (?<serial>\S+)/s";
$config['os'][$os]['mibs'][]                = "SNR-SWITCH-MIB";

$os = "snr-erd-2";
$config['os'][$os]['text']                  = "SNR ERD-2";
$config['os'][$os]['icon']                  = "snr";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.40418.2.2";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['sysDescr_regex'][]      = "/Fmv_(?<version>\d[\w\.]+)/";
$config['os'][$os]['ports_skip_ifType']     = TRUE;
$config['os'][$os]['remote_access']         = array('http');
$config['os'][$os]['mibs'][]                = "SNR-ERD-2";

$os = "snr-erd-4";
$config['os'][$os]['text']                  = "SNR ERD-4";
$config['os'][$os]['icon']                  = "snr";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.40418.2.6";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['remote_access']         = array('http');
$config['os'][$os]['mibs'][]                = "SNR-ERD-4";

// Juniper

$os = "junos";
$config['os'][$os]['text']                  = "Juniper JunOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "juniper";
// $config['os'][$os]['snmp']['max-rep']       = 50; // Juniper is full of derp, this massively reduces performance.
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2636";
$config['os'][$os]['sysDescr'][]            = "/kernel JUNOS \d/";
$config['os'][$os]['mibs'][]                = "JUNIPER-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-ALARM-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-DOM-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-IFOPTICS-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-SRX5000-SPU-MONITORING-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-VLAN-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-MAC-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-PING-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-VPN-MIB"; // Pseudowires
$config['os'][$os]['mibs'][]                = "JUNIPER-COS-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-QOS-MIB";
$config['os'][$os]['mibs'][]                = "BGP4-V2-MIB-JUNIPER";
$config['os'][$os]['mibs'][]                = "POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "MPLS-L3VPN-STD-MIB";
$config['os'][$os]['mibs'][]                = "MPLS-VPN-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";
$config['os'][$os]['mib_blacklist'][]       = "PW-STD-MIB";

$os = "junose";
$config['os'][$os]['text']                  = "Juniper JunOSe";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "juniper";
//$config['os'][$os]['snmp']['max-rep']       = 50; // Juniper is full of derp, this massively reduces performance.
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4874";
$config['os'][$os]['mibs'][]                = "JUNIPER-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-DOM-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-IFOPTICS-MIB";
$config['os'][$os]['mibs'][]                = "JUNIPER-PING-MIB";
$config['os'][$os]['mibs'][]                = "Juniper-System-MIB";
$config['os'][$os]['mibs'][]                = "BGP4-V2-MIB-JUNIPER";

$os = "juniper-ex";
$config['os'][$os]['text']                  = "Juniper EX Switch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Juniper";
// $config['os'][$os]['snmp']['max-rep']       = 50; // Juniper is full of derp, this massively reduces performance.
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1411";
//Juniper Networks EX2500 10GbE Switch, SW Version 3.1R2
$config['os'][$os]['sysDescr_regex'][]      = "/(?<hardware>EX\S+) .+?, SW Version (?<version>\d[\w\.]+)/";
$config['os'][$os]['mibs'][]                = "EX2500-BASE-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";
$config['os'][$os]['mib_blacklist'][]       = "PW-STD-MIB";

$os = "jwos";
$config['os'][$os]['text']                  = "Juniper JWOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "juniper";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8239.1";
$config['os'][$os]['mibs'][]                = "JUNIPER-WX-GLOBAL-REG";

$os = "screenos";
$config['os'][$os]['text']                  = "Juniper ScreenOS";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
//NetScreen-500 version 5.4.0r20.0 (SN: 0010122003000061, Firewall+VPN)
//SSG-350M version 6.2.0r6.0 (SN: JN11A4AF9ADE, Firewall+VPN)
//SSG5-Serial version 6.3.0r16a.0 (SN: 0162042010001738, Firewall+VPN)
$config['os'][$os]['sysDescr_regex'][]      = "/(?<hardware>[\w\-]+) version (?<version>\d[\w\.]+) \(SN: (?<serial>\w+), (?<features>[\w\-\+]+)/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.3224.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3224";
$config['os'][$os]['mibs'][]                = "NETSCREEN-RESOURCE-MIB";

// Pulse Secure since August 1, 2015

$os = "juniperive";
$config['os'][$os]['text']                  = "Pulse Connect Secure";
$config['os'][$os]['type']                  = "security";
//$config['os'][$os]['icon']                  = "juniper";
$config['os'][$os]['icon']                  = "pulse_secure";
$config['os'][$os]['vendor']                = "Pulse Secure";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
//Juniper Networks,Inc,Pulse Connect Secure,VA-DTE,8.1R1 (build 33493)
//Pulse Secure,LLC,Pulse Connect Secure,SA-2500,8.1R3.1 (build 36151)
//Juniper Networks,Inc,SA-4500,7.4R9.3 (build 30667)
$config['os'][$os]['sysDescr_regex'][]      = "/,\s*(?<hardware>[A-Z][\w\-]+),\s*(?<version>\d+[\w\.]+)/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12532";
//$config['os'][$os]['mibs'][]                = "JUNIPER-IVE-MIB";
$config['os'][$os]['mibs'][]                = "PULSESECURE-PSG-MIB";

// Ekinops

$os = "ekinops-360";
$config['os'][$os]['text']                  = "Ekinops 360";
$config['os'][$os]['vendor']                = "Ekinops";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.20044";
//Ekinops 360 Platform, C200, Release 3.1.542
//Ekinops 360 Platform, C200HC, Release 4.5.710
$config['os'][$os]['sysDescr_regex'][]      = "/^.*, (?<hardware>[\w]+), Release (?<version>[\d\.]+)/i";
$config['os'][$os]['remote_access']         = array('ssh', 'http');

// Eltex

// Eltex Switch -- Another Radlan!

$os = "eltex-switch";
$config['os'][$os]['text']                  = "Eltex Switch";
$config['os'][$os]['vendor']                = "Eltex";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['group']                 = "radlan";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35265.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35265.1.72"; // ESR-1000 (router)
//MES-3124 28-port 1G/10G Managed Switch
//MES3124F 28-port Fiber 1G/10G Managed Switch
//MES2124M AC 28-port 1G Managed Switch
$config['os'][$os]['sysDescr_regex'][]      = "/^(?:Eltex +)?(?<hardware>[a-z]+\-?\d+[\w]+)(?:(?: +AC)? +(?:[0-9a-z-]+) +(?<features>.+))?/i";
$config['os'][$os]['remote_access']         = array('telnet', 'ssh');

$os = "eltex-voip";
$config['os'][$os]['text']                  = "Eltex VoIP";
$config['os'][$os]['vendor']                = "Eltex";
$config['os'][$os]['type']                  = "communication"; // not voip, because voip used for phones
//$config['os'][$os]['group']                 = "radlan";
//$config['os'][$os]['graphs'][]              = "device_bits";
//$config['os'][$os]['graphs'][]              = "device_processor";
//$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['model']                 = "eltex";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35265.1.4";  // MC
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35265.1.7.90"; // not sure, but not switch
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35265.1.9";  // TAU
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35265.1.29"; // SMG1016M
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35265.1.46"; // RG14xx
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35265.1.56"; // RG14xx
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.35265.1',
  // Linux smg1016m 2.6.22.18 #45 Tue Feb 24 15:04:30 NOVT 2015 armv5tejl
  // Linux RG-2402G-W 2.6.30.9 #1 Mon Mar 31 14:12:01 NOVT 2014 rlx
  // Linux tau72 2.6.22.19-4.03.0-c300evm #281 Mon Jul 8 09:46:27 OMST 2013 armv6l
  'sysDescr'                                => '/^(ELTEX|Linux) (SMG|TAU|SBC|RG|TSU|MC)/i',
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.35265.1',
  'sysDescr'                                => '/^Linux /',
  'sysName'                                 => '/^(SMG|TAU|SBC|RG|TSU|MC)/i',
);
$config['os'][$os]['remote_access']         = array('http');

$os = "eltex-gpon";
$config['os'][$os]['text']                  = "Eltex GPON";
$config['os'][$os]['vendor']                = "Eltex";
$config['os'][$os]['type']                  = "network";
//$config['os'][$os]['graphs'][]              = "device_bits";
//$config['os'][$os]['graphs'][]              = "device_processor";
//$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['model']                 = "eltex";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35265.1.21";  // LTE
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35265.1.22";  // LTP
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35265.1.70";  // LTP
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.35265.1',
  // ELTEX LTP-8X
  // ELTEX LTE-2X
  // Linux LTP-4X 2.6.22.18 #1 Fri Apr 29 18:15:11 NOVT 2016 armv5tejl
  // Linux LTP-8X 2.6.22.18 #1 Tue Dec 15 11:48:50 NOVT 2015 armv5tejl
  'sysDescr'                                => '/^(ELTEX|Linux) (LTP|LTE)/i',
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.35265.1',
  'sysDescr'                                => '/^Linux /',
  'sysName'                                 => '/^(LTP|LTE)/i',
);
$config['os'][$os]['mibs'][]                = "ELTEX-OMS";
$config['os'][$os]['remote_access']         = array('telnet', 'ssh', 'http');

// Fortinet

$os_group = "fortinet";
$config['os_group'][$os_group]['vendor']          = "Fortinet";
$config['os_group'][$os_group]['model']           = "fortinet";
$config['os_group'][$os_group]['snmp']['max-rep'] = 100;
$config['os_group'][$os_group]['ifname']            = 1;
$config['os_group'][$os_group]['mibs'][]          = "FORTINET-CORE-MIB";

$os = "fortigate";
$config['os'][$os]['text']                  = "Fortinet Fortigate";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['group']                 = "fortinet";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_fortigate_cpu";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.15";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.101"; // FortiGate
$config['os'][$os]['mibs'][]                = "FORTINET-FORTIGATE-MIB";

$os = "fortiswitch";
$config['os'][$os]['text']                  = "Fortinet FortiSwitch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "fortinet";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.106"; // FortiSwitch

$os = "fortivoice";
$config['os'][$os]['text']                  = "Fortinet FortiVoice";
$config['os'][$os]['type']                  = "voip";
$config['os'][$os]['group']                 = "fortinet";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.113"; // FortiVoice

$os = "forti-wl";
$config['os'][$os]['text']                  = "Fortinet (Meru) Wireless";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['group']                 = "fortinet";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.15983";

// All other Fortinet devices
$os = "forti-os";
$config['os'][$os]['text']                  = "Fortinet OS";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['group']                 = "fortinet";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysDescr'][]            = "/FortiBalancer/";        // FortiBalancer
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.102"; // FortiAnalyzer
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.103"; // FortiManager
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.104"; // FortiDefender
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.105"; // FortiMail
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.107"; // FortiWeb
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.108"; // FortiScan
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.109"; // FortiCache
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.110"; // FortiDNS
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.111"; // FortiDDoS
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12356.112"; // FortiADC
$config['os'][$os]['mibs'][]                = "FORTINET-FORTIMAIL-MIB";
//$config['os'][$os]['mibs'][]                = "FORTINET-FORTIMANAGER-MIB";
//$config['os'][$os]['mibs'][]                = "FORTINET-FORTIANALYZER-MIB";

// BTI Systems / Juniper Networks has acquired BTI Systems

$os = "bti7000";
$config['os'][$os]['text']                  = "BTI 7000";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "bti";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.18070.2";
//BTI Systems.;BTI 7000;BTI 7200;10.3.6 C004
//BTI; WDM; BTI 7800; 1.6.0 18795; 0.1
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>BTI \S+); ?(?<version>\d[\w\.]+)/';

// Ciena

$os = "ciena";
$config['os'][$os]['text']                  = "SAOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Ciena";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6141.1";
$config['os'][$os]['mibs'][]                = "WWP-LEOS-CHASSIS-MIB";
$config['os'][$os]['mibs'][]                = "WWP-LEOS-PORT-XCVR-MIB";
$config['os'][$os]['mibs'][]                = "WWP-LEOS-SYSTEM-CONFIG-MIB";

$os = "ciena-waveserveros";
$config['os'][$os]['text']                  = "Waveserver OS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Ciena";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1271.3";
$config['os'][$os]['sysDescr'][]            = "/^Waveserver Platform$/";
$config['os'][$os]['mibs'][]                = "CIENA-WS-MIB";
$config['os'][$os]['mibs'][]                = "CIENA-WS-SOFTWARE-MIB";
$config['os'][$os]['mibs'][]                = "CIENA-WS-TYPEDEFS-MIB";
$config['os'][$os]['mibs'][]                = "CIENA-WS-XCVR-MIB";

$os = "ciena-6500";
$config['os'][$os]['text']                  = "Ciena 6500";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Ciena";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.562.68.11";


// Dasan

$os = "dasan-nos";
$config['os'][$os]['text']                  = "DASAN NOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "dasan";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.*) NOS (?<version>\d[\w\.]+)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6296.1";
$config['os'][$os]['mibs'][]                = "DASAN-SWITCH-MIB";

// Mikrotik

$os = "routeros";
$config['os'][$os]['text']                  = "Mikrotik RouterOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['remote_access']         = array('ssh','http');
$config['os'][$os]['vendor']                = "Mikrotik";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['graphs'][]              = "device_uptime";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14988";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14988.1"; // Routers
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14988.2"; // SOHO swithes
$config['os'][$os]['mibs'][]                = "MIKROTIK-MIB";

$os = "mikrotik-swos";
$config['os'][$os]['text']                  = "Mikrotik SwOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Mikrotik";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14988.2";
//$config['os'][$os]['mibs'][]                = "MIKROTIK-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";
// This MIBs produce long polling: [ netstats ] time: 540.5996s
#$config['os'][$os]['mib_blacklist'][]       = "SNMPv2-MIB";
$config['os'][$os]['mib_blacklist'][]       = "IP-MIB";
$config['os'][$os]['mib_blacklist'][]       = "TCP-MIB";
$config['os'][$os]['mib_blacklist'][]       = "UDP-MIB";

// Brocade / Foundry

$os = "ironware";
$config['os'][$os]['text']                  = "Brocade FastIron/IronWare";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "brocade";
$config['os'][$os]['model']                 = "brocade";
$config['os'][$os]['snmp']['max-rep']       = 60;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1991.1";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1991.1.1"; // FastIron Workgroup Switch
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1991.1.2";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1991.1.3";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1991.1.5"; // EdgeIron
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1991.1.16";
$config['os'][$os]['mibs'][]                = "FOUNDRY-SN-SWITCH-GROUP-MIB";
$config['os'][$os]['mibs'][]                = "FOUNDRY-SN-AGENT-MIB";
$config['os'][$os]['mibs'][]                = "FOUNDRY-POE-MIB";
$config['os'][$os]['mibs'][]                = "FOUNDRY-BGP4V2-MIB";
$config['os'][$os]['mibs'][]                = "MPLS-L3VPN-STD-MIB";
$config['os'][$os]['mibs'][]                = "MPLS-VPN-MIB";
$config['os'][$os]['mib_blacklist'][]       = "CISCO-CDP-MIB";

$os = "ironware-ap";
$config['os'][$os]['text']                  = "Brocade AP";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "brocade";
$config['os'][$os]['model']                 = "brocade";
//$config['os'][$os]['snmp']['max-rep']       = 60;
//$config['os'][$os]['graphs'][]              = "device_bits";
//$config['os'][$os]['graphs'][]              = "device_processor";
//$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1991.1.6";  // Foundry AP
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1991.1.15";
$config['os'][$os]['mibs'][]                = "FOUNDRY-SN-SWITCH-GROUP-MIB";
$config['os'][$os]['mibs'][]                = "FOUNDRY-SN-AGENT-MIB";
$config['os'][$os]['mib_blacklist'][]       = "CISCO-CDP-MIB";

$os = "fabos";
$config['os'][$os]['text']                  = "Brocade FabricOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "brocade";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1588.2.1";
$config['os'][$os]['mibs'][]                = "SW-MIB";
$config['os'][$os]['mibs'][]                = "FA-EXT-MIB";

$os = "nos";
$config['os'][$os]['text']                  = "Brocade NOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "brocade";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['ifDescr_ifAlias']       = 1;
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1588.2.2";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1588.3.3";
$config['os'][$os]['sysDescr'][]            = "/(Brocade|Steel|BR)[ \-]VDX/";
//Brocade VDX Switch, BR-VDX6720-24, Network Operating System Software Version 4.1.3b.
//Brocade VDX Switch, BR-VDX6740T, Network Operating System Software Version 4.1.3b.
//Brocade VDX Switch, BR-VDX6740, Network Operating System Software Version 6.0.1a.
// VDX6940-144S:
//fab02-par02, BR-VDX6940-144S, Network Operating System Software Version 7.1.0a.
// VDX-6740-24-R:
//fab02-par02, BR-VDX6740, Network Operating System Software Version 7.1.0a.
$config['os'][$os]['sysDescr_regex'][]      = '/, BR\-(?<hardware>\S+), Network Operating System Software Version (?<version>\d+(?:\.\w+)+)/';
$config['os'][$os]['mibs'][]                = "SW-MIB";
$config['os'][$os]['mibs'][]                = "FA-EXT-MIB";

// Extreme Networks

$os_group = 'extremeware';
$config['os_group'][$os_group]['vendor']          = "Extreme Networks";
$config['os_group'][$os_group]['icon']            = "extreme";
$config['os_group'][$os_group]['ifname']          = 1;
$config['os_group'][$os_group]['mibs'][]          = "POWER-ETHERNET-MIB";
$config['os_group'][$os_group]['mibs'][]          = "EXTREME-SOFTWARE-MONITOR-MIB";
$config['os_group'][$os_group]['mibs'][]          = "EXTREME-BASE-MIB";
$config['os_group'][$os_group]['mibs'][]          = "EXTREME-SYSTEM-MIB";
$config['os_group'][$os_group]['mibs'][]          = "EXTREME-POE-MIB";
$config['os_group'][$os_group]['mib_blacklist'][] = "HOST-RESOURCES-MIB";
$config['os_group'][$os_group]['mib_blacklist'][] = "CISCO-CDP-MIB";

$os = "xos";
$config['os'][$os]['text']                  = "Extreme XOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "extremeware";
//$config['os'][$os]['snmp']['max-rep']       = 100; // Seems to break on some tested systems
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/XOS/',
  'sysObjectID'                             => '.1.3.6.1.4.1.1916.2',
);
//ExtremeWare XOS version 11.5.2.10 v1152b10 by release-manager on Thu Oct 26 09:53:04 PDT 2006
//ExtremeXOS version 12.5.4.5 v1254b5-patch1-20 by release-manager on Tue Apr 24 16:16:37 EDT 2012
//ExtremeXOS (X670G2-48x-4q) version 15.7.1.4 v1571b4-patch1-2 by release-manager on Fri May 1 15:16:42 EDT 2015
//ExtremeXOS (X480-24x(SS128)) version 16.1.2.14 16.1.2.14 by release-manager on Tue Oct 6 19:03:00 EDT 2015
//ExtremeXOS (Stack) version 15.3.3.5 v1533b5 by release-manager on Mon Dec 2 16:08:07 EST 2013
$config['os'][$os]['sysDescr_regex'][]      = '/XOS(?: \((?<hardware>(?!Stack).+)\))? version (?<version>\d[\d\.]+)/'; // exclude Stack
$config['os'][$os]['sysDescr_regex'][]      = '/XOS \(Stack\) version (?<version>\d[\d\.]+)/';
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['graphs'][]              = "device_fanspeed";

$os = "extremeware";
$config['os'][$os]['text']                  = "Extremeware";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "extremeware";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1916.2";
// sambong_pc - Version 4.1.19 (Build 2) by Release_Master Wed 08/09/2000 6:09p
// Summit200-24 - Version 6.2e.2 (Build 16) by Release_Master_ABU Thu 06/26/2003 16:33:54
// Alpine3804 - Version 7.8.3 (Build 5) by Release_Master 03/15/10 14:21:36
// BD6808 - Version 7.8.4 (Build 1) by Patch_Master 02/17/12 03:51:25
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+) - Version (?<version>\d[\w\.\-]+)/';
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";

$os = "extreme-wlc";
$config['os'][$os]['text']                  = "Extreme Wireless Controller";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['group']                 = "extremeware";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/Wireless Controller/',
  'sysObjectID'                             => '.1.3.6.1.4.1.1916.2',
);
//WM3600 Wireless Controller, Version 4.2.1.3-001R MIB=01a
//WM3400 Wireless Controller, Version 5.5.4.0-018R MIB=01a
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+) Wireless Controller, Version (?<version>\d[\w\.\-]+)/';
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";

// Enterasys / Extreme Networks since 2013

$os = "enterasys";
$config['os'][$os]['text']                  = "Extreme (Enterasys) OS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "enterasys";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5624.2.1.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5624.2.2.";
//Enterasys Networks, Inc. C5G124-48P2 Rev 06.51.02.0018
//Extreme Networks, Inc. B5G124-48P2 Rev 06.81.05.0003
$config['os'][$os]['sysDescr_regex'][]      = '/(?:Enterasys|Extreme) Networks, Inc\. (?<hardware>\S+) Rev (?<version>\d[\d\.\-]+)/';
$config['os'][$os]['mibs'][]                = "POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "ENTERASYS-POWER-ETHERNET-EXT-MIB";

$os = "enterasys-wl";
$config['os'][$os]['text']                  = "Extreme Wireless Controller";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "enterasys";
#$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4329.15.1.1";

// Eltek

$os = "eltek";
$config['os'][$os]['text']                  = "Eltek";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['vendor']                = "Eltek";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12148.7";  // Energy SNMP Agent
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12148.9";  // ComPack/WebPower
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12148.11"; // Theia
//Theia(405015.009) Rev: 1.01a, Oct 23 2012 OS:2.5.2
//WebPower(402414.003) 4.7, May 25 2011 OS:2.5.2
//ELTEK Webpower(402411.003) Rev4.2, Apr 22 2008 OS:1.99
//ComPack(405002.009) Rev: 1.05, Jul 6 2011 OS:2.5.2
//WebPower(402414.003) Rev4.5,Jul 9 2010 OS:2.4 RC2
$config['os'][$os]['sysDescr_regex'][]      = '/(?:ELTEK )?(?<hardware>.+?)\([\d\.]+\).+?OS:(?<version>.+)/';
$config['os'][$os]['mibs'][]                = "ELTEK-DISTRIBUTED-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";

// ATTO Technology, Inc

$os = "atto-storage";
$config['os'][$os]['text']                  = "ATTO Storage";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['icon']                  = "atto";
$config['os'][$os]['vendor']                = "ATTO Technology";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4547.1";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4547.1.1"; // iPBridge
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4547.1.5"; // 6500N
$config['os'][$os]['model']                 = "atto"; // Use per model MIBs
//$config['os'][$os]['mibs'][]                = "ATTO6500N-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";
$config['os'][$os]['mib_blacklist'][]       = "EtherLike-MIB";
$config['os'][$os]['discovery_blacklist'][] = "ports-stack";
//$config['os'][$os]['discovery_blacklist'][] = "ipv6-addresses";
$config['os'][$os]['discovery_blacklist'][] = "inventory";
$config['os'][$os]['discovery_blacklist'][] = "storage";
$config['os'][$os]['discovery_blacklist'][] = "neighbours";
$config['os'][$os]['discovery_blacklist'][] = "bgp-peers";
$config['os'][$os]['discovery_blacklist'][] = "pseudowires";
$config['os'][$os]['discovery_blacklist'][] = "ucd-diskio";

// Maipu (another cisco cloner) // Mibs seem difficult to find.

$os = "maipu-mypower";
$config['os'][$os]['text']                  = "Maipu MyPower";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Maipu";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5651.1.101."; // Routers
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5651.1.102."; // Switches
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5651.1.103."; // Wireless Controllers
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => array('/^MyPower .+Software (?!MPSec)/', // excluded MPSec
                                                     '/^MyPower [A-Z]\w+/'),
  'sysObjectID'                             => '.1.3.6.1.4.1.5651.1',
);

$os = "maipu-mpsec";
$config['os'][$os]['text']                  = "Maipu MPSec";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['vendor']                = "Maipu";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/MPSec|VPN/',
  'sysObjectID'                             => '.1.3.6.1.4.1.5651.1',
);

$os = "maipu-ios";
$config['os'][$os]['text']                  = "Maipu IOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Maipu";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5651.1"; // All other

// New Greennet / GCOM

$os = "gcom";
$config['os'][$os]['text']                  = "GCOM";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "gcom";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.13464.1.3.";
$config['os'][$os]['mibs'][]                = "GBNPlatformOAM-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";

// Bluecat

$os = "bluecat-adonis";
$config['os'][$os]['text']                  = "BlueCat Adonis";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "bluecat";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.13315";

// Bluecoat

$os = "bcmc";
$config['os'][$os]['text']                  = "Blue Coat Management Center";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "bluecoat";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14501.6";
$config['os'][$os]['sysDescr_regex'][]      = '/ release (?<version>\d[\w\.]+)/';

$os = "cas";
$config['os'][$os]['text']                  = "Blue Coat Content Analysis System";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "bluecoat";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3417.1.4";
//Blue Coat S400-A3, Version: 1.2.4.4, Release Id: 157593
$config['os'][$os]['sysDescr_regex'][]      = '/Blue Coat (?<hardware>\S+), Version: (?<version>\d[\w\.]+)/';
$config['os'][$os]['mibs'][]                = "BLUECOAT-CAS-MIB";
$config['os'][$os]['mibs'][]                = "BLUECOAT-SG-SENSOR-MIB";
$config['os'][$os]['mibs'][]                = "BLUECOAT-SG-USAGE-MIB";

$os = "packetshaper";
$config['os'][$os]['text']                  = "Blue Coat Packetshaper";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "bluecoat";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2334.";

$os = "proxyav";
$config['os'][$os]['text']                  = "Blue Coat Proxy AV";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3417.1.3";
$config['os'][$os]['sysDescr'][]            = "/ProxyAV/";
//Blue Coat AV810 Series, ProxyAV Version: 3.5.2.1, Release id: 145195
$config['os'][$os]['sysDescr_regex'][]      = '/Blue Coat (?<hardware>[\w\ ]+?)(?: Series)?,(?: Proxy\w+)? Version:(?: SGOS)? (?<version>\d[\d\.]+)/';
$config['os'][$os]['mibs'][]                = "BLUECOAT-MIB";
$config['os'][$os]['mibs'][]                = "BLUECOAT-AV-MIB";
$config['os'][$os]['mibs'][]                = "BLUECOAT-SG-SENSOR-MIB";
$config['os'][$os]['mibs'][]                = "BLUECOAT-SG-USAGE-MIB";

$os = "proxysg";
$config['os'][$os]['text']                  = "Blue Coat SGOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "bluecoat";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3417.1.1";
$config['os'][$os]['sysDescr'][]            = "/SGOS/";
//Blue Coat SG600 Series, Version: SGOS 5.5.11.1, Release id: 110885 Proxy Edition
//Blue Coat SG8100 Series, ProxySG Version: SGOS 4.2.8.6, Release id: 35252
$config['os'][$os]['sysDescr_regex'][]      = '/Blue Coat (?<hardware>[\w\ ]+?)(?: Series)?,(?: Proxy\w+)? Version:(?: SGOS)? (?<version>\d[\d\.]+)/';
$config['os'][$os]['mibs'][]                = "BLUECOAT-MIB";
$config['os'][$os]['mibs'][]                = "BLUECOAT-SG-PROXY-MIB";
$config['os'][$os]['mibs'][]                = "BLUECOAT-SG-SENSOR-MIB";
// $config['os'][$os]['mibs'][]                = "BLUECOAT-SG-ICAP-MIB";

// Zhone

$os = "zhonedslam";
$config['os'][$os]['text']                  = "Zhone DLSAM";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Zhone";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
//Paradyne ATM ReachDSL Unit; Model: 4213-A1-530; CCA: 868-5315-8201; S/W Release: 02.03.05; Hardware Revision: 5315-82H; Serial number: 6938473 ;
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>(?:Paradyne|Zhone) .+?)(?: Unit)?; Model: (?<hardware1>\w+(?:\-\w+)*);.+?; S\/W Release: (?<version>[\d\.]+);.+; Serial number: (?<serial>\w+)/';
//Paradyne DSLAM; Model: 8820-A2-xxx
//Zhone DSLAM; Model: 8820-A2-xxx
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>(?:Paradyne|Zhone) .+?)(?: Unit)?; Model: (?<hardware1>\w+(?:\-\w+)*)/';
//PARADYNE GranDSLAM 4200; S/W Release: 02.01.23;
//PARADYNE BitStorm 2600; S/W Release: 02.05.07;
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>(?:Paradyne|Zhone) .+?); S\/W Release: (?<version>[\d\.]+)/i';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1795";

$os = "zhone-znid";
$config['os'][$os]['text']                  = "Zhone ZNID";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Zhone";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5504.1.2.9";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5504.1.2.10";

$os = "zhone-ethx";
$config['os'][$os]['text']                  = "Zhone EtherXtend";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Zhone";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5504.1.2.8";
//$config['os'][$os]['mibs'][]                = "";

$os_group = "zhone";
$config['os_group'][$os_group]['vendor']          = "Zhone";
$config['os_group'][$os_group]['graphs'][]        = "device_bits";
$config['os_group'][$os_group]['graphs'][]        = "device_processor";
$config['os_group'][$os_group]['graphs'][]        = "device_mempool";
$config['os_group'][$os_group]['mibs'][]          = "ZHONE-CARD-RESOURCES-MIB";
$config['os_group'][$os_group]['mibs'][]          = "ZHONE-SHELF-MONITOR-MIB";
$config['os_group'][$os_group]['mib_blacklist'][] = "HOST-RESOURCES-MIB";
$config['os_group'][$os_group]['mib_blacklist'][] = "CISCO-CDP-MIB";

$os = "zhone-mxk";
$config['os'][$os]['text']                  = "Zhone MXK";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "zhone";
$config['os'][$os]['snmp']['max-rep']       = 50;
//$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5504.1.7";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5504.1.8";

$os = "zhone-malc";
$config['os'][$os]['text']                  = "Zhone MALC";
$config['os'][$os]['type']                  = "voice";
$config['os'][$os]['group']                 = "zhone";
$config['os'][$os]['snmp']['max-rep']       = 50;
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5504.1.6";

// Zhone Raptor - .1.3.6.1.4.1.5504.1.6.19.4.1.1

// A10

$os = "a10-ax";
$config['os'][$os]['text']                  = "A10 ACOS";
$config['os'][$os]['type']                  = "loadbalancer";
$config['os'][$os]['vendor']                = "A10";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.22610.1.3";
//AX Series Advanced Traffic Manager AXvThunder, ACOS 2.8.1-P2,
//AX Series Advanced Traffic Manager AXvThunder, ACOS 4.0.1,
//AX Series Advanced Traffic Manager AX1000, Advanced Core OS (ACOS) version 2.2.4-p8,
//Thunder Series Unified Application Service Gateway TH3030S, ACOS 2.7.2-P7,
$config['os'][$os]['sysDescr_regex'][]      = '/(?:AX)?(?<hardware>\w+), (?:ACOS|Advanced Core OS \(ACOS\) version) (?<version>\d[\w\.\-]+)/';
$config['os'][$os]['mibs'][]                = "A10-AX-MIB";

$os = "a10-ex";
$config['os'][$os]['text']                  = "A10 EX";
$config['os'][$os]['type']                  = "loadbalancer";
$config['os'][$os]['vendor']                = "A10";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.22610.1.2";
// A10 Networking System Software Copyright (c) 2004-2009 by A10 Networks, Inc. http://www.a10networks.com/support Hardware Version: 1.0 Software Version: 3.2
$config['os'][$os]['sysDescr_regex'][]      = '/Software Version: (?<version>\d[\w\.\-]+)/';
//$config['os'][$os]['mibs'][]                = "A10-EX-MIB";

// Avaya/Nortel

$os_group = "avaya";
$config['os_group'][$os_group]['vendor']    = "Avaya Networks";
$config['os_group'][$os_group]['icon']      = "avaya";
$config['os_group'][$os_group]['graphs'][]  = "device_bits";
$config['os_group'][$os_group]['mibs'][]    = "S5-CHASSIS-MIB";
$config['os_group'][$os_group]['mibs'][]    = "RAPID-CITY";
$config['os_group'][$os_group]['mib_blacklist'][] = "HOST-RESOURCES-MIB";
$config['os_group'][$os_group]['mib_blacklist'][] = "CISCO-CDP-MIB";

$os = "avaya-ers";
$config['os'][$os]['text']                  = "ERS Software";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "avaya";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.45.3";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2272";

$os = "avaya-bsr";
$config['os'][$os]['text']                  = "BSR Software";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "avaya";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.45.3.70";

$os = "avaya-wl";
$config['os'][$os]['text']                  = "Avaya Wireless";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['group']                 = "avaya";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.45.3.77";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.45.8.1";  // ArrayOS

$os = "avaya-phone";
$config['os'][$os]['text']                  = "Avaya IP Phone";
$config['os'][$os]['vendor']                = "Avaya Networks";
$config['os'][$os]['icon']                  = "avaya";
$config['os'][$os]['type']                  = "voip";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6889.1.69.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6889.1.69.5";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6889.1.70.1";
//$config['os'][$os]['mibs'][]                = "Avaya-46xxIPTelephone-MIB";
//$config['os'][$os]['mibs'][]                = "Avaya-96xxIPTelephone-MIB";

$os = "avaya-server";
$config['os'][$os]['text']                  = "Avaya Server";
$config['os'][$os]['vendor']                = "Avaya Networks";
$config['os'][$os]['icon']                  = "avaya";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6889.1.8.1";    // Generic
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6889.1.8.1.49"; // S8700
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6889.1.8.1.56"; // S8400
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['mibs'][]                = "G3-AVAYA-MIB";


$os = "avaya-eis"; /// Avaya product families are very confusing.
$config['os'][$os]['text']                  = "Avaya EIS";
$config['os'][$os]['vendor']                = "Avaya Networks";
$config['os'][$os]['icon']                  = "avaya";
$config['os'][$os]['type']                  = "voice";
$config['os'][$os]['ifname']                 = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6889.1.45";    // Generic

$os = "avaya-g700"; /// Avaya product families are very confusing.
$config['os'][$os]['text']                  = "Avaya G700";
$config['os'][$os]['vendor']                = "Avaya Networks";
$config['os'][$os]['icon']                  = "avaya";
$config['os'][$os]['type']                  = "voice";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6889.1.9";    // Generic
$config['os'][$os]['mibs'][]                = "G700-MG-MIB";


$os = "avaya-ipo";
$config['os'][$os]['text']                  = "Avaya IP Office";
$config['os'][$os]['vendor']                = "Avaya Networks";
$config['os'][$os]['icon']                  = "avaya";
$config['os'][$os]['type']                  = "workstation";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/ \d[\d\.]+ build \d+/',
  'sysObjectID'                             => '.1.3.6.1.4.1.6889.1.',
);
//IPO-Linux-PC 9.1.7.0 build 163
//S-Edition Expansion (V2) 9.1.8.0 build 172
//IP 500 V2 9.1.0.0 build 437
$config['os'][$os]['sysDescr_regex'][]      = '/ (?<version>\d[\d\.]+) build \d+/';
$config['os'][$os]['mibs'][]                = "G3-AVAYA-MIB";

$os = "nortel-baystack";
$config['os'][$os]['text']                  = "Baystack Software";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Nortel Networks"; // Avaya since 2009
$config['os'][$os]['icon']                  = "nortel";
$config['os'][$os]['group']                 = "avaya";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.45.3.49"; // Baystack Series
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.45.3.43";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.45.3.35";

// Arista

$os = "arista_eos";
$config['os'][$os]['text']                  = "Arista EOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "arista";
//$config['os'][$os]['snmp']['max-rep']       = 100; // Seems to break.
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.30065.1.2759";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.30065.1.3011";
$config['os'][$os]['sysDescr'][]            = "/^Arista Networks EOS/";
//Arista Networks EOS version 4.14.6M running on an Arista Networks DCS-7050S-64
$config['os'][$os]['sysDescr_regex'][]      = '/EOS version (?<version>\d[\w\.]+) running on an Arista Networks (?<hardware>\w\S+)/';
$config['os'][$os]['mibs'][]                = "ARISTA-ENTITY-SENSOR-MIB";
$config['os'][$os]['mibs'][]                = "ARISTA-BGP4V2-MIB";

// Calix

$os = "calix";
$config['os'][$os]['text']                  = "Calix";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "calix";
$config['os'][$os]['ifname']                  = 1;
#$config['os'][$os]['snmp']['max-rep']       = 15; // More - breaks, less or nobulk - very slow polling and discovery
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6321";
$config['os'][$os]['mibs'][]                = "E7-Calix-MIB";
$config['os'][$os]['model']                 = "calix"; // Per-HW hardware names

// Occam Networks was acquired by Calix in 2011
$os = "calix-blc";
$config['os'][$os]['text']                  = "Calix BLC";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "calix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6066";

// Citrix

$os = "netscaler";
$config['os'][$os]['text']                  = "Citrix Netscaler";
$config['os'][$os]['type']                  = "loadbalancer";
$config['os'][$os]['icon']                  = "citrix";
$config['os'][$os]['snmp']['max-rep']       = 50; // Seems to break
$config['os'][$os]['graphs'][]              = "device_netscaler_tcp_conn";
$config['os'][$os]['graphs'][]              = "device_netscaler_tcp_pkts";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5951";
$config['os'][$os]['mibs'][]                = "NS-ROOT-MIB";
$config['os'][$os]['mibs'][]                = "BGP4-MIB";

// F5

$os = "f5";
$config['os'][$os]['text']                  = "F5 BIG-IP";
$config['os'][$os]['type']                  = "loadbalancer";
$config['os'][$os]['icon']                  = "f5";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3375.2.1.3.4.";
$config['os'][$os]['mibs'][]                = "F5-BIGIP-SYSTEM-MIB";
$config['os'][$os]['mibs'][]                = "F5-BIGIP-LOCAL-MIB";
#$config['os'][$os]['mibs'][]                = "F5-BIGIP-GLOBAL-MIB";
$config['os'][$os]['mibs'][]                = "F5-BIGIP-APM-MIB";
$config['os'][$os]['mib_blacklist'][]       = "IPV6-MIB";
$config['os'][$os]['snmp']['max-rep']       = 100;

// Airconsole

$os = "airconsole";
$config['os'][$os]['text']                  = "Air Console";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['icon']                  = "airconsole";
$config['os'][$os]['graphs'][]              = "device_uptime";
$config['os'][$os]['sysDescr'][]            = "/^Airconsole/";
$config['os'][$os]['sysDescr_regex'][]      = '/^Airconsole (?<version>\d[\w\.]+)/';

// PacketFlux

$os = "sitemonitor";
$config['os'][$os]['text']                  = "PacketFlux SiteMonitor";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['icon']                  = "packetflux";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.32050";
$config['os'][$os]['mibs'][]                = "PACKETFLUX-MIB";

// Aerohive

$os = "hiveos";
$config['os'][$os]['text']                  = "HiveOS";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "aerohive";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.26928.1";
//AP230, HiveOS 6.6r1 release build2287
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>.+), HiveOS (?<version>\d[\w\.]+)/';

$os = "aerohive-os"; // Another broadcom device..
$config['os'][$os]['text']                  = "Aerohive Switch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Aerohive";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^Aerohive/',
  'sysObjectID'                             => array('.1.3.6.1.4.1.4413', '.1.3.6.1.4.1.7244'),
);
//Aerohive SR2348P: 48 GE POE+ ports, 4 XE SFP+ ports, Stackable, 1.0.1.22, Linux 3.6.5
$config['os'][$os]['sysDescr_regex'][]      = '/Aerohive (?<hardware>\S+): (?<features>.+), (?<version>\d[\d\.\-]+), Linux \d[\d\.]+/';
$config['os'][$os]['graphs'][]              = "device_bits";
// NOTE, MIBs not tested!
$config['os'][$os]['mibs'][]                = "FASTPATH-BOXSERVICES-PRIVATE-MIB";
//$config['os'][$os]['mibs'][]                = "BROADCOM-POWER-ETHERNET-MIB";
//$config['os'][$os]['mibs'][]                = "FASTPATH-SWITCHING-MIB";
//$config['os'][$os]['mibs'][]                = "FASTPATH-ISDP-MIB"; // Neighbours discovery
$config['os'][$os]['mibs'][]                = "EdgeSwitch-BOXSERVICES-PRIVATE-MIB";
$config['os'][$os]['mibs'][]                = "EdgeSwitch-ISDP-MIB";
$config['os'][$os]['mibs'][]                = "EdgeSwitch-SWITCHING-MIB";
$config['os'][$os]['mibs'][]                = "POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "EdgeSwitch-POWER-ETHERNET-MIB";

// Cambium Canopy
$os = "canopy";
$config['os'][$os]['text']                  = "Cambium Canopy";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "cambium";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.161.19.250.256";
$config['os'][$os]['mibs'][]                = "WHISP-APS-MIB";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_wifi_clients";

// Cambium PTP
$os = "cambium-ptp";
$config['os'][$os]['text']                  = "Cambium PTP";
$config['os'][$os]['vendor']                = "Cambium Networks";
$config['os'][$os]['model']                 = "cambium";  // Per-HW MIBs and hardware names
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "cambium";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.17713";

// Cambium ePMP
$os = "epmp";
$config['os'][$os]['text']                  = "Cambium ePMP";
$config['os'][$os]['vendor']                = "Cambium Networks";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "cambium";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.17713.21";

// Proxim

$os = "proxim";
$config['os'][$os]['text']                  = "Proxim Wireless";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "proxim";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11898.2.4";

// Raisecom

$os = "raisecom";
$config['os'][$os]['text']                  = "Raisecom";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['icon']                  = "raisecom";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8886.";

// Ruckus Wireless <http://www.ruckuswireless.com>

$os_group = "ruckus";
$config['os_group'][$os_group]['vendor']    = "Ruckus Wireless";
$config['os_group'][$os_group]['icon']      = "ruckus";
$config['os_group'][$os_group]['graphs'][]  = "device_bits";
$config['os_group'][$os_group]['ports_separate_walk'] = 1; // Force use separate ports polling feature
$config['os_group'][$os_group]['mibs'][]    = "RUCKUS-RADIO-MIB";
$config['os_group'][$os_group]['mibs'][]    = "RUCKUS-WLAN-MIB";
$config['os_group'][$os_group]['mibs'][]    = "RUCKUS-SWINFO-MIB";
$config['os_group'][$os_group]['mibs'][]    = "RUCKUS-HWINFO-MIB";
#$config['os_group'][$os_group]['mibs'][]    = "RUCKUS-DEVICE-MIB";
#$config['os_group'][$os_group]['mibs'][]    = "RUCKUS-SYSTEM-MIB";
$config['os_group'][$os_group]['mib_blacklist'][] = "HOST-RESOURCES-MIB";
$config['os_group'][$os_group]['mib_blacklist'][] = "CISCO-CDP-MIB";

$os = "ruckus-zf";                          // Ruckus ZoneFlex
$config['os'][$os]['text']                  = "Ruckus ZoneFlex";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['group']                 = "ruckus";
// $config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25053.3.1.4";

$os = "ruckus-zd";                          // Ruckus ZoneDirector
$config['os'][$os]['text']                  = "Ruckus ZoneDirector";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['group']                 = "ruckus";
// $config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25053.3.1.5";
$config['os'][$os]['mibs'][]                = "RUCKUS-ZD-SYSTEM-MIB";

$os = "ruckus-scg";                         // Ruckus SmartCellGateway
$config['os'][$os]['text']                  = "Ruckus SmartCellGateway";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['group']                 = "ruckus";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25053.3.1.10";

$os = "ruckus-sz";                          // Ruckus SmartZone
$config['os'][$os]['text']                  = "Ruckus SmartZone";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['group']                 = "ruckus";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25053.3.1.11";

// All other
$os = "ruckus-wl";
$config['os'][$os]['text']                  = "Ruckus Wireless";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['group']                 = "ruckus";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25053.3";

// Trango

$os = "trango-apex";
$config['os'][$os]['text']                  = "Trango Apex";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "trango";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5454.1.60";
$config['os'][$os]['mibs'][]                = "TRANGO-APEX-RF-MIB";
$config['os'][$os]['mibs'][]                = "TRANGO-APEX-GIGE-MIB";
$config['os'][$os]['mibs'][]                = "TRANGO-APEX-MODEM-MIB";
$config['os'][$os]['mibs'][]                = "TRANGO-APEX-SYS-MIB";
$config['os'][$os]['graphs'][]              = "device_dbm";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_ping";

// Dell

// Dell Force 10. FTOS is now called DNOS v9 and runs on mid/high-end dell switches.

$os = "ftos";
$config['os'][$os]['text']                  = "Dell/Force10 NOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "force10";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['model']                 = "force10"; // Per-HW MIBs and hardware names
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6027.1.1"; // E-Series
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6027.1.2"; // C-Series
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6027.1.3"; // S-Series
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6027.1.4"; // M-Series
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6027.1.5"; // Z-Series
$config['os'][$os]['mibs'][]                = "F10-CHASSIS-MIB";
$config['os'][$os]['mibs'][]                = "F10-C-SERIES-CHASSIS-MIB";
$config['os'][$os]['mibs'][]                = "F10-S-SERIES-CHASSIS-MIB";
$config['os'][$os]['mibs'][]                = "F10-M-SERIES-CHASSIS-MIB";
$config['os'][$os]['mibs'][]                = "DELL-NETWORKING-CHASSIS-MIB";
$config['os'][$os]['mibs'][]                = "FORCE10-BGP4-V2-MIB";

/// This is only to be used for Dell Network Operating System (DNOS) v6 Devices.
/// This is just a renaming of Dell PowerConnect

$os = "dnos6";
$config['os'][$os]['text']                  = 'Dell Networking OS';
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['type']                  = 'network';
$config['os'][$os]['icon']                  = 'dell';
$config['os'][$os]['graphs'][]              = 'device_bits';
$config['os'][$os]['graphs'][]              = 'device_processor';
$config['os'][$os]['graphs'][]              = 'device_mempool';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3042";  // N4032
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3044";  // N4032F
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3045";  // N4064
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3046";  // N4064F
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3053";  // N2024
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3054";  // N2048
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3055";  // N2024P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3056";  // N2048P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3057";  // N3024
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3058";  // N3048
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3059";  // N3024P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3060";  // N3048P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3061";  // N3024F
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3063";  // N1524
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3064";  // N1524P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3065";  // N1548
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3066";  // N1548P
$config['os'][$os]['sysDescr'][]            = "/^Dell Networking N\d+/";      // Any other N-series switch
//Dell Networking N4064F, 6.1.2.4, Linux 2.6.32.9
//Dell Networking N3048, 6.2.0.5, Linux 3.6.5-1289203e
$config['os'][$os]['sysDescr_regex'][]      = '/Dell Networking (?<hardware>N\w+), (?<version>[\d\.\-]+)/';
$config['os'][$os]['mibs'][]                = 'DNOS-SWITCHING-MIB';
$config['os'][$os]['mibs'][]                = 'DNOS-BOXSERVICES-PRIVATE-MIB';

/// This is only to be used for Broadcom-based PowerConnects

$os = "powerconnect-fastpath";
$config['os'][$os]['text']                  = "Dell PowerConnect (FastPath)";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Dell";
$config['os'][$os]['group']                 = "fastpath";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3006";  // 3424
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3007";  // 3448
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3008";  // 3424P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3009";  // 3448P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3010";  // 6224
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3011";  // 6248
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3012";  // 6224P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3013";  // 6248P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3014";  // 6224F
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3015";  // M6220
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3022";  // M8024
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3023";  // 8024
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3024";  // 8024F
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3025";  // M6384
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3026";  // 2824
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3027";  // 2848
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3034";  // 7024
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3035";  // 7048
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3036";  // 7024P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3037";  // 7048P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3038";  // 7024F
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3039";  // 7048R
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3040";  // 7048R-RA
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3041";  // M8024-k
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3052";  // VRTX R1-2401
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3062";  // VRTX R1-2210
$config['os'][$os]['sysDescr'][]            = "/^(Dell|PowerConnect) .+?, VxWorks/";
$config['os'][$os]['mibs'][]                = "DNOS-BOXSERVICES-PRIVATE-MIB";
$config['os'][$os]['mibs'][]                = "OLD-DNOS-BOXSERVICES-PRIVATE-MIB"; // Note, DNOS-BOXSERVICES-PRIVATE-MIB and OLD-DNOS-BOXSERVICES-PRIVATE-MIB are crossed
$config['os'][$os]['mibs'][]                = "Dell-Vendor-MIB";                  // Keep this below OLD-DNOS-BOXSERVICES-PRIVATE-MIB, checks for duplicate sensors
$config['os'][$os]['mibs'][]                = "POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "DNOS-POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "DNOS-SWITCHING-MIB";
$config['os'][$os]['mibs'][]                = "DNOS-ISDP-MIB"; // Neighbours discovery
$config['os'][$os]['mibs'][]                = "SMON-MIB";
$config['os'][$os]['mibs'][]                = "FASTPATH-INVENTORY-MIB"; // Stack ports

// This is only to be used for RADLAN-based PowerConnects
$os = "powerconnect-radlan";
$config['os'][$os]['text']                  = "Dell PowerConnect (RADLAN)";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "radlan";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['vendor']                = "Dell";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3000"; // 6024
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3002"; // 3324
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3003"; // 3348
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3004"; // 5324
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3005"; // 5316
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3016"; // 3534
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3017"; // 3548
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3018"; // 3524P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3019"; // 3548P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3020"; // 5424
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3021"; // 5448
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3028"; // 2824
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3029"; // 2848
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3030"; // 5524
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3031"; // 5548
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3032"; // 5524P
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3033"; // 5548P
//$config['os'][$os]['mibs'][]                = "RADLAN-HWENVIROMENT";
$config['os'][$os]['mibs'][]                = "Dell-Vendor-MIB"; // For version/hardware/...
//$config['os'][$os]['mibs'][]                = "RADLAN-rndMng";
$config['os'][$os]['mibs'][]                = "Dell-POE-MIB";
$config['os'][$os]['mibs'][]                = "RADLAN-DEVICEPARAMS-MIB";

$os = "powerconnect-old";
$config['os'][$os]['text']                  = "Dell PowerConnect (OLD)";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['vendor']                = "Dell";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.1";    // 3024
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.2";    // 5012
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.3";    // 3248
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.4";    // 5224
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.5";    // 3048
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.1000"; // 5212
//$config['os'][$os]['mibs'][]                = "powerConnect4-MIB";    // 5212 / 5224
//$config['os'][$os]['mibs'][]                = "POWERCONNECT3024-MIB"; // 3024
//$config['os'][$os]['mibs'][]                = "POWERCONNECT3048-MIB"; // 3048
//$config['os'][$os]['mibs'][]                = "POWERCONNECT5012-MIB"; // 5012
//$config['os'][$os]['mibs'][]                = "PowerConnect3248-MIB"; // 3248

$os = "dell-ups";
$config['os'][$os]['text']                  = "Dell UPS";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['vendor']                = "Dell";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10902.2";
$config['os'][$os]['mibs'][]                = "UPS-MIB";
//$config['os'][$os]['mibs'][]                = "DELL-SNMP-UPS-MIB";

$os = "dell-pdu";
$config['os'][$os]['text']                  = "Dell PDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['vendor']                = "Dell";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10903.200";
$config['os'][$os]['mibs'][]                = "DellrPDU-MIB";

$os = "powervault";
$config['os'][$os]['text']                  = "Dell PowerVault";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['icon']                  = "dell";
$config['os'][$os]['remote_access']         = array('http');
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10893.2.102";
$config['os'][$os]['mibs'][]                = "DELL-TL4000-MIB"; // Also covers TL2000-MIB, as the OIDs are identical, only the names differ (silly Dell!)

$os = "drac";
$config['os'][$os]['text']                  = "Dell iDRAC";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['icon']                  = "dell";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_fanspeed";
$config['os'][$os]['ports_unignore_descr']  = TRUE;
$config['os'][$os]['remote_access']         = array('http');
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10892.";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10892.2";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10892.5";
$config['os'][$os]['sysDescr'][]            = "/^Dell Out-of-band SNMP Agent/";
$config['os'][$os]['sysDescr'][]            = "/^This system component provides a complete set of remote management functions/";
$config['os'][$os]['sysDescr'][]            = "/^Linux iDRAC/i";
$config['os'][$os]['mibs'][]                = "DELL-RAC-MIB";
$config['os'][$os]['mibs'][]                = "IDRAC-MIB-SMIv2";
$config['os'][$os]['ipmi']                  = TRUE;

$os = "compellent";
$config['os'][$os]['text']                  = "Storage Center";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['vendor']                = "Dell";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['sysDescr'][]            = "/COMPELLENT/i";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/COMPELLENT/i',
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.8',
);
// FreeBSD hostname 9.1-RELEASE-p4 FreeBSD 9.1-RELEASE-p4 #0: Thu Dec 18 07:47:20 CST 2014 root@es-vb91-1:/work/buildmaster/r06.05.20/R06.05.20.018/06_05_20_018/work/buildmaster/r06.05.20/R06.05.20.018/src/sys/COMPELLENT amd64
$config['os'][$os]['mibs'][]                = "COMPELLENT-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "UCD-SNMP-MIB";

// Dell/EMC

$os = "onefs";
$config['os'][$os]['text']                  = "Isilon OneFS";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['icon']                  = "isilon";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12124.1";
$config['os'][$os]['sysDescr'][]            = "/Isilon OneFS/";
//Isilon OneFS isilon-1 v5.5.7.9 Isilon OneFS v5.5.7.9 B_5_5_7_9(RELEASE) i386
//Isilon OneFS isi-power-4 v7.1.1.2 Isilon OneFS v7.1.1.2 B_7_1_1_123(RELEASE) amd64
//ice-3 189406574 Isilon OneFS v8.0.0.1
$config['os'][$os]['sysDescr_regex'][]      = '/Isilon OneFS v(?<version>[\d\.\-]+)/';
$config['os'][$os]['mibs'][]                = "HOST-RESOURCES-MIB";   // There duplicate entry as in default, for correct order!
$config['os'][$os]['mibs'][]                = "ISILON-MIB";

$os = "emc-flare";
$config['os'][$os]['text']                  = "EMC Flare OS";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['icon']                  = "emc";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1981.1";
$config['os'][$os]['sysDescr'][]            = "/^[\w\-]+ - Flare \d[\d\.\-]+/";
// CX300 - Flare 2.07.0.300.5.008
// AX4-5i - Flare 2.23.0.50.5.711
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>[\w\-]+) - Flare (?<version>\d[\d\.\-]+)/';

$os = "emc-snas";
$config['os'][$os]['text']                  = "EMC SNAS";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['icon']                  = "emc";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1139";
// Product: EMC Celerra File Server Project: SNAS Version: T6.0.70.4
$config['os'][$os]['sysDescr_regex'][]      = '/SNAS Version: [A-Z]*(?<version>\d[\d\.\-]+)/i';

$os = "sonicwall";
$config['os'][$os]['text']                  = "SonicOS";
$config['os'][$os]['icon']                  = "sonicwall";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8741.1"; // Firewall
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8741.3"; // Global Management System
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8741.4"; // Email Security
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8741.5"; // Datacenter Operations
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8741.7"; // CDP
$config['os'][$os]['mibs'][]                = "SONICWALL-FIREWALL-IP-STATISTICS-MIB";
$config['os'][$os]['mibs'][]                = "SNWL-COMMON-MIB";

$os = "sonicwall-ssl";
$config['os'][$os]['text']                  = "SonicOS SSL";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['icon']                  = "sonicwall";
$config['os'][$os]['graphs'][0]             = "device_bits";
$config['os'][$os]['graphs'][1]             = "device_processor";
$config['os'][$os]['graphs'][2]             = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8741.6"; // SSL VPN
$config['os'][$os]['mibs'][]                = "SNWL-SSLVPN-MIB";

// Arbor Networks

$os = "arbos";
$config['os'][$os]['text']                  = "ArbOS";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['icon']                  = "arbor";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9694";
//Peakflow SP 5.7 (build CDHD-B) System Board Model: T5000PAL0 Serial Number: AZLH1260370SA
//Peakflow SP 7.6 (build FKCG-B) System Board Model: T5000PAL0 Serial Number: AZLH9070078A
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>[\w ]+?) +(?<version>\d[\d\.]+) +\(build \S+\) +System Board Model: +(?<hardware1>\w+) +Serial Number: +(?<serial>\S+)/';
//Pravail NSI 5.6.2 (build ELQM) T5520UR
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>[\w ]+?) +(?<version>\d[\d\.]+) +\(build \S+\) +(?<hardware1>\w+)/';
$config['os'][$os]['mibs'][]                = "PEAKFLOW-SP-MIB";

// Broadcom

/*
//Broadcom Bcm963xx Software Version 3.00L.01V.
//Broadcom Bcm963xx Software Version A131-306CTU-C08_R04
//$os = 'comtrend-';
*/

$os = "broadcom_fastpath";
$config['os'][$os]['text']                  = "Broadcom (FastPath)";
$config['os'][$os]['group']                 = "fastpath";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "broadcom";
$config['os'][$os]['sysDescr'][]            = "/Broadcom FASTPATH/";
$config['os'][$os]['sysDescr'][]            = "/^TSM \- /";
// Note this sysObjectID also used, as complex match arrays, in:
//  quanta-switch, unifi-switch, edgemax, dlink-dsl
//$config['os'][$os]['sysObjectID'][]         = '.1.3.6.1.4.1.4413';
//$config['os'][$os]['sysObjectID'][]         = '.1.3.6.1.4.1.7244';
$config['os'][$os]['discovery_os']          = "broadcom";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['mibs'][]                = "FASTPATH-BOXSERVICES-PRIVATE-MIB";
$config['os'][$os]['mibs'][]                = "BROADCOM-POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "FASTPATH-SWITCHING-MIB";
$config['os'][$os]['mibs'][]                = "FASTPATH-ISDP-MIB"; // Neighbours discovery

$os = "quanta-switch";
$config['os'][$os]['text']                  = "Quanta Switch";
$config['os'][$os]['group']                 = "fastpath";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Quanta";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4413.1.2";           // Quanta LB4M
$config['os'][$os]['sysDescr'][]            = "/^FASTPATH (Routing|Switching)/"; // Quanta LB4M
$config['os'][$os]['sysDescr'][]            = "/^Quanta LB/";                    // Quanta LB6M
//LB9, Runtime Code 1.4.12.00, Linux 2.6.35, ONIE
//LB4M 48x1G 2x10G, 1.1.0.8, VxWorks 6.6
//Quanta LB6M, 1.2.0.18, Linux 2.6.21.7
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^(LB\d|Quanta)/',
  'sysObjectID'                             => array('.1.3.6.1.4.1.4413', '.1.3.6.1.4.1.7244'),
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => array('.1.3.6.1.4.1.4413', '.1.3.6.1.4.1.7244'),
  'FASTPATH-SWITCHING-MIB::agentInventoryMachineType.0' => '/^(Quanta )?L[A-Z]\d/i',
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => array('.1.3.6.1.4.1.4413', '.1.3.6.1.4.1.7244'),
  'FASTPATH-SWITCHING-MIB::agentInventoryMachineModel.0' => '/^(Quanta )?L[A-Z]\d/i',
);
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['mibs'][]                = "FASTPATH-BOXSERVICES-PRIVATE-MIB";
//$config['os'][$os]['mibs'][]                = "BROADCOM-POWER-ETHERNET-MIB";
//$config['os'][$os]['mibs'][]                = "FASTPATH-SWITCHING-MIB";
//$config['os'][$os]['mibs'][]                = "FASTPATH-ISDP-MIB"; // Neighbours discovery
$config['os'][$os]['mibs'][]                = "EdgeSwitch-BOXSERVICES-PRIVATE-MIB";
$config['os'][$os]['mibs'][]                = "EdgeSwitch-ISDP-MIB";
$config['os'][$os]['mibs'][]                = "EdgeSwitch-SWITCHING-MIB";
$config['os'][$os]['mibs'][]                = "POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "EdgeSwitch-POWER-ETHERNET-MIB";

// Peplink

$os = "peplink-apone";
$config['os'][$os]['text']                  = "Pepwave AP One";
$config['os'][$os]['vendor']                = "Peplink";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.27662.100.1.";
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>.+)/';
$config['os'][$os]['mibs'][]                = "AP-SYSTEM-BASIC";
$config['os'][$os]['mibs'][]                = "AP-SYSTEM-NETWORK";
$config['os'][$os]['mibs'][]                = "AP-RADIO";
$config['os'][$os]['mibs'][]                = "AP-WLAN";
$config['os'][$os]['mibs'][]                = "AP-MANAGEMENT";
$config['os'][$os]['mibs'][]                = "AP-SPEEDFUSION";

// Peplink Balance

$os = "peplink-balance";
$config['os'][$os]['text']                  = "Peplink Balance";
$config['os'][$os]['vendor']                = "Peplink";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "network";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^Peplink Balance/',
  'sysObjectID'                             => array('.1.3.6.1.4.1.8072.3.2.10'),
);
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>.+)/';
$config['os'][$os]['mibs'][]                = "PEPLINK-BALANCE-MIB";


// Procera

$os = "plos";
$config['os'][$os]['text']                  = "PacketLogic";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['graphs'][]              = "device_storage";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['icon']                  = "procera";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.15397.2";

// Mellanox
$os = "mlnx-os";
$config['os'][$os]['text']                  = "MLNX-OS";
$config['os'][$os]['vendor']                = "Mellanox";
$config['os'][$os]['group']                 = "mellanox";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.33049";
//Mellanox SX1012,MLNX-OS,SWvSX_3.4.0012
//Mellanox SX1036,MLNX-OS,SWv3.4.1100
$config['os'][$os]['sysDescr_regex'][]      = "/Mellanox (?<hardware>\w+),MLNX-OS,SWv[^\d]*(?<version>[\d\.\-]+)/";

$os = "mlnx-ufm";
$config['os'][$os]['text']                  = "Mellanox UFM";
$config['os'][$os]['vendor']                = "Mellanox";
$config['os'][$os]['group']                 = "mellanox";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.33049.1.1.2";
//UFM Server 4.0.0
$config['os'][$os]['sysDescr_regex'][]      = "/Server (?<version>\d[\d\.\-]+)/";

// Motorola

$os = "netopia";
$config['os'][$os]['text']                  = "Motorola Netopia";
$config['os'][$os]['icon']                  = "motorola";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.304.2.2.";
//Netopia 2241N-VGx v7.7.4r1
//Netopia R910 v8.2r0
//Netopia 3346N-VGx v7.5.1r5_dp5030
$config['os'][$os]['sysDescr_regex'][]      = '/Netopia (?<hardware>[\w\-]+) v(?<version>\d[A-Za-z0-9\.]+)/';

// Tranzeo

$os = "tranzeo";
$config['os'][$os]['text']                  = "Tranzeo";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['graphs'][]              = "device_bits";
//Tranzeo TR6Rt, OS 6.8.0(1024), FW TR6-3.6.0Rt, 5.xGHz, 19dBi int. antenna
//Tranzeo TR6CPQ, OS 6.3.34(1019), FW TR6-2.0.12CPQ, 2.4GHz, 15dBi int. antenna
//Tranzeo TR900Rt, OS 6.8.0(1024), FW TR900-3.3.3Rt, 900MHz, 17dBi ext. antenna
$config['os'][$os]['sysDescr_regex'][]      = '/Tranzeo (?<hardware>.+?), OS (?<version>\d[\d\.]+).+?, FW .+?, (?<features>.+)/';
$config['os'][$os]['sysDescr'][]            = "/^Tranzeo/";

// Exalt

$os = "exalt";
$config['os'][$os]['text']                  = "Exalt";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25651.1.2";
$config['os'][$os]['mibs'][]                = "ExaltComProducts";

// Alvarion

$os = "breeze";
$config['os'][$os]['text']                  = "Alvarion Breeze";
$config['os'][$os]['vendor']                = "Alvarion";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_wifi_clients";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12394.4.1.";
//Alvarion - BreezeACCESS VL , Version: 4.0.24 (Date: Jul 03 2006, 15:31:42)
//Alvarion - BreezeNet B , Version: 4.0.24 (Date: Jul 03 2006, 15:31:42)
//Alvarion - BreezeACCESS VL , Version: 6.6.2 (Date: Jun 08 2011, 17:19:27)
$config['os'][$os]['sysDescr_regex'][]      = '/Alvarion \- (?<hardware>[\w\ ]+?) ?, Version: (?<version>\d[\d\.]+)/';
$config['os'][$os]['mibs'][]                = "ALVARION-DOT11-WLAN-MIB";
$config['os'][$os]['mibs'][]                = "ALVARION-DOT11-WLAN-TST-MIB";

$os = "breezemax";
$config['os'][$os]['text']                  = "Alvarion BreezeMax";
$config['os'][$os]['vendor']                = "Alvarion";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12394.1.";
//$config['os'][$os]['mibs'][]                = "ALVARION-DOT11-WLAN-MIB";

// D-Link

$os = "dlinkap";
$config['os'][$os]['text']                  = "D-Link Access Point";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['vendor']                = "D-Link";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.10.37";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.10.129";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.10.130";

$os = "dlinkvoip";
$config['os'][$os]['text']                  = "D-Link VoIP Gateway";
$config['os'][$os]['type']                  = "voip";
$config['os'][$os]['vendor']                = "D-Link";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.10.33";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.30.4.1.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.30.4.1.2";

$os = "dlinkdpr";
$config['os'][$os]['text']                  = "D-Link Print Server";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['vendor']                = "D-Link";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.11.10.1";

$os = "dlink";
$config['os'][$os]['text']                  = "D-Link Switch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "D-Link";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['model']                 = "d-link"; // Per-HW MIBs and hardware names
$config['os'][$os]['mibs'][]                = "AGENT-GENERAL-MIB";
$config['os'][$os]['mibs'][]                = "POWER-ETHERNET-MIB";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.171',
  'sysDescr'                                => '/^D[EG]S\-/',
);

$os = "dlink-ios";
$config['os'][$os]['text']                  = "D-Link Router";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "D-Link";
//$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.10.56.";
//D-Link Internetwork Operating System Software 602LB Series Software, Version 1.0.7D (BASE), RELEASE SOFTWARE Copyright (c) 2002 D-Link Corporation. Compiled: 2007-01-29 18:32:26 by system, Image text-base: 0x10000 ROM: System Bootstrap, Version 0.8.2 Serial num:DP6D176000120, ID num:206308 System image file is "Router.bin" DI-602LB (RISC) 32768K bytes of memory,3584K bytes of flash
//D-Link <C2><A3><C2><A8>India<C2><A3><C2><A9> Limited Internetwork Operating System Software 1705 Series Software, Version 1.0.7F (BASE), RELEASE SOFTWARE Copyright (c) 2007 by D-Link <C2><A3><C2><A8>India<C2><A3><C2><A9> Limited Compiled: 2008-01-31 14:25:20 by system, Image text-base: 0x10000 ROM: System Bootstrap, Version 0.8.2 Serial num:000H682000022, ID num:009675 System image file is "Router.bin" DI-1705 (RISC) 32768K bytes of memory,3584K bytes of flash
//D-Link Internetwork Operating System Software 602MB+ Series Software, Version 5.0.0D (BASE), RELEASE SOFTWARE Copyright (c) 2007 D-Link Corporation. Compiled: 2008-01-31 14:25:02 by system, Image text-base: 0x10000 ROM: System Bootstrap, Version 0.4.5 Serial num:DP6E193000097, ID num:201077 System image file is "DI3605-5.0.0D.bin" DI-602MB+ (RISC) 131072K bytes of memory,16384K bytes of flash
$config['os'][$os]['sysDescr_regex'][]      = '/(?:[\w\d\.\+\-]+) Series Software, Version (?<version>[\w\d\.\+\-]+) .+?Version (?:[\w\d\.\+\-]+)\sSerial num:(?<serial>[\d\w]+), .+System image file is "[^"]+"\s(?<hardware>[\w\d\.\+\-]+)/s';

$os = "dlink-generic";
$config['os'][$os]['text']                  = "D-Link Device";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "D-Link";
//$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.3";   // DSA- (VPN)
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.40."; // DWL- (Modem)
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.";    // All other D-Link devices (too many IDs)
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.10.65";   // DSA- (VPN)
$config['os'][$os]['model']                 = "d-link"; // Per-HW MIBs and hardware names
$config['os'][$os]['mibs'][]                = "POWER-ETHERNET-MIB";

// Broadcom based
$os = "dlink-dsl";
$config['os'][$os]['text']                  = "D-Link DSL";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "D-Link";
//$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.30.3.";      // Bcm963xx
$config['os'][$os]['sysDescr'][]            = "/^GE_\d\.\d+$/";              // DSL-2640B
$config['os'][$os]['sysDescr'][]            = "/^(D\-Link_)?DSL[_\-]\d+\w$/"; // DSL-2500E, DSL-2520U, DSL-2730R
//Bcm963xx Software Version 3.10L.02.
//Broadcom Bcm963xx Software Version 3-12-01-0G00
//Broadcom Bcm963xx Software Version RU_DSL-2500U_3-06-04-0Z00
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/bcm963/i',
  'sysObjectID'                             => '.1.3.6.1.4.1.4413.2.10',
);
$config['os'][$os]['model']                 = "d-link"; // Per-HW MIBs and hardware names

$os = "dlink-nas";
$config['os'][$os]['text']                  = "D-Link Storage";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['vendor']                = "D-Link";
//$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.50.";
$config['os'][$os]['model']                 = "d-link"; // Per-HW MIBs and hardware names

$os = "dlink-mc";
$config['os'][$os]['text']                  = "D-Link MediaConverter";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['vendor']                = "D-Link";
//$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.41.";
$config['os'][$os]['model']                 = "d-link"; // Per-HW MIBs and hardware names

$os = "dlinkfw";
$config['os'][$os]['text']                  = "D-Link Firewall";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['vendor']                = "D-Link";
$config['os'][$os]['sysDescr'][]            = "/D\-Link Firewall /";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171.20.";
$config['os'][$os]['model']                 = "d-link"; // Per-HW MIBs and hardware names
//D-Link Firewall 2.27.06.10-19064
$config['os'][$os]['sysDescr_regex'][]      = '/[Ff]irewall (?<version>\d[\d\.]+)/';

$os = "dlink-cam";
$config['os'][$os]['text']                  = "D-Link IP-Camera";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['vendor']                = "D-Link";
//$config['os'][$os]['sysDescr'][]            = "/D-Link Firewall /";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.171";
$config['os'][$os]['model']                 = "d-link"; // Per-HW MIBs and hardware names
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>DCS\-[\d\-]+)/';
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.171',
  'sysDescr'                                => '/^DCS\-/',
);
$config['os_group'][$os_group]['remote_access']     = array('http');


// Moxa

$os = "moxa-serial";
$config['os'][$os]['text']                  = "Moxa Serial Terminal";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['vendor']                = "Moxa";
$config['os'][$os]['model']                 = "moxa";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8691.2";    // Common oid for all
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8691.2.8";  // NPort 6000
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8691.2.11"; // CN2600
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8691.2.12"; // NPort S8000
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8691.2.13"; // NPort W2x50A
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8691.2.21"; // NPort IAW5000A-6I/O

$os = "moxa-np5000";
$config['os'][$os]['text']                  = "Moxa NP5000";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['vendor']                = "Moxa";
$config['os'][$os]['snmp']['nobulk']        = TRUE; // bulkwalk fails after 10 entries
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8691.2.7";

$os = "moxa-router";
$config['os'][$os]['text']                  = "Moxa Router";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['vendor']                = "Moxa";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8691.6";

$os = "moxa-switch";
$config['os'][$os]['text']                  = "Moxa Switch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Moxa";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8691.7";

// TP-LINK

$os = "tplinkap";
$config['os'][$os]['text']                  = "TP-LINK Wireless";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['vendor']                = "TP-LINK";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11863.1.1.2";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux TL\-WA\w+ /',
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux CPE[\d]+ [\d\.\-]+ .* PREEMPT/',
);
$config['os'][$os]['sysDescr_regex'][]      = '/^Linux (?<hardware>CPE[\d]+)/i';

$os = "tplink";
$config['os'][$os]['text']                  = "TP-LINK Switch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "TP-LINK";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11863";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux TL\-S\w+ /',
);

$os = "tplink-router";
$config['os'][$os]['text']                  = "TP-LINK Router";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "TP-LINK";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux TL\-(?!WA|S)\w+ /', // exclude tplink, tplinkap
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => array('.1.3.6.1.4.1.16972', '.1.3.6.1.4.1.1.2.3.4.5'),
  'sysDescr'                                => array('/^(?:TD\w+ )?\d+\.\d+\.\d+(?: \d\.\d+ v[\w\.]+)? Build \d+ Rel\.\d+\w?/i',
                                                     '/^TD\-[a-z]?\d{4}\w*/i'),
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => array('.1.3.6.1.4.1.16972', '.1.3.6.1.4.1.1.2.3.4.5'),
  'sysName'                                 => array('/^TD\-\w+/i', '/(TP\-LINK|Archer)/'),
);
//1.1.1 Build 140815 Rel.40202
//0.7.0 0.18 v0007.0 Build 130114 Rel.62291n
//TD8840T 0.8.0 2.4 v0007.0 Build 141022 Rel.38384n
$config['os_group'][$os_group]['sysDescr_regex'][]  = '/^(?(?:(?<hardware>TD\w+) )?<version>\d+\.\d+\.\d+)(?: \d\.\d+ v[\w\.]+)? Build \d+ Rel\.\d+\w?/';
//TD-W8951ND
//TD-8816 1.0
//TD-W8901G 3.0
$config['os_group'][$os_group]['sysDescr_regex'][]  = '/^(?<hardware>TD-\w+)(?: (?<version>\d+[\d\.]+))?/';

// Innatech

$os = "innacomm";
$config['os'][$os]['text']                  = "Innacomm Router";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Innatech";
//110118_1917-4.02L.03.wp1.A2pB025k.d21j2
//100826_0217-4.02L.03.wp1.A2pB025k.d21j2
//100702_1223-4.02L.03.wp1.A2pB025k.d21j2
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => array('.1.3.6.1.4.1.16972', '.1.3.6.1.4.1.1.2.3.4.5'),
  'sysDescr'                                => '/^\d{6}_\d{4}-[\d\.]+\w\.\d+\.wp\d\.\w+\.\w+/',
);

// AXIS

$os_group = "axis";
$config['os_group'][$os_group]['icon']              = "axis";
//; AXIS 241S; Video Server; 4.47; May 30 2008 15:19; 11C.1; 1;
//; AXIS M1011-W; Network Camera; 5.20.2; Sep 09 2011 10:44; 171; 1;
//; AXIS P5534-E; PTZ Dome Network Camera; 5.40.9.4; Aug 02 2013 13:03; 188.2; 1;
//; AXIS 232D; Network Dome Camera; 4.41; Apr 08 2008 11:38; 13A.3; 1;
//; AXIS Q1931-E; Thermal Network Camera; 5.75.1.3; Nov 25 2015 15:30; 1A5.1; 1;
//; AXIS P7214; Network Video Encoder; 5.50.4; May 23 2014 12:23; 197.2; 1;
$config['os_group'][$os_group]['sysDescr_regex'][]  = '/AXIS (?<hardware>[\w\-\+]+);.+?; (?<version>[\d\.]+)/';
// AXIS 5600+ Network Print Server V7.10.2 Jan 30 2007
$config['os_group'][$os_group]['sysDescr_regex'][]  = '/AXIS (?<hardware>[\w\-\+]+) [\w ]+?V(?<version>[\d\.]+)/';

$os = "axiscam";
$config['os'][$os]['text']                  = "AXIS Network Camera";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['group']                 = "axis";
$config['os'][$os]['sysDescr'][]            = "/AXIS .*? (Network (\w+ )?Camera|Video Server)/";
$config['os'][$os]['mibs'][]                = "AXIS-VIDEO-MIB";
$config['os'][$os]['mib_blacklist'][]       = "BGP4-MIB";
$config['os'][$os]['mib_blacklist'][]       = "OSPF-MIB";
$config['os'][$os]['mib_blacklist'][]       = "Q-BRIDGE-MIB";

$os = "axisencoder";
$config['os'][$os]['text']                  = "AXIS Network Video Encoder";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['group']                 = "axis";
$config['os'][$os]['sysDescr'][]            = "/AXIS .*? Video Encoder/";
$config['os'][$os]['mibs'][]                = "AXIS-VIDEO-MIB";
$config['os'][$os]['mib_blacklist'][]       = "BGP4-MIB";
$config['os'][$os]['mib_blacklist'][]       = "OSPF-MIB";
$config['os'][$os]['mib_blacklist'][]       = "Q-BRIDGE-MIB";

$os = "axisdocserver";
$config['os'][$os]['text']                  = "AXIS Network Document Server";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "axis";
$config['os'][$os]['sysDescr'][]            = "/^AXIS .*? Network Document Server/";

$os = "axisprintserver";
$config['os'][$os]['text']                  = "AXIS Network Print Server";
$config['os'][$os]['type']                  = "printer";
$config['os'][$os]['group']                 = "axis";
$config['os'][$os]['sysDescr'][]            = "/^AXIS .*? Network Print Server/";

// Hikvision

$os = "hikvision-cam";
$config['os'][$os]['text']                  = "Hikvision Network Camera";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['vendor']                = "Hikvision";
$config['os'][$os]['snmpable'][]            = ".1.3.6.1.4.1.39165.1.1.0"; // This device complete not have sysObjectID.0 or sysUpTime.0
$config['os'][$os]['discovery'][]           = array(
  // HIK-DEVICE-MIB::deviceType.0 = STRING: "DS-2CD2332-I"
  'HIK-DEVICE-MIB::deviceType.0'            => '/.+/', // any non empty output
);
$config['os'][$os]['mibs'][]                = "HIK-DEVICE-MIB";

$os = "hikvision-dvr";
$config['os'][$os]['text']                  = "Hikvision DVR";
$config['os'][$os]['vendor']                = "Hikvision";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.50001";
$config['os'][$os]['sysDescr'][]            = "/^Hikvision/";

// Wowza

$os = "wowza-engine";
$config['os'][$os]['text']                  = "Wowza Streaming Engine";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['group']                 = "enterprise_tree_only"; // Really this device not support anything except own MIB
$config['os'][$os]['vendor']                = "Wowza";
$config['os'][$os]['snmpable'][]            = ".1.3.6.1.4.1.46706.100.10.1.1.1.25.1"; // This device complete not have sysObjectID.0 or sysUpTime.0
$config['os'][$os]['discovery'][]           = array(
  'WOWZA-STREAMING-ENGINE-MIB::serverCounterGetVersion.1' => '/.+/', // any non empty output
  //'.1.3.6.1.4.1.46706.100.10.1.1.1.28.1' => '/.+/', // same numeric
);
//Wowza Streaming Engine 4 Monthly Edition 4.5.0.01 build18956
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>.+) (?<version>\d\.[\d.]+)(?: \w+)?$/';
$config['os'][$os]['mibs'][]                = "WOWZA-STREAMING-ENGINE-MIB";
$config['os'][$os]['poller_blacklist'][]    = "ports";

// MessPC

$os = "messpc-ethernetbox";
$config['os'][$os]['text']                  = "MessPC Ethernetbox";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['group']                 = "enterprise_tree_only"; // Really this device not support anything except own MIB
$config['os'][$os]['vendor']                = "MessPC";
$config['os'][$os]['snmpable'][]            = ".1.3.6.1.4.1.14848.2.1.1.1.0"; // This device complete not have sysObjectID.0 or sysUpTime.0
$config['os'][$os]['discovery'][]           = array(
  'BETTER-NETWORKS-ETHERNETBOX-MIB::version.0' => '/.+/', // any non empty output
);
//Version 1.62
$config['os'][$os]['sysDescr_regex'][]      = '/Version (?<version>\d+[\d.]+)/';
$config['os'][$os]['mibs'][]                = "BETTER-NETWORKS-ETHERNETBOX-MIB";
$config['os'][$os]['poller_blacklist'][]    = "ports";

// Global Technology Asssociates

$os = "gta-gb";
$config['os'][$os]['text']                  = "GTA GB-OS";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['icon']                  = "gta";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_ucd_cpu";
$config['os'][$os]['graphs'][]              = "device_netstat_ip";
$config['os'][$os]['graphs'][]              = "device_gbStatistics-conns-inout";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.13559";
//GB-250 5.2.7 FWKAMERBEEK
//GB-Ware Unrestricted x86 6.1.5 MaidaVale
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>GB\-[\w\-]+)(?:.*?) (?<version>\d[\w\.]+)/';
$config['os'][$os]['mibs'][]                = "GBOS-MIB";

// EPPC UPS

$os = "eppc-ups";
$config['os'][$os]['text']                  = "EPPC UPS";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['icon']                  = "powerwalker";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.935.10.1";
$config['os'][$os]['mibs'][]                = "EPPC-MIB";

// General Electric UPS

$os = "ge-ups";
$config['os'][$os]['text']                  = "General Electric UPS";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['icon']                  = "ge";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.818.1";
$config['os'][$os]['mibs'][]                = "GEPARALLELUPS-MIB";

// Gamatronic

$os = "gamatronicups";
$config['os'][$os]['text']                  = "Gamatronic UPS Stack";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6050.5";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^$/', // Required? empty sysDescr
  'GAMATRONIC-MIB::psUnitManufacture.0'     => '/Gamatronic/',
);
$config['os'][$os]['mibs'][]                = "GAMATRONIC-MIB";

// Powerware

$os = "powerware";
$config['os'][$os]['text']                  = "Powerware UPS";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['icon']                  = "eaton";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_frequency";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.534";
$config['os'][$os]['mibs'][]                = "XUPS-MIB";

// Mega System Technologies

$os = "netagent";
$config['os'][$os]['text']                  = "NetAgent";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['vendor']                = "MegaTec";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.935";
$config['os'][$os]['mibs'][]                = "XPPC-MIB";

// Eaton

$os = "eaton-sc";
$config['os'][$os]['text']                  = "Eaton SC"; // SC == System Controller
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['icon']                  = "eaton";
//$config['os'][$os]['graphs'][]              = "device_voltage";
//$config['os'][$os]['graphs'][]              = "device_current";
//$config['os'][$os]['graphs'][]              = "device_frequency";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1918.2";
//SC200 Controller - software version 2.02
//SC200 Controller - software version 4.04
//SC200 Supervisory Module - software version 1.01
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>SC\d+) .+?version (?<version>[\d\.]+)/';
$config['os'][$os]['mibs'][]                = "RPS-SC200-MIB";

$os = "eaton-epdu";
$config['os'][$os]['text']                  = "Eaton ePDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['vendor']                = "Eaton";
$config['os'][$os]['model']                 = "eaton";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_frequency";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.534.6";
$config['os'][$os]['mibs'][]                = "EATON-EPDU-MIB";
//$config['os'][$os]['mibs'][]                = "EATON-EPDU-MA-MIB"; see in models

// Eaton acquired MGE in 2007 year

$os = "mgeups";
$config['os'][$os]['text']                  = "Eaton (MGE) UPS";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['icon']                  = "mge";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.705.1";
$config['os'][$os]['sysDescr'][]            = "/^MGE UPS/";
$config['os'][$os]['mibs'][]                = "MG-SNMP-UPS-MIB"; // before XUPS-MIB!
$config['os'][$os]['mibs'][]                = "XUPS-MIB";

$os = "mgepdu";
$config['os'][$os]['text']                  = "Eaton (MGE) PDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['icon']                  = "mge";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.705.2";

// Enlogic

$os = "enlogic-pdu";
$config['os'][$os]['text']                  = "Enlogic PDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['vendor']                = "Enlogic";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.38446.1";
$config['os'][$os]['mibs'][]                = "ENLOGIC-PDU-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

// Delta

$os = "deltaups";
$config['os'][$os]['text']                  = "Delta UPS";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['icon']                  = "delta";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_frequency";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2254.2.4";
$config['os'][$os]['mibs'][]                = "DeltaUPS-MIB";

// Janitza Electronics

$os = "janitza";
$config['os'][$os]['vendor']                = "Janitza";
$config['os'][$os]['text']                  = "Janitza Electronics";
$config['os'][$os]['type']                  = "power";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.2.1.1.2.0";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.34278.10.1";
$config['os'][$os]['sysDescr'][]            = "/^Janitza/";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['mibs'][]                = "JANITZA-MIB";

// Liebert / Emerson

$os = "liebert";
$config['os'][$os]['vendor']                = "Emerson";
$config['os'][$os]['text']                  = "Liebert OS";
$config['os'][$os]['group']                 = "ups"; // Note, really this is multi-type os (power/environment/network)
$config['os'][$os]['ifType_ifDescr']        = TRUE;
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.476.1.42";
$config['os'][$os]['mibs'][]                = "UPS-MIB";
$config['os'][$os]['mibs'][]                = "LIEBERT-GP-AGENT-MIB";
//$config['os'][$os]['mibs'][]                = "LIEBERT-GP-FLEXIBLE-MIB";
$config['os'][$os]['mibs'][]                = "LIEBERT-GP-POWER-MIB";
$config['os'][$os]['mibs'][]                = "LIEBERT-GP-PDU-MIB";
$config['os'][$os]['mibs'][]                = "LIEBERT-GP-ENVIRONMENTAL-MIB";

// Chloride / Oneac / 90NET / MasterGuard / EDP
$os = "manageups";
//$config['os'][$os]['vendor']                = "Chloride";
$config['os'][$os]['text']                  = "ManageUPS Adapter";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['icon']                  = "vertiv";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.2.1.33.250.10";
$config['os'][$os]['mibs'][]                = "UPS-MIB";
//$config['os'][$os]['mibs'][]                = "CHLORIDE-ENVIRONMENT-SENSOR-MIB";

$os = "avocent";
$config['os'][$os]['vendor']                = "Emerson";
$config['os'][$os]['text']                  = "Avocent";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['icon']                  = "avocent";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.10418.3.1.26";
$config['os'][$os]['sysDescr'][]            = "/^Avocent/";
$config['os'][$os]['sysDescr'][]            = "/^AlterPath/";
$config['os'][$os]['sysDescr'][]            = "/^DSR\ /";
//AlterPath ACS5032 - version: V_1.0.2 (Mar/19/10).
$config['os'][$os]['sysDescr_regex'][]      = 'AlterPath (?<hardware>\S+) - version: V_(?<version>[\d\.]+)/';

$os = "cyclades";
$config['os'][$os]['vendor']                = "Emerson";
$config['os'][$os]['text']                  = "Cyclades";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['sysDescr'][]            = "/^Cyclades/";
$config['os'][$os]['mibs'][]                = "ACS-MIB";

// Aten

$os = "aten";
$config['os'][$os]['text']                  = "Aten";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['vendor']                = "Aten";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['sysObjectID'][]         = '.1.3.6.1.4.1.21317';

$os = "aten-pdu";
$config['os'][$os]['text']                  = "Aten PDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['vendor']                = "Aten";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.21317',
  // ATEN-PE-CFG::modelName.0 = STRING: "PE8108G"
  'ATEN-PE-CFG::modelName.0'                => '/.+/', // non empty string
);
$config['os'][$os]['mibs'][]                = "ATEN-PE-CFG";

// Rittal

$os_group = "rittalcmc3";
$config['os_group'][$os_group]['vendor']            = "Rittal";
//Rittal CMC III PU SN 40341455 HW V3.00 - SW V3.13.00_2
$config['os_group'][$os_group]['sysDescr_regex'][]  = '/Rittal (?<hardware>.+?) SN (?<serial>\w+) HW V.+? - SW V(?<version>[\d\.\-_]+)/';
$config['os_group'][$os_group]['mibs'][]            = "RITTAL-CMC-III-MIB";
$config['os_group'][$os_group]['mib_blacklist'][]   = "ENTITY-SENSOR-MIB";

// FIXME combinate 'rittalcmc3_lcp' and 'rittalcmc3_pu' to 'rittalcmc3' (and set hardware in os)
$os = "rittalcmc3_lcp";
$config['os'][$os]['group']                 = "rittalcmc3";
$config['os'][$os]['text']                  = "Rittal CMC-III-LCP"; // LCP == Liquid Cooling Package
$config['os'][$os]['sysDescr'][]            = "!Rittal LCP!";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['graphs'][]              = "device_waterflow";

$os = "rittalcmc3_pu";
$config['os'][$os]['group']                 = "rittalcmc3";
$config['os'][$os]['text']                  = "Rittal CMC-III-PU"; // PU == Processing Unit
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['sysDescr'][]            = "!CMC-III-PU!";
$config['os'][$os]['sysDescr'][]            = "/^Rittal CMC III PU/";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_frequency";

$os_group = "rittalcmc";
$config['os_group'][$os_group]['vendor']    = "Rittal";
//Rittal CMC III PU SN 40341455 HW V3.00 - SW V3.13.00_2
$config['os_group'][$os_group]['sysDescr_regex'][]  = '/Rittal (?<hardware>.+?) SN (?<serial>\w+) HW V.+? - SW V(?<version>[\d\.\-_]+)/';
$config['os_group'][$os_group]['mibs'][]    = "HOST-RESOURCES-MIB";
$config['os_group'][$os_group]['mibs'][]    = "UCD-SNMP-MIB";
$config['os_group'][$os_group]['mibs'][]    = "RITTAL-CMC-TC-MIB";

// FIXME combinate 'rittalcmc_lcp' and 'rittalcmc_pu' to 'rittalcmc' (and set hardware in os)
$os = "rittalcmc_pu";
$config['os'][$os]['group']                 = "rittalcmc";
$config['os'][$os]['text']                  = "Rittal CMC-PU"; // PU == Processing Unit
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['sysDescr'][]            = "!CMC-TC/PU2!";
$config['os'][$os]['sysDescr'][]            = "!Rittal CMC Ser!";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_frequency";

$os = "rittalcmc_lcp";
$config['os'][$os]['group']                 = "rittalcmc";
$config['os'][$os]['text']                  = "Rittal CMC-LCP"; // LCP == Liquid Cooling Package
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['sysDescr'][]            = "!CMC-TC/LCP!";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_fanspeed";
$config['os'][$os]['graphs'][]              = "device_waterflow";

// Teracom
$os = "teracom";
$config['os'][$os]['vendor']                = "Teracom";
$config['os'][$os]['text']                  = "Teracom TCW";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.38783";
$config['os'][$os]['mibs'][]                = "TERACOM-MIB";
$config['os'][$os]['snmp']['max-rep']       = 100;

// Engenius

$os = "engenius";
$config['os'][$os]['text']                  = "EnGenius Wireless";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "engenius";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_wifi_clients";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14125.100.1.3";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14125.101.1.3";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.14125',
  'sysDescr'                                => '/^Wireless/',
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Wireless/',
  // ENGENIUS-PRIVATE-MIB::wirelessMacAddress.0 = STRING: 00:02:6F:EB:8B:5A
  'ENGENIUS-PRIVATE-MIB::wirelessMacAddress.0' => '/.+/', // non empty output
);
$config['os'][$os]['mibs'][]                = "SENAO-ENTERPRISE-INDOOR-AP-CB-MIB";
$config['os'][$os]['mibs'][]                = "ENGENIUS-PRIVATE-MIB";
$config['os'][$os]['mibs'][]                = "ENGENIUS-MESH-MIB";

// Engenius Switch

$os = "engenius-switch";
$config['os'][$os]['text']                  = "EnGenius Managed Switch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "engenius";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14125.3.2.10";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.44194.3.2.10";
$config['os'][$os]['mibs'][]                = "ENGENIUS-PRIVATE-MIB";

// Apple

$os = "airport";
$config['os'][$os]['text']                  = "Apple AirPort";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "apple";
$config['os'][$os]['sysDescr'][]            = "/^Apple AirPort/";
$config['os'][$os]['sysDescr'][]            = "/^(Apple )?Base Station V[\d\.]+ Compatible/";
$config['os'][$os]['mibs'][]                = "AIRPORT-BASESTATION-3-MIB";

// Microsoft

$os = "windows";
$config['os'][$os]['text']                  = "Microsoft Windows";
$config['os'][$os]['icons'][]               = "windows";
$config['os'][$os]['icons'][]               = "windows_old";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['processor_stacked']     = 1;
$config['os'][$os]['uptime_max']            = array('hrSystemUptime' => 4294967); // 49 days 17 hours 2 minutes 47 seconds, counter 2^32 (4294967296) divided by 1000
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['graphs'][]              = "device_storage";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.311.1.1.3";
$config['os'][$os]['sysDescr'][]            = "/(?<!\()Windows/";      // Excluded entries: 'EFI Fiery Controller (Windows-based)'
$config['os'][$os]['mibs'][]                = "MIB-Dell-10892";        // Dell OpenManage agent MIB
$config['os'][$os]['mibs'][]                = "CPQSINFO-MIB";          // HP/Compaq agent MIB
$config['os'][$os]['mibs'][]                = "CPQHLTH-MIB";           // HP/Compaq agent MIB
$config['os'][$os]['mibs'][]                = "CPQIDA-MIB";            // HP/Compaq agent MIB (RAID)
$config['os'][$os]['mibs'][]                = "SUPERMICRO-HEALTH-MIB"; // Supermicro agent MIB
$config['os'][$os]['mibs'][]                = "LSI-MegaRAID-SAS-MIB";  // LSI/Intel/... agent MIB
#$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";
$config['os'][$os]['ipmi']                  = TRUE;

// IBM

$os = "ibmnos";
$config['os'][$os]['text']                  = "IBM NOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "IBM";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.26543.1.7.4"; // G8124-E
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.26543.1.7.6"; // G8264
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.26543.1.7.7"; // G8052
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.26543.1.18.11";
$config['os'][$os]['sysDescr'][]            = "/^IBM Networking Operating System/";
$config['os'][$os]['sysDescr'][]            = "/^IBM Networking OS/";
$config['os'][$os]['sysDescr'][]            = "/Blade Network Technologies/"; // Old bnt
$config['os'][$os]['sysDescr'][]            = "/^BNT /";

$os = "ibm-svc";
$config['os'][$os]['text']                  = "IBM SAN Volume Controller";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['vendor']                = "IBM";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2.6.4"; // ibm2145TSVEObjects


$os = "ibm-tape";
$config['os'][$os]['text']                  = "IBM Tape Library";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['vendor']                = "IBM";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2.6.182.1.0.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2.6.219.1";
$config['os'][$os]['sysDescr'][]            = "/^IBM .+ Tape Library/";
$config['os'][$os]['mibs'][]                = "SNIA-SML-MIB";

$os = "ibm-flexswitch";
$config['os'][$os]['text']                  = "IBM (Lenovo) Flex Switch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "IBM"; // old Lenovo
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.20301.1.18";
// IBM Flex System Fabric EN4093 10Gb Scalable Switch(Upgrade1)
// Lenovo Flex System Fabric EN4093R 10Gb Scalable Switch
$config['os'][$os]['sysDescr'][]            = "/^(IBM|Lenovo) Flex .+?Switch/";

$os = "ibm-imm";
$config['os'][$os]['text']                  = "Lenovo IMM";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['vendor']                = "Lenovo"; // old Lenovo
$config['os'][$os]['sysDescr'][]            = "/^Linux \S+\-imm \d/";
$config['os'][$os]['ipmi']                  = TRUE;
$config['os'][$os]['sysObjectID'][]          = ".1.3.6.1.4.1.2.3.51.3";

$os = "ibm-infoprint";
$config['os'][$os]['text']                  = "IBM Infoprint";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['icon']                  = "ibm";
$config['os'][$os]['sysDescr'][]            = "/^(IBM )?Info[Pp]rint \d+/";

// NetAPP

$os = "netapp";
$config['os'][$os]['text']                  = "NetApp";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['icon']                  = "netapp";
$config['os'][$os]['sysDescr_regex'][]      = '/^NetApp Release (?<version>[\w\.]+)(?: [\w\-]+)?:/';
$config['os'][$os]['snmp']['max-rep']       = 50;
$config['os'][$os]['port_label'][]          = '/((?<port_label_base>.+?:(?:MGMT_PORT_ONLY )?[a-z]+)(?<port_label_num>\d[\w\-]*).*)/'; // vega-01:MGMT_PORT_ONLY e0M
$config['os'][$os]['graphs'][]              = "device_NETAPP-MIB_net_io";
$config['os'][$os]['graphs'][]              = "device_NETAPP-MIB_ops";
$config['os'][$os]['graphs'][]              = "device_NETAPP-MIB_disk_io";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.789.2.";
$config['os'][$os]['mibs'][]                = "NETAPP-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

// DDN
$os = "ddn";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['icon']                  = "ddn";
$config['os'][$os]['text']                  = "DataDirect Networks";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux/',
  'SFA-INFO::systemName.0'                  => '/.+/', // non empty
);
$config['os'][$os]['mibs'][]                = "SFA-INFO";
//$config['os'][$os]['mibs'][]                = "GPFS-MIB";
//$config['os'][$os]['mibs'][]                = "DDN-WOD-MIB";

// Arris

$os = "arris-d5";
$config['os'][$os]['text']                  = "Arris D5";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['icon']                  = "arris";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4115.1.8.1";

$os = "arris-c3";
$config['os'][$os]['text']                  = "Arris C3";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "arris";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4115.1.4.3";

$os = "arris-e6000";  // This may just be C4
$config['os'][$os]['text']                  = "Arris E6000";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "arris";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4115.1.9.1";

// HP / 3Com

$os = "procurve";
$config['os'][$os]['text']                  = "HPE ProCurve";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.2.3.7.5.";   // ProCurve Hub
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.2.3.7.8.";   // Switch Stack
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.2.3.7.11.";  // ProCurve Switch
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.2.14.11.7";  // ProCurve Secure Router
$config['os'][$os]['sysDescr'][]            = "/^(HPE |HP )?ProCurve (?!AP|Access Point)/"; // Fallback for unknown sysObjectID (APs excludes)
$config['os'][$os]['mibs'][]                = "STATISTICS-MIB";
$config['os'][$os]['mibs'][]                = "NETSWITCH-MIB";
$config['os'][$os]['mibs'][]                = "HP-ICF-CHASSIS";
$config['os'][$os]['mibs'][]                = "SEMI-MIB";
$config['os'][$os]['mibs'][]                = "SMON-MIB";
$config['os'][$os]['mibs'][]                = "POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "HP-ICF-POE-MIB";

$os = "procurve-ap";
$config['os'][$os]['text']                  = "HPE ProCurve Access Point";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.2.14.11.6";  // ProCurve Access Point
#$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11898.2.4.6";   // ProCurve AP. WARNING, this is multibranded sysObjectID!
$config['os'][$os]['sysDescr'][]            = "/^(HPE |HP )?ProCurve (AP|Access Point)/";

$os = "hpuww";
$config['os'][$os]['text']                  = "HPE Unified Wired-WLAN Appliance";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['graphs'][]              = "device_wifi_clients";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['ports_separate_walk']   = TRUE;
//HP Comware Platform Software HP 870. Product Version Release 2607P46 Copyright (c) 2010-2015 Hewlett-Packard Development Company, L.P.
$config['os'][$os]['sysDescr_regex'][]      = '/Platform Software (?<hardware>.*?). Product Version Release (?<version>\d\w+)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.2.3.7.15";
$config['os'][$os]['mibs'][]                = "ENTITY-MIB";
$config['os'][$os]['mibs'][]                = "HPN-ICF-ENTITY-EXT-MIB";
$config['os'][$os]['mibs'][]                = "HPN-ICF-ENTITY-VENDORTYPE-OID-MIB";
$config['os'][$os]['mibs'][]                = "HPN-ICF-DOT11-ACMT-MIB";

$os = "hpvc";
$config['os'][$os]['text']                  = "HPE Virtual Connect";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "hpe";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.5.7.5";       // Virtual Connect
//HP VC Flex-10 Enet Module Virtual Connect 3.70
//HP VC FlexFabric 10Gb/24-Port Module Virtual Connect 3.60
//HP VC FlexFabric 10Gb/24-Port Module 4.45 2015-07-20T23:55:25Z
//HP VC Flex-10/10D Module 4.45 2015-07-21T00:14:47Z
//HP VC Flex-10 Enet Module 4.45 2015-07-20T23:29:00Z
//HP 1/10Gb-F VC-Enet Module Virtual Connect 3.60
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>.* Module)[A-z\ ]+(?<version>\d[\d\.]+)/';
$config['os'][$os]['mibs'][]                = "HPVC-MIB";

$os = "hp-gbe2c";
$config['os'][$os]['text']                  = "HPE GbE2c";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.2.3.7.11.33"; // Ethernet Blade Switch for HP c-Class BladeSystem

$os = "hpux";
$config['os'][$os]['text']                  = "HP-UX";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.2.3.2";

$os = "hp-proliant";
$config['os'][$os]['text']                  = "HPE ProLiant";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.232.22";
$config['os'][$os]['mibs'][]                = "CPQSINFO-MIB";
$config['os'][$os]['mibs'][]                = "CPQRACK-MIB";

$os = "hpstorage";
$config['os'][$os]['text']                  = "HPE StorageWorks";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.10";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.2.51";
//HP P2000 G3 FC
//HP MSL G3 Series
//HP StorageWorks MSA2012sa
//HP StorageWorks P2000G3 FC/iSCSI
//HP StorageWorks D2D Backup System CZJ23203XD HP-CZJ23203XD EH998B
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+)$/';
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "UCD-SNMP-MIB";

$os = "hpmsm";
$config['os'][$os]['text']                  = "HPE Colubris";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8744.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8744.1.41"; // Lots of different stuff in .1, not sure which shares MIBs
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8744.1.42";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8744.1.43";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8744.1.44";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8744.1.45";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8744.1.46";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8744.1.47";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8744.1.48";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.8744.1.49";
//E-MSM720 - Hardware revision J9694-60101 - Serial number CN3AF2F409 - Firmware version 5.7.1.1-12533
//MSM760 - Hardware revision B - Serial number SG4153P3RL - Firmware version 5.7.3.0-sr2-13986
//MSM760 - Hardware revision B - Serial number SG3263P142 - Firmware version 5.7.4.0.fcc.june2014.1-17273
//MSM760 - Hardware revision Greyhound - Serial number SG5383P038 - Firmware version 6.3.0.2-19149
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>\S+) \- Hardware revision .+ \- Serial number (?<serial>\S+) \- Firmware version (?<version>\d+(?:\.\d+)+(?:\-[a-z]+\d+)?)/';
$config['os'][$os]['mibs'][]                = "COLUBRIS-USAGE-INFORMATION-MIB";
$config['os'][$os]['mibs'][]                = "COLUBRIS-SYSTEM-MIB";

$os = "hpilo";
$config['os'][$os]['text']                  = "HPE iLO Management";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['icon']                  = "hpe";
//$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.5.7.3.2";  // iLO Management Processor
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.232.9.4.10";  // iLO 4
$config['os'][$os]['sysDescr'][]            = "/^Integrated Lights\-Out \d/";
$config['os'][$os]['mibs'][]                = "CPQSINFO-MIB";
$config['os'][$os]['mibs'][]                = "CPQHLTH-MIB";
$config['os'][$os]['mibs'][]                = "CPQIDA-MIB";
$config['os'][$os]['ipmi']                  = TRUE;

$os = "hpoa";
$config['os'][$os]['text']                  = "HPE Onboard Administrator";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.5.7.1.2";  // Onboard Administrator
$config['os'][$os]['mibs'][]                = "CPQSINFO-MIB";
$config['os'][$os]['mibs'][]                = "CPQHLTH-MIB";
$config['os'][$os]['mibs'][]                = "CPQIDA-MIB";
$config['os'][$os]['mibs'][]                = "CPQRACK-MIB";
$config['os'][$os]['mibs'][]                = "HOST-RESOURCES-MIB";
$config['os'][$os]['ipmi']                  = TRUE;

// HP / Compaq UPS

$os = "hpups";
$config['os'][$os]['text']                  = "HPE UPS";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.232.165.3";
//HP UPS Network Module, revision BD06, firmware revision 1.05.001
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^HP .*UPS/',
  'sysObjectID'                             => '.1.3.6.1.4.1.232.165',
);
$config['os'][$os]['mibs'][]                = "CPQPOWER-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "hppdu";
$config['os'][$os]['text']                  = "HP PDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.232.165.5";
//HP Intelligent Modular PDU , revision W-J, firmware version 2.0.22
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^HP .*PDU/',
  'sysObjectID'                             => '.1.3.6.1.4.1.232.165',
);
$config['os'][$os]['mibs'][]                = "CPQPOWER-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "3com";
$config['os'][$os]['text']                  = "3Com OS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "3com";
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.43";

$os = "h3c";
$config['os'][$os]['text']                  = "H3C Comware";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "h3c";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2011.10"; // Not correct, this is Huawei VRP
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25506.1.";
$config['os'][$os]['mibs'][]                = "HH3C-ENTITY-EXT-MIB";
$config['os'][$os]['mibs'][]                = "HH3C-ENTITY-VENDORTYPE-OID-MIB"; // Inventory module
$config['os'][$os]['mibs'][]                = "HH3C-TRANSCEIVER-INFO-MIB";
$config['os'][$os]['mibs'][]                = "HH3C-NQA-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "hh3c";
$config['os'][$os]['text']                  = "HPE Comware";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "hpe";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25506";
$config['os'][$os]['mibs'][]                = "HH3C-ENTITY-EXT-MIB";
$config['os'][$os]['mibs'][]                = "HH3C-ENTITY-VENDORTYPE-OID-MIB"; // Inventory module
$config['os'][$os]['mibs'][]                = "HH3C-TRANSCEIVER-INFO-MIB";
$config['os'][$os]['mibs'][]                = "POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "HH3C-POWER-ETH-EXT-MIB";
$config['os'][$os]['mibs'][]                = "HH3C-NQA-MIB";
$config['os'][$os]['mibs'][]                = "HH3C-STACK-MIB";

// Thomson

$os = "speedtouch";
$config['os'][$os]['text']                  = "Thomson Speedtouch";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+)$/';
$config['os'][$os]['sysDescr'][]            = "/TG585v7/";
$config['os'][$os]['sysDescr'][]            = "/SpeedTouch /";
$config['os'][$os]['sysDescr'][]            = "/^ST\d/";

// ZyXEL

$os = "zyxeles";
$config['os'][$os]['text']                  = "ZyXEL Switch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "ZyXEL";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.890.1.5";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => array('/^[MX]?(?:S|GS|ES|SC)[^A-Z]/',
                                                     '/IES|[AS]AM/',
                                                     '/^$/'), // empty string
  'sysObjectID'                             => '.1.3.6.1.4.1.890.1.',
);
$config['os'][$os]['mibs'][]                = "ZYXEL-AS-MIB";

$os = "zywall";
$config['os'][$os]['text']                  = "ZyXEL ZyWALL";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['vendor']                = "ZyXEL";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.890.1.6";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/ZyWALL|U[AS]G|ISG/i',
  'sysObjectID'                             => '.1.3.6.1.4.1.890.1.',
);
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+)$/';

$os = "prestige";
$config['os'][$os]['text']                  = "ZyXEL Prestige";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "ZyXEL";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.890.2.1.6";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^(?:TRUE_)?P/',
  'sysObjectID'                             => '.1.3.6.1.4.1.890.',
);

$os = "zyxelnwa";
$config['os'][$os]['text']                  = "ZyXEL NWA";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['vendor']                = "ZyXEL";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.890.1.9.100";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.890.1.9.101";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/NWA|NXC/i',
  'sysObjectID'                             => '.1.3.6.1.4.1.890.1.',
);
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+)$/';

// q: Why this os named ies?
// a: No one know.. because in r1382 Tom added it as "ZyXEL IES DSLAM",
//    but this not IES os, this is all small ZyXEL routers including DSL devices
$os = "ies";
$config['os'][$os]['text']                  = "ZyXEL Router";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "ZyXEL";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.890.1."; // Detect all other of this huge list as ies

// Allied Telesis

$os = "allied";
$config['os'][$os]['text']                  = "AlliedWare";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['icon']                  = "alliedtelesis";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_fdb_count";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.207";
//Allied Telesis AT-8624T/2M version 2.9.1-13 11-Dec-2007
//Allied Telesis AT-9924T-EMC version 2.9.2-08 02-Aug-2012
//Allied Telesyn AT-8948 version 2.7.4-02 22-Aug-2005
//Allied Telesyn AT-RP24i Rapier 24i version 2.6.1-04 09-Dec-2003
$config['os'][$os]['sysDescr_regex'][]      = '/Allied Teles(?:is|yn) (?<hardware>\S+).* version (?<version>[\d\.\-]+)/';
//Allied Telesyn AT-9424T/SP - ATS63 v2.0.0 P_03
//Allied Telesis AT-9424T - ATS63 v4.1.0
$config['os'][$os]['sysDescr_regex'][]      = '/Allied Teles(?:is|yn) (?<hardware>\S+) - (?<features>.+) v(?<version>[\d\.\-]+)/';
//AT-8126XL, AT-S21 version 1.4.2
//AT-TQ2403 version 2.1.5, Wed May 6 00:26:25 CST 2009
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>AT-\S+)(?:, (?<features>\S+))? version (?<version>[\d\.\-]+)/';
//iMG634A - Hw: H - Sw: 3-7_150 Copyright (c) 2005 by Allied Telesis K.K.
//iMG624A-R2 - Hw: V1.1A - Sw: 3-8-03_14 Copyright (c) 2011 by Allied Telesis K.K.
//iMG616SRF+ - Hw: F - Sw: 3-5_83_03_113 Copyright (c) 2005 by Allied Telesis K.K.
//RG634A - Hw: 2A - Sw: 3-5_78 Copyright (c) 2005 by Allied Telesis K.K.
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>\S+) - Hw: \S+ - Sw: (?<version>[\d\-\._]+)/';
//Allied Telesyn Ethernet Switch AT-8012M
//Allied Telesyn Ethernet Switch AT-8024
$config['os'][$os]['sysDescr_regex'][]      = '/Allied Teles(?:is|yn) .+ Switch (?<hardware>\S+)/';
//ATI AT-8000S
//AT-8326GB
//AT-AR250E ADSL ROUTER
//AT-GS950/24 Gigabit Ethernet WebSmart Switch
$config['os'][$os]['sysDescr_regex'][]      = '/AT\-(?<hardware>\S+)/';
$config['os'][$os]['mibs'][]                = "AT-SYSINFO-MIB";

$os = "alliedwareplus";
$config['os'][$os]['text']                  = "AlliedWare Plus";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['icon']                  = "alliedtelesis";
//Allied Telesis router/switch, AW+ v5.3.4-0.2
//Allied Telesis router/switch, AW+ v5.2.2-0.11
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/AW\+/',
  'sysObjectID'                             => '.1.3.6.1.4.1.207.',
);
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>AW\+) v(?<version>[\d\.\-]+)/';
$config['os'][$os]['mibs'][]                = "AT-SYSINFO-MIB";

// This is only to be used for RADLAN-based PowerConnects

$os = "allied-radlan";
$config['os'][$os]['text']                  = "Allied Telesis (RADLAN)";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "radlan";
$config['os'][$os]['icon']                  = "alliedtelesis";
$config['os'][$os]['ports_separate_walk']   = 1; // Force use separate ports polling feature
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.207.1.4.125"; // ATI 8000S
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.207.1.4.126"; // ATI AT-8000S
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.207.1.4.129"; // ATI AT-8000S (also?)
//$config['os'][$os]['sysDescr'][]            = "/ATI (AT\-)?8000/"; // Already detected by sysObjectID

$os = "actelis";
$config['os'][$os]['text']                  = "Actelis";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5468.1";

$os = "microsens";
$config['os'][$os]['text']                  = "Microsens";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3181.10.3"; // Generation 5
$config['os'][$os]['mibs'][]                = "MS-SWITCH30-MIB";

$os = "microsens-g6";
$config['os'][$os]['text']                  = "Microsens G6";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3181.10.6"; // Generation 6
$config['os'][$os]['mibs'][]                = "G6-SYSTEM-MIB";
$config['os'][$os]['mibs'][]                = "G6-FACTORY-MIB";
$config['os'][$os]['icon']                  = "microsens";

// APC / Schneider Electric

$os = "apc";
$config['os'][$os]['text']                  = "APC OS";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.318";
$config['os'][$os]['remote_access']         = array('http');
$config['os'][$os]['uptime_max']            = array('sysUpTime' => 4294967); // 49 days 17 hours 2 minutes 47 seconds, counter 2^32 (4294967296) divided by 1000
$config['os'][$os]['mibs'][]                = "PowerNet-MIB";
$config['os'][$os]['mib_blacklist'][]       = "BGP4-MIB";
$config['os'][$os]['mib_blacklist'][]       = "OSPF-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "netbotz";
$config['os'][$os]['text']                  = "APC Netbotz";
$config['os'][$os]['vendor']                = "APC";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['group']                 = "apc"; // for os/version poller
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
$config['os'][$os]['remote_access']         = array('http');
$config['os'][$os]['uptime_max']            = array('sysUpTime' => 4294967); // 49 days 17 hours 2 minutes 47 seconds, counter 2^32 (4294967296) divided by 1000
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5528";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.318.1.3.8";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/NetBotz|Environmental/',
  'sysObjectID'                             => '.1.3.6.1.4.1.318.1.3.',
);
//$config['os'][$os]['mibs'][]                = "PowerNet-MIB";
$config['os'][$os]['mibs'][]                = "NETBOTZV2-MIB";
$config['os'][$os]['mib_blacklist'][]       = "BGP4-MIB";
$config['os'][$os]['mib_blacklist'][]       = "OSPF-MIB";
//$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "apc-kvm";
$config['os'][$os]['text']                  = "APC IP-KVM";
$config['os'][$os]['vendor']                = "APC";
$config['os'][$os]['type']                  = "management";
//$config['os'][$os]['graphs'][]              = "device_current";
//$config['os'][$os]['graphs'][]              = "device_voltage";
//$config['os'][$os]['graphs'][]              = "device_power";
//$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.318.1.3.19";

$os = "apc-isx";
$config['os'][$os]['text']                  = "APC InfraStruXure"; // RH based
$config['os'][$os]['vendor']                = "APC";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['snmp']['max-rep']       = 100;
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['graphs'][]              = "device_storage";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['mibs'][]                = "LM-SENSORS-MIB";
$config['os'][$os]['realtime']              = 15;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.318.1.3.25"; // ISX
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.318.1.3.29"; // StruxureWare

$os = "racktivity";
$config['os'][$os]['text']                  = "Racktivity EnergySwitch";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['graphs'][]              = "device_temperature";
//Racktivity AC2Meter.
$config['os'][$os]['sysDescr_regex'][]      = '/Racktivity (?<hardware>[\w\ ]+)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.34097.9";
$config['os'][$os]['mibs'][]                = "ES-RACKTIVITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";

$os = "interseptor";
$config['os'][$os]['text']                  = "Jacarta InterSeptor";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['icon']                  = "jacarta";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.19011";
$config['os'][$os]['mibs'][]                = "ISPRO-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";

$os = "oec";
$config['os'][$os]['text']                  = "OEC PDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['graphs'][]              = "device_uptime";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.29640.1.2.4";
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+)$/';
$config['os'][$os]['mibs'][]                = "APNL-MODULAR-PDU-MIB";

$os = "pcoweb-crac";
$config['os'][$os]['text']                  = "Carel pCOWeb (CRAC unit)";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['discovery_os']          = "linux";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
$config['os'][$os]['icon']                  = "carel";
$config['os'][$os]['icons'][]               = "uniflair";
/*
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux/',
  // READYNAS-MIB::nasMgrSoftwareVersion.0 = STRING: "5.3.12"
  'READYNAS-MIB::nasMgrSoftwareVersion.0'   => '/.+/', // non empty
);
*/
$config['os'][$os]['mibs'][]                = "CAREL-ug40cdz-MIB";

$os = "pcoweb-chiller";
$config['os'][$os]['text']                  = "Carel pCOWeb (Chiller unit)";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['discovery_os']          = "linux";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
$config['os'][$os]['icon']                  = "carel";
$config['os'][$os]['icons'][]               = "uniflair";
$config['os'][$os]['mibs'][]                = "UNCDZ-MIB";

// C&C Power

$os = "ccpower";
$config['os'][$os]['text']                  = "C&C Power";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['icon']                  = "ccpower";
$config['os'][$os]['mibs'][]                = "CCPOWER-MIB";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.18642.1";
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>[\w ]+) - Software Version (?<version>\d[\w\.]+)/';
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "LLDP-MIB";
$config['os'][$os]['mib_blacklist'][]       = "CISCO-CDP-MIB";
$config['os'][$os]['mib_blacklist'][]       = "Q-BRIDGE-MIB";
$config['os'][$os]['mib_blacklist'][]       = "PW-STD-MIB";

// SAF Tehnika (http://www.saftehnika.com)

$os = "saf-ipradio";
$config['os'][$os]['text']                  = "SAF Radio";
$config['os'][$os]['type']                  = "radio";
$config['os'][$os]['icon']                  = "saf";
//SAF microwave radio;CFIP Lumina FODU v2.64.33;Model:2;HW:15;SN: 3690205xxxxx;PC: I11HJT05HA;IDU PCB: I0BMDB05_R07
$config['os'][$os]['sysDescr_regex'][]      = '/;(?<hardware>.+?) v(?<version>\d[\d\.]+);Model.+?;SN: *(?<serial>\w+)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.7571.100.1.1.5.1";
$config['os'][$os]['mibs'][]                = "SAF-ALARM-MIB";
$config['os'][$os]['mibs'][]                = "SAF-ENTERPRISE";
$config['os'][$os]['mibs'][]                = "SAF-IPRADIO";
$config['os'][$os]['mibs'][]                = "SAF-IPADDONS";

// Socomec

$os = "netvision";
$config['os'][$os]['text']                  = "Socomec Net Vision";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['sysDescr'][]            = "/^Net Vision/";
$config['os'][$os]['mibs'][]                = "SOCOMECUPS-MIB";

/* other Socomec products, never tested and not supported:
$os = "pduvision";
$config['os'][$os]['text']                  = "Socomec PDU Vision";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['sysDescr'][]            = "/^PDU Vision/";
$config['os'][$os]['mibs'][]                = "SOCOMECPDU-MIB";

$os = "ipdu";
$config['os'][$os]['text']                  = "Socomec iPDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['sysDescr'][]            = "/^iPDU/";
$config['os'][$os]['mibs'][]                = "SOCOMECUPS-MIB-v2"; // This old MIB version, not compatible with SOCOMECUPS-MIB
*/

// Baytech

$os = "baytech-pdu";
$config['os'][$os]['text']                  = "Baytech PDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['vendor']                = "Baytech";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4779";
$config['os'][$os]['mibs'][]                = "Baytech-MIB-403-1";
//$config['os'][$os]['mibs'][]                = "Baytech-MIB-401-4";

$os = "areca";
$config['os'][$os]['text']                  = "Areca RAID Subsystem";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.18928.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5257.1";
$config['os'][$os]['remote_access']         = array('telnet', 'http'); // Unfortunately non-standard ports are not reported via SNMP :(
$config['os'][$os]['mibs'][]                = "ARECA-SNMP-MIB";

$os = "netmanplus";
$config['os'][$os]['text']                  = "NetMan Plus";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['mibs'][]                = "UPS-MIB";
$config['os'][$os]['mib_blacklist'][]       = "BGP4-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5491.6";

$os = "generex-ups";
$config['os'][$os]['text']                  = "Generex UPS Adapter";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['icon']                  = "generex";
$config['os'][$os]['sysDescr'][]            = "/^CS1\d1 v/";            // CS121 v 5.14.97
$config['os'][$os]['sysDescr'][]            = "/C[Ss]1\d1 SNMP Agent/"; // The Cs141 SNMP Agent
$config['os'][$os]['sysDescr_regex'][]      = '/CS1\d1 v *(?<version>\d[\d\.]+)/';
$config['os'][$os]['mibs'][]                = "UPS-MIB";

$os = "sensorgateway";
$config['os'][$os]['text']                  = "ServerRoom Sensor Gateway";
$config['os'][$os]['group']                 = "environment";
$config['os'][$os]['icon']                  = "serverscheck";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
$config['os'][$os]['sysDescr'][]            = "/^Temperature & Sensor Gateway/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.17095";
$config['os'][$os]['mibs'][]                = "ServersCheck";

$os = "sensorprobe";
$config['os'][$os]['text']                  = "AKCP SensorProbe";
$config['os'][$os]['group']                 = "sensorprobe";
$config['os'][$os]['icon']                  = "akcp";
$config['os'][$os]['sysDescr'][]            = "/SensorProbe/i";

$os = "securityprobe";
$config['os'][$os]['text']                  = "AKCP securityProbe";
$config['os'][$os]['group']                 = "sensorprobe";
$config['os'][$os]['icon']                  = "akcp";
$config['os'][$os]['sysDescr'][]            = "/securityProbe/i";

$os = "servsensor";
$config['os'][$os]['text']                  = "BlackBox ServSensor";
$config['os'][$os]['group']                 = "sensorprobe"; // AKCP SensorProbe clone
$config['os'][$os]['icon']                  = "blackbox";
$config['os'][$os]['sysDescr'][]            = "/ServSensor/";

$os = "minkelsrms";
$config['os'][$os]['text']                  = "Minkels RMS";
$config['os'][$os]['group']                 = "sensorprobe"; // AKCP SensorProbe clone
$config['os'][$os]['sysDescr'][]            = "/8VD-X20/";

$os = "roomalert";
$config['os'][$os]['text']                  = "AVTECH RoomAlert";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['icon']                  = "avtech";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
$config['os'][$os]['sysDescr'][]            = "/^Room ?Alert/";
//Room Alert 12E v3.0.2
$config['os'][$os]['sysDescr_regex'][]      = '/^Room ?Alert( .*)? v(?<version>\d[\d\.]+)/';
$config['os'][$os]['mibs'][]                = "ROOMALERT24E-MIB";
$config['os'][$os]['mibs'][]                = "ROOMALERT12E-MIB";
$config['os'][$os]['mibs'][]                = "ROOMALERT4E-MIB";

$os = "ipoman";
$config['os'][$os]['text']                  = "Ingrasys iPoMan";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['icon']                  = "ingrasys";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2468.1.4.2.1";
$config['os'][$os]['mibs'][]                = "IPOMANII-MIB";

$os = "wxgoos";
$config['os'][$os]['text']                  = "ITWatchDogs Goose";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.17373";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.901.1"; // FIXME, this is incorrect, copied from old os discovery, probably "DPI LAN Adapter"
$config['os'][$os]['mibs'][]                = "IT-WATCHDOGS-MIB-V3";
$config['os'][$os]['mibs'][]                = "IT-WATCHDOGS-V4-MIB";

$os = "papouch";
$config['os'][$os]['text']                  = "Papouch Probe";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['sysDescr'][]            = "/^(SNMP )?TME$/";
$config['os'][$os]['sysDescr'][]            = "/^TH2E$/";
$config['os'][$os]['sysDescr'][]            = "/^Papago_2TH_ETH$/";
// SNMP TME
// TME
// TH2E
$config['os'][$os]['sysDescr_regex'][]      = '/^(?:SNMP )?(?<hardware>T\w+)/';
// Papago_2TH_ETH
$config['os'][$os]['sysDescr_regex'][]      = '/^Papago_(?<hardware>\d*\w+)/';
$config['os'][$os]['mibs'][]                = "TMESNMP2-MIB";
$config['os'][$os]['mibs'][]                = "the_v01-MIB";
$config['os'][$os]['mibs'][]                = "papago_temp_V02-MIB";

$os = "cometsystem-p85xx";
$config['os'][$os]['text']                  = "Comet System P85xx";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['icon']                  = "comet";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/.+Firmware Version \d+-\d+-\d+.+/',
  '.1.3.6.1.4.1.22626.1.5.2.1.3.0'          => '/\d+/', // numeric
);
$config['os'][$os]['mibs'][]                = "P8510-MIB";

// Printers
//FIXME. Currently not detected printers (sysObjectID, sysDescr):
//.1.3.6.1.4.1.641.4.0 4900   Series version NET.CH.N208 kernel 2.6.12.5-88w8xx8 All-N-1
//.1.3.6.1.4.1.641.1.71106853 Laser Printer 66 version NR.APS.N310 kernel 2.6.18.5 All-N-1
//.1.3.6.1.4.1.641.2.71106878 Color Laser Printer 59-MFP version NR.APS.N434 kernel 2.6.18.5 All-N-1
//.1.3.6.1.4.1.641.1          FLP T630 version 55.10.19 kernel 2.4.0-test6 All-N-1
//.1.3.6.1.4.1.367.1.1        RFG SP 3300 Series OS 1.50.02.44 06-17-2009;Engine 1.01.25;NIC V4.01.03 06-02-2009;S/N S4099302659W
//.1.3.6.1.4.1.367.1.1        SAVIN C3030 1.62.1 / SAVIN Network Printer C model / SAVIN Network Scanner C model
//.1.3.6.1.4.1.367.1.1        LANIER MP 7001/LD370 1.20 / LANIER Network Printer C model / LANIER Network Scanner C model
//.1.3.6.1.4.1.367.1.1        infotec ISC 2020 1.68 / infotec Network Printer C model / infotec Network Scanner C model / infotec Network Facsimile C model
//.1.3.6.1.4.1.18334.1.2.1.2.1.69.4.1 Fiery PRO80 70-60C-KM
//.1.3.6.1.4.1.18334.1.2.1.2.1.58.1.2 Color MF30-1

$os = "dell-laser";
$config['os'][$os]['text']                  = "Dell Laser";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['icon']                  = "dell";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10898.";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10898.2.100";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10898.10.51";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.100";
$config['os'][$os]['sysDescr'][]            = "/^Dell (?:[\w ]+ )?Laser Printer/";
$config['os'][$os]['sysDescr'][]            = "/^Dell .*?MFP/";
$config['os'][$os]['sysDescr'][]            = "/^Dell +\d{4}\w+ (Series|Laser)/"; // 1815dn

// This is not real priter, but controller for other printers, used if printer not detected
$os = "efi-fiery";
$config['os'][$os]['text']                  = "EFI Print Controller";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['vendor']                = "EFI";
$config['os'][$os]['sysDescr'][]            = "/^(EFI )?Fiery/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2136.2";

$os = "ricoh";
$config['os'][$os]['text']                  = "Ricoh Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['icon']                  = "ricoh";
$config['os'][$os]['sysDescr'][]            = "/(RICOH|Gestetner) Network Printer/";
$config['os'][$os]['sysDescr'][]            = "/^RICOH( Pro|$)/";
//This sysObjectID intersected with many other printers, use sysDescr instead
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.367.1.1";

$os = "lexmark";
$config['os'][$os]['text']                  = "Lexmark Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['icon']                  = "lexmark";
$config['os'][$os]['sysDescr'][]            = "/^Lexmark /";
//This sysObjectID intersected with many other printers, use sysDescr instead
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.641.1";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.641.2";

$os = "lg-printer";
$config['os'][$os]['text']                  = "LG Printer";
$config['os'][$os]['vendor']                = "LG";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.38191.6.2.2";
$config['os'][$os]['sysDescr'][]            = "/^LG L/";

$os = "sindoh";
$config['os'][$os]['text']                  = "SINDOH Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['sysDescr'][]            = "/^SINDO(RICO)?H /";

$os = "nrg";
$config['os'][$os]['text']                  = "NRG Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['vendor']                = "NRG";
$config['os'][$os]['sysDescr'][]            = "/NRG Network Printer/";
//NRG MP C4500 1.60 / NRG Network Printer C model / NRG Network Scanner C model / NRG Network Facsimile C model
//NRG SP C410DN 1.01 / NRG Network Printer C model
//NRG MP 171 1.01 / NRG Network Printer C model / NRG Network Scanner C model / NRG Network Facsimile C model
$config['os'][$os]['sysDescr_regex'][]      = '!NRG (?<hardware>.+) (?<version>\d[\d\.]+) /!';

$os = "epson-printer";
$config['os'][$os]['text']                  = "Epson Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['vendor']                = "Epson";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1248.1.1.2";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1248.1.2.1";

$os = "xerox-printer";
$config['os'][$os]['text']                  = "Xerox Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['vendor']                = "Xerox";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysDescr_regex'][]      = '/Xerox Phaser .+; System Software (?<version>[\d\.]+),/i'; // Xerox Phaser 6700DN; System Software 081.140.103.22600, ESS 0.040.0.0
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.253.8.62.1.";

$os = "fuji-xerox-printer";
$config['os'][$os]['text']                  = "Fuji Xerox Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['icon']                  = "xerox";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.297.1.11.93.1.";

$os = "samsung-printer";
$config['os'][$os]['text']                  = "Samsung Printer";
$config['os'][$os]['vendor']                = "Samsung";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['sysDescr'][]            = "/^(?:Samsung )+[CMKSX][\w\-]+/";
$config['os'][$os]['sysDescr'][]            = "/^SAMSUNG NETWORK PRINTER/";
$config['os'][$os]['discovery'][]           = array(
  'Printer-MIB::prtGeneralServicePerson.1' => '/Samsung/',
);
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1129.2.3.15.2";

$os = "canon-printer";
$config['os'][$os]['text']                  = "Canon Printer";
$config['os'][$os]['vendor']                = "Canon";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1602.4";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.27748.4";

$os = "jetdirect";
$config['os'][$os]['text']                  = "HP Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['icon']                  = "hp";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.11.1"; // Sometime intersected with Samsung printers
$config['os'][$os]['sysDescr'][]            = "/^(HP ETHERNET|Type) .*?,JETDIRECT(,|$)/";
$config['os'][$os]['sysDescr'][]            = "/^(HP ETHERNET|Type) .*?,LaserJet(,|$)/";
$config['os'][$os]['sysDescr'][]            = "/^HP ETHERNET MULTI-ENVIRONMENT(?!, ROM J\.sp\.00)/"; // excluded: Kyocera
$config['os'][$os]['mibs'][]                = "HP-LASERJET-COMMON-MIB";

//.1.3.6.1.4.1.18334.1.2.1.2.1.106.2.4  Generic 28C-6e
//.1.3.6.1.4.1.18334.1.2.1.2.1.64.2.1   Generic 36BW-4
//.1.3.6.1.4.1.18334.1.2.1.2.1.48.2.1   Generic 45C-5
$os = "olivetti-printer";
$config['os'][$os]['text']                  = "Olivetti Printer";
$config['os'][$os]['vendor']                = "Olivetti";
$config['os'][$os]['group']                 = "printer";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.18334.1.2.1.2.1.";
$config['os'][$os]['sysDescr'][]            = "/^Generic \w+-\w+$/";

$os = "sharp-printer";
$config['os'][$os]['text']                  = "Sharp Printer";
$config['os'][$os]['vendor']                = "Sharp";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2385.3.1.";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3369.1.1.2.40";

$os = "okilan";
$config['os'][$os]['text']                  = "OKI Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['icon']                  = "oki";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2001.1";

$os = "brother-printer";
$config['os'][$os]['text']                  = "Brother Printer";
$config['os'][$os]['vendor']                = "Brother";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2435.2.3.9.1";
$config['os'][$os]['sysDescr'][]            = "/^Brother NC-/"; // Sometime sysObjectID empty

$os = "konica-printer";
$config['os'][$os]['text']                  = "Konica-Minolta Printer/Copier";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['icon']                  = "konica";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.18334.1.1.1"; // Intersected with Develop ineo and Sindoh
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2590.1.1.1.2.1";
$config['os'][$os]['sysDescr'][]            = "/^KONICA MINOLTA/i";
$config['os'][$os]['mib_blacklist'][]       = "Q-BRIDGE-MIB";

//.1.3.6.1.4.1.18334.1.2.1.2.1.57.2.1   Develop ineo+ 220
//.1.3.6.1.4.1.18334.1.2.1.2.1.64.3.1   Develop ineo 363
$os = "develop";
$config['os'][$os]['text']                  = "Develop Printer";
$config['os'][$os]['group']                 = "printer";
//$config['os'][$os]['icon']                  = "develop";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.18334.1.2.1.2.1.";
$config['os'][$os]['sysDescr'][]            = "/^Develop ineo/";

//.1.3.6.1.4.1.1347.43.5.1.1.1  KYOCERA Print System IB-110 Ver 1.2.0
//.1.3.6.1.4.1.1347.41          KYOCERA MITA Printing System
//.1.3.6.1.4.1.1347.41          KYOCERA Document Solutions Printing System
//.1.3.6.1.4.1.1347.41          HP ETHERNET MULTI-ENVIRONMENT, ROM J.sp.00, JETDIRECT EX and JD28 EEPROM 5.58
$os = "kyocera";
$config['os'][$os]['text']                  = "Kyocera Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['ifname']                = 1;
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1347.41"; // Intersected with other printers
$config['os'][$os]['sysDescr'][]            = "/^KYOCERA .*?Print/";
$config['os'][$os]['sysDescr'][]            = "/^HP ETHERNET MULTI-ENVIRONMENT, ROM J\.sp\.00/";
$config['os'][$os]['mibs'][]                = "KYOCERA-Private-MIB";

$os = "toshiba-printer";
$config['os'][$os]['text']                  = "Toshiba Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['vendor']                = "Toshiba";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1129.1.2";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1129.2.3."; // Intersected with other printers
$config['os'][$os]['sysDescr'][]            = "/^TOSHIBA (TEC|e\-STUDIO)/";

$os = "panasonic-printer";
$config['os'][$os]['text']                  = "Panasonic Printer";
$config['os'][$os]['group']                 = "printer";
$config['os'][$os]['icon']                  = "panasonic";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.258.406.2";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.258.406.3";

$os = "sentry3";
$config['os'][$os]['text']                  = "ServerTech Sentry3";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['icon']                  = "servertech";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1718.3";
$config['os'][$os]['mibs'][]                = "Sentry3-MIB";

$os = "sentry-pdu";
$config['os'][$os]['text']                  = "ServerTech Sentry PDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['icon']                  = "servertech";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1718.4";
$config['os'][$os]['mibs'][]                = "Sentry4-MIB";

// Gude

$os = "gude-epc";
$config['os'][$os]['text']                  = "Gude Expert Power Control";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['icon']                  = "gude";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.28507.1";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.28507.6";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.28507.11";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.28507.38";
//Expert Power Control 1200
//IPower Control 2x6 M
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+)$/';
$config['os'][$os]['mibs'][]                = "GUDEADS-EPC8X-MIB";
$config['os'][$os]['mibs'][]                = "GUDEADS-EPC2X6-MIB";

$os = "gude-pdu";
$config['os'][$os]['text']                  = "Gude Expert PDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['icon']                  = "gude";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.28507.23";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.28507.27";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.28507.35";
//Expert PDU Basic 8111
//Expert PDU energy 8182
//Expert PDU 8310
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+)$/';
$config['os'][$os]['mibs'][]                = "GUDEADS-PDU8110-MIB";
$config['os'][$os]['mibs'][]                = "GUDEADS-PDU8310-MIB";

$os = "geist-pdu";
$config['os'][$os]['text']                  = "Geist PDU";
$config['os'][$os]['vendor']                = "Geist";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.21239.2"; // Intersect with Watchdog Environmental, but used complex array
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.21239.5.2"; // Intersect with Geist Environmental
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/PDU/',
  'sysObjectID'                             => '.1.3.6.1.4.1.21239',
);
$config['os'][$os]['mibs'][]                = "GEIST-MIB-V3";
$config['os'][$os]['mibs'][]                = "GEIST-IMD-MIB";

$os = "geist-watchdog";
$config['os'][$os]['text']                  = "Geist Watchdog";
$config['os'][$os]['vendor']                = "Geist";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['discovery'][]           = array(
  // DESC: required to match both - sysDescr and any of sysObjectID from list
  'sysDescr'                                => '/Watchdog/',
  'sysObjectID'                             => '.1.3.6.1.4.1.21239.2',
);
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.21239.2"; // Intersect with PDU
$config['os'][$os]['remote_access']         = array('http');
$config['os'][$os]['mibs'][]                = "GEIST-MIB-V3";

$os = "geist-climate";
$config['os'][$os]['text']                  = "Geist Environmental";
$config['os'][$os]['vendor']                = "Geist";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.21239.4"; // Containment Cooling
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.21239.5"; // Environmental Monitor
$config['os'][$os]['remote_access']         = array('http');
$config['os'][$os]['mibs'][]                = "GEIST-V4-MIB";

// Raritan

$os = "raritan"; // FIXME rename to raritan-pdu
$config['os'][$os]['text']                  = "Raritan PDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['vendor']                = "Raritan";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.13742";
//$config['os'][$os]['sysDescr'][]            = "/^Raritan/";
$config['os'][$os]['mibs'][]                = "PDU-MIB";
$config['os'][$os]['mibs'][]                = "PDU2-MIB";

$os = "raritan-kvm";
$config['os'][$os]['text']                  = "Raritan KVM";
$config['os'][$os]['type']                  = "management";
//$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['vendor']                = "Raritan";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.13742.1"; // CommandCenter Secure Gateway
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.13742.3"; // DKX2
//$config['os'][$os]['sysDescr'][]            = "/^DKX/";
//$config['os'][$os]['mibs'][]                = "RARITANCCv2-MIB";
//$config['os'][$os]['mibs'][]                = "RemoteKVMDevice-MIB";

$os = "raritan-emx";
$config['os'][$os]['text']                  = "Raritan EMX";
$config['os'][$os]['type']                  = "environment";
//$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['vendor']                = "Raritan";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.13742.8";
$config['os'][$os]['sysDescr'][]            = "/^EMX \d+$/";
$config['os'][$os]['mibs'][]                = "EMD-MIB";

// MRV

$os = "mrvld";
$config['os'][$os]['text']                  = "MRV LambdaDriver";
$config['os'][$os]['group']                 = "mrv";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "mrv";
//$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysDescr'][]            = "/^LambdaDriver/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.629.100";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.629.100.2.1";
$config['os'][$os]['mibs'][]                = "OA-SFP-MIB";
$config['os'][$os]['mibs'][]                = "OADWDM-MIB";

$os = "mrvos";
$config['os'][$os]['text']                  = "MRV Optiswitch";
$config['os'][$os]['group']                 = "mrv";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "mrv";
$config['os'][$os]['port_label'][]          = '/^(.*?) \- /';               // Port 2 - ETH10/100/1000
$config['os'][$os]['remote_access']         = array('telnet', 'ssh', 'scp', 'http');
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_ucd_cpu";
//$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['sysDescr'][]            = "/^OptiSwitch/";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.629.22";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.629.22.23.1"; // OS9124-410GX
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.629.22.10.1"; // OS9024M-210Gx
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.629.22.30.1"; // OS904-MBH-4
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.629.22.33.1"; // OS606
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.629.22.25.1"; // OS904-MBH
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.629.22.21.1"; // OS912
$config['os'][$os]['mibs'][]                = "UCD-SNMP-MIB";
$config['os'][$os]['mibs'][]                = "DEV-ID-MIB";
$config['os'][$os]['mibs'][]                = "DEV-CFG-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "mrvnbs";
$config['os'][$os]['text']                  = "MRV";
$config['os'][$os]['group']                 = "mrv";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "mrv";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.629"; //FIXME, incorrect, too global
$config['os'][$os]['mibs'][]                = "NBS-CMMC-MIB";

// Tripp Lite

$os = "poweralert";
$config['os'][$os]['text']                  = "Tripp Lite PowerAlert";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['icon']                  = "tripplite";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.850";
$config['os'][$os]['mibs'][]                = "UPS-MIB";
$config['os'][$os]['mibs'][]                = "TRIPPLITE-12X";

$os = "tl-mgmt";
$config['os'][$os]['text']                  = "Tripp Lite Management";
$config['os'][$os]['type']                  = "management";
//$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['icon']                  = "tripplite";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.850.1.2";
//$config['os'][$os]['mibs'][]                = "UPS-MIB";

$os = "jdsu_edfa";
$config['os'][$os]['text']                  = "JDSU OEM Erbium Dotted Fibre Amplifier";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "avocent";
$config['os'][$os]['discovery'][]           = array(
  'NSCRTV-ROOT::commonDeviceVendorInfo.1'   => '/JDSU/',
  'NSCRTV-ROOT::commonDeviceName.1'         => '/EDFA/',
);
$config['os'][$os]['mibs'][]                = "NSCRTV-ROOT";

$os = "symbol";
$config['os'][$os]['text']                  = "Symbol Wireless";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "symbol";
//MotorolaADP-5131HW=J SW=2.5.1.0-016R MIB=01h07
//MotorolaAP-7131NHW=A SW=4.1.5.0-004R MIB=01h18
$config['os'][$os]['sysDescr_regex'][]      = '/Motorola(?<hardware>[\w\-]+)HW=\w+ SW=(?<version>\d[\d\.]+(?:\-\w+)?)/';
//Symbol WS2000 HW=A SW=2.2.2.0-003R MIB=08d29
//Symbol AP5131 HW=J SW=2.3.2.0-008R MIB=01g13
$config['os'][$os]['sysDescr_regex'][]      = '/Symbol (?<hardware>\w+) HW=\w+ SW=(?<version>\d[\d\.]+(?:\-\w+)?)/';
//VX9000 Wireless Controller, Version 5.8.3.1-002R MIB=01a
//RFS6000 Wireless Switch, Version 4.2.0.0-024R MIB=01a
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>\w+) Wireless (?:Controller|Switch), Version (?<version>\d[\d\.]+(?:\-\w+)?)/';
//WS5100 Wireless Switch, Revision WS.02.3.2.0.0-040R MIB=01a
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>\w+) Wireless Switch, Revision WS\.\d+\.(?<version>\d[\d\.]+(?:\-\w+)?)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.388";
$config['os'][$os]['mibs'][]                = "SYMBOL-CC-WS2000-MIB";

$os = "firebox";
$config['os'][$os]['text']                  = "WatchGuard Fireware";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['icon']                  = "watchguard";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.3097.1";
$config['os'][$os]['sysDescr'][]            = "/^WatchGuard (Fireware|OS)/";
$config['os'][$os]['sysDescr'][]            = "/^XTM/";
//WatchGuard OS 5.2.0
//WatchGuard Fireware v10.2
//WatchGuard Fireware v11.3.8
$config['os'][$os]['sysDescr_regex'][]      = '/WatchGuard (\w+) v?(?<version>[\d\.]+)/';
//M4600
//T10-W
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+)$/';
$config['os'][$os]['mibs'][]                = "WATCHGUARD-SYSTEM-STATISTICS-MIB";

$os = "panos";
$config['os'][$os]['text']                  = "PanOS";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['icon']                  = "paloalto";
$config['os'][$os]['vendor']                = "Palo Alto Networks";
//$config['os'][$os]['snmp']['max-rep']       = 50; // PanOS seems to fail here.
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['graphs'][]              = "device_panos_sessions";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25461.2";
$config['os'][$os]['mibs'][]                = "PAN-COMMON-MIB";
$config['os'][$os]['mibs'][]                = "HOST-RESOURCES-MIB";

// Aruba

$os = "arubaos";
$config['os'][$os]['text']                  = "ArubaOS";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "aruba";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['graphs'][]              = "device_arubacontroller_numaps";
$config['os'][$os]['graphs'][]              = "device_arubacontroller_numclients";
//ArubaOS (MODEL: Aruba3600), Version 6.1.2.2 (29541)
//ArubaOS Version 6.1.2.3-2.1.0.0
$config['os'][$os]['sysDescr_regex'][]      = '/Version (?<version>[\d\.]+)/';
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6486.800.1.1.2.2"; // Seems as wrong, because intersects with OmniStack and AOS-W
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14823";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.674.10895.5000-5099"; // lists currently unsupported
$config['os'][$os]['sysDescr'][]            = "/^ArubaOS/";
$config['os'][$os]['mibs'][]                = "WLSX-SWITCH-MIB";
$config['os'][$os]['mibs'][]                = "WLSX-WLAN-MIB";

$os = "aruba-meshos";
$config['os'][$os]['text']                  = "MeshOS";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "aruba";
$config['os'][$os]['ifname']                = 1;
$config['os'][$os]['sysObjectID'][]         = "1.3.6.1.4.1.23631";
$config['os'][$os]['sysDescr'][]            = "/^Azalea/";

$os = "trapeze";
$config['os'][$os]['text']                  = "Juniper Wireless";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['vendor']                = "Juniper";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_wifi_clients";
//Juniper Networks, Inc WLC2800 9.1.1.11 REL
$config['os'][$os]['sysDescr_regex'][]      = '/Juniper .+? (?<hardware>\w+) (?<version>\d[\d\.]+)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14525.3.3";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.14525.3.1";
$config['os'][$os]['mibs'][]                = "TRAPEZE-NETWORKS-ROOT-MIB";
$config['os'][$os]['mibs'][]                = "TRAPEZE-NETWORKS-SYSTEM-MIB";
$config['os'][$os]['mibs'][]                = "TRAPEZE-NETWORKS-CLIENT-SESSION-MIB";
$config['os'][$os]['mibs'][]                = "TRAPEZE-NETWORKS-AP-STATUS-MIB";
$config['os'][$os]['mibs'][]                = "TRAPEZE-NETWORKS-AP-CONFIG-MIB";

// Lancom devices - lcos is new, unified-MIB software. The others are legacy bullshit.

$os = "lcos";
$config['os'][$os]['text']                  = "LCOS";
$config['os'][$os]['vendor']                = "Lancom";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2356.11";
$config['os'][$os]['mibs'][]                = "LCOS-MIB";

$os = "lcos-old";
$config['os'][$os]['text']                  = "LCOS (OLD)";
$config['os'][$os]['vendor']                = "Lancom";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2356.600";
$config['os'][$os]['model']                 = "lancom";

/*
$os = "lancom-l54-dual";                    // Yes. Model-specific OS type for model-specific MIB.
$config['os'][$os]['text']                  = "LCOS (L-54 Dual)";
$config['os'][$os]['vendor']                = "Lancom";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2356.600.3.55";

$os = "lancom-l310";                        // Yes. Model-specific OS type for model-specific MIB.
$config['os'][$os]['text']                  = "LCOS (L-310)";
$config['os'][$os]['vendor']                = "Lancom";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2356.600.6.310";
$config['os'][$os]['mibs'][]                = "LANCOM-L310-MIB";

$os = "lancom-c54g";                        // Yes. Model-specific OS type for model-specific MIB.
$config['os'][$os]['text']                  = "LCOS (C-54g)";
$config['os'][$os]['vendor']                = "Lancom";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2356.600.4.54";

$os = "lancom-3550";                        // Yes. Model-specific OS type for model-specific MIB.
$config['os'][$os]['text']                  = "LCOS (3550)";
$config['os'][$os]['vendor']                = "Lancom";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2356.600.2.3550";

$os = "lancom-3850";                        // Yes. Model-specific OS type for model-specific MIB.
$config['os'][$os]['text']                  = "LCOS (3850)";
$config['os'][$os]['vendor']                = "Lancom";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2356.600.3.3850";
*/

// Synology

$os = "dsm";
$config['os'][$os]['text']                  = "Synology DSM";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['vendor']                = "Synology";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux/',
  // HOST-RESOURCES-MIB::hrSystemInitialLoadParameters.0 = STRING: "console=ttyS0,115200 ip=off initrd=0x00800040,4M root=/dev/md0 rw syno_hw_version=DS212jv20 ihd_num=2 netif_num=1"
  'HOST-RESOURCES-MIB::hrSystemInitialLoadParameters.0' => array('/syno_hw_version=(?:DS|RS)/',
                                                                 '/syno_hw_versio$/'), // http://jira.observium.org/browse/OBSERVIUM-1244
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux/',
  // SYNOLOGY-SYSTEM-MIB::modelName.0 = STRING: "DS209"
  'SYNOLOGY-SYSTEM-MIB::modelName.0'        => '/^(DS|RS)/',
);
$config['os'][$os]['mibs'][]                = "SYNOLOGY-SYSTEM-MIB";
$config['os'][$os]['mibs'][]                = "SYNOLOGY-DISK-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";
$config['os'][$os]['mib_blacklist'][]       = "LSI-MegaRAID-SAS-MIB";
$config['os'][$os]['mib_blacklist'][]       = "BGP4-MIB";

$os = "srm";
$config['os'][$os]['text']                  = "Synology SRM";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['vendor']                = "Synology";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux/',
  // HOST-RESOURCES-MIB::hrSystemInitialLoadParameters.0 = STRING: "console=ttyS0,115200 ip=off maxcpus=2 mem=240M initrd= root=/dev/mmcblk0p5 rootdelay=0 rw syno_hw_version=RT1900acv10 netif_num="
  'HOST-RESOURCES-MIB::hrSystemInitialLoadParameters.0' => '/syno_hw_version=RT/',
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux/',
  // SYNOLOGY-SYSTEM-MIB::modelName.0 = STRING: "RT1900ac"
  'SYNOLOGY-SYSTEM-MIB::modelName.0'        => '/^RT/',
);
$config['os'][$os]['mibs'][]                = "SYNOLOGY-SYSTEM-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-SENSOR-MIB";
$config['os'][$os]['mib_blacklist'][]       = "LSI-MegaRAID-SAS-MIB";
$config['os'][$os]['mib_blacklist'][]       = "BGP4-MIB";

// Ceragon

$os = "ceragon";
$config['os'][$os]['text']                  = "Ceragon FibeAir";
$config['os'][$os]['vendor']                = "Ceragon";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['snmp']['nobulk']        = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2281.1";
$config['os'][$os]['mibs']                  = "CERAGON-MIB";

// CTS - Connection Technology Systems

$os = "cts-switch";
$config['os'][$os]['text']                  = "CTS Switch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "cts";
$config['os'][$os]['group']                 = "cts";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['remote_access']         = array('telnet', 'ssh', 'scp', 'http');
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9304.100";
//FOS-2128G Version 1.01.00
//HES-3106-RF Version 1.03.00
$config['os'][$os]['sysDescr_regex'][]      = '/(?:(?<hardware>[\w\-]+) )?[Vv]ersion (?<version>\d[\w\.]+)/';

$os = "cts-wl";
$config['os'][$os]['text']                  = "CTS Switch";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['icon']                  = "cts";
$config['os'][$os]['group']                 = "cts";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['remote_access']         = array('telnet', 'ssh', 'scp', 'http');
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9304.200";
//version 1.07.24
//VRGIII-31412SFP-CW-N-DR version 1.04.4A
$config['os'][$os]['sysDescr_regex'][]      = '/(?:(?<hardware>[\w\-]+) )?[Vv]ersion (?<version>\d[\w\.]+)/';

// Digi

$os = "digios";
$config['os'][$os]['text']                  = "Digi OS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Digi";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.332.11.6";
//Connect WAN 3G (MEI serial, Watchport sensor) Version 82002592_B1 05/07/2011
//Connect WAN 3G (RS232 serial) Version ubuntu_tj 10/22/2012 16:32:14 PDT
//ConnectPort X4 NEMA Version 82001536_N 08/30/2013
//Digi Connect Device, Version Unknown
//Digi Connect N2S-170 Version 82001120_J1 04/21/2008
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>Connect.+?)(?:\(.+?\))?,? Version (?<version>\S+)/';

$os = "digi-anyusb";
$config['os'][$os]['text']                  = "Digi AnywhereUSB";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['vendor']                = "Digi";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/AnywhereUSB/',
  'sysObjectID'                             => '.1.3.6.1.4.1.332.11.6',
);
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+)$/';

// TSL

$os = "tsl-mdu12";
$config['os'][$os]['text']                  = "TSL MDU12";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['icon']                  = "tsl";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.332.11.6',
  'TSL-MIB::mdu12Ident.0'                   => '/MDU12/',
);

// Ubiquiti

$os = "unifi";
$config['os'][$os]['text']                  = "Ubiquiti UniFi Wireless";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['discovery_os']          = "linux";
$config['os'][$os]['vendor']                = "Ubiquiti";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['mibs'][]                = "FROGFOOT-RESOURCES-MIB";
//$config['os'][$os]['mibs'][]                = "UBNT-UniFi-MIB";
$config['os'][$os]['mib_blacklist'][]       = "BGP4-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "unifi-switch";
$config['os'][$os]['text']                  = "Ubiquiti UniFi Switch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Ubiquiti";
$config['os'][$os]['group']                 = "fastpath";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
//USW-24P-250, 3.3.5.3734, Linux 3.6.5
//USW-48P-500, 3.3.5.3734, Linux 3.6.5
$config['os'][$os]['sysDescr_regex'][]      = "/(?<hardware>USW[\w\-]+), (?<version>\d+(?:\.\d+){1,2})/";
// $config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.4413"; 4413 is actually broadcom
$config['os'][$os]['sysDescr'][]            = "/^USW\-/";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^USW\-/',
  'sysObjectID'                             => array('.1.3.6.1.4.1.4413', '.1.3.6.1.4.1.7244'),
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => array('.1.3.6.1.4.1.4413', '.1.3.6.1.4.1.7244'),
  'FASTPATH-SWITCHING-MIB::agentInventoryMachineType.0' => '/^USW\-/',
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => array('.1.3.6.1.4.1.4413', '.1.3.6.1.4.1.7244'),
  'FASTPATH-SWITCHING-MIB::agentInventoryMachineModel.0' => '/^USW\-/',
);
$config['os'][$os]['mibs'][]                = "EdgeSwitch-BOXSERVICES-PRIVATE-MIB";
$config['os'][$os]['mibs'][]                = "EdgeSwitch-ISDP-MIB";
$config['os'][$os]['mibs'][]                = "EdgeSwitch-SWITCHING-MIB";
$config['os'][$os]['mibs'][]                = "EdgeSwitch-POWER-ETHERNET-MIB";
$config['os'][$os]['mib_blacklist'][]       = "BGP4-MIB";

$os = "airos";
$config['os'][$os]['text']                  = "Ubiquiti AirOS";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['discovery_os']          = "linux";
$config['os'][$os]['vendor']                = "Ubiquiti";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['mibs'][]                = "FROGFOOT-RESOURCES-MIB";
// AIROS support Mikrotik experimental wireless MIB module
//$config['os'][$os]['mibs'][]                = "MIKROTIK-MIB";
$config['os'][$os]['mibs'][]                = "UBNT-AirFIBER-MIB";
$config['os'][$os]['mibs'][]                = "UBNT-AirMAX-MIB";

$os = "edgeos";
$config['os'][$os]['text']                  = "Ubiquiti EdgeOS"; // EdgeOS is a fork and port of Vyatta 6.3
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['vendor']                = "Ubiquiti";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.30803"; // EdgeOS < 1.5, but overlaps with Vyatta
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.41112"; // EdgeOS >    = 1.5 - overlaps with AirOS
$config['os'][$os]['sysDescr'][]            = "/Edge(Max|OS)/";
//EdgeOS v1.8.5.4884695.160608.1104
//EdgeOS v1.8.5alpha1.4866876.160407.1401
$config['os'][$os]['sysDescr_regex'][]      = '/Edge(?:Max|OS) v(?<version>\d+(?:\.\w+){2})/';
$config['os'][$os]['mibs'][]                = "UBNT-MIB";

$os = "edgemax";
$config['os'][$os]['text']                  = "Ubiquiti EdgeMAX";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['vendor']                = "Ubiquiti";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysDescr'][]            = "/Edge(Point )?Switch/";
$config['os'][$os]['sysDescr'][]            = "/^(Ubiquit[ie] )?(Edgrerouter|EdgeRouter)/i"; // Ubiquite Edgrerouter X
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => array('.1.3.6.1.4.1.4413', '.1.3.6.1.4.1.7244'),
  'FASTPATH-SWITCHING-MIB::agentInventoryMachineType.0' => '/^Edge(Point|Switch)/',
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => array('.1.3.6.1.4.1.4413', '.1.3.6.1.4.1.7244'),
  'FASTPATH-SWITCHING-MIB::agentInventoryMachineModel.0' => '/^Edge(Point|Switch)/',
);
$config['os'][$os]['mibs'][]                = "UBNT-MIB"; // ?
$config['os'][$os]['mibs'][]                = "EdgeSwitch-BOXSERVICES-PRIVATE-MIB";
$config['os'][$os]['mibs'][]                = "EdgeSwitch-SWITCHING-MIB";
$config['os'][$os]['mibs'][]                = "POWER-ETHERNET-MIB";
$config['os'][$os]['mibs'][]                = "BROADCOM-POWER-ETHERNET-MIB";

$os = "airos-af";
$config['os'][$os]['text']                  = "Ubiquiti AirFiber";
$config['os'][$os]['type']                  = "radio";
$config['os'][$os]['discovery_os']          = "linux";
$config['os'][$os]['vendor']                = "Ubiquiti";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['mibs'][]                = "FROGFOOT-RESOURCES-MIB";
// AIROS support Mikrotik experimental wireless MIB module
//$config['os'][$os]['mibs'][]                = "MIKROTIK-MIB";
$config['os'][$os]['mibs'][]                = "UBNT-AirFIBER-MIB";
$config['os'][$os]['mibs'][]                = "UBNT-AirMAX-MIB";

// Draytek firewall/routers

$os = "draytek";
$config['os'][$os]['text']                  = "Draytek";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['icon']                  = "draytek";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.7367";
$config['os'][$os]['sysDescr'][]            = "/DrayTek/i";
//DrayTek Corporation, Router Model: Vigor2710, Version: 3.6.3_232201, Build Date/Time:Nov 2 2012 16:56:21
//DrayTek Corporation, Router Model: Vigor2760 Series, Version: 3.8.2_VT3, Build Date/Time:Apr 11 2016 04:26:15
//DrayTek Corporation, Router Model: Vigor2830v2 Series, Version: 3.7.8.1, Build Date/Time:Jun 15 2015 16:13:33
//DrayTek Corporation, Router Model: Vigor3900, Version: 1.1.0_Beta/1.1.0, Build Date/Time: 2015-07-24 16:23:38
//DrayTek Corporation, Router Model: VigorBX 2000, Version: 3.8.1.3_RC1, Build Date/Time:Mar 28 2016 20:03:54
$config['os'][$os]['sysDescr_regex'][]      = "/Router Model: (?<hardware>[\w\ ]+?)(?: Series)?, Version: (?<version>\d[\d\.]+(_[a-z]+\d*)?)/i";

// SmartEdge OS

$os = "seos";
$config['os'][$os]['text']                  = "SmartEdge OS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "ericsson";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysDescr'][]            = "/SmartEdge.*? SEOS/";
//Redback Networks SmartEdge OS Version SEOS-CPG_13A:CXP9020159/19-R17K-SEOS_C13A-11.1.2.906.167-Build:CGW_13A_R17K06_130502_113121-Release Built by eabinfte@eselnlx1230.mo.sw.ericsson.se R17K06_130502
//Redback Networks SmartEdge OS Version SEOS-6.5.1.6-Release Built by sysbuild@SWB-node18 Wed Dec 19 17:57:32 PST 2012 Copyright (C) 1998-2012, Redback Networks Inc. All rights reserved.
//Redback Networks SmartEdge OS Version SEOS-11.1.2.7p2-Release Built by sysbuild@eussjlx7059.sj.us.am.ericsson.se Wed Apr 10 02:38:40 PDT 2013
$config['os'][$os]['sysDescr_regex'][]      = "/SEOS(?:_\w+)?\-(?<version>\d[\w\.]+)/";
$config['os'][$os]['mibs'][]                = "RBN-ENVMON-MIB";
$config['os'][$os]['mibs'][]                = "RBN-CPU-METER-MIB";
$config['os'][$os]['mibs'][]                = "RBN-MEMORY-MIB";
$config['os'][$os]['mibs'][]                = "RBN-SUBSCRIBER-ACTIVE-MIB";

$os = "ipos";
$config['os'][$os]['text']                  = "Ericsson IPOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Ericsson";
//$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysDescr'][]            = "/^Ericsson IPOS/";
//Ericsson IPOS-12.2.112.13.32p12-Release Built by sysbuild@eussjlx7009.sj.us.am.ericsson.se Tue Feb 17 17:09:35 PST 2015 Current Platform is SSR 8020
//Ericsson IPOS-14.1.127.8.90p7-Release Built by sysbuild@eussjlx7034.sj.us.am.ericsson.se Tue Jul 14 21:30:42 PDT 2015 Current Platform is SSR 8004
//Ericsson IPOS-16.1.1.4.27-Release Built by ciflash@eussjblx1012.sj.us.am.ericsson.se 2016-05-25 11:11:25-0700 Copyright (C) 1998-2016, Ericsson AB. All rights reserved. Current Platform is SSR 8004
$config['os'][$os]['sysDescr_regex'][]      = "/IPOS\-(?<version>\d[\w\.]+).* Current Platform is (?<hardware>[\w\ ]+)/";

$os = "ericsson-ucp";
$config['os'][$os]['text']                  = "Ericsson-LG UCP";
$config['os'][$os]['type']                  = "communication";
$config['os'][$os]['icon']                  = "ericsson";
//$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.572.16838";
$config['os'][$os]['sysDescr_regex'][]      = "/(?<hardware>(?:iPECS\-|UCP)\S+?),/";

$os = "ericsson-switch";
$config['os'][$os]['text']                  = "Ericsson-LG Switch";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "ericsson";
//$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.572.17389";
$config['os'][$os]['sysDescr_regex'][]      = "/(?<hardware>^ES\-[\w\-]+)/";

// Redline Communications

$os = "rdl";
$config['os'][$os]['text']                  = "Redline";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "redline";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.10728";

// Barracuda

$os = "barracudangfw";
$config['os'][$os]['text']                  = "Barracuda NG Firewall";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['icon']                  = "barracuda";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.10704";

$os = "barracuda-sc";
$config['os'][$os]['text']                  = "Barracuda Security";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['icon']                  = "barracuda";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysDescr'][]            = "/^Barracuda .+ (Filter|Firewall|VPN|Control)/";

$os = "barracuda-lb";
$config['os'][$os]['text']                  = "Barracuda LB";
$config['os'][$os]['type']                  = "loadbalancer";
$config['os'][$os]['icon']                  = "barracuda";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysDescr'][]            = "/Barracuda (Load|Link) Balancer/";

// Audiocodes

$os = "audiocodes";
$config['os'][$os]['text']                  = "Audiocodes";
$config['os'][$os]['type']                  = "voip";
$config['os'][$os]['icon']                  = "audiocodes";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5003";
//Product: GW 2 FXS;SW Version: 5.40A.032.004
//Product: MG 600;SW Version: 5.40A.022.003
//Product: MS 2K;SW Version: 5.60A.038.001
//Product: MP-114 FXS;SW Version: 5.00A.034.001
//Product: TrunkPack 260_UN;SW Version: 4.80.000
//Product: Mediant 1000;SW Version: 5.00A.046.004
//Product: MEDIANT8000 ; SW Version: 5.4.39
$config['os'][$os]['sysDescr_regex'][]      = '/Product: (?<hardware>\w[^;]+?) *; *SW Version: (?<version>\d[\w\.]+)/';
$config['os'][$os]['mibs'][]                = "AC-SYSTEM-MIB";

// ShoreTel

$os = "shoretelos";
$config['os'][$os]['text']                  = "ShoreTel OS";
$config['os'][$os]['type']                  = "voip";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5329";

// Mitel

$os = "mcd";
$config['os'][$os]['text']                  = "Mitel Controller";
$config['os'][$os]['type']                  = "voip";
$config['os'][$os]['vendor']                = "Mitel";
$config['os'][$os]['ifname']                = TRUE;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1027.1.2.3";
#$config['os'][$os]['mibs'][]                = "MITEL-MIB";
$config['os'][$os]['mibs'][]                = "MITEL-IperaVoiceLAN-MIB";

// Acme Packet / Oracle since 2013

$os = "acme";
$config['os'][$os]['text']                  = "Acme Packet";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "acmepacket";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.9148.1";
$config['os'][$os]['mibs'][]                = "ACMEPACKET-ENTITY-VENDORTYPE-OID-MIB";
$config['os'][$os]['mibs'][]                = "ACMEPACKET-ENVMON-MIB";
$config['os'][$os]['mibs'][]                = "APSYSMGMT-MIB";

// HW group

$os = "poseidon";
$config['os'][$os]['text']                  = "Poseidon";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_temperature";
//Poseidon 2250 SNMP Supervisor v1.0.9
//Poseidon2 4002 SNMP Supervisor v1.2.0
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>Poseidon\d* \d+) SNMP Supervisor v(?<version>[\d\.]+)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.21796.3.3";
$config['os'][$os]['mibs'][]                = "POSEIDON-MIB";

$os = "hwg-ste";
$config['os'][$os]['text']                  = "HWg-STE";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['vendor']                = "HW Group";
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.21796.4.1"; // STE1
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.21796.4.9"; // STE2
//HWg-STE
//HWg-STE Plus
//HWg-STE PoE
//STE2
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+)$/';
$config['os'][$os]['mibs'][]                = "STE-MIB";
$config['os'][$os]['mibs'][]                = "STE2-MIB";

$os = "hwg-pwr";
$config['os'][$os]['text']                  = "HWg-PWR";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['vendor']                = "HW Group";
$config['os'][$os]['icon']                  = "hwg";
//$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.21796.4.6";
//HWg-PWR
//HWg-SH4
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.+)$/';
$config['os'][$os]['mibs'][]                = "HWG-PWR-MIB";

$os = "teradici-pcoip";
$config['os'][$os]['text']                  = "PCoIP";
$config['os'][$os]['vendor']                = "Teradici";
$config['os'][$os]['type']                  = "workstation";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25071.1";
$config['os'][$os]['graphs'][]              = "device_pcoip-net-latency";
$config['os'][$os]['graphs'][]              = "device_pcoip-net-packets";
$config['os'][$os]['mibs'][]                = "TERADICI-PCOIP-MIB";
$config['os'][$os]['mibs'][]                = "TERADICI-PCOIPv2-MIB";

$os = "iqnos";
$config['os'][$os]['text']                  = "Infinera IQ";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "infinera";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.21296";
// MIBs disabled until not implemented
//$config['os'][$os]['mibs'][]                = "INFINERA-REG-MIB";
//$config['os'][$os]['mibs'][]                = "INFINERA-TC-MIB";

$os = "picos";
$config['os'][$os]['text']                  = "Pica8 OS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "pica8";
$config['os'][$os]['sysDescr'][]            = "/^Pica8/";
$config['os'][$os]['sysDescr_regex'][]      = '/Pica8 .+Software version (?<version>[\d\.]+).+Hardware model (?<hardware>[\w\-]+)/s';
// MIBs disabled until not implemented
//$config['os'][$os]['mibs'][]                = "PICA-PRIVATE-MIB";

// Radware

$os = "radware";
$config['os'][$os]['text']                  = "Radware DefensePro";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['icon']                  = "radware";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.89.1.1.62.16";
$config['os'][$os]['sysDescr'][]            = "/^DefensePro/";
$config['os'][$os]['sysDescr'][]            = "/Check Point DDoS Protector/";
$config['os'][$os]['mibs'][]                = "RADWARE-MIB";

$os = "radware-os";
$config['os'][$os]['text']                  = "Radware OS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "radware";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.89.1.1.62";
//$config['os'][$os]['sysDescr'][]            = "/^LinkProof/";
$config['os'][$os]['mibs'][]                = "RADWARE-MIB";

// Deliberant (now LigoWave)

$os = "dlb-wl";
$config['os'][$os]['text']                  = "Deliberant Wireless";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['vendor']                = "Deliberant";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.32761";
//APC 5M-18 V2, v5.94-3.rt3883.Intelbras.APC.pt_BR.48107.130923.104039
//APC 5M +, v5.95.rt3883.Intelbras.APC.pt_BR.49868.140627.165652
//DLB APC Propeller 5, v7.00.rt3883.Deliberant.APC.en_US.51058.cal.150218.142755
$config['os'][$os]['sysDescr_regex'][]      = '/^(?:DLB )?(?<hardware>APC .+?)\ *, v(?<version>\d[\w\.\-]+?)\.\w+/';
$config['os'][$os]['mibs'][]                = "DELIBERANT-MIB";
$config['os'][$os]['mibs'][]                = "DLB-802DOT11-EXT-MIB";
$config['os'][$os]['mibs'][]                = "DLB-RADIO3-DRV-MIB";

// LigoWave

$os = "ligo-wl";
$config['os'][$os]['text']                  = "LigoWave Wireless";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['vendor']                = "LigoWave";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.32750";
//PTP 5-23 MiMo PRO, v6.94-7.rt3883.Intelbras.PTP.pt_BR.49085.140218.173803
//LigoPTP 5-23 MiMo, v6.91.rt2880.LIGO.PTP.en_US.43942.120917.105604
//LigoPTP 5-N UNITY, v6.95-1.kirkwood.LIGO.PTP.en_US.50073.140808.114751
//LigoPTP 5-N UNITY, v6.91-1.kirkwood.ligowave.PTP.en_US.44473.121009.0237
$config['os'][$os]['sysDescr_regex'][]      = '/^(?:Ligo)?(?<hardware>PTP .+?)\ *, v(?<version>\d[\w\.\-]+?)\.\w+/';
$config['os'][$os]['mibs'][]                = "LIGO-802DOT11-EXT-MIB";

// AWind

$os = "wipg";
$config['os'][$os]['text']                  = "WePresent WiPG";
// no type set currently
$config['os'][$os]['icon']                  = "wepresent";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35251.2.3";

// Patton

$os = "smartware";
$config['os'][$os]['text']                  = "Patton Smartnode";
$config['os'][$os]['type']                  = "voip";
$config['os'][$os]['icon']                  = "patton";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1768.100.4.";
$config['os'][$os]['mibs'][]                = "SMARTNODE-MIB";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";

// Riverbed

$os = "steelhead";
$config['os'][$os]['text']                  = "Riverbed Steelhead";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "riverbed";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.17163.1.1"; // Steelhead
$config['os'][$os]['mibs'][]                = "STEELHEAD-MIB";

// Opengear

$os = "opengear";
$config['os'][$os]['text']                  = "Opengear";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['icon']                  = "opengear";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.";   // Wildcard sysObjectID
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.16.";  // Wildcard sysObjectID
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.1";  //CM4001
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.2";  //CM4002
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.3";  //CM4008
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.10"; //CM41xx
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.20"; //SD4001
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.21"; //SD4002
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.22"; //SD4008
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.23"; //SD4001DW
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.24"; //SD4002DX
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.31"; //CMx86
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.40"; //CMS61xx
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.41"; //Lighthouse
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.50"; //IM4004
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.60"; //IM42xx
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.61"; //IM72xx
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.70"; //KCS61xx
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.80"; //ACM500x
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25049.1.81"; //ACM550x
// MIBs disabled until not implemented
//$config['os'][$os]['mibs'][]              = "OG-CONNECT-MIB";
//$config['os'][$os]['mibs'][]              = "OG-DATA-MIB";
//$config['os'][$os]['mibs'][]              = "OG-FAILOVER-MIB";
//$config['os'][$os]['mibs'][]              = "OG-HOST-MIB";
//$config['os'][$os]['mibs'][]              = "OG-PATTERN-MIB";
//$config['os'][$os]['mibs'][]              = "OG-PRODUCTS-MIB";
//$config['os'][$os]['mibs'][]              = "OG-SENSOR-MIB";
//$config['os'][$os]['mibs'][]              = "OG-SIGNAL-MIB";
//$config['os'][$os]['mibs'][]              = "OG-SMI-MIB";
$config['os'][$os]['mibs'][]              = "OG-STATUS-MIB";
$config['os'][$os]['mibs'][]              = "OG-STATUSv2-MIB";
//$config['os'][$os]['mibs'][]              = "OG-UPS-MIB";
//$config['os'][$os]['mibs'][]              = "OGTRAP-MIB";
//$config['os'][$os]['mibs'][]              = "OGTRAPv2-MIB";

$os = "zeustm";
$config['os'][$os]['text']                  = "Riverbed Stingray";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "riverbed";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux/',
  // ZXTM-MIB::version.0 = STRING: "9.1"
  'ZXTM-MIB::version.0'                     => '/.+/', // non empty
);
$config['os'][$os]['mibs'][]                = "ZXTM-MIB";
//$config['os'][$os]['mibs'][]                = "ZXTM-MIB-SMIv2";

// Brocade Virtual Traffic Manager
$os = "brocade-vtm";
$config['os'][$os]['text'] = "Brocade VTM";
$config['os'][$os]['type'] = "network";
$config['os'][$os]['icon'] = "brocade";
$config['os'][$os]['discovery'][] = array(
 'sysObjectID' => '.1.3.6.1.4.1.7146.1.2',
 'sysDescr' => '/^Linux/',
 'ZXTM-MIB-SMIv2::version.0' => '/.+/',
);
$config['os'][$os]['mibs'][] = "ZXTM-MIB-SMIv2";

// Scientific Atlanta / Cisco

$os = "cisco-dmn";
$config['os'][$os]['vendor']                = "Cisco";
$config['os'][$os]['text']                  = "Cisco DMN";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['icon']                  = "cisco";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1429.2";
$config['os'][$os]['mibs'][]                = "CISCO-DMN-DSG-DL-MIB";
$config['os'][$os]['mibs'][]                = "CISCO-DMN-DSG-BKPRST-MIB";
$config['os'][$os]['graphs'][]              = "device_bits";
 

// SmartOptics M-series hardware with SmartOS software

$os = "smartos";
$config['os'][$os]['vendor']                = "SmartOptics";
$config['os'][$os]['text']                  = "SmartOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['icon']                  = "smartoptics";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.30826.1";
$config['os'][$os]['mibs'][]                = "MSERIES-ENVMON-MIB";
$config['os'][$os]['mibs'][]                = "MSERIES-ALARM-MIB";
$config['os'][$os]['mibs'][]                = "MSERIES-PORT-MIB";
$config['os'][$os]['graphs'][]              = "device_bits";

// FiberRoad
$os = "fiberroad-mc";
$config['os'][$os]['text']                  = "FiberRoad Media Converter";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "FiberRoad";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6688";
$config['os'][$os]['sysDescr'][]            = "/^MGMT MC & OEO NMS/";
$config['os'][$os]['mibs'][]                = "XXX-MIB";

// Clavister
$os = "clavister-cos";
$config['os'][$os]['vendor']                = "Clavister";
$config['os'][$os]['text']                  = "Clavister cOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5089.1";
$config['os'][$os]['sysDescr'][]            = "/^Clavister/";
//Clavister cOS Core 10.21.02.01-25325
//Clavister CorePlus 9.30.08.21-22257 TP
//Clavister CorePlus 9.30.04.10-18175
$config['os'][$os]['sysDescr_regex'][]      = '/Core(?:Plus)? (?<version>\d[\d\.]+)/';
$config['os'][$os]['mibs'][]                = "CLAVISTER-SMI-MIB";
$config['os'][$os]['mibs'][]                = "CLAVISTER-MIB";

// OneOS
$os = "oneos";
$config['os'][$os]['vendor']                = "OneAccess";
$config['os'][$os]['text']                  = "OneAccess OneOS";
//$config['os'][$os]['type']                  = "voip"; // Set type in polling os
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.13191.1.1";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['mibs'][]                = "ONEACCESS-SYS-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

// Sophos
$os = "sophos";
$config['os'][$os]['vendor']                = "Sophos";
$config['os'][$os]['text']                  = "Sophos UTM";
$config['os'][$os]['type']                  = "firewall";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['discovery'][]           = array(
  'sysDescr'                                => '/^Linux \w\S+ \d[\.\d]+-\d[\.\d]+\.g\w{7}(?:\.rb\d+)?-smp(?:64)? #/',
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
);
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_ping";

// NetPing East, Alentis Electronics
$os_group = "netping";
$config['os_group'][$os_group]['vendor']            = "NetPing";
//UniPing Server Solution, FW v50.11.7.A-10
//UniPing Server Solution v3/SMS, FW v70.5.2.E-1
//UniPing v3, FW v60.3.6.A-1
//NetPing 8/PWRv3/SMS, FW v48.4.5.A-1
$config['os_group'][$os_group]['sysDescr_regex'][]  = '/(?<hardware>UniPing|NetPing) (?<hardware1>.*?), FW v(?<version>\d[\w\-\.]+)/';
$config['os_group'][$os_group]['mib_blacklist'][] = "ENTITY-MIB";
$config['os_group'][$os_group]['mib_blacklist'][] = "ENTITY-SENSOR-MIB";
$config['os_group'][$os_group]['mib_blacklist'][] = "HOST-RESOURCES-MIB";

$os = "uniping";
$config['os'][$os]['text']                  = "UniPing";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['group']                 = "netping";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25728";
$config['os'][$os]['sysDescr'][]            = "/UniPing v\d/";
$config['os'][$os]['mibs'][]                = "DKSF-60-4-X-X-X";  // UniPing v3

$os = "uniping-server-v3";
$config['os'][$os]['text']                  = "UniPing Server";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['group']                 = "netping";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25728";
$config['os'][$os]['sysDescr'][]            = "/UniPing Server Solution[^\,]+/";
$config['os'][$os]['mibs'][]                = "DKSF-70-5-X-X-1";  // UniPing server solution v3/SMS

$os = "uniping-server";
$config['os'][$os]['text']                  = "UniPing Server";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['group']                 = "netping";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
$config['os'][$os]['sysDescr'][]            = "/UniPing Server Solution\,/";
$config['os'][$os]['mibs'][]                = "DKSF-50-11-X-X-X"; // UniPing server solution

// NOTE, this device/os has PDU outlet statuses
$os = "netping-pwr3";
$config['os'][$os]['text']                  = "NetPing 8/PWRv3/SMS";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['group']                 = "netping";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['graphs'][]              = "device_temperature";
$config['os'][$os]['graphs'][]              = "device_humidity";
$config['os'][$os]['sysDescr'][]            = "/^NetPing 8/";
$config['os'][$os]['mibs'][]                = "DKSF-48-4-X-X-1";

/* Not tested
$os = "netping";
$config['os'][$os]['text']                  = "NetPing";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['vendor']                = "NetPing";
$config['os'][$os]['group']                 = "netping";
$config['os'][$os]['snmp']['nobulk']        = 1;
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.25728";
$config['os'][$os]['sysDescr'][]            = "/NetPing ????/";
$config['os'][$os]['mibs'][]                = "DKSF-253-5-X-A-X"; // NetPing IO v2
$config['os'][$os]['mibs'][]                = "DKSF-707-1-X-X-1"; // NetPing SMS
*/


// Alpha aka Argus aka Cordex Power Systems

$os = "alpha-cordex";  // This works for Alpha Cordex controllers
$config['os'][$os]['text']                  = "Alpha Cordex";
$config['os'][$os]['icon']                  = "alpha";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.7309.4";
$config['os'][$os]['mibs'][]                = "AlphaPowerSystem-MIB";

$os = "alpha-cxcrmu";  // This works for CXC RMU controllers
$config['os'][$os]['text']                  = "Alpha";
$config['os'][$os]['icon']                  = "alpha";
$config['os'][$os]['type']                  = "power";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.7309.6";
$config['os'][$os]['mibs'][]                = "Argus-Power-System-MIB";

// Bintec Elmeg

$os = "bintec-os";
$config['os'][$os]['vendor']                = "BinTec Elmeg";
$config['os'][$os]['icon']                  = "bintec";
$config['os'][$os]['group']                 = "bintec";
$config['os'][$os]['text']                  = "BinTec OS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['snmp']['noincrease']    = 1; // This os has troubles with increase OIDs in snmpwalk
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.272.4";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['mibs'][]                = "BIANCA-BRICK-MIB";
$config['os'][$os]['mibs'][]                = "BIANCA-BRICK-MIBRES-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

$os = "bintec-voip";
$config['os'][$os]['vendor']                = "BinTec Elmeg";
$config['os'][$os]['icon']                  = "bintec";
$config['os'][$os]['group']                 = "bintec";
$config['os'][$os]['text']                  = "BinTec VoIP";
$config['os'][$os]['type']                  = "voip";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['snmp']['noincrease']    = 1; // This os has troubles with increase OIDs in snmpwalk
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.272.4.200.65.49";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.272.4.200.83.78";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.272.4.201.84";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['mibs'][]                = "BIANCA-BRICK-MIB";
$config['os'][$os]['mibs'][]                = "BIANCA-BRICK-MIBRES-MIB";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

// Accedian Networks

$os = "acdos";
$config['os'][$os]['text']                  = "Accedian Networks";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.22420.1.1";
$config['os'][$os]['icon']                  = "accedian";
$config['os'][$os]['mibs'][]                = "ACD-DESC-MIB";

// Mimosa Networks

$os = "mimosa-backhaul";
$config['os'][$os]['text']                  = "Mimosa Backhaul Radio";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['vendor']                = "Mimosa Networks";
$config['os'][$os]['icon']                  = "mimosa";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.43356.1.1";
//$config['os'][$os]['mibs'][]                = "MIMOSA-NETWORKS-BASE-MIB";
$config['os'][$os]['mibs'][]                = "MIMOSA-NETWORKS-BFIVE-MIB";

// jetNexus

$os = "jetnexus-lb";
$config['os'][$os]['text']                  = "jetNexus LB";
$config['os'][$os]['type']                  = "loadbalancer";
$config['os'][$os]['vendor']                = "jetNexus";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux/',
  // JETNEXUS-MIB::jetnexusVersionInfo.0 = STRING: "4.1.2 (Build 1644) "
  'JETNEXUS-MIB::jetnexusVersionInfo.0'     => '/.+/', // non empty
);
$config['os'][$os]['mibs'][]                = "JETNEXUS-MIB";

// Aethra

$os = "aethra-dsl";
$config['os'][$os]['text']                  = "Aethra DSL";
$config['os'][$os]['vendor']                = "Aethra";
$config['os'][$os]['group']                 = "aethra";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.7745.4";
//FS5104 Aethra DSL Device Release: 3.4.25
//MY2441 Aethra DSL Device Release: 4.0.16C1
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>\S+) Aethra DSL Device Release:\s+(?<version>\d[\w\.]+)/';
//$config['os'][$os]['mibs'][]                = "AETHRA-MIB";

$os = "atosnt";
$config['os'][$os]['text']                  = "Aethra ATOS-NT";
$config['os'][$os]['vendor']                = "Aethra";
$config['os'][$os]['group']                 = "aethra";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.7745.5";
//Aethra BG1220 - Hardware Version: 2361A - Aethra Telecomunications Operating System - Software Release: 5.2.0.0 - Copyright (c) 2010 by A TLC Srl
//Aethra SV6044EVXW - Hardware Version: 2440A - Aethra Telecomunications Operating System - Software Release: 6.1.9.3 - Copyright (c) 2010 by A TLC Srl
$config['os'][$os]['sysDescr_regex'][]      = '/Aethra\s+(?<hardware>[\w\-]+).+?Hardware Version:.+?Software Release:\s+(?<version>\d[\w\.]+)/';
$config['os'][$os]['mibs'][]                = "AETHRA-MIB";

$os = "aethra-vcs";
$config['os'][$os]['text']                  = "Aethra VCS";
$config['os'][$os]['vendor']                = "Aethra";
$config['os'][$os]['group']                 = "aethra";
$config['os'][$os]['type']                  = "voip";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.7745.7";
//AVC8500_Series_3 Aethra Video Communication System.
//VegaX3Series3 Aethra Video Communication System.
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>\S+) Aethra Video/';
//$config['os'][$os]['mibs'][]                = "AETHRA-MIB";

// Iskratel

$os = "iskratel-fb";
$config['os'][$os]['text']                  = "Iskratel Fiberblade";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Iskratel";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['graphs'][]              = "device_ucd_memory";
$config['os'][$os]['remote_access']         = array('telnet', 'ssh', 'scp', 'http');
$config['os'][$os]['sysDescr'][]            = "/ISKRATEL Switching/";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1332.1.3"; // Multiple oses
//$config['os'][$os]['mibs'][]                = "ISKRATEL-MSAN-MIB";
//$config['os'][$os]['mibs'][]                = "ISKRATEL-IPMI-MIB";

$os = "iskratel-linux";
$config['os'][$os]['text']                  = "Iskratel Server";
$config['os'][$os]['vendor']                = "Iskratel";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.1332.1.3',
  'sysDescr'                                => '/^Linux /',
);

// McAfee

$os = "mcafee-meg";
$config['os'][$os]['text']                  = "McAfee MEG Appliance";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['vendor']                = "McAfee";
$config['os'][$os]['sysDescr'][]            = "/^McAfee Email Gateway/";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";

// Progres Plus Plug&Track Sensor-Net-Connect

$os = "plugandtrack";
$config['os'][$os]['text']                  = "Plug&Track v2";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.31440";
$config['os'][$os]['mibs'][]                = "EDS-MIB";

// Another per-hardware/model MIBs device (like D-Link)
$os = "edgecore-os";
$config['os'][$os]['text']                  = "Edgecore OS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Edgecore Networks";
$config['os'][$os]['icon']                  = "edgecore";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.259";
$config['os'][$os]['model']                 = "edgecore";

// IgniteNet

$os = "metrolinq";
$config['os'][$os]['text']                  = "MetroLinq";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['vendor']                = "IgniteNet";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.47307";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['mibs'][]                = "UCD-SNMP-MIB";
$config['os'][$os]['mibs'][]                = "IGNITENET-MIB";
$config['os'][$os]['ports_skip_ifType']     = 1;

// DCN / Digital China Networks

$os = "dcn-os";
$config['os'][$os]['text']                  = "Digital China IOS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['vendor']                = "Digital China Networks";
$config['os'][$os]['icon']                  = "dcn";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.6339.1";
$config['os'][$os]['mibs'][]                = "AMER-MIB";

// Panasas

$os = "panasas-panfs";
$config['os'][$os]['text']                  = "Panasas ActiveStor";
$config['os'][$os]['type']                  = "storage";
$config['os'][$os]['vendor']                = "Panasas";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.10159";
//$config['os'][$os]['mibs'][]                = "PANASAS-PANFS-MIB-V1";

$os = "truen-video";
$config['os'][$os]['text']                  = "Truen Camera/Server";
$config['os'][$os]['vendor']                = "Truen";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['sysDescr'][]            = "!^IP video camera/server!";

$os = "knuerr-rms";
$config['os'][$os]['vendor']                = "Knuerr";
$config['os'][$os]['text']                  = "Knuerr RMS";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2769.10";
$config['os'][$os]['mibs'][]                = "RMS-MIB";

$os = "meinberg-lantime";
$config['os'][$os]['vendor']                = "Meinberg";
$config['os'][$os]['text']                  = "Meinberg LANTIME";
$config['os'][$os]['type']                  = "server";
$config['os'][$os]['group']                 = "unix";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5597";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5597.3";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.5597.30";
$config['os'][$os]['mibs'][]                = "MBG-SNMP-LT-MIB";
$config['os'][$os]['mibs'][]                = "MBG-SNMP-LTNG-MIB";

$os = "kemp-lb";
$config['os'][$os]['vendor']                = "KEMP";
$config['os'][$os]['text']                  = "KEMP Load Balancer";
$config['os'][$os]['type']                  = "loadbalancer";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.12196.250.10";
$config['os'][$os]['mibs'][]                = "B100-MIB";
//$config['os'][$os]['mibs'][]                = "IPVS-MIB";

$os = "arrayos";
$config['os'][$os]['vendor']                = "Array Networks";
$config['os'][$os]['text']                  = "Array OS";
$config['os'][$os]['icon']                  = "array";
$config['os'][$os]['type']                  = "loadbalancer";
//$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.7564";

$os = "exinda-os";
$config['os'][$os]['vendor']                = "Exinda";
$config['os'][$os]['text']                  = "Exinda OS";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.21091";

// ICT Power

$os = "ict-pdu";
$config['os'][$os]['text']                  = "ICT Distribution Panel";
$config['os'][$os]['vendor']                = "ICT Power";
$config['os'][$os]['icon']                  = "ict";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.39145.10";
$config['os'][$os]['mibs'][]                = "ICT-MIB";

$os = "ict-power";
$config['os'][$os]['text']                  = "ICT Power";
$config['os'][$os]['vendor']                = "ICT Power";
$config['os'][$os]['icon']                  = "ict";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.39145";
$config['os'][$os]['mibs'][]                = "ICT-MIB";

// Mitsubishi

$os = "mitsubishi-ups";
$config['os'][$os]['vendor']                = "Mitsubishi";
$config['os'][$os]['text']                  = "Mitsubishi UPS";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_frequency";
$config['os'][$os]['graphs'][]              = "device_power";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.13891.101";
$config['os'][$os]['mibs'][]                = "MITSUBISHI-UPS-MIB";

// TrendMicro

$os = "tippingpoint-ips";
$config['os'][$os]['text']                  = "TippingPoint IPS"; // Intrusion Prevention System
$config['os'][$os]['vendor']                = "TrendMicro";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['graphs'][]              = "device_processor";
$config['os'][$os]['graphs'][]              = "device_mempool";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.10734.1.3";
$config['os'][$os]['sysDescr'][]            = "/^TippingPoint IPS/";
$config['os'][$os]['mibs'][]                = "TPT-HEALTH-MIB";
$config['os'][$os]['mibs'][]                = "TPT-RESOURCE-MIB";
$config['os'][$os]['mibs'][]                = "TPT-TPA-HARDWARE-MIB";

$os = "tippingpoint-sms";
$config['os'][$os]['text']                  = "TippingPoint SMS"; // Security Management System
$config['os'][$os]['vendor']                = "TrendMicro";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.10734.1.4";
// SMS vsidsmon vSMS 4.5.0.98012
$config['os'][$os]['sysDescr_regex'][]      = '/(?<hardware>\w+) (?<version>\d[\d\.\-]+)/';

// iRZ

$os = "irz-os";
$config['os'][$os]['text']                  = "iRZ Linux";
$config['os'][$os]['vendor']                = "iRZ";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.35489";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['ports_skip_ifType']     = TRUE;

// Siklu EH
$os = "siklu-wl";
$config['os'][$os]['text']                  = "Siklu EtherHaul";
$config['os'][$os]['vendor']                = "Siklu";
$config['os'][$os]['type']                  = "radio";
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>.*)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.31926";

// SUMMIT DEVELOPLMENT

$os = "summitd-wl";
$config['os'][$os]['text']                  = "Summit Developlment";
$config['os'][$os]['icon']                  = "summitd";
$config['os'][$os]['type']                  = "radio";
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>\w+)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.23688";

// Vubiq Networks

$os = "vubiq-wl";
$config['os'][$os]['text']                  = "Vubiq HaulPass";
$config['os'][$os]['icon']                  = "vubiq";
$config['os'][$os]['type']                  = "radio";
$config['os'][$os]['sysDescr_regex'][]      = '/^(?<hardware>\w+)/';
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.46330";

// Hirschmann

$os = "hirschmann-os";
$config['os'][$os]['text']                  = "Hirschmann HiOS";
$config['os'][$os]['vendor']                = "Hirschmann";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.248.11";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['mibs'][]                = "HM2-DEVMGMT-MIB";
$config['os'][$os]['mibs'][]                = "HM2-PWRMGMT-MIB";
$config['os'][$os]['mibs'][]                = "HM2-DIAGNOSTIC-MIB";

$os = "hirschmann-switch";
$config['os'][$os]['text']                  = "Hirschmann Switch";
$config['os'][$os]['vendor']                = "Hirschmann";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.248.14.10";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['mibs'][]                = "HMPRIV-MGMT-SNMP-MIB";

$os = "hirschmann-security";
$config['os'][$os]['text']                  = "Hirschmann Security OS";
$config['os'][$os]['vendor']                = "Hirschmann";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.248.14.10.23";
$config['os'][$os]['type']                  = "firewall";

// Digipower

$os = "digipower-ups";
$config['os'][$os]['text']                  = "Digipower UPS";
$config['os'][$os]['group']                 = "ups";
$config['os'][$os]['vendor']                = "Digipower";
$config['os'][$os]['graphs'][]              = "device_current";
$config['os'][$os]['graphs'][]              = "device_voltage";
//$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.17420";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.17420',
  'sysDescr'                                => '/^SNMPIV/',
);
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.17420',
  // DGPUPS-MIB::protocol.0 = INTEGER: rpm(2)
  'DGPUPS-MIB::protocol.0'                => '/.+/', // non empty string
);
$config['os'][$os]['mibs'][]                = "DGPUPS-MIB";
$config['os'][$os]['mibs'][]                = "DGPRPM-MIB";

$os = "digipower-pdu";
$config['os'][$os]['text']                  = "Digipower PDU";
$config['os'][$os]['group']                 = "pdu";
$config['os'][$os]['vendor']                = "Digipower";
$config['os'][$os]['graphs'][]              = "device_current";
//$config['os'][$os]['graphs'][]              = "device_voltage";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.17420";
$config['os'][$os]['mibs'][]                = "DGPPDU-MIB";

// RTCS Generic Embedded OS - Used by embedded platforms, often useless sysObjectID / sysDescr

$os = "nxp-mqx-rtcs";
$config['os'][$os]['text']                  = "NXP MQX RTOS/RTCS";
$config['os'][$os]['group']                 = "network";
$config['os'][$os]['vendor']                = "nxp";
$config['os'][$os]['graphs'][]              = "device_bits";
$config['os'][$os]['sysDescr_regex'][]      = "/RTCS version (?<version>[\d\.\-]+)/";
//$config['os'][$os]['sysDescr'][]            = "/^RTCS version/";

// Accuenergy
// .1.3.6.1.4.1.39604.1.1.1.1

$os = "accuvimii";
$config['os'][$os]['text']                  = "Accuvim II";
$config['os'][$os]['group']                 = "power";
$config['os'][$os]['vendor']                = "accuenergy";
$config['os'][$os]['graphs'][]              = "device_bits";
//$config['os'][$os]['discovery'][]           = array(
//  'sysDescr'                                => '/RTCS version/',
//  '.1.3.6.1.4.1.39604.1.1.1.1.6.20.0'       => '/.+/', // not empty
//);
$config['os'][$os]['mibs'][]                = "ACCUENERGY-MIB";

// Omnitron Systems

$os = "omnitron-iconverter";
$config['os'][$os]['text']                  = "Omnitron iConverter";
$config['os'][$os]['vendor']                = "Omnitron Systems";
$config['os'][$os]['icon']                  = "omnitron";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.7342";
$config['os'][$os]['mibs'][]                = "OMNITRON-MIB";
$config['os'][$os]['mibs'][]                = "OMNITRON-POE-MIB";
//Omnitron iConverter GM4-HPOE 8991T11D v5.2.14 s/n 00716236 - GM4-PoE - x
$config['os'][$os]['sysDescr_regex'][]      = "/iConverter .+? v(?<version>[\d\.\-]+) s\/n (?<serial>\w+) - (?<hardware>[\w\-\+]+)/";

$os = "vivotek-encoder";
$config['os'][$os]['vendor']                = "Vivotek";
$config['os'][$os]['text']                  = "Vivotek Video Server";
$config['os'][$os]['type']                  = "video";
$config['os'][$os]['icon']                  = "vivotek";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.23465";
$config['os'][$os]['sysDescr'][]            = "/^(H\.264 )?Video Server$/";
$config['os'][$os]['graphs'][]              = "device_bits";
//$config['os'][$os]['graphs'][]              = "device_processor";
//$config['os'][$os]['graphs'][]              = "device_mempool";

// DPS Telecom
$os = "dps-ng";
$config['os'][$os]['text']                  = "DPS NetGuardian";
$config['os'][$os]['vendor']                = "DPS Telecom";
$config['os'][$os]['type']                  = "environment";
$config['os'][$os]['icon']                  = "dps";
$config['os'][$os]['snmp']['nobulk']        = 1;
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2682";
//CopperCom-16S v1.0F.0288
//NetGuardian-G4 v4.2P.0022
//NG216-G3 v2.1G.0023
$config['os'][$os]['sysDescr_regex'][]      = "/^(?<hardware>\S+) v(?<version>\d+[\w\.\-]+)/";
$config['os'][$os]['mibs'][]                = "DPS-MIB-V38";

// WTI Console
$os = "wti-rsm-tsm";
$config['os'][$os]['text']                  = "WTI";
$config['os'][$os]['type']                  = "management";
$config['os'][$os]['vendor']                = "Western Telematic Inc";
$config['os'][$os]['icon']                  = "wti";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2634.2"; // TSM
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2634.3"; // ??
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.2634.6"; // ??
$config['os'][$os]['mibs'][]                = "WTI-RSM-TSM-MIB";

// ATMedia

$os = "atmedia-crypt";
$config['os'][$os]['text']                  = "ATMedia Encryptor";
$config['os'][$os]['type']                  = "security";
$config['os'][$os]['vendor']                = "ATMedia GmbH";
$config['os'][$os]['icon']                  = "atmedia";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.13458.1.1";
$config['os'][$os]['mibs'][]                = "ATMEDIA-MIB";

// Perle
// Media Converters
$os = "perle-mcr";
$config['os'][$os]['text']                  = "Perle MCR-MGT";
$config['os'][$os]['icon']                  = "perle";
$config['os'][$os]['type']                  = "network";
$config['os'][$os]['sysObjectID'][]         = ".1.3.6.1.4.1.1966.20.1.1";
$config['os'][$os]['mibs'][]                = "PERLE-MCR-MGT-MIB";
$config['os'][$os]['sysDescr_regex'][]      = "/(?<hardware>.+?), (?<version>\d+[\w\.\-]+)$/";
$config['os'][$os]['mib_blacklist'][]       = "ENTITY-MIB";
$config['os'][$os]['mib_blacklist'][]       = "HOST-RESOURCES-MIB";

// Teltonika

$os = "teltonika";
$config['os'][$os]['text']                  = "Teltonika";
$config['os'][$os]['type']                  = "wireless";
$config['os'][$os]['vendor']                = "teltonika";
$config['os'][$os]['group']                 = "unix";
$config['os'][$os]['discovery'][]           = array(
  'sysObjectID'                             => '.1.3.6.1.4.1.8072.3.2.10',
  'sysDescr'                                => '/^Linux/',
  'TELTONIKA-MIB::RouterName.0'             => '/.+/', // non empty
);
$config['os'][$os]['mibs'][]                = "TELTONIKA-MIB";



foreach ($config['os'] as $this_os => $os_data)
{
  if (isset($config['os'][$this_os]['group']))
  {
    $this_os_group = $config['os'][$this_os]['group'];
    if (isset($config['os_group'][$this_os_group]))
    {
      foreach ($config['os_group'][$this_os_group] as $property => $value)
      {
        if (!isset($config['os'][$this_os][$property]))
        {
          $config['os'][$this_os][$property] = $value;
        }
      }
    }
  }

  if (isset($os_data['snmpable']))
  {
    // Add all 'snmpable' to generic
    $config['os']['generic']['snmpable'] = array_merge($config['os']['generic']['snmpable'], $os_data['snmpable']);
  }
}
$config['os']['generic']['snmpable'] = array_unique($config['os']['generic']['snmpable']);
if (count($config['os']['generic']['snmpable']) == 0) { unset($config['os']['generic']['snmpable']); }
unset($config['os']['generic']['type']); // Reset type for generic os (added by unix group)

// EOF
