<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\User;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;
use Exception;
use LogicException;
use TodoApp\Domain\Model\User\User;
use TodoApp\Domain\Model\User\UserId;

abstract class UserTestCase extends AggregateRootTestCase
{
    /**
     * @var Exception|null
     */
    private $caughtException;

    protected function newAggregateRootId(): AggregateRootId
    {
        return UserId::generate();
    }

    protected function aggregateRootClassName(): string
    {
        return User::class;
    }

    protected function handle($command)
    {
//        $process = $this->repository->retrieve($command->processId());
//
//        if ($command instanceof InitiateSignUpProcess) {
//        }
//
//        $this->repository->persist($process);
    }

    protected function handleMethod(string $methodName, ...$arguments)
    {
        if ('register' === $methodName) {
            try {
                $user = call_user_func_array($this->aggregateRootClassName().'::register', $arguments);
            } finally {
                $this->repository->persist($user);
            }
        } else {
            $user = $this->repository->retrieve($arguments[0]); // var_dump($user);

            call_user_func_array([$user, $methodName], $arguments);
            $this->repository->persist($user);
        }
    }

    /**
     * @return $this
     */
    protected function whenMethod(string $methodName, ...$arguments)
    {
        try {
            if (!method_exists($this, 'handleMethod')) {
                throw new LogicException(sprintf('Class %s is missing a ::handle method.', get_class($this)));
            }

            $this->handleMethod($methodName, ...$arguments);
        } catch (Exception $exception) {
            $this->caughtException = $exception;
        }

        return $this;
    }

    /**
     * @return User
     */
    protected function retriveUserByid(UserId $userId): User
    {
        return $this->repository->retrieve($userId);
    }
}
