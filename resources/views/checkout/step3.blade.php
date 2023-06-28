<x-app-layout>
    <div class="pccoll">

        <h1 class="pagehead2">Step 3 : Payment</h1>

{{-- /// order detail section --}}
<div class="chksum">
    <table class="text-start">
        {{-- <tr>
            <td class="underline">Total Payment</td>
        </tr> --}}
        <tr>
            <td>Item Price (incl. vat) :  </td>
            <td> {{number_format($itemsprice)}}</td>
        </tr>
        <tr class="underline">
            <td>Shipping Cost : </td>
            <td>{{number_format($shipcost)}}</td>
        </tr>
        <tr>
            <td>Total Payment : </td>
            <td class="bold">{{number_format($totalpayment)}} (thb)</td>
        </tr>

    </table>
        </div>
    </br>

    </br>

    <div x-show="ordertype == 'paynow'">
        <h2>Payment option</h2>
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
                    reference_order="test124"
                    data-mid="451320492949001"
            >
        </script>
        <input type="hidden" name="paytype"  value="card_DCC">
        <input type="hidden" name="amount" value="{{$totalpayment}}">          
        
                </form>
            </div>

        <div>
            <h3>QR code</h3>
        </br>
            <form class="qrform" method="POST" action="{{route('kpayment')}}">
                @csrf
            <input type="hidden" name="paytype"  value="qr">
            <input type="hidden" name="amount" value="{{$totalpayment}}">          
            <input class="subbut" type="submit"value="Pay with QR">
            </form>
        </div>

        <div>
            <h3>Alipay</h3>
        </br>
            <form class="qrform" method="POST" action="{{route('kpayment')}}">
                @csrf
                <input type="hidden" name="paytype"  value="alipay">
                <input type="hidden" name="amount" value="{{$totalpayment}}">          
                <button class="subbut" type="submit"value="Pay with Ali"  onclick="clicked(Alipay)"> Pay with Ali
                </button>
            </form>
        </div>
        </div>
    </div>

</x-app-layout>