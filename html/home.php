<?php
// config
require_once '../resources/config.php';

// header information
$cssArray = array();
generateHeader($cssArray, 'Home');

// print out any flash messages after redirect if necessary
printFlash();

// redirect to the login page if the user is not logged in
if (!isSignedIn()) {
  setFlash(FLASH_WARNING, 'Must be signed in, in order to access Home page');
  redirect('/login.php');
}

?>



<h1>Home page</h1>

<!-- Main container -->
<div class="container-lg">

  <!-- Filter and search top row -->
  <div class="row">
    <!-- Products Filter -->
    <div class="col-3">
      <form action="/home.php" method="get">
        <select class="form-select" aria-label="Default select example" name="productType">
          <option value="" disabled selected>Product</option>
          <?php
            $productsArray = getProductTypes();
          ?>
        </select>
        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
      </form>
    </div>

    <!-- Manufacturer Filter -->
    <div class="col-3">
      <form action="/home.php" method="get">
        <select class="form-select" aria-label="Default select example" name="manufacturerName">
          <option value="" disabled selected>Manufacturer</option>
          <?php

          ?>
        </select>
        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
      </form>
    </div>

    <!-- Empty portion -->
    <div class="col-3">
    </div>

    <!-- Serial Number search -->
    <div class="col-3">
      Serial Number search
    </div>

  </div>

  <!-- Table -->
  <table class="table table-striped">
    <?php
    // get all the manufacturers
    $manufacturers_array = getManufacturers();

    // columns
    generateColumnNames();
    
    // table data
    generateTableEntries($manufacturers_array);
    ?>
  </table>



</div>




<?php
global $config;

$jsArray = array(
  //   $config['paths']['js'] . '/home.js'
);
generateFooter($jsArray);
?>