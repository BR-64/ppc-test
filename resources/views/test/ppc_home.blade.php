
  <head>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

  </head>

  <x-app-layout>
    <main>

  <!-- portfolio-area -->
    
  {{-- @include('mkt.portfolio') --}}
  @include('mkt.slider1')

  <!-- portfolio-area-end -->
  @include('components.shopcat')


      <div class="premcoll">
        <a href="{{ route('homeprem')}}">
          <img class="logo" src="https://smoootstudio.com/pic/prempracha/pic/premlg1.png" alt="" />
        </a>
          {{-- <h4>Prem ceramic</h4> --}}
          {{-- <p >
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur
            magnam qui porro similique doloremque, quia illum harum id velit
            totam? Tempora perferendis unde doloribus corporis itaque nemo sed,
            dolorem aspernatur.
          </p> --}}
          {{-- <div class="prem_banner"> --}}
            @include('mkt.prem_slider')
            
          {{-- </div> --}}
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

      <div class="footspace"></div>
      
      
      
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
        </br></br>Operating Hour: 8:30 - 17:30 hrs, 
      </br>Monday - Saturday<br>
      <div class="footericon">
        
        <a class="icon" href="https://www.facebook.com/premprachaco/" target="_blank"><img src="https://smoootstudio.com/pic/prempracha/pic/icon_fb.png" width="32" height="32" alt="Facebook"/></a> 
        
        <a class="icon" href="https://www.instagram.com/premprachaco/" target="_blank"><img src="https://smoootstudio.com/pic/prempracha/pic/icon_ig.png" width="32" height="32" alt="instagram"/></a><br></td>
      </div>
    </tr>
  </tbody>
</table>
</footer>


<!-- Initialize Swiper -->
  <script>
  var swiper = new Swiper(".mySwiper", {
    pagination: {
      el: ".swiper-pagination",
    },
  });
  </script>      

</x-app-layout>
