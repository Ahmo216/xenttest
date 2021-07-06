<?php

declare(strict_types = 1);

use Xentral\Components\Logger\Logger;

/** @var $app */

/** @var Logger $logger */
$logger = $app->Container->get('Logger');
try {
    $importer = $app->Container->get('GoogleCalendarSynchronizerTask');
    $importer->execute();
} catch (Throwable $e) {
    $logger->error('google calendar task failed', ['exception' => $e]);
}


