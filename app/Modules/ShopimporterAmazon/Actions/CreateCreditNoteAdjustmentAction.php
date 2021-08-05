<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use Illuminate\Database\ConnectionInterface;

class CreateCreditNoteAdjustmentAction
{
    /** @var ConnectionInterface */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(int $shopId, int $creditNoteId, int $articleId, int $invoiceId, string $adjustmentId): int
    {
        return $this->connection->table('shopimporter_amazon_creditnotes_adjustmentid')->insertGetId(
            [
                'shop_id' => $shopId,
                'creditnote_id' => $creditNoteId,
                'article_id' => $articleId,
                'adjustmentid' => $adjustmentId,
            ]
        );
    }
}
