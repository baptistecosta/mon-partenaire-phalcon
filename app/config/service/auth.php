<?php

use BCosta\Validator\Validator;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

$di->set('Validator\\Auth\\Password', function() use ($di) {
    return new Validator($di->get('Validation\\Auth\\Password'));
});

$di->set('Validation\\Auth\\Password', function() {
    $validation = new Validation();
    $validation->add('clientId', new PresenceOf([
        'message' => 'The client ID is required'
    ]));
    $validation->add('clientSecret', new PresenceOf([
        'message' => 'The client secret is required'
    ]));
    $validation->add('email', new PresenceOf([
        'message' => 'The user email address is required'
    ]));
    $validation->add('password', new PresenceOf([
        'message' => 'The user password is required'
    ]));
    return $validation;
});