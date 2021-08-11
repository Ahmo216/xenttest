<?php

declare(strict_types=1);

namespace Xentral\Modules\DatevApi\Data;

final class ValidationResultErrorData
{
    public const DATEX_XML_ERROR = 'error';

    public const DATEV_XML_WARNING = 'warning';

    public const DATEV_XML_FATAL = 'fatal';

    /** @var string $level */
    private $level;

    /** @var int $code */
    private $code;

    /** @var string $message */
    private $message;

    /** @var int $line */
    private $line;

    /**
     * @param string $level
     * @param int    $code
     * @param string $message
     * @param int    $line
     */
    public function __construct(string $level, int $code, string $message, int $line)
    {
        $this->level = $level;
        $this->code = $code;
        $this->message = $message;
        $this->line = $line;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }
}
