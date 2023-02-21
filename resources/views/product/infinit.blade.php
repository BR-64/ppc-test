@vite(['resources/css/style3.css', 'resources/js/app.js'])

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>


<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
?>

{{-- <x-shop> --}}

{{-- products --}}
    <?php if ($products->count() === 0): ?>
        <div class="text-center text-gray-600 py-16 text-xl">
            There are no products published
        </div>
    <?php else: ?>
    
    {{-- <h2 class="pagehead">Shop All</h2> --}}
    <br>
<body>
    
    <div class="pccoll">
        <div class="scrolling-pagination">
        <div class="gridHL">
<!-- Product Item -->
            @foreach($products as $product)
                <div>
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
            {{-- {{$products->links()}} --}}
            </div>

      </div>
    </div>

    <?php endif; ?>
    <div class="footspace"></div>
{{-- </x-shop> --}}

<script type="text/javascript">
    $('ul.pagination').hide();
    $(function() {
        $('.scrolling-pagination').jscroll({
            autoTrigger: true,
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.scrolling-pagination',
            callback: function() {
                $('ul.pagination').remove();
            }
        });
    });
</script>
    </body>
