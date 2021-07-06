<?php

declare(strict_types=1);

namespace Xentral\Components\Database;


use Xentral\Components\Database\Data\ProcessListQueryCollection;

interface DatabaseProcessListInterface
{
    public function getProcessList(): ProcessListQueryCollection;
}
