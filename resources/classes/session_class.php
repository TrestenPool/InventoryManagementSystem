<?php
class Session{
  // username of the user signed in
  var $username = null;
  var $isSignedIn = false;

  // filter object that the current session is using
  var $filterObject = null;

  function __construct(){
    // initializes the filter object
    $this->filterObjectj = new Filter();
  }

  // setters
  public function set_username($username){
    $this->username = $username;
  }
  public function set_isSignedIn($value){
    $this->isSignedIn = $value;
  }
  public function set_filterObject($value){
    $this->filterObject = $value;
  }

  // getters
  public function get_username(){
    return $this->username;
  }
  public function get_isSignedIn(){
    return $this->isSignedIn;
  }
  public function get_filterObject(){
    return $this->filterObject;
  }

}


?>