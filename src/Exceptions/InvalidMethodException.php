<?php

namespace TONYLABS\Shopify\Exceptions;

class InvalidMethodException extends \Exception
{
    public function __construct($message = 'method not allowed.')
    {
        parent::__construct($message);
    }
}
