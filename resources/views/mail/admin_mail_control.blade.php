<x-app-layout>
    <div class="pccoll">
        <h1> Admin email control</h1>
{{-- New order --}}
        <div>
            <div class="pccoll" style="width:400px;">
            <h2>New Order </br>(when customer created order)</h2>
                <form onsubmit="return confirm('Are you sure you want to submit?');" method="POST" action="{{route('testmail_newOrder')}}">
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
        {{-- <div>
            <div class="pccoll" style="width:400px;">
            <h2>Order Received > [admin] </br></h2>
                <form method="POST" action="{{route('testmail_showroomOrder')}}">
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
        </div> --}}
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
        {{-- <div class="pccoll" style="width:400px;">
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
            </div> --}}
{{-- PDF --}}
        {{-- <div class="pccoll" style="width:400px;">
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
            <hr> --}}
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

            <div class="pccoll" style="width:400px;">
                <h2>Test order delete function (get stock back)</h2>
                    <form onsubmit="return confirm('Are you sure you want to cancel the order?');" method="POST" action="{{route('cancelOrder')}}">
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

            
        


    </div>

    <div id='loader'>
        <div class="loader"></div>
    </div>


    {{-- <div class="loader-wrapper">
        <span class="loader"><span class="loader-inner"></span></span>
    </div>

    <script>
        $(window).on("load",function(){
          $(".loader-wrapper").fadeOut("slow");
        });
    </script> --}}
    




</x-app-layout>