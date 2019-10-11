<?php

/**
 * This file is part of prooph/proophessor-do.
 * (c) 2014-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TodoApp\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TodoApp\Domain\Model\Todo\TodoReminder;
use TodoApp\Domain\Model\Todo\TodoReminderStatus;

class TodoReminderTest extends TestCase
{
    /**
     * @test
     * @dataProvider getReminders
     */
    public function it_correctly_validates_the_reminder(string $reminder, bool $inThePast): void
    {
        $reminder = TodoReminder::from($reminder, TodoReminderStatus::OPEN()->name());

        if ($inThePast) {
            $this->assertTrue($reminder->isInThePast());
            $this->assertFalse($reminder->isInTheFuture());
        } else {
            $this->assertFalse($reminder->isInThePast());
            $this->assertTrue($reminder->isInTheFuture());
        }
    }

    public function getReminders(): array
    {
        return [
            ['2047-02-01 10:00:00', false],
            ['1947-01-01 10:00:00', true],
        ];
    }

    /**
     * @test
     */
    public function it_knows_about_its_status(): void
    {
        $reminder = TodoReminder::from('2047-02-01 10:00:00', TodoReminderStatus::OPEN()->name());
        $this->assertTrue($reminder->isOpen());

        $reminder = TodoReminder::from('2047-02-01 10:00:00', TodoReminderStatus::CLOSED()->name());
        $this->assertFalse($reminder->isOpen());
    }

    /**
     * @test
     */
    public function it_returns_a_new_reminder_with_status_closed_when_closed(): void
    {
        $reminder = TodoReminder::from('2047-02-01 10:00:00', TodoReminderStatus::OPEN()->name());
        $reminderClosed = $reminder->close();

        $this->assertNotEquals($reminder, $reminderClosed);
        $this->assertFalse($reminderClosed->isOpen());
    }
}
