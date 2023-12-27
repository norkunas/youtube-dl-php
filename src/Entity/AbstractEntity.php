<?php

declare(strict_types=1);

namespace YoutubeDl\Entity;

use Countable;
use JsonException;

use const JSON_THROW_ON_ERROR;

use function count;
use function is_array;
use function json_encode;
use function json_last_error_msg;

abstract class AbstractEntity implements Countable
{
    /**
     * @var array<non-empty-string, class-string>
     */
    protected static array $objectMap = [];

    /**
     * @var array<mixed>
     */
    protected array $elements = [];

    /**
     * @param array<mixed> $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $this->convert($elements);
    }

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->elements[$key] ?? $default;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        $toArray = static function ($data) use (&$toArray): array {
            foreach ($data as $k => $v) {
                if (is_array($v)) {
                    $data[$k] = $toArray($v);
                } elseif ($v instanceof AbstractEntity) {
                    $data[$k] = $v->toArray();
                }
            }

            return $data;
        };

        return $toArray($this->elements);
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function toJson(int $options = JSON_THROW_ON_ERROR): string
    {
        $json = json_encode($this->toArray(), $options);

        if ($json === false) {
            throw new JsonException(json_last_error_msg());
        }

        return $json;
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * @param array<mixed> $data
     *
     * @return array<mixed>
     */
    protected function convert(array $data): array
    {
        foreach ($data as $key => $item) {
            if (!isset(static::$objectMap[$key])) {
                continue;
            }

            foreach ($item as $k2 => $v) {
                $data[$key][$k2] = new static::$objectMap[$key]($v);
            }
        }

        return $data;
    }
}
