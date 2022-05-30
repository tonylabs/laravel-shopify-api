<?php

namespace TONYLABS\Shopify\REST\Resources;

use ArrayAccess;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use TONYLABS\Shopify\Shopify;

class ApiResource implements ArrayAccess, Arrayable
{
    use Macroable;

    protected array $attributes = [];
    protected Shopify $shopify;

    public function __construct(array $attributes, Shopify $shopify)
    {
        $this->attributes = $attributes;
        $this->shopify = $shopify;
    }

    public function except($keys): array
    {
        return Arr::except($this->getAttributes(), is_array($keys) ? $keys : func_get_args());
    }

    public function only($keys): array
    {
        return Arr::only($this->getAttributes(), is_array($keys) ? $keys : func_get_args());
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->getAttribute($key);
        }

        throw new Exception('Property '.$key.' does not exist on '.get_called_class());
    }

    public function __isset($key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->attributes);
    }

    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->setAttribute($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    protected function getAttribute($key)
    {
        return $this->attributes[$key];
    }

    protected function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function toArray()
    {
        return $this->getAttributes();
    }
}
