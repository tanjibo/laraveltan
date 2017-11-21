<?php

namespace App\Console\Commands;

use App\Models\ExperienceBooking;
use Illuminate\Console\Command;

class DelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delOrder:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除已经取消的订单';

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
        $ids= ExperienceBooking::query()->where("status",ExperienceBooking::STATUS_CANCEL)->pluck('id')->toArray();
        ExperienceBooking::query()->whereIn("id",$ids)->delete();
    }
}
