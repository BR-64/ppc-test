
    @php
    $mktSliders = App\Models\Banner::query()
        ->where('name', '=', 'main banner')
        ->latest()->get();
        
    $imgs = count($mktSliders[0]['extra_image']);
        // $ex_image=App\Models\Banner::imgs['main banner'];
        // $ex_image = count($mktSliders->extra_image);
        // dd($ex_image);
    // $imgs = App\Models\Banner::imgs('main banner')

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
            @foreach($mktSliders as $slide)
            <div class="swiper-slide">
              <img class='slidemain' src="{{asset ('/storage/'.$slide->image)}}" alt="">
            </div>
                @for ($i =0; $i < $imgs; $i++)
                <div class="swiper-slide">
                  <img src="
                  {{asset ('/storage/'.$slide->extra_image[$i])}}
                  " alt="" class="slidemain">
                </div>
                @endfor
            </div>
              @endforeach
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


