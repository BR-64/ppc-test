<style>
.mySwiper_hl{
  height: 500px;
  width: 100%;
  height: 30vh;
  /* width: 363px; */
  margin-top: 5px;
  }

</style>

<div class="swiper mySwiper_hl">
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

<script>
    var swiper = new Swiper(".mySwiper_hl", {
      pagination: {
        el: ".swiper-pagination",
      },
    });
  </script>