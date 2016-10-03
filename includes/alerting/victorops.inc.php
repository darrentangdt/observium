<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage alerting
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

/*
 * For full docs see http://victorops.force.com/knowledgebase/articles/Integration/Alert-Ingestion-API-Documentation/
 */

// Unless this is a recovery, it is a new incident by default (extend to WARNING later)
$message_type = ($message_tags['ALERT_STATE'] == "RECOVER" ? "RECOVERY" : "CRITICAL");

// JSON data
$data_string = json_encode(array(
  "message_type"        => $message_type,
  "entity_id"           => $message_tags['ALERT_ID'],
  //"state_start_time"   => NULL,
  "state_message"       => $message_tags['ALERT_MESSAGE'],
  "entity_display_name" => "[".$message_tags['DEVICE_HOSTNAME']."][".$message_tags['ENTITY_TYPE'] . "][" . $message_tags['ENTITY_NAME']."] ".$message_tags['ENTITY_DESC'],
  "monitoring_tool"     => "Observium", // Perhaps user-defined hostname here?
));

// JSON data + HTTP headers
$context_data = array(
  'method' => 'POST',
  'header' =>
    "Connection: close\r\n".
    "Content-Type: application/json\r\n".
    "Content-Length: ".strlen($data_string)."\r\n",
  'content'=> $data_string
);

// API URL to POST to. Remove placeholder of routing key if it exists.
$url = str_replace('$routing_key', '', $endpoint['url']) . $endpoint['routing_key'];

// Send out API call and parse response into an associative array
$result = json_decode(get_http_request($url, $context_data), TRUE);

// Check if call succeeded
if ($result['result'] == 'success')
{
  $notify_status['success'] = TRUE;
} else {
  $notify_status['success'] = FALSE;
  // Error is $result['message'];
}

unset($result);

// EOF
