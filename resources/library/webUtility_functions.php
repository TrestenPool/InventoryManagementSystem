<?php
/****************************************************************************************/
/****************************** GENERAL FUNCTIONS ***************************************/
/****************************************************************************************/
function writeToError($string){
  global $config;
  $output = date(DATE_RSS) . " [ " . $string . " ]" . "\n";
  file_put_contents($config["paths"]["error"], $output, FILE_APPEND | LOCK_EX);
}

// returns null if no body was passed
// returns an associative array if request body was passed
// to access, ex. $data = getRequestBody(); $data["bodyVariable"]
function getRequestBody(){
  return json_decode(file_get_contents('php://input'), true);
}

function redirect($url){
  global $config;
  header("Location: " .$config["urls"]["baseUrl"] . $url);
  exit;
}

/****************************************************************************************/
/****************************** HEADER & FOOTER FUNCTIONS *******************************/
/****************************************************************************************/
// takes in an array of css stylesheet files, that will be added to the header, the css files must be located in the /css directory
// also takes in the title to be rendered for the specific page
function generateHeader($cssArray, $titleArg){
  $cssArray = $cssArray;
  $title = $titleArg;

  // insert header 
  require_once TEMPLATES_PATH . '/header.php';
}

// pass array of javascript files to include in the footer
// must be located in /js
function generateFooter($array){
  global $config;

  // bootstrap5 needed on all forms
  echo '    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>' . "\n";

  // include all scripts given to the function in our footer
  if(isset($array)){
    foreach($array as $script){
        echo '<script src="' .$script . '"></script>' . "\n";
    }
  }

  require_once TEMPLATES_PATH . '/footer.php';
}

/****************************************************************************************/
/****************************** SESSION FUNCTIONS ***************************************/
/****************************************************************************************/
function clearSession(){

  if(isset($_SESSION["session"])){
    writeToError("deleting \$_SESSION['session'] variable");
    unset($_SESSION["session"]);
  }

  if(isset($_SESSION)){
    writeToError("deleting the \$_SESSION variable");
    session_destroy();
  }

}

function initSession(){
  session_start();

  if(!isset($_SESSION["session"])){
    writeToError("Creating new session");

    $_SESSION["session"] = new Session();
  }
}

function isSessionSet(){
  if( !isset($_SESSION) ){
    writeToError("issSessionSet() -- \$_SESSION is not set");
    return false;
  }
  if( !isset($_SESSION["session"]) ){
    writeToError("issSessionSet() -- \$_SESSION['session] is not set");
    return false;
  }

  return true;
}

/****************************************************************************************/
/****************************** FLASH MESSAGES ******************************************/
/****************************************************************************************/
function setFlash($type, $message){

  // returns if no session is set
  if(!isSessionSet()){
    return;
  }

  if(isset($_SESSION['flash'])){
    unset($_SESSION['flash']);
  }

  $_SESSION['flash'] = new Flash();
  $_SESSION['flash']->set_type($type);
  $_SESSION['flash']->set_message($message);
}

function printFlash(){
  if(!isSessionSet() || !isset($_SESSION['flash'])){
    return;
  }

  $_SESSION['flash']->printFlashMessage();
  unset($_SESSION['flash']);
}

?>