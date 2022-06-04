<?php

namespace TONYLABS\Shopify;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use TONYLABS\Shopify\REST\Actions\ManagesAccess;
use TONYLABS\Shopify\REST\Actions\ManagesAnalytics;
use TONYLABS\Shopify\REST\Actions\ManagesBilling;
use TONYLABS\Shopify\REST\Actions\ManagesCollections;
use TONYLABS\Shopify\REST\Actions\ManagesCustomers;
use TONYLABS\Shopify\REST\Actions\ManagesDiscounts;
use TONYLABS\Shopify\REST\Actions\ManagesEvents;
use TONYLABS\Shopify\REST\Actions\ManagesFulfillments;
use TONYLABS\Shopify\REST\Actions\ManagesInventory;
use TONYLABS\Shopify\REST\Actions\ManagesMarketingEvents;
use TONYLABS\Shopify\REST\Actions\ManagesMetafields;
use TONYLABS\Shopify\REST\Actions\ManagesOnlineStore;
use TONYLABS\Shopify\REST\Actions\ManagesOrders;
use TONYLABS\Shopify\REST\Actions\ManagesPlus;
use TONYLABS\Shopify\REST\Actions\ManagesProducts;
use TONYLABS\Shopify\REST\Actions\ManagesSalesChannel;
use TONYLABS\Shopify\REST\Actions\ManagesShopifyPayments;
use TONYLABS\Shopify\REST\Actions\ManagesStoreProperties;
use TONYLABS\Shopify\REST\Paginator;
use TONYLABS\Shopify\Support\MakesHttpRequests;
use TONYLABS\Shopify\Support\TransformsResources;

class Shopify
{
    use MakesHttpRequests;
    use ManagesAccess;
    use ManagesAnalytics;
    use ManagesBilling;
    use ManagesCollections;
    use ManagesCustomers;
    use ManagesDiscounts;
    use ManagesEvents;
    use ManagesFulfillments;
    use ManagesInventory;
    use ManagesMarketingEvents;
    use ManagesMetafields;
    use ManagesOnlineStore;
    use ManagesOrders;
    use ManagesPlus;
    use ManagesProducts;
    use ManagesSalesChannel;
    use ManagesShopifyPayments;
    use ManagesStoreProperties;
    use TransformsResources;

    protected string $api_key;
    protected string $api_token;
    protected string $api_version;
    protected string $domain;

    protected ?PendingRequest $httpClient = null;

    public function __construct(string $api_key, string $api_token, string $api_version, string $domain)
    {
        $this->withCredentials($api_key, $api_token, $api_version,  $domain);
    }

    public function paginator(Collection $results): Paginator
    {
        return new Paginator($this, $results);
    }

    public function getHttpClient(): PendingRequest
    {
        return Http::baseUrl($this->getBaseUrl())->withBasicAuth($this->api_key, $this->api_token);
    }

    public function graphQl(): PendingRequest
    {
        return Http::baseUrl("https://{$this->domain}/admin/api/graphql.json")->withHeaders(['X-Shopify-Access-Token' => $this->api_token]);
    }

    public function getBaseUrl(): string
    {
        return "https://{$this->domain}/admin/api/{$this->api_version}";
    }

    public function tap(callable $callback): self
    {
        $callback($this->getHttpClient());
        return $this;
    }

    public function withCredentials(string $api_key, string $api_token, string $api_version, string $domain): self
    {
        $this->api_key = $api_key;
        $this->api_token = $api_token;
        $this->api_version = $api_version;
        $this->domain = $domain;
        return $this;
    }
}
