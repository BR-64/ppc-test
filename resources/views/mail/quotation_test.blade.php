<x-mail::message>
Dear {{$order->customer['first_name']}},
<br>
Thank you for your interest in our products. Please review your quotation attached in this e-mail. Please note that this quotation will be valid until **{{date_format($order['created_at'],"d/m/Y")}}**. You can confirm your order by making payment on our website via the link below.

For further assistance please e-mail to us at <a href="mailto: shoponline@prempracha.com">showroom@prempracha.com</a> or add LINE ID showroomprem or call 053-338-540.


<br>

<button>    
    <a href="">
        Your Quotation:
    </a>
</button>
<br>

<br>
Best regards,<br>
Prempracha Team


[PDF attached](/quotation.pdf) 
</x-mail::message>
