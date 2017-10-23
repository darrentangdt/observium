<?php

include(dirname(__FILE__) . '/../includes/defaults.inc.php');
//include(dirname(__FILE__) . '/../config.php'); // Do not include user editable config here
include(dirname(__FILE__) . '/data/test_definitions.inc.php'); // Fake definitions for testing
include(dirname(__FILE__) . '/../includes/definitions.inc.php');
include(dirname(__FILE__) . '/../includes/functions.inc.php');

/**
 * @backupGlobals disabled
 */
class IncludesFunctionsTest extends \PHPUnit\Framework\TestCase
{
  /**
  * @dataProvider providerEmail
  */
  public function testParseEmail($string, $result)
  {
    $this->assertSame($result, parse_email($string));
  }

  public function providerEmail()
  {
    return array(
        array('test@example.com',     array('test@example.com' => NULL)),
        array(' test@example.com ',   array('test@example.com' => NULL)),
        array('<test@example.com>',   array('test@example.com' => NULL)),
        array('<test@example.com> ',  array('test@example.com' => NULL)),
        array(' <test@example.com> ', array('test@example.com' => NULL)),

        array('Test Title <test@example.com>',      array('test@example.com' => 'Test Title')),
        array('Test Title<test@example.com>',       array('test@example.com' => 'Test Title')),
        array('"Test Title" <test@example.com>',    array('test@example.com' => 'Test Title')),
        array('"Test Title <test@example.com>',     array('test@example.com' => 'Test Title')),
        array('Test Title" <test@example.com>',     array('test@example.com' => 'Test Title')),
        array('" Test Title " <test@example.com>',  array('test@example.com' => 'Test Title')),
        array('\'Test Title\' <test@example.com>',  array('test@example.com' => 'Test Title')),

        array('"Test Title" <test@example.com>,"Test Title 2" <test2@example.com>',
              array('test@example.com' => 'Test Title', 'test2@example.com' => 'Test Title 2')),
        array('\'Test Title\' <test@example.com>, "Test Title 2" <test2@sub.example.com>',
              array('test@example.com' => 'Test Title', 'test2@sub.example.com' => 'Test Title 2')),

        array('example.com',                 FALSE),
        array('<example.com>',               FALSE),
        array('Test Title test@example.com', FALSE),
        array('Test Title <example.com>',    FALSE),
    );
  }

  /**
  * @dataProvider providerSiToScale
  */
  public function testSiToScale($units, $precision, $result)
  {
    $this->assertSame($result, si_to_scale($units, $precision));
  }

  public function providerSiToScale()
  {
    $results = array(
      array('yocto',  5, 1.0E-29),
      array('zepto', -6, 1.0E-21),
      array('atto',   9, 1.0E-27),
      array('femto',  8, 1.0E-23),
      array('pico',   0, 1.0E-12),
      array('nano',  -7, 1.0E-9),
      array('micro',  4, 1.0E-10),
      array('milli',  7, 1.0E-10),
      array('deci',   0, 0.1),
      array('units',  3, 0.001),
      array('deca',   0, 10),
      array('kilo',   2, 10),
      array('mega',  -2, 1000000),
      array('giga',  -1, 1000000000),
      array('tera',  -4, 1000000000000),
      array('peta',   4, 100000000000),
      array('exa',   -3, 1000000000000000000),
      array('zetta',  1, 1.0E+20),
      array('yotta',  7, 100000000000000000),
      array('',      -6, 1),
      array('test',   6, 1.0E-6),
      array('0',     -3, 1),
      array('5',      2, 1000),
      array('-1',     1, 0.01),
    );
    return $results;
  }

  /**
  * @dataProvider providerSiToScaleValue
  */
  public function testSiToScaleValue($value, $scale, $result)
  {
    $this->assertSame($result, $value * si_to_scale($scale));
  }

  public function providerSiToScaleValue()
  {
    return array(
      array('330',  '-2', 3.3),
      array('1194', '-2', 11.94),
      array('928',  NULL, 928),
      array('9',     '1', 90),
      array('22',    '0', 22),
      array('1194', 'milli', 1.194),
    );
  }

  /**
  * @dataProvider providerFloatCompare
  * @group numbers
  */
  public function testFloatCompare($a, $b, $epsilon, $result)
  {
    $this->assertSame($result, float_cmp($a, $b, $epsilon));
  }

  public function providerFloatCompare()
  {
    return array(
      // numeric tests
      array('330', '-2', NULL,  1), // $a > $b
      array('1',    '2', 0.1,  -1), // $a < $b
      array(-1,      -2, 0.1,   1), // $a > $b
      array(-1.1,  -1.4, 0.5,   0), // $a == $b
      array(-1.1,  -1.4, -0.5,  0), // $a == $b
      array((double)0, (double)70, 0.1, -1), // $a < $b and $a == 0
      array((double)70, (double)0, 0.1,  1), // $a > $b and $b == 0
      array((int)0.001, (double)0, NULL, 0), // $a == $b
      array(0.001,    0.000999999,  0.00001,  0), // $a == $b
      array(-0.001,  -0.000999999,  0.00001,  0), // $a == $b
      array(-0.001,  -0.000899999,  0.00001, -1), // $a < $b
      //array('-0.00000001', 0.00000002, NULL,  0), // $a == $b, FIXME, FALSE
      //array(0.00000002, '-0.00000001', NULL,  0), // $a == $b, FIXME, FALSE
      array(0.2, '-0.000000000001', NULL,  1), // $a == $b
      array(0.99999999, 1.00000002, NULL,  0), // $a == $b
      array(0.001,   -0.000999999,  NULL,  1), // $a > $b
      array(-0.000999999,   0.001,  NULL, -1), // $a < $b
      array(3672,   3888,           0.05,  0), // big numbers, greater epsilon
      array(3888,   3672,           0.05,  0), // big numbers, greater epsilon
      array(4000,   4810,            0.1,  0), // big numbers, greater epsilon
      array(4000,   4000.01,        NULL,  0), // big numbers

      /* Regular large numbers */
      array(1000000,      1000001,  NULL,  0),
      array(1000001,      1000000,  NULL,  0),
      array(10000,          10001,  NULL, -1),
      array(10001,          10000,  NULL,  1),
      /* Negative large numbers */
      array(-1000000,    -1000001,  NULL,  0),
      array(-1000001,    -1000000,  NULL,  0),
      array(-10000,        -10001,  NULL,  1),
      array(-10001,        -10000,  NULL, -1),
      /* Numbers around 1 */
      array(1.0000001,  1.0000002,  NULL,  0),
      array(1.0000002,  1.0000001,  NULL,  0),
      array(1.0002,        1.0001,  NULL,  1),
      array(1.0001,        1.0002,  NULL, -1),
      /* Numbers around -1 */
      array(-1.0000001,-1.0000002,  NULL,  0),
      array(-1.0000002,-1.0000001,  NULL,  0),
      array(-1.0002,      -1.0001,  NULL, -1),
      array(-1.0001,      -1.0002,  NULL,  1),
      /* Numbers between 1 and 0 */
      array(0.000000001000001,   0.000000001000002,  NULL,  0),
      array(0.000000001000002,   0.000000001000001,  NULL,  0),
      array(0.000000000001002,   0.000000000001001,  NULL,  1),
      array(0.000000000001001,   0.000000000001002,  NULL, -1),
      /* Numbers between -1 and 0 */
      array(-0.000000001000001, -0.000000001000002,  NULL,  0),
      array(-0.000000001000002, -0.000000001000001,  NULL,  0),
      array(-0.000000000001002, -0.000000000001001,  NULL, -1),
      array(-0.000000000001001, -0.000000000001002,  NULL,  1),
      /* Comparisons involving zero */
      array(0.0,              0.0,  NULL,  0),
      array(0.0,             -0.0,  NULL,  0),
      array(-0.0,            -0.0,  NULL,  0),
      array(0.00000001,       0.0,  NULL,  1),
      array(0.0,       0.00000001,  NULL, -1),
      array(-0.00000001,      0.0,  NULL, -1),
      array(0.0,      -0.00000001,  NULL,  1),

      array(0.0,     1.0E-10,        0.1,  0),
      array(1.0E-10,     0.0,        0.1,  0),
      array(1.0E-10,     0.0, 0.00000001,  1),
      array(0.0,     1.0E-10, 0.00000001, -1),

      array(0.0,    -1.0E-10,        0.1,  0),
      array(-1.0E-10,    0.0,        0.1,  0),
      array(-1.0E-10,    0.0, 0.00000001, -1),
      array(0.0,    -1.0E-10, 0.00000001,  1),
      /* Comparisons of numbers on opposite sides of 0 */
      array(1.000000001, -1.0,  NULL,  1),
      array(-1.0,   1.0000001,  NULL, -1),
      array(-1.000000001, 1.0,  NULL, -1),
      array(1.0, -1.000000001,  NULL,  1),
      /* Comparisons involving extreme values (overflow potential) */
      array(PHP_INT_MAX,  PHP_INT_MAX,  NULL,  0),
      array(PHP_INT_MAX, -PHP_INT_MAX,  NULL,  1),
      array(-PHP_INT_MAX, PHP_INT_MAX,  NULL, -1),
      array(PHP_INT_MAX,  PHP_INT_MAX / 2, NULL,  1),
      array(PHP_INT_MAX, -PHP_INT_MAX / 2, NULL,  1),
      array(-PHP_INT_MAX, PHP_INT_MAX / 2, NULL, -1),

      // other tests
      array('test',       'milli', 1.194,  1),
      array(array('NULL'),    '0',  0.01,  1),
      array(array('NULL'), array('NULL'), NULL, 0),
    );
  }

  /**
  * @dataProvider providerIsHexString
  * @group hex
  */
  public function testIsHexString($string, $result)
  {
    $this->assertSame($result, IsHexString($string));
  }

  public function providerIsHexString()
  {
    $results = array(
      array('49 6E 70 75 74 20 31 00 ', TRUE),
      array('49 6E 70 75 74 20 31 00',  TRUE),
      array('496E707574203100',         FALSE), // SNMP HEX string only with spaces!
      array('49 6E 70 75 74 20 31 0',   FALSE),
      array('Simple String',            FALSE),
      array('49 6E 70 75 74 20 31 0R ', FALSE)
    );
    return $results;
  }

  /**
  * @dataProvider providerSNMPHexString
  * @group hex
  */
  public function testSNMPHexString($string, $result)
  {
    $this->assertSame($result, snmp_hexstring($string));
  }

  public function providerSNMPHexString()
  {
    $results = array(
      // HEX strings
      array('49 6E 70 75 74 20 31 00 ', 'Input 1'),
      array('49 6E 70 75 74 20 31',     'Input 1'),
      array('4A 7D 34 3D',              'J}4='),
      array('73 70 62 2D    6F 66 66 2D 67 77', 'spb-off-gw'),
      array('32 35 00 ',                '25'),
      //Incorrect HEX strings
      array('496E707574203100',         '496E707574203100'), // SNMP HEX string only with spaces!
      array('49 6E 70 75 74 20 31 0',   '49 6E 70 75 74 20 31 0'),
      array('Simple String',            'Simple String'),
      array('49 6E 70 75 74 20 31 0R ', '49 6E 70 75 74 20 31 0R '),
    );
    return $results;
  }

  /**
  * @dataProvider providerStr2Hex
  * @group hex
  */
  public function testStr2Hex($string, $result)
  {
    $this->assertSame($result, str2hex($string));
  }

  public function providerStr2Hex()
  {
    $results = array(
      array(' ',              '20'),
      array('Input 1',        '496e7075742031'),
      array('J}4=',           '4a7d343d'),
      array('Simple String',  '53696d706c6520537472696e67'),
    );
    return $results;
  }

  /**
  * @dataProvider providerHex2IP
  * @group ip
  */
  public function testHex2IP($string, $result)
  {
    $this->assertSame($result, hex2ip($string));
  }

  public function providerHex2IP()
  {
    $results = array(
      // IPv4
      array('C1 9C 5A 26',  '193.156.90.38'),
      array('4a7d343d',     '74.125.52.61'),
      array('207d343d',     '32.125.52.61'),
      // IPv4 (converted to snmp string)
      array('J}4=',         '74.125.52.61'),
      array('J}4:',         '74.125.52.58'),
      // with newline
      array('
^KL=', '94.75.76.61'),
      // with first space char (possible for OBS_SNMP_CONCAT)
      array(' ^KL=',        '94.75.76.61'),
      array('  KL=',        '32.75.76.61'),
      array('    ',         '32.32.32.32'),
      // IPv6
      array('20 01 07 F8 00 12 00 01 00 00 00 00 00 05 02 72',  '2001:07f8:0012:0001:0000:0000:0005:0272'),
      array('20:01:07:F8:00:12:00:01:00:00:00:00:00:05:02:72',  '2001:07f8:0012:0001:0000:0000:0005:0272'),
      array('200107f8001200010000000000050272',                 '2001:07f8:0012:0001:0000:0000:0005:0272'),
      // Wrong data
      array('4a7d343dd',                        '4a7d343dd'),
      array('200107f800120001000000000005027',  '200107f800120001000000000005027'),
      array('193.156.90.38',                    '193.156.90.38'),
      array('Simple String',                    'Simple String'),
      array('',  ''),
      array(FALSE,  FALSE),
    );
    return $results;
  }

  /**
  * @dataProvider providerIp2Hex
  * @group ip
  */
  public function testIp2Hex($string, $separator, $result)
  {
    $this->assertSame($result, ip2hex($string, $separator));
  }

  public function providerIp2Hex()
  {
    $results = array(
      // IPv4
      array('193.156.90.38', ' ', 'c1 9c 5a 26'),
      array('74.125.52.61',  ' ', '4a 7d 34 3d'),
      array('74.125.52.61',   '', '4a7d343d'),
      // IPv6
      array('2001:07f8:0012:0001:0000:0000:0005:0272', ' ', '20 01 07 f8 00 12 00 01 00 00 00 00 00 05 02 72'),
      array('2001:7f8:12:1::5:0272',                   ' ', '20 01 07 f8 00 12 00 01 00 00 00 00 00 05 02 72'),
      array('2001:7f8:12:1::5:0272',                    '', '200107f8001200010000000000050272'),
      // Wrong data
      array('4a7d343dd',                       NULL, '4a7d343dd'),
      array('200107f800120001000000000005027', NULL, '200107f800120001000000000005027'),
      array('300.156.90.38',                   NULL, '300.156.90.38'),
      array('Simple String',                   NULL, 'Simple String'),
      array('',    NULL, ''),
      array(FALSE, NULL, FALSE),
    );
    return $results;
  }

  /**
  * @dataProvider providerGetIpVersion
  * @group ip
  */
  public function testGetIpVersion($string, $result)
  {
    $this->assertSame($result, get_ip_version($string));
  }

  public function providerGetIpVersion()
  {
    $results = array(
      // IPv4
      array('193.156.90.38',    4),
      array('32.125.52.61',     4),
      array('127.0.0.1',        4),
      array('0.0.0.0',          4),
      array('255.255.255.255',  4),
      // IPv6
      array('ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff',  6),
      array('2001:07f8:0012:0001:0000:0000:0005:0272',  6),
      array('2001:7f8:12:1::5:0272',                    6),
      array('::1',                                      6),
      array('::',                                       6),
      array('::ffff:192.0.2.128',                       6), // IPv4 mapped to IPv6
      array('2002:c000:0204::',                         6), // 6to4 address 192.0.2.4
      // Wrong data
      array('4a7d343dd',              FALSE),
      array('my.domain.name',         FALSE),
      array('256.156.90.38',          FALSE),
      array('1.1.1.1.1',              FALSE),
      array('2001:7f8:12:1::5:0272f', FALSE),
      array('gggg:7f8:12:1::5:272f',  FALSE),
      //array('2002::',                 FALSE), // 6to4 address, must be full
      array('',                       FALSE),
      array(FALSE,                    FALSE),
      // IP with mask also wrong!
      array('193.156.90.38/32',           FALSE),
      array('2001:7f8:12:1::5:0272/128',  FALSE),
    );
    return $results;
  }

  /**
  * @dataProvider providerParseBgpPeerIndex
  * @group bgp
  */
  public function testParseBgpPeerIndex($mib, $index, $result)
  {
    $peer = array();
    parse_bgp_peer_index($peer, $index, $mib);
    $this->assertSame($result, $peer);
  }

  public function providerParseBgpPeerIndex()
  {
    $results = array(
      // IPv4
      array('BGP4-V2-MIB-JUNIPER', '0.1.203.153.47.15.1.203.153.47.207',
            array('jnxBgpM2PeerRoutingInstance' => '0',
                  'jnxBgpM2PeerLocalAddrType'   => 'ipv4',
                  'jnxBgpM2PeerLocalAddr'       => '203.153.47.15',
                  'jnxBgpM2PeerRemoteAddrType'  => 'ipv4',
                  'jnxBgpM2PeerRemoteAddr'      => '203.153.47.207')),
      array('BGP4-V2-MIB-JUNIPER', '47.1.0.0.0.0.1.10.241.224.142',
            array('jnxBgpM2PeerRoutingInstance' => '47',
                  'jnxBgpM2PeerLocalAddrType'   => 'ipv4',
                  'jnxBgpM2PeerLocalAddr'       => '0.0.0.0',
                  'jnxBgpM2PeerRemoteAddrType'  => 'ipv4',
                  'jnxBgpM2PeerRemoteAddr'      => '10.241.224.142')),
      // IPv6
      array('BGP4-V2-MIB-JUNIPER', '0.2.32.1.4.112.0.20.0.101.0.0.0.0.0.0.0.2.2.32.1.4.112.0.20.0.101.0.0.0.0.0.0.0.1',
            array('jnxBgpM2PeerRoutingInstance' => '0',
                  'jnxBgpM2PeerLocalAddrType'   => 'ipv6',
                  'jnxBgpM2PeerLocalAddr'       => '2001:0470:0014:0065:0000:0000:0000:0002',
                  'jnxBgpM2PeerRemoteAddrType'  => 'ipv6',
                  'jnxBgpM2PeerRemoteAddr'      => '2001:0470:0014:0065:0000:0000:0000:0001')),
      // Wrong data
      //array('4a7d343dd',              FALSE),
    );
    return $results;
  }

  /**
  * @dataProvider providerStateStringToNumeric
  */
  public function testStateStringToNumeric($type, $value, $result)
  {
    $this->assertSame($result, state_string_to_numeric($type, $value));
  }

  public function providerStateStringToNumeric()
  {
    $results = array(
      array('mge-status-state',           'No', 2),
      array('mge-status-state',           'no', 2),
      array('mge-status-state',           'Banana', FALSE),
      array('inexistent-status-state',    'Vanilla', FALSE),
      array('radlan-hwenvironment-state', 'notFunctioning', 6),
      array('radlan-hwenvironment-state', 'notFunctioning ', 6),
      array('cisco-envmon-state',         'warning', 2),
      array('cisco-envmon-state',         'war ning', FALSE),
      array('powernet-sync-state',        'inSync', 1),
      // Numeric value
      array('cisco-envmon-state',         '2', 2),
      array('cisco-envmon-state',          2, 2),
      array('cisco-envmon-state',         '2.34', FALSE),
      array('cisco-envmon-state',          10, FALSE),
    );
    return $results;
  }

  /**
  * @dataProvider providerPriorityStringToNumeric
  */
  public function testPriorityStringToNumeric($value, $result)
  {
    $this->assertSame($result, priority_string_to_numeric($value));
  }

  public function providerPriorityStringToNumeric()
  {
    $results = array(
      // Named value
      array('emerg',    0),
      array('alert',    1),
      array('crit',     2),
      array('err',      3),
      array('warning',  4),
      array('notice',   5),
      array('info',     6),
      array('debug',    7),
      array('DeBuG',    7),
      // Numeric value
      array('0',        0),
      array('7',        7),
      array(8,          8),
      // Wrong value
      array('some',    15),
      array(array(),   15),
      array(0.1,       15),
      array('100',     15),
    );
    return $results;
  }

  /**
  * @dataProvider providerArrayMergeIndexed
  */
  public function testArrayMergeIndexed($result, $array1, $array2, $array3 = NULL)
  {

    if ($array3 == NULL)
    {
      $this->assertSame($result, array_merge_indexed($array1, $array2));
    } else {
      $this->assertSame($result, array_merge_indexed($array1, $array2, $array3));
    }
  }

  public function providerArrayMergeIndexed()
  {
    $results = array(
      array( // Simple 2 array test
        array( // Result
          1 => array('Test1' => 'Moo', 'Test2' => 'Foo', 'Test3' => 'Bar'),
          2 => array('Test1' => 'Baz', 'Test4' => 'Bam', 'Test2' => 'Qux'),
        ),
        array( // Array 1
          1 => array('Test1' => 'Moo'),
          2 => array('Test1' => 'Baz', 'Test4' => 'Bam'),
          ),
        array( // Array 2
          1 => array('Test2' => 'Foo', 'Test3' => 'Bar'),
          2 => array('Test2' => 'Qux'),
        ),
      ),
      array( // Simple 3 array test
        array( // Result
          1 => array('Test1' => 'Moo', 'Test2' => 'Foo', 'Test3' => 'Bar'),
          2 => array('Test1' => 'Baz', 'Test4' => 'Bam', 'Test2' => 'Qux'),
        ),
        array( // Array 1
          1 => array('Test1' => 'Moo'),
          2 => array('Test1' => 'Baz', 'Test4' => 'Bam'),
          ),
        array( // Array 2
          1 => array('Test2' => 'Foo'),
          2 => array('Test2' => 'Qux'),
        ),
        array( // Array 3
          1 => array('Test3' => 'Bar'),
          2 => array('Test2' => 'Qux'),
        ),
      array( // Partial overwrite by array 2
        array( // Result
          1 => array('Test1' => 'Moo', 'Test2' => 'Foo', 'Test3' => 'Bar'),
          2 => array('Test1' => 'Baz', 'Test4' => 'Bam', 'Test2' => 'Qux'),
        ),
        array( // Array 1
          1 => array('Test1' => 'Moo', 'Test2' => '000', 'Test3' => '666'),
          2 => array('Test1' => 'Baz', 'Test4' => 'Bam'),
          ),
        array( // Array 2
          1 => array('Test2' => 'Foo', 'Test3' => 'Bar'),
          2 => array('Test2' => 'Qux'),
        ),
      ),
      ),
    );

    return $results;
  }

  /**
  * @dataProvider providerMatchNetwork
  * @group ip
  */
  public function testMatchNetwork($result, $ip, $nets, $first = FALSE)
  {
    $this->assertSame($result, match_network($ip, $nets, $first));
  }

  public function providerMatchNetwork()
  {
    $nets1 = array('127.0.0.0/8', '192.168.0.0/16', '10.0.0.0/8', '172.16.0.0/12', '!172.16.6.7/32');
    $nets2 = array('fe80::/16', '!fe80:ffff:0:ffff:1:144:52:0/112', '192.168.0.0/16', '172.16.0.0/12', '!172.16.6.7/32');
    $nets3 = array('fe80::/16', 'fe80:ffff:0:ffff:1:144:52:0/112', '!fe80:ffff:0:ffff:1:144:52:0/112');
    $nets4 = array('172.16.0.0/12', '!172.16.6.7');
    $nets5 = array('fe80::/16', '!FE80:FFFF:0:FFFF:1:144:52:38');
    $nets6 = "I'm a stupid";

    $results = array(
      // Only IPv4 nets
      array(TRUE,  '127.0.0.1',  $nets1),
      array(FALSE, '1.1.1.1',    $nets1),       // not in ranges
      array(TRUE,  '172.16.6.6', $nets1),
      array(FALSE, '172.16.6.7', $nets1),       // excluded net
      array(TRUE,  '172.16.6.7', $nets1, TRUE), // excluded, but first match
      array(FALSE, '256.16.6.1', $nets1),       // wrong IP
      // Both IPv4 and IPv6
      array(FALSE, '1.1.1.1',    $nets2),
      array(TRUE,  '172.16.6.6', $nets2),
      array(TRUE,  'FE80:FFFF:0:FFFF:129:144:52:38', $nets2),
      array(FALSE, 'FE81:FFFF:0:FFFF:129:144:52:38', $nets2), // not in ranges
      array(FALSE, 'FE80:FFFF:0:FFFF:1:144:52:38',   $nets2), // excluded net
      // Only IPv6 nets
      array(FALSE, '1.1.1.1',    $nets3),
      array(FALSE, '172.16.6.6', $nets3),
      array(TRUE,  'FE80:FFFF:0:FFFF:129:144:52:38', $nets3),
      array(FALSE, 'FE81:FFFF:0:FFFF:129:144:52:38', $nets3),
      array(FALSE, 'FE80:FFFF:0:FFFF:1:144:52:38',   $nets3),
      array(TRUE,  'FE80:FFFF:0:FFFF:1:144:52:38',   $nets3, TRUE), // excluded, but first match
      // IPv4 net without mask
      array(TRUE,  '172.16.6.6', $nets4),
      array(FALSE, '172.16.6.7', $nets4),       // excluded net
      // IPv6 net without mask
      array(TRUE,  'FE80:FFFF:0:FFFF:129:144:52:38', $nets5),
      array(FALSE, 'FE81:FFFF:0:FFFF:129:144:52:38', $nets5),
      array(FALSE, 'FE80:FFFF:0:FFFF:1:144:52:38',   $nets5),
      array(TRUE,  'FE80:FFFF:0:FFFF:1:144:52:38',   $nets5, TRUE), // excluded, but first match
      // Are you stupid? YES :)
      array(FALSE, '172.16.6.6', $nets6),
      array(FALSE, 'FE80:FFFF:0:FFFF:129:144:52:38', $nets6),
    );
    return $results;
  }

  /**
  * @dataProvider providerStringTransform
  * @group string
  */
  public function testStringTransform($result, $string, $transformations)
  {
    $this->assertSame($result, string_transform($string, $transformations));
  }

  public function providerStringTransform()
  {
    $results = array(
      // Append
      array('Bananarama',     'Banana',          array(
                                                   array('action' => 'append', 'string' => 'rama')
                                                 )),
      array('Bananarama',     'Banana',          array(
                                                   array('action' => 'append', 'string' => 'ra'),
                                                   array('action' => 'append', 'string' => 'ma')
                                                 )),
      // Prepend
      array('Benga boys',     'boys',            array(
                                                   array('action' => 'prepend', 'string' => 'Benga ')
                                                 )),
      // Replace
      array('Observium',      'ObserverNMS',     array(
                                                   array('action' => 'replace', 'from' => 'erNMS', 'to' => 'ium')
                                                 )),
      array('ObserverNMS',    'ObserverNMS',     array(
                                                   array('action' => 'replace', 'from' => 'ernms', 'to' => 'ium')
                                                 )),
      // Case Insensitive Replace
      array('Observium',      'ObserverNMS',     array(
                                                   array('action' => 'ireplace', 'from' => 'erNMS', 'to' => 'ium')
                                                 )),
      array('Observium',      'ObserverNMS',     array(
                                                   array('action' => 'ireplace', 'from' => 'ernms', 'to' => 'ium')
                                                 )),
      // Regex Replace
      array('1.46.82', 'CS141-SNMP V1.46.82 161207', array(
                                                   array('action' => 'regex_replace', 'from' => '/CS1\d1\-SNMP V(\d\S+).*/', 'to' => '$1')
                                                 )),
      // Regex Replace
      array('1.46.82', 'CS141-SNMP V1.46.82 161207', array(
                                                   array('action' => 'preg_replace', 'from' => '/CS1\d1\-SNMP V(\d\S+).*/', 'to' => '$1')
                                                 )),
      // Regex Replace (not match)
      array('CS141-SNMP', 'CS141-SNMP',          array(
                                                   array('action' => 'preg_replace', 'from' => '/CS1\d1\-SNMP V(\d\S+).*/', 'to' => '$1')
                                                 )),
      // Trim
      array('OOObservium',    'oooOOObserviumo', array(
                                                   array('action' => 'trim', 'characters' => 'o')
                                                 )),
      // LTrim
      array('OOObserviumo',   'oooOOObserviumo', array(
                                                   array('action' => 'ltrim', 'characters' => 'o')
                                                 )),
      // RTrim
      array('oooOOObservium', 'oooOOObserviumo', array(
                                                   array('action' => 'rtrim', 'characters' => 'o')
                                                 )),
      // Timeticks
      array(15462419, '178:23:06:59.03', array(
                                                   array('action' => 'timeticks')
                                                 )),

      // Explode (defaults - delimiter: " ", index: first)
      array('1.6', '1.6 Build 13120415', array(
                                                   array('action' => 'explode')
                                                 )),
      array('1.6', '1.6 Build 13120415', array(
                                                   array('action' => 'explode', 'delimiter' => ' ', 'index' => 'first')
                                                 )),
      array('1.6', '1.6 Build 13120415', array(
                                                   array('action' => 'explode', 'delimiter' => ' ', 'index' => 0)
                                                 )),
      array('13120415', '1.6 Build 13120415', array(
                                                   array('action' => 'explode', 'delimiter' => ' ', 'index' => 'end')
                                                 )),
      array('Build', '1.6 Build 13120415', array(
                                                   array('action' => 'explode', 'delimiter' => ' ', 'index' => 1)
                                                 )),
      array('6 Build 13120415', '1.6 Build 13120415', array(
                                                   array('action' => 'explode', 'delimiter' => '.', 'index' => 1)
                                                 )),
      // (unknown index)
      array('1.6 Build 13120415', '1.6 Build 13120415', array(
                                                   array('action' => 'explode', 'delimiter' => '.', 'index' => 10)
                                                 )),


      // Combinations, to be done in exact order, including no-ops
      array('Observium',      'oooOOOKikkero',   array(
                                                   array('action' => 'trim', 'characters' => 'o'),
                                                   array('action' => 'ltrim', 'characters' => 'O'),
                                                   array('action' => 'rtrim', 'characters' => 'F'),
                                                   array('action' => 'replace', 'from' => 'Kikker', 'to' => 'ObserverNMS'),
                                                   array('action' => 'replace', 'from' => 'erNMS', 'to' => 'ium')
                                                 )),
    );
    return $results;
  }

  /**
  * @dataProvider providerIsPingable
  * @group network
  */
  public function testIsPingable($result, $hostname, $try_a = TRUE)
  {
    if (!is_executable($GLOBALS['config']['fping']))
    {
      // CentOS 6.8
      $GLOBALS['config']['fping']  = '/usr/sbin/fping';
      $GLOBALS['config']['fping6'] = '/usr/sbin/fping6';
    }
    $flags = OBS_DNS_ALL;
    if (!$try_a) { $flags = $flags ^ OBS_DNS_A; }
    $ping = isPingable($hostname, $flags);
    $ping = is_numeric($ping) && $ping > 0; // Function return random float number
    $this->assertSame($result, $ping);
  }

  public function providerIsPingable()
  {
    $array = array(
      array(TRUE,  'localhost'),
      array(TRUE,  '127.0.0.1'),
      array(FALSE, 'yohoho.i.butylka.roma'),
      array(FALSE, '127.0.0.1', FALSE), // Try ping IPv4 with IPv6 disabled
    );
    $cmd = $GLOBALS['config']['fping6'] . " -c 1 -q ::1 2>&1";
    exec($cmd, $output, $return); // Check if we have IPv6 support in current system
    if ($return === 0)
    {
      // IPv6 only
      //$array[] = array(TRUE,  'localhost', FALSE);
      $array[] = array(TRUE,  '::1',       FALSE);
      $array[] = array(FALSE, '::ffff',    FALSE);
      foreach (array('localhost', 'ip6-localhost') as $hostname)
      {
        // Debian used ip6-localhost instead localhost.. lol
        $ip = gethostbyname6($hostname, OBS_DNS_AAAA);
        if ($ip)
        {
          $array[] = array(TRUE,  $hostname, FALSE);
          //var_dump($hostname);
          break;
        }
      }
    }
    return $array;
  }

  /**
  * @dataProvider providerCalculateMempoolProperties
  * @group numbers
  */
  public function testCalculateMempoolProperties($scale, $used, $total, $free, $perc, $result)
  {
    $this->assertSame($result, calculate_mempool_properties($scale, $used, $total, $free, $perc));
  }

  public function providerCalculateMempoolProperties()
  {
    $results = array(
      array(  1, 123456789, 234567890, NULL, NULL, array('used' => 123456789,  'total' => 234567890,   'free' => 111111101,  'perc' => 52.63)), // Used + Total known
      array( 10, 123456789, 234567890, NULL, NULL, array('used' => 1234567890, 'total' => 2345678900,  'free' => 1111111010, 'perc' => 52.63)), // Used + Total known, scale factor 10
      array(0.5, 123456789, 234567890, NULL, NULL, array('used' => 61728394.5, 'total' => 117283945.0, 'free' => 55555550.5, 'perc' => 52.63)), // Used + Total known, scale factor 0.5

      array(  1, NULL, 1234567890, 1597590, NULL, array('used' => 1232970300,   'total' => 1234567890,   'free' => 1597590,   'perc' => 99.87)), // Total + Free known
      array(100, NULL, 1234567890, 1597590, NULL, array('used' => 123297030000, 'total' => 123456789000, 'free' => 159759000, 'perc' => 99.87)), // Total + Free known, scale factor 10
      array(0.5, NULL, 1234567890, 1597590, NULL, array('used' => 616485150.0,  'total' => 617283945.0,  'free' => 798795.0,  'perc' => 99.87)), // Total + Free known, scale factor 0.5

      array(  1, 13333337, 23333337, 10000000, NULL, array('used' => 13333337,  'total' => 23333337,   'free' => 10000000,    'perc' => 57.14)), // All known
      array( 10, 13333337, 23333337, 10000000, NULL, array('used' => 133333370, 'total' => 233333370,  'free' => 100000000,   'perc' => 57.14)), // All known, scale factor 10
      array(0.5, 13333337, 23333337, 10000000, NULL, array('used' => 6666668.5, 'total' => 11666668.5, 'free' => 5000000.0,   'perc' => 57.14)), // All known, scale factor 0.5

      array(  1, 123456789, NULL, 163840, NULL, array('used' => 123456789,   'total' => 123620629,   'free' => 163840,        'perc' => 99.87)), // Used + Free known
      array(100, 123456789, NULL, 163840, NULL, array('used' => 12345678900, 'total' => 12362062900, 'free' => 16384000,      'perc' => 99.87)), // Used + Free known, scale factor 100
      array(0.5, 123456789, NULL, 163840, NULL, array('used' => 61728394.5,  'total' => 61810314.5,  'free' => 81920.0,       'perc' => 99.87)), // Used + Free known, scale factor 0.5

      array(   1, NULL, 600000000, NULL, 30, array('used' => 180000000,    'total' => 600000000,    'free' => 420000000,      'perc' => 30)),    // Total + Percentage known
      array(1000, NULL, 600000000, NULL, 30, array('used' => 180000000000, 'total' => 600000000000, 'free' => 420000000000,   'perc' => 30)),    // Total + Percentage known, scale factor 1000
      array( 0.5, NULL, 600000000, NULL, 30, array('used' => 90000000.0,   'total' => 300000000.0,  'free' => 210000000.0,    'perc' => 30)),    // Total + Percentage known, scale factor 0.5

      array(  1, 1597590, 1234567890, NULL, NULL, array('used' => 1597590,  'total' => 1234567890,  'free' => 1232970300,     'perc' => 0.13)),  // Used + Total known
      array( 10, 1597590, 1234567890, NULL, NULL, array('used' => 15975900, 'total' => 12345678900, 'free' => 12329703000,    'perc' => 0.13)),  // Used + Total known, scale factor 10
      array(0.5, 1597590, 1234567890, NULL, NULL, array('used' => 798795.0, 'total' => 617283945.0, 'free' => 616485150.0,    'perc' => 0.13)),  // Used + Total known, scale factor 0.5

      array(  1, NULL, NULL, NULL, 57, array('used' => 57, 'total' => 100, 'free' => 43, 'perc' => 57)),  // Only percentage known
      array( 40, NULL, NULL, NULL, 23, array('used' => 23, 'total' => 100, 'free' => 77, 'perc' => 23)),  // Only percentage known, scale factor 40
      array(0.1, NULL, NULL, NULL, 16, array('used' => 16, 'total' => 100, 'free' => 84, 'perc' => 16)),  // Only percentage known, scale factor 0.1
    );
    return $results;
  }

  /**
  * @dataProvider providerCalculateMempoolPropertiesScale
  * @group numbers
  */
  public function testCalculateMempoolPropertiesScale($scale, $used, $total, $free, $perc, $options, $result)
  {
    $this->assertSame($result, calculate_mempool_properties($scale, $used, $total, $free, $perc, $options));
  }

  public function providerCalculateMempoolPropertiesScale()
  {
    $scale1 = array('scale_total' => 1024);
    $scale2 = array('scale_used'  => 2048);
    $scale3 = array('scale_free'  => 4096);

    $results = array(
      array(  1, 123456789, 234567890, NULL, NULL, $scale1, array('used' => 123456789,    'total' => 240197519360,  'free' => 240074062571,  'perc' =>     0.05)), // Used + Total known
      array( 10, 123456789, 234567890, NULL, NULL, $scale2, array('used' => 252839503872, 'total' => 2345678900,    'free' => -250493824972, 'perc' => 10778.95)), // Used + Total known, scale factor 10
      array(0.5, 123456789, 234567890, NULL, NULL, $scale3, array('used' => 61728394.5,   'total' => 117283945.0,   'free' => 55555550.5,    'perc' =>    52.63)), // Used + Total known, scale factor 0.5

      array(  1, NULL, 1234567890, 1597590, NULL, $scale1, array('used' => 1264195921770, 'total' => 1264197519360, 'free' => 1597590,       'perc' =>    100.0)), // Total + Free known
      array(100, NULL, 1234567890, 1597590, NULL, $scale2, array('used' => 123297030000,  'total' => 123456789000,  'free' => 159759000,     'perc' =>    99.87)), // Total + Free known, scale factor 10
      array(0.5, NULL, 1234567890, 1597590, NULL, $scale3, array('used' => -5926444695.0, 'total' => 617283945.0,   'free' => 6543728640,    'perc' =>  -960.08)), // Total + Free known, scale factor 0.5
    );
    return $results;
  }
}

// EOF
