<div class="dropdown-menu">
   <div class="dropdownmenu-wrapper">
      @if (isset($cart) && count($cart) > 0)
      <div class="dropdown-cart-header">
         <span>
            @if ($total_items > 1)
            {{$total_items}}-Items
            @else
            {{$total_items}}-Item
            @endif
         </span>

         <a href="{{route('frontend.cart')}}">View Cart</a>
      </div><!-- End .dropdown-cart-header -->
      <div class="dropdown-cart-products">
         @php
         $total = 0;
         @endphp
         @foreach ($cart as $key => $item)
         @php
         $total += ($item['price'] * $item['quantity'])
         @endphp
         <div class="product">
            <div class="product-details">
               <h4 class="product-title">
                  <a href="{{route('frontend.productDetail', ['slug' => $item['slug']])}}" title="{{$item['name']}}">{{maxCharLen($item['name'], 20)}}</a>
                  <small>
                     @if(count($item['option_arr']) > 0)
                     @foreach($item['option_arr'] as $option)
                     @foreach($option->product_option_values as $option_value)
                     <br>
                     - {{$option->eng_description->name}}: {{$option_value->eng_description->name}}
                     @endforeach
                     @endforeach
                     @endif
                  </small>
               </h4>
               <span class="cart-product-info">
                  <span class="cart-product-qty">{{$item['quantity']}}</span>
                  x ${{$item['price']}}
               </span>
            </div><!-- End .product-details -->

            <figure class="product-image-container">
               <a href="{{route('frontend.productDetail', ['slug' => $item['slug']])}}" class="product-image" title="{{$item['name']}}">
                  <img @if ($item['image']) src="{{asset('storage/product_images/thumbnail/' . $item['image'])}}" alt="{{$item['image']}}" @else src="{{asset('frontend_assets/images/600_600.png')}}"
                     alt="600_600.png" @endif>
               </a>
               <a href="javascript:void(0);" class="btn-remove" title="Remove Product" onclick="remove('-1', '{{route('frontend.remove' , ['slug' => $key])}}')"><i class="icon-retweet"></i></a>
            </figure>
         </div><!-- End .product -->
         @endforeach
      </div><!-- End .cart-product -->
      <div class="dropdown-cart-total">
         <span>Total</span>
         <span class="cart-total-price">${{$total}}</span>
      </div><!-- End .dropdown-cart-total -->
      <div class="dropdown-cart-action">
         <a href="{{route('frontend.checkoutView')}}" class="btn btn-block">Checkout</a>
      </div><!-- End .dropdown-cart-total -->
      @else
      <div class="dropdown-cart-header">
         <span>No Data found...</span>
      </div><!-- End .dropdown-cart-header -->
      @endif
   </div><!-- End .dropdownmenu-wrapper -->
</div>