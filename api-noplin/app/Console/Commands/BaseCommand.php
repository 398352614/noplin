<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'base:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '基础命令';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->deal();
            $this->info(now()->format('Y-m-d H:i:s ') . 'The command has been proceed successfully.');
        } catch (\Exception) {
            $this->error(now()->format('Y-m-d H:i:s ') . 'The command process has been failed.');
        }
    }

    public function deal()
    {

    }
}
