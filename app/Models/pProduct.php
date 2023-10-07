<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Sluggable\HasSlug;
// use Spatie\Sluggable\SlugOptions;
use App\Models\Att_category;


class pProduct extends Model
{
    use HasFactory;
    // use HasSlug;
    // use SoftDeletes;

    // protected $table = 'test_product';
    protected $table = 'p_products_t1';
    // protected $table = 'p_products';
    protected $fillable = ['description', 
    'form', 'glaze', 'ฺBZ','technique','collection','category','type','brand_name','product_description','color','finish',
    'image', 'published', 'color','finish', 'tags', 'image_size', 'created_by', 'updated_by', 'webimage','Highlight','newp'];

    protected $casts=[
        'webimage'=>'array'
    ];

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

    public function stock():HasOne
    {
        return $this->hasOne(Stock::class, 'item_code','item_code');
    }
    public static function webstock($item_code)
    {
        // $stock= Stock::query()
        // ->where('item_code', '=',$item_code)
        // ->get('stock');
    
        $stock= Stock::where('item_code', '=',$item_code)->value('stock');

        return $stock;
    }

    public function collection():BelongsTo
    {
        return $this->belongsTo(pCollection::class);
        // return $this->belongsTo(pCollection::class, 'collection','collection_name');
    }
    public function category():BelongsTo
    {
        return $this->belongsTo(att_category::class);
        // return $this->belongsTo(att_category::class,'category','category_name');
    }

    // public function catEdit():BelongsTo{
    //      return $this->belongsTo(att_category::class,'category','category_name');
    // }

    public static  function realtimeStock($item_code)
    {

        $url='http://1.1.220.113:7000/PrempApi.asmx/getStockBalance?strItemCodeList='.$item_code;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $response = curl_exec($ch);
        curl_close($ch);

        preg_match('#\[([^]]+)\]#', $response, $match);
        $data=json_decode($match[1],true);

        $enprostock = $data['STK'];

        $webstock = self::webstock($item_code);
        
    /// compare to get lowest stock
        $realtimeStock = min($enprostock,$webstock);
    
    /// update lowest stock to database
        if($realtimeStock <> $webstock){
            Stock::where('item_code', '=',$item_code)->update(['stock'=>$realtimeStock]);
        };
   

        return $realtimeStock;

        // return json_decode($response);

    }
    public static  function enproStock($item_code)
    {

        $url='http://1.1.220.113:7000/PrempApi.asmx/getStockBalance?strItemCodeList='.$item_code;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $response = curl_exec($ch);
        curl_close($ch);

        preg_match('#\[([^]]+)\]#', $response, $match);
        $data=json_decode($match[1],true);

        $enprostock = $data['STK'];

        dd($enprostock);

        return $enprostock;

    }

    
    // public static  function realtimeStock_test($item_code)
    // {
    //     return $item_code;
    // }



}
