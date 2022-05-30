<?php

namespace TONYLABS\Shopify\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Resource not found.');
    }
}
