<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class kCheckoutController extends Controller
{
    public function webhook(Request $request){
    
        // $varname = json_decode(file_get_contents('php://input'));

        // echo $varname;

        $obj=$request->post();

        // $obj=json_decode($_POST['transaction_state']);

        // try{
        //     $obj=json_decode($_POST['transaction_state']);

        //     echo $obj;

        // } catch ('Authorized') {

        // };

        echo $obj;
        
    return response('', 200);
    // return view('test.ppc_home');


    }
    //
}
