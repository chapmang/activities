import '../css/walk.css';

var map = new ol.Map({
    target: 'routeMap',
    layers: [
        new ol.layer.Tile({
            source: new ol.source.OSM()
        })
    ],
    view: new ol.View({
        center: ol.proj.fromLonLat([-1.058944, 51.281472]),
        zoom: 15
    })
});

$(document).ready(function () {
    (function () {
        var $tagInput = $('input[name$="[tagsText]"]');
        function tags($input) {
            $input.attr('type', 'hidden').select2({
                tags: true,
                tokenSeparators: [","],
                createSearchChoice: function(term, data) {
                    if ($(data).filter(function () {
                        return this.text.localeCompare(term) === 0;
                    }).length === 0) {
                        return {
                            id: term,
                            text: term
                        };
                    }
                },
                multiple: true,
                ajax: {
                    url: $input.data('ajax'),
                    dataType: "json",
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data, page) {
                        return {
                            results: data
                        };
                    }
                },
                initSelection: function (element, callback) {
                    var data = [];
                    function splitVal(string, separator) {
                        var val, i, l;
                        if (string === null || string.length < 1) {
                            return [];
                        }
                        val = string.split(separator);
                        for (i = 0, l = val.length; i < l; i = i + 1) {
                            val[i] = $.trim(val[i]);
                        }
                        return val;
                    }
                    $(splitVal(element.val(), ",")).each(function () {
                        data.push({
                            id: this,
                            text: this
                        });
                    });
                    callback(data);
                }
            });
        }
        if ($tagInput.length > 0) {
            tags($tagInput);
        }
    }());
});

var $collectionHolder;

// setup an "add a tag" link
var $addDirectionButton = $('<a href="#" class="add_direction_link">\n' +
    '<i class="plus circle icon"></i>\n' +
    'Add Another Direction\n' +
    '</a>');
var $newLinkLi = $('<div class="field"></div>').append($addDirectionButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of directions
    $collectionHolder = $('div.directions');

    // add the "add a direction" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('textarea').length);

    $addDirectionButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addDirectionForm($collectionHolder, $newLinkLi);
    });
});

function addDirectionForm($collectionHolder, $newLinkLi) {

    // Get the data-prototype explained earlier
    let prototype = $collectionHolder.data('prototype');

    // get the new index
    let index;
    if ($collectionHolder.data('index') == 0) {
        index = 1;
    } else {
        index = $collectionHolder.data('index');
    }

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);
    console.log(newForm);
    //newForm = newForm(".field").prepend("<label for=\"walk_form_directions_3_direction\">Direction: 5</label>")
//<label for="walk_form_directions_3_direction">Direction: 5</label>

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li

    //var $newFormLi = $('<div class="field"></div>').append(newForm);
    $newLinkLi.before(newForm);
    $("#walk_form_directions_"+index+"_direction").before("<label>Direction: "+index+"</label>");
    $("#walk_form_directions_"+index+"_position").val(index);
}