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

$supply_data = dbFetchRows("SELECT * FROM `printersupplies` WHERE `device_id` = ?", array($device['device_id']));

foreach ($supply_data as $supply)
{
  echo("Checking " . $supply['supply_descr'] . " (" . nicecase($supply['supply_type']) . ")... ");

  $level = snmp_get($device, $supply['supply_oid'], "-OUqnv");
  if ($level == '-1')
  {
    // Unlimited
    $level = 100;
  }
  //else if ($level == '-3')
  //{
  //  $level = 1; // This is wrong SuppliesLevel (1%), but better than nothing
  //}
  if ($level >= 0)
  {
    $supplyperc = round($level / $supply['supply_capacity'] * 100);
  } else {
    $supplyperc = $level;
  }

  // CLEANME remove after r8500 but not before CE 2016/10
  // Compatibility with old filenames
  $supply_rrd = "toner-" . $supply['supply_index'] . ".rrd";
  switch ($supply['supply_type'])
  {
    case 'toner':
      $old_rrd = "toner-" . implode('.',array_splice(explode('.',$supply['supply_index']),1)) . ".rrd"; // toner-1.5.rrd (new) -> toner-5.rrd (old)
      if (rename_rrd($device, $old_rrd, $supply_rrd))
      {
        rrdtool_rename_ds($device, $supply_rrd, 'toner', 'level');
      }
      break;
    case 'opc':
    case 'transferunit':
      if (stristr($supply['supply_descr'], 'drum') !== FALSE)
      {
        if (stristr($supply['supply_descr'], 'cyan') !== FALSE)
        {
          $old_rrd = "drum-c.rrd";
        } else if (stristr($supply['supply_descr'], 'magenta') !== FALSE) {
          $old_rrd = "drum-m.rrd";
        } else if (stristr($supply['supply_descr'], 'magenta') !== FALSE) {
          $old_rrd = "drum-y.rrd";
        } else if (stristr($supply['supply_descr'], 'black') !== FALSE) {
          $old_rrd = "drum-k.rrd";
        } else {
          $old_rrd = "drum.rrd";
        }

        if (rename_rrd($device, $old_rrd, $supply_rrd))
        {
          rrdtool_rename_ds($device, $supply_rrd, 'drum', 'level');
        }
      } else if (stristr($supply['supply_descr'], 'transfer') !== FALSE) {
        $old_rrd = 'transferroller.rrd';
        rename_rrd($device, $old_rrd, $supply_rrd);
      }
      break;
    case 'wastetoner':
      $old_rrd = 'wastebox.rrd';
      rename_rrd($device, $old_rrd, $supply_rrd);
      break;
    case 'fuser':
      $old_rrd = 'fuser.rrd';
      rename_rrd($device, $old_rrd, $supply_rrd);
      break;
  }
  // END CLEANME

  echo($supplyperc . " %\n");

  rrdtool_update_ng($device, 'toner', array('level' => $supplyperc),  $supply['supply_index']);

  if ($supplyperc > $supply['supply_value'])
  {
    log_event('Printer supply ' . $supply['supply_descr'] . ' (type ' . nicecase($supply['supply_type']) . ') was replaced (new level: ' . $supplyperc . '%)', $device, 'toner', $supply['supply_id']);
  }

  dbUpdate(array('supply_value' => $supplyperc, 'supply_capacity' => $supply['supply_capacity']), 'printersupplies', '`supply_id` = ?', array($supply['supply_id']));

  check_entity('printersupply', $supply, array('supply_value' => $supplyperc));

  $graphs['printersupplies'] = TRUE;
}

// Old stuff, to replace?
$oid = get_dev_attrib($device, 'pagecount_oid');

if ($oid)
{
  echo("Checking page count... ");
  $pages = snmp_get($device, $oid, "-OUqnv");

  set_dev_attrib($device, "pagecounter", $pages);
  rrdtool_update_ng($device, 'pagecount', array('pagecount' => $pages));

  echo("$pages\n");
}

// EOF
