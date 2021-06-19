<?php

namespace Ahmadwaleed\Blanket\Http\Controllers;

use Illuminate\Http\Request;
use Ahmadwaleed\Blanket\Models\Log;
use Illuminate\Database\Eloquent\Builder;

class FilterHostController
{
    public function __invoke(Request $request)
    {
        return Log::query()
            ->select('host')
            ->distinct('host')
            ->when(
                $request->filled('host_filter'),
                fn (Builder $query) =>
                $query->where('host', 'LIKE', "%$request->host_filter%")
            )
            ->take(5)
            ->get()
            ->pluck('host');
    }
}
