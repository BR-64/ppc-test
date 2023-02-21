<x-app-layout>
    Test
    @foreach($products as $filter)
    <a href="#"
    class="">
        <p>{{$filter->collection}}</p>
    </a>
    @endforeach

</x-app-layout>
