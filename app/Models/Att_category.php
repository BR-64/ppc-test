<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class att_category extends Model
{
    protected $fillable = ['code', 'category', 'description'];

    use HasFactory;
}
