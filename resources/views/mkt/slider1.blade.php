
    @php
    $mktSliders = App\Models\Portfolio::query()
        ->where('type', '=', 'main_banner')
        // ->where('type', '=', 'prem_coll_cover')
        ->latest()->get();
    @endphp

  <!-- Swiper -->
  <div class="banner">
      <div class="swiper mySwiper">
          <div class="swiper-wrapper">
              @foreach($mktSliders as $slide)
              <div class="swiper-slide"><img src="{{$slide->portfolio_image}}" alt=""></div>
              {{-- <div class="swiper-slide"><p>{{$slide->portfolio_title}}</p></div> --}}
              
              @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

      <!-- Swiper JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
      pagination: {
        el: ".swiper-pagination",
      },
    });
  </script> --}}


