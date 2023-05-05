<x-app-layout>
    <div class="pccoll">

        <h1 class="pagehead2">Checkout Summary</h1>
        <h2 class ="pprice">Order Type : [{{$ordertype}}]</h2>

        @foreach($items as $product)
        <div>
            <p>{{$product['price_data']['product_data']['name']}}</p>
            {{-- <p>{{$product['price_data']['product_data']->name}}</p> --}}
            <p>{{ $product['quantity']}}</p>

        </div>
        @endforeach

    <h3> {{$totalpriceShow}}</h3>

    {{-- <p>{{$paydata['order_id']}}</p> --}}
    </br>
    <div x-data="{ordertype:{{json_encode($ordertype)}}}">
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
                        data-amount={{$totalprice}}
                        data-currency="THB"
                data-payment-methods="card"
                data-name="Test shop Prempracha"
                description="product21"
                reference_order="test124"
                data-mid="451320492949001"
                >
            </script>
            <input type="hidden" name="paytype"  value="card_DCC">
            <input type="hidden" name="amount" value="{{$totalprice}}">          
            
                    </form>
                </div>

            <div>
                <h3>QR code</h3>
            </br>
                <form class="qrform" method="POST" action="{{route('kpayment')}}">
                    @csrf
                <input type="hidden" name="paytype"  value="qr">
                <input type="hidden" name="amount" value="{{$totalprice}}">          
                <input class="subbut" type="submit"value="Pay with QR">
                </form>
            </div>

            <div>
                <h3>Alipay</h3>
            </br>
                <form class="qrform" method="POST" action="{{route('kpayment')}}">
                    @csrf
                <input type="hidden" name="paytype"  value="alipay">
                <input type="hidden" name="amount" value="{{$totalprice}}">          
                <input class="subbut" type="submit"value="Pay with Ali">
                </form>
            </div>
            </div>
        </div>

        <div x-show="ordertype == 'quotation'">
            <a href="/orders/{{$paydata['order_id']}}">
                <button class="btn-primary w-full py-3 text-lg">
                    Check your Quotation
                </button>
            </a>
        </div>

    </div>
</div>


</x-app-layout>