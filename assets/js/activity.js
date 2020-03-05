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