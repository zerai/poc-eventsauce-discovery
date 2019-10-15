<?php

declare(strict_types=1);

namespace EventSauceTools\Tests\ClassMapInflector;

use EventSauceTools\ClassMapInflector\ClassMap;

class EmptyClassMapStub implements ClassMap
{
    private const MAP = [
    ];

    public function supportClass(): array
    {
        return self::MAP;
    }
}
