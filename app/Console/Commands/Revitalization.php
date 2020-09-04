<?php

namespace App\Console\Commands;

use App\Facades\Hierarchy;
use Illuminate\Console\Command;

class Revitalization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revitalization:start {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        if($this->argument('type') == 1) {
            Hierarchy::revitalization();
        }
        else{
            Hierarchy::revitalizationCron();
        }
    }
}
