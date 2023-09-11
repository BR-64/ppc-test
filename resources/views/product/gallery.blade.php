
<style>
    swiper-container {
      width: 100%;
      height: 100%;
    }

    swiper-slide {
      text-align: center;
      font-size: 18px;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height:55px;
    }

    swiper-slide img {
      display: block;
      width: 100%;
      height: 10%;
      object-fit: cover;
    }

    /* swiper-container {
      width: 100%;
      height: 300px;
      margin-left: auto;
      margin-right: auto;
    } */

    swiper-slide {
      background-size: cover;
      background-position: center;
    }



    /* .mySwiper2 {
      width: 363px;

      height: 250px;
      /* box-sizing: content-box; */
      /* padding: 10px 0; */

    } */

    .mySwiper2 swiper-slide {
      width: 25%;
      height: 10%;
      opacity: 0.4;
    }

    .mySwiper2 .swiper-slide-thumb-active {
      opacity: 1;
    }

    swiper-slide img {
      display: block;
      width: 10%;
      height: 10%;
      object-fit: cover;
    }
  </style>


<div class='gallerycol'>
  <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
      <div class="swiper-wrapper">
          <div class="swiper-slide">
              <img class="pgallpic" src="{{$product->image}}" alt="{{$product->image}}" />
          </div>
          @foreach($gallery as $gall)
              <div class="swiper-slide">
                  <img class="pgallpic" src="{{$gall->portfolio_image}}" />
              </div>
          @endforeach
      
      </div>
      {{-- <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div> --}}
  </div>
  <div thumbsSlider="" class="swiper thumbSwiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img class="pgallpicthumb" src="{{$product->image}}" />
        </div>
        @foreach($gallery as $gall)
        <div class="swiper-slide">
            <img class="pgallpicthumb" src="{{$gall->portfolio_image}}" />
        </div>
    @endforeach

    </div>
  </div>
</div>

  {{-- <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script> --}}

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".thumbSwiper", {
      spaceBetween: 10,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
    });
    var swiper2 = new Swiper(".mySwiper2", {
      spaceBetween: 10,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      thumbs: {
        swiper: swiper,
      },
    });
  </script>
