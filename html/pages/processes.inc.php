<?php

function build_table($array)
{

    // start table
    $html = '<table class="table table-condensed table-striped">';
    // header row
    $html .= '<thead><tr>';
    foreach($array[0] as $key => $value)
    {
      $html .= '<th>' . $key . '</th>';
    }
    $html .= '</tr></thead>';

    // data rows
    foreach($array as $key => $value)
    {
      $html .= '<tr>';
      foreach($value as $key2 => $value2)
      {
        $html .= '<td>' . $value2 . '</td>';
      }
      $html .= '</tr>';
    }

    // finish table and return it

    $html .= '</table>';
    return $html;
}

if ($_SESSION['userlevel'] >= 10)
{
  echo generate_box_open();
  echo build_table(dbFetchRows("SELECT * FROM `observium_processes` ORDER BY `process_ppid`, `process_start`"));
  echo generate_box_close();
} else {
  print_error_permission();
}

// EOF

