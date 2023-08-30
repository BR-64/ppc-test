<html>
    <head>
        <meta charset="UTF-8">
        <title>Shipping Label</title>
    
        <style type="text/css">
        @page {
    size: a4 landscape;
  }
            /* @page {
                margin: 0px;
            } */
            body {
                margin: 0px;
                font-size:22pt;
            }
            * {
                font-family: Verdana, Arial, sans-serif;
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
                font-weight: bold;
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


        </style>
    </head>
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
                 <td class='right'>Box No. x / x</td>
            </tr>
            <tr>
                <td></td>
                <td class="mid">
                    <br>{{$order->customer->shippingAddress['address1']}},
                    {{$order->customer->shippingAddress['address2']}}
                    <br>{{$order->customer->shippingAddress['city']}}, {{$order->customer->shippingAddress['zipcode']}},
                    {{$order->customer->shippingAddress['country_code']}}
                    <br>Tel : {{$order->customer['phone']}}
                </td>
                <td></td>
            </tr>
        </table>
        
    </div>
    <br>
    <br>
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
            </tr>
        </table>
    </div>

</html>