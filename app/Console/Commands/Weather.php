<?php

namespace App\Console\Commands;

use App\Component\Traits\Weather as TraitWeather;
use Exception;
use Illuminate\Console\Command;


class Weather extends Command
{
    use TraitWeather;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Weather Information From Weather API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $this->fetchWeatherInformation();
            $this->info("Weather Info Petch Successfully");
        }catch(Exception $e){
            $this->error($e->getMessage());
        }
    }
}
