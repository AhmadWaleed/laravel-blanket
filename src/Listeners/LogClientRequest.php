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
        $host = parse_url($request->url(), PHP_URL_HOST);

        rescue(
            fn () =>
            Log::create([
                'host' => $host,
                'url' => $request->url(),
                'request' => [
                    'body' => $request->body(),
                    'headers' => $request->headers(),
                    'payload' => $this->payload($request),
                    'is_multipart' => $request->isMultipart(),
                ],
                'method' => $request->method(),
                'status' => $response->status(),
                'response' => [
                    'headers' => $response->headers(),
                    'body' => tap($this->response($response), fn ($response) =>
                        is_string($response) ? Arr::wrap($response) : $response),
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
                : '*** response out of size limit ***';
        }

        if (Str::startsWith(strtolower($response->header('Content-Type')), 'text/plain')) {
            return $this->contentInLimits($content) ? $content : '*** response out of size limit ***';
        }

        return $content;
    }

    private function contentInLimits(string $content): bool
    {
        return mb_strlen($content) / 1000 <= 64; // @todo: allow user to manage limit
    }
}
