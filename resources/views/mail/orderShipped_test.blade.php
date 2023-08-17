
</br>THANKYOU FOR SHOPPING WITH US! 
</br>
</br>You order number <strong>{{$order['id']}}</strong> has been shipped. Your tracking number is <strong> < {{$order['tracking']}} ></strong>  you can easily track your delivery status by clicking on “Track You Order” below.
    
</br>
<div>
    <button>    
        <a href="https://track.thailandpost.co.th/">
            Track your order:
        </a>
    </button>
</div>
</br>
<table>
    <tr>
        <th>Order #</th>
        <td>

            {{$order->id}}
        </td>
    </tr>
    <tr>
        <th>Order Status</th>
        <td>{{ $order->status }}</td>
    </tr>
    <tr>
        <th>Order Price (THB)</th>
        <td>{{$order->total_price}}</td>
    </tr>
    <tr>
        <th>Order Date</th>
        <td>{{$order->created_at}}</td>
    </tr>
</table>
<table>
    <tr>
        <th>Image</th>
        <th>Code</th>
        <th>Price (THB)</th>
        <th>Quantity</th>
    </tr>
    @foreach($order->items as $item)
        <tr>
            <td>
                <img src="{{$item->product->image}}" style="width: 100px">
            </td>
            <td>{{$item->product->item_code}}</td>
            <td>{{$item->unit_price * $item->quantity}}</td>
            <td>{{$item->quantity}}</td>
        </tr>
    @endforeach
</table>

<table>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
        <tr>
            <td>subtotal</td><td>{{$order['total_price']}}</td>
        </tr>
            <tr><td>shipping fee   </td><td>{{$order['shipping']}}</td></tr>
            <tr><td>insurance</td><td>{{$order['insurance']}}</td></tr>
            <tr>
                <td>Total Payment (THB)</td>
                <td>{{number_format(($order['total_price']+$order['insurance']+$order['shipping']),2)}}</td>
            </tr>

</table>
</br>
</br>
<table>
    <tr>
    </tr>
        <tr>
            <td>Shipping Address</td>
            <td>
                {{$order->customer['first_name']}}
                {{$order->customer['last_name']}}
                {{$order->customer->shippingAddress['address1']}}
            </br>{{$order->customer->shippingAddress['address2']}}
            </br>{{$order->customer->shippingAddress['city']}}
            </br>{{$order->customer->shippingAddress['zipcode']}}
            </td>
        </tr>
            <tr><td>Shipping Method:</td><td>{{$order['ship_method']}}</td></tr>
            <tr><td>Payment Method:</td><td>{{$order['pay_method']}}</td></tr>

</table>

</br>
</br>
Thank you,<br>
{{ config('app.name') }}

</br>
</br>

<div>
    <p style="color:red;">

        NOTE:  
        Prempracha will not be responsible for any loss or damage caused during the shipment of the product. 
        All of our products are handmade, therefore, the colors and shapes may not be exactly the same as in the picture. Each piece will be slightly different.
        For further assistance please e-mail to us at showroom@prempracha.com or add LINE ID showroomprem or call 053-338-540.
    </p>
        
    

</div>
