<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyOrderStatusChange extends Mailable
{
    use Queueable, SerializesModels;

    public $order_id;
    public $order_status;
    public $order_created_at;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_id, $order_status, $order_created_at)
    {
        $this->order_id = $order_id;
        $this->order_status = $order_status;
        $this->order_created_at = $order_created_at;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Order Update')
            ->markdown('emails.notify_customer');
    }
}
