<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\Goods;

/**
 * 批量检查商品
 * php artisan goods:check2
 *
 */
class CheckGoods extends Command {

    /**
     * command的名字
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'goods:check2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'goods check';

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
        $goods = new Goods();
        return $goods->mulCheck();
    }
}