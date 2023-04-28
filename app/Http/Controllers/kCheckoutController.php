<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\models\Payment;

class kCheckoutController extends Controller
{
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
    // $R_amount=$_POST["amount"];
    $R_paymentmethod=$_POST["paymentMethods"];
    // $R_product=$_POST["product"];

    $publickey = "pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3fC";
    $secretkey = "skey_test_216332Jyp8b6aUYfYJKgBqEJpdtMDWlcgCg3M";

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
    
         if ($R_paymentmethod == "card" )
         {
             $R_TOKEN=$_POST["token"];
             $reforder = rand();
             
         $data_array =  array(
            //   "amount"=> $R_amount,
              "amount"=> 5000,
                  "currency" => "THB",
              "description" => "test product",
                  "source_type" => "card",
                  "mode" => "token",
                  "token" => $R_TOKEN,
                  "reference_order" => $reforder,
                "additional_data" => [
                  "mid"=> "451320492949001"
           ]
         
         );
            //call charge API with Token
            $make_call = callAPI('POST','https://dev-kpaymentgateway-services.kasikornbank.com/card/v2/charge',json_encode($data_array));
             
             echo ($make_call);
             $response = json_decode($make_call, true);
    
             $rediurl=$response["redirect_url"];
             return redirect($rediurl);
             
        } else if ($R_paymentmethod == "qr" ){

            // $R_OrderID=$_POST["id"];
            $reforder = rand();

            $data_array =  array(
            "amount"=> 22200,
            "currency"=> "THB",
            "description"=> "TESTPRODUCT",
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

        } else if ($R_paymentmethod == "alipay" ){

            $reforder = rand();

            $data_array =  array(
            "amount"=> 1777,
            "currency"=> "THB",
            "description"=> "TESTPRODUCT alipay",
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
    

    // return redirect('/');

    }
    //

    public function webhook()
    {
        $payload = @file_get_contents('php://input');
        $body = json_decode($payload,true);

        echo $payload;

        var_dump ($body);

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

        // Handle the event
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

        switch($body['object']){
            case 'qr':
                echo ($body['id']);
                echo (' QR payment amount is ' .$body['amount']);

                echo ("\n".'  source ID '.$body['source']['id']);

                // $payment = Payment::create([
                //     'amount'=>$payload['amount']
                // ]);

                // $this->createOrderPay($payment);
                break;

            case 'charge':
                echo ($body['id']);
                echo  (' Credit Card payment amount is'. $body['amount']);

                echo (' source ID'.$body['source']);

                break;
            
                default:
                echo 'Received unknown event type ';


        }

        return response('', 200);
    }

    private function createOrderPay(Payment $payment)
    {
        $payment->status = PaymentStatus::Paid->value;
        $payment->update();

        $order = $payment->order;

        $order->status = OrderStatus::Paid->value;
        $order->update();

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

}
