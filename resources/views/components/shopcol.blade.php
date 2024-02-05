<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
  /* html,
  body {
    position: relative;
    height: 100%;
  } */

  /* body {
    background: #eee;
    font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
    font-size: 14px;
    color: #000;
    margin: 0;
    padding: 0;
  } */

  .swiper {
    width: 100%;
    height: 100%;
  }

  .swiper-slide {
    text-align: center;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    /* width: 180px; */
  }

  /* .swiper-slide-next {
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 180px;
  } */

  .swiper-slide img {
    display: block;
    /* width: 100%;
    height: 100%; */
    /* object-fit: cover; */
  }
</style>

<div class="shopcoll_main">
    <a href="{{ route('product.collection') }}"><h4 class="deco">Shop by Collection</h4></a>

    <div class="swiper mySwiper_col">
      <div class="swiper-wrapper">
        @foreach($collections as $product)
          <div class="swiper-slide">
              <a href="{{ route('product.collection.view', $product->collection_name) }}" class="">
                  <img
                      src="{{ asset ('/storage/'.$product->image) }}"
                      alt="{{ $product->collection_name }}"
                      class="SCpic"
                  />
              </a>
          </div>
      @endforeach
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>

  {{-- <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script> --}}

  <script>
    var swiper = new Swiper(".mySwiper_col", {
      slidesPerView: 4,
      spaceBetween: 30,
      freeMode: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });
  </script>

