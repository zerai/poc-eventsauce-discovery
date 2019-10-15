<?php

declare(strict_types=1);

namespace EventSauceTools\ClassMapInflector;

use EventSauce\EventSourcing\ClassNameInflector;
use EventSauce\EventSourcing\Serialization\SerializablePayload;
use function get_class;

class ClassMapInflector implements ClassNameInflector
{
    /** @var ClassMap */
    private $map;

    /**
     * @param ClassMap $map
     */
    public function __construct(ClassMap $map)
    {
        $this->map = $map;
    }

    /**
     * @param string $className
     *
     * @return string
     *
     * @throws ClassMapInflectorException
     */
    public function classNameToType(string $className): string
    {
        // detect if it's event class or other type (AGGREGATE_ROOT_ID_TYPE)
        if (!is_subclass_of($className, SerializablePayload::class)) {
            // default for other class - same as DotSeparatedSnakeCaseInflector
            return str_replace('\\_', '.', strtolower((string) preg_replace('/(.)(?=[A-Z])/u', '$1_', $className)));
        }

        // check class name in map
        if (!array_key_exists($className, $this->map->supportClass())) {
            throw ClassMapInflectorException::withClassName($className);
        }

        return $this->map->supportClass()[$className];
    }

    /**
     * @param string $eventName
     *
     * @return string
     *
     * @throws ClassMapInflectorException
     */
    public function typeToClassName(string $eventName): string
    {
        // detect if it's event class or other type (AGGREGATE_ROOT_ID_TYPE)
        if (class_exists($className = str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('.', '\\ ', $eventName)))))) {
            return $className;
        }

        if (!$className = array_search($eventName, $this->map->supportClass())) {
            throw ClassMapInflectorException::withEventName($eventName);
        }

        return $className;
    }

    public function instanceToType(object $instance): string
    {
        return $this->classNameToType(get_class($instance));
    }
}
