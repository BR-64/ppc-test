<x-app-layout>

    <div class='qrdiv'>
        <h1>QR Payment</h1>
        <br>
        <form method="POST" action="{{route('paymentresult')}}">
            <script type="text/javascript"
                {{-- src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                data-apikey="pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3fC" --}}

                src={{$src}}
                data-apikey={{$apikey}}
                data-amount={{$qrinfo['amount']}}
                data-payment-methods="qr"
                data-name="prempracha online shop"
                data-order-id={{$qrinfo['id']}}>
            </script>
        </form>
    </div>


{{-- <h1>{{$qrinfo['id']}}</h1> --}}

</x-app-layout>
