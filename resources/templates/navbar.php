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
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Pricing</a>
        </li>
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