@vite(['resources/css/style3.css', 'resources/js/app.js'])

<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
?>

<x-app-layout>
    {{-- <div class="shopcat">
        <h4>shop by category</h4>
        <div class="gridShopCat">
          <div class="SCat">
            <a href="{{ route('shop.cat', "vase") }}"><img src="https://smoootstudio.com/pic/prempracha/pic/cat1.jpg" alt="" />
            <div class="centered">Vase</div>
            </a>
          </div>
          <div class="SCat">
            <a href="{{ route('shop.cat', "tableware") }}">
            <img src="https://smoootstudio.com/pic/prempracha/pic/cat2.jpg" alt="" />
            <div class="centered">Tableware</div>
          </a>
          </div>
          <div class="SCat">
            <a href="{{ route('shop.cat', "Cup & Mug") }}">
            <img src="https://smoootstudio.com/pic/prempracha/pic/cat3.jpg" alt="" />
            <div class="centered">Cup & Mug</div>
          </a>
          </div>
          <div class="SCat">
            <a href="{{ route('shop.cat', "figurine") }}">
            <img src="https://smoootstudio.com/pic/prempracha/pic/cat4.jpg" alt="" />
            <div class="centered">Figurine</div>
            </a>
          </div>
          <div class="SCat">
            <a href="{{ route('shop.cat', "bathroom") }}">
            <img src="https://smoootstudio.com/pic/prempracha/pic/cat5.jpg" alt="" />
            <div class="centered">Bathroom</div>
          </a>
          </div>
          <div class="SCat">
            <a href="{{ route('shop.cat', "Decorative items") }}">
            <img src="https://smoootstudio.com/pic/prempracha/pic/cat6.jpg" alt="" />
            <div class="centered">Decoratives</div>
          </a>
          </div>
          <div class="SCat">
            <a href="{{ route('shop.cat', "planter") }}">
            <img src="https://smoootstudio.com/pic/prempracha/pic/cat7.jpg" alt="" />
            <div class="centered">Planter</div>
          </a>
          </div>
          <div class="SCat">
            <a href="{{ route('shop.cat', "Wash basin") }}">
            <img src="https://smoootstudio.com/pic/prempracha/pic/cat8.jpg" alt="" />
            <div class="centered">Wash basin</div>
          </a>
          </div>
          <div class="SCat">
            <a href="{{ route('shop.cat', "discount") }}">
            <img src="https://smoootstudio.com/pic/prempracha/pic/pchero.jpg" alt="" />
            <div class="centered" style="background-color: red">
              Special Price
            </div>
          </a>
          </div>
        </div>
    </div>

    <div class="centercontainer" x-data="{ open: false }">
      <button class="button1" x-on:click="open = ! open">Filters</button>
   
      <div x-show="open">
        <div class="filter">
          <div class="filchild" >
              <h3 class="underline">collection</h3>
              @foreach($filter_collections as $filter)
                          <a href="#"
                          class="">
                              <p>{{$filter->collection}}</p>
                          </a>
                          @endforeach
  
                          <br>
          </div>
          <div class="filchild" >
              <h3 class="underline">category</h3>
              @foreach($filter_cats as $filter)
                          <a href="#"
                          class="">
                              <p>{{$filter->category}}</p>
                          </a>
          @endforeach
          </div>
  
          <div class="filchild" >
              <h3 class="underline">our type</h3>
              @foreach($filter_types as $filter)
                          <a href="#"
                          class="">
                              <p>{{$filter->type}}</p>
                          </a>
          @endforeach
                      </div>   
          <div>
          <br>
          <h3 class="underline">color</h3>
          @foreach($filter_colors as $filter)
              <a href="#"
              class="">
              <p>{{$filter->color}}</p>
          </a>
          @endforeach
          </div>
          <div>
          <br>
          <h3 class="underline">Finish</h3>
          @foreach($filter_finishes as $filter)
              <a href="#"
              class="">
              <p>{{$filter->finish}}</p>
          </a>
          @endforeach
          </div>
          <div>
          <br>
          <h3 class="underline">brand</h3>
          @foreach($filter_brands as $filter)
                          <a href="#"
                          class="">
                              <p>{{$filter->brand_name}}</p>
                          </a>
          @endforeach
                      </div>
      </div>
  
      </div>
    </div> --}}


<h1 class="pagehead">Collections</h1>
    <?php if ($products->count() === 0): ?>
        <div class="text-center text-gray-600 py-16 text-xl">
            There are no collection
        </div>
    <?php else: ?>
    
    {{-- <h2 class="pagehead">Shop All</h2> --}}
    <br>

    <div class="pccoll">
    <div class="gridHL">
            @foreach($products as $product)
                <!-- Product Item -->
                    <div   class="card2">

                <div
                    x-data="productItem({{ json_encode([
                        'id' => $product->id,
                        'title' => $product->collection_name,
                        'image' => $product->image,
                    ]) }})"
                >
                    <a href="{{ route('product.collection.view', $product->collection_name) }}"
                       class="">
                        <img
                        src="{{ asset ('/storage/'.$product->image) }}"
                        alt="{{ $product->collection_name }}"
                            {{-- class="pimage hover:scale-105 hover:rotate-1 transition-transform" --}}
                            class="SCpic"
                        />
                    </a>
                    <div>
                        <h5 class="text2 undertext">{{$product->collection_name}}</h5>
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
