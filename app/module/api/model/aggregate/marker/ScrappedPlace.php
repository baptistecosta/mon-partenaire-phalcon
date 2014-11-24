<?php

namespace MyTennisPal\Api\Model\Aggregate\Marker;

class ScrappedPlace
{
    public static function fetchAll()
    {
        $results = \MyTennisPal\Api\Model\ScrappedPlace::find(['columns' => ['id', 'latitude', 'longitude']]);

        $data = [];
        foreach ($results as $res) {
            $data[] = [
                'id' => $res->id,
                'latitude' => $res->latitude,
                'longitude' => $res->longitude,
                'icon' => 'http://maps.google.com/mapfiles/ms/micons/sunny.png',
                'title' => $res->id . ' - ' . $res->latitude . ',' . $res->longitude
            ];
        }
        return $data;
    }
}