<h1>the test page</h1>

<div
class="grid gap-8 grig-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 p-5"
>
@foreach($products as $product)
    <!-- Product Item -->
    <div
        x-data="productItem({{ json_encode([
            'id' => $product->id,
            // 'slug' => $product->item_code,
            'image' => $product->image,
            'title' => $product->item_code,
            'price' => $product->price,
            'addToCartUrl' => route('cart.add', $product)
        ]) }})"
        class="border border-1 border-gray-200 rounded-md hover:border-purple-600 transition-colors bg-white"
    >
        <a href="{{ route('product.view', $product->item_code) }}"
           class="aspect-w-3 aspect-h-2 block overflow-hidden">
            <img
                src="{{ $product->image }}"
                alt=""
                class="object-cover rounded-lg hover:scale-105 hover:rotate-1 transition-transform"
            />
        </a> 
        <div class="p-4">
            <h3 class="text-lg">
                {{-- {{$product->item_code}} --}}
                <a href="{{ route('product.view', $product->item_code) }}">
                    {{$product->item_code}}
                </a>
            </h3>
            <h5 class="font-bold">${{$product->retail_price}}</h5>
        </div>
        <div class="flex justify-between py-3 px-4">
            <button class="btn-primary" @click="addToCart()">
                Add to Cart
            </button>
        </div>
    </div>
    <!--/ Product Item -->
@endforeach
</div>
