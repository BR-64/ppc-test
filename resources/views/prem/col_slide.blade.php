
<body>
  
      @php
          $mktSliders = App\Models\Portfolio::query()
        ->where('name2', '=', $product->collection_name)
        ->latest()->get();
      @endphp
  
  <!-- Swiper -->
        <div class="premcol_cover swiper mySwiper_prem">
            <div class="swiper-wrapper">
                @foreach($mktSliders as $slide)
                <div class="swiper-slide"><img src="{{$slide->portfolio_image}}" alt=""></div>
                
                @endforeach
              </div>
              <div class="swiper-pagination"></div>
          </div>

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper_prem", {
      pagination: {
        el: ".swiper-pagination",
      },
    });
  </script>      
</body>

  


  
  