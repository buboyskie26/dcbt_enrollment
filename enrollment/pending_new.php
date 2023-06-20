<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>


<?php 

    require_once('./classes/Pending.php');
    require_once('./classes/Email.php');
    require_once('../includes/config.php');
    require_once('../includes/classes/form-helper/Constants.php');

    $pending = new Pending($con);

    require "../vendor/autoload.php";

    // use Dompdf\Dompdf;

    use PHPMailer\PHPMailer\PHPMailer;
 
    $mail = new PHPMailer(true);

    // if (class_exists('Dompdf\Dompdf')) {
    //     echo "Dompdf is working correctly.";
    // } else {
    //     echo "autoload.php is not working.";
    // }

    if(

        isset($_POST['pending_submit_btn']) &&
        isset($_POST['pending_firstname']) &&
        isset($_POST['pending_lastname']) &&
        isset($_POST['pending_mi']) &&
        isset($_POST['email_address']) &&
        isset($_POST['pending_password']) ){

        $pending_firstname = $_POST['pending_firstname'];
        $pending_lastname = $_POST['pending_lastname'];
        $pending_mi = $_POST['pending_mi'];
        $pending_password = $_POST['pending_password'];
        $email_address = $_POST['email_address'];

        // Generate a unique token for the user
        // Store the token and user's email address in your database
        try {

            $email = new Email();

            $token = $email->generateToken();

            $isEmailSent = $email->sendVerificationEmail($email_address,
                $token);
            
            if ($isEmailSent) {

                $wasSuccess = $pending->PendingRegularFormSubmitv2($pending_firstname, $pending_lastname, 
                    $pending_mi, $pending_password, $email_address, $token);
                
                if($wasSuccess == true){

                    // echo "
                    //     <script>
                    //         alert('Please check you email to proceed.');
                    //     </script>
                    // ";

                    Constants::success("Please check your email to proceed.", "");
                }
            }
                        
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } 
?>
<html>
    <body>
        <div class="signInContainer">
            <div style="width: 520px;" class="column">
                <div class="header">
                    <!-- <img src="assets/images/icons/VideoTubeLogo.png" title="logo" alt="Site logo"> -->
                    <h3 class="text-center text-muted">Pre-Enrollment Register</h3>
                    <span style="font-size: 15px;">To create an applicant account, provide your basic information first which ill be used to verify your email address.</span>
                </div>
             
                <div class="loginForm" style="margin-bottom: 15px; padding-bottom: 15px;">
                    <form method="POST">
                        <label for="">Firstname</label>
                        <input  type="text" name="pending_firstname" placeholder="Firstname" autocomplete="off">
                        <label for="">Lastname</label>
                        <input  type="text" name="pending_lastname" placeholder="Lastname" autocomplete="off">
                        
                        <label for="">Middle Name</label>
                        <input  type="text" value="" name="pending_mi" placeholder="Middle Initial" autocomplete="off">
                        <label for="">Email</label>
                        <input  type="text" value="" name="email_address" placeholder="Email" autocomplete="off">

                        <label for="">Password</label>
                        <input type="password" name="pending_password" value="123456" placeholder="Password" autocomplete="off">

                        <div style="margin-top:10px; display: flex;flex-direction: center;align-items: center;justify-content: center;" class="register_div">
                            <button style="width: 180px;" type="submit" name="pending_submit_btn" class="btn btn-success">Register</button>
                        </div>

                        <!-- <div class="row">
                            
                            <div class="col-md-3">
                                <button type="submit" name="pending_submit_btn" class="btn btn-success">Register</button>
                            </div>
                            <div style="text-align: center;" class="col-md-6">
                                <a href="forgot_password.php">
                                    <button type="button" class="btn btn-danger">Forgot Password</button>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="resend_verification_email.php">
                                    <button type="button" class="btn btn-primary">Resend</button>
                                </a>
                            </div>
                        </div> -->

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>


