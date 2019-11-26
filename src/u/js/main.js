$(document).ready(function () {
    $('#example').DataTable();
    $('div.alert').delay(3000).fadeOut(500);


    lightbox.option({
        'resizeDuration': 300,
        'wrapAround': true
    })

});
$('[data-toggle="tooltip"]').tooltip();


function makeid(length = 33) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function CopyKey() {
    /* Get the text field */
    var copyText = document.getElementById("secure_keyModal");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/

    /* Copy the text inside the text field */
    document.execCommand("copy");
}

function CopyUrl(value) {
    /* Get the text field */
    var copyText = document.getElementById("copyurl");

    const el = document.createElement('textarea');
    el.value = copyText.dataset.copyurl;
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
}

function goBack() {
    window.history.back();
}
$("#show_hide").on('click', function (event) {

    if ($('#secure_key').attr("type") == "text") {
        $('#secure_key').attr('type', 'password');
        $('#show_hide_password i').addClass("fa-eye-slash");
        $('#show_hide_password i').removeClass("fa-eye");
    } else if ($('#secure_key').attr("type") == "password") {
        $('#secure_key').attr('type', 'text');
        $('#show_hide_password i').removeClass("fa-eye-slash");
        $('#show_hide_password i').addClass("fa-eye");
    }
});

$("#newkey").on('click', function (event) {
    event.preventDefault();
    const key = makeid(33);
    $('#secure_key').attr('value', key);
    $('#secure_keyModal').attr('value', key);
    $('#newkeygenerated').modal('show');

});

$("#copykey").on('click', function (event) {
    event.preventDefault();
    CopyKey();
    $('#secure_key').attr('type', 'text');
    $('#show_hide_password i').removeClass("fa-eye-slash");
    $('#show_hide_password i').addClass("fa-eye");
    $('#newkeygenerated').modal('hide');

});

$("#copyurl").on('click', function (event) {
    event.preventDefault();
    var value = $("#copyurl").data('copyurl');
    $.notify({
        // options
        message: 'Link copied <strong>' + value + '</strong>'
    }, {
        // settings
        type: 'success'
    });
    CopyUrl();
});