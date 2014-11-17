<?php

namespace BCosta\Sanitizer;

use Phalcon\Filter;

/**
 * Class AbstractSanitizer
 * @package BCosta\Sanitizer
 */
abstract class AbstractSanitizer
{
    protected $filter;

    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    abstract public function sanitize($data);
}