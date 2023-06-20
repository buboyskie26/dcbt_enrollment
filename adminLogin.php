<?php
    include('includes/config.php');
    include('includes/classes/form-helper/Account.php');
    include('includes/classes/form-helper/Constants.php');
    include('includes/classes/form-helper/FormSanitizer.php');

    $account = new Account($con);

    if(isset($_POST['loginButton'])){

        $username = FormSanitizer::SanitizeFormUsername($_POST['username']);
        $password = FormSanitizer::SanitizeFormUsername($_POST['password']);

        $wasSuccessful = $account->loginAdminUser($username, $password);

        if($wasSuccessful == true){
            $_SESSION['adminLoggedIn'] = $username;
            header("Location: admin/index.php");
        }
    };

    function getInputValue($input){
        if(isset($_POST[$input])){
            echo $_POST[$input];
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>ELMS_THESIS</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 
    </head>
    <body>
        <div class="signInContainer">
            <div class="column">
                <div class="header">
                    <!-- <img src="assets/images/icons/VideoTubeLogo.png" title="logo" alt="Site logo"> -->
                    <h3 class="text-center text-muted">Admin</h3>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="cashierLogin.php">
                                <button class="btn btn-success btn-sm">Cashier</button>
                            </a>
                        </div>
                        <div class="col-md-4">
<a href="registrar_login.php">
                        <button class="btn btn-secondary btn-sm">Registrar</button>
                    </a>
                        </div>

                        <div class="col-md-4">
  <a href="teacher_login.php">
                        <button class="btn btn-info btn-sm">Teacher</button>
                    </a>
                        </div>
                    </div>
                </div>
                
               

                <div class="loginForm">
                    <form action="adminLogin.php" method="POST">

                        <?php echo $account->getError(Constants::$loginFailed) ?>
                        <input  type="text" value="admin" value="<?php echo getInputValue('username') ?>" name="username" placeholder="Admin Username" autocomplete="off" required>
 
                        <input type="password" name="password" value="123456" placeholder="Password" autocomplete="off" required>

                        <input type="submit" name="loginButton" value="Login">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

