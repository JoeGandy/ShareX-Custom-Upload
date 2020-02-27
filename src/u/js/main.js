$(document).ready(function() {
    $.fn.dataTable.moment( 'D MMM YYYY HH:mm' );
    $('#example').DataTable();
    $('div.alert').delay(3000).fadeOut(500);
} );
$('[data-toggle="tooltip"]').tooltip();
