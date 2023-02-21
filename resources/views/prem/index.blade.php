@vite(['resources/css/style3.css', 'resources/js/app.js'])

    <!-- Swiper JS -->
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
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur
            magnam qui porro similique doloremque, quia illum harum id velit
            totam? Tempora perferendis unde doloribus corporis itaque nemo sed,
            dolorem aspernatur.
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


    @foreach($products as $product)
    <div class="pccoll">
            <!-- Product Item -->

                <a href="{{ route('product.collection.view', $product->collection_name) }}">
                    <h5>{{ $product->collection_name }}</h5>
                    <div class="pchero">
{{-- collection cover --}}
                      @include('prem.col_slide')

                    </div>
                </a>
        <!--/ Product Item -->
    </div>
        @endforeach


<?php endif; ?>



    <div class="footspace"></div>
</x-app-layout>
