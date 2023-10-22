<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = ['name','image','extra_image'];
    protected $casts=[
        'extra_image'=>'array',
    ];

    public static function imgs($name){

        $banner = Banner::query()
        ->where('name', '=', $name)
        ->latest()->get();

        // $imgs = count($banner->image);
        $imgs = count($banner[0]['extra_image']);
        // $imgs = count($banner['id']);
        // $imgs=$banner['name'];
        // $imgs=$banner;

        return $imgs;

    }
}
