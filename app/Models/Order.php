<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'total_price', 'created_by', 'updated_by','shipping','insurance','ship_method','pay_method','discount_base','bill_id','ship_id'];

    public function isPaid()
    {
        return $this->status === OrderStatus::Paid->value;
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
    public function bill(): BelongsTo
    {
        return $this->belongsTo(BillingAddress::class,'bill_id');
        // return $this->hasOne(BillingAddress::class);
    }
    public function ship(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
