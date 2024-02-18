<x-mail::message>
# Confirmation of you Order {{$order['id']}}

Dear {{$customer['first_name']}},

THANKYOU FOR SHOPPING WITH US! 

You order number {{$order['id']}} has been confirmed. You will receive another e-mail once your order has been shipped.

<x-mail::button :url="''">
View Order
</x-mail::button>

    @foreach($items as $product)
                <div class="ordersummary">
                    <div class="os1">
                        <img src="{{$product['price_data']['product_data']['images']['0']}}" class="sumpic" alt=""/>
                    </div>
                    <div class="os2">
                        <p>{{$product['price_data']['product_data']['name']}}</p>
                        <div class="os2_1">
                            <p>{{number_format($product['price_data']['price'])}}</p>
                            <p>x{{ $product['quantity']}}</p>
                        </div>
                    </div>
                    <div class="os3">
                        <p>{{number_format( $product['itemtotal'])}}</p>
                        
                    </div>
                </div>
                <hr class="my-3"/>
    @endforeach


<p style="color:red;">

    NOTE:  
    Prempracha will not be responsible for any loss or damage caused during the shipment of the product. 
    All of our products are handmade, therefore, the colors and shapes may not be exactly the same as in the picture. Each piece will be slightly different.
    For further assistance please e-mail to us at showroom@prempracha.com or add LINE ID showroomprem or call 053-338-540.
</p>
    

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
