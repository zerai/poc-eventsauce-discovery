<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\Todo;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootId;
use Generator;
use TodoApp\Domain\Model\Todo\Exception\TodoNotFound;

trait TodoAggregateRootBehaviourWithRequiredHistory
{
    use AggregateRootBehaviour {
        AggregateRootBehaviour::reconstituteFromEvents as private defaultAggregateRootReconstitute;
    }

    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot
    {
        $aggregateRoot = static::defaultAggregateRootReconstitute($aggregateRootId, $events);

        if (0 === $aggregateRoot->aggregateRootVersion()) {
            //throw new InvalidAggregateRootReconstitutionException();
            throw TodoNotFound::withTodoId($aggregateRootId);
        }

        return $aggregateRoot;
    }
}
