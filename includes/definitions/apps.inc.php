<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage webui
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

// Definitions related to the applications system

// Application graph definitions

$config['app']['apache']['top']            = array('bits', 'hits', 'scoreboard', 'cpu');
$config['app']['nginx']['top']             = array('connections', 'req');
$config['app']['bind']['top']              = array('req_in', 'answers', 'resolv_errors', 'resolv_rtt');
$config['app']['powerdns']['top']          = array('recursing', 'queries', 'querycache', 'latency');
$config['app']['powerdns-recursor']['top'] = array('queries', 'timeouts', 'cache', 'latency');
$config['app']['unbound']['top']           = array('queries', 'queue', 'memory', 'qtype');
$config['app']['nsd']['top']               = array('queries', 'memory', 'qtype', 'rcode');
$config['app']['mysql']['top']             = array('network_traffic', 'connections', 'command_counters', 'select_types');
$config['app']['postgresql']['top']        = array('xact', 'blks', 'tuples', 'tuples_query');
$config['app']['memcached']['top']         = array('bits', 'commands', 'data', 'items');
$config['app']['dhcpkit']['top']           = array('packets', 'msgtypes');
$config['app']['drbd']['top']              = array('disk_bits', 'network_bits', 'queue', 'unsynced');
$config['app']['ioping']['top']            = array('iops', 'speed', 'timing');
$config['app']['ntpd']['top']              = array('stats', 'freq', 'stratum', 'bits');
$config['app']['nfs']['top']               = array('nfs2','nfs3', 'nfs4');
$config['app']['nfsd']['top']              = array('rc','io','net','rpc','proc3');
$config['app']['shoutcast']['top']         = array('multi_stats', 'multi_bits');
$config['app']['freeradius']['top']        = array('authentication','accounting');
$config['app']['exim-mailqueue']['top']    = array('total');
$config['app']['zimbra']['top']            = array('threads','mtaqueue','fdcount');
$config['app']['crashplan']['top']         = array('bits', 'sessions', 'archivesize', 'disk');
$config['app']['asterisk']['top']          = array('activecall', 'peers');
$config['app']['kamailio']['top']          = array('shmen', 'core', 'usrloc', 'registrar');
$config['app']['mssql']['top']             = array('stats', 'cpu_usage', 'memory_usage');
$config['app']['openvpn']['top']           = array('nclients', 'bits');
$config['app']['postfix_mailgraph']['top'] = array('sent', 'spam','reject');
$config['app']['postfix_qshape']['top']    = array('stats');
$config['app']['lvs_stats']['top']         = array('connections', 'packets', 'bytes');
$config['app']['varnish']['top']           = array('backend', 'cache', 'lru');
$config['app']['vmwaretools']['top']       = array('mem', 'cpu');
$config['app']['dovecot']['top']           = array('connected');
$config['app']['exim']['top']              = array('sent', 'spam','reject');
// EOF
