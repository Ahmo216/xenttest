<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail\Data;

interface InboxMessageAttachmentInterface
{
    /**
     * Returns the name of the attachment.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the absolute path ot the attachment in the userdata directory.
     *
     * @return string|null
     */
    public function getFilePath(): ?string;

    /**
     * Returns the database id of the attachments entry in the `datei` table.
     *
     * @return int
     */
    public function getFileDataId(): int;
}
