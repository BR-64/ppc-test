<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class pCollection extends Model
{
    use HasFactory;
    // use HasSlug;
    // use SoftDeletes;

    protected $table = 'p_collections';
    protected $fillable = ['collection_name'];

    public function getRouteKeyName()
    {
        return 'collection_name';
    }


}
