<x-app-layout>

    <div class="container mx-auto lg:w-2/3 p-5 blacktext">
        <h1 class="text-3xl font-bold mb-2">Order #{{$order->id}}</h1>
        <div class="bg-white rounded-lg p-3">
            <table>
                <tbody>
                <tr>
                    <td class="font-bold py-1 px-2">Order #</td>
                    <td>{{$order->id}}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1 px-2">Order Date</td>
                    <td>{{$order->created_at}}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1 px-2">Order Status</td>
                    <td>
                        <span
                            class="text-white py-1 px-2 rounded {{$order->isPaid() ? 'bg-emerald-500' : 'bg-gray-400'}}">
                            {{$order->status}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="font-bold py-1 px-2">Product price</td>
                    <td>{{ number_format($order->total_price) }}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1 px-2 text-red-500">Discount</td>
                    <td class="text-red-500">{{ number_format($order->discount_amount) }}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1 px-2">Shipping</td>
                    <td>{{ number_format($order->shipping) }}</td>
                </tr>
                <tr>
                    <td class="font-bold py-1 px-2">Insurance</td>
                    <td>{{ number_format($order->insurance) }}</td>
                </tr>
                <tr class="border-solid border-2">
                    <td class="font-bold py-1 px-2">Net</td>
                    <td>Thb {{ number_format($order->fullprice) }}</td>
                </tr>
                
                </tbody>
            </table>

            <hr class="my-5"/>

            @foreach($order->items as $item)
                <!-- Order Item -->
                <div class="ordersummary">
                    <a href="{{ route('product.view', $item->product) }}"
                       class="">
                        <img src="{{$item->product->image}}" class="sumpic" alt=""/>
                    </a>
                    <div class="os2">
                            <p>
                                {{$item->product->item_code}}
                            </p>
                        <div class="os2_1">
                            Qty: {{$item->quantity}}
                        </div>
                    </div>
                    <div class="os3">
                        <span class=""> Thb {{number_format($item->unit_price)}} </span>
                    </div>
                </div>
                <hr class="my-3"/>
            @endforeach

            @if (!$order->isPaid())
                <form action="{{ route('cart.checkout-order', $order) }}"
                      method="POST">
                    @csrf
                    <button class="btn-primary flex items-center justify-center w-full mt-3">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                            />
                        </svg>
                        Make a Payment
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
