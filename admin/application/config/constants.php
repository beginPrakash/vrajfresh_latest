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

	
define('SITE_URI','https://www.vrajfresh.com/admin/');
//define('SITE_URI','http://'.$_SERVER['HTTP_HOST'].'/vrj');
define('SITE_URL',SITE_URI.'/');
define('ADMIN_PANEL_THEME_PATH',SITE_URL.'themes/admin_panel/');
define('DATE_FORMAT',"m-d-Y");

defined('PRODUCT_IMAGE_WIDTH')      OR define('PRODUCT_IMAGE_WIDTH', "250PX"); 
defined('PRODUCT_IMAGE_HEIGHT')      OR define('PRODUCT_IMAGE_HEIGHT', "250PX"); 
defined('CATEGORY_IMAGE_WIDTH')      OR define('CATEGORY_IMAGE_WIDTH', "250PX"); 
defined('CATEGORY_IMAGE_HEIGHT')      OR define('CATEGORY_IMAGE_HEIGHT', "250PX"); 

defined('API_URL')      OR define('API_URL', 'https://www.vrajfresh.com/api/');
defined('PRODUCT_IMAGE_PATH')      OR define('PRODUCT_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'] . "/admin/");


 defined('STRIPE_SECRET_KEY')    OR define('STRIPE_SECRET_KEY','sk_test_Nf5qbJOgwQg3chjObUc7TimG006zBZcXbI'); // Stripe secret key
defined('STRIPE_PUBLISHABLE_KEY')    OR define('STRIPE_PUBLISHABLE_KEY','pk_test_yd5gvyIjhcQuvcys2VGQY9yj00PXtsIsOU'); // Stripe secret key 


/* CART SESSION EXPIRATION TIME */
defined('CART_SESSION_EXPIRATION_TIME')    OR define('CART_SESSION_EXPIRATION_TIME', 1296000); // In seconds (15 Days)

/* LIVE CREDENTIALS */
/*defined('STRIPE_SECRET_KEY')    OR define('STRIPE_SECRET_KEY','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'); // Stripe secret key
defined('STRIPE_PUBLISHABLE_KEY')    OR define('STRIPE_PUBLISHABLE_KEY','pk_live_51HwVYoBuuwY7F4Wu1XJEb9GWhec9XTSRxQNt0TAhfKL2bHxYE9us9ak7gXgKRYk4uYfZ3jp5Hbxj3SEnIlSiEtOq00p8oOOFBw'); // Stripe secret key*/


defined('MODE') or define('MODE', 'PROD');
if(MODE=="DEV")
{
	defined('SMTP_HOST') or define('SMTP_HOST', 'thcitsolutions.com');
	defined('SMTP_USER') or define('SMTP_USER', 'test@thcitsolutions.com');
	defined('SMTP_PASSWORD') or define('SMTP_PASSWORD', 'VedikIn@231');
	defined('SMTP_PORT') or define('SMTP_PORT', '587');
}
else if (MODE == "PROD") 
{	
	defined('SMTP_HOST') or define('SMTP_HOST', '62.72.30.214');
	defined('SMTP_USER') or define('SMTP_USER', 'vrajfresh@vedikin.com');
	defined('SMTP_PASSWORD') or define('SMTP_PASSWORD', 'VedikIn@231');
	defined('SMTP_PORT') or define('SMTP_PORT', '587');
	
	
/*	defined('SMTP_HOST') or define('SMTP_HOST', 'vrajfresh.com');
	defined('SMTP_USER') or define('SMTP_USER', 'orders@vrajfresh.com');
	defined('SMTP_PASSWORD') or define('SMTP_PASSWORD', '01)Cg,ibAbrN');
	defined('SMTP_PORT') or define('SMTP_PORT','25');
*/
	
	
}
