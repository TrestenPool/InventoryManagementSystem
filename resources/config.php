<?php
// load .env variables into $_ENV['variableName'] to use
require_once('/var/www/vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createMutable(__DIR__);
$dotenv->load();

/*
    Creating constants for heavily used paths makes things a lot easier.
    ex. require_once(LIBRARY_PATH . "Paginator.php")
*/
define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));
define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));
define("CLASSES_PATH", realpath(dirname(__FILE__) . '/classes'));
define("VENDOR_PATH", '/var/www/vendor');

// used for the vendor for 
define('DIR_VENDOR', '/var/www/vendor/');

// flash types
define ('FLASH_SUCCESS', 'success');
define ('FLASH_WARNING', 'warning');
define ('FLASH_DANGER', 'danger');

define ('FLASH_PRIMARY', 'primary');
define ('FLASH_SECONDARY', 'secondary');

define ('FLASH_INFO', 'info');
define ('FLASH_LIGHT', 'light');
define ('FLASH_DARK', 'dark');

/*
    The important thing to realize is that the config file should be included in every
    page of your project, or at least any page you want access to these settings.
    This allows you to confidently use these settings throughout a project because
    if something changes such as your database credentials, or a path to a specific resource,
    you'll only need to update it here.
*/
 
$config = array(

    "db" => array(
        "db1" => array(
            "dbname" => "InventoryManagementSystem",
            "username" => $_ENV['DB_USERNAME'],
            "password" => $_ENV['DB_PASSWORD'],
            "host" => "localhost"
        )
    ),

    "urls" => array(
        "baseUrl" => "https://ec2-54-90-227-153.compute-1.amazonaws.com"
    ),

    "paths" => array(
        // NOT public files, backend code. Holds config.php,library,pages,templates
        "resources" => "/var/www/resources",

        // where we store the code for the rendering of the pages
        "pages" => "/var/www/resources/pages",

        // css files
        "css" => "/css",

        // javascript files
        "js" =>  "/js",

        // images files
        "images" => "/images",

        // error file
        "error" => "/var/log/nginx/error.log"
    ),

    "classes" => array(
      // works with the user that is signed in 
      "session" => CLASSES_PATH . '/session_class.php'
    ),

    "pagination" => array(
      "entries" => 50
    ),

    "Hashing_Algorithm" => PASSWORD_BCRYPT,
);
 


/*
    Error reporting.
*/
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


/****************************************************************************************/
/****************************************************************************************/
/****** THE FOLLOWING PHP WILL BE RENDERED ON ALL PHP FILES THAT INCLUDE THE CONFIG ****/
/****************************************************************************************/

  // the library functions
  require_once LIBRARY_PATH . '/mysql_functions.php';
  require_once LIBRARY_PATH . '/webUtility_functions.php';
  require_once LIBRARY_PATH . '/user_functions.php';
  require_once LIBRARY_PATH . '/inventory_functions.php';

  // classes
  require_once CLASSES_PATH . '/session_class.php';
  require_once CLASSES_PATH . '/flash_class.php';
  require_once CLASSES_PATH . '/filter_class.php';

  // pageination library
  require_once VENDOR_PATH . '/stefangabos/zebra_pagination/Zebra_Pagination.php';

  // initialize the session everytime a page is loaded
  initSession();

//   echo 'product ' . getFilterObject()->get_productID_selected() . "\n";
//   echo 'manufacturer ' . getFilterObject()->get_manufacturerID_selected();
?>