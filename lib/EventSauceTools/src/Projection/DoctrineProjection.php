<?php

declare(strict_types=1);

namespace EventSauceTools\Projection;

use Doctrine\DBAL\Connection;

class DoctrineProjection extends AbstractProjection
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * DoctrineProjection constructor.
     *
     * @param Connection $connection
     * @param string     $tableName
     */
    public function __construct(Connection $connection, string $tableName)
    {
        $this->connection = $connection;
        $this->tableName = $tableName;
    }
}
