<?php

namespace Ignite\Shared\Data\DTO;

use App\Exceptions\JsonEncodingException;
use App\Support\Arr;
use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class DTO implements JsonSerializable, ArrayAccess, Arrayable, Jsonable
{
    /**
     * Create a new DTO instance.
     *
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function __construct(protected array $data)
    {
    }

    /**
     * Dynamically retrieve attributes on the DTO.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        return $this->offsetGet($key);
    }

    /**
     * Dynamically set attributes on the DTO.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function __set(string $key, $value): void
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Dynamically check if a value is set on the DTO.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Get the data collection or a specific key.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed|array<string, mixed>
     */
    public function getData(?string $key = null, mixed $default = null): mixed
    {
        return is_null($key) ? $this->data : Arr::get($this->data, $key, $default);
    }

    /**
     * Set the data for the DTO.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function setData(string $key, mixed $value): void
    {
        Arr::set($this->data, $key, $value);
    }

    /**
     * Merge the given data with the current data.
     *
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function mergeData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return Arr::wrap($this->getData());
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param int $options
     *
     * @return string
     *
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     */
    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (json_last_error() !== \JSON_ERROR_NONE) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Get the value for a given offset.
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->data[$offset];
    }

    /**
     * Set the value at the given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * Unset the value at the given offset.
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }
}
