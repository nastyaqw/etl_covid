<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ParsingController;

class ParseWebsite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse-web';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse data from website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new ParsingController();
        $result = $controller->parseData();
        if ($result['status'] === 'success') {
            $this->info($result['message']);
        } else {
            $this->error($result['message']); 
        }
    }
}
