<?php

namespace MonPartenaire\Api\Model;

use Phalcon\Mvc\Model;

class ScrappedPlace extends Model
{
    public function getSource()
    {
        return 'scrapped_place';
    }
}