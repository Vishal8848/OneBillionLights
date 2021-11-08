$(document).ready(function() {
    adjustForm();
    $('#form-page').animate({
        marginTop: "+=25"
    }, 500, function() {
        $(this).animate({
            marginTop: "-=25"
        }, 750);
    });
    SendOTPByMail(1);
});

$(window).resize(function() {
    adjustForm();
});

function adjustForm() {
    let LastField = $(".last");
    if($(window).width() < 768) {
        LastField.addClass("mt-3");
    }   else {
        LastField.removeClass("mt-3");
    }
}

function Countdown() {
    let N = 60, Resend = $("#OTP-Countdown");
    let Timer = setInterval(() => {
        Resend.html(--N);
        if(N == 1)  {
            Resend.removeAttr('disabled');
            Resend.html('Resend OTP');
            clearInterval(Timer);
        }
    }, 1000);
}

function checkField() {
    let OTP = $('#OTP'), Submit = $('#OTP-Submit');
    (OTP.val().length >= 5) ? Submit.removeAttr('disabled') : Submit.attr('disabled', '');
}

function ResendOTP() {
    $('#OTP-Countdown').attr('disabled', '');
    $('.verify-email').html('Please Wait ...');
    SendOTPByMail(2);
}

function SendOTPByMail(Type) {
    $.ajax({
        url: 'otphandler.php',
        type: 'post',
        beforeSend: function() {
            $('#preloader').show();
        },
        success: function(output) {
            $('#preloader').hide();
        },
        complete: function() {
            if(Type === 2)  $('.verify-email').removeClass('text-danger').addClass('text-success').html('New OTP sent to inbox. Enter OTP to verify email.');
            if(Type === 1)  $('.verify-email').html('Please check your inbox and enter the OTP given to complete the registration.');
            Countdown();
        },
        error: function(status, error) {
            $('.verify-email').removeClass('text-success alert-success border-success').addClass('text-danger alert-danger border-danger');
            $('.verify-email').html('Registration Failed. Please try again later.');
        }
    });
}