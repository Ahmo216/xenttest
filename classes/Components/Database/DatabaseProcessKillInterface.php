<?php

declare(strict_types=1);

namespace Xentral\Components\Database;

interface DatabaseProcessKillInterface
{
    public function kill(int $processId): void;
}
