<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class boxinfo extends Model
{
    use HasFactory;
    protected $table = 'box_info';

    protected $fillable = ['max_weight_g', 'max_cubic_cbcm', 'shipcost_v1', 'shipcost_v2', 'shipcost_v3'];

}
