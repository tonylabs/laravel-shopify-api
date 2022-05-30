<?php

namespace TONYLABS\Shopify\Support;

use Illuminate\Support\Collection;
use TONYLABS\Shopify\REST\Resources\ApiResource;

trait TransformsResources
{
    protected function transformCollection(array $items, string $class): Collection
    {
        return collect($items)->map(function ($attributes) use ($class) {
            return $this->transformItem($attributes, $class);
        });
    }

    protected function transformItem(array $attributes, string $class): ApiResource
    {
        return new $class($attributes, $this);
    }
}
