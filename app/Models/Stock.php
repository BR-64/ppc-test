<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    // protected $table = 'test_stock';
    // protected $table = 'p_stocks_old';
    protected $table = 'p_stocks';
    protected $fillable = ['stock'];

    public function product():BelongsTo
    {
        return $this->belongsTo(pProduct::class);
    }


}
