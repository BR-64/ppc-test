<x-app-layout>
    <div class="pccoll">
        <h1> Email testing hub</h1>
{{-- New order --}}
        <div>
            <div class="pccoll" style="width:400px;">
            <h2>New Order </br>(when customer created order)</h2>
                <form method="POST" action="{{route('testmail_newOrder')}}">
                    @csrf
        
                    <!-- Password -->
                    <div >
                        <label for="OrderID">OrderID</label>
                        <input type="text" style="color:black;" id="OrderID" name="OrderID" value="" required>
                    </br>
                        {{-- <label for="SessionID">SessionID</label>
                        <input style="color:black;"type="text" id="payment" name="SessionID" value="test" required> --}}
                    </div>
        
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
{{-- showroom order --}}
        <div>
            <div class="pccoll" style="width:400px;">
            <h2>Showroom Order Received > [admin] </br></h2>
                <form method="POST" action="{{route('showroomOrder_fin')}}">
                    @csrf
        
                    <!-- Password -->
                    <div >
                        <label for="OrderID">OrderID</label>
                        <input type="text" style="color:black;" id="OrderID" name="OrderID" value="" required>
                    </br>
                    </div>
        
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
{{-- Payment Completed --}}
        <div>
            <div class="pccoll" style="width:400px;">
            <h2>Payment Completed > [admin] </br></h2>
                <form method="POST" action="{{route('mail.paycom')}}">
                    @csrf
        
                    <!-- Password -->
                    <div >
                        <label for="OrderID">OrderID</label>
                        <input type="text" style="color:black;" id="OrderID" name="OrderID" value="" required>
                    </br>
                    </div>
        
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
{{-- order shipped --}}
        <div class="pccoll" style="width:400px;">
            <h2>Order Shipped > [customer] </br></h2>
                <form method="POST" action="{{route('testmail_orderShipped')}}">
                    @csrf
        
                    <!-- Password -->
                    <div >
                        <label for="OrderID">OrderID</label>
                        <input type="text" style="color:black;" id="OrderID" name="OrderID" value="" required>
                    </br>
                    </div>
        
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
        </div>
{{-- Quotation --}}
        <div class="pccoll" style="width:400px;">
            <h2>Quatation > [customer] </br></h2>
                <form method="POST" action="{{route('testmail_quotation')}}">
                    @csrf
        
                    <!-- Password -->
                    <div >
                        <label for="OrderID">OrderID</label>
                        <input type="text" style="color:black;" id="OrderID" name="OrderID" value="" required>
                    </br>
                    </div>
        
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
            </div>
{{-- PDF --}}
        <div class="pccoll" style="width:400px;">
            <h2>PDF attach test</h2>
                <form method="POST" action="{{route('testmail_pdf')}}">
                    @csrf
        
                    <!-- Password -->
                    <div >
                        <label for="OrderID">OrderID</label>
                        <input type="text" style="color:black;" id="OrderID" name="OrderID" value="" required>
                    </br>
                    </div>
        
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
            </div>
            <hr>
{{-- PDF box --}}
        {{-- <div class="pccoll" style="width:400px;">
            <h2>Box label test</h2>
                <form method="POST" action="{{route('pdf-boxlabel')}}">
                    @csrf
        
                    <!-- Password -->
                    <div >
                        <label for="OrderID">OrderID</label>
                        <input type="text" style="color:black;" id="OrderID" name="OrderID" value="" required>
                    </br>
                    </div>
        
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
            </div> --}}
            <hr>
{{-- Create SC --}}
        <div class="pccoll" style="width:400px;">
            <h2>Create SC</h2>
                <form method="POST" action="{{route('order.create_sc')}}">
                    @csrf
        
                    <!-- Password -->
                    <div >
                        <label for="OrderID">OrderID</label>
                        <input type="text" style="color:black;" id="OrderID" name="OrderID" value="" required>
                    </br>
                    </div>
        
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
            </div>
            <hr>
{{-- Discount Voucher --}}
        <div class="pccoll" style="width:400px;">
            <h2>Apply Voucer</h2>
                <form method="POST" action="{{route('cart-voucher')}}">
                    @csrf
                    <!-- Password -->
                    <div >
                        <label for="voucher">Voucher Code</label>
                        <input type="text" style="color:black;" id="voucher" name="voucher" value="" required>
                    </br>
                    </div>
        
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
            </div>
{{-- Discount Voucher --}}
        <div class="pccoll" style="width:400px;">
            <h2>Test base discount function</h2>
                <form method="POST" action="{{route('test-discount')}}">
                    @csrf
                    <!-- Password -->
                    <div >
                        <input type="text" style="color:black;" id="" name="discount" value="" required>
                    </br>
                    </div>
        
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
            </div>
{{-- PDF - Order info --}}
        {{-- <div class="pccoll" style="width:400px;">
            <h2>PDF : Order info</br></h2>
                <form method="POST" action="{{route('pdf-orderinfo')}}">
                    @csrf
        
                    <!-- Password -->
                    <div >
                        <label for="OrderID">OrderID</label>
                        <input type="text" style="color:black;" id="OrderID" name="OrderID" value="" required>
                    </br>
                    </div>
        
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
            </div> --}}

        <div class="pccoll" style="width:400px;">
            <h2>Test order delete function (get stock back)</h2>
                <form method="POST" action="{{route('cancelOrder')}}">
                    @csrf
                    <!-- Password -->
                    <div >
                        <input type="text" style="color:black;" id="OrderID" name="OrderID" value="" required>
                        </br>
                    </div>
                    <div class="flex justify-end mt-4">
                        <x-button>
                            {{ __('Confirm') }}
                        </x-button>
                    </div>
                </form>
        </div>

        <div class="pccoll" style="width:400px;">
            <h2>Test order delete function (get stock back)</h2>
            <a
                        href="{{ route('test_http') }}"
                        class="flex items-center py-2 px-3 transition-colors hover:bg-slate-800"
                        >
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                        >
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                        />
                        </svg>
                        <p>
                        Login

                        </p>
                </a>
        </div>

    </div>



</x-app-layout>