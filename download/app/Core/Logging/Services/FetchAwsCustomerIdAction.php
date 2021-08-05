<?php

declare(strict_types=1);

namespace App\Core\Logging\Services;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;

class FetchAwsCustomerIdAction
{
    private const CUSTOMER_ID_PATH = 'customer_id.txt';

    /** @var Filesystem */
    private $filesystem;

    public function __construct(Factory $filesystemFactory)
    {
        $this->filesystem = $filesystemFactory->disk('root');
    }

    public function __invoke(): ?string
    {
        if (!$this->filesystem->exists(self::CUSTOMER_ID_PATH)) {
            return null;
        }

        $customerId = trim($this->filesystem->get(self::CUSTOMER_ID_PATH));
        if (empty($customerId)) {
            return null;
        }

        return $customerId;
    }
}
