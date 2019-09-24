<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

final class TodoStatus
{
    public const OPTIONS = [
        'OPEN' => 0,
        'DONE' => 1,
        'EXPIRED' => 2,
    ];

    public const OPEN = 0;
    public const DONE = 1;
    public const EXPIRED = 2;

    private $name;
    private $value;

    private function __construct(string $name)
    {
        $this->name = $name;
        $this->value = self::OPTIONS[$name];
    }

    public static function OPEN(): self
    {
        return new self('OPEN');
    }

    public static function DONE(): self
    {
        return new self('DONE');
    }

    public static function EXPIRED(): self
    {
        return new self('EXPIRED');
    }

    public static function fromName(string $value): self
    {
        if (!isset(self::OPTIONS[$value])) {
            throw new \InvalidArgumentException('Unknown enum name given');
        }

        return self::{$value}();
    }

    public static function fromValue($value): self
    {
        foreach (self::OPTIONS as $name => $v) {
            if ($v === $value) {
                return self::{$name}();
            }
        }

        throw new \InvalidArgumentException('Unknown enum value given');
    }

    public function equals(TodoStatus $other): bool
    {
        return \get_class($this) === \get_class($other) && $this->name === $other->name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value()
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function toString(): string
    {
        return $this->name;
    }
}
