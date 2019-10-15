<?php

declare(strict_types=1);

namespace EventSauceTools\ClassMapInflector;

interface ClassMap
{
    const CLASS_MAP = [];

    public function supportClass(): array;
}
