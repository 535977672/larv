<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

//* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1 &  //丢弃一切写入其中的数据
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     * 应用提供的 Artisan 命令
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Order::class,
    ];

    /**
     * Define the application's command schedule.
     * 定义应用的命令调度
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $filePath = '/var/www/console.log';
        $schedule->command('order:check --force')
            ->everyThirtyMinutes() //每半小时执行一次任务
            ->timezone('Asia/Shanghai')
            ->withoutOverlapping() //避免任务重复
            ->appendOutputTo($filePath);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
