<?php

declare(strict_types=1);

namespace Xentral\Modules\AmaInvoice\Service;

use Application;
use Briefpapier;
use DateInterval;
use DateTime;
use DateTimeInterface;
use GutschriftPDF;
use RechnungPDF;
use Xentral\Components\Database\Database;
use Xentral\Components\Filesystem\PathInfo;
use Xentral\Modules\AmaInvoice\Exception\AmazonInvoiceConfigNotFoundException;
use Xentral\Modules\AmaInvoice\Exception\AmazonInvoiceServiceException;
use Xentral\Modules\AmaInvoice\Exception\InvalidArgumentException;
use Xentral\Modules\AmaInvoice\Exception\ThrottlingException;
use Xentral\Modules\AmaInvoice\Wrapper\TurnoverThresholdWrapper;

final class AmaInvoiceService
{
    private const CANCEL_REASON = 'Rechnung storniert';

    /** @var Database $db */
    private $db;

    /** @var Application $app */
    private $app;

    /** @var array $config */
    private $config;

    /** @var TurnoverThresholdWrapper */
    private $thresholdWrapper;

    /**
     * AmaInvoiceService constructor.
     *
     * @param Database                 $db
     * @param Application              $app
     * @param TurnoverThresholdWrapper $thresholdWrapper
     */
    public function __construct(Database $db, $app, TurnoverThresholdWrapper $thresholdWrapper)
    {
        $this->db = $db;
        $this->app = $app;
        $this->thresholdWrapper = $thresholdWrapper;
    }

    /**
     * @return bool
     */
    public function hasTurnoverThresholdModule(): bool
    {
        return $this->thresholdWrapper->hasTurnoverThresholdModule();
    }

    /**
     * @return array
     */
    public function getMissingThresholdCountries(): array
    {
        $thresholdCountries = $this->thresholdWrapper->getThresholdsCountries();
        $amaInvoiceThresholds = $this->db->fetchCol(
            'SELECT `country_code` FROM `amainvoice_turnoverthreshold` AS `att`'
        );

        return array_diff($amaInvoiceThresholds, $thresholdCountries);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @param string $type
     *
     * @return array
     */
    public function getDocumentsByApi($startDate, $endDate, $type = 'inv'): array
    {
        $response = $this->prepareParametersByXentral($startDate, $endDate, $type);

        $response = @json_decode($response, true);
        if (empty($response)) {
            return [];
        }

        if (isset($response[0])) {
            return $response;
        }
        if (isset($response['rem_gs_nr']) || isset($response['inv_rech_nr'])) {
            return [$response];
        }

        return [];
    }

    /**
     * @return string
     */
    public function getBasicDataSet(): string
    {
        $response = $this->prepareParametersByXentral('', '', 'basicdatatax');

        $json = @json_decode($response, true);
        if ($json === null) {
            throw new AmazonInvoiceServiceException($response);
        }

        return $response;
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @param string $type
     *
     * @return string
     */
    public function prepareParametersByXentral($startDate, $endDate, $type = 'inv'): string
    {
        $this->loadConfig();
        $firmKeyId = $this->config['firmkeyid'];
        $clientIdentifier = $this->config['clientidentifier'];
        if (empty($firmKeyId)) {
            throw new AmazonInvoiceConfigNotFoundException('firmkeyid is empty');
        }
        if (empty($clientIdentifier)) {
            throw new AmazonInvoiceConfigNotFoundException('clientIdentifier is empty');
        }

        if (!in_array($type, ['inv', 'rem', 'basicdatatax'], true)) {
            throw new InvalidArgumentException('type must be "inv", "rem" or "basicdatatax"');
        }
        $needDate = in_array($type, ['inv', 'rem']);
        if ($needDate) {
            if (empty($startDate)) {
                throw new InvalidArgumentException('startdate is empty');
            }
            if (empty($endDate)) {
                throw new InvalidArgumentException('endDate is empty');
            }

            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));

            $now = new DateTime();
            $beforeTwoMonths = clone $now;
            $beforeTwoMonths->modify('-2 month');

            if ($endDate >= $now->format('Y-m-d')) {
                throw new InvalidArgumentException('endDate is not in the past');
            }
            if ($startDate < $beforeTwoMonths->format('Y-m-d')) {
                throw new InvalidArgumentException('endDate is older than two months');
            }
        }
        $serial = $this->app->erp->Firmendaten('lizenz');
        if (empty($clientIdentifier)) {
            throw new InvalidArgumentException('lizenz is empty');
        }
        $url = 'https://amazon.xentral.com/amainvoiceapp.php';
        $schluessel = $this->app->erp->Firmendaten('schluessel');
        if (empty($clientIdentifier)) {
            throw new InvalidArgumentException('schluessel is empty');
        }

        $paras = [
            'firm_key_id' => $firmKeyId,
            'client_identifier' => $clientIdentifier,
            'data_type' => $type,
            'json' => false,
        ];
        if ($needDate) {
            $paras['start_date'] = $startDate;
            $paras['end_date'] = $endDate;
        }

        $paras = [
            'serial' => $serial,
            'schluessel' => $schluessel,
            'paras' => json_encode($paras),
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($paras));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch);
        curl_close($ch);
        if ($response === 'Throttel is On') {
            $this->db->perform(
                "INSERT INTO `logfile` ( meldung, dump, module, action, bearbeiter, funktionsname, datum) 
                VALUES ('Throttle is On', '', 'amainvoice', '', 'Cronjob', '', NOW() )"
            );
            throw new ThrottlingException($response);
        }
        $httpcode = $httpcode['http_code'];
        if (strpos((string)$httpcode, '2') === 0) {
            if ($response === 'Error: Parameters not loaded [0]') {
                throw new AmazonInvoiceServiceException($response);
            }

            return $response;
        }

        if (!empty($response)) {
            $response2 = @json_decode($response, true);
            if (!empty($response2['error'])) {
                if (!empty($response2['throttled'])) {
                    $error = $response2['error'];
                    if (is_array($error)) {
                        $error = reset($error);
                    }
                    $this->db->perform(
                        "INSERT INTO `logfile` ( meldung, dump, module, action, bearbeiter, funktionsname, datum) 
                         VALUES ('Throttle by xentral is On', '', 'amainvoice', '', 'Cronjob', '', NOW() )"
                    );
                    throw new ThrottlingException($error);
                }
                if (is_array($response2['error'])) {
                    throw new AmazonInvoiceServiceException(reset($response2['error']));
                }
                if (is_string($response2['error'])) {
                    throw new AmazonInvoiceServiceException($response2['error']);
                }
            }
        }

        throw new AmazonInvoiceServiceException($response);
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->db->fetchPairs('SELECT `name`, `value` FROM `amainvoice_config`');
    }

    /**
     * @param array $configArr
     */
    public function setConfig($configArr): void
    {
        if (empty($configArr)) {
            throw new AmazonInvoiceConfigNotFoundException('Config Array empty');
        }

        foreach ($configArr as $key => $value) {
            if (empty($key)) {
                throw new AmazonInvoiceConfigNotFoundException('Config Array invalid');
            }
        }

        $config = $this->getConfig();

        foreach ($configArr as $key => $value) {
            if (!isset($config[$key])) {
                $this->db->perform(
                    'INSERT INTO `amainvoice_config`  (`name`, `value`) VALUES (:name, :value)',
                    ['name' => $key, 'value' => (string)$value]
                );
            } elseif ((string)$value !== $config[$key]) {
                $this->db->perform(
                    'UPDATE `amainvoice_config` SET `value` = :value WHERE `name` = :name',
                    ['name' => $key, 'value' => (string)$value]
                );
            }
        }
    }

    /*
     * @var string|null $type
     *
     * @return array|PathInfo[]
     */
    public function getFiles(?string $type = null): array
    {
        if ($this->config === null) {
            $this->loadConfig();
        }
        try {
            $ret = [];
            $now = new DateTime();
            if ($type === null || $type === 'tax') {
                $ret[] = new PathInfo(
                    [
                        'type' => 'file',
                        'filename' => $now->format('Y-m-d') . '.tax',
                        'path' => $now->format('Y-m-d') . '.tax',
                    ]
                );
            }
            $beforeTwoMonths = clone $now;
            $beforeTwoMonths->modify('-2 month');
            $startDate = new DateTime($this->config['startdate']);
            if ($startDate < $beforeTwoMonths) {
                $startDate = $beforeTwoMonths;
            }
            $toDate = clone $now;
            $toDate->sub(new DateInterval('P1D'));
            while ($startDate < $toDate) {
                $dateFormated = $startDate->format('Y-m-d');
                if ($type === null || $type === 'inv') {
                    $ret[] = new PathInfo(
                        [
                            'type' => 'file',
                            'filename' => $dateFormated . '.inv',
                            'path' => $dateFormated . '.inv',
                        ]
                    );
                }
                if ($type === null || $type === 'rem') {
                    $ret[] = new PathInfo(
                        [
                            'type' => 'file',
                            'filename' => $dateFormated . '.rem',
                            'path' => $dateFormated . '.rem',
                        ]
                    );
                }
                $startDate->add(new DateInterval('P1D'));
            }

            return $ret;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @return array
     */
    public function getNewFiles(?string $type = null): array
    {
        $files = $this->getFiles($type);
        if (empty($files)) {
            return [];
        }

        $fileNames = [];
        foreach ($files as $file) {
            $fileName = $file->getPath();
            $fileNames[] = $fileName;
        }

        $inDb = $this->db->fetchPairs(
            'SELECT `id`, `filename` FROM `amainvoice_files` WHERE `filename` IN (:filenames)',
            ['filenames' => $fileNames]
        );

        $inDb = array_values($inDb);

        return array_diff($fileNames, $inDb);
    }

    public function syncBasicTaxDataForCurrentDateIfNotExists(): void
    {
        $files = $this->getNewFiles('tax');
        if (empty($files)) {
            return;
        }
        $dateFiles = $this->getFirstApiFiles($files);
        if (empty($dateFiles['basicdatatax'])) {
            return;
        }
        $file = reset($dateFiles['basicdatatax']);
        $this->executeImportDateFile($file);
    }

    /**
     * @param array $files
     *
     * @return array|array[]
     */
    public function getFirstApiFiles($files): array
    {
        $ret = [
            'invoice' => [],
            'returnorder' => [],
            'basicdatatax' => [],
        ];
        if (empty($files)) {
            return $ret;
        }

        foreach ($files as $file) {
            if (substr($file, -4) === '.inv') {
                $ret['invoice'][] = $file;

                return $ret;
            }
            if (substr($file, -4) === '.rem') {
                $ret['returnorder'][] = $file;

                return $ret;
            }
            if (substr($file, -4) === '.tax') {
                $ret['basicdatatax'][] = $file;

                return $ret;
            }
        }

        return $ret;
    }

    /**
     * @param string $type
     * @param string $amazonOrderId
     * @param string $number
     * @param array  $datevRows
     * @param array  $positions
     * @param string $pdfFile
     * @param bool   $createOrder
     *
     * @return bool
     */
    public function createDocument(
        $type,
        $amazonOrderId,
        $number,
        $datevRows,
        $positions,
        $pdfFile = '',
        $createOrder = false
    ): bool {
        if (empty($amazonOrderId)) {
            throw new InvalidArgumentException('amazonOrderId is empty');
        }
        if ($number !== null && empty($number)) {
            throw new InvalidArgumentException('document number is empty');
        }
        if ($type !== 'invoice' && $type !== 'returnorder') {
            throw new InvalidArgumentException('type not valid');
        }
        $order = $this->db->fetchCol(
            "SELECT `id` 
            FROM `auftrag` 
            WHERE `internet` = :amazonorderid  AND `internet` <> '' AND `status` <> 'storniert' ",
            ['amazonorderid' => $amazonOrderId]
        );
        $firstOrderId = empty($order) ? null : reset($order);
        $orderCreated = false;
        $firstDatevRow = reset($datevRows);
        $isOrderFbm = true;
        if (!empty($firstDatevRow['fulfillmentchannel']) && $firstDatevRow['fulfillmentchannel'] === 'AFN') {
            $isOrderFbm = false;
        }
        $statusInfo = null;
        if ($isOrderFbm) {
            $statusInfo = $this->db->fetchRow(
                'SELECT `status`, `schreibschutz`
                FROM `auftrag` 
                WHERE `id` = :order_id ',
                ['order_id' => (int)$firstOrderId]
            );
            if (empty($statusInfo) || $statusInfo['status'] !== 'abgeschlossen') {
                return false;
            }
        }

        $billaddress1 = $firstDatevRow['billaddress1'];
        $billaddress2 = $firstDatevRow['billaddress2'];
        $billaddress3 = $firstDatevRow['billaddress3'];
        $shipaddress1 = $firstDatevRow['shipaddress1'];
        $shipaddress2 = $firstDatevRow['shipaddress2'];
        $shipaddress3 = $firstDatevRow['shipaddress3'];
        $billStreet3 = '';
        $shipStreet3 = '';
        if (!empty($billaddress1)) {
            $billStreet = $billaddress1;
            if (is_numeric($billaddress2)) {
                $billStreet .= ' ' . $billaddress2;
                $billStreet2 = $billaddress3;
            } else {
                $billStreet2 = $billaddress2;
                $billStreet3 = $billaddress3;
            }
        } else {
            $billStreet = $billaddress2;
            if (is_numeric($billaddress3)) {
                $billStreet .= ' ' . $billaddress3;
            } else {
                $billStreet2 = $billaddress2;
            }
        }
        if (!empty($shipaddress1)) {
            $shipStreet = $shipaddress1;
            if (is_numeric($shipaddress2)) {
                $shipStreet .= ' ' . $shipaddress2;
                $shipStreet2 = $shipaddress3;
            } else {
                $shipStreet2 = $shipaddress2;
                $shipStreet3 = $shipaddress3;
            }
        } else {
            $shipStreet = $shipaddress2;
            if (is_numeric($shipaddress3)) {
                $shipStreet .= ' ' . $shipaddress3;
            } else {
                $shipStreet2 = $shipaddress2;
            }
        }

        if (empty($order) && $createOrder) {
            $address = [
                'name' => $firstDatevRow['buyername'],
                'kundennummer' => $this->app->erp->GetNextKundennummer(),
                'strasse' => $billStreet,
                'addresszusatz' => $billStreet2,
                'ort' => $firstDatevRow['billcity'],
                'plz' => $firstDatevRow['billpostalcode'],
                'land' => $firstDatevRow['billcountry'],
            ];
            $address['id'] = (int)$this->app->erp->InsertUpdateAdresse($address);
            $orderId = (int)$this->app->erp->CreateAuftrag($address['id']);
            $this->app->erp->LoadAuftragStandardwerte($orderId, $address['id']);
            $order = [
                'name' => $firstDatevRow['buyername'],
                'strasse' => $billStreet,
                'addresszusatz' => $billStreet2,
                'ort' => $firstDatevRow['billcity'],
                'plz' => $firstDatevRow['billpostalcode'],
                'land' => $firstDatevRow['billcountry'],
                'email' => $firstDatevRow['buyeremail'],
                'lieferstrasse' => $shipStreet,
                'lieferaddresszusatz' => $shipStreet2,
                'lieferland' => $firstDatevRow['shipcountry'],
                'versandart' => $firstDatevRow['carrier'],
                'lieferort' => $firstDatevRow['shipcity'],
                'lieferplz' => $firstDatevRow['shippostalcode'],
                'liefername' => $firstDatevRow['recipientname'],
            ];
            if ($isOrderFbm && !empty($this->config['projectfbm'])) {
                $order['projekt'] = $this->config['projectfbm'];
            } elseif (!$isOrderFbm && !empty($this->config['projectfba'])) {
                $order['projekt'] = $this->config['projectfba'];
            }
            if (!empty($this->config['paymentmethod'])) {
                $order['versandart'] = $this->config['paymentmethod'];
            }
            $this->app->DB->UpdateArr('auftrag', $orderId, 'id', $order, true);
            $this->db->perform(
                'UPDATE `auftrag` SET `internet` = :orderid WHERE `id` = :id',
                [
                    'orderid' => $amazonOrderId,
                    'id' => $orderId,
                ]
            );
            $this->app->erp->BelegFreigabe('auftrag', $orderId);
            $orderCreated = true;
            $order = $this->db->fetchCol(
                "SELECT `id` 
                FROM `auftrag` 
                WHERE `internet` = :amazonorderid  AND `internet` <> '' AND `status` <> 'storniert' ",
                ['amazonorderid' => $amazonOrderId]
            );
            $this->app->erp->AuftragProtokoll($orderId, 'Auftrag erstellt durch AmaInvoice');
        }

        if (empty($order)) {
            return false;
        }
        $order = (int)reset($order);

        if ($type === 'invoice') {
            if ($number !== null) {
                $invoice = $this->db->fetchCol(
                    'SELECT `id` FROM `rechnung` WHERE `auftragid` = :orderid AND `belegnr` = :number',
                    ['orderid' => $order, 'number' => $number]
                );
            } else {
                $invoice = $this->db->fetchCol(
                    'SELECT `id` FROM `rechnung` WHERE `auftragid` = :orderid',
                    ['orderid' => $order]
                );
            }
            if (!empty($invoice)) {
                return false;
            }
            $invoice = (int)$this->app->erp->WeiterfuehrenAuftragZuRechnung($order, null, null, 0, false);
            if ($isOrderFbm && !empty($statusInfo)) {
                $this->db->perform(
                    'UPDATE `auftrag` SET `status` = :status, `schreibschutz` = :readonly WHERE `id` = :order_id',
                    [
                        'status' => $statusInfo['status'],
                        'readonly' => (int)$statusInfo['schreibschutz'],
                        'order_id' => $order,
                    ]
                );
            }
            $invoiceArr = [
                'name' => $firstDatevRow['buyername'],
                'strasse' => $billStreet,
                'addresszusatz' => $billStreet2,
                'abteilung' => $billStreet3,
                'ort' => $firstDatevRow['billcity'],
                'plz' => $firstDatevRow['billpostalcode'],
                'land' => $firstDatevRow['billcountry'],
                'email' => $firstDatevRow['buyeremail'],
                'datum' => $firstDatevRow['date'],
                'belegnr' => $number ?? $firstDatevRow['inv_rech_nr'],
            ];

            $orderArr = [
                'name' => $firstDatevRow['buyername'],
                'strasse' => $billStreet,
                'addresszusatz' => $billStreet2,
                'abteilung' => $billStreet3,
                'ort' => $firstDatevRow['billcity'],
                'plz' => $firstDatevRow['billpostalcode'],
                'land' => $firstDatevRow['billcountry'],
                'email' => $firstDatevRow['buyeremail'],
            ];
            if (
                $firstDatevRow['buyername'] !== $firstDatevRow['recipientname']
                || $billStreet !== $shipStreet
                || $billStreet2 !== $shipStreet2
            ) {
                $orderArr['abweichendelieferadresse'] = 1;
                $orderArr['liefername'] = $firstDatevRow['recipientname'];
                $orderArr['lieferstrasse'] = $shipStreet;
                $orderArr['lieferadresszusatz'] = $shipStreet2;
                $orderArr['lieferabteilung'] = $shipStreet3;
                $orderArr['lieferort'] = $firstDatevRow['shipcity'];
                $orderArr['lieferplz'] = $firstDatevRow['shippostalcode'];
                $orderArr['lieferland'] = $firstDatevRow['shipcountry'];
            }
            $this->app->DB->UpdateArr('auftrag', $order, 'id', $orderArr, true);
            $this->app->DB->UpdateArr('rechnung', $invoice, 'id', $invoiceArr, true);
            $this->app->erp->AuftragProtokoll($order, 'Rechnung erstellt durch AmaInvoice');
            $this->app->erp->BelegFreigabe('rechnung', $invoice, true);

            $soll = 0;
            foreach ($positions as $position) {
                $soll += (float)str_replace(',', '.', $position['brutto_total']);
            }

            $this->addPositions('invoice', $invoice, $positions, $orderCreated ? $order : null);
            $this->addDiscountArticles(
                'invoice',
                $invoice,
                $datevRows,
                $positions,
                $orderCreated ? $order : null
            );
            $this->addShippingArticles(
                'invoice',
                $invoice,
                $datevRows,
                $positions,
                $orderCreated ? $order : null
            );
            $this->app->erp->RechnungNeuberechnen($invoice);

            $this->db->perform(
                "UPDATE `rechnung` 
                SET `datum` = :date, `waehrung` = :currency, `soll` = :soll, `extsoll` = :soll,
                    schreibschutz = 1, `status` = 'versendet'
                WHERE `id` = :invoiceId",
                [
                    'date' => $firstDatevRow['date'],
                    'currency' => $firstDatevRow['currency'],
                    'invoiceId' => $invoice,
                    'soll' => $soll,
                ]
            );
            if (!empty($firstDatevRow['fulfillmentchannel']) && $firstDatevRow['fulfillmentchannel'] === 'AFN') {
                $this->db->perform(
                    'UPDATE `rechnung` SET `ist` = `soll` WHERE `id` = :invoiceId',
                    [
                        'invoiceId' => $invoice,
                    ]
                );
            }

            $this->app->erp->PDFArchivieren('rechnung', $invoice, true);
            if (empty($pdfFile) && !empty($firstDatevRow['documentlink'])) {
                $content = file_get_contents($firstDatevRow['documentlink']);
                $pdfFile = basename($firstDatevRow['documentlink']) . '.pdf';
                file_put_contents($this->app->erp->GetTMP() . $pdfFile, $content);
                $this->archiveDocument('invoice', $invoice, $pdfFile);
                if (is_file($this->app->erp->GetTMP() . $pdfFile)) {
                    unlink($this->app->erp->GetTMP() . $pdfFile);
                }
            } elseif (!empty($pdfFile)) {
                $this->archiveDocument('invoice', $invoice, $pdfFile);
            }

            if ($orderCreated && !$isOrderFbm) {
                $this->db->perform(
                    "UPDATE `auftrag` 
                    SET `datum` = :date, `waehrung` = :currency, `gesamtsumme` = :soll, `extsoll` = :soll,
                        schreibschutz = 1, `status` = 'abgeschlossen'
                    WHERE `id` = :orderId",
                    [
                        'date' => $firstDatevRow['date'],
                        'currency' => $firstDatevRow['currency'],
                        'orderId' => $order,
                        'soll' => $soll,
                    ]
                );

                $this->app->erp->PDFArchivieren('auftrag', $order, true);
            }

            return true;
        }
        if ($type === 'returnorder') {
            $invoices = $this->db->fetchCol(
                'SELECT `id` FROM `rechnung` WHERE `auftragid` = :orderid',
                ['orderid' => $order]
            );
            if (empty($invoices)) {
                throw new InvalidArgumentException('invoice not found');
            }
            $returnOrder = $this->db->fetchCol(
                'SELECT `id` FROM `gutschrift` WHERE `rechnungid` IN (:invoiceids) AND `belegnr` = :number',
                ['invoiceids' => $invoices, 'number' => $number]
            );

            if (!empty($returnOrder)) {
                return false;
            }

            $invoice = reset($invoices);
            $returnOrder = $this->app->erp->WeiterfuehrenRechnungZuGutschrift($invoice, self::CANCEL_REASON, false);
            $this->app->erp->RechnungProtokoll($invoice, 'Gutschrift erstellt durch AmaInvoice');
            $this->db->perform(
                'UPDATE `gutschrift` SET `belegnr` = :number, `datum` = :date WHERE `id` = :id LIMIT 1',
                [
                    'number' => (string)$number,
                    'id' => (int)$returnOrder,
                    'date' => (string)$firstDatevRow['date'],
                ]
            );

            $soll = 0;
            foreach ($datevRows as $datevRow) {
                $soll += (float)str_replace(',', '.', $datevRow['brutto']);
            }

            $this->addPositions('returnorder', $returnOrder, $positions);
            $this->addDiscountArticles('returnorder', $returnOrder, $datevRows, $positions);
            $this->addShippingArticles('returnorder', $returnOrder, $datevRows, $positions);
            $this->app->erp->BelegFreigabe('gutschrift', $returnOrder, true);
            $this->app->erp->GutschriftNeuberechnen($returnOrder);
            $this->db->perform(
                "UPDATE `gutschrift` 
                SET `datum` = :date, `waehrung` = :currency, `soll` = :soll, `extsoll` = :soll,
                    schreibschutz = 1, `status` = 'versendet'
                WHERE `id` = :returnorderid",
                [
                    'date' => $firstDatevRow['date'],
                    'currency' => $firstDatevRow['currency'],
                    'returnorderid' => $returnOrder,
                    'soll' => $soll,
                ]
            );
            $this->app->erp->PDFArchivieren('gutschrift', $returnOrder, true);
            if (empty($pdfFile) && !empty($firstDatevRow['documentlink'])) {
                $content = file_get_contents($firstDatevRow['documentlink']);
                $pdfFile = basename($firstDatevRow['documentlink']) . '.pdf';
                file_put_contents($this->app->erp->GetTMP() . $pdfFile, $content);
                $this->archiveDocument('returnorder', $returnOrder, $pdfFile);
                if (is_file($this->app->erp->GetTMP() . $pdfFile)) {
                    unlink($this->app->erp->GetTMP() . $pdfFile);
                }
            } elseif (!empty($pdfFile)) {
                $this->archiveDocument('returnorder', $returnOrder, $pdfFile);
            }

            return true;
        }

        return false;
    }

    /**
     * @param string $file
     * @param string $type
     * @param string $status
     */
    public function markFile($file, $type, $status = ''): void
    {
        $this->db->perform(
            'INSERT INTO `amainvoice_files` (`filename`, `type`, `status`) VALUES (:filename, :type, :status)',
            ['filename' => $file, 'type' => $type, 'status' => (string)$status]
        );
    }

    /**
     * @param string $file
     */
    public function cleanFile($file): void
    {
        $file = $this->app->erp->GetTMP() . $file;
        if (!is_file($file)) {
            return;
        }
        @unlink($file);
    }

    /**
     * @param string $file
     */
    public function executeImportDateFile($file)
    {
        $ext = substr($file, -3);
        if ($ext === 'tax') {
            $type = 'basicdatatax';
        } else {
            $type = $ext === 'rem' ? 'returnorder' : 'invoice';
        }
        $apiPositions = $this->executeGetPostionsByApiAndFilename($file);
        foreach ($apiPositions[$type] as $amazonOrderId => $apiSubPositions) {
            $document = [$apiSubPositions['document']];
            foreach ($apiSubPositions['positions'] as $numberInvoice => $apiSubPosition) {
                try {
                    $this->createDocument(
                        $type,
                        $amazonOrderId,
                        null,
                        $document,
                        $apiSubPosition,
                        '',
                        $this->isCreateOrderActive()
                    );
                } catch (InvalidArgumentException $e) {
                }
            }
        }

        $this->markFile($file, 'api_' . $type, 'imported');
    }

    /**
     * @param bool $isReturnOrder
     * @param bool $createOrder
     */
    public function executeImportDateDbEntries($isReturnOrder, $createOrder = false): void
    {
        $apiPositions = $this->getNotImportedPositionsFromDb($isReturnOrder, $createOrder);
        $type = $isReturnOrder ? 'returnorder' : 'invoice';
        foreach ($apiPositions[$type] as $amazonOrderId => $apiSubPositions) {
            $document = [$apiSubPositions['document']];
            foreach ($apiSubPositions['positions'] as $numberInvoice => $apiSubPosition) {
                try {
                    $this->createDocument(
                        $type,
                        $amazonOrderId,
                        null,
                        $document,
                        $apiSubPosition,
                        '',
                        $createOrder
                    );
                } catch (InvalidArgumentException $e) {
                }
            }
        }
    }

    /**
     * @param DateTimeInterface $dateFrom
     * @param DateTimeInterface $dateTo
     * @param bool              $isReturnOrder
     */
    public function markDbEntriesToImport($dateFrom, $dateTo, $isReturnOrder): void
    {
        if (!$isReturnOrder) {
            $this->loadConfig();
            $createOrder = $this->isCreateOrderActive();
            if (!$createOrder) {
                $this->db->perform(
                    "UPDATE `amazoninvoice_position`
                    SET `create` = 1
                    WHERE `doctype_id` = 0 AND `create_order` = 0 AND `rem_gs_nr` = ''
                    AND `inv_date` >= :dateFrom AND `inv_date` >= :dateTo",
                    [
                        'dateFrom' => $dateFrom->format('Y-m-d'),
                        'dateTo' => $dateTo->format('Y-m-d'),
                    ]
                );
            } else {
                $this->db->perform(
                    "UPDATE `amazoninvoice_position`
                    SET `create` = 1, `create_order` = 1
                    WHERE `doctype_id` = 0 AND `create_order` = 0 AND `rem_gs_nr` = ''
                    AND `inv_date` >= :dateFrom AND `inv_date` >= :dateTo",
                    [
                        'dateFrom' => $dateFrom->format('Y-m-d'),
                        'dateTo' => $dateTo->format('Y-m-d'),
                    ]
                );
            }
        } else {
            $this->db->perform(
                "UPDATE `amazoninvoice_position`
                SET `create` = 1
                WHERE `doctype_id` = 0 AND `create_order` = 0 AND `rem_gs_nr` <> ''
                AND `rem_gs_nr` >= :dateFrom AND `rem_gs_nr` >= :dateTo",
                [
                    'dateFrom' => $dateFrom->format('Y-m-d'),
                    'dateTo' => $dateTo->format('Y-m-d'),
                ]
            );
        }
    }

    /**
     * @param string $file
     *
     * @return array|array[]
     */
    public function executeGetPostionsByApiAndFilename($file): array
    {
        $date = substr($file, 0, 10);
        $ext = substr($file, -3);

        if ($ext === 'tax') {
            $basicDataTaxJson = json_decode($this->prepareParametersByXentral('', '', 'basicdatatax'));
            $this->importBasicDataTaxJson($basicDataTaxJson);

            return [];
        }
        $arr = $this->getDocumentsByApi($date, $date, $ext);
        $arr = $this->insertFromApiReturn($arr, $date, $ext === 'rem');

        return $this->getPositionsFromApiRequest(
            $arr,
            $ext === 'rem'
        );
    }

    /**
     * @param bool  $isReturnOrder
     * @param bool  $createOrder
     * @param mixed $hours
     *
     * @return array|array[]
     */
    public function getNotImportedPositionsFromDb($isReturnOrder, $createOrder = false, $hours = 8): array
    {
        if (!$isReturnOrder) {
            if (!$createOrder) {
                $arr = $this->db->fetchAll(
                    "SELECT ap.* 
                    FROM `amazoninvoice_position` AS `ap` 
                    WHERE ap.`create` = 1 AND ap.doctype_id = 0 AND `create_order` = 0 AND ap.rem_gs_nr = ''
                    ORDER BY ap.`inv_date`, ap.id"
                );
            } else {
                $arr = $this->db->fetchAll(
                    "SELECT ap.* 
                    FROM `amazoninvoice_position` AS `ap` 
                    WHERE ap.`create` = 1 AND ap.doctype_id = 0 AND `create_order` = 1 AND ap.rem_gs_nr = '' 
                        AND ap.created_at < DATE_SUB(NOW(), INTERVAL :hours HOUR)
                    ORDER BY ap.`inv_date`, ap.id",
                    [
                        'hours' => $hours,
                    ]
                );
            }
        } else {
            $arr = $this->db->fetchAll(
                "SELECT ap.* 
                FROM `amazoninvoice_position` AS `ap` 
                WHERE ap.`create` = 1 AND ap.doctype_id = 0 AND ap.rem_gs_nr <> '' 
                ORDER BY ap.`rem_date`, ap.id"
            );
        }

        return $this->getPositionsFromApiRequest(
            $arr,
            $isReturnOrder
        );
    }

    /**
     * @param array  $arr
     * @param string $date
     * @param bool   $isReturnOrder
     *
     * @return array
     */
    public function insertFromApiReturn($arr, $date, $isReturnOrder): array
    {
        if ($isReturnOrder) {
            $inDb = $this->db->fetchPairs(
                'SELECT `rem_gs_nr`, `id` FROM `amazoninvoice_position` WHERE `rem_date` = :date',
                ['date' => $date]
            );
        } else {
            $inDb = $this->db->fetchPairs(
                'SELECT `inv_rech_nr`, `id` FROM `amazoninvoice_position` WHERE `inv_date` = :date',
                ['date' => $date]
            );
        }
        foreach ($arr as $key => $row) {
            if ($isReturnOrder) {
                if (!empty($inDb[$row['rem_gs_nr']])) {
                    $arr[$key]['id'] = $inDb[$row['rem_gs_nr']];
                    continue;
                }
            } elseif (!empty($inDb[$row['inv_rech_nr']])) {
                $arr[$key]['id'] = $inDb[$row['inv_rech_nr']];
                continue;
            }

            $this->db->perform(
                'INSERT INTO `amazoninvoice_position` 
                    (
                        `inv_rech_nr`,`inv_date`,`amazonorderid`,`shipmentdate`,`buyeremail`,`buyerphonenumber`,
                     `buyername`,`sku`,`productname`,`quantitypurchased`,`quantityshipped`,`currency`,`mwst`,
                     `taxrate`,`brutto_total`,`netto_total`,`tax_total`,`itemprice`,`itemprice_netto`,`itemprice_tax`,
                     `shippingprice`,`shippingprice_netto`,`shippingprice_tax`,`giftwrapprice`,`giftwrapprice_netto`,
                     `giftwrapprice_tax`,`itempromotiondiscount`,`itempromotiondiscount_netto`,
                     `itempromotiondiscount_tax`,`shippromotiondiscount`,`shippromotiondiscount_netto`,
                     `shippromotiondiscount_tax`,`giftwrappromotiondiscount`,`giftwrappromotiondiscount_netto`,
                     `giftwrappromotiondiscount_tax`,`shipservicelevel`,`recipientname`,`shipaddress1`,`shipaddress2`,
                     `shipaddress3`,`shipcity`,`shipstate`,`shippostalcode`,`shipcountry`,`shipphonenumber`,
                     `billaddress1`,`billaddress2`,`billaddress3`,`billcity`,`billstate`,`billpostalcode`,
                     `billcountry`,`carrier`,`trackingnumber`,`fulfillmentcenterid`,`fulfillmentchannel`,
                     `saleschannel`,`asin`,`conditiontype`,`quantityavailable`,`isbusinessorder`,`uid`,`vatcheck`,
                     `documentlink`,`order_id`,`rem_gs_nr`,`orderid`,
                     `rem_date`,`returndate`,`buyercompanyname`,`quantity`,`remreturnshipcost`,
                     `remsondererstattung`,`itempromotionid`,`reason`,`rem_gs_nr_real`, `created_at`
                     ) VALUES (
                        :inv_rech_nr, :inv_date, :amazonorderid, :shipmentdate, :buyeremail, :buyerphonenumber, 
                     :buyername, :sku, :productname, :quantitypurchased, :quantityshipped, :currency, :mwst, 
                     :taxrate, :brutto_total, :netto_total, :tax_total, :itemprice, :itemprice_netto, :itemprice_tax, 
                     :shippingprice, :shippingprice_netto, :shippingprice_tax, :giftwrapprice, :giftwrapprice_netto, 
                     :giftwrapprice_tax, :itempromotiondiscount, :itempromotiondiscount_netto, 
                     :itempromotiondiscount_tax, :shippromotiondiscount, :shippromotiondiscount_netto, 
                     :shippromotiondiscount_tax, :giftwrappromotiondiscount, :giftwrappromotiondiscount_netto, 
                     :giftwrappromotiondiscount_tax, :shipservicelevel, :recipientname, :shipaddress1, 
                     :shipaddress2, :shipaddress3, :shipcity, :shipstate, :shippostalcode, :shipcountry, 
                     :shipphonenumber, :billaddress1, :billaddress2, :billaddress3, :billcity, :billstate, 
                     :billpostalcode, :billcountry, :carrier, :trackingnumber, :fulfillmentcenterid, 
                     :fulfillmentchannel, :saleschannel, :asin, :conditiontype, :quantityavailable, 
                     :isbusinessorder, :uid, :vatcheck, :documentlink, :order_id, :rem_gs_nr, :orderid, 
                     :rem_date, :returndate, :buyercompanyname, :quantity, :remreturnshipcost, 
                     :remsondererstattung, :itempromotionid, :reason, :rem_gs_nr_real, NOW()
                     ) ',
                [
                    'inv_rech_nr' => (string)$row['inv_rech_nr'],
                    'inv_date' => (string)$row['inv_date'],
                    'amazonorderid' => (string)$row['amazonorderid'],
                    'shipmentdate' => (string)$row['shipmentdate'],
                    'buyeremail' => (string)$row['buyeremail'],
                    'buyerphonenumber' => (string)$row['buyerphonenumber'],
                    'buyername' => (string)$row['buyername'],
                    'sku' => (string)$row['sku'],
                    'productname' => (string)$row['productname'],
                    'quantitypurchased' => (string)$row['quantitypurchased'],
                    'quantityshipped' => (string)$row['quantityshipped'],
                    'currency' => (string)$row['currency'],
                    'mwst' => (string)$row['mwst'],
                    'taxrate' => (string)$row['taxrate'],
                    'brutto_total' => (string)$row['brutto_total'],
                    'netto_total' => (string)$row['netto_total'],
                    'tax_total' => (string)$row['tax_total'],
                    'itemprice' => (string)$row['itemprice'],
                    'itemprice_netto' => (string)$row['itemprice_netto'],
                    'itemprice_tax' => (string)$row['itemprice_tax'],
                    'shippingprice' => (string)$row['shippingprice'],
                    'shippingprice_netto' => (string)$row['shippingprice_netto'],
                    'shippingprice_tax' => (string)$row['shippingprice_tax'],
                    'giftwrapprice' => (string)$row['giftwrapprice'],
                    'giftwrapprice_netto' => (string)$row['giftwrapprice_netto'],
                    'giftwrapprice_tax' => (string)$row['giftwrapprice_tax'],
                    'itempromotiondiscount' => (string)$row['itempromotiondiscount'],
                    'itempromotiondiscount_netto' => (string)$row['itempromotiondiscount_netto'],
                    'itempromotiondiscount_tax' => (string)$row['itempromotiondiscount_tax'],
                    'shippromotiondiscount' => (string)$row['shippromotiondiscount'],
                    'shippromotiondiscount_netto' => (string)$row['shippromotiondiscount_netto'],
                    'shippromotiondiscount_tax' => (string)$row['shippromotiondiscount_tax'],
                    'giftwrappromotiondiscount' => (string)$row['giftwrappromotiondiscount'],
                    'giftwrappromotiondiscount_netto' => (string)$row['giftwrappromotiondiscount_netto'],
                    'giftwrappromotiondiscount_tax' => (string)$row['giftwrappromotiondiscount_tax'],
                    'shipservicelevel' => (string)$row['shipservicelevel'],
                    'recipientname' => (string)$row['recipientname'],
                    'shipaddress1' => (string)$row['shipaddress1'],
                    'shipaddress2' => (string)$row['shipaddress2'],
                    'shipaddress3' => (string)$row['shipaddress3'],
                    'shipcity' => (string)$row['shipcity'],
                    'shipstate' => (string)$row['shipstate'],
                    'shippostalcode' => (string)$row['shippostalcode'],
                    'shipcountry' => (string)$row['shipcountry'],
                    'shipphonenumber' => (string)$row['shipphonenumber'],
                    'billaddress1' => (string)$row['billaddress1'],
                    'billaddress2' => (string)$row['billaddress2'],
                    'billaddress3' => (string)$row['billaddress3'],
                    'billcity' => (string)$row['billcity'],
                    'billstate' => (string)$row['billstate'],
                    'billpostalcode' => (string)$row['billpostalcode'],
                    'billcountry' => (string)$row['billcountry'],
                    'carrier' => (string)$row['carrier'],
                    'trackingnumber' => (string)$row['trackingnumber'],
                    'fulfillmentcenterid' => (string)$row['fulfillmentcenterid'],
                    'fulfillmentchannel' => (string)$row['fulfillmentchannel'],
                    'saleschannel' => (string)$row['saleschannel'],
                    'asin' => (string)$row['asin'],
                    'conditiontype' => (string)$row['conditiontype'],
                    'quantityavailable' => (string)$row['quantityavailable'],
                    'isbusinessorder' => (string)$row['isbusinessorder'],
                    'uid' => (string)$row['uid'],
                    'vatcheck' => (string)$row['vatcheck'],
                    'documentlink' => (string)$row['documentlink'],
                    'order_id' => (string)$row['order_id'],
                    'rem_gs_nr' => (string)$row['rem_gs_nr'],
                    'orderid' => (string)$row['orderid'],
                    'rem_date' => (string)$row['rem_date'],
                    'returndate' => (string)$row['returndate'],
                    'buyercompanyname' => (string)$row['buyercompanyname'],
                    'quantity' => (string)$row['quantity'],
                    'remreturnshipcost' => (string)$row['remreturnshipcost'],
                    'remsondererstattung' => (string)$row['remsondererstattung'],
                    'itempromotionid' => (string)$row['itempromotionid'],
                    'reason' => (string)$row['reason'],
                    'rem_gs_nr_real' => (string)$row['rem_gs_nr_real'],
                ]
            );

            $arr[$key]['id'] = $this->db->lastInsertId();
        }

        return $arr;
    }

    /**
     * @param array $arr
     * @param bool  $isReturnOrder
     *
     * @return array|array[]
     */
    public function getPositionsFromApiRequest($arr, $isReturnOrder = false): array
    {
        if (empty($arr)) {
            return [];
        }
        $type = $isReturnOrder ? 'returnorder' : 'invoice';
        $return = [$type => []];
        $numberKey = $isReturnOrder ? 'rem_gs_nr' : 'inv_rech_nr';
        $dateKey = $isReturnOrder ? 'rem_date' : 'inv_date';

        foreach ($arr as $row) {
            $amazonOrder = empty($row['amazonorderid']) ? $row['orderid'] : $row['amazonorderid'];
            $number = $row[$numberKey];
            $qty = empty($row['quantity']) ? $row['quantitypurchased'] : $row['quantity'];
            $taxRate = empty($row['mwst']) ? 0 : $row['taxrate'];
            $row['taxrate'] = (float)$row['taxrate'];
            $row['date'] = $row[$dateKey];
            $row['quantity'] = $qty;
            $row['bruttototal'] = $row['brutto_total'];
            $row['nettototal'] = $row['netto_total'];
            $row['preis'] = empty($row['mwst'])
                ? $row['itemprice_netto'] / $qty
                : $row['itemprice'] / (1 + $row['taxrate'] / 100.0) / $qty;
            $row['itemdiscount_price'][$taxRate] = empty($row['mwst'])
                ? $row['itempromotiondiscount_netto']
                : $row['itempromotiondiscount'] / (1 + $row['taxrate'] / 100.0);
            $row['shipping_net_price'][$taxRate] = empty($row['mwst'])
                ? $row['shippingprice_netto']
                : $row['shippingprice'] / (1 + $row['taxrate'] / 100.0);
            $row['shippingdiscount_price'][$taxRate] = empty($row['mwst'])
                ? $row['shippromotiondiscount_netto']
                : $row['shippromotiondiscount'] / (1 + $row['taxrate'] / 100.0);
            $row['giftwrap_price'] = empty($row['mwst'])
                ? $row['giftwrapprice_netto'] / $qty
                : $row['giftwrapprice'] / (1 + $row['taxrate'] / 100.0) / $qty;
            $row['giftwrapdiscount_price'] = empty($row['mwst'])
                ? $row['giftwrappromotiondiscount_netto']
                : $row['giftwrappromotiondiscount'] / (1 + $row['taxrate'] / 100.0);

            $row['giftwrap_price_with_discount'] = round(
                (float)$row['giftwrapprice'] + (float)$row['giftwrappromotiondiscount'],
                2
            ) / $qty;
            $row['giftwrap_price_net_with_discount'] = empty($row['mwst'])
                ? round(
                    (float)$row['giftwrapprice_netto'] + (float)$row['giftwrappromotiondiscount_netto'],
                    2
                ) / $qty : round(
                    (float)$row['giftwrapprice'] + (float)$row['giftwrappromotiondiscount'],
                    2
                ) / $qty / (1.0 + $row['taxrate'] / 100.0);

            $row['shipping_price_with_discount'][$taxRate] = round(
                (float)$row['shippingprice'] + (float)$row['shippromotiondiscount'],
                2
            );

            if (!isset($return[$type][$amazonOrder]['document'])) {
                $return[$type][$amazonOrder]['document'] = $row;
            } else {
                $return[$type][$amazonOrder]['document']['brutto_total']
                    = round(
                        (float)$return[$type][$amazonOrder]['document']['brutto_total'] + (float)$row['brutto_total'],
                        2
                    );
                $return[$type][$amazonOrder]['document']['nettototal']
                    = round(
                        (float)$return[$type][$amazonOrder]['document']['nettototal'] + (float)$row['nettototal'],
                        2
                    );

                if (!isset($return[$type][$amazonOrder]['document']['shipping_net_price'][$taxRate])) {
                    $return[$type][$amazonOrder]['document']['shipping_net_price'][$taxRate] = 0.0;
                }
                $return[$type][$amazonOrder]['document']['shipping_net_price'][$taxRate]
                    = (float)$return[$type][$amazonOrder]['document']['shipping_net_price'][$taxRate]
                    + (float)$row['shipping_net_price'][$taxRate];

                if (!isset($return[$type][$amazonOrder]['document']['shippingdiscount_price'][$taxRate])) {
                    $return[$type][$amazonOrder]['document']['shippingdiscount_price'][$taxRate] = 0.0;
                }
                $return[$type][$amazonOrder]['document']['shippingdiscount_price'][$taxRate]
                    = (float)$return[$type][$amazonOrder]['document']['shippingdiscount_price'][$taxRate]
                    + (float)$row['shippingdiscount_price'][$taxRate];

                if (!isset($return[$type][$amazonOrder]['document']['shipping_price_with_discount'][$taxRate])) {
                    $return[$type][$amazonOrder]['document']['shipping_price_with_discount'][$taxRate] = 0.0;
                }

                $return[$type][$amazonOrder]['document']['shipping_price_with_discount'][$taxRate]
                    = (float)$return[$type][$amazonOrder]['document']['shipping_price_with_discount'][$taxRate]
                    + (float)$row['shipping_price_with_discount'][$taxRate];

                if (!isset($return[$type][$amazonOrder]['document']['itemdiscount_price'][$taxRate])) {
                    $return[$type][$amazonOrder]['document']['itemdiscount_price'][$taxRate] = 0.0;
                }

                $return[$type][$amazonOrder]['document']['itemdiscount_price'][$taxRate]
                    = (float)$return[$type][$amazonOrder]['document']['itemdiscount_price'][$taxRate]
                    + (float)$row['itemdiscount_price'][$taxRate];
            }
            $return[$type][$amazonOrder]['document']['brutto']
                = $return[$type][$amazonOrder]['document']['brutto_total'];
            $return[$type][$amazonOrder]['positions'][$number][] = $row;
        }

        return $return;
    }

    /**
     * @param string $file
     *
     * @return array
     */
    public function getPositionFromExportCsv($file): array
    {
        $handle = fopen($file, 'r');
        if (empty($handle)) {
            throw new InvalidArgumentException('could not open file ' . $file);
        }

        $header = fgetcsv($handle, 0, ';');
        $headerToKey = array_flip($header);
        $isReturnOrder = in_array('rem_gs_nr', $header);

        $amazonOrderKey = isset($headerToKey['orderid']) ? $headerToKey['orderid'] : null;
        if ($amazonOrderKey === null) {
            $amazonOrderKey = isset($headerToKey['amazonorderid']) ? $headerToKey['amazonorderid'] : null;
        }
        $returnOrderKey = isset($headerToKey['rem_gs_nr']) ? $headerToKey['rem_gs_nr'] : null;
        $returnOderDateKey = isset($headerToKey['rem_date']) ? $headerToKey['rem_date'] : null;
        $invDateKey = isset($headerToKey['inv_date']) ? $headerToKey['inv_date'] : null;
        $invoiceKey = isset($headerToKey['inv_rech_nr']) ? $headerToKey['inv_rech_nr'] : null;
        $skuKey = isset($headerToKey['sku']) ? $headerToKey['sku'] : null;
        $quantityKey = isset($headerToKey['quantity']) ? $headerToKey['quantity'] : null;
        if ($quantityKey === null) {
            $quantityKey = isset($headerToKey['quantitypurchased']) ? $headerToKey['quantitypurchased'] : null;
        }
        $currencyKey = isset($headerToKey['currency']) ? $headerToKey['currency'] : null;
        $taxrateKey = isset($headerToKey['taxrate']) ? $headerToKey['taxrate'] : null;
        $bruttoTotalKey = isset($headerToKey['brutto_total']) ? $headerToKey['brutto_total'] : null;
        $nettoTotalKey = isset($headerToKey['netto_total']) ? $headerToKey['netto_total'] : null;
        $mwstKey = isset($headerToKey['mwst']) ? $headerToKey['mwst'] : null;
        $dateKey = $isReturnOrder ? $returnOderDateKey : $invDateKey;
        $numberKey = $isReturnOrder ? $returnOrderKey : $invoiceKey;

        $type = $isReturnOrder ? 'returnorder' : 'invoice';
        $return = [
            $type => [],
        ];
        while ($row = fgetcsv($handle, 0, ';')) {
            $amazonOrder = $row[$amazonOrderKey];
            $date = $row[$dateKey];
            $number = $row[$numberKey];
            $sku = $row[$skuKey];
            $quantity = $row[$quantityKey];
            $mwst = $row[$mwstKey];
            $bruttoTotal = $row[$bruttoTotalKey];
            $nettoTotal = $row[$nettoTotalKey];
            $taxRate = $row[$taxrateKey];
            $currency = $row[$currencyKey];
            if (empty($amazonOrder) || empty($number)) {
                continue;
            }
            $return[$type][$amazonOrder][$number][] = [
                'date' => $date,
                'sku' => $sku,
                'quantity' => $quantity,
                'mwst' => $mwst,
                'bruttototal' => $bruttoTotal,
                'nettototal' => $nettoTotal,
                'taxrate' => $taxRate,
                'currency' => $currency,
            ];
        }

        fclose($handle);

        return $return;
    }

    /**
     * @return bool
     */
    public function isCreateOrderActive()
    {
        return !empty($this->config['createorder']);
    }

    private function validateConfig(): void
    {
        foreach (['firmkeyid', 'clientidentifier'] as $name) {
            if (!isset($this->config[$name])) {
                throw new AmazonInvoiceConfigNotFoundException(sprintf('Config field %s not found', $name));
            }
        }
    }

    private function loadConfig(): void
    {
        $this->config = $this->getConfig();
        if (empty($this->config)) {
            throw new AmazonInvoiceConfigNotFoundException('Amazon config does not found');
        }

        $this->validateConfig();
    }

    /**
     * @param string $doctype
     * @param int    $doctypeId
     *
     * @return int|null
     */
    private function getAmazonShopIdFromDocument($doctype, $doctypeId): ?int
    {
        if ($doctype === 'rechnung') {
            $invoiceId = $doctypeId;
        } else {
            $invoiceId = $this->db->fetchValue(
                'SELECT `rechnungid` FROM `gutschrift` WHERE `id` = :id',
                ['id' => $doctypeId]
            );
        }
        if (empty($invoiceId)) {
            return null;
        }
        $orderId = $this->db->fetchValue(
            'SELECT `auftragid` FROM `rechnung` WHERE `id` = :id',
            ['id' => $invoiceId]
        );
        if (empty($orderId)) {
            return null;
        }
        $shopId = (int)$this->db->fetchValue(
            'SELECT `shop` FROM `auftrag` WHERE `id` = :id',
            ['id' => $orderId]
        );
        if (empty($shopId)) {
            return null;
        }

        $shopId = $this->db->fetchValue(
            'SELECT `id` FROM `shopexport` WHERE `id` = :id AND `modulename` = :module_name',
            [
                'id' => $shopId,
                'module_name' => 'shopimporter_amazon',
            ]
        );
        if (empty($shopId)) {
            return null;
        }

        return (int)$shopId;
    }

    /**
     * @param int $shopId
     *
     * @return int|null
     */
    private function getDiscountArticleIdByShopId($shopId): ?int
    {
        $discountArticle = $this->db->fetchRow(
            'SELECT `art`.`id` 
            FROM `shopexport` AS `s`
            LEFT JOIN `artikel` AS `art` ON `s`.`artikelrabatt` = art.id 
                    AND (`art`.`geloescht` = 0 OR `art`.`geloescht` IS NULL)
                    AND (`art`.`intern_gesperrt` = 0 OR `art`.`intern_gesperrt` IS NULL)
            WHERE `s`.`id` = :id AND `s`.`modulename` = :module_name',
            [
                'id' => $shopId,
                'module_name' => 'shopimporter_amazon',
            ]
        );
        if (empty($discountArticle)) {
            return null;
        }
        if ($discountArticle['id'] > 0) {
            return (int)$discountArticle['id'];
        }

        return $this->createDiscountArticleByShopId($shopId);
    }

    /**
     * @param int $shopId
     *
     * @return int|null
     */
    private function getShippingArticleIdByShopId($shopId): ?int
    {
        $shippingArticle = $this->db->fetchRow(
            'SELECT `art`.`id` 
            FROM `shopexport` AS `s`
            LEFT JOIN `artikel` AS `art` ON `s`.`artikelporto` = art.id 
                    AND (`art`.`geloescht` = 0 OR `art`.`geloescht` IS NULL)
                    AND (`art`.`intern_gesperrt` = 0 OR `art`.`intern_gesperrt` IS NULL)
            WHERE `s`.`id` = :id AND `s`.`modulename` = :module_name',
            [
                'id' => $shopId,
                'module_name' => 'shopimporter_amazon',
            ]
        );
        if (empty($shippingArticle)) {
            return null;
        }
        if ($shippingArticle['id'] > 0) {
            return (int)$shippingArticle['id'];
        }

        return $this->createShippingArticleByShopId($shopId);
    }

    /**
     * @param int $shopId
     *
     * @return int|null
     */
    private function getGiftWrapArticleIdByShopId($shopId): ?int
    {
        $json = $this->db->fetchValue(
            'SELECT `einstellungen_json` FROM `shopexport` WHERE `id` = :id AND `modulename` = :module_name',
            [
                'id' => $shopId,
                'module_name' => 'shopimporter_amazon',
            ]
        );
        if (empty($json)) {
            return null;
        }

        $json = @json_decode($json, true);
        if (empty($json) || empty($json['felder']) || empty($json['felder']['giftwrap'])) {
            return null;
        }

        return (int)$json['felder']['giftwrap'];
    }

    /**
     * @param int $shopId
     *
     * @return int|null
     */
    private function createDiscountArticleByShopId($shopId): ?int
    {
        $shop = $this->db->fetchRow(
            'SELECT `s`.`projekt`, `art`.`id` 
            FROM `shopexport` AS `s`
            LEFT JOIN `artikel` AS `art` ON `s`.`artikelrabatt` = art.id 
                    AND (`art`.`geloescht` = 0 OR `art`.`geloescht` IS NULL)
                    AND (`art`.`intern_gesperrt` = 0 OR `art`.`intern_gesperrt` IS NULL)
            WHERE `s`.`id` = :id AND `s`.`modulename` = :module_name',
            [
                'id' => $shopId,
                'module_name' => 'shopimporter_amazon',
            ]
        );
        if (empty($shop) || !empty($shop['id'])) {
            return null;
        }
        $discountArticle = [
            'projekt' => $shop['projekt'],
            'name_de' => 'Rabatt',
            'name_en' => 'Discount',
            'lagerartikel' => 0,
        ];
        $discountArticle['nummer'] = $this->app->erp->GetNextArtikelnummer('', '1', $shop['projekt']);
        $discountArticleId = (int)$this->app->erp->InsertUpdateArtikel($discountArticle);
        if ($discountArticleId <= 0) {
            return null;
        }
        $this->db->perform(
            'UPDATE `shopexport` SET `artikelrabatt` = :articleId WHERE `id` = :shopId ',
            [
                'articleId' => $discountArticleId,
                'shopId' => $shopId,
            ]
        );

        return $discountArticleId;
    }

    /**
     * @param int $shopId
     *
     * @return int|null
     */
    private function createShippingArticleByShopId($shopId): ?int
    {
        $shop = $this->db->fetchRow(
            'SELECT `s`.`projekt`, `art`.`id` 
            FROM `shopexport` AS `s`
            LEFT JOIN `artikel` AS `art` ON `s`.`artikelporto` = art.id 
                    AND (`art`.`geloescht` = 0 OR `art`.`geloescht` IS NULL)
                    AND (`art`.`intern_gesperrt` = 0 OR `art`.`intern_gesperrt` IS NULL)
            WHERE `s`.`id` = :id AND `s`.`modulename` = :module_name',
            [
                'id' => $shopId,
                'module_name' => 'shopimporter_amazon',
            ]
        );
        if (empty($shop) || !empty($shop['id'])) {
            return null;
        }
        $firstShippingArticle = $this->db->fetchRow(
            'SELECT `art`.`id` 
            FROM `artikel` AS `art`
            LEFT JOIN `projekt` AS `p` ON `art`.projekt = p.id
            WHERE `art`.`porto` = 1 
              AND (`art`.`geloescht` IS NOT NULL OR `art`.geloescht = 0)
                    AND (`art`.`intern_gesperrt` = 0 OR `art`.`intern_gesperrt` IS NULL)
                AND (`p`.`id` IS NULL OR p.`oeffentlich` = 1)
                '
        );
        if (!empty($firstShippingArticle)) {
            $this->db->perform(
                'UPDATE `shopexport` SET `artikelporto` = :articleId WHERE `id` = :shopId ',
                [
                    'articleId' => (int)$firstShippingArticle['id'],
                    'shopId' => $shopId,
                ]
            );

            return (int)$firstShippingArticle['id'];
        }
        $shippingArticle = ['projekt' => $shop['projekt'], 'name_de' => 'Porto', 'proto' => 1];
        $shippingArticle['nummer'] = $this->app->erp->GetNextArtikelnummer('', '1', $shop['projekt']);
        $shippingArticleId = (int)$this->app->erp->InsertUpdateArtikel($shippingArticle);
        if ($shippingArticleId <= 0) {
            return null;
        }
        $this->db->perform(
            'UPDATE `shopexport` SET `artikelporto` = :articleId WHERE `id` = :shopId ',
            [
                'articleId' => $shippingArticleId,
                'shopId' => $shopId,
            ]
        );

        return $shippingArticleId;
    }

    /**
     * @param int $shopId
     *
     * @return int|null
     */
    private function createGiftWrapArticle($shopId): ?int
    {
        $shop = $this->db->fetchRow(
            'SELECT `projekt`, `einstellungen_json` FROM `shopexport` WHERE `id` = :id AND `modulename` = :module_name',
            [
                'id' => $shopId,
                'module_name' => 'shopimporter_amazon',
            ]
        );
        if (empty($shop)) {
            return null;
        }
        $giftwrapArticle = ['projekt' => $shop['projekt'], 'name_de' => 'Geschenkverpackung', 'lagerartikel' => 1];
        $giftwrapArticle['nummer'] = $this->app->erp->GetNextArtikelnummer('', '1', $shop['projekt']);
        $giftwrapArticleId = (int)$this->app->erp->InsertUpdateArtikel($giftwrapArticle);
        if ($giftwrapArticleId <= 0) {
            return null;
        }
        $json = @json_decode($shop['einstellungen_json'], true);
        if (!is_array($json)) {
            $json = ['felder' => []];
        }
        if (!isset($json['felder'])) {
            $json['felder'] = [];
        }
        $json['felder']['giftwrap'] = $giftwrapArticleId;
        $this->db->perform(
            'UPDATE `shopexport` SET `einstellungen_json` = :json WHERE `id` = :id',
            [
                'json' => json_encode($json),
                'id' => $shopId,
            ]
        );

        return $giftwrapArticleId;
    }

    /**
     * @param string $document
     * @param int    $positionId
     * @param float  $tax
     */
    private function changePositionTax($document, $positionId, $tax): void
    {
        if (!is_numeric($tax) || empty($positionId) || !in_array($document, ['rechnung', 'gutschrift'])) {
            return;
        }

        if ($document === 'rechnung') {
            $this->db->perform(
                'UPDATE `rechnung_position` SET `steuersatz` = :tax WHERE `id` = :positionId',
                [
                    'tax' => (float)$tax,
                    'positionId' => (int)$positionId,
                ]
            );

            return;
        }

        if ($document === 'gutschrift') {
            $this->db->perform(
                'UPDATE `gutschrift_position` SET `steuersatz` = :tax WHERE `id` = :positionId',
                [
                    'tax' => (float)$tax,
                    'positionId' => (int)$positionId,
                ]
            );

            return;
        }
    }

    /**
     * @param string       $doctype
     * @param int          $doctypeId
     * @param float|string $tax
     * @param float        $price
     *
     * @return int|null
     */
    private function addDiscountPosition($doctype, $doctypeId, $tax, $price): ?int
    {
        $shopId = $this->getAmazonShopIdFromDocument($doctype, $doctypeId);
        if ($shopId === null) {
            return null;
        }
        $discountArticleId = $this->getDiscountArticleIdByShopId($shopId);
        if ($discountArticleId === null) {
            $discountArticleId = $this->createDiscountArticleByShopId($shopId);
            if ($discountArticleId === null) {
                return null;
            }
        }

        $document = $this->getDocumentInfoForAddPosition($doctype, $doctypeId);
        if ($document === null) {
            return null;
        }
        $discountArticle = $this->getArticleInfoForAddPosition($document, $discountArticleId);
        if ($discountArticle === null) {
            return null;
        }

        $positionId = $this->app->erp->AddPositionManuellPreisNummer(
            $doctype,
            $doctypeId,
            0,
            $discountArticle['nummer'],
            1,
            $discountArticle['name'],
            $price,
            !empty($tax) && in_array($tax, ['normal', 'ermaessigt', 'befreit']) ? $tax : '',
            0,
            0,
            $document['waehrung']
        );

        if (is_numeric($tax)) {
            $this->changePositionTax($doctype, $positionId, $tax);
        }

        if (empty($positionId)) {
            return null;
        }

        return (int)$positionId;
    }

    /**
     * @param string       $doctype
     * @param int          $doctypeId
     * @param float|string $tax
     * @param float        $price
     *
     * @return int|null
     */
    private function addShippingPosition($doctype, $doctypeId, $tax, $price): ?int
    {
        $shopId = $this->getAmazonShopIdFromDocument($doctype, $doctypeId);
        if ($shopId === null) {
            return null;
        }
        $shippingArticleId = $this->getShippingArticleIdByShopId($shopId);
        if ($shippingArticleId === null) {
            $shippingArticleId = $this->createShippingArticleByShopId($shopId);
            if ($shippingArticleId === null) {
                return null;
            }
        }

        $document = $this->getDocumentInfoForAddPosition($doctype, $doctypeId);
        if ($document === null) {
            return null;
        }
        $shippingArticle = $this->getArticleInfoForAddPosition($document, $shippingArticleId);
        if ($shippingArticle === null) {
            return null;
        }

        $positionId = $this->app->erp->AddPositionManuellPreisNummer(
            $doctype,
            $doctypeId,
            0,
            $shippingArticle['nummer'],
            1,
            $shippingArticle['name'],
            $price,
            !empty($tax) && in_array($tax, ['normal', 'ermaessigt', 'befreit']) ? $tax : '',
            0,
            0,
            $document['waehrung']
        );

        if (is_numeric($tax)) {
            $this->changePositionTax($doctype, $positionId, $tax);
        }

        if (empty($positionId)) {
            return null;
        }

        return (int)$positionId;
    }

    /**
     * @param string $doctype
     * @param int    $doctypeId
     *
     * @return array|null
     */
    private function getDocumentInfoForAddPosition($doctype, $doctypeId): ?array
    {
        if ($doctype === 'rechnung') {
            $document = $this->db->fetchRow(
                'SELECT `waehrung`, `sprache` FROM `rechnung` WHERE `id` = :id',
                ['id' => $doctypeId]
            );
        } elseif ($doctype === 'gutschrift') {
            $document = $this->db->fetchRow(
                'SELECT `waehrung`, `sprache` FROM `gutschrift` WHERE `id` = :id',
                ['id' => $doctypeId]
            );
        } else {
            return null;
        }
        if (empty($document)) {
            return null;
        }
        if (empty($document['waehrung'])) {
            $document['waehrung'] = 'EUR';
        }

        return $document;
    }

    /**
     * @param array $document
     * @param int   $articleId
     *
     * @return array|null
     */
    private function getArticleInfoForAddPosition($document, $articleId): ?array
    {
        $article = $this->db->fetchRow(
            'SELECT `nummer`, `name_de`, `name_en` FROM `artikel` WHERE `id` = :id ',
            ['id' => $articleId]
        );
        if (empty($article)) {
            return null;
        }
        $name = $article['name_de'];
        if (
            !empty($article['name_en'])
            && !empty($document['sprache'])
            && !in_array($document['sprache'], ['deutsch', 'german', 'de', 'DE'])
        ) {
            $name = $article['name_en'];
        }

        $article['name'] = $name;

        return $article;
    }

    /**
     * @param string   $doctype
     * @param int      $doctypeId
     * @param int      $quantity
     * @param float    $price
     * @param int|null $orderId
     *
     * @return int|null
     */
    private function addGiftWrapPositon($doctype, $doctypeId, $quantity, $price, $orderId = null): ?int
    {
        if ($doctype !== 'rechnung') {
            $createOrderPosition = false;
        }
        $shopId = $this->getAmazonShopIdFromDocument($doctype, $doctypeId);
        if ($shopId === null) {
            return null;
        }
        $giftWrapArticleId = $this->getGiftWrapArticleIdByShopId($shopId);
        if ($giftWrapArticleId === null) {
            $giftWrapArticleId = $this->createGiftWrapArticle($shopId);
            if ($giftWrapArticleId === null) {
                return null;
            }
        }

        $document = $this->getDocumentInfoForAddPosition($doctype, $doctypeId);
        if ($document === null) {
            return null;
        }

        $giftWrapArticle = $this->getArticleInfoForAddPosition($document, $giftWrapArticleId);
        if ($giftWrapArticle === null) {
            return null;
        }

        $positionId = (int)$this->app->erp->AddPositionManuellPreisNummer(
            $doctype,
            $doctypeId,
            0,
            $giftWrapArticle['nummer'],
            $quantity,
            $giftWrapArticle['name'],
            $price,
            '',
            0,
            0,
            $document['waehrung']
        );

        if (empty($positionId)) {
            return null;
        }

        if ($createOrderPosition) {
            $orderPositionId = (int)$this->app->erp->AddPositionManuellPreisNummer(
                'auftrag',
                $orderId,
                0,
                $giftWrapArticle['nummer'],
                $quantity,
                $giftWrapArticle['name'],
                $price,
                '',
                0,
                0,
                $document['waehrung']
            );
            $this->db->perform(
                'UPDATE `rechnung_position` SET `auftrag_position_id` = :orderPositionId WHERE `id` = :id',
                [
                    'orderPositionId' => $orderPositionId,
                    'id' => $positionId,
                ]
            );
        }

        return $positionId;
    }

    /**
     * @param string   $type
     * @param int      $documentId
     * @param array    $documentRows
     * @param array    $positions
     * @param int|null $orderId
     */
    private function addDiscountArticles($type, $documentId, $documentRows, $positions, $orderId = null): void
    {
        if (empty($documentId)
            || empty($documentRows)
            || empty($positions)
            || $documentId <= 0
            || !in_array($type, ['invoice', 'returnorder'])) {
            return;
        }
        $doctype = $type === 'invoice' ? 'rechnung' : 'gutschrift';
        foreach ($documentRows as $documentRow) {
            if (empty($documentRow['itemdiscount_price'])) {
                continue;
            }
            foreach ($documentRow['itemdiscount_price'] as $taxRate => $price) {
                if ($price == 0) {
                    continue;
                }

                $positionId = (int)$this->addDiscountPosition($doctype, $documentId, $taxRate, $price);
                if ($orderId !== null) {
                    $orderPositionId = (int)$this->addDiscountPosition('auftrag', $orderId, $taxRate, $price);
                    $this->db->perform(
                        'UPDATE `rechnung_position` SET `auftrag_position_id` = :orderPositionId WHERE `id` = :id',
                        [
                            'orderPositionId' => $orderPositionId,
                            'id' => $positionId,
                        ]
                    );
                }
            }
        }
    }

    /**
     * @param string   $type
     * @param int      $documentId
     * @param array    $documentRows
     * @param array    $positions
     * @param int|null $orderId
     */
    private function addShippingArticles($type, $documentId, $documentRows, $positions, $orderId = null): void
    {
        if (empty($documentId)
            || empty($documentRows)
            || empty($positions)
            || $documentId <= 0
            || !in_array($type, ['invoice', 'returnorder'])) {
            return;
        }
        $doctype = $type === 'invoice' ? 'rechnung' : 'gutschrift';
        foreach ($documentRows as $documentRow) {
            if (empty($documentRow['shipping_price_with_discount'])) {
                continue;
            }
            foreach ($documentRow['shipping_price_with_discount'] as $taxRate => $price) {
                if ($price == 0) {
                    continue;
                }

                $positionId = (int)$this->addShippingPosition(
                    $doctype,
                    $documentId,
                    $taxRate,
                    $price / (1 + $taxRate / 100)
                );
                if ($orderId !== null) {
                    $orderPositionId = (int)$this->addShippingPosition(
                        'auftrag',
                        $orderId,
                        $taxRate,
                        $price / (1 + $taxRate / 100)
                    );
                    $this->db->perform(
                        'UPDATE `rechnung_position` SET `auftrag_position_id` = :orderPositionId WHERE `id` = :id',
                        [
                            'orderPositionId' => $orderPositionId,
                            'id' => $positionId,
                        ]
                    );
                }
            }
        }
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    private function getShopIdFromExternalNumber(string $sku): int
    {
        return (int)$this->db->fetchValue(
            "SELECT af.shopid 
            FROM `artikelnummer_fremdnummern` AS `af`
            INNER JOIN `shopexport` AS `s` ON af.shopid = s.id AND s.aktiv = 1 
                                                  AND s.modulename LIKE 'shopimporter_amazon%'
            WHERE af.aktiv = 1 AND af.nummer = :nummer AND af.nummer <> '' 
            LIMIT 1",
            ['nummer' => trim($sku)]
        );
    }

    /**
     * @param string   $type
     * @param int      $documentId
     * @param array    $positions
     * @param int|null $orderId
     */
    private function addPositions($type, $documentId, $positions, $orderId = null): void
    {
        if ($type === 'invoice') {
            $this->db->perform(
                'DELETE FROM `rechnung_position` WHERE `rechnung` = :invoice',
                ['invoice' => $documentId]
            );
            foreach ($positions as $key => $position) {
                if ($position['quantity'] == 0) {
                    $position['quantity'] = 1;
                }
                $dataWithExternalNumber = null;
                $shopId = $this->getShopIdFromExternalNumber((string)$position['sku']);
                if ($shopId > 0) {
                    $dataWithExternalNumber = ['fremdnummer' => trim($position['sku'])];
                }

                $positionId = (int)$this->app->erp->AddPositionManuellPreisNummer(
                    'rechnung',
                    $documentId,
                    0,
                    $position['sku'],
                    $position['quantity'],
                    !isset($position['name']) ? $position['productname'] : $position['name'],
                    $position['taxrate'] > 0
                        ? $position['itemprice'] / $position['quantity'] / (1 + $position['taxrate'] / 100) :
                        $position['itemprice_netto'] / $position['quantity'],
                    '',
                    0,
                    $shopId,
                    $position['currency'],
                    $dataWithExternalNumber
                );
                $this->db->perform(
                    'UPDATE `rechnung_position` 
                    SET `steuersatz` = :taxRate, preis = :price 
                    WHERE `id` = :id',
                    [
                        'taxRate' => $position['taxrate'],
                        'price' => $position['taxrate'] > 0
                            ? $position['itemprice'] / $position['quantity'] / (1 + $position['taxrate'] / 100) :
                            $position['itemprice_netto'] / $position['quantity'],
                        'id' => $positionId,
                    ]
                );
                if ($orderId !== null) {
                    $orderPositionId = (int)$this->app->erp->AddPositionManuellPreisNummer(
                        'auftrag',
                        $orderId,
                        0,
                        $position['sku'],
                        $position['quantity'],
                        !isset($position['name']) ? $position['productname'] : $position['name'],
                        $position['taxrate'] > 0
                            ? $position['itemprice'] / $position['quantity'] / (1 + $position['taxrate'] / 100) :
                            $position['itemprice_netto'] / $position['quantity'],
                        '',
                        0,
                        $shopId,
                        $position['currency'],
                        $dataWithExternalNumber
                    );
                    $this->db->perform(
                        'UPDATE `auftrag_position` 
                        SET `steuersatz` = :taxRate, preis = :price 
                        WHERE `id` = :id',
                        [
                            'taxRate' => $position['taxrate'],
                            'price' => $position['taxrate'] > 0
                                ? $position['itemprice'] / $position['quantity'] / (1 + $position['taxrate'] / 100) :
                                $position['itemprice_netto'] / $position['quantity'],
                            'id' => $orderPositionId,
                        ]
                    );
                    $this->db->perform(
                        'UPDATE `rechnung_position` 
                        SET `auftrag_position_id` = :orderPositionId 
                        WHERE `id` = :id',
                        [
                            'orderPositionId' => $orderPositionId,
                            'id' => $positionId,
                        ]
                    );
                }
                if (!empty($position['id'])) {
                    $this->db->perform(
                        'UPDATE `amazoninvoice_position` 
                        SET `doctype_id` = :doctype_id, `doctype` = :doctype, `position_id` = :position_id 
                        WHERE `id` = :id',
                        [
                            'doctype_id' => (int)$documentId,
                            'doctype' => 'rechnung',
                            'position_id' => (int)$positionId,
                            'id' => (int)$position['id'],
                        ]
                    );
                }
                if (
                    !empty($position['giftwrap_price_net_with_discount'])
                    && $position['giftwrap_price_net_with_discount'] != 0.0
                ) {
                    $this->addGiftWrapPositon(
                        'rechnung',
                        (int)$documentId,
                        $position['quantity'],
                        $position['giftwrap_price_net_with_discount'],
                        $orderId
                    );
                }
            }
        } else {
            $this->db->perform(
                'DELETE FROM `gutschrift_position` WHERE `gutschrift` = :returnorder',
                ['returnorder' => $documentId]
            );
            foreach ($positions as $key => $position) {
                if ($position['quantity'] == 0) {
                    $position['quantity'] = 1;
                }
                $dataWithExternalNumber = null;
                $shopId = $this->getShopIdFromExternalNumber((string)$position['sku']);
                if ($shopId > 0) {
                    $dataWithExternalNumber = ['fremdnummer' => trim($position['sku'])];
                }
                $positionId = $this->app->erp->AddPositionManuellPreisNummer(
                    'gutschrift',
                    $documentId,
                    0,
                    $position['sku'],
                    $position['quantity'],
                    $position['name'],
                    $position['price'],
                    '',
                    0,
                    $shopId,
                    $position['currency'],
                    $dataWithExternalNumber
                );
                $this->db->perform(
                    'UPDATE `gutschrift_position` 
                    SET `steuersatz` = :taxRate, preis = :price 
                    WHERE `id` = :id',
                    [
                        'taxRate' => $position['taxrate'],
                        'price' => $position['taxrate'] > 0
                            ? $position['bruttototal'] / $position['quantity'] / (1 + $position['taxrate'] / 100) :
                            $position['nettototal'] / $position['quantity'],
                        'id' => $positionId,
                    ]
                );
                if (!empty($position['id'])) {
                    $this->db->perform(
                        'UPDATE `amazoninvoice_position` 
                        SET `doctype_id` = :doctype_id, `doctype` = :doctype, `position_id` = :position_id 
                        WHERE `id` = :id',
                        [
                            'doctype_id' => (int)$documentId,
                            'doctype' => 'gutschrift',
                            'position_id' => (int)$positionId,
                            'id' => (int)$position['id'],
                        ]
                    );
                }
                if (
                    !empty($position['giftwrap_price_net_with_discount'])
                    && $position['giftwrap_price_net_with_discount'] != 0.0
                ) {
                    $this->addGiftWrapPositon(
                        'gutschrift',
                        (int)$documentId,
                        $position['quantity'],
                        $position['giftwrap_price_net_with_discount']
                    );
                }
            }
        }
    }

    /**
     * @param string $type
     * @param int    $documentId
     * @param string $file
     */
    private function archiveDocument($type, $documentId, $file): void
    {
        if ($type === 'invoice') {
            $Brief = new RechnungPDF($this->app);
            $table = 'rechnung';
        } else {
            $Brief = new GutschriftPDF($this->app);
            $table = 'gutschrift';
        }
        $file = $this->app->erp->GetTMP() . $file;
        if (!is_file($file)) {
            throw new InvalidArgumentException(sprintf('file %s not found', $file));
        }
        $list = $Brief->getArchivedFiles($documentId, $table);
        if (!empty($list)) {
            foreach ($list as $pdfArchive) {
                if (empty($pdfArchive['schreibschutz'])) {
                    continue;
                }
                $dir = $this->app->Conf->WFuserdata . '/pdfarchiv/' . $this->app->Conf->WFdbname . '/' . $table;
                $pdfFile = Briefpapier::getPDFfolder($dir, $documentId, $pdfArchive['dateiname']);

                if (@rename($file, $pdfFile)) {
                    $this->db->perform(
                        'UPDATE `pdfarchiv` SET `checksum` = :checksum WHERE `id` = :id',
                        ['checksum' => md5_file($pdfFile), 'id' => $pdfArchive['id']]
                    );

                    return;
                }
                throw new InvalidArgumentException(sprintf('could not write file %s', $pdfFile));
            }
        }
    }

    /**
     * @param $thresholdCountry
     */
    private function tryUpdateDateTaxItem($thresholdCountry): void
    {
        $date = null;
        if (isset($thresholdCountry->date)) {
            $date = $thresholdCountry->date;
        }
        if (empty($date)) {
            $date = null;
        } elseif (strpos($date, '.') !== false) {
            $date = explode('.', $date);
            try {
                $date = new DateTime($date[2] . '-' . $date[1] . '-' . $date[0]);
            } catch (\Exception $e) {
                $date = null;
            }
        } else {
            try {
                $date = new DateTime($date);
            } catch (\Exception $e) {
                $date = null;
            }
        }
        $vatNumber = '';
        if (isset($thresholdCountry->vatNumber)) {
            $vatNumber = $thresholdCountry->vatNumber;
        }
        $this->tryUpdateTurnoverThreshold(
            (string)$thresholdCountry->country,
            $vatNumber,
            !empty($thresholdCountry->isThresholdCountry),
            $date
        );
    }

    /**
     * @param $basicDataTaxJson
     */
    private function importBasicDataTaxJson($basicDataTaxJson): void
    {
        if (!empty($basicDataTaxJson->thresholdCountries)) {
            foreach ($basicDataTaxJson->thresholdCountries as $thresholdCountry) {
                $this->tryUpdateDateTaxItem($thresholdCountry);
            }
        }
        if (empty($basicDataTaxJson->closedThresholds)) {
            return;
        }
        foreach ($basicDataTaxJson->closedThresholds as $thresholdCountry) {
            $this->tryUpdateDateTaxItem($thresholdCountry);
        }
    }

    /**
     * @param string                 $countryCode
     * @param string                 $vatNumber
     * @param bool                   $isActive
     * @param DateTimeInterface|null $activeSince
     *
     * @return int
     */
    private function createTurnoverThreshold(
        string $countryCode,
        string $vatNumber,
        bool $isActive,
        ?DateTimeInterface $activeSince
    ): int {
        $this->db->perform(
            'INSERT INTO `amainvoice_turnoverthreshold` 
            (`country_code`, `vat_number`, `is_active`, `active_since`, `turnoverthreshold_id`) 
            VALUES (:country_code, :vat_number, :is_active, :active_since, 0)',
            [
                'country_code' => $countryCode,
                'vat_number' => $vatNumber,
                'is_active' => (int)$isActive,
                'active_since' => $activeSince === null ? null : $activeSince->format('Y-m-d'),
            ]
        );

        return $this->db->lastInsertId();
    }

    /**
     * @param int                    $thresholdId
     * @param string                 $vatNumber
     * @param bool                   $isActive
     * @param DateTimeInterface|null $activeSince
     */
    private function updateTurnoverThreshold(
        int $thresholdId,
        string $vatNumber,
        bool $isActive,
        ?DateTimeInterface $activeSince
    ): void {
        $this->db->perform(
            'UPDATE `amainvoice_turnoverthreshold` 
            SET `vat_number` = :vat_number, `is_active` = :is_active, `active_since` = :active_since
            WHERE `id` = :id',
            [
                'id' => $thresholdId,
                'vat_number' => $vatNumber,
                'is_active' => (int)$isActive,
                'active_since' => $activeSince === null ? null : $activeSince->format('Y-m-d'),
            ]
        );
    }

    /**
     * @param string $countryCode
     *
     * @return int|null
     */
    private function getTurnoverThresholdIdByCountryCode(string $countryCode): ?int
    {
        $id = $this->db->fetchValue(
            'SELECT `id` FROM `amainvoice_turnoverthreshold` WHERE `country_code` = :country_code',
            ['country_code' => $countryCode]
        );
        if ($id === false) {
            return null;
        }

        return (int)$id;
    }

    /**
     * @param string                 $countryCode
     * @param string                 $vatNumber
     * @param bool                   $isActive
     * @param DateTimeInterface|null $activeSince
     *
     * @return int
     */
    private function tryUpdateTurnoverThreshold(
        string $countryCode,
        string $vatNumber,
        bool $isActive,
        ?DateTimeInterface $activeSince
    ): int {
        $id = $this->getTurnoverThresholdIdByCountryCode($countryCode);
        if ($id === null) {
            return $this->createTurnoverThreshold($countryCode, $vatNumber, $isActive, $activeSince);
        }
        $this->updateTurnoverThreshold($id, $vatNumber, $isActive, $activeSince);

        return $id;
    }
}
