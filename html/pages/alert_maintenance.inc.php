<?php

/*
 * Observium Network Management and Monitoring System
 * Copyright (C) 2006-2015, Adam Armstrong - http://www.observium.org
 *
 * @package    observium
 * @subpackage webui
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

// Scheduled Maintenance Display and Addition

//r(cache_alert_maintenance());

//r(time());

// Only show page to global secure read or above
if ($_SESSION['userlevel'] < 7)
{
  print_error_permission();
  return;
}

  include($config['html_dir']."/includes/alerting-navbar.inc.php");
  include($config['html_dir']."/includes/maintenance-navbar.inc.php");

  if (isset($vars['maint_id']))
  {
    if ($maint = dbFetchRow("SELECT * FROM `alerts_maint` WHERE `maint_id` = ?", array($vars['maint_id'])))
    {
      $assocs = get_maintenance_associations($maint['maint_id']);
      //print_vars($assocs);
?>

<div class="row">
  <div class="col-md-7">

<?php

  $box_args['title']         = 'Maintenance Details';
  $box_args['header-border'] = TRUE;
  $box_args['padding']       = FALSE;
  //$box_args['header-controls'] = array('controls' => array('edit' => array('text' => 'Edit',
  //                                                                         'icon' => 'icon-edit',
  //                                                                         'anchor' => TRUE,
  //                                                                         'url'  => ($vars['view'] == "edit" ? generate_url($vars, array('view' => NULL)) : generate_url($vars, array('view' => 'edit'))),
  //                                                                      )));

  //echo generate_box_open($box_args);

  $form = array('type'      => 'horizontal',
                'id'        => 'edit_maintenance',
                'title'     => 'Maintenance Details',
                'url'       => generate_url(array('page' => 'alert_maintenance', 'maint_id' => $maint['maint_id'])),
               );

  $form['row'][1]['maint_name'] = array(
                                  'type'        => 'text',
                                  'name'        => 'Name',
                                  'live-search' => FALSE,
                                  //'width'       => '600px',
                                  'class'       => 'col-md-10 col-xs-11',
                                  'value'       => $maint['maint_name']);
  $form['row'][2]['maint_descr'] = array(
                                  'type'        => 'textarea',
                                  'name'        => 'Description',
                                  //'width'       => '600px',
                                  'class'       => 'col-md-10 col-xs-11',
                                  'rows'        => 12,
                                  'value'       => $maint['maint_descr']);
  $form['row'][3]['maint_time'] = array(
                                  'type'        => 'datetime',
                                  'name'        => 'Maintenance Period',
                                  'from'        => $maint['maint_start'],
                                  'to'          => $maint['maint_end']);
  $form['row'][4]['maint_global'] = array(
                                  'type'        => 'switch',
                                  'name'        => 'Global Maintenance',
                                  'value'       => $maint['maint_global']);
  $form['row'][30]['action']    = array(
                                  'type'        => 'submit',
                                  'name'        => 'Update Maintenance',
                                  'icon'        => $config['icon']['checked'],
                                  //'right'       => TRUE,
                                  'class'       => 'btn-success',
                                  'value'       => 'update_maintenance');

  print_form($form);
  unset($form);

  //echo generate_box_close($box_args);
?>

  </div>
  <div class="col-md-5">

<?php

  // Group association
  if (OBSERVIUM_EDITION != 'community')
  {
    echo generate_box_open(array('title' => 'Group Associations', 'header-border' => TRUE));
    if (count($assocs['group']))
    {
      echo('<table class="'. OBS_CLASS_TABLE_STRIPED .'">');

      foreach ($assocs['group'] as $group_id => $status)
      {
        $group = get_group_by_id($group_id);

        echo('<tr><td style="width: 1px;"></td>
                <td style="overflow: hidden;"><i class="'.$config['entities'][$group['entity_type']]['icon'].'"></i> '.generate_entity_link('group', $group).'
                <small>' . escape_html($group['group_descr']) . '</small></td>
                <td style="width: 25px;">');


        $form = array('type'       => 'simple',
                      //'userlevel'  => 10,          // Minimum user level for display form
                      'id'         => 'delete_group_'.$group['group_id'],
                      'style'      => 'display:inline;',
                     );
        $form['row'][0]['maint_id'] = array(
                                        'type'        => 'hidden',
                                        'value'       => $vars['maint_id']);
        $form['row'][0]['entity_id'] = array(
                                        'type'        => 'hidden',
                                        'value'       => $group['group_id']);
        $form['row'][0]['entity_type'] = array(
                                        'type'        => 'hidden',
                                        'value'       => 'group');

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
                                                               'data-confirm-content'   => 'Delete associated group "'.escape_html($group['group_name']).'"?',
                                                              ),
                                        'value'       => 'delete_maintenance_entity');

        print_form($form);
        unset($form);

        echo('</td>
           </tr>' . PHP_EOL);

      }
      echo('  </table>' . PHP_EOL);

    } else {
      echo('<p class="text-center text-warning bg-warning" style="padding: 10px; margin: 0px;"><strong>There are no groups currently associated with this maintenance</strong></p>');
    }

    $form_items['groups'] = array();
    foreach (get_type_groups() as $group)
    {
      if (!isset($assocs['group'][$group['group_id']]))
      {
        $form_items['groups'][$group['group_id']] = array('name' => $group['group_name'],
                                                          'icon' => $config['entities'][$group['entity_type']]['icon'],
                                                          'subtext' => $group['group_descr']);
      }
    }

    $form = array('type'       => 'simple',
                  //'userlevel'  => 10,          // Minimum user level for display form
                  'id'         => 'associate_groups',
                  'style'      => 'padding: 7px; margin: 0px;',
                  'right'      => TRUE,
                  );
    $form['row'][0]['maint_id'] = array(
                                      'type'        => 'hidden',
                                      'value'       => $vars['maint_id']);
    $form['row'][0]['entity_type'] = array(
                                      'type'        => 'hidden',
                                      'value'       => 'group');
    $form['row'][0]['entity_id'] = array(
                                      'type'        => 'multiselect',
                                      'name'        => 'Associate Groups',
                                      //'live-search' => FALSE,
                                      'width'       => '250px',
                                      //'right'       => TRUE,
                                      'values'      => $form_items['groups'],
                                      'value'       => $vars['entity_id']);
    $form['row'][0]['action'] = array(
                                      'type'        => 'submit',
                                      'name'        => 'Associate',
                                      'icon'        => $config['icon']['plus'],
                                      //'right'       => TRUE,
                                      'class'       => 'btn-primary',
                                      'value'       => 'add_maintenance_entity');

    $box_close['footer_content'] = generate_form($form);
    $box_close['footer_nopadding'] = TRUE;
    unset($form, $form_items);

    echo generate_box_close($box_close);

  }

    // Device associations
    echo generate_box_open(array('title' => 'Device Associations', 'header-border' => TRUE));
    if (count($assocs['device']))
    {

      echo('<table class="'. OBS_CLASS_TABLE_STRIPED .'">');

      foreach ($assocs['device'] as $device_id => $status)
      {
        $device = device_by_id_cache($device_id);

        echo('<tr><td style="width: 1px;"></td>
                <td style="overflow: hidden;"><i class="'.$config['entities']['device']['icon'].'"></i> '.generate_device_link($device).'
                <small>' . escape_html($device['location']) . '</small></td>
                <td style="width: 25px;">');

        $form = array('type'       => 'simple',
                      //'userlevel'  => 10,          // Minimum user level for display form
                      'id'         => 'delete_device_'.$device['device_id'],
                      'style'      => 'display:inline;',
                     );
        $form['row'][0]['maint_id'] = array(
                                        'type'        => 'hidden',
                                        'value'       => $vars['maint_id']);
        $form['row'][0]['entity_id'] = array(
                                        'type'        => 'hidden',
                                        'value'       => $device['device_id']);
        $form['row'][0]['entity_type'] = array(
                                        'type'        => 'hidden',
                                        'value'       => 'device');

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
                                                               'data-confirm-content'   => 'Delete associated device "'.escape_html($device['hostname']).'"?',
                                                              ),
                                        'value'       => 'delete_maintenance_entity');

        print_form($form);
        unset($form);

        echo('</td>
           </tr>' . PHP_EOL);
      }
      echo('  </table>' . PHP_EOL);

    } else {
      echo('<p class="text-center text-warning bg-warning" style="padding: 10px; margin: 0px;"><strong>There are no devices currently associated with this maintenance</strong></p>');
    }

    $form_devices = array_keys(array_diff_key($cache['devices']['id'], $assocs['device']));
    $form_items['devices'] = generate_form_values('device', $form_devices);

    $form = array('type'       => 'simple',
                  //'userlevel'  => 10,          // Minimum user level for display form
                  'id'         => 'associate_devices',
                  'style'      => 'padding: 7px; margin: 0px;',
                  'right'      => TRUE,
                  );
    $form['row'][0]['maint_id'] = array(
                                      'type'        => 'hidden',
                                      'value'       => $vars['maint_id']);
    $form['row'][0]['entity_type'] = array(
                                      'type'        => 'hidden',
                                      'value'       => 'device');
    $form['row'][0]['entity_id'] = array(
                                      'type'        => 'multiselect',
                                      'name'        => 'Associate Devices',
                                      //'live-search' => FALSE,
                                      'width'       => '250px',
                                      //'right'       => TRUE,
                                      'groups'      => array('', 'UP', 'DOWN', 'DISABLED'), // This is optgroup order for values (if required)
                                      'values'      => $form_items['devices'],
                                      'value'       => $vars['entity_id']);
    $form['row'][0]['action'] = array(
                                      'type'        => 'submit',
                                      'name'        => 'Associate',
                                      'icon'        => $config['icon']['plus'],
                                      //'right'       => TRUE,
                                      'class'       => 'btn-primary',
                                      'value'       => 'add_maintenance_entity');

    $box_close['footer_content'] = generate_form($form);
    $box_close['footer_nopadding'] = TRUE;
    unset($form, $form_items);

    echo generate_box_close($box_close);


    // Alert Checker associations
    echo generate_box_open(array('title' => 'Alert Checker Associations', 'header-border' => TRUE));
    if (count($assocs['alert_checker']))
    {

      echo('<table class="'. OBS_CLASS_TABLE_STRIPED .'">');

      foreach ($assocs['alert_checker'] as $entity_id => $status)
      {
        $alert_check = $alert_rules[$entity_id];
        //print_vars($alert_check);

        echo('<tr><td style="width: 1px;"></td>
                  <td width="150px"><i class="'.$config['entities'][$alert_check['entity_type']]['icon'].'"></i> '.nicecase($alert_check['entity_type']).'</td>
                  <td>'.escape_html($alert_check['alert_name']).'</td>
                  <td width="25px">');

        $form = array('type'       => 'simple',
                      //'userlevel'  => 10,          // Minimum user level for display form
                      'id'         => 'delete_alert_checker_'.$alert_check['alert_test_id'],
                      'style'      => 'display:inline;',
                     );
        $form['row'][0]['entity_id'] = array(
                                        'type'        => 'hidden',
                                        'value'       => $alert_check['alert_test_id']);
        $form['row'][0]['maint_id'] = array(
                                        'type'        => 'hidden',
                                        'value'       => $vars['maint_id']);
        $form['row'][0]['entity_type'] = array(
                                        'type'        => 'hidden',
                                        'value'       => 'alert_checker');

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
                                                               'data-confirm-content'   => 'Delete associated checker "'.escape_html($alert_check['alert_name']).'"?',
                                                               //'data-confirm-content' => '<div class="alert alert-warning"><h4 class="alert-heading"><i class="icon-warning-sign"></i> Warning!</h4>
                                                               //                           This association will be deleted!</div>'),
                                                              ),
                                        'value'       => 'delete_maintenance_entity');

        print_form($form);
        unset($form);

        echo('</td>
           </tr>' . PHP_EOL);
      }
      echo('  </table>' . PHP_EOL);

    } else {
      echo('<p class="text-center text-warning bg-warning" style="padding: 10px; margin: 0px;"><strong>There are no alert checkers currently associated with this maintenance</strong></p>');
    }

    $alert_tests = dbFetchRows('SELECT * FROM `alert_tests` ORDER BY `entity_type`, `alert_name`');

    if (count($alert_tests))
    {
      foreach ($alert_tests as $alert_test)
      {
        if (!isset($assocs['alert_checker'][$alert_test['alert_test_id']]))
        {
          $form_items['entity_id'][$alert_test['alert_test_id']] = array('name' => escape_html($alert_test['alert_name']),
                                                                         'icon' => $config['entities'][$alert_test['entity_type']]['icon']);
        }
      }

      $form = array('type'       => 'simple',
                    //'userlevel'  => 10,          // Minimum user level for display form
                    'id'         => 'associate_alert_checker',
                    'style'      => 'padding: 7px; margin: 0px;',
                    'right'      => TRUE,
                    );
      $form['row'][0]['maint_id'] = array(
                                        'type'        => 'hidden',
                                        'value'       => $vars['maint_id']);
      $form['row'][0]['entity_type'] = array(
                                        'type'        => 'hidden',
                                        'value'       => 'alert_checker');
      $form['row'][0]['entity_id'] = array(
                                        'type'        => 'select',
                                        'name'        => 'Associate Alert Checker',
                                        'live-search' => FALSE,
                                        'width'       => '250px',
                                        //'right'       => TRUE,
                                        'values'      => $form_items['entity_id'],
                                        'value'       => $vars['entity_id']);
      $form['row'][0]['action'] = array(
                                        'type'        => 'submit',
                                        'name'        => 'Associate',
                                        'icon'        => $config['icon']['plus'],
                                        //'right'       => TRUE,
                                        'class'       => 'btn-primary',
                                        'value'       => 'add_maintenance_entity');

      $box_close['footer_content'] = generate_form($form);
      $box_close['footer_nopadding'] = TRUE;
      unset($form, $form_items);
    }

    echo generate_box_close($box_close);

  } else { // Maintenance doesn't exist, so print an error.
    print_error("<strong>ERROR:</strong> The requested scheduled maintenance no longer exists.");
  }
} else {

    // No maint_id supplied, print list of scheduled maintenances

    if($vars['view'] == "ended")
    {
      $maints = dbFetchRows("SELECT * FROM `alerts_maint` WHERE `maint_end` < UNIX_TIMESTAMP(NOW()) ORDER BY `maint_end`");
    } else {
      $maints = dbFetchRows("SELECT * FROM `alerts_maint` WHERE `maint_end` >= UNIX_TIMESTAMP(NOW()) ORDER BY `maint_end`");
    }

    if (count($maints))
    {

      echo generate_box_open();

    ?>

<table class="table table-condensed  table-striped  table-hover">
  <thead>
    <tr>
    <th class="state-marker"></th>
    <th style="width: 1px"></th>
    <th style="width: 400px">Name / Description</th>
    <th style="width: 175px">Start</th>
    <th style="width: 175px">End</th>
    <th>Duration / Scope</th>
    <th style="width: 30px;"></th>
    </tr>
  </thead>
  <tbody>

    <?php
    foreach ($maints as $maint)
    {
      // Process $maint array and generate UI-related elements
      humanize_maintenance($maint);

      echo '<tr class="'.$maint['row_class'].'">';
      echo '<td class="state-marker">'.'</td>';
      echo '<td>'.'</td>';
      echo '<td><strong><a href="'.generate_url(array('page' => 'alert_maintenance', 'maint_id' => $maint['maint_id'])).'">'.escape_html($maint['maint_name']).'</a></strong>';
//        echo '<td><strong>'.$maint['maint_name'].'</strong>';

      echo $maint['active_text'].'<br />';
      echo '<i>'.escape_html(truncate($maint['maint_descr'], 140)).'</i></td>';
      echo '<td>'.format_unixtime($maint['maint_start']).'<br />';
      echo ''.$maint['start_text'].'</td>';
      echo '<td>'.format_unixtime($maint['maint_end']) . '<br />' . $maint['end_text'].'</td>';
      echo '<td><span class="label label-info">'.formatUptime($maint['duration']).'</span><br />'.$maint['entities_text'].'</td>';
      //echo '<td>'.($_SESSION['userlevel'] >= 10 ? '<a href="#maint_del_modal_'.$maint['maint_id'].'" data-toggle="modal"><i class="'.$config['icon']['cancel'].'"></i></a>' : '' ).'</td>';
      echo '<td>';

      $form = array('type'       => 'simple',
                    'userlevel'  => 10,          // Minimum user level for display form
                    'id'         => 'maint_del_'.$maint['maint_id'],
                    //'title'      => 'Delete Scheduled Maintenance (Id: ' . $maint['maint_id'] . ')',
                    'style'      => 'display:inline;',
                   );
      $form['row'][0]['maint_id'] = array(
                                      'type'        => 'hidden',
                                      'value'       => $maint['maint_id']);
      $form['row'][0]['confirm_'.$maint['maint_id']] = array(
                                      'type'        => 'hidden',
                                      'value'       => 1);

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
                                                             'data-confirm-content'   => 'Delete scheduled maintenance "'.escape_html($maint['maint_name']).'"?',
                                                             //'data-confirm-content' => '<div class="alert alert-warning"><h4 class="alert-heading"><i class="icon-warning-sign"></i> Warning!</h4>
                                                             //                           Are you sure you want to delete this scheduled maintenance?</div>'
                                                            ),
                                      'value'       => 'delete_maintenance');

      print_form($form);
      unset($form);
      echo '</td></tr>';
    } // End foreach maint

?>

  </tbody>
</table>

<?php

    echo generate_box_close();

  } else {
    print_message("There are currently no scheduled maintenances configured.", FALSE);
  } // end count($maints)
  // End userlevel > 5
}

register_html_title('Scheduled Maintenance');

// EOF
