@php
$gallery = App\Models\pProduct::query()
    ->where('item_code', '=', $product->item_code)
    ->latest()->get();
    
$imgs = count($gallery[0]['webimage']);
    // dd($gallery);

@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

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

    swiper-slide {
      background-size: cover;
      background-position: center;
    }

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

    @media only screen and (min-width: 1200px) {
      .swiper,
      swiper-container {
        margin-left: 0;
        margin-right: auto;
      }

    }

  </style>


<div class='gallerycol'>
  <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff;" class="swiper mySwiper2 ml-0">
      <div class="swiper-wrapper">
          <div class="swiper-slide">
              <img class="pgallpic" src="{{$product->image}}" alt="{{$product->image}}" />
          </div>
          @foreach($gallery as $gal)
            @for ($i =0; $i < $imgs; $i++)
              <div class="swiper-slide">
                <img class="pgallpic" 
                src="{{asset ('/storage/'.$gal->webimage[$i])}}" />
              </div>
              
              @endfor
              
              @endforeach
              
          </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
  </div>
  <div thumbsSlider="" class="swiper thumbSwiper"  >
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img class="pgallpicthumb" src="{{$product->image}}" />
        </div>
        @foreach($gallery as $gal)
            @for ($i =0; $i < $imgs; $i++)
              <div class="swiper-slide">
                <img class="pgallpicthumb" 
                src="{{asset ('/storage/'.$gal->webimage[$i])}}" />
              </div>
            @endfor
        @endforeach

    </div>
  </div>
</div>
{{-- 
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script> --}}

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
