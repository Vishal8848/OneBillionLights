<?php

    require_once('dbops.php');

    # OTP Creation
    $OTP = rand(100000, 999999);

    # Access SESSION
    session_start();

    # Mailing Details
    $Recipient = trim($_SESSION['OTPMail']);
    $Subject = "Email Verification for One Billion Lights";
    $Message = "Greetings from One Billion Lights\n\nNew Device attempted for Account Access. Enter below OTP in your new device to grant access.\n\n   Device Signature : " . get_browser(null, true)['browser'] . " on " . get_browser(null, true)['platform'] . "\n\n   Verification Code : " . $OTP . "\n\nWARNING: Make sure this action is performed intentionally, as this may grant access to your account or to change account password.\n\nNOTE: Do not share OTP to untrusted audience, this may make your account insecure\n\nIf you don't recognize this activity, please report at (OBL link) as soon as possible.\n\n Thanks\nOneBillionLights";
    $Headers = "From: onebillionlights@gmail.com";

    # Sending Mail
    if(mail($Recipient, $Subject, $Message, $Headers)) {
        $_SESSION['OTP'] = password_hash($OTP, PASSWORD_DEFAULT);
        $_SESSION['OTPDuration'] = strtotime(date("y-m-d H:i:s"));
        $OBL->otpRequest($_SESSION['OTPMail'], session_id(), $_SESSION['OTP']);
        if(isset($_SESSION['OTP'])) echo TRUE;
    }   else echo FALSE;

?>