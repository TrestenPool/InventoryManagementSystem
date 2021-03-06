<?php
  // config
  require_once '../resources/config.php';

  // header information
  $cssArray = array(
  );
  generateHeader($cssArray, 'Edit');

  // print out any flash messages after redirect if necessary
  printFlash();

  // redirect to the login page if the user is not logged in
  if (!isSignedIn()) {
    setFlash(FLASH_WARNING, 'Must be signed in, in order to access edit page');
    redirect('/login.php');
  }

  $productsArray = getProductsArray();
  $manufacturersArray = getManufacturersArray();

  // check if it was a post request made 
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    editProduct($productsArray, $manufacturersArray);
  }

  // current product 
  $currentProduct = validateProduct($productsArray, $manufacturersArray);
  $_SESSION['product'] = $currentProduct;

  // redirects to the home page if the product was not found
  if($currentProduct == null){
    setFlash(FLASH_INFO, 'product provided does not exist');
    redirect('/home.php');
  }

?>


<div class="container">
  <h1>Edit Product</h1>

  <form action="/edit.php" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
    <!-- choose product -->
    <div class="form-floating">
      <select class="form-select" id="productSelect" name="productType" required>
        <?php
          displayProductsFilter($productsArray, $currentProduct);
        ?>
      </select>
      <label for="productSelect">Change Product Type</label>
    </div>

    <!-- Choose the manufacturer -->
    <div class="form-floating">
      <select class="form-select" id="manufacturerSelect" name="manufacturerName" required>
        <?php
          displayManufacturersFilter($manufacturersArray, $currentProduct);
        ?>
      </select>
      <label for="manufacturerSelect">Change Manufacturer</label>
    </div>

    <!-- Change serial number -->
    <div>
      <div class="input-group mb-3">
        <span class="input-group-text">Serial number</span>
        <?php
          displaySerialNumber($currentProduct);
        ?>
        <div class="invalid-feedback">
          Serial number field required
        </div>
      </div>
    </div>

    <!-- Upload file -->
    <div class="mb-3">
    <label for="formFileMultiple" class="form-label">Upload file</label>
    <input class="form-control" type="file" id="formFile" name="fileToUpload">
    </div>

    <!-- Active / Inactive switch -->
    <div class="form-check form-switch">
      <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
      <?php
        if($currentProduct->get_active_flag() == '1'){
          echo '<input name="active_flag" class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>';
        }
        else{
          echo '<input name="active_flag" class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">';
        }
      ?>
    </div>

    <!-- Submit button -->
    <button type="submit" class="btn btn-primary">Edit</button>

    <!-- Delete button -->
    <?php
      $delete_url = sprintf("/delete.php?product_name=%s&serial_number=%s&manufacturer_name=%s", Product::convert_productID_to_productName($currentProduct->get_product_id(), $productsArray), $currentProduct->get_serial_number(), Product::convert_manufacturerId_to_manufacturerName($currentProduct->get_manufacturer_id(), $manufacturersArray));
      echo sprintf('<a href="%s" class="btn btn-danger btn-md" tabindex="-1" role="button" aria-disabled="true">Delete</a>', $delete_url);
    ?>
  </form>

  <!-- Back home button -->
  <div style="margin-top: 20px; margin-bottom: 100px">
    <a href="/home.php" class="btn btn-secondary">Go back home</a>  
  </div>

  <h3>Files</h3>

  <?php
    global $config;
    $connection = connectToDB($config['db']['db1']);
    
    // gets all the auto_ids for the files that belong to this product in the Files table
    $sql_prepared = "SELECT file_id FROM Product_files WHERE product_id = ? AND product_auto_id = ?";
    $resultSet = executePreparedStatement($connection, $sql_prepared, array($currentProduct->get_product_id(), $currentProduct->get_auto_id()))[0];

    $array_of_file_ids = array();
    foreach ($resultSet as $row) {
      $array_of_file_ids[] = $row['file_id'];
    }

    foreach ($array_of_file_ids as $file_id) {
      $sql = "SELECT file_name, generated_file_name FROM Files WHERE auto_id = " . $file_id;
      $resultSet2 = executeSqlQuery($connection, $sql);

      foreach ($resultSet2 as $row) {
        $button_link = "/files/" . $row['generated_file_name'];
        $original_file_name = $row['file_name'];
        echo sprintf('<a href="%s" class="btn btn-secondary">%s</a>', $button_link, $original_file_name);
      }
    }
  ?>

</div>

<?php
  global $config;
  $jsArray = array(
    $config['paths']['js'] . '/jquery-3.6.0.min.js',
    $config['paths']['js'] . '/formValidation.js'
  );
  generateFooter($jsArray);
?>