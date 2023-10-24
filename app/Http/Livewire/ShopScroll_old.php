<?php

namespace App\Http\Livewire;

use App\Models\pProduct;
use Livewire\Component;

class ShopScroll_old extends Component
{
    public $totalRecords;
    public $loadAmount =10;

    public function loadMore(){
        $this->loadAmount += 10;
    }

    public function mount(){
        $this->totalRecords = pProduct::count();
        // $this->totalRecords =10;
    }

    public function render()
    {
        return view('livewire.shop-scroll')
        ->with(
            'products',pProduct::orderBy('id','desc')
            ->limit($this->loadAmount)->get()
        );
    }
}
