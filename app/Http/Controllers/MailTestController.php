<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Mail\NewOrderEmail;
use App\Mail\OrderShippedEmail;
use App\Mail\PaymentCompleted;
use App\Mail\ShowroomOrderEmail;
use App\Mail\YourQuotation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Session;

// use Barryvdh\DomPDF\PDF as PDF;

class MailTestController extends Controller
{

    private $mail_1='smooot.stu@gmail.com';
    private $mail_2='kraikan@prempracha.com';
    private $mail_3='showroom@prempracha.com';

    private $sr_mail=['kraikan@prempracha.com','showroom@prempracha.com'];

    // private $sr_mail=['kraikan@prempracha.com','showroom@prempracha.com'];
    private $sr_mail2=['smooot.stu@gmail.com','mawkard.th@gmail.com'];
    private $sr_mail3=['aviroot@prempracha.com','info@prempracha.com','kraikan@prempracha.com','shoponline@prempracha.com','mawkard.th@gmail.com'];
    // private $adminUsers = User::where('is_admin', 1)->get();
    // private $ppc_team = User::where('is_admin', 2)->get();
    // private $sr_mail3=['mawkard.th@gmail.com'];


    public function view()
    {
        return view('mail.email_hub');
    }
    public function admail()
    {
        return view('mail.admin_mail_control');
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
    public function newOrder_created(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = (Order::query()
                ->where(['id' => $OrderId])
                ->first());
        
        $buyer = $order->user;

        $payment = Payment::query()
                    ->where(['order_id' => $OrderId])
                    ->first();

        // $this->OrderConfirmedMail($payment);

        
        $adminUsers = User::where('is_admin', 1)->get();
        $ppc_team = User::where('is_admin', 2)->get();
        
        // dd($adminUsers);
        // dd($ppc_team);

        foreach ([...$adminUsers, ...$ppc_team,  $buyer] as $user) {
            // print_r($user->email);
            Mail::to($user->email)->send(new NewOrderEmail($order));
        }

        dd('neworder created mail sent');
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

        // $pdf = Pdf::loadView('pdf.invoice', $order);
        // return $pdf->download('invoice.pdf');

        // dd($maildata['totalpayment']);

        // $pdf = Pdf::loadView('pdf.shippingLabel',compact('order'));
        
        Mail::to($this->sr_mail3)->send(new NewOrderEmail($order,$maildata));
    }

    public function showroomOrder(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = Order::query()
                    ->where(['id' => $OrderId])
                    ->first();

        $totaypayment=0;

        $maildata=[
            'totalpayment'=>number_format(($order['total_price']+$order['insurance']+$order['shipping']),2)
        ];

        $pdf1 = Pdf::loadView('pdf.orderinfo',compact('order'));
        // $pdf2 = Pdf::loadView('pdf.shippingLabel',compact('order'));
        // $pdf = Pdf::loadView('pdf.shippingLabel',compact('order'));

        Mail::to($this->sr_mail3)->send(new ShowroomOrderEmail($order),['mdata'=>$maildata])->AddAttachment($pdf1->output(),'orderinfo.pdf');


        return view('mail.email_test');
    }
    public function showroomOrder_final(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = Order::query()
                    ->where(['id' => $OrderId])
                    ->first();

        Mail::to($this->sr_mail)->send(new ShowroomOrderEmail($order));

        dd('showroom mail sent');
    }

    public function PaymentCompleted(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = Order::query()
                    ->where(['id' => $OrderId])
                    ->first();
        $buyer = $order->user;

        $adminUsers = User::where('is_admin', 1)->get();
        $ppc_team = User::where('is_admin', 2)->get();

        foreach ([...$adminUsers, ...$ppc_team,  $buyer] as $user) {
            // print_r($user->email);
            Mail::to($user->email)->send(new PaymentCompleted($order));
        }
        
        Mail::to($this->sr_mail)->send(new ShowroomOrderEmail($order));

        dd('PaymentCompleted mail sent');

        return view('mail.email_test');
    }
    public function OrderShipped(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = Order::query()
                    ->where(['id' => $OrderId])
                    ->first();
        $buyer = $order->user;

        // Mail::to($this->sr_mail3)->send(new OrderShippedEmail($order));

        $adminUsers = User::where('is_admin', 1)->get();
        $ppc_team = User::where('is_admin', 2)->get();

        foreach ([...$adminUsers, ...$ppc_team,  $buyer] as $user) {
            // print_r($user->email);
            Mail::to($user->email)->send(new OrderShippedEmail($order));
        }

        dd('Order shipped mail sent');

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

    public function quotation(Request $request)
    {
        $OrderId = $request->OrderID;

        $order = Order::query()
                    ->where(['id' => $OrderId])
                    ->first();
        
        $qty = OrderItem::query()
                    ->where(['order_id' => $OrderId])
                    ->sum('quantity');
        
        $pdf = Pdf::loadView('pdf.quotation',compact('order','qty'));
        

        Mail::to($this->sr_mail3)
            ->send(new YourQuotation($order,$OrderId));
            // ->attachData($pdf->output(), "text.pdf");

        // Mail::to($this->sr_mail3)
        //     ->send(new YourQuotation($order),function($message)use($pdf) {
        //             $message->attachData($pdf->output(), "text.pdf");
        //         });

        // Mail::to($this->sr_mail3)
        // ->send('mail.Quotation_test',$order);
        
        // Mail::send('mail.Quotation_test',$order);

        return view('mail.email_test');
    }

    public function mailpdf_markdown_test(Request $request)
    {
        $data["email"] = "contact@cdlcell.com";
        $data["title"] = "cdlcell";
        $data["body"] = "Demo";

        $OrderId = $request->OrderID;

        $order = Order::query()
                    ->where(['id' => $OrderId])
                    ->first();
        
        $qty = OrderItem::query()
                    ->where(['order_id' => $OrderId])
                    ->sum('quantity');
  
        $pdf = Pdf::loadView('pdf.quotation',compact('order','qty'));
  
        Mail::send('mail.Quotation_test', $data, function($message)use($data, $pdf) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), "text.pdf");
        });
  
        dd('Mail sent successfully');
    }
    public function mailpdf_test2(Request $request)
    {
        
        $OrderId = $request->OrderID;
        
        $order = Order::query()
        ->where(['id' => $OrderId])
        ->first();
        
        $qty = OrderItem::query()
        ->where(['order_id' => $OrderId])
        ->sum('quantity');
        
        $data = array(
            'id' => $request->OrderID,
            // 'name' =>  $cusname
            'name' =>  $order->customer['first_name']
        );
        
        // dd($data['name']);

        $pdf = Pdf::loadView('pdf.orderinfo',compact('order','qty'));
        $pdf2 = Pdf::loadView('pdf.invoice',compact('order','qty'));
        $pdf3 = Pdf::loadView('pdf.shippingLabel',compact('order','qty'));
        

    Mail::send('mail.showroomOrder', $data, function($message) use ($data,$pdf,$pdf2,$pdf3){
            $message->from('info@**********');
            $message->to($this->sr_mail3);
            $message->subject('You have received an order! '.$data['name']);


            //Attach PDF doc
            $message->attachData($pdf->output(),'orderinfo_'.$data['name'].'.pdf');
            $message->attachData($pdf2->output(),'invoice_'.$data['name'].'.pdf');
            $message->attachData($pdf3->output(),'boxlabel_'.$data['name'].'.pdf');
        });

    Session::flash('success', 'Hello &nbsp;'.$data['name'].'&nbsp;Thank You for choosing us. Will reply to your query as soon as possible');

    return redirect()->back();
    }

}
