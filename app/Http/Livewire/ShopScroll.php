<?php

namespace App\Http\Livewire;

use App\Models\pProduct;
use Livewire\Component;

class ShopScroll extends Component
{
    public $totalRecords;
    public $loadAmount =20;

    public function loadMore(){
        $this->loadAmount += 20;
    }

    public function mount(){
        $this->totalRecords = pProduct::count();
    }

    public function render()
    {
        return view('livewire.shop-scroll')
        ->with(
            'products',pProduct::orderBy('created_at','desc')
            ->limit($this->loadAmount)->get()
        );
    }
}