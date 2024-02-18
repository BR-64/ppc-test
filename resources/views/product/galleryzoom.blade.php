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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <script>
         $(function () {
            //Larger thumbnail preview
            var perc = 40;
            $("ul.thumb li").hover(function () {
                $("ul.thumb li").find(".thumbnail-wrap").css({
                    "z-index": "0"
                });
                $(this).find(".thumbnail-wrap").css({
                    "z-index": "10"
                });
                var imageval = $(this).find(".thumbnail-wrap").css("background-image").slice(5);
                var img;
                var thisImage = this;
                img = new Image();
                img.src = imageval.substring(0, imageval.length - 2);
                img.onload = function () {
                    var imgh = this.height * (perc / 100);
                    var imgw = this.width * (perc / 100);
                    $(thisImage).find(".thumbnail-wrap").addClass("hover").stop()
                        .animate({
                            marginTop: "-" + (imgh / 4) + "px",
                            marginLeft: "-" + (imgw / 4) + "px",
                            width: imgw + "px",
                            height: imgh + "px"
                        }, 200);
                }
            }, function () {
                var thisImage = this;
                $(this).find(".thumbnail-wrap").removeClass("hover").stop()
                    .animate({
                        marginTop: "0",
                        marginLeft: "0",
                        top: "0",
                        left: "0",
                        // width: "100px",
                        // height: "100px",
                        width: "500px",
                        height: "750px",
                        padding: "5px"
                    }, 400, function () {});
            });

            //Show thumbnail in fullscreen
            $("ul.thumb li .thumbnail-wrap").click(function () {

                var imageval = $(this).css("background-image").slice(5);
                imageval = imageval.substring(0, imageval.length - 2);
                $(".thumbnail-zoomed-image img").attr({
                    src: imageval
                });
                $(".thumnail-zoomed-wrapper").fadeIn();
                return false;
            });

            //Close fullscreen preview
            $(".thumnail-zoomed-wrapper .close-image-zoom").click(function () {
                $(".thumnail-zoomed-wrapper").hide();
                return false;
            });
        });
  </script>


<div class='gallerycol'>
  <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff;" class="swiper mySwiper2 ml-0">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
        <ul class="thumb">
          <li>
            <a href="javascript:void(0)">
              <img class=" thumbnail-wrap" style="background-image:url({{$product->image}})"
              {{-- src="{{$product->image}}"  --}}
              alt="{{$product->image}}" />
            </a>
          </li>
          {{-- <li>
              <img class="pgallpic" src="{{$product->image}}" alt="{{$product->image}}" />
            </a>
          </li> --}}
          <li>
            <a href="javascript:void(0)">
              <img class="thumbnail-wrap" src="{{$product->image}}" alt="{{$product->image}}" />
            </a>
          </li>
        </ul>
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


<ul class="thumb">
  <li>
      <a href="javascript:void(0)">
          <div class="thumbnail-wrap" style="background-image:url(./images/1.jpg)"></div>
      </a>
  </li>
  <li>
      <a href="javascript:void(0)">
          <div class="thumbnail-wrap" style="background-image:url(./images/2.jpg)"></div>
      </a>
  </li>
  <li>
      <a href="javascript:void(0)">
          <div class="thumbnail-wrap" style="background-image:url(./images/3.jpg)"></div>
      </a>
  </li>
  <li>
      <a href="javascript:void(0)">
          <div class="thumbnail-wrap" style="background-image:url(./images/4.jpg)"></div>
      </a>
  </li>
  <li>
      <a href="javascript:void(0)">
          <div class="thumbnail-wrap" style="background-image:url(./images/5.jpg)"></div>
      </a>
  </li>
  <li>
      <a href="javascript:void(0)">
          <div class="thumbnail-wrap" style="background-image:url(./images/6.jpg)"></div>
      </a>
  </li>
  <li>
      <a href="javascript:void(0)">
          <div class="thumbnail-wrap" style="background-image:url(./images/7.jpg)"></div>
      </a>
  </li>
  <li>
      <a href="javascript:void(0)">
          <div class="thumbnail-wrap" style="background-image:url(https://images.unsplash.com/photo-1472457897821-70d3819a0e24?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8c21hbGx8ZW58MHx8MHx8fDA%3D)"></div>
      </a>
  </li>
  <li>
      <a href="javascript:void(0)">
          <div class="thumbnail-wrap" style="background-image:url(./images/9.jpg)"></div>
      </a>
  </li>
</ul>


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
