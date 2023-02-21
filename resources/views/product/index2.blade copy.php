@vite(['resources/css/style3.css', 'resources/js/app.js'])

<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
?>

<x-app-layout>
    <div class="shopcat">
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

{{-- Filter --}}
    <div class="centercontainer" x-data="{ 
      open: false,
      Fil_coll: [],
      Fil_cat: [],
      Fil_type: [],
      Fil_brand: [],
      Fil_color: [],
      Fil_finish: []}
      ">

      <button class="button1 upp" x-on:click="open = ! open">shop by attributes</button>

      <div 
      x-show="open">
        @foreach($filterables as $key=>$filter)
        <div class="filter boxy">
          <h1 class="filterhead">{{$key}}</h1>

          @foreach($filter as $key=>$cat)
            @if($cat->collection)
            <div>
              <input type="checkbox" id="{{$cat['collection']}}" value="{{$cat['collection']}}" x-model="Fil_coll" >
              <label for="{{$cat['collection']}}">{{$cat['collection']}}</label>
            </div>
            
            @elseif($cat->category)
            <div>
              <input type="checkbox" id="{{$cat['category']}}" value="{{$cat['category']}}" x-model="Fil_cat" >
              <label for="{{$cat['category']}}">{{$cat['category']}}</label>
            </div>

            @elseif($cat->type)
            <div>
              <input type="checkbox" id="{{$cat['type']}}" value="{{$cat['type']}}" x-model="Fil_type" >
              <label for="{{$cat['type']}}">{{$cat['type']}}</label>
            </div>

            @elseif($cat->brand)
            <div>
              <input type="checkbox" id="{{$cat['brand_name']}}" value="{{$cat['brand_name']}}" x-model="Fil_brand" >
              <label for="{{$cat['brand_name']}}">{{$cat['brand_name']}}</label>
            </div>

            @elseif($cat->color)
            <div>
              <input type="checkbox" id="{{$cat['color']}}" value="{{$cat['color']}}" x-model="Fil_color" >
              <label for="{{$cat['color']}}">{{$cat['color']}}</label>
            </div>

            @elseif($cat->finish)
            <div>
              <input type="checkbox" id="{{$cat['finish']}}" value="{{$cat['finish']}}" x-model="Fil_finish" >
              <label for="{{$cat['finish']}}">{{$cat['finish']}}</label>
            </div>

            @endif
          @endforeach
          </br>
        </div>
        @endforeach
        <div>
          {{-- <input type="text" :value="Fil_coll"> --}}
          <a x-bind:href="'http://localhost:8000/shop/f'
          +'?filter%5Bcollection%5D='+Fil_coll
          +'&filter%5Bcategory%5D='+Fil_cat
          +'&filter%5Btype%5D='+Fil_type
          +'&filter%5Bbrand%5D='+Fil_brand
          +'&filter%5Bcolor%5D='+Fil_color
          +'&filter%5Bfinish%5D='+Fil_finish
          ">
          <button class="button2">SEARCH</button>
        </a>
      </div>
    </div>
    </div>




{{-- products --}}
    <?php if ($products->count() === 0): ?>
        <div class="text-center text-gray-600 py-16 text-xl">
            There are no products published
        </div>
    <?php else: ?>
    
    {{-- <h2 class="pagehead">Shop All</h2> --}}
    <br>

    <div class="pccoll">
    <div class="gridHL">
 <!-- Product Item -->
@foreach($products as $product)

    <div
        {{-- x-data="product({{ json_encode([
            'id' => $product->id,
            'slug' => $product->item_code,
            'image' => $product->image,
            'title' => $product->title,
            'price' => $product->retail_price,
            'cat'=>$product->pcategory_id,
            'addToCartUrl' => route('cart.add', $product)
        ]) }})" --}}
        >
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


{{-- @foreach($filterables as $filter)

<h5 class="text2 undertext">{{$filter[0]['category']}}</h5>
<h5 class="text2 undertext">{{$filter[1]['category']}}</h5> --}}

            {{-- </div> --}}

        </div>
  

    <?php endif; ?>
    <div class="footspace"></div>
</x-app-layout>
