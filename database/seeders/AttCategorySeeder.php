<?php

namespace Database\Seeders;

use App\Models\att_category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        att_category::create(['category'=>'Vase']);
        att_category::create(['category'=>'Tableware']);
        att_category::create(['category'=>'Planter']);
        att_category::create(['category'=>'Figurine']);
        att_category::create(['category'=>'Wash basin']);
        att_category::create(['category'=>'Decorative items']);
        att_category::create(['category'=>'BathroomAccessories']);
        att_category::create(['category'=>'Cup & Mug']);
        att_category::create(['category'=>'Special price']);
        att_category::create(['category'=>'Other']);
        //
    }
}
