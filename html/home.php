<?php
  // config
  require_once '../resources/config.php';

  // header information
  $cssArray = array(
    $config['paths']['css'] . '/zebra_pagination.css'
  );
  generateHeader($cssArray, 'Home');

  // print out any flash messages after redirect if necessary
  printFlash();

  // redirect to the login page if the user is not logged in
  if (!isSignedIn()) {
    setFlash(FLASH_WARNING, 'Must be signed in, in order to access Home page');
    redirect('/login.php');
  }
?>

<!-- Main container -->
<div class="container-lg">

  <h1>Home page</h1>

  <!-- FILTERS AND SERIAL NUMBER SEARCH -->
  <form action="/home.php" method="GET">
    <div class="row">

      <div class="col-3">
        <!-- Products filter -->
        <select class="form-select" name="productType">
          <option value="-1">All Products</option>
          <?php
          getProductTypes();
          ?>
        </select>
      </div>

      <div class="col-3">
        <!-- Manufacturer Filter -->
        <select class="form-select" name="manufacturerName">
          <option value="-1">All Manufacturers</option>
          <?php
          getManufacturers();
          ?>
        </select>
      </div>

      <!-- Empty portion -->
      <div class="col-3">
      </div>

      <!-- Serial Number search -->
      <div class="col-3">
        Serial Number search
      </div>

    </div>

    <!-- Submit button -->
    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
  </form>

  <!-- Table -->
    <?php
    // columns
    generateColumnNames();

    // table data
    generateTableEntries();
    ?>
</div>


<?php
global $config;
$jsArray = array(
  $config['paths']['js'] . '/jquery-3.6.0.min.js',
  $config['paths']['js'] . '/zebra_pagination.js'
);
generateFooter($jsArray);
?>