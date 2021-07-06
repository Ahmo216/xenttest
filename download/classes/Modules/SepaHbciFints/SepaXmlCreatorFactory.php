<?php

declare(strict_types=1);

namespace Xentral\Modules\SepaHbciFints;

use DateTimeInterface;
use Xentral\Modules\SepaHbciFints\Service\SepaDirectDebitXmlCreator;

final class SepaXmlCreatorFactory
{
    public function __construct()
    {
    }

    /**
     * @param DateTimeInterface $execDate
     * @param string            $owner
     * @param string            $iban
     * @param string            $bic
     * @param string            $paymentInformation
     * @param string            $creditor
     *
     * @return SepaDirectDebitXmlCreator
     */
    public function createDirectDebitXmlCreator(
        DateTimeInterface $execDate,
        string $owner,
        string $iban,
        string $bic,
        string $paymentInformation,
        string $creditor
    ): SepaDirectDebitXmlCreator {
        return new SepaDirectDebitXmlCreator($execDate, $owner, $iban, $bic, $paymentInformation, $creditor);
    }
}
