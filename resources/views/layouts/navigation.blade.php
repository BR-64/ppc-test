{{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
<style>
    @media only screen and (max-width: 900px){
            .spacebox1{
                    align-items: center;
                    position: absolute;
                    left: 0;
                    right: 0;
                    margin-left: 0;
                    /* margin-right: 100px; */
                    width: 200px;
                    text-align: left;

            }

            .mainlogo{
                margin:0;
            }

            

        }
</style>
<header
    x-data="{
        mobileMenuOpen: false,
        cartItemsCount: {{ \App\Helpers\Cart::getCartItemsCount() }},
    }"
    @cart-change.window="cartItemsCount = $event.detail.count"
    class="flex justify-space-between justify-end prembg shadow-md "
>
<div class="spacebox1 ">
    {{-- <div > --}}
        <a href="{{ route('test') }}" class="block p-2"> 
            <img class="mainlogo" src="https://smoootstudio.com/pic/prempracha/pic/ppclogo.png" alt=""> 
        </a>
    {{-- </div> --}}
</div>
{{-- <div>
    <p>Contact Us</p>
</div> --}}
{{-- <div class="spacebox1"></div> --}}
{{-- dropdown 2 //////////////////////////////////////////////////////// --}}
    {{-- <div class="dropbtn">
        <p>Contact Us</p>
    </div> --}}
    <div class="dropdown search">
        <div class="dropbtn2" onclick="openSearch()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                  </svg>
        </div>
    </div>
        <div class="dropdown ">
            {{-- <div class="dropbtn">Products</div> --}}
            <div class="dropbtn3">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-shop-window" viewBox="0 0 16 16">
                <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5"/>
            </svg>
            <div class="dropdown-one">
                <a href="{{ route('shopmain') }}" class="dItem">Shop All</a>
                <a href="/prem" class="dItem">PREM</a>
                @foreach($sharedData['filterables'] as $key=>$filter)
                <div id="link1" class="dItem" >
                    <a href="/{{$key}}">
                        <h3 class="drop1">{{$key}}</h3>
                    </a>
                        <div class="dropdown-two">
                            {{-- <h1 class="">{{$key}}</h1> --}}
            
                    @foreach($filter as $key=>$cat)
                        @if($cat->collection)
                            <a class="dItem" href="/shop/f2?filter%5Bcollection%5D={{$cat['collection']}}">
                                {{$cat['collection']}}
                            </a>
                        @elseif($cat->category)
                            <a class="dItem" href="/shop/f2?filter%5Bcategory%5D={{$cat['category']}}">
                                {{$cat['category']}}
                            </a>
                        @elseif($cat->type)
                            <a class="dItem" href="/shop/f2?filter%5Btype%5D={{$cat['type']}}">
                                {{$cat['type']}}
                            </a>
                        @elseif($cat->color)
                            <a class="dItem" href="/shop/f2?filter%5Bcolor%5D={{$cat['color']}}">
                                {{$cat['color']}}
                            </a>
                        @elseif($cat->finish)
                            <a class="dItem" href="/shop/f2?filter%5Bfinish%5D={{$cat['finish']}}">
                                {{$cat['finish']}}
                            </a>
                        @endif
                    @endforeach

                    </div>
                    </div>
                @endforeach

        
            </div>
            </div>
        </div>

        <div class="dropbtn3">
            <a
            href="{{ route('cart.index') }}"
            class="relative flex justify-end py-6 transition-colors ">
            {{-- <div class="flex items-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2 "
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                    />
                </svg>
            </div> --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-cart2" viewBox="0 0 16 16">
                <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l1.25 5h8.22l1.25-5zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>
            </svg>
            <!-- Cart Items Counter -->
            <small
                x-show="cartItemsCount"
                x-transition
                x-text="cartItemsCount"
                x-cloak
                class="py-[2px] px-[8px] rounded-full bg-red-500"
            ></small>
            <!--/ Cart Items Counter -->
            </a>
        </div>


    <!-- Responsive Menu -->
    <div
        class="block fixed z-10 top-0 bottom-0 height h-full w-[220px] transition-all bg-slate-900 md:hidden"
        :class="mobileMenuOpen ? 'left-0' : '-left-[220px]'"
    >
        <ul>
            <li>
            </li>
            @if (!Auth::guest())
                <li x-data="{open: false}" class="relative">
                    <a
                        @click="open = !open"
                        class="cursor-pointer flex justify-between items-center py-2 px-3 "
                    >
              <span class="flex items-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                  <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
                My Account
              </span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </a>
                    <ul
                        x-show="open"
                        x-transition
                        class="z-10 right-0 bg-slate-800 py-2"
                    >
                        <li>
                            <a href="{{ route('profile') }}" class="flex px-3 py-2 hover:bg-slate-900">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                                My Profile
                            </a>
                        </li>
                        <li class="hover:bg-slate-900">
                            <a
                                href="{{ route('order.index') }}"
                                class="flex items-center px-3 py-2 hover:bg-slate-900"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                    />
                                </svg>
                                My Orders
                            </a>
                        </li>
                        <li class="hover:bg-slate-900">
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a href="{{ route('logout') }}"
                                   class="flex items-center px-3 py-2 hover:bg-slate-900"
                                   onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                        />
                                    </svg>
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <li>
                        <a
                        href="{{ route('login') }}"
                        class="flex items-center py-2 px-3 transition-colors hover:bg-slate-800"
                        >
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                        >
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                        />
                        </svg>
                        <p>
                        Login

                        </p>
                </a>
                </li>
                <li class="px-3 py-3">
                    <a
                        href="{{ route('register') }}"
                        class="block text-center text-white bg-emerald-600 py-2 px-3 rounded shadow-md hover:bg-emerald-700 active:bg-emerald-800 transition-colors w-full"
                    >
                        Register now
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <!--/ Responsive Menu -->
    <div>

    <nav class="hidden md:block">
        <ul class="grid grid-flow-col items-center">
            <li>
            </li>
            @if (!Auth::guest())
                <li x-data="{open: false}" class="relative">
                    <a
                        @click="open = !open"
                        class="cursor-pointer flex items-center py-navbar-item px-navbar-item pr-5 hover:bg-slate-900"
                    >
              <span class="flex items-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                  <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
                My Account
              </span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 ml-2"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </a>
                    <ul
                        @click.outside="open = false"
                        x-show="open"
                        x-transition
                        x-cloak
                        class="absolute z-10 right-0 bg-slate-800 py-2 w-48"
                    >
                        <li>
                            <a
                                href="{{ route('profile') }}"
                                class="flex px-3 py-2 hover:bg-slate-900"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                                My Profile
                            </a>
                        </li>
                        <li>
                            <a
                                href="{{ route('order.index') }}"
                                class="flex px-3 py-2 hover:bg-slate-900"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                    />
                                </svg>
                                My Orders
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a href="{{ route('logout') }}"
                                   class="flex px-3 py-2 hover:bg-slate-900"
                                   onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                        />
                                    </svg>
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <li>
                    <div class="dropdown2">

                        <div class="navitem">
                            <a
                            href="{{ route('login') }}"
                            >
                            {{-- <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                                >
                                <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                />
                            </svg> --}}
                            Login
                            </a>
                        </div>
                    </div>
                </li>
                <li>
                    <a
                        href="{{ route('register') }}"
                        class="inline-flex items-center text-white bg-emerald-600 py-2 px-3 rounded shadow-md hover:bg-emerald-700 active:bg-emerald-800 transition-colors mx-5"
                    >
                        Register now
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>

    <button
        @click="mobileMenuOpen = !mobileMenuOpen"
        class="p-3 block md:hidden"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M4 6h16M4 12h16M4 18h16"
            />
        </svg>
    </button>

{{-- search box --}}
    <div id="myOverlay" class="overlay">
        <div class='overlay_layout'>
            <div class="overlay-content">
                <form action="{{ route('shopf2') }}">
                    <input type="text" placeholder="Search..." name="search" value="{{ @request()->search }}">
                    <button type="submit" class="searchsubmit"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <span class="closebtn" onclick="closeSearch()" title="Close Overlay">x</span>
        </div>
      </div> 

    <script>
        // Open the full screen search box
        function openSearch() {
        document.getElementById("myOverlay").style.display = "block";
        }

        // Close the full screen search box
        function closeSearch() {
        document.getElementById("myOverlay").style.display = "none";
        } 
    </script>
</header>
