<?php

namespace BCosta\Sql;

class Sql
{
    /**
     * @param $bottomLeftX
     * @param $bottomLeftY
     * @param $topRightX
     * @param $topRightY
     * @return string
     */
    public static function framePolygon($bottomLeftX, $bottomLeftY, $topRightX, $topRightY)
    {
        return vsprintf('POLYGON((%f %f, %f %f, %f %f, %f %f, %f %f))', [
            $bottomLeftX, $bottomLeftY,
            $bottomLeftX, $topRightY,
            $topRightX, $topRightY,
            $topRightX, $bottomLeftY,
            $bottomLeftX, $bottomLeftY,
        ]);
    }
}