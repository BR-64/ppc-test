<?php

namespace App\Imports;

use App\Models\pProduct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TestImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        dd($row);
        return new pProduct([
            'item_code'=>$row['item_code'],
            'form'=>$row['form'],
            'glaze'=>$row['glaze'],
            'BZ'=>$row['bz'],
            'technique'=>$row['technique'],
            'collection'=>$row['collection'],
            'category'=>$row['category'],
            'type'=>$row['type'],
            'brand_name'=>$row['brand_name'],
            'product_description'=>$row['product_description'],
            'color'=>$row['color'],
            'finish'=>$row['finish_2'],
            'pre_order'=>$row['pre_order'],
            'promotion'=>$row['promotion'],
            'discount'=>$row['discount'],
        ]);
        //
    }
}
