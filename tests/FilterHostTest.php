<?php

namespace Ahmadwaleed\Blanket\Tests;

use Ahmadwaleed\Blanket\Models\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterHostTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_log_hosts()
    {
        Log::factory()->create([
            'host' => 'laravel-blanket.com',
        ]);
        Log::factory()->create([
            'host' => 'toollit.com',
        ]);
        Log::factory()->create([
            'host' => 'darazhunt.com',
        ]);

        $this->get($this->route('hosts/filter/?host_filter=laravel-blanket.com'))
            ->assertOk()
            ->assertJsonCount(1);
    }
}
