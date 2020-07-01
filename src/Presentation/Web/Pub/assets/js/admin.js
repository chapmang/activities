import '../css/admin.css';

$(document).ready(function(){
    $('#addUser').click(function(){
        $(".ui.test.modal").modal({
            blurring: true
        }).modal('show');
    });
    $("#adminUserAuth").click(function(){
        $('.ui.checkbox').checkbox();
    })
});