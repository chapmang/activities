/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/semantic.min.css';
import '../js/select2-3.5.4/select2.css'
import '../css/app.css';
import '../js/semantic.min.js';
import '../js/select2-3.5.4/select2.min.js';
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

// document.getElementById("addUser").onclick = function(){
//     console.log('I was Clicked');
//     $("ui.test.modal").modal('show');
// }
$('#masterHead .ui.dropdown')
    .dropdown({
        action: 'select'
    })
;
$('.mapMenu .item').tab();
$('.adminMenu .item').tab();

$("document").ready(function(){
    setTimeout(function(){
        $("div.message").fadeOut('slow');
    }, 5000 ); // 5 secs

});
$('select.dropdown')
    .dropdown()
;