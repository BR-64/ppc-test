
    @php
    $mktSliders = App\Models\Banner::query()
        ->where('name', '=', 'collection')
        ->latest()->get();
        
    $imgs = count($mktSliders[0]['extra_image']);
    @endphp

<style>
  .swiper_banner {
    width: 100%;
  }

  .swiper-slide {
    text-align: center;
    font-size: 18px;
    /* background: #fff; */
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* .swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    /* height: 80vh; */
    object-fit: cover;
  } */

  .mySwiper_banner{
  height: 500px;
  width: 100%;
  height: 80vh;
  /* width: 363px; */
  margin-top: 5px;
  }

</style>

  <!-- Swiper -->
  <div class="banner">
      <div class="swiper mySwiper_banner">
          <div class="swiper-wrapper">
            @foreach($collections as $product)
            <div class="swiper-slide">
                <a href="{{ route('product.collection.view', $product->collection_name) }}" class="">
                    <img
                        src="{{ asset ('/storage/'.$product->image) }}"
                        alt="{{ $product->collection_name }}"
                        class="slidemain"
                    />
                </a>
            </div>
        @endforeach
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper_banner", {
      pagination: {
        el: ".swiper-pagination",
      },
      autoplay: {
        delay: 4000,
      },

    });
  </script>


