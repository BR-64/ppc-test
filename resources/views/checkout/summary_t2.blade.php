<x-app-layout>
    <div class="pccoll">

        <h1 class="pagehead2">Checkout Summary</h1>
        {{-- <h2 class ="pprice">Order Type : [{{$ordertype}}]</h2> --}}
        {{-- <p>OrderNumber #{{$paydata['order_id']}}</p> --}}

        <p>{{$totalweight}}</p>
        {{-- <p>{{$shipzonezone_air}}</p> --}}
        <p>{{$shipcountry}}</p>

        <div x-data="{
            flashMessage: '{{\Illuminate\Support\Facades\Session::get('flash_message')}}',
            init() {
                if (this.flashMessage) {
                    setTimeout(() => this.$dispatch('notify', {message: this.flashMessage}), 200)
                }
            }
        }" class="">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start blacktext">
            <div class="bg-white p-3 shadow rounded-lg md:col-span-2">
                <form x-data="{
                    countries: {{ json_encode($countries) }},
                    billingAddress: {{ json_encode([
                        'address1' => old('billing.address1', $billingAddress->address1),
                        'address2' => old('billing.address2', $billingAddress->address2),
                        'city' => old('billing.city', $billingAddress->city),
                        'state' => old('billing.state', $billingAddress->state),
                        'country_code' => old('billing.country_code', $billingAddress->country_code),
                        'zipcode' => old('billing.zipcode', $billingAddress->zipcode),
                    ]) }},
                    shippingAddress: {{ json_encode([
                        'address1' => old('shipping.address1', $shippingAddress->address1),
                        'address2' => old('shipping.address2', $shippingAddress->address2),
                        'city' => old('shipping.city', $shippingAddress->city),
                        'state' => old('shipping.state', $shippingAddress->state),
                        'country_code' => old('shipping.country_code', $shippingAddress->country_code),
                        'zipcode' => old('shipping.zipcode', $shippingAddress->zipcode),
                    ]) }},
                    get billingCountryStates() {
                        const country = this.countries.find(c => c.code === this.billingAddress.country_code)
                        if (country && country.states) {
                            return JSON.parse(country.states);
                        }
                        return null;
                    },
                    get shippingCountryStates() {
                        const country = this.countries.find(c => c.code === this.shippingAddress.country_code)
                        if (country && country.states) {
                            return JSON.parse(country.states);
                        }
                        return null;
                    }
                }" action="{{ route('profile.update') }}" method="post">
                    @csrf
                    <div class="chksum">
                        <table class="summarytable">
                            <tr>
                                <td class="underline">Customer Info </td>
                            </tr>
                            <tr>
                                <td>Customer Name : </td>
                                <td>{{$customer->first_name}}  {{$customer->last_name}}</td>
                            </tr>
                            <tr>
                                <td>Customer Phone : </td>
                                <td>{{$customer->phone}}</td>
                            </tr>
                            <tr>
                                <td>Customer Tax ID : </td>
                                <td>{{$customer->customer_taxid}}</td>
                            </tr>
                        </table>
                    </br>
                        <table class="summarytable">
                            <tr>
                                <td class="underline">Billing Info </td>
                            </tr>
                            <tr>
                                <td>{{$billingAddress->address1}}</td>
                            </tr>
                            <tr>
                                <td>{{$billingAddress->address2}}</td>
                            </tr>
                            <tr>
                                <td>{{$billingAddress->city}}</td>
                                <td>{{$billingAddress->zipcode}}</td>
                            </tr>
                            <tr>
                                <td>{{$billingAddress->country->name}}</td>
                            </tr>
                        </table>
                    </br>
                        <table class="summarytable">
                            <tr>
                                <td class="underline">Shipping Info </td>
                            </tr>
                            <tr>
                                <td>{{$shippingAddress->address1}}</td>
                            </tr>
                            <tr>
                                <td>{{$shippingAddress->address2}}</td>
                            </tr>
                            <tr>
                                <td>{{$shippingAddress->city}}</td>
                                <td>{{$shippingAddress->zipcode}}</td>
                            </tr>
                            <tr>
                                <td>{{$shippingAddress->country->name}}</td>
                            </tr>
                        </table>
                        

                    </div>

</br>

{{-- /// order detail section --}}
<div class="chksum">
            <p class="underline summarytable">Item(s) Info</p>
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
{{-- shipping cost section --}}
    <div class="chksum summarytable">
        <p class="underline">Shipping Selection (thb)</p>
        <form action="">
            {{-- <p>{{$ship_th}}</p> --}}
            <input type="radio" id="EMS" name="Shipcost" value="$ship_ems" required>
            <label for="EMS">EMS : {{number_format($ship_ems)}}</label>
        </br>
            <input type="radio" id="Air" name="Shipcost" value="$ship_air">
            <label for="Air">Air : {{number_format($ship_air)}}</label>
        </form>

    </div>
    </br>

    <a href="{{ route('checkout.step3') }}">
        <x-button>Step 3 : Payment</x-button>
    </a>

</x-app-layout>