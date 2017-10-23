<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage alerting
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2015 Observium Limited
 *
 */

/* Endpoint variables:
 * api_key: API key is used for authenticating API requests
 * recipients: Optional comma-separated list of users, groups, schedules or escalation names to receive alerts
 * For full docs see https://www.opsgenie.com/docs/web-api/alert-api
 */

$url = "https://api.opsgenie.com/v1/json/observiumv2";

// Send entire request to OpsGenie.

$payload = $message_tags;
unset($payload['ENTITY_GRAPHS_ARRAY']);
$payload['apiKey'] = $endpoint['api_key'];

if (!empty($endpoint['recipients'])) {
  $payload['recipients'] = explode(",", $endpoint['recipients']);
}


/** $payload = array(
  "apiKey"      => $endpoint['api_key'],
  "message"     => $message_tags['TITLE'],
  "description" => $message_tags['METRICS'] . PHP_EOL . PHP_EOL . 'Conditions: '. PHP_EOL . $message_tags['CONDITIONS'],
  "alias"       => $message_tags['ALERT_ID'],
  "entity"      => $message_tags['DEVICE_HOSTNAME'],
  "source"      => php_uname('n'),
  "details"     => array(
    "Alert URL" => $message_tags['ALERT_URL'],
    "Duration"  => $message_tags['DURATION'],
    "Hardware"  => $message_tags['DEVICE_HARDWARE'],
    "OS"        => $message_tags['DEVICE_OS'],
    "Location"  => $message_tags['DEVICE_LOCATION'],
  ),
);

if (!empty($endpoint['recipients'])) {
  $payload['recipients'] = explode(",", $endpoint['recipients']);
}

*/

//print_r($MESSAGE_TAGS);

if($message_tags['ALERT_STATE'] == 'RECOVER') {
//  $url .= "/close";
}

// JSON data
$data_string = json_encode($payload);

//print_r($data_string);

// JSON data + HTTP headers
$context_data = array(
  'method' => 'POST',
  'header' =>
    "Connection: close\r\n".
    "Content-Type: application/json\r\n".
    "Content-Length: ".strlen($data_string)."\r\n",
  'content'=> $data_string
);

//print_r($context_data);

// Send out API call and parse response into an associative array
$result = json_decode(get_http_request($url, $context_data), TRUE);

// Check if call succeeded
if ($result['status'] == 'successful')
{
  $notify_status['success'] = TRUE;
} else {
  $notify_status['success'] = FALSE;
}

unset($result);

// EOF
