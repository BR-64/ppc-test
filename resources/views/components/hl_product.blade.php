
<div class="mainHL">
        <h4 class="deco">Highlight</h4>
        <div class="swiper mySwiper_hl">
            <div class="swiper-wrapper">
                <div class="gridHLmain ">
                    @foreach($hlproducts as $key => $hlproduct)
                        @if($key<4)
                    <div class="hlitem">
                        <a href="{{ route('product.view', $hlproduct->item_code) }}"
                            class=" block overflow-hidden">
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
                <div class="gridHLmain ">
                    @foreach($hlproducts as $key => $hlproduct)
                        @if($key>=4 && $key <8)
                        <div class="hlitem">
                            <a href="{{ route('product.view', $hlproduct->item_code) }}"
                            class=" block overflow-hidden">
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
                <div class="gridHLmain ">
                    @foreach($hlproducts as $key => $hlproduct)
                    @if($key>=8 && $key <12)
                    <div class="hlitem">
                        <a href="{{ route('product.view', $hlproduct->item_code) }}"
                            class=" block overflow-hidden">
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
                <div class="gridHLmain ">
                    @foreach($hlproducts as $key => $hlproduct)
                    @if($key>=12 && $key <16)
                    <div class="hlitem">
                        <a href="{{ route('product.view', $hlproduct->item_code) }}"
                            class=" block overflow-hidden">
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
                <div class="gridHLmain ">
                    @foreach($hlproducts as $key => $hlproduct)
                    @if($key>=16 && $key <20)
                    <div class="hlitem">
                        <a href="{{ route('product.view', $hlproduct->item_code) }}"
                            class=" block overflow-hidden">
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
                <div class="gridHLmain ">
                    @foreach($hlproducts as $key => $hlproduct)
                    @if($key>=20 && $key <24)
                    <div class="hlitem">
                        <a href="{{ route('product.view', $hlproduct->item_code) }}"
                            class=" block overflow-hidden">
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

        <div class="gridHLmain swiper-slide"></div>
        <div class="gridHLmain swiper-slide"></div>
        <div class="gridHLmain swiper-slide"></div>
    </div>
    <div class="swiper-pagination"></div>
    </div>
</div>

<script>
    var swiper = new Swiper(".mySwiper_hl", {
      pagination: {
        el: ".swiper-pagination",
      },
    });
</script>