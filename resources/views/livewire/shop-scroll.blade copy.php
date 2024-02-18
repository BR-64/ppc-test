<div>

    <div class="pccoll">
        <div wire:loading.delay.class="opacity-50" class="gridHL">
    @foreach($products as $product)
              <div @if ($loop->last) id="last_record" @endif>
                  <div class="card2">
                      <a href="{{ route('product.view', $product->item_code) }}"
                        class="">
                          <img
                              src="{{ $product->image }}"
                              alt=""
                              class="pimage hover:scale-105 hover:rotate-1 transition-transform"
                          />
                      </a>
                      <div>
                          <h5 class="text2 undertext">THB {{number_format($product->retail_price)}}</h5>
                      </div>
                  </div>
              </div>
    @endforeach
    </div>


    @if ($loadAmount >= $totalRecords)
          <p class="">- No Remaining Products -</p>
    @endif
    
    <script>
        const lastRecord = document.getElementById('last_record');
        const options = {
            root: null,
            threshold: 1,
            rootMargin: '0px'
        }
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    @this.loadMore()
                }
            });
        });
        observer.observe(lastRecord);
    </script>
</div>
