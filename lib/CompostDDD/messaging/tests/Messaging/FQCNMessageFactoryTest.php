<?php
/**
 * This file is part of the prooph/common.
 * (c) 2014-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CompostDDD\Tests\Messaging\Messaging;

use PHPUnit\Framework\TestCase;
use CompostDDD\Messaging\FQCNMessageFactory;
use CompostDDD\Tests\Messaging\Mock\DoSomething;
use CompostDDD\Tests\Messaging\Mock\InvalidMessage;
use Ramsey\Uuid\Uuid;

class FQCNMessageFactoryTest extends TestCase
{
    /**
     * @var FQCNMessageFactory
     */
    private $messageFactory;

    protected function setUp()
    {
        $this->messageFactory = new FQCNMessageFactory();
    }

    /**
     * @test
     */
    public function it_creates_a_new_message_from_array_and_fqcn(): void
    {
        $uuid = Uuid::uuid4();
        $createdAt = new \DateTimeImmutable();

        $command = $this->messageFactory->createMessageFromArray(DoSomething::class, [
            'uuid' => $uuid->toString(),
            'payload' => ['command' => 'payload'],
            'metadata' => ['command' => 'metadata'],
            'created_at' => $createdAt,
        ]);

        $this->assertEquals(DoSomething::class, $command->messageName());
        $this->assertEquals($uuid->toString(), $command->uuid()->toString());
        $this->assertEquals($createdAt, $command->createdAt());
        $this->assertEquals(['command' => 'payload'], $command->payload());
        $this->assertEquals(['command' => 'metadata'], $command->metadata());
    }

    /**
     * @test
     */
    public function it_creates_a_new_message_with_defaults_from_array_and_fqcn(): void
    {
        $command = $this->messageFactory->createMessageFromArray(DoSomething::class, [
            'payload' => ['command' => 'payload'],
        ]);

        $this->assertEquals(DoSomething::class, $command->messageName());
        $this->assertEquals(['command' => 'payload'], $command->payload());
        $this->assertEquals([], $command->metadata());
    }

    /**
     * @test
     */
    public function it_throws_exception_when_message_class_cannot_be_found(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        $this->messageFactory->createMessageFromArray('NotExistingClass', []);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_message_class_is_not_a_sub_class_domain_message(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        $this->messageFactory->createMessageFromArray(InvalidMessage::class, []);
    }
}
