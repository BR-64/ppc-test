<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
    {{-- <meta name="viewport" content="width=device-width, initial-scale=.5, maximum-scale=12.0, minimum-scale=.25, user-scalable=yes"/> --}}

    {{-- <title>{{ config('app.name', 'Prempracha Ecommerce') }}</title> --}}

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">

    <title>Prempracha Online Store</title>

    @vite([
        'resources/css/reset.css',
        'resources/css/app.css',
        'resources/css/style3.css',
        'resources/css/queries.css',
        'resources/css/loader.css',
        'resources/js/app.js',
        'resources/js/nav.js',
        // 'resources/js/cookieconsent-config.js',
        ])

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" /> --}}


    <!-- Scripts -->
    <style>
        [x-cloak] {
            display: none !important;
        }

         @import url('https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;1,200&family=Open+Sans:wght@300&display=swap');

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }

        /* @media only screen (min-width: 700px) { 
            .pccoll{
                    width: 70%;
                    }

            .pccollc{
                    width: 70%;
                    }
        
        } */

    </style>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://kit.fontawesome.com/071439a03d.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

    <script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script  src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>
<script  src="https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v3.0.0/dist/cookieconsent.umd.js"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    </script>
    

    @livewireStyles

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v3.0.0/dist/cookieconsent.css">

</head>

<body>
@include('layouts.navigation')

    <main>

        {{ $slot }}
    </main>

{{-- <!-- Toast -->
    <div
        x-data="toast"
        x-show="visible"
        x-transition
        x-cloak
        @notify.window="show($event.detail.message)"
        class="front fixed w-[400px] left-1/2 -ml-[200px] top-16 py-2 px-4 pb-4 bg-emerald-500 text-white"
    >
        <div class="font-semibold" x-text="message"></div>
        <button
            @click="close"
            class="absolute flex items-center justify-center right-2 top-2 w-[30px] h-[30px] rounded-full hover:bg-black/10 transition-colors"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </button>
        <!-- Progress -->
        <div>
            <div
                class="absolute left-0 bottom-0 right-0 h-[6px] bg-black/10"
                :style="{'width': `${percent}%`}"
            ></div>
        </div>
    </div> --}}


@include('layouts.footer')

<!--/ Toast -->
@livewireScripts

<script>
    // $('#loader').show()
    $(function() {
        $( "form" ).submit(function() {
            $('#loader').show();
        });
    });

</script>
<script type="module" src="cookieconsent-config.js"></script>

</body>


</html>