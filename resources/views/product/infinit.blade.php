@vite([
    'resources/css/reset.css',
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/css/style3.css'
    ])

{{-- <script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script  src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script> --}}


<body>
    
    <x-app-layout>

    {{-- <x-shop> --}}
        
        <?php if ($products->count() === 0): ?>
        <div class="text-center text-gray-600 py-16 text-xl">
            There are no products published
        </div>
        <?php else: ?>
        
        <br>
        
        <h1>infinit test</h1>
        <div class="pccoll">
            <div class="gridHL">
                <div class="scrolling-pagination">
                    @foreach($products as $product)
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
                        @endforeach
                        {{$products->links()}}
                </div>
            
            </div>
        </div>
    
    <?php endif; ?>
    <div class="footspace"></div>
    {{-- </x-shop> --}}
</body>

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

</x-app-layout>
