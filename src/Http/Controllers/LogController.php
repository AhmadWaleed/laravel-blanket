<?php

namespace Ahmadwaleed\Blanket\Http\Controllers;

use Illuminate\Http\Request;
use Ahmadwaleed\Blanket\Models\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder;

class LogController
{
    public function index(Request $request)
    {
        $length = (int) config('blanket.logs_per_page', 100);
        $take = (int) $request->get('take', $length);

        $logs = Log::query()
            ->when(
                $request->filled('filter_host'),
                fn (Builder $query) =>
                    $query->where('host', $request->get('filter_host'))
            )
            ->when(
                $request->filled('filter_method') && $request->filter_method !== 'all',
                fn (Builder $query) =>
                    $query->where('method', strtoupper($request->get('filter_method')))
            )
            ->latest()
            ->take($take)
            ->get();

        $totalCount = Log::count();

        return [
            'take' => $totalCount > $take ? $take + $length : $take,
            'end' => $totalCount <= $take,
            'logs' => $logs,
            'counts' => Log::counts(),
        ];
    }

    public function store(Request $request)
    {
        $method = $request->get('log_method', 'get');
        $body = ['title' => 'foo', 'body' => 'bar', 'userId' => 1];
        $http = Http::baseUrl('https://jsonplaceholder.typicode.com')->withHeaders([
            'Content-type' => 'application/json; charset=UTF-8',
        ]);

        match ($method) {
            'get' => $http->get('/posts/1'),
            'post' => $http->post('/posts', $body),
            'put' => $http->put('/posts/1', $body),
            'patch' => $http->patch('/posts/1', $body),
            'delete' => $http->delete('/posts/1'),
        };

        return response()->noContent();
    }

    public function destroy($id)
    {
        $log = Log::findOrFail($id);
        $log->delete();

        return response()->noContent();
    }
}
