<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\TodoDeadline;

class TodoDeadlineTest extends TestCase
{
    /**
     * @test
     * @dataProvider getDeadlines
     */
    public function it_correctly_validates_the_deadline($deadline, $inThePast): void
    {
        $deadline = TodoDeadline::fromString($deadline);
        $deadlineInThePast = $deadline->isInThePast();
        if ($inThePast) {
            $this->assertTrue($deadlineInThePast);
        } else {
            $this->assertFalse($deadlineInThePast);
        }
    }

    public function getDeadlines(): array
    {
        return [
            [
                '2047-02-01 10:00:00',
                false,
            ],
            [
                '1947-01-01 10:00:00',
                true,
            ],
        ];
    }
}
