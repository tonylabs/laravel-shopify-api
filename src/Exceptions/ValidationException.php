<?php

namespace TONYLABS\Shopify\Exceptions;

class ValidationException extends \Exception
{
    public array $errors;

    public function __construct(array $errors = [])
    {
        $this->errors = $errors;
        parent::__construct(
            env('SHOPIFY_TOGGLE_VALIDATION_ERROR', false) ? 'Data validation error: '.json_encode($this->errors) : 'Data validation failed'
        );
    }
}
