<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //->dailyAt('03:00')

        /*$schedule->call('App\Http\Controllers\MaxiScheduleController@getMaxiAction')->everyMinute();
        $schedule->call('App\Http\Controllers\MaxiScheduleController@getMaxiDrink')->everyMinute();
        $schedule->call('App\Http\Controllers\MaxiScheduleController@getMaxiMeats')->everyMinute();
        $schedule->call('App\Http\Controllers\MaxiScheduleController@getMaxiSweets')->everyMinute();
        $schedule->call('App\Http\Controllers\MaxiScheduleController@getMaxiFreeze')->everyMinute();
        $schedule->call('App\Http\Controllers\IdeaScheduleController@deleteRecords')->everyMinute();
        $schedule->call('App\Http\Controllers\IdeaScheduleController@getIdeaAction')->everyMinute();
        $schedule->call('App\Http\Controllers\IdeaScheduleController@getIdeaDrink', ['60007883',null])->everyMinute();
        $schedule->call('App\Http\Controllers\IdeaScheduleController@getIdeaMeat', ['60007823',null])->everyMinute();
        $schedule->call('App\Http\Controllers\IdeaScheduleController@getIdeaMeat2', ['60007780',null])->everyMinute();
        $schedule->call('App\Http\Controllers\IdeaScheduleController@getIdeaSweet', ['60007896',null])->everyMinute();
        $schedule->call('App\Http\Controllers\IdeaScheduleController@getIdeaFreeze', ['60007907',null])->everyMinute();
        $schedule->call('App\Http\Controllers\DisMarketController@updateExistingDrinks')->everyMinute();
        $schedule->call('App\Http\Controllers\DisMarketController@updateExistingMeat')->everyMinute();
        $schedule->call('App\Http\Controllers\DisMarketController@updateExistingFreeze')->everyMinute();
        $schedule->call('App\Http\Controllers\DisMarketController@updateExistingSweet')->everyMinute();*/
        /*$schedule->call('App\Http\Controllers\UniverexportMarketController@updateExistingUniverArticles')->everyMinute();
        $schedule->call('App\Http\Controllers\IdeaScheduleController@deleteCachedData')->everyMinute();*/
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
