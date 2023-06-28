<x-app-layout>
    <div class="pccoll">

        <h1 class="pagehead2">Checkout Summary</h1>
        <h2 class ="pprice">Order Type : [{{$ordertype}}]</h2>
        <p>OrderNumber #{{$paydata['order_id']}}</p>

        <div class="chksum">
            <h2>Customer Info</h2>
        </div>

        </br>
        
        <div class="chksum">

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
    
                <h3 class="chksumtotal">{{$totalpriceShow}}</h3>
                
        </div>
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
                        {{-- data-currency="THB" --}}
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
                    <button class="subbut" type="submit"value="Pay with Ali"  onclick="clicked(Alipay)"> Pay with Ali
                    </button>
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

<script>
    function clicked(e)
    {
        if(!confirm('Do you want to pay with 'e'?')) {
            e.preventDefault();
        }
    }

</script>


</x-app-layout>