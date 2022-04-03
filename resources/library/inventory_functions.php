<?php

  /*******************************************************************************/
  /********************************** FILTER OBJECT  *****************************/
  /*******************************************************************************/
  function getFilterObject(){
    if(!isset($_SESSION['filter'])){
      $_SESSION['filter'] = new Filter();
    }

    return $_SESSION['filter'];
  }

  function clearFilterObject(){
    unset($_SESSION['filter']);
  }
  
  /*******************************************************************************/
  /****************** USED TO POPULATE FILTERS ON HOME PAGE ***********************/
  /*******************************************************************************/
  function getProductTypes(){
    global $config;

    $connection = connectToDB($config['db']['db1']);
    $resultSet = executeSqlQuery($connection, 'SELECT * FROM Products');
    $queryParameters = getQueryParameters(array('productType'));
    
    // the array of products we store in the session
    $productsArray = [];

    // go through all the records in the Product table
    /* auto ID = $row['auto_id'] ***** product name = $row['product_name'] **/
    foreach ($resultSet as $row) {
      // use the query parameters to find the product supposed to be selected
      if($queryParameters['productType']){
        echo sprintf("<option value=\"%s\"%s>%s</option>", $row['product_name'], ($queryParameters['productType'] == $row['product_name'])?' selected':'', $row['product_name'] );
      }
      // use the filter object to find the one that will be selected
      else{
        echo sprintf("<option value=\"%s\"%s>%s</option>", $row['product_name'], (getFilterObject()->get_productID_selected() == $row['auto_id'])?' selected':'', $row['product_name'] );
      }

      // set the products array
      $productsArray[$row['auto_id']] = $row['product_name'];
    }

    // assign the filter object products array
    getFilterObject()->set_products_array($productsArray);
  }

  function getManufacturers(){
    global $config;

    $connection = connectToDB($config['db']['db1']);
    $resultSet = executeSqlQuery($connection, 'SELECT * FROM Manufacturer');
    $queryParameters = getQueryParameters(array('manufacturerName'));

    // array we will store in the filter Object
    $manufacturers_array = [];

    // go though all the manufacturers
    /** Auto ID = $row['auto_id'] **** Manufacturer Name = $row['manufacturer_name'] **/
    foreach ($resultSet as $row) {
      // use the query parameter to select
      if($queryParameters['manufacturerName']){
        echo sprintf("<option value=\"%s\"%s>%s</option>", $row['manufacturer_name'], ($queryParameters['manufacturerName'] == $row['manufacturer_name'])?' selected':'', $row['manufacturer_name'] );
      }
      // use the session to select
      else{
        echo sprintf("<option value=\"%s\"%s>%s</option>", $row['manufacturer_name'], (getFilterObject()->get_manufacturerID_selected() == $row['auto_id'])?' selected':'', $row['manufacturer_name'] );
      }
      
      // append to the manufacturer array
      $manufacturers_array[$row['auto_id']]  = $row['manufacturer_name'];
    }

    // set the manufacturers array in the filter object
    getFilterObject()->set_manufacturers_array($manufacturers_array);
  }

  /*******************************************************************************/
  /****************** HELPER FUNCTIONS TO GENERATE THE TABLE DATA ****************/
  /*******************************************************************************/
  /* GET PRODUCT FROM FILTER
  * returns the product that was selected in the string format of the table in the db. ex. computer = computer_table
  * returns -1 if the user selected All Products
  * returns null if no product was selected
  */
  function getProductFromFilter(){
    $productsArray = getFilterObject()->get_products_array();
    $queryParameters = getQueryParameters(array('productType', 'manufacturerName'));

    $productSelected = $queryParameters['productType'];

    if($productSelected != null){

      // all products was selected
      if($productSelected == '-1'){
        getFilterObject()->set_productID_selected(-1);
        return -1;
      }

      // see if the product that was passed is in the list of valid products
      foreach($productsArray as $key=>$value){
        if($productSelected == $value){
          getFilterObject()->set_productID_selected($key); // sets the selected product ID
          $productSelected = str_replace(' ', '_', $productSelected); // replace spaces with underscores
          $productSelected = $productSelected . '_table';
          return $productSelected;
        }
      }

    }
    else{
      // no product was selected
      return null;
    }

  }

  /* GET MANUFACTURER FROM FILTER
  * returns the manufacturer ID of the manufacturer that was selected ex. Sony = 2
  * returns -1 if the user selected All Manufacturers
  * returns null if no Manufacturer was selected
  */
  function getManufacturerFromFilter(){
    global $config;
    $manufacturers_array = getFilterObject()->get_manufacturers_array();
    $queryParameters = getQueryParameters(array('productType', 'manufacturerName'));

    // gets the manufacturer that was selected
    $manufacturer_selected = $queryParameters['manufacturerName'];

    // user passed query parameter manufacturerName
    if($manufacturer_selected != null){

      // user selected all products
      if($manufacturer_selected == -1){
        getFilterObject()->set_manufacturerID_selected(-1);
        return -1;
      }

      // see if the manufacturer name provided is valid
      foreach($manufacturers_array as $key=>$value){
        if($manufacturer_selected == $value){
          getFilterObject()->set_manufacturerID_selected($key); // saves the manufacturer that was selected by the name
          return $key;
        }
      }

    }
    else{
      return null;
    }


  }


  
  /*******************************************************************************/
  /*************** USED TO GENERATE THE TABLE ON THE HOME PAGE *******************/
  /*******************************************************************************/
  function generateColumnNames(){
    echo '<table class="table table-striped">';
    echo '<thead>';
      echo '<tr>';
        echo '<th>Manufacturer</th>';
        echo '<th>SerialNumber</th>';
        echo '<th>Actions</th>';
      echo '</tr>';
    echo '</thead>';
  }

  function generateTableEntries(){
    global $config;

    $productsArray = getFilterObject()->get_products_array();
    $manufacturers_array = getFilterObject()->get_manufacturers_array();

    // connect to the db
    $connection = connectToDB($config['db']['db1']);
    
    // query parameters array results from the url
    $queryParameters = getQueryParameters(array('productType', 'manufacturerName'));

    /** Get the items selected from the filter **/
    $table_name = getProductFromFilter(); // gets the product
    $manufacturer_id = getManufacturerFromFilter(); // gets the manufacturer
    // $serial_number = getSerialNumber(); // gets the serialNumber field TODO: 

    // if either of the filters is not set, return
    if($table_name == null || $manufacturer_id == null){
      return;
    }

    // pagination object
    $pagination = new Zebra_Pagination();
    $recordsPerPage = 20;
    
    /*** Logic to display the table entries depending on the item selected in the filter ***/
    // search for all products under any manufacturer
    if($table_name === -1 && $manufacturer_id === -1){
      //TODO: return all products for all manufacturers
    }
    // search for all products for the given manufacturer_id
    else if($table_name === -1){
      //TODO: return all products for the given manufacturer
    }
    // search for the product specified with any manufacturer
    else if($manufacturer_id === -1){
      //TODO: return the prouduct specified for all manufacturers
    }
    // both the manufacturer_id and the product were specified
    else{
      $sql_prepared = 'SELECT * FROM ' . $table_name . ' WHERE manufacturer_id = ? LIMIT ' . (($pagination->get_page() - 1) * $recordsPerPage) . ', ' . $recordsPerPage . '' ;
      $resultSet = executePreparedStatement($connection, $sql_prepared, array($manufacturer_id));

      // gets the number of records from the table
      $sql2 = 'SELECT COUNT(*) as num_records FROM ' . $table_name . ' WHERE manufacturer_id = ?';
      $result2 = executePreparedStatement($connection, $sql2, array($manufacturer_id));

      // pass the total number of records to the pagination class
      $pagination->records($result2->fetch_assoc()['num_records']);
      $pagination->records_per_page($recordsPerPage);
    }

    /** format the results to the table **/
    echo '<tbody>';
    foreach ($resultSet as $row) {
      echo '<tr>';
      echo "<td>" . $manufacturers_array[$row['manufacturer_id']] . "</td>";
      echo "<td>${row['serialNumber']}</td>";

      // Delete button
      $deleteLink = $config['urls']['baseUrl'] . '/edit.php' . '?tableName=' . $table_name . '&serialNumber=' . $row['serialNumber'];
      echo sprintf('<td><a href="%s" style="color:red; margin: 5px"><svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
        </svg></a>'
        ,$deleteLink);

      // edit button
      $editLink = $config['urls']['baseUrl'] . '/edit.php' . '?tableName=' . $table_name . '&serialNumber=' . $row['serialNumber'];
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

?>