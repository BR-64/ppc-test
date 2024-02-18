Hello Showroom!

</br> Confirmation of Order #{{$order['id']}}
<p>

    เราได้รับออเดอร์จากลูกค้า คุณ <strong>{{$order->customer['first_name']}}</strong> 
</br>E-mail : {{$order->user['email']}} หมายเลขออเดอร์ <strong>#{{$order['id']}}</strong>
    รหัสเอกสาร enpro : <strong>{{$order['enpro_doc']}}</strong>
</br>โปรดเช็ครายละเอียดออเดอร์, ใบขายสด และ ปริ้นใบปะหน้ากล่องเพื่อเตรียมแพ้คสินค้าเพื่อส่งให้ลูกค้าด้วยค่ะ 
    
</p>
<p>
    From Admin Showroom
</p>

ไฟล์แนบ PDF
<br>1. รายละเอียดออเดอร์ทั้งหมด (Orderinfo)
<br>2. ใบขายสด (Invoice)
<br>3. ใบปะหน้ากล่อง (Boxlabel)


<br>


</div>