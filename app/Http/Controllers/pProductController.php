<?php

namespace App\Http\Controllers;

use App\Models\pProduct;
use App\Models\pCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class pProductController extends Controller

{

    public function index()
    {
        $products = pProduct::query()
            ->where('published', '=', 2)
            // ->stock()->where('stock','>',100)
            ->orderBy('updated_at', 'desc')
            ->paginate(30);
            
        $filter_collections = pProduct::distinct()->get('collection');
        $filter_cats = pProduct::distinct()->get('category');
        $filter_types = pProduct::distinct()->get('type');
        $filter_brands = pProduct::distinct()->get('brand_name');
        $filter_colors = pProduct::distinct()->get('color');
        $filter_finishes = pProduct::distinct()->get('finish');

        $filterables = [
            'cats' => pProduct::distinct()->get('category'),
            'colors' => pProduct::distinct()->get('color'),
            'collections' => pProduct::distinct()->get('collection')
        ];

        return view('product.index2', [
            'products' => $products,
            'filter_collections' => $filter_collections,
            'filter_cats' => $filter_cats,
            'filter_types' => $filter_types,
            'filter_brands' => $filter_brands,
            'filter_colors' => $filter_colors,
            'filter_finishes' => $filter_finishes,
            'filterables'=>$filterables

        ]);

    }

    public function home(){
        $products = pProduct::query()
            ->where('published', '=', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(50);
        $newproducts = pProduct::query()
                ->where('newp', '=', 1)
                // ->orderBy('updated_at', 'desc')
                ->inRandomOrder()
                ->paginate(6);
        $hlproducts = pProduct::query()
                ->where('highlight', '=', 1)
                // ->orderBy('id', 'asc')
                ->inRandomOrder()
                // ->orderBy('updated_at', 'desc')
                ->paginate(30);
        $collections=pCollection::query()
                ->where('published', '=', 1)
                ->orderBy('id', 'desc')
                ->paginate(4);

        // $filterables = pProduct::select('collection')->distinct()->get();
        $filterables = [
            'collection' => pProduct::distinct()->get('collection'),
            'category' => pProduct::distinct()->get('category'),
            'type' => pProduct::distinct()->get('type'),
            'brand' => pProduct::distinct()->get('brand_name'),
            'color' => pProduct::distinct()->get('color'),
            'finish' => pProduct::distinct()->get('finish'),
        ];

        View::share('sharedData', [
            'filterables'=>$filterables
        ]);

        return view('test.ppc_home', [
            'products' => $products,
            'newproducts' => $newproducts,
            'hlproducts' => $hlproducts,
            'collections' => $collections,
            // 'filterables'=> $filterables
        ]);
    }

    public function view(pProduct $product)
    {
        $gallery =Portfolio::query()
        ->where('item_code', '=',$product->item_code)
        ->latest()->get(); ;

        $stock = pProduct::realtimeStock($product['item_code']);
        // $stock=$product->stock->stock;

        return view('product.view', [
            'product' => $product,
            'gallery'=>$gallery,
            // 'stock'=>$product->stock->stock
            'stock'=>$stock
        ]);
    }
    public function view_test(pProduct $product)
    {
        $gallery =Portfolio::query()
        ->where('item_code', '=',$product->item_code)
        ->latest()->get(); ;

        // $stock= pProduct::realtimeStock($product->item_code);

        $url='http://1.1.220.113:7000/PrempApi.asmx/getStockBalance?strItemCodeList='.$product['item_code'];

        $url2='http://1.1.220.113:7000/PrempApi.asmx/getItemData?strItemCodeList='.$product['item_code'];

        $ch = curl_init();
        $fields=array(
            'strItemCodeList'=> $product
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        // curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

        $response = curl_exec($ch);

        curl_close($ch);

        // $JsonResponse=substr($response,)
        // preg_match_all('/\(([^\)]+)\)/', $response, $matches);
        preg_match('#\[([^]]+)\]#', $response, $match);
        // dd($match[1]);

        $fromEnpro='{{"item_code":"1AK1925Y6","weight_g":0.000000,"width_cm":21.500000,"length_cm":21.500000,"height_cm":33.500000,"retail_price":3800.000000,"img_path":""},{"item_code":"1G1523Y","weight_g":1800.000000,"width_cm":17.500000,"length_cm":17.500000,"height_cm":12.500000,"retail_price":600.000000,"img_path":""}}';

        $fromEnpro2='{
            "id": 1,
            "title": "iPhone 9",
            "description": "An apple mobile which is nothing like apple",
            "price": 549,
            "discountPercentage": 12.96,
            "rating": 4.69,
            "stock": 94,
            "brand": "Apple",
            "category": "smartphones",
            "thumbnail": "https://i.dummyjson.com/data/products/1/thumbnail.jpg",
            "images": [
                "https://i.dummyjson.com/data/products/1/1.jpg",
                "https://i.dummyjson.com/data/products/1/2.jpg",
                "https://i.dummyjson.com/data/products/1/3.jpg",
                "https://i.dummyjson.com/data/products/1/4.jpg",
                "https://i.dummyjson.com/data/products/1/thumbnail.jpg"
            ]
        }';

        // preg_match('#\[([^]]+)\]#', $fromEnpro, $match);
        // preg_match('#\[([^]]+)\]#', $response, $match);

        // $data=0;
        // var_dump(json_decode($response));
        // $data=json_decode($fromEnpro,true);
        // $data=json_decode($match[1]);
        // $stock=json_decode($response,true);
        // $stock=$response;
        // dd($response);
        // dd($fromEnpro);
        // dd($data);
        
        // echo($response);
        
        
        $data=json_decode($match[1],true);
        $stock=$data['STK'];
        // dd($data);
        
        // print_r($data);

        // $price=$pdata['retail_price'];
        // $price=$pdata['retail_price'];
        // $price=$pdata['retail_price'];



        return view('product.view', [
            'product' => $product,
            'gallery'=>$gallery,
            'stock'=>$stock,
            // 'price'=>$price
        ]);
    }


    public function catFilter($cat){

        if($cat=="discount"){
            $products = pProduct::query()
            ->where('discount','!=',0)
            ->orderBy('updated_at', 'desc')
            ->paginate(300);
        }

        else{   
            $products = pProduct::query()
                ->where('category','=',$cat)
                ->orderBy('updated_at', 'desc')
                ->paginate(300);
        }

        $filterables = [
            'collection' => pProduct::distinct()->get('collection'),
            'category' => pProduct::distinct()->get('category'),
            'type' => pProduct::distinct()->get('type'),
            'brand' => pProduct::distinct()->get('brand_name'),
            'color' => pProduct::distinct()->get('color'),
            'finish' => pProduct::distinct()->get('finish'),
        ];

        // dd($filterables);
        // dd($filterables);
        // dd($products);

        View::share('sharedData', [
            'filterables'=>$filterables
        ]);

        return view('product.index_fil',[
        // return view('livewire.shop-scroll',[
            'products'=>$products,
            'filterables'=> $filterables,
        ]);

    }

    public function qfilter(){
        $allproducts = pProduct::query()
            ->where('published', '=', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(30);

        $qproducts = QueryBuilder::for (pProduct::class)
            ->where('published', '=', 1)
            ->allowedFilters([
                AllowedFilter::exact('collection'),
                AllowedFilter::exact('category'),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('brand'),
                AllowedFilter::exact('color'),
                AllowedFilter::exact('finish'),
                ])
            ->get();
            // ->paginate(20);


        $filterables = [
            'collection' => pProduct::distinct()->get('collection'),
            'category' => pProduct::distinct()->get('category'),
            'type' => pProduct::distinct()->get('type'),
            'brand' => pProduct::distinct()->get('brand_name'),
            'color' => pProduct::distinct()->get('color'),
            'finish' => pProduct::distinct()->get('finish'),
        ];
                // dd($qproducts);

        View::share('sharedData', [
            'products' => $qproducts,
            'filterables'=>$filterables,
            // 'products'=>$allproducts
        ]);

        return view('product.index2', [
            'products' => $qproducts,
            'filterables'=>$filterables,
            // 'products'=>$allproducts
        ]);
    }
    public function qfilter2(){
        $qproducts = QueryBuilder::for (pProduct::class)
            // ->allowedFilters(['collection'])
            ->allowedFilters([
                AllowedFilter::exact('collection'),
                AllowedFilter::exact('category'),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('brand'),
                AllowedFilter::exact('color'),
                AllowedFilter::exact('finish'),
                ])
            ->get();
            // ->paginate(20);


        $filterables = [
            'collection' => pProduct::distinct()->get('collection'),
            'category' => pProduct::distinct()->get('category'),
            'type' => pProduct::distinct()->get('type'),
            'brand' => pProduct::distinct()->get('brand_name'),
            'color' => pProduct::distinct()->get('color'),
            'finish' => pProduct::distinct()->get('finish'),
        ];
                // dd($qproducts);

        View::share('sharedData', [
            'products' => $qproducts,
            'filterables'=>$filterables
        ]);

        return view('product.index_fil', [
            'products' => $qproducts,
            'filterables'=>$filterables
        ]);
    }

    public function infinit(Request $request){

        $products=pProduct::query()
        ->where('published', '=', 1)
        ->orderBy('updated_at', 'desc')
        ->paginate(20);

        if($request->ajax()){
                $view=view('data',compact('products'))->render();
            return response()->json(['html'=>$view]);
        }

        return view('product.infinit',compact('products'));
    }

    public function stockTest(pProduct $product)
    {
        pProduct::realtimeStock($product->item_code);
    }

    public function getAllStockEnpro()
    {
        $url='http://1.1.220.113:7000/PrempApi.asmx/getAllStockBalance';

        // $url='http://1.1.220.113:7000/PrempApi.asmx/getStockBalance?strItemCodeList=C34ZFB19A09UW7';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $response = curl_exec($ch);
        curl_close($ch);

        preg_match('#\[([^]]+)\]#', $response, $match);
        // $data=json_decode($match[1],true);

        // $data2=json_decode($response,true);
        
        // $data3 = json_decode( preg_replace('#\[([^]]+)\]#', '', $response), true );

        // $arr = array_map(function($data5){ return $data5->value; }, $data5);
        
        // $data7=explode("},{",$data5);

        // dd($match[0]);

        $data8=json_decode($match[0],true);

        // print_r(DB::table('p_stocks')->select('item_code','stock')->get());

        // dd($data8);

        $newArr = array();
        foreach($data8 as $enprodata){
            $newArr[$enprodata['code']]=$enprodata['STK'];
        }

        foreach($newArr as $key => $stk){
            Stock::where('item_code','=',$key)->update(['stock'=>$stk]);
        }

        echo('///////////////////////////////////////////////// update finished');

        // print_r(DB::table('p_stocks')->select('item_code','stock')->get());



    }
    public function getAllDataEnpro()
    {
        $url='http://1.1.220.113:7000/PrempApi.asmx/getAllItemData';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $response = curl_exec($ch);
        curl_close($ch);

        echo($response);
        dd('k');

        preg_match('#\[([^]]+)\]#', $response, $match);
                
        $data99=json_decode($match[1],true);

        // dd($data99);
        $data14 = array($match[1]);
        
        // dd($data14);

        // $data10=explode("},{",$match[1]);

        $data11=trim($match[1],'[{}]');
        // $data11=trim($match[0],'[]');
        // dd($data11);


        $data12=explode("},{",$data11);
        $data13=explode(",",$data12[0]);
        $data14=explode(":",$data13[0]);
        // $data14=json_decode($data12[0],1);

        print_r($data12);
        preg_match_all("/ ([^:]+) : ([^,]+) /x", $data12[0], $p);
        $array = array_combine($p[1], $p[2]);

        // dd($data12);
        dd($array);
        dd($data13);
        dd($data14);

        $result = []; 
            for($i=0; $i<count($keys); $i++) {
            $result[$keys[$i]] = $vals[$i];
            }

        // $testdata=string("item_code":"002","weight_g":"0.000000","width_cm":"0.000000","length_cm":"0.000000","height_cm":"0.000000","retail_price":"1.000000","img_path":"");
        // dd($testdata);

        // dd($data12);
        
        $arr = array(   
            "action: Added; amount: 1; code: RNA1; name: Mens Organic T-shirt; colour: White; size: XL",    
            "action: Subtracted; amount: 7; code: RNC1; name: Kids Basic T-shirt; colour: Denim Blue; size: 3-4y",    
            "action: Added; amount: 20; code: RNV1; name: Gift Voucher; style: Mens; value: £20"
          );
              
          
          
          foreach ($arr as $string) {
             //Build array
             preg_match_all("/ [ ]?([^:]+): ([^;]+)[ ;]? /x", $string, $p);
             $array = array_combine($p[1], $p[2]);

             $finalArray =[];
             $finalArray=array_push($finalArray,$array);
          
             //Print it or do something else with it
            }
            dd($array);

        $data15=json_decode($data12[0],1);
        // dd($data15);

        // print_r(DB::table('p_stocks')->select('item_code','stock')->get());

        // $pArr2 = array();
        // foreach($data12 as $enprodata){
        //     $pArr2[$enprodata['item_code']]=[
        //         'weight'=>$enprodata['weight_g'],
        //         'width'=>$enprodata['width_cm'],
        //         'length'=>$enprodata['length_cm'],
        //         'height'=>$enprodata['height_cm'],
        //         'retail_price'=>$enprodata['retail_price']
        //     ];
        // }

        // dd($pArr2);

        // foreach($newArr as $key => $stk){
        //     Stock::where('item_code','=',$key)->update(['stock'=>$stk]);
        // }

        echo('///////////////////////////////////////////////// update finished');


    }

    public function getAllDataEnpro_v2()
    {
        set_time_limit(300);

        $start = microtime(true);

        $webItem = pProduct::query()
            ->where('id','>',0)
            ->get();

        // dd($webItem[0]['item_code']);

        foreach($webItem as $key=>$product){

        $item_code =$product['item_code'];


//// get data from enpro
        $url='http://1.1.220.113:7000/PrempApi.asmx/getItemData?strItemCodeList='.$item_code;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $response = curl_exec($ch);
        curl_close($ch);

//// edit string -> convert to json
        preg_match('#\[([^]]+)\]#', $response, $match);
        $newstring = preg_replace("/(.*?)(,\"img)(.*)/", "$1", $match[1]);
        
        $dataEnpro=json_decode($newstring."}",true);

//// update data to database
        pProduct::where('item_code','=',$dataEnpro['item_code'])
            ->update([
                'weight_g'=>$dataEnpro['weight_g'],
                'width'=>$dataEnpro['width_cm'],
                'length'=>$dataEnpro['length_cm'],
                'height'=>$dataEnpro['height_cm'],
                'retail_price'=>$dataEnpro['retail_price'],
            ]);
        
        }


        $time_elapsed_secs = microtime(true) - $start;

        echo('/// update finished // Time used '.$time_elapsed_secs.' sec');

    }

    public function unpdateStockEnpro(){

            set_time_limit(300);

            $start = microtime(true);
    
            $webItem = pProduct::query()
                ->where('id','>',0)
                ->get();
        
            foreach($webItem as $key=>$product){
    
                $item_code =$product['item_code'];
            
                $url='http://1.1.220.113:7000/PrempApi.asmx/getStockBalance?strItemCodeList='.$item_code;
        
                $ch = curl_init();
        
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_HTTPGET, true);
        
                $response = curl_exec($ch);
                curl_close($ch);
        
                preg_match('#\[([^]]+)\]#', $response, $match);
                $dataEnpro=json_decode($match[1],true);

                //// update data to database
                Stock::where('item_code','=',$dataEnpro['code'])
                ->update(['stock'=>$dataEnpro['STK']]);
                
                // dd($dataEnpro['code']);

            }

            
        $time_elapsed_secs = microtime(true) - $start;

        echo('/// update finished // Time used '.$time_elapsed_secs.' sec');
    
            // $newArr = array();
            // foreach($data8 as $enprodata){
            //     $newArr[$enprodata['code']]=$enprodata['STK'];
            // }
    
            // foreach($newArr as $key => $stk){
            //     Stock::where('item_code','=',$key)->update(['stock'=>$stk]);
            // }
    
            echo('///////////////////////////////////////////////// update finished');
    
    
    }

}
