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
use Carbon\Carbon;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Session;

// use Barryvdh\DomPDF\PDF as PDF;

class MailTestController extends Controller
{

    private $mail_1='smooot.stu@gmail.com';
    private $mail_2='kraikan@prempracha.com';
    private $mail_3='showroom@prempracha.com';

    private $sr_mail=['kraikan@prempracha.com','showroom@prempracha.com','shoponline@prempracha.com','smooot.stu@gmail.com']; // real use
    // private $sr_mail=['smooot.stu@gmail.com']; // test

    // private $sr_mail=['kraikan@prempracha.com','showroom@prempracha.com'];
    private $sr_mail2=['smooot.stu@gmail.com','mawkard.th@gmail.com'];
    private $sr_mail3=['aviroot@prempracha.com','kraikan@prempracha.com','shoponline@prempracha.com','mawkard.th@gmail.com'];
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
        set_time_limit(30000);
        
        $OrderId = $request->OrderID;

        // dd( $request);
        // dd( $OrderId);

        $order = Order::query()
                    ->where(['id' => $OrderId])
                    ->first();

        // dd($order->status);

        if ($order->status == 'paid') {  
    //// create SC
            // dd(is_null($order->enpro_doc));
            if (is_null($order->enpro_doc) ){
                $this->createSCauto($order->id);
            }; 

            // $this->createSCauto($order->id);
    
            $buyer = $order->user;

            $adminUsers = User::where('is_admin', 1)->get();
            $ppc_team = User::where('is_admin', 2)->get();

            foreach ([...$adminUsers, ...$ppc_team,  $buyer] as $user) {
                // print_r($user->email);
                Mail::to($user->email)->send(new PaymentCompleted($order));
            }
            
            Mail::to($this->sr_mail)->send(new ShowroomOrderEmail($order));

            dd('PaymentCompleted mail sent');
        } else {

            dd('Order Status is "Unpaid"');

        }

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


    public function createSCauto($orderid){
            $OrderId = $orderid;
            $Order = (Order::query()
                        ->where(['id' => $OrderId])
                        ->first());
            $Items = OrderItem::query()
                        ->where(['order_id' => $OrderId])
                        ->get();
            
        $scdate=date('Ymd'); /// for real use
        $docdate=date('ymd'); /// for enpro ID

        $latestdoc = Order::whereDate('created_at', Carbon::today())->get()->count();  /// count today doc
        
        if ($latestdoc == 1) {
            $enproID = '0001';
        } else {
            $enproID = str_pad($latestdoc + 1, 4, '0', STR_PAD_LEFT);
        };
    
        // $enproID = str_pad($latestdoc + 1, 4, '0', STR_PAD_LEFT);

        ///// random no duplicate
            $enpro_doc='WB'.$docdate.'_'.$enproID;

        ////////// order data
            $shipping_cost=$Order['shipping'];
            $insure_cost=$Order['insurance'];
           
        /////// order items data
            $sa_detail=[];
                foreach ($Items as $Item => $value){
                    $sa_detail[]=[
                        'item_code'=>$value->product->item_code,
                        // 'item_code'=>$item->product->item_code,
                        'unit_code'=>'PCS',
                        'qty'=>$value->quantity,
                        'unit_price'=>$value->product->retail_price,
                        // 'discount_amt'=>0
                        'discount_amt'=>($Order->discount_percent/100)*($value->product->retail_price)*$value->quantity
                    ];
            }  
            
            $shipping_sc=[
                'item_code'=>'002',
                'unit_code'=>'BAHT',
                'qty'=>1,
                'unit_price'=>$shipping_cost,
                'discount_amt'=>0
            ];
            
            $insurance_sc=[
                'item_code'=>'003',
                'unit_code'=>'BAHT',
                'qty'=>1,
                'unit_price'=>$insure_cost,
                'discount_amt'=>0
            ];
            
    
            $ch = curl_init();                    // Initiate cURL
            $url = "http://1.1.220.113:7000/PrempApi.asmx/createSC"; // Where you want to post data

            $sa_header=array([
                    "doc_date"=>$scdate,
                    "vat_rate"=>"7",
                    "discount_amt"=>"0",
                    "ref1"=>$enpro_doc,
                ]);
            array_push($sa_detail,$shipping_sc,$insurance_sc);
    
            $sah_json=json_encode($sa_header);
            $sad_json=json_encode($sa_detail);
    
            $key_val="sa_header=".$sah_json."&sa_detail=".$sad_json;

            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something

            curl_setopt($ch, CURLOPT_POSTFIELDS, $key_val,); // Define what you want to post
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the output in string format
    
            $output = curl_exec ($ch); // Execute 
            curl_close ($ch); // Close cURL handle
    
            //// update sc number in web order data
            
            preg_match('#(?<=\[{)(.*?)(?=\}])#', $output,$match);
            $mjson = "{".$match[1]."}"; /// make output to json format
            $dataEnpro=json_decode($mjson,true);
    
            /// check if sc sent completed
                if ($dataEnpro['str_return']=="success"){
                    Order::where('id',$OrderId)->update(['enpro_doc'=>$enpro_doc]);    
    
                } else {
                    echo nl2br ("\n \n Error in creating SC \n");
                }
    
        }
    
}
