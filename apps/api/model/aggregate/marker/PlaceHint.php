<?php

namespace MonPartenaire\Api\Model\Aggregate\Marker;

use BCosta\Sql\Sql;
use MonPartenaire\Api\Model\Place;
use Phalcon\Mvc\Model;

class PlaceHint
{
    public static function fetchAll(array $params = [])
    {
        $model = new Place();
        $connection = $model->getReadConnection()->getInternalHandler();
        $stmt = $connection->prepare(sprintf("
            SELECT
                p.id,
                p.name title,
                pl.latitude,
                pl.longitude
            FROM place p
            INNER JOIN place_location pl
               ON p.id = pl.place_id
            WHERE CONTAINS(GeomFromText('%s'), location)
        ", Sql::framePolygon(
            $params['southWestBound']['longitude'],
            $params['southWestBound']['latitude'],
            $params['northEastBound']['longitude'],
            $params['northEastBound']['latitude']
        )));

        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function($r) use ($params) {
            $r['icon'] = $params['zoom'] >= 11 ? '/img/spot.png' : '/img/measle_5px.png';
            return $r;
        }, $results);
    }
}