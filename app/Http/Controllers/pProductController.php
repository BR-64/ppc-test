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
use App\Models\Stock;

class pProductController extends Controller

{

    public function index()
    {
        $products = pProduct::query()
            ->where('published', '=', 1)
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

        // $stock= Stock::query()
        // ->where('item_code', '=',$product->item_code)
        // ->first(); ;


        return view('product.view', [
            'product' => $product,
            'gallery'=>$gallery,
            'stock'=>$product->stock->stock
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

        // $url='http://1.1.220.113:7000/PrempApi.asmx/getAllStockBalance';
        // $url='http://1.1.220.113:7000/PrempApi.asmx/getAllItemData';

        // $url="https://dummyjson.com/products/1";

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
        // $allproducts = pProduct::query()
        //     ->where('published', '=', 2)
        //     ->orderBy('updated_at', 'desc')
        //     ->paginate(30);

        $qproducts = QueryBuilder::for (pProduct::class)
            ->where('published', '=', 0)
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
}
