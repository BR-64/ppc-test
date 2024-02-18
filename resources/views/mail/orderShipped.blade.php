<x-mail::message>
Dear {{$order->customer['first_name']}},  
You order **#{{$order['id']}}**  has been shipped. Your tracking number is **{{$order['tracking']}}** you can easily track your delivery status by clicking on “Track You Order” below. 
<br>

{{-- <button>    
    <a href="https://track.thailandpost.co.th/">
        Track your order
    </a>
</button> --}}

<a href="https://track.thailandpost.co.th">
    Track your order
</a>
<br>

{{-- /// order details --}}
|   |   |
|---|---|
|**Order # :** |{{$order->id}}|  
|**Order Status :**|{{ $order->status }}|  
|**Order Total (THB) :**|{{number_format($order->fullprice,2)}}|
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
|**Subtotal**| {{number_format($order['total_price'],2)}}|
|**Discount**| - {{number_format($order['discount_amount'],2)}}|
|**Shipping fee**| {{number_format($order['shipping'],2)}} |
|**Insurance**| {{number_format($order['insurance'],2)}}|
|**Total Payment (THB)**|{{number_format($order['fullprice'],2)}}|
<br>
<br>

{{-- /// shipping --}}
| | |
|-|-|
|**Shipping Address :**|{{$order->customer['first_name']}} {{$order->customer['last_name']}}, {{$order->customer->Ship_address['address1']}},{{$order->customer->Ship_address['address2']}},{{$order->customer->Ship_address['city']}},{{$order->customer->Ship_address['zipcode']}}|
|**Shipping Method :**|{{$order['ship_method']}}|
|**Payment Method :**|{{$order['pay_method']}}|
<br>

<x-mail::panel>
<p style="color:red;">
Remark :
<br>
สินค้าทุกชิ้นเป็นสินค้าแฮนด์เมด สีและรูปทรงอาจไม่เหมือนในภาพ แต่ละชิ้นอาจจะมีความแตกต่างกันเล็กน้อย
<br>
<br>All of our products are handmade, therefore, colors and shapes may not be exactly the same as in the picture. Each piece will be slightly different.
<br>
<br>
กรุณาแกะกล่องเมื่อได้รับสินค้าทันที
<br>
<br>Please unpack the box upon receiving the product immediately.
<br>
<br>
กรุณาถ่ายภาพหรือวีดีโอ ขณะแกะกล่องสินค้า เพื่อเป็นหลักฐานหากพบว่าสินค้าเสียหายจากการแพคหรือขนส่ง และติดต่อเคลมสินค้าไปยัง <a href = "mailto: shoponline@prempracha.com">shoponline@prempracha.com</a> หรือแอดไลน์ showroomprem หรือโทร 053-338-540 ภายใน 7 วันหลังจากที่ได้รับสินค้าแล้วเท่านั้น
<br>
<br>Please take photos or videos. while unpacking the product as evidence if it is found that the product is damaged from packing or transportation. Please report claim to us via e-mail within 7 days of receiving the package to <a href = "mailto: shoponline@prempracha.com">shoponline@prempracha.com</a> or add LINE ID showroomprem or call +66-53-338-540
</p>
</x-mail::panel>
<br>
Thank You,<br>
{{ config('app.name') }}
</x-mail::message>
