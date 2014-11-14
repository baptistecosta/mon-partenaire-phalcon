<?php

namespace MonPartenaire\Api\Model\Aggregate\Marker;

use MonPartenaire\Api\Model\Place;
use Phalcon\Mvc\Model;

class PlaceHint
{
    public static function fetchAll(array $params = [])
    {
        $model = new Place();
        $connection = $model->getReadConnection()->getInternalHandler();
        $stmt = $connection->prepare("
            SELECT
                p.id id,
                p.name name,
                p.name title,
                'http://mon-partenaire.loc/img/measle_5px.png' icon,
                pl.latitude latitude,
                pl.longitude longitude
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