<?php

namespace XentralAdapters\Shopify\Factories;

use DateTime;
use League\Csv\Reader;
use XentralAdapters\Shopify\Data\CsvTransaction;

class CsvTransactionFactory
{
  /**
   * @return CsvTransaction[]
   */
  public static function fromCsvString(string $csv): array
  {
    $document = Reader::createFromString($csv);
    $document->setHeaderOffset(0);
    $header = null;
    $transactions = [];

    foreach ($document->getRecords() as $record) {
      $transactions[] = self::fromCsvLine($record);
    }

    return $transactions;
  }

  protected static function fromCsvLine(array $data): CsvTransaction
  {
    return new CsvTransaction(
      DateTime::createFromFormat('Y-m-d H:i:s T', $data['Transaction Date']) ?: new DateTime(),
      $data['Type'],
      $data['Order'],
      $data['Payout Status'],
      DateTime::createFromFormat('Y-m-d H:i:s T', $data['Payout Date']) ?: new DateTime(),
      DateTime::createFromFormat('Y-m-d H:i:s T', $data['Available On']) ?: new DateTime(),
      $data['Amount'],
      $data['Fee'],
      $data['Net'],
      $data['Checkout'],
      $data['Card Source'],
      $data['Card Brand'],
      $data['Currency'] ?: null
    );
  }
}
