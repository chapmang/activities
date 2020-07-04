import '../css/walk.css';

(function(){
    $('#export').click(function(){
        $(".ui.modal").modal({
            detachable: false,
            onApprove: function(){
                return false;
            }
        }).modal('show');
    });
    $('.ui.dropdown')
        .dropdown({ showOnFocus:false, clearable:true })
    ;
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

(function(){
    $('#exportFormSubmit').click(function(e){
        e.preventDefault();
        $('.export-modal').dimmer('show');
        let text_format = $('input[name="text_format"]').val()
        let route_format = $('input[name="route_format"]').val();
        let url = $('#exportForm').data('ajax');
        console.log(url);

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
    var draw = new MapboxDraw({
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
    let routeUrl = "/walk/route/view/"+activity;
    fetch (routeUrl, {
        method: 'POST',
    }).then((response) =>{
        return response.json()
    }).then((data) =>{
        draw.add(data)
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
                    console.log(route);
                    $.ajax({
                        url: '/walk/route/update/'+activity,
                        type: 'POST',
                        data: {route: route}
                    })
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
        const myCustomControl = new saveGeographyControl();
        map.addControl(myCustomControl, 'top-left');
    }
    map.on('error', error => {
        $('.apierror').show();
    });
})();


// Load line edit tools only on the edit page of a given walk
// $(document).ready(function () {
//     let editEle = document.getElementById("editMapping");
//     if(editEle){
//         draw = new MapboxDraw({
//             styles: [
//                 // ACTIVE (being drawn)
//                 // line stroke
//                 {
//                     "id": "gl-draw-line",
//                     "type": "line",
//                     "filter": ["all", ["==", "$type", "LineString"]],
//                     "layout": {
//                         "line-cap": "round",
//                         "line-join": "round"
//                     },
//                     "paint": {
//                         "line-color": "#D20C0C",
//                         "line-dasharray": [0.5, 2],
//                         "line-width": 4
//                     }
//                 },
//                 // vertex point halos
//                 {
//                     "id": "gl-draw-polygon-and-line-vertex-halo-active",
//                     "type": "circle",
//                     "filter": ["all", ["==", "meta", "vertex"], ["==", "$type", "Point"]],
//                     "paint": {
//                         "circle-radius": 7,
//                         "circle-color": "#FFF"
//                     }
//                 },
//                 // vertex points
//                 {
//                     "id": "gl-draw-polygon-and-line-vertex-active",
//                     "type": "circle",
//                     "filter": ["all", ["==", "meta", "vertex"], ["==", "$type", "Point"]],
//                     "paint": {
//                         "circle-radius": 5,
//                         "circle-color": "#D20C0C",
//                     }
//                 },
//                 // vertex point halos
//                 {
//                     "id": "gl-draw-polygon-and-line-midpoint-halo-active",
//                     "type": "circle",
//                     "filter": ["all", ["==", "meta", "midpoint"], ["==", "$type", "Point"]],
//                     "paint": {
//                         "circle-radius": 5,
//                         "circle-color": "#FFF"
//                     }
//                 },
//                 // vertex points
//                 {
//                     "id": "gl-draw-polygon-and-line-midpoint-active",
//                     "type": "circle",
//                     "filter": ["all", ["==", "meta", "midpoint"], ["==", "$type", "Point"]],
//                     "paint": {
//                         "circle-radius": 3,
//                         "circle-color": "#D20C0C",
//                     }
//                 },
//
//                 // INACTIVE (static, already drawn)
//                 // line stroke
//                 {
//                     "id": "gl-draw-line-static",
//                     "type": "line",
//                     "filter": ["all", ["==", "$type", "LineString"], ["==", "active", "false"]],
//                     "layout": {
//                         "line-cap": "round",
//                         "line-join": "round"
//                     },
//                     "paint": {
//                         "line-color": "#000",
//                         "line-width": 4
//                     }
//                 }
//             ],
//             displayControlsDefault: false,
//             controls: {
//                 line_string: true,
//                 trash: true
//             }
//         })
//         draw.add
//         map.addControl(draw, 'top-left');
//         const myCustomControl = new saveGeographyControl();
//         map.addControl(myCustomControl, 'top-left');
//     }
// });



