@extends('frontend.master')

@section('meta')
@include('frontend.common.meta')
@endsection

@section('content')
<nav aria-label="breadcrumb" class="breadcrumb-nav">
   <div class="container">
      <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="{{route('frontend.home')}}"><i class="icon-home"></i></a></li>
         <li class="breadcrumb-item active" aria-current="page">
            {{strtoupper($title)}}
         </li>
      </ol>
   </div>
</nav>

<div class="container mb-5">
   <div id="custom-alert-success" class="d-none">
      <div class="row">
         <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <span id="alert-msg"></span>
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-md-12">
         <div class="table-responsive">
            <table class="table table-striped table-lg">
               <thead class="thead-dark">
                  <tr>
                     <th class="py-4 px-3" scope="col" style="width: 15%; text-align: center;">Image</th>
                     <th class="py-4 px-3" scope="col" style="width: 30%;">Product</th>
                     <th class="py-4 px-3" scope="col" style="width: 20%; text-align: center;">Stock</th>
                     <th class="py-4 px-3" scope="col" style="width: 20%; text-align: right;">Price</th>
                     <th class="py-4 px-3" scope="col" style="width: 15%; text-align: center;">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @if (count($wishlist->products) > 0)
                  @foreach ($wishlist->products as $product)
                  @php
                  $price = $product->price;
                  if(isset($product->discount) && !is_null($product->discount)){
                  $price -= $product->discount->price;
                  }
                  @endphp
                  <tr>
                     <td style="width: 15%;">
                        <img class="img-fluid" style="width: 40%; height: auto; margin: auto;" @if ($product->thumbnail_image)
                        src="{{asset('storage/product_images/thumbnail/')}}/{{$product->thumbnail_image->image}}"
                        alt="{{$product->thumbnail_image->image}}"
                        @else
                        src="{{asset('frontend_assets/images/600_600.png')}}"
                        alt="600_600.png"
                        @endif>
                     </td>
                     <th scope="row" style="width: 30%;">
                        {{$product->eng_description->name}}
                     </th>
                     <th scope="row" style="width: 20%; text-align: center;">
                        {{$product->stock_status->name}}
                     </th>
                     <th scope="row" style="width: 20%; text-align: right;">
                        ${{$price}}
                     </th>
                     <td style="width: 15%; text-align: center;">
                        <a href="{{route('frontend.productDetail' , ['slug' => $product->slug])}}" class="btn btn-sm p-2" style="min-width: 30%;" type="button" title="Product Detail">
                           <i class="fas fa-shopping-cart"></i>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-sm p-2" style="min-width: 30%;" type="button" title="Delete Item from Wishlist"
                           onclick="removeFromWishlist(this, '{{route('frontend.removeFromWishlist' , ['id' => $product->id])}}')">
                           <i class="fas fa-trash-alt"></i>
                        </a>
                     </td>
                  </tr>
                  @endforeach
                  @else
                  <tr>
                     <th scope="row" colspan="5" class="text-center">No Data found...</th>
                  </tr>
                  @endif
               </tbody>
            </table>
         </div>
      </div>
   </div><!-- End .row -->

   <div class="row mt-2">
      <div class="col-md-12 text-right">
         <a href="{{route('frontend.cart')}}" type="button" class="btn btn-primary">View Cart</a>
         <a href="{{route('frontend.checkoutView')}}" type="button" class="btn btn-primary">Checkout</a>
      </div>
   </div>
</div>
@endsection