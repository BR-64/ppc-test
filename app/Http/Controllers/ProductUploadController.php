<?php

namespace App\Http\Controllers;

use App\Models\pProduct;
use App\Models\pProduct_upload;
use App\Models\ProductNew;
use App\Models\ProductUpload;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;

class ProductUploadController extends Controller
{
    public function compare(){
        // compare products_upload vs products in db then put all new products into 'p_products_new' table

        // compareExistingProducts
        ProductNew::truncate();

        $t1='p_products_upload';
        $t2='p_products_t1';

        $newP_from_upload = pProduct_upload::leftJoin('p_products_t1',function($join){
            $join->on('p_products_upload.item_code','=','p_products_t1.item_code');
        })
        ->whereNull($t2.'.item_code')
        ->get([
            $t1.'.item_code',
            $t1.'.form',
            $t1.'.glaze',
            $t1.'.BZ',
            $t1.'.technique',
            $t1.'.collection',
            $t1.'.category',
            $t1.'.type',
            $t1.'.brand_name',
            $t1.'.product_description',
            $t1.'.color',
            $t1.'.finish',
            $t1.'.pre_order',
        ])
        ->toArray();

        // dd($newP_from_upload);

        // insert into product_new table
        foreach($newP_from_upload as $p){
            ProductNew::create($p);
        }

        // get enpro stock data to new products
            $webItem = ProductNew::query()
            ->where('id','>',0)
            ->get();

        // get stock data
            foreach($webItem as $key=>$product){

            $item_code =$product['item_code'];

            //// get data from enpro
            $url='http://1.1.220.113:7000/PrempApi.asmx/getStockBalance?strItemCodeList='.$item_code;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            //// edit string -> convert to json
            preg_match('#\[([^]]+)\]#', $response, $match);

            try{
                $newstring = preg_replace("/(.*?)(,\"img)(.*)/", "$1", $match[1]);
                    $dataEnpro=json_decode($newstring,true);
                } catch (Exception $e){
                    echo ('Error: '.$item_code."<br>\n");
                };
                    //// update data to database
                    ProductNew::where('item_code','=',$dataEnpro['code'])
                    ->update([
                        'stock'=>$dataEnpro['STK'],
                    ]);
            }

        // get products data
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
        
                try{
                    $newstring = preg_replace("/(.*?)(,\"img)(.*)/", "$1", $match[1]);
                    
                        $dataEnpro=json_decode($newstring."}",true);
                    // echo ($item_code.','."<br>\n");
            
                    } catch (Exception $e){
                        echo ('Error: '.$item_code."<br>\n");
                    };                       
                        //// update data to database
                        ProductNew::where('item_code','=',$dataEnpro['item_code'])
                        ->update([
                            'weight_g'=>$dataEnpro['weight_g'],
                            'width'=>$dataEnpro['width_cm'],
                            'length'=>$dataEnpro['length_cm'],
                            'height'=>$dataEnpro['height_cm'],
                            'retail_price'=>$dataEnpro['retail_price'],
                        ]);
                        
                }

        echo 'Compare Done';


    }

    public function addNewPtoTables () {
        // insert into stock table
            $newP = ProductNew::query()
                ->where('id','>',0)
                ->get([
                    'item_code',
                    'form',
                    'glaze',
                    'BZ',
                    'technique',
                    'collection',
                    'category',
                    'type',
                    'brand_name',
                    'product_description',
                    'color',
                    'finish',
                    'pre_order',
                    'stock'
                ])
                ->toArray();
    
            foreach($newP as $p){
                Stock::create($p);
            }
        
        // insert into product table
            foreach($newP as $p){
                pProduct::create($p);
            }

        echo 'New Products Added';

    }



    public function insertNewPtoStock(){
        app(\App\Http\Controllers\pProductController::class)->getPrintReport();

        $newP_from_upload = ProductNew::query()
        ->get([
            'item_code','stock'
        ])
        ->toArray();

        // dd($newP_from_upload);

        // foreach($newP_from_upload as $p){
        //     echo ($p['item_code']."\r\n" );
        // }

        // Stock::create($newP_from_upload[0]['item_code']);

        foreach($newP_from_upload as $p){
            Stock::create($p);
        }







    }
}
