<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User\Projection;

use TodoApp\Domain\Model\User\Event\UserNameWasChanged;
use TodoApp\Domain\Model\User\Event\UserWasRegistered;

interface UserProjection
{
    const PROJECTABLE_EVENTS = [
        UserWasRegistered::class,
        UserNameWasChanged::class,
    ];

    public function projectWhenUserWasRegistered(UserWasRegistered $event): void;

    public function projectWhenUserNameWasChanged(UserNameWasChanged $event): void;
}
