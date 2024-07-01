<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Keyword;
use App\Models\Domain;
use App\Models\Country;
use App\Models\Language;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Register your commands here
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $keywords = Keyword::with('domain')->get();

            foreach ($keywords as $keyword) {
                $domain = $keyword->domain;
                $countries = Country::all();
                $languages = Language::all();

                foreach ($countries as $country) {
                    foreach ($languages as $language) {
                        $controller = new \App\Http\Controllers\KeywordSearchController();
                        $test = $controller->search(new \Illuminate\Http\Request([
                            'domain_id' => $domain->id,
                            'keyword_id' => $keyword->id,
                            'country' => $country->code,
                            'language' => $language->code,
                            'service' => 'google.selenium', 
                            'recaptcha_key' => '' 
                        ]));
                    }
                }
            }
        })->daily();
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
