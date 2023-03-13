@vite(['resources/css/style3.css', 'resources/js/app.js'])


<x-app-layout>
    <div  x-data="productItem({{ json_encode([
        'id' => $product->id,
        'slug' => $product->item_code,
        'image' => $product->image,
        'title' => $product->item_code,
        'price' => $product->retail_price,
        'preorder'=>$product->pr_eorder,
        'cat'=>$product->pcategory_id,
        'addToCartUrl' => route('cart.add', $product)]) 
        }})" class="">

        @include('product.gallery')

        <!-- <h2>product details</h2> -->
        <div class="productgal">
        <div class="lg:col-span-3">
            <div            >
                <div >
                    <template x-for="image in images">
                        <div
                            x-show="activeImage === image"
                            {{-- class="aspect-w-3 aspect-h-2" --}}
                        >
                            <img :src="image" alt="" class="pmainpic"/>
                        </div>
                    </template>
       
                    </div>
                </div>
            </div>


        <div class="centercontainer">
            <h3 class="pprice">THB {{number_format($product->retail_price)}}</h3>
            <div class="pdetails2">
            <table>
                <tr>
                <td><h5>code</h5></td>
                <td><p>{{$product->item_code}}</p></td>
                </tr>
                <tr>
                <td><h5>type</h5></td>
                <td><p>{{$product->type}}</p></td>
                </tr>
                <tr>
                <td><h5>color</h5></td>
                <td><p>{{$product->color}}</p></td>
                </tr>
                <tr>
                <td><h5>finish</h5></td>
                <td><p>{{$product->finish}}</p></td>
                </tr>
            </table>
            <table>
                <tr>
                <td><h5>collection</h5></td>
                <td><p>{{$product->collection}}</p></td>
                </tr>
                <tr>
                <td><h5>dimension </br>W-L-H (cm)</h5></td>
                <td><p>{{$product->wlh}}</p></td>
                </tr>
            </table>
            <table class="descript">
                <tr>
                <td><h5>description</h5></td>
                </tr>
                <tr>
                <td>
                    <p class="description">
                        {{$product->product_description}}
                    </p>
                </td>
                </tr>
            </table>
                <div class="stock">
                <p>Stock : xx</p>
                </div>
                <div class="qtyinput">
                    
                    <input
                    type="number"
                    name="quantity"
                    x-ref="quantityEl"
                    value="1"
                min="1"
                class="qtyinputbox focus:border-purple-500 focus:outline-none rounded"/>        
                </div>
                <div class="additem3">
                    <div x-show="{{$product->pre_order}} == 0">
                        <button @click="addToCart($refs.quantityEl.value)"class="addtocart">Add to Cart</button>
                    </div>
                    <div x-show="{{$product->pre_order}} == 1">
                        <button @click="addToCart($refs.quantityEl.value)"class="addtocart">Pre-Order</button>
                    </div>
                </div>
                {{-- <div class="relatedp">
                    <h4>related products</h4>
                    <div class="gridShopcoll">
                        <div class="cardrp">
                            <img class="SCollpic" src="pic/test11.jpg" alt="" />
                            <p class="undertext">$179</p>
                        </div>
                        <div class="cardrp">
                            <img class="SCollpic" src="pic/test10.jpg" alt="" />
                            <p class="undertext">$299</p>
                        </div>
                    </div>
                </div> --}}
                <div class="footspace"></div>
            </div>  
        </div>

    </div>
    
</x-app-layout>
