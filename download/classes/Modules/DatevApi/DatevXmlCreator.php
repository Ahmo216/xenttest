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

    private const APPEND_INTERNET_STANDARD_NUMBER = 1;

    private const APPEND_INTERNET_TRANSACTION_NUMBER = 2;

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

    public function getDocumentInfosFromDateRange(
        string $documentType,
        DateTimeInterface $from,
        DateTimeInterface $till,
        int $projectId = 0
    ): array {
        $documentType = $this->ensureDocumentType($documentType);
        $isZeroEuroAllowed = (bool)$this->systemConfig->tryGetLegacyValue('buchaltungexport_list_nulleurorechnungen');
        $datevLiabilityInvoiceDate =
            (int)$this->systemConfig->tryGetLegacyValue('buchaltungexport_list_datevverbindlichkeitrechnungsdatum');

        return $this->getDocumentInfos(
            $documentType,
            $from,
            $till,
            $isZeroEuroAllowed,
            $datevLiabilityInvoiceDate,
            $projectId
        );
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
        $datevAppendInternet = (int)$this->systemConfig->tryGetLegacyValue('datev_append_internet');
        $extendedBuCode = !empty($this->systemConfig->tryGetLegacyValue('buchaltungexport_list_allowextendedbucode'));

        foreach ($documentInfos as $documentInfo) {
            $xmlFileContents[] = $this->getXmlFileContentByDocument(
                $documentType,
                $documentInfo,
                $datevAppendInternet,
                $nominalAccountLength,
                $extendedBuCode
            );
        }

        $xmlFileContents[] = [
            'name' => 'document.xml',
            'content_head' => $this->getXmlHead($documentType),
            'content_foot' => '  </content>
        </archive>',
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
            $debitFormatted = number_format($documentInfo->getDebit() * $debitMultiplierFactor, 2, '.', '');
            $documentInfo->setDebit((float)$debitFormatted);
        }

        return $documentInfos;
    }

    /**
     * @param string       $documentType
     * @param DocumentData $documentInfo
     * @param int          $datevAppendInternet
     * @param int          $nominalAccountLength
     * @param bool         $extendedBuCode
     *
     * @return array
     */
    public function getXmlFileContentByDocument(
        string $documentType,
        DocumentData $documentInfo,
        int $datevAppendInternet,
        int $nominalAccountLength,
        bool $extendedBuCode
    ): array {
        $xmldescription2 = $this->getXmlDocumentTypeDescription($documentType);
        $bookings = $this->getBookingsFromDocumentInfo($documentType, $documentInfo);
        $xmlString = '';
        $totalAmount = 0;

        foreach ($bookings as $booking) {
            if ($booking['betrag'] == 0) {
                continue;
            }
            $valueFactor =
                ($booking['haben'] == '1' && $documentType !== self::DOCUMENT_TYPE_LIABILITY)
                || ($booking['haben'] == '0' && $documentType === self::DOCUMENT_TYPE_LIABILITY)
                    ? -1 : 1;
            $amount = $valueFactor * $booking['betrag'];
            $xmlString .= $this->addBookingInfoToXml(
                $documentType,
                $documentInfo,
                $booking,
                $amount,
                $nominalAccountLength,
                $extendedBuCode
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
            'name' => $xmldescription2 . '_' . $documentInfo->getDocumentNumber(),
            'document_id' => $documentInfo->getDocumentId(),
            'document_type' => $documentType,
            'content' => $xmlString,
            'pdf' => $this->getFileExtension($documentType, $documentInfo),
            'document_content' => $this->getDocumentInfoForDocumentXML($documentType, $documentInfo),
        ];
    }

    public function addContentPropertyToDocument(array $xmlFiles, string $documentType): array
    {
        foreach ($xmlFiles as $xmlKey => $xmlFile) {
            if ($xmlFile['name'] === 'document.xml') {
                $xmlFiles[$xmlKey]['content'] = $this->generateDocumentContentString($xmlFiles, $xmlFile, $documentType);
            }
        }

        return $xmlFiles;
    }

    public function generateDocumentContentString(array $xmlFiles, array $xmlFile, string $documentType): string
    {
        $content = $xmlFile['content_head'];

        foreach ($xmlFiles as $xml) {
            if ($xml['name'] === 'document.xml') {
                continue;
            }
            $content .= '
<document>
' . $xml['document_content'];
            if ($documentType !== 'verbindlichkeit' || !empty($xml['file_name'])) {
                $content .= $xml['pdf'];
            }
            $content .= '
</document>';
        }

        return $content . $xmlFile['content_foot'];
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

    public function getDocumentInfoForDocumentXML(string $documentType, DocumentData $documentInfo): string
    {
        $xmlDescription1 = $this->getXmlAccountDescription($documentType);
        $xmlDescription2 = $this->getXmlDocumentTypeDescription($documentType);

        return '
              <extension xsi:type="' . $xmlDescription1 . '" datafile="' . $xmlDescription2 . '_' . $documentInfo->getDocumentNumber(
            ) . '.xml">
              <property value="' . $documentInfo->getYearMonth() . '" key="1" />
              <property value="' . $xmlDescription2 . 'en" key="3" />
              </extension>';
    }

    public function getFileExtension(string $documentType, DocumentData $documentInfo): string
    {
        return '<extension xsi:type="File" name="'
            . $this->getXmlDocumentTypeDescription($documentType) . '_' . $documentInfo->getDocumentNumber() . '.pdf"/>';
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
     * @param int          $datevAppendInternet
     * @param float        $totalAmount
     *
     * @return string
     */
    private function getDocumentXmlHead(
        DocumentData $documentInfo,
        int $datevAppendInternet,
        float $totalAmount
    ): string {
        $consolidatedOrderIdPart = '';
        $internetNumber = $documentInfo->getOnlineOrderId();
        $transactionNumber = $documentInfo->getTransactionNumber();
        if ($datevAppendInternet === self::APPEND_INTERNET_STANDARD_NUMBER && !empty($internetNumber)) {
            $consolidatedOrderIdPart = "consolidatedOrderId=\"{$this->ensureValidPattern($internetNumber, 'p13')}\"";
        } elseif ($datevAppendInternet === self::APPEND_INTERNET_TRANSACTION_NUMBER && !empty($transactionNumber)) {
            $consolidatedOrderIdPart = "consolidatedOrderId=\"{$this->ensureValidPattern($transactionNumber, 'p13')}\"";
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
     * @param bool         $extendedBuCode
     *
     * @return string
     */
    private function addBookingInfoToXml(
        string $documentType,
        DocumentData $documentInfo,
        array $booking,
        float $amount,
        int $nominalAccountLength,
        bool $extendedBuCode
    ): string {
        $booking['ort'] = $this->ensureValidXmlString($booking['ort'], 30);
        $booking['name'] = $this->ensureValidXmlString($booking['name'], 50);

        if ($booking['buchungstext'] == '') {
            $booking['buchungstext'] = 'Daten&#252;bername aus Wawision';
        }
        $booking['buchungstext'] = $this->ensureValidXmlString($booking['buchungstext'], 50);

        if ($documentType === self::DOCUMENT_TYPE_CREDITNOTE) {
            $booking['betrag'] *= -1;
        }
        $booking['betrag'] = number_format((float)$booking['betrag'], 2, '.', '');
        $booking['steuersatz'] = number_format((float)$booking['steuersatz'], 2, '.', '');
        if ($documentType === self::DOCUMENT_TYPE_LIABILITY) {
            $bookingText = 'Wareneingang ' . $booking['steuersatz'] . '%';
            $ledgerTag = 'accountsPayableLedger';
            $xmlString = "<accountsPayableLedger>\r\n";
            $tagPrefix = 'supplier';
        } else {
            $bookingText = 'Erlöse ' . $booking['steuersatz'] . '%';
            $ledgerTag = 'accountsReceivableLedger';
            $xmlString = "<accountsReceivableLedger>\r\n";
            $tagPrefix = 'customer';
        }

        $xmlString .= '<date>' . $booking['datum'] . "</date>\r\n";
        $xmlString .= '<amount>' . number_format($amount, 2, '.', '') . "</amount>\r\n";

        [$contraAccount, $bookingKey] = $this->accountingExportModule->getContrAccountBuPair((string)$booking['gegenkonto'], $extendedBuCode, $nominalAccountLength);
        if($contraAccount !== '') {
            $xmlString .= '<accountNo>' . $contraAccount . "</accountNo>\r\n";
        }
        if ($bookingKey !== '') {
            $xmlString .= '<buCode>' . $bookingKey . "</buCode>\r\n";
        }
        if (!empty($booking['kostenstelle'])) {
            $xmlString .= '<costCategoryId>' . $booking['kostenstelle'] . "</costCategoryId>\r\n";
        }
        $xmlString .= '<tax>' . $booking['steuersatz'] . "</tax>\r\n";
        $xmlString .= '<information>' . $booking['buchungstext'] . "</information>\r\n";
        $xmlString .= '<currencyCode>' . ($booking['waehrung'] ?: 'EUR') . "</currencyCode>\r\n";
        $xmlString .= '<invoiceId>' . $this->ensureValidPattern($documentInfo->getDocumentNumber(), 'p10040') . "</invoiceId>\r\n";
        $xmlString .= '<bookingText>' . $bookingText . "</bookingText>\r\n";
        $xmlString .= $this->addShipFromInfo($booking, $documentType);
        if (strpos($booking['konto'], 'DEL-') === 0) {
            $booking['konto'] = substr($booking['konto'], 4);
        }

        $xmlString .= '<partyId>' . $this->ensureValidPattern($booking['konto'], 'p10011') . "</partyId>\r\n";
        $booking['ustid'] = $this->ensureValidPattern($booking['ustid'] ?? '', 'p10027');
        if ($booking['ustid'] != '' && $booking['land'] !== 'DE') {
            $xmlString .= '<vatId>' . $booking['ustid'] . "</vatId>\r\n";
        }
        if ($documentType !== self::DOCUMENT_TYPE_LIABILITY && !empty($booking['delivery_country'])) {
            $xmlString .= '<shipToCountry>' . $booking['delivery_country'] . '</shipToCountry>';
        }
        if (!empty($booking['kurs']) && $booking['kurs'] > 0 && $booking['kurs'] != 1) {
            $xmlString .= '<exchangeRate>' . number_format(1 / (float)$booking['kurs'], 6, '.', '') . "</exchangeRate>\r\n";
        }

        $xmlString .= '<bpAccountNo>' . $this->ensureValidPattern($booking['konto'], 'p11') . "</bpAccountNo>\r\n";
        if ($booking['name'] != '') {
            $xmlString .= "<{$tagPrefix}Name>" . $booking['name'] . "</{$tagPrefix}Name>\r\n";
        }
        if ($booking['ort'] != '') {
            $xmlString .= "<{$tagPrefix}City>" . $booking['ort'] . "</{$tagPrefix}City>\r\n";
        }

        return $xmlString . "</{$ledgerTag}>\r\n";
    }

    private function addShipFromInfo(array $booking, string $documentType): string
    {
        if ($documentType === self::DOCUMENT_TYPE_LIABILITY) {
            return '';
        }
        $xmlString = '';
        $country = substr(strtoupper(trim($this->legacyWrapper->findCompanyData('land'))), 0, 2);
        $ustId = $this->legacyWrapper->findCompanyData('steuernummer');
        if (!empty($booking['deliverythresholdvatid'])) {
            $ustId = $booking['deliverythresholdvatid'];
        }
        $ustId = $this->ensureValidPattern($ustId, 'p10027');
        $ustId = preg_replace('/[^A-Z0-9]/', '', strtoupper($ustId));
        if (!preg_match('/^[A-Z]{2}[A-Z0-9]+$/', $ustId)) {
            $ustId = '';
        }
        if (!empty($ustId)) {
            $country = substr($ustId, 0, 2);
            $xmlString .= "<ownVatId>${ustId}</ownVatId>\n";
            $xmlString .= "<shipFromCountry>${country}</shipFromCountry>\n";

            return $xmlString;
        }
        if (!empty($booking['storage_country'])) {
            $country = substr(strtoupper(trim($booking['storage_country'])), 0, 2);
        }
        if (!preg_match('/^[A-Z]{2}$/', $country)) {
            return '';
        }
        $xmlString .= "<shipFromCountry>${country}</shipFromCountry>\n";

        return $xmlString;
    }

    /**
     * @return string
     */
    private function getCompanyName(): string
    {
        $companyName = $this->legacyWrapper->readyForPdf($this->legacyWrapper->findCompanyData('name'));
        $companyName = str_replace(['&', ',', '  ', '<', '>'], ['&amp;', ' ', ' ', '&lt;', '&gt;'], $companyName);
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

        // Wenn keine Gültige Rechnungsnummer erzeugt werden kann oder Re.Nr. nicht eingegeben wurde
        if (strlen($match) < 3) {
            $match .= substr((string)$documentInfo->getDocumentId(), -10);
        }
        // oder besser letzte 10 Stellen der ID der Verbindlichkeit

        // Wenn Rechnungsnummer länger als 30 Stellen
        if (strlen($match) > 30) {
            return substr($match, 0, 2) . substr($match, -28);
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
            return $this->accountingExportModule->getDatevExportForInvoiceId($documentInfo->getDocumentId());
        }
        if ($documentType === self::DOCUMENT_TYPE_CREDITNOTE) {
            return $this->accountingExportModule->getDatevExportForCreditNoteId($documentInfo->getDocumentId());
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

    private function ensureValidXmlString(?string $string, ?int $maxLength = null): string
    {
        if ($string === null) {
            return '';
        }
        $string = str_replace(['&', ',', '  ', '<', '>'], ['&amp;', ' ', ' ', '&lt;', '&gt;'], $this->legacyWrapper->readyForPdf($string));
        if ($maxLength === null || mb_strlen($string) <= $maxLength) {
            return $string;
        }
        while (true) {
            $ampPos = mb_strrpos($string, '&');
            if ($ampPos === false) {
                return trim(mb_substr($string, 0, $maxLength, 'UTF-8'));
            }
            $ampLen = mb_strpos($string, ';', $ampPos) - $ampPos + 1;
            if ($ampPos < $maxLength - $ampLen) {
                return trim(mb_substr($string, 0, $maxLength, 'UTF-8'));
            }

            $string = trim(mb_substr($string, 0, $ampPos, 'UTF-8'));
            if (mb_strlen($string) <= $maxLength) {
                return $string;
            }
        }
    }

    private function ensureValidPattern(string $string, string $patternName): string
    {
        switch ($patternName) {
            case 'p10027':
                return preg_replace("/[^0-9a-zA-Z\. _]/", '', trim($string));
            case 'p10030':
                return preg_replace('/[^0-9a-zA-Z]/', '', trim($string));
            case 'p10037':
                return preg_replace("/[^0-9A-Fa-f\-]/", '', trim($string));
            case 'p13':
                return $this->ensureValidXmlString(preg_replace("/[^a-zA-Z0-9\$%&amp;*+\-.\/]/", '', trim($string)), 30);
            case 'p11':
                return $this->ensureValidXmlString(preg_replace('/[^0-9]/', '', trim($string)), 9);
            case 'p10011':
                return $this->ensureValidXmlString(trim($string), 15);
            case 'p10040':
                return $this->ensureValidXmlString(preg_replace("/[^a-zA-Z0-9$%&amp;*+\-\/]/", '', trim($string)), 36);
        }

        return $string;
    }
}
