let datumReference = require('./DatumReference');
let coordinates = require('./coordinates');
function invertSign(number) {

	if (!isNaN(parseFloat(number)) && isFinite(number)) {
		let invertedNumber = number * -1;
		return invertedNumber;
	} else {
		return number;
	}
};
function recursiveLoop (object, method) {
	for (var property in object) {
		if (object.hasOwnProperty(property)) {

			if (typeof object[property] == 'object') {
				recursiveLoop(object[property], method);
			} else {
				object[property] = invertSign(object[property]);
			}
		}
	}
	return object;
}

export function DatumConversion (fromDatum) {

	if (typeof fromDatum === 'undefined') {
		return "A source datum must be set";
	}

	this._fromDatum = datumReference.DatumReference(fromDatum);
	this._fromEllipsoid = this._fromDatum.getEllipsoid();
	return this;
};

DatumConversion.prototype.getFromDatum = function(){
	return _fromDatum;
};


DatumConversion.prototype.conversion = function(coordinates, toDatum) {

	this._toDatum = datumReference.DatumReference(toDatum);
	this._toEllipsoid = this._toDatum.getEllipsoid();

	var wgs84 = datumReference.DatumReference("WGS84");

	// From WGS84
	if (this._fromDatum.getDatumReference() == wgs84.getDatumReference()) {
		this._toHelmert = this._toDatum.getHelmertParameters();
	}

	// To WGS84
	if (this._toDatum.getDatumReference() == wgs84.getDatumReference()){
		this._toHelmert = this._fromDatum.getHelmertParameters();
		this._toHelmert = recursiveLoop(this._toHelmert, "invertSign");
	}

	// Neither to or from WGS84 so go via WGS84 as reference point
	if (this._toHelmert === undefined) {
		coordinates = conversion(coordinates, "WGS84");
		this._toHelmert = this._toDatum.getHelmertParameters();
	}

	let source_xyz = this.toCartesian(coordinates);
	let destination_xyz = this.helmertTransformation(source_xyz.getXAxis(), source_xyz.getYAxis(), source_xyz.getZAxis(), this._toHelmert);
	let destination_latlon = this.fromCartesian(destination_xyz);

	return destination_latlon;

};

DatumConversion.prototype.toCartesian = function (coordinates) {

	let lon = Math.deg2Rad(coordinates.getXAxis());
	let lat = Math.deg2Rad(coordinates.getYAxis());
	let height = coordinates.getZAxis();

	let semiMajor = this._fromEllipsoid.a;
	let semiMinor = this._fromEllipsoid.b;
	let e2 = 1 - Math.pow(semiMinor, 2) / Math.pow(semiMajor, 2);

	let sinLat = Math.sin(lat);
	let cosLat = Math.cos(lat);
	let sinLon = Math.sin(lon);
	let cosLon = Math.cos(lon);

	let v = semiMajor / (Math.sqrt(1 - (e2 * Math.pow(sinLat,2))));

	let x = (v + height) * cosLat * cosLon;
	let y = (v + height) * cosLat * sinLon;
	let z = (v* (1 - e2) + height) * sinLat;

	return coordinates.XyzValues(x,y,z);
};

DatumConversion.prototype.fromCartesian = function(coordinates) {

	let x = coordinates.getXAxis();
	let y = coordinates.getYAxis();
	let z = coordinates.getZAxis();

	let semiMajor = this._toEllipsoid.a;
	let semiMinor = this._toEllipsoid.b;
	let e2 = 1 - Math.pow(semiMinor, 2) / Math.pow(semiMajor, 2);

	let lon = Math.atan2(y , x);

	let v = semiMajor / (Math.sqrt(1 - (e2 * Math.pow(Math.sin(y),2))));
	let p = Math.sqrt(Math.pow(x, 2) + Math.pow(y, 2));
	let lat = Math.atan2(z , p * (1 - e2));

	while (Math.abs(y - lat) > (4 / semiMajor)) {
		y = lat;
		v = semiMajor / (Math.sqrt(1 - (e2 * Math.pow(Math.sin(y),2))));
		lat = Math.atan2((z + e2 * v * Math.sin(y)) , p);
	}

	let height = (p / Math.cos(lat)) - v;

	lon = Math.rad2Deg(lon);
	lat = Math.rad2Deg(lat);

	return coordinates.LonLatValues(lon, lat, height);
};

DatumConversion.prototype.helmertTransformation = function(x, y, z, t) {

	let tx = t.translationVectors.x;
	let ty = t.translationVectors.y;
	let tz = t.translationVectors.z;

	let rx = Math.deg2Rad(t.rotationMatrix.x / 3600);
	let ry = Math.deg2Rad(t.rotationMatrix.y / 3600);
	let rz = Math.deg2Rad(t.rotationMatrix.z / 3600);

	let s = t.scaleFactor / 1e6;

	let xAxis = tx + x * (1 + s) - y * rz + z * ry;
	let yAxis = ty + x * rz + y * (1 + s) - z * rx;
	let zAxis = tz - x * ry + y * rx + z * (1 + s);

	return coordinates.XyzValues(xAxis,yAxis,zAxis);
};


