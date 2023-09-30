<x-app-layout>
    <div class="container lg:w-2/3 xl:w-2/3 mx-auto blacktext">
        <h1 class="pagehead" style="color:black;">Your Cart Items</h1>

        <div x-data="{
            cartItems: {{
                json_encode(
                    $products->map(fn($product) => [
                        'id' => $product->id,
                        // 'slug' => $product->slug,
                        'image' => $product->image,
                        'title' => $product->item_code,
                        'price' => $product->retail_price,
                        'price_re' => number_format($product->retail_price),
                        'dimension' => $product->wlh,
                        'quantity' => $cartItems[$product->id]['quantity'],
                        'href' => route('product.view', $product->item_code),
                        'removeUrl' => route('cart.remove', $product),
                        'updateQuantityUrl' => route('cart.update-quantity', $product),
                        'type'=>$product->pre_order,
                        'stock'=>$rtStock[($product->item_code)]
                        // 'stock'=>$product->$realtimeStock
                        // 'stock'=>(int)$product->stock->stock
                    ])
                )
            }},
            get cartto(){
                {{-- return number_format('2000') --}}
                return 2000
                {{-- cartTotal() --}}
                {{-- return Math.floor(Math.random() * number) --}}
                {{-- return cartTotal() --}}
            },
            get cartTotal() {
                cartto = this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0).toLocaleString()

                return cartto
            },
            get Total_afterdis(){
                cart = this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0)

                return cart - 1000

            },
            get after_discount(){
                const x = this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0)

                b_discount = 0
                v_discount = {{$vdis_percent}}

                switch (true) {
                    case x < 10000:
                            b_discount =0;
                            break;
                        case x < 30000:
                            b_discount = 0.1;
                            break;
                        case x < 50000:
                            b_discount = 0.15;
                            break;
                        case x < 70000:
                            b_discount = 0.2;
                            break;
                        case x >= 70000:
                            b_discount = 0.25;
                            break;
                  }
                
                  discount=Math.max(b_discount,v_discount)

                  afterdiscount = x-(x*discount)

                  return afterdiscount.toLocaleString()
                    },
            get dis_percent(){
                const x = this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0)

                discount = 0

                    switch (true) {
                        case x < 10000:
                        return 'No Discount';
                        break;
                        case x < 30000:
                        discount = 0.1;
                        return '10%';
                        break;
                        case x < 50000:
                        discount = 0.15;
                        return '15%';
                        break;
                        case x < 70000:
                        discount = 0.2;
                        return '20%';
                        break;
                        case x >= 70000:
                        discount = 0.25;
                        return '25%';
                        break;
                }
            },
            get dis_amount(){
                const x = this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0)

                discount = 0

                    switch (true) {
                        case x < 10000:
                            discount =0;
                            break;
                        case x < 30000:
                            discount = 0.1;
                            break;
                        case x < 50000:
                            discount = 0.15;
                            break;
                        case x < 70000:
                            discount = 0.2;
                            break;
                        case x >= 70000:
                            discount = 0.25;
                            break;
                }

                disAmount = x*discount
                return disAmount.toLocaleString()

            },
            get dis_v_amount(){
                const x = this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0)
                
                discount = {{$vdis_percent}}
                disAmount = x*discount
                return disAmount.toLocaleString()

            }
            
        }" class="bg-white p-4 rounded-lg shadow">
            <!-- Product Items -->
            <template x-if="cartItems.length">
                <div>
                    
<!-- Product Item -->
            <h2>In Stock</h2>
{{-- <h2 >{{$rtStock}}</h2> --}}
                <form action="{{route('checkout.step1')}}" method="get";>
                    <template x-for="product of cartItems" :key="product.id">
{{-- Normal Items --}}
                        <template x-if="product.type == 0">  
                            <div x-data="productItem(product)">
                                <div
                                    class=" w-full flex flex-row items-top gap-4 flex-1">
                                    <div class="piccol">
                                        <a :href="product.href"
                                        class=" flex items-top justify-center overflow-hidden">
                                            <img :src="product.image" class=" piccol object-cover" alt=""/>
                                        </a>
                                    </div>
                                    <div class="flex flex-col justify-between flex-1">
                                        <div class="flex justify-between mb-3">
                                            <div class="prodcol">
                                                <div class="text-sm">
                                                    <h3 class="nomar" x-text="product.title"></h3>
                                                    <p class="nomar"x-text="product.dimension"></p>
                                                </br>
                                                <div class="opacity-25">
                                                    stock : <span x-text="product.stock">
                                                </div>
                                                </div>
                                            </div>
                                            <div class="calcol flex flex-col justify-between items-end">
                                                <div class="text-sm font-semibold items-end">
                                                    THB
                                                    <span x-text="product.price_re">
                                                    </span>
                                                    <p></p>
                                                </div>
                                                <div class="flex items-center">
                                                    <p class="text-sm nomar">Qty:</p>
                                                    <input
                                                        type="number"
                                                        min="1"
                                                        :max="product.stock"
                                                        {{-- value="product.quantity" --}}
                                                        x-model.number="product.quantity"             
                                                        @change="changeQuantity()"
                                                        class="qtycart  border-gray-200"
                                                    />
                                                </div>
                                                    <i class="fa-solid fa-trash binicon" 
                                                    @click.prevent="removeItemFromCart()"></i>
                                                {{-- <div class="qtyinput">  
                                                    <div class="incre_sm">
                                                        <button onclick="decrenum()"><i class="fa-regular fa-square-minus"></i></button>
                                                    </div>  
                                                    <input
                                                    id='qty_sm'
                                                    type="number"
                                                    name="quantity"
                                                    x-ref="quantityEl"
                                                    x-model="product.quantity"
                                                    @change="changeQuantity()"
                                                    class="qtyinputbox focus:outline-none rounded"/>       
                                                    <div class="incre_sm">
                                                        <button  onclick="increnum()"><i class="fa-regular fa-square-plus"></i></button>
                                                    </div>
                                                    <a
                                                        href="#"
                                                        @click.prevent="removeItemFromCart()"
                                                        class="binicon text-purple-600 hover:text-purple-500"
                                                    ><i class="fa-solid fa-trash"></i></a
                                                    >
                                                </div> --}}
                                            </div>
                                                
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-5"/>
                            </div>
                        </template>
                    </template>

                    
                    {{-- Pre-order items  --}}
<h2>Pre-Order</h2>
<p class='notice'>* Pre-Order item will be ready to ship within XX Days after payment</p>
                    <template x-for="product of cartItems" :key="product.id">
                        <template x-if="product.type == 1"> 
                            <div x-data="productItem(product)">
                                <div
                                    class=" w-full flex flex-row items-top gap-4 flex-1">
                                    <div class="piccol">
                                        <a :href="product.href"
                                        class=" flex items-top justify-center overflow-hidden">
                                            <img :src="product.image" class=" piccol object-cover" alt=""/>
                                        </a>
                                    </div>
                                    <div class="flex flex-col justify-between flex-1">
                                        <div class="flex justify-between mb-3">
                                            <div class="prodcol">

                                                <div class="text-sm">
                                                    <h3 class="nomar" x-text="product.title"></h3>
                                                    <p class="nomar"x-text="product.dimension"></p>
                                                </div>
                                            </br>
                                                <div class="opacity-25">
                                                stock : <span x-text="product.stock">
                                                </div>

                                            </div>
                                            <div class="calcol flex flex-col justify-between items-end">
                                                <div class="text-sm font-semibold items-end">
                                                    THB 
                                                    <span x-text="product.price_re">
                                                    </span>
                                                    </div>
                                                <div class="flex items-center">
                                                    <p class="text-sm nomar">Qty:</p>
                                                    <input
                                                        type="number"
                                                        min="1"
                                                        :max="product.stock"
                                                        x-model="product.quantity"
                                                        @change="changeQuantity()"
                                                        class="qtycart  border-gray-200" 
                                                    />
                                                </div>
                                                <i class="fa-solid fa-trash binicon" 
                                                @click.prevent="removeItemFromCart()"></i>
                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                    <hr class="my-5"/>

<!-- subtotal -->

                    {{-- <div class="border-t border-gray-300 pt-4">
                        <p  x-text="`${baseDiscount}`"></p>
                        <p  x-text="`${cartTotal}`">yo</p>
                        <div class="flex justify-between"> --}}

                    <div class="flex justify-between">
                                <span class="font-semibold">Subtotal 
                                    <span class="notice">(Tax included)
                                        {{-- <?php echo number_format(200000, 0, ",", "&#8239;")?>; --}}
                                    </span>
    
                                </span>
                                    
                                <span id="cartTotal" class="text-xl" x-text="`${cartTotal}`"></span>
                    </div>
                    
                    {{-- @if (isset($voucher))
                    <h2>hello</h2>
                    @endif --}}

                    <div x-show="{{$apply_voucher}}=0" id="base_dis" class="flex justify-between">
                            <span class="font-semibold">Discount
                                    <p class='notice' style='padding-left: 2rem;'>
                                         BAHT 10,000 UP DISC. 10%
                                        </br> BAHT 30,000 UP DISC. 15%
                                        </br> BAHT 50,000 UP DISC. 20%
                                        </br> BAHT 70,000 UP DISC. 25%
                                    </p>
                            </span>                                
                            {{-- <span id="cartTotal" class=" tthin" x-text="` ${base_discount}`"></span> --}}
                            <span id="cartTotal" class="tthin" x-text="`${dis_percent}`"></span>
                            <span id="cartTotal" class="notice text-xl" x-text="`-${dis_amount}`"></span>
                            
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold">Voucher </span>
                        @if (isset($voucher))
                        <form method="get" action="{{route('cart.index')}}">
                            @csrf
                            <div >
                                <button class='bg-red-500 hover:bg-red-700 text-white font-bold py-0 px-0 my-1 rounded'>{{ __('clear Voucher') }}</button>
                            </div>
                        </form>
                        <span id="cartTotal" class="tthin">{{$voucher->code}} : {{$voucher->discount_percent}} %</span>
                            <span id="cartTotal" class="notice text-xl" x-text="`-${dis_v_amount}`"></span>
                        @else 
                        {{-- <span></span>
                            <span></span> --}}

                            @endif
                        </div>
                    <div class="flex justify-between">
                        {{-- <h2>Apply Voucer</h2> --}}
                        <form method="POST" action="{{route('cart-voucher')}}">
                            @csrf
                            <div >
                                <input type="text" style="color:black;" id="voucher" name="voucher" value="" required>
                                <button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'>{{ __('Confirm') }}</button>
                            </div>
                            <div>{{$vcheck}}</div>
                        </form>
                
                        
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="font-semibold">After Discount (Tax included)</span>
                        <span id="cartTotal" class="text-xl" x-text="`THB ${after_discount}`"></span>
                        
                    </div>
                    </br>
                        <p class="text-gray-500 mb-6">Shipping calculated at checkout.</p>

                        {{-- <form action="{{route('cart.checkout')}}" method="post"> --}}

                            @csrf
                            <button type="submit" class="btn-primary w-full py-3 text-lg"  onclick="clicked(event)">
                                Proceed to Checkout
                            </button>
                            <input type="hidden" name="checkouttype"  value="paynow">
                            <input type="hidden" name="apply_voucher"  value="{{$apply_voucher}}">
                            
                        </form>
                        {{-- <h1>{{$apply_voucher}}</h1> --}}
                        
                        <br>
                        <div>
                        <form action="{{route('checkout.summary')}}" method="post">
                                @csrf
                            <button type="submit" class="btn-secondary w-full py-3 text-lg" onclick="clicked(event)">
                                    Ask for Quotation
                            </button>
                            <input type="hidden" name="checkouttype"  value="quotation">
                            </form>
                        </div>
                    </div>
                </div>


                
            </template>

<!--/ No Items -->
            <template x-if="!cartItems.length">
                <div class="text-center py-8 text-gray-500">
                    You don't have any items in cart
                </div>
            </template>

        </div>
    </div>
    <div class="footspace"></div>

    <script>
        var i = 1;
        function increnum() {
            document.getElementById('qty_sm').value = ++i;
        }
        function decrenum() {
            document.getElementById('qty_sm').value = --i;
        }

    // if (){
    //     document.getElementById('base_dis').style.display = 'none'
    // }

        

function format(n, sep, decimals) {
    sep = sep || "."; // Default to period as decimal separator
    decimals = decimals || 2; // Default to 2 decimals

    return n.toLocaleString().split(sep)[0]
        + sep
        + n.toFixed(decimals).split(sep)[1];
}

// document.getElementById("totalprice").innterHTML=cartto()

    function clicked(e)
    {
        if(!confirm('Do you want to create order?')) {
            e.preventDefault();
        }
    }

    </script>

</x-app-layout>

