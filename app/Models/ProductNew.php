<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductNew extends Model
{
    use HasFactory;

    protected $table='p_products_new';
    protected $fillable = ['item_code','form','glaze','BZ','technique','collection','category','type','brand_name','product_description','color','finish','pre_order','promotion','discount','title', 'description', 'image', 'published', 'image_mime','tags', 'image_size', 'webimage','created_at', 'updated_at','stock'];

}
