<?php

declare(strict_types=1);

namespace App\Modules\ShopimporterAmazon\Actions;

use App\Modules\Accounting\Models\Invoice;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;

class FilterOutInvoicesWithManuallyCreatedCreditNotesAction
{
    /** @var ConnectionInterface */
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(Collection $invoices): Collection
    {
        $creditNoteIdsByInvoiceId = $invoices->reduce(function (array $creditNoteIdsByInvoiceId, Invoice $invoice) {
            $creditNoteIds = $invoice->creditNotes()->pluck('id')->all();
            if (empty($creditNoteIds)) {
                return $creditNoteIdsByInvoiceId;
            }
            $creditNoteIdsByInvoiceId[$invoice->id] = $creditNoteIds;

            return $creditNoteIdsByInvoiceId;
        }, []);
        $allCreditNoteIds = array_reduce($creditNoteIdsByInvoiceId, function (array $allCreditNoteIds, array $creditNotedIds) {
            return array_merge($allCreditNoteIds, $creditNotedIds);
        }, []);
        $manuallyCreatedCreditNotes = array_diff(
            $allCreditNoteIds,
            $this->connection
                ->table('shopimporter_amazon_creditnotes_adjustmentid')
                ->whereIn('creditnote_id', $allCreditNoteIds)
                ->pluck('creditnote_id')
                ->all()
        );
        $invoiceIdsWithManuallyCreatedCreditNotes = array_keys(array_filter($creditNoteIdsByInvoiceId, function (array $creditNotesIds) use ($manuallyCreatedCreditNotes) {
            return !empty(array_intersect($creditNotesIds, $manuallyCreatedCreditNotes));
        }));

        return $invoices->filter(function (Invoice $invoice) use ($invoiceIdsWithManuallyCreatedCreditNotes) {
            return !in_array($invoice->id, $invoiceIdsWithManuallyCreatedCreditNotes);
        });
    }
}
