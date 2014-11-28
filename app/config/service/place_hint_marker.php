<?php

use BCosta\Validator\Validator;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

$di->set('Validation\\PlaceHintMarker', function() {
    $validation = new Validation();
    $validation->add('south-west-bound', new PresenceOf([
        'message' => 'The south west bound is required'
    ]));
    $validation->add('north-east-bound', new PresenceOf([
        'message' => 'The north east bound is required'
    ]));
    $validation->add('zoom', new PresenceOf([
        'message' => 'The map zoom is required'
    ]));
    $validation->setFilters('south-west-bound', 'geolocation');
    $validation->setFilters('north-east-bound', 'geolocation');
    $validation->setFilters('zoom', 'int');
    return $validation;
});

$di->set('Validator\\PlaceHintMarker', function() use ($di) {
    return new Validator($di->get('Validation\\PlaceHintMarker'));
});