<?php
  // config
  require_once '../resources/config.php';

  // header information
  $cssArray = array(
  );
  generateHeader($cssArray, 'Delete');

  // redirect to the login page if the user is not logged in
  if (!isSignedIn()) {
    setFlash(FLASH_WARNING, 'Must be signed in, in order to access delete');
    redirect('/login.php');
  }

  $productsArray = getProductsArray();
  $manufacturersArray = getManufacturersArray();

  // redirect to the home page if not a get request
  if($_SERVER['REQUEST_METHOD'] != 'GET'){
    redirect('/home.php');
  }

  $queryParameters = getQueryParameters(array('product_name', 'serial_number', 'manufacturer_name'));
  $product_name = $queryParameters['product_name'];
  $serial_number = $queryParameters['serial_number'];
  $manufacturer_name = $queryParameters['manufacturer_name'];

  if( !$product_name || !$serial_number || !$manufacturer_name ){
    redirect('/home.php');
  }

  $table_name = Product::convert_productName_to_tableName($product_name);
  $product_id = Product::convert_productName_to_productId($product_name, $productsArray);
  $manufacturer_id = Product::convert_manufacturerName_to_id($manufacturer_name, $manufacturersArray);

  $productToDelete = Product::getProductFromDb($product_id, $table_name, $manufacturer_id, $serial_number);

  if($productToDelete != null){
    $productToDelete->set_products_array($productsArray);
    $productToDelete->set_manufacturers_array($manufacturersArray);
    if( $productToDelete->delete() ){
      setFlash(FLASH_SUCCESS, 'product deleted successfully');
      redirect('/home.php');
    }
    else{
      setFlash(FLASH_DANGER, 'there was an issue deleting the product');
      redirect('/home.php');
    }
  }
?>