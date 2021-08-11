<?php

declare(strict_types=1);

namespace Xentral\Modules\AmaInvoice\Wrapper;

use ApplicationCore;
use Xentral\Components\Database\Database;

final class TurnoverThresholdWrapper
{
    /** @var ApplicationCore $app */
    private $app;

    /** @var Database $db */
    private $db;

    public function __construct(ApplicationCore $app, Database $db)
    {
        $this->app = $app;
        $this->db = $db;
    }

    /**
     * @return bool
     */
    public function hasTurnoverThresholdModule(): bool
    {
        return $this->app->erp->ModulVorhanden('lieferschwelle');
    }

    /**
     * @return array
     */
    public function getThresholdsCountries(): array
    {
        return $this->db->fetchCol('SELECT `empfaengerland` FROM `lieferschwelle`');
    }
}
