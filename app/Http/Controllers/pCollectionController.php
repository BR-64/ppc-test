<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pCollection;
use App\Models\pProduct;



class pCollectionController extends Controller
{
    public function index(){
        $colls = pCollection::query()
        ->where('published', '=', 1)
        ->orderBy('id', 'desc')
        ->paginate(100);
        
        return view('product.collection', ['products' => $colls]);
    }

    public function prem(){
        $colls = pCollection::query()
        ->where('published', '=', 1)
        ->where('brand_name', '=', 'prem')
        ->orderBy('id', 'desc')
        ->paginate(10);
        
        return view('prem.index', ['products' => $colls]);
    }

    public function view($col)
    {
        $products = pProduct::query()
        ->where('collection', '=', $col)
        ->orderBy('updated_at', 'desc')
        ->paginate(300);

        $colname = pProduct::query()
        ->where('collection', '=', $col)
        ->distinct()
        ->get('collection');

        return view('product.index_coll', [
            'products' => $products,
            'colname'=>$colname[0]['collection']]);

    }

    //
}
