<?php

namespace MonPartenaire\Api\Model\Aggregate\Marker;

use Phalcon\DI;

class Place
{
    public static function fetchAll(array $params = [])
    {
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
                    {$params['lngWest']} {$params['latSouth']},
                    {$params['lngWest']} {$params['latNorth']},
                    {$params['lngEast']} {$params['latNorth']},
                    {$params['lngEast']} {$params['latSouth']},
                    {$params['lngWest']} {$params['latSouth']}
                ))'),
                location
            )
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}