<?php

namespace App\Core\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

class ForceHttpsMiddleware
{
    /** @var UrlGenerator */
    private $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function handle(Request $request, \Closure $next)
    {
        if ($this->shouldForceHttps($request)) {
            $this->urlGenerator->forceScheme('https');
        }

        return $next($request);
    }

    private function shouldForceHttps(Request $request): bool
    {
        return $request->hasHeader('X-Forwarded-For')
            && $request->header('X-Forwarded-Proto') === 'https';
    }
}
