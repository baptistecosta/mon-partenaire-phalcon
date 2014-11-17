<?php

namespace BCosta\Validator;

use Phalcon\Filter;
use Phalcon\Validation;

class Validator
{
    /**
     * @var Validation $validation
     */
    protected $validation;

    protected $messages;

    public function __construct(Validation $validation)
    {
        $this->validation = $validation;
        $this->messages = [];
    }

    public function isValid($data)
    {
        $this->messages = $this->validation->validate($data);
        return count($this->messages) == 0;
    }

    public function getMessages()
    {
        return $this->messages;
    }
}