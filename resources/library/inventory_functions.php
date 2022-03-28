<?php
  
  function generateColumnNames(){
    echo '<thead>';
      echo '<tr>';
        echo '<th>Manufacturer</th>';
        echo '<th>SerialNumber</th>';
      echo '</tr>';
    echo '</thead>';
  }

  function getTableEntries($manufacturers_array){
    global $config;

    // get the connection to the db
    $connection = connectToDB($config['db']['db1']);

    // get the table if there was one that passed in 
    if(isset($_GET['productType'])){
      $table_name = $_GET['productType'];
      // replace all spaces in the string with an underscore
      $table_name = str_replace(' ', '_', $table_name);
    }
    else{
      // $table_name = reset($)
      $table_name = 'computer';
    }

    // append the _table to the end of the product
    $table_name = $table_name . '_table';

    // attempt to get the records for the correct table
    try{
      $resultSet = executeSqlQuery($connection, "SELECT * FROM " . $table_name . " limit 10");
    }
    catch(Exception $e){
      writeToError($e);
      return;
    }

    echo '<tbody>';
    // format the table row
    foreach($resultSet as $row){
      echo '<tr>';
      echo "<td>" . $manufacturers_array[$row['manufacturer_id']] . "</td>";
      echo "<td>${row['serialNumber']}</td>";
      echo '</tr>';
    }

    echo '</tbody>';
  }

  function getProductTypes(){
    global $config;

    // get the connection to the db
    $connection = connectToDB($config['db']['db1']);

    // get all the results from the db
    $resultSet = executeSqlQuery($connection, 'SELECT * FROM Products');

    // place all the products in the select
    foreach ($resultSet as $row) {
      echo "<option value='${row['product_name']}'>${row['product_name']}</option>";
    }
  }

  function getManufacturers(){
    global $config;

    // get the connection to the db
    $connection = connectToDB($config['db']['db1']);

    // get all the results from the db
    $resultSet = executeSqlQuery($connection, 'SELECT * FROM Manufacturer');

    $manufacturers_array = [];
    foreach ($resultSet as $row) {
      $manufacturers_array[$row['auto_id']]  = $row['manufacturer_name'];
    }

    return $manufacturers_array;
  }
?>