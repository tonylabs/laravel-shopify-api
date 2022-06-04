<?php

namespace TONYLABS\Shopify\Webhooks;

interface SecretProvider
{
    public function getSecret(string $domain): string;
}
