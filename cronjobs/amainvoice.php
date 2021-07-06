<?php

use Xentral\Modules\AmaInvoice\Exception\AmazonInvoiceConfigNotFoundException;
use Xentral\Modules\AmaInvoice\Scheduler\AmaInvoiceTask;

if (!$app->erp->ModulVorhanden('amainvoice')) {
    return;
}

try {
    /** @var AmaInvoiceTask $amaInvoiceTask */
    $amaInvoiceTask = $app->Container->get('AmaInvoiceTask');
    $amaInvoiceTask->execute();
    $amaInvoiceTask->cleanup();
} catch (AmazonInvoiceConfigNotFoundException $e) {
    $amaInvoiceTask->cleanup();
    app('log')->warning($e->getMessage(), [
        'module' => 'amainvoice',
        'action' => 'AmaInvoiceTask',
        'file' => __FILE__,
    ]);
} catch (\Exception $exception) {
    $amaInvoiceTask->cleanup();

    throw $exception;
}
