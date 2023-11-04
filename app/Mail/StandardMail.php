<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StandardMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
        /*	return $this->from("here@this.com")
        ->view(‘emails.order.shipped)
        ->with([‘name’=>$this->order->name,....]) //esempio di passaggio parametri 
        ->attach(‘/path/to/file’,[‘as’=>’name.pdf’,’mime’=>’application/pdf’]) //solo 1° obbligatorio
        ->text('emails.orders.shipped_plain') //definizione alternativa solo testo
         * 
         */
    }
}
