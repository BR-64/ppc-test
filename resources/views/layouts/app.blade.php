<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 

    {{-- <title>{{ config('app.name', 'Prempracha Ecommerce') }}</title> --}}

    <title>test 8 Prempracha Online Store </title>

    @vite([
        'resources/css/reset.css',
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/nav.js',
        'resources/css/style3.css'
        ])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />


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

        @media only screen and (min-width: 700px) { 
            .spacebox1 {
                margin-right:30%;
                }

            .shopcat{
                width:420px;
            }

            .pccoll{
                width: 800px;
                padding: 20px;
                align-items: center;
            }

            .mainHL{
                width: 800px;
                align-items: center;
            }

            .gridHLmain{
                margin-right: 30px;
            }

            .premmaincoll{
                width: 800px;
                display:grid;
                grid-template-columns: repeat(auto-fill, 370px);
                gap: 10px;
                margin: auto;
            }

            .shopcoll{
                width:800px;
                align-items: center;
                padding: 10px;
            }

            .SCpic {
                margin: auto;
                object-fit: cover;
                min-height:300px;
            }

            .gridShopcoll{
                grid-template-columns: repeat(auto-fill, 180px);
                gap: 10px;
                align-items: center;
                width:100%;
                padding: 10px;
            }

        }

    </style>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://kit.fontawesome.com/071439a03d.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

    <script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script  src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>

    <script src="sweetalert2.all.min.js"></script>

    @livewireStyles

</head>

<body>
@include('sweetalert::alert')

@include('layouts.navigation')

    <main >
        {{ $slot }}
    </main>

<!-- Toast -->
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
    </div>


@include('layouts.footer')

<!--/ Toast -->
@livewireScripts


</body>


</html>
