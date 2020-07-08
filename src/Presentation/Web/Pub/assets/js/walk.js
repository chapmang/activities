import '../css/walk.css';

// Dropdown Control
(function(){
    $('#export').click(function(){
        $(".ui.modal").modal({
            detachable: false,
            onApprove: function(){
                return false;
            }
        }).modal('show');
    });
    // $('.ui.dropdown')
    //     .dropdown({ showOnFocus:false, clearable:true })
    // ;
    $('input[name="text_format"],input[name="route_format"]').on('change', function(){
        let text_format = $('input[name="text_format"]').val();
        let route_format = $('input[name="route_format"]').val();
        if (text_format || route_format) {
            $('.actions>.positive').removeClass('disabled');
        } else {
            $('.actions>.positive').addClass('disabled');
        }
    })
})();

// Modal Box Control
(function(){
    $('#exportFormSubmit').click(function(e){
        e.preventDefault();
        $('.export-modal').dimmer('show');
        let text_format = $('input[name="text_format"]').val()
        let route_format = $('input[name="route_format"]').val();
        let url = $('#exportForm').data('ajax');

        fetch (url, {
            method: 'POST',
            body: JSON.stringify({
                text_format: text_format,
                route_format: route_format
            })
        }).then((response) =>{
            return response.json()
        }).then((data) =>{
            window.open(data.url);
            $('.export-modal').dimmer('hide');
            $(".ui.modal").modal('hide');
        });
    })
})();


// Mapping Control
let map, draw;

(function () {
    if(map) {
        map.remove();
        map = null;
    }

    // Setting up the initial API key input
    let key = '1DqRCvF1tLgcjEKo8undpO7lHCkzAAcl';
    let activity = document.getElementById("activityContent").dataset.activity;

    // This sets up the actual VTS layer
    // Center coordinates are defined in EPSG:3857 lon/lat and we are asking for srs=3857 in the "transformRequest"
    let serviceUrl = "https://api.os.uk/maps/vector/v1/vts/";
    map = new mapboxgl.Map({
        container: 'map',
        style: serviceUrl + '/resources/styles?key='+ key,
        center: [-1.058944, 51.281472],
        zoom: 9,
        maxZoom: 15,
        transformRequest: url => {
            console.log(url);
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

    // Create a Draw control
    draw = new MapboxDraw({
        styles: [
            // ACTIVE (being drawn)
            // line stroke
            {
                "id": "gl-draw-line",
                "type": "line",
                "filter": ["all", ["==", "$type", "LineString"]],
                "layout": {
                    "line-cap": "round",
                    "line-join": "round"
                },
                "paint": {
                    "line-color": "#D20C0C",
                    "line-dasharray": [0.5, 2],
                    "line-width": 4
                }
            },
            // vertex point halos
            {
                "id": "gl-draw-polygon-and-line-vertex-halo-active",
                "type": "circle",
                "filter": ["all", ["==", "meta", "vertex"], ["==", "$type", "Point"]],
                "paint": {
                    "circle-radius": 7,
                    "circle-color": "#FFF"
                }
            },
            // vertex points
            {
                "id": "gl-draw-polygon-and-line-vertex-active",
                "type": "circle",
                "filter": ["all", ["==", "meta", "vertex"], ["==", "$type", "Point"]],
                "paint": {
                    "circle-radius": 5,
                    "circle-color": "#D20C0C",
                }
            },
            // vertex point halos
            {
                "id": "gl-draw-polygon-and-line-midpoint-halo-active",
                "type": "circle",
                "filter": ["all", ["==", "meta", "midpoint"], ["==", "$type", "Point"]],
                "paint": {
                    "circle-radius": 5,
                    "circle-color": "#FFF"
                }
            },
            // vertex points
            {
                "id": "gl-draw-polygon-and-line-midpoint-active",
                "type": "circle",
                "filter": ["all", ["==", "meta", "midpoint"], ["==", "$type", "Point"]],
                "paint": {
                    "circle-radius": 3,
                    "circle-color": "#D20C0C",
                }
            },

            // INACTIVE (static, already drawn)
            // line stroke
            {
                "id": "gl-draw-line-static",
                "type": "line",
                "filter": ["all", ["==", "$type", "LineString"], ["==", "active", "false"]],
                "layout": {
                    "line-cap": "round",
                    "line-join": "round"
                },
                "paint": {
                    "line-color": "#000",
                    "line-width": 4
                }
            }
        ],
        displayControlsDefault: false,
        controls: {
            line_string: true,
            trash: true
        }
    });

    class saveGeographyControl {

        onAdd(map) {
            this._map = map;
            let _this = this;
            let activity = document.getElementById("activityContent").dataset.activity;

            this._btn = document.createElement('button');
            this._btn.className = 'mapboxgl-ctrl-icon save-geometry';
            this._btn.title = 'Save route';
            this._btn.type = 'button';
            this._btn['aria-label'] = 'Save';
            this._btn.onclick = function() {
                let data = draw.getAll()

                if (data.features.length === 1) {
                    let route = JSON.stringify(data.features[0].geometry);
                    document.getElementById('walk_form_json_route').value = route;
                    // $.ajax({
                    //     url: '/walk/route/update/'+activity,
                    //     type: 'POST',
                    //     data: {route: route}
                    // })
                } else {
                    console.log(data);
                    // @TODO formalise error
                    alert('there is something wrong with the route');
                }
            };

            this._container = document.createElement('div');
            this._container.className = 'mapboxgl-ctrl mapboxgl-ctrl-group';
            this._container.appendChild(this._btn);

            return this._container;
        }

        onRemove() {
            this._container.parentNode.removeChild(this._container);
            this._map = undefined;
        }
    }

    let editEle = document.getElementById("editMapping");
    if(editEle){
        map.addControl(draw, 'top-left');
        // const myCustomControl = new saveGeographyControl();
        // map.addControl(myCustomControl, 'top-left');
        let routeEditData = JSON.parse(document.getElementById("walk_form_json_route").value);
        draw.add(routeEditData);
    } else {
        let routeViewData = JSON.parse(document.getElementById("walk_json_route").dataset.json);
        map.on('load', function() {
            map.addSource("route", {
                'type': 'geojson',
                'data': routeViewData
            });
            map.addLayer({
                "id": "route",
                "type": "line",
                "source": "route",
                "layout": {
                    "line-cap": "round",
                    "line-join": "round"
                },
                "paint": {
                    "line-color": "#000",
                    "line-width": 4
                }
            });
        });
    }

    map.on('error', error => {
        $('.apierror').show();
    });
})();

$('#saveAll').on('click', function(e){
    e.preventDefault();
    let data = draw.getAll()
    console.log(data);
    if (data.features.length === 1) {
        let route = JSON.stringify(data.features[0].geometry);
        document.getElementById('walk_form_json_route').value = route;
        document.getElementById('walk_form_save_type').value = 'save';
        document.getElementById("walkForm").submit();
    } else {
        console.log(data);
        // @TODO formalise error
        alert('there is something wrong with the route');
    }
})
$('#saveClose').on('click', function(e){
    e.preventDefault();
    let data = draw.getAll()
    console.log(data);
    if (data.features.length === 1) {
        let route = JSON.stringify(data.features[0].geometry);
        document.getElementById('walk_form_json_route').value = route;
        document.getElementById('walk_form_save_type').value = 'save-close';
        document.getElementById("walkForm").submit();
    } else {
        console.log(data);
        // @TODO formalise error
        alert('there is something wrong with the route');
    }
})


