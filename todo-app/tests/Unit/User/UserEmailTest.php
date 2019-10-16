<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\User;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\User\UserEmail;

class UserEmailTest extends TestCase
{
    /** @test */
    public function can_be_create_from_string(): void
    {
        $email = UserEmail::fromString('irrelevant@example.com');
        $this->assertSame('irrelevant@example.com', $email->toString());
        $this->assertSame('irrelevant@example.com', $email->email());
    }

    /**
     * @test
     * @depends can_be_create_from_string
     */
    public function it_can_be_compared()
    {
        $first = UserEmail::fromString('irrelevant@example.com');
        $second = UserEmail::fromString('other-irrelevant@example.com');
        $copyOfFirst = UserEmail::fromString('irrelevant@example.com');

        $this->assertFalse($first->equals($second));
        $this->assertTrue($first->equals($copyOfFirst));
        $this->assertFalse($second->equals($copyOfFirst));
    }
}
