<x-app-layout>
    <div class="pccoll">

        <h1 class="pagehead2">Checkout Summary</h1>
        {{-- <h2 class ="pprice">Order Type : [{{$ordertype}}]</h2> --}}
        {{-- <p>OrderNumber #{{$paydata['order_id']}}</p> --}}

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
                    <div class="mb-3">
                        <x-input
                            type="text"
                            name="phone"
                            value="{{old('phone', $customer->phone)}}"
                            placeholder="Your Phone Number"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>
{{-- //// billing section --}}
            <h3 style="text-align: start">Billing</h3>
                <div class="chksum">
                    <div class="mb-3">
                        <x-input
                            type="text"
                            name="company_name"
                            value="{{old('company_name', $user->company_name)}}"
                            placeholder="customer / company name"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>
                        <x-input
                        type="text"
                        name="billing[address1]"
                        x-model="billingAddress.address1"
                        placeholder="Address row 1"
                        class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        <x-input
                        type="text"
                        name="billing[address2]"
                        x-model="billingAddress.address2"
                        placeholder="Address row 2"
                        class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                </br>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                            <x-input
                                type="text"
                                name="billing[city]"
                                x-model="billingAddress.city"
                                placeholder="City"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                            <x-input
                                type="text"
                                name="billing[zipcode]"
                                x-model="billingAddress.zipcode"
                                placeholder="ZipCode"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                    </div>

                            <x-input type="select"
                                     name="billing[country_code]"
                                     x-model="billingAddress.country_code"
                                     class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded">
                                <option value="">Select Country</option>
                                <template x-for="country of countries" :key="country.code">
                                    <option :selected="country.code === billingAddress.country_code"
                                            :value="country.code" x-text="country.name"></option>
                                </template>
                            </x-input>
                </div>
            </br>
{{-- //// shipping section --}}
                <div class="chksum">
                    <label for="sameAsBillingAddress" class="text-gray-700">
                        <input @change="$event.target.checked ? shippingAddress = {...billingAddress} : ''"
                               id="sameAsBillingAddress" type="checkbox"
                               class="text-purple-600 focus:ring-purple-600 mr-2"> Same as Billing
                    </label>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <x-input
                            type="text"
                            name="shipping[address1]"
                            x-model="shippingAddress.address1"
                            placeholder="Address 1"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>
                    <div>
                        <x-input
                            type="text"
                            name="shipping[address2]"
                            x-model="shippingAddress.address2"
                            placeholder="Address 2"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <x-input
                            type="text"
                            name="shipping[city]"
                            x-model="shippingAddress.city"
                            placeholder="City"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>
                    <div>
                        <x-input
                            name="shipping[zipcode]"
                            x-model="shippingAddress.zipcode"
                            type="text"
                            placeholder="ZipCode"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <x-input type="select"
                                 name="shipping[country_code]"
                                 x-model="shippingAddress.country_code"
                                 class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded">
                            <option value="">Select Country</option>
                            <template x-for="country of countries" :key="country.code">
                                <option :selected="country.code === shippingAddress.country_code"
                                        :value="country.code" x-text="country.name"></option>
                            </template>
                        </x-input>
                    </div>
                </div>
            </br>
            <x-button class="w-full">Update</x-button>
        </form>
    </div>
</div>
</br>

{{-- /// order detail section --}}
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

    </div>
    <a href="{{ route('checkout.step2') }}">
        <x-button>Step 2 : Shipping Calculation</x-button>
    </a>
</div>



</x-app-layout>