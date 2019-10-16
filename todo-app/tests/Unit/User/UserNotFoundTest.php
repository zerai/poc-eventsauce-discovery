<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\User;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\User\Exception\UserNotFound;
use TodoApp\Domain\Model\User\UserId;

class UserNotFoundTest extends TestCase
{
    private const UUID = '51a06930-3191-4a67-bf59-40b200655269';

    /**
     * @test
     * @expectedException  \TodoApp\Domain\Model\User\Exception\UserNotFound
     * @expectedExceptionMessage User with id: 51a06930-3191-4a67-bf59-40b200655269 not found.
     */
    public function can_be_created_with_user_id(): void
    {
        $userId = UserId::fromString(self::UUID);

        throw $e = UserNotFound::withUserId($userId);
        //self::assertEquals($userId, $e->userId());
    }
}
