<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS Stylesheets -->
    <?php
      // bootstrap 5
      echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">' . "\n";

      global $config;
      if(isset($cssArray)){
        foreach($cssArray as $cssFile){
          echo '    <link rel="stylesheet" href="' . $cssFile  . '">' . "\n";
        }
      }
    ?>

    <!-- Title -->
    <title>Inventory Manager - <?php echo $title ?></title>
  </head>

  <body>
  <!-- NavBar -->
  <?php
    include_once('navbar.php');
  ?>