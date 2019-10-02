<?php

declare(strict_types=1);

namespace TodoApp\Infrastructure\Projection;

use Doctrine\DBAL\Connection;
use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use TodoApp\Domain\Model\User\Event\UserNameWasChanged;
use TodoApp\Domain\Model\User\Event\UserWasRegistered;
use TodoApp\Domain\Model\User\Projection\UserProjection as UserProjectionPort;
use TodoApp\Infrastructure\Projection\Common\AbstractProjection;

class UserProjection extends AbstractProjection implements UserProjectionPort, Consumer
{
    private const TABLE_NAME = 'user_projection';

    protected $connection;

    /**
     * UserProjection constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(Message $message)
    {
        if ($this->isProjectabile($message)) {
            $this->project($message);
        }
    }

    public function isProjectabile(Message $message): bool
    {
        if (!in_array(get_class($message->event()), $this::PROJECTABLE_EVENTS)) {
            return false;
        }

        return true;
    }

    public function projectWhenUserWasRegistered(UserWasRegistered $event): void
    {
        $tableName = $this::TABLE_NAME;
        $sql = "INSERT INTO $tableName (`user_id`, `user_name`, `email`)
                VALUES (:user_id, :user_name, :email);"
        ;

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':user_id' => (string) $event->userId()->toString(),
            ':user_name' => $event->username(),
            ':email' => $event->email(),
        ]);

        //$data = $event->toPayload();
        //$this->insert($data);
    }

    public function projectWhenUserNameWasChanged(UserNameWasChanged $event): void
    {
        $identifier = ['user_id' => $event->userId()->toString()];

        $data = $event->toPayload();

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
