<?php
  // config
  require_once '../resources/config.php';

  // header information
  $cssArray = array(
  );
  generateHeader($cssArray, 'New Product');

  // print out any flash messages after redirect if necessary
  printFlash();

  // redirect to the login page if the user is not logged in
  if (!isSignedIn()) {
    setFlash(FLASH_WARNING, 'Must be signed in, in order to add a new product');
    redirect('/login.php');
  }

  $productsArray = getProductsArray();
  $manufacturersArray = getManufacturersArray();

  // check if it was a post request made 
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    addProduct($productsArray, $manufacturersArray);
  }

?>

<div class="container">
  <h1>New Product</h1>

  <form action="/new.php" method="post" class="needs-validation" id="newForm" enctype="multipart/form-data" novalidate>
    <!-- choose product -->
    <div class="form-floating">
      <select class="form-select" id="productSelect" name="productType" required>
        <?php
          displayProductsFilter($productsArray, null);
        ?>
      </select>
      <label for="productSelect">Change Product Type</label>
    </div>

    <!-- Choose the manufacturer -->
    <div class="form-floating">
      <select class="form-select" id="manufacturerSelect" name="manufacturerName" required>
        <?php
          displayManufacturersFilter($manufacturersArray, null);
        ?>
      </select>
      <label for="manufacturerSelect">Change Manufacturer</label>
    </div>

    <!-- Change serial number -->
    <div>
      <div class="input-group mb-3">
        <span class="input-group-text">Serial number</span>
          <input value="11111111111111111111111111111111" id="serial_number" name="serialNumber" type="text" class="form-control" placeholder="111222333444555666" aria-label="serial number" aria-describedby="basic-addon2" required>
        <div class="invalid-feedback">
          Serial number field required
        </div>
      </div>
    </div>

    <!-- Active / Inactive switch -->
    <div class="form-check form-switch">
      <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
      <?php
        echo '<input name="active_flag" class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>';
      ?>
    </div>

    <!-- Upload file -->
    <div class="mb-3">
    <label for="formFileMultiple" class="form-label">Upload file</label>
    <input class="form-control" type="file" id="formFile" name="fileToUpload">
    </div>

    <!-- Submit button -->
    <button type="submit" name="submit" class="btn btn-primary">Add New Product</button>

  </form>

  <div class="container" style="margin-top: 50px;">
    <a href="/home.php" class="btn btn-secondary">Go back home</a>  
    <?php
    ?>
  </div>
  

</div>

<?php
  global $config;
  $jsArray = array(
    $config['paths']['js'] . '/jquery-3.6.0.min.js',
    $config['paths']['js'] . '/formValidation.js'
  );
  generateFooter($jsArray);
?>