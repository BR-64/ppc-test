<head>
  <!-- Link Swiper's CSS -->
  {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" /> --}}

  <!-- Demo styles -->
</head>


    @php
    $mktSliders = App\Models\Portfolio::query()
        // ->where('type', '=', 'main_banner')
        ->where('type', '=', 'prem_coll_cover')
        ->latest()->get();
    @endphp

<style>

</style>

  <!-- Swiper -->
  <div class="prem_banner">
      <div class="swiper mySwiper_prem">
          <div class="swiper-wrapper">
              @foreach($mktSliders as $slide)
              <div class="swiper-slide"><img src="{{$slide->portfolio_image}}" alt=""></div>
              
              @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
  </div>

  <script>
    var swiper = new Swiper(".mySwiper_prem", {
      pagination: {
        el: ".swiper-pagination",
      },
    });
  </script>

