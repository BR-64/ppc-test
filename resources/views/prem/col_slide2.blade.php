
<body>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

  
      @php
          $mktSliders = App\Models\pCollection::query()
        ->where('collection_name', '=', $product->collection_name)
        // ->where('collection_name', '=', 'bijan')
        ->latest()->get();

        $imgs = count($mktSliders[0]['coll_image']);

      @endphp
      <style>
          /* .swiper-slide {
              text-align: center;
              font-size: 18px;
              background: #fff;
              display: flex;
              justify-content: center;
              align-items: center;
              object-fit: cover;


            } */
      </style>
  
  <!-- Swiper -->
        <div class="premcol_cover swiper mySwiper_prem">
            <div class="swiper-wrapper">
                @foreach($mktSliders as $slide)
                  <div class="swiper-slide">
                    <img class='slideprem2' src="{{asset ('/storage/'.$slide->image)}}" alt="">
                  </div>
                  @for ($i =0; $i < $imgs; $i++)
                    <div class="swiper-slide">
                      <img 
                      src="{{asset ('/storage/'.$slide->coll_image[$i])}}" alt="" class="slideprem2">
                    </div>
                  @endfor

            </div>
                @endforeach
              <div class="swiper-pagination"></div>
            </div>

            {{-- <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div> --}}

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper_prem", {
      pagination: {
        el: ".swiper-pagination",
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>      
</body>

  


  
  