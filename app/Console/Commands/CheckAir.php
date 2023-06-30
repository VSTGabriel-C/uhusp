<?php

namespace App\Console\Commands;

use App\Http\Controllers\AirController;
use App\Models\Air;
use Illuminate\Console\Command;

class CheckAir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'air:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if Air Conditioning is on or off';

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
     * @return int
     */
    public function handle()
    {
        (new Air)->list_airs();
        return 0;
    }
}
