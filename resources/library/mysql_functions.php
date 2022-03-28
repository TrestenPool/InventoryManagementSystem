<?php

function connectToDB($dbArray){
  $conn = new mysqli($dbArray["host"],$dbArray["username"],$dbArray["password"], $dbArray["dbname"] );
  if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
  }

  return $conn;
}


// takes in a connection to a db $connection and a prepared statement $sql
// returns the result set
function executeSqlQuery($connection, $sql){
  $result = $connection->query($sql);

  if(mysqli_errno($connection) != 0){
    throw new Exception('There was an error with error code ' . mysqli_errno($connection));
  }

  $resultSet = array();
  while($row = mysqli_fetch_array($result)){
    $resultSet[] = $row;
  }

  return $resultSet;
  // in order to access 
  // foreach($resultSet as $row){
  // $row['columnName']
  // }
}

?>