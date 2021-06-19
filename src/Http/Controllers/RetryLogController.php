<?php

namespace Ahmadwaleed\Blanket\Http\Controllers;

use Ahmadwaleed\Blanket\Models\Log;
use Illuminate\Support\Facades\Http;

class RetryLogController
{
    public function __invoke(string $id)
    {
        $log = Log::findOrFail($id);

        $method = strtolower($log->method);
        $request = $log->request;

        if ($request['is_multipart'] ?? false) {
            $http = Http::attach(
                $request['payload']['name'],
                $request['payload']['content'],
                $request['payload']['filename'],
                $request['payload']['headers'],
            );
        } else {
            $http = Http::withBody($request['body'], $request['headers']['Content-Type'] ?? '');
        }

        $http->withHeaders($request['headers'])->{$method}($log->url);

        return response()->noContent();
    }
}
