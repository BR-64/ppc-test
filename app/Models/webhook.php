<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class webhook extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'status', 'amount', 'type',  'created_by', 'updated_by'];

    public $timestamps = false;
}
