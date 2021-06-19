<?php

namespace Ahmadwaleed\Blanket\Tests;

use Ahmadwaleed\Blanket\Models\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_logs_per_page()
    {
        Log::factory()->count(101)->create([
            'method' => 'GET',
        ]);

        $this->get($this->route('logs'))
            ->assertOk()
            ->assertJson([
                'take' => 200,
                'end' => false,
                'counts' => [
                    'get' => 101,
                    'post' => 0,
                    'put' => 0,
                    'patch' => 0,
                    'delete' => 0,
                ],
            ])
            ->assertJsonCount(100, 'logs');

        $this->assertCount(101, Log::all());
    }

    public function test_get_logs_for_next_page()
    {
        Log::factory()->count(101)->create([
            'method' => 'GET',
        ]);

        $this->call('GET', $this->route('logs'), ['take' => 200])
            ->assertOk()
            ->assertJson([
                'take' => 200,
                'end' => true,
                'counts' => [
                    'get' => 101,
                    'post' => 0,
                    'put' => 0,
                    'patch' => 0,
                    'delete' => 0,
                ],
            ])
            ->assertJsonCount(101, 'logs');

        $this->assertCount(101, Log::all());
    }

    public function test_filter_logs_by_host()
    {
        Log::factory()->count(50)->create([
            'host' => 'jsonplaceholder.typicode.co',
        ]);
        Log::factory()->count(100)->create([
            'host' => 'fake-api.co',
        ]);

        $this->call('GET', $this->route('logs'), ['filter_host' => 'jsonplaceholder.typicode.co'])
            ->assertOk()
            ->assertJsonCount(50, 'logs');

        $this->call('GET', $this->route('logs'), ['filter_host' => 'fake-api.co'])
            ->assertOk()
            ->assertJsonCount(100, 'logs');

        $this->assertCount(150, Log::all());
    }

    public function test_filter_logs_by_method()
    {
        Log::factory()->count(50)->create([
            'method' => 'GET',
        ]);
        Log::factory()->count(50)->create([
            'method' => 'POST',
        ]);

        $this->call('GET', $this->route('logs'), ['filter_method' => 'all'])
            ->assertOk()
            ->assertJsonCount(100, 'logs');

        $this->call('GET', $this->route('logs'), ['filter_method' => 'get'])
            ->assertOk()
            ->assertJsonCount(50, 'logs');

        $this->call('GET', $this->route('logs'), ['filter_method' => 'post'])
            ->assertOk()
            ->assertJsonCount(50, 'logs');
    }
}
