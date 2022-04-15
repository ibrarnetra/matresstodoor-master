@extends('frontend.master')

@section('meta')
@include('frontend.common.meta')
@endsection

@push('page_lvl_css')
<link rel="stylesheet" href="{{asset('frontend_assets/')}}/custom/css/category_animate.css">
@endpush

@section('content')

{{-- main slider --}}
@php
$slider = getSliderWithSlides('main-home-page-slider-1')
@endphp
@if ($slider)
<section class="svg-container svg-bottom">
   @if (count($slider->slides) > 0)
   <div class="home-slider owl-carousel owl-carousel-lazy owl-theme">
      @foreach ($slider->slides as $slide)
      <div class="home-slide" class="owl-lazy">
         <img class="owl-lazy" src="{{asset('storage/slider_images/')}}/{{$slide->image}}" data-src="{{asset('storage/slider_images/')}}/{{$slide->image}}" alt="slide-1">
         <img src="{{asset('storage/slider_images/')}}/{{$slide->image}}">
      </div>
      @endforeach
   </div>
   @endif
</section>
@endif

{{-- categories --}}
<section class="svg-top">
   <div class="container">
      <div class=" border-0 ">
         <p class=" display-4 font-weight-bold text-dark mt-5 mb-4 text-center">Categories</p>
         <div class="row">
            @if (count($categories) > 0)
            @foreach ($categories as $key => $value)
            @if ($key > 3)
            @break
            @endif
            <div class="col-sm-12 col-lg-3 col-md-3 justify-content-center text-center mt-3">
               <div class="category-container">
                  <a href="{{route('frontend.shop', ['category' => $value->slug])}}">
                     <img class="lazy" @if ($value->image)
                     data-src="{{asset('storage/category_images/150x150/')}}/{{$value->image}}"
                     alt="{{$value->image}}"
                     @else
                     data-src="{{asset('frontend_assets/images/600_600.png')}}"
                     alt="600_600.png"
                     @endif>
                     <button type="button" class="btn btn-sm btn-primary mt-3 category-container-button">{{$value->eng_description->name}}</button>
                  </a>
               </div>
            </div>
            @endforeach
            @endif
         </div>
      </div>
   </div>
</section>

{{-- cat1 products --}}
@if (count($categories_with_products) > 0 && count($categories_with_products[0]->products) > 0)
<div class="featured-section bg-grey py-5 m-0">
   <div class="container text-center">
      @foreach ($categories_with_products as $key => $value)
      @php
      if($key > 0){
      break;
      }
      @endphp
      <p class="display-4 font-weight-bold text-dark">{{$value->eng_description->name}}</p>

      <div class="featured-products owl-carousel owl-theme owl-dots-top" data-toggle="owl" data-owl-options="{
                        'loop': false,
                        'margin': 20,
                        'autoplay': false,
                        'nav': true,
                        'slideBy': '4',
                        'responsive': {
                          '0': {
                            'items': 2
                          },
                          '700': {
                            'items': 3,
                            'margin': 15
                          },
                          '1200': {
                            'items': 4
                          }
                        }
                    }">
         @foreach ($value->products as $product)
         <div class="product-default inner-icon inner-icon-inline inner-quickview">
            <figure>
               <a href="{{route('frontend.productDetail', ['slug' => $product->slug])}}" title="{{$product->eng_description->name}}">
                  <img class="img-fluid lazy" @if ($product->thumbnail_image)
                  data-src="{{asset('storage/product_images/thumbnail/')}}/{{$product->thumbnail_image->image}}"
                  alt="{{$product->thumbnail_image->image}}"
                  @else
                  data-src="{{asset('frontend_assets/images/600_600.png')}}"
                  alt="600_600.png"
                  @endif>
               </a>
               @if (isset($product->discount) && !is_null($product->discount))
               <div class="label-group">
                  <div class="product-label label-hot">SALE</div>
               </div>
               @endif
               <div class="btn-icon-group">
                  <a class="btn-icon btn-add-cart" @guest('frontend') href="{{route('frontend.signIn')}}" @endguest @auth('frontend') href="javascript:void(0);"
                     onclick="addToWishlist(this, '{{route('frontend.addToWishlist', ['id' => $product->id])}}')" @endauth>
                     <i class="icon-heart"></i>
                  </a>
               </div>

               <a class="btn-quickview" title="Quick View" href="javascript:void(0);" onclick="quickView(this, '{{route('frontend.quickView', ['slug' => $product->slug])}}')">
                  Quick View
               </a>
            </figure>
            <div class="product-details">
               <div class="row">
                  <div class="col-md-6">
                     @if ($product->manufacturer)
                     <a href="{{route('frontend.shop', ['manufacturer' => $product->manufacturer->slug])}}" title="{{$product->manufacturer->name}}">
                        <img class="img-fluid lazy" data-src="{{asset('storage/manufacturer_images/150x150/')}}/{{$product->manufacturer->image}}" alt="{{$product->manufacturer->image}}">
                     </a>
                     @endif
                  </div>
                  <div class="col-md-6">
                     <h2 class="product-title">
                        <a href="{{route('frontend.shop',['product' => $product->slug])}}" title="{{$product->eng_description->name}}">{{maxCharLen($product->eng_description->name, 20)}}</a>
                     </h2>

                     <div class="ratings-container">
                        <div class="product-ratings">
                           <span class="ratings" style="width:{{(getAvgRating($product->approved_reviews_count, $product->approved_reviews) * 20)}}%"></span>
                        </div>
                     </div>
                  </div>
               </div>

               @php
               $base_price = $product->price;
               if (isset($product->discount) && !is_null($product->discount)){
               $base_price -= $product->discount->price;
               }
               @endphp
               <div class="row mt-2">
                  <div class="col-md-6 text-left">
                     @if (count($product->product_options))
                     @foreach ($product->product_options as $product_option)
                     @if (count($product_option->product_option_values))
                     @foreach ($product_option->product_option_values as $product_option_value)
                     <p class="d-inline-block"><strong>{{$product_option_value->eng_description->name}}</strong>
                        <span class="text-primary">@if ($product_option_value->price_prefix == "+")
                           ${{$base_price + $product_option_value->price}}
                           @else
                           ${{$base_price - $product_option_value->price}}
                           @endif
                        </span>
                     </p>
                     @endforeach
                     @endif
                     @endforeach
                     @endif
                  </div>
                  <div class="col-md-6 custom-short-desc">
                     @if ($product->eng_description->short_description)
                     {!! $product->eng_description->short_description !!}
                     @endif
                  </div>
               </div>

               <div class="row mt-2">
                  <div class="col-md-12">
                     <a class="btn btn-sm btn-primary text-white" @if (count($product->product_options) > 0)
                        href="{{route('frontend.productDetail', ['slug' => $product->slug])}}"
                        @else
                        href="javascript:void(0);"
                        onclick="addToCart('{{route('frontend.addToCart', ['slug' => $product->slug])}}', '-1')"
                        @endif
                        type="button">
                        Add to Cart
                     </a>
                  </div>
               </div>
            </div>
         </div>
         @endforeach
      </div>
      @endforeach
   </div>
</div>
@endif

{{-- info section --}}
<div class="container-fluid py-5" style="background-color:#243746;">
   <div class="container text-center bg-white p-5">
      <div class="row">
         <div class="col-md-4 col-sm-12 col-lg-4">
            <img class="custom-section-img lazy" data-src="{{asset('frontend_assets/')}}/custom/icons/link.png" alt="link">
            <p class="font-weight-bold text-dark custom-section-heading">Unbeatable Prices</p>
            <p class="font-weight-semibold custom-section-text">No one Can match our <br> available Price</p>
         </div>
         <div class="col-md-4 col-sm-12 col-lg-4">
            <img class="custom-section-img lazy" data-src="{{asset('frontend_assets/')}}/custom/icons/truck.png" alt="truck">
            <p class="font-weight-bold text-dark custom-section-heading">All over Canada Deliveries</p>
            <p class="font-weight-semibold custom-section-text">24 to 48-hrs delivery in GTA</p>
         </div>
         <div class="col-md-4 col-sm-12 col-lg-4">
            <img class="custom-section-img lazy" data-src="{{asset('frontend_assets/')}}/custom/icons/headphones.png" alt="headphones">
            <p class="font-weight-bold text-dark custom-section-heading">100 Days Sleep Guarntee</p>
            <p class="font-weight-semibold custom-section-text">A mattress with you had bought<br> years ago.</p>
         </div>
      </div>
   </div>
</div>

{{-- sale section --}}
<section class="announcment container mt-4">
   <div class="card" style="height: 400px; 
                                                      background-image: url('{{asset('frontend_assets/')}}/custom/icons/bedroomimage2.png'); 
                                                      background-size:cover; 
                                                      background-repeat:no-repeat; 
                                                      background-position: bottom;">

      <div class="card-img-overlay">
         <div style='margin-top:100px; margin-left:40px;' style="">
            <h1 class="text-white mb-1">Big Brands on Big Sale</h1>
            <h1 class="text-white">Upto 70% Off</h1>
            <a href="{{route('frontend.shop')}}" type="button" class="btn btn-danger mt-5 text-white">Shop Now</a>
         </div>
      </div>
   </div>

</section>

{{-- cat2 products --}}
@if (count($categories_with_products) > 1 && count($categories_with_products[1]->products) > 1)
<div class="featured-section bg-grey py-5 m-0">
   <div class="container text-center">
      @foreach ($categories_with_products as $key => $value)
      @php
      if($key == 0){
      continue;
      }
      @endphp
      <p class="display-4 font-weight-bold text-dark">{{$value->eng_description->name}}</p>

      <div class="featured-products owl-carousel owl-theme owl-dots-top" data-toggle="owl" data-owl-options="{
                        'loop': false,
                        'margin': 20,
                        'autoplay': false,
                        'nav': true,
                        'slideBy': '4',
                        'responsive': {
                          '0': {
                            'items': 2
                          },
                          '700': {
                            'items': 3,
                            'margin': 15
                          },
                          '1200': {
                            'items': 4
                          }
                        }
                    }">
         @foreach ($value->products as $product)
         <div class="product-default inner-icon inner-icon-inline inner-quickview">
            <figure>
               <a href="{{route('frontend.productDetail', ['slug' => $product->slug])}}" title="{{$product->eng_description->name}}">
                  <img class="img-fluid lazy" @if ($product->thumbnail_image)
                  data-src="{{asset('storage/product_images/thumbnail/')}}/{{$product->thumbnail_image->image}}"
                  alt="{{$product->thumbnail_image->image}}"
                  @else
                  data-src="{{asset('frontend_assets/images/600_600.png')}}"
                  alt="600_600.png"
                  @endif>
               </a>
               @if (isset($product->discount) && !is_null($product->discount))
               <div class="label-group">
                  <div class="product-label label-hot">SALE</div>
               </div>
               @endif

               <div class="btn-icon-group">
                  <a class="btn-icon btn-add-cart" @guest('frontend') href="{{route('frontend.signIn')}}" @endguest @auth('frontend') href="javascript:void(0);"
                     onclick="addToWishlist(this, '{{route('frontend.addToWishlist', ['id' => $product->id])}}')" @endauth>
                     <i class="icon-heart"></i>
                  </a>
               </div>

               <a class="btn-quickview" title="Quick View" href="javascript:void(0);" onclick="quickView(this, '{{route('frontend.quickView', ['slug' => $product->slug])}}')">
                  Quick View
               </a>
            </figure>
            <div class="product-details">
               <div class="row">
                  <div class="col-md-6">
                     @if ($product->manufacturer)
                     <a href="{{route('frontend.shop', ['manufacturer' => $product->manufacturer->slug])}}" title="{{$product->manufacturer->name}}">
                        <img class="img-fluid lazy" data-src="{{asset('storage/manufacturer_images/150x150/')}}/{{$product->manufacturer->image}}" alt="{{$product->manufacturer->image}}">
                     </a>
                     @endif
                  </div>
                  <div class="col-md-6">
                     <h2 class="product-title">
                        <a href="{{route('frontend.shop',['product' => $product->slug])}}" title="{{$product->eng_description->name}}">{{maxCharLen($product->eng_description->name, 20)}}</a>
                     </h2>

                     <div class="ratings-container">
                        <div class="product-ratings">
                           <span class="ratings" style="width:{{(getAvgRating($product->approved_reviews_count, $product->approved_reviews) * 20)}}%"></span>
                        </div>
                     </div>
                  </div>
               </div>

               @php
               $base_price = $product->price;
               if (isset($product->discount) && !is_null($product->discount)){
               $base_price -= $product->discount->price;
               }
               @endphp
               <div class="row mt-2">
                  <div class="col-md-6 text-left">
                     @if (count($product->product_options))
                     @foreach ($product->product_options as $product_option)
                     @if (count($product_option->product_option_values))
                     @foreach ($product_option->product_option_values as $product_option_value)
                     <p class="d-inline-block"><strong>{{$product_option_value->eng_description->name}}</strong>
                        <span class="text-primary">@if ($product_option_value->price_prefix == "+")
                           ${{$base_price + $product_option_value->price}}
                           @else
                           ${{$base_price - $product_option_value->price}}
                           @endif
                        </span>
                     </p>
                     @endforeach
                     @endif
                     @endforeach
                     @endif
                  </div>
                  <div class="col-md-6 custom-short-desc">
                     @if ($product->eng_description->short_description)
                     {!! $product->eng_description->short_description !!}
                     @endif
                  </div>
               </div>

               <div class="row mt-2">
                  <div class="col-md-12">
                     <a class="btn btn-sm btn-primary text-white" @if (count($product->product_options) > 0)
                        href="{{route('frontend.productDetail', ['slug' => $product->slug])}}"
                        @else
                        href="javascript:void(0);"
                        onclick="addToCart('{{route('frontend.addToCart', ['slug' => $product->slug])}}', '-1')"
                        @endif
                        type="button">
                        Add to Cart
                     </a>
                  </div>
               </div>
            </div>
         </div>
         @endforeach
      </div>
      @endforeach
   </div>
</div>
@endif

{{-- reviews section --}}
<section class="bg-grey mb-0 border-0">
   <div class="container">
      <div class="row row-sm" style="padding: 7rem 0 5rem;">
         <div class="col-sm-12 col-md-8 col-lg-8">
            <img class="img-fluid lazy" data-src="{{asset('frontend_assets/')}}/custom/icons/sofaimage.png" alt="sofa">
            <!-- End .testimonials-slider -->
         </div>

         <div class="review-slider owl-carousel owl-theme col-sm-12 col-md-4 col-lg-4" style="height: 280px;">
            @if (count(phpGrabGoogleReviews()) > 0)
            @foreach (phpGrabGoogleReviews() as $review)
            @if ($review['rating'] < 4) @continue @endif <div class="card border-0 text-center m-0" style="width: 367px;">
               <div class="card-body">
                  <img class="rounded-circle w-25 mx-auto lazy" data-src="{{$review['profile_photo_url']}}" alt="{{$review['author_name']}}">
                  <h2 class="mt-2">{{$review['author_name']}}</h2>
                  <div class="mt-1">
                     @if ($review['rating'] == 4)
                     <span class="fa fa-star text-warning"></span>
                     <span class="fa fa-star text-warning"></span>
                     <span class="fa fa-star text-warning"></span>
                     <span class="fa fa-star text-warning"></span>
                     @else
                     <span class="fa fa-star text-warning"></span>
                     <span class="fa fa-star text-warning"></span>
                     <span class="fa fa-star text-warning"></span>
                     <span class="fa fa-star text-warning"></span>
                     <span class="fa fa-star text-warning"></span>
                     @endif
                  </div>
                  <p class="card-text mt-1">{{substr($review['text'],0,300)."..." }}</p>
               </div>
         </div>
         @endforeach
         @endif
      </div>

   </div>
   </div>
</section>
@endsection

@push('page_lvl_js')
<script src="{{asset('frontend_assets/')}}/custom/home.js"></script>
@endpush