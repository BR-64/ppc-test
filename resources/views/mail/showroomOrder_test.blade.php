
Hello Showroom!

</br># Confirmation of you Order {{$order['id']}}
<p>

    เราได้รับออเดอร์จากลูกค้า คุณ <strong>{{$order->customer['first_name']}}</strong> หมายเลขออเดอร์ <strong>{{$order['id']}}</strong>
</br>โปรดเช็ครายละเอียดออเดอร์, ใบขายสด และ ปริ้นใบปะหน้ากล่องเพื่อเตรียมแพ้คสินค้าเพื่อส่งให้ลูกค้าด้วยค่ะ 
    
</p>
<p>
    From Admin Showroom
</p>

แนบไฟล์ PDF
1.รายละเอียดออเดอร์ทั้งหมด
2. ใบขายสด
3. ใบปะหน้ากล่อง


</br>

{{-- <table>
    <tr>
        <th>Order ID</th>
        <td>
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
        <th>Price</th>
        <th>Quantity</th>
    </tr>
    @foreach($order->items as $item)
        <tr>
            <td>
                <img src="{{$item->product->image}}" style="width: 100px">
            </td>
            <td>{{$item->product->title}}</td>
            <td>Thb {{$item->unit_price * $item->quantity}}</td>
            <td>{{$item->quantity}}</td>
        </tr>
    @endforeach
</table> --}}

</div>
