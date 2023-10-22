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
use App\Models\BillingAddress;
use App\Models\CartItem;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShipAir;
use App\Models\ShipEMS;
use App\Models\ShippingAddress;
use App\Models\ShiprateThai;
use App\Models\Stock;
use App\Models\Voucher;

use function PHPUnit\Framework\isEmpty;

class CheckoutSummaryController extends Controller
{
    private $b_discount;
    private $v_discount;
    public function test_Discount(Request $request){
        $this->baseDiscount($request->discount);

        // dd($request->discount);

        dd($this->b_discount['cal'],
        $this->b_discount['percent']
    );
    }

    public function voucher_discount($apply_voucher){
        $voucher = Voucher::query()
                ->where(['code'=>$apply_voucher])
                ->first();
        $dispercent =0;        

        if(!empty($voucher)){
            $vdis_percent = $voucher->discount_percent/100;

            if($this->b_discount['cal'] > $vdis_percent){
                $dispercent = $this->b_discount['percent'];
            } else {
                $dispercent = ($voucher->discount_percent).'%';
            }

        } else {
            $vdis_percent=0;
        }

        $v_discount=[
            'cal'=>$vdis_percent,
            'percent'=>$dispercent
        ];
    
    $dis_percent= max($this->b_discount['cal'],$vdis_percent);

        return $v_discount;
    }
    
    public function baseDiscount($itemstotal){
        $dispercent=0;
        $basediscount=0;
        $x = $itemstotal;

        switch(true){
            case $x < 10000:
                $basediscount=0; 
                $dispercent = ' '; 
                break;
            case $x < 30000:
                $basediscount=0.1; 
                $dispercent = '10%'; 
                break;
            case $x < 50000:
                $basediscount=0.15; 
                $dispercent = '15%'; 

                break;
            case $x < 70000:
                $basediscount=0.2; 
                $dispercent = '20%'; 
                break;
            case $x >= 70000:
                $basediscount=0.25; 
                $dispercent = '25%'; 
                break;
        }

        $this->b_discount=[
            'cal'=>$basediscount,
            'percent'=>$dispercent
        ];

        return $this->b_discount;

    }
    public function chkout_step1(Request $request){
        // step 1 : add billing and shipping
        $user = $request->user();
        $customer = $user->customer;

        // $R_chkouttype=$_POST["checkouttype"];

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        // $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        // $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);

        $shippingAddress = $customer->Ship_Address ?: new ShippingAddress;
        $billingAddress = $customer->Bill_Address ?: new BillingAddress;

        
        // dd($customer->Ship_address,$customer->billingAddress);
        // dd($shippingAddress, $billingAddress);

        $countries = Country::query()->orderBy('name')->get();

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
    $this->baseDiscount($subtotalPrice);
    $dispercent =0;

/// voucher discount 
    $apply_voucher=$request->apply_voucher;
    $voucher = Voucher::query()
                ->where(['code'=>$apply_voucher])
                ->first();
        if(!empty($voucher)){
            $vdis_percent=$voucher->discount_percent/100;

            if($this->b_discount['cal'] > $vdis_percent){
                $dispercent = $this->b_discount['percent'];
            } else {
                $dispercent = ($voucher->discount_percent).'%';
            }

        } else {
            $vdis_percent=0;
        }
    
    $dis_percent= max($this->b_discount['cal'],$vdis_percent);

///
    $baseDis_amt = $dis_percent * $subtotalPrice;
    // $baseDis_amt = $this->voucher_discount($request->apply_voucher) * $subtotalPrice;

/// total price        
        $totalPrice = $subtotalPrice-$baseDis_amt;

        return view('checkout.step1_test',[
                'items'=>$lineItems,
                'orderitems'=> $orderItems,
                'subtotal'=> number_format($subtotalPrice),
                'totalpriceShow'=> number_format($totalPrice),
                'dis_percent'=> $dispercent,
                'baseDis_amt'=> number_format($baseDis_amt),
                'totalprice'=> $totalPrice,
                // 'ordertype'=> $R_chkouttype
            ],compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries','apply_voucher'));
    }
    public function chkout_step2(Request $request){

        /** @var \App\Models\User $user */

        $user = $request->user();

        /** @var \App\Models\Customer $customer */

        $customer = $user->customer;
        // $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        // $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);

        $shippingAddress = $customer->Ship_Address;
        $billingAddress = $customer->Bill_Address;
        $shipcountry = $customer->Ship_Address->country_code;
        $domestic= $shipcountry==='THA'; 
        
        $countries = Country::query()->orderBy('name')->get();

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems = [];
        $subtotalPrice = 0;
        $totalCubic = 0;
        $totalWeight = 0;
        $shipCost = 0;

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $subtotalPrice += $product->retail_price * $quantity;
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

// base discount cal
        $this->baseDiscount($subtotalPrice);
        $dispercent =0;

/// voucher discount 
        $apply_voucher=$request->apply_voucher;
        $voucher = Voucher::query()
            ->where(['code'=>$apply_voucher])
            ->first();
            if(!empty($voucher)){
                $vdis_percent=$voucher->discount_percent/100;
    
                if($this->b_discount['cal'] > $vdis_percent){
                    $dispercent = $this->b_discount['percent'];
                } else {
                    $dispercent = ($voucher->discount_percent).'%';
                }
    
            } else {
                $vdis_percent=0;
            }

        $dis_percent= max($this->b_discount['cal'],$vdis_percent);

        $baseDis_amt = $dis_percent * $subtotalPrice;

/// total price        
        $totalPrice = $subtotalPrice-$baseDis_amt;
    
//////// total cubic test, comment out when production
    // $totalWeight = 2000;
    // $totalCubic = 540000;
    // $subtotalPrice = 300000;
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
        $nonFullBoxPriceIndex_th = ceil($LastCubicBoxWeight/5000);

        // dd($nonFullCubicBoxCubic,$nonFullBoxPriceIndex_th);

        if($nonFullCubicBoxCubic>0){
            $shipPricenonFullBox_th= ShiprateThai::query()->where(['id'=>$nonFullBoxPriceIndex_th])->value('price');
        } else {
            $shipPricenonFullBox_th =0;
        }

        $shipCost_TH = (($fullBox * $maxrate) + ($nonFullBox * $shipPricenonFullBox_th))*1.07;

        // dd($shipPricenonFullBox_th);

        // insurance cost = totalcost[after discount] + shippingcost * 10%[on top] * 2%
        // $TH_insurance= max(ceil((($totalPrice + $shipCost_TH)*1.1)*0.02),550);
        //     if($TH_insurance > 550){
        //         $TH_insurance = $TH_insurance*1.07;
        //     }; 
        
            

        $shipPricenonFullBox_ems=0;
        $shipPricenonFullBox_air=0;

        
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

        $EMS_insurance= max(ceil((($subtotalPrice + $shipCost_EMS)*1.1)*0.02),550);

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

        $Air_insurance= max(ceil((($subtotalPrice + $shipCost_Air)*1.1)*0.02),550);

        if($Air_insurance > 550){
            $Air_insurance = $Air_insurance*1.07;
        }; 

        // $shipCost_TH =0;
    }

    $total_TH = $subtotalPrice+$shipCost_TH+$TH_insurance;
    $total_EMS = $subtotalPrice+$shipCost_EMS+$EMS_insurance;
    $total_Air = $subtotalPrice+$shipCost_Air+$Air_insurance;

    // var_dump(
    //     'Total Cubic (cm): '.number_format($totalCubic),
    //     'Total Weight (g): '.number_format($totalWeight),
    //     'Total_product_price: '.number_format($totalPrice),
    //     '',
    //     'Total Cubic Box (box) : '.$totalCubicBox,
    //     'Full(XL) Cubic Box (box) : '.$fullCubicBox,
    //     'Non Full(XL) Cubic Box (box) : '.$nonFullCubicBox,
    //     '',
    //     'nonFullCubicBox Cubic (cm) : '.$nonFullCubicBoxCubic,
    //     'LastCubicBox Weight (g): '.$LastCubicBoxWeight,
    //     '',
    //     'LastCubicBox Size : '.$CubicboxSize,
    //     '',
    //     'shipcountry:'.$shipcountry,
    //     'ShippingZone EMS : '.$shippingZone_ems,
    //     'ShippingZone Air : '.$shippingZone_air,
    //     '',
    //     '',
    //     'Criteria : Shipping -> +7% vat',
    //     'Criteria : Insurance -> +7% vat if more than 550 thb',
    //     '',
    //     'Total_product_price: '.number_format($totalPrice),
    //     'ship_th :'.number_format($shipCost_TH),
    //     'TH_insurance : '.number_format($TH_insurance),
    //     'Total_TH: '.number_format($total_TH),
    //     '',
    //     'Total_product_price: '.number_format($totalPrice),
    //     'ship_ems :'.number_format($shipCost_EMS),
    //     'EMS_insurance: '.number_format($EMS_insurance),
    //     'Total_EMS: '.number_format($total_EMS),
    //     '',
    //     'Total_product_price: '.number_format($totalPrice),
    //     'ship_air :'.number_format($shipCost_Air),
    //     'Air_insurance: '.number_format($Air_insurance),
    //     'Total_Air: '.number_format($total_Air),
    // );

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
                'subtotal'=> $subtotalPrice,
                'dis_percent'=> $dispercent,
                'baseDis_amt'=> number_format($baseDis_amt),
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
            ],compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries','apply_voucher'));
    }    

    public function chkout_step3(Request $request){
        $user = $request->user();

        $shipcostArray=explode('|',$_POST["Shipcost"]);

        $R_chkouttype=$_POST["checkouttype"];
        $R_shipcost=$shipcostArray[0];
        $R_Insurance=$_POST["Insurance"];
        $R_ShipMethod=$shipcostArray[1];

        // dd($R_ShipMethod);

        // dd($user->id);

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
        $this->baseDiscount($subtotalPrice);
        $dispercent =0;

/// voucher discount 
        $apply_voucher=$request->apply_voucher;
        $voucher = Voucher::query()
                ->where(['code'=>$apply_voucher])
                ->first();
        if(!empty($voucher)){
            $vdis_percent=$voucher->discount_percent/100;

            if($this->b_discount['cal'] > $vdis_percent){
                $dispercent = $this->b_discount['percent'];
            } else {
                $dispercent = ($voucher->discount_percent).'%';
            }

        } else {
            $vdis_percent=0;
        }

        $dis_percent= max($this->b_discount['cal'],$vdis_percent);
        $baseDis_amt = $dis_percent * $subtotalPrice;
//////
        $totalpayment = $subtotalPrice-$baseDis_amt+$R_shipcost+$R_Insurance;

        // Create Order
            $orderData = [
                    'total_price' => $subtotalPrice,
                    'discount_base' => $baseDis_amt,
                    'status' => OrderStatus::Unpaid,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                    'shipping' => $R_shipcost,
                    'insurance'=>$R_Insurance,
                    'ship_method'=>$R_ShipMethod,
                    'bill_id'=>$user->id,
                    'ship_id'=>$user->id,
                    
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
                'discount_base' => $baseDis_amt,
                'totalpayment'=> $totalpayment,
                // 'totalpaymentShow'=> number_format($totalpayment),
                'ordertype'=> $R_chkouttype,
                'shipcost'=> $R_shipcost,
                'insure'=> $R_Insurance,
                // 'paydata'=>$paymentData
            ]);
    }


    public function createSC(){
        ///////// no Use

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
