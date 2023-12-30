<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Enums\OrderStatus;
use App\Mail\NewOrderEmail;
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
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

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
            'percent'=>$dispercent,
            'percent_value'=>$basediscount*100
        ];

        return $this->b_discount;

    }
    public function chkout_step1(Request $request){
        // step 1 : add billing and shipping
        $user = $request->user();
        $customer = $user->customer;

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $shippingAddress = $customer->Ship_Address ?: new ShippingAddress;
        $billingAddress = $customer->Bill_Address ?: new BillingAddress;

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
    $vvalid=$request->vvalid;

    // dd($vvalid);

    $voucher = Voucher::query()
                ->where(['code'=>$apply_voucher])
                ->first();

        if($vvalid){
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

/// total price        
        $totalPrice = $subtotalPrice-$baseDis_amt;

        return view('checkout.step1',[
                'items'=>$lineItems,
                'orderitems'=> $orderItems,
                'subtotal'=> number_format($subtotalPrice),
                'totalpriceShow'=> number_format($totalPrice),
                'dis_percent'=> $dispercent,
                'baseDis_amt'=> number_format($baseDis_amt),
                'totalprice'=> $totalPrice,
                // 'ordertype'=> $R_chkouttype
            ],compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries','apply_voucher','vvalid'));
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

        // dd($shipcountry);

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

//// voucher dis new
        $apply_voucher=$request->apply_voucher;
        $vvalid=$request->vvalid;

        $voucher = Voucher::query()
                ->where(['code'=>$apply_voucher])
                ->first();

        if($vvalid){
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
    
    $shippingBoxes = $totalCubicBox; // number of box needed 
    $fullBox=$fullCubicBox;         // number of full box needed (always biggest box)
    $LastBoxWeight = $LastCubicBoxWeight;  // Last box weight
    $nonFullBox=(int)($LastBoxWeight>0);

    // shipping boxes data
    $Sbox=0;
    $Mbox=0;
    $Lbox=0;
    $Xlbox=0;

    switch($CubicboxSize){
        case $CubicboxSize = 'S':
            $Sbox=1;
            break;
        case $CubicboxSize = 'M':
            $Mbox=1;
            break;
        case $CubicboxSize = 'L':
            $Lbox=1;
            break;
        case $CubicboxSize = 'XL':
            $Xlbox=1;
            break;
                    }

    $box_info=[
    'box_count' => $shippingBoxes,
    's' =>$Sbox,
    'm' =>$Mbox,
    'l' =>$Lbox,
    'xl' =>$fullBox+$Xlbox,

    ];

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

    // dd($shipCost_EMS, $EMS_insurance, $shipCost_Air,$Air_insurance );

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
                'Air_insurance'=>$Air_insurance,
                // 'Ship_boxes'=>$box_info,
            ],compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries','apply_voucher','vvalid'));
    }    

    public function chkout_step3(Request $request){
        $user = $request->user();
        $shipcostArray=explode('|',$_POST["Shipcost"]);

        $R_chkouttype=$_POST["checkouttype"];
        $R_shipcost=$shipcostArray[0];
        $R_Insurance=$_POST["Insurance"];
        $R_ShipMethod=$shipcostArray[1];
        // $R_ShipBoxes=$_POST["ship_boxes"];

        // dd($_POST);
        // dd($R_ShipBoxes);

        // dd($user->id);

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems = [];
        $subtotalPrice = 0;
        $totalCubic = 0;
        $totalWeight = 0;

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $subtotalPrice += $product->retail_price * $quantity;

            $totalWeight += $product->weight_g * $quantity;
            $totalCubic += $product->cubic_cm * $quantity;

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
        $dispercent =0;
        $this->baseDiscount($subtotalPrice);
        $dispercent_v = $this->b_discount['percent_value'];

//// voucher dis new
        $apply_voucher=$request->apply_voucher;
        $vvalid=$request->vvalid;
        $vid =0;

        $voucher = Voucher::query()
                ->where(['code'=>$apply_voucher])
                ->first();

        if($vvalid){
            $vdis_percent=$voucher->discount_percent/100;
                if($this->b_discount['cal'] > $vdis_percent){
                    $dispercent = $this->b_discount['percent'];
                    
                } else {
                    $dispercent = ($voucher->discount_percent).'%';
                    $dispercent_v = ($voucher->discount_percent);
                    $vid = $voucher->id;
                }
        } else {
            $vdis_percent=0;
        }

    ///// decrease voucher from dbs
        Voucher::where(['code'=>$apply_voucher])
        ->decrement('qty',1);    


        $dis_percent= max($this->b_discount['cal'],$vdis_percent);
        $baseDis_amt = $dis_percent * $subtotalPrice;
//////
        $totalpayment = $subtotalPrice-$baseDis_amt+$R_shipcost+$R_Insurance;

///// box calculation
                $xlCubicBox=97336;
            $LastCubicBoxWeight=0;

            $totalCubicBox = ceil($totalCubic/$xlCubicBox);
            $fullCubicBox=floor($totalCubic/$xlCubicBox);         // number of full box needed 

            $nonFullCubicBoxCubic = $totalCubic-($fullCubicBox*$xlCubicBox);  // non-full box weight

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

            $shippingBoxes = (int)$totalCubicBox; // number of box needed 
            $fullBox=$fullCubicBox;         // number of full box needed (always biggest box)

            // shipping boxes data
            $Sbox=0;
            $Mbox=0;
            $Lbox=0;
            $Xlbox=0;

            switch($CubicboxSize){
                case $CubicboxSize = 'S':
                    $Sbox=1;
                    break;
                case $CubicboxSize = 'M':
                    $Mbox=1;
                    break;
                case $CubicboxSize = 'L':
                    $Lbox=1;
                    break;
                case $CubicboxSize = 'XL':
                    $Xlbox=1;
                    break;
                            }

            $box_info=[
            'box_count' => $shippingBoxes,
            's' =>$Sbox,
            'm' =>$Mbox,
            'l' =>$Lbox,
            'xl' =>(int)$fullBox+$Xlbox,
            ];

            // dd($box_info);


        // Create Order
            $orderData = [
                    'total_price' => $subtotalPrice,
                    'discount_amount' => $baseDis_amt,
                    'status' => OrderStatus::Unpaid,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                    'shipping' => $R_shipcost,
                    'insurance'=>$R_Insurance,
                    'ship_method'=>$R_ShipMethod,
                    'bill_id'=>$user->id,
                    'ship_id'=>$user->id,
                    'boxes'=>$box_info,
                    'boxcount'=>$shippingBoxes,
                    'vc'=>$vid,
                    'discount_percent'=>$dispercent_v,
                    'fullprice'=>$totalpayment
                ];

// dd($orderData);

            if ($R_chkouttype == "paynow" ){
            // $orderData = ['status' => OrderStatus::Unpaid];
            $orderData['status'] = OrderStatus::Unpaid;
            } else {
                $orderData['status'] = OrderStatus::Quotation;
            }

            if($subtotalPrice>0){
                $order = Order::create($orderData);
            } else {
                echo "error";
                exit();
            }

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


        $this->createSCauto($order->id);

        // send email to user/admin
        $adminUsers = User::where('is_admin', 1)->get();
        $ppc_team = User::where('is_admin', 2)->get();

        foreach ([...$adminUsers, ...$ppc_team,  $user] as $user) {
            // print_r($user->email);
            Mail::to($user->email)->send(new NewOrderEmail($order));
        }

        // return view('checkout.step3_test',[
        return view('checkout.step3_prod',[
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
                'orderid'=> $order->id,
                // 'paydata'=>$paymentData
            ]);
    }

    public function createSC(Request $request){
    // public function createSC($id){
        $OrderId = $request->OrderID;
        // $OrderId = $id;
        $Order = (Order::query()
                    ->where(['id' => $OrderId])
                    ->first());
        $Items = OrderItem::query()
                    ->where(['order_id' => $OrderId])
                    ->get();

                    // dd($OrderId);
        
    $scdate=date('Ymd'); /// for real use
    $docdate=date('ymd'); /// for enpro ID
    // $scdate=date('Y-m-d');  /// for error test
    // $scdate="20241220"; /// for test
    // $scdate=date('Ymd', strtotime('+1 year'));  /// for test

    // $latestdoc = Order::query()->latest('created_at')->first();
    $latestdoc = Order::whereDate('created_at', Carbon::today())->get()->count();  /// count today doc
    // $latestdoc = Order::whereDate('created_at','=','2023-12-16')->get()->count();  /// count specific date doc
    $enproID = str_pad($latestdoc + 1, 4, '0', STR_PAD_LEFT);

    // dd($enproID);

    ///// random no duplicate
        // $r=range(1,100);
        // shuffle($r);
        // $enpro_doc='WB'.$docdate.'_'.rand(1,100);  for test
        $enpro_doc='WB'.$docdate.'_'.$enproID;

        // dd($enpro_doc);


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
                    'discount_amt'=>($Order->discount_percent/100)*($value->product->retail_price)*$value->quantity

                ];
        }  
        
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
    public function createSCauto($orderid){
    // public function createSC($id){
        $OrderId = $orderid;
        // $OrderId = $id;
        $Order = (Order::query()
                    ->where(['id' => $OrderId])
                    ->first());
        $Items = OrderItem::query()
                    ->where(['order_id' => $OrderId])
                    ->get();

                    // dd($OrderId);
        
    $scdate=date('Ymd'); /// for real use
    $docdate=date('ymd'); /// for enpro ID
    // $scdate=date('Y-m-d');  /// for error test
    // $scdate="20241220"; /// for test
    // $scdate=date('Ymd', strtotime('+1 year'));  /// for test

    // $latestdoc = Order::query()->latest('created_at')->first();
    $latestdoc = Order::whereDate('created_at', Carbon::today())->get()->count();  /// count today doc
    // $latestdoc = Order::whereDate('created_at','=','2023-12-16')->get()->count();  /// count specific date doc
    $enproID = str_pad($latestdoc + 1, 4, '0', STR_PAD_LEFT);

    // dd($enproID);

    ///// random no duplicate
        // $r=range(1,100);
        // shuffle($r);
        // $enpro_doc='WB'.$docdate.'_'.rand(1,100);  for test
        $enpro_doc='WB'.$docdate.'_'.$enproID;

        // dd($enpro_doc);


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
                    'discount_amt'=>($Order->discount_percent/100)*($value->product->retail_price)*$value->quantity

                ];
        }  
        
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

        // print_r($key_val);
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
                // echo nl2br ("\n \n SC created ! \n");


            } else {
                echo nl2br ("\n \n Error in creating SC \n");
            }

        // var_dump($output);


		// return back()->withSuccess('UPload Done !');
    }


}
