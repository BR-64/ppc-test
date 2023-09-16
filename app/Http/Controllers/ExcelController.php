<?php

namespace App\Http\Controllers;

use App\Imports\TestImport;
use App\Models\pProduct;
use App\Models\pProduct_up;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function TestImport(Request $request){
        // $path1 = $request->file('mcafile')->store('temp'); 
        // $path=storage_path('app').'/'.$path1;  

        // Excel::import(new TestImport,$path);
        // Excel::import(new TestImport,);

        Excel::import(new TestImport, request()->file('your_file'));
        // DB::table('p_products')->delete();
        pProduct_up::truncate();
        // Excel::import(new TestImport, 'testupload2.csv');


        return 'success';
    }

    public function getAllDataEnpro_v2()
    {
        set_time_limit(3000);

        $start = microtime(true);

        $webItem = pProduct::query()
            ->where('id','>',0)
            ->get();

        // dd($webItem[0]['item_code']);

        foreach($webItem as $key=>$product){

        $item_code =$product['item_code'];

        // dd($product);


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
        // dd(preg_match('#\[([^]]+)\]#', $response, $match));
        try{
        $newstring = preg_replace("/(.*?)(,\"img)(.*)/", "$1", $match[1]);
        
            $dataEnpro=json_decode($newstring."}",true);
        // echo ($item_code.','."<br>\n");

        } catch (Exception $e){
            echo ('Error: '.$item_code."<br>\n");
        };


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

    //
}
