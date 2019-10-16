<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\User;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\User\UserName;

class UserNameTest extends TestCase
{
    /** @test */
    public function userName_can_be_create_from_string(): void
    {
        $userName = UserName::fromString('j.Doe');
        $this->assertSame('j.Doe', $userName->toString());
        $this->assertSame('j.Doe', $userName->userName());
    }

    /**
     * @test
     * @depends userName_can_be_create_from_string
     */
    public function userName_can_be_compared()
    {
        $first = UserName::fromString('j.Doe');
        $second = UserName::fromString('other-j.Doe');
        $copyOfFirst = UserName::fromString('j.Doe');

        $this->assertFalse($first->equals($second));
        $this->assertTrue($first->equals($copyOfFirst));
        $this->assertFalse($second->equals($copyOfFirst));
    }
}
