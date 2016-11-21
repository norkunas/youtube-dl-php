<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

abstract class AbstractEntity implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected static $objectMap = [];

    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $this->convert($elements);
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return isset($this->elements[$key]) ? $this->elements[$key] : $default;
    }

    public function toArray(): array
    {
        return $this->elements;
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @param mixed $key
     *
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return array_key_exists($key, $this->elements);
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->elements[$key];
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function offsetSet($key, $value)
    {
        if (!is_null($key)) {
            $this->elements[$key] = $value;
        } else {
            $this->elements[] = $value;
        }
    }

    /**
     * @param string $key
     */
    public function offsetUnset($key)
    {
        unset($this->elements[$key]);
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    protected function convert(array $data): array
    {
        foreach ($data as $key => $item) {
            if (!isset(static::$objectMap[$key])) {
                continue;
            }

            if (is_array($data[$key])) {
                foreach ((array) $data[$key] as $k2 => $v) {
                    $data[$key][$k2] = new static::$objectMap[$key]($v);
                }
            } else {
                $data[$key] = new static::$objectMap[$key]($item ?: []);
            }
        }

        return $data;
    }
}
