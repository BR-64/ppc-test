@vite(['resources/css/style3.css', 'resources/js/app.js'])


<x-app-layout>
    <h2>test 1</h2>
    <div  x-data="productItem({{ json_encode([
        'id' => $product->id,
        'slug' => $product->item_code,
        'image' => $product->image,
        'title' => $product->item_code,
        'price' => $product->retail_price,
        'preorder'=>$product->pr_eorder,
        'cat'=>$product->pcategory_id,
        'stock'=>$product->stock,
        'addToCartUrl' => route('cart.add', $product)])
        }})" class="productview">

        @include('product.gallery')

        <div class="productinfo">
            <div class="lg:col-span-3">
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


            <div class="centercontainer">
                <h3 class="pprice mobileshow">THB {{number_format($product->retail_price)}}</h3>
                <div class="pdetails2" >
                    <table>
                        <tr>
                        <td><h5>code</h5></td>
                        <td><p>{{$product->item_code}}</p></td>
                        </tr>
                        <tr>
                        <td><h5>type</h5></td>
                        <td>
                            <a href="/shop/f2?filter%5Btype%5D={{$product->type}}" >
                                <p>{{$product->type}}</p>
                            </a>
                        </td>
                        </tr>
                        <tr>
                        <td><h5>color</h5></td>
                        <td>
                            <a href="/shop/f2?filter%5Bcolor%5D={{$product->color}}">
                                <p>{{$product->color}}</p>
                            </a>
                        </td>
                        </tr>
                        <tr>
                        <td><h5>finish</h5></td>
                        <td>
                            <a href="/shop/f2?filter%5Bfinish%5D={{$product->finish}}">
                                <p>{{$product->finish}}</p>
                            </a>
                        </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                        <td><h5>collection</h5></td>
                        <td>
                            <a href="/shop/f2?filter%5Bcollection%5D={{$product->collection}}">
                                <p>{{$product->collection}}</p>
                            </a>
                        </td>                    
                        </tr>
                        <tr>
                        <td><h5>W-L-H (cm)</h5></td>
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
                                สินค้าทุกชิ้นเป็นสินค้าแฮนด์เมด สีและรูปทรงอาจไม่เหมือนในภาพ แต่ละชิ้นอาจจะมีความแตกต่างกันเล็กน้อย 

<br>All of our products are handmade, therefore, colors and shapes may not be exactly the same as in the picture. Each piece will be slightly different.
                            </p>
                        </td>
                        </tr>
                    </table>
                    <h3 class="pprice desktopshow">THB {{number_format($product->retail_price)}}</h3>
                    <div class="stock">
                    <p>In Stock : {{$stock}}</p>
                    </div>
                    
                    <div x-show="{{$stock}} > 0">
                        <div class="qtyinput">  
                            <div class="decre">
                                <button onclick="decrenum()">-</button>
                            </div>  
                            <input
                            id='qty'
                            type="number"
                            name="quantity"
                            x-ref="quantityEl_s"
                            value="1"
                            min="1"
                        class="qtyinputbox focus:outline-none rounded"/>       
                            <div class="incre">
                                <button  onclick="increnum()">+</button>
                            </div>
                        </div>
                        <div class="additem3">
                            <div x-show="{{$product->pre_order}} == 0">
                                <button @click="addToCart($refs.quantityEl_s.value)"class="addtocart">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                    <div x-show="{{$stock}} <= 0">
                        <div x-show="{{$product->pre_order}} == 0">
                            </br>
                            <p class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">This product is out of stock</p>
                        </div>
                        <div x-show="{{$product->pre_order}} == 1">
                            <div class="qtyinput">  
                                <div class="decre">
                                    <button onclick="decrenum()">-</button>
                                </div>  
                                <input
                                id='qty'
                                type="number"
                                name="quantity"
                                x-ref="quantityEl_p"
                                value="1"
                                min="1"
                            class="qtyinputbox focus:outline-none rounded"/>       
                                <div class="incre">
                                    <button  onclick="increnum()">+</button>
                                </div>
                            </div>    
                            <button @click="addToCart($refs.quantityEl_p.value)"class="addtocart">Pre-Order</button>
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
                    </div>  
                </div>
            </div>
        </div>
    </div>

    <div class="footspace"></div>
    <script>
        var i = 1;
        function increnum() {
            document.getElementById('qty').value = ++i;
        }
        function decrenum() {
            document.getElementById('qty').value = --i;
        }
    </script>
    
</x-app-layout>
