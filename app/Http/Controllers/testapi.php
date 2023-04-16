<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testapi extends Controller
{
    public function getJSON(Request $request)
{

    $url = 'https://dev-kpaymentgateway.kasikornbank.com';
    // $url= 'https://dev-kpaymentgateway-services.kasikornbank.com';

    $response = file_get_contents($url);
    $newsData = json_decode($response);

    // dd($response);


    return response()->json($newsData);         

}
}
