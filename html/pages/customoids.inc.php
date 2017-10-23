<?php

/**
 * Observium Network Management and Monitoring System
 * Copyright (C) 2006-2015, Adam Armstrong - http://www.observium.org
 *
 * @package    observium
 * @subpackage webui
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

// Custom OID display and editing

// Only show page to global read or above
if ($_SESSION['userlevel'] < 5)
{
  print_error_permission();
  return;
}

include("includes/customoids-navbar.inc.php");

?>

<div class="row">
<div class="col-md-12">

<?php

$oids = dbFetchRows("SELECT * FROM `oids`");

if (count($oids))
{
  // We have customoids, print the table.
  echo generate_box_open();
?>

<table class="table table-condensed table-striped<?php echo ($vars['graphs'] ? '-two' : ''); ?> table-hover">
  <thead>
    <tr>
    <th style="width: 1px"></th>
    <th>Description</th>
    <th style="width: 250px">OID</th>
    <th style="width: 250px">Name</th>
    <th style="width: 70px">Type</th>
    <th style="width: 100px">Unit</th>
    <th style="width: 100px">Thresholds</th>
    <th style="width: 70px">Devices</th>
    <th style="width: 60px">Discovery</th>
    <th style="width: 32px"></th>
    </tr>
  </thead>
  <tbody>

<?php

  foreach ($oids as $oid)
  {

    $thresholds = threshold_string($oid['oid_alert_low'], $oid['oid_warn_low'], $oid['oid_warn_high'], $oid['oid_alert_high'], $oid['oid_symbol']);

    //$sql  = "SELECT *";
    //$sql .= " FROM  `oids_entries`";
    //$sql .= " LEFT JOIN `devices` USING(`device_id`)";
    //$sql .= " WHERE `oid_id` = ?";

    //$assocs = dbFetchRows($sql, array($oid['oid_id']));

    $count = dbFetchCell('SELECT COUNT(*) FROM `oids_entries` WHERE `oid_id` = ?', array($oid['oid_id']));;

    echo '    <tr>';
    echo '      <td></td>';
    echo '      <td><strong><a href="'.generate_url(array('page' => 'customoid', 'oid_id' => $oid['oid_id'])).'">'.$oid['oid_descr'].'</a></strong></td>';
    echo '      <td>'.$oid['oid'].'</td>';
    echo '      <td>'.$oid['oid_name'].'</td>';
    echo '      <td><span class="label label-'.($oid['oid_type'] == "GAUGE" ? "success" : "error").'">'.$oid['oid_type'].'</span></td>';
    echo '      <td>'.$oid['oid_unit'].'</td>';
    echo '      <td>'.$thresholds.'</td>';
    echo '      <td><span class="label">'.$count.'</span></td>';
    echo '      <td>'.($oid['oid_autodiscover'] ? '<span class="label label-success">auto</span>' : '<span class="label label-disabled">off</span>').'</td>';
    echo '      <td>'; //<a href="#modal-customoid_del_' . $oid['oid_id'] . '" data-toggle="modal"><i class="'.$config['icon']['cancel'].'"></i></a></td>';

    /* Begin delete custom oid */
    $form = array('type'       => 'simple',
                  'userlevel'  => 10,          // Minimum user level for display form
                  'id'         => 'customoid_del_' . $oid['oid_id'],
                  'style'      => 'display:inline;',
                 );
    $form['row'][0]['oid_id'] = array(
                                    'type'        => 'hidden',
                                    'value'       => $oid['oid_id']);
    $form['row'][0]['confirm_'.$oid['oid_id']] = array(
                                    'type'        => 'hidden',
                                    'value'       => 'confirm');

    $form['row'][99]['action'] = array(
                                    'type'        => 'submit',
                                    'icon_only'   => TRUE, // hide button styles
                                    'name'        => '',
                                    'icon'        => $config['icon']['cancel'],
                                    //'right'       => TRUE,
                                    //'class'       => 'btn-small',
                                    // confirmation dialog
                                    'attribs'     => array('data-toggle'            => 'confirm', // Enable confirmation dialog
                                                           'data-confirm-placement' => 'left',
                                                           'data-confirm-content'   => 'Delete Custom OID "'  . $oid['oid_descr'] . '" (Id: '. $oid['oid_id'] . ')?',
                                                          ),
                                    'value'       => 'delete_customoid');

    print_form($form);
    unset($form);
    /* End delete custom oid */

    echo '</td>';
    echo '    </tr>';


    if($vars['graphs'])
    {
      echo '<tr>';
      echo '  <td colspan=10>';
      $graph_array['id']     = $oid['oid_id'];
      $graph_array['type']   = 'multi-customoid_line';
      $graph_array['legend'] = 'no';
      print_graph_row($graph_array);
      echo '  </td>';
      echo '</tr>';
    }
  }

  echo '</table>';
  echo generate_box_close();

} // End IF oids

// EOF
