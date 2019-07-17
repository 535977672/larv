<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\Pay;

/**
 * 创建任务
 * php artisan make:command Order
 * 有效期检查
 */
class Order extends Command { 

    /**
     * command的名字
     * The name and signature of the console command.
     * $schedule->command($signature)->everyMinute();
     * @var string
     */
    protected $signature = 'order:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'order exp check';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $this->check();
    }
    
    private function check() {
        $pay = new Pay();
        return $pay->payExpireCheck();
    }
}