<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
  
  <div class="container-fluid">
    <!-- Brand -->
    <a class="navbar-brand" href="/home.php">Inventory Management System</a>
    
    <!-- Collapsable button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse">

      <!-- Left side -->
      <ul class="navbar-nav">

        <!-- Home -->
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/home.php">Home</a>
        </li>

        <!-- New product -->
        <li class="nav-item">
          <a class="nav-link" href="/new.php">Add Product</a>
        </li>

        <!-- New Product Type -->
        <!-- <li class="nav-item">
          <a class="nav-link" href="/newProductType.php">New Product type</a>
        </li> -->

        <!-- New Manufacturer -->
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">New Manufacturer</a>
        </li> -->
      </ul>

      <!-- Right side -->
      <ul class="navbar-nav ms-auto">
        
        <?php
          require_once '../resources/config.php';
          
          // show the logout button if the user is signed in
          if(isSignedIn()){
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="/logout.php">Logout</a>';
            echo '</li>';
          }
          else{
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="/login.php">Login</a>';
            echo '</li>';

            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="/register.php">Register</a>';
            echo '</li>';
          }
        ?>
      </ul>

    </div>

  </div>
</nav>