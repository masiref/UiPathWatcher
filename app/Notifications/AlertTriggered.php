<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertTriggered extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($alert)
    {
        $this->alert = $alert;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $app = config('app.name');
        $id = $this->alert->id;
        $level = ucfirst($this->alert->definition->level);
        $process = $this->alert->watchedAutomatedProcess;
        $client = $process->client;
        $title = $this->alert->trigger->title;
        
        $body = "ID: $id\nClient: $client\nAutomated process: $process\nAlert trigger: $title";
        
        return [
            'title' => "$app - $level alert triggered",
            'body' => $body,
            'level' => $level
        ];
    }
}
