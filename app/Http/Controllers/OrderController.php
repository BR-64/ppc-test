<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderEmail;
use App\Mail\OrderConfirmedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $orders = Order::query()
            ->where(['created_by' => $user->id])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order.index', compact('orders'));
    }

    public function view(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = \request()->user();
        if ($order->created_by !== $user->id) {
            return response("You don't have permission to view this order", 403);
        }

        return view('order.view', compact('order'));
    }

    public function mail_order_confirmed(Payment $payment)
    {


        $order = $payment->order;
        // echo $payload;

        var_dump ($body);

        Mail::to($order->user)->send(new OrderConfirmedMail($body));  

        return response()->json(['Message'=>'status code 200 ok'], 200);

    }

    public function mail_new_order(Order $order){
        Mail::to('admin@prempracha.com')->send(new NewOrderEmail());

        return response()->json(['Message'=>'status code 200 ok'],200);
    }

    public function payOrder(Request $request){
        $user = $request->user();

        $mid="451005592743001";

        $R_id=$_POST["orderid"];
        $R_STprice=$_POST["subtotal_price"];
        $R_dispercent=$_POST["dispercent"];
        $R_Discount=$_POST["Discount"];
        $R_shipcost=$_POST["Shipcost"];
        $R_Netprice=$_POST["amount"];
        $R_Insurance=$_POST["Insurance"];
        $R_ckouttype=$_POST["checkouttype"];

        // <input type="hidden" name="amount" value="{{$totalpayment}}">
        // <input type="hidden" name="reforder" value="{{$orderid}}">  
        return view('checkout.step3_prod',[
            'itemsprice'=> $R_STprice,
            'dispercent' => $R_dispercent,
            'discount_amount' => $R_Discount,
            'totalpayment'=> $R_Netprice,
            'ordertype'=>  $R_ckouttype,
            'shipcost'=> $R_shipcost,
            'insure'=> $R_Insurance,
            'orderid'=> $R_id,
            'mid'=> $mid,
        ]);
        }
}
