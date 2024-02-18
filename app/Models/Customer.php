<?php

namespace App\Models;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $fillable = ['first_name', 'last_name', 'phone', 'status','customer_name','customer_taxid'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order() : HasMany
    {
        return $this->hasMany(Order::class,'created_by','user_id');
    }

    private function _getAddresses(): HasOne
    {
        // return $this->hasOne(CustomerAddress::class, 'customer_id', 'user_id');
        return $this->hasOne(CustomerAddress::class, 'user_id', 'customer_id');
    }

    public function shippingAddress(): HasOne
    {
        return $this->_getAddresses()->where('type', '=', AddressType::Shipping->value);
    }
    public function billingAddress(): HasOne
    {
        return $this->_getAddresses()->where('type', '=', AddressType::Billing->value);
    }

    // public function Bill_Address(): HasMany
    // {
    //     return $this->hasmany(BillingAddress::class,'customer_id','user_id');
    // }
    // public function Ship_Address(): HasMany
    // {
    //     return $this->hasmany(ShippingAddress::class,'customer_id','user_id');
    // }
    public function Ship_Address(): HasOne
    {
        return $this->hasOne(ShippingAddress::class,'customer_id','user_id');
    }

    public function Bill_Address(): HasOne
    {
        return $this->hasOne(BillingAddress::class,'customer_id','user_id');
    }

}
