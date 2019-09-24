<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\EventSauce\ClassNameInflector;

interface NameMap
{
    public function supportTypes(): array;
}
