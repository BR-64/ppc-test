<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    // protected $table = 'p_stocks';
    protected $table = 'p_stocks_t1';
    protected $fillable = ['stock'];

    public function product():BelongsTo
    {
        return $this->belongsTo(pProduct::class);
    }


}
