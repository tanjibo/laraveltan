<?php

namespace App\Console\Commands;

use App\Api\ExperienceSpecialPrice;
use Illuminate\Console\Command;

class CancelSpecialPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancelSpecialPrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cancel special price out of date';

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
      ExperienceSpecialPrice::delSpecialPriceOutDate();
    }
}
