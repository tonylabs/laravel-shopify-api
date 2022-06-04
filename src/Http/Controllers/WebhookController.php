<?php

namespace TONYLABS\Shopify\Http\Controllers;

use Exception;
use TONYLABS\Shopify\Http\Middleware\ValidateWebhook;
use TONYLABS\Shopify\Webhooks\Webhook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;

class WebhookController extends Controller
{
    public function __construct()
    {
        $this->middleware(ValidateWebhook::class);
    }

    public function handle(Request $request)
    {
        try {
            $webhook = Webhook::fromRequest($request);
            Event::dispatch($webhook->eventName(), $webhook);
            return new JsonResponse();
        } catch (Exception $e) {
            return new Response('Error handling webhook', 500);
        }
    }
}
