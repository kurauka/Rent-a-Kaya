<?php ob_start();
    session_start();

        // LOGIN SCRIPT


      /* DATABASE CONNECTION*/
      require "functions/db.php";
      /*DATABASE CONNECTION */

       $conn=$connection; //global connection as initialized in functions/db.php

    // database availability error (if any)
    $db_err = "";


      // Define variables and initialize with empty values

      $email = $password = "";

      $email_err = $password_err = "";



      // Processing form data when form is submitted

      if($_SERVER["REQUEST_METHOD"] == "POST"){



          // Check if email is empty

          if(empty(trim($_POST["email"]))){

              $email_err = 'Please enter an email address.';

          } else{

              $email = trim($_POST["email"]);

          }



          // Check if password is empty

          if(empty(trim($_POST['password']))){

              $password_err = 'Please enter your password.';

          } else{

              $password = trim($_POST['password']);

          }


          } // Closing brace for the credentials-validation if block

          // Validate credentials

          if(empty($email_err) && empty($password_err)){

              // Prepare a select statement
              $sql = "SELECT email, password FROM admin WHERE email = ?";

              // Ensure we have a valid mysqli connection before using mysqli_prepare
              if (!empty($conn) && (is_object($conn) || is_resource($conn))) {
                  $stmt = @mysqli_prepare($conn, $sql);
                  if ($stmt) {
                      // Bind variables to the prepared statement as parameters
                      mysqli_stmt_bind_param($stmt, "s", $param_email);
                      // Set parameters
                      $param_email = $email;
                      // Attempt to execute the prepared statement
                      if(mysqli_stmt_execute($stmt)){
                          // Store result
                          mysqli_stmt_store_result($stmt);
                          // Check if email exists, if yes then verify password
                          if(mysqli_stmt_num_rows($stmt) == 1){
                              // Bind result variables
                              mysqli_stmt_bind_result($stmt, $email, $hashed_password);
                              if(mysqli_stmt_fetch($stmt)){
                                  if(password_verify($password, $hashed_password)){
                                      /* Password is correct, so start a new session and
                                      save the email to the session */
                                      $_SESSION['email'] = $email;
                                      header("Location: index.php");
                                  } else{
                                      // Display an error message if password is not valid
                                      $password_err = 'The password you entered was not valid. Please try again.';
                                  }
                              }
                          } else{
                              // Display an error message if email doesn't exist
                              $email_err = 'No account found with that email. Please recheck and try again.';
                          }
                      } else{
                          // execution failed
                          $db_err = 'Database error: failed to execute query.';
                      }
                      // close stmt
                      if (isset($stmt) && $stmt) mysqli_stmt_close($stmt);
                  } else {
                      // prepare failed -> DB unavailable or bad connection
                      $db_err = 'Cannot connect to the database. Please try again later.';
                  }
              } else {
                  $db_err = 'Cannot connect to the database. Please try again later.';
              }




          // Close connection if it exists
          if (isset($conn) && $conn) mysqli_close($conn);

      }



      ?>

    <!--- LOGIN SCRIPT----->


    
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/icon.png">
    <title>Company Admin</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- login custom styles (modernized) -->
    <link href="css/login-custom.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="css/colors/blue.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
        <section class="login-wrapper">
            <div class="login-card" role="main">
                <div class="login-brand">
                    <h2 class="login-title">Rent-A-Kaya Admin</h2>
                    <div class="login-subtitle">Sign in to manage houses, tenants and invoices</div>
                </div>

                <?php if(!empty($email_err) || !empty($password_err)) { ?>
                    <div class="alert-error">
                        <?php echo htmlspecialchars($email_err . ' ' . $password_err); ?>
                    </div>
                <?php } ?>

                <form id="loginform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" novalidate>
                    <div class="form-group input-group-icon">
                        <i class="fa fa-envelope"></i>
                        <input class="form-control" type="email" name="email" required placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                    </div>

                    <div class="form-group input-group-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control" type="password" name="password" required placeholder="Password">
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary btn-block" type="submit" name="submit">Sign In</button>
                    </div>
                </form>
                <div class="footer-note">© <?php echo date('Y'); ?> Rent-A-Kaya</div>
            </div>
        </section>
               <!--  <form class="form-horizontal" id="recoverform" action="index.php">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3>Recover Password</h3>
                            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                        </div>
                    </div>
                </form> -->
            </div>
        </div>
    </section>
    <!-- jQuery -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/tether.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
