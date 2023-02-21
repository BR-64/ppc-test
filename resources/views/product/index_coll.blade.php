@vite(['resources/css/style3.css', 'resources/js/app.js'])

<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
?>

<x-app-layout>
    <?php if ($products->count() === 0): ?>
        <div class="text-center text-gray-600 py-16 text-xl">
            There are no products published
        </div>
    <?php else: ?>
    
    {{-- <h2 class="pagehead">Shop All</h2> --}}
    <br>

    <div class="pccoll">
        {{-- <?php echo $colname[0] ?> --}}
        <h5 class="pagehead">{{$colname}}</h5>
    <div class="gridHL">
            @foreach($products as $product)
                <!-- Product Item -->
            <div class="card2">

                <div>
                    <a href="{{ route('product.view', $product->item_code) }}"
                       class="">
                        <img
                            src="{{ $product->image }}"
                            alt=""
                            class="pimage hover:scale-105 hover:rotate-1 transition-transform"
                        />
                    </a>
                    <div>
                        <h5 class="text2 undertext">THB {{$product->retail_price}}</h5>
                    </div>
                </div>
            </div>
            <!--/ Product Item -->
            @endforeach
        </div>

        </div>
  

    <?php endif; ?>
    <div class="footspace"></div>
</x-app-layout>
