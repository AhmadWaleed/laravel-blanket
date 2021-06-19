<?php

namespace Ahmadwaleed\Blanket\Tests;

use Ahmadwaleed\Blanket\Models\Log;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RetryLogTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Http::fake([
            'jsonplaceholder.typicode.com/*' => function (Request $request) {
                $method = strtolower($request->method());

                return match ($method) {
                    'get' => Http::response(['title' => 'foo', 'body' => 'bar', 'userId' => 1]),
                    'post' => Http::response(['title' => 'bar', 'body' => 'baz', 'userId' => 1], 201),
                };
            },
        ]);
    }

    public function test_retry_get_log_request()
    {
        $log = Log::factory()->create([
            'method' => 'GET',
            'request' => [
                'body' => '',
                'is_multipart' => false,
                'headers' => ['Content-Type' => 'application/json'],
            ],
            'host' => 'jsonplaceholder.typicode.com',
            'url' => 'https://jsonplaceholder.typicode.com/posts/1',
            'status' => 200,
        ]);

        $this->assertDatabaseCount('blanket_logs', 1);

        $this->post($this->route("logs/{$log->id}/retry"))
            ->assertNoContent();

        $this->assertDatabaseCount('blanket_logs', 2);
        $this->assertDatabaseHas('blanket_logs', [
            'method' => 'GET',
            'host' => 'jsonplaceholder.typicode.com',
            'url' => 'https://jsonplaceholder.typicode.com/posts/1',
            'status' => 200,
        ]);
    }

    public function test_retry_post_log_request()
    {
        $request = [
            'headers' => ['Content-Type' => 'application/json'],
            'is_multipart' => false,
            'body' => '{"title":"foo","body":"bar","userId":1}',
        ];

        $log = Log::factory()->create([
            'method' => 'POST',
            'request' => $request,
            'host' => 'jsonplaceholder.typicode.com',
            'url' => 'https://jsonplaceholder.typicode.com/posts',
            'status' => 201,
        ]);

        $this->assertDatabaseCount('blanket_logs', 1);

        $this->post($this->route("logs/{$log->id}/retry"))
            ->assertNoContent();

        $this->assertDatabaseCount('blanket_logs', 2);

        $this->assertDatabaseHas('blanket_logs', [
            'method' => 'POST',
            'request' => json_encode($request),
            'host' => 'jsonplaceholder.typicode.com',
            'url' => 'https://jsonplaceholder.typicode.com/posts',
            'status' => 201,
        ]);
    }
}
