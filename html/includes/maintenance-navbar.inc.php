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

if (!is_array($alert_rules)) { $alert_rules = cache_alert_rules(); }

$navbar['class'] = 'navbar-narrow';
$navbar['brand'] = 'Maintenance';

$pages = array('scheduled' => 'Scheduled', 'ended' => 'Ended');

if(!isset($vars['view']) && !isset($vars['maint_id'])) { $vars['view'] = 'scheduled'; }

foreach ($pages as $page_name => $page_desc)
{
    if ($vars['view'] == $page_name)
    {
      $navbar['options'][$page_name]['class'] = "active";
    }

    $navbar['options'][$page_name]['url'] = generate_url(array('page' => 'alert_maintenance', 'view' => $page_name));
    $navbar['options'][$page_name]['text'] = escape_html($page_desc);
}

$navbar['options_right']['schedule']['url']       = generate_url(array('page' => 'alert_maintenance_add'));
//$navbar['options_right']['schedule']['link_opts'] = 'data-toggle="modal"';
$navbar['options_right']['schedule']['text']      = 'Add Schedule Maintenance';
$navbar['options_right']['schedule']['icon']      = $config['icon']['scheduled-maintenance-add'];
$navbar['options_right']['schedule']['userlevel'] = 8;

// Print out the navbar defined above
print_navbar($navbar);
unset($navbar);

//r($vars);

// Run Actions
if ($_SESSION['userlevel'] < 8)
{
  return;
}

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
      case 'delete_maintenance':
        if (in_array($vars['confirm_'.$vars['maint_id']], array('1', 'on', 'yes', 'confirm')))
        {
          $rows_deleted  = dbDelete('alerts_maint',       '`maint_id` = ?', array($vars['maint_id']));
          $assoc_deleted = dbDelete('alerts_maint_assoc', '`maint_id` = ?', array($vars['maint_id']));

          if ($assoc_deleted)
          {
            print_success('Deleted scheduled maintenance associations (Id: '.$vars['maint_id'].', count: '.$assoc_deleted.')');
          }
          if ($rows_deleted)
          {
            print_success('Deleted scheduled maintenance (Id: '.$vars['maint_id'].')');
          }
        }
        unset($vars['maint_id']);
        break;

      case 'add_maintenance':
        $update_array = array(
          'maint_name'    => $vars['maint_name'],
          'maint_descr'   => $vars['maint_descr'],
          'maint_global'  => (in_array($vars['maint_global'], array('1', 'on', 'yes', 'confirm')) ? 1 : 0),
          'maint_start'   => strtotime($vars['maint_time_from']),
          'maint_end'     => strtotime($vars['maint_time_to'])
        );

        $maint_id = dbInsert($update_array, 'alerts_maint');
        if ($maint_id)
        {
          print_success("<strong>SUCCESS:</strong> Added scheduled maintenance");
        } else {
          print_warning("<strong>WARNING:</strong> Entry not added");
        }
        break;

      case 'update_maintenance':
        $update_array = array(
          'maint_name'    => $vars['maint_name'],
          'maint_descr'   => $vars['maint_descr'],
          'maint_global'  => (in_array($vars['maint_global'], array('1', 'on', 'yes', 'confirm')) ? 1 : 0),
          'maint_start'   => strtotime($vars['maint_time_from']),
          'maint_end'     => strtotime($vars['maint_time_to'])
        );

        $rows_updated = dbUpdate($update_array, 'alerts_maint', "`maint_id` = ?", array($vars['maint_id']));

        if ($rows_updated)
        {
          print_success("<strong>SUCCESS: </strong> Updated scheduled maintenance");
        } else {
          print_warning("<strong>WARNING: </strong> Entry not updated");
        }

        break;

      case 'add_maintenance_entity':
        foreach ((array)$vars['entity_id'] as $entity_id)
        {
          $update_array = array(
            'maint_id'    => $vars['maint_id'],
            'entity_type' => $vars['entity_type'],
            'entity_id'   => $entity_id
          );

          if (is_array(get_entity_by_id_cache($vars['entity_type'], $entity_id)))
          {
            if ($assoc_id = dbInsert($update_array, 'alerts_maint_assoc'))
            {
              print_success("<strong>SUCCESS:</strong> Added maintenance entity association (id: $assoc_id)");
            } else {
              print_warning("<strong>ERROR:</strong> Unable to add maintenance association");
            }
          } else {
            print_warning("<strong>ERROR:</strong> Invalid Entity.");
          }
        }
        break;

      case 'delete_maintenance_entity':
        foreach ((array)$vars['entity_id'] as $entity_id)
        {
          $deleted = dbDelete('alerts_maint_assoc', '`maint_id` = ? AND entity_type = ? AND entity_id = ?', array($vars['maint_id'], $vars['entity_type'], $entity_id));

          if ($deleted)
          {
            print_success("<strong>SUCCESS: </strong> Removed maintenance entity association");
          } else {
            print_warning("<strong>ERROR: </strong> Unable to remove maintenance entity association");
          }
        }
        break;

    }

  }

  // Clean common action vars
  unset($vars['submit'], $vars['action'], $vars['confirm']);

// EOF
