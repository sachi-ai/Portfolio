<?php
require_once '../config.php';

if(file_exists('../validation/user_input.php')) {
  require '../validation/user_input.php';
} else {
  die('Validation file not found.');
}

$success = 0;
$userExist = 0;
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $firstname = trim($_POST['firstname']);
  $lastname = trim($_POST['lastname']);
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  $alerts = validateUserInput($firstname, $lastname, $username, $password);
  $error = false;
  foreach ($alerts as $key => $value) {
    if ($value > 0) {
      $error = true;
      break;
    }
  }
  if (!$error) {
    // User already exists check
    $stmt = $conn->prepare("SELECT * FROM `user` WHERE `username` = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $userExist = 1;
    } else {
      $password_hash = password_hash($password, PASSWORD_DEFAULT);
      // Insert new user
      $stmt = $conn->prepare("INSERT INTO `user` (`firstname`, `lastname`, `username`, `password`, `date_added`) VALUES (?, ?, ?, ?, NOW())");
      $stmt->bind_param("ssss", $firstname, $lastname, $username, $password_hash);
      if ($stmt->execute()) {
        $success = 1;
      } else {
        $msg = "<div class=\"alert alert-danger\">Error: {$stmt->error}</div>";
      }
      $stmt->close();
    }
  } else {
    foreach ($alerts as $key => $value) {
      switch ($key) {
        case 'AlertFirstname':
          if ($value == 1) {
            $FirstnameError = true;
          }
          break;
        case 'AlertLastname':
          if ($value == 1) {
            $LastnameError = true;
          }
          break;
        case 'AlertUsername':
          if ($value == 1) {
            $UsernameError = true;
          } elseif ($value == 2) {
            $UsernameInvalid = true;
          }
          break;
        case 'AlertPassword':
          if ($value == 1) {
            $PasswordError = true;
          } elseif ($value == 2) {
            $PasswordInvalid = true;
          }
          break;
      }
      $FirstnameError = $FirstnameError ?? false;
      $LastnameError = $LastnameError ?? false;
      $UsernameError = $UsernameError ?? false;
      $PasswordError = $PasswordError ?? false;
      $UsernameInvalid = $UsernameInvalid ?? false;
      $PasswordInvalid = $PasswordInvalid ?? false;
    }
  }
}
?>

<!doctype html>
<html lang="en">
<?php require_once 'includes/header.php'; ?>
<!--begin::Body-->

<body class="register-page bg-body-secondary">
  <div class="register-box">
    <!-- /.register-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header">
        <a href="./register.php" class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover ">
          <h1 class="mb-0"><b>Signup</b>Form</h1>
        </a>
      </div>
      <div class="card-body register-card-body">
        <?php 
        if (!empty($msg)) {
          echo $msg;
        }
        if ($userExist) {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Username already exists. Please choose a different username.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
        if ($success) {
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Registration successful. You can now log in.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
        ?>   
          
        <form action="" id="register-frm" method="post">
          <div class="input-group mb-1">
            <div class="form-floating">
              <input id="registerFirstName" type="text" class="form-control" placeholder="" name="firstname" value="<?php echo isset($firstname) ? htmlspecialchars($firstname) : ''; ?>">
              <label for="registerFirstName">Firstname</label>
            </div>
            <div class="input-group-text"><span class="bi bi-person"></span></div>
          </div>
         
          <div class="input-group mb-1">
            <div class="form-floating">
              <input id="registerLastName" type="text" class="form-control" placeholder="" name="lastname" value="<?php echo isset($lastname) ? htmlspecialchars($lastname) : ''; ?>">
              <label for="registerLastName">Lastname</label>
            </div>
            <div class="input-group-text"><span class="bi bi-person-vcard"></span></div>
          </div>
         
          <div class="input-group mb-1">
            <div class="form-floating">
              <input id="registerUsername" type="text" class="form-control" placeholder="" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
              <label for="registerUsername">Username</label>
            </div>
            <div class="input-group-text"><span class="bi bi-at"></span></div>
          </div>
         
          <div class="input-group mb-1">
            <div class="form-floating">
              <input id="registerPassword" type="password" class="form-control" placeholder="" name="password">
              <label for="registerPassword">Password</label>
            </div>
            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
          </div>
          
          <!--begin::Row-->
          <div class="row">
            <div class="col-8 d-inline-flex align-items-center">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                  I agree to the <a href="#">terms</a>
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary" name="submit">Sign In</button>
              </div>
            </div>
            <!-- /.col -->
          </div>
          <!--end::Row-->
        </form>
        <div class="social-auth-links text-center mb-3 d-grid gap-2">
          <p>- OR -</p>
          <a href="#" class="btn btn-primary">
            <i class="bi bi-facebook me-2"></i> Sign in using Facebook
          </a>
          <a href="#" class="btn btn-danger">
            <i class="bi bi-google me-2"></i> Sign in using Google+
          </a>
        </div>
        <!-- /.social-auth-links -->
        <p class="mb-0">
          <a href="./login.php" class="link-primary text-center"> I already have an account </a>
        </p>
      </div>
      <!-- /.register-card-body -->
    </div>
  </div>
  <!-- /.register-box -->
  <?php require_once 'includes/footer.php'; ?>
</body>
<!--end::Body-->

</html>
