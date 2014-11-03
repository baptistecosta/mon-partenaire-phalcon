<?php

use Phalcon\Mvc\Model,
    Phalcon\Mvc\Model\Validator\Uniqueness;

class Places extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    public function validation()
    {
        $this->validate(new Uniqueness([
            "field" => "name",
            "message" => "The robot name must be unique"
        ]));

        if ($this->validationHasFailed()) {
            return false;
        }
    }
}