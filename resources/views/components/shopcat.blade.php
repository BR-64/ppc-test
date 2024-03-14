<div class="shopcat">
    <h4>shop by category</h4>
    <div class="gridShopCat">
      <div class="SCat">
          <img src="https://smoootstudio.com/pic/prempracha/pic/cat1.jpg" alt="" />
          <div class="centered">
              <a href="{{ route('shop.cat', "vase") }}">
              Vase
              </a>
          </div>
      </div>
      <div class="SCat">
          <img src="https://smoootstudio.com/pic/prempracha/pic/cat2.jpg" alt="" />
          <div class="centered">
        <a href="{{ route('shop.cat', "tableware") }}">
              Tableware
            </a>
            </div>
      </div>
      <div class="SCat">
          <img src="https://smoootstudio.com/pic/prempracha/pic/cat3.jpg" alt="" />
          <div class="centered">
              <a href="{{ route('shop.cat', "cups and mugs") }}">
              Cup & Mug
            </a>
            </div>
      </div>
      <div class="SCat">
          <img src="https://smoootstudio.com/pic/prempracha/pic/cat6.jpg" alt="" />
          <div class="centered">
              <a href="{{ route('shop.cat', "figurine") }}">
              Figurine
            </a>
            </div>
      </div>
      <div class="SCat">
          <img src="https://smoootstudio.com/pic/prempracha/pic/cat5.jpg" alt="" />
          <div class="centered">
              <a href="{{ route('shop.cat', "Bathroom Accessories") }}">
                Bathroom
              </a>
          </div>
      </div>
      <div class="SCat">
          <img src="https://smoootstudio.com/pic/prempracha/pic/cat4.jpg" alt="" />
          <div class="centered">
              <a href="{{ route('shop.cat', "Decorative items") }}">
              Decoratives
            </a>
            </div>
      </div>
      <div class="SCat">
          <img src="https://smoootstudio.com/pic/prempracha/pic/cat7.jpg" alt="" />
          <div class="centered">
              <a href="{{ route('shop.cat', "planter") }}">
                Planter
              </a>
          </div>
      </div>
      <div class="SCat">
          <img src="https://smoootstudio.com/pic/prempracha/pic/cat8.jpg" alt="" />
          <div class="centered">
              <a href="{{ route('shop.cat', "Wash basin") }}">
                Wash basin
              </a>
          </div>
      </div>
      <div class="SCat">
          <img src="https://smoootstudio.com/pic/prempracha/pic/pchero.jpg" alt="" />
          <div class="centered" style="background-color: red">
            <a href="{{ route('shop.cat', "sp") }}">
                Special Price
              </a>
            </div>
      </div>
    </div>
  </div>

    <div class="shopcat">
      <h4>shop by category</h4>
      <div class="gridShopCat">
        @foreach($sharedData['categories'] as $product)
          <div class="SCat">
            <img src="{{ asset ('/storage/'.$product->image) }}" alt="{{ $product->label }}"/>
              <div class="centered">
                <a href="{{ route('shop.cat', $product->name) }}">
                  {{$product->label}}
                </a>
              </div>
          </div>
        @endforeach
      </div>
    </div>
