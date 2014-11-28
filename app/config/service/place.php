<?php

use BCosta\Validator\Validator;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

$di->set('Validation\\Place', function() {
    $validation = new Validation();
    $validation->add('geolocation', new PresenceOf([
        'message' => 'The geolocation is required'
    ]));
    $validation->setFilters('geolocation', 'geolocation');
    return $validation;
});

$di->set('Validator\\Place', function() use ($di) {
    return new Validator($di->get('Validation\\Place'));
});