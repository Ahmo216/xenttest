<?php

declare(strict_types=1);

namespace Xentral\Components\Logger;

/**
 * @deprecated Use constructor injection to get the logger
 */
trait LoggerAwareTrait
{
    /** @var LoggerInterface $logger */
    protected $logger;

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
