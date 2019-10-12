<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\Projection;

use Doctrine\DBAL\Connection;
use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use TodoApp\Domain\Model\Todo\Event\TodoWasMarkedAsDone;
use TodoApp\Domain\Model\Todo\Event\TodoWasPosted;
use TodoApp\Domain\Model\Todo\Projection\TodoProjection as TodoProjectionPort;
use TodoApp\Infrastructure\Projection\Common\AbstractProjection;

class TodoProjection extends AbstractProjection implements TodoProjectionPort, Consumer
{
    private const TABLE_NAME = 'todo_projection';

    protected $connection;

    /**
     * TodoProjection constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(Message $message)
    {
        if ($this->isProjectable($message)) {
            $this->project($message);
        }
    }

    public function isProjectable(Message $message): bool
    {
        if (!in_array(get_class($message->event()), $this::PROJECTABLE_EVENTS)) {
            return false;
        }

        return true;
    }

    public function projectWhenTodoWasPosted(TodoWasPosted $event): void
    {
        $data = $event->toPayload();
        $this->insert($data);
    }

    public function projectWhenTodoWasMarkedAsDone(TodoWasMarkedAsDone $event): void
    {
        $identifier = ['todo_id' => $event->todoId()->toString()];

        $data = ['status' => $event->newStatus()->toString()];

        $this->update($data, $identifier);
    }

    public function isInitialized(): bool
    {
        $tableName = $this::TABLE_NAME;

        $sql = "SHOW TABLES LIKE '$tableName';";

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $result = $statement->fetch();

        if (false === $result) {
            return false;
        }

        return true;
    }

    public function reset(): void
    {
        $tableName = $this::TABLE_NAME;

        $sql = "TRUNCATE TABLE '$tableName';";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    public function delete(): void
    {
        $tableName = $this::TABLE_NAME;

        $sql = "DROP TABLE $tableName;";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    protected function insert(array $data): void
    {
        $this->connection->beginTransaction();

        $this->connection->insert($this::TABLE_NAME, $data);

        $this->connection->commit();
    }

    protected function update(array $data, array $identifier): void
    {
        $this->connection->update(
            $this::TABLE_NAME,
            $data,
            $identifier
        );
    }
}
