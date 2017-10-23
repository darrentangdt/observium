<?php

/**
 * Observium Network Management and Monitoring System
 * Copyright (C) 2006-2015, Adam Armstrong - http://www.observium.org
 *
 * @package    observium
 * @subpackage syslog
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

// FIXME use db functions properly

// DOCME needs phpdoc block
// TESTME needs unit testing
function get_cache($host, $value)
{
  global $dev_cache;

  if (empty($host)) { return; }

  // Check cache expiration
  $now = time();
  $expired = TRUE;
  if (isset($dev_cache[$host]['lastchecked']))
  {
    if (($now - $dev_cache[$host]['lastchecked']) < 600) { $expired = FALSE; } // will expire after 10 min
  }
  if ($expired) { $dev_cache[$host]['lastchecked'] = $now; }

  if (!isset($dev_cache[$host][$value]) || $expired)
  {
    switch($value)
    {
      case 'device_id':
        // Try by map in config
        if (isset($GLOBALS['config']['syslog']['host_map'][$host]))
        {
          $new_host = $GLOBALS['config']['syslog']['host_map'][$host];
          if (is_numeric($new_host))
          {
            // Check if device id exist
            $dev_cache[$host]['device_id'] = dbFetchCell('SELECT `device_id` FROM `devices` WHERE `device_id` = ?', array($new_host));
          } else {
            $dev_cache[$host]['device_id'] = dbFetchCell('SELECT `device_id` FROM `devices` WHERE `hostname` = ? OR `sysName` = ?', array($new_host, $new_host));
          }
          // If syslog host map correct, return device id or try onward
          if ($dev_cache[$host]['device_id'])
          {
            return $dev_cache[$host]['device_id'];
          }
        }
        // Try by hostname
        $dev_cache[$host]['device_id'] = dbFetchCell('SELECT `device_id` FROM `devices` WHERE `hostname` = ? OR `sysName` = ?', array($host, $host));
        // If failed, try by IP
        if (!is_numeric($dev_cache[$host]['device_id']))
        {
          $ip = $host;

          $ip_version = get_ip_version($ip);
          if ($ip_version !== FALSE)
          {
            if ($ip_version == 6 && preg_match('/::ffff:(\d+\.\d+\.\d+\.\d+)/', $ip, $matches))
            {
              // IPv4 mapped to IPv6, like ::ffff:192.0.2.128
              // See: http://jira.observium.org/browse/OBSERVIUM-1274
              $ip = $matches[1];
              $ip_version = 4;
            }
            else if ($ip_version == 6)
            {
              $ip = Net_IPv6::uncompress($ip, TRUE);
            }
            $address_count = dbFetchCell('SELECT COUNT(*) FROM `ipv'.$ip_version.'_addresses` WHERE `ipv'.$ip_version.'_address` = ?;', array($ip));
            if ($address_count)
            {
              $query = 'SELECT `device_id` FROM `ipv'.$ip_version.'_addresses` AS A, `ports` AS I WHERE A.`ipv'.$ip_version.'_address` = ? AND I.`port_id` = A.`port_id`';
              // If more than one IP address, also check the status of the port.
              if ($address_count > 1) { $query .= " AND I.`ifOperStatus` = 'up'"; }
              $dev_cache[$host]['device_id'] = dbFetchCell($query, array($ip));
            }
          }
        }
        break;
      case 'os':
      case 'version':
        if ($device_id = get_cache($host, 'device_id'))
        {
          $dev_cache[$host][$value] = dbFetchCell('SELECT `'.$value.'` FROM `devices` WHERE `device_id` = ?', array($device_id));
        } else {
          return NULL;
        }
        break;
      case 'os_group':
        $os = get_cache($host, 'os');
        $dev_cache[$host]['os_group'] = (isset($GLOBALS['config']['os'][$os]['group']) ? $GLOBALS['config']['os'][$os]['group'] : '');
        break;
      default:
        return NULL;
    }
  }

  return $dev_cache[$host][$value];
}

function cache_syslog_rules()
{

  $rules = array();
  foreach(dbFetchRows("SELECT * FROM `syslog_rules` WHERE `la_disable` = '0'") as $lat)
  {
    $rules[$lat['la_id']] = $lat;
  }

  return $rules;

}

function cache_syslog_rules_assoc()
{
  $device_rules = array();
  foreach(dbFetchRows("SELECT * FROM `syslog_rules_assoc`") as $laa)
  {

    //print_r($laa);

    if($laa['entity_type'] == 'group')
    {
      $devices = get_group_entities($laa['entity_id']);
      foreach($devices as $dev_id)
      {
        $device_rules[$dev_id][$laa['la_id']] = TRUE;
      }
    } elseif($laa['entity_type'] == 'device')
    {
      $device_rules[$laa['entity_id']][$laa['la_id']] = TRUE;
    }
  }
  return $device_rules;
}


// DOCME needs phpdoc block
// TESTME needs unit testing
function process_syslog($entry, $update)
{
  global $config;
  global $rules;
  global $device_rules;
  global $maint;

  foreach ($config['syslog']['filter'] as $bi)
  {
    if (strpos($entry['msg'], $bi) !== FALSE)
    {
      //echo('D-'.$bi);
      return FALSE;
    }
  }

  $entry['msg_orig'] = $entry['msg'];

  // Initial rewrites
  $entry['host']      = strtolower(trim($entry['host']));

  // Rewrite priority and level from strings to numbers
  $entry['priority']  = priority_string_to_numeric($entry['priority']);
  $entry['level']     = priority_string_to_numeric($entry['level']);

  $entry['device_id'] = get_cache($entry['host'], 'device_id');
  //print_vars($entry);
  //print_vars($GLOBALS['dev_cache']);
  if ($entry['device_id'])
  {
    $os       = get_cache($entry['host'], 'os');
    $os_group = get_cache($entry['host'], 'os_group');

    if (in_array($os, array('ios', 'iosxe', 'catos', 'asa')))
    {
      $matches = array();
#      if (preg_match('#%(?P<program>.*):( ?)(?P<msg>.*)#', $entry['msg'], $matches)) {
#        $entry['msg'] = $matches['msg'];
#        $entry['program'] = $matches['program'];
#      }
#      unset($matches);

      //NOTE. Please include examples for syslog entries, to know why need some preg_replace()
      if (strstr($entry['msg'], '%'))
      {
        //10.0.0.210||23||4||4||26644:||2013-11-08 07:19:24|| 033884: Nov  8 07:19:23.993: %FW-4-TCP_OoO_SEG: Dropping TCP Segment: seq:-1169729434 1500 bytes is out-of-order; expected seq:3124765814. Reason: TCP reassembly queue overflow - session 10.10.32.37:56316 to 93.186.239.142:80 on zone-pair Local->Internet class All_Inspection||26644
        //hostname||17||5||5||192462650:||2014-06-17 11:16:01|| %SSH-5-SSH2_SESSION: SSH2 Session request from 10.95.0.42 (tty = 0) using crypto cipher 'aes256-cbc', hmac 'hmac-sha1' Succeeded||192462650
        if (strpos($entry['msg'], ': %'))
        {
          list(,$entry['msg']) = explode(': %', $entry['msg'], 2);
          $entry['msg'] = "%" . $entry['msg'];
        }
        $entry['msg'] = preg_replace("/^%(.+?):\ /", "\\1||", $entry['msg']);
      } else {
        $entry['msg'] = preg_replace("/^.*[0-9]:/", "", $entry['msg']);
        $entry['msg'] = preg_replace("/^[0-9][0-9]\ [A-Z]{3}:/", "", $entry['msg']);
        $entry['msg'] = preg_replace("/^(.+?):\ /", "\\1||", $entry['msg']);
      }
      //$entry['msg'] = preg_replace("/^.+\.[0-9]{3}:/", "", $entry['msg']); /// FIXME. Show which entries this should replace. It's broke all entries with 'IP:PORT'.
      $entry['msg'] = preg_replace("/^.+-Traceback=/", "Traceback||", $entry['msg']);

      list($entry['program'], $entry['msg']) = explode("||", $entry['msg'], 2);
      $entry['msg'] = preg_replace("/^[0-9]+:/", "", $entry['msg']);

      if (!$entry['program'])
      {
         $entry['msg'] = preg_replace("/^([0-9A-Z\-]+?):\ /", "\\1||", $entry['msg']);
         list($entry['program'], $entry['msg']) = explode("||", $entry['msg'], 2);
      }

      if (!$entry['msg']) { $entry['msg'] = $entry['program']; unset ($entry['program']); }
    }
    else if ($os == 'iosxr')
    {
      //1.1.1.1||23||5||5||920:||2014-11-26 17:29:48||RP/0/RSP0/CPU0:Nov 26 16:29:48.161 : bgp[1046]: %ROUTING-BGP-5-ADJCHANGE : neighbor 1.1.1.2 Up (VRF: default) (AS: 11111) ||920
      //1.1.1.2||23||6||6||253:||2014-11-26 17:30:21||RP/0/RSP0/CPU0:Nov 26 16:30:21.710 : SSHD_[65755]: %SECURITY-SSHD-6-INFO_GENERAL : Client closes socket connection ||253
      //1.1.1.3||local0||err||err||83||2015-01-14 07:29:45||oly-er-01 LC/0/0/CPU0:Jan 14 07:29:45.556 CET: pfilter_ea[301]: %L2-PFILTER_EA-3-ERR_IM_CAPS : uidb set  acl failed on interface Bundle-Ether1.1501.ip43696. (null) ||94795
      list(, $entry['msg']) = explode(': %', $entry['msg'], 2);
      list($entry['program'], $entry['msg']) = explode(' : ', $entry['msg'], 2);
    }
    else if (in_array($os, array('junos', 'junose')))
    {
      //1.1.1.1||9||6||6||/usr/sbin/cron[1305]:||2015-04-08 14:30:01|| (root) CMD (   /usr/libexec/atrun)||
      if (empty($entry['program']))
      {
        $entry['program'] = preg_replace('/\[\d+\]$/', '', rtrim($entry['tag'], ':')); // /usr/sbin/cron[1305]: -> /usr/sbin/cron
        $entry['program'] = end(explode('/', $entry['program'])); // /usr/sbin/cron -> cron
      }
      // FIXME, not sure about this messages, probably also parse program like for cisco?
      //1.1.1.1||3||4||4||mib2d[1230]:||2015-04-08 14:30:11|| SNMP_TRAP_LINK_DOWN: ifIndex 602, ifAdminStatus up(1), ifOperStatus down(2), ifName ge-0/1/0||mib2d
      //1.1.1.1||3||6||6||chassism[1210]:||2015-04-08 14:30:16|| ethswitch_eth_devstop: called for port ge-0/1/1||chassism
      //1.1.1.1||3||3||3||chassism[1210]:||2015-04-08 14:30:22|| ETH:if_ethgetinfo() returns error||chassism
    }
    else if ($os == 'linux' && get_cache($entry['host'], 'version') == 'Point')
    {
      // Cisco WAP200 and similar
      $matches = array();
      if (preg_match('#Log: \[(?P<program>.*)\] - (?P<msg>.*)#', $entry['msg'], $matches))
      {
        $entry['msg']     = $matches['msg'];
        $entry['program'] = $matches['program'];
      }
      unset($matches);

    }
    else if ($os_group == 'unix')
    {
      $matches = array();
      // User_CommonName/123.213.132.231:39872 VERIFY OK: depth=1, /C=PL/ST=Malopolska/O=VLO/CN=v-lo.krakow.pl/emailAddress=root@v-lo.krakow.pl
      if ($entry['facility'] == 'daemon' && preg_match('#/([0-9]{1,3}\.) {3}[0-9]{1,3}:[0-9]{4,} ([A-Z]([A-Za-z])+( ?)) {2,}:#', $entry['msg']))
      {
        $entry['program'] = 'OpenVPN';
      }
      // pop3-login: Login: user=<username>, method=PLAIN, rip=123.213.132.231, lip=123.213.132.231, TLS
      // POP3(username): Disconnected: Logged out top=0/0, retr=0/0, del=0/1, size=2802
      else if ($entry['facility'] == 'mail' && preg_match('/^(((pop3|imap)\-login)|((POP3|IMAP)\(.*\))):/', $entry['msg']))
      {
        $entry['program'] = 'Dovecot';
      }
      // pam_krb5(sshd:auth): authentication failure; logname=root uid=0 euid=0 tty=ssh ruser= rhost=123.213.132.231
      // pam_krb5[sshd:auth]: authentication failure; logname=root uid=0 euid=0 tty=ssh ruser= rhost=123.213.132.231
      else if (preg_match('/^(?P<program>(\S((\(|\[).*(\)|\])))):(?P<msg>.*)$/', $entry['msg'], $matches))
      {
        $entry['msg']     = $matches['msg'];
        $entry['program'] = $matches['program'];
      }
      // pam_krb5: authentication failure; logname=root uid=0 euid=0 tty=ssh ruser= rhost=123.213.132.231
      // diskio.c: don't know how to handle 10 request
      else if (preg_match('/^(?P<program>[^\s\(\[]*):\ (?P<msg>.*)$/', $entry['msg'], $matches))
      {
        $entry['msg']     = $matches['msg'];
        $entry['program'] = $matches['program'];
      }
      // Wed Mar 26 12:54:17 2014 : Auth: Login incorrect (mschap: External script says Logon failure (0xc000006d)): [username] (from client 10.100.1.3 port 0 cli a4c3612a4077 via TLS tunnel)
      else if (!empty($entry['program']) && preg_match('/^.*:\ '.$entry['program'].':\ (?P<msg>[^(]+\((?P<program>[^:]+):.*)$/', $entry['msg'], $matches))
      {
        $entry['msg']     = $matches['msg'];
        $entry['program'] = $matches['program'];
      }
      // SYSLOG CONNECTION BROKEN; FD='6', SERVER='AF_INET(123.213.132.231:514)', time_reopen='60'
      // fallback, better than nothing...
      else if (empty($entry['program']) && !empty($entry['facility']))
      {
        $entry['program'] = $entry['facility'];
      }
      // 1.1.1.1||5||3||3||rsyslogd-2039:||2016-10-06 23:03:27|| Could no open output pipe '/dev/xconsole': No such file or directory [try http://www.rsyslog.com/e/2039 ]||rsyslogd-2039
      $entry['program'] = preg_replace('/\-\d+$/', '', $entry['program']);
      unset($matches);
    }
    else if ($os == 'ftos')
    {
      if (empty($entry['program']))
      {
        //1.1.1.1||23||5||5||||2014-11-23 21:48:10|| Nov 23 21:48:10.745: hostname: %STKUNIT0-M:CP %SEC-5-LOGOUT: Exec session is terminated for user rancid on line vty0||
        list(,, $entry['program'], $entry['msg']) = explode(': ', $entry['msg'], 4);
        list(, $entry['program']) = explode(' %', $entry['program'], 2);
      }
      //Jun 3 02:33:23.489: %STKUNIT0-M:CP %SNMP-3-SNMP_AUTH_FAIL: SNMP Authentication failure for SNMP request from host 176.10.35.241
      //Jun 1 17:11:50.806: %STKUNIT0-M:CP %ARPMGR-2-MAC_CHANGE: IP-4-ADDRMOVE: IP address 11.222.30.53 is moved from MAC address 52:54:00:7b:37:ad to MAC address 52:54:00:e4:ec:06 .
      //if (strpos($entry['msg'], '%STKUNIT') === 0)
      //{
      //  list(, $entry['program'], $entry['msg']) = explode(': ', $entry['msg'], 3);
      //  //$entry['timestamp'] = date("Y-m-d H:i:s", strtotime($entry['timestamp'])); // convert to timestamp
      //  list(, $entry['program']) = explode(' %', $entry['program'], 2);
      //}
    }
    else if ($os == 'netscaler')
    {
      //10/03/2013:16:49:07 GMT dk-lb001a PPE-4 : UI CMD_EXECUTED 10367926 : User so_readonly - Remote_ip 10.70.66.56 - Command "stat lb vserver" - Status "Success"
      list(,,,$entry['msg']) = explode(' ', $entry['msg'], 4);
      list($entry['program'], $entry['msg']) = explode(' : ', $entry['msg'], 3);
    }
    else if (str_starts($entry['program'], '(') && str_contains($entry['msg'], ': '))
    {
      // Ubiquiti Unifi devices
      // Wtf is BZ2LR and BZ@..
      /**
       *Old:  10.10.34.10||3||6||6||hostapd:||2014-07-18 11:29:35|| ath2: STA c8:dd:c9:d1:d4:aa IEEE 802.11: associated||hostapd
       *New:  10.10.34.10||3||6||6||(BZ2LR,00272250c1cd,v3.2.5.2791)||2014-12-12 09:36:39|| hostapd: ath2: STA dc:a9:71:1b:d6:c7 IEEE 802.11: associated||(BZ2LR,00272250c1cd,v3.2.5.2791)
       *New2: 10.10.34.11||1||6||6||("BZ2LR,00272250c119,v3.7.8.5016")||2016-10-06 18:20:25|| syslog: wevent.ubnt_custom_event(): EVENT_STA_LEAVE ath0: dc:a9:71:1b:d6:c7 / 3||("BZ2LR,00272250c119,v3.7.8.5016")
       *      10.10.34.7||1||6||6||("U7LR,44d9e7f618f2,v3.7.17.5220")||2016-10-06 18:21:22|| libubnt[16915]: wevent.ubnt_custom_event(): EVENT_STA_JOIN ath0: fc:64:ba:c1:7d:28 / 1||("U7LR,44d9e7f618f2,v3.7.17.5220")
       */
      list($entry['program'], $entry['msg']) = explode(': ', $entry['msg'], 2);
      $entry['program'] = preg_replace('/\[\d+\]$/', '', $entry['program']);
    }

    if ($entry['program'] == '')
    {
      /** FIXME, WHAT? Pls examples.
       $entry['program'] = $entry['msg'];
       unset($entry['msg']);
       */
      if ($entry['msg'] == '')
      {
        // Something wrong, both program and msg empty
        return $entry;
      }
    }

    $entry['program'] = strtoupper($entry['program']);
    array_walk($entry, 'trim');

    if ($update)
    {
      $log_id = dbInsert(
        array(
          'device_id' => $entry['device_id'],
          'host'      => $entry['host'],
          'program'   => $entry['program'],
          'facility'  => $entry['facility'],
          'priority'  => $entry['priority'],
          'level'     => $entry['level'],
          'tag'       => $entry['tag'],
          'msg'       => $entry['msg'],
          'timestamp' => $entry['timestamp']
        ),
        'syslog'
      );
    }

//$req_dump = print_r(array($entry, $rules, $device_rules), TRUE);
//$fp = fopen('/tmp/syslog.log', 'a');
//fwrite($fp, $req_dump);
//fclose($fp);

    $notification_type = 'syslog';

      /// FIXME, I not know how 'syslog_rules_assoc' is filled, I pass rules to all devices
      /// FIXME, this is copy-pasted from above, while not have WUI for syslog_rules_assoc
      foreach ($rules as $la_id => $rule)
      {
        if ((empty($device_rules) || isset($device_rules[$entry['device_id']][$la_id])) && preg_match($rule['la_rule'], $entry['msg_orig']))
        {

          // Mark no notification during maintenance
          if(isset($maint['device'][$entry['device_id']]) || (isset($maint['global']) && $maint['global'] > 0)) { $notified = '-1'; } else { $notified = '0'; }

          $log_id = dbInsert(array('device_id' => $entry['device_id'],
                                   'la_id'     => $la_id,
                                   'syslog_id' => $log_id,
                                   'timestamp' => $entry['timestamp'],
                                   'program'   => $entry['program'],
                                   'message'   => $entry['msg_orig'],
                                   'notified'  => $notified), 'syslog_alerts');

          // Add notification to queue
          if ($notified !='-1')
          {
            $device = device_by_id_cache($entry['device_id']);
            $message_tags = array(
                'ALERT_STATE'     => "SYSLOG",
                'ALERT_URL'       => generate_url(array('page'        => 'device',
                                                        'device'      => $device['device_id'],
                                                        'tab'         => 'alert',
                                                        'entity_type' => 'syslog')),
                'ALERT_ID'        => $la_id,
                'ALERT_MESSAGE'   => $rule['la_descr'],
                'CONDITIONS'      => $rule['la_rule'],
                'METRICS'         => $entry['msg'],
                'SYSLOG_RULE'     => $rule['la_rule'],
                'SYSLOG_MESSAGE'  => $entry['msg'],
                'SYSLOG_PROGRAM'  => $entry['program'],
                'TIMESTAMP'       => $entry['timestamp'],
                'DEVICE_HOSTNAME' => $device['hostname'],
                'DEVICE_LINK'     => generate_device_link($device),
                'DEVICE_HARDWARE' => $device['hardware'],
                'DEVICE_OS'       => $device['os_text'] . ' ' . $device['version'] . ' ' . $device['features'],
                'DEVICE_LOCATION' => $device['location'],
                'DEVICE_UPTIME'   => deviceUptime($device)
            );
            $message_tags['TITLE'] = alert_generate_subject($device, 'SYSLOG', $message_tags);

              // Get contacts for $la_id
              $contacts = get_alert_contacts($entry['device_id'], $la_id, $notification_type);

              foreach($contacts AS $contact) {

                $notification = array(
                    'device_id'             => $entry['device_id'],
                    'log_id'                => $log_id,
                    'aca_type'              => $notification_type,
                    'severity'              => $entry['priority'],
                    'endpoints'             => json_encode($contact),
                    //'message_graphs'        => $message_tags['ENTITY_GRAPHS_ARRAY'],
                    'notification_added'    => time(),
                    'notification_lifetime' => 300,                   // Lifetime in seconds
                    'notification_entry'    => json_encode($entry),   // Store full alert entry for use later if required (not sure that this needed)
                );
                //unset($message_tags['ENTITY_GRAPHS_ARRAY']);
                $notification['message_tags'] = json_encode($message_tags);
                $notification_id = dbInsert($notification, 'notifications_queue');
            } // End foreach($contacts)
          } // End if($notified)
        }  // End if syslog rule matches
      } // End foreach($rules)

    unset($os);
  }
  else if ($config['syslog']['unknown_hosts'])
  {
    if ($update)
    {
      array_walk($entry, 'trim');

      // Store entries for unknown hosts with NULL device_id
      $log_id = dbInsert(
        array(
          //'device_id' => $entry['device_id'], // Default is NULL
          'host'      => $entry['host'],
          'program'   => $entry['program'],
          'facility'  => $entry['facility'],
          'priority'  => $entry['priority'],
          'level'     => $entry['level'],
          'tag'       => $entry['tag'],
          'msg'       => $entry['msg'],
          'timestamp' => $entry['timestamp']
        ),
        'syslog'
      );
      //var_dump($entry);
    }
  }

  return $entry;
}

// EOF
