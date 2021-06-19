<?php

namespace Ahmadwaleed\Blanket\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;

    protected $table = 'blanket_logs';
    public $timestamps = false;
    protected $guarded = [];
    protected $appends = ['path'];
    protected $casts = ['request' => 'array', 'response' => 'array', 'created_at', 'datetime'];

    public static function booted()
    {
        static::creating(fn ($model) => $model->created_at = $model->freshTimestamp());
    }

    public function getPathAttribute(): string
    {
        return parse_url($this->url, PHP_URL_PATH);
    }

    public function getCreatedAtAttribute(string $date): string
    {
        return Carbon::parse($date)->diffForHumans();
    }

    public static function counts(): mixed
    {
        return static::query()
            ->selectRaw("count(*) as `all`")
            ->selectRaw("count(case when method = 'GET' then 1 end) as get")
            ->selectRaw("count(case when method = 'POST' then 1 end) as post")
            ->selectRaw("count(case when method = 'PUT' then 1 end) as put")
            ->selectRaw("count(case when method = 'PATCH' then 1 end) as patch")
            ->selectRaw("count(case when method = 'DELETE' then 1 end) as `delete`")
            ->first();
    }
}
