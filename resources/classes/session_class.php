<?php
class Session{
  // username of the user signed in
  var $username = null;
  var $isSignedIn = false;

  function __construct(){
  }

  // setters
  public function set_username($username){
    $this->username = $username;
  }
  public function set_isSignedIn($value){
    $this->isSignedIn = $value;
  }

  // getters
  public function get_username(){
    return $this->username;
  }
  public function get_isSignedIn(){
    return $this->isSignedIn;
  }

}


?>