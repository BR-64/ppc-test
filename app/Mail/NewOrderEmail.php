<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mailer\Envelope;

class NewOrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Order $order)
    {
        $this->order=$order;


        // $maildata=[
        //     'totalpayment'=>number_format(($order['total_price']+$order['insurance']+$order['shipping']),2)
        // ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Confirmation of your Order')
            // ->view('mail.TestMKdown');
            // ->markdown('mail.TestMKdown');
            ->markdown('mail.NewOrder_Mkdown');
            // ->with([
            //     'totalpayment'=>number_format(($this->order['total_price']+$this->order['insurance']+$this->order['shipping']),2)
            // ]);
    }
}
