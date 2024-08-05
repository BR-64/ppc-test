<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boxbuffer extends Model
{
    use HasFactory;

    protected $table = 'box_buffer';

    protected $fillable = ['buffer_percent'];
}
