<?php

/**
* Observium
*
*   This file is part of Observium.
*
* @package    observium
* @subpackage definitions
* @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
*
*/

// Transport definitions

$config['alerts']['transports']['email'] = array(
  'name' => 'E-mail',
  'identifiers' => array('email'),
  'parameters' => array(
    'required' => array(
      'email' => array('description' => 'Address'),
    ),
    //'optional' => array(
    //  'text_only' => array('description' => 'Text Only',
    //                       'type' => 'bool',
    //                       'default' => 'false',
    //                       'tooltip'     => 'Simplified text-only format. Values: true or false.'),
    //),
  )
);

$config['alerts']['transports']['hipchat'] = array(
  'name' => 'HipChat',
  'identifiers' => array('room_id'),
  'parameters' => array(
    'required' => array(
      'room_id' => array('description' => 'Room ID'),
    ),
    'optional' => array(
      'url' => array('description' => 'API URL', 'tooltip' => 'Optional parameter, set this only for use with on-premise installations. When unset, the transport uses Atlassian hosted API.'),
      'notify' => array('description' => 'Trigger a user notification', 'tooltip' => 'Whether this message should trigger a user notification (change the tab color, play a sound, notify mobile phones, etc)',
                        'default' => 'true',),
//      'color' => array('description' => 'Background color for message', // FIXME not supported in transport yet
    ),
    'global' => array(
      'token' => array('description' => 'Room notification token'),
      'from' => array('description' => 'Sender of notification'),
      //'url' => array('description' => 'API URL', 'tooltip' => 'Optional parameter, set this only for use with on-premise installations. When unset, the transport uses Atlassian hosted API.'),
    ),
  )
);

$config['alerts']['transports']['messagebird'] = array(
  'name' => 'Messagebird SMS',
  'identifiers' => array('recipient', 'originator'),
  'parameters' => array(
    'required' => array(
      'recipient' => array('description' => "Recipient's phone number"),
    ),
    'global' => array(
      'originator' => array('description' => 'Sender'),
      'accesskey' => array('description' => 'API access key', 'tooltip' => 'API key provided by MessageBird to send SMS (make sure this is for the "live" environment!)'),
    ),
  )
);

$config['alerts']['transports']['slack'] = array(
  'name' => 'Slack',
  'identifiers' => array('channel', 'username'),
  'parameters' => array(
    'required' => array(
      'channel' => array('description' => 'Channel name', 'default' => 'general'),
    ),
    'global' => array(
      'username' => array('description' => 'Username', 'default' => 'observium'),
      'url' => array('description' => 'Webhook URL', 'tooltip' => 'e.g. https://hooks.slack.com/services/TXXXXXXXX/BXXXXXXXX/XXXXXXXXXXXXXXXXXXXXXXXX'),
    ),
  )
);

$config['alerts']['transports']['pagerduty'] = array(
  'name' => 'PagerDuty',
  'identifiers' => array('service_key'),
  'parameters' => array(
    'required' => array(
      'service_key' => array('description' => 'PagerDuty contact GUID'),
    ),
  )
);

$config['alerts']['transports']['victorops'] = array(
  'name' => 'VictorOps',
  'identifiers' => array('routing_key'),
  'parameters' => array(
    'required' => array(
      'routing_key' => array('description' => 'Incident Routing Key', 'tooltip' => 'Used to route alerts to specific users or groups. Default "everyone"', 'default' => 'everyone'),
    ),
    'global' => array(
      'url' => array('description' => 'REST Endpoint URL', 'tooltip' => 'Obtained from VictorOps (Settings -> Integrations -> REST Endpoint -> Enable Integration)'),
    ),
  )
);


$config['alerts']['transports']['pushover'] = array(
  'name' => 'Pushover',
  'identifiers' => array('user'),
  'parameters' => array(
    'required' => array(
      'user' => array('description' => 'Destination key', 'tooltip' => "Recipient user or group key (not the user's e-mail address)"),
    ),
    'global' => array(
      'token' => array('description' => 'Application API token'),
    ),
  )
);

$config['alerts']['transports']['redoxygen'] = array(
  'name' => 'Red Oxygen',
  'identifiers' => array('acctid', 'email'),
  'parameters' => array(
    'required' => array(
      'acctid' => array('description' => 'Account ID'),
      'email' => array('description' => 'Email address'),
      'password' => array('description' => 'Password'),
      'recipient' => array('description' => 'Recipient/s'),
    ),
    'global' => array(
      'from' => array('description' => 'Originating system description', 'tooltip' => 'e.g. "Observium"'),
      'url' => array('description' => 'URL', 'tooltip' => 'e.g. https://redoxygen.net/sms.dll?Action=SendSMS'),
    ),
  )
);

$config['alerts']['transports']['smsbox'] = array(
  'name' => 'Kannel SMSBox',
  'identifiers' => array('phone'),
  'parameters' => array(
    'required' => array(
      'phone' => array('description' => "Recipient's phone number"),
    ),
  )
);

$config['alerts']['transports']['clickatell'] = array(
  'name' => 'Clickatell SMS',
  'identifiers' => array('recipient', 'originator'),
  'parameters' => array(
    'required' => array(
      'recipient' => array('description' => "Recipient's phone number"),
    ),
    'global' => array(
      'originator' => array('description' => 'Sender of SMS message'),
      'apiid' => array('description' => 'API access key to send SMS'),
      'username' => array('description' => 'API username'),
      'password' => array('description' => 'API password'),
    ),
  )
);

$config['alerts']['transports']['telegram'] = array(
  'name'        => 'Telegram Bot',
  'identifiers' => array('recipient'),
  'parameters'  => array(
    'required'  => array(
      'recipient' => array('description' => 'Chat Identifier')
    ),
    'global'    => array(
      'bot_hash'  => array('description' => 'Bot Token'),
    ),
    'optional'  => array(
      'disable_notification' => array('type'        => 'bool',
                                      'description' => 'Send silently',
                                      'tooltip'     => 'iOS users will not receive a notification, Android users will receive a notification with no sound. Values: true or false.'),
    ),
  )
);

$config['alerts']['transports']['script'] = array(
  'name'        => 'External program',
  'identifiers' => array('script'),
  'parameters'  => array(
    'required'  => array(
      'script'    => array('description' => 'External program path'),
    ),
  )
);

$config['alerts']['transports']['xmpp'] = array(
  'name'        => 'XMPP',
  'identifiers' => array('recipient'),
  'parameters'  => array(
    'required'  => array(
      'recipient' => array('description' => 'Recipient'),
    ),
    'global'    => array(
      'username'  => array('description' => 'XMPP Server username'),
      'password'  => array('description' => 'XMPP Server password'),
      'server'    => array('description' => 'XMPP Serrver hostname', 'tooltip' => 'Optional, if not specified, finds server hostname via SRV records'),
      'port'      => array('description' => 'XMPP Server port', 'tooltip' => 'Optional, defaults to 5222'),
    ),
  )
);

$config['alerts']['transports']['webhook-old'] = array(
  'name' => 'Webhook (Old)',
  'identifiers' => array('url'),
  'parameters' => array(
    'required' => array(
      'url' => array('description' => 'URL', 'tooltip' => 'e.g. https://webhook/api'),
    ),
    'global' => array(
      'token' => array('description' => 'Authentication token'),
      'originator' => array('description' => 'Sender of message'),
    ),
  )
);

$config['alerts']['transports']['webhook'] = array(
  'name' => 'Webhook',
  'identifiers' => array('url'),
  'parameters' => array(
    'required' => array(
      'url' => array('description' => 'URL', 'tooltip' => 'e.g. https://webhook/api'),
    ),
    'global' => array(
      //'token'    => array('description' => 'Authentication token'),
      //'username' => array('description' => 'Authentication username'),
      //'password' => array('description' => 'Authentication password'),
    ),
  )
);

$config['alerts']['transports']['opsgenie'] = array(
  'name' => 'OpsGenie',
  'identifiers' => array('api_key'),
  'parameters' => array(
    'optional' => array(
      'recipients' => array('description' => 'Recipient list'),
    ),
    'required' => array(
      'api_key' => array('description' => 'API Key')
    ),
  )
);


$config['alerts']['transports']['alertops'] = array(
  'name' => 'AlertOps',
  'identifiers' => array('url'),
  'parameters' => array(
    'required' => array(
      'url' => array('description' => 'Endpoint URL',),
    ),
    'optional' => array(
      'assignee' => array('description' => 'Assignee',),
    ),
  )
);

$config['alerts']['transports']['textlocal'] = array(
  'name' => 'Textlocal SMS',
  'identifiers' => array('recipient', 'originator'),
  'parameters' => array(
    'required' => array(
      'recipient' => array('description' => "Recipient's phone number", 'tooltip' => 'Destination number(s). Comma Separated, no spaces'),
    ),
    'global' => array(
      'originator' => array('description' => 'Sender', 'tooltip' => 'Source number shown on SMS'),
      'accesskey' => array('description' => 'API access key', 'tooltip' => 'API key provided by Textlocal to send SMS'),
    ),
  )
);

// EOF
