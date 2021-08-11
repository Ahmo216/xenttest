<?php

declare(strict_types=1);

namespace Xentral\Modules\AmaInvoice\Scheduler;

use Exception;
use Xentral\Modules\AmaInvoice\Exception\SchedulerTaskAlreadyRunningException;
use Xentral\Modules\AmaInvoice\Exception\ThrottlingException;
use Xentral\Modules\AmaInvoice\Service\AmaInvoiceService;
use Xentral\Modules\SuperSearch\Wrapper\CompanyConfigWrapper;

final class AmaInvoiceTask
{
    /** @var AmaInvoiceService $service */
    private $service;

    /** @var CompanyConfigWrapper $config */
    private $config;

    /**
     * AmaInvoiceService constructor.
     *
     * @param AmaInvoiceService    $service
     * @param CompanyConfigWrapper $config
     */
    public function __construct($service, $config)
    {
        $this->service = $service;
        $this->config = $config;
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function execute(): void
    {
        $taskActive = (int)$this->config->get('amainvoice_task_mutex');
        if ($taskActive > 0) {
            throw new SchedulerTaskAlreadyRunningException(
                'Amainvoice task is already running. Task can only run once at a time.'
            );
        }

        $this->config->set('amainvoice_task_mutex', '1');

        $this->syncNewFiles();
        $this->config->set('amainvoice_task_mutex', '1');
        $this->service->executeImportDateDbEntries(false, false);
        $this->config->set('amainvoice_task_mutex', '1');
        $this->service->executeImportDateDbEntries(false, true);
        $this->config->set('amainvoice_task_mutex', '1');
        $this->service->executeImportDateDbEntries(true, false);
    }

    /**
     * @return void
     */
    public function cleanup(): void
    {
        $this->config->set('amainvoice_task_mutex', '0');
    }

    /**
     * @throws Exception
     */
    private function syncNewFiles(): void
    {
        $files = $this->service->getNewFiles();
        $dateFiles = $this->service->getFirstApiFiles($files);

        foreach (['invoice', 'returnorder', 'basicdatatax'] as $type) {
            foreach ($dateFiles[$type] as $file) {
                try {
                    $this->service->executeImportDateFile($file);
                } catch (ThrottlingException $e) {
                    return;
                }
            }
        }
    }
}
