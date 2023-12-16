<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'discount_percent', 'qty','valid_until'];

    
    public function getRouteKeyName()
    {
        return 'id';
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class,'vc','id');
    }
    
}