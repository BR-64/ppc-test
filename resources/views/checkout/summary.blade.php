<x-app-layout>
    <h1 class="pagehead">Checkout Summary</h1>

    {{-- <div x-data="{
        summaryItems:{{
            json_encode(

            )
        }}
    }">


    </div> --}}
    <div class="pccoll">

        @foreach($items as $product)
        <div>
            <p>{{$product['price_data']['product_data']['name']}}</p>
            {{-- <p>{{$product['price_data']['product_data']->name}}</p> --}}
            <p>{{ $product['quantity']}}</p>

        </div>
        @endforeach

    <h3>{{$totalprice}}</h3>
    <h3>{{$kk}}</h3>

    <h2>Payment option</h2>
    <div class="payoption ">
        <div class="border-4 border-white">
            <h3>Credit Card DCC</h3>
            <form method="POST" action="{{route('kpayment')}}">
                @csrf
                <script type="text/javascript"
                src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                data-apikey="pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3fC"
                data-amount={{$totalprice}}
                data-currency="THB"
                data-payment-methods="card"
                data-name="Test shop"
                description="product21"
                reference_order="test124"
                data-mid="451320492949001"
                >
            </script>
            <input type="hidden" name="amount" value="{{$totalprice}}">          

            </form>
        </div>

        {{-- <div class="border-4 border-white">
            <h3>Credit Card MCC</h3>
            <form method="POST" action="{{route('kpayment')}}">
                @csrf
                <script type="text/javascript"
                src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                data-apikey="pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3fC"
                data-amount={{$totalprice}}
                data-currency="THB"
                data-payment-methods="card"
                data-name="Test shop"
                description="product21"
                reference_order="test124"
                data-mid="401232949944001"
                >
            </script>
            <input type="hidden" name="amount" value="{{$totalprice}}">          

            </form>
        </div> --}}

        <div>
            <h3>QR code</h3>
            <form class="qrform" method="POST" action="{{route('kpayment')}}">
                @csrf
            <input type="hidden" name="paymentMethods"  value="qr">
            {{-- <label for="qr">QR</label> --}}
            {{-- <input name="paymentMethods" type="radio" value="Submit" id="card">
            <label for="card">credit card</label> --}}

            <input type="hidden" name="amount" value="{{$totalprice}}">          
            <input class="subbut" type="submit"value="Pay with QR">
            </form>
        </div>

        <div>
            <h3>Alipay</h3>
            <form class="qrform" method="POST" action="{{route('kpayment')}}">
                @csrf
            <input type="hidden" name="paymentMethods"  value="alipay">
            {{-- <label for="qr">QR</label> --}}
            {{-- <input name="paymentMethods" type="radio" value="Submit" id="card">
            <label for="card">credit card</label> --}}

            <input type="hidden" name="amount" value="{{$totalprice}}">          
            <input class="subbut" type="submit"value="Pay with Ali">
            </form>
        </div>
    </div>
</div>

</x-app-layout>