<?php
// require_once '../config.php';

class Filter{

  // keeps an associative array version of their respective table
  // manufacturers_array structure ['auto_id'] = "manufacturer_name"
  // products_array structure ['auto_id'] = "serialNumber"
  var $manufacturers_array = null;
  var $products_array = null;
  
  // stores the auto_id of the selected
  var $manufacturerID_selected = null;
  var $productID_selected = null;
  
  // searching for a serialNumber
  var $serialNumber_selected = null;

  function __construct(){
  }

  // setters for the arrays
  public function set_manufacturers_array($new_array){
    $this->manufacturers_array = $new_array;
  }
  public function set_products_array($new_array){
    $this->products_array = $new_array;
  }

  // setters for the selected
  public function set_manufacturerID_selected($id){
    $this->manufacturerID_selected = $id;
  }
  public function set_productID_selected($id){
    $this->productID_selected = $id;
  }


  // getters for the arrays
  public function get_manufacturers_array(){
    return $this->manufacturers_array;
  }
  public function get_products_array(){
    return $this->products_array;
  }

  // getters for the selected
  public function get_manufacturerID_selected(){
    return $this->manufacturerID_selected;
  }
  public function get_productID_selected(){
    return $this->productID_selected;
  }

}


?>