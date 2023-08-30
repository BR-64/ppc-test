<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class YourQuotation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Order $order, $OrderId)
    {
        // $this->ppdf=$pdf;
        $this->oid=$OrderId;

        //
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    // public function envelope()
    // {
    //     return new Envelope(
    //         subject: 'Your Quotation',
    //     );
    // }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     * 
     */
    // public function content()
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    public function build()
    {
        return $this
            ->subject('Your Quotation')
            ->markdown('mail.Quotation_test')
            ->attachData($this->Qpdf(), 'quote.pdf', [
                'mime' => 'application/pdf',
            ]); 
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [
            // Attachment::fromData(fn () => $this->Qpdf, 'Quotation.pdf')
            //     ->withMime('application/pdf'),
        ];
    }

    public function Qpdf()
    {
        $OrderId = $this->oid;

        $order = (Order::query()
                    ->where(['id' => $OrderId])
                    ->first());
        
        $qty = OrderItem::query()
            ->where(['order_id' => $OrderId])
            ->sum('quantity');

        $pdf = Pdf::loadView('pdf.quotation',compact('order','qty'));
    }
}
