<?php


    // Poll mem for load memory utilisation stats on UNIX-like hosts running UCD/Net-SNMPd
    #UCD-SNMP-MIB::memIndex.0 = INTEGER: 0
    #UCD-SNMP-MIB::memErrorName.0 = STRING: swap
    #UCD-SNMP-MIB::memTotalSwap.0 = INTEGER: 32762248 kB
    #UCD-SNMP-MIB::memAvailSwap.0 = INTEGER: 32199396 kB
    #UCD-SNMP-MIB::memTotalReal.0 = INTEGER: 8187696 kB
    #UCD-SNMP-MIB::memAvailReal.0 = INTEGER: 1211056 kB
    #UCD-SNMP-MIB::memTotalFree.0 = INTEGER: 33410452 kB
    #UCD-SNMP-MIB::memMinimumSwap.0 = INTEGER: 16000 kB
    #UCD-SNMP-MIB::memBuffer.0 = INTEGER: 104388 kB
    #UCD-SNMP-MIB::memCached.0 = INTEGER: 2595556 kB
    #UCD-SNMP-MIB::memShared.0 = INTEGER: 595556 kB
    #UCD-SNMP-MIB::memSwapError.0 = INTEGER: noError(0)
    #UCD-SNMP-MIB::memSwapErrorMsg.0 = STRING:

    $snmpdata = snmpwalk_cache_oid($device, "mem", array(), "UCD-SNMP-MIB");
    $data = $snmpdata[0];

    if(is_array($data) && isset($data['memTotalReal']) && isset($data['memBuffer']) && isset($data['memCached']) && isset($data['memShared']))
    {
      $total = $data['memTotalReal'] * 1024;
      $free  = ($data['memFree'] + ($data['memBuffer'] + $data['memCached'])) * 1024;
      $used  = $total - $free;
      $perc  = $free / $total * 100;

      $index = '0';
      $descr = 'Physical memory';

      discover_mempool($valid['mempool'], $device, $index, 'UCD-SNMP-MIB', $descr, "1", $total, $used);
    }
