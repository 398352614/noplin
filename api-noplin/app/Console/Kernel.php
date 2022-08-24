<?php

namespace App\Console;

use Closure;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Stringable;
use function PHPUnit\Framework\directoryExists;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //每日清除telescope记录，保留近1天
        $schedule->command('telescope:prune --hours=24')->everyMinute()->after($this->record('telescope'));
        //数据库备份
        $schedule->command('db:backup')->daily()->after($this->record('backup'));
        //每周清理一次文件，保留近1个月
        $schedule->command('file:clean')->weekly()->after($this->record('file'));
    }

    /**
     * 记录
     * @param $params
     * @return Closure
     */
    public function record($params): Closure
    {
        return function (Stringable $output) use ($params) {
            $path = storage_path('logs/' . $params . '/' . $params . '-' . today()->format('Y-m') . '.log');
            if (!directoryExists(storage_path('logs/' . $params))) {
                mkdir(storage_path('logs/' . $params));
            }
            File::append($path, '[' . now() . '] ' . $output);
        };
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
