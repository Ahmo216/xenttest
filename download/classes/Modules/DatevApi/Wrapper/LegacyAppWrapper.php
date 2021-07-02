<?php

declare(strict_types=1);

namespace Xentral\Modules\DatevApi\Wrapper;

use erpAPI;

class LegacyAppWrapper
{
    /** @var erpAPI $erp */
    private $erp;

    /**
     * @param erpAPI $erp
     */
    public function __construct(erpAPI $erp)
    {
        $this->erp = $erp;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    public function readyForPdf(string $content): string
    {
        return $this->erp->ReadyForPDF($content);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function findCompanyData(string $name): ?string
    {
        return $this->erp->Firmendaten($name);
    }
}
