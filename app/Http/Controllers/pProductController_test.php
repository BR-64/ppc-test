<?php

namespace App\Http\Controllers;

use App\Models\pCollection;
use App\Models\pProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;
use Spatie\QueryBuilder\QueryBuilder;

class pProductController_test extends Controller
{
    public function index()
    {

    }

    public function view(pProduct $product)
    {
        return view('product.view', ['product' => $product]);
    }

    public function qfilter(){
        $qproducts = QueryBuilder::for(pProduct::class)
            ->allowedFilters(['collection'])
            ->get();
// dump($qproducts->toSql());    

        return view('welcome', [
            'products'=>$qproducts
        ]);
    }
}
