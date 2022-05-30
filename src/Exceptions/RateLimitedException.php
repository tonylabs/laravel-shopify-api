<?php

namespace TONYLABS\Shopify\Exceptions;

use Illuminate\Http\Client\Response;

class RateLimitedException extends \Exception
{
    public Response $response;

    public function __construct(Response $response, $message = null)
    {
        $this->response = $response;

        parent::__construct($message ?? 'Request rate limit reached.');
    }
}
