<?php

namespace TONYLABS\Shopify;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TONYLABS\Shopify\Webhooks\Webhook;
use TONYLABS\Shopify\Exceptions\Handler;
use TONYLABS\Shopify\Webhooks\SecretProvider;
use TONYLABS\Shopify\Http\Controllers\WebhookController;
use TONYLABS\Shopify\Exceptions\ErrorHandlerInterface;

class ShopifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerMacros();
    }

    public function register()
    {
        $this->app->singleton(Shopify::class, fn () => Factory::env());
        $this->app->alias(Shopify::class, 'shopify');
        $this->app->bind(ErrorHandlerInterface::class, Handler::class);
        $this->app->singleton(SecretProvider::class, function (Application $app) {
            return $app->make(\TONYLABS\Shopify\Webhooks\ConfigSecretProvider::class);
        });
    }

    protected function registerMacros(): void
    {
        Route::macro('shopifyWebhooks', function (string $uri = 'shopify/webhooks') {
            return $this->post($uri, [WebhookController::class, 'handle'])->name('shopify.webhooks');
        });
        Request::macro('shopifyShopDomain', fn () => $this->header(Webhook::HEADER_SHOP_DOMAIN));
        Request::macro('shopifyHmacSignature', fn () => $this->header(Webhook::HEADER_HMAC_SIGNATURE));
        Request::macro('shopifyTopic', fn () => $this->header(Webhook::HEADER_TOPIC));
    }
}
