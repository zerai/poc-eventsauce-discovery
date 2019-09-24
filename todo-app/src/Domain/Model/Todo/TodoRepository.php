<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

interface TodoRepository
{
    public function store(Todo $todo): void;

    public function ofId(TodoId $userId): Todo;
}
