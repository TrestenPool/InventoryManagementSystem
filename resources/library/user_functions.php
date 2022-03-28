<?php

// returns the hashed password of $password
function hashedPassword($password){
  global $config;
  return password_hash($password, $config["Hashing_Algorithm"]);
}

// returns true if the user is signed in, false otherwise
function isSignedIn(){
  
  // check if the session variable exists
  if(!isSessionSet()){
    return false;
  }

  if( $_SESSION["session"]->get_isSignedIn() === true ){
    return true;
  }

  return false;

}

// connection to a db has been passed with $conn
// the username and password are being passed to check if the user is logged in the db
// returns true if the user is registered, false otherwise
function attemptSignIn($username, $password){
  global $config;

  // get the connection to the db
  $connection = connectToDB($config['db']['db1']);

  // prepared statement sent to query db
  $stmt = $connection->prepare( "SELECT * FROM Users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();

  $resultSet = $stmt->get_result();

  // no results
  if($resultSet->num_rows == 0){
    return false;
  }

  // get the result form the resultset
  $row = $resultSet->fetch_assoc();

  // return false if the result is not in the correct format
  if(!isset($row) || !isset($row["username"]) || !isset($row["password"])){
    return false;
  }

  // return true if the username and password match
  if(strcmp($row['username'], $username) === 0){
    if(password_verify($password, $row["password"])){
      $_SESSION["session"]->set_isSignedIn(true);
      $_SESSION["session"]->set_username($username);
      return true;
    }
  }

  // default to false
  return false;
}

// return true if able to logout
// return false otherwise
function attemptLogout(){
  // session not set
  if(!isSessionSet()){
    return false;
  }

  // user is not signed in
  if(!isSignedIn()){
    return false;
  }

  $_SESSION['session']->set_isSignedIn(false);
  return true;
}

?>