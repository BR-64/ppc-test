<?php

namespace App\Livewire;

use App\Models\pProduct;
use Livewire\Component;

class ShopScroll extends Component
{
    public $totalRecords;
    public $loadAmount =20;

    public function loadMore(){
        $this->loadAmount += 10;
    }

    public function mount(){
        $this->totalRecords = pProduct::count();
        // $this->totalRecords =100;
    }

    public function render()
    {
        return view('livewire.shop-scroll')
        ->with(
            'products',
            pProduct::orderBy('id','desc')->limit($this->loadAmount)->get()
        );
    }
}
