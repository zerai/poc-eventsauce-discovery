<?php

declare(strict_types=1);

namespace EventSauceTools\Tests\ClassMapInflector;

use EventSauceTools\ClassMapInflector\ClassMapInflectorException;
use PHPUnit\Framework\TestCase;

class ClassMapInflectorExceptionTest extends TestCase
{
    /**
     * @test
     * @expectedException \EventSauceTools\ClassMapInflector\ClassMapInflectorException
     * @expectedExceptionMessage Class name EventSauceTools\Tests\ClassMapInflector\DummyBarEventClass is not mapped to any event.
     */
    public function can_be_created_with_class_name(): void
    {
        $className = get_class(new DummyBarEventClass());

        throw ClassMapInflectorException::withClassName($className);
    }

    /**
     * @test
     * @expectedException \EventSauceTools\ClassMapInflector\ClassMapInflectorException
     * @expectedExceptionMessage Message type fake-event-name is not mapped to any class.
     */
    public function can_be_created_with_event_name(): void
    {
        throw ClassMapInflectorException::withEventName('fake-event-name');
    }
}
