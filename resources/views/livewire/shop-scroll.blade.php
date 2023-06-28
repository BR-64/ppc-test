
    <div class="pccoll">
        <div wire:loading.delay.class="opacity-50" class="gridHL">

    @foreach($products as $product)
            <div @if ($loop->last) id="last_record" @endif>
                <div  x-data="productItem({{ json_encode([
                    'stock'=>$product->stock->stock ?? ''])
                    }})" >
                  <div class="card2 rel">
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
                    <div x-show="stock <= 0" class="oosbanner border border-t-0 border-red-400 rounded-b bg-red-100 text-red-700 opacity-75">
                        out of stock
                    </div>
                    <div x-show="{{$product->pre_order}} == 1"class="preorbanner bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 shadow-md opacity-100">
                        Pre-Order
                    </div>
                  </div>
                </div>
            </div>
    @endforeach


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
