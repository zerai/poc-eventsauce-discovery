<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootId;
use Generator;
use TodoApp\Domain\Model\User\Exception\UserNotFound;

trait UserAggregateRootBehaviourWithRequiredHistory
{
    use AggregateRootBehaviour {
        AggregateRootBehaviour::reconstituteFromEvents as private defaultAggregateRootReconstitute;
    }

    /**
     * @param AggregateRootId $aggregateRootId
     * @param Generator       $events
     *
     * @return AggregateRoot
     *
     * @throws UserNotFound
     */
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot
    {
        $aggregateRoot = static::defaultAggregateRootReconstitute($aggregateRootId, $events);

        if (0 === $aggregateRoot->aggregateRootVersion()) {
            throw UserNotFound::withUserId($aggregateRootId);
        }

        return $aggregateRoot;
    }
}
