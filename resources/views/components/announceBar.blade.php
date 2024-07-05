

<style>
 .announce{
  box-sizing: border-box;
  margin:auto;
  height:50px;
  display:flex;
  justify-content: center;
  align-items: center;
  color:#FC6C85;

  font-family: "Libre Baskerville", serif;
  font-weight: 400;
  font-style: normal;
  font-size:0.8rem;

  text-align: center;

  /* text-transform: uppercase; */
 }


</style>

@foreach($sharedData['announce'] as $announce)
  <div class="announce">
    <p>
      {{ $announce->details }}
    </p>
    {{-- <p>midyear sale !! - 20% Discount - storewide : code [midprem20]</p> --}}
  </div>
  @endforeach


