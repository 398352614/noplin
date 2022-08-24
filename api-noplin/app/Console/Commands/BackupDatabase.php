<?php

namespace App\Console\Commands;

class BackupDatabase extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '数据库备份';

    /**
     * Execute the console command.
     *
     */
    public function deal()
    {
        if (!file_exists(storage_path('app/backup'))) {
            mkdir(storage_path('app/backup/'), 777, true);
        }
        exec(sprintf(
            'mysqldump -h%s -u%s -p%s %s --ignore-table=%s --ignore-table=%s --ignore-table=%s | gzip > %s',
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            config('database.connections.mysql.database') . '.telescope_entries',
            config('database.connections.mysql.database') . '.telescope_entries_tags',
            config('database.connections.mysql.database') . '.telescope_monitoring',
            storage_path(config('project.backup.mysql')) . '.gz'
        ));
    }
}
