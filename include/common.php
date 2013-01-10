<?PHP
date_default_timezone_set('UTC');

// Setup
set_include_path(get_include_path()
                   . PATH_SEPARATOR . __DIR__.'/../'
                   . PATH_SEPARATOR . __DIR__.'/../lib'
                   . PATH_SEPARATOR . __DIR__.'/../class'
                   . PATH_SEPARATOR . __DIR__.'/../function');
                
// required objects for session
require_once('basedata.class.php');
require_once('user.class.php');

// sessions
session_start();

// error handler
if(defined('ENV') && ENV == 'development'){
    require( __DIR__ . '/../lib/PHP-Error/src/php_error.php' );
    \php_error\reportErrors();
}

// Variables
define("SITE_DIR", realpath(__DIR__ . '/../'));
define('TMP_DIR', realpath(__DIR__ . '/../tmp/'));

// Includes (required everywhere)
// Classes
require_once('smarty/Smarty.class.php');
require_once('swift/swift_required.php');

// Function
require_once('getBestSupportedMimeType.func.php');
require_once('x.func.php');
require_once('prettyurlencode.func.php');
require_once('dates.func.php');
require_once('generatePassword.func.php');

// Autoload any missing classes
function class_autoload($class_name) {
    if(is_file(__DIR__.'/../class/'. $class_name . '.class.php') )
        return (include($class_name . '.class.php'));
    return false;
}
spl_autoload_register('class_autoload');

// Database
DBQ::init();
DBQ::set('server', DB_HOST);
DBQ::set('user', DB_USER);
DBQ::set('password', DB_PASSWORD);
DBQ::set('db', DB_DB);
