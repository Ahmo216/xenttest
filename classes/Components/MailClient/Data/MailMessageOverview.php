<?php

declare(strict_types=1);

namespace Xentral\Components\MailClient\Data;

class MailMessageOverview
{
    /** @var MailMessageHeaderCollection $headers */
    private $headers;

    /** @var int $temporaryId */
    private $temporaryId;

    /** @var string $folder */
    private $folder;

    /**
     * @param int                              $temporaryId
     * @param MailMessageHeaderCollection|null $headers
     * @param string|null                      $folder
     */
    public function __construct(
        int $temporaryId = 0,
        ?string $folder = null,
        ?MailMessageHeaderCollection $headers = null
    ) {
        $this->temporaryId = $temporaryId;
        $this->headers = $headers ?? new MailMessageHeaderCollection();
        $this->folder = $folder;
    }

    /**
     * @return MailMessageHeaderCollection
     */
    public function getHeaders(): MailMessageHeaderCollection
    {
        return $this->headers;
    }

    /**
     * @return int temporary id in current session
     */
    public function getTemporaryId(): int
    {
        return $this->temporaryId;
    }

    /**
     * @return string|null
     */
    public function getFolder(): ?string
    {
        return $this->folder;
    }
}
