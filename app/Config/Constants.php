<?php

defined('VERSIONING_NO') || define('VERSIONING_NO', '0.1.6');
defined('VERSIONING_DT') || define('VERSIONING_DT', '2026-01-08');

/**
 * 0.1.1 2025-11-07
 * 0.1.2 2025-12-14
 * 0.1.3 2025-12-17
 * 0.1.4 2025-12-23
 * 0.1.5 2025-12-26
 * 0.1.6 2026-01-08
 */

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
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
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
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
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

defined('STATUS_RESPONSE_ERR') || define('STATUS_RESPONSE_ERR', 'ERR');
defined('STATUS_RESPONSE_OK')  || define('STATUS_RESPONSE_OK', 'OK');

defined('DEFAULT_COUNTRY_CODE')       || define('DEFAULT_COUNTRY_CODE', 'TH');
defined('URL_PRIME_NONCE')            || define('URL_PRIME_NONCE', 787);

defined('BANNED_PASSWORD')            || define('BANNED_PASSWORD', '123|password|qwerty|111|letmein|1q2w3e|aaa|football|iloveyou|admin|princess|dragon|welcome|hello|world|master');

defined('DATETIME_FORMAT_DB')         || define('DATETIME_FORMAT_DB', 'Y-m-d H:i:s');
defined('DATE_FORMAT_DB')             || define('DATE_FORMAT_DB', 'Y-m-d');
defined('DATETIME_FORMAT_UI')         || define('DATETIME_FORMAT_UI', 'd M Y h:i A');
defined('DATE_FORMAT_UI')             || define('DATE_FORMAT_UI', 'd M Y');

defined('ID_MASKED_PRIME')            || define('ID_MASKED_PRIME', 787);

defined('DEFAULT_FREE_TRIAL')         || define('DEFAULT_FREE_TRIAL', '+30 days');