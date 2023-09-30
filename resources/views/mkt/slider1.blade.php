
    @php
    $mktSliders = App\Models\Portfolio::query()
        ->where('type', '=', 'main_banner')
        // ->where('type', '=', 'prem_coll_cover')
        ->latest()->get();
    @endphp

<style>
  .swiper_banner {
    width: 100%;
  }

  .swiper-slide {
    text-align: center;
    font-size: 18px;
    background: #fff;
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
              @foreach($mktSliders as $slide)
              <div class="swiper-slide"><img class='slidemain' src="{{$slide->portfolio_image}}" alt=""></div>
              {{-- <div class="swiper-slide"><p>{{$slide->portfolio_title}}</p></div> --}}
              
              @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

      <!-- Swiper JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>--}}

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


