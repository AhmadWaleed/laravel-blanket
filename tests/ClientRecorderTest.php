<?php

namespace Ahmadwaleed\Blanket\Tests;

use Ahmadwaleed\Blanket\Models\Log;
use Illuminate\Support\Facades\Http;

class ClientRecorderTest extends TestCase
{
    public function test_hides_request_sensitive_data()
    {
        Http::fake([
            '*' => Http::response(null, 204),
        ]);

        Http::post('https://site.com/auth', [
            'email' => 'j.doe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $log = Log::first();

        $this->assertSame('********', $log->request['payload']['password']);
        $this->assertSame('********', $log->request['payload']['password_confirmation']);
    }

    public function test_hides_request_headers_sensitive_data()
    {
        Http::fake([
            '*' => Http::response(null, 204),
        ]);

        Http::withHeaders([
            'Authorization' => 'Basic YWxhZGRpbjpvcGVuc2VzYW1l',
            'Content-Type' => 'application/json',
        ])->post('https://site.com/home');

        $this->assertSame('********',  Log::first()->request['headers']['Authorization']);
    }

    public function test_hides_response_body_sensitive_data()
    {
        config()->set('blanket.hide_sensitive_data.response', [
            'client_id',
            'client_secret',
        ]);

        Http::fake([
            '*' => Http::response([
                'client_id' => '123xcv',
                'client_secret' => 'YWxhZGRpbjpvcGVuc2VzYW1l',
            ], 204),
        ]);


        Http::post('https://site.com/creds');

        $log = Log::first();

        $this->assertSame('********',  $log->response['body']['client_id']);
        $this->assertSame('********',  $log->response['body']['client_secret']);
    }

    public function test_hides_nested_response_body_sensitive_data()
    {
        config()->set('blanket.hide_sensitive_data.response', [
            'client.id',
            'client.secret',
        ]);

        Http::fake([
            '*' => Http::response([
                'client' => [
                    'id' => '123xcv',
                    'secret' => 'YWxhZGRpbjpvcGVuc2VzYW1l',
                ],
            ], 204),
        ]);


        Http::post('https://site.com/creds');

        $log = Log::first();

        $this->assertSame('********',  $log->response['body']['client']['id']);
        $this->assertSame('********',  $log->response['body']['client']['secret']);
    }
}
