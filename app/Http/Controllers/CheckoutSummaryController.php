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
            $totalCubic += $product->cubic_m * $quantity;
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
    
    // $totalWeight = 2000;
    // $totalCubic = 97335*2+3000;

    $xlCubicBox=97336;
    $LastCubicBoxWeight=0;

    $shipCost_TH=0;
    $shipCost_EMS =0;
    $shipCost_Air = 0;

    $shippingZone_ems=0; 
    $shippingZone_air= 0;

// Total cubic box calculation
    $totalCubicBox = ceil($totalCubic/$xlCubicBox);
    $fullCubicBox=floor($totalCubic/$xlCubicBox);         // number of full box needed 

    // dd(floor(1));
    // dd($fullCubicBox);

    // $nonFullCubicBoxWeight = $totalCubic % 97336;  // non-full box weight
    $nonFullCubicBoxWeight = $totalCubic-($fullCubicBox*$xlCubicBox);  // non-full box weight

    $CubicboxSize ='none';

// LastCubicbox calculation weight in gram
if($nonFullCubicBoxWeight<>0){    
    switch($nonFullCubicBoxWeight){
        case $nonFullCubicBoxWeight < 11907:
            $LastCubicBoxWeight= 2500;
            $CubicboxSize='S';
            break;
            case $nonFullCubicBoxWeight < 46656:
                $LastCubicBoxWeight= 9500;
                $CubicboxSize='M';
                break;
                case $nonFullCubicBoxWeight < 73644:
                    $LastCubicBoxWeight= 15000;
                    $CubicboxSize='L';
                    break;
                    case $nonFullCubicBoxWeight <= $xlCubicBox:
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
        $nonFullBoxPriceIndex_th = ceil($nonFullCubicBoxWeight/5000);

        if($nonFullCubicBoxWeight>0){
            $shipPricenonFullBox_th= ShiprateThai::query()->where(['id'=>$nonFullBoxPriceIndex_th])->value('price');
        } else {
            $shipPricenonFullBox_th =0;
        }

        $shipCost_TH = ($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_th);

        
    } else {
        // Ship by EMS
        $shippingZone_ems= Country::query()->where(['code'=>$shipcountry])->value('zone_ems');
        $maxrate = ShipEMS::query()->where(['id'=>ShipEMS::max('id')])->value($shippingZone_ems);

        $nonFullBoxPriceIndex_ems = ceil(($LastBoxWeight/500)+1);
        
        if($LastBoxWeight > 0){
            $shipPricenonFullBox_ems= ShipEMS::query()->where(['id'=>$nonFullBoxPriceIndex_ems])->value($shippingZone_ems);
        } else {
            $shipPricenonFullBox_ems =0;
        }

        $shipCost_EMS = ($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_ems);

///////////////////////////////////////////////////////////

        // Ship by Air
        $shippingZone_air= Country::query()->where(['code'=>$shipcountry])->value('zone_air');
        $maxrate = ShipAir::query()->where(['id'=>ShipAir::max('id')])->value($shippingZone_air);

        $nonFullBoxPriceIndex_air = ceil(($LastBoxWeight/1000));

        if($LastBoxWeight > 0){
            $shipPricenonFullBox_air= ShipAir::query()->where(['id'=>$nonFullBoxPriceIndex_air])->value($shippingZone_air);
        } else {
            $shipPricenonFullBox_air =0;
        }

        $shipCost_Air = ($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_air);

        $shipCost_TH =0;
    }

    // dd(
    //     'Total Cubic : '.$totalCubic,
    //     'Total Cubic Box : '.$totalCubicBox,
    //     'Full(XL) Cubic Box : '.$fullCubicBox,
    //     'nonFullCubicBox Weight : '.$nonFullCubicBoxWeight,
    //     'LastCubicBox Weight : '.$LastCubicBoxWeight,
    //     'LastCubicBox Size : '.$CubicboxSize,
    //     'Max Rate : '.$maxrate,
    //     'Total Weight : '.$totalWeight,
    //     'ShippingBox : '.$shippingBoxes,
    //     'FullBox : '.$fullBox,
    //     'nonFullBox : '.$nonFullBox,
    //     'nonFullBox Weight : '.$LastBoxWeight,
    //     // 'nonFullBox PriceIndex : '.$nonFullBoxPriceIndex,
    //     'nonFullBox Cost : '.$shipPricenonFullBox_ems,
    //     'Total ShipCost : '.$shipCost_EMS,
    //     'ShippingZone EMS : '.$shippingZone_ems,
    //     'shipcountry:'.$shipcountry,
    //     $nonFullBox);

    // dd(
    //     'ShippingZone EMS : '.$shippingZone_ems,
    //     'Max Rate : '.$maxrate,
    //     'Total Weight (g): '.$totalWeight,
    //     'ShippingBox : '.$shippingBoxes,
    //     'FullBox : '.$fullBox,
    //     'nonFullBox : '.$nonFullBox,
    //     'nonFullBox Weight (g) : '.$nonFullBoxWeight,
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
                'domescheck'=>$domestic
            ],compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }    
    public function chkout_step3(Request $request){

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
        $totalWeight = 0;
        $shipCost = 0;

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->retail_price * $quantity;
            $totalWeight += $product->weight_g * $quantity;
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
    
    $shipCost_TH=0;
    $shipCost_EMS =0;
    $shipCost_Air = 0;

    $shippingZone_ems=0; 
    $shippingZone_air= 0;

// universal cal : same for Domestic and Inter 
    $shippingBoxes = ceil($totalWeight/20000); // number of box needed 
    $fullBox=floor($totalWeight/20000);         // number of full box needed 
    $nonFullBoxWeight = $totalWeight % 20000;  // non-full box weight
    $nonFullBox=(int)($nonFullBoxWeight>0);

    if ($domestic){
        $maxrate = ShiprateThai::query()->where(['id'=>ShiprateThai::max('id')])->value('price');
        $nonFullBoxPriceIndex_th = ceil($nonFullBoxWeight/5000);

        if($nonFullBoxWeight>0){
            $shipPricenonFullBox_th= ShiprateThai::query()->where(['id'=>$nonFullBoxPriceIndex_th])->value('price');
        } else {
            $shipPricenonFullBox_th =0;
        }

        $shipCost_TH = ($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_th);

        
    } else {
        // Ship by EMS
        $shippingZone_ems= Country::query()->where(['code'=>$shipcountry])->value('zone_ems');
        $maxrate = ShipEMS::query()->where(['id'=>ShipEMS::max('id')])->value($shippingZone_ems);

        $nonFullBoxPriceIndex_ems = ceil(($nonFullBoxWeight/500)+1);
        
        if($nonFullBoxWeight > 0){
            $shipPricenonFullBox_ems= ShipEMS::query()->where(['id'=>$nonFullBoxPriceIndex_ems])->value($shippingZone_ems);
        } else {
            $shipPricenonFullBox_ems =0;
        }

        $shipCost_EMS = ($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_ems);

///////////////////////////////////////////////////////////

        // Ship by Air
        $shippingZone_air= Country::query()->where(['code'=>$shipcountry])->value('zone_air');
        $maxrate = ShipAir::query()->where(['id'=>ShipAir::max('id')])->value($shippingZone_air);

        $nonFullBoxPriceIndex_air = ceil(($nonFullBoxWeight/1000));

        if($nonFullBoxWeight > 0){
            $shipPricenonFullBox_air= ShipAir::query()->where(['id'=>$nonFullBoxPriceIndex_air])->value($shippingZone_air);
        } else {
            $shipPricenonFullBox_air =0;
        }

        $shipCost_Air = ($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_air);

        $shipCost_TH =0;
    }

    // dd(
    //     'Max Rate : '.$maxrate,
    //     'Total Weight : '.$totalWeight,
    //     'ShippingBox : '.$shippingBoxes,
    //     'FullBox : '.$fullBox,
    //     'nonFullBox : '.$nonFullBox,
    //     'nonFullBox Weight : '.$nonFullBoxWeight,
    //     'nonFullBox PriceIndex : '.$nonFullBoxPriceIndex,
    //     'nonFullBox Cost : '.$shipPricenonFullBox_th,
    //     'Total ShipCost : '.$shipCost_TH,
    //     $nonFullBox);

    // dd(
    //     'ShippingZone EMS : '.$shippingZone_ems,
    //     'Max Rate : '.$maxrate,
    //     'Total Weight (g): '.$totalWeight,
    //     'ShippingBox : '.$shippingBoxes,
    //     'FullBox : '.$fullBox,
    //     'nonFullBox : '.$nonFullBox,
    //     'nonFullBox Weight (g) : '.$nonFullBoxWeight,
    //     'nonFullBox PriceIndex : '.$nonFullBoxPriceIndex,
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
                'domescheck'=>$domestic
            ],compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }    

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
