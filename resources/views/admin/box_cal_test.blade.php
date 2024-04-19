<x-app-layout>
    <div class="pccoll">
        <h1> Box calculation test</h1>
{{-- Box calculation test --}}
        <div>
            <div class="pccoll" style="width:400px;">
            <h2>Box calculation test</h2>
            
                <form  method="POST" action="{{route('boxcal')}}">
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
    </div>

    <div id='loader'>
        <div class="loader"></div>
    </div>

    
</x-app-layout>