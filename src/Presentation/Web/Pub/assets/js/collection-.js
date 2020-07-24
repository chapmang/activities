import '../css/collection.css';
(function(){
    $('#export').click(function(){
        $(".ui.mini.modal").modal({
            detachable: false
        }).modal('show');
    });
    $('.ui.dropdown')
        .dropdown({ showOnFocus:false, clearable:true })
    ;
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


$('.collection-contents-search')
    .search({
    apiSettings: {
        onResponse: function(collectionsResponse) {
            let response = {
                results: []
            };
            if(!collectionsResponse) {
                return false;
            }
            $.each(collectionsResponse.activities, function(index, item) {
                response.results.push({
                    id: item.id,
                    title: item.name,
                    description: item.shortDescription
                });
            });
            return response;
        },
        urlData:{
            collection: $('#collectionContent').data('collection')
        },
        url: '/collection/api/select-activity?q={query}&collection={collection}',
        cache: false
        },onSelect: function(result, response){
            $('.activity-id').text(result.id);
            $('.addCollection').css({'display':'block'});
        },
        minCharacters : 3
});

$('.addCollection').on('click', function(e){
    e.preventDefault();
    let activityID = $('.activity-id').text();
    let collectionID = $('#collectionContent').data('collection');
    $.ajax({
        method: 'POST',
        url: '/collection/addactivity',
        data: { collection: collectionID, activity: activityID }
    }).done(function (data){
        console.log(data);
        $('.collection-contents').append("<div class='item'><div class='content'>"+data.name+"</div></div>");

        new ManageCollectionActivities();

    }).fail(function(jqXHR, textStatus){

    });
});



function ManageCollectionActivities() {

    this._customHtmlMarkers = [];
    this._removeMarkers();

    let collection_id = $('#collectionContent').data('collection');
    let url ='/map/activities/'+collection_id;
    fetch(url, {
        method: 'GET'
    }).then((response) => {
        return response.json()
    }).then((data) => {
        this._updateMarkers(data.features);
    });
}

ManageCollectionActivities.prototype._updateMarkers = function(features) {

    features.forEach((marker) => {
        this._customHtmlMarkers.push(this._addAndGetCustomHtmlMarker(marker));
    })
}

ManageCollectionActivities.prototype._addAndGetCustomHtmlMarker = function(marker) {

    // create a HTML element for each feature
    let el = document.createElement('div');
    el.innerHTML = "<div class='" + marker.properties.activityType + "'></div>" +
        "<div class='marker-shadow'></div>";

    let markerHeight = 50, markerRadius = 20, linearOffset = 25;
    let popupOffsets = {
        'top': [0, 0],
        'top-left': [0, 0],
        'top-right': [0, 0],
        'bottom': [0, -markerHeight],
        'bottom-left': [linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
        'bottom-right': [-linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
        'left': [markerRadius, (markerHeight - markerRadius) * -1],
        'right': [-markerRadius, (markerHeight - markerRadius) * -1]
    };

    return new mapboxgl.Marker(el)
        .setLngLat(marker.geometry.coordinates)
        .setPopup(new mapboxgl.Popup({ offset: popupOffsets, closeButton: false, className: 'activityPopup' }) // add popups
            .setHTML('<h3><a href="/'+marker.properties.activityType+'/'+marker.properties.slug+'">' + marker.properties.name + '</a></h3><p>'+marker.properties.shortDescription+'</p>'))
        .addTo(map);
}

ManageCollectionActivities.prototype._removeMarkers = function() {

    for (var i = 0; i < this._customHtmlMarkers.length; ++i) {
        this._customHtmlMarkers[i].remove();
    }
    this._customHtmlMarkers = [];
}

// Mapping Control
let map;
(function () {
    if(map) {
        map.remove();
        map = null;
    }

    // Setting up the initial API key input
    let key = '1DqRCvF1tLgcjEKo8undpO7lHCkzAAcl';

    // This sets up the actual VTS layer
    // Center coordinates are defined in EPSG:3857 lon/lat and we are asking for srs=3857 in the "transformRequest"
    let serviceUrl = "https://api.os.uk/maps/vector/v1/vts/";
    map = new mapboxgl.Map({
        container: 'map',
        style: serviceUrl + '/resources/styles?key='+ key,
        center: [-1.058944, 51.281472],
        zoom: 10,
        maxZoom: 15,
        transformRequest: url => {
            url += '&srs=3857';
            return {
                url: url
            }
        }
    });

    map.addControl(new mapboxgl.AttributionControl({
        customAttribution: '&copy; <a href="http://www.ordnancesurvey.co.uk/">Ordnance Survey</a>'
    }));

    // Add zoom and rotation controls to the map.
    map.addControl(new mapboxgl.NavigationControl({
        showCompass: false
    }));

    new ManageCollectionActivities();

    map.on('error', error => {
        $('.apierror').show();
    });
})();