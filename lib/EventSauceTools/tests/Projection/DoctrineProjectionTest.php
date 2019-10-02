<?php

declare(strict_types=1);

namespace EventSauceTools\Tests\Projection;

use Doctrine\DBAL\DriverManager;
use EventSauceTools\Projection\DoctrineProjection;
use EventSauceTools\Projection\Projection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineProjectionTest extends KernelTestCase
{
    protected function setUp(): void
    {
    }

    /** @test */
    public function it_can_create(): void
    {
        $conn = DriverManager::getConnection([
            'dbname' => 'poc',
            'user' => 'poc_user',
            'password' => 'poc_password',
            'host' => '127.0.0.1',
            'driver' => 'pdo_mysql',
        ]);

        $projection = new DoctrineProjection(
            $conn,
            'table_name'
        );

        self::assertInstanceOf(Projection::class, $projection);
    }

    /** @test */
    public function event_map_should_be_empty_by_default(): void
    {
        $conn = DriverManager::getConnection([
            'dbname' => 'poc',
            'user' => 'poc_user',
            'password' => 'poc_password',
            'host' => '127.0.0.1',
            'driver' => 'pdo_mysql',
        ]);

        $projection = new DoctrineProjection(
            $conn,
            'table_name'
        );

        self::assertEquals(0, count($projection->supportTypes()));
    }

    protected function tearDown(): void
    {
    }
}
