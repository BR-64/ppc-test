<html>
    <head>
        {{-- <meta http-equiv="Content-Language" content="th" /> --}}
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        {{-- <meta http-equiv="Content-Type" content="text/html; charset=windows-874"> --}}
        {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&family=Nunito:ital,wght@0,200;1,200&family=Open+Sans:wght@300&display=swap" rel="stylesheet"> --}}
        {{-- <link href="https://fonts.googleapis.com/css2?family=Tangerine&display=swap" rel="stylesheet" />
        {{-- <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Thai:wght@300&display=swap" rel="stylesheet"> --}}
        {{-- <link href="https://fonts.googleapis.com/css2?family=Tinos:ital@1&display=swap" rel="stylesheet">  --}}
        {{-- <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Thai&display=swap" rel="stylesheet">  --}}

        <title>Shipping Label</title>

        <style type="text/css">
            @page {
                size: a4 landscape;
            }
            /* @font-face {
                font-family: "noto sans thai";
                font-style: normal;
                font-weight: normal;
                src: url('storage/fonts/noto_sans_thai_normal_be0dac6beb348956ae77235fa2f48dd1.ttf') format('truetype');
                } */
            /* @font-face {
                font-family: 'NotoSansThai';
                src: url('{{public_path()}}/font/NotoSansThai.ttf') format('truetype') 
                src: url('http://localhost:8000/storage/font/NotoSansThai.ttf') format('truetype') 
                src: url('{{asset ('/storage/font/NotoSansThai.ttf')}}') format('truetype') 
                    } */

            body {
                margin: 0px;
                font-size:22pt;
                /* font-family: 'Noto Serif Thai'; */

                /* font-family: 'NotoSansThai', sans-serif;  */
                /* font-family: 'noto sans thai'; */
                /* font-family: 'DejaVuSans'; */
                /* font-family: 'Tangerine'; */
                /* font-family: Impact; */
                /* font-family: sans-serif; */
                /* src: url({{ storage_path('fonts\NotoSansThai-VariableFont_wdth,wght.ttf') }}) format("truetype"); */


            }
            * {
                /* font-family: 'noto sans thai'; */

                /* font-family: Verdana, Arial, sans-serif; */
                /* font-family: garuda; */
            }
            a {
                color: #fff;
                text-decoration: none;
            }
            tr td {
                vertical-align: top;
                text-align: left;

            }

            .to{
                /* font-weight: bold; */
                /* font-family: 'noto serif thai'; */
                font-family: 'noto sans thai';
                max-height: 50%;
                font-size:22pt;

                /* font-family: 'DejaVu Sans'; */
                /* font-family: 'Tinos', serif; */


            }
            .from{
                /* font-family: 'noto sans thai'; */
                font-family: sans-serif;
                font-size:16pt;
                /* font-weight: bold; */
                /* font-family: 'Tangerine'; */
                /* font-family: 'Tinos', serif;
                font-family: 'rubik doodle triangles', serif; */


            }

            .left{
                width:100pt;
            }

            .mid{
                width:470pt;

            }

            .right{
                text-align: right;
            }

            .caps{
                text-transform: uppercase;
            }

            .box{
                font-size: 24pt;
            }


        </style>
    </head>
{{-- /// loop mail --}}
<body>
    
@for ($i =0; $i < $boxcount; $i++)
    <div class="from">
        <table>
            <tr>
                <td class='left'>From :</td>
                <td class='mid'>PREMPRACHA’S COLLECTION CO., LTD.
                    <br>224 M.3 CHIANGMAI-San Kamphaeng RD.,
                    <br>T.TONPAO A.San Kamphaeng
                    <br>CHIANGMAI THAILAND 50130 
                    <br>TEL: 053-338540, 053-338857 
                    <br>E-mail: showroom@prempracha.com
                </td>
                <td class='right box'>Box No. {{$i+1}} / {{$boxcount}}</td>
            </tr>
        </table>
    </div>
    <br>
    <br>
    <div class="to">
        <table>
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td class='left'>To :</td>
                <td class='mid caps'>{{$order->customer['first_name']}}  {{$order->customer['last_name']}}
                    <br>{{$order->customer['customer_name']}}
                 </td>
            </tr>
            <tr>
                <td></td>
                <td class="mid">
                    {{$order->customer->Ship_address['address1']}},{{$order->customer->Ship_address['address2']}}
                    <br>{{$order->customer->Ship_address['city']}}, {{$order->customer->Ship_address['zipcode']}}, {{$order->customer->Ship_address['country_code']}}
                    <br>Tel : {{$order->customer['phone']}}
                </td>
                <td></td>
            </tr>
        </table>
        
    </div>


    
    @if ($i+1 < $boxcount)
        <div style="page-break-before: always;"></div>      
    @endif

@endfor 
</body>


</html>