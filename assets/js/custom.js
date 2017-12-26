$(function () {
    $('#example1').DataTable();
    $('#example3').DataTable();
    $('#example2').DataTable({
        'paging': true,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        order: [[0, "desc"]]
    });
    $("#invoicetable").DataTable({order: [[0, "desc"]]});
    $('#iddata').click(function (event) {
        console.log("hello");
    });

})
