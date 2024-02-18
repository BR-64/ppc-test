<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mailer\Envelope;

class ShowroomOrderEmail extends Mailable
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

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('#'.$this->order['id'].' Prempracha Online Shop : You have received an order!')
            // ->subject('Prempracha Online Shop : You have received an order! #'.$this->order['id'])
            ->view('mail.showroomOrder');
    }

    public function attachments()
{
    $OrderId = $this->order['id'];

    $order = (Order::query()
                ->where(['id' => $OrderId])
                ->first());

    $qty = OrderItem::query()
                ->where(['order_id' => $OrderId])
                ->sum('quantity');
    
    $boxcount = $order->boxcount;

    $pdf = Pdf::loadView('pdf.orderinfo_fin',compact('order'));
    $pdf2 = Pdf::loadView('pdf.invoice',compact('order','qty'));
    $pdf3 = Pdf::loadView('pdf.shippingLabel',compact('order','boxcount'));
    
    return [
        Attachment::fromData(fn()=> $pdf->output(),'OrderInfo_'.$OrderId.'.pdf'),
        Attachment::fromData(fn()=> $pdf2->output(),'Invoice_'.$OrderId.'.pdf'),
        Attachment::fromData(fn()=> $pdf3->output(),'ShippingLabel_'.$OrderId.'.pdf'),
    ];
}
}
