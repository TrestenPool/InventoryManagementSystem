<?php

class Flash{
  private $type;
  private $message;

  public function __construct(){
    $this->type = FLASH_SUCCESS;
    $this->message = "";
  }

  // print out the the current flash message stored, does nothing if it has none set
  public function printFlashMessage(){
    echo '<div class="alert alert-' . $this->type  . ' alert-dismissible show" id="flash-msg" >';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    echo '<strong>' . $this->message . '</strong>';
    echo '</div>';
  }

  public function set_type($type){
    // only set the type if it is valid
    if(strcmp($type, FLASH_SUCCESS) == 0 || strcmp($type, FLASH_WARNING) == 0 || strcmp($type, FLASH_DANGER) == 0 || strcmp($type, FLASH_PRIMARY) == 0 || strcmp($type, FLASH_SECONDARY) == 0 || strcmp($type, FLASH_INFO) || strcmp($type, FLASH_LIGHT) ||  strcmp($type, FLASH_DARK)){
      $this->type = $type;
    }

  }
  public function set_message($message){
    if(is_string($message)){
      $this->message = $message;
    }
  }

}

?>