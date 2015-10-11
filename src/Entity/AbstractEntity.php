<?php

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
    public function get($key, $default = null)
    {
        return isset($this->elements[$key]) ? $this->elements[$key] : $default;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @param mixed $key
     *
     * @return bool
     */
    public function offsetExists($key)
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
     * @param mixed $key
     * @param mixed $value
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

    /**
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    protected function convert(array $data)
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
