<?php

namespace App\Notifications;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserCreated extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->user->name.' has been create a new user('.$this->newUser->name.')')
            ->line($this->user->name.' has been create a new user('.$this->newUser->name.') as following:')
            ->line('email: '.$this->newUser->email)
            ->action('Login', url('/login'))
            ->line('Thank you for using our application!');
    }


}
