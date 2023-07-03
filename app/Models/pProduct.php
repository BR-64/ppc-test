<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Sluggable\HasSlug;
// use Spatie\Sluggable\SlugOptions;

class pProduct extends Model
{
    use HasFactory;
    // use HasSlug;
    // use SoftDeletes;

    protected $table = 'p_products';
    protected $fillable = ['title', 'description', 'image', 'published', 'image_mime','collection','category', 'type', 'color','finish', 'tags', 'image_size', 'created_by', 'updated_by'];

    /**
     * Get the options for generating the slug.
     */
    // public function getSlugOptions() : SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('title')
    //         ->saveSlugsTo('slug');
    // }

    public function getRouteKeyName()
    {
        return 'item_code';
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'item_code','item_code');
    }

    public static  function realtimeStock($item_code)
    {

        $url='http://1.1.220.113:7000/PrempApi.asmx/getStockBalance?strItemCodeList='.$item_code;

        // $url='http://1.1.220.113:8000/PrempApi.asmx/getStockBalance?strItemCodeList=C34ZFB19A09UW7';

        // $url='http://1.1.220.113:8000/PrempApi.asmx/getStockBalance';



        $ch = curl_init();

        $fields=array(
            'strItemCodeList'=> $item_code
        );

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

        $response = curl_exec($ch);

        curl_close($ch);

        $data=json_decode($response,true);

        // echo($data);

        // $data['STK'];

        echo($data);

        return $data;

        // return json_decode($response);

    }
    // public static  function realtimeStock_test($item_code)
    // {
    //     return $item_code;
    // }



}
