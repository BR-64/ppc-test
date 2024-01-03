<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Enums\OrderStatus;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Models\webhook;
use App\Helpers\Cart;
use App\Mail\WebhookMail;
use App\Models\CartItem;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use App\Models\Voucher;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class kCheckoutController extends Controller
{
    private $publickey = "pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3f"; //test
    private $secretkey = "skey_test_216332Jyp8b6aUYfYJKgBqEJpdtMDWlcgCg3M"; // test key
    private $MID= "401012148319001";

    public function paymentresult(Request $request){

        // $body=$request->json()->all()

        // $obj=json_decode($_POST['status']);
        response()->json(['success' => 'success'], 200);

        // $body=json_decode($request->getContent());

        // $body=json_decode($_POST,true);

        // $body = json_decode($request->json()->all());

        // $body = json_decode(file_get_contents('php://input'));

        // echo ($body);
    
        // if($obj=='success'){
        //     return view('checkout.success');
        // } 
        // else{
        //     return view('checkout.failure');
        // }

        // return redirect('/dd')->with([
        //     'body'=>$body
        // ]);

        return redirect('/');

    }
     

    public function webhooktest(Request $request){
    
        // $varname = json_decode(file_get_contents('php://input'));

        // echo $varname;

        // $obj=json_decode($request->post());

        $obj=json_decode($_POST['status']);

        // try{
        //     $obj=json_decode($_POST['transaction_state']);

        //     echo $obj;

        // } catch ('Authorized') {

        // };

        echo $obj;
    
    response()->json(['success' => 'success'], 200);

    return redirect('/');

    // return view('test.ppc_home');


    }
    public function kpayment(Request $request){
    $R_amount=$_POST["amount"];
    // $R_paymentmethod=$_POST["paymentMethods"];
    $R_paytype=$_POST["paytype"];
    // $R_product=$_POST["product"];

    // $publickey = "pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3fC"; // test
    // $secretkey = "skey_test_216332Jyp8b6aUYfYJKgBqEJpdtMDWlcgCg3M"; // test key

    // Test url
    // $cardApi_url = 'https://dev-kpaymentgateway-services.kasikornbank.com/card/v2/charge';
    // $qrApi_url ='https://dev-kpaymentgateway-services.kasikornbank.com/qr/v2/order';
    // $aliApi_url ='https://dev-kpaymentgateway-services.kasikornbank.com/card/v2/charge';

    // production url
    $cardApi_url = 'https://kpaymentgateway-services.kasikornbank.com/card/v2/charge';
    $qrApi_url ='https://kpaymentgateway-services.kasikornbank.com/qr/v2/order';
    $aliApi_url ='https://kpaymentgateway-services.kasikornbank.com/card/v2/charge';
    

    $payload = @file_get_contents('php://input');
    $body = json_decode($payload,true);

    var_dump ($body);
        function callAPI($method, $url, $data){
            $curl = curl_init();

            // $skey = "skey_test_216332Jyp8b6aUYfYJKgBqEJpdtMDWlcgCg3M"; // test
            $skey = "skey_prod_6726vx4ZPinx0ZawffVaVtJXid8rN4duJK55"; // real
         
            switch ($method){
               case "POST":
                  curl_setopt($curl, CURLOPT_POST, 1);
                  if ($data)
                     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                  break;
               case "PUT":
                  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                  if ($data)
                     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
                  break;
               default:
                  if ($data)
                     $url = sprintf("%s?%s", $url, http_build_query($data));
            }
         
            // OPTIONS:
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            //    'x-api-key:'.$skey,
               'x-api-key:skey_prod_6726vx4ZPinx0ZawffVaVtJXid8rN4duJK55',
               'Content-Type: application/json',
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
         
            // EXECUTE:
            $result = curl_exec($curl);
            if(!$result){die("Connection Failure");}
            curl_close($curl);
            return $result;
         }
    
         if ($R_paytype == "card_DCC" ){
             $R_TOKEN=$_POST["token"];
             $R_dcc_cur=$_POST["dcc_currency"];
             $R_amount=$_POST["amount"];
             $reforder = $_POST["reforder"];
            //  $reforder = rand();
             
         $data_array =  array(
                "amount"=> $R_amount,
                "currency" => "THB",
                "description" => "test product",
                "source_type" => "card",
                "mode" => "token",
                "token" => $R_TOKEN,
                "reference_order" => $reforder,
                "additional_data" => [
                  "mid"=> $this->MID,
                  "tid"=> "88292023"],
                "dcc_data"=> [
                    "dcc_currency"=>$R_dcc_cur
                ]
         
         );
            //call charge API with Token
            // $make_call = callAPI('POST','https://dev-kpaymentgateway-services.kasikornbank.com/card/v2/charge',json_encode($data_array));
            $make_call = callAPI('POST',$cardApi_url,json_encode($data_array));

             
             echo ($make_call);
             $response = json_decode($make_call, true);

            // var_dump($response);
            print_r($data_array);

            echo('response');
            print_r($response);
    
             $rediurl=$response["redirect_url"];
             return redirect($rediurl);
             
        // } elseif ($R_paytype == "card_MCC" ){
        //      $R_TOKEN=$_POST["token"];
        //      $reforder = rand();
             
        //  $data_array =  array(
        //         "amount"=> $R_amount,
        //         "currency" => "THB",
        //         "description" => "test product",
        //         "source_type" => "card",
        //         "mode" => "token",
        //         "token" => $R_TOKEN,
        //         "reference_order" => $reforder,
        //         "additional_data" => [
        //           "mid"=> "401232949944001"
        //    ]
        //  );
        //     //call charge API with Token
        //     $make_call = callAPI('POST',$cardApi_url,json_encode($data_array));
             
        //      echo ($make_call);
        //      $response = json_decode($make_call, true);
    
        //      $rediurl=$response["redirect_url"];
        //      return redirect($rediurl);

             
        } elseif ($R_paytype == "qr" ){
            // $R_OrderID=$_POST["id"];
            $reforder = $_POST["reforder"];

            $data_array =  array(
            "amount"=> $R_amount,
            "currency"=> "THB",
            "description"=> "TESTPRODUCT",
            "source_type"=> "qr",
            "reference_order" => $reforder
            );

            $make_call = callAPI('POST',$qrApi_url,json_encode($data_array));
             
            $response = json_decode($make_call, true);
   
            // $rediurl=$response["redirect_url"];
            return view('checkout.payQR',[
                'qrinfo'=>$response
            ]);

        } elseif ($R_paytype == "alipay" ){
            $reforder = $_POST["reforder"];

            $data_array =  array(
            "amount"=> $R_amount,
            "currency"=> "THB",
            "description"=> "TESTPRODUCT alipay",
            "source_type"=> "alipay",
            "reference_order" => $reforder,
            "additional_data" => [
                "mid"=> $this->MID
         ]
            );

            //call charge API with Token
            $make_call = callAPI('POST',$aliApi_url,json_encode($data_array));

            // echo $make_call;
             $response = json_decode($make_call, true);

            //  var_dump($response);
    
             $rediurl=$response["redirect_url"];
             return redirect($rediurl);
        }    
    }


    public function kpayment_step(Request $request){
    $R_amount=$_POST["amount"];
    // $R_paymentmethod=$_POST["paymentMethods"];
    $R_paytype=$_POST["paytype"];
    // $R_product=$_POST["product"];

    $publickey = "pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3fC";
    $secretkey = "skey_test_216332Jyp8b6aUYfYJKgBqEJpdtMDWlcgCg3M";
    

    $payload = @file_get_contents('php://input');
    $body = json_decode($payload,true);

    var_dump ($body);
        function callAPI($method, $url, $data){
            $curl = curl_init();
         
            switch ($method){
               case "POST":
                  curl_setopt($curl, CURLOPT_POST, 1);
                  if ($data)
                     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                  break;
               case "PUT":
                  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                  if ($data)
                     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
                  break;
               default:
                  if ($data)
                     $url = sprintf("%s?%s", $url, http_build_query($data));
            }
         
            // OPTIONS:
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            //    'x-api-key: skey_test_216332Jyp8b6aUYfYJKgBqEJpdtMDWlcgCg3M',
               'x-api-key: skey_test_216332Jyp8b6aUYfYJKgBqEJpdtMDWlcgCg3M',
               'Content-Type: application/json',
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
         
            // EXECUTE:
            $result = curl_exec($curl);
            if(!$result){die("Connection Failure");}
            curl_close($curl);
            return $result;
         }
    
         if ($R_paytype == "card_DCC" ){
             $R_TOKEN=$_POST["token"];
             $R_dcc_cur=$_POST["dcc_currency"];
             $R_amount2=$_POST["amount"];
             $reforder = rand();
             
         $data_array =  array(
                "amount"=> $R_amount,
                "currency" => "THB",
                "description" => "test product",
                "source_type" => "card",
                "mode" => "token",
                "token" => $R_TOKEN,
                "reference_order" => $reforder,
                "additional_data" => [
                  "mid"=> $this->MID,
                  "tid"=> "88292023"],
                "dcc_data"=> [
                    "dcc_currency"=>$R_dcc_cur
                ]
         
         );
            //call charge API with Token
            $make_call = callAPI('POST','https://dev-kpaymentgateway-services.kasikornbank.com/card/v2/charge',json_encode($data_array));
             
             echo ($make_call);
             $response = json_decode($make_call, true);

            // var_dump($response);
            print_r($data_array);

            echo('response');
            print_r($response);
    
             $rediurl=$response["redirect_url"];
             return redirect($rediurl);
             
        } elseif ($R_paytype == "card_MCC" ){
             $R_TOKEN=$_POST["token"];
             $reforder = rand();
             
         $data_array =  array(
                "amount"=> $R_amount,
                "currency" => "THB",
                "description" => "test product",
                "source_type" => "card",
                "mode" => "token",
                "token" => $R_TOKEN,
                "reference_order" => $reforder,
                "additional_data" => [
                  "mid"=> "401232949944001"
           ]
         );
            //call charge API with Token
            $make_call = callAPI('POST','https://dev-kpaymentgateway-services.kasikornbank.com/card/v2/charge',json_encode($data_array));
             
             echo ($make_call);
             $response = json_decode($make_call, true);
    
             $rediurl=$response["redirect_url"];
             return redirect($rediurl);

            // var_dump($response);

             
        } elseif ($R_paytype == "qr" ){
            // $R_OrderID=$_POST["id"];
            $reforder = rand();

            $data_array =  array(
            "amount"=> $R_amount,
            "currency"=> "THB",
            "description"=> "TEST QR",
            "source_type"=> "qr",
            "reference_order" => $reforder
            );

            $make_call = callAPI('POST','https://dev-kpaymentgateway-services.kasikornbank.com/qr/v2/order',json_encode($data_array));
             
            // echo ($make_call);
            $response = json_decode($make_call, true);
   
            // $rediurl=$response["redirect_url"];
            return view('checkout.payQR',[
                'qrinfo'=>$response
            ]);

        } elseif ($R_paytype == "alipay" ){
            $reforder = rand();

            $data_array =  array(
            "amount"=> 1777,
            "currency"=> "THB",
            "description"=> "TEST alipay",
            "source_type"=> "alipay",
            "reference_order" => $reforder,
            "additional_data" => [
                "mid"=> "501932408444001"
         ]
            );

            //call charge API with Token
            $make_call = callAPI('POST','https://dev-kpaymentgateway-services.kasikornbank.com/card/v2/charge',json_encode($data_array));

            // echo $make_call;
             
             $response = json_decode($make_call, true);

            //  var_dump($response);
    
             $rediurl=$response["redirect_url"];
             return redirect($rediurl);
        }    
    }


    public function webhook()
    {
        $payload = @file_get_contents('php://input');
        $body = json_decode($payload,true);

        // echo $payload;

        var_dump ($body);

        Mail::to('smoootstu.mailtest@gmail.com')->send(new WebhookMail($body));  

        // try {
        //     $event = \Stripe\Webhook::constructEvent(
        //         $payload, $sig_header, $endpoint_secret
        //     );
        // } catch (\UnexpectedValueException $e) {
        //     // Invalid payload
        //     return response('', 401);
        // } catch (\Stripe\Exception\SignatureVerificationException $e) {
        //     // Invalid signature
        //     return response('', 402);
        // }

        // Handle the eventP
        // switch ($event->type) {
        //     case 'checkout.session.completed':
        //         $paymentIntent = $event->data->object;
        //         $sessionId = $paymentIntent['id'];

        //         $payment = Payment::query()
        //             ->where(['session_id' => $sessionId, 'status' => PaymentStatus::Pending])
        //             ->first();
        //         if ($payment) {
        //             $this->updateOrderAndSession($payment);
        //         }
        //     // ... handle other event types
        //     default:
        //         echo 'Received unknown event type ' . $event->type;
        // }

        // switch($body['object']){
        //     case 'qr':
        //         echo ($body['id']);
        //         echo (' QR payment amount is ' .$body['amount']);

        //         echo ("\n".'  source ID '.$body['source']['id']);

        //         $payment = webhook::create([
        //             // 'id'=>(int)substr($body['id'],-2),
        //             'amount'=>$body['amount'],
        //             'order_id'=>(int)substr($body['id'],-2),
        //             'status'=>$body['status'],
        //             'type'=>$body['object'],
        //             'created_at'=>$body
        //         ]);

        //         // $this->createOrderPay($payment);
        //         break;

        //     case 'charge':
        //         echo ($body['id']);
        //         echo  (' Credit Card payment amount is'. $body['amount']);

        //         echo (' source ID'.$body['source']);

        //         break;
            
        //         default:
        //         echo 'Received unknown event type ';


        // }

        return response()->json(['Message'=>'status code 200 ok'], 200);
        // return Response::json([
        //     'hello' => $value
        // ], 200); // Status code here
        
    }

    private function createOrderPay(Payment $payment)
    {
        $payment->status = PaymentStatus::Paid->value;
        $payment->update();

        // $order = $payment->order;

        // $order->status = OrderStatus::Paid->value;
        // $order->update();

    }
    private function updateOrderPay(Payment $payment)
    {
        $payment->status = PaymentStatus::Paid->value;
        $payment->update();

        $order = $payment->order;

        $order->status = OrderStatus::Paid->value;
        $order->update();
        $adminUsers = User::where('is_admin', 1)->get();

        // foreach ([...$adminUsers, $order->user] as $user) {
        //     Mail::to($user)->send(new NewOrderEmail($order, (bool)$user->is_admin));
        // }
    }

    public function chkout_step3(Request $request){
        $user = $request->user();

        $R_chkouttype=$_POST["checkouttype"];
        $R_shipcost=$_POST["Shipcost"];
        $R_Insurance=$_POST["Insurance"];

        // dd($R_shipcost);

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems = [];
        $subtotalPrice = 0;
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $subtotalPrice += $product->retail_price * $quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'thb',
                    'product_data' => [
                        'name' => $product->item_code,
                       'images' => [$product->image]
                    ],
                    'unit_amount' => $product->retail_price * 100,
                    'price' => $product->retail_price
                ],
                'quantity' => $quantity,
                'itemtotal'=> $quantity * $product->retail_price
            ];
            $orderItems[] = [
                'item_code'=>$product->item_code,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->retail_price
            ];
        }

        /// base discount cal
        $basediscount=0;
        $x = $subtotalPrice;
        switch(true){
            case $x < 10000:
                $basediscount=0; 
                $dispercent = ' '; 
                break;
            case $x < 20000:
                $basediscount=0.1; 
                $dispercent = '10%'; 
                break;
            case $x < 30000:
                $basediscount=0.15; 
                $dispercent = '15%'; 

                break;
            case $x > 30000:
                $basediscount=0.2; 
                $dispercent = '20%'; 
                break;
        }

/// voucher discount 
        $apply_voucher=$request->apply_voucher;
        $voucher = Voucher::query()
            ->where(['code'=>$apply_voucher])
            ->first();
        if(!empty($voucher)){
            $vdis_percent=$voucher->discount_percent/100;

            if($basediscount > $vdis_percent){
                $dispercent = $dispercent;
            } else {
                $dispercent = ($voucher->discount_percent).'%';
            }

        } else {
            $vdis_percent=0;
        }

        $dis_percent= max($basediscount,$vdis_percent);

        $baseDis_amt = $dis_percent * $subtotalPrice;

//////
        $totalpayment = $subtotalPrice-$baseDis_amt+$R_shipcost+$R_Insurance;

        // Create Order
            $orderData = [
                    'total_price' => $subtotalPrice,
                    'discount_amount' => $baseDis_amt,
                    'status' => OrderStatus::Unpaid,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                    'shipping' => $R_shipcost,
                    'insurance'=>$R_Insurance,
                    
                ];

            if ($R_chkouttype == "paynow" ){
            // $orderData = ['status' => OrderStatus::Unpaid];
            $orderData['status'] = OrderStatus::Unpaid;
            } else {
                $orderData['status'] = OrderStatus::Quotation;
            }

            $order = Order::create($orderData);

        // Create Order Items
        foreach ($orderItems as $orderItem) {
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }

        // Update stock
        foreach ($orderItems as $orderItem) {
            Stock::where('item_code',$orderItem['item_code'])
            ->decrement('stock',(int) $orderItem['quantity']);
        }

        
        // Create Payment
        $paymentData = [
            'order_id' => $order->id,
            'amount' => $totalpayment,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            // 'session_id' => $session->id
        ];
        Payment::create($paymentData);

        CartItem::where(['user_id' => $user->id])->delete();
        

        return view('checkout.step3',[
                'items'=>$lineItems,
                'orderitems'=> $orderItems,
                'itemsprice'=> $subtotalPrice,
                'dispercent' => $dispercent,
                'discount_amount' => $baseDis_amt,
                'totalpayment'=> $totalpayment,
                // 'totalpaymentShow'=> number_format($totalpayment),
                'ordertype'=> $R_chkouttype,
                'shipcost'=> $R_shipcost,
                'insure'=> $R_Insurance,
                // 'paydata'=>$paymentData
            ]);
    }
    public function chkout_summary(Request $request){
        $user = $request->user();
        $customer = $user->customer;

        $R_chkouttype=$_POST["checkouttype"];

        $countries = Country::query()->orderBy('name')->get();
        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);

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
                    'price' => $product->retail_price
                ],
                'quantity' => $quantity,
                'itemtotal'=> $quantity * $product->retail_price
            ];
            $orderItems[] = [
                'item_code'=>$product->item_code,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->retail_price
            ];
        }

        // Create Order
            $orderData = [
                    'total_price' => $totalPrice,
                    'status' => OrderStatus::Unpaid,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ];

            if ($R_chkouttype == "paynow" ){
            // $orderData = ['status' => OrderStatus::Unpaid];
            $orderData['status'] = OrderStatus::Unpaid;
            } else {
                $orderData['status'] = OrderStatus::Quotation;
            }

            $order = Order::create($orderData);

        // Create Order Items
        foreach ($orderItems as $orderItem) {
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }

        // Update stock
        foreach ($orderItems as $orderItem) {
            Stock::where('item_code',$orderItem['item_code'])
            ->decrement('stock',(int) $orderItem['quantity']);
        }


        // Create Payment
        $paymentData = [
            'order_id' => $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            // 'session_id' => $session->id
        ];
        Payment::create($paymentData);

        CartItem::where(['user_id' => $user->id])->delete();
        

        return view('checkout.summary',[
                'items'=>$lineItems,
                'orderitems'=> $orderItems,
                'totalprice'=> $totalPrice,
                'totalpriceShow'=> number_format($totalPrice),
                'ordertype'=> $R_chkouttype,
                'paydata'=>$paymentData
            ],compact('customer', 'user', 'shippingAddress', 'billingAddress','countries'));
    }
 
    public function chkout_step2(Request $request){
        // step 1 : add billing and shipping
    
        $user = $request->user();
        $customer = $user->customer;

        // $R_chkouttype=$_POST["checkouttype"];

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);

        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);

        $countries = Country::query()->orderBy('name')->get();


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
                    'price' => $product->retail_price
                ],
                'quantity' => $quantity,
                'itemtotal'=> $quantity * $product->retail_price
            ];
            $orderItems[] = [
                'item_code'=>$product->item_code,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->retail_price
            ];
        }
        

        return view('checkout.step1',[
                'items'=>$lineItems,
                'orderitems'=> $orderItems,
                'totalprice'=> $totalPrice,
                'totalpriceShow'=> number_format($totalPrice),
                // 'ordertype'=> $R_chkouttype
            ],compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }

    public function chkout_summary_test(Request $request){
        // $user = $request->user();
        // $R_chkouttype=$_POST["checkouttype"];

        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;
        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);

        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);

        $countries = Country::query()->orderBy('name')->get();

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;
        $totalWeight = 0;

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->retail_price * $quantity;
            $totalWeight += $product->weight_g * $quantity;

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'thb',
                    'product_data' => [
                        'name' => $product->item_code,
                       'images' => [$product->image]
                    ],
                    'unit_amount' => $product->retail_price * 100,
                    'price' => $product->retail_price
                ],
                'quantity' => $quantity,
                'itemtotal'=> $quantity * $product->retail_price
            ];

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->retail_price
            ];
        }

        // Create Order
            // $orderData = [
            //         'total_price' => $totalPrice,
            //         'status' => OrderStatus::Unpaid,
            //         'created_by' => $user->id,
            //         'updated_by' => $user->id,
            //     ];

            // if ($R_chkouttype == "paynow" ){
            // // $orderData = ['status' => OrderStatus::Unpaid];
            // $orderData['status'] = OrderStatus::Unpaid;
            // } else {
            //     $orderData['status'] = OrderStatus::Quotation;
            // }

            // $order = Order::create($orderData);

        // Create Order Items
        // foreach ($orderItems as $orderItem) {
        //     $orderItem['order_id'] = $order->id;
        //     OrderItem::create($orderItem);
        // }

        // // Create Payment
        // $paymentData = [
        //     'order_id' => $order->id,
        //     'amount' => $totalPrice,
        //     'status' => PaymentStatus::Pending,
        //     'type' => 'cc',
        //     'created_by' => $user->id,
        //     'updated_by' => $user->id,
        //     // 'session_id' => $session->id
        // ];
        // Payment::create($paymentData);

        // CartItem::where(['user_id' => $user->id])->delete();
        

        return view('checkout.summary_t',[
                'items'=>$lineItems,
                'orderitems'=> $orderItems,
                'totalprice'=> $totalPrice,
                'totalpriceShow'=> number_format($totalPrice),
                'totalweight'=> $totalWeight
                // 'ordertype'=> $R_chkouttype,
                // 'paydata'=>$paymentData
            ],compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }

    public function test(){
        $payload = @file_get_contents('php://input');
        $body = json_decode($payload,true);

        echo $payload;

        var_dump ($body);

    }

    public function quotation(Request $request)
    {
               $user = $request->user();
       
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
                //    'session_id' => $session->id
               ];
               Payment::create($paymentData);
       
               CartItem::where(['user_id' => $user->id])->delete();
       
            //    return redirect();
               return redirect('/orders/'.$orderItem['order_id']);

       
    }

    public function chkout_order(Request $request){

        $OrderId = $request->order;
        // $R_shipcost=$_POST["Shipcost"];
        // $R_Insurance=$_POST["Insurance"];

        // dd($request->query->all());
        // dd($OrderId);

        // get order data
        $orders = Order::query()
            ->where(['id' => $OrderId])
            ->first();

        // dd($orders['total_price']);
        $totalPrice=$orders['total_price'];
        $R_shipcost=$orders['shipping'];
        $R_Insurance=$orders['insurance'];


        $totalpayment = $totalPrice+$R_shipcost+$R_Insurance;

        return view('checkout.step3',[
                'itemsprice'=> $totalPrice,
                'shipcost'=> $R_shipcost,
                'insure'=> $R_Insurance,
                'totalpayment'=> $totalpayment,
                'ordertype'=> 'paynow',
            ]);
    }


}
