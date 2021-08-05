<?php

declare(strict_types=1);

namespace Xentral\Modules\SepaHbciFints\Service;

use DateTimeImmutable;
use DateTimeInterface;
use Digitick\Sepa\TransferFile\Facade\CustomerDirectDebitFacade;
use Digitick\Sepa\TransferFile\Factory\TransferFileFacadeFactory;
use Xentral\Modules\SepaHbciFints\Exception\InvalidArgumentException;

final class SepaDirectDebitXmlCreator
{
    /** @var CustomerDirectDebitFacade $directDebit */
    private $directDebit;
    /** @var string $paymentInfoId */
    private $paymentInfoId;

    public function __construct(
        DateTimeInterface $execDate,
        string $owner,
        string $iban,
        string $bic,
        string $paymentInformation,
        string $creditor
    ) {
        $creationTime = new DateTimeImmutable();
        $uniqueMessageIdentification = $bic . '00' . $creationTime->format('YmdHis');
        $this->paymentInfoId = 'PMT-ID0-' . $creationTime->format('YmdHis');

        $this->directDebit = TransferFileFacadeFactory::createDirectDebit(
            $uniqueMessageIdentification,
            $owner
        );

        $this->addPaymentInfo($execDate, $owner, $iban, $bic, $paymentInformation, $creditor);
    }

    /**
     * @param DateTimeInterface $execDate
     * @param string            $owner
     * @param string            $iban
     * @param string            $bic
     * @param string            $paymentInformation
     * @param string            $creditor
     *
     * @throws InvalidArgumentException
     */
    private function addPaymentInfo(
        DateTimeInterface $execDate,
        string $owner,
        string $iban,
        string $bic,
        string $paymentInformation,
        string $creditor
    ): void {
        try {
            $this->directDebit->addPaymentInfo(
                $this->paymentInfoId,
                [
                    'id'                  => $this->paymentInfoId,
                    'dueDate'             => $execDate->format('d.m.Y'),
                    'creditorName'        => $owner,
                    'creditorAccountIBAN' => $iban,
                    'creditorAgentBIC'    => $bic,
                    'seqType'             => $paymentInformation,
                    'creditorId'          => $creditor,
                ]
            );
        } catch (\Digitick\Sepa\Exception\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @param float             $amount
     * @param string            $debtorIban
     * @param string            $debtorBic
     * @param string            $debtorName
     * @param string            $debtorMandate
     * @param DateTimeInterface $debtorMandateSignDate
     * @param string            $remittanceInformation
     *
     * @throws InvalidArgumentException
     */
    public function addTransfer(
        float $amount,
        string $debtorIban,
        string $debtorBic,
        string $debtorName,
        string $debtorMandate,
        DateTimeInterface $debtorMandateSignDate,
        string $remittanceInformation
    ) {
        try {
            $this->directDebit->addTransfer(
                $this->paymentInfoId,
                [
                    'amount'                => (int)($amount * 100),
                    'debtorIban'            => $debtorIban,
                    'debtorBic'             => $debtorBic,
                    'debtorName'            => $debtorName,
                    'debtorMandate'         => $debtorMandate,
                    'debtorMandateSignDate' => $debtorMandateSignDate->format('d.m.Y'),
                    'remittanceInformation' => $remittanceInformation,
                ]
            );
        } catch (\Digitick\Sepa\Exception\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function asXML(): string
    {
        return $this->directDebit->asXML();
    }
}
