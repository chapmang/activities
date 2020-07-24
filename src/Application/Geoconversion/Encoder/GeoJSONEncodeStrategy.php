<?php
declare(strict_types=1);
namespace App\Application\GeoConversion\Encoder;

use App\Domain\DataTypes\Spatial\Types\Geography\GeographyInterface;
use App\Domain\DataTypes\Spatial\Types\Geography\Point;
use Symfony\Component\Serializer\SerializerInterface;

final class GeoJSONEncodeStrategy implements GeoEncoderStrategyInterface
{
    private $key = 'json';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function isEncodable(string $format): bool
    {
        return $format === $this->key;
    }

    /**
     * @param $geom
     * @param null $name
     * @param null $mode
     * @return string
     * @TODO Need to finnish support for feature collections
     */
    public function encode($geom, $name = null, $mode = null) {

        $feature = [];
        $feature['type'] = 'feature';
        $recursiveJSON = function ($geom) use (&$recursiveJSON) {
            if ($geom instanceof Point) {
                return array($geom->getLongitude(), $geom->getLatitude());
            } else {
                return array_map($recursiveJSON, $geom->getPoints());
            }
        };

        if(is_array($geom)) {
            foreach ($geom as $key => $value) {
                if ($value instanceof GeographyInterface) {
                    $feature['geometry'] = (object)array('type' => $value::name, 'coordinates' => call_user_func($recursiveJSON, $value));
                } else {
                    $feature['properties'][$key] = $value;
                }
            }
        } else {
            if ($geom instanceof GeographyInterface) {
                $feature['geometry'] = (object)array('type' => $geom::name, 'coordinates' => call_user_func($recursiveJSON, $geom));
            } else {
                $feature['properties'];
            }
        }

        return $this->serializer->serialize($feature, $this->key);
    }




}