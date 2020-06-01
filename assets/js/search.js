import '../css/search.css';
import '../js/autocomplete.min.js'
$('.activityNav .icon')
    .popup()
;


$(document).ready(function() {
    $('js-autocomplete').each(function() {
        let autocompleteUrl = $(this).data('ajax');
        $(this).autocomplete({hint: false}, [
            {
                source: function(query, cb) {
                    $.ajax({
                        url: autocompleteUrl
                    }).then(function(data) {
                        cb(data)
                    });
                },
                displayKey: ''
            }
        ])
    })
   });