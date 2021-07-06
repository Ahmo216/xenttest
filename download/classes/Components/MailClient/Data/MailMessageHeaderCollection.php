<?php

declare(strict_types=1);

namespace Xentral\Components\MailClient\Data;

use Xentral\Components\MailClient\Exception\InvalidArgumentException;

class MailMessageHeaderCollection
{
    /** @var MailMessageHeaderInterface[] */
    private $headers;

    /**
     * @param array $headers
     */
    public function __construct(array $headers = [])
    {
        foreach ($headers as $header) {
            $this->add($header);
        }
    }

    /**
     * @param string $headerName
     *
     * @return MailMessageHeaderInterface|null
     */
    public function get(string $headerName): ?MailMessageHeaderInterface
    {
        return $this->has($headerName)
            ? $this->headers[mb_strtolower($headerName)]
            : null;
    }

    /**
     * @param string $headerName
     *
     * @return bool
     */
    public function has(string $headerName): bool
    {
        return isset($this->headers[mb_strtolower($headerName)]);
    }

    /**
     * @return MailMessageHeaderInterface[]
     */
    public function getAll(): array
    {
        return array_values($this->headers);
    }

    /**
     * @param MailMessageHeaderInterface $header
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    private function add(MailMessageHeaderInterface $header): void
    {
        $this->headers[mb_strtolower($header->getName())] = $header;
    }
}
