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

// FIXME -- this code sucks, what the fuck. -- adama
// FIXME, really sucks, REWRITE this!

if ($device['type'] == 'wireless' && $device['os'] == 'arubaos')
{
  global $config;

  echo("Aruba Controller: ");

  // Build array of APs in the database

  $query  = 'SELECT *, A.`accesspoint_id` AS `accesspoint_id` FROM `accesspoints` AS A';
  $query .= ' LEFT JOIN `accesspoints-state` AS S ON A.`accesspoint_id` = S.`accesspoint_id`';
  $query .= ' WHERE `device_id` = ?';

  $aps_db = dbFetchRows($query, array($device['device_id']));
  foreach ($aps_db as $ap) { $aps[$ap['mac_addr']][$ap['radio_number']] = $ap; }

  unset($aps_db); unset($ap);

  /// Build SNMP Cache Array

  //stuff about the controller
  $switch_info_oids = array('wlsxSwitchRole','wlsxSwitchMasterIp');
  $switch_counter_oids = array('wlsxSwitchTotalNumAccessPoints.0','wlsxSwitchTotalNumStationsAssociated.0');

  $switch_apinfo_oids = array('wlsxWlanRadioEntry','wlanAPChInterferenceIndex');
  $switch_apname_oids = array('wlsxWlanRadioEntry.16');
  $aruba_oids = array_merge($switch_info_oids,$switch_counter_oids);

  echo("Caching Oids: ");
  foreach ($aruba_oids as $oid) { echo("$oid "); $aruba_stats = snmpwalk_cache_oid($device, $oid, $aruba_stats, "WLSX-SWITCH-MIB"); }
  //FIXME, numeric not needed here, probably just numeric index
  foreach ($switch_apinfo_oids as $oid) { echo("$oid "); $aruba_apstats = snmpwalk_cache_oid($device, $oid, $aruba_apstats, "WLSX-WLAN-MIB", NULL, OBS_SNMP_ALL_NUMERIC); }
  foreach ($switch_apname_oids as $oid) { echo("$oid "); $aruba_apnames = snmpwalk_cache_oid($device, $oid, $aruba_apnames, "WLSX-WLAN-MIB", NULL, OBS_SNMP_ALL_NUMERIC); }

  echo("\n");

  rrdtool_update_ng($device, 'aruba-controller', array(
    'NUMAPS'     => $aruba_stats[0]['wlsxSwitchTotalNumAccessPoints'],
    'NUMCLIENTS' => $aruba_stats[0]['wlsxSwitchTotalNumStationsAssociated'],
  ));

  $graphs['arubacontroller_numclients'] = TRUE;
  $graphs['arubacontroller_numaps'] = TRUE;

  // also save the info about how many clients in the same place as the wireless module
  rrdtool_update_ng($device, 'wificlients', array('wificlients' => $aruba_stats[0]['wlsxSwitchTotalNumStationsAssociated']), 'radio1');
  $graphs['wifi_clients'] = TRUE;

  foreach ($aruba_apnames as $key => $value)
  {
    $radioid       = str_replace(".1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.16.","",$key);
    $name          = $value[''];
    $type          = $aruba_apstats[".1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.2.$radioid"];
    $channel       = $aruba_apstats[".1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.3.$radioid"]+0;
    $txpow         = $aruba_apstats[".1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.4.$radioid"]+0;
    $radioutil     = $aruba_apstats[".1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.6.$radioid"]+0;
    $numasoclients = $aruba_apstats[".1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.7.$radioid"]+0;
    $nummonclients = $aruba_apstats[".1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.8.$radioid"]+0;
    $numactbssid   = $aruba_apstats[".1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.9.$radioid"]+0;
    $nummonbssid   = $aruba_apstats[".1.3.6.1.4.1.14823.2.2.1.5.2.1.5.1.10.$radioid"]+0;
    $interference  = $aruba_apstats[".1.3.6.1.4.1.14823.2.2.1.5.3.1.6.1.11.$radioid"]+0;

    $radionum      = substr($radioid,strlen($radioid)-1,1);

    print_debug("* radioid: $radioid\n" .
                "  radionum: $radionum\n" .
                "  name: $name\n" .
                "  type: $type\n" .
                "  channel: $channel\n" .
                "  txpow: $txpow\n" .
                "  radioutil: $radioutil\n" .
                "  numasoclients: $numasoclients\n" .
                "  interference: $interference");

    // if there is a numeric channel, assume the rest of the data is valid, I guess
    if (is_numeric($channel))
    {
      rrdtool_update_ng($device, 'aruba-ap', array(
        'channel'       => $channel,
        'txpow'         => $txpow,
        'radioutil'     => $radioutil,
        'nummonclients' => $nummonclients,
        'nummonbssid'   => $nummonbssid,
        'numasoclients' => $numasocclients,
        'interference'  => $interference,
      ), "$name.$radionum");
    }

    //generate the mac address - FIXME surely this can be done cleaner?!
    $macparts = explode(".",$radioid,-1);
    $mac = ""; foreach ($macparts as $part) { $mac.=sprintf("%02x",$part).":"; } $mac=rtrim($mac,":");

    $foundid=0;

    if (is_array($aps[$mac][$radionum]))
    {
      $ap_id = $aps[$mac][$radionum]['accesspoint_id'];
      dbUpdate(array('mac_addr' => $mac, 'deleted' => 0 ), 'accesspoints', '`accesspoint_id` = ?', Array($foundid));
      echo(".");
    } else {
      $ap_id = dbInsert(array('device_id' => $device['device_id'], 'name' => $name,'radio_number'=>$radionum, 'type'=>$type,'mac_addr'=>$mac, 'deleted' => '0'), 'accesspoints');
      echo("+");
    }

    if ($aps[$mac][$radionum]['channel'] == NULL)
    {
       dbInsert(array('accesspoint_id' => $ap_id, 'channel'=>$channel,'txpow'=>$txpow,'radioutil'=>$radioutil,'numasoclients'=>$numasoclients,'nummonclients'=>$nummonclients,'numactbssid'=>$numactbssid,
                'nummonbssid'=>$nummonbssid,'interference'=>$interference), 'accesspoints-state');
    } else {
       dbUpdate(array('channel'=>$channel,'txpow'=>$txpow,'radioutil'=>$radioutil,'numasoclients'=>$numasoclients,'nummonclients'=>$nummonclients,
               'numactbssid'=>$numactbssid,'nummonbssid'=>$nummonbssid,'interference'=>$interference), 'accesspoints-state', '`accesspoint_id` = ?', Array($ap_id));
    }

    unset($aps[$mac][$radionum]);

  }



  //mark APs which are not on this controller anymore as deleted
  //learn to use foreach you fucking flaming spaztard! - adama
  for ($z=0;$z<sizeof($ap_db);$z++)
  {
    if (!isset($ap_db[$z]['seen']) && $ap_db[$z]['deleted']==0)
    {
      dbUpdate(array('deleted'=>1), 'accesspoints', '`accesspoint_id` = ?', Array($ap_db[$z]['accesspoint_id']));
    }
  }
}

// EOF
