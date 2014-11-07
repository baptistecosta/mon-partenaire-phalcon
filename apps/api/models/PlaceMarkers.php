<?php

namespace MonPartenaire\Api\Models;

use Phalcon\DI;
use Phalcon\Mvc\Model;

class PlaceMarkers extends Model
{
    public function getSource()
    {
        return 'places';
    }

    public static function fetchAll(array $params = [])
    {
//        $latSouth = floatval($params['south-west-bound']['latitude']);
//        $latNorth = floatval($params['north-east-bound']['latitude']);
//        $lngWest = floatval($params['south-west-bound']['longitude']);
//        $lngEast = floatval($params['north-east-bound']['longitude']);

        $latSouth = 38.776287335840166;
        $latNorth = 47.1866665542551;
        $lngWest = 1.5963689000000159;
        $lngEast = 10.033868900000016;

        $connection = DI::getDefault()->get('db')->getInternalHandler();
        $stmt = $connection->prepare("
            SELECT
                p.id,
                latitude,
                longitude,
                name title,
                'http://maps.google.com/mapfiles/ms/icons/blue-dot.png' icon
            FROM place p
            INNER JOIN place_location pl ON p.id = pl.place_id
            WHERE CONTAINS(
                GeomFromText('POLYGON((
                    {$lngWest} {$latSouth},
                    {$lngWest} {$latNorth},
                    {$lngEast} {$latNorth},
                    {$lngEast} {$latSouth},
                    {$lngWest} {$latSouth}
                ))'),
                location
            )
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}