<?php
declare(strict_types=1);
namespace App\Application\GeoConversion;

class GeoConverter
{

    /**
     * @var GeoEncode
     */
    private $geoEncoder;
    private $geoDecoder;


    public function __construct(GeoEncode $geoEncoder, GeoDecode $geoDecoder)
    {
        $this->geoEncoder = $geoEncoder;
        $this->geoDecoder = $geoDecoder;
    }


    public function geom_to_geojson($text)
    {
        return $this->geoEncoder->encodeGeometry('json', $text);
    }

    public function geom_to_gpx($text)
    {
        return $this->geoEncoder->encodeGeometry('gpx', $text);
    }

    public function geojson_to_wkt ($text)
    {
        //$geom = $this->geoJsonDecoder->geomFromText($text);
        $geom = $this->geoDecoder->decodeGeometry('json', $text);
        return $this->geoEncoder->encodeGeometry('wkt', $geom, null);
    }


//
//    public function wkt_to_geojson ($text)
//    {
//        return $this->wktDecoder->geomFromText($text)->toGeoJSON();
//    }
//    public function wkt_to_kml ($text)
//    {
//        return $this->wktDecoder->geomFromText($text)->toKML();
//    }
//    public function wkt_to_gpx($text, $mode)
//    {
//        return $this->wktDecoder->geomFromText($text)->toGPX($mode);
//    }
//    public function geojson_to_wkt ($text)
//    {
//        //$geom = $this->geoJsonDecoder->geomFromText($text);
//        $geom = $this->geoDecoder->decodeGeometry('gpx', $text);
//        return $this->geoEncoder->encodeGeometry('json',$geom, null, );
//    }
//    public function geojson_to_kml ($text)
//    {
//        return $this->geoJsonDecoder->geomFromText($text)->toKML();
//    }
//    public function geojson_to_gpx ($text, $name, $mode)
//    {
//        $gpx = $this->geoJsonDecoder->geomFromText($text)->toGPX($mode, $name);
//        return
//
//
//
//    }
//    public function kml_to_wkt ($text)
//    {
//        return $this->kmlDecoder->geomFromText($text)->toWKT();
//    }
//    public function kml_to_geojson ($text)
//    {
//        return $this->kmlDecoder->geomFromText($text)->toGeoJSON();
//    }
//    public function kml_to_gpx ($text, $mode)
//    {
//        return $this->kmlDecoder->geomFromText($text)->toGPX($mode);
//    }
//    public function gpx_to_wkt ($text)
//    {
//        return $this->gpxDecoder->geomFromText($text)->toWKT();
//    }
//    public function gpx_to_geojson ($text)
//    {
//        return $this->gpxDecoder->geomFromText($text)->toGeoJSON();
//    }
//    public function gpx_to_kml ($text)
//    {
//        return $this->gpxDecoder->geomFromText($text)->toGPX();
//    }

}