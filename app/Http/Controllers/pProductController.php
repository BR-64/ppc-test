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

class pProductController extends Controller

{

    public function index()
    {
        $products = pProduct::query()
            ->where('published', '=', 1)
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

    public function test(){
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
                ->orderBy('id', 'asc')
                ->paginate(20);
        $collections=pCollection::query()
                ->where('published', '=', 1)
                ->orderBy('id', 'desc')
                ->paginate(4);

        // $filterables = pProduct::select('collection')->distinct()->get();

        return view('test.ppc_home', [
            'products' => $products,
            'newproducts' => $newproducts,
            'hlproducts' => $hlproducts,
            'collections' => $collections,
        ]);
    }

    public function view(pProduct $product)
    {
        $gallery =Portfolio::query()
        ->where('item_code', '=',$product->item_code)
        ->latest()->get(); ;

        return view('product.view', [
            'product' => $product,
            'gallery'=>$gallery
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

        return view('product.index2',[
        // return view('livewire.shop-scroll',[
            'products'=>$products,
            'filterables'=> $filterables,
        ]);

    }

    public function qfilter(){
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

        return view('product.index2', [
            'products' => $qproducts,
            'filterables'=>$filterables
        ]);
    }

    public function infinit(Request $request){

        $products=pProduct::query()
        ->where('published', '=', 1)
        ->orderBy('updated_at', 'desc')
        ->paginate(10);

        // if($request->ajax()){
        //         $view=view('data',compact('products'))->render();
        //     return response()->json(['html'=>$view]);
        // }

        return view('product.infinit',compact('products'));
    }
}
