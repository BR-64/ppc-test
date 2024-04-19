<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxInfo extends Model
{
    use HasFactory;
    protected $table = 'box_info';

    protected $fillable = ['size','weight','cubic','shipcost_v1'];

}
