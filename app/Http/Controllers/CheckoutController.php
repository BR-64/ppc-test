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

    public function createSC(){
        

        // $scdate=date('Ymd');
        $scdate="20231220";

        ///// random no duplicate
        // $r=range(1,100);
        // shuffle($r);

        $ref1='WB'.rand(1,100);

        ////////// order data
        $shipping_cost=400;
        $insure_cost=550;
        
        // $item_sc=[
        //     'item_code'=>$code,
        //     'unit_code'=>'PCS',
        //     'qty'=>$qty,
        //     'unit_price'=>$retail_price,
        //     'discount_amt'=>$discount
        // ];

        
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
            'discount_amt'=>0
        ];
        $item_sc_test2=[
            'item_code'=>"C34IEJ5433X261",
            'unit_code'=>'PCS',
            'qty'=>1,
            'unit_price'=>480,
            'discount_amt'=>0
        ];

//         {"item_code":"R34OBM897V89","unit_code":"PCS","qty":"1","unit_price":"500","discount_amt":""}
// ,{"item_code":"R34OBM859V89","unit_code":"PCS","qty":"1","unit_price":"2000","discount_amt":""}
// ,{"item_code":"003","unit_code":"BAHT","qty":"1","unit_price":"550","discount_amt":"0"},{"item_code":"002","unit_code":"BAHT","qty":"1","unit_price":"1263","discount_amt":"0"}


        $ch = curl_init();                    // Initiate cURL
        $url = "http://1.1.220.113:7000/PrempApi.asmx/createSC"; // Where you want to post data

        // $sa_header=[{"doc_date":"20231220","vat_rate":"7","discount_amt":"0","ref1":"WBtest019"}];
        $sa_header=array([
                "doc_date"=>$scdate,
                "vat_rate"=>"7",
                "discount_amt"=>"0",
                "ref1"=>$ref1,
            ]);
        $sa_detail=array(
            $item_sc_test,
            $item_sc_test2,
            $shipping_sc,
            $insurance_sc,
        );

        // $sc_payload=array(
        //     "sa_header"=>[
        //         $sa_header
        //     ],
        //     "sa_detail"=>[
        //         // $items,$shipping_sc,$insurance_sc
        //         $item_sc_test
        //         ]
        //     );

        // $sc_json=json_encode($sc_payload);
        // print_r($sc_json);

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
        
        var_dump($output); // Show output

        // return response()->json(['Message'=>'status code 200 ok'], 200);
    }
}
