<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table='Shipping_addresses';
    protected $fillable = ['address1', 'address2', 'city', 'state', 'zipcode', 'country_code', 'customer_id'];

    public function customer():BelongsTo{
        return $this->belongsTo(Customer::class,'customer_id');
        // return $this->belongsTo(Customer::class);
    }

    public function order():HasMany{
        return $this->hasMany(Order::class,'bill_id','id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }


}
