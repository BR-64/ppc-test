<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class kCheckoutController extends Controller
{
    public function paymentresult(Request $request){
        $obj=json_decode($_POST['status']);
        response()->json(['success' => 'success'], 200);

        // $varname=json_decode($request->getContent());

        // $varname = json_decode(file_get_contents('php://input'));

        // echo $varname;
    
        if($obj=='success'){
            // return view('checkout.success');
            return view('checkout.failure');
        } 
        else{
            // return view('checkout.failure');
            return view('checkout.success');
        }

    }
     

    public function webhook(Request $request){
    
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
             
        }
    

    // return redirect('/');

    }
    //
}
