<?php

declare(strict_types=1);

namespace Xentral\Modules\SepaHbciFints\Data;

final class BankUrlData
{
    private const BANKCODE_COLUMN = 1;

    private const BIC_COLUMN = 2;

    private const URL_COLUMN = 24;

    /** @var string $bankCode */
    private $bankCode;

    /** @var string $bic */
    private $bic;

    /** @var string $url */
    private $url;

    public function __construct(string $bankCode, string $bic, string $url)
    {
        $this->bankCode = $bankCode;
        $this->bic = $bic;
        $this->url = $url;
    }

    /**
     * @param array $columns
     *
     * @return BankUrlData|null
     */
    public static function fromCsv(array $columns): ?BankUrlData
    {
        //the testing file had many empty lines with only an id at the bottom
        //therefore this check
        if (empty($columns[1])) {
            return null;
        }
        $instance = new self(
            $columns[self::BANKCODE_COLUMN],
            $columns[self::BIC_COLUMN],
            $columns[self::URL_COLUMN]
        );

        return $instance;
    }

    /**
     * @return string
     */
    public function getBankCode(): string
    {
        return $this->bankCode;
    }

    /**
     * @return string
     */
    public function getBic(): string
    {
        return $this->bic;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
}
