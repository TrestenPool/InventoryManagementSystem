<?php
class Filter{

  // keeps array version of their respective table
  var $manufacturers_array = null;
  var $products_array = null;
  
  // selected manufacturer and product
  var $manufacturer_selected = null;
  var $product_selected = null;
  
  // searching for a serialNumber
  var $serialNumber_selected = null;

  function __construct(){
  }

  // setters
  public function set_manufacturers_array($new_array){
    $this->manufacturers_array = $new_array;
  }
  public function set_products_array($new_array){
    $this->products_array = $new_array;
  }

  public function set_manufacturers_selected($new_manufacturers){
    $this->manufacturers_selected = $new_manufacturers;
  }
  public function set_product_selected($new_product){
    $this->product_selected = $new_product;
  }

  // getters
  public function get_manufacturers_array(){
    return $this->manufacturers_array;
  }
  public function get_products_array(){
    return $this->products_array;
  }
  public function get_manufacturer_selected(){
    return $this->manufacturer_selected;
  }
  public function get_product_selected(){
    return $this->product_selected;
  }

}


?>