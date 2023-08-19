<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Mail\NewOrderEmail;
use App\Mail\OrderShippedEmail;
use App\Mail\ShowroomOrderEmail;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Barryvdh\DomPDF\Facade\Pdf;


class MailTestController extends Controller
{

    private $mail_1='smooot.stu@gmail.com';
    private $mail_2='kraikan@prempracha.com';
    private $mail_3='showroom@prempracha.com';

    private $sr_mail=['kraikan@prempracha.com','showroom@prempracha.com'];
    private $sr_mail2=['smooot.stu@gmail.com','mawkard.th@gmail.com'];
    // private $sr_mail3=['aviroot@prempracha.com','info@prempracha.com','kraikan@prempracha.com','shoponline@prempracha.com','mawkard.th@gmail.com'];
    private $sr_mail3=['mawkard.th@gmail.com'];


    public function view()
    {
        return view('mail.email_test');
    }

    public function newOrder(Request $request)
    {
        $OrderId = $request->OrderID;
        
        $payment = Payment::query()
                    ->where(['order_id' => $OrderId])
                    ->first();

        // dd($payment);

        $this->OrderConfirmedMail($payment);

        // return view('mail.email_test');
        dd('mail sent');
    }

    private function OrderConfirmedMail(Payment $payment)
    {
        
        $order = $payment->order;

        // dd($order);

        // $adminUsers = User::where('is_admin', 1)->get();

        // foreach ([...$adminUsers, $order->user] as $user) {
        //     Mail::to($user)->send(new NewOrderEmail($order, (bool)$user->is_admin));
        // }

        $maildata=[
            'totalpayment'=>number_format(($order['total_price']+$order['insurance']+$order['shipping']),2)
        ];

        $pdf = Pdf::loadView('pdf.invoice', $order);
        return $pdf->download('invoice.pdf');

        // dd($maildata['totalpayment']);
        
        // Mail::to($this->sr_mail3)->send(new NewOrderEmail($order,$maildata));
    }

    public function showroomOrder(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = Order::query()
                    ->where(['id' => $OrderId])
                    ->first();

        $totaypayment=0;


        Mail::to($this->sr_mail3)->send(new ShowroomOrderEmail($order),['mdata'=>$maildata]);

        return view('mail.email_test');
    }
    public function OrderShipped(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = Order::query()
                    ->where(['id' => $OrderId])
                    ->first();

        Mail::to($this->sr_mail3)->send(new OrderShippedEmail($order));

        return view('mail.email_test');
    }

    public function PdfOrderinfo(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = Order::query()
                    ->where(['id' => $OrderId])
                    ->first();

        Mail::to($this->sr_mail3)->send(new PdfController($order));

        return view('mail.email_test');
    }

    //
}
