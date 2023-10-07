<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class att_category extends Model
{
    use HasFactory;
    protected $table = 'att_categories';

    protected $fillable = ['code', 'category_name', 'description'];


    public function products(): HasMany
    {
        return $this->hasMany(pProduct::class, 'category','category_name');
    }   


}
