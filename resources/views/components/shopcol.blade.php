<div class="shopcoll">
    <a href="{{ route('product.collection') }}"><h4 class="deco">Shop by Collection</h4></a>
    <div class="gridShopcoll">
      @foreach($collections as $product)
      <!-- Product Item -->
      <div
        class="border border-1 border-gray-200 rounded-md hover:border-purple-600 transition-colors bg-white"
        >
          <a href="{{ route('product.collection.view', $product->collection_name) }}"
             class="aspect-w-3 aspect-h-2 block overflow-hidden">
              <img
                  src="{{ $product->image }}"
                  alt="{{ $product->collection_name }}"
                  class="SCpic"
              />
          </a>
      </div>
      <!--/ Product Item -->
  @endforeach
    </div>
  </div>
