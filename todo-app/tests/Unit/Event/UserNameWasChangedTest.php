<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Event;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\User\Event\UserNameWasChanged;
use TodoApp\Domain\Model\User\UserId;

class UserNameWasChangedTest extends TestCase
{
    /** @test */
    public function can_be_created_from_payload(): void
    {
        $userId = UserId::generate();
        $name = (string) 'irrelevant username';

        $event = UserNameWasChanged::fromPayload([
            'user_id' => $userId->toString(),
            'user_name' => $name,
        ]);

        self::assertInstanceOf(UserNameWasChanged::class, $event);
        self::assertTrue($userId->equals($event->userId()));
        self::assertEquals($name, $event->userName());
    }

    /** @test */
    public function it_return_payload_as_array(): void
    {
        $userId = UserId::generate();
        $name = (string) 'irrelevant username';
        $expectedPayload = [
            'user_id' => $userId->toString(),
            'user_name' => $name,
        ];

        $event = UserNameWasChanged::fromPayload([
            'user_id' => $userId->toString(),
            'user_name' => $name,
        ]);

        self::assertEquals($expectedPayload, $event->toPayload());
    }
}
