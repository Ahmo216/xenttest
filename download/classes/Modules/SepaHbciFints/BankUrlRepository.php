<?php

declare(strict_types=1);

namespace Xentral\Modules\SepaHbciFints;

use Generator;
use Xentral\Components\Filesystem\Exception\FileNotFoundException;
use Xentral\Components\Filesystem\FilesystemInterface;
use Xentral\Components\Filesystem\PathInfo;
use Xentral\Modules\SepaHbciFints\Data\BankUrlData;
use Xentral\Modules\SepaHbciFints\Exception\BankDataFileHasWrongFormatException;
use Xentral\Modules\SepaHbciFints\Exception\BankDataNotFoundExceptionSepa;

final class BankUrlRepository
{

    /** @var FilesystemInterface $fileSystem */
    private $fileSystem;

    /** @var array|BankUrlData[] $data */
    private $data;

    private const CSV_SEPARATOR = ';';

    public function __construct(FilesystemInterface $fileSystem)
    {
        $this->fileSystem = $fileSystem;
        $urlFile = $this->getLatestFile();
        $this->evaluateData($urlFile);
    }

    /**
     * @param PathInfo $urlFile
     *
     * @throws FileNotFoundException
     * @return Generator
     *
     */
    private function getFileIterator(PathInfo $urlFile): Generator
    {
        $stream = $this->fileSystem->readStream($urlFile->getPath());

        while ($line = fgets($stream)) {
            yield $line;
        }

        if (is_resource($stream)) {
            fclose($stream);
        }
    }

    /**
     * @param PathInfo $urlFile
     */
    private function evaluateData(PathInfo $urlFile): void
    {
        $iterator = $this->getFileIterator($urlFile);
        $data = [];
        $severalLines = '';
        foreach ($iterator as $key => $line) {
            if ($key == 0) {
                continue;
            }

            $line = utf8_encode($line);

            $hasColumnStart = strstr($line, ';"') !== false;
            $hasColumnEnd = strstr($line, '";') !== false;

            if ($hasColumnStart && !$hasColumnEnd
            ) {
                $severalLines .= ' ' . $line;
                continue;
            }

            if (!$hasColumnStart && $hasColumnEnd) {
                $line = $severalLines . $line;
                $severalLines = '';
            }

            $columns = explode(self::CSV_SEPARATOR, $line);
            $urlData = BankUrlData::fromCsv($columns);
            if ($urlData !== null) {
                $data[$urlData->getBankCode()] = $urlData;
            }
        }

        $this->data = $data;
    }

    /**
     * @throws BankDataNotFoundExceptionSepa
     * @throws BankDataFileHasWrongFormatException
     * @return PathInfo
     *
     */
    private function getLatestFile(): PathInfo
    {
        $files = $this->fileSystem->listFiles();

        $highestTimestamp = 0;
        $lastFile = null;
        foreach ($files as $file) {
            if ($file->getTimestamp() > $highestTimestamp) {
                $highestTimestamp = $file->getTimestamp();
                $lastFile = $file;
            }
        }

        if (empty($lastFile)) {
            throw new BankDataNotFoundExceptionSepa(
                "No bank data file found."
            );
        }

        if ($lastFile->getExtension() !== 'csv') {
            throw new BankDataFileHasWrongFormatException(
                "File has wrong format: {$lastFile->getExtension()}"
            );
        }

        return $lastFile;
    }

    /**
     * @param string $bankCode
     *
     * @return BankUrlData|null
     */
    public function findBankDataByBankCode(string $bankCode): ?BankUrlData
    {
        return $this->data[$bankCode] ?? null;
    }
}
