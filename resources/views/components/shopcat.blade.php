    <div class="shopcat">
      <h4>shop by category</h4>
      <div class="gridShopCat">
        @foreach($sharedData['categories'] as $product)
          <div class="SCat">
            <img src="{{ asset ('/storage/'.$product->image) }}" alt="{{ $product->label }}"/>
              <div class="centered">
                <a href="{{ route('shop.cat', $product->name) }}">
                  {{$product->label}}
                </a>
              </div>
          </div>
        @endforeach
      </div>
    </div>
