<?php

namespace App\Providers;


use App\Observers\UserObserver;
use App\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        view()->composer('widgets.notifications', function ($view) {

            $data = [
                'allNoti' => auth()->user()->notifications,
                'newNoti' => auth()->user()->unreadNotifications,
                'oldNoti' => auth()->user()->readNotifications,
            ];
            $view->with('notifications', $data);
        });
        User::observe(UserObserver::class);
    }
}