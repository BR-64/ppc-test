<x-app-layout>
    <form method="POST" action="{{ route('login') }}" class="w-[400px] mx-auto blacktext ">
        <h2 class="pagehead">
            Login to your account
        </h2>
        <p class="text-center mb-6 text-gray-500">
            or
            <a
                href="{{ route('register') }}"
                style="color:#5bbb64;"
                class=" "
            >
                create new account
            </a>
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        @csrf
        <div class="mb-4">
            <x-input type="email" name="email" placeholder="Your email address" :value="old('email')"/>
        </div>
        <div class="mb-4">
            <x-input type="password" name="password" placeholder="Your password" :value="old('password')" />
        </div>
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center">
                <input
                    id="loginRememberMe"
                    type="checkbox"
                    class="mr-3 rounded border-gray-300 text-purple-500 focus:ring-purple-500"
                    
                />
                <label for="loginRememberMe" class="prodinfo">Remember Me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm"
                style="color:#5bbb64;"
                >
                    Forgot Password?
                </a>
            @endif
        </div>
        <div style="text-align: center">

            <button
            class="addtocart"
            >
            Login
        </button>
    </div>
    </form>
    <div class="footspace"></div>
</x-app-layout>
