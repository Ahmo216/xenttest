<?php

declare(strict_types=1);

namespace Xentral\Modules\SubscriptionCycle\Scheduler;

use DateTimeInterface;
use Xentral\Modules\SubscriptionCycle\Service\SubscriptionCycleJobService;
use Xentral\Modules\SubscriptionCycle\SubscriptionModuleInterface;

final class SubscriptionCycleFullTask
{
    /** @var TaskMutexServiceInterface $taskMutexService */
    private $taskMutexService;

    /** @var SubscriptionModuleInterface $subscriptionModule */
    private $subscriptionModule;

    /** @var bool $isOrdersActive */
    private $isOrdersActive;

    /** @var bool $isInvoiceActive */
    private $isInvoiceActive;

    /** @var int|null $printerId */
    private $printerId;

    /** @var string $mailPrinter */
    private $mailPrinter;

    /** @var SubscriptionCycleJobService $cycleJobService */
    private $cycleJobService;

    /** @var DateTimeInterface $simulatedDay */
    private $simulatedDay;

    /**
     * SubscriptionCycleFullTask constructor.
     *
     * @param TaskMutexServiceInterface   $taskMutexService
     * @param SubscriptionCycleJobService $cycleJobService
     * @param SubscriptionModuleInterface $subscriptionModule
     * @param bool                        $isOrdersActive
     * @param bool                        $isInvoiceActive
     * @param int|null                    $printerId
     * @param string                      $mailPrinter
     * @param DateTimeInterface           $simulatedDay
     */
    public function __construct(
        TaskMutexServiceInterface $taskMutexService,
        SubscriptionCycleJobService $cycleJobService,
        SubscriptionModuleInterface $subscriptionModule,
        bool $isOrdersActive,
        bool $isInvoiceActive,
        ?int $printerId,
        string $mailPrinter,
        DateTimeInterface $simulatedDay
    ) {
        $this->taskMutexService = $taskMutexService;
        $this->subscriptionModule = $subscriptionModule;
        $this->cycleJobService = $cycleJobService;
        $this->isOrdersActive = $isOrdersActive;
        $this->isInvoiceActive = $isInvoiceActive;
        $this->printerId = $printerId;
        $this->mailPrinter = $mailPrinter;
        $this->simulatedDay = $simulatedDay;
    }

    public function execute(): void
    {
        if ($this->taskMutexService->isTaskInstanceRunning('rechnungslauf')) {
            return;
        }
        $this->taskMutexService->setMutex('rechnungslauf');

        if (empty($this->isOrdersActive) && empty($this->isInvoiceActive)) {
            return;
        }

        if ($this->isOrdersActive) {
            $orderAddresses = array_map(
                'intval',
                array_keys((array)$this->subscriptionModule->GetRechnungsArray('auftrag'))
            );
            $addressIdsInJobs = $this->cycleJobService->getAddressIdsByDocumentType('auftrag');
            $orderAddresses = array_diff($orderAddresses, $addressIdsInJobs);
            foreach ($orderAddresses as $addressToAdd) {
                $this->cycleJobService->create(
                    $addressToAdd, 'auftrag', $this->mailPrinter, $this->printerId, $this->simulatedDay
                );
            }
            unset($orderAddresses);
        }
        if ($this->isInvoiceActive) {
            $invoiceAddresses = array_map(
                'intval',
                array_keys((array)$this->subscriptionModule->GetRechnungsArray('rechnung'))
            );
            $addressIdsInJobs = $this->cycleJobService->getAddressIdsByDocumentType('rechnung');
            $invoiceAddresses = array_diff($invoiceAddresses, $addressIdsInJobs);
            foreach ($invoiceAddresses as $addressToAdd) {
                $this->cycleJobService->create(
                    $addressToAdd, 'rechnung', $this->mailPrinter, $this->printerId, $this->simulatedDay
                );
            }
        }
    }

    public function cleanup(): void
    {
        $this->taskMutexService->setMutex('rechnungslauf', false);
    }
}
