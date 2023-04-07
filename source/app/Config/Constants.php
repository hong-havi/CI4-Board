<?php

//--------------------------------------------------------------------
// App Namespace
//--------------------------------------------------------------------
// This defines the default Namespace that is used throughout
// CodeIgniter to refer to the Application directory. Change
// this constant to change the namespace that all application
// classes should use.
//
// NOTE: changing this will require manually modifying the
// existing namespaces of App\* namespaced-classes.
//
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
|--------------------------------------------------------------------------
| Composer Path
|--------------------------------------------------------------------------
|
| The path that Composer's autoload file is expected to live. By default,
| the vendor folder is in the Root directory, but you can customize that here.
*/
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
|--------------------------------------------------------------------------
| Timing Constants
|--------------------------------------------------------------------------
|
| Provide simple ways to work with the myriad of PHP functions that
| require information to be in seconds.
*/
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

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
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

// defined("IP");
// defined("MENU");

define('DEV_IP_ARR',['127.0.0.1']);

define('PAGE_CACHE_TIME',0);

define('CSS_VERSION','1.2');
define('CSS_U_VERSION','1.12');
define('JS_VERSION','1.3');
define('JS_U_VERSION','1.8');


define('ENC_KEY_DEF','');
define('ENC_KEY_TOKEN','');


define('FILE_PATH','/home/data/');
define('FILE_PATH_DEV','');
define('FILE_URL', 'https://localhost/');
define('IMG_URL', '//localhost/common/attach/viewimg/');
define('IMG_URL_DEV', '//localhost/common/attach/viewimg/');



define('DB_S_db', 'ci4board');
define('DB_S_smsdb', 'sms_all');

define('DB_T_s_mbrip',          DB_S_db.".board_s_mbrip");
define('DB_T_s_mbrid',          DB_S_db.".board_s_mbrid");
define('DB_T_s_mbrdata',        DB_S_db.".board_s_mbrdata");
define('DB_T_s_mbrdata_sub',    DB_S_db.".board_s_mbrdata_sub");
define('DB_T_s_mbrgroup',       DB_S_db.".board_s_mbrgroup");
define('DB_T_s_mbrlevel',       DB_S_db.".board_s_mbrlevel");
define('DB_T_s_mbrlevel_det',   DB_S_db.".board_s_mbrlevel_det");

define('DB_T_s_menu',           DB_S_db.".board_s_menu");
define('DB_T_s_menu_user',      DB_S_db.".board_s_menu_users");
define('DB_T_s_permission',     DB_S_db.".board_s_permission");
define('DB_T_s_toekn',          DB_S_db.".board_s_token");

define('DB_T_s_upload',         DB_S_db.".board_s_upload");
define('DB_T_s_paper',          DB_S_db.".board_s_paper");


define('DB_T_bbs_list',         DB_S_db.".board_bbs_list");
define('DB_T_bbs_data',         DB_S_db.".board_bbs_data");
define('DB_T_bbs_data_del',     DB_S_db.".board_bbs_data_del");
define('DB_T_bbs_scrap',        DB_S_db.".board_s_scrap");
define('DB_T_bbs_hidshow',      DB_S_db.".board_bbs_hidshow");
define('DB_T_bbs_view',         DB_S_db.".board_bbs_view");

define('DB_T_s_comment',        DB_S_db.".board_s_comment");
define('DB_T_s_oneline',        DB_S_db.".board_s_oneline");


define('DB_T_bbs_month',         DB_S_db.".board_bbs_month");
define('DB_T_bbs_day',         DB_S_db.".board_bbs_day");


define('DB_T_wp_cate',         DB_S_db.".board_wp_cate");
define('DB_T_wp_plist',         DB_S_db.".board_wp_plist");
define('DB_T_wp_pcate',         DB_S_db.".board_wp_pcate");
define('DB_T_wp_link',         DB_S_db.".board_wp_link");
define('DB_T_wp_favorit',         DB_S_db.".board_wp_favorit");
define('DB_T_wp_log',         DB_S_db.".board_wp_log");
define('DB_T_wp_time',         DB_S_db.".board_wp_time");
define('DB_T_wp_timed',         DB_S_db.".board_wp_timed");




define('DB_T_s_numinfo',        DB_S_db.".board_s_numinfo");

define('DB_T_log_access',       DB_S_db.".access_log");


define('DB_T_sms_msg_queue',       DB_S_smsdb.".msg_queue");