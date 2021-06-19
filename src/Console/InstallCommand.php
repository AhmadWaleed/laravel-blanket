<?php

namespace Ahmadwaleed\Blanket\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blanket:wrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Blanket resources';

    public function handle(): int
    {
        $this->comment('Publishing Blanket Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'blanket-assets']);

        $this->comment('Publishing Blanket Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'blanket-config']);

        $this->info('Blanket scaffolding installed successfully.');

        return 0;
    }
}
