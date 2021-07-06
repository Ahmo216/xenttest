@php
    /**
     * @var \App\Core\License\License $license
    **/
$daysUntilLicenseExpires = now()->daysUntil($license->expiresAt())->count();
$licenseExpiryDate = $license->expiresAt()->format(__('d.m.Y'));
@endphp
@if($license->isExpired() || $daysUntilLicenseExpires < 4)
    <div id="licence-alert" class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-xentral">
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            <p class="alert-message">
                                @if($license->isExpired())
                                    @if($license->isDemo())
                                        {{ __('Dein Demo-Zugang ist am :date ausgelaufen.', ['date' => $licenseExpiryDate]) }}
                                    @else
                                        {{ __('Der Supportvertrag für diese Lizenz ist am :date ausgelaufen.', ['date' => $licenseExpiryDate]) }}
                                    @endif
                                @else
                                    @if($license->isDemo())
                                        {{ __('Dein Demo-Zugang läuft :days am :date ab.', ['days' => trans_choice('tage bis',$daysUntilLicenseExpires),'date' => $licenseExpiryDate]) }}
                                    @else
                                        {{ __('Der Supportvertrag für diese Lizenz läuft :days am :date ab.', ['days' => trans_choice('tage bis', $daysUntilLicenseExpires), 'date' => $licenseExpiryDate]) }}
                                    @endif
                                @endif
                                {{ __('Sichere Dir unbegrenzten Zugang zu xentral Home.') }}
                            </p>
                        </div>
                        <div class="col-sm-12 col-md-4 alert-buttons">
                            <a href="tel:+4982126841040" class="btn btn-phone">Tel.: +49 821 268 41 0 40</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($license->isSuspended())
    <div id="xentral-licence-notification" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h1>{{ __('Oops, es scheint als wäre diese Lizenz aktuell gesperrt.') }}</h1>
                    <p>{{ __('Bitte wende dich an das xentral-Team, um die Lizenz zu entsperren.') }}</p>
                    <div class="modal-buttons">
                        <a href="tel:+4982126841040" class="btn btn-phone">Tel.: +49 821 268 41 0 40</a>
                        <a href="mailto:kontakt@xentral.com?Subject=Bitte%20um%20ein%20Angebot" class="btn btn-contact">
                            kontakt@xentral.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
