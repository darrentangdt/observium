<?php

/**
 * Observium Network Management and Monitoring System
 * Copyright (C) 2006-2015, Adam Armstrong - http://www.observium.org
 *
 * @package    observium
 * @subpackage webui
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

$navbar['class'] = 'navbar-narrow';
$navbar['brand'] = 'Custom OIDs';

$pages = array('customoids' => 'Custom OID List');

foreach ($pages as $page_name => $page_desc)
{
    if ($vars['page'] == $page_name)
    {
        $navbar['options'][$page_name]['class'] = "active";
    }

    $navbar['options'][$page_name]['url'] = generate_url(array('page' => $page_name));
    $navbar['options'][$page_name]['text'] = escape_html($page_desc);
}

      if($vars['page'] == "customoids")
      {
        $navbar['options_right']['graphs']['text']  = 'Graphs';
        $navbar['options_right']['graphs']['icon']  = $config['icon']['graphs'];
        $navbar['options_right']['graphs']['right'] = TRUE;
        $navbar['options_right']['graphs']['class'] = (isset($vars['graphs']) ? 'active' : NULL );
        $navbar['options_right']['graphs']['url']   = generate_url($vars, array('graphs' => (isset($vars['graphs']) ? NULL : 'yes' ) ));
      }


$navbar['options_right']['add']['url']       = '#modal-add_customoid';
$navbar['options_right']['add']['link_opts'] = 'data-toggle="modal"';
$navbar['options_right']['add']['text']      = 'Add Custom OID';
$navbar['options_right']['add']['icon']      = $config['icon']['plus'];
$navbar['options_right']['add']['userlevel'] = 10; // Minimum user level to display item

// Print out the navbar defined above
print_navbar($navbar);
unset($navbar);

    /* Begin Add custom oid */

    /*
    $modal_args = array(
      'id'    => 'modal-add_customoid',
      'title' => 'Add Custom OID',
      //'icon'  => 'oicon-target',
      //'hide'  => TRUE,
      //'fade'  => TRUE,
      //'role'  => 'dialog',
      //'class' => 'modal-md',
    );
    */

    $form = array('type'       => 'horizontal',
                  'userlevel'  => 10,          // Minimum user level for display form
                  'id'         => 'modal-add_customoid',
                  'title'      => 'Add Custom OID',
                  //'modal_args' => $modal_args, // !!! This generate modal specific form
                  //'class'      => '',          // Clean default box class!
                  'url'        => generate_url(array('page' => 'customoids')),
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
                                      'placeholder' => '.1.3.6.1.2.1.4.555.0',
                                      'value'       => '');
    $form['row'][6]['oid_name'] = array(
                                      'type'        => 'text',
                                      'fieldset'    => 'body',
                                      'name'        => 'Text OID',
                                      'class'       => 'input-xlarge',
                                      'placeholder' => 'sysReboots.0',
                                      'value'       => '');
    $form['row'][7]['oid_type'] = array(
                                      'type'        => 'select',
                                      'fieldset'    => 'body',
                                      'name'        => 'Value Type',
                                      'width'       => '270px',
                                      'live-search' => FALSE,
                                      'values'      => $form_params['oid_type'],
                                      'value'       => 'GAUGE');
    $form['row'][8]['oid_descr'] = array(
                                      'type'        => 'text',
                                      'fieldset'    => 'body',
                                      'name'        => 'Description',
                                      'class'       => 'input-xlarge',
                                      'placeholder' => 'System Reboot Counter',
                                      'value'       => '');
    $form['row'][9]['oid_unit'] = array(
                                      'type'        => 'text',
                                      'fieldset'    => 'body',
                                      'name'        => 'Display Unit',
                                      'class'       => 'input-xlarge',
                                      'placeholder' => 'Packets/sec',
                                      'value'       => '');
    $form['row'][10]['oid_symbol'] = array(
                                      'type'        => 'text',
                                      'fieldset'    => 'body',
                                      'name'        => 'Display Symbol',
                                      'class'       => 'input-small',
                                      'placeholder' => 'Hz',
                                      'value'       => '');
    $form['row'][11]['oid_autodiscover'] = array(
                                      'type'        => 'toggle',
                                      'fieldset'    => 'body',
                                      'name'        => 'Auto-discovery',
                                      'class'       => 'input-large');


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
                                    'name'        => 'Add Custom OID',
                                    'icon'        => 'icon-ok icon-white',
                                    //'right'       => TRUE,
                                    'class'       => 'btn-primary',
                                    'value'       => 'add_customoid');

    echo generate_form_modal($form);
    unset($form, $form_params);

    // End add custom oid
    register_html_title('Custom OIDs');

// Begin Actions
$readonly = $_SESSION['userlevel'] < 10; // Currently edit allowed only for Admins
if ($readonly) { return; }

// FIXME: move all actions to separate include(s) with common options!
if (isset($vars['submit']) && !isset($vars['action']))
{
  // Convert submit to action (for compatibility)
  $vars['action'] = $vars['submit'];
}

if (isset($vars['action']))
{
  switch ($vars['action'])
  {
    case 'add_customoid':
      if (strlen($vars['oid_descr']) &&
          strlen($vars['oid_name']) &&
          strlen($vars['oid_type']) &&
          strlen($vars['oid']) &&
          strlen($vars['oid_unit']))
      {
        $oid_id = dbInsert('oids', array('oid_descr' => $vars['oid_descr'],
                                         'oid_name' => $vars['oid_name'],
                                         'oid_type' => $vars['oid_type'],
                                         'oid'      => $vars['oid'],
                                         'oid_unit' => $vars['oid_unit'],                                         'oid_unit' => $vars['oid_unit'],
                                         'oid_symbol' => $vars['oid_symbol'],
                                         'oid_autodiscover' => $vars['oid_autodiscover']));

        if ($oid_id)
        {
          print_success("<strong>SUCCESS:</strong> Added Custom OID");
        } else {
          print_warning("<strong>WARNING:</strong> Entry not added");
        }
      } else {
        print_error("<strong>ERROR:</strong> All fields must be completed to add a new Custom OID.");
      }
      break;

    case 'edit_customoid':

      $changes = dbUpdate(array('oid_descr'        => $vars['oid_descr'],
                               'oid_name'         => $vars['oid_name'],
                               'oid_type'         => $vars['oid_type'],
                               'oid_unit'         => $vars['oid_unit'],
                               'oid_symbol'       => $vars['oid_symbol'],
                               'oid_autodiscover' => $vars['oid_autodiscover']),
                           'oids',
                           'oid_id = ?',
                           array($vars['oid_id'])
                         );

      if ($changes)
      {
        print_success("<strong>SUCCESS:</strong> Saved Custom OID Changes");
      } else {
        print_warning("<strong>WARNING:</strong> No Changes Saved");
      }
      break;

    case 'delete_customoid':
      if (in_array($vars['confirm_'.$vars['oid_id']], array('1', 'on', 'yes', 'confirm')))
      {
        $rows_deleted  = dbDelete('oids',       '`oid_id` = ?', array($vars['oid_id']));
        $assoc_deleted = dbDelete('oids_entries', '`oid_id` = ?', array($vars['oid_id']));

        if ($assoc_deleted)
        {
          print_success('Deleted Custom OID associations (Id: '.$vars['oid_id'].', count: '.$assoc_deleted.')');
        }
        if ($rows_deleted)
        {
          print_success('Deleted Custom OID (Id: '.$vars['oid_id'].')');
        }
      }
      break;

    case 'add_customoid_entity':

      foreach ((array)$vars['entity_id'] as $entity_id)
      {
        if ($vars['entity_type'] != 'device') { continue; } // Currently only device entities

        $update_array = array(
          'oid_id'      => $vars['oid_id'],
          //'entity_type' => $vars['entity_type'],
          //'entity_id'   => $entity_id,
          'device_id'   => $entity_id
        );

        if (is_array(get_entity_by_id_cache($vars['entity_type'], $entity_id)))
        {
          if ($assoc_id = dbInsert($update_array, 'oids_entries'))
          {
            print_success("<strong>SUCCESS:</strong> Added OID entity association (id: $assoc_id)");
          } else {
            print_warning("<strong>ERROR:</strong> Unable to add OID association");
          }
        } else {
          print_warning("<strong>ERROR:</strong> Invalid Entity.");
        }
      }
      break;

    case 'delete_customoid_device':

      foreach ((array)$vars['form_device_id'] as $device_id)
      {

        $deleted = dbDelete('oids_entries', '`oid_id` = ? AND `device_id` = ?', array($vars['form_oid_id'], $vars['form_device_id']));

        if ($deleted)
        {
          print_success("<strong>SUCCESS: </strong> Removed OID entity association");
        } else {
          print_warning("<strong>ERROR: </strong> Unable to remove OID entity association");
        }
      }
      break;
  }
}

// EOF
