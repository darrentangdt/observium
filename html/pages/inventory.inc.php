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

?>
<div class="row">
<div class="col-md-12">

<?php

$where = ' WHERE 1 ';
$where .= generate_query_permitted(array('device'), array('device_table' => 'E'));

$form_items = array();

// Select devices only with Inventory parts
foreach (dbFetchRows('SELECT E.`device_id` AS `device_id`, `hostname`, `entPhysicalModelName`
                     FROM `entPhysical` AS E
                     INNER JOIN `devices` AS D ON D.`device_id` = E.`device_id`' . $where .
                    'GROUP BY `device_id`, `entPhysicalModelName`;') as $data)
{
  $form_devices[] = $data['device_id'];
  if ($data['entPhysicalModelName'] != '')
  {
    $form_items['parts'][$data['entPhysicalModelName']] = $data['entPhysicalModelName'];
  }
}

  $where_array = build_devices_where_array($vars);
  $query_permitted = generate_query_permitted(array('device'), array('device_table' => 'devices'));
  $where = ' WHERE 1 ';
  $where .= implode('', $where_array);

  // Generate array with form elements
  //foreach (array('os', 'hardware', 'version', 'features', 'type') as $entry)
  foreach (array('os') as $entry)
  {
    $query  = "SELECT `$entry` FROM `devices`";
    if (isset($where_array[$entry]))
    {
      $tmp = $where_array[$entry];
      unset($where_array[$entry]);
      $query .= ' WHERE 1 ' . implode('', $where_array);
      $where_array[$entry] = $tmp;
    } else {
      $query .= $where;
    }
    $query .= " AND `$entry` != '' $query_permitted GROUP BY `$entry` ORDER BY `$entry`";
    foreach (dbFetchColumn($query) as $item)
    {
      if ($entry == 'os')
      {
        $name = $config['os'][$item]['text'];
      } else {
        $name = nicecase($item);
      }
      $form_items[$entry][$item] = $name;
    }
  }

$form = array('type'  => 'rows',
              'space' => '5px',
              'submit_by_key' => TRUE,
              'url'   => generate_url($vars));

//Device field
$form_items['devices'] = generate_form_values('device', $form_devices);
$form['row'][0]['device_id'] = array(
                                'type'        => 'multiselect',
                                'name'        => 'Device',
                                'width'       => '100%',
                                'value'       => $vars['device_id'],
                                'groups'      => array('', 'UP', 'DOWN', 'DISABLED'), // This is optgroup order for values (if required)
                                'values'      => $form_items['devices']);

// Device OS field
$form['row'][0]['os']       = array(
                                'type'        => 'multiselect',
                                'name'        => 'Select OS',
                                'width'       => '100%', //'180px',
                                'value'       => $vars['os'],
                                'values'      => $form_items['os']);

// Parts field
ksort($form_items['parts']);
$form['row'][0]['parts']       = array(
                                'type'        => 'multiselect',
                                'name'        => 'Part Numbers',
                                'width'       => '100%', //'180px',
                                'value'       => $vars['parts'],
                                'values'      => $form_items['parts']);

//Serial field
$form['row'][0]['serial']  = array(
                                'type'        => 'text',
                                'name'        => 'Serial',
                                'width'       => '100%',
                                'placeholder' => TRUE,
                                'submit_by_key' => TRUE,
                                'value'       => escape_html($vars['serial']));

//Description field
$form['row'][0]['description']  = array(
                                'type'        => 'text',
                                'name'        => 'Description',
                                'grid'        => 3,
                                'width'       => '100%',
                                'placeholder' => TRUE,
                                'submit_by_key' => TRUE,
                                'value'       => escape_html($vars['description']));
// search button
$form['row'][0]['search']   = array(
                                'type'        => 'submit',
                                'grid'        => 1,
                                'right'       => TRUE);

print_form($form);
unset($form, $form_items, $form_devices);

// Pagination
$vars['pagination'] = TRUE;

print_inventory($vars);

register_html_title('Inventory');

?>

  </div> <!-- col-md-12 -->
</div> <!-- row -->

<?php

// EOF
