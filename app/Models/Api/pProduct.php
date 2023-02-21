<?php

namespace App\Models\Api;

class Product extends \App\Models\pProduct
{
    public function getRouteKeyName()
    {
        return 'id';
    }
}
