<div class="newproducts">
    <h4 class="deco">New Products</h4>
    <div class="gridNewProduct">
      @foreach($newproducts as $newproduct)
      <!-- Product Item -->
      <div
          x-data="productItem({{ json_encode([
              'id' => $newproduct->id,
              'slug' => $newproduct->item_code,
              'image' => $newproduct->image,
              'title' => $newproduct->title,
              'price' => $newproduct->retail_price,
              'cat'=>$newproduct->pcategory_id,
              'addToCartUrl' => route('cart.add', $newproduct)
          ]) }})"
          class=""
      >
          <a href="{{ route('product.view', $newproduct->item_code) }}"
             class="aspect-w-3 aspect-h-2 block overflow-hidden">
              <img
                  src="{{ $newproduct->image }}"
                  alt="{{ $newproduct->item_code }}"
                  class="NPpic border border-1 border-gray-200 rounded-sm bg-white "
              />
          </a>
      </div>
      <!--/ Product Item -->
  @endforeach

    </div>
  </div>
