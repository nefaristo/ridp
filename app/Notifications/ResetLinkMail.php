<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetLinkMail extends Notification
{
    use Queueable;
    private $token=NULL;
    private $data=[];
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token,$data=[])
    {
        $this->token=$token;
        $this->data=$data;
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
            //->greeting("Reset password")
            ->line($this->data["intro"])
            ->action($this->data["actionText"], url('password/reset/'.$this->token))            
            ->line($this->data["outro"])
            ->view("mail.resetLink")               
            //->attach(‘/path/to/file’,[‘as’=>’name.pdf’,’mime’=>’application/pdf’]) //solo 1° obbligatorio
            //->text('emails.orders.shipped_plain') //definizione alternativa solo testo        
            ;
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
            //
        ];
    }
}
