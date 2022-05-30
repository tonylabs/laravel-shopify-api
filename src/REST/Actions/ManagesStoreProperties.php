<?php

namespace TONYLABS\Shopify\REST\Actions;

use Illuminate\Support\Collection;
use TONYLABS\Shopify\REST\Paginator;
use TONYLABS\Shopify\REST\Resources\ApiResource;
use TONYLABS\Shopify\REST\Resources\CountryResource;
use TONYLABS\Shopify\Shopify;

trait ManagesStoreProperties
{
    public function createCountry(array $data): CountryResource
    {
        return $this->createResource('countries', $data);
    }

    public function getCountriesCount(array $params = []): int
    {
        return $this->getResourceCount('countries', $params);
    }

    public function getCountries(array $params = []): Collection
    {
        return $this->getResources('countries', $params);
    }

    public function getCountry($countryId): CountryResource
    {
        return $this->getResource('countries', $countryId);
    }

    public function updateCountry($countryId, array $data): CountryResource
    {
        return $this->updateResource('countries', $countryId, $data);
    }

    public function deleteCountry($countryId): void
    {
        $this->deleteResource('countries', $countryId);
    }

    public function paginateCurrencies(array $params = []): Paginator
    {
        return $this->paginator($this->getCurrencies($params));
    }

    public function getCurrencies(array $params = []): Collection
    {
        return $this->getResources('currencies', $params);
    }

    public function getPolicies(array $params = []): Collection
    {
        return $this->getResources('policies', $params);
    }

    public function getCountryProvincesCount($countryId, array $params = []): int
    {
        return $this->getResourceCount('provinces', $params, ['countries', $countryId]);
    }

    public function getCountryProvinces($countryId, array $params = []): Collection
    {
        return $this->getResources('provinces', $params, ['countries', $countryId]);
    }

    public function getCountryProvince($countryId, $provinceId): CountryResource
    {
        return $this->getResource('provinces', $provinceId, ['countries', $countryId]);
    }

    public function updateCountryProvince($countryId, $provinceId, array $data): CountryResource
    {
        return $this->updateResource('provinces', $provinceId, $data, ['countries', $countryId]);
    }

    public function getShippingZones(array $params = []): Collection
    {
        return $this->getResources('shipping_zones', $params);
    }

    public function shop(): ApiResource
    {
        $response = $this->get('shop.json');

        return new ApiResource($response->json('shop'), $this);
    }
}
