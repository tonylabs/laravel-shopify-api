<?php

namespace TONYLABS\Shopify\Webhooks;

class ConfigSecretProvider implements SecretProvider
{
    public function getSecret(string $domain): string
    {
        return env('SHOPIFY_API_SECRET_KEY');
    }
}
