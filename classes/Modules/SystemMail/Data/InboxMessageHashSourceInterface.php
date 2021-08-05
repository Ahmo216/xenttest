<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

/**
 * Represents the values used for generating a checksum of an email message.
 *
 * This checksum will be used to check whether an email has been imported
 * from remote email server to system.
 */
interface InboxMessageHashSourceInterface
{
    /**
     * Get the email address of the person who sent the email.
     *
     * @return string
     */
    public function getSenderEmailAddress(): string;

    /**
     * Get unix timestamp of the time when email message was received.
     *
     * Make sure to transform email date to unix timestamp:
     *
     *     17-Dec-2020 12:18:51 +0100 -> 1608203931
     *
     * @return int Unit timestamp
     */
    public function getTimestamp(): int;

    /**
     * Get subject from email or email header.
     *
     * @return string
     */
    public function getSubject(): string;
}
