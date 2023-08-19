

<table>
    <tr>
        <th>Order ID</th>
        <td>
            {{$id}}
        </td>
    </tr>
<tr>
        <th>Order Status</th>
        <td>{{ $status }}</td>
    </tr>
    <tr>
        <th>Order Price (THB)</th>
        <td>{{$total_price}}</td>
    </tr>
    <tr>
        <th>Order Date</th>
        <td>{{$created_at}}</td>
    </tr>
</table>
{{-- <table>
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
</table> --}}
</br>
</br>
<table>
        <tr>
            <td>subtotal</td><td>{{$total_price}}</td>
        </tr>
            <tr><td>shipping fee   </td><td>{{$shipping}}</td></tr>
            <tr><td>insurance</td><td>{{$insurance}}</td></tr>
            <tr>
                <td>Total Payment (THB)</td>
                <td>{{$fullprice}}</td>
            </tr>

</table>
</br>
</br>
<table>
        {{-- <tr>
            <td>Shipping Address</td>
            <td>
                {{$order->customer['first_name']}}
                {{$order->customer['last_name']}}
                {{$order->customer->shippingAddress['address1']}}
            </br>{{$order->customer->shippingAddress['address2']}}
            </br>{{$order->customer->shippingAddress['city']}}
            </br>{{$order->customer->shippingAddress['zipcode']}}
            </td>
        </tr> --}}
            <tr><td>Shipping Method:</td><td>{{$ship_method}}</td></tr>
            <tr><td>Payment Method:</td><td>{{$pay_method}}</td></tr>
</table>
