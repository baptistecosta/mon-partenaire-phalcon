<?php

namespace BCosta\Mvc\Model;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;

/**
 * Class PhalconModel
 * @package BCosta\Phalcon\Mvc\Model
 */
class PhalconModel extends Model
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