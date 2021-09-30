<?php

namespace Ahmadwaleed\Blanket\Listeners;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Ahmadwaleed\Blanket\Models\Log;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\Events\ResponseReceived;

class LogClientRequest
{
    public function handle(ResponseReceived $event): void
    {
        if (! config('blanket.enabled', true)) {
            return;
        }

        $request = $event->request;
        $response = $event->response;
        $hidden = config('blanket.hide_sensitive_data');
        $host = parse_url($request->url(), PHP_URL_HOST);

        rescue(
            fn () =>
            Log::create([
                'host' => $host,
                'url' => $request->url(),
                'request' => [
                    'body' => $request->body(),
                    'headers' => $this->hideParameters($request->headers(), $hidden['headers'] ?? []),
                    'payload' => $this->hideParameters($this->payload($request), $hidden['request'] ?? []),
                    'is_multipart' => $request->isMultipart(),
                ],
                'method' => $request->method(),
                'status' => $response->status(),
                'response' => [
                    'headers' => $this->hideParameters($response->headers(), $hidden['headers'] ?? []),
                    'body' => ! is_array($this->response($response))
                        ? Arr::wrap($response)
                        : $this->hideParameters($this->response($response), $hidden['response'] ?? []),
                ],
                'created_at' => now(),
            ])
        );
    }

    private function payload(Request $request): null | array
    {
        if (! $request->isMultipart()) {
            return $request->data();
        }

        return collect($request->data())->map(function ($data) {
            return [
                'name' => $data['name'] ?? $data['filename'] ?? null,
                'filename' => $data['filename'] ?? null,
                'size' => (strlen($data['content']) / 1000).'KB',
                'headers' => $data['headers'] ?? [],
                'content' => $data['content'],
            ];
        })->toArray();
    }

    private function response(Response $response): array | string
    {
        if ($response->redirect()) {
            return Arr::wrap('Redirected to Location: '.$response->header('Location'));
        }

        $content = is_string($response->body()) ? $response->body() : '*** unexpected response ***';

        if (! is_string($content)) {
            return $content;
        }

        if (is_array(json_decode($content, true)) && json_last_error() === JSON_ERROR_NONE) {
            return $this->contentInLimits($content)
                ? json_decode($content, true)
                : '*** Purge by blanket ***';
        }

        if (Str::startsWith(strtolower($response->header('Content-Type')), 'text/plain')) {
            return $this->contentInLimits($content) ? $content : '***  Purge by blanket ***';
        }

        return $content;
    }

    protected function hideParameters($data, $hidden): mixed
    {
        foreach ($hidden as $parameter) {
            if (Arr::get($data, $parameter)) {
                Arr::set($data, $parameter, '********');
            }
        }

        return $data;
    }

    private function contentInLimits(string $content): bool
    {
        return mb_strlen($content) / 1000 <= (int) config('blanket.log_response_limit', 64);
    }
}
