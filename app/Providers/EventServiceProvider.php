<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        'App\Events\Activation' => [
            'App\Listeners\UserActivated',
            //'App\Listeners\SponsorStatus',
            //'App\Listeners\TelegramSmsSender'
        ],

        'App\Events\ShopTurnover' => [
            'App\Listeners\BonusDistribution'
        ],

        'App\Events\Upgrade' => [
            'App\Listeners\UserUpgraded'
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
