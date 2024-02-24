<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CubicCal extends Model
{
    use HasFactory;
    protected $table = 'cubiccal_info';

    protected $fillable = ['cubic_span', 'v1', 'v2', 'v3'];

}
