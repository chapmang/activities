import '../css/map.css';
let coordinates = require('./jsCoordinateConverter/coordinates');
let transMerConversion = require('./jsCoordinateConverter/TransversMercatorConversion');
let datumConversion = require('./jsCoordinateConverter/DatumConversion');
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

    let url ='/map/activities';
    let geojson;
    fetch(url, {
        method: 'GET'
    }).then((response) => {
        return response.json()
    }).then((data) =>{
        geojson = data
        geojson.features.forEach(function(marker) {

            // create a HTML element for each feature
            var el = document.createElement('div');
            el.className = marker.properties.activityType;

            // make a marker for each feature and add to the map
            new mapboxgl.Marker(el)
                .setLngLat(marker.geometry.coordinates)
                .setPopup(new mapboxgl.Popup({ offset: 25 }) // add popups
                    .setHTML('<h3><a href="/'+marker.properties.activityType+'/'+marker.properties.slug+'">' + marker.properties.name + '</a></h3>'))
                .addTo(map);
        });
    });

    var iconMarkerEl = document.createElement("div");
    iconMarkerEl.innerHTML = "<div class='marker-arrow'></div>" +
        "<div class='marker-pulse'></div>";
    map.on('load', function (){
        map.addControl(new MapSearch({
            params: {'key': '1DqRCvF1tLgcjEKo8undpO7lHCkzAAcl'},
            filters: ['Airport','City','Hamlet','island','Other_Settlement','Postcode','Spot_Height','Suburban_Area','Town','Valley','Village','Woodland_Or_Forest'],
            flyTo: 'hybrid',
            url: 'https://api.os.uk/search/names/v1',
            useFocusPoint: true,
            marker: {
                icon: iconMarkerEl,
                multiple: false
            }
        }));
    })


    map.on('error', error => {
        $('.apierror').show();
    });
})();


// ---------------------------------------------
// ---------- Plugin/Mapbox GL JS API ----------
// ---------- pelias-mapbox-gl-js --------------
// ---------------------------------------------

function MapSearch(opts) {
    opts = opts || {};

    this.opts = {};
    this.opts.placeholder = opts.placeholder || 'Search';
    this.opts.url = opts.url;
    this.opts.flyTo = opts.flyTo === undefined ? true : opts.flyTo;
    this.opts.sources = opts.sources;
    this.opts.useFocusPoint = opts.useFocusPoint;
    this.opts.removeDuplicates = opts.removeDuplicates === undefined ? true : opts.removeDuplicates;
    this.opts.onSubmitOnly = opts.onSubmitOnly;
    this.customAttribution = opts.customAttribution;

    if (opts.marker) {
        this.opts.marker = {};
        this.opts.marker.icon = opts.marker.icon || 'marker-15';
        this.opts.marker.anchor = opts.marker.anchor || 'bottom';
        this.opts.marker.multiple = opts.marker.multiple !== undefined ? opts.marker.multiple : true;
        this._customHtmlMarkers = [];
    }

    if(opts.filters) {
        this.filters = '';
        let _this = this;
        opts.filters.forEach(function(item, index){
            _this.filters += 'LOCAL_TYPE:'+item+' ';
        });
        this.filters.trim();
    }

    if (opts.params) {
        this.params = '';
        for (var key in opts.params) {
            if (opts.params.hasOwnProperty(key)) {
                this.params += '&' + key + '=' + opts.params[key];
            }
        }
    }

    this.markerLayerId = 'pelias-mapbox-gl-js-marker';
    this.polygonLayerId = 'pelias-mapbox-gl-js-polygon';
    this._removePolygon = this._removeSources.bind(this, this.polygonLayerId);

    this._keys = {};
    this._keys.enter = {};
    this._keys.enter.key = 'Enter';
    this._keys.enter.keyCode = 13;
    this._keys.arrowUp = {};
    this._keys.arrowUp.key = 'ArrowUp';
    this._keys.arrowUp.keyCode = 38;
    this._keys.arrowDown = {};
    this._keys.arrowDown.key = 'ArrowDown';
    this._keys.arrowDown.keyCode = 40;
}

MapSearch.prototype.onAdd = function (map) {
    this._map = map;

    // let a = document.getElementsByClassName("mapboxgl-ctrl-top-left");
    // a.style.height = "100%";
    var wrapperEl = this._createElement({class: 'pelias-ctrl mapboxgl-ctrl'});
    var inputWrapperEl = this._createElement({class: 'pelias-ctrl-input-wrapper'});
    var inputActionsWrapperEl = this._createElement({class: 'pelias-ctrl-input-actions-wrapper pelias-ctrl-shadow'});
    this._resultsEl = this._createElement({class: 'pelias-ctrl-results pelias-ctrl-hide pelias-ctrl-shadow'});

    this._inputEl = this._buildInputHTMLElement();
    this._iconCrossEl = this._buildIconCrossHTMLElement();
    this._iconSearchEl = this._buildIconSearchHTMLElement();
    this._resultsListEl = this._buildResultsListHTMLElement();

    inputWrapperEl.appendChild(this._inputEl);
    inputWrapperEl.appendChild(this._iconCrossEl);
    inputActionsWrapperEl.appendChild(inputWrapperEl);
    inputActionsWrapperEl.appendChild(this._iconSearchEl);
    this._resultsEl.appendChild(this._resultsListEl);
    wrapperEl.appendChild(inputActionsWrapperEl);
    wrapperEl.appendChild(this._resultsEl);

    return wrapperEl;
};

MapSearch.prototype.getDefaultPosition = function () {
    return 'top-left'
};

// ----------------------------
// ---------- search ----------
// ----------------------------

MapSearch.prototype.search = function (opts, callback) {
    opts = opts || {};
    if (!opts.text || opts.text.length === 0) {
        return callback();
    }
    if (this.opts.sources instanceof Array) {
        this.opts.sources = this.opts.sources.join(',');
    }
    this._search = opts.text;
    var url = this.opts.url + '/find?query=' + opts.text
        + (this.params ? this.params : '')
        + (this.filters ? ('&fq='+ this.filters) : '')
        // + (this.opts.sources ? ('&sources=' + this.opts.sources) : '')
        // + (this.opts.useFocusPoint ? ('&focus.point.lat=' + this._map.getCenter().lat + '&focus.point.lon=' + this._map.getCenter().lng) : '');
    this._sendXmlHttpRequest(url, callback);
};

MapSearch.prototype._showResults = function (results) {
    var self = this;
    var features = this._removeDuplicates(results.results);

    this._results = results;
    this._resultsListEl.removeAll();
    this._addOrRemoveClassToElement(this._resultsEl, features.length === 0, "pelias-ctrl-hide");

    features.forEach(function (feature, index) {
        self._resultsListEl.appendChild(self._buildAndGetResult(feature, index));
        if (self.opts.marker && self.opts.marker.multiple) {
            self._updateMarkers(features);
        }
    })

    // self._resultsListEl.appendChild(self._createAttribution(results.geocoding, features.length))
};

MapSearch.prototype._removeDuplicates = function (features) {
    var self = this;
    var groupBy = {};
    if (!this.opts.removeDuplicates) {
        return features;
    }
    features.forEach(function (feature) {
        var label = feature.GAZETTEER_ENTRY.NAME1;
        if (!groupBy[label]) {
            groupBy[label] = []
        }
        groupBy[label].push(feature);
    });
    for (var label in groupBy) {
        if (groupBy.hasOwnProperty(label)) {
            groupBy[label].forEach(function (feature, index) {
                if (feature.remove || groupBy[label].length === 1) {
                    return;
                }
                for (var j = index + 1; j < groupBy[label].length; j++) {
                    let coordinates_1 = self._getLonLat(feature.GAZETTEER_ENTRY.GEOMETRY_X, feature.GAZETTEER_ENTRY.GEOMETRY_Y, 0)
                    let coordinates_2 = self._getLonLat(groupBy[label][j].GAZETTEER_ENTRY.GEOMETRY_X, groupBy[label][j].GAZETTEER_ENTRY.GEOMETRY_Y, 0)
                    if (!groupBy[label][j].remove && self._areNear(coordinates_1, coordinates_2, 0.2)) {
                        groupBy[label][j].remove = true;
                    }
                }
            });
        }
    }
    return features.filter(function (feature) {
        return !feature.remove;
    })
};

MapSearch.prototype._showError = function (error) {
    var errorEl = this._createElement();
    errorEl.innerHTML = error;
    this._resultsListEl.removeAll();
    this._resultsListEl.appendChild(errorEl);
};

MapSearch.prototype._selectFeature = function (feature) {
    this._selectedFeature = feature;
    this._inputEl.value = feature.GAZETTEER_ENTRY.NAME1;
    this._addOrRemoveClassToElement(this._iconSearchEl, false, "pelias-ctrl-disabled");
};

MapSearch.prototype._goToFeatureLocation = function (feature) {
    this._results = undefined;
    this._resultsListEl.removeAll();
    let coordinates = this._getLonLat(feature.GAZETTEER_ENTRY.GEOMETRY_X, feature.GAZETTEER_ENTRY.GEOMETRY_Y, 0)
    let cameraOpts = {
        center: coordinates,
        zoom: this._getBestZoom(feature)
    };
    if (this._useFlyTo(cameraOpts)) {
        this._map.flyTo(cameraOpts);
    } else {
        this._map.jumpTo(cameraOpts);
    }
    this._updateMarkers(feature);

};


// ----------------------------
// ---------- marker ----------
// ----------------------------

MapSearch.prototype._updateMarkers = function (features) {
    if (!this.opts.marker) {
        return;
    }
    this._removeMarkers();
    if (!Array.isArray(features)) {
        features = [features];
    }
    if (this.opts.marker.icon instanceof HTMLElement) {
        for (var i = 0; i < features.length; ++i) {
            let coordinates = this._getLonLat(features[i].GAZETTEER_ENTRY.GEOMETRY_X, features[i].GAZETTEER_ENTRY.GEOMETRY_X, 0)
            this._customHtmlMarkers.push(this._addAndGetCustomHtmlMarker(coordinates));
        }
    } else {
        this._map.addLayer({
            id: this.markerLayerId,
            type: "symbol",
            source: {
                type: "geojson",
                data: {
                    type: "FeatureCollection",
                    features: features
                }
            },
            layout: {
                "icon-allow-overlap": true,
                "icon-image": this.opts.marker.icon,
                "icon-anchor": this.opts.marker.anchor
            }
        })
    }
};

MapSearch.prototype._addAndGetCustomHtmlMarker = function (coordinates) {
    return new mapboxgl.Marker(this.opts.marker.icon.cloneNode(true))
        .setLngLat(coordinates)
        .addTo(this._map)
};

MapSearch.prototype._removeMarkers = function () {
    if (!this.opts.marker) {
        return;
    }
    if (this.opts.marker.icon instanceof HTMLElement) {
        for (var i = 0; i < this._customHtmlMarkers.length; ++i) {
            this._customHtmlMarkers[i].remove();
        }
        this._customHtmlMarkers = [];
    } else {
        this._removeSources(this.opts.marker, this.markerLayerId)
    }
};

// --------------------------
// ---------- HTML ----------
// --------------------------

MapSearch.prototype._createElement = function (opts) {
    var element = document.createElement(opts.type || 'div');
    opts.class !== undefined && (element.className = opts.class);
    opts.html !== undefined && (element.innerHTML = opts.html);
    return element;
};

MapSearch.prototype._buildIconCrossHTMLElement = function () {
    var self = this;
    var iconCrossEl = this._createElement({type: "span", class: "pelias-ctrl-icon-cross pelias-ctrl-hide"});
    iconCrossEl.addEventListener("click", function () {
        self._clearAll();
    });
    return iconCrossEl;
};

MapSearch.prototype._buildIconSearchHTMLElement = function () {
    var self = this;
    var iconSearchEl = this._createElement({type: "span", class: "pelias-ctrl-action-icon pelias-ctrl-action-icon-search pelias-ctrl-disabled"});
    iconSearchEl.addEventListener("click", function () {
        if (self._selectedFeature) {
            self._goToFeatureLocation(self._selectedFeature);
        }
    });
    return iconSearchEl;
};

MapSearch.prototype._buildInputHTMLElement = function () {
    var self = this;
    var inputEl = this._createElement({type: 'input'});
    inputEl.type = 'text';
    inputEl.placeholder = this.opts.placeholder;

    inputEl.addEventListener("keyup", function (e) {
        // Enter -> go to feature location.
        if (self._eventMatchKey(e, self._keys.enter) && self._selectedFeature) {
            inputEl.blur();
            self._goToFeatureLocation(self._selectedFeature);
            return;
        }

        // Arrow down -> focus on the first result.
        if (self._eventMatchKey(e, self._keys.arrowDown) && self._results && self._results.features[0]) {
            self._resultsListEl.firstChild.focus();
            return;
        }

        var value = this.value.trim();

        if (value.length === 0) {
            self._clearAll();
            return;
        }

        if (self._selectedFeature && value !== self._selectedFeature.GAZETTEER_ENTRY.NAME1) {
            self._addOrRemoveClassToElement(self._iconSearchEl, true, "pelias-ctrl-disabled");
            self._selectedFeature = undefined;
        }

        if (!self._eventMatchKey(e, self._keys.enter) && self.opts.onSubmitOnly) {
            return;
        }

        self._addOrRemoveClassToElement(self._iconCrossEl, false, "pelias-ctrl-hide");

        // Do the search request.
        if (this._timeoutId !== undefined) {
            clearTimeout(this._timeoutId);
        }
        this._timeoutId = setTimeout(function () {
            self.search({text: value}, function (err, result) {
                if (err) {
                    return self._showError(err);
                }
                if (result) {
                    return self._showResults(result)
                }
            });
        }, self._eventMatchKey(e, self._keys.enter) ? 0 : 350);
    });
    return inputEl;
};

MapSearch.prototype._buildResultsListHTMLElement = function () {
    var self = this;
    var resultsListEl = this._createElement({class: "pelias-ctrl-results-list"});
    resultsListEl.removeAll = function () {
        self._addOrRemoveClassToElement(self._resultsEl, true, "pelias-ctrl-hide");
        while (this.firstChild) {
            this.removeChild(this.firstChild);
        }
    };
    return resultsListEl;
};

MapSearch.prototype._buildAndGetResult = function (feature, index) {
    var self = this;

    var resultEl = this._createElement({class: "pelias-ctrl-result"});
    resultEl.feature = feature;
    resultEl.setAttribute("tabindex", "-1");

    // var iconClassName = this._layerToIcon(feature.properties.layer);
    // if (iconClassName) {
    //     var resultIconEl = this._createElement({type: "span", class: "pelias-ctrl-icon-result " + iconClassName});
    //     resultEl.appendChild(resultIconEl);
    // }

    var labelWrapperEl = this._createElement({type: "span", class: "pelias-ctrl-wrapper-label"});

    let name2 = (feature.GAZETTEER_ENTRY.NAME2 ? "("+feature.GAZETTEER_ENTRY.NAME2+"), " : '');
    let spoof = feature.GAZETTEER_ENTRY.NAME1+", "+name2+feature.GAZETTEER_ENTRY.COUNTY_UNITARY;
    // Allow to extract the first part (before the first comma) of each result.
    // find[1] : the first capturing group -> before the first comma.
    // find[3] : the third capturing group -> after the first comma.
    var find = spoof.match(/^([^,]+)(, (.+))?$/);

    // Add a span containing the name of the result with potentially bold span.
    var nameEl = this._boldingPartsOfStringAccordingToTheSearch(find[1], this._search);
    nameEl.className = "name";
    labelWrapperEl.appendChild(nameEl);

    if (find[3]) {
        // Add a span containing the dash separator.
        var separatorEl = this._createElement({type: "span", class: "pelias-ctrl-separator"});
        separatorEl.innerHTML = " - ";
        labelWrapperEl.appendChild(separatorEl);

        // Add a span containing the location of the result with potentially bold span.
        var locationEl = this._boldingPartsOfStringAccordingToTheSearch(find[3], this._search);
        locationEl.className = "pelias-ctrl-location";
        labelWrapperEl.appendChild(locationEl);
    }

    resultEl.appendChild(labelWrapperEl);

    resultEl.onclick = function () {
        self._goToFeatureLocation(feature);
    };
    resultEl.addEventListener("focus", function () {
        self._selectFeature(this.feature);
    });
    resultEl.addEventListener("keydown", function (e) {
        if (self._eventMatchKey(e, self._keys.enter)) {
            self._goToFeatureLocation(feature);
        }
        if (self._eventMatchKey(e, self._keys.arrowUp)) {
            if (self._resultsListEl.childNodes[index - 1]) {
                self._resultsListEl.childNodes[index - 1].focus();
            } else if (index - 1 === -1) {
                self._inputEl.focus();
            }
        }
        if (self._eventMatchKey(e, self._keys.arrowDown) && self._resultsListEl.childNodes[index + 1]) {
            self._resultsListEl.childNodes[index + 1].focus();
        }
    });

    return resultEl;
};

MapSearch.prototype._layerToIcon = function (layer) {
    // Layers with missing icon :
    // Who’s on First : borough, coarse, county, macrocounty, macroregion, neighbourhood, region
    // Geonames : coarse, county, macroregion, neighbourhood, region
    switch (layer) {
        case "address":
            return "pelias-ctrl-icon-result-marker";
        case "country":
            return "pelias-ctrl-icon-result-flag";
        case "localadmin":
        case "locality":
            return "pelias-ctrl-icon-result-city";
        case "street":
            return "pelias-ctrl-icon-result-road";
        case "venue":
            return "pelias-ctrl-icon-result-marker";
        default:
            return "pelias-ctrl-icon-result-marker";
    }
};

MapSearch.prototype._boldingPartsOfStringAccordingToTheSearch = function (label, search) {
    if (!search) return this._createElement({type: 'span', html: label});
    var labelSplit = label.toLowerCase().split(search.toLowerCase());
    var spanWrapperEl = this._createElement({type: "span"});
    var index = -1;

    for (var i = 0; i < labelSplit.length; ++i) {
        var pieceOfInitialLabel;

        // Add a span containing a light piece of the initial label.
        if (labelSplit[i].length > 0) {
            var spanNotBoldEl = this._createElement({type: "span"});
            pieceOfInitialLabel = "";
            for (var j = 0; j < labelSplit[i].length; ++j) {
                index++;
                pieceOfInitialLabel += label[index];
            }
            spanNotBoldEl.innerHTML = pieceOfInitialLabel;
            spanWrapperEl.appendChild(spanNotBoldEl);
        }

        // Add a span containing a bold piece of the initial label.
        if ((i + 1) < labelSplit.length) {
            var spanBoldEl = this._createElement({type: "span"});
            spanBoldEl.className = "pelias-ctrl-bold";
            pieceOfInitialLabel = "";
            for (var k = 1; k <= search.length; ++k) {
                index++;
                pieceOfInitialLabel += label[index];
            }
            spanBoldEl.innerHTML = pieceOfInitialLabel;
            spanWrapperEl.appendChild(spanBoldEl)
        }
    }

    return spanWrapperEl;
};

MapSearch.prototype._createAttribution = function(geocoding, index) {
    var self = this;

    var attributionElt = this._createElement({class: "pelias-ctrl-attribution"});
    attributionElt.setAttribute("tabindex", "-1");

    var labelWrapperEl = this._createElement({type: "span", class: "pelias-ctrl-wrapper-label"});

    labelWrapperEl.innerHTML = this.customAttribution || geocoding.attribution;

    attributionElt.appendChild(labelWrapperEl);

    return attributionElt;
}

// ---------------------------
// ---------- utils ----------
// ---------------------------

MapSearch.prototype._sendXmlHttpRequest = function (url, callback) {
    var req = new XMLHttpRequest();
    req.addEventListener('load', function () {
        switch (this.status) {
            case 200:
                return callback(null, JSON.parse(this.responseText));
            case 400:
                return callback('You sent a bad request.');
            case 401:
                return callback('You are not authorized to use this geocode.');
            case 500:
                return callback('This server can not answer yet.');
        }
    });
    req.open('GET', url);
    req.send();
};

MapSearch.prototype._coordinatesToArray = function (coordinates) {
    return [coordinates.lng, coordinates.lat];
};

MapSearch.prototype._between = function (x, min, max) {
    return x >= min && x <= max;
};

MapSearch.prototype._getBestZoom = function (feature) {
    if (feature.GAZETTEER_ENTRY.MBR_XMIN) {
        let minPoint = this._getLonLat(feature.GAZETTEER_ENTRY.MBR_XMIN, feature.GAZETTEER_ENTRY.MBR_YMIN, 0);
        let maxPoint = this._getLonLat(feature.GAZETTEER_ENTRY.MBR_XMAX, feature.GAZETTEER_ENTRY.MBR_YMAX, 0);
        var bbox = [minPoint[0], minPoint[1], maxPoint[0], maxPoint[1]];
    }
    if (!bbox) {
        return (['Named_Road', 'Numbered_Road', 'Postcode'].indexOf(feature.GAZETTEER_ENTRY.LOCAL_TYPE) > -1) ? 18 : 14;
    }
    var abs = Math.abs(bbox[2] - bbox[0]) * Math.abs(bbox[3] - bbox[1]);
    return abs !== 0 ? 8.5 - Math.log10(abs) : 8.5;
};

MapSearch.prototype._addOrRemoveClassToElement = function (element, add, className) {
    var elementContainsClassName = element.classList.contains(className);
    if (elementContainsClassName && !add) {
        element.classList.remove(className);
    } else if (!elementContainsClassName && add) {
        element.classList.add(className);
    }
};

MapSearch.prototype._useFlyTo = function (cameraOpts) {
    if (this.opts.flyTo === 'hybrid') {
        return this._areNear(cameraOpts.center, this._coordinatesToArray(this._map.getCenter()), this._getFlyToToleranceByZoom(this._map.getZoom()));
    }
    return this.opts.flyTo;
};

MapSearch.prototype._getFlyToToleranceByZoom = function (zoom) {
    return zoom < 3 ? 360 : 160 / Math.pow(zoom + 1, 2);
};

MapSearch.prototype._areNear = function (c1, c2, tolerance) {
    return this._between(c1[0], c2[0] - tolerance, c2[0] + tolerance) && this._between(c1[1], c2[1] - tolerance, c2[1] + tolerance);
};

MapSearch.prototype._removeSources = function (enabled, layer) {
    if (!enabled) {
        return;
    }
    if (this._map.getSource(layer)) {
        this._map.removeLayer(layer);
        this._map.removeSource(layer);
    }
};

MapSearch.prototype._eventMatchKey = function (e, key) {
    return (e.key !== undefined && e.key === key.key)
        || (e.keyCode !== undefined && e.keyCode === key.keyCode);
};

MapSearch.prototype._clearAll = function () {
    this._selectedFeature = undefined;
    this._addOrRemoveClassToElement(this._iconSearchEl, true, "pelias-ctrl-disabled");
    this._addOrRemoveClassToElement(this._iconCrossEl, true, "pelias-ctrl-hide");
    this._resultsListEl.removeAll();
    this._inputEl.value = "";
    this._removeMarkers();
    this._removePolygon();
};

MapSearch.prototype._getLonLat = function(easting, northing, height) {
    // Generate valid Easting/Nothing object

    let point = coordinates.EastNorthValues(easting, northing, height);

    // Transverse Mercator conversion from AIRY_1830 geoid
    let tansMerConverter = new transMerConversion.TranMerConversion();
    let convertedPoint = tansMerConverter.enToLonLat(point, "OSNG", "AIRY_1830");

    // Convert form OSGB36 datum to WGS84
    let datumConverter = new datumConversion.DatumConversion("OSGB36");
    let converted = datumConverter.conversion(convertedPoint, "WGS84");
    let latitude = Math.round(converted.getYAxis()*Math.pow(10,6))/Math.pow(10,6);
    let longitude = Math.round(converted.getXAxis()*Math.pow(10,6))/Math.pow(10,6);
    return [longitude, latitude];

}

