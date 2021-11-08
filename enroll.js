// Regex Formats
const Email = new RegExp(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
const Password = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]{8,30}$/);

$(document).ready(function(){
    adjustForm();

    $('#form-page').animate({
        marginTop: "+=25"
    }, 500, function() {
        $(this).animate({
            marginTop: "-=25"
        }, 750);
    })

    $('.toggle-eye > i').on('click', function() {
        $(this).toggleClass('fa-eye').toggleClass('fa-eye-slash');
        $(this).hasClass('fa-eye') ? $(this).prev().prev().attr('type', 'password') : $(this).prev().prev().attr('type', 'text');
    });

    $('#passwd-sample').hover(function() {
        $(this).html('Atleast 8 characters with lowercase, uppercase, numbers and symbols');
    },  function() {
        $(this).html('See Example');
    }); 
});

$(window).resize(function() {
    adjustForm();
});

function formType(type) {
    if(type === 2) {
        $('#lemail').removeAttr('required');
        $('#lpasswd').removeAttr('required');
    }   else if(type === 1) {
        $('#fname').removeAttr('required');
        $('#remail').removeAttr('required');
        $('#passwd').removeAttr('required');
        $('#cpasswd').removeAttr('required');
    }
}

function adjustForm() {
    let LastField = $(".last");
    if($(window).width() < 768) {
        LastField.addClass("mt-3");
    }   else {
        LastField.removeClass("mt-3");
    }
}

function fieldTracer() {
    let X = $('#fname').val(), Y = $('#lname').val(), Z = $('.register-msg').html();
    if (X.length > 0 || Y.length > 0) {
        $('.register-msg').html("Welcome, " + X + " " + Y);
    } else {
        $('.register-msg').html("Welcome New User");
    }
}

function rightField(ID) {
    $(ID).attr('class', 'form-control border border-success');
    $(ID + '-text').html('');
}

function wrongField(ID, Text) {
    $(ID).attr('class', 'form-control border border-danger');
    $(ID + '-text').html(Text);
}

function Validation() {
    let formError = 0;
    if($('#fname').val().length > 0) {
        rightField('#fname');   ++formError;
    }   else {
        wrongField('#fname', 'Invalid Name');   formError = 0;
    }
    ($('#lname').val().length > 0) ? $('#lname').addClass("border border-success") : $('#lname').removeClass("border border-success");
    if(Email.test($('#remail').val())) {
        rightField('#remail'); ++formError;
    }   else {
        wrongField('#remail', 'Invalid Email'); formError = 0;
    }
    if(Password.test($('#passwd').val())) {
        rightField('#passwd'); ++formError;
        if($('#passwd').val() === $('#cpasswd').val()) {
            rightField('#cpasswd'); ++formError;
        }   else {
            wrongField('#cpasswd', 'Password Unmatched'); formError = 0;
        }
    }   else {
        wrongField('#passwd', 'Weak Password'); formError = 0;
    }
    if(formError == 4) return true;
    return false;
}