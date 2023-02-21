@vite(['resources/css/style3.css', 'resources/js/app.js'])


<x-app-layout>
    <div  x-data="productItem({{ json_encode([
        'id' => $product->id,
        'slug' => $product->item_code,
        'image' => $product->image,
        'title' => $product->item_code,
        'price' => $product->retail_price,
        'preorder'=>$product->preorder,
        'cat'=>$product->pcategory_id,
        'addToCartUrl' => route('cart.add', $product)]) 
        }})" class="">

        <!-- <h2>product details</h2> -->
        <div class="productgal">
        <div class="lg:col-span-3">
            <div
                x-data="{
                images: ['{{$product->image}}'],
                activeImage: null,
                prev() {
                    let index = this.images.indexOf(this.activeImage);
                    if (index === 0)
                        index = this.images.length;
                    this.activeImage = this.images[index - 1];
                },
                next() {
                    let index = this.images.indexOf(this.activeImage);
                    if (index === this.images.length - 1)
                        index = -1;
                    this.activeImage = this.images[index + 1];
                },
                init() {
                    this.activeImage = this.images.length > 0 ? this.images[0] : null
                }
                }"
            >
                {{-- <div class="relative"> --}}
                <div >
                    <template x-for="image in images">
                        <div
                            x-show="activeImage === image"
                            {{-- class="aspect-w-3 aspect-h-2" --}}
                        >
                            <img :src="image" alt="" class="pmainpic"/>
                        </div>
                    </template>
                    {{-- <a
                        @click.prevent="prev"
                        class="cursor-pointer bg-black/30 text-white absolute left-0 top-1/2 -translate-y-1/2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </a> --}}
                    {{-- <a
                        @click.prevent="next"
                        class="cursor-pointer bg-black/30 text-white absolute right-0 top-1/2 -translate-y-1/2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </a> --}}
                </div>
                <div class="flex">
                    {{-- <template x-for="image in images">
                        <a
                            @click.prevent="activeImage = image"
                            class="cursor-pointer w-[80px] h-[80px] border border-gray-300 hover:border-purple-500 flex items-center justify-center"
                            :class="{'border-purple-600': activeImage === image}"
                        >
                            <img :src="image" alt="" class="w-auto max-auto max-h-full"/>
                        </a>
                    </template> --}}
                    </div>
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
                    <div x-show="{{$product->preorder}} == 0">
                        <button @click="addToCart($refs.quantityEl.value)"class="addtocart">Add to Cart</button>
                    </div>
                    <div x-show="{{$product->preorder}} == 1">
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
