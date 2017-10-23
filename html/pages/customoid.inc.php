<?php

/**
 * Observium Network Management and Monitoring System
 *
 *   This file is part of Observium.
 *
 * @package        observium
 * @subpackage     webui
 * @author         Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

// Custom OID display and editing page.

// Only show page to global secure read or above
if ($_SESSION['userlevel'] < 7) {
  print_error_permission();

  return;
}

$readonly = $_SESSION['userlevel'] < 10;

include($config['html_dir'] . "/includes/customoids-navbar.inc.php");

$entries = array();
if ($oid = get_customoid_by_id($vars['oid_id'])) {

  $count = count(dbFetchRows("SELECT `oid_entry_id` FROM `oids_entries` WHERE `oid_id` = ?", array($oid['oid_id'])));


  // Print OID Definition headers

  ?>

    <div class="row">
    <div class="col-md-12">
      <?php

      $box_args = array(
        //'title' => 'Custom OID Information',
        'header-border' => FALSE,
      );

      echo generate_box_open($box_args);

      $thresholds = threshold_string($oid['oid_alert_low'], $oid['oid_warn_low'], $oid['oid_warn_high'],
                                     $oid['oid_alert_high'], $oid['oid_symbol']);

      ?>

        <table class="table  table-condensed">
            <thead>
            <tr>
                <th>Description</th>
                <th>OID Type</th>
                <th>Devices</th>
                <th>Numeric OID</th>
                <th>OID Name</th>
                <th>Unit</th>
                <th>Thresholds</th>
                <th width="80">Discovery</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><strong><?php echo $oid['oid_descr']; ?></strong></td>
                <td>
                    <span class="label label-<?php echo($oid['oid_type'] == "GAUGE" ? "success" : "error"); ?>"><?php echo $oid['oid_type']; ?></span>
                </td>
                <td><span class="label"><?php echo escape_html($count) ?></span></td>
                <td><?php echo escape_html($oid['oid']) ?></td>
                <td><?php echo escape_html($oid['oid_name']) ?></td>
                <td><?php echo escape_html($oid['oid_unit']); ?></td>
                <td><?php echo $thresholds; ?></td>
                <td><?php echo($oid['oid_autodiscover'] ? '<span class="label label-success">auto</span>' : '<span class="label label-disabled">off</span>') ?></td>
            </tr>
            </tbody>
        </table>

      <?php
      echo generate_box_close();
      ?>

    </div>

    <div class="col-md-12">

  <?php

  $navbar['brand'] = $oid['oid_descr'];
  $navbar['class'] = "navbar-narrow";

  $views = array('overview' => 'Overview', 'line' => 'Line Graph', 'stacked' => 'Stacked Graph');

  foreach ($views as $view => $text) {
    if (!isset($vars['view'])) {
      $vars['view'] = $view;
    }

    if ($vars['view'] == $view) {
      $navbar['options'][$view]['class'] = "active";
    }
    $navbar['options'][$view]['url'] = generate_url(array('page' => 'customoid', 'oid_id' => $vars['oid_id'], 'view' => $view));
    if ($view == 'overview') {
      $navbar['options'][$view]['icon'] = $config['icon'][$view];
    } else {
      $navbar['options'][$view]['icon'] = $config['icon']['graphs-' . $view];
    }
    $navbar['options'][$view]['text'] = $text;
  }

  if ($vars['view'] == "overview") {
    $navbar['options']['graphs']['text'] = 'Graphs';
    $navbar['options']['graphs']['icon'] = $config['icon']['graphs'];
    $navbar['options']['graphs']['right'] = TRUE;
    $navbar['options']['graphs']['class'] = (isset($vars['graphs']) ? 'active' : NULL);
    $navbar['options']['graphs']['url'] = generate_url($vars,
                                                       array('graphs' => (isset($vars['graphs']) ? NULL : 'yes')));
  }

  $navbar['options_right']['edit']['url']       = '#modal-edit_customoid';
  $navbar['options_right']['edit']['link_opts'] = 'data-toggle="modal"';
  $navbar['options_right']['edit']['text']      = 'Edit';
  $navbar['options_right']['edit']['icon']      = $config['icon']['tools'];
  $navbar['options_right']['edit']['userlevel'] = 10; // Minimum user level to display item

  print_navbar($navbar);

  // END OID Definition headers

  if ($vars['view'] == "overview") {

    print_oid_table($vars);

    if ($entry['oid_autodiscover'] != '1' && $vars['page'] == "customoid") {

      $entries = dbFetchRows("SELECT `device_id` FROM `oids_entries` WHERE `oid_id` = ?", array($oid['oid_id']));

      foreach ($entries as $entry) {
        $exists[$entry['device_id']] = $entry;
      }

      $form_devices = array_keys(array_diff_key($cache['devices']['id'], $exists));
      $form_items['devices'] = generate_form_values('device', $form_devices);

      $form = array('type'  => 'simple',
        //'userlevel'  => 10,          // Minimum user level for display form
                    'id'    => 'associate_devices',
                    'style' => 'padding: 7px; margin: 0px;',
                    'right' => TRUE,
      );
      $form['row'][0]['oid_id'] = array(
        'type'  => 'hidden',
        'value' => $vars['oid_id']);
      $form['row'][0]['entity_type'] = array(
        'type'  => 'hidden',
        'value' => 'device');
      $form['row'][0]['entity_id'] = array(
        'type'   => 'multiselect',
        'name'   => 'Add to Device',
        //'live-search' => FALSE,
        'width'  => '250px',
        //'right'       => TRUE,
        'groups' => array('', 'UP', 'DOWN', 'DISABLED'), // This is optgroup order for values (if required)
        'values' => $form_items['devices'],
        'value'  => $vars['entity_id']);
      $form['row'][0]['action'] = array(
        'type'  => 'submit',
        'name'  => 'Add',
        //'icon'        => $config['icon']['plus'],
        //'right'       => TRUE,
        'icon'  => 'icon-plus',
        'class' => 'btn-primary',
        'value' => 'add_customoid_entity');

      echo '<div style="margin: 5 0;">' . generate_form($form) . '</div>';
    }


  } else {
    $graph_array['to'] = $config['time']['now'];
    $graph_array['id'] = $oid['oid_id'];
    $graph_array['type'] = 'multi-customoid_' . $vars['view'];

    $box_args = array(
      //'title'         => 'Aggregated Graph',
      'header-border' => TRUE);

    echo generate_box_open($box_args);

    print_graph_row($graph_array);

    echo generate_box_close();
  }



  $form = array('type'       => 'horizontal',
                'userlevel'  => 10,          // Minimum user level for display form
                'id'         => 'modal-edit_customoid',
                'title'      => 'Edit Custom OID '.$oid['oid'],
    //'modal_args' => $modal_args, // !!! This generate modal specific form
    //'class'      => '',          // Clean default box class!
                //'url'        => generate_url(array('page' => 'customoids')),
  );
  //$form['fieldset']['body']   = array('class' => 'modal-body');   // Required this class for modal body!
  //$form['fieldset']['footer'] = array('class' => 'modal-footer'); // Required this class for modal footer!

  $form_params = array();
  $form_params['oid_type']['GAUGE']   = array('name' => 'GAUGE',   'subtext' => 'Values is simply stored as-is, is for things like temperatures.');
  $form_params['oid_type']['COUNTER'] = array('name' => 'COUNTER', 'subtext' => 'Data source assumes that the counter never decreases.');
  //$form_params['oid_type']['DERIVE']  = array('name' => 'DERIVE',  'subtext' => 'Internally, derive works exactly like COUNTER but without overflow checks.');
  //$form_params['oid_type']['ABSOLUTE']= array('name' => 'ABSOLUTE','subtext' => 'Value is an (unsigned) integer and will be divided by the time since the last reading.');

  $form['row'][5]['oid'] = array(
    'type'        => 'text',
    'fieldset'    => 'body',
    'name'        => 'Numeric OID',
    'class'       => 'input-xlarge',
    'readonly'    => TRUE,
    'value'       => $oid['oid']);
  $form['row'][6]['oid_name'] = array(
    'type'        => 'text',
    'fieldset'    => 'body',
    'name'        => 'Text OID',
    'class'       => 'input-xlarge',
    'value'       => $oid['oid_name']);
  $form['row'][7]['oid_type'] = array(
    'type'        => 'select',
    'fieldset'    => 'body',
    'name'        => 'Type',
    'width'       => '270px',
    'live-search' => FALSE,
    'values'      => $form_params['oid_type'],
    'value'       => $oid['oid_type']);
  $form['row'][8]['oid_descr'] = array(
    'type'        => 'text',
    'fieldset'    => 'body',
    'name'        => 'Description',
    'class'       => 'input-xlarge',
    'value'       => $oid['oid_descr']);
  $form['row'][9]['oid_unit'] = array(
    'type'        => 'text',
    'fieldset'    => 'body',
    'name'        => 'Display Unit',
    'placeholder' => 'Packets/sec',
    'class'       => 'input-xlarge',
    'value'       => $oid['oid_unit']);
  $form['row'][10]['oid_symbol'] = array(
    'type'        => 'text',
    'fieldset'    => 'body',
    'name'        => 'Display Symbol',
    'class'       => 'input-small',
    'placeholder' => 'Hz',
    'value'       => $oid['oid_symbol']);
  $form['row'][11]['oid_autodiscover'] = array(
    'type'        => 'toggle',
    'fieldset'    => 'body',
    'name'        => 'Autodiscovery',
    'class'       => 'input-large',
    'value'       => $oid['oid_autodiscover']);


  $form['row'][99]['close'] = array(
    'type'        => 'submit',
    'fieldset'    => 'footer',
    'div_class'   => '', // Clean default form-action class!
    'name'        => 'Close',
    'icon'        => '',
    'attribs'     => array('data-dismiss' => 'modal',
                           'aria-hidden'  => 'true'));
  $form['row'][99]['action'] = array(
    'type'        => 'submit',
    'fieldset'    => 'footer',
    'div_class'   => '', // Clean default form-action class!
    'name'        => 'Save',
    'icon'        => 'icon-save icon-white',
    //'right'       => TRUE,
    'class'       => 'btn-primary',
    'value'       => 'edit_customoid');

  echo generate_form_modal($form);
  unset($form, $form_params);



} else {
  print_error("The requested Custom OID doesn't seem to exist.");
}

?>

    </div>
    </div>

<?php

// EOF
