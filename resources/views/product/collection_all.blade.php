
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

        <?php if ($products->count() === 0): ?>
            <div class="text-center text-gray-600 py-16 text-xl">
            There are no products published
            </div>
        <?php else: ?>
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
                    {{-- <p class='coll-des'> --}}
                    <p>
                      {{-- {{$product->collection('description')}} --}}
                      {{$product->description}}</p>
          </div>
        @endforeach
      </div>


<?php endif; ?>



    <div class="footspace"></div>
</x-app-layout>
