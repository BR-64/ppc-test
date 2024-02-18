<?php

namespace App\Http\Controllers;
use App\Enums\AddressType;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\BillingAddress;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ProfileController extends Controller
{
    public function view(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;
    
    // old address system
        // $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        // $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);

    // new address system
        $shippingAddress = $customer->Ship_Address ?: new ShippingAddress;
        $billingAddress = $customer->Bill_Address ?: new BillingAddress;

//        dd($customer, $shippingAddress->attributesToArray(), $billingAddress, $billingAddress->customer);
        $countries = Country::query()->orderBy('name')->get();
        // $test = Customer;


        return view('profile.view2', compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }
    public function billshipView(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;

        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);

        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);

        $countries = Country::query()->orderBy('name')->get();

        return view('checkout.billing_shipping', compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));


    }

    public function store(ProfileRequest $request)
    {
        $customerData = $request->validated();
        $shippingData = $customerData['shipping'];
        $billingData = $customerData['billing'];

        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;

        $customer->update($customerData);

        if ($customer->shippingAddress) {
            $customer->shippingAddress->update($shippingData);
        } else {
            $shippingData['customer_id'] = $customer->user_id;
            $shippingData['type'] = AddressType::Shipping->value;
            CustomerAddress::create($shippingData);
        }

        if ($customer->billingAddress) {
            $customer->billingAddress->update($billingData);
        } else {
            $billingData['customer_id'] = $customer->user_id;
            $billingData['type'] = AddressType::Billing->value;
            CustomerAddress::create($billingData);
        }

        $request->session()->flash('flash_message', 'Profile was successfully updated.');

        // dd($customer->billingAddress);

        // return redirect()->route('profile');
        return redirect()->back();

    }
    public function store_new(ProfileRequest $request)
    {
        $customerData = $request->validated();
        $shippingData = $customerData['shipping'];
        $billingData = $customerData['billing'];

        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;

        $customer->update($customerData);

        if ($customer->Ship_Address) {
            $customer->Ship_Address->update($shippingData);
        } else {
            $shippingData['customer_id'] = $customer->user_id;
            ShippingAddress::create($shippingData);
        }

        if ($customer->Bill_Address) {
            $customer->Bill_Address->update($billingData);
        } else {
            $billingData['customer_id'] = $customer->user_id;
            BillingAddress::create($billingData);
        }

        $request->session()->flash('flash_message', 'Profile was successfully updated.');

        // dd($customer->billingAddress);

        return redirect()->back();

    }

    public function passwordUpdate(PasswordUpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $passwordData = $request->validated();

        $user->password = Hash::make($passwordData['new_password']);
        $user->save();

        $request->session()->flash('flash_message', 'Your password was successfully updated.');

        return redirect()->route('profile');
    }
}
