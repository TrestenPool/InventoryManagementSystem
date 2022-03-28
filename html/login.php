<?php
require_once '../resources/config.php';

$cssStyleSheets = array(
   $config['paths']['css'] . '/login.css'
  );

generateHeader($cssStyleSheets, 'Login');

printFlash();

// TODO: throw flash message telling the user they are already signed in, disable username/password fields
if (isSignedIn()) {
  redirect('/home.php');
}

/******* POST REQUEST  ********/
if (isset($_POST["submit"])) {
  if (attemptSignIn($_POST["username"], $_POST["password"])) {
    setFlash(FLASH_SUCCESS, 'WELCOME BACK ' .$_SESSION['session']->get_username());
    redirect('/home.php');
  } else {
    setFlash(FLASH_DANGER, 'Login failed..');
    redirect('/login.php');
  }
}

/******* GET REQUEST  ********/
?>
<section class="h-100 gradient-form">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-xl-10">
        <div class="card rounded-3 cardColor text-black">
          <div class="row g-0">
            <div class="col-lg-6">
              <div class="card-body p-md-5 mx-md-4">

                <div class="text-center">
                  <h4 class="mt-1 mb-5 pb-1">Inventory Management System <br>Login</h4>
                </div>

                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="needs-validation" novalidate method="POST">
                  <!-- Username -->
                  <div class="form-outline mb-2">
                    <input type="text" id="username" class="form-control" name="username" required />
                    <label class="form-label" for="username">Username</label>
                    <!-- invalid feedback -->
                    <div class="invalid-feedback">
                      username is required!!
                    </div>
                  </div>

                  <!-- Password -->
                  <div class="form-outline mb-4">
                    <input type="password" id="password" class="form-control" name="password" required />
                    <label class="form-label" for="password">Password</label>
                    <!-- invalid feedback -->
                    <div class="invalid-feedback">
                      password is required!!
                    </div>
                  </div>

                  <!-- Submit button -->
                  <div class="text-center pt-1 mb-5 pb-1">
                    <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit" name="submit">Log in</button>
                    <!-- <a class="text-muted"  href="/forgotPassword.php">Forgot password?</a> -->
                  </div>

                  <!-- Register button -->
                  <div class="d-flex align-items-center justify-content-center pb-4">
                    <a href="/register.php" class="btn btn-outline-danger">Register New account</a>
                  </div>
                </form>
              </div>
            </div>


            <!-- Text Segement to the right -->
            <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
              <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                <h4 class="mb-4">Author - Tresten Pool</h4>
                <p class="small mb-0">This inventory management system is hosted on an AWS EC2 instance with an Nginx server running on top. This application provides a web interface as well as a fully implemented CRUD API written in PHP,HTML and CSS</p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<?php
$js_library_functions = array(
  $config['paths']['js']  . '/formValidation.js'
);

generateFooter($js_library_functions);;
?>