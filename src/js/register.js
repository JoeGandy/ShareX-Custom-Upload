$(document).ready(function() {
    $('#verify-password, #password').on('input', (e) => {
        if ($('#verify-password').val() !== $('#password').val()) {
            $('#verify-password')[0].setCustomValidity('Your passwords do not match.');
        } else {
            $('#verify-password')[0].setCustomValidity('');
        }
    });

    $('#register-form').on('submit', (e) => {
        if ($('#verify-password').val() !== $('#password').val()) {
            $('#verify-password')[0].setCustomValidity('Your passwords do not match.');
        } else {
            $('#verify-password')[0].setCustomValidity('');
        }

        if (document.querySelector('#register-form').checkValidity() === false) {
            e.preventDefault();
            e.stopPropagation();
            $('#register-form').addClass('was-validated');
        } else {
            $('#register-form').removeClass('was-validated');
        }
    });
});
