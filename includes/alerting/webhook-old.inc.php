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

$message = $message_tags['TITLE'] . PHP_EOL;
$message .= str_replace("             ", "", $message_tags['METRICS']);

// JSON data
$data_string = json_encode(array(
  "originator" => $endpoint['originator'],
  "body" => $message));

// JSON data + HTTP headers
$context_data = array(
  'method' => 'POST',
  'header' =>
    "Connection: close\r\n".
    "Accept: application/json\r\n".
    "Content-Type: application/json\r\n".
    "Content-Length: ".strlen($data_string)."\r\n".
    "Authorization: AccessKey " . $endpoint['token'] . "\r\n",
  'content'=> $data_string);

// API URL to POST to
$url = $endpoint['url'];

// Send out API call and parse response into an associative array
$result = json_decode(get_http_request($url, $context_data), TRUE);

if ($result['message'] == 'SUCCESS')
{
  $notify_status['success'] = TRUE;
} else {
  $notify_status['success'] = FALSE;
}

unset($message, $result, $context_data);

// EOF
