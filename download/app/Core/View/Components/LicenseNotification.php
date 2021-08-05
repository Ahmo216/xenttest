<?php

namespace App\Core\View\Components;

use App\Core\License\License;
use Illuminate\View\Component;

class LicenseNotification extends Component
{
    /** @var License */
    private $license;

    public function __construct(License $license)
    {
        $this->license = $license;
    }

    public function render()
    {
        return view('components.license-notification', ['license' => $this->license]);
    }
}
