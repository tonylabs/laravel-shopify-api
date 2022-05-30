<?php

namespace TONYLABS\Shopify\REST\Actions;

use Illuminate\Support\Collection;
use TONYLABS\Shopify\REST\Paginator;
use TONYLABS\Shopify\REST\Resources\ApiResource;

trait ManagesEvents
{
    public function createEvent(array $data): ApiResource
    {
        return $this->createResource('events', $data);
    }

    public function getEventsCount(array $params = []): int
    {
        return $this->getResourceCount('events', $params);
    }

    public function paginateEvents(array $params = []): Paginator
    {
        return $this->paginator($this->getEvents($params));
    }

    public function getEvents(array $params = []): Collection
    {
        return $this->getResources('events', $params);
    }
}
