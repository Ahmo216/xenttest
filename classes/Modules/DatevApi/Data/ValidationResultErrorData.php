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

    /**
     * @param string $level
     * @param int    $code
     * @param string $message
     */
    public function __construct(string $level, int $code, string $message)
    {
        $this->level = $level;
        $this->code = $code;
        $this->message = $message;
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
}
