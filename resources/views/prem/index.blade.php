{{-- @vite(['resources/css/style3.css', 'resources/js/app.js']) --}}

    <!-- Swiper JS -->
      {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" /> --}}
      

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
  
    <!-- Initialize Swiper -->
    <script>
      var swiper = new Swiper(".mySwiper", {
        pagination: {
          el: ".swiper-pagination",
        },
      });
    </script>
    
<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
?>

<x-app-layout>
    <div class="header pt-5">
        <a href="#">
          <img class="mainlogo2" src="https://smoootstudio.com/pic/prempracha/pic/white%20no%20background.png" alt="" />
        </a>
    </div>

    <div class="premcollhero">
        <div class="pcherotext">
          <p class="tleft">
            For more than 30 years, Prempracha's Collection has been the producer of finest handmade ceramics crafted by talented local artisans. Together with talented Thai designers, we would like to introduce our brand, PREM Ceramics. 
          </br>
          </br>

PREM Ceramics represents the link between past, present and future. Personifying the balancing dynamics of the expertise and creativity between generations. All items are handmade 🩷
          </p>
        </div>
    </div>

        <?php if ($products->count() === 0): ?>
            <div class="text-center text-gray-600 py-16 text-xl">
            There are no products published
            </div>
        <?php else: ?>

    {{-- <h2 class="pagehead">Shop All</h2> --}}
    <br>


    <div class="premmaincoll">
    @foreach($products as $product)
          <div class="pchero">
                    <a href="{{ route('product.collection.view', $product->collection_name) }}">
                      <h5 class='colname'>{{ $product->collection_name }}</h5>
                      {{-- @include('prem.col_slide') --}}
                      @include('prem.col_slide2')
                    </a>
                  </br>
                    <p class='coll-des'>
                      {{-- {{$product->collection('description')}} --}}
                      {{$product->description}}</p>
          </div>
        @endforeach
      </div>


<?php endif; ?>



    <div class="footspace"></div>
</x-app-layout>
