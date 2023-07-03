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
use App\Models\ShipAir;
use App\Models\ShipEMS;
use App\Models\ShiprateThai;
use App\Models\Stock;

class CheckoutSummaryController extends Controller
{
    public function chkout_step1(Request $request){
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
        

        return view('checkout.step1_test',[
                'items'=>$lineItems,
                'orderitems'=> $orderItems,
                'totalprice'=> $totalPrice,
                'totalpriceShow'=> number_format($totalPrice),
                // 'ordertype'=> $R_chkouttype
            ],compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }
    public function chkout_step2(Request $request){

        /** @var \App\Models\User $user */

        $user = $request->user();

        /** @var \App\Models\Customer $customer */

        $customer = $user->customer;
        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);

        $shipcountry = $customer->shippingAddress->country_code;
        $domestic= $shipcountry==='THA';       

        $countries = Country::query()->orderBy('name')->get();

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;
        $totalCubic = 0;
        $totalWeight = 0;
        $shipCost = 0;

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->retail_price * $quantity;
            $totalWeight += $product->weight_g * $quantity;
            $totalCubic += $product->cubic_cm * $quantity;
            // $totalw = $totalWeight += $product->weight_g * $quantity;

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
    
//////// total cubic test, comment out when production
    // $totalWeight = 2000;
    // $totalCubic = 540000;
    // $totalPrice = 300000;
    // // $domestic= true;       
    // $domestic= false;    
    // $ems_zone= 1;
    // $air_zone=2;   

///////////////////////

    $xlCubicBox=97336;
    $LastCubicBoxWeight=0;

    $shipCost_TH=0;
    $shipCost_EMS =0;
    $shipCost_Air = 0;

    $shippingZone_ems=0; 
    $shippingZone_air= 0;

    $TH_insurance = 0;
    $EMS_insurance = 0;
    $Air_insurance = 0;

// Total cubic box calculation
    $totalCubicBox = ceil($totalCubic/$xlCubicBox);
    $fullCubicBox=floor($totalCubic/$xlCubicBox);         // number of full box needed 

    // $nonFullCubicBoxCubic = $totalCubic % 97336;  // non-full box weight
    $nonFullCubicBoxCubic = $totalCubic-($fullCubicBox*$xlCubicBox);  // non-full box weight

    $nonFullCubicBox = $totalCubicBox-$fullCubicBox;

    $CubicboxSize ='none';

// LastCubicbox calculation weight in gram
if($nonFullCubicBoxCubic<>0){    
    switch($nonFullCubicBoxCubic){
        case $nonFullCubicBoxCubic < 11907:
            $LastCubicBoxWeight= 2500;
            $CubicboxSize='S';
            break;
            case $nonFullCubicBoxCubic < 46656:
                $LastCubicBoxWeight= 9500;
                $CubicboxSize='M';
                break;
                case $nonFullCubicBoxCubic < 73644:
                    $LastCubicBoxWeight= 15000;
                    $CubicboxSize='L';
                    break;
                    case $nonFullCubicBoxCubic <= $xlCubicBox:
                        $LastCubicBoxWeight= 20000;
                        $CubicboxSize='XL';
                        break;       
                    }
    }


// universal cal : same for Domestic and Inter 
    // $shippingBoxes = ceil($totalWeight/20000); // number of box needed 
    // $fullBox=floor($totalWeight/20000);         // number of full box needed 
    // $nonFullBoxWeight = $totalWeight % 20000;  // non-full box weight
    // $LastBoxWeight = $LastCubicBoxWeight % 20000;  // non-full box weight
    
    $shippingBoxes = $totalCubicBox; // number of box needed 
    $fullBox=$fullCubicBox;         // number of full box needed 
    $LastBoxWeight = $LastCubicBoxWeight;  // Last box weight
    $nonFullBox=(int)($LastBoxWeight>0);

    if ($domestic){
        $maxrate = ShiprateThai::query()->where(['id'=>ShiprateThai::max('id')])->value('price');
        $nonFullBoxPriceIndex_th = ceil($nonFullCubicBoxCubic/5000);

        if($nonFullCubicBoxCubic>0){
            $shipPricenonFullBox_th= ShiprateThai::query()->where(['id'=>$nonFullBoxPriceIndex_th])->value('price');
        } else {
            $shipPricenonFullBox_th =0;
        }

        $shipCost_TH = (($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_th))*1.07;

        // insurance cost = totalcost[after discount] + shippingcost * 10%[on top] * 2%
        $TH_insurance= max(ceil((($totalPrice + $shipCost_TH)*1.1)*0.02),550);
            if($TH_insurance > 550){
                $TH_insurance = $TH_insurance*1.07;
            }; 

        $shipPricenonFullBox_ems=0;
        $shipPricenonFullBox_air=0;

        // dd(
        //     $totalPrice,
        //     $shipCost_TH
        // );

        
    } else {
        // Ship by EMS
        $shippingZone_ems= Country::query()->where(['code'=>$shipcountry])->value('zone_ems');

 ////////////////////  for test only, comment out when in production     
// $shippingZone_ems=$ems_zone;
/////////////
        $maxrate = ShipEMS::query()->where(['id'=>ShipEMS::max('id')])->value($shippingZone_ems);

        $nonFullBoxPriceIndex_ems = ceil(($LastBoxWeight/500)+1);
        
        if($LastBoxWeight > 0){
            $shipPricenonFullBox_ems= ShipEMS::query()->where(['id'=>$nonFullBoxPriceIndex_ems])->value($shippingZone_ems);
        } else {
            $shipPricenonFullBox_ems =0;
        }

        $shipCost_EMS = (($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_ems))*1.07;

        $EMS_insurance= max(ceil((($totalPrice + $shipCost_EMS)*1.1)*0.02),550);

        if($EMS_insurance > 550){
            $EMS_insurance = $EMS_insurance*1.07;
        }; 


        // Ship by Air
        $shippingZone_air= Country::query()->where(['code'=>$shipcountry])->value('zone_air');
 ////////////////////  for test only, comment out when in production     
//  $shippingZone_air=$air_zone;

////////////////////
        $maxrate = ShipAir::query()->where(['id'=>ShipAir::max('id')])->value($shippingZone_air);

        $nonFullBoxPriceIndex_air = ceil(($LastBoxWeight/1000));

        if($LastBoxWeight > 0){
            $shipPricenonFullBox_air= ShipAir::query()->where(['id'=>$nonFullBoxPriceIndex_air])->value($shippingZone_air);
        } else {
            $shipPricenonFullBox_air =0;
        }

        $shipCost_Air = (($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_air))*1.07;

        $Air_insurance= max(ceil((($totalPrice + $shipCost_Air)*1.1)*0.02),550);

        if($Air_insurance > 550){
            $Air_insurance = $Air_insurance*1.07;
        }; 

        $shipCost_TH =0;
    }

    $total_TH = $totalPrice+$shipCost_TH+$TH_insurance;
    $total_EMS = $totalPrice+$shipCost_EMS+$EMS_insurance;
    $total_Air = $totalPrice+$shipCost_Air+$Air_insurance;

    var_dump(
        'Total Cubic (cm): '.number_format($totalCubic),
        'Total Weight (g): '.number_format($totalWeight),
        'Total_product_price: '.number_format($totalPrice),
        '',
        'Total Cubic Box (box) : '.$totalCubicBox,
        'Full(XL) Cubic Box (box) : '.$fullCubicBox,
        'Non Full(XL) Cubic Box (box) : '.$nonFullCubicBox,
        '',
        'nonFullCubicBox Cubic (cm) : '.$nonFullCubicBoxCubic,
        'LastCubicBox Weight (g): '.$LastCubicBoxWeight,
        '',
        'LastCubicBox Size : '.$CubicboxSize,
        // 'Max Rate : '.$maxrate,
        
        // 'ShippingBox : '.$shippingBoxes,
        // 'FullBox : '.$fullBox,
        // 'nonFullBox : '.$nonFullBox,
        // 'nonFullBox Weight : '.$LastBoxWeight,
        // 'nonFullBox PriceIndex : '.$nonFullBoxPriceIndex,
        // 'nonFullBox Cost EMS: '.$shipPricenonFullBox_ems,
        // 'nonFullBox Cost Air: '.$shipPricenonFullBox_air,
        // 'Total ShipCost EMS : '.$shipCost_EMS,
        // 'Total ShipCost Air : '.$shipCost_Air,
        '',
        'shipcountry:'.$shipcountry,
        'ShippingZone EMS : '.$shippingZone_ems,
        'ShippingZone Air : '.$shippingZone_air,
        '',
        '',
        'Criteria : Shipping -> +7% vat',
        'Criteria : Insurance -> +7% vat if more than 550 thb',
        '',
        'Total_product_price: '.number_format($totalPrice),
        'ship_th :'.number_format($shipCost_TH),
        'TH_insurance : '.number_format($TH_insurance),
        'Total_TH: '.number_format($total_TH),
        '',
        'Total_product_price: '.number_format($totalPrice),
        'ship_ems :'.number_format($shipCost_EMS),
        'EMS_insurance: '.number_format($EMS_insurance),
        'Total_EMS: '.number_format($total_EMS),
        '',
        'Total_product_price: '.number_format($totalPrice),
        'ship_air :'.number_format($shipCost_Air),
        'Air_insurance: '.number_format($Air_insurance),
        'Total_Air: '.number_format($total_Air),
    );

    //     $nonFullBox);
  
    // var_dump(
    //     '',
    //     '',
    //     'ShippingZone EMS : '.$shippingZone_ems,
    //     'Max Rate : '.$maxrate,
    //     'Total Weight (g): '.$totalWeight,
    //     'ShippingBox : '.$shippingBoxes,
    //     'FullBox : '.$fullBox,
    //     'nonFullBox : '.$nonFullBox,
    //     // 'nonFullBox Weight (g) : '.$nonFullBoxWeight,
    //     // 'nonFullBox PriceIndex : '.$nonFullBoxPriceIndex,
    //     'Total Shipcost EMS : '.$shipCost_EMS
    // );

    // dd(
    //     'ShippingZone EMS : '.$shippingZone_ems,
    //     'ShippingZone Air : '.$shippingZone_air,
    //     'Max Rate : '.$maxrate,
    //     'Total Weight (g): '.$totalWeight,
    //     'ShippingBox : '.$shippingBoxes,
    //     'FullBox : '.$fullBox,
    //     'nonFullBox : '.$nonFullBox,
    //     'nonFullBox Weight (g) : '.$nonFullBoxWeight,
    //     'nonFullBox PriceIndex : '.$nonFullBoxPriceIndex_air,
    //     'nonFullBox Air : '.$shipPricenonFullBox_air,
    //     'Total Shipcost EMS : '.$shipCost_EMS,
    //     'Total Shipcost Air : '.$shipCost_Air
    // );


            return view('checkout.step2',[
                'items'=>$lineItems,
                'orderitems'=> $orderItems,
                'totalprice'=> $totalPrice,
                'totalpriceShow'=> number_format($totalPrice),
                'totalweight'=> $totalWeight,
                'shipcountry'=>$shipcountry,
                'ship_th'=>$shipCost_TH,
                'ship_ems'=>$shipCost_EMS,
                'ship_air'=>$shipCost_Air,
                'domescheck'=>$domestic,
                'TH_insurance'=>$TH_insurance,
                'EMS_insurance'=>$EMS_insurance,
                'Air_insurance'=>$Air_insurance
            ],compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }    
//     public function chkout_step3(Request $request){

//         $user = $request->user();

//         $customer = $user->customer;
//         $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
//         $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);

//         $shipcountry = $customer->shippingAddress->country_code;
//         $domestic= $shipcountry==='THA';       

//         $countries = Country::query()->orderBy('name')->get();

//         [$products, $cartItems] = Cart::getProductsAndCartItems();

//         $orderItems = [];
//         $lineItems = [];
//         $totalPrice = 0;
//         $totalWeight = 0;
//         $shipCost = 0;

//         foreach ($products as $product) {
//             $quantity = $cartItems[$product->id]['quantity'];
//             $totalPrice += $product->retail_price * $quantity;
//             $totalWeight += $product->weight_g * $quantity;
//             // $totalw = $totalWeight += $product->weight_g * $quantity;

//             $lineItems[] = [
//                 'price_data' => [
//                     'currency' => 'thb',
//                     'product_data' => [
//                         'name' => $product->item_code,
//                        'images' => [$product->image]
//                     ],
//                     'unit_amount' => $product->retail_price * 100,
//                     'price' => $product->retail_price
//                 ],
//                 'quantity' => $quantity,
//                 'itemtotal'=> $quantity * $product->retail_price
//             ];

//             $orderItems[] = [
//                 'product_id' => $product->id,
//                 'quantity' => $quantity,
//                 'unit_price' => $product->retail_price
//             ];
//         }
    
//     $shipCost_TH=0;
//     $shipCost_EMS =0;
//     $shipCost_Air = 0;

//     $shippingZone_ems=0; 
//     $shippingZone_air= 0;

// // universal cal : same for Domestic and Inter 
//     $shippingBoxes = ceil($totalWeight/20000); // number of box needed 
//     $fullBox=floor($totalWeight/20000);         // number of full box needed 
//     $nonFullBoxWeight = $totalWeight % 20000;  // non-full box weight
//     $nonFullBox=(int)($nonFullBoxWeight>0);

//     if ($domestic){
//         $maxrate = ShiprateThai::query()->where(['id'=>ShiprateThai::max('id')])->value('price');
//         $nonFullBoxPriceIndex_th = ceil($nonFullBoxWeight/5000);

//         if($nonFullBoxWeight>0){
//             $shipPricenonFullBox_th= ShiprateThai::query()->where(['id'=>$nonFullBoxPriceIndex_th])->value('price');
//         } else {
//             $shipPricenonFullBox_th =0;
//         }

//         $shipCost_TH = ($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_th);

        
//     } else {
//         // Ship by EMS
//         $shippingZone_ems= Country::query()->where(['code'=>$shipcountry])->value('zone_ems');
//         $maxrate = ShipEMS::query()->where(['id'=>ShipEMS::max('id')])->value($shippingZone_ems);

//         $nonFullBoxPriceIndex_ems = ceil(($nonFullBoxWeight/500)+1);
        
//         if($nonFullBoxWeight > 0){
//             $shipPricenonFullBox_ems= ShipEMS::query()->where(['id'=>$nonFullBoxPriceIndex_ems])->value($shippingZone_ems);
//         } else {
//             $shipPricenonFullBox_ems =0;
//         }

//         $shipCost_EMS = ($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_ems);

// ///////////////////////////////////////////////////////////

//         // Ship by Air
//         $shippingZone_air= Country::query()->where(['code'=>$shipcountry])->value('zone_air');
//         $maxrate = ShipAir::query()->where(['id'=>ShipAir::max('id')])->value($shippingZone_air);

//         $nonFullBoxPriceIndex_air = ceil(($nonFullBoxWeight/1000));

//         if($nonFullBoxWeight > 0){
//             $shipPricenonFullBox_air= ShipAir::query()->where(['id'=>$nonFullBoxPriceIndex_air])->value($shippingZone_air);
//         } else {
//             $shipPricenonFullBox_air =0;
//         }

//         $shipCost_Air = ($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_air);

//         $shipCost_TH =0;
//     }

//             return view('checkout.step2',[
//                 'items'=>$lineItems,
//                 'orderitems'=> $orderItems,
//                 'totalprice'=> $totalPrice,
//                 'totalpriceShow'=> number_format($totalPrice),
//                 'totalweight'=> $totalWeight,
//                 'shipcountry'=>$shipcountry,
//                 'ship_th'=>$shipCost_TH,
//                 'ship_ems'=>$shipCost_EMS,
//                 'ship_air'=>$shipCost_Air,
//                 'domescheck'=>$domestic
//             ],compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
//     }    

    public function createSC(){

        $ch=curl_init();
        $url="http://1.1.220.113:7000/PrempApi.asmx/createSC";
        $post=[
            'sa_header' => '[{"doc_date":"20231210","vat_rate":"7","discount_amt":"3.1","ref1":"WBtest002"}]',
            'sa_detail' => '[{"item_code":"R34OBM897V89","unit_code":"PCS","qty":"11","unit_price":"123","discount_amt":"2.7"}
            ,{"item_code":"R34OBM859V89","unit_code":"PCS","qty":"22","unit_price":"189","discount_amt":"2.2"}]'
        ];

        $post2=[
            'sa_header'=>[
                'doc_date'=>'20231210',
                'vat_rate'=>7,
                'discount_amt'=>3.1,
                'ref1'=> 'WBtest002'
            ],
            'sa_detail'=>[

            ]
        ];

        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);

        $response=curl_exec($ch);

        var_dump($response);

        dd(
            'test'
        );

        return view('checkout.step2');
    }
}
