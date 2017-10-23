<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage alerting
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

$message = $message_tags['TITLE'] . PHP_EOL;
$message .= str_replace("             ", "", $message_tags['METRICS']);

// POST Data
$postdata = http_build_query(array(
  "apiKey" => $endpoint['accesskey'],
  "numbers" => $endpoint['recipient'],
  "sender" => $endpoint['originator'],
  "message" => rawurlencode($message)));

// JSON data + HTTP headers
$context_data = array(
  'method' => 'POST',
  'content' => $postdata);

// API URL to POST to
$url = 'https://api.txtlocal.com/send/';

// Send out API call and parse response into an associative array
$response = get_http_request($url, $context_data);

$notify_status['success'] = FALSE;
if ($response !== FALSE)
{
  $response = json_decode($response, TRUE);
  //var_dump($response);
  if (isset($response['status']) && $response['status'] == 'success') { $notify_status['success'] = TRUE; }
}

unset($message, $postdata, $context_data, $url, $response);

// EOF
