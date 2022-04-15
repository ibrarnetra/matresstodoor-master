@extends('frontend.master')

@section('meta')
@include('frontend.common.meta')
@endsection
@push("page_lvl_css")
<style>
   .custom-scrollba
   {
      height: auto;
      overflow-y:auto;
      overflow-x:hidden;
   }
::-webkit-scrollbar {
  width: 8px;
}

/* Track */
::-webkit-scrollbar-track {
  background:  white;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: white;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: white;
}
</style>
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
         <nav class="toolbox mb-4">
            <div class="toolbox-left">
               <div class="toolbox-item toolbox-sort">
                  <div class="select-custom">
                     @php
                     $selected = "";
                     if(isset($_GET['order-by']) && !is_null($_GET['order-by']) && $_GET['order-by'] != ""){
                     $selected = $_GET['order-by'];
                     }
                     @endphp
                     <select name="order-by" id="order-by" class="form-control" onchange="filterProducts('{{route('frontend.shop')}}')">
                        <option @if ($selected=="a-z" ) selected @endif value="a-z">Sort by alphabetically: a-z</option>
                        <option @if ($selected=="z-a" ) selected @endif value="z-a">Sort by alphabetically: z-a</option>
                        <option @if ($selected=="date" ) selected @endif value="date">Sort by newness</option>
                        <option @if ($selected=="price-asc" ) selected @endif value="price-asc">Sort by price: low to high</option>
                        <option @if ($selected=="price-desc" ) selected @endif value="price-desc">Sort by price: high to low</option>
                     </select>
                  </div><!-- End .select-custom -->

                  <a href="#" class="sorter-btn" title="Set Ascending Direction"><span class="sr-only">Set Ascending Direction</span></a>
               </div><!-- End .toolbox-item -->
            </div><!-- End .toolbox-left -->

            @if ($products->total() > 0)
            <div class="toolbox-item toolbox-show">
               <label>Showing {{($products->currentPage()-1)* $products->perPage() + 1}}
                  –{{ ($products->currentPage()-1)* $products->perPage() + $products->perPage() }}
                  of {{ $products->total() }} results</label>
            </div><!-- End .toolbox-item -->
            @endif
         </nav>

         <div class="row row-sm">
            @if (count($products) > 0)
            @foreach ($products as $product)
            <div class="col-sm-6 col-md-4 col-lg-4 ">
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
                  <!-- End .product-details -->
               </div>
            </div>
            @endforeach
            @endif
         </div>

         @if ($products->total() > 0)
         <nav class="toolbox toolbox-pagination">
            <div class="toolbox-item toolbox-show">
               <label>Showing {{($products->currentPage()-1)* $products->perPage() + 1}}
                  –{{ ($products->currentPage()-1)* $products->perPage() + $products->perPage() }}
                  of {{ $products->total() }} results</label>
            </div><!-- End .toolbox-item -->

            @php
            $q = (isset($_GET['q'])) ? $_GET['q']: "";
            $category = isset($_GET['category']) ? $_GET['category'] : "";
            $manufacturer = isset($_GET['manufacturer']) ? $_GET['manufacturer'] : "";
            $variant = isset($_GET['variant']) ? $_GET['variant'] : "";
            $price_filter = isset($_GET['price-filter']) ? $_GET['price-filter'] : "";
            $order_by = isset($_GET['order-by']) ? $_GET['order-by'] : "";
            $params = [];
            if ($q != "") {
            $params['q'] = $q;
            }
            if ($category != "") {
            $params['category'] = $category;
            }
            if ($manufacturer != "") {
            $params['manufacturer'] = $manufacturer;
            }
            if ($variant != "") {
            $params['variant'] = $variant;
            }
            if ($price_filter != "") {
            $params['price-filter'] = $price_filter;
            }
            if ($order_by != "") {
            $params['order-by'] = $order_by;
            }
            @endphp
            {{$products->appends($params)->links('frontend.shop.pagination')}}
         </nav>
         @else
         <div class="row row-sm">
            <div class="col-md-12">
               <h4>No Data Found...</h4>
            </div>
         </div>
         @endif
      </div><!-- End .col-lg-9 -->

      <aside class="sidebar-shop col-lg-3 order-lg-first custom-scrollba">
         <div class="sidebar-wrapper">
            {{-- categories --}}
            <div class="widget p-0 m-0">
               <h3 class="widget-title">
                  <a data-toggle="collapse" href="#widget-body-1" role="button" aria-expanded="true" aria-controls="widget-body-1">Category</a>
               </h3>

               <div class="collapse show" id="widget-body-1">
                  <div class="widget-body">
                     <ul class="cat-list">
                        @if (count(getAllCategories()) > 0)
                        @foreach (getAllCategories() as $parent)
                        <li>
                           <a href="{{route('frontend.shop',['category' => $parent->slug])}}">{{$parent->eng_description->name}} ({{$parent->products_count}})</a>
                        </li>
                        @if (count($parent->children) > 0)
                        @foreach ($parent->children as $child)
                        <li>
                           <a href="{{route('frontend.shop',['category' => $child->slug])}}">- {{$child->eng_description->name}} ({{$child->products_count}})</a>
                        </li>
                        @endforeach
                        @endif
                        @endforeach
                        @endif
                     </ul>
                  </div><!-- End .widget-body -->
               </div><!-- End .collapse -->
            </div>

            {{-- manufactures --}}
            <div class="widget p-0 m-0">
               <h3 class="widget-title">
                  <a data-toggle="collapse" href="#widget-body-2" role="button" aria-expanded="false" aria-controls="widget-body-2" class="collapsed">Brands</a>
               </h3>

               <div class="collapse" id="widget-body-2">
                  <div class="widget-body">
                     <ul class="cat-list">
                        @if (count(getAllManufacturers()) > 0)
                        @foreach (getAllManufacturers() as $manufacturer)
                        <li>
                           <a href="{{route('frontend.shop', ['manufacturer' => $manufacturer->slug])}}">{{$manufacturer->name}} ({{$manufacturer->products_count}})</a>
                        </li>
                        @endforeach
                        @endif
                     </ul>
                  </div><!-- End .widget-body -->
               </div><!-- End .collapse -->
            </div>

            {{-- variants --}}
            <div class="widget p-0 m-0">
               <h3 class="widget-title">
                  <a data-toggle="collapse" href="#widget-body-3" role="button" aria-expanded="false" aria-controls="widget-body-3" class="collapsed">Variant</a>
               </h3>

               <div class="collapse" id="widget-body-3">
                  <div class="widget-body">
                     <ul class="cat-list">
                        @if (count(getProductOptions()) > 0)
                        @foreach (getProductOptions() as $key => $option_value)
                        @if ($key > 5)
                        @break
                        @endif
                        <li>
                           <a href="{{route('frontend.shop', ['variant' => $option_value->option_value_id])}}">{{$option_value->option_value->eng_description->name}}</a>
                        </li>
                        @endforeach
                        @endif
                     </ul>
                  </div><!-- End .widget-body -->
               </div><!-- End .collapse -->
            </div>

            {{-- price filter --}}
            <div class="widget p-0 m-0">
               <h3 class="widget-title">
                  <a data-toggle="collapse" href="#widget-body-4" role="button" aria-expanded="false" aria-controls="widget-body-4" class="collapsed">Price</a>
               </h3>

               <div class="collapse" id="widget-body-4">
                  <div class="widget-body">
                     <div class="row">
                        <div class="col-md-12">
                           @php
                           if (isset($_GET['price-filter']) && !is_null($_GET['price-filter']) && $_GET['price-filter'] !="" ){
                           $split_string = explode('-' , $_GET['price-filter']);
                           }
                           @endphp
                           <div class="input-group mb-1">
                              <input type="number" class="form-control form-control-sm" id="min" placeholder="Min" aria-label="Min" onchange="setMinPrice(this)" onkeyup="setMinPrice(this)"
                                 autocomplete="off" @if (isset($_GET['price-filter']) && !is_null($_GET['price-filter']) && $_GET['price-filter'] !="" ) value="{{$split_string[0]}}" @endif>
                              <span class="input-group-text">-</span>
                              <input type="number" class="form-control form-control-sm" id="max" placeholder="Max" aria-label="Max" onchange="setMaxPrice(this)" onkeyup="setMaxPrice(this)"
                                 autocomplete="off" @if (isset($_GET['price-filter']) && !is_null($_GET['price-filter']) && $_GET['price-filter'] !="" ) value="{{$split_string[1]}}" @endif>
                           </div>
                           <input type="hidden" name="price-filter" id="price-filter">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12 d-flex justify-content-between">
                           <button type="button" class="btn btn-sm btn-primary" onclick="filterProducts('{{route('frontend.shop')}}')">Filter</button>
                           <a href="{{route('frontend.shop')}}" type="button" class="btn btn-sm btn-primary">Clear</a>
                        </div>
                     </div>
                  </div><!-- End .widget-body -->
               </div><!-- End .collapse -->
            </div>
         </div><!-- End .sidebar-wrapper -->
      </aside><!-- End .col-lg-3 -->
   </div><!-- End .row -->
</div><!-- End .container -->
@endsection

@push('page_lvl_js')
<script src="{{asset('frontend_assets/')}}/custom/shop.js"></script>
<script>
   function filterProducts() {
      let q = '{{(isset($_GET['q']) && !is_null($_GET['q']) && $_GET['q'] != "") ? $_GET['q'] : ""}}';
      let category = '{{(isset($_GET['category']) && !is_null($_GET['category']) && $_GET['category'] != "") ? $_GET['category'] : ""}}';
      let manufacturer = '{{(isset($_GET['manufacturer']) && !is_null($_GET['manufacturer']) && $_GET['manufacturer'] != "") ? $_GET['manufacturer'] : ""}}';
      let variant = '{{(isset($_GET['variant']) && !is_null($_GET['variant']) && $_GET['variant'] != "") ? $_GET['variant'] : ""}}';
      let price_filter = 
         $("#price-filter").val() !== undefined &&
         $("#price-filter").val() !== NaN &&
         $("#price-filter").val() !== ""
               ? $("#price-filter").val()
               : "";
      let order_by =
         $("#order-by option:selected").val() !== undefined &&
         $("#order-by option:selected").val() !== NaN &&
         $("#order-by option:selected").val() !== ""
               ? $("#order-by option:selected").val()
               : "";

      window.location = shopUrl('{{route('frontend.shop')}}', q, category, manufacturer, variant, price_filter, order_by);
   }
</script>
@endpush