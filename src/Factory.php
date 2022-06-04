<?php

namespace TONYLABS\Shopify;

class Factory
{
    public static function env(): Shopify
    {
        return new Shopify(env('SHOPIFY_API_KEY'), env('SHOPIFY_API_TOKEN'), env('SHOPIFY_API_VERSION'), env('SHOPIFY_DOMAIN'));
    }

    public static function config(array $config): Shopify
    {
        return new Shopify($config['api_key'], $config['api_token'], $config['api_version'], $config['domain']);
    }
}
