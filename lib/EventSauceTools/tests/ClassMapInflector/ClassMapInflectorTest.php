<?php

declare(strict_types=1);

namespace EventSauceTools\Tests\ClassMapInflector;

use EventSauce\EventSourcing\ClassNameInflector;
use EventSauceTools\ClassMapInflector\ClassMapInflector;
use PHPUnit\Framework\TestCase;

class ClassMapInflectorTest extends TestCase
{
    /** @test */
    public function can_be_created(): void
    {
        $inflector = new ClassMapInflector(
            new ClassMapStub()
        );

        self::assertInstanceOf(ClassNameInflector::class, $inflector);
    }

    /** @test */
    public function can_convert_classname_to_type(): void
    {
        $inflector = new ClassMapInflector(
            new ClassMapStub()
        );

        self::assertEquals(
            'inflector.foo-class',
            $inflector->classNameToType(DummyFooEventClass::class)
            );
    }

    /**
     * @test
     * @expectedException \EventSauceTools\ClassMapInflector\ClassMapInflectorException
     */
    public function throw_exception_if_class_name_is_not_in_map(): void
    {
        $inflector = new ClassMapInflector(
            new EmptyClassMapStub()
        );

        $inflector->classNameToType(DummyFooEventClass::class);
    }

    /** @test */
    public function can_convert_type_to_classname(): void
    {
        $inflector = new ClassMapInflector(
            new ClassMapStub()
        );

        self::assertEquals(
            DummyFooEventClass::class,
            $inflector->typeToClassName('inflector.foo-class')
        );
    }

    /**
     * @test
     * @expectedException \EventSauceTools\ClassMapInflector\ClassMapInflectorException
     */
    public function throw_exception_if_event_name_is_not_in_map(): void
    {
        $inflector = new ClassMapInflector(
            new EmptyClassMapStub()
        );

        $inflector->typeToClassName('fake.event-name');
    }

    /** @test */
    public function can_convert_from_instance_To_Type(): void
    {
        $inflector = new ClassMapInflector(
            new ClassMapStub()
        );
        $instance = new DummyFooEventClass();

        self::assertEquals(
            'inflector.foo-class',
            $inflector->instanceToType($instance)
        );
    }

    /** @test */
    public function it_apply_default_inflector_strategy_to_normal_class(): void
    {
        $inflector = new ClassMapInflector(
            new ClassMapStub()
        );
        $instance = new DummyClass();

        self::assertEquals(
            'event_sauce_tools.tests.class_map_inflector.dummy_class',
            $inflector->instanceToType($instance)
        );
    }
}
