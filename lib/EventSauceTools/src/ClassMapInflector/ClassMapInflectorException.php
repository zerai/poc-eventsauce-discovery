<?php

declare(strict_types=1);

namespace EventSauceTools\ClassMapInflector;

final class ClassMapInflectorException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withClassName(string $className, int $code = 0, \Exception $previous = null): self
    {
        return new self(sprintf('Class name %s is not mapped to any event.', $className), $code, $previous);
    }

    public static function withEventName(string $eventName, int $code = 0, \Exception $previous = null): self
    {
        return new self(sprintf('Message type %s is not mapped to any class.', $eventName), $code, $previous);
    }
}
