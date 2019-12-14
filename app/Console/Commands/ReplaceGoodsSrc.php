<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\Goods;

/**
 * 批量替换商品src
 * <img class="lazyload" data-original="" />
 * nohup php artisan goodssrc:replace &
 *
 */
class ReplaceGoodsSrc extends Command {

    /**
     * command的名字
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'goodssrc:replace';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'goods src replace';

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
	ini_set('memory_limit','512M');
        $goods = new Goods();
        return $goods->replaceGoodsSrc();
    }
}