<?php

namespace Ahmadwaleed\Blanket\Http\Controllers;

use Ahmadwaleed\Blanket\Models\Log;

class TruncateLogController
{
    public function __invoke()
    {
        Log::query()->truncate();

        return response()->noContent();
    }
}
