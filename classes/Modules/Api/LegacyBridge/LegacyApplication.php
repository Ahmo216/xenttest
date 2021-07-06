<?php

namespace Xentral\Modules\Api\LegacyBridge;

class LegacyApplication extends \ApplicationCore
{
    public function __construct()
    {
        require_once __DIR__ . '/../../../../bootstrap/app.php';
        parent::__construct();
    }
}
