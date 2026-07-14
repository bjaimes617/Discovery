<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\LoginLog;
use Illuminate\Support\Facades\Request;

class EventServiceProvider extends ServiceProvider {

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            if ($event->user) {
                $loginLog = new LoginLog();
                $loginLog->ip = Request::ip();
                $loginLog->event = "Login";
                $loginLog->user_id = $event->user->id;
                $loginLog->save();
            }
        });

        Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            if ($event->user) {
                $loginLog = new LoginLog();
                $loginLog->ip = Request::ip();
                $loginLog->event = "Logout";
                $loginLog->user_id = $event->user->id;
                $loginLog->save();
            }
        });
        parent::boot();

        //
    }

}
