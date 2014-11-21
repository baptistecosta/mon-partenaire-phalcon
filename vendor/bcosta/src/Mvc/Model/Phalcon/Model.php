<?php

namespace BCosta\Mvc\Model\Phalcon;

use Phalcon\Mvc\Model as PhalconModel;
use Phalcon\Mvc\Model\Message;

/**
 * Class Model
 * @package BCosta\Mvc\Model\Phalcon
 */
class Model extends PhalconModel
{
    public function getErrorMessages()
    {
        return array_map(function(Message $msg) {
            return [
                'field' => $msg->getField(),
                'type' => $msg->getType(),
                'message' => $msg->getMessage(),
                'code' => $msg->getCode()
            ];
        }, $this->_errorMessages);
    }
}