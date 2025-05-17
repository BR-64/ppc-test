
<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
?>

<x-shop>

{{-- products --}}
    <?php if ($products->count() === 0): ?>
        <div class="text-center text-gray-600 py-16 text-xl">
            There are no products published
        </div>
    <?php else: ?>
    
    <br>

        <div class="pccoll">
            <div class="gridHL">
    <!-- Product Item -->
          @foreach($products as $product)
                    <div x-show="{{$product->stock->stock}} > 0"
                        class='card2' 
                        x-data="productItem({{ json_encode([
                                'stock'=>$product->stock->stock,
                                'sp'=>$product->sp])
                                }})" 
                        >
                        <a href="{{ route('product.view', $product->item_code) }}"
                          class="">
                            <img
                                src="{{ $product->image }}"
                                alt=""
                                class="pimage hover:scale-105 hover:rotate-1 transition-transform"
                            />
                        </a>
                        <div>
                            <p class="text2 undertext">{{$product->item_code}}</p>
                            <h5 class="text2 undertext">THB {{number_format($product->retail_price)}}</h5>
                        </div>
                        
                    </div>
            @endforeach
            </div>
        </div>



    <?php endif; ?>
    {{-- <!-- Pagination links -->
{{ $products->links() }} --}}
<br>
<br>
    <div class="footspace"></div>
</x-shop>

{{-- @livewireScripts --}}
