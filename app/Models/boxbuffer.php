<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class boxbuffer extends Model
{
    use HasFactory;

    protected $table = 'box_buffer';
    protected $fillable = [`range`,`buffer_W`,`buffer_L`,`buffer_H`];


}
