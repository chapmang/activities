$('#activity_lock .icon')
    .popup()
;
$('#activityMenu .ui.dropdown')
    .dropdown({
        action: 'select'
    })
;
$('#exportModal .ui.dropdown')
    .dropdown({ showOnFocus:false, clearable:true })
;

(function(){
    $('#export').click(function(){
        $(".ui.mini.modal").modal({
            detachable: false
        }).modal('show');
    });

    $('input[name="format"]').on('change', function(){
        let selectedFormat = $(this).val();
        let geoFormats = ['xml','json']
        if ($.inArray(selectedFormat, geoFormats) !== -1) {
            $('.ui.checkbox').checkbox('set enabled');
        } else {
            $('.ui.checkbox').checkbox('set disabled').checkbox('uncheck');
        }
        if (selectedFormat){
            $('.actions>.positive').removeClass('disabled');
        } else {
            $('.actions>.positive').addClass('disabled');
        }
    })
})();

$("#activity_lock").on('click', function(e){
   e.preventDefault();
   let authorised = $(this).data('authorised');
   if (authorised === true) {
       let status = $(this).data("status");
       let url = $(this).data('ajax');
       let activity = $('.activityContent').data('activity');

       if ( status === 0) {
           lockAjaxCaller(url, status, activity);
           $(this).data("status", 1);
       } else if (status === 1) {
           lockAjaxCaller(url, status, activity)
           $(this).data("status", 0);
       } else {
           // @Todo not authorised to change the status of this activity
       }
   }
});

function lockAjaxCaller (url, status, activity) {
    $.ajax({
        url: url,
        data: { "status": status, "activity": activity}
    })
    .done(function(data) {
        if (data.status === 0) {
            $('#activity_lock').html('<i class="unlock icon"></i>');
        } else {
            let tooltip = "This activity was locked by "+ data.user;
            $('#activity_lock').html('<span data-tooltip="'+tooltip+'" data-position="bottom center">'+
                '<i class="lock icon"></i></span>');
        }
    })
    .fail(function(){
        // @Todo work out graceful way to inform that lock/unlock action failed
    });
}
