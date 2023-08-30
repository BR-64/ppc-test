<x-mail::message>

{{-- /// order details --}}
|   |   |
|---|---|
|**Order # :** |{{$order->id}}|  
|**Order Status :**|{{ $order->status }}|  
|**Order Price (THB) :**|{{$order->total_price}}|
|**Order Date :**|{{$order->created_at}}|
<br>

{{-- /// product details --}}
|Image|Code| Price (THB) |Quantity|
|---|:---:|:---:|:---:|
@foreach($order->items as $item)
|<img src="{{$item->product->image}}" style="width: 100px">|{{$item->product->item_code}}|{{$item->unit_price}}|{{$item->quantity}}|
@endforeach
<br>

{{-- /// order summary --}}
|  |  |
|--|--|
|**subtotal**| {{$order['total_price']}}|
|**shipping fee**| {{$order['shipping']}} |
|**insurance**| {{$order['insurance']}}|
|**Total Payment (THB)**|{{$order['fullprice']}}|
<br>

{{-- /// shipping --}}
| | |
|-|-|
|**Shipping Address :**|{{$order->customer['first_name']}} {{$order->customer['last_name']}}, {{$order->customer->shippingAddress['address1']}},{{$order->customer->shippingAddress['address2']}},{{$order->customer->shippingAddress['city']}},{{$order->customer->shippingAddress['zipcode']}}|
|**Shipping Method :**|{{$order['ship_method']}}|
|**Payment Method :**|{{$order['pay_method']}}|
<br>

</x-mail::message>
