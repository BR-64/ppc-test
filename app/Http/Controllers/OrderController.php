<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderEmail;
use App\Mail\OrderConfirmedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    public function cancelOrder(Request $request){
        // set_time_limit(300000);

            /// get order data
            $OrderId = $request->OrderID;
            $Order = (Order::query()
                ->where(['id' => $OrderId])
                ->value('status'));
            $Items = OrderItem::query()
                        ->where(['order_id' => $OrderId])
                        ->get();
    
            if ($Order !== 'Cancelled'){
    
                $toDelItems=[];
    
                // create normal array
                // foreach ($Items as $Item){
                //     $toDelItems[]=[
                //         'item_code'=> $Item->product->item_code,
                //         'quantity'=> $Item->quantity
                //     ];
                // }
    
            // create associative array
                foreach ($Items as $Item){
                    $toDelItems[$Item->product->item_code]= $Item->quantity;
                }
    
            /// add stock back to web stock
                foreach ($toDelItems as $Item=>$value){
    
                    $curStock = (Stock::query()
                    ->where(['item_code' => $Item])
                    ->value('stock'));
    
                    $newStock=$curStock+ $value;
    
                    Stock::where('item_code',$Item)->update(['stock'=>$newStock]);   
    
                    echo('Item : ['.$Item.'] added ['.$value.'] stock back.'."<br/>");
                }
            } else {
                dd('Order already Cancelled');
                }
            //update Order status to 'cancel'
                Order::where('id',$OrderId)->update(['status'=>'Cancelled']); 
    
                dd('Order no.['.$OrderId.'] has been calcelled');
    
            }

    public function testHttp(){
        $jsonData = [
            '_token'=>csrf_token(),
            // 'OrderID' => $data['id'],
            // 'OrderID' => $this->record->id,
            'OrderID' => 279,
         ];

         dd($jsonData);

         $url= route('cancelOrder');
        // $response = Http::post($url,$jsonData);
        // $response = Http::post($url);
        $response = Http::get('www.pantip.com');
        // $response = Http::post($url,['OrderID' => 279]);
        // $response = Http::asForm()->post($url,['OrderID' => 279]);
        // $response = Http::asForm()->post($url,$jsonData);

        dd($response);
    }
    public function testHttp_ob(){
        $url= route('cancelOrder');

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->withBody(http_build_query([
                'OrderID' => 279,

                ]), 'application/json')->post(
                    $url);
        // )->collect()->toArray();
    

        dd($response);
    }
    
    
}
