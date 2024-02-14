
<?php
// /** @var \Illuminate\Database\Eloquent\Collection $products */
?>
<x-shop>

{{-- products --}}
    <?php if ($products->count() === 0): ?>
        <div class="text-center text-gray-600 py-16 text-xl">
            There are no products published
        </div>
    <?php else: ?>
    
    {{-- <h2 class="pagehead">Shop All</h2> --}}
    <br>

        @livewire('shop-scroll')

    <?php endif; ?>
    <div class="footspace"></div>
</x-shop>

{{-- @livewireScripts --}}
