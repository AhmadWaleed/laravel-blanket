<?php

namespace Ahmadwaleed\Blanket\Tests;

use Ahmadwaleed\Blanket\Models\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class PruneLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_logs_that_are_longer_than_prunable_duration()
    {
        Log::factory()->count(30)->state(new Sequence(
            ['created_at' => now()->toDateString()],
            ['created_at' => now()->subDays(15)],
            ['created_at' => now()->subMonth()],
        ))->create();

        $this->artisan('model:prune', [
            '--model' => [Log::class],
        ]);

        $this->assertSame(Log::count(), 20);
    }
}
