let coordinates = require('./coordinates');
const projectionConstants = {
	OSNG : {
		F0 : 0.9996012717,	// NatGrid scale factor on central meridian
		lat0 : 49.0,		// NatGrid true origin - Latitude
		lon0 : -2.0,		// NatGrid true origin - Longitude
		N0 : -100000,		// Northing of true origin (metres)
		E0 : 400000,		// Easting of true origin (meters)
	}
};

const ellipsoidConstants = {
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

const datumConstants = {
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

// Utility function to convert degrees to radians
Math.deg2Rad = function(degrees) {
	return degrees * Math.PI / 180;
};

// Utility function to convert radians to degrees
Math.rad2Deg = function(radians) {
	return radians * 180 / Math.PI;
};

export function TranMerConversion() {}

TranMerConversion.prototype.meridionalArc = function (b, F0, n, lat, lat0) {
	
	let Ma = (1 + n + ((5 / 4) * Math.pow(n, 2)) + ((5 / 4) * Math.pow(n, 3))) * (lat - lat0);
	let Mb = ((3 * n) + (3 * Math.pow(n, 2)) + ((21 / 8) * Math.pow(n, 3))) * Math.sin(lat - lat0) * Math.cos(lat + lat0);
	let Mc = ((15 / 8) * Math.pow(n, 2) + (15 / 8) * Math.pow(n, 3)) * Math.sin(2 * (lat - lat0)) * Math.cos(2 * (lat + lat0));
	let Md = (35 / 24) * Math.pow(n, 3) * Math.sin(3 * (lat - lat0)) * Math.cos(3 * (lat + lat0));
	let M = b * F0 * (Ma - Mb + Mc - Md);

  	return M;
};

TranMerConversion.prototype.eccentricitySquared = function(a,b) {

	let e2 = 1 - Math.pow(b, 2) / Math.pow(a, 2);
	return e2;
};

TranMerConversion.prototype.latLonToEN = function(coordinates, projectionCode, ellipsoidCode) {

	let projection = projectionConstants[projectionCode];
	let ellipoid = ellipsoidConstants[ellipsoidCode];

	let lon = Math.deg2Rad(coordinates.getXAxis());
	let lat = Math.deg2Rad(coordinates.getYAxis());

	let F0 = projection.F0;
	let lon0 = Math.deg2Rad(projection.lon0);
	let lat0 = Math.deg2Rad(projection.lat0);
	let N0 = projection.N0;
	let E0 = projection.E0;
	let a = ellipoid.a;
	let b = ellipoid.b;
	let e2 = this.eccentricitySquared(a,b);
	let n = (a - b) / (a + b);

	let cosLat = Math.cos(lat);
	let sinLat = Math.sin(lat);
	let tanLat = Math.tan(lat);
	let nu = a * F0 * (Math.pow(1 - e2 * Math.pow(sinLat, 2), -0.5));
	let rho = a * F0 * (1 - e2) * Math.pow(1 - e2 * Math.pow(sinLat, 2), -1.5);
	let eta2 = (nu / rho) - 1;

	let M = this.meridionalArc(b, F0, n, lat, lat0);

	let I = M + N0;
	let II = (nu / 2) * sinLat * cosLat;
	let III = (nu / 24) * sinLat * Math.pow(cosLat, 3) * (5 - Math.pow(tanLat, 2) + 9 * eta2);
	let IIIA = (nu / 720) * sinLat * Math.pow(cosLat, 5) * (61 - 58 * Math.pow(tanLat, 2) + Math.pow(tanLat, 4));
	let IV = nu * cosLat;
	let V = (nu / 6) * Math.pow(cosLat, 3) * (nu / rho - Math.pow(tanLat, 2));
	let VI = (nu / 120) * Math.pow(cosLat, 5) * (5 - 18 * Math.pow(tanLat, 2) + Math.pow(tanLat, 4) + 14 * eta2 - 58 * Math.pow(tanLat, 2) * eta2);

	let dLon = lon - lon0;

	let N = I + II * Math.pow(dLon, 2) + III * Math.pow(dLon, 4) + IIIA * Math.pow(dLon, 6);
	let E = E0 + IV * dLon + V * Math.pow(dLon, 3) + VI * Math.pow(dLon, 5);

	return coordinates.EastNorthValues(E, N, 0);
};

TranMerConversion.prototype.enToLonLat = function(coordinates, projectionCode, ellipsoidCode) {

	let E = coordinates.getXAxis();
	let N = coordinates.getYAxis();

	let projection = projectionConstants[projectionCode];
	let ellipoid = ellipsoidConstants[ellipsoidCode];

	let a = ellipoid.a;
	let b = ellipoid.b;
	let F0 = projection.F0;
	let lat0 = Math.deg2Rad(projection.lat0);
	let lon0 = Math.deg2Rad(projection.lon0);
	let N0 = projection.N0;
	let E0 = projection.E0;
	let e2 = this.eccentricitySquared(a,b);
	let n = (a - b) / (a + b);
	let n2 = n*n;
	let n3 = n*n*n;

	let lat1 = lat0;
	let M = this.meridionalArc(b, F0, n, lat1, lat0);

	do {
		lat1 = (N - N0 - M) / (a * F0) + lat1;
		M = this.meridionalArc(b, F0, n, lat1, lat0);
	} while (N - N0 - M >= 0.00001);

	let cosLat1 = Math.cos(lat1);
	let sinLat1 = Math.sin(lat1);
	let tanLat1 = Math.tan(lat1);
	let nu = a * F0 * (Math.pow(1 - e2 * Math.pow(sinLat1, 2), -0.5));
	let rho = a * F0 * (1 - e2) * Math.pow(1 - e2 * Math.pow(sinLat1, 2), -1.5);
	let eta2 = (nu / rho) - 1;

	let VII = tanLat1 / (2 * rho * nu);
	let VIII = tanLat1 / (24 * rho * Math.pow(nu, 3)) * (5 + 3 * Math.pow(tanLat1, 2) + eta2 - 9 * Math.pow(tanLat1, 2) * eta2);
	let IX = tanLat1 / (720 * rho * Math.pow(nu, 5)) * (61 + 90 * Math.pow(tanLat1, 2) + 45 * Math.pow(tanLat1, 4));
	let X = (1 / cosLat1) / nu;
	let XI = (1 / cosLat1) / (6 * Math.pow(nu, 3)) * (nu / rho + 2 * Math.pow(tanLat1, 2));
	let XII = (1 / cosLat1) / (120 * Math.pow(nu, 5)) * (5 + 28 * Math.pow(tanLat1, 2) + 24 * Math.pow(tanLat1, 4));
	let XIIA = (1 / cosLat1) / (5040 * Math.pow(nu,7)) * (61 + 662 * Math.pow(tanLat1, 2) + 1320 * Math.pow(tanLat1, 4) + 720 * Math.pow(tanLat1, 6));

	let lat = lat1 - (VII * Math.pow((E - E0), 2)) + (VIII * Math.pow((E - E0), 4)) - (IX * Math.pow((E - E0), 6));
	let lon = lon0 + X * (E - E0) - XI * Math.pow((E - E0), 3) + XII * Math.pow((E - E0), 5) - XIIA * Math.pow((E - E0), 7);
	
	return coordinates.LonLatValues(Math.rad2Deg(lon), Math.rad2Deg(lat), 0);
};

function ENPoint(location) {

	this.east = location.getXAxis();
	this.north = location.getYAxis();
}

function lonLatPoint(location) {

	this.lon = location.getXAxis();
	this.lat = location.getYAxis();
}

