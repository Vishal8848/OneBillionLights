// Regex Formats
const Password = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]{8,30}$/);

$(document).ready(function() {
    adjustForm();
    $('#form-page').animate({
        marginTop: "+=25"
    }, 500, function() {
        $(this).animate({
            marginTop: "-=25"
        }, 750);
    });
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

function adjustForm() {
    let LastField = $(".last");
    if($(window).width() < 768) {
        LastField.addClass("mt-3");
    }   else {
        LastField.removeClass("mt-3");
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

function ValidatePasswd() {
    let formError = 0;
    if(Password.test($('#chpasswd').val()))  {
        rightField('#chpasswd'); ++formError;
        if($('#chpasswd').val() === $('#ccpasswd').val())    {
            rightField('#ccpasswd'); ++formError;
        }   else {
            wrongField('#ccpasswd', 'Password Unmatched'); formError = 0;
        }
    }   else {
        wrongField('#chpasswd', 'Weak Password'); formError = 0;
    }
    if(formError == 2)  return true;
    return false;
}