<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;

class RecoverDatabase extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:recover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '恢复数据库';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function deal()
    {
        if (file_exists(storage_path('app/backup/backup.sql.gz'))) {
            exec(sprintf('gunzip -c %s > %s', storage_path(config('project.backup.mysql')) . '.gz', storage_path(config('project.backup.mysql'))));
            DB::unprepared(file_get_contents(storage_path(config('project.backup.mysql'))));
        }
    }
}
