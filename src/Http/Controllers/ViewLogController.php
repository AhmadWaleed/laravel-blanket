<?php

namespace Ahmadwaleed\Blanket\Http\Controllers;

class ViewLogController
{
    public function __invoke()
    {
        return view('blanket::show');
    }
}
