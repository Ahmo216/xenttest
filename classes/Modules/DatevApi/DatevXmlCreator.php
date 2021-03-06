<?php

declare(strict_types=1);

namespace Xentral\Modules\DatevApi;

use Buchhaltungexport;
use DateTimeInterface;
use Xentral\Modules\DatevApi\Data\DocumentData;
use Xentral\Modules\DatevApi\Exception\NoDocumentsFoundException;
use Xentral\Modules\DatevApi\Service\AccountingSettingsGateway;
use Xentral\Modules\DatevApi\Service\DocumentGateway;
use Xentral\Modules\DatevApi\Wrapper\LegacyAppWrapper;
use Xentral\Modules\SystemConfig\SystemConfigModule;

final class DatevXmlCreator
{
    public const DOCUMENT_TYPE_INVOICE = 'rechnung';

    public const DOCUMENT_TYPE_CREDITNOTE = 'gutschrift';

    public const DOCUMENT_TYPE_LIABILITY = 'verbindlichkeit';

    private const FALLBACK_NOMINAL_ACCOUNT_LENGTH = 4;

    /** @var DocumentGateway $documentGateway */
    private $documentGateway;

    /** @var AccountingSettingsGateway $accountingSettingsGateway */
    private $accountingSettingsGateway;

    /** @var LegacyAppWrapper $legacyWrapper */
    private $legacyWrapper;

    /** @var SystemConfigModule $systemConfig */
    private $systemConfig;

    /** @var Buchhaltungexport $accountingExportModule */
    private $accountingExportModule;

    /** @var string $datevVersionPath */
    private $datevVersionPath = 'v05.0';

    /** @var string $datevDocumentExportVersionPath */
    private $datevDocumentExportVersionPath = 'v050';

    /** @var string $datevVersionNumber */
    private $datevVersionNumber = '5.0';

    /**
     * @param DocumentGateway           $documentGateway
     * @param AccountingSettingsGateway $accountingSettingsGateway
     * @param LegacyAppWrapper          $legacyWrapper
     * @param SystemConfigModule        $systemConfig
     * @param Buchhaltungexport         $accountingExportModule
     */
    public function __construct(
        DocumentGateway $documentGateway,
        AccountingSettingsGateway $accountingSettingsGateway,
        LegacyAppWrapper $legacyWrapper,
        SystemConfigModule $systemConfig,
        Buchhaltungexport $accountingExportModule
    ) {
        $this->documentGateway = $documentGateway;
        $this->accountingSettingsGateway = $accountingSettingsGateway;
        $this->legacyWrapper = $legacyWrapper;
        $this->systemConfig = $systemConfig;
        $this->accountingExportModule = $accountingExportModule;
    }

    /**
     * @param string            $documentType
     * @param DateTimeInterface $from
     * @param DateTimeInterface $till
     * @param int               $projectId
     *
     * @return array
     */
    public function createXmlContents(
        string $documentType,
        DateTimeInterface $from,
        DateTimeInterface $till,
        int $projectId = 0
    ): array {
        $xmlFileContents = [];
        $documentType = $this->ensureDocumentType($documentType);
        $isZeroEuroAllowed = (bool)$this->systemConfig->tryGetLegacyValue('buchaltungexport_list_nulleurorechnungen');
        $datevLiabilityInvoiceDate =
            (int)$this->systemConfig->tryGetLegacyValue('buchaltungexport_list_datevverbindlichkeitrechnungsdatum');
        $documentInfos = $this->getDocumentInfos(
            $documentType,
            $from,
            $till,
            $isZeroEuroAllowed,
            $datevLiabilityInvoiceDate,
            $projectId
        );
        $nominalAccountLength = $this->getNominalAccountLength($projectId);

        if (empty($documentInfos)) {
            throw new NoDocumentsFoundException(
                'No documents were found for the selected period.'
            );
        }
        $documentInfos = $this->formatDocumentInfosForXmlExport($documentType, $documentInfos);
        $datevAppendInternet = $this->systemConfig->tryGetLegacyValue('datev_append_internet') == '1';

        $xmlstringdoc = $this->getXmlHead($documentType);

        foreach ($documentInfos as $documentInfo) {
            $xmlFileContents[] = $this->getXmlFileContentByDocument(
                $documentType,
                $documentInfo,
                $datevAppendInternet,
                $nominalAccountLength
            );
            $xmlstringdoc .= $this->getDocumentXmlString($documentType, $documentInfo);
        }
        $xmlstringdoc .= '  </content>
        </archive>';

        $xmlFileContents[] = [
            'name' => 'document.xml',
            'content' => $xmlstringdoc,
        ];

        return $xmlFileContents;
    }

    /**
     * @param string $documentType
     * @param array  $documentInfos
     *
     * @return array
     */
    public function formatDocumentInfosForXmlExport(string $documentType, array $documentInfos): array
    {
        $debitMultiplierFactor = $documentType === self::DOCUMENT_TYPE_INVOICE ? 1 : -1;
        foreach ($documentInfos as $documentInfo) {
            $documentInfo->setDocumentNumber($this->getValidDocumentNumberFromInfo($documentInfo));
            $debitFormatatted = number_format($documentInfo->getDebit() * $debitMultiplierFactor, 2, '.', '');
            $documentInfo->setDebit((float)$debitFormatatted);
        }

        return $documentInfos;
    }

    /**
     * @param string       $documentType
     * @param DocumentData $documentInfo
     * @param bool         $datevAppendInternet
     * @param int          $nominalAccountLength
     *
     * @return array
     */
    public function getXmlFileContentByDocument(
        string $documentType,
        DocumentData $documentInfo,
        bool $datevAppendInternet,
        int $nominalAccountLength
    ): array {
        $xmldescription2 = $this->getXmlDocumentTypeDescription($documentType);
        $bookings = $this->getBookingsFromDocumentInfo($documentType, $documentInfo);
        $xmlString = '';
        $totalAmount = 0;

        foreach ($bookings as $booking) {
            if ($booking['betrag'] == 0) {
                continue;
            }
            $valueFactor = ($booking['haben'] == '1' && $documentType === self::DOCUMENT_TYPE_INVOICE)
            || ($booking['haben'] == '0' && $documentType !== self::DOCUMENT_TYPE_INVOICE
            ) ? -1 : 1;
            $amount = $valueFactor * $booking['betrag'];
            $xmlString = $this->addBookingInfoToXml(
                $documentType,
                $documentInfo,
                $booking,
                $amount,
                $nominalAccountLength
            );
            $totalAmount += $amount;
        }

        if ($totalAmount == 0 && $documentInfo->getDebit() <> 0) {
            $totalAmount = $documentInfo->getDebit();
            if ($documentType === self::DOCUMENT_TYPE_LIABILITY) {
                $totalAmount *= -1;
            }
        }

        $xmlstringtop = $this->getDocumentXmlHead($documentInfo, $datevAppendInternet, $totalAmount);
        $xmlString = $xmlstringtop . $xmlString;
        $xmlString .= "</consolidate>\r\n";
        $xmlString .= '</LedgerImport>';

        return [
            'name'          => $xmldescription2 . '_' . $documentInfo->getDocumentNumber(),
            'document_id'   => $documentInfo->getDocumentId(),
            'document_type' => $documentType,
            'content'       => $xmlString,
        ];
    }

    /**
     * @param string       $documentType
     * @param DocumentData $documentInfo
     *
     * @return string
     */
    public function getDocumentXmlString(string $documentType, DocumentData $documentInfo): string
    {
        $xmlDescription1 = $this->getXmlAccountDescription($documentType);
        $xmlDescription2 = $this->getXmlDocumentTypeDescription($documentType);

        return '
              <document>
              <extension xsi:type="' . $xmlDescription1 . '" datafile="' . $xmlDescription2 . '_' . $documentInfo->getDocumentNumber(
            ) . '.xml">
              <property value="' . $documentInfo->getYearMonth() . '" key="1" />
              <property value="' . $xmlDescription2 . 'en" key="3" />
              </extension>
              <extension xsi:type="File" name="' . $xmlDescription2 . '_' . $documentInfo->getDocumentNumber() . '.pdf"/>
              </document>';
    }

    /**
     * @param string            $documentType
     * @param DateTimeInterface $from
     * @param DateTimeInterface $till
     * @param bool              $isZeroEuroAllowed
     * @param int               $datevLiabilityInvoiceDate
     * @param int               $projectId
     *
     * @return array
     */
    public function getDocumentInfos(
        string $documentType,
        DateTimeInterface $from,
        DateTimeInterface $till,
        bool $isZeroEuroAllowed,
        int $datevLiabilityInvoiceDate,
        int $projectId = 0
    ): array {
        if ($documentType === self::DOCUMENT_TYPE_INVOICE) {
            return $this->documentGateway->findDataFromInvoice(
                $projectId,
                $isZeroEuroAllowed,
                $from,
                $till
            );
        }
        if ($documentType === self::DOCUMENT_TYPE_CREDITNOTE) {
            return $this->documentGateway->findDataFromCreditNote(
                $projectId,
                $isZeroEuroAllowed,
                $from,
                $till
            );
        }

        return $this->documentGateway->findDataFromLiability(
            $projectId,
            $isZeroEuroAllowed,
            $from,
            $till,
            $datevLiabilityInvoiceDate
        );
    }

    /**
     * @param string $documentType
     *
     * @return string
     */
    private function getXmlAccountDescription(string $documentType): string
    {
        if ($documentType === self::DOCUMENT_TYPE_INVOICE || $documentType === self::DOCUMENT_TYPE_CREDITNOTE) {
            return 'accountsReceivableLedger';
        }

        return 'accountsPayableLedger';
    }

    /**
     * @param string $documentType
     *
     * @return string
     */
    private function getXmlDocumentTypeDescription(string $documentType): string
    {
        if ($documentType === self::DOCUMENT_TYPE_INVOICE || $documentType === self::DOCUMENT_TYPE_CREDITNOTE) {
            return 'Ausgangsrechnung';
        }

        return 'Eingangsrechnung';
    }

    /**
     * @param int $projectId
     *
     * @return int
     */
    private function getNominalAccountLength(int $projectId = 0): int
    {
        if ($projectId > 0) {
            $accountingSettings = $this->accountingSettingsGateway->findSettingsByProject($projectId);
        } else {
            $accountingSettings = $this->accountingSettingsGateway->findSettings();
        }

        if ($accountingSettings !== null && !empty($accountingSettings->getNominalAccountLength())) {
            return $accountingSettings->getNominalAccountLength();
        }

        return self::FALLBACK_NOMINAL_ACCOUNT_LENGTH;
    }

    /**
     * @param string $documentType
     *
     * @return string
     */
    private function getXmlDescriptionByDocumentType(string $documentType): string
    {
        if ($documentType === self::DOCUMENT_TYPE_INVOICE) {
            return 'Ausgangsrechnung';
        }
        if ($documentType === self::DOCUMENT_TYPE_CREDITNOTE) {
            return 'Ausgangsgutschrift';
        }

        return 'Eingangsrechnung';
    }

    /**
     * @param DocumentData $documentInfo
     * @param bool         $datevAppendInternet
     * @param float        $totalAmount
     *
     * @return string
     */
    private function getDocumentXmlHead(
        DocumentData $documentInfo,
        bool $datevAppendInternet,
        float $totalAmount
    ): string {
        $consolidatedOrderIdPart = '';
        $internetNumber = $documentInfo->getOnlineOrderId();
        if ($datevAppendInternet && !empty($internetNumber)) {
            $consolidatedOrderIdPart = "consolidatedOrderId=\"{$internetNumber}\"";
        }
        return '<?xml version="1.0" encoding="utf-8"?>
                <LedgerImport xmlns="http://xml.datev.de/bedi/tps/ledger/' .
            $this->datevDocumentExportVersionPath .
            '" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://xml.datev.de/bedi/tps/ledger/' .
            $this->datevDocumentExportVersionPath .
            ' Belegverwaltung_online_ledger_import_' .
            $this->datevDocumentExportVersionPath .
            '.xsd" version="' .
            $this->datevVersionNumber .
            "\" generator_info=\"Xentral Robot V0.1\" generating_system=\"Xentral Robot Datev XML Schnittstelle\" xml_data=\"Kopie nur zur Verbuchung berechtigt nicht zum Vorsteuerabzug\">\r\n"
            . '<consolidate ' . $consolidatedOrderIdPart . " consolidatedAmount='" . number_format(
                $totalAmount,
                2,
                '.',
                ''
            ) . "' consolidatedDate='" .
            $documentInfo->getDocumentDate()->format('Y-m-d') .
            "' consolidatedInvoiceId='" .
            $documentInfo->getDocumentNumber() .
            "' consolidatedCurrencyCode='" . $documentInfo->getCurrency() .
            "'>\r\n";
    }

    /**
     * @param string       $documentType
     * @param DocumentData $documentInfo
     * @param array        $booking
     * @param float        $amount
     * @param int          $nominalAccountLength
     *
     * @return string
     */
    private function addBookingInfoToXml(
        string $documentType,
        DocumentData $documentInfo,
        array $booking,
        float $amount,
        int $nominalAccountLength
    ): string
    {
        $booking['ort'] = $this->ensureValidXmlString($booking['ort'], 30);
        $booking['name'] = $this->ensureValidXmlString($booking['name'], 50);

        if ($booking['buchungstext'] == '') {
            $booking['buchungstext'] = 'Daten&#252;bername aus Wawision';
        }
        $booking['buchungstext'] = $this->ensureValidXmlString($booking['buchungstext'], 50);

        if ($documentType === self::DOCUMENT_TYPE_CREDITNOTE) {
            $booking['betrag'] *= -1;
        }
        $booking['betrag'] = number_format($booking['betrag'], 2, '.', '');
        $booking['steuersatz'] = number_format($booking['steuersatz'], 2, '.', '');
        if ($documentType === self::DOCUMENT_TYPE_LIABILITY) {
            $bookingText = 'Wareneingang ' . $booking['steuersatz'] . '%';
            $ledgerTag = 'accountsPayableLedger';
            $xmlString = "<accountsPayableLedger>\r\n";
            $tagPrefix = 'supplier';
        } else {
            $bookingText = 'Erl??se ' . $booking['steuersatz'] . '%';
            $ledgerTag = 'accountsReceivableLedger';
            $xmlString = "<accountsReceivableLedger>\r\n";
            $tagPrefix = 'customer';
        }

        $xmlString .= '<date>' . $booking['datum'] . "</date>\r\n";
        $xmlString .= '<amount>' . number_format($amount, 2, '.', '') . "</amount>\r\n";
        $xmlString .= $this->getContrAccountXmlString($booking, $nominalAccountLength);
        if (!empty($booking['kostenstelle'])) {
            $xmlString .= '<costCategoryId>' . $booking['kostenstelle'] . "</costCategoryId>\r\n";
        }
        $xmlString .= '<tax>' . $booking['steuersatz'] . "</tax>\r\n";
        $xmlString .= '<information>' . $booking['buchungstext'] . "</information>\r\n";
        $xmlString .= '<currencyCode>' . $booking['waehrung'] . "</currencyCode>\r\n";
        $xmlString .= '<invoiceId>' . $documentInfo->getDocumentNumber() . "</invoiceId>\r\n";
        $xmlString .= '<bookingText>' . $bookingText . "</bookingText>\r\n";

        if (strpos($booking['konto'], 'DEL-') === 0) {
            $booking['konto'] = substr($booking['konto'], 4);
        }

        $xmlString .= '<partyId>' . $booking['konto'] . "</partyId>\r\n";

        if ($booking['ustid'] != '' && $booking['land'] !== 'DE') {
            $xmlString .= '<vatId>' . $booking['ustid'] . "</vatId>\r\n";
        }
        if (!empty($booking['kurs']) && $booking['kurs'] > 0 && $booking['kurs'] != 1) {
            $xmlString .= '<exchangeRate>' . number_format((float)$booking['kurs'], 6, '.', '') . "</exchangeRate>\r\n";
        }

        $xmlString .= '<bpAccountNo>' . $booking['konto'] . "</bpAccountNo>\r\n";
        if ($booking['name'] != '') {
            $xmlString .= "<{$tagPrefix}Name>" . $booking['name'] . "</{$tagPrefix}Name>\r\n";
        }
        if ($booking['ort'] != '') {
            $xmlString .= "<{$tagPrefix}City>" . $booking['ort'] . "</{$tagPrefix}City>\r\n";
        }

        return $xmlString . "</{$ledgerTag}>\r\n";
    }

    /**
     * @param array $booking
     * @param int   $nominalAccountLength
     *
     * @return string
     */
    private function getContrAccountXmlString(array $booking, int $nominalAccountLength): string
    {
        // gegenkonto sachkontenlaenge + 2 ist dann abschneiden und aufteilen
        $contraAccount = (string)$booking['gegenkonto'];
        $bookingKey = '';

        if (strlen($contraAccount) === ($nominalAccountLength + 2)) {
            $bookingKey = substr($contraAccount, 0, 2);
            $bookingKey = rtrim($bookingKey, '0');
            $contraAccount = substr($contraAccount, 2);
        }

        $xmlString = '<accountNo>' . $contraAccount . "</accountNo>\r\n";
        if ($bookingKey != '') {
            $xmlString .= '<buCode>' . $bookingKey . "</buCode>\r\n";
        }

        return $xmlString;
    }

    /**
     * @return string
     */
    private function getCompanyName(): string
    {
        $companyName = $this->legacyWrapper->readyForPdf($this->legacyWrapper->findCompanyData('name'));
        $companyName = str_replace(['&', ',', '  '], ['&amp;', ' ', ' '], $companyName);
        if (mb_strlen($companyName, 'UTF-8') <= 36) {
            return $companyName;
        }

        return mb_substr($companyName, 0, 36, 'UTF-8');
    }

    /**
     * @param DocumentData $documentInfo
     *
     * @return string
     */
    private function getValidDocumentNumberFromInfo(DocumentData $documentInfo): string
    {
        $documentNumberSearchPattern = '/[a-zA-Z0-9$&%\*\+\-]*/m';
        preg_match_all($documentNumberSearchPattern, $documentInfo->getDocumentNumber(), $matches, PREG_PATTERN_ORDER, 0);
        $match = implode($matches[0]);

        // Wenn keine G??ltige Rechnungsnummer erzeugt werden kann oder Re.Nr. nicht eingegeben wurde
        if (strlen($match) < 3) {
            $match .= substr((string)$documentInfo->getDocumentId(), -10);
        }
        // oder besser letzte 10 Stellen der ID der Verbindlichkeit

        // Wenn Rechnungsnummer l??nger als 12 Stellen
        if (strlen($match) > 12) {
            return substr($match, 0, 2) . substr($match, -10);
        }

        if (!empty($match)) {
            return $match;
        }

        return (string)$documentInfo->getDocumentId();
    }

    /**
     * @param string       $documentType
     * @param DocumentData $documentInfo
     *
     * @return array
     */
    private function getBookingsFromDocumentInfo(string $documentType, DocumentData $documentInfo): array
    {
        if ($documentType === self::DOCUMENT_TYPE_INVOICE) {
            return (array)$this->accountingExportModule->DatevEinnahmenExport(
                '',
                '',
                0,
                'datum',
                '',
                false,
                $documentInfo->getDocumentId(),
                0
            );
        }
        if ($documentType === self::DOCUMENT_TYPE_CREDITNOTE) {
            return (array)$this->accountingExportModule->DatevEinnahmenExport(
                '',
                '',
                0,
                'datum',
                '',
                false,
                0,
                $documentInfo->getDocumentId()
            );
        }

        return (array)$this->accountingExportModule->DatevVerbindlichkeitKontierungExport(
            '',
            '',
            $documentInfo->getDocumentId(),
            true
        );
    }

    /**
     * @param string $documentType
     *
     * @return string
     */
    private function getXmlHead(string $documentType): string
    {
        return '<?xml version="1.0" encoding="utf-8"?>
            <archive xmlns="http://xml.datev.de/bedi/tps/document/' .
            $this->datevVersionPath .
            '" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://xml.datev.de/bedi/tps/document/' .
            $this->datevVersionPath . ' Document_' .
            $this->datevDocumentExportVersionPath .
            '.xsd" version="' .
            $this->datevVersionNumber . '"
            generatingSystem="Xentral ERP CRM">
            <header>
              <date>' . date('Y-m-d') . 'T' . date('H:i:s') . '</date>
              <description>Belegsatzdaten ' . $this->getXmlDescriptionByDocumentType($documentType) . '</description>
              <clientName>' . $this->getCompanyName() . '</clientName>
            </header>
            <content>';
    }

    /**
     * @param string $documentType
     *
     * @return string
     */
    private function ensureDocumentType(string $documentType): string
    {
        if (in_array($documentType, [self::DOCUMENT_TYPE_CREDITNOTE, self::DOCUMENT_TYPE_LIABILITY])) {
            return $documentType;
        }

        return self::DOCUMENT_TYPE_INVOICE;
    }

    private function ensureValidXmlString(string $string, ?int $maxLength = null): string
    {
        $string = str_replace(['&', ',', '  '], ['&amp;', ' ', ' '], $this->legacyWrapper->readyForPdf($string));
        if($maxLength === null || mb_strlen($string) <= $maxLength) {
            return $string;
        }
        $ampPos = mb_strrpos($string, '&amp;');
        if($ampPos === false || $ampPos < $maxLength - 5) {
            return trim(mb_substr($string, 0, $maxLength, 'UTF-8'));
        }

        return trim(mb_substr($string, 0, $ampPos, 'UTF-8'));
    }
}
