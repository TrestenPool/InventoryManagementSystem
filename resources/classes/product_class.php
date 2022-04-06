<?php
  class Product{
    
    private $products_array = null;
    private $manufacturers_array = null;
    
    private $product_id; // represents the auto_id in the Product table
    private $manufacturer_id; // represents the auto_id in the Manufacturers table
    private $serial_number; // represents the serial number of the product
    private $active_flag; // represents the active flag of the product

    // constructor
    public function __construct($product_id, $manufacturer_id, $serial_number, $active_flag){
      $this->set_product_id($product_id);
      $this->set_manufacturer_id($manufacturer_id);
      $this->set_serial_number($serial_number);
      $this->set_active_flag($active_flag);
    }

    // setters
    public function set_product_id($product_id){
      $this->product_id = $product_id;
    }
    public function set_manufacturer_id($manufacturer_id){
      $this->manufacturer_id = $manufacturer_id;
    }
    public function set_serial_number($serial_number){
      $this->serial_number = $serial_number;
    }
    public function set_active_flag($active_flag){
      $this->active_flag = $active_flag;
    }

    // setters for the arrays
    public function set_products_array($products_array){
      $this->products_array = $products_array;
    }
    public function set_manufacturers_array($manufacturers_array){
      $this->manufacturers_array = $manufacturers_array;
    }

    // getters
    public function get_product_id(){
      return $this->product_id;
    }
    public function get_manufacturer_id(){
      return $this->manufacturer_id;
    }
    public function get_serial_number(){
      return $this->serial_number;
    }
    public function get_active_flag(){
      return $this->active_flag;
    }

    // getters for the arrays
    public function get_products_array(){
      return $this->products_array;
    }
    public function get_manufacturers_array(){
      return $this->manufacturers_array;
    }


    /*******************************************************************/
    /************************ MYSQLI FUNCTIONS *************************/
    /*******************************************************************/

    /* CREATE 
    * creates the product and inserts into the db
    */
    public function create(){

    }

    /* UPDATE 
    * upates the current product in the db with the new values provided in the parameters
    */
    public function update($new_product_id, $new_manufacturer_id, $new_serial_number, $new_active_flag){
      global $config;
      $conn = connectToDB($config['db']['db1']);

      $new_product_name = Product::convert_productID_to_productName($new_product_id, $this->get_products_array());
      $new_table_name = Product::convert_productName_to_tableName($new_product_name);

      $old_table_name = Product::convert_productID_to_productName($this->get_product_id(), $this->get_products_array());
      $old_table_name = Product::convert_productName_to_tableName($old_table_name);

      $new_manufacturer_name = Product::convert_manufacturerId_to_manufacturerName($new_manufacturer_id, $this->get_manufacturers_array());

      // the product type was changed
      if( $this->get_product_id() != $new_product_id ){

        // first delete the current product from the table
        $sql_prepared = "DELETE FROM " . $old_table_name . " WHERE manufacturer_id = ? AND serialNumber = ?";
        $deleteResult = executePreparedStatement($conn, $sql_prepared, array($this->get_manufacturer_id(), $this->get_serial_number()));

        // insert the product into the new table with the new values
        $sql_prepared = "INSERT INTO " . $new_table_name . "(manufacturer_id, serialNumber, Active) VALUES (?,?,?);";
        $insertResult = executePreparedStatement($conn, $sql_prepared, array($new_manufacturer_id, $new_serial_number, $new_active_flag));
      }
      // this is just going to be an update into the same table
      else{
        $sql_prepared = "UPDATE " . $old_table_name  . " SET manufacturer_id = ?, serialNumber = ?, Active = ? WHERE manufacturer_id = ? AND serialNumber = ?";
        $updateResult = executePreparedStatement($conn, $sql_prepared, array($new_manufacturer_id, $new_serial_number, $new_active_flag, $this->get_manufacturer_id(), $this->get_serial_number()));
      }

      // set the new values 
      $this->set_product_id($new_product_id);
      $this->set_manufacturer_id($new_manufacturer_id);
      $this->set_serial_number($new_serial_number);
      $this->set_active_flag($new_active_flag);

      // redirect to the show page 
      setFlash(FLASH_SUCCESS, 'The product updated successfully');
      $url_redirect = sprintf("/edit.php?tableName=%s&serialNumber=%s&manufacturerName=%s", $new_table_name, $new_serial_number, $new_manufacturer_name);
      redirect($url_redirect);
    }

    /* DELETE 
    * deletes the product from the db
    */
    public function delete(){
    }

    /* GET PRODUCT FROM DB
    * input is the table name & serial number  
    * output: if found in the db, a product with the fields filled in
    * otherwise will return null if no result was found
    */
    public static function getProductFromDb($product_id, $table_name, $manufacturer_id, $serial_number){
      global $config;
      $conn = connectToDB($config['db']['db1']);

      // gets the results from the db
      $sql_prepared = "SELECT * from " . $table_name . " WHERE manufacturer_id = ? AND serialNumber = ?";
      $resultSet = executePreparedStatement($conn, $sql_prepared, array($manufacturer_id, $serial_number));
      
      // product we will be returning
      $product_fetched = null;

      foreach ($resultSet as $row) {
        $active_flag = $row['Active'];
        $product_fetched = new Product($product_id, $manufacturer_id, $serial_number, $active_flag);
        return $product_fetched;
      }

      return $product_fetched;
    }




    /*******************************************************************/
    /************************ STATIC FUNCTIONS *************************/
    /*******************************************************************/

    /*
    * returns true if the $serialNumber is valid
    * returns false otherwise
    */
    public static function isValid_serialNumber($serialNumber){
      // serial number is null
      if(!$serialNumber){
        return false;
      }

      // make sure the serial number is 32
      if(strlen($serialNumber) != 32){
        return false;
      }

      // make sure string only contains numbers and or lower case letters
      if (preg_match('/^[0-9a-z]+$/', $serialNumber)) {
        return true;
      }
      else {
        return false;
      }

    }

    /* 
    * input is a product name
    * output is the table name for that product
    */
    public static function convert_productName_to_tableName($product_name){
      $product_name = str_replace(' ', '_', $product_name); // replace spaces with underscores
      $product_name = $product_name . '_table';
      return $product_name;
    }

    /* 
    * input: productID and the products array
    * output: product name or null if it does not exists
    */
    public static function convert_productID_to_productName($product_id, $productArray){
      if(isset($productArray[$product_id])){
        return $productArray[$product_id];
      }

      return null;
    }

    /* 
    * input is a table name
    * output is the product name
    */
    public static function convert_tableName_to_productName($table_name){
      $product_name = str_replace('_table', '', $table_name); // remove the end 
      $product_name = str_replace('_', ' ', $product_name);
      return $product_name;
    }


    /*
    * input is a product name and the products array
    * output is the product_id for that product name in the products array
    * return null if the product name is not found (case sensitive)
    */
    public static function convert_productName_to_productId($product_name, $products_array){
      if(in_array($product_name, $products_array)){
          return array_search($product_name, $products_array);
      }

      return null;
    }


    /* 
    * input is a manufacturer name
    * output is the id for that manufacurer
    * return null if the manufacturer name is not found (case sensitive)
    */
    public static function convert_manufacturerName_to_id($manufacturer_name, $manufacturers_array){
      if(in_array($manufacturer_name, $manufacturers_array)){
        return array_search ($manufacturer_name, $manufacturers_array);
      }
      return null;
    }

    /* 
    * input is the manufacturer id
    * output is the name for the manufacturer
    * return null if the manufacturer id was not found
    */
    public static function convert_manufacturerId_to_manufacturerName($manufacturer_id, $manufacturers_array){
     if(isset($manufacturers_array[$manufacturer_id])) {
       return $manufacturers_array[$manufacturer_id];
     }
     
     return null;
    }

  }

?>