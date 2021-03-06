<?php
  /*******************************************************************************/
  /********** GETS THE RESPECTIVE TABLES FROM THE DB IN ARRAY FORM ***************/
  /*******************************************************************************/
  function getProductsArray(){
    global $config;
    $connection = connectToDB($config['db']['db1']);
    $resultSet = executeSqlQuery($connection, 'SELECT auto_id, product_name FROM Products');
    $productsArray = [];

    foreach($resultSet as $row){
      $productsArray[$row['auto_id']] = $row['product_name'];
    }

    return $productsArray;
  }

  function getManufacturersArray(){
    global $config;
    $connection = connectToDB($config['db']['db1']);
    $resultSet = executeSqlQuery($connection, 'SELECT auto_id, manufacturer_name FROM Manufacturer');
    $manufacturersArray = [];

    foreach($resultSet as $row){
      $manufacturersArray[$row['auto_id']] = $row['manufacturer_name'];
    }

    return $manufacturersArray;
  }




  /*******************************************************************************/
  /****************** USED TO POPULATE FILTERS ON HOME PAGE ***********************/
  /*******************************************************************************/
  function displayProductsFilter($productsArray, $currentProduct){
    global $config;
    $queryParameters = getQueryParameters(array('productType'));

    // go through all the products in the Products table
    foreach ($productsArray as $auto_id=>$product_name) {
      if($currentProduct != null){
        // writeToError('currentProduct productid = ' . $currentProduct->get_product_id());
        echo sprintf("<option value=\"%s\"%s>%s</option>", $product_name, ($currentProduct->get_product_id() == $auto_id)?' selected':'', $product_name );
      }
      else if($queryParameters['productType']){
        echo sprintf("<option value=\"%s\"%s>%s</option>", $product_name, ($queryParameters['productType'] == $product_name)?' selected':'', $product_name );
      }
      else{
        echo sprintf("<option value=\"%s\">%s</option>", $product_name, $product_name );
      }
    }
  }

  function displayManufacturersFilter($manufacturersArray, $currentProduct){
    global $config;
    $queryParameters = getQueryParameters(array('manufacturerName'));

    foreach ($manufacturersArray as $auto_id=>$manufacturer_name) {
      if($queryParameters['manufacturerName']){
        echo sprintf("<option value=\"%s\"%s>%s</option>", $manufacturer_name, ($queryParameters['manufacturerName'] == $manufacturer_name)?' selected':'', $manufacturer_name );
      }
      else if($currentProduct != null){
        echo sprintf("<option value=\"%s\"%s>%s</option>", $manufacturer_name, ($currentProduct->get_manufacturer_id() == $auto_id)?' selected':'', $manufacturer_name );
      }
      else{
        echo sprintf("<option value=\"%s\">%s</option>", $manufacturer_name, $manufacturer_name );
      }
    }
  }

  function displaySerialNumber($currentProduct){
    $queryParameters = getQueryParameters(array('serialNumber'));
    $serial_number = $queryParameters['serialNumber'];

    if($currentProduct){
      echo sprintf('<input name="serialNumber" type="text" class="form-control" placeholder="111222333444555666" aria-label="serial number" aria-describedby="basic-addon2" value="%s" required>', $currentProduct->get_serial_number());
    }
    else{
      echo sprintf('<input name="serialNumber" value="%s" type="text" class="form-control" placeholder="111222333444555666" aria-label="serial number" aria-describedby="basic-addon2">', ($serial_number) ?$serial_number :'');
    }
  }

  function displayActiveSelection($currentProduct){
    $queryParameters = getQueryParameters(array('activeSelect'));
    $selection = $queryParameters['activeSelect'];

    if($currentProduct){

    }
    else{
      echo sprintf('<option value="active"%s>Active</option>', ($selection === 'active') ?' selected' :'');
      echo sprintf('<option value="inactive"%s>Inactive</option>', ($selection === 'inactive') ?' selected' :'');
    }

  }


  /*******************************************************************************/
  /****************** HELPER FUNCTIONS TO GENERATE THE TABLE DATA ****************/
  /*******************************************************************************/
  /* GET PRODUCT FROM FILTER
  * returns the product that was selected if it is valid
  * returns null if no product was selected or if it is invalid
  */
  function getProductFromFilter($productsArray){
    $queryParameters = getQueryParameters(array('productType', 'manufacturerName'));
    $productSelected = $queryParameters['productType'];

    // no product was passed through query parameters
    if($productSelected === null){
      return null;
    }

    // product found in the array, return in table name format
    if( in_array($productSelected, $productsArray) ){
      return $productSelected;
    }

    // the product was not found in the products array
    return null;
  }

  /* GET MANUFACTURER FROM FILTER
  * returns the name of the manufacturer that was selected
  * returns null if no Manufacturer was selected
  */
  function getManufacturerFromFilter($manufacturersArray){
    global $config;
    $queryParameters = getQueryParameters(array('productType', 'manufacturerName'));
    $manufacturer_selected = $queryParameters['manufacturerName'];

    if($manufacturer_selected === null){
      return null;
    }

    if(in_array($manufacturer_selected, $manufacturersArray)){
      return $manufacturer_selected;
    }

    return null;
  }

  /* GET SERIAL NUMBER FROM FILTER
  * returns the serial number was input into the search filter
  * returns null if no serialnumber was provided
  */
  function getSerialNumberFromFilter(){
    $queryParameters = getQueryParameters(array('serialNumber'));
    $serial_number = $queryParameters['serialNumber'];

    if($serial_number){
      return $queryParameters['serialNumber'];
    }
    else{
      return null;
    }
  }

  /* GET ACTIVE SELECTION FROM FILTER
  * returns true if the active filter was set to active
  * returns false if the user selected inactive 
  * returns null otherwise
  */
  function getActiveSelectionFromFilter(){
    $queryParameters = getQueryParameters(array('activeSelect'));
    $active_selection = $queryParameters['activeSelect'];

    if($active_selection === null){
      return null;
    }

    if($active_selection === 'active'){
      return '1';
    }
    if($active_selection === 'inactive'){
      return '0';
    }

    return null;
  }

  
  /*******************************************************************************/
  /*************** USED TO GENERATE THE TABLE ON THE HOME PAGE *******************/
  /*******************************************************************************/
  /*
  * GENERATE COLUMN NAMES 
  */
  function generateColumnNames(){
    echo '<table class="table table-striped">';
    echo '<thead>';
      echo '<tr>';
        echo '<th>Manufacturer</th>';
        echo '<th>SerialNumber</th>';
        echo '<th>Active</th>';
        echo '<th>Actions</th>';
      echo '</tr>';
    echo '</thead>';
  }

  /*
  * GENERATE TABLE DATA
  */
  function generateTableEntries($productsArray, $manufacturersArray){
    global $config;

    // connect to the db
    $connection = connectToDB($config['db']['db1']);
    
    // query parameters that were passed in
    $queryParameters = getQueryParameters(array('productType', 'manufacturerName'));

    /** Get the items selected from the filter **/
    $product_selected = getProductFromFilter($productsArray); // gets the product
    $table_name = Product::convert_productName_to_tableName($product_selected);

    $manufacturer_name = getManufacturerFromFilter($manufacturersArray); // gets the manufacturer
    $manufacturer_id = Product::convert_manufacturerName_to_id($manufacturer_name, $manufacturersArray);

    $serial_number = getSerialNumberFromFilter(); // gets the serialNumber field
    $active_selection = getActiveSelectionFromFilter(); // gets the active flag filter


    // if either of the filters is not set, return
    if($table_name === null || $manufacturer_id === null || $active_selection === null){
      return;
    }

    // pagination object
    $pagination = new Zebra_Pagination();
    $recordsPerPage = 20;
    
    if($serial_number === null){
      // gets all the information and makes a query based on it
      $sql_prepared = 'SELECT manufacturer_id, serialNumber, Active FROM ' . $table_name . ' WHERE manufacturer_id = ? AND Active = ? LIMIT ' . (($pagination->get_page() - 1) * $recordsPerPage) . ', ' . $recordsPerPage . '' ;
      $resultSet = executePreparedStatement($connection, $sql_prepared, array($manufacturer_id, $active_selection))[0];

      // gets the number of records from the table
      $sql2 = 'SELECT COUNT(*) as num_records FROM ' . $table_name . ' WHERE manufacturer_id = ? AND Active = ?';
      $result2 = executePreparedStatement($connection, $sql2, array($manufacturer_id, $active_selection))[0];

      // pass the total number of records to the pagination class
      $pagination->records($result2->fetch_assoc()['num_records']);
      $pagination->records_per_page($recordsPerPage);
    }

    // serial number was provided
    else{
      // gets all the information and makes a query based on it
      $sql_prepared = 'SELECT manufacturer_id, serialNumber, Active FROM ' . $table_name . ' WHERE manufacturer_id = ? AND serialNumber = ? AND Active = ? LIMIT ' . (($pagination->get_page() - 1) * $recordsPerPage) . ', ' . $recordsPerPage . '' ;
      $resultSet = executePreparedStatement($connection, $sql_prepared, array($manufacturer_id, $serial_number, $active_selection))[0];

      // gets the number of records from the table
      $sql2 = 'SELECT COUNT(*) as num_records FROM ' . $table_name . ' WHERE manufacturer_id = ? AND serialNumber = ? AND Active = ?';
      $result2 = executePreparedStatement($connection, $sql2, array($manufacturer_id, $serial_number, $active_selection))[0];

      // pass the total number of records to the pagination class
      $pagination->records($result2->fetch_assoc()['num_records']);
      $pagination->records_per_page($recordsPerPage);
    }

    /** format the results to the table **/
    echo '<tbody>';

    foreach ($resultSet as $row) {
      echo '<tr>';
      echo "<td>" . $manufacturersArray[$row['manufacturer_id']] . "</td>";
      echo "<td>${row['serialNumber']}</td>";
      echo sprintf("<td>%s</td>", ($row['Active']) ?'active' : 'inactive');

      // Delete button
      $deleteLink = $config['urls']['baseUrl'] . '/delete.php' . '?product_name=' . $product_selected . '&serial_number=' . $row['serialNumber'] . '&manufacturer_name=' .$manufacturer_name;
      echo sprintf('<td><a href="%s" style="color:red; margin: 5px"><svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
        </svg></a>'
        ,$deleteLink);

      // edit button
      $editLink = $config['urls']['baseUrl'] . '/edit.php' . '?tableName=' . $table_name . '&serialNumber=' . $row['serialNumber'] . '&manufacturerName=' . $manufacturer_name;
      echo sprintf('<a href="%s" style="color:blue"><svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
      <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
      </svg></a></td>'
      , $editLink);
    }

    echo '</tbody>';
    echo '</table>';

    // render the pagination below the table
    $pagination->render();
  }


  /*******************************************************************************/
  /*************************** EDIT PAGE FUNCTIONS  *****************************/
  /*******************************************************************************/

  /* VALIDATE PRODUCT
  * input: product and manufacturer arrays
  * output: object of the product, null if it is not found in the db
  */
  function validateProduct($productsArray, $manufacturersArray){
    global $config;
    
    // query parameters from the request
    $queryParameters = getQueryParameters(array('tableName', 'serialNumber', 'manufacturerName'));
    $table_name = $queryParameters['tableName'];
    $serial_number = $queryParameters['serialNumber'];
    $manufacturer_name = $queryParameters['manufacturerName'];

    // return either of the required fields was not passed in
    if($serial_number === null || $table_name === null){
      return false;
    }

    // get the manufacturer_id
    $manufacturer_id = Product::convert_manufacturerName_to_id($manufacturer_name, $manufacturersArray);

    // gets the product id
    $product_name = Product::convert_tableName_to_productName($table_name);
    $product_id = Product::convert_productName_to_productId($product_name, $productsArray);

    // gets the product from the db
    $product_fetched = Product::getProductFromDb($product_id, $table_name, $manufacturer_id, $serial_number);

    if($product_fetched == null){
      return null;
    }

    // set the arrays
    $product_fetched->set_products_array($productsArray);
    $product_fetched->set_manufacturers_array($manufacturersArray);

    return $product_fetched;
  }

  function editProduct($productsArray, $manufacturersArray){
    global $config;
    $connection = connectToDB($config['db']['db1']);

    // redirect to home since the original product was not 
    if(!isset($_SESSION['product'])){
      setFlash(FLASH_DANGER, 'the post request was made to edit.php before the get request was made to edit.php');
      redirect('/home.php');
    }
    
    // grab the product information from the request body
    $product = $_POST['productType'];
    $manufacturer_name = $_POST['manufacturerName'];
    $serial_number = $_POST['serialNumber'];

    // gets the active switch form the webpage
    if(isset($_POST['active_flag'])){
      $active_flag = true;
    }
    else{
      $active_flag = false;
    }

    // one or more of the fields are missing 
    if( !$product || !$manufacturer_name || !$serial_number ){
      setFlash(FLASH_WARNING, 'Edit Failed, Missing one of the required fields');
      redirect('/home.php');
    }

    // the product is not valid
    if(!in_array($product, $productsArray)){
      setFlash(FLASH_WARNING, 'Edit Failed, The product provided was not valid');
      redirect('/home.php');
    }

    // the manufacturer is not valid
    if(!in_array($manufacturer_name, $manufacturersArray)){
      setFlash(FLASH_WARNING, 'Edit Failed, The manufacturer provided was not valid');
      redirect('/home.php');
    }

    // the serial number is invalid
    if(Product::isValid_serialNumber($serial_number) == false){
      setFlash(FLASH_WARNING, 'Edit Failed, the serial number is not valid');
      redirect('/home.php');
    }

    /********************************************************************/
    if(isset($_FILES['fileToUpload']) ){
      // flag to upload file too
      $flag_upload = true;

      // setting up where we are uploading the file
      $target_dir = "/var/www/html/files/";

      // get the filename from the user
      $file_name = $_FILES['fileToUpload']["name"];

      $original_file_name = $file_name;

      // make sure the filename only has one period in it 
      if( substr_count($file_name, ".") === 0 || substr_count($file_name, ".") > 1 ){
        writeToError('FAILED UPLOAD:: The file name has to contain only 1 period in the name');
        return;
      }

      // adding the unique id to the filename
      list($file_name, $extension) = explode('.', $_FILES['fileToUpload']['name']);

      // append the id to the filename
      $unique_id = uniqid();
      $file_name .=  "_ID_" . $unique_id;
      $file_name .= "." . $extension;

      // the final target file we will be writing to
      $target_file = $target_dir . $file_name;

      // only allow the following file types
      $acceptable_fileTypes = array('jpg', 'png', 'jpeg', 'pdf');

      // gets the image file type from the extension
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

      // Check if file already exists
      if (file_exists($target_file)) {
        writeToError('FAILED UPLOAD:: file already exists');
        $flag_upload = false;
      }

      // check the file type
      if(in_array($imageFileType, $acceptable_fileTypes) == false) {
        writeToError('FAILED UPLOAD:: Only the following formats are allowed (JPG, JPEG, PNG, PDF)');
        $flag_upload = false;
      }

      // uploaded file success
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) == false) {
        writeToError('FAILED UPLOAD:: failed during move_uploaded_file()');
        $flag_upload = false;
      } 
    }
    /********************************************************************/

    // get product id form
    $product_id = Product::convert_productName_to_productId($product, $productsArray);
    // get manufacturer id form
    $manufacturer_id = Product::convert_manufacturerName_to_id($manufacturer_name, $manufacturersArray);

    // update the product and manufacturer arrays
    $_SESSION['product']->set_products_array($productsArray);
    $_SESSION['product']->set_manufacturers_array($manufacturersArray);

    // attempt to upload the file
    if($flag_upload == true){
      $sql_prepared = "INSERT INTO Files(file_name, file_type, file_id, generated_file_name) VALUES(?, ?, ?, ?)";
      $resultSet = executePreparedStatement($connection, $sql_prepared, array($original_file_name, $extension, $unique_id, $file_name));
      $file_auto_id = $connection->insert_id;
      $num_rows_inserted = $resultSet[1]->affected_rows;
      if($num_rows_inserted != 1){
        writeToError('FAILED UPLOAD:: There was an issue uploading the file to the database');
        $flag_upload = false;
      }
    }

    // add to product_files pivot table
    if($flag_upload){
      $sql_prepared = "INSERT INTO Product_files (product_id, product_auto_id, file_id) VALUES(?, ?, ?)";
      $resultSet = executePreparedStatement($connection, $sql_prepared, array($product_id, $_SESSION['product']->get_auto_id(), $file_auto_id));
    }

    // update the product in the db
    $_SESSION['product']->update($product_id, $manufacturer_id, $serial_number, $active_flag);
  }

  /*******************************************************************************/
  /*************************** NEW PRODUCT FUNCTIONS  ****************************/
  /*******************************************************************************/
  /* ADD NEW PRODUCT
  * Takes the new product passed in through the request body 
  * 
  */
  function addProduct($products_array, $manufacturers_array){
    global $config;
    $connection = connectToDB($config['db']['db1']);

    // gets the request body
    if( !isset($_POST['productType']) || !isset($_POST['manufacturerName']) || !isset($_POST['serialNumber']) ){
      setFlash(FLASH_DANGER, 'You did not provide all the necessary input to add a new file!!');
      redirect('/new.php');
    }

    $productName = $_POST['productType'];
    $manufacturerName = $_POST['manufacturerName'];
    $serialNumber = $_POST['serialNumber'];

    if(isset($_POST['active_flag'])){
      if(strcmp($_POST['active_flag'], 'on') !== 0){
        setFlash(FLASH_DANGER, 'The active flag was passed but with the wrong value');
        redirect('/new.php');
      } 
      
      $active_flag = '1';
    }
    else{
      $active_flag = '0';
    }

    // checks to see if the serial number is correct
    if(!Product::isValid_serialNumber($serialNumber)){
      setFlash(FLASH_DANGER, 'The serial number must be exactly 32 characters in length. All numbers and lowercase letters');
      redirect('/new.php');
    }

    $product_id = Product::convert_productName_to_productId($productName, $products_array);

    // the product id is not valid
    if($product_id === null){
      setFlash(FLASH_DANGER, 'The product ID provided is not valid');
      redirect('/new.php');
    }

    $manufacturer_id = product::convert_manufacturerName_to_id($manufacturerName, $manufacturers_array);

    // manufacturer id is not valid
    if($manufacturer_id === null){
      setFlash(FLASH_DANGER, 'The manufacturer id provided is not valid');
      redirect('/new.php');
    }

    /***** WRITING DIRECTLY TO THE FILE SYSTEM ****/
    /********************************************************************/
    if(isset($_FILES['fileToUpload']) ){
      // flag to upload file too
      $flag_upload = true;

      // setting up where we are uploading the file
      $target_dir = "/var/www/html/files/";

      // get the filename from the user
      $file_name = $_FILES['fileToUpload']["name"];

      $original_file_name = $file_name;

      // make sure the filename only has one period in it 
      if( substr_count($file_name, ".") === 0 || substr_count($file_name, ".") > 1 ){
        writeToError('FAILED UPLOAD:: The file name has to contain only 1 period in the name');
        return;
      }

      // adding the unique id to the filename
      list($file_name, $extension) = explode('.', $_FILES['fileToUpload']['name']);

      // append the id to the filename
      $unique_id = uniqid();
      $file_name .=  "_ID_" . $unique_id;
      $file_name .= "." . $extension;

      // the final target file we will be writing to
      $target_file = $target_dir . $file_name;

      // only allow the following file types
      $acceptable_fileTypes = array('jpg', 'png', 'jpeg', 'pdf');

      // gets the image file type from the extension
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

      // Check if file already exists
      if (file_exists($target_file)) {
        writeToError('FAILED UPLOAD:: file already exists');
        $flag_upload = false;
      }

      // Check file size
      if ($_FILES["fileToUpload"]["size"] > 500000) {
        writeToError('FAILED UPLOAD:: file to large');
        $flag_upload = false;
      }

      // check the file type
      if(in_array($imageFileType, $acceptable_fileTypes) == false) {
        writeToError('FAILED UPLOAD:: Only the following formats are allowed (JPG, JPEG, PNG, PDF)');
        $flag_upload = false;
      }

      // uploaded file success
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) == false) {
        writeToError('FAILED UPLOAD:: failed during move_uploaded_file()');
        $flag_upload = false;
      } 

    }

    // create the product
    $newProduct = new Product($product_id, $manufacturer_id, $serialNumber, $active_flag);
    $newProduct->set_products_array($products_array);
    $newProduct->set_manufacturers_array($manufacturers_array);

    // if there was an issue saving the product in the db
    if( $newProduct->create() == false){
      setFlash(FLASH_DANGER, 'There was an issue inserting the product into the database');
      redirect('/new.php');
    }

    // attempt to upload the file
    if($flag_upload == true){
      $sql_prepared = "INSERT INTO Files(file_name, file_type, file_id, generated_file_name) VALUES(?, ?, ?, ?)";
      $resultSet = executePreparedStatement($connection, $sql_prepared, array($original_file_name, $extension, $unique_id, $file_name));
      $file_auto_id = $connection->insert_id;
      $num_rows_inserted = $resultSet[1]->affected_rows;
      if($num_rows_inserted != 1){
        writeToError('FAILED UPLOAD:: There was an issue uploading the file to the database');
        $flag_upload = false;
      }
    }

    // add to product_files pivot table
    if($flag_upload){
      $sql_prepared = "INSERT INTO Product_files (product_id, product_auto_id, file_id) VALUES(?, ?, ?)";
      $resultSet = executePreparedStatement($connection, $sql_prepared, array($product_id, $newProduct->get_auto_id(), $file_auto_id));
    }

    // the item was added successfully
    setFlash(FLASH_SUCCESS, 'The product was added into the inventory system successfully');
    redirect('/home.php');
  }
  

  /*******************************************************************************/
  /*************************** NEW PRODUCT TYPE FUNCTIONS  ****************************/
  /*******************************************************************************/

?>