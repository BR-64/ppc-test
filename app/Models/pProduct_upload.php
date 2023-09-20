<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\att_category;


class pProduct_upload extends Model
{
    use HasFactory;

    protected $table = 'p_products_upload';
    protected $fillable = ['item_code','form','glaze','BZ','technique','collection','type','brand_name','product_description','color','finish','pre_order','promotion','discount','title', 'description', 'image', 'published', 'image_mime','tags', 'image_size', 'created_by', 'updated_by'];




}
