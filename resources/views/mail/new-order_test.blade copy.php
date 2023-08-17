
# Confirmation of you Order {{$order['id']}}
</br>
</br>Dear {{$order->customer['first_name']}}
</br>
</br>THANKYOU FOR SHOPPING WITH US! 
</br>
</br>You order <br {{$order['id']}} > has been confirmed. You will receive another e-mail once your order has been shipped.
</br>
</br>
<div>
    <button>    
        Link to your order:
        <a href="{{route('order.view', $order, true)}}">Order #{{$order->id}}</a>
    </button>
</div>
</br>
<table>
    <tr>
        <th>Order ID</th>
        <td>
            {{-- <a href="{{ $forAdmin ? env('BACKEND_URL').'/app/orders/'.$order->id : route('order.view', $order, true) }}">
                {{$order->id}}
            </a> --}}
            {{$order->id}}
        </td>
    </tr>
    <tr>
        <th>Order Status</th>
        <td>{{ $order->status }}</td>
    </tr>
    <tr>
        <th>Order Price</th>
        <td>Thb {{$order->total_price}}</td>
    </tr>
    <tr>
        <th>Order Date</th>
        <td>{{$order->created_at}}</td>
    </tr>
</table>
<table>
    <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Price (Thb)</th>
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
</br>
</br>
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
                <td>Total Payment</td>
                <td>{{$order['total_price']+$order['insurance']+$order['shipping']}}</td>
            </tr>

</table>
</br>
</br>
<table>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
        <tr>
            <td>Shipping Address</td>
            <td>
                {{$order->customer->shippingAddress['address1']}}
            </br>{{$order->customer->shippingAddress['address2']}}
            </br>{{$order->customer->shippingAddress['city']}}
            </br>{{$order->customer->shippingAddress['zipcode']}}
            </td>
        </tr>
            <tr><td>Shipping Method:</td><td>{{$order['ship_method']}}</td></tr>
            <tr><td>Payment Method:</td><td>{{$order['pay_method']}}</td></tr>

</table>

<div>
    <p style="color:red;">

        NOTE:  
        Prempracha will not be responsible for any loss or damage caused during the shipment of the product. 
        All of our products are handmade, therefore, the colors and shapes may not be exactly the same as in the picture. Each piece will be slightly different.
        For further assistance please e-mail to us at showroom@prempracha.com or add LINE ID showroomprem or call 053-338-540.
    </p>
        
    
    Thanks,<br>
    {{ config('app.name') }}
</div>
