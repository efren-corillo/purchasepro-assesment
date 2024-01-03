<?php

namespace App\Mail;

use App\Models\ProcessedOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public ProcessedOrder $order;

    /**
     * Create a new message instance.
     *
     *
     * @return void
     */
    public function __construct(ProcessedOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Order Confirmed: '.$this->order->order_number)
            ->markdown('emails.confirmed', ['order' => $this->order]);
    }
}
