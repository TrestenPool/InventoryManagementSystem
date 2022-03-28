<?php
/** INDEX - redirects to home page if the user is already signed in, otherwise redirects to the login page **/

  // config
  require_once '../resources/config.php';

  // redirect to home page if the user is logged in, redirect to login page if not
  if(isSignedIn() == true){
    redirect('/home.php');
  }
  else{
    redirect('/login.php');
  }

?>