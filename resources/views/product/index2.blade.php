{{-- @vite(['resources/css/style3.css', 'resources/js/app.js']) --}}

<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
?>
 {{-- @livewireStyles --}}
<x-shop>

{{-- products --}}
    <?php if ($products->count() === 0): ?>
        <div class="text-center text-gray-600 py-16 text-xl">
            There are no products published
        </div>
    <?php else: ?>
    
    {{-- <h2 class="pagehead">Shop All</h2> --}}
    <br>

    {{-- @livewire('shop-scroll') --}}

    <div class="pccoll">
        <div class="gridHL">
    <!-- Product Item -->
          @foreach($products as $product)
  
                <div>
                    <div class="card2">
  
                        <a href="{{ route('product.view', $product->item_code) }}"
                          class="">
                            <img
                                src="{{ $product->image }}"
                                alt=""
                                class="pimage hover:scale-105 hover:rotate-1 transition-transform"
                            />
                        </a>
                        <div>
                            <h5 class="text2 undertext">THB {{number_format($product->retail_price)}}</h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- {{$products->links()}} --}}
      </div>


    <?php endif; ?>
    <div class="footspace"></div>
</x-shop>

{{-- @livewireScripts --}}
