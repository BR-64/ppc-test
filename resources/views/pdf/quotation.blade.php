<html>
    <head>
        <meta charset="UTF-8">
        <title>Quotation</title>
    
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

            .unit{
                text-align:center;
                text-decoration: underline;
                font-size:0.8rem;
                line-height: 1.6;
            }

            .orderinfo{
                vertical-align: top;
                text-align: left;
            }

            .header{
                text-align: center;
                line-height: 1rem;
                font-size:0.7rem;
                font-style: bold;
            }

            .midhead{
                text-transform: uppercase;
                text-align: center;
            }

            .quotationinfo{
                font-size: 0.8rem;
            }

            tr >:first-child{
                font-weight: bold;

            }
            .col2{
                width:280pt;
            }

            .address{
                vertical-align: top;
            }

            th{
                text-transform: uppercase;
            }

            .items{
                width:100%;
                font-size: 0.8rem;
                border: 1.5px solid black;
                border-collapse: collapse;
                /* padding: 0.3rem; */
            }

            items td + td { border-left:2px solid red; }

            .items td {
                border-left: 1px solid #c3c3c3;
                border-right: 1px solid #c3c3c3;
                padding: 0.3rem;
            }

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
                padding-right: 10pt;

                /* color:aqua; */
                /* width:80pt; */

            }

            .qty{
                /* width:100pt; */
                text-align:center
            }

            tfoot{
                text-transform:uppercase;
            }

            .right{text-align:right;}

            .total{
                text-decoration: underline;
                /* text-decoration-style: double; */
            }

            .discountRate{
                font-size: 0.7rem;
                text-align: center;

            }

            .bankDetails{
                font-size: 0.85rem;
            }

            .note{
                font-size: 0.7rem;
            }


        </style>
    </head>
<body>
    <div class="header">
        PREMPRACHA'S COLLECTION CO., LTD. 
        <br>224 M. 3 CHIANGMAI-San Kamphaeng RD., T.TONPAO A.San Kamphaeng, CHIANGMAI 50130 THAILAND. 
        <br>TEL: (66 53) 338540, 338857 FAX: (66 53) 338540 E-Mail : showroom@prempracha.com
    </div>
        <h3 class="midhead">Quotation</h3>

        <table class="quotationinfo">
            <tr>
                <td >Quotation :</td>
                <td class='col2'>Quotation Number</td>
                <td >Date:</td>
                <td>{{ date_format($order->updated_at,"F d, Y") }}</td>
            </tr>
            <tr>
                <td>Customer name :</td>
                <td>{{$order->customer['customer_name']}}</td>
            </tr>
            <tr>
                <td class='address'>Address :</td>
                <td>{{$order->customer->shippingAddress['address1']}},
                    <br>{{$order->customer->shippingAddress['address2']}}
                    <br>{{$order->customer->shippingAddress['city']}}, {{$order->customer->shippingAddress['country_code']}}
                    <br>{{$order->customer->shippingAddress['zipcode']}}</td>
            </tr>
            <tr>
                <td>Delivery :</td>
                <td></td>
            </tr>
            <tr>
                <td>Term Of Payment :</td>
                <td></td>
            </tr>
            <tr>
                <td>Note :</td>
                <td></td>
            </tr>
        </table>
        <br>

        <table class='items'>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Picture</th>
                    <th>marks & nos</th>
                    <th>description</th>
                    <th>size</th>
                    <th>Quantity</th>
                    <th>unit price</th>
                    <th>amount</th>
                </tr>
            </thead>
            <tr class='unit'>
                <td></td>
                <td></td>
                <td>CODE</td>
                <td></td>
                <td>WLH CM.</td>
                <td>PCS.</td>
                <td>BAHT</td>
                <td>BAHT</td>
            </tr>
            @foreach($order->items as $item)
                <tr>
                    <td></td>
                    <td>
                        <img src="{{$item->product->image}}" style="width: 100px">
                    </td>
                    <td>{{$item->product->item_code}}</td>
                    <td>{{$item->product->type}}</td>
                    <td>{{$item->product->wlh}}</td>
                    <td class='qty'>{{$item->quantity}}</td>
                    <td class='price'>{{$item->unit_price}}</td>
                    <td class='price end'>{{number_format($item->unit_price * $item->quantity,2)}}</td>
                </tr>
            @endforeach
            <tfoot class='right'>
                <tr>
                    <td colspan="4">total</td>
                    <td></td>
                    <td class='qty total'>{{$qty}}</td>
                    <td></td>
                    <td class='price'>{{number_format($order['total_price'],2)}}</td>
                </tr>
                <tr>

                    <td colspan="4">discount</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4">total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class='total'>{{number_format($order['total_price'],2)}}</td>
                </tr>
            </tfoot>
            <tr >
                <td class='header' colspan="4">
                    Discount rate 
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class='discountRate'colspan="4">
                    BAHT 10,000 UP DISC. 10%
                    <br>BAHT 30,000 UP DISC. 15%
                    <br>BAHT 50,000 UP DISC. 20%
                    <br>BAHT 70,000 UP DISC. 25%
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr >
                <td class='header' colspan="4">
                    BANK ACCOUNT DETAIL:
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class='discountRate'colspan="4">
                    BANGKOK BANK PUBLIC COMPANY LIMITED
                    <br>96,M.7 A.SAN KAMPHAENG CHIANGMAI THAILAND
                    <br>SWIFT CODE: BKKBTHBK
                    <br>ACC.NO. 357-3-01302-0
                    <br>ACC.NAME PREMPRACHA'S COLLECTION CO.,LTD
                    <br>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td><br></td>
            </tr>
            <tr >
                <td></td>
                <td class='left note' colspan="7">
                    <u>NOTES:</u> HANDLING CHARGES AND CUSTOMS DUTIES AT DESTINATION ARE NOT INCLUDED
                    <br>YOUR ORDER MAY BE SUBJECT TO ADDITIONAL TAXES, CUSTOMS FEES AND IMPORT DUTIES AT TIME OF DELIVERY.
                    <br>THESE CHARGES ARE SEPARATE FROM YOUR SHIPPING CHARGE, AND YOU WILL BE BILLED DIRECTLY FOR THEM BY 
                    <br>YOUR LOCAL CUSTOMS OFFICE.
                </td>
            </tr>
        </table>
        </br>
        </br>
        {{-- <table class="discountRate">
            <tr>
                <td class='header'>
                    Discount rate 
                </td>
            </tr>
            <tr>
                <td>
                    BAHT 10,000 UP DISC. 10%
                    <br>BAHT 30,000 UP DISC. 15%
                    <br>BAHT 50,000 UP DISC. 20%
                    <br>BAHT 70,000 UP DISC. 25%
                </td>
            </tr>
        </table>
        <br>
        <table class="bankDetails">
            <tr>
                <td class='header'>
                    BANK ACCOUNT DETAIL:
                </td>
            </tr>
            <tr>
                <td>
                    BANGKOK BANK PUBLIC COMPANY LIMITED
                    <br>96,M.7 A.SAN KAMPHAENG CHIANGMAI THAILAND
                    <br>SWIFT CODE: BKKBTHBK
                    <br>ACC.NO. 357-3-01302-0
                    <br>ACC.NAME PREMPRACHA'S COLLECTION CO.,LTD
                </td>
            </tr>
        </table> --}}
    </body>

    {{-- <p>{{$order['id']}}</p> --}}
    {{-- <p>{{$order->id}}</p> --}}

</html>