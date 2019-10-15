<?php

declare(strict_types=1);

namespace EventSauceTools\Tests\ClassMapInflector;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class DummyBarEventClass implements SerializablePayload
{
    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self([]);
    }
}
