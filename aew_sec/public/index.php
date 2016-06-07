<?php 
define('APPLICATION_ENV','development');

defined('DS') || define('DS', DIRECTORY_SEPARATOR); 

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to library
defined('LIBRARY_PATH') || define('LIBRARY_PATH', realpath(APPLICATION_PATH . '/../../library'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    LIBRARY_PATH,
    get_include_path(),
)));

//AutoLoader secundario
//require_once 'Zend/Loader/Autoloader.php';
//$autoloader = Zend_Loader_Autoloader::getInstance();
//$autoloader->setDefaultAutoloader(create_function('$class',
//	"include str_replace('_', '/', \$class) . '.php';"
//));   

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
