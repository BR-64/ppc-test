<x-app-layout>


    <form method="POST" action="{{route('paymentresult')}}">
        @csrf
        <script type="text/javascript"
            status="success"
            data-apikey="pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3fC"
            data-amount="1900.00"
            data-currency="THB"
            data-payment-methods="card"
            data-name="Test shop"
            description="product21"
            reference_order="test124"
            data-mid="451320492949001">
        </script>
            <button>test pay</button>
        </form>


</x-app-layout>