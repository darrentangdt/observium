#! /usr/bin/env python
"""
 Observium

   This file is part of Observium.

 @package    observium
 @subpackage poller
 @author     Job Snijders <job.snijders@atrato.com>
 @copyright  (C) 2013-2014 Job Snijders, (C) 2014-2017 Observium Limited
"""

"""
 observium-wrapper A small tool which wraps around the Observium poller/discovery/billing
                   and tries to guide the relevant process with a more modern
                   approach with a Queue and workers

 Original:      Job Snijders <job.snijders@atrato.com>
 Rewritten:     Most code parts rewritten since 2014 by Observium developers

 Usage:         This program accepts process name as first command line argument,
                that should run simultaneously. For other options see help -h

                In /etc/cron.d/observium replace this (or the equivalent) poller entry:
                */5 *  * * *   root /opt/observium/poller.php -h all >> /dev/null 2>&1
                with something like this:
                */5 *  * * *   root /opt/observium/observium-wrapper poller >> /dev/null 2>&1

                In /etc/cron.d/observium replace this (or the equivalent) discovery entries:
                34 */6 * * *   root /opt/observium/discovery.php -h all >> /dev/null 2>&1
                */5 *  * * *   root /opt/observium/discovery.php -h new >> /dev/null 2>&1
                with something like this:
                34 */6 * * *   root /opt/observium/observium-wrapper discovery >> /dev/null 2>&1
                */5 *  * * *   root /opt/observium/observium-wrapper discovery --host new >> /dev/null 2>&1

                In /etc/cron.d/observium replace this (or the equivalent) billing entries:
                */5 *  * * *   root /opt/observium/poll-billing.php >> /dev/null 2>&1
                with something like this:
                */5 *  * * *   root /opt/observium/observium-wrapper billing >> /dev/null 2>&1

 Ubuntu Linux:  apt-get install python-mysqldb
 RHEL/CentOS:   yum install MySQL-python (Requires the EPEL repo!)
 FreeBSD:       cd /usr/ports/*/py-MySQLdb && make install clean

 Tested on:     Python 2.7.3  / PHP 5.3.10-1ubuntu3.4 / Ubuntu 12.04 LTS
                Python 3.2.3  / PHP 5.3.27-1ubuntu3.6 / Ubuntu 12.04 LTS
                Python 2.7.6  / PHP 5.5.9-1ubuntu4.14 / Ubuntu 14.04 LTS
                Python 3.4.3  / PHP 5.5.9-1ubuntu4.14 / Ubuntu 14.04 LTS
                Python 2.7.12 / PHP 7.0.13            / Ubuntu 16.04 LTS
                Python 3.5.2  / PHP 7.0.13            / Ubuntu 16.04 LTS

"""

"""
    Import required modules
"""

try:
    # Import time module first for more accurate start time
    import time
    # start time
    s_time = time.time()
except:
    print("ERROR: missing python module: time")
    sys.exit(2)

try:
    # Required modules
    import threading, sys, subprocess, os, json
except:
    print("ERROR: missing one or more of the following python modules:")
    print("threading, sys, subprocess, os, json")
    sys.exit(2)

"""
    Register global exepthook for ability stop execute wrapper by Ctrl+C
    See: https://stackoverflow.com/questions/6598053/python-global-exception-handling
"""

def new_except_hook(exctype, value, traceback):
    if exctype == KeyboardInterrupt:
        print("\n\nExiting by CTRL+C.\n\n")
        sys.exit(2)
    else:
        sys.__excepthook__(exctype, value, traceback)
sys.excepthook = new_except_hook

"""
    Definition for write msg to log file
"""

def logfile(msg):
    msg = "[%s] %s(%s): %s/%s: %s\n" % (time.strftime("%Y/%m/%d %H:%M:%S %z"), scriptname, os.getpid(), config['install_dir'], scriptname, msg)
    f = open(log_path,'a')
    f.write(msg)
    f.close()

# base script name
scriptname      = os.path.basename(sys.argv[0])

# major/minor python version: (2,7), (3,2), etc
python_version  = sys.version_info[:2]

if python_version < (3,0):
    try:
        import Queue
    except:
        print("ERROR: missing python module: Queue")
        sys.exit(2)
    try:
        import MySQLdb
    except:
        print("ERROR: missing python module: MySQLdb")
        print("On Ubuntu: apt-get install python-mysqldb")
        print("On RHEL/CentOS: yum install MySQL-python")
        print("On FreeBSD: cd /usr/ports/*/py-MySQLdb && make install clean")
        sys.exit(2)
else:
    try:
        import queue as Queue
    except:
        print("ERROR: missing python module: queue")
        sys.exit(2)
    # MySQLdb not avialable for python3
    # http://stackoverflow.com/questions/4960048/python-3-and-mysql
    try:
        import pymysql as MySQLdb
        MySQLdb.install_as_MySQLdb()
        import MySQLdb
    except:
        print("ERROR: missing python module: pymysql")
        print(" On Ubuntu: sudo apt-get install python3-setuptools")
        print("            sudo easy_install3 pip")
        print("            sudo pip3 install PyMySQL")
        # FIXME. I not know how install on RHEL and FreeBSD
        print(" On other OSes install pip for python3 and than run from root user:")
        print("            pip3 install PyMySQL")
        #print(" On RHEL/CentOS: yum install MySQL-python")
        #print(" On FreeBSD: cd /usr/ports/*/py-MySQLdb && make install clean")
        sys.exit(2)


"""
    Parse Arguments
    Attempt to use argparse module.  Probably want to use this moving forward
    especially as more features want to be added to this wrapper.
    and
    Take the amount of threads we want to run in parallel from the commandline
    if None are given or the argument was garbage, fall back to default of 8
"""
try:
    import argparse
    parser = argparse.ArgumentParser(description='Process Wrapper for Observium')
    parser.add_argument('process', nargs='?', default='poller', help='Process name, one of \'poller\', \'discovery\', \'billing\'.')
    parser.add_argument('-w', '--workers', nargs='?', type=int, default=0, help='Number of threads to spawn. Defauilt: CPUs x 2')
    parser.add_argument('-s', '--stats', action='store_true', help='Store total polling times to RRD.', default=False)
    parser.add_argument('-i', '--instances', nargs='?', type=int, default=-1, help='Process instances count.')
    parser.add_argument('-n', '--number', nargs='?', type=int, default=-1, help='Instance id (number), must start from 0 and to be less than instances count.')
    parser.add_argument('-d', '--debug', action='store_true', help='Enable debug output. WARNING, do not use this option unless you know what it does. This generates a lot of very huge files in TEMP dir.', default=False)
    parser.add_argument('-t', '--test', action='store_true', help='Do not spawn processes, just test DB connection, config, etc.', default=False)
    parser.add_argument('--host', help='Process hostname wildcard.')

    # Parse passed arguments
    args = parser.parse_args()

    # for compatability with old passed argument with worker
    try:
        # poller-wrapper.py 16
        workers = int(args.process)
        process = 'poller'
        print("Please replace your poller wrapper command line with:")
        print("observium-wrapper poller -w %s" % workers)
    except ValueError:
        # observium-wrapper poller -w 16
        process = args.process
        workers = int(args.workers)

    instances_count   = int(args.instances)
    instance_number   = int(args.number)
    stats = args.stats
    debug = args.debug
    test  = args.test
except ImportError:
    ### FIXME, do not use this compatability, just stop execute here
    print("WARNING: missing the argparse python module:")
    print("On Ubuntu: apt-get install libpython%s.%s-stdlib" % python_version)
    print("On RHEL/CentOS: yum install python-argparse")
    print("On Debian: apt-get install python-argparse")
    print("Continuing with basic argument support.")
    instances_count   = 1
    instance_number   = 0
    stats = False
    debug = False
    test  = False
    try:
        # poller-wrapper.py 16
        workers = int(sys.argv[1])
        process = 'poller'
    except:
        # observium-wrapper poller -w 16
        process = sys.argv[1]
        if not process:
            process = 'poller'
        workers = 0

if test:
    print("Script: %s, Prosess: %s, Workers: %s, Stats: %s, Debug: %s, Test: %s" % (scriptname, process, workers, stats, debug, test))

"""
    Allowed process list, if not one of this exit with error
"""

process_list = ['poller', 'discovery', 'billing']
if process not in process_list:
    print("ERROR: Incorrect process name '%s' passed, should be one of: %s" % (process, process_list))
    sys.exit(2)

# entity name for log and messages
if process == 'billing':
    entity = 'bill'
else:
    entity = 'device'

# full process name, ie: 'observium-wrapper poller'
processname = scriptname + ' ' + process

"""
    Fetch configuration details from the config_to_json.php script
"""

ob_install_dir = os.path.dirname(os.path.realpath(__file__))
config_file = ob_install_dir + '/config.php'

def get_config_data():
    config_cmd = ['/usr/bin/env', 'php', '%s/config_to_json.php' % ob_install_dir]
    try:
        proc = subprocess.Popen(config_cmd, stdout=subprocess.PIPE, stdin=subprocess.PIPE)
    except:
        print("ERROR: Could not execute: %s" % config_cmd)
        sys.exit(2)
    return proc.communicate()[0].decode('utf-8') # decode required in python3

try:
    with open(config_file) as f: pass
except IOError as e:
    print("ERROR: Oh dear... %s does not seem readable" % config_file)
    sys.exit(2)

try:
    config = json.loads(get_config_data())
except:
    print("ERROR: Could not load or parse observium configuration, are PATHs correct?")
    sys.exit(2)

db_username  = config['db_user']
db_password  = config['db_pass']
db_server    = config['db_host']
db_dbname    = config['db_name']
try:
    db_port  = int(config['db_port'])
except KeyError:
    db_port  = 3306
try:
    db_socket = config['db_socket']
except KeyError:
    db_socket = False

poller_path     = config['install_dir'] + '/poller.php'
discovery_path  = config['install_dir'] + '/discovery.php'
alerter_path    = config['install_dir'] + '/alerter.php'
billing_path    = config['install_dir'] + '/poll-billing.php'
#temp_path       = config['temp_dir']
temp_path       = '/tmp'
try:
    rrd_path = config['rrd_dir']
except KeyError:
    rrd_path = config['install_dir'] + '/rrd'
try:
    log_path = config['log_dir'] + '/observium.log'
except KeyError:
    log_path = config['install_dir'] + '/logs/observium.log'

# Amount of threads
try:
    # use config option if set
    amount_of_workers = int(config['poller-wrapper']['threads'])
except KeyError:
    amount_of_workers = 0

if amount_of_workers < 1 and workers > 0:
    # use amount threads passed as argument
    amount_of_workers = workers

if amount_of_workers < 1:
    try:
        # use threads count based on cpus count
        import multiprocessing
        amount_of_workers = multiprocessing.cpu_count() * 2
    except (ImportError, NotImplementedError):
        amount_of_workers = 8
        print("WARNING: used default threads number %s. For change use configuration option or pass as argument -w [THREADS]" % (amount_of_workers))

if os.path.isfile(alerter_path):
    alerting = config['poller-wrapper']['alerter']
else:
    alerting = False

if stats == False:
    try:
        stats = bool(config['poller-wrapper']['stats'])
    except KeyError:
        pass

# max running and max LA
try:
    max_running = int(config['poller-wrapper']['max_running'])
except:
    max_running = 3
if max_running < 1:
    max_running = 3

try:
    max_la = float(config['poller-wrapper']['max_la'])
except:
    max_la = 10
if max_la <= 0:
    max_la = 10

"""
    Check mibs dir for stale .index files
"""

try:
    import glob

    # use this cleanup in discovery --host new (not in poller)
    if process == 'discovery':
        mib_indexes  = glob.glob(config['mib_dir'] + '/.index')
        mib_indexes += glob.glob(config['mib_dir'] + '/*/.index')
        #print(mib_indexes)
        for mib_index in mib_indexes:
            #print(mib_index)
            try:
                os.remove(mib_index)
            except:
                # break loop because not permitted to remove files in mibs dir
                print("WARNING: .index files are found in mibs directories which can't be removed (there aren't enough permissions)")
                print("         see: http://www.observium.org/docs/faq/#all-my-hosts-seem-down-to-observium-snmp-doesnt-seem-to-work-anymore")
                logfile("WARNING: .index files are found in mibs directories which can't be removed (there aren't enough permissions)")
                break

except:
    pass

#sys.exit(2)

real_duration = 0
per_device_duration = {}

devices_list = []

try:
    if bool(db_socket) == False:
        db = MySQLdb.connect(host=db_server, user=db_username, passwd=db_password, db=db_dbname, port=db_port)
    else:
        db = MySQLdb.connect(host=db_server, user=db_username, passwd=db_password, db=db_dbname, port=db_port, unix_socket=db_socket)
    #db.autocommit(True)
    cursor = db.cursor()
except:
    print("ERROR: Could not connect to MySQL database!")
    logfile("ERROR: Could not connect to MySQL database!")
    sys.exit(2)

""" 
    This query specifically orders the results depending on the last_polled_timetaken variable
    Because this way, we put the devices likely to be slow, in the top of the queue
    thus greatening our chances of completing _all_ the work in exactly the time it takes to 
    poll the slowest device! cool stuff he
    Additionally, if a hostname wildcard is passed, add it to the where clause.  This is
    important in cases where you have pollers distributed geographically and want to limit
    pollers to polling hosts matching their geographic naming scheme.
"""

stop_script = False # trigger for stop execute script inside try

if instances_count > 1 and instance_number >= 0 and (instance_number < instances_count):
    # Use distributed wrapper
    if process == 'billing':
        # billing
        query = """SELECT bill_id FROM (SELECT @rownum :=0) r,
                   (
                      SELECT @rownum := @rownum +1 AS rownum, bill_id
                      FROM bills
                      ORDER BY bill_id ASC
                  ) temp
                WHERE MOD(temp.rownum, %s) = %s"""
    else:
        # poller or discovery
        query = """SELECT device_id FROM (SELECT @rownum :=0) r,
                   (
                      SELECT @rownum := @rownum +1 AS rownum, device_id
                      FROM devices
                      WHERE disabled != 1
                      ORDER BY device_id ASC
                  ) temp
                WHERE MOD(temp.rownum, %s) = %s"""
    cursor.execute(query, (instances_count, instance_number))

    # Increase maximum allowed running wrapper processes by instances count
    max_running *= instances_count
else:
    # set instances count and number for single process wrapper
    instances_count = 1
    instance_number = 0

    if process == 'billing':
        # Normal billing poll
        query = """SELECT   bill_id
                   FROM     bills"""
        order =  " ORDER BY bill_id ASC"
        query = query + order
        cursor.execute(query)

    else:
        # Normal poller/discovery
        query = """SELECT   device_id
                   FROM     devices
                   WHERE    disabled != '1'"""
        if process == 'discovery':
            order =  " ORDER BY last_discovered_timetaken DESC"
        else:
            order =  " ORDER BY last_polled_timetaken DESC"

        # skip down devices in discovery
        if process == 'discovery':
            query = query + " AND status = '1'"

        try:
            host_wildcard = args.host.replace('*', '%')

            # expand process name for do not calculate count inside main processes
            processname = processname + ' --host ' + host_wildcard

            # new devices discovery just pass ./discovery.php -h new, do not spawn processes
            if process == 'discovery' and host_wildcard == 'new':
                if debug == True:
                    command_options = '-d -h ' + host_wildcard
                else:
                    command_options = '-q -h ' + host_wildcard
                print("INFO: starting discovery.php for %s" % host_wildcard)
                #print("/usr/bin/env php %s %s >> /dev/null 2>&1" % (discovery_path, command_options))
                os.system("/usr/bin/env php %s %s >> /dev/null 2>&1" % (discovery_path, command_options))
                print("INFO: finished discovery.php for %s" % host_wildcard)
                #sys.exit(0) # this not worked inside try, see: https://stackoverflow.com/questions/7709411/why-finally-block-is-executing-after-calling-sys-exit0-in-except-block
                stop_script = True
                #wc_query = query + " AND (last_discovered IS NULL OR last_discovered = '0000-00-00 00:00:00' OR force_discovery = '1')"
                #cursor.execute(wc_query)
            else:
                wc_query = query + " AND hostname LIKE %s " + order
                cursor.execute(wc_query, (host_wildcard,))
            #print(wc_query)
        except:
            query = query + order
            #print(query)
            cursor.execute(query)

# stop script execute after try
if stop_script == True:
    sys.exit(0)

devices = cursor.fetchall()
for row in devices:
    devices_list.append(int(row[0]))

#print(devices_list)
#sys.exit(2)

"""
    Get current wrapper process info and remove stale db entries
"""

pid     = os.getpid()
ppid    = os.getppid()
uid     = os.getuid()
la      = os.getloadavg()
try:
  command = subprocess.check_output('ps -ww -o args -p %s' % (pid), shell=True, universal_newlines=True).splitlines()[1]
except:
  command = scriptname


"""
    Search if same poller wrapper proccesses running
    Protect from race condition
"""

ps_count = 0

p_query = """SELECT `process_id`, `process_pid`, `process_ppid`, `process_uid`, `process_command`, `process_start`
             FROM   `observium_processes`
             WHERE  `process_name` = %s"""
try:
  cursor.execute(p_query, (processname,))
  for row in cursor.fetchall():
    # print(row)
    test_running = False
    test_id, test_pid, test_ppid, test_uid, test_command, test_start = row
    try:
      test_ps = subprocess.check_output('ps -ww -o ppid,uid,args -p %s' % (test_pid), shell=True, universal_newlines=True).splitlines()[1]
      # print(test_ps)
      test_ps = test_ps.split(None, 2)
      # print(test_ps)
      # print("PPID: %s, %s" % (test_ppid, int(test_ps[0])))
      # print(" UID: %s, %s" % (test_uid, int(test_ps[1])))
      # print("name: %s, %s" % (processname, test_ps[2]))
      test_running = (test_ppid == int(test_ps[0])) and (test_uid == int(test_ps[1])) and (scriptname in test_ps[2])
    except:
      #not exist pid
      pass

    #print("Test: %s" % (test_running))
    if test_running == False:
      # process not exist, remove stale db entry
      try:
        cursor.execute("""DELETE FROM `observium_processes` WHERE `process_id` = %s""", (test_id,))
        db.commit()
        # print("Removed stale DB entry %s" % test_id)
      except:
        pass
    else:
      ps_count += 1
      # print("Count: %s" % (ps_count))

except:
  try:
    ### FIXME. Remove this compatability, always use from db process counts!
    ps_list = subprocess.check_output('ps ax | grep %s | grep -v grep' % (scriptname), shell=True, universal_newlines=True)
    # divide by 2 because cron starts 2 processes (/bin/sh and main process)
    ps_count = len(ps_list.splitlines()) / 2
    ps_count -= 1 # decrease current process
  except:
    # Skip searching, something wrong
    pass

# This prevents race and too high LA on server. Default is 4 processes and 10 load average. More than 4 already running poller-wrapper it's big trouble!
if ps_count > max_running and la[1] >= max_la:
  print("URGENT: %s not started because already running %s processes, load average (5min) %.2f" % (processname, ps_count, la[1]))
  logfile("URGENT: %s not started because already running %s processes, load average (5min) %.2f" % (processname, ps_count, la[1]))
  sys.exit(2)

# Increase count by current wrapper process
ps_count += 1

# write into db current process info
p_query = """INSERT INTO `observium_processes` (`process_pid`,`process_ppid`,`process_name`,`process_uid`,`process_command`,`process_start`,`device_id`)
             VALUES (%s,%s,%s,%s,%s,%s,'0')"""
try:
  cursor.execute(p_query, (pid,ppid,processname,uid,command,s_time))
  process_id = db.insert_id()
  db.commit()
except:
  pass

if test == True:
  print("Already running %s processes: %s, load average (5min) %.2f" % (processname, ps_count, la[1]))
  #print(process_id)
  #time.sleep(30) # delays for 30 seconds
  p_query = """DELETE FROM `observium_processes` WHERE `process_id` = %s"""
  cursor.execute(p_query, (process_id,))
  db.commit()
  sys.exit(2)


"""
    Since ./discovery -h all additionally do db schema update,
    also do this here before wrap discovery processes.
    Check if this is first process instance and --host parameter not passed
"""
if process == 'discovery' and instance_number == 0 and 'host_wildcard' not in globals():
    #print("/usr/bin/env php %s -q -u >> /dev/null 2>&1" % (discovery_path))
    if debug == True:
        command_options = '-d -u'
    else:
        command_options = '-q -u'

    print("INFO: starting discovery.php for update")
    os.system("/usr/bin/env php %s %s >> /dev/null 2>&1" % (discovery_path, command_options))
    print("INFO: finished discovery.php for update")


"""
    A seperate queue and a single worker for printing information to the screen prevents
    the good old joke:

        Some people, when confronted with a problem, think, 
        "I know, I'll use threads," and then two they hav erpoblesms.
"""

def printworker():
    while True:
        worker_id, device_id, elapsed_time = print_queue.get()
        global real_duration
        global per_device_duration
        real_duration += elapsed_time
        per_device_duration[device_id] = elapsed_time

        if elapsed_time < 300:
            print("INFO: worker %s finished %s %s in %s seconds" % (worker_id, entity, device_id, elapsed_time))
        else:
            print("WARNING: worker %s finished %s %s in %s seconds" % (worker_id, entity, device_id, elapsed_time))
        print_queue.task_done()

"""
    This class will fork off single instances of the poller.php process, record
    how long it takes, and push the resulting reports to the printer queue
"""

def process_worker():
    command_paths = {
        'poller': poller_path,
        'discovery': discovery_path,
        'billing': billing_path
    }
    process_path = command_paths[process]

    if process == 'billing':
        # billing use -b [bill_id]
        command_options = '-b'
    else:
        # poller/discovery us -h [device_id]
        command_options = '-h'

    if debug == True:
        command_options = '-d ' + command_options
    else:
        command_options = '-q ' + command_options

    while True:
        device_id = process_queue.get()
        try:
            start_time = time.time()
            if debug == True:
                debug_file = temp_path + '/observium_' + process + '_' + str(device_id) + '.debug'
                command = "/usr/bin/env php %s %s %s >> %s 2>&1"        % (process_path, command_options, device_id, debug_file)
            else:
                command = "/usr/bin/env php %s %s %s >> /dev/null 2>&1" % (process_path, command_options, device_id)
            print(command) #debug
            subprocess.check_call(command, shell=True)

            # additional alerter process only after poller process
            if process == 'poller' and alerting == True:
                print("INFO: starting alerter.php for %s" % device_id)
                if debug == True:
                    debug_file = temp_path + '/observium_alerter_' + str(device_id) + '.debug'
                    command = "/usr/bin/env php %s -d -h %s >> %s 2>&1" % (alerter_path, device_id, debug_file)
                else:
                    command = "/usr/bin/env php %s -q -h %s >> /dev/null 2>&1" % (alerter_path, device_id)
                print("INFO: finished alerter.php for %s" % device_id)
                subprocess.check_call(command, shell=True)
            elapsed_time = int(time.time() - start_time)
            print_queue.put([threading.current_thread().name, device_id, elapsed_time])
        except (KeyboardInterrupt, SystemExit):
            raise
        except:
            pass
        process_queue.task_done()

process_queue = Queue.Queue()
print_queue = Queue.Queue()

print("INFO: starting the %s at %s with %s threads" % (process, time.strftime("%Y/%m/%d %H:%M:%S"), amount_of_workers))
if debug == True:
    print("WARNING: DEBUG enabled, each %s %s store output to %s/observium_%s_id.debug (where id is %s_id)" % (entity, process, temp_path, process, entity))

for device_id in devices_list:
    process_queue.put(device_id)
 
for i in range(amount_of_workers):
    t = threading.Thread(target=process_worker)
    t.setDaemon(True)
    t.start()

p = threading.Thread(target=printworker)
p.setDaemon(True)
p.start()

try:
    process_queue.join()
    print_queue.join()
except (KeyboardInterrupt, SystemExit):
    raise

total_time = time.time() - s_time

la  = os.getloadavg() # get new load average after polling
msg = "processed %s %ss in %.2f seconds with %s threads, load average (5min) %.2f" % (len(devices_list), entity, total_time, amount_of_workers, la[1])
print("INFO: %s %s\n" % (processname, msg))
logfile(msg)

show_stopper = False
exit_code = 0
if total_time > 300:
    recommend = int(total_time / 300.0 * amount_of_workers + 1)
    print("WARNING: the process took more than 5 minutes to finish, you need faster hardware or more threads")
    print("INFO: in sequential style processing the elapsed time would have been: %s seconds" % real_duration)
    for device in per_device_duration:
        if per_device_duration[device] > 300:
            print("WARNING: device %s is taking too long: %s seconds" % (device, per_device_duration[device]))
            show_stopper = True
    if show_stopper == True:
        print("ERROR: Some devices are taking more than 300 seconds, the script cannot recommend you what to do.")
    if show_stopper == False:
        print("WARNING: Consider setting a minimum of %d threads. (This does not constitute professional advice!)" % recommend)
    exit_code = 1

"""
    Get and update poller wrapper stats
"""
if process == 'poller' and stats == True:
    import socket
    localhost = socket.getfqdn()
    if localhost.find('.') == 0:
        localhost_t = subprocess.check_output(["/bin/hostname", "-f"], universal_newlines=True).strip()
        if localhost_t.find('.') > 0:
            localhost = localhost_t

    poller_stats   = {'instances_count': instances_count,
                      'wrapper_count':   ps_count}
    instance_stats = {
                      'hostname':        localhost,
                      'instances_count': instances_count, # this is need for check in calculate average
                      'workers_count':   amount_of_workers,
                      'devices_count':   len(devices_list),
                      'devices_ids':     devices_list,
                      'last_starttime':  '%.3f' % s_time,
                      'last_runtime':    '%.3f' % total_time,
                      'last_status':     exit_code,
                      #'last_message':    msg
                     }

    instances_clean      = []
    instances_total_time = total_time
    instances_total_devices = len(devices_list)
    i                    = 1

    query = """SELECT attrib_type, attrib_value FROM observium_attribs WHERE attrib_type LIKE 'poller_wrapper_instance_%'"""
    cursor.execute(query)

    for row in cursor.fetchall():
        #print(row)
        n = int(row[0].replace('poller_wrapper_instance_', '')) # get stat for instance number n
        if n >= instances_count:
            # seems as old stats, instances count reduced
            instances_clean.append(row[0])
        elif n == instance_number:
            # current instance, skip
            pass
        else:
            instance = json.loads(row[1])
            if int(instance['instances_count']) == instances_count:
                #print(instance)
                i += 1
                instances_total_time    += float(instance['last_runtime'])
                instances_total_devices += int(instance['devices_count'])
            else:
                instances_clean.append(row[0])

    instances_average_time = instances_total_time / i
    poller_stats['total_devices_count'] = instances_total_devices
    poller_stats['total_runtime']       = '%.3f' % instances_total_time
    poller_stats['average_runtime']     = '%.3f' % instances_average_time

    #print(poller_stats)
    #print(instance_stats)
    #print(instances_clean)

    # write instance and total stats into db
    cursor.execute("UPDATE `observium_attribs` SET `attrib_value` = %s WHERE `attrib_type` = %s", (json.dumps(instance_stats), 'poller_wrapper_instance_' + str(instance_number)))
    db.commit()
    #print("Number of rows updated: %d" % cursor.rowcount)
    if cursor.rowcount == 0:
        cursor.execute("INSERT INTO `observium_attribs` (`attrib_value`,`attrib_type`) VALUES (%s,%s)", (json.dumps(instance_stats), 'poller_wrapper_instance_' + str(instance_number)))
        db.commit()

    cursor.execute("UPDATE `observium_attribs` SET `attrib_value` = %s WHERE `attrib_type` = %s", (json.dumps(poller_stats), 'poller_wrapper_stats'))
    db.commit()
    print("Number of rows updated: %d" % cursor.rowcount)
    if cursor.rowcount == 0:
        cursor.execute("INSERT INTO `observium_attribs` (`attrib_value`,`attrib_type`) VALUES (%s,%s)", (json.dumps(poller_stats), 'poller_wrapper_stats'))
        db.commit()

    # clean stale instance stats
    for row in instances_clean:
        cursor.execute("DELETE FROM `observium_attribs` WHERE `attrib_type` = %s", (row,))
        db.commit()

    # Write poller statistics to RRD
    rrd_path_total = rrd_path + '/poller-wrapper.rrd'
    if os.path.isfile(rrd_path_total) == False:
        # Create RRD
        rrd_dst = ':GAUGE:' + str(config['rrd']['step'] * 2) + ':0:U'
        cmd_create = config['rrdtool'] + ' create ' + rrd_path_total + ' DS:devices' + rrd_dst + ' DS:totaltime' + rrd_dst + ' DS:threads' + rrd_dst
        cmd_create += ' --step ' + str(config['rrd']['step']) + ' ' + ' '.join(config['rrd']['rra'].split())
        os.system(cmd_create)
        logfile(cmd_create)
        if debug == True:
            print("DEBUG: " + cmd_create)
    cmd_update = config['rrdtool'] + ' update ' + rrd_path_total + ' N:%s:%s:%s' % (poller_stats['total_devices_count'], poller_stats['average_runtime'], amount_of_workers)
    os.system(cmd_update)
    if debug == True:
        print("DEBUG: " + cmd_update)

    # Write poller wrapper count statistics to RRD
    rrd_path_count = rrd_path + '/poller-wrapper_count.rrd'
    if os.path.isfile(rrd_path_count) == False:
        # Create RRD
        rrd_dst = ':GAUGE:' + str(config['rrd']['step'] * 2) + ':0:U'
        cmd_create = config['rrdtool'] + ' create ' + rrd_path_count + ' DS:wrapper_count' + rrd_dst
        cmd_create += ' --step ' + str(config['rrd']['step']) + ' ' + ' '.join(config['rrd']['rra'].split())
        os.system(cmd_create)
        logfile(cmd_create)
        if debug == True:
            print("DEBUG: " + cmd_create)
    cmd_update = config['rrdtool'] + ' update ' + rrd_path_count + ' N:%s' % (poller_stats['wrapper_count'])
    os.system(cmd_update)
    if debug == True:
        print("DEBUG: " + cmd_update)

    if instances_count > 1:
        # Per instance statistics
        rrd_path_instance = rrd_path + '/poller-wrapper_' + str(instances_count) + '_' + str(instance_number) + '.rrd'
        if os.path.isfile(rrd_path_instance) == False:
            # Create RRD
            rrd_dst = ':GAUGE:' + str(config['rrd']['step'] * 2) + ':0:U'
            cmd_create = config['rrdtool'] + ' create ' + rrd_path_instance + ' DS:devices' + rrd_dst + ' DS:totaltime' + rrd_dst + ' DS:threads' + rrd_dst
            cmd_create += ' --step ' + str(config['rrd']['step']) + ' ' + ' '.join(config['rrd']['rra'].split())
            os.system(cmd_create)
            logfile(cmd_create)
            if debug == True:
                print("DEBUG: " + cmd_create)
        cmd_update = config['rrdtool'] + ' update ' + rrd_path_instance + ' N:%s:%s:%s' % (instance_stats['devices_count'], instance_stats['last_runtime'], amount_of_workers)
        os.system(cmd_update)
        if debug == True:
            print("DEBUG: " + cmd_update)

# Remove process info from DB
p_query = """DELETE FROM `observium_processes` WHERE `process_id` = %s"""
try:
  cursor.execute(p_query, (process_id,))
  db.commit()
except:
  pass

db.close()

# Return exit code
sys.exit(exit_code)

# EOF
