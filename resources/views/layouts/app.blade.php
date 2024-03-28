<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    

<!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6YD7XP8E8D"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-6YD7XP8E8D');
    </script>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 


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