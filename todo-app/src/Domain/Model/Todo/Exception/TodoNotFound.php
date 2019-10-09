<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo\Exception;

use TodoApp\Domain\Model\Todo\TodoId;

final class TodoNotFound extends \Exception
{
    private $todoId;

    public function __construct(TodoId $todoId, string $message = '', int $code = 0, \Exception $previous = null)
    {
        $this->todoId = $todoId;
        parent::__construct($message, $code, $previous);
    }

    public static function withTodoId(TodoId $todoId, int $code = 0, \Exception $previous = null): self
    {
        return new self($todoId, sprintf('Todo with id: %s not found.', $todoId), $code, $previous);
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }
}
