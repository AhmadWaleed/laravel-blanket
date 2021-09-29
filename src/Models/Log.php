<?php

namespace Ahmadwaleed\Blanket\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;
    use MassPrunable;

    protected $table = 'blanket_logs';
    public $timestamps = false;
    protected $guarded = [];
    protected $appends = ['path'];
    protected $casts = ['request' => 'array', 'response' => 'array', 'created_at', 'datetime'];

    public function prunable(): Builder
    {
        return static::query()->where('created_at', '<=', config('blanket.prune_logs_duration'));
    }

    public function getPathAttribute(): string
    {
        return parse_url($this->url, PHP_URL_PATH);
    }

    public function getCreatedAtAttribute(?string $date): ?string
    {
        return Carbon::parse($date)->diffForHumans();
    }

    public function getRequestAttribute(): string|array|null
    {
        if (config('blanket.encrypt_data', false)) {
            try {
                return decrypt($this->attributes['request']);
            } catch (DecryptException $e) {
            }
        }

        return json_decode($this->attributes['request'], true);
    }

    public function getResponseAttribute(): string|array|null
    {
        if (config('blanket.encrypt_data', false)) {
            try {
                return decrypt($this->attributes['response']);
            } catch (DecryptException $e) {
            }
        }

        return json_decode($this->attributes['response'], true);
    }

    public function setRequestAttribute($value): void
    {
        $this->attributes['request'] = config('blanket.encrypt_data', false) ? encrypt($value) : $value;
    }

    public function setResponseAttribute($value): void
    {
        $this->attributes['response'] = config('blanket.encrypt_data', false) ? encrypt($value) : $value;
    }

    public static function counts(): mixed
    {
        return static::query()
            ->selectRaw("count(*) as `all`")
            ->selectRaw("count(case when method = 'GET' then 1 end) as `get`")
            ->selectRaw("count(case when method = 'POST' then 1 end) as `post`")
            ->selectRaw("count(case when method = 'PUT' then 1 end) as `put`")
            ->selectRaw("count(case when method = 'PATCH' then 1 end) as `patch`")
            ->selectRaw("count(case when method = 'DELETE' then 1 end) as `delete`")
            ->first();
    }
}
