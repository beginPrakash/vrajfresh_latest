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

defined('BASE_URL')         OR define('BASE_URL','https://patelmansi.com/api/');
defined('ASSET_URL')         OR define('ASSET_URL',BASE_URL.'assets/');
defined('FRONT_URL')         OR define('FRONT_URL','https://patelmansi.com/');

defined('ADMIN_URL')         OR define('ADMIN_URL','https://patelmansi.com/admin/');

defined('FEDEX_CLIENT_ID')  OR define('FEDEX_CLIENT_ID', 'l7da850e647e304b0faba020cadce84dd4'); //Fedex Client id
defined('FEDEX_CLIENT_SECRET') OR define('FEDEX_CLIENT_SECRET', '799e545107064f38b283ff16fe3e0fa0'); //Fedex secret id

/*
defined('STRIPE_SECRET_KEY')    OR define('STRIPE_SECRET_KEY','sk_test_Nf5qbJOgwQg3chjObUc7TimG006zBZcXbI'); // Stripe secret key
defined('STRIPE_PUBLISHABLE_KEY')    OR define('STRIPE_PUBLISHABLE_KEY','pk_test_yd5gvyIjhcQuvcys2VGQY9yj00PXtsIsOU'); // Stripe secret key
*/

defined('STRIPE_SECRET_KEY')    OR define('STRIPE_SECRET_KEY','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'); // Stripe secret key
defined('STRIPE_PUBLISHABLE_KEY')    OR define('STRIPE_PUBLISHABLE_KEY','pk_live_51HwVYoBuuwY7F4Wu1XJEb9GWhec9XTSRxQNt0TAhfKL2bHxYE9us9ak7gXgKRYk4uYfZ3jp5Hbxj3SEnIlSiEtOq00p8oOOFBw'); // Stripe secret key


defined('FILE_UPLOAD_PATH') OR define('FILE_UPLOAD_PATH',ADMIN_URL."uploads/");

defined('FILE_UPLOAD_PATH_REPORT') OR define('FILE_UPLOAD_PATH_REPORT','../admin/uploads/');


defined('MODE') or define('MODE', 'PROD');

if(MODE=="DEV")
{
	defined('SMTP_HOST') or define('SMTP_HOST', 'thcitsolutions.com');
	defined('SMTP_USER') or define('SMTP_USER', 'test@thcitsolutions.com');
	defined('SMTP_PASSWORD') or define('SMTP_PASSWORD', 'VedikIn@231');
	defined('SMTP_PORT') or define('SMTP_PORT', '587');
}
else if (MODE=="PROD")
{

	defined('SMTP_HOST') or define('SMTP_HOST', '62.72.30.214');
	defined('SMTP_USER') or define('SMTP_USER', 'vrajfresh@vedikin.com');
	defined('SMTP_PASSWORD') or define('SMTP_PASSWORD', 'VedikIn@231');
	defined('SMTP_PORT') or define('SMTP_PORT','587');
	
/*
	defined('SMTP_HOST') or define('SMTP_HOST', 'vrajfresh.com');
        defined('SMTP_USER') or define('SMTP_USER', 'orders@vrajfresh.com');
        defined('SMTP_PASSWORD') or define('SMTP_PASSWORD', '01)Cg,ibAbrN');
        defined('SMTP_PORT') or define('SMTP_PORT','25'); 
*/
}
