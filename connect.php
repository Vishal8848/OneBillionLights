<?php

    require_once('dbops.php');

    # Form Type : Login - 1 / Register - 2
    $formType = 1;

    # Login & Register Flags
    $RegStep = 1;
    $RegStatus = 0;
    $LogMessage = [ 'Welcome to Login', 'Already Registered', 'Registration Successful', 'Registration Failed', 'Login Successful', 'Incorrect Username or Password', 'Password Reset Successful' ];

    # Reset Password Flags
    $Reset = 0;
    $ResetStatus = 0;
    $ResetMessage = [ 'Reset Your Password', 'Email Not Registered', 'Email Verified. Change Password', 'Password Reset Failed' ];

    # Start PHP Session
    session_start();

    # Form Type Handler
    if(isset($_POST['Reg-Type']))   $formType = 2;
    if(isset($_POST['Log_Type']))   $formType = 1;

    # Login Handler
    if(isset($_POST['Log-Submit'])) {

        # Fetch Details from POST
        $Email = htmlspecialchars(trim($_POST['lemail']));
        $Password = htmlspecialchars(trim($_POST['lpasswd']));

        # Check User in Database
        if($OBL->checkUser($Email) && $OBL->inspectUser($Email)) {
            $Credentials = $OBL->fetchUser($Email);
            # Verify Password
            if(password_verify($Password, $Credentials['PASSWORD'])) {
                $_SESSION['login'] = true;
                $OBL->setLogin($Email);
                $RegStatus = 4;
            }   else $RegStatus = 5;
        }   else $RegStatus = 5;
    }

    # Registration Handler
    if(isset($_POST['Reg-Submit'])) {

        # Basic Credentials
        $Email = htmlspecialchars(trim($_POST['remail']));
        $Password = password_hash(htmlspecialchars(trim($_POST['passwd'])), PASSWORD_DEFAULT);

        # Other Credentials
        $Firstname = htmlspecialchars(trim($_POST['fname']));
        $Lastname = htmlspecialchars(trim($_POST['lname']));

        # Store Mail ID for OTP Verification
        $_SESSION['OTPMail'] = $Email;

        if(!$OBL->checkUser($Email))  {
            # Add New user
            if($OBL->addUser($Firstname, $Lastname, $Email, $Password)) $RegStep = 2;
        }   else {
            # Check User Verified
            if(!$OBL->inspectUser($Email)) {
                # Update Existing User
                if($OBL->updateUser($Firstname, $Lastname, $Email, $Password)) $RegStep = 2;
            }   else    $RegStatus = $RegStep = 1;
        }

    }

    # Reset Password Utility
    if(isset($_GET['reset']))   {
        $Reset = 1;

        if(isset($_POST['Reset-Submit']))   {

            # Verify Changer Email
            if(isset($_POST['reset-email']))    {

                # Store Mail ID for OTP Verification and Password Reset
                $_SESSION['OTPMail'] = $Email = htmlspecialchars(trim($_POST['reset-email']));

                if($OBL->checkUser($Email) && $OBL->inspectUser($Email)) $RegStep = 2;
                else $ResetStatus = 1;

            }   else if(isset($_POST['chpasswd'])) {

                # Verified. Change Password
                $Email = $_SESSION['Email'];
                $Password = htmlspecialchars(trim($_POST['chpasswd']));

                $OBL->changePassword($Email, password_hash($Password, PASSWORD_DEFAULT));

                $RegStatus = 6;
                $RegStep = 1;
                $Reset = 0;
            }
        }
    }

    # Verification Handler
    if(isset($_POST['OTP-Submit'])) {
        # Check OTP Timer
        if((strtotime(date("y-m-d H:i:s")) - $_SESSION['OTPDuration']) < 120) {
            # Fetch OTP from Mail & POST
            if(password_verify(htmlspecialchars(trim($_POST['OTP'])), $_SESSION['OTP'])) {
                if($Reset === 0) {
                    # Verify User in Database
                    $OBL->verifyUser($_SESSION['OTPMail']);
                    $RegStatus = 2;
                }   else {
                    # Mail Requesting Password Reset
                    $_SESSION['Email'] = $_SESSION['OTPMail'];
                    $ResetStatus = $Reset = 2;
                }
                unset($_SESSION['OTPDuration']);
                unset($_SESSION['OTPMail']);
                unset($_SESSION['OTP']);
            }   else    {
                if($Reset !== 0)  {
                    $ResetMessage[3] .= ' - Invalid OTP';
                    $ResetStatus = 3;
                }   else {
                    $LogMessage[3] .= ' - Invalid OTP';
                    $RegStatus = 3;
                }
            }        
        }   else {
            if($Reset !== 0)  {
                $ResetMessage[3] .= ' - OTP Expired';
                $ResetStatus = 3;
            }   else {
                $LogMessage[3] .= ' - OTP Expired';
                $RegStatus = 3;
            }
        }
        unset($_POST['OTP-Submit']);
        $RegStep = 1;
    }

?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <!-- Brand -->
        <title>One Billion Lights</title>
        <link rel="icon" href="lightbulb.ico" type="image/icon type">
    
        <!-- Bootstrap v5.0.2 CDN CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        
        <!-- Font Awesome v6.0.0 CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
              
        <!-- Google APIs -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Google Fonts : Quicksand -->
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap" rel="stylesheet">
        
        <!-- Customization CSS -->
        <style>
            * {
                font-family: 'Quicksand', 'Segoe UI';
            }
            body {
                background: #FEF5ED;
            }
            .form-set {
                background: whitesmoke;
                width: fit-content;
                margin: 0 auto;
            }
            .shade {
                box-shadow:
                0 2.8px 2.2px rgba(0, 0, 0, 0.034),
                0 6.7px 5.3px rgba(0, 0, 0, 0.048),
                0 12.5px 10px rgba(0, 0, 0, 0.06),
                0 22.3px 17.9px rgba(0, 0, 0, 0.072),
                0 41.8px 33.4px rgba(0, 0, 0, 0.086),
                0 80px 80px rgba(0, 0, 0, 0.12)
            }
            .toggle-eye {
                position: relative;
            }
            .toggle-eye > i {
                margin-left: -12.5px;
                position: absolute;
                cursor: pointer;
                opacity: 0.5;
                left: 87.5%;
                top: 50%;
            }
            .toggle-eye > i:hover {
                opacity: 1;
            }
            #preloader {
                position: absolute;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.25);
                display: none;
            }
            #preloader > div {
                position: absolute;
                width: 250px;
                height: 200px;
                left: 50%;
                top: 50%;
                margin-top: -100px;
                margin-left: -125px;
            }
            @media only screen and (max-width : 860px) {
                .form-set {
                    width: 96%;
                    margin: 2%;
                }
            }
            @media only screen and (min-width : 860px) {
                .form-set {
                    width: 30%;
                    margin-top: 12.5vh;
                }
            }
        </style>

    </head>

    <body>
        
        <!-- User Form Page -->
        <div id="form-page" class="form-set rounded shade p-4">

            <!-- Form Title -->
            <div class="form-title text-center">
                <strong class="fs-2" style="color: orange">One</strong><strong class="fs-2" style="color: red">Billion</strong><strong class="fs-2" style="color: orangered">Lights</strong>
            </div><hr/>

            <!-- Status Icons -->
            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </symbol>
                <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                </symbol>
                <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </symbol>
            </svg>

            <?php if($RegStep === 1 && !$Reset && $formType === 1): ?>
            <!-- Login Form --> 
            <form id="login" action="connect" method="POST">
                <div class="login-msg text-center mb-1 alert border <?php echo ($RegStatus % 2 !== 0) ? 'alert-danger border-danger' : 'alert-success border-success' ; ?>">
                <?php   
                    if($RegStatus !== 0)
                    echo ($RegStatus % 2 === 0) ? '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>' : '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>';
                    echo $LogMessage[$RegStatus]; 
                ?>
                </div><br/>
                <div class="row mb-4">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="lemail" name="lemail" placeholder="Email" required>
                        <label for="lemail" style="margin-left: 10px">Email <sup style="color: red">*</sup></label>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="form-floating toggle-eye">
                        <input type="password" class="form-control" id="lpasswd" name="lpasswd" placeholder="Password" required>
                        <label for="lpasswd" style="margin-left: 10px">Password <sup style="color: red">*</sup></label>
                        <i class="fas fa-eye fa-lg"></i>
                    </div>
                </div>
                <div class="forgot-pass mt-2">
                    <a href="connect?reset=1" style="text-decoration: none">&nbsp;&nbsp;Forgot Password ?</a>
                </div>
                <div class="form-submit text-center mt-5">
                    <input id="Log-Submit" name="Log-Submit" type="submit" class="btn btn-success me-2" value="Sign In">
                    <input id="Reg-Type" name="Reg-Type" type="submit" class="btn btn-primary ms-2" value="Sign Up" onclick="return formType(2)">
                </div>
            </form>
            <?php endif; ?>

            <?php if($RegStep === 1 && !$Reset && $formType === 2): ?>
            <!-- Register Form --> 
            <form id="register" action="connect" method="POST">
                <div class="register-msg text-center alert alert-primary mb-1 border border-primary">Welcome New User</div><br/>
                <div class="row mb-3">
                    <div class="form-floating col-md-6 border border-light">
                        <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" onkeyup="fieldTracer()" required>
                        <label for="fname" style="margin-left: 10px">First Name <sup style="color: red">*</sup></label>
                        <div id="fname-text" class="text-danger ms-2"></div>
                    </div>
                    <div class="form-floating col-md-6 last">
                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" onkeyup="fieldTracer()">
                        <label for="lname" style="margin-left: 10px">Last Name</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="remail" name="remail" placeholder="Email" required>
                        <label for="remail" style="margin-left: 10px">Email <sup style="color: red">*</sup></label>
                        <div id="remail-text" class="text-danger ms-2"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="form-floating col-md-6 toggle-eye">
                        <input type="password" class="form-control" id="passwd" name="passwd" placeholder="Password" required>
                        <label for="passwd" style="margin-left: 10px">Password <sup style="color: red">*</sup></label>
                        <i class="fas fa-eye fa-lg"></i>
                        <div id="passwd-text" class="text-danger ms-2"></div>
                    </div>
                    <div class="form-floating col-md-6 last toggle-eye">
                        <input type="password" class="form-control" id="cpasswd" name="cpasswd" placeholder="Confirm" required>
                        <label for="cpasswd" style="margin-left: 10px">Confirm<sup style="color: red">*</sup></label>
                        <i class="fas fa-eye fa-lg"></i>
                        <div id="cpasswd-text" class="text-danger ms-2"></div>
                    </div>
                    <div id="passwd-sample" class="text-muted ms-2" style="font-size: 15px">See Example</div>
                </div>
                <div class="form-submit text-center mt-4">
                    <input id="Reg-Submit" name="Reg-Submit" type="submit" class="btn btn-success me-2" value="Sign Up" onclick="return Validation()">
                    <input id="Log-Type" name="Log-Type" type="submit" class="btn btn-primary ms-2" value="Sign In"onclick="return formType(1)">
                </div>
            </form>
            <?php endif; ?>

            <?php if($RegStep === 2): ?>
            <!-- Verify Form -->
            <form id="verify" action="connect<?php echo ($Reset) ? '?reset=1' : ''; ?>" method="POST">
                <div class="text-center alert alert-success mb-1 border border-success">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#check-circle-fill"/></svg>
                    <span class="verify-email text-success">Please Wait ...</span>    
                </div><br/>
                <div class="row mb-3">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="vemail" name="vemail" placeholder="Email" value="<?php echo substr($Email, 0, 4) . '******@' . substr(strrchr($Email, '@'), 1); ?>" readonly>
                        <label for="remail" style="margin-left: 10px">Email <sup style="color: red">*</sup></label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="OTP" name="OTP" placeholder="One Time Password" onkeyup="checkField()" required>
                        <label for="OTP" style="margin-left: 10px">One Time Password<sup style="color: red">*</sup></label>
                    </div>
                </div>
                <div class="form-submit text-center mt-4">
                    <input id="OTP-Submit" name="OTP-Submit" type="submit" class="btn btn-success me-2" value="Submit" disabled>
                    <button id="OTP-Countdown" class="btn btn-primary ms-2" style="width: 125px" onclick="ResendOTP()" disabled>60</button>
                </div>
            </form>
            <?php endif; ?>

            <?php if($Reset && $RegStep !== 2): ?>
            <!-- Reset Form -->
            <form id="reset" action="connect?reset=1" method="post">
                <div class="reset-msg text-center mb-1 alert border <?php echo ($ResetStatus % 2 !== 0) ? 'alert-danger border-danger' : 'alert-primary border-primary'; ?>">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="<?php echo ($ResetStatus % 2 !== 0) ? '#exclamation-triangle-fill' : '#info-fill'; ?>"/></svg>
                    <?php echo $ResetMessage[$ResetStatus]; ?>
                </div><br/>
                <?php if($Reset === 1) { ?>
                <div class="row mb-2">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="reset-email" name="reset-email" placeholder="Email" required>
                        <label for="reset-email" style="margin-left: 10px">Email <sup style="color: red">*</sup></label>
                    </div>
                </div>
                <div class="text-muted ms-2" style="font-size: 15px">Enter Registered Email to Proceed</div>
                <?php } else if($Reset === 2) { ?>
                <div class="row">
                    <div class="form-floating toggle-eye">
                        <input type="password" class="form-control" id="chpasswd" name="chpasswd" placeholder="Password" required>
                        <label for="chpasswd" style="margin-left: 10px">New Password <sup style="color: red">*</sup></label>
                        <i class="fas fa-eye fa-lg"></i>
                        <div id="chpasswd-text" class="text-danger ms-2"></div>
                    </div>
                </div>
                <div class="row mt-4 mb-1">
                    <div class="form-floating toggle-eye">
                        <input type="password" class="form-control" id="ccpasswd" name="ccpasswd" placeholder="Password" required>
                        <label for="ccpasswd" style="margin-left: 10px">Confirm Password <sup style="color: red">*</sup></label>
                        <i class="fas fa-eye fa-lg"></i>
                        <div id="ccpasswd-text" class="text-danger ms-2"></div>
                    </div>
                </div>
                <div id="passwd-sample" class="text-muted ms-2" style="font-size: 15px">See Example</div>
                <?php } ?>
                <div class="form-submit text-center mt-4">
                    <input id="Reset-Submit" name="Reset-Submit" type="submit" class="btn btn-success me-2" value="Continue" <?php if($Reset === 2) echo 'onclick="return ValidatePasswd()"'?>>
                    <a href="connect" class="btn btn-danger ms-2">Back</a>
                </div>
            </form>
            <?php endif; ?>
        
        </div>

        <!-- AJAX Preloader -->
        <div id="preloader">
            <div class="p-5 bg-light shadow-lg rounded text-center">
                <img src="trisection.gif" alt="Loading ...">
                <h4 class="mt-3">LOADING ...</h4>
            </div>
        </div>

        <!-- Bootstrap v5.0.2 CDN JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

        <!-- jQuery v3.6.0 CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>                
    
        <!-- Customization JS -->
        <script src="<?php echo ($RegStep - 1) ? 'verify.js' : (($Reset === 0) ? 'enroll.js' : 'reset.js'); ?>"></script>
    
    </body>

</html>