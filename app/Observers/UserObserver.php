<?php

namespace App\Observers;

use App\Configuration;
use App\Notifications\NewUserCreatedDB;
use App\Notifications\WelcomeNewUser;
use App\User;
use Illuminate\Support\Facades\Notification;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if (Configuration::where('name','new-user-welcome-email')->first()->value==1){
            $user->notify(new WelcomeNewUser());
        }
        $actor = auth()->user();
        $users = User::with('Settings')->where('id', '!=', $user->id)->where('id', '!=', $actor->id)->get();
        $users_collections=[];
        foreach ($users as $user){
            if($user->settings()->where('name','receive-noti-4new-users')->first()){
                if($user->settings()->where('name','receive-noti-4new-users')->first()->value==1){
                    $users_collections[]=$user;
                }

            }
        }


        Notification::send($users_collections, new NewUserCreatedDB($actor, $user));


    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
