<x-mail::message>
# THANK YOU FOR SHOPPING WITH US! 

Dear {{$order->customer['first_name']}},  
You order {{$order['id']}} has been confirmed. You will receive another e-mail once your order has been shipped.

<a href="{{route('order.view', $order, true)}}">
Link to your order : #{{$order->id}}
</a>

{{-- /// order details --}}
|   |   |
|---|---|
|**Order # :** |6|  
|**Order Status :**|unpaid|  
|**Order Price (THB) :**|661.00|
|**Order Date :**|2023-01-15 11:00:39|
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
|**Shipping Address :**|{{$order->customer['first_name']}} {{$order->customer['last_name']}}, {{$order->customer->Ship_Address['address1']}},{{$order->customer->Ship_Address['address2']}},{{$order->customer->Ship_Address['city']}},{{$order->customer->Ship_Address['zipcode']}}|
|**Shipping Method :**|{{$order['ship_method']}}|
|**Payment Method :**|{{$order['pay_method']}}|
<br>

<x-mail::panel>
<p style="color:red;">
Remark :
<br>
สินค้าทุกชิ้นเป็นสินค้าแฮนด์เมด สีและรูปทรงอาจไม่เหมือนในภาพ แต่ละชิ้นอาจจะมีความแตกต่างกันเล็กน้อย
All of our products are handmade, therefore, colors and shapes may not be exactly the same as in the picture. Each piece will be slightly different.
<br>
<br>
กรุณาแกะกล่องเมื่อได้รับสินค้าทันที
Please unpack the box upon receiving the product immediately.
<br>
<br>
กรุณาถ่ายภาพหรือวีดีโอ ขณะแกะกล่องสินค้า เพื่อเป็นหลักฐานหากพบว่าสินค้าเสียหายจากการแพคหรือขนส่ง และติดต่อเคลมสินค้าไปยัง <a href = "mailto: shoponline@prempracha.com">shoponline@prempracha.com</a> หรือแอดไลน์ showroomprem หรือโทร 053-338-540 ภายใน 7 วันหลังจากที่ได้รับสินค้าแล้วเท่านั้น
</br>Please take photos or videos. while unpacking the product as evidence if it is found that the product is damaged from packing or transportation. Please report claim to us via e-mail within 7 days of receiving the package to <a href = "mailto: shoponline@prempracha.com">shoponline@prempracha.com</a> or add LINE ID showroomprem or call +66-53-338-540
</p>
</x-mail::panel>
<br>
Thank You,<br>
{{ config('app.name') }}
</x-mail::message>
