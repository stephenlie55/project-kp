<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('NAMA_PERUSAHAAN','PT. BPR KREDIT MANDIRI INDONESIA');
define('ALAMAT_PERUSAHAAN','');

define('NAMA_APLIKASI','SUPPORT');

define('DB_DPM','dpm_online');
define('DB_TICKET_SUPPORT', 'ticket_support');

define('HOST_ONLINE','103.234.254.186:3308');
define('USER_ONLINE','test');
define('PASS_ONLINE','test123!');

// define('HOST_ONLINE','103.31.232.148:3307');
// define('USER_ONLINE','u2Qi7Jfui');
// define('PASS_ONLINE','qJ7ysIkg8ce!');

define('TB_USER','user');
define('TB_PARAMETER','parameter');
define('TB_DETAIL','rfm_new_detail');
define('TB_ATTACHMENT','rfm_new_attachment');
define('TB_COMMENT','rfm_new_comment');
define('TB_PROBLEM_TYPE','rfm_new_problem_type');
define('TB_REQUEST_TYPE','rfm_new_request_type');
define('TB_SYS_PESAN','sys_pesan');
define('TB_DAILY_ACTIVITY', 'daily_activity');

define('STT_ON_QUEUE','ON QUEUE');
define('STT_VALIDATED','VALIDATED');
define('STT_APPROVED','APPROVED');
define('STT_ON_PROGRESS','ON PROGRESS');
define('STT_DONE','DONE');
define('STT_REJECT','REJECT');
define('STT_ASSIGN','ASSIGN');
define('STT_PENDING','PENDING');
define('STT_SOLVED','SOLVED');

define('TYPE_RFP','rfp');
define('APP_HC', '764'); //abi
define('JABATAN_HEAD', ["AREA MANAGER", "DIREKTUR", "DEPARTMENT HEAD", "DEPARTMENT HEAD HC", "KEPALA KANTOR KAS", "PIMPINAN CABANG", "UNIT HEAD OPERATIONAL &AMP; SUPPORT", "UNIT HEAD OPERASIONAL MICRO FINANCING", "UNIT HEAD OPERASIONAL DEVELOPMENT"]);
define('JABATAN_HC', ["COMPENSATION AND BENEFIT HEAD", "COMPENSATION AND BENEFIT SPECIALIST", "HC LEARNING &AMP; DEVELOPMENT SECURE FINANCING SPC", "HC PERSONALIA", "HC RECRUITMENT", "HC RECRUITMENT SPECIALIST", "HC TRAINING", "HEAD HUMAN CAPITAL", "HUMAN CAPITAL AREA HEAD", "HUMAN CAPITAL BUSINESS PARTNER HEAD", "SUPERVISOR HUMAN CAPITAL AREA"]);
define('JABATAN_HEAD_SPV', ["ADMIN HEAD OFFICE", "BRANCH DEVELOPMENT AND MONITORING DEPARTEMEN HEAD", "COMPENSATION AND BENEFIT HEAD", "CREDIT ANALYST HEAD", "DEPARTMENT HEAD", "DEPARTMENT HEAD HC", "DEPARTMENT HEAD SECURE FINANCING", "DISTRICT MICRO HEAD", "FUNDING HEAD", "HEAD ACCOUNTING", "Head Cabang", "HEAD COLLECTOR", "HEAD DEPT BISNIS", "HEAD FINANCE", "HEAD HUMAN CAPITAL", "HEAD IT", "HEAD KOLEKTOR", "HEAD LEGAL", "HEAD MARKETING", "HEAD MARKETING KARAWANG", "Head Op", "HEAD OPERASIONAL", "HEAD OPERASIONAL BEKASI BSD", "HEAD OPERASIONAL BSD", "HEAD OPERASIONAL CITEUREUP", "HEAD OPERASIONAL KARAWANG", "HEAD OPERATIONAL", "HEAD OPS", "HEAD OPS CITEUREUP", "HEAD OPS SINBAR PJS", "HEAD SKAI DAN FA", "HEADKASCIKARANG_PJS", "HEADOPSBEKUT_PJS", "HEADOPSBSD_PJS", "HEADOPSKARAWANG_CADANGAN", "HEADOPS_CIKARANG_PJS", "HUMAN CAPITAL AREA HEAD", "HUMAN CAPITAL BUSINESS PARTNER HEAD", "TELE CENTER HEAD", "UM MICRO HEAD", "UNIT HEAD AUDIT MIKRO", "UNIT HEAD COLLECTION", "UNIT HEAD FIELD INVESTIGATION", "UNIT HEAD HO", "UNIT HEAD LITIGASI", "UNIT HEAD MIKRO", "UNIT HEAD OPERASIONAL MICRO FINANCING", "UNIT HEAD OPERATIONAL &AMP; SUPPORT", "UNIT HEAD QUALITY SERVICE", "UNIT HEAD RESEARCH &AMP; DEVELOPMENT", "UNIT MICRO HEAD", "DISTRICT COLLECTION SUPERVISOR", "SUPERVISOR BDD", "SUPERVISOR HUMAN CAPITAL AREA", "SUPERVISOR INTERNAL AUDIT", "SUPERVISOR IT", "SUPERVISOR OPERASIONAL", "SUPERVISOR RESEARCH &AMP; DEVELOPMENT", "SUPERVISOR TELE CENTER"]);