<?php

namespace TONYLABS\Shopify\Webhooks;

use Illuminate\Http\Request;
use TONYLABS\Shopify\Exceptions\WebhookFailed;
use TONYLABS\Shopify\Support\VerifiesWebhooks;

class WebhookValidator
{
    use VerifiesWebhooks;

    private SecretProvider $secretProvider;

    public function __construct(SecretProvider $secretProvider)
    {
        $this->secretProvider = $secretProvider;
    }

    public function validate(string $signature, string $domain, string $data): void
    {
        $secret = $this->secretProvider->getSecret($domain);
        throw_if(empty($secret), WebhookFailed::missingSigningSecret());
        throw_unless(
            $this->isWebhookSignatureValid($signature, $data, $secret),
            WebhookFailed::invalidSignature($signature)
        );
    }

    public function validateFromRequest(Request $request): void
    {
        $signature = $request->shopifyHmacSignature();
        throw_unless($signature, WebhookFailed::missingSignature());
        throw_unless($request->shopifyTopic(), WebhookFailed::missingTopic());
        $this->validate($signature, $request->shopifyShopDomain(), $request->getContent());
    }
}
