<?php

namespace BCosta\Sanitizer;

use Phalcon\Filter;

class Place extends AbstractSanitizer
{
    public function sanitize($data)
    {
        $data['geolocation'] = $this->filter->sanitize($data['geolocation'], 'trim');
        $data['geolocation'] = $this->filter->sanitize($data['geolocation'], 'geolocation');
        return $data;
    }
}