Hello Showroom!

</br># Confirmation Order #{{$id}}
<p>

    เราได้รับออเดอร์จากลูกค้า คุณ <strong>{{$name}}</strong> หมายเลขออเดอร์ <strong>#{{$id}}</strong>
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


SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`laravel_vue_ecommerce`.`cart_items`, CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)) (SQL: insert into `cart_items` (`user_id`, `product_id`, `quantity`, `updated_at`, `created_at`) values (5, 782, 1, 2023-08-30 13:12:39, 2023-08-30 13:12:39))