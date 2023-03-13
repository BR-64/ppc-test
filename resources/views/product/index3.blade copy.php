@vite(['resources/css/style3.css', 'resources/js/app.js'])

<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
?>

<x-app-layout>

{{-- products --}}
    <?php if ($products->count() === 0): ?>
        <div class="text-center text-gray-600 py-16 text-xl">
            There are no products published
        </div>
    <?php else: ?>
    
    {{-- <h2 class="pagehead">Shop All</h2> --}}
    <br>

    <div class="container">
        <h2 class="text-center">Laravel infinite scroll pagination</h2>   
        <br/>
        <div class="col-md-12" id="post-data"> 
            @include('data')
        </div>   
    </div>
    <div class="ajax-load text-center" style="display:none">
        <p>Loading More products</p>
    </div>



{{-- Infinit scroll     --}}
    <script type="text/javascript">

        var page = 1;
    
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        });
    
    
        function loadMoreData(page){
          $.ajax(
                {
                    url: '?page=' + page,
                    type: "get",
                    beforeSend: function()
                    {
                        $('.ajax-load').show();
                    }
                })
    
                .done(function(data)
                {
                    if(data.html == " "){
                        $('.ajax-load').html("No more records found");
                        return;
                    }
    
                    $('.ajax-load').hide();
                    $("#post-data").append(data.html);
    
                })
    
                .fail(function(jqXHR, ajaxOptions, thrownError)
                {
                      alert('server not responding...');
                });
    
        }
    
        </script>
    <?php endif; ?>

    <div class="footspace"></div>
</x-app-layout>
