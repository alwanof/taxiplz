<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class NewUserCreatedDB extends Notification
{
    use Queueable;
    private $user;
    private $newUser;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user,User $newUser)
    {
        $this->user=$user;
        $this->newUser=$newUser;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }



    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'data'=>$this->user->name.__('noticenter.new-user-noti').' '.$this->newUser->name,
            'user'=>$this->user,

        ];
    }
}
