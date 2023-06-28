<div class="shopcoll">
    <a href="{{ route('product.collection') }}"><h4 class="deco">Shop by Collection</h4></a>
    <div class="gridShopcoll">
      @foreach($collections as $product)
      <div
        class=""
        >
          <a href="{{ route('product.collection.view', $product->collection_name) }}"
             class="">
             
              <img
                  src="{{ $product->image }}"
                  alt="{{ $product->collection_name }}"
                  class="SCpic"
              />
          </a>
      </div>
  @endforeach
    </div>
  </div>
