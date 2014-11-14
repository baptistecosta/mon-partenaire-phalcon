<?php

namespace BCosta\Filter;

class Geolocation
{
    public function filter($value)
    {
        $ll = explode(',', $value);
        if (count($ll) != 2) {
            return false;
        }
        return [
            'latitude' => floatval($ll[0]),
            'longitude' => floatval($ll[1])
        ];
    }
}