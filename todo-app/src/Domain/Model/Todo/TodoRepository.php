<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use TodoApp\Domain\Model\Todo\Exception\TodoAlreadyExist;
use TodoApp\Domain\Model\Todo\Exception\TodoNotFound;

interface TodoRepository
{
    /**
     * @param Todo $todo
     *
     * @throws TodoAlreadyExist
     */
    public function addNewTodo(Todo $todo): void;

    /**
     * @param Todo $todo
     *
     * @throws TodoNotFound
     */
    public function save(Todo $todo): void;

    /**
     * @param TodoId $todoId
     *
     * @return Todo|null
     *
     * @throws TodoNotFound
     */
    public function ofId(TodoId $todoId): ?Todo;
}
