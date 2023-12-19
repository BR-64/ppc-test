<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\Cart;
use App\Mail\NewOrderEmail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->retail_price * $quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'thb',
                    'product_data' => [
                        'name' => $product->item_code,
                       'images' => [$product->image]
                    ],
                    'unit_amount' => $product->retail_price * 100,
                ],
                'quantity' => $quantity,
            ];
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->retail_price
            ];
        }
//        dd(route('checkout.failure', [], true));

//        dd(route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}');

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        // Create Order
        $orderData = [
            'total_price' => $totalPrice,
            'status' => OrderStatus::Unpaid,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];
        $order = Order::create($orderData);

        // Create Order Items
        foreach ($orderItems as $orderItem) {
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }

        // Create Payment
        $paymentData = [
            'order_id' => $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => $session->id
        ];
        Payment::create($paymentData);

        CartItem::where(['user_id' => $user->id])->delete();

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        try {
            $session_id = $request->get('session_id');
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            if (!$session) {
                return view('checkout.failure', ['message' => 'Invalid Session ID']);
            }

            $payment = Payment::query()
                ->where(['session_id' => $session_id])
                ->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])
                ->first();
            if (!$payment) {
                throw new NotFoundHttpException();
            }
            if ($payment->status === PaymentStatus::Pending->value) {
                $this->updateOrderAndSession($payment);
            }
            $customer = \Stripe\Customer::retrieve($session->customer);
            return view('checkout.success', compact('customer'));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
    }

    public function failure(Request $request)
    {
        return view('checkout.failure', ['message' => ""]);
    }

    public function checkoutOrder(Order $order, Request $request)
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'thb',
                    'product_data' => [
                        'name' => $item->product->item_code,
                        'images' => [$item->product->image],
                    //    'images' => [$product->image]
                    ],
                    'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            // 'payment_method_types'=>['card'],
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        $order->payment->session_id = $session->id;
        $order->payment->save();


        return redirect($session->url);
    }

    public function webhook()
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $endpoint_secret = env('WEBHOOK_SECRET_KEY');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 401);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 402);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $paymentIntent = $event->data->object;
                $sessionId = $paymentIntent['id'];

                $payment = Payment::query()
                    ->where(['session_id' => $sessionId, 'status' => PaymentStatus::Pending])
                    ->first();
                if ($payment) {
                    $this->updateOrderAndSession($payment);
                }
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('', 200);
    }

    private function updateOrderAndSession(Payment $payment)
    {
        $payment->status = PaymentStatus::Paid->value;
        $payment->update();

        $order = $payment->order;

        $order->status = OrderStatus::Paid->value;
        $order->update();
        $adminUsers = User::where('is_admin', 1)->get();

        foreach ([...$adminUsers, $order->user] as $user) {
            Mail::to($user)->send(new NewOrderEmail($order, (bool)$user->is_admin));
        }
    }

    public function quotation(Request $request)
    {
               /** @var \App\Models\User $user */
               $user = $request->user();

               \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
       
               [$products, $cartItems] = Cart::getProductsAndCartItems();
       
               $orderItems = [];
               $lineItems = [];
               $totalPrice = 0;
               foreach ($products as $product) {
                   $quantity = $cartItems[$product->id]['quantity'];
                   $totalPrice += $product->retail_price * $quantity;
                   $lineItems[] = [
                       'price_data' => [
                           'currency' => 'thb',
                           'product_data' => [
                               'name' => $product->item_code,
       //                        'images' => [$product->image]
                           ],
                           'unit_amount' => $product->retail_price * 100,
                       ],
                       'quantity' => $quantity,
                   ];
                   $orderItems[] = [
                       'product_id' => $product->id,
                       'quantity' => $quantity,
                       'unit_price' => $product->retail_price
                   ];
               }
       //        dd(route('checkout.failure', [], true));
       
       //        dd(route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}');
       
               $session = \Stripe\Checkout\Session::create([
                   'line_items' => $lineItems,
                   'mode' => 'payment',
                   'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
                   'cancel_url' => route('checkout.failure', [], true),
               ]);
       
               // Create Order
               $orderData = [
                   'total_price' => $totalPrice,
                   'status' => OrderStatus::Quotation,
                   'created_by' => $user->id,
                   'updated_by' => $user->id,
               ];
               $order = Order::create($orderData);
       
               // Create Order Items
               foreach ($orderItems as $orderItem) {
                   $orderItem['order_id'] = $order->id;
                   OrderItem::create($orderItem);
               }
       
               // Create Payment
               $paymentData = [
                   'order_id' => $order->id,
                   'amount' => $totalPrice,
                   'status' => PaymentStatus::Pending,
                   'type' => 'cc',
                   'created_by' => $user->id,
                   'updated_by' => $user->id,
                   'session_id' => $session->id
               ];
               Payment::create($paymentData);
       
               CartItem::where(['user_id' => $user->id])->delete();
       
               return redirect($session->url);
       
    }

    public function createSC(Request $request){
        $OrderId = $request->OrderID;
        $Order = (Order::query()
                    ->where(['id' => $OrderId])
                    ->first());
        $Items = OrderItem::query()
                    ->where(['order_id' => $OrderId])
                    ->get();
        
    // $scdate=date('Ymd'); /// for real use
    // $scdate=date('Y-m-d');  /// for error test
    // $scdate="20241220"; /// for test
        $scdate=date('Ymd', strtotime('+1 year'));  /// for test

    ///// random no duplicate
        // $r=range(1,100);
        // shuffle($r);
        $enpro_doc='WB'.rand(1,100);

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
                    'discount_amt'=>($Order->discount_percent/100)*($value->product->retail_price)

                ];
        }

        // dd($sa_detail);        
        
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
        
        $item_sc_test=[
            'item_code'=>"R34OBM859V89",
            'unit_code'=>'PCS',
            'qty'=>2,
            'unit_price'=>340,
            'discount_amt'=>20
        ];

        $ch = curl_init();                    // Initiate cURL
        $url = "http://1.1.220.113:7000/PrempApi.asmx/createSC"; // Where you want to post data

        // $sa_header=[{"doc_date":"20231220","vat_rate":"7","discount_amt":"0","ref1":"WBtest019"}];
        $sa_header=array([
                "doc_date"=>$scdate,
                "vat_rate"=>"7",
                "discount_amt"=>"0",
                "ref1"=>$enpro_doc,
            ]);
        array_push($sa_detail,$shipping_sc,$insurance_sc);
        // array_push($item_sc_test,$shipping_sc,$insurance_sc);


        $sah_json=json_encode($sa_header);
        $sad_json=json_encode($sa_detail);

        // dd($sah_json);
        $key_val="sa_header=".$sah_json."&sa_detail=".$sad_json;

        print_r($key_val);
        // dd($key_val);

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $sc_json,); // Define what you want to post
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

                // return back()->withSuccess('SC created Done !');
                echo nl2br ("\n \n SC created ! \n");


            } else {
                echo nl2br ("\n \n Error in creating SC \n");
            }

        var_dump($output);


		// return back()->withSuccess('UPload Done !');
    }

    public function checkoutOrder_K(Order $order, Request $request)
    {

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'thb',
                    'product_data' => [
                        'name' => $item->product->item_code,
                        'images' => [$item->product->image],
                    //    'images' => [$product->image]
                    ],
                    'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $order->payment->save();


        return redirect('/orders');
    }
}
