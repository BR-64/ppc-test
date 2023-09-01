<x-app-layout>
    <form x-data="{
        {{-- 'country_code' => $billingAddress->country_code, --}}
        countries: {{ json_encode($countries) }}
    }"
        action="{{ route('register') }}"
        method="post"
        class="w-[400px] mx-auto blacktext"
    >
        @csrf

        <h2 class="pagehead ">Create an account</h2>
        <p class="text-center text-gray-500 mb-3">
            or
            <a
                href="{{ route('login') }}"
                class="text-sm text-purple-700 hover:text-purple-600"
            >
                login with existing account
            </a>
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')"/>
    <div class="bg-white p-3 shadow rounded-lg md:col-span-2 mb-3">
        <h2 class="text-xl font-semibold mb-2">Account Info</h2>

            <div class="mb-4">
            <x-input placeholder="Your name" type="text" name="name" :value="old('name')" />
            </div>
            <div class="mb-4">
                <x-input placeholder="Your Email" type="email" name="email" :value="old('email')" />
            </div>
            <div class="mb-4">
                <x-input placeholder="Password" type="password" name="password"/>
            </div>
            <div class="mb-4">
                <x-input placeholder="Repeat Password" type="password" name="password_confirmation"/>
            </div>
    </div>
    {{-- <div class="bg-white p-3 shadow rounded-lg md:col-span-2">
        <h2 class="text-xl font-semibold mb-2">Customer details (optional)</h2>
            <div class="mb-4">
                <div class="mb-3">
                    <x-input
                    type="text"
                    name="first_name"
                    placeholder="Customer / Company name"
                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                    />
                </div>
                <div class="mb-3">
                    <x-input
                    type="text"
                    name="TaxID"
                    placeholder="Tax ID (For invoice)"
                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                    />
                </div>
            </div>
            <div class="mb-4">
            </div>

            <h2 class="text-xl mt-6 font-semibold mb-2">Billing Address</h2>
            <div class="mb-3">
                <div>
                    <x-input
                        type="text"
                        name="billing[address1]"
                        x-model="billingAddress.address1"
                        placeholder="Address line 1"
                        class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                    />
                    <x-input
                        type="text"
                        name="billing[address2]"
                        x-model="billingAddress.address2"
                        placeholder="Address line 2"
                        class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                    />
                </div>
                <div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <x-input
                        type="text"
                        name="billing[city]"
                        x-model="billingAddress.city"
                        placeholder="City"
                        class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                    />
                </div>
                <div>
                    <x-input
                        type="text"
                        name="billing[zipcode]"
                        x-model="billingAddress.zipcode"
                        placeholder="ZipCode"
                        class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                    />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <x-input type="select"
                             name="billing[country_code]"
                             x-model="billingAddress.country_code"
                             class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded">
                        <option value="" selected>Select Country</option>
                        <template x-for="country of countries" :key="country.code">
                            <option :selected="select country"
                                    :value="country.code" x-text="country.name"></option>
                        </template>
                    </x-input>
                </div>
                <div>
                </div>
            </div>

            <div class="flex justify-between mt-6 mb-2">
                <h2 class="text-xl font-semibold">Shipping Address</h2>
                <label for="sameAsBillingAddress" class="text-gray-700">
                    <input @change="$event.target.checked ? shippingAddress = {...billingAddress} : ''"
                           id="sameAsBillingAddress" type="checkbox"
                           class="text-purple-600 focus:ring-purple-600 mr-2"> Same as Billing
                </label>
            </div>
            <div class="mb-3">
                <div>
                    <x-input
                        type="text"
                        name="shipping[address1]"
                        x-model="shippingAddress.address1"
                        placeholder="Address line 1"
                        class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                    />
                </div>
                <div>
                    <x-input
                        type="text"
                        name="shipping[address2]"
                        x-model="shippingAddress.address2"
                        placeholder="Address line 2"
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
    </div> --}}
        <div style="text-align: center">
            <button class="addtocart">Signup</button>
        </div>
    </form>

    <div class="footspace"></div>

</x-app-layout>
