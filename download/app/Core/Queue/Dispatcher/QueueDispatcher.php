<?php

declare(strict_types=1);

namespace App\Core\Queue\Dispatcher;

use App\Core\Queue\Exception\InvalidJobException;
use App\Core\Queue\Job\JobInterface;
use Illuminate\Bus\Dispatcher as BaseDispatcher;

/**
 * This class is used to dispatch new Jobs to the queue.
 * Use this as a facade for the framework's Dispatcher.
 */
class QueueDispatcher extends BaseDispatcher
{
    /**
     * @param JobInterface $command
     *
     * @throws InvalidJobException
     *
     * @return mixed
     */
    public function dispatch($command)
    {
        $this->validateJob($command);

        return parent::dispatch($command);
    }

    /**
     * Check if the dispatched job is valid.
     *
     * The job class MUST implements the `JobInterface`.
     * The job class MUST contain a method `handle`.
     *
     * @param mixed $job
     *
     * @throws InvalidJobException
     */
    protected function validateJob($job): void
    {
        if (
            (gettype($job) !== 'object')
            || !($job instanceof JobInterface)
        ) {
            throw new InvalidJobException(sprintf(
                'invalid job: expected %1, %2 provided',
                JobInterface::class,
                gettype($job)
            ));
        }

        if (!method_exists($job, 'handle')) {
            $class = get_class($job);
            throw new InvalidJobException("Invalid job {$class} , method \"handle\" is not implemented.");
        }
    }
}
