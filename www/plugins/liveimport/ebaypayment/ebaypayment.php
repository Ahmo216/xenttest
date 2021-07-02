<?php
use Xentral\Components\HttpClient\HttpClientFactory;
use Xentral\Modules\Ebay\Client\EbayRestApiClient;
use Xentral\Modules\Ebay\Module\EbayRestApiModule;
use Xentral\Modules\Ebay\Gateway\EbayRestApiGateway;
use Xentral\Modules\EbayPayment\Service\EbayPaymentDocumentService;
use Xentral\Modules\PaymentTransaction\Service\PaymentDocumentService;

class ebaypayment extends LiveimportBase
{
  private const FULFILLMENT_API_URL = 'https://api.ebay.com/sell/fulfillment';

  private const FINANCE_API_URL = 'https://apiz.ebay.com/sell/finances';

  private const REFUND_API = '/v1/order/{ORDERID}/issue_refund';

  /** @var int $numberofdays */
  private $numberofdays;

  private $json;

  private $lastError;

  private $APIDays;

  private $shopId;

  /** @var EbayRestApiModule $restApi */
  private $restApi;

  /** @var EbayPaymentDocumentService $ebayDocumentService */
  private $ebayDocumentService;

  /** @var PaymentDocumentService $documentService */
  private $documentService;

  /** @var EbayRestApiGateway */
  private $ebayRestApiGateway;

  private $datev = [];

  /**
   * ebaypayment constructor.
   *
   * @param null|Application $app
   * @param false            $intern
   */
  public function __construct($app = null, $intern = false)
  {
    if($app !== null){
      $this->app = $app;
    }
    $this->numberofdays = 5;
  }


  /**
   * @param array $zugangsdaten
   *
   * @return array
   */
  protected function loadCredentials(array $credentialArr): array
  {
    if(empty($credentialArr)){
      return [];
    }

    $this->APIDays = !empty($credentialArr['API_DAYS']) ? $credentialArr['API_DAYS'] : 5;
    $this->shopId = !empty($credentialArr['SHOP_ID']) ? (int)$credentialArr['SHOP_ID'] : 0;
    $this->datev['SALEFEE'] = $credentialArr['DATEV_SALE_FEE'] ?? '';
    $this->datev['SHIPPING_LABEL'] = $credentialArr['DATEV_SHIPPING_LABEL'] ?? '';
    $this->datev['TRANSFER'] = $credentialArr['DATEV_TRANSFER'] ?? '';
    $this->datev['NON_SALE_CHARGE'] = $credentialArr['DATEV_NON_SALE_CHARGE'] ?? '';
    $this->datev['OTHER'] = $credentialArr['DATEV_OTHER'] ?? '';
    if($this->APIDays > 0){
      $this->numberofdays = $this->APIDays;
    }

    return $credentialArr;
  }

  /**
   * @return  array
   */
  public function showReturnOrderStructure(): array
  {
    return [
      'legend1' => [
        'typ' => 'legend',
        'bezeichnung' => 'Betrag',
      ],
      'betrag' => [
        'bezeichnung' => 'Betrag:',
        'notd' => true,
        'typ' => 'price',
        'size' => 12,
      ],
      'waehrung' => [
        'bezeichnung' => ' ',
        'autocomplete' => 'waehrung',
        'size' => 8,
      ],
      'legend2' => [
        'typ' => 'legend',
        'bezeichnung' => 'Ebay OrderId',
      ],
      'internet' => [
        'bezeichnung' => ' ',
      ],
    ];
  }

  /**
   * @param int[] $paymentTransactionIds
   *
   * @return int
   */
  public function createReturnOrdersPaymentEntries($paymentTransactionIds)
  {
    return parent::createReturnOrdersPaymentEntries($paymentTransactionIds);
  }

  /**
   * @param array|int|null $paymentTransaction
   *
   * @return array|bool
   */
  public function isPaymentTransactionOk($paymentTransaction)
  {
    $order = parent::isPaymentTransactionOk($paymentTransaction);
    if($order === false){
      return false;
    }
    $isValidManual = $order['returnorder_id'] === 0
      && $order['liability_id'] === 0
      && !empty($order['json'])
      && is_array($order['json'])
      && !empty($order['json']['vz1'])
      && strlen($order['json']['vz1']) >= 14;
    if($isValidManual){
      $order['internet'] = (string)$order['json']['vz1'];

      return $order;
    }
    if(empty($order['returnorder_id'])){
      return false;
    }
    $creditNote = $this->documentService->getOrderIdFromCreditNoteId((int)$order['returnorder_id']);
    if(empty($creditNote)){
      return false;
    }
    $order['internet'] = (string)$creditNote['internet'];

    return $order;
  }

  /**
   * @param int $paymentTransactionId
   *
   * @return bool
   */
  public function createReturnOrderPaymentEntry($paymentTransactionId)
  {
    $orders = $this->isPaymentTransactionOk($paymentTransactionId);
    if($orders === false){
      return false;
    }

    $return = parent::createReturnOrderPaymentEntry($paymentTransactionId);
    if($return){
      $json = @json_decode(
        $this->app->DB->Select(
          sprintf(
            'SELECT `payment_json` FROM `payment_transaction` WHERE id = %d',
            $paymentTransactionId
          )
        ),
        true
      );
      if(!empty($json['internet'])){
        $this->app->DB->Update(
          sprintf(
            "UPDATE `payment_transaction` SET `payment_reason` = '%s' WHERE id = %d",
            $this->app->DB->real_escape_string($json['internet']),
            $paymentTransactionId
          )
        );
      }elseif(is_array($json)){
        $isValidManual = $orders['returnorder_id'] === 0
          && $orders['liability_id'] === 0
          && !empty($orders['json'])
          && is_array($orders['json'])
          && !empty($orders['json']['vz1'])
          && strlen($orders['json']['vz1']) >= 14;
        if($isValidManual){
          $json['internet'] = (string)$orders['json']['vz1'];
          $this->app->DB->Update(
            sprintf(
              "UPDATE `payment_transaction` SET `payment_reason` = '%s', `payment_json` = '%s' WHERE id = %d",
              $this->app->DB->real_escape_string($json['internet']),
              $this->app->DB->real_escape_string(json_encode($json)),
              $paymentTransactionId
            )
          );
        }
      }
    }

    return $return;
  }

  /**
   * @param array $order
   *
   * @return array
   */
  public function getJsonFormPaymentEntry($order): array
  {
    $ret = [];
    if(empty($order['returnorder_id'])){
      return [];
    }

    $transactionnumber = $this->documentService->getOrderIdFromCreditNoteId((int)$order['returnorder_id']);
    if(empty($transactionnumber)){
      return [];
    }
    $ret['internet'] = $transactionnumber['internet'];
    $ret['waehrung'] = $transactionnumber['waehrung'];
    if(empty($ret['waehrung'])){
      $ret['waehrung'] = 'EUR';
    }
    $ret['betrag'] = str_replace(',', '', $transactionnumber['soll']);

    return $ret;
  }

  /**
   * @param array $returnOrders
   * @param array $liabilities
   * @param array $transactions
   *
   * @return array
   */
  public function createPayments($returnOrders, $liabilities, $transactions)
  {
    $checkPayment = $this->checkPayments($returnOrders, $liabilities, $transactions);
    if(empty($checkPayment['status'])){
      return $checkPayment;
    }

    $ids = array_merge($returnOrders, $liabilities);

    $idsOk = $this->app->DB->SelectFirstCols(
      sprintf(
        "SELECT `id` 
        FROM `payment_transaction` AS `pt` 
        WHERE `payment_reason` <> '' AND `amount` > 0 AND pt.id IN (%s)",
        implode(',', $ids)
      )
    );

    $transactionRows = $this->app->DB->SelectArr(
      sprintf(
        "SELECT pt.* FROM `payment_transaction` AS `pt` WHERE pt.id IN (%s)",
        implode(',', $idsOk)
      )
    );

    $this->loadCredentialsFromId();

    foreach ($transactionRows as $transactionRow) {
      $currency = empty($transactionRow['currency']) ? 'EUR' : $transactionRow['currency'];
      if(empty($transactionRow['payment_reason'])){
        $this->app->DB->Update(
          sprintf(
            "UPDATE `payment_transaction` SET `payment_status` = 'error' WHERE `id` = %d",
            $transactionRow['id']
          )
        );
        continue;
      }
      $this->refund((string)$transactionRow['payment_reason'], (float)$transactionRow['amount'], (string)$currency);
      if(!empty($this->json) && !empty($this->json->refundId)){
        $this->app->DB->Update(
          sprintf(
            "UPDATE `payment_transaction` SET `payment_status` = 'verbucht' WHERE `id` = %d", $transactionRow['id']
          )
        );
        $this->removeJsonEntry($transactionRow['id'], 'error_message');
      }elseif(!empty($this->lastError)){
        $this->app->DB->Update(
          sprintf(
            "UPDATE `payment_transaction` SET `payment_status` = 'error' WHERE `id` = %d", $transactionRow['id']
          )
        );
        $this->addJsonEntry($transactionRow['id'], 'error_message', $this->lastError);
      }
    }

    return $checkPayment;
  }

  /**
   * @param array $returnOrders
   * @param array $liabilities
   * @param array $transactions
   *
   * @return array
   */
  public function checkPayments($returnOrders, $liabilities, $transactions): array
  {
    $ids = array_merge($returnOrders, $liabilities);
    $idsOk = [];

    if(!empty($ids)){
      $idsNotOk = $this->app->DB->SelectFirstCols(
        sprintf(
          "SELECT `id`
          FROM `payment_transaction` AS `pt` 
          WHERE `payment_reason` = '' AND `amount` > 0 
            AND pt.id IN (%s)",
          implode(',', $ids)
        )
      );

      if(!empty($idsNotOk)){
        foreach ($idsNotOk as $id) {
          $this->afterSavePaymentTransaction($id);
        }
      }

      $idsOk = $this->app->DB->SelectFirstCols(
        sprintf(
          "SELECT `id`
          FROM `payment_transaction` AS `pt` 
          WHERE `payment_reason` != '' AND `amount` > 0 
            AND pt.id IN (%s)",
          implode(',', $ids)
        )
      );
    }

    $countOK = count($idsOk);

    $ret = ['idsstring' => implode(';', $transactions), 'status' => 1, 'accountid' => $this->id];
    if($countOK === 0){
      $ret['status'] = 0;
      $ret['error'] = 'Die Zahlungen können nicht erstellt werden';
      return $ret;
    }
    if(count($ids) === $countOK){
      return $ret;
    }
    $ret['error'] = 'Es können nicht alle Zahlungen erstellt werden';

    return $ret;
  }

  /**
   * @param int $paymentTransactionId
   */
  public function afterSavePaymentTransaction($paymentTransactionId): void
  {
    $paymentTransaction = $this->app->DB->SelectRow(
      sprintf(
        "SELECT `id`, `payment_reason`, `payment_json` 
          FROM `payment_transaction` AS `pt` 
          WHERE  `amount` > 0 
            AND pt.id = %d",
        $paymentTransactionId
      )
    );
    if(empty($paymentTransaction['payment_json'])){
      return;
    }
    $json = json_decode($paymentTransaction['payment_json'], true);
    if(empty($json)){
      return;
    }
    if(empty($json['internet']) || $json['internet'] === $paymentTransaction['payment_reason']){
      return;
    }
    $this->app->DB->Update(
      sprintf(
        "UPDATE `payment_transaction` SET `payment_reason` = '%s' WHERE `id` = %d ",
        $this->app->DB->real_escape_string($json['internet']), $paymentTransactionId
      )
    );
  }

  protected function loadCredentialsFromId(): void
  {
    $this->loadCredentials($this->getCredentialsFromId());
  }

  /**
   * @param Konten            $paymentObj
   * @param int               $accountId
   * @param DateTimeInterface $startDateInterval
   */
  public function createJobsFromStartDate(Konten $paymentObj, int $accountId, DateTimeInterface $startDateInterval): void
  {
    $oneHourInterval = new DateInterval('PT1H');
    $now = new DateTime();
    $hourPeriode = new DatePeriod(
      $startDateInterval,
      $oneHourInterval,
      $now
    );

    foreach ($hourPeriode as $value) {
      $nextHour = clone $value;
      $nextHour->add($oneHourInterval);
      if($nextHour >= $now){
        $nextHour->sub($oneHourInterval);
        break;
      }
    }
    $paymentObj->CreatePaymentsByRangeIfNotExists(
      $accountId, $startDateInterval, $nextHour, $oneHourInterval
    );
  }

  /**
   * @param array            $zugangsdaten
   * @param null|int         $accountId
   * @param null|Application $app
   *
   * @return string
   * @throws Exception
   */
  public function Import($zugangsdaten, $accountId = null, $app = null)
  {
    $fromCronjob = defined('CRONJOBUID');
    $this->loadCredentials($zugangsdaten);
    if(empty($this->shopId)){
      throw new Exception('No Shop set');
    }

    $paymentObj = null;
    if($accountId !== null && $app !== null){
      $this->loadApp($app, $accountId);
      /** @var Konten $paymentObj */
      $paymentObj = $app->loadModule('konten');
    }
    $ebayShopsWithRestApi = array_keys(self::getEbayShops($app, true));
    if(empty($ebayShopsWithRestApi)){
      throw new Exception("No Ebay-shops found with activated REST-API");
    }
    if(!in_array($this->shopId, $ebayShopsWithRestApi)){
      throw new Exception("ShopId {$this->shopId} is no valid Ebay-shop with REST-API");
    }
    $restToken = $this->ebayRestApiGateway->tryGetRestApiAccessTokenFromDatabase($this->shopId, EbayRestApiClient::TOKEN_TYPE_USER);
    if($restToken === null){
      throw new Exception("ShopId {$this->shopId} has no valid REST-API tokens");
    }
    $date = new DateTime(date('Y-m-d'));
    $date->sub(new DateInterval('P' . $this->numberofdays . 'D'));
    $days_ago = $date->format('Y-m-d');

    $begin = new DateTime(date('Y-m-d'));
    $begin->modify('+1 day');  // muss ein tag in der zukunft sein
    $days_today = $begin->format('Y-m-d');

    $today1 = new DateTime(date('Y-m-d'));
    $today1->modify('-2 day');
    $today = new DateTime(date('Y-m-d'));
    $today->modify('-1 day');

    $startDateInterval = new DateTime($days_ago);
    $jobs = [];
    $outstr = "Datum, Zeit, Zeitzone, Name, Art\r\n";
    if($fromCronjob && $paymentObj !== null){
      $this->createJobsFromStartDate($paymentObj, (int)$accountId, $startDateInterval);
      $jobs = $paymentObj->GetPaymentAccountImportJobsByStatus($accountId);
      if(empty($jobs)){
        return utf8_decode($outstr);
      }
    }

    $jobsToImport = [];
    if($fromCronjob && $paymentObj !== null){
      $result = [];
      foreach ($jobs as $job) {
        $result2 = $this->getSplitedTime($job['from'], $job['to']);
        $cresult = empty($result2) ? 0 : count($result2);
        if($cresult === 0){
          $paymentObj->UpdatePaymentAccountImportJobStatus($job['id'], 'imported');
          continue;
        }
        for ($i = 0; $i < $cresult; $i++) {
          $result[] = $result2[$i];
        }
        $jobsToImport[] = $job;
        if(count($result) > 500){
          break;
        }
      }
      if(empty($result)){
        return utf8_decode($outstr);
      }
    }else{
      $oneDayInterval = new DateInterval('P1D');
      $period = new DatePeriod(
        new DateTime($days_ago),
        $oneDayInterval,
        new DateTime($days_today)
      );

      $result = [];

      foreach ($period as $value) {
        $result2 = $this->getSplitedTime($value->format('Y-m-d'));
        $cresult = empty($result2) ? 0 : count($result2);
        for ($i = 0; $i < $cresult; $i++) {
          $result[] = $result2[$i];
        }
      }
    }

    foreach ($result as $transaction) {
      $outstr .= $this->addFromTransactionObject($transaction);
    }

    if(!empty($jobsToImport)){
      foreach ($jobsToImport as $job) {
        $paymentObj->UpdatePaymentAccountImportJobStatus($job['id'], 'imported');
      }
    }

    return $outstr;
  }

  /**
   * @param string           $csv
   * @param int              $konto
   * @param null|Application $app
   *
   * @return array
   */
  public function ImportKontoauszug($csv, $konto, $app = null)
  {
    if($app !== null){
      $this->app = $app;
      $this->loadApp($app, $konto);
    }
    $dbArray = preg_split("/(\r\n)+|(\n|\r)+/", $csv);
    if(empty($dbArray)){
      return array(0, 0);
    }
    $inserted = 0;
    $duplicate = 0;
    $importgroup = $this->app->User->GetID() > 0 ? time() : 0;
    foreach ($dbArray as $key => $item) {
      if($key === 0){
        continue;
      }
      $columns = str_getcsv($item);
      if(count($columns) < 8){
        continue;
      }
      $transactionDate = $columns[0];
      $transactionId = $columns[1];
      $orderId = $columns[2];
      $itemId = $columns[3];
      $text = $columns[4];
      $currency = $columns[5];
      $amount = (string)number_format($columns[6], 2, '.', '');
      $transactionName = $columns[7];
      $transactionType = $columns[8] ?? '';
      $datev = '';
      if($transactionType !== 'SALE' && $transactionType !== '' && $transactionType !== 'REFUND'){
        $datev = $this->datev[$transactionType] ?? $this->datev['OTHER'];
      }
      $hash = md5(serialize([$transactionDate, $transactionId, $orderId, $itemId, $text, $amount, $currency]));
      $check = (int)$this->app->DB->Select(
        sprintf(
          "SELECT `id` FROM `kontoauszuege` WHERE `buchung` = '%s' AND `konto` = %d AND `pruefsumme` = '%s' LIMIT 1",
          $transactionDate, $konto, $hash
        )
      );
      if($check > 0){
        $duplicate++;
        continue;
      }
      $buchung = $transactionDate;
      $vorgang = $this->app->DB->real_escape_string($transactionName);
      $soll = (float)$amount > 0 ? 0 : -(float)$amount;
      $haben = (float)$amount > 0 ? $amount : 0;
      $gebuehr = 0;
      $waehrung = $currency;
      $bearbeiter = '';
      $pruefsumme = $hash;
      $gegenkonto = '';
      $sql = "INSERT INTO `kontoauszuege` (
          `konto`,
          `buchung`,
          `vorgang`,
          `soll`,
          `haben`,
          `gebuehr`,
          `waehrung`,
          `fertig`,
          `bearbeiter`,
          `pruefsumme`,
          `importgroup`,
          `originalbuchung`,
          `originalvorgang`,
          `originalsoll`,
          `originalhaben`,
          `originalgebuehr`,
          `originalwaehrung`,
          `gegenkonto`
        ) VALUE (
          '$konto',
          '$buchung',
          '$vorgang',
          '$soll',
          '$haben',
          '$gebuehr',
          '$waehrung',
          0,
          '" . $bearbeiter . "',
          '$pruefsumme',
          '$importgroup',
          '$buchung',
          '$vorgang',
          '$soll',
          '$haben',
          '$gebuehr',
          '$waehrung',
          '$gegenkonto')";
      $this->app->DB->Insert($sql);
      $newid = (int)$this->app->DB->GetInsertID();
      if($newid <= 0){
        continue;
      }
      if($this->addDatev($newid, $datev)){
        continue;
      }
      $inserted++;
      $order = $this->documentService->getOrderByOrderId($orderId);
      if(empty($order)){
        continue;
      }
      $invoice = $this->documentService->getInvoiceByIntOrderId((int)$order['id']);
      if(empty($invoice)){
        $this->app->DB->Update(
          "UPDATE `kontoauszuege` SET `doctype` = 'auftrag', `doctypeid` = {$order['id']} WHERE `id` = {$newid}"
        );
        continue;
      }
      if($haben > 0){
        $this->app->DB->Update(
          "UPDATE `kontoauszuege` SET `doctype` = 'rechnung', `doctypeid` = {$invoice['id']} WHERE `id` = {$newid}"
        );
        continue;
      }
      $creditNote = $this->documentService->getCreditNoteFromInvoiceId((int)$invoice['id']);
      if(empty($creditNote)){
        continue;
      }

      $this->app->DB->Update(
        "UPDATE `kontoauszuege` SET `doctype` = 'gutschrift', `doctypeid` = {$creditNote['id']} WHERE `id` = {$newid}"
      );
    }

    return [$inserted, $duplicate];
  }

  private function addDatev(int $newid, ?string $datev): bool
  {
    if(!empty($datev)){
      $this->app->DB->Update(
        "UPDATE `kontoauszuege` SET `vorauswahltyp` = 'datev', `vorauswahlparameter` = '${datev}' WHERE `id` = {$newid} LIMIT 1"
      );
      return true;
    }

    return false;
  }

  /**
   * @param mixed $transaction
   *
   * @return string
   * @throws Exception
   */
  public function addFromTransactionObject($transaction): string
  {
    $transactionId = (string)$transaction->transactionId;
    $orderId = (string)$transaction->orderId;
    $transactionDate = $transaction->transactionDate;
    $transactionDate = substr($transactionDate, 0, 10) . ' ' . substr($transactionDate, 11, 8);
    $transactionDate = new DateTime($transactionDate, new DateTimeZone('UTC'));
    $transactionDate->setTimezone(new DateTimeZone('Europe/Berlin'));
    $transactionDate = $transactionDate->format('Y-m-d');
    $bookingEntry = $transaction->bookingEntry;
    $amount = $transaction->amount;
    $orderLineItems = $transaction->orderLineItems ?? null;
    $status = $transaction->transactionStatus;
    $transactionType = $transaction->transactionType;
    $result = '';
    if($orderLineItems === null){
      $value = (isset($amount->convertedFromValue)
        ? (float)$amount->convertedFromValue
        : (float)$amount->value);
      if($value == 0){
        return '';
      }
      return $this->getEntryString(
        $transactionDate,
        $transactionId,
        $orderId,
        '',
        ($bookingEntry === 'CREDIT' ? 1 : -1) * $value,
        $amount->convertedFromCurrency ?? $amount->currency,
        '',
        $transactionType
      );
    }
    foreach ($orderLineItems as $orderLineItem) {
      $lineItemId = $orderLineItem->lineItemId;
      $base = round((float)$orderLineItem->feeBasisAmount->value, 4);

      $result .= $this->getEntryString(
        $transactionDate,
        $transactionId,
        $orderId,
        $lineItemId,
        ($bookingEntry === 'CREDIT' ? 1 : -1)
        * $base,
        $orderLineItem->feeBasisAmount->convertedFromCurrency ?? $orderLineItem->feeBasisAmount->currency,
        '',
        $transactionType,
      );

      if(!empty($orderLineItem->marketplaceFees)){
        foreach ($orderLineItem->marketplaceFees as $fee) {
          if($fee->amount->value == 0){
            continue;
          }
          $result .= $this->getEntryString(
            $transactionDate,
            $transactionId,
            $orderId,
            $lineItemId,
            ($bookingEntry === 'CREDIT' ? -1 : 1)
            * $fee->amount->value,
            $fee->amount->convertedFromCurrency ?? $fee->amount->currency,
            $fee->feeType,
            'SALEFEE'
          );
        }
      }
    }

    return $result;
  }

  /**
   * @param string      $transactionDate
   * @param string      $transactionId
   * @param string      $itemId
   * @param string      $orderId
   * @param float       $amount
   * @param string|null $currency
   * @param string      $text
   * @param string      $type
   *
   * @return string
   */
  public function getEntryString(
    string $transactionDate, string $transactionId, string $itemId, string $orderId,
    float $amount, ?string $currency, string $text = '', string $type = ''
  ): string
  {
    $element = [];
    $element[0] = $transactionDate;
    $element[1] = $transactionId;
    $element[2] = $orderId;
    $element[3] = $itemId;
    $element[4] = $text;
    $element[5] = $currency ?? 'EUR';
    $element[6] = $amount;
    $element[7] = trim(preg_replace('/\s\s+/', ' ',
        $transactionId . ' ' . $orderId
        . (empty($itemId) ? '' : (' ' . $itemId))
        . (empty($text) ? '' : (' ' . $text))
      )
    );
    $element[8] = $type;

    return implode(',', array_map(static function ($val) {
        return '"' . $val . '"';
      }, $element)) . "\r\n";
  }

  /**
   * @param string      $dateFromString
   * @param string|null $dateToString
   *
   * @return array
   */
  public function getSplitedTime(string $dateFromString, ?string $dateToString = null)
  {
    $dateFrom = new DateTime($dateFromString);
    if($dateToString === null){
      $dateTo = new DateTime($dateFromString);
      $dateTo->add(new DateInterval('P1D'));
    }else{
      $dateTo = new DateTime($dateToString);
    }
    $offset = 0;
    $transactions = [];
    do {
      $transactionArray = $this->getTransactions(
        $this->restApi->getRestApiUserAccessToken((int)$this->shopId),
        $dateFrom,
        $dateTo,
        null,
        $offset,
        1000
      );
      if(empty($transactionArray->total) || empty($transactionArray->transactions)){
        break;
      }
      foreach ($transactionArray->transactions as $transaction) {
        $transactions[] = $transaction;
      }

      $offset += 1000;
    } while (!empty($transactionArray->total) && $transactionArray->total >= $offset);

    return $transactions;
  }

  /**
   * @param string        $token
   * @param DateTime      $dateFrom
   * @param DateTime|null $dateTo
   * @param array|null    $transactionTypes
   * @param int           $offset
   * @param int|null      $limit
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getTransactions(
    string $token,
    DateTime $dateFrom,
    ?DateTime $dateTo = null,
    ?array $transactionTypes = null,
    int $offset = 0,
    ?int $limit = 50
  )
  {
    /** @var HttpClientFactory $factory */
    $factory = $this->app->Container->get('HttpClientFactory');
    $client = $factory->createClient();
    $headers = [
      'Authorization' => 'Bearer ' . $token,
    ];
    $dateFrom->setTimezone(new DateTimeZone('UTC'));
    $dateFromString = $dateFrom->format('Y-m-d\TH:i:s');

    $url = self::FINANCE_API_URL . '/v1/transaction?';
    $filterDate = "{$dateFromString}.000Z..";
    if($dateTo !== null){
      $dateTo->setTimezone(new DateTimeZone('UTC'));
      $dateToString = $dateTo->format('Y-m-d\TH:i:s');
      $filterDate .= "{$dateToString}.000Z";
    }
    $filter = "transactionDate:[{$filterDate}]";
    if($transactionTypes !== null){
      $filter .= sprintf(",transactionType:{%s}", implode('|', $transactionTypes));
    }
    if($limit > 1000){
      $limit = 1000;
    }
    $url .= http_build_query([
      'offset' => $offset,
      'limit' => $limit > 0 ? $limit : 20,
      'filter' => $filter,
    ]);
    $response = $client->request('GET', $url, $headers);

    return json_decode($response->getBody()->getContents());
  }

  /**
   * @param Application $app
   * @param int         $accountId
   */
  public function loadApp($app, $accountId)
  {
    parent::loadApp($app, $accountId);
    $this->restApi = $app->Container->get('EbayRestApiModule');
    $this->ebayDocumentService = $app->Container->get(EbayPaymentDocumentService::class);
    $this->documentService = $app->Container->get('PaymentDocumentService');
    $this->ebayRestApiGateway = $app->Container->get('EbayRestApiGateway');
  }

  /**
   * @param ApplicationCore|null $app
   * @param bool                 $onlyWithRestAccount
   *
   * @return array
   */
  public static function getEbayShops($app = null, bool $onlyWithRestAccount = false): array
  {
    if($app === null){
      return [];
    }
    /** @var EbayPaymentDocumentService $documentService */
    $documentService = $app->Container->get(EbayPaymentDocumentService::class);
    return $documentService->getEbayShops($onlyWithRestAccount, '(REST-API ist nicht aktiviert)');
  }

  /**
   * @param string $orderId
   * @param float  $amount
   * @param string $currency
   * @param string $reason
   * @param string $comment
   *
   * @return bool|string
   */
  public function refund(string $orderId, float $amount, string $currency = 'EUR', string $reason = 'BUYER_CANCEL', string $comment = '')
  {
    $this->json = null;
    if(empty($currency)){
      $currency = 'EUR';
    }
    $orderShop = $this->ebayDocumentService->getOrderShopIdFromOrderId($orderId);
    if($orderShop === null){
      $this->lastError = 'EbayImporter not found';
      return null;
    }
    $shopId = (int)$orderShop['shop'];

    try {
      $userAccessToken = $this->restApi->getRestApiUserAccessToken($shopId);
    } catch (Exception $e) {
      $this->lastError = $e->getMessage();
      return null;
    }
    if(!$userAccessToken){
      return null;
    }

    /** @var HttpClientFactory $factory */
    $factory = $this->app->Container->get('HttpClientFactory');
    $client = $factory->createClient();
    $headers = [
      'Authorization' => 'Bearer ' . $userAccessToken,
    ];

    $url = str_replace('{ORDERID}', $orderId, self::FULFILLMENT_API_URL . self::REFUND_API);

    $validReasons = [
      'BUYER_CANCEL',
      'SELLER_CANCEL',
      'ITEM_NOT_RECEIVED',
      'BUYER_RETURN',
      'ITEM_NOT_AS_DESCRIBED',
      'OTHER_ADJUSTMENT',
      'SHIPPING_DISCOUNT'
    ];
    if(!in_array($reason, $validReasons)){
      $this->lastError = "{$reason} is no valid reason.";
      return null;
    }

    $body = [
      'orderLevelRefundAmount' => [
        'value' => number_format($amount, 2, '.', ''),
        'currency' => $currency
      ],
      'reasonForRefund' => $reason,
      'comment' => $comment,
    ];

    try {
      $response = $client->request('POST', $url, $headers, json_encode($body));
    } catch (Exception $e) {
      $this->lastError = $e->getMessage();
      return null;
    }

    $result = $response->getBody()->getContents();

    $json = json_decode($result);
    if($json === null){
      $this->lastError = $result;
      return null;
    }

    return $json;
  }


  /**
   * @return array|string[][]
   */
  public static function structure($app = null): array
  {
    $options = self::getEbayShops($app);
    return [
      'SHOP_ID' => [
        'type' => 'select',
        'options' => $options,
        'label' => 'Ebay-Shop:',
        'vue_page' => 1,
        'required' => true,
      ],
      'DATEV_SALE_FEE' => [
        'type' => 'text',
        'label' => 'Konto Order Fee:',
      ],
      'DATEV_SHIPPING_LABEL' => [
        'type' => 'text',
        'label' => 'Konto SHIPPING_LABEL:',
      ],
      'DATEV_TRANSFER' => [
        'type' => 'text',
        'label' => 'Konto TRANSFER:',
      ],
      'DATEV_NON_SALE_CHARGE' => [
        'type' => 'text',
        'label' => 'Konto NON_SALE_CHARGE:',
      ],
      'DATEV_OTHER' => [
        'type' => 'text',
        'label' => 'Konto andere:',
      ],
    ];
  }

  /**
   * @param int $paymentAccountId
   */
  public function afterCreatePaymentAccounts($paymentAccountId): void
  {
    $this->app->DB->Update(
      sprintf(
        'UPDATE `konten` SET `importperiode_in_hours` = 1 WHERE `id` = %d',
        $paymentAccountId
      )
    );
  }
}
