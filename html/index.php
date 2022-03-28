<?php
/** INDEX - redirects to home page if the user is already signed in, otherwise redirects to the login page **/

  // config
  require_once '../resources/config.php';

  if(isSignedIn() == true){
    redirect('/home.php');
  }
  else{
    redirect('/login.php');
  }

?>