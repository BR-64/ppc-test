<!DOCTYPE html>
<html lang="en">
<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
?>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {{-- <link rel="stylesheet" href="public/css/style3.css" /> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/style3.css') }}" > --}}

    @vite(['resources/css/style3.css', 'resources/js/app.js'])


    <title>Prempracha Online Store test2</title>

    <style>
      @import url('https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;1,200&family=Open+Sans:wght@300&display=swap');
    </style>
  </head>
  <body>
    <x-app-layout>

    {{-- <header>
      <div class="header">
        <a href="index.html">
          <img class="mainlogo" src="https://smoootstudio.com/pic/prempracha/pic/prempralogo.png" alt="" />
        </a>
      </div>
    </header> --}}
    <main>

  <!-- portfolio-area -->
    
  {{-- @include('mkt.portfolio') --}}
  @include('mkt.slider1')

  <!-- portfolio-area-end -->

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
            <a href="{{ route('shop.cat', "vase") }}">
            <img src="https://smoootstudio.com/pic/prempracha/pic/pchero.jpg" alt="" />
            <div class="centered" style="background-color: red">
              Special Price
            </div>
          </a>
          </div>
        </div>
      </div>

      <div class="premcoll">
        <a href="{{ route('homeprem')}}">
          <img class="logo" src="https://smoootstudio.com/pic/prempracha/pic/premlg1.png" alt="" />
        </a>
        <h4>Prem ceramic</h4>
        <p >
          Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur
          magnam qui porro similique doloremque, quia illum harum id velit
          totam? Tempora perferendis unde doloribus corporis itaque nemo sed,
          dolorem aspernatur.
        </p>
        <div class="gridgall">
          <img class="fg1" src="https://smoootstudio.com/pic/prempracha/pic/test9.jpg" alt="" />
          <img class="fg2" src="https://smoootstudio.com/pic/prempracha/pic/test9.jpg" alt="" />
          <img class="fg3" src="https://smoootstudio.com/pic/prempracha/pic/test1.jpg" alt="" />
        </div>
      </div>
      <div class="mainHL">
        <h4 class="deco">Highlight</h4>
{{-- Highlight slider --}}
@include('product.hl_product')


      </div>
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
              class="border border-1 border-gray-200 rounded-md hover:border-purple-600 transition-colors bg-white"
          >
              <a href="{{ route('product.view', $newproduct->item_code) }}"
                 class="aspect-w-3 aspect-h-2 block overflow-hidden">
                  <img
                      src="{{ $newproduct->image }}"
                      alt="{{ $newproduct->item_code }}"
                      class="NPpic"
                  />
              </a>
          </div>
          <!--/ Product Item -->
      @endforeach
  
        </div>
      </div>
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
                      class="HLpic"
                  />
              </a>
          </div>
          <!--/ Product Item -->
      @endforeach


        </div>
      </div>
      <div class="shopall">
        <a href="{{ route('shopf') }}"><button>Shop All</button></a>
      </div>
      <div class="bottom"></div>

      <img src="/PPC-3_1.jpg" alt="">
      <img src="{{ asset('storage/banner/PPC-3_1.jpg') }}" alt="">

      <div class="footspace"></div>
    </x-app-layout>


    
    </main>
    <footer>
    <table class="footercontent"  border="0" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td height="140" align="center" bgcolor="#b2b2b2" class="linkGray">
           <br>
           <h2 style="font-size: 17px;">Prempracha’s Collection</h2>
            224 M.3, Chiang mai-Sankampang Rd., T.Tonpao, A.Sankampang Chiang mai, 
            50130 Thailand<br />
          </br>
            Tel.: 66 5333 8540, 66 5333 8857
        </br> Email: <a href="mailto:info@prempracha.com">info@prempracha.com</a>
      </br></br>Operating Hour: 8:30 - 17:30, 
    </br>Monday - Saturday<br>
            <div class="footericon">

              <a class="icon" href="https://www.facebook.com/premprachaco/" target="_blank"><img src="https://smoootstudio.com/pic/prempracha/pic/icon_fb.png" width="32" height="32" alt="Facebook"/></a> 
              
              <a class="icon" href="https://www.instagram.com/premprachaco/" target="_blank"><img src="https://smoootstudio.com/pic/prempracha/pic/icon_ig.png" width="32" height="32" alt="instagram"/></a><br></td>
            </div>
            </tr>
      </tbody>
    </table>
  </footer>

  </body>
</html>
