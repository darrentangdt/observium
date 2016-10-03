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

// Contact display and editing page.

if ($_SESSION['userlevel'] < 7)
{
  // Allowed only secure global read permission
  print_error_permission();
  return;
}

$readonly = $_SESSION['userlevel'] < 10;

if (!$readonly)
{
  if (isset($vars['delete_contact_assoc']))
  {
    $rows_updated   = dbDelete('alert_contacts_assoc',       '`aca_id` = ?', array($vars['delete_contact_assoc']));
  }

  if (isset($vars['submit']) && is_numeric($vars['contact_id']))
  {
    switch ($vars['submit'])
    {
      case 'associate_alert_check':
        if (is_numeric($vars['alert_checker_id']))
        {
          dbInsert('alert_contacts_assoc', array('aca_type'         => 'alert', // $vars['type']
                                                 'contact_id'       => $vars['contact_id'],
                                                 'alert_checker_id' => $vars['alert_checker_id']));
        }
        break;

      case 'associate_syslog_rule':
        if (is_numeric($vars['la_id']))
        {
          dbInsert('alert_contacts_assoc', array('aca_type'         => 'syslog', // $vars['type']
                                                 'contact_id'       => $vars['contact_id'],
                                                 'alert_checker_id' => $vars['la_id']));
        }
        break;

      case 'update-contact-entry':
        $update_state = array();
        $contact = get_contact_by_id($vars['contact_id']);

        foreach (json_decode($contact['contact_endpoint']) as $field => $value)
        {
          $contact['endpoint_parameters'][$field] = $value;
        }

        $update_state['contact_disabled'] = $vars['contact_enabled'] == '1' ? 0 : 1;

        if (strlen($vars['contact_descr']) && $vars['contact_descr'] != $contact['contact_descr'])
        {
          $update_state['contact_descr'] = $vars['contact_descr'];
        }

        $data = $config['alerts']['transports'][$contact['contact_method']];
        if (!count($data['parameters']['global']))   { $data['parameters']['global'] = array(); } // Temporary until we separate "global" out.
        if (!count($data['parameters']['optional'])) { $data['parameters']['optional'] = array(); }
        // Plan: add defaults for transport types to global settings, which we use by default, then be able to override the settings via this GUI
        // This needs supporting code in the transport to check for set variable and if not, use the global default

        $update_endpoint = $contact['endpoint_parameters'];
        foreach (array_merge($data['parameters']['required'], $data['parameters']['global'], $data['parameters']['optional']) as $parameter => $param_data)
        {
          if (strlen($vars['contact_endpoint_'.$parameter]) && $vars['contact_endpoint_'.$parameter] != $contact['endpoint_parameters'][$parameter])
          {
            $update_endpoint[$parameter] = $vars['contact_endpoint_'.$parameter];
          }
        }
        $update_endpoint = json_encode($update_endpoint);
        if ($update_endpoint != $contact['contact_endpoint'])
        {
          //r($update_endpoint);
          //r($contact['contact_endpoint']);
          $update_state['contact_endpoint'] = $update_endpoint;
        }

        $rows_updated = dbUpdate($update_state, 'alert_contacts', 'contact_id = ?', array($vars['contact_id']));
        break;
    }
  }
}

include($config['html_dir'].'/includes/contacts-navbar.inc.php');

if ($contact = get_contact_by_id($vars['contact_id']))
{

?>

<div class="row">
  <div class="col-sm-6">
<?php

foreach (json_decode($contact['contact_endpoint']) as $field => $value)
{
  $contact['endpoint_parameters'][$field] = $value;
}

$data = $config['alerts']['transports'][$contact['contact_method']];
if (!count($data['parameters']['global']))   { $data['parameters']['global'] = array(); } // Temporary until we separate "global" out.
// Plan: add defaults for transport types to global settings, which we use by default, then be able to override the settings via this GUI
// This needs supporting code in the transport to check for set variable and if not, use the global default

    $form = array('type'      => 'horizontal',
                  'id'        => 'update_contact_status',
                  'title'     => 'Contact Information',
                  'space'     => '5px',
                  'fieldset'  => array('edit' => ''),
                  );
    $i = 0;
    $form['row'][++$i]['contact_method'] = array(
                                    'type'        => 'raw',
                                    //'fieldset'    => 'edit',
                                    'name'        => 'Transport Method',
                                    'class'       => 'label',
                                    'div_style'   => 'padding-top: 5px;',
                                    'readonly'    => $readonly,
                                    'value'       => $data['name']);

    $form['row'][++$i]['contact_enabled'] = array(
                                    'type'        => 'switch',
                                    //'fieldset'    => 'edit',
                                    'name'        => 'Contact Status',
                                    'size'        => 'small',
                                    'on-color'    => 'success',
                                    'off-color'   => 'danger',
                                    'on-text'     => 'Enabled',
                                    'off-text'    => 'Disabled',
                                    'readonly'    => $readonly,
                                    'value'       => !$contact['contact_disabled']);

    $form['row'][++$i]['contact_descr'] = array(
                                    'type'        => 'text',
                                    //'fieldset'    => 'edit',
                                    'name'        => 'Description',
                                    'width'       => '80%',
                                    'readonly'    => $readonly,
                                    'value'       => $contact['contact_descr']);

  if (count($data['parameters']['required']))
  {
    $form['row'][++$i]['contact_required'] = array(
                                    'type'        => 'raw',
                                    //'fieldset'    => 'edit',
                                    'html'        => '<strong>Required parameters:</strong>');

    foreach (array_merge($data['parameters']['required'], $data['parameters']['global']) as $parameter => $param_data) // Temporary merge req & global
    {
      $form['row'][++$i]['contact_endpoint_'.$parameter] = array(
                                    'type'        => 'text',
                                    //'fieldset'    => 'edit',
                                    'width'       => '80%',
                                    'name'        => $param_data['description'],
                                    'readonly'    => $readonly,
                                    'value'       => $contact['endpoint_parameters'][$parameter]);


      if (isset($param_data['tooltip']))
      {
        $form['row'][$i]['tooltip_'.$parameter] = array(
                                    'type'        => 'raw',
                                    //'fieldset'    => 'edit',
                                    'readonly'    => $readonly,
                                    'html'        => generate_tooltip_link(NULL, '<i class="oicon-question"></i>', $param_data['tooltip']));

        //echo(generate_tooltip_link(NULL, '<i class="oicon-question"></i>', $param_data['tooltip']));
      }

    }
  }

  if (count($data['parameters']['optional']))
  {
    $form['row'][++$i]['contact_optional'] = array(
                                    'type'        => 'raw',
                                    //'fieldset'    => 'edit',
                                    'html'        => '<strong>Optional parameters:</strong>');

    foreach ($data['parameters']['optional'] as $parameter => $param_data)
    {
      $form['row'][++$i]['contact_endpoint_'.$parameter] = array(
                                    'type'        => 'text',
                                    //'fieldset'    => 'edit',
                                    'width'       => '80%',
                                    'name'        => $param_data['description'],
                                    'readonly'    => $readonly,
                                    'value'       => $contact['endpoint_parameters'][$parameter]);


      if (isset($param_data['tooltip']))
      {
        $form['row'][$i]['tooltip_'.$parameter] = array(
                                    'type'        => 'raw',
                                    //'fieldset'    => 'edit',
                                    'readonly'    => $readonly,
                                    'html'        => generate_tooltip_link(NULL, '<i class="oicon-question"></i>', $param_data['tooltip']));

        //echo(generate_tooltip_link(NULL, '<i class="oicon-question"></i>', $param_data['tooltip']));
      }

    }
  }

    $form['row'][++$i]['submit']    = array(
                                    'type'        => 'submit',
                                    'name'        => 'Save Changes',
                                    'icon'        => 'icon-ok icon-white',
                                    'right'       => TRUE,
                                    'class'       => 'btn-primary',
                                    'readonly'    => $readonly,
                                    'value'       => 'update-contact-entry');

    //r($form);
    print_form($form);
    unset($form, $i);
?>

  </div>

 <div class="col-sm-6">

<?php

    // Alert associations
    $assoc_exists = array();
    $assocs = dbFetchRows('SELECT * FROM `alert_contacts_assoc` AS A
                           LEFT JOIN `alert_tests` AS T ON T.`alert_test_id` = A.`alert_checker_id`
                           WHERE `aca_type` = ? AND `contact_id` = ?
                           ORDER BY `entity_type`, `alert_name` DESC', array('alert', $contact['contact_id']));
    //r($assocs);
    echo generate_box_open(array('title' => 'Associated Alert Checkers', 'header-border' => TRUE));
    if (count($assocs))
    {

      echo('<table class="table table-striped table-condensed">');

      foreach ($assocs as $assoc)
      {

        $alert_test = get_alert_test_by_id($assoc['alert_checker_id']);

        $assoc_exists[$assoc['alert_checker_id']] = TRUE;

        echo('<tr>
                  <td width="150"><i class="'.$config['entities'][$alert_test['entity_type']]['icon'].'"></i> '.nicecase($alert_test['entity_type']).'</td>
                  <td>'.escape_html($alert_test['alert_name']).'</td>
                  <td width="25"><a href="'.generate_url(array('page' => 'contact', 'contact_id' => $contact['contact_id'], 'delete_contact_assoc' => $assoc['aca_id'])).'"><i class="icon-trash text-danger"></i></a></td>
              </tr>');

      }

      echo('</table>');

    } else {
    echo('<table class="table table-striped table-condensed">');
    echo('<tr class="warning" style="padding: 5px;">
                <td style="padding: 10px;">This contact is not currently associated with any Alert Checkers.</td>
          </tr>');

    echo('</table>');
    }

  // FIXME -- use NOT IN to mask already associated things.

  $alert_tests = dbFetchRows('SELECT * FROM `alert_tests` ORDER BY `entity_type`, `alert_name`');

  if (count($alert_tests))
  {
    $form = '<form method="post" action="" class="form form-inline pull-right" style="margin: 10px;">';

    $form .= generate_form_element(array('id' => 'type', 'value' => 'alert'), 'hidden');

    $item = array('id'          => 'alert_checker_id',
                  'live-search' => FALSE,
                  'width'       => '220px',
                  'readonly'    => $readonly,
                  'value'       => $vars['alert_checker_id']);

    foreach ($alert_tests as $alert_test)
    {
      if (!isset($assoc_exists[$alert_test['alert_test_id']]))
      {
        $item['values'][$alert_test['alert_test_id']] = array('name' => escape_html($alert_test['alert_name']),
                                                              'icon' => $config['entities'][$alert_test['entity_type']]['icon']);
      }
    }

    $form .= generate_form_element($item, 'select');

    $item = array('id'          => 'submit',
                  'name'        => 'Associate',
                  'class'       => 'btn-primary',
                  'icon'        => 'icon-plus',
                  'readonly'    => $readonly,
                  'value'       => 'associate_alert_check');
    $form .= generate_form_element($item, 'submit');

    $form .= '</form>';

    $box_close['footer_content'] = $form;
    $box_close['footer_nopadding'] = TRUE;
  } else {
    // print_warning('No unassociated alert checkers.');
  }

  echo generate_box_close($box_close);

  // Syslog associations
  $assoc_exists = array();
  $assocs = dbFetchRows('SELECT * FROM `alert_contacts_assoc` AS A
                         LEFT JOIN `syslog_rules` AS T ON T.`la_id` = A.`alert_checker_id`
                         WHERE `aca_type` = ? AND `contact_id` = ?
                         ORDER BY `la_severity`, `la_name` DESC', array('syslog', $contact['contact_id']));
  //r($assocs);
  echo generate_box_open(array('title' => 'Associated Syslog Rules', 'header-border' => TRUE));
  if (count($assocs))
  {

    echo('<table class="table table-striped table-condensed">');

    foreach ($assocs as $assoc)
    {

      //$alert_test = get_alert_test_by_id($assoc['alert_checker_id']);

      $assoc_exists[$assoc['la_id']] = TRUE;

      echo('<tr>
                <td width="150"><i class="oicon-clipboard--exclamation"></i> '.escape_html($assoc['la_name']).'</td>
                <td>'.escape_html($assoc['la_rule']).'</td>
                <td width="25"><a href="'.generate_url(array('page' => 'contact', 'contact_id' => $contact['contact_id'], 'delete_contact_assoc' => $assoc['aca_id'])).'"><i class="icon-trash text-danger"></i></a></td>
            </tr>');

    }

    echo('</table>');

  } else {
    echo('<table class="table table-striped table-condensed">');
    echo('<tr class="warning">
                <td style="padding: 10px;">This contact is not currently associated with any Syslog Rules.</td>
          </tr>');
    echo('</table>');
  }

  $alert_tests = dbFetchRows('SELECT * FROM `syslog_rules` ORDER BY `la_severity`, `la_name`');

  if (count($alert_tests))
  {
    $form = '<form method="post" action="" class="form form-inline pull-right" style="margin: 10px;">';

    $form .= generate_form_element(array('id' => 'type', 'value' => 'syslog'), 'hidden');

    $item = array('id'          => 'la_id',
                  'live-search' => FALSE,
                  'width'       => '220px',
                  'readonly'    => $readonly,
                  'value'       => $vars['la_id']);

    foreach ($alert_tests as $alert_test)
    {
      if (!isset($assoc_exists[$alert_test['la_id']]))
      {
        $item['values'][$alert_test['la_id']] = array('name'    => escape_html($alert_test['la_name']),
                                                      'subtext' => escape_html($alert_test['la_rule']),
                                                      'icon'    => 'oicon-clipboard--exclamation');
      }
    }

    $form .= generate_form_element($item, 'select');

    $item = array('id'          => 'submit',
                  'name'        => 'Associate',
                  'class'       => 'btn-primary',
                  'icon'        => 'icon-plus',
                  'readonly'    => $readonly,
                  'value'       => 'associate_syslog_rule');
    $form .= generate_form_element($item, 'submit');

    $form .= '</form>';

    $box_close['footer_content'] = $form;
    $box_close['footer_nopadding'] = TRUE;
  } else {
    // print_warning('No unassociated syslog rules.');
  }

  echo generate_box_close($box_close);

  echo('</div>');
} else {
  print_error("Contact doesn't exist.");
}

// EOF
