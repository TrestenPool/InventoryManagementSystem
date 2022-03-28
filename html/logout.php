<?php
  // config
  require_once '../resources/config.php';

  // the user is not even signed in, return to login page
  if(!isSignedIn()){
    setFlash(FLASH_DANGER, 'Must be signed in, in order to logout');
    redirect('/login.php');
  }

  // sign out user and redirect to the login page with flash message
  if(attemptLogout() == true){
    setFlash(FLASH_SUCCESS, 'You have successfully logged out');
    redirect('/login.php');
  }

?>