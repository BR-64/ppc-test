<html>
    <head>
        <meta charset="UTF-8">
        <title>OrderInfo</title>
    
        <style type="text/css">

            body {
                margin: 0px;
            }
            * {
                font-family: Verdana, Arial, sans-serif;
            }
            a {
                color: #fff;
                text-decoration: none;
            }
            th tr td {
                vertical-align: top;
                text-align: left;

            }

            .orderinfo{
                vertical-align: top;
                text-align: left;
                border-style: solid;
            }

            tr > :first-child{
                width: 120pt;
                font-weight: bold;
            }

            .unit{
                text-align:center;
                text-decoration: underline;
                font-size:0.8rem;
                line-height: 1.6;
            }

            .items{
                vertical-align: top;
                text-align: left;
                border-style: solid;
                border-collapse: collapse;
            }

            /* .items > tr >td{
                text-align:right;
                color:aqua;
            } */

            .item{
                border-bottom: 1px solid black;
            }

            .end{
                padding-right: 10pt;
                /* color:green; */
            }

            .code{
                width:130pt;

            }

            .price{
                text-align:right;
                /* color:aqua; */
                width:80pt;

            }

            .qty{
                width:100pt;
                text-align:center
            }

            .summarysec{
                /* float: right; */
            }

            .summary{
                /* border-bottom: 3px solid red; */
            }

            .summary >td:first-of-type {
                width:140pt;
                /* color:aqua; */
            }
            .summary >tr {
                text-align:right;
                /* color:aqua; */
            }

            .shipping > :first-child{
                vertical-align: top;
            }

            .right{text-align:right;}

            tfoot > tr> td{
                margin: 30px 0 0 0;
                color:green;
            }
        </style>
    </head>
<body>
    
        <h1>Order Info # {{$order->id}}</h1>
        <table class='orderinfo'>
            <tr>
                <td>Order ID</td>
                <td>: {{$order->id}}</td>
            </tr>
            <tr>
                <td>Order Status</td>
                <td>: {{ $order->status }}</td>
            </tr>
            <tr>
                <td>Order Price (THB)</td>
                <td>: {{number_format($order->total_price,2)}}</td>
            </tr>
            <tr>
                <td>Order Date</td>
                <td>: {{$order->created_at}}</td>
            </tr>
        </table>
    <br>
        <table class='items'>
            <tr class='item'>
                <th>Image</th>
                <th>Code</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Amount</th>
            </tr>
            <tr class='unit'>
                <td></td>
                <td></td>
                <td>PCS.</td>
                <td>BAHT</td>
                <td>BAHT</td>
            </tr>
            @foreach($order->items as $item)
                <tr class='item'>
                    <td>
                        <img src="{{$item->product->image}}" style="width: 100px">
                    </td>
                    <td class='code'>{{$item->product->item_code}}</td>
                    <td class='qty'>{{$item->quantity}}</td>
                    <td class='price'>{{number_format($item->unit_price,2)}}</td>
                    <td class='price end'>{{number_format($item->unit_price * $item->quantity,2)}}</td>
                </tr>
            @endforeach
            <tfoot class='right'>
                <tr>
                    <td  colspan="4">subtotal :</td><td>{{number_format($order['total_price'],2)}}</td>
                </tr>
                <tr><td colspan="4">shipping fee :</td><td>{{$order['shipping']}}</td></tr>
                <tr><td colspan="4">insurance :</td><td>{{$order['insurance']}}</td></tr>
                <tr>
                    <td colspan="4">Total Payment (THB) :</td>
                    <td>{{number_format(($order['total_price']+$order['insurance']+$order['shipping']),2)}}</td>
                </tr>    
            </tfoot>
        </table>
    <br>
    {{-- <div class="summarysec">
        <table class='summary'>
            <tr>
                <td>subtotal :</td><td>{{$order['total_price']}}</td>
            </tr>
            <tr><td>shipping fee :</td><td>{{$order['shipping']}}</td></tr>
            <tr><td>insurance :</td><td>{{$order['insurance']}}</td></tr>
            <tr>
                <td>Total Payment (THB) :</td>
                <td>{{number_format(($order['total_price']+$order['insurance']+$order['shipping']),2)}}</td>
            </tr>            
        </table>
    </div> --}}
        </br>
        <table class='shipping'>
            <tr>
                <td>Shipping Address:</td>
                <td>
                    {{$order->customer['first_name']}}
                    {{$order->customer['last_name']}}
                <br>{{$order->customer->Ship_Address['address1']}},
                <br>{{$order->customer->Ship_Address['address2']}}
                <br>{{$order->customer->Ship_Address['city']}}, {{$order->customer->Ship_Address['country_code']}}
                <br>{{$order->customer->Ship_Address['zipcode']}}
                </td>
            </tr>
            <tr><td>Shipping Method:</td><td>{{$order['ship_method']}}</td></tr>
            <tr><td>Payment Method:</td><td>{{$order['pay_method']}}</td></tr>
        </table>
    </body>
</html>