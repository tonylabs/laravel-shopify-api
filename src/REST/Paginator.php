<?php

namespace TONYLABS\Shopify\REST;

use Iterator;
use RuntimeException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use TONYLABS\Shopify\Shopify;

class Paginator implements Iterator
{
    const LINK_REGEX = '/<(.*page_info=([a-z0-9\-]+).*)>; rel="?{type}"?/i';

    protected Shopify $shopify;
    protected int $page = 0;
    protected array $links = [];
    protected array $collections = [];
    protected string $resourceClass;

    public function __construct(Shopify $shopify, Collection $results)
    {
        $this->shopify = $shopify;
        $this->collections[$this->page] = $results;
        $this->detectResourceClass();
        $this->extractLinks();
    }

    public function current(): Collection
    {
        return $this->collections[$this->page];
    }

    public function hasNext(): bool
    {
        return ! empty($this->links['next']);
    }

    public function hasPrev(): bool
    {
        return $this->page > 0;
    }

    public function key(): int
    {
        return $this->page;
    }

    public function next(): void
    {
        $this->page++;

        if (! $this->valid() && $this->hasNext()) {
            $this->collections[$this->page] = $this->fetchNextResults();
            $this->extractLinks();
        }
    }

    public function prev(): void
    {
        if (! $this->hasPrev()) {
            throw new RuntimeException('No previous link found.');
        }
        $this->page--;
    }

    public function rewind(): void
    {
        $this->page = 0;
    }

    public function valid(): bool
    {
        return isset($this->collections[$this->page]);
    }

    protected function extractLinks(): void
    {
        $response = $this->shopify->getLastResponse();
        if (! $response->header('Link')) {
            $this->links = [];
            return;
        }
        $arrayLinks = ['next' => null, 'previous' => null];
        foreach (array_keys($arrayLinks) as $type)
        {
            $boolMatched = preg_match(str_replace('{type}', $type, static::LINK_REGEX), $response->header('Link'), $arrayMatches);
            if ($boolMatched) $arrayLinks[$type] = $arrayMatches[1];
        }
        $this->links = $arrayLinks;
    }

    protected function fetchNextResults(): Collection
    {
        $response = $this->shopify->get(
            Str::after($this->links['next'], $this->shopify->getBaseUrl())
        );
        return Collection::make(Arr::first($response->json()))->map(fn ($attr) => new $this->resourceClass($attr, $this->shopify));
    }

    private function detectResourceClass()
    {
        if ($resource = optional($this->collections[0])->first()) {
            $this->resourceClass = get_class($resource);
        }
    }
}
