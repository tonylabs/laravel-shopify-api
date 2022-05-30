<?php

namespace TONYLABS\Shopify\Exceptions;

class ValidationException extends \Exception
{
    public array $errors;

    public function __construct(array $errors = [])
    {
        $this->errors = $errors;

        parent::__construct(
            config('shopify.exceptions.include_validation_errors', false)
            ? 'Data validation failure caused by: '.json_encode($this->errors)
            : 'Data validation failed'
        );
    }
}
