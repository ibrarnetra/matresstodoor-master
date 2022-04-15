<div class="modal fade" id="quick-view" tabindex="-1" role="dialog" aria-labelledby="quick-view" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header p-0 p-3 custom-modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="product-single-container product-single-default product-quick-view container">
            <div class="row">
               <div class="col-lg-6 col-md-6 product-single-gallery ">
                  <div class="product-slider-container product-item">
                     <div class="product-single-carousel owl-carousel owl-theme">
                        @if (count($product->original_images) > 0)
                        @foreach ($product->original_images as $image)
                        <div class="product-item">
                           <img class="product-single-image" @if ($image->image)
                           src="{{asset('storage/product_images/' . $image->image)}}"
                           data-zoom-image="{{asset('storage/product_images/' . $image->image)}}" alt="{{$image->image}}" @else
                           data-src="{{asset('frontend_assets/images/600_600.png')}}"
                           data-zoom-image="{{asset('frontend_assets/images/600_600.png')}}"
                           alt="600_600.png" @endif
                           />
                        </div>
                        @endforeach
                        @endif
                     </div>
                     <!-- End .product-single-carousel -->
                  </div>
                  <div class="prod-thumbnail row owl-dots" id='carousel-custom-dots'>
                     @if (count($product->original_images) > 0)
                     @foreach ($product->original_images as $image)
                     <div class="col-3 owl-dot">
                        <img @if ($image->image)
                        src="{{asset('storage/product_images/' . $image->image)}}"
                        data-zoom-image="{{asset('storage/product_images/' . $image->image)}}" alt="{{$image->image}}" @else
                        data-src="{{asset('frontend_assets/images/600_600.png')}}"
                        data-zoom-image="{{asset('frontend_assets/images/600_600.png')}}"
                        alt="600_600.png" @endif />
                     </div>
                     @endforeach
                     @endif
                  </div>
               </div><!-- End .col-lg-7 -->

               <div class="col-lg-6 col-md-6 text-left">
                  <div class="product-single-details">
                     <h1 class="product-title">{{$product->eng_description->name}}</h1>
                     <div class="price-box">
                        @if (isset($product->discount) && !is_null($product->discount))
                        <span class="old-price">${{$product->price}}</span>
                        <span class="product-price">${{$product->price - $product->discount->price}}</span>
                        @else
                        <span class="product-price">${{$product->price}}</span>
                        @endif
                     </div><!-- End .price-box -->
                     <div class="product-desc">
                        {!! $product->eng_description->short_description !!}
                     </div><!-- End .product-desc -->

                     <form id="addToCart" action="{{route('frontend.addToCart', ['slug' => $product->slug])}}" method="POST" onsubmit="return ajaxFormSubmit(this, event.preventDefault());">
                        @csrf
                        @if (count($product->product_options) > 0)
                        <div class="row">
                           @php
                           $option_type_order = [];
                           @endphp
                           @foreach($product->product_options as $option)
                           @php
                           array_push($option_type_order, $option->option->type);
                           @endphp

                           @if($option->option->type == "select")
                           <div class="form-group col-md-12 @if($option->required == '1') required-field @endif">
                              <label class="form-label">{{$option->eng_description->name}}</label>
                              <select class="form-control form-control-sm" name="option[{{$option->id}}]" id="option[{{$option->id}}]" data-id="select" @if($option->required == '1')
                                 required
                                 @endif>
                                 @if($option->required != '1') <option value="0">-- None --</option> @endif
                                 @foreach($option->product_option_values as $product_option_value)
                                 <option value="{{$product_option_value->id}}">
                                    {{$product_option_value->eng_description->name}}
                                    ({{$product_option_value->price_prefix."$".$product_option_value->price}})
                                 </option>
                                 @endforeach
                              </select>
                           </div>

                           @elseif($option->option->type == "radio")
                           <div class="form-group col-md-12 @if($option->required == '1') required-field @endif">
                              <label class="form-label">{{$option->eng_description->name}}</label>
                              <div class="row">
                                 @foreach($option->product_option_values as $product_option_value)
                                 <div class="col-md-12 form-group">
                                    <div class="form-check">
                                       <input class="form-check-input" type="radio" id="{{$option->eng_description->name}}-{{$product_option_value->id}}" name="option[{{$option->id}}]"
                                          value="{{$product_option_value->id}}" data-id="radio" @if($option->required == '1')
                                       required @endif />
                                       <label class="form-check-label ml-2" for="{{$option->eng_description->name}}-{{$product_option_value->id}}">
                                          {{$product_option_value->eng_description->name}}
                                          ({{$product_option_value->price_prefix."$".$product_option_value->price}})
                                       </label>
                                    </div>
                                 </div>
                                 @endforeach
                              </div>
                           </div>

                           @elseif($option->option->type == "checkbox")
                           <div class="form-group col-md-12 @if($option->required == '1') required-field @endif">
                              <label class="form-label">{{$option->eng_description->name}}</label>
                              <div class="row">
                                 @foreach($option->product_option_values as $product_option_value)
                                 <div class="col-md-12 form-group">
                                    <div class="form-check">
                                       <input class="form-check-input" type="checkbox" id="{{$option->eng_description->name}}-{{$product_option_value->id}}" name="option[{{$option->id}}][]"
                                          value="{{$product_option_value->id}}" data-id="checkbox" />
                                       <label class="form-check-label ml-2" for="{{$option->eng_description->name}}-{{$product_option_value->id}}">
                                          {{$product_option_value->eng_description->name}}
                                          ({{$product_option_value->price_prefix."$".$product_option_value->price}})
                                       </label>
                                    </div>
                                 </div>
                                 @endforeach
                              </div>
                           </div>

                           @elseif($option->option->type == "text")
                           <div class="form-group col-md-12 @if($option->required == '1') required-field @endif">
                              <label class="form-label">{{$option->eng_description->name}}</label>
                              <input class="form-control" type="text" name="option[{{$option->id}}]" id="option[{{$option->id}}]" value="{{$option->value}}" readonly data-id="text"
                                 @if($option->required ==
                              '1') required @endif>
                           </div>

                           @else
                           <div class="form-group col-md-12 @if($option->required == '1') required-field @endif">
                              <label class="form-label">{{$option->eng_description->name}}</label>
                              <textarea class="form-control" name="option[{{$option->id}}]" id="option[{{$option->id}}]" rows="4" readonly data-id="textarea"
                                 @if($option->required == '1') required @endif>{{$option->value}}</textarea>
                           </div>
                           @endif
                           @endforeach
                        </div>
                        @endif

                        <div class="product-action">
                           <div class="product-single-qty">
                              <input class="horizontal-quantity form-control" type="number" min="1" value="1" id="product-qty" name="qty" required>
                           </div><!-- End .product-single-qty -->

                           <button class="paction add-cart" title="Add to Cart" type="submit">
                              <span>Add to Cart</span>
                           </button>
                           <a class="paction add-wishlist" title="Add to Wishlist" @guest('frontend') href="{{route('frontend.signIn')}}" @endguest @auth('frontend') href="javascript:void(0);"
                              onclick="addToWishlist(this, '{{route('frontend.addToWishlist', ['id' => $product->id])}}')" @endauth>
                              <span>Add to Wishlist</span>
                           </a>
                        </div><!-- End .product-action -->
                     </form>

                  </div><!-- End .product-single-details -->
               </div><!-- End .col-lg-5 -->
            </div><!-- End .row -->
         </div><!-- End .product-single-container -->
      </div>
   </div>
</div>