$(document).ready(function () {
    $('#example').DataTable();
    $('div.alert').delay(3000).fadeOut(500);



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
