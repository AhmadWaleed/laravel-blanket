<?php

namespace Ahmadwaleed\Blanket\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateLogTest extends TestCase
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
                    'post' => Http::response(['title' => 'bar', 'body' => 'foo', 'userId' => 1], 201),
                    'put' => Http::response(['title' => 'bar', 'body' => 'foo', 'userId' => 1]),
                    'patch' => Http::response(['title' => 'lorem', 'body' => 'ipsum', 'userId' => 1]),
                    'delete' => Http::response([]),
                };
            },
        ]);
    }

    public function test_create_get_log()
    {
        $this->post($this->route('logs'), ['log_method' => 'get'])
            ->assertNoContent();

        $this->assertDatabaseHas('blanket_logs', [
            'method' => 'GET',
            'host' => 'jsonplaceholder.typicode.com',
            'url' => 'https://jsonplaceholder.typicode.com/posts/1',
            'status' => 200,
        ]);
    }

    public function test_create_post_log()
    {
        $this->post($this->route('logs'), ['log_method' => 'post'])
            ->assertNoContent();

        $this->assertDatabaseHas('blanket_logs', [
            'method' => 'POST',
            'host' => 'jsonplaceholder.typicode.com',
            'url' => 'https://jsonplaceholder.typicode.com/posts',
            'status' => 201,
        ]);
    }

    public function test_create_put_log()
    {
        $this->post($this->route('logs'), ['log_method' => 'put'])
            ->assertNoContent();

        $this->assertDatabaseHas('blanket_logs', [
            'method' => 'PUT',
            'host' => 'jsonplaceholder.typicode.com',
            'url' => 'https://jsonplaceholder.typicode.com/posts/1',
            'status' => 200,
        ]);
    }

    public function test_create_patch_log()
    {
        $this->post($this->route('logs'), ['log_method' => 'patch'])
            ->assertNoContent();

        $this->assertDatabaseHas('blanket_logs', [
            'method' => 'PATCH',
            'host' => 'jsonplaceholder.typicode.com',
            'url' => 'https://jsonplaceholder.typicode.com/posts/1',
            'status' => 200,
        ]);
    }

    public function test_create_delete_log()
    {
        $this->post($this->route('logs'), ['log_method' => 'delete'])
            ->assertNoContent();

        $this->assertDatabaseHas('blanket_logs', [
            'method' => 'DELETE',
            'host' => 'jsonplaceholder.typicode.com',
            'url' => 'https://jsonplaceholder.typicode.com/posts/1',
            'status' => 200,
        ]);
    }
}
