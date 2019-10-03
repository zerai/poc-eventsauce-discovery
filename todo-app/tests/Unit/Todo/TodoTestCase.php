<?php

declare(strict_types=1);

namespace TodoApp\Tests\Unit\Todo;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;
use Exception;
use LogicException;
use TodoApp\Domain\Model\Todo\Todo;
use TodoApp\Domain\Model\Todo\TodoId;

abstract class TodoTestCase extends AggregateRootTestCase
{
    /**
     * @var Exception|null
     */
    private $caughtException;

    protected function newAggregateRootId(): AggregateRootId
    {
        return TodoId::generate();
    }

    protected function aggregateRootClassName(): string
    {
        return Todo::class;
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
        if ('post' === $methodName) {
            try {
                $todo = call_user_func_array($this->aggregateRootClassName().'::post', $arguments);
            } finally {
                $this->repository->persist($todo);
            }
        }

        if ('markAsDone' === $methodName) {
            $todo = $this->repository->retrieve($this->aggregateRootId());
            call_user_func_array([$todo, $methodName], $arguments);
            $this->repository->persist($todo);
        }

//        //else {
//            $todo = $this->repository->retrieve($arguments[0]);
//
//            call_user_func_array([$todo, $methodName], $arguments);
//            $this->repository->persist($todo);
//        //}
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
     * @return Todo
     */
    protected function retriveTodoByid(TodoId $todoId): Todo
    {
        return $this->repository->retrieve($todoId);
    }
}
