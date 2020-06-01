import '../css/collection.css';

// Only loaded on individual collection pages
if (typeof(ol) !== "undefined") {
    var map = new ol.Map({
        target: 'collectionMap',
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
}


$('.collection-search').search({
    apiSettings: {
        onResponse: function(collectionsResponse) {
            let response = {
                results: []
            };
            if(!collectionsResponse) {
                return false;
            }
            $.each(collectionsResponse.collections, function(index, item){
                response.results.push({
                    title: item.name,
                    description: item.description,
                    url: '/collection/'+item.slug
                });
            });
            return response;
        },
        url: '/collection/api/collections?q={query}'
    },
    minCharacters : 3
});

$('.collection-contents-search').search({
    apiSettings: {
        onResponse: function(collectionsResponse) {
            let response = {
                results: []
            };
            if(!collectionsResponse) {
                return false;
            }
            $.each(collectionsResponse.activities, function(index, item){
                response.results.push({
                    id: item.id,
                    title: item.name,
                    description: item.description
                });
            });
            return response;
        },
        urlData:{
            collection: $('.collectionContent').data('collection')
        },
        url: '/collection/api/select-activity?q={query}&collection={collection}'
    },
    onSelect: function(result, response){
        $('.activity-id').text(result.id);
        $('.addCollection').css({'display':'block'});
    },
    minCharacters : 3
});

$('.addCollection').on('click', function(e){
    e.preventDefault();
    let activityID = $('.activity-id').text();
    let collectionID = $('.collectionContent').data('collection');
    $.ajax({
        method: 'POST',
        url: '/collection/addactivity',
        data: { collection: collectionID, activity: activityID }
    })
        .done(function (data){
            $('.collection-contents').append("<div class='item'><div class='content'>"+data.name+"</div></div>");
        })
})