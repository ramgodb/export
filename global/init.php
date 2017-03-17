<?php

/*****
 * Error display settings
 **/
if(DISPLAY_ERRORS === true) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
} else {
	ini_set('display_errors', 0);
	error_reporting(0);
}

/* every time a change is made to the server, do not forget to change the version # here */
define('VERSION', "1.01");

/* force UTC as default time format */
date_default_timezone_set ("UTC");

/*****
 * Error handling class
 **/
ini_set("error_log", ROOT_PATH . "/tmp/export-error.log");

define('E_FATAL',  E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR);
define('ENV', 'dev'); //production : dev

register_shutdown_function('shut');

set_error_handler('handler');

//Function to catch no user error handler function errors...
function shut(){
    $error = error_get_last();
    if($error && ($error['type'] & E_FATAL)){
        handler($error['type'], $error['message'], $error['file'], $error['line']);
    }

}

function handler( $errno, $errstr, $errfile, $errline ) {
    switch ($errno){

        case E_ERROR: // 1 //
            $typestr = 'E_ERROR'; break;
        case E_WARNING: // 2 //
            $typestr = 'E_WARNING'; break;
        case E_PARSE: // 4 //
            $typestr = 'E_PARSE'; break;
        case E_NOTICE: // 8 //
            $typestr = 'E_NOTICE'; break;
        case E_CORE_ERROR: // 16 //
            $typestr = 'E_CORE_ERROR'; break;
        case E_CORE_WARNING: // 32 //
            $typestr = 'E_CORE_WARNING'; break;
        case E_COMPILE_ERROR: // 64 //
            $typestr = 'E_COMPILE_ERROR'; break;
        case E_CORE_WARNING: // 128 //
            $typestr = 'E_COMPILE_WARNING'; break;
        case E_USER_ERROR: // 256 //
            $typestr = 'E_USER_ERROR'; break;
        case E_USER_WARNING: // 512 //
            $typestr = 'E_USER_WARNING'; break;
        case E_USER_NOTICE: // 1024 //
            $typestr = 'E_USER_NOTICE'; break;
        case E_STRICT: // 2048 //
            $typestr = 'E_STRICT'; break;
        case E_RECOVERABLE_ERROR: // 4096 //
            $typestr = 'E_RECOVERABLE_ERROR'; break;
        case E_DEPRECATED: // 8192 //
            $typestr = 'E_DEPRECATED'; break;
        case E_USER_DEPRECATED: // 16384 //
            $typestr = 'E_USER_DEPRECATED'; break;

    }

    $message = '<b>'.$typestr.': </b>'.$errstr.' in <b>'.$errfile.'</b> on line <b>'.$errline.'</b><br/>';

    if(($errno & E_FATAL) && ENV === 'production'){

        //header('Location: 500.html');
        header('Status: 500 Internal Server Error');

    }

    if(!($errno & ERROR_REPORTING))
        return;

    if(DISPLAY_ERRORS)
        printf('%s', $message);

    //Logging error on php file error log...
    if(LOG_ERRORS) {
    	ini_set("log_errors", 1);
        error_log(strip_tags($message), 0);
    }
}

/* create new session or use existing one from user cookie */
ob_start();
session_start();

/* Email SMTP configuration */
ini_set("SMTP", MAIL_SERVER);
ini_set("sendmail_from", SITE_EMAIL_NAME . " <".MAIL_USER.">");

//include_once(ROOT_PATH . 'lib/core.php');

/* autoload library classes
 * name your classes lib_xxx and place it under docroot/lib folder
 * name your controller handlers control_xxx and place it under docroot/controller folder
 * name your model handlers model_xxx and place it under docroot/model folder
 */
spl_autoload_register(function($name){
	if( strpos($name, "control") === 0 )
	{
		$name = substr($name, 7);
		$requirePath = ROOT_PATH."controller/$name.php";
	}
	if( strpos($name, "model") === 0 )
	{
		$name = substr($name, 5);
		$requirePath = ROOT_PATH."model/$name.php";
	}
	if( strpos($name, "lib") === 0 )
	{
		$name = substr($name, 3);
		$requirePath = ROOT_PATH."lib/$name.php";
	}
	if($name=='phpexcel')
	{
		$requirePath = ROOT_PATH."plugins/phpexcel/Classes/$name.php";
	}
	if(file_exists($requirePath)) {
		include_once($requirePath);
		return true;
	} else {
        return false;
	}
});

/*****
 * Database Connection direct
 **/
//$db = new libDatabase();