<x-app-layout>
  @include('components.shopcat')


{{-- Filter --}}
    <div class="centercontainer" x-data="{ 
      open: false,
      Fil_coll: [],
      Fil_cat: [],
      Fil_type: [],
      Fil_brand: [],
      Fil_color: [],
      Fil_finish: []}
      ">

      <button class="button1 upp" x-on:click="open = ! open">shop by attributes</button>

      <div 
      x-show="open">
        @foreach($sharedData['filterables'] as $key=>$filter)
        <div class="filter boxy">
          <h1 class="filterhead">{{$key}}</h1>

          @foreach($filter as $key=>$cat)
            @if($cat->collection)
            <div>
              <input type="checkbox" id="{{$cat['collection']}}" value="{{$cat['collection']}}" x-model="Fil_coll" >
              <label for="{{$cat['collection']}}">{{$cat['collection']}}</label>
            </div>
            
            @elseif($cat->category)
            <div>
              <input type="checkbox" id="{{$cat['category']}}" value="{{$cat['category']}}" x-model="Fil_cat" >
              <label for="{{$cat['category']}}">{{$cat['category']}}</label>
            </div>

            @elseif($cat->type)
            <div>
              <input type="checkbox" id="{{$cat['type']}}" value="{{$cat['type']}}" x-model="Fil_type" >
              <label for="{{$cat['type']}}">{{$cat['type']}}</label>
            </div>

            @elseif($cat->brand)
            <div>
              <input type="checkbox" id="{{$cat['brand_name']}}" value="{{$cat['brand_name']}}" x-model="Fil_brand" >
              <label for="{{$cat['brand_name']}}">{{$cat['brand_name']}}</label>
            </div>

            @elseif($cat->color)
            <div>
              <input type="checkbox" id="{{$cat['color']}}" value="{{$cat['color']}}" x-model="Fil_color" >
              <label for="{{$cat['color']}}">{{$cat['color']}}</label>
            </div>

            @elseif($cat->finish)
            <div>
              <input type="checkbox" id="{{$cat['finish']}}" value="{{$cat['finish']}}" x-model="Fil_finish" >
              <label for="{{$cat['finish']}}">{{$cat['finish']}}</label>
            </div>

            @endif
          @endforeach
          </br>
        </div>
        @endforeach
        <div>
          {{-- <input type="text" :value="Fil_coll"> --}}
          <a x-bind:href="'/shop/f'
          +'?filter%5Bcollection%5D='+Fil_coll
          +'&filter%5Bcategory%5D='+Fil_cat
          +'&filter%5Btype%5D='+Fil_type
          +'&filter%5Bbrand%5D='+Fil_brand
          +'&filter%5Bcolor%5D='+Fil_color
          +'&filter%5Bfinish%5D='+Fil_finish
          ">
          <button class="button2">SEARCH</button>
        </a>
      </div>
    </div>
    </div>

{{-- ----- product list     --}}
  <div>
    {{$slot}}
  </div>


</x-app-layout>