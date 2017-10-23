<?php

include(dirname(__FILE__) . '/../includes/defaults.inc.php');
//include(dirname(__FILE__) . '/../config.php'); // Do not include user editable config here
//include(dirname(__FILE__) . '/data/test_definitions.inc.php'); // Fake definitions for testing
include(dirname(__FILE__) . '/../includes/definitions.inc.php');
include(dirname(__FILE__) . '/../includes/functions.inc.php');

class IncludesSnmpTest extends \PHPUnit\Framework\TestCase
{
  /**
  * @dataProvider providerMibDirs
  */
  public function testMibDirs($result, $value1, $value2 = '')
  {
    global $config;

    $config['mib_dir'] = '/opt/observium/mibs';

    $this->assertSame($result, mib_dirs($value1, $value2));
  }

  public function providerMibDirs()
  {
    $results = array(
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp', ''),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp', 'rfc', 'net-snmp'),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/cisco', 'cisco'),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/areca:/opt/observium/mibs/dell', 'areca', 'dell'),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/areca:/opt/observium/mibs/dell', array('areca','dell')),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/cisco:/opt/observium/mibs/dell:/opt/observium/mibs/broadcom:/opt/observium/mibs/netgear', array('cisco','dell'), array('broadcom','netgear')),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/d-link:/opt/observium/mibs/d_link', array('d-link','d_link')),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/dell', array('inv@lid.name','dell')),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/banana', 'banana', '######'),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/banana', 'banana', array('banana')),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs', 'mibs'),
    );
    return $results;
  }

  /**
  * @dataProvider providerSnmpMib2MibDir
  */
  public function testSnmpMib2MibDir($result, $mib)
  {
    global $config;

    $config['mib_dir'] = '/opt/observium/mibs';

    $this->assertSame($result, snmp_mib2mibdirs($mib));
  }

  public function providerSnmpMib2MibDir()
  {
    $results = array(
      // Basic
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp', 'HOST-RESOURCES-MIB'),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp', 'HOST-RESOURCES-MIB:HOST-RESOURCES-TYPES'),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/cisco', 'ENTITY-MIB:CISCO-ENTITY-VENDORTYPE-OID-MIB'),
      array('/opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/cisco:/opt/observium/mibs/broadcom', 'ENTITY-MIB:CISCO-ENTITY-VENDORTYPE-OID-MIB:FASTPATH-SWITCHING-MIB'),
      // Unknown
      array('/opt/observium/mibs', 'HOST-RESOURCES'),
    );
    return $results;
  }

  /**
  * @dataProvider providerSnmpDewrap32bit
  * @group numbers
  */
  public function testSnmpDewrap32bit($result, $value)
  {
    $this->assertSame($result, snmp_dewrap32bit($value));
  }

  public function providerSnmpDewrap32bit()
  {
    return array(
      array(         0,           0),
      array(     65000,       65000),
      array(   '65000',     '65000'),
      array(        '',          ''),
      array(  'some.0',    'some.0'),
      array(     FALSE,       FALSE),
      // Here wrong (negative) 32bit values
      array(4294967289,        '-7'),
      array(4200000080, '-94967216'),
      array(4200000066,   -94967230),
    );
  }

  /**
  * @dataProvider providerSnmpSize64HighLow
  * @group numbers
  */
  public function testSnmpSize64HighLow($high, $low, $result)
  {
    $this->assertSame($result, snmp_size64_high_low($high, $low));
  }

  public function providerSnmpSize64HighLow()
  {
    return array(
      array(         0,           0, 0),
      array(     65000,           0, 279172874240000),
      array(   '65000',     '65000', 279172874305000),
      array(       '0',     '65000', 65000),
    );
  }

  /**
  * @dataProvider providerSnmpFixNumeric
  * @group numbers
  */
  public function testSnmpFixNumeric($value, $result)
  {
    $this->assertSame($result, snmp_fix_numeric($value));
  }

  public function providerSnmpFixNumeric()
  {
    return array(
      array(         0,           0),
      array(  '-65000',      -65000),
      array(        '',          ''),
      array(  'Some.0',    'Some.0'),
      array(     FALSE,       FALSE),
      array(4200000066,  4200000066),
      // Here numeric fixes
      array('"-7"',              -7),
      array('+7',                 7),
      array('  20,4',          20.4),
      array('4,200000067', 4.200000067),
      array('" -002.4336 dBm: Normal "', -2.4336),
      array('"66.1 C (151.0 F)"', 66.1),
      array('"36 C/96 F"', 36),
      array('"8232W"', 8232),
      array('"1628W (+/- 3.0%)"', 1628),
    );
  }

  /**
  * @dataProvider providerSnmpFixString
  * @group string
  */
  /* Needed real data for test
  public function testSnmpFixString($value, $result)
  {
    $this->assertSame($result, snmp_fix_string($value));
  }

  public function providerSnmpFixString()
  {
    return array(
      array("This is a &#269;&#x5d0; test&#39; &#250;", "This is a čא test' ú"),
      array("P<FA>lt stj<F3>rnst<F6><F0>",              "Púlt stjórnstöð"),
    );
  }
  */

  /**
  * @dataProvider providerSnmpStringToOid
  * @group index
  */
  public function testSnmpStringToOid($value, $result)
  {
    $this->assertSame($result, snmp_string_to_oid($value));
  }

  /**
  * @dataProvider providerSnmpStringToOid
  * @group index
  */
  public function testSnmpOidToString($result, $value)
  {
    $this->assertSame((string)$result, snmp_oid_to_string($value));
  }

  public function providerSnmpStringToOid()
  {
    return array(
      array(                     '',                                    '0'),
      array(                    ' ',                                 '1.32'),
      array(                      0,                                 '1.48'),
      array(               '-65000',                  '6.45.54.53.48.48.48'),
      array(               'Some.0',               '6.83.111.109.101.46.48'),
      array(            'Observium',  '9.79.98.115.101.114.118.105.117.109'),
      array('Observium Great Again',  '21.79.98.115.101.114.118.105.117.109.32.71.114.101.97.116.32.65.103.97.105.110'),
    );
  }

  /**
  * @dataProvider providerSnmpParseLine
  */
  public function testSnmpParseLine($flags, $value, $result)
  {
    $this->assertSame($result, snmp_parse_line($value, $flags));
  }

  public function providerSnmpParseLine()
  {
    $flags = OBS_SNMP_ALL;
    return array(
      array($flags,
            'jnxVpnPwLocalSiteId.l2Circuit."ge-0/1/1.0".621 = "some"',
            array('oid'       => 'jnxVpnPwLocalSiteId.l2Circuit."ge-0/1/1.0".621',
                  'value'     => 'some',
                  'oid_name'  => 'jnxVpnPwLocalSiteId',
                  'index_parts' => array('l2Circuit', 'ge-0/1/1.0', '621'),
                  'index_count' => 3,
                  'index'     => 'l2Circuit.ge-0/1/1.0.621',
            ),
      ),
      array($flags,
            'vacmSecurityModel.0."wes" = xxx',
            array('oid'       => 'vacmSecurityModel.0."wes"',
                  'value'     => 'xxx',
                  'oid_name'  => 'vacmSecurityModel',
                  'index_parts' => array('0', 'wes'),
                  'index_count' => 2,
                  'index'     => '0.wes',
            ),
      ),
      array($flags,
            'vacmSecurityModel.0.3.119.101.115 = xxx',
            array('oid'       => 'vacmSecurityModel.0.3.119.101.115',
                  'value'     => 'xxx',
                  'oid_name'  => 'vacmSecurityModel',
                  'index_parts' => array('0', '3', '119', '101', '115'),
                  'index_count' => 5,
                  'index'     => '0.3.119.101.115',
            ),
      ),
      array($flags | OBS_SNMP_CONCAT,
            'lldpRemChassisId.0.71.31591 = "08 2E 5F 17 E7 71 "',
            array('oid'       => 'lldpRemChassisId.0.71.31591',
                  'value'     => '08 2E 5F 17 E7 71 ',
                  'oid_name'  => 'lldpRemChassisId',
                  'index_parts' => array('0', '71', '31591'),
                  'index_count' => 3,
                  'index'     => '0.71.31591',
            ),
      ),
      array($flags | OBS_SNMP_TABLE,
            'ipv6RouteIfIndex[3ffe:100:ff00:0:0:0:0:0][64][1] = 2',
            array('oid'       => 'ipv6RouteIfIndex[3ffe:100:ff00:0:0:0:0:0][64][1]',
                  'value'     => '2',
                  'oid_name'  => 'ipv6RouteIfIndex',
                  'index_parts' => array('3ffe:100:ff00:0:0:0:0:0', '64', '1'),
                  'index_count' => 3,
                  'index'     => '3ffe:100:ff00:0:0:0:0:0.64.1',
            ),
      ),
      array($flags | OBS_SNMP_NUMERIC,
            '.1.3.6.1.2.1.1.3.0 = 15:09:27.63',
            array('oid'       => '.1.3.6.1.2.1.1.3.0',
                  'value'     => '15:09:27.63',
                  'index_count' => 1,
                  'index'     => '.1.3.6.1.2.1.1.3.0',
            ),
      ),
      // Not used, but allowed:
      array($flags,
            'vacmSecurityModel.0.\"wes\" = xxx', // not used
            array('oid'       => 'vacmSecurityModel.0.\"wes\"',
                  'value'     => 'xxx',
                  'oid_name'  => 'vacmSecurityModel',
                  'index_parts' => array('0', '\"wes\"'),
                  'index_count' => 2,
                  'index'     => '0.\"wes\"',
            ),
      ),
      // WARNING for cleaners, here trailing spaces!!!
      array($flags,
            'vacmSecurityModel.0."wes" = 00 66 
            AA BB ', // not used
            array('oid'       => 'vacmSecurityModel.0."wes"',
                  'value'     => '00 66 
            AA BB',
                  'oid_name'  => 'vacmSecurityModel',
                  'index_parts' => array('0', 'wes'),
                  'index_count' => 2,
                  'index'     => '0.wes',
            ),
      ),

      // Empty, wrong
      array($flags,
            'jnxVpnPwLocalSiteId.l2Circuit."ge-0/1/1.0".621', // Complete not exist value part
            array('oid'       => 'jnxVpnPwLocalSiteId.l2Circuit."ge-0/1/1.0".621',
                  'value'     => NULL,
                  'oid_name'  => 'jnxVpnPwLocalSiteId',
                  'index_parts' => array('l2Circuit', 'ge-0/1/1.0', '621'),
                  'index_count' => 3,
                  'index'     => 'l2Circuit.ge-0/1/1.0.621',
            ),
      ),
      array($flags,
            'jnxVpnPwLocalSiteId.l2Circuit."ge-0/1/1.0".621 = ', // Value is string ''
            array('oid'       => 'jnxVpnPwLocalSiteId.l2Circuit."ge-0/1/1.0".621',
                  'value'     => '',
                  'oid_name'  => 'jnxVpnPwLocalSiteId',
                  'index_parts' => array('l2Circuit', 'ge-0/1/1.0', '621'),
                  'index_count' => 3,
                  'index'     => 'l2Circuit.ge-0/1/1.0.621',
            ),
      ),
      array($flags, // Yah, numeric passed without OBS_SNMP_NUMERIC flag
            '.1.3.6.1.2.1.1.3.0 = 15:09:27.63',
            array('oid'       => '.1.3.6.1.2.1.1.3.0',
                  'value'     => '15:09:27.63',
                  'oid_name' => '',
                  'index_parts' => array('1', '3', '6', '1', '2', '1', '1', '3', '0'),
                  'index_count' => 9,
                  'index'     => '1.3.6.1.2.1.1.3.0',
            ),
      ),
      array($flags | OBS_SNMP_NUMERIC,
            ' = 15:09:27.63',
            array('oid'       => '',
                  'value'     => '15:09:27.63',
                  'index_count' => 0,
                  'index'     => '',
            ),
      ),
      array($flags,
            '',
            array('oid'       => '',
                  'value'     => NULL,
                  'oid_name'  => '',
                  'index_parts' => array(),
                  'index_count' => 0,
                  'index'     => '',
            ),
      ),
    );
  }
}

// EOF
