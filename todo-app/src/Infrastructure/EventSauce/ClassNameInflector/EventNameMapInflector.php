<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce\ClassNameInflector;

use EventSauce\EventSourcing\ClassNameInflector;
use EventSauce\EventSourcing\Serialization\SerializablePayload;
use function get_class;

class EventNameMapInflector implements ClassNameInflector
{
    /** @var NameMap */
    private $map;

    /**
     * EventNameMapInflector constructor.
     *
     * @param NameMap $map
     */
    public function __construct(NameMap $map)
    {
        $this->map = $map;
    }

    public function classNameToType(string $className): string
    {
        // detect if event class or other type (AGGREGATE_ROOT_ID)
        if (!is_subclass_of($className, SerializablePayload::class)) {
            // default for other class - same as DotSeparatedSnakeCaseInflector
            return str_replace('\\_', '.', strtolower((string) preg_replace('/(.)(?=[A-Z])/u', '$1_', $className)));
        }

        // check event in map
        if (!array_key_exists($className, $this->map->supportTypes())) {
            throw new \UnexpectedValueException('Class name '.$className.' is not mapped to any event.');
        }

        return $this->map->supportTypes()[$className];
    }

    public function typeToClassName(string $eventName): string
    {
        if (!$className = array_search($eventName, $this->map->supportTypes())) {
            throw new \UnexpectedValueException('Message type '.$eventName.' is not mapped to any class.');
        }

        return $className;
    }

    public function instanceToType(object $instance): string
    {
        return $this->classNameToType(get_class($instance));
    }
}
