<?php

namespace Ahmadwaleed\Blanket\Http\Middlewares;

class Authorize
{
    public function handle($request, $next)
    {
        if (! config('blanket.enabled')) {
            abort(403);
        }

        return $next($request);
    }
}
