<x-app-layout>
    <div class="pccoll">
        <h1> Box calculation test</h1>
{{-- Box calculation test : Cubic--}}
        <div>
            <div class="pccoll" style="width:400px;">
            <h2>Box calculation test : Cubic [CM]</h2>
            
                <form  method="POST" action="{{route('boxcal_cubic')}}">
                    @csrf
                            <div >
                        <label for="CBCM">Cubic CM</label>
                        <input type="text" style="color:black;" id="CBCM" name="CBCM" value="" required>
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
{{-- Box calculation test : Weight--}}
        <div>
            <div class="pccoll" style="width:400px;">
            <h2>Box calculation test : Weight [grams]</h2>
            
                <form  method="POST" action="{{route('boxcal_weight')}}">
                    @csrf
                    <div >
                        <label for="Grams">Weight grams</label>
                        <input type="text" style="color:black;" id="Grams" name="Grams" value="" required>
                        </br>
                    </div>
                    <div >
                        <label for="shipCountry">Ship country</label>
                        <input type="text" style="color:black;" id="shipCountry" name="shipCountry" value="THA" required>
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
    </div>

    <div id='loader'>
        <div class="loader"></div>
    </div>

    
</x-app-layout>