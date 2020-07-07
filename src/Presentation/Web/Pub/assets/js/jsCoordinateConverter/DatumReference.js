let ellipsoidConstants = {
	AIRY_1830 : {
		a : 6377563.396,	// Semi-major axis (metres)
		b : 6356256.909,	// Semi-minor axis (metres)
	},
	GRS80 : {
		a: 6378137.0,		// Semi-major axis (metres)
		b: 6356752.3141		// Semi-minor axis (metres)
	},
	WGS_84 : {
		a: 6378137.0,		// Semi-major axis (metres)
		b: 6356752.3141		// Semi-minor axis (metres)
	}
};
let datumConstants = {
	OSGB36: {
		name : "Ordnance Survey - Great Britain (1936)",
		synonyms : "OSGB",
		epsg_id : "4277",
		esri_name : "D_OSGB_1936",
		defaultRegion : "GB_Great_Britain",
		referenceEllipsoid : "AIRY_1830",
		regions : {
			GB_Great_Britain : {
				translationVectors : {
					x : -446.448,
					y : +125.157,
					z : -542.06,
				},
				translationVectorsUOM : "meters",
				rotationMatrix : {
					x : -0.1502,
					y : -0.247,
					z : -0.8421,
				},
				rotationMatrixUOM : "ARCSECONDS",
				scaleFactor : 20.4894    //  ppm
			},
		},
	},

	WGS84 : {//    Global GPS
		name : "WGS 1984",
		epsg_id : "4326",
		esri_name : "D_WGS_1984",
		defaultRegion : "Global_Definition",
		referenceEllipsoid : "WGS_84",
		regions :  {
			Global_Definition : {
				translationVectors : {
					x : 0.0,
					y : 0.0,
					z : 0.0,
				},
				translationVectorsUOM : "meters",
				rotationMatrix : {
					x : 0.0,
					y : 0.0,
					z : 0.0,
				},
				rotationMatrixUOM : "ARCSECONDS",
				scaleFactor : 0.0    //  ppm
			},
		},
	}
};
export function DatumReference (datum, region) {

	var _datumReference;
	var _datumName;
	var _ellipsoidName;
	var _ellipsoid;
	var _helmertParameters;

	function _setHelmertParameters(region) {

		var regionParameters;

		if (typeof region === 'undefined') throw new Error('No region name set');

		var datum = _datumReference;

		if (datumConstants[datum].regions[region]) {
			regionParameters = datumConstants[datum].regions[region];
		} else {
			throw new Error(region + " is not a valid region for this datum");
		}

		_helmertParameters = {
			translationVectors : regionParameters.translationVectors,
			rotationMatrix : regionParameters.rotationMatrix,
			scaleFactor: regionParameters.scaleFactor
		};

	}

	this.setDatum = function (datum, region) {

		var datumConfig;

		if (typeof datumConstants[datum] !== 'undefined') {
			datumConfig = datumConstants[datum];
		} else {
			throw new Error(datum + " is not a valid datum");
		}

		if (typeof region === 'undefined' || region === null) {
			region = datumConfig.defaultRegion;
		}

		_datumReference = datum;
		_datumName = datumConfig.name;
		_ellipsoidName = datumConfig.referenceEllipsoid;
		_ellipsoid = ellipsoidConstants[_ellipsoidName];
		_setHelmertParameters(region);

		return this;
	};



	this.getDatumReference = function () {

		return _datumReference;
	};

	this.getEllipsoid = function () {

		return _ellipsoid;
	};

	this.getHelmertParameters = function () {

		return _helmertParameters;
	};

	if (typeof datum === 'undefined') {
		this.setDatum("WGS84", null);
	} else {
		this.setDatum(datum, region);
	}
	return this;
}

