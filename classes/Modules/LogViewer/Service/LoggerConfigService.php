<?php

declare(strict_types=1);

namespace Xentral\Modules\LogViewer\Service;

use Xentral\Components\Logger\LogLevel;
use Xentral\Modules\LogViewer\Exception\InvalidArgumentException;
use Xentral\Modules\LogViewer\Exception\InvalidLoglevelException;
use Xentral\Modules\LogViewer\Wrapper\CompanyConfigWrapper;

final class LoggerConfigService
{
    /** @var string CONFIG_KEY_LEVEL */
    private const CONFIG_KEY_LEVEL = 'logfile_logging_level';

    /** @var CompanyConfigWrapper $db */
    private $companyConfig;

    /**
     * @param CompanyConfigWrapper $companyConfig
     */
    public function __construct(CompanyConfigWrapper $companyConfig)
    {
        $this->companyConfig = $companyConfig;
    }

    /**
     * @throws InvalidLoglevelException
     *
     * @return string
     */
    public function getLogLevel(): string
    {
        $level = (string)$this->companyConfig->get(self::CONFIG_KEY_LEVEL);
        $level = strtolower($level);
        if (!$this->isAllowedLogLevel($level)) {
            throw new InvalidLoglevelException(sprintf('Unrecognized Loglevel "%s".', $level));
        }

        return $level;
    }

    /**
     * @param string $level
     *
     * @throws InvalidLoglevelException
     *
     * @return void
     */
    public function setLogLevel(string $level): void
    {
        if (!$this->isAllowedLogLevel($level)) {
            throw new InvalidArgumentException(sprintf('Unrecognised Loglevel "%s"',  $level));
        }
        $this->companyConfig->set(self::CONFIG_KEY_LEVEL, $level);
    }

    /**
     * @param string $level
     *
     * @return bool
     */
    public function isAllowedLogLevel(string $level): bool
    {
        return (
            $level === LogLevel::DEBUG
            || $level === LogLevel::INFO
            || $level === LogLevel::NOTICE
            || $level === LogLevel::WARNING
            || $level === LogLevel::ERROR
            || $level === LogLevel::CRITICAL
            || $level === LogLevel::ALERT
            || $level === LogLevel::EMERGENCY
        );
    }
}
