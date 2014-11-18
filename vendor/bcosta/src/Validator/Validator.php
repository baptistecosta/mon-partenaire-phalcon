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
        $messages = $this->validation->validate($data);
        if (count($messages) == 0) {
            return true;
        }
        foreach ($messages as $msg) {
            $this->messages[] = [
                'type' => $msg->getType(),
                'field' => $msg->getField(),
                'message' => $msg->getMessage(),
            ];
        }
        return false;
    }

    public function getValue($name)
    {
        return $this->validation->getValue($name);
    }

    public function getMessages()
    {
        return $this->messages;
    }
}