<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class pCollection extends Model
{
    use HasFactory;
    // use HasSlug;
    // use SoftDeletes;

    protected $table = 'p_collections';
    protected $fillable = ['collection_name','image','brand_name','description','coll_image','published'];

    protected $casts=[
        'coll_image'=>'array',
    ];

    public function getRouteKeyName()
    {
        return 'collection_name';
    }

    public function products(): HasMany
    {
        return $this->hasMany(pProduct::class, 'collection','collection_name');
    }   

}
