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
<style>
    * {box-sizing: border-box;}
    
    .img-zoom-container {
      position: relative;
    }
    
    .img-zoom-lens {
      position: absolute;
      border: 1px solid #d4d4d4;
      /*set the size of the lens:*/
      width: 80px;
      height: 80px;
    }
    
    .img-zoom-result {
      border: 1px solid #d4d4d4;
      /*set the size of the result div:*/
      width: 300px;
      height: 300px;
    }
</style>

<script>
  function imageZoom(imgID, resultID) {
    var img, lens, result, cx, cy;
    img = document.getElementById(imgID);
    result = document.getElementById(resultID);
    /*create lens:*/
    lens = document.createElement("DIV");
    lens.setAttribute("class", "img-zoom-lens");
    /*insert lens:*/
    img.parentElement.insertBefore(lens, img);
    /*calculate the ratio between result DIV and lens:*/
    cx = result.offsetWidth / lens.offsetWidth;
    cy = result.offsetHeight / lens.offsetHeight;
    /*set background properties for the result DIV:*/
    result.style.backgroundImage = "url('" + img.src + "')";
    result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
    /*execute a function when someone moves the cursor over the image, or the lens:*/
    lens.addEventListener("mousemove", moveLens);
    img.addEventListener("mousemove", moveLens);
    /*and also for touch screens:*/
    lens.addEventListener("touchmove", moveLens);
    img.addEventListener("touchmove", moveLens);
    function moveLens(e) {
      var pos, x, y;
      /*prevent any other actions that may occur when moving over the image:*/
      e.preventDefault();
      /*get the cursor's x and y positions:*/
      pos = getCursorPos(e);
      /*calculate the position of the lens:*/
      x = pos.x - (lens.offsetWidth / 2);
      y = pos.y - (lens.offsetHeight / 2);
      /*prevent the lens from being positioned outside the image:*/
      if (x > img.width - lens.offsetWidth) {x = img.width - lens.offsetWidth;}
      if (x < 0) {x = 0;}
      if (y > img.height - lens.offsetHeight) {y = img.height - lens.offsetHeight;}
      if (y < 0) {y = 0;}
      /*set the position of the lens:*/
      lens.style.left = x + "px";
      lens.style.top = y + "px";
      /*display what the lens "sees":*/
      result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
    }
    function getCursorPos(e) {
      var a, x = 0, y = 0;
      e = e || window.event;
      /*get the x and y positions of the image:*/
      a = img.getBoundingClientRect();
      /*calculate the cursor's x and y coordinates, relative to the image:*/
      x = e.pageX - a.left;
      y = e.pageY - a.top;
      /*consider any page scrolling:*/
      x = x - window.pageXOffset;
      y = y - window.pageYOffset;
      return {x : x, y : y};
    }
  }
</script>


    
<div class='gallerycol'>
  <h3 class="pprice mobileshow" style="color: #32322f">THB {{number_format($product->retail_price)}}</h3>
  <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff;" class="swiper mySwiper2 ml-0">
    {{-- <div class="img-zoom-container"> --}}
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img id="myimage" class="pgallpic" src="{{$product->image}}" alt="{{$product->image}}" />
        </div>
          @foreach($gallery as $gal)
            @for ($i =0; $i < $imgs; $i++)
              <div class="swiper-slide">
                {{-- <img id="myimage" class="pgallpic" src="{{asset ('/storage/'.$gal->webimage[$i])}}" /> --}}
                <img class="pgallpic" src="{{$product->image}}" alt="{{$product->image}}" />
              </div>
              
            @endfor     
          @endforeach
        </div>
        {{-- </div> --}}
   
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
    </div>

<div id="myresult" class="img-zoom-result"></div>



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


{{-- <div class="img-zoom-container">
  <img id="myimage" src="{{$product->image}}" width="300" height="240">
  <div id="myresult" class="img-zoom-result"></div>
</div> --}}


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


<script>
  // Initiate zoom effect:
  imageZoom("myimage", "myresult");
</script>
