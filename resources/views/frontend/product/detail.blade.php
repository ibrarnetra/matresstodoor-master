@extends('frontend.master')

@section('meta')
@include('frontend.common.meta')
@endsection

@push('page_lvl_css')
<link rel="stylesheet" href="{{asset('frontend_assets/')}}/custom/css/review.css">
@endpush

@section('content')
<nav aria-label="breadcrumb" class="breadcrumb-nav">
   <div class="container">
      <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="{{route('frontend.home')}}"><i class="icon-home"></i></a></li>
         <li class="breadcrumb-item active" aria-current="page">
            {{strtoupper($title)}}
         </li>
      </ol>
   </div><!-- End .container -->
</nav>
<div class="container">
   <div class="row">
      <div class="col-lg-9">
         <div class="product-single-container product-single-default">
            <div class="row">
               <div class="col-lg-7 col-md-6 product-single-gallery">
                  <div class="product-slider-container product-item">

                     @if (isset($product->discount) && !is_null($product->discount))
                     <div class="label-group">
                        <div class="product-label label-hot">SALE</div>
                     </div>
                     @endif

                     <div class="product-single-carousel owl-carousel owl-theme">
                        @if (count($product->original_images) > 0)
                        @foreach ($product->original_images as $image)
                        <div class="product-item">
                           <img class="product-single-image" @if ($image->image) src="{{asset('storage/product_images/')}}/{{$image->image}}"
                           data-zoom-image="{{asset('storage/product_images/')}}/{{$image->image}}" alt="{{$image->image}}" @else src="{{asset('frontend_assets/images/600_600.png')}}"
                           data-zoom-image="{{asset('frontend_assets/images/600_600.png')}}"
                           alt="600_600.png" @endif />
                        </div>
                        @endforeach
                        @endif
                     </div>
                     <!-- End .product-single-carousel -->
                     <span class="prod-full-screen">
                        <i class="icon-plus"></i>
                     </span>
                  </div>
                  <div class="prod-thumbnail row owl-dots" id='carousel-custom-dots'>
                     @if (count($product->original_images) > 0)
                     @foreach ($product->original_images as $image)
                     <div class="col-3 owl-dot">
                        <img @if ($image->image)
                        src="{{asset('storage/product_images/'. $image->image)}}" alt="{{$image->image}}" @else
                        src="{{asset('frontend_assets/images/600_600.png')}}"
                        alt="600_600.png" @endif />
                     </div>
                     @endforeach
                     @endif
                  </div>
               </div><!-- End .col-lg-7 -->

               <div class="col-lg-5 col-md-6">
                  @if (session('success'))
                  <div class="row">
                     <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                           {{session('success')}}
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                     </div>
                  </div>
                  @endif

                  <div class="product-single-details">
                     <h1 class="product-title">{{$product->eng_description->name}}</h1>

                     <div class="ratings-container">
                        <div class="product-ratings">
                           <span class="ratings" style="width:{{(getAvgRating($product->approved_reviews_count, $product->approved_reviews) * 20)}}%"></span><!-- End .ratings -->
                        </div><!-- End .product-ratings -->

                        <a href="javascript:void(0);" class="rating-link">( @if ($product->approved_reviews_count == "1")
                           {{$product->approved_reviews_count}} Review
                           @else
                           {{$product->approved_reviews_count}} Reviews
                           @endif )</a>
                     </div><!-- End .product-container -->

                     <div class="price-box">
                        @if (isset($product->discount) && !is_null($product->discount))
                        <span class="old-price">${{$product->price}}</span>
                        <input type="hidden" id="base-price" value="{{$product->price - $product->discount->price}}">
                        <span class="product-price">${{$product->price - $product->discount->price}}</span>
                        @else
                        <input type="hidden" id="base-price" value="{{$product->price}}">
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
                                 @if ($product_option_value->eng_description)
                                 <option value="{{$product_option_value->id}}" data-prefix="{{$product_option_value->price_prefix}}" data-price="{{$product_option_value->price}}">
                                    {{$product_option_value->eng_description->name}}
                                    {{-- ({{$product_option_value->price_prefix."$".$product_option_value->price}}) --}}
                                 </option>
                                 @endif
                                 @endforeach
                              </select>
                           </div>

                           @elseif($option->option->type == "radio")
                           <div class="form-group col-md-12 @if($option->required == '1') required-field @endif">
                              <label class="form-label">{{$option->eng_description->name}}</label>
                              <div class="row">
                                 @foreach($option->product_option_values as $product_option_value)
                                 @if ($product_option_value->eng_description)
                                 <div class="col-md-12 form-group">
                                    <div class="form-check">
                                       <input class="form-check-input" type="radio" id="{{$option->eng_description->name}}-{{$product_option_value->id}}" name="option[{{$option->id}}]"
                                          value="{{$product_option_value->id}}" data-id="radio" @if($option->required == '1')
                                       required @endif data-prefix="{{$product_option_value->price_prefix}}" data-price="{{$product_option_value->price}}"/>
                                       <label class="form-check-label ml-2" for="{{$option->eng_description->name}}-{{$product_option_value->id}}">
                                          {{$product_option_value->eng_description->name}}
                                          {{-- ({{$product_option_value->price_prefix."$".$product_option_value->price}}) --}}
                                       </label>
                                    </div>
                                 </div>
                                 @endif
                                 @endforeach
                              </div>
                           </div>

                           @elseif($option->option->type == "checkbox")
                           <div class="form-group col-md-12 @if($option->required == '1') required-field @endif">
                              <label class="form-label">{{$option->eng_description->name}}</label>
                              <div class="row">
                                 @foreach($option->product_option_values as $product_option_value)
                                 @if ($product_option_value->eng_description)
                                 <div class="col-md-12 form-group">
                                    <div class="form-check">
                                       <input class="form-check-input" type="checkbox" id="{{$option->eng_description->name}}-{{$product_option_value->id}}" name="option[{{$option->id}}][]"
                                          value="{{$product_option_value->id}}" data-id="checkbox" data-prefix="{{$product_option_value->price_prefix}}"
                                          data-price="{{$product_option_value->price}}" />
                                       <label class="form-check-label ml-2" for="{{$option->eng_description->name}}-{{$product_option_value->id}}">
                                          {{$product_option_value->eng_description->name}}
                                          {{-- ({{$product_option_value->price_prefix."$".$product_option_value->price}}) --}}
                                       </label>
                                    </div>
                                 </div>
                                 @endif
                                 @endforeach
                              </div>
                           </div>

                           @elseif($option->option->type == "text")
                           <div class="form-group col-md-12 @if($option->required == '1') required-field @endif">
                              <label class="form-label">{{$option->eng_description->name}}</label>
                              <input class="form-control" type="text" name="option[{{$option->id}}]" id="option[{{$option->id}}]" value="{{$option->value}}" readonly data-id="text"
                                 @if($option->required
                              == '1') required @endif>
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

                        <div class="sticky-header">
                           <div class="container">
                              <div class="sticky-img mr-4">
                                 <img @if ($product->thumbnail_image)
                                 src="{{asset('storage/product_images/thumbnail/')}}/{{$product->thumbnail_image->image}}"
                                 alt="{{$product->thumbnail_image->image}}" @else
                                 src="{{asset('frontend_assets/images/600_600.png')}}"
                                 alt="600_600.png" @endif
                                 />
                              </div>
                              <div class="sticky-detail">
                                 <div class="sticky-product-name">
                                    <h2 class="product-title" style="margin-top: 0px;">{{$product->eng_description->name}}</h2>
                                    <div class="price-box">
                                       @if (isset($product->discount) && !is_null($product->discount))
                                       <span class="old-price">${{$product->price}}</span>
                                       <span class="product-price">${{$product->price - $product->discount->price}}</span>
                                       @else
                                       <span class="product-price">${{$product->price}}</span>
                                       @endif
                                    </div><!-- End .price-box -->
                                 </div>
                              </div><!-- End .sticky-detail -->
                              <a href="javascript:void(0);" class="paction add-cart" title="Add to Cart" onclick="addToCart('{{route('frontend.addToCart', ['slug' => $product->slug])}}', '-1')">
                                 <span>Add to Cart</span>
                              </a>
                           </div><!-- end .container -->
                        </div><!-- end .sticky-header -->

                        <div class="product-action product-all-icons">
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

         <div class="product-single-tabs">
            <ul class="nav nav-tabs" role="tablist">
               <li class="nav-item">
                  <a class="nav-link active" id="product-tab-desc" data-toggle="tab" href="#product-desc-content" role="tab" aria-controls="product-desc-content" aria-selected="true">Description</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="product-tab-reviews" data-toggle="tab" href="#product-reviews-content" role="tab" aria-controls="product-reviews-content" aria-selected="false">Reviews</a>
               </li>
            </ul>
            <div class="tab-content">
               <div class="tab-pane fade show active" id="product-desc-content" role="tabpanel" aria-labelledby="product-tab-desc">
                  <div class="product-desc-content">
                     {!! $product->eng_description->description !!}
                  </div><!-- End .product-desc-content -->
               </div><!-- End .tab-pane -->

               <div class="tab-pane fade" id="product-reviews-content" role="tabpanel" aria-labelledby="product-tab-reviews">
                  <div class="product-reviews-content">
                     <div class="add-product-review">
                        <div class="row border-bottom">
                           <div class="col-md-8 form-group">
                              <h1 class="d-inline-block">{{getAvgRating($product->approved_reviews_count, $product->approved_reviews)}}</h1>

                              <div class="ratings-container custom-ratings-container">
                                 <div class="product-ratings">
                                    <span class="ratings" style="width:{{(getAvgRating($product->approved_reviews_count, $product->approved_reviews) * 20)}}%"></span>
                                 </div>

                                 <a href="javascript:void(0);" class="rating-link">( @if ($product->approved_reviews_count == "1")
                                    {{$product->approved_reviews_count}} Review
                                    @else
                                    {{$product->approved_reviews_count}} Reviews
                                    @endif )</a>
                              </div>
                           </div>
                           <div class="col-md-4 form-group text-right">
                              <button class="btn btn-sm btn-primary" data-toggle="collapse" href="#review-collapse" role="button" aria-expanded="false" aria-controls="review-collapse">Write a
                                 Review</button>
                           </div>
                        </div><!-- End .product-container -->

                        <div class="row border-top border-bottom collapse" id="review-collapse">
                           <div class="col-md-12 my-5">
                              <form action="{{route('reviews.store')}}" method="POST" id="form" autocomplete="off">
                                 @csrf
                                 <input type="hidden" name="product_id" value="{{$product->id}}">
                                 <div class="row">
                                    <div class="col-md-12 d-flex justify-content-start form-group">
                                       <p class="m-0"><strong>Rating:</strong></p>
                                       <div class="wrapper">
                                          <input type="radio" id="rating-5" value="5" name="rating">
                                          <label for="rating-5"></label>
                                          <input type="radio" id="rating-4" value="4" name="rating">
                                          <label for="rating-4"></label>
                                          <input type="radio" id="rating-3" value="3" name="rating">
                                          <label for="rating-3"></label>
                                          <input type="radio" id="rating-2" value="2" name="rating">
                                          <label for="rating-2"></label>
                                          <input type="radio" id="rating-1" value="1" name="rating" required data-error="#rating_error">
                                          <label for="rating-1"></label>
                                       </div>
                                    </div>
                                    <div class="invalid-feedback" id="rating_error">
                                    </div>
                                 </div>

                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label for="review">Review</label>
                                          <textarea id="review" class="form-control custom-form-control" name="review" rows="1" placeholder="Your Review" required data-error="#review_error"
                                             autocomplete="off"></textarea>
                                          <div class="invalid-feedback" id="review_error">
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div @guest('frontend') class="row" @endguest @auth('frontend') class="row d-none" @endauth>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label for="name">Name</label>
                                          <input type="text" id="name" name="name" class="form-control custom-form-control" @guest('frontend') required data-error="#name_error" @endguest
                                             placeholder="Your Name" autocomplete="off">
                                          <div class="invalid-feedback" id="name_error">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label for="email">Email</label>
                                          <input type="text" id="email" name="email" class="form-control custom-form-control" @guest('frontend') required data-error="#email_error" @endguest
                                             placeholder="Your Email" autocomplete="off">
                                          <div class="invalid-feedback" id="email_error">
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="row">
                                    <div class="col-md-12">
                                       <button class="btn btn-sm btn-primary" type="submit">Submit Review</button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>

                        <div class="row">
                           @if (count($product->approved_reviews))
                           @foreach ($product->approved_reviews as $key => $review)
                           <div class="col-md-12 @if($key + 1 < count($product->reviews)) border-bottom @endif">
                              <div class="row">
                                 <div class="col-md-3">

                                    <div class="row">
                                       <div class="col-md-12">
                                          <div class="header-user">
                                             <i class="icon-user-2"></i>
                                          </div>
                                       </div>
                                    </div>

                                    <div class="row">
                                       <div class="col-md-12">
                                          <p class="m-0">{{$review->name}}</p>
                                       </div>
                                    </div>

                                    <div class="row">
                                       <div class="col-md-12">
                                          <div class="ratings-container">
                                             <div class="product-ratings">
                                                <span class="ratings" @if ($review->rating == "1")
                                                   style="width: 20%;"
                                                   @elseif($review->rating == "2")
                                                   style="width: 40%;"
                                                   @elseif($review->rating == "3")
                                                   style="width: 60%;"
                                                   @elseif($review->rating == "4")
                                                   style="width: 80%;"
                                                   @elseif($review->rating == "5")
                                                   style="width: 100%;"
                                                   @endif></span>
                                             </div>
                                          </div>
                                       </div>
                                    </div>

                                 </div>

                                 <div class="col-md-9">
                                    <div class="row">
                                       <div class="col-md-12">
                                          <p class="my-4">{{$review->review}}</p>
                                       </div>
                                    </div>

                                    <div class="row">
                                       <div class="col-md-12 text-right">
                                          <p class="m-0">Posted {{$review->created_at->diffForHumans()}}</p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @endforeach
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
            </div><!-- End .tab-content -->
         </div><!-- End .product-single-tabs -->
      </div><!-- End .col-lg-9 -->

      <div class="sidebar-overlay"></div>
      <div class="sidebar-toggle"><i class="icon-right-open"></i></div>
      <aside class="sidebar-product col-lg-3 padding-left-lg mobile-sidebar">
         <div class="sidebar-wrapper">
            <div class="widget widget-info">
               <ul>
                  {{-- <li class="m-0 border-top-0">
                     <i class="icon-shipping"></i>
                     <h4>FREE SHIPPING</h4>
                  </li> --}}
                  <li class="m-0 border-top-0">
                     <i class="icon-us-dollar"></i>
                     <h4>100% MONEY<br>BACK GUARANTEE</h4>
                  </li>
                  <li class="m-0">
                     <i class="icon-online-support"></i>
                     <h4>ONLINE<br>SUPPORT 24/7</h4>
                  </li>
               </ul>
            </div><!-- End .widget -->
         </div>
      </aside><!-- End .col-md-3 -->
   </div><!-- End .row -->
</div><!-- End .container -->
@endsection

@push('page_lvl_js')
<script src="{{asset('frontend_assets/')}}/custom/product-detail.js"></script>
<!-- jQuery Validation Plugin -->
<script src="{{asset('/')}}custom/jqueryValidation/jquery.validate.min.js"></script>
<script src="{{asset('/')}}custom/jqueryValidation/additional-methods.min.js"></script>
<script>
   @guest('frontend')
   let customRules = {
      rating: {
         required: true,
      },
      review: {
         required: true,
      },
      name: {
         required: true,
      },
      email: {
         required: true,
         email: true,
      },
   };

   let customMessages = {
      rating: {
         required: "You must select at least 1-star rating.",
      },
      review: {
         required: "The review field is required.",
      },
      name: {
         required: "The name field is required.",
      },
      email: {
         required: "The email field is required.",
      },
   };
@endguest

@auth('frontend')
   let customRules = {
      rating: {
         required: true,
      },
      review: {
         required: true,
      },
   };

   let customMessages = {
      rating: {
         required: "You must select atleast 1-star rating.",
      },
      review: {
         required: "The review field is required.",
      },
   };
@endauth

$("#form").validate({
    errorClass: "is-invalid",
    rules: customRules,
    messages: customMessages,
    errorPlacement: function (error, element) {
        let placement = $(element).data("error");
        if (placement) {
            $(placement).html(error.text());
        }
    },
    success: function (label) {
        $(label).remove();
    },
});

</script>
@endpush