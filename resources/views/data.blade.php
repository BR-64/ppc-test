@foreach($products as $post)

<div>
    <div class="pccoll">
		<div class="gridHL">

			@foreach($products as $product)
			<div>
				<div class="card2">

					<a href="{{ route('product.view', $product->item_code) }}"
					class="">
						{{-- <img
							src="{{ $product->image }}"
							alt=""
							class="pimage hover:scale-105 hover:rotate-1 transition-transform"
						/> --}}
					</a>
					<div>
						<h5 class="text2 undertext">THB {{number_format($product->retail_price)}}</h5>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
<hr style="margin-top:5px;">

</div>

@endforeach