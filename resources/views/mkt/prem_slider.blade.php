
    @php
    $mktSliders = App\Models\Banner::query()
        ->where('name', '=', 'prem banner')
        ->latest()->get();

        $imgs = count($mktSliders[0]['extra_image']);

    @endphp

<style>

</style>

  <!-- Swiper -->
  <div class="prem_banner">
      <div class="swiper mySwiper_prem">
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

