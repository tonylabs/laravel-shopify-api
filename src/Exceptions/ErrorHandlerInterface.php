<?php

namespace TONYLABS\Shopify\Exceptions;

use Illuminate\Http\Client\Response;

interface ErrorHandlerInterface
{
    public function handle(Response $response);
}
