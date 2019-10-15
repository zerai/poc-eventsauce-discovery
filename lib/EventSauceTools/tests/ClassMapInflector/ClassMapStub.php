<?php

declare(strict_types=1);

namespace EventSauceTools\Tests\ClassMapInflector;

use EventSauceTools\ClassMapInflector\ClassMap;

class ClassMapStub implements ClassMap
{
    private const MAP = [
        DummyFooEventClass::class => 'inflector.foo-class',
        DummyBarEventClass::class => 'inflector.bar-class',
    ];

    public function supportClass(): array
    {
        return self::MAP;
    }
}
