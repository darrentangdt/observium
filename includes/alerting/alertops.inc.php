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

$url = $endpoint['url'];

// Send entire request to AlertOps.

$payload = $message_tags;

if (!empty($endpoint['assignee'])) {
  $payload['ASSIGNEE'] = $endpoint['ASSIGNEE'];
}


unset($payload['ENTITY_GRAPHS_ARRAY']);

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
