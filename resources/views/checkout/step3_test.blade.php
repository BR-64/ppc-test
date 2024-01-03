<?php
//Set no caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>


<x-app-layout>
    <div class="pccollc">

        <h1 class="pagehead2">Step 3 : Payment</h1>

{{-- /// order detail section --}}
<div class="chksum">
    <table class="text-start">
        {{-- <tr>
            <td class="underline">Total Payment</td>
        </tr> --}}
        <tr>
            <td>Item Price (incl. vat) :  </td>
            <td class='text-right'> {{number_format($itemsprice)}}</td>
        </tr>
        <tr>
            <td>Discount {{$dispercent}} :  </td>
            <td class='text-right'> - {{number_format($discount_amount)}}</td>
        </tr>
        <tr class="underline">
            <td>Shipping Cost : </td>
            <td class='text-right'>{{number_format($shipcost)}}</td>
        </tr>
        <tr class="">
            <td>Insurance : </td>
            <td class='text-right'>{{number_format($insure)}}</td>
        </tr>
        <tr>
            <td>Total Payment : </td>
            <td class="bold text-right">(thb) {{number_format($totalpayment)}} </td>
        </tr>

    </table>
        </div>
    </br>

    </br>

    <div x-show="ordertype == 'paynow'">
        <h2>Payment option p30</h2>
        <div class="payoption ">
            <div class="border-4 border-white">
                <h3>Credit Card DCC</h3>
                <form method="POST" action="{{route('kpayment')}}">
                    @csrf
                    <script type="text/javascript"
                    src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                    data-apikey="pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3fC"
                    data-amount={{$totalpayment}}
                    {{-- data-currency="THB" --}}
                    data-payment-methods="card"
                    data-name="Test shop Prempracha"
                    description="product21"
                    reference_order="{{$orderid}}"
                    {{-- data-mid="451320492949001" --}}
                    data-mid="{{$mid}}"
            >
        </script>
        <input type="hidden" name="paytype"  value="card_DCC">
        <input type="hidden" name="amount" value="{{$totalpayment}}">          
        <input type="hidden" name="reforder" value="{{$orderid}}">          
        
                </form>
            </div>

        <div>
            <h3>QR code</h3>
        {{-- </br> --}}
            <form class="qrform" method="POST" action="{{route('kpayment')}}">
                @csrf
            <input type="hidden" name="paytype"  value="qr">
            <input type="hidden" name="amount" value="{{$totalpayment}}"> 
            <input type="hidden" name="reforder" value="{{$orderid}}">                   
            <input class="subbut" type="submit"value="Pay with QR">
            </form>
        </div>

        <div>
            <h3>Alipay</h3>
        {{-- </br> --}}
            <form class="qrform" method="POST" action="{{route('kpayment')}}">
                @csrf
                <input type="hidden" name="paytype"  value="alipay">
                <input type="hidden" name="amount" value="{{$totalpayment}}">   
                <input type="hidden" name="reforder" value="{{$orderid}}">                 
                <button class="subbut" type="submit"value="Pay with Ali"  onclick="clicked(Alipay)"> Pay with Ali
                </button>
            </form>
        </div>
        </div>
    </div>

</x-app-layout>