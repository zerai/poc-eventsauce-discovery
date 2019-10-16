<?php

declare(strict_types=1);

namespace TodoApp\Domain\Model\User\Exception;

use TodoApp\Domain\Model\User\UserId;

final class UserAlreadyExist extends \Exception
{
    private $userId;

    public function __construct(UserId $userId, string $message = '', int $code = 0, \Exception $previous = null)
    {
        $this->userId = $userId;
        parent::__construct($message, $code, $previous);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public static function withUserId(UserId $userId, int $code = 0, \Exception $previous = null): self
    {
        return new self($userId, sprintf('User with id: %s already exist.', $userId), $code, $previous);
    }
}
