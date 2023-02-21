
<div class="mainHL swiper mySwiper">
    <div class="swiper-wrapper">


    <div class="gridHLmain swiper-slide">
        @foreach($hlproducts as $key => $hlproduct)
            @if($key<4)
            <div
            class="border border-1 border-gray-200 rounded-md hover:border-purple-600 transition-colors bg-white"
            >
                <a href="{{ route('product.view', $hlproduct->item_code) }}"
                    class="aspect-w-3 aspect-h-2 block overflow-hidden">
                    <img
                    src="{{ $hlproduct->image }}"
                    alt="{{ $hlproduct->item_code }}"
                    class="HLpic"
                    />
                </a>
            </div>
            @endif
        @endforeach
    </div>

    <div class="gridHLmain swiper-slide">
        @foreach($hlproducts as $key => $hlproduct)
        @if($key>=4 && $key <8)
            <div
            class="border border-1 border-gray-200 rounded-md hover:border-purple-600 transition-colors"
            >
                <a href="{{ route('product.view', $hlproduct->item_code) }}"
                    class="aspect-w-3 aspect-h-2 block overflow-hidden">
                    <img
                    src="{{ $hlproduct->image }}"
                    alt="{{ $hlproduct->item_code }}"
                    class="HLpic"
                    />
                </a>
            </div>
            @endif
        @endforeach
    </div>

    <div class="gridHLmain swiper-slide">
        @foreach($hlproducts as $key => $hlproduct)
        @if($key>=8 && $key <12)
            <div
            class="border border-1 border-gray-200 rounded-md hover:border-purple-600 transition-colors bg-white"
            >
                <a href="{{ route('product.view', $hlproduct->item_code) }}"
                    class="aspect-w-3 aspect-h-2 block overflow-hidden">
                    <img
                    src="{{ $hlproduct->image }}"
                    alt="{{ $hlproduct->item_code }}"
                    class="HLpic"
                    />
                </a>
            </div>
            @endif
        @endforeach
    </div>

    </div>
    <div class="swiper-pagination"></div>

</div>

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
      pagination: {
        el: ".swiper-pagination",
      },
    });
  </script>      
</body>