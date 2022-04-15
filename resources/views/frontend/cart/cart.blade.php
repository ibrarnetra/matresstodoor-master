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
   </div><!-- End .container -->
</nav>

<div class="container">
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

   <div class="row">
      <div class="col-lg-8">
         <div class="cart-table-container">
            <table class="table table-cart">
               <thead>
                  <tr>
                     <th class="product-col">Product</th>
                     <th class="price-col">Price</th>
                     <th class="qty-col">Qty</th>
                     <th>Subtotal</th>
                  </tr>
               </thead>
               <tbody>
                  @if (isset($cart) && count($cart) > 0)
                  @foreach ($cart as $key => $item)
                  <tr class="product-row" data-id="{{$key}}">
                     <td class="product-col">
                        <figure class="product-image-container">
                           <a href="{{route('frontend.productDetail', ['slug' => $item['slug']])}}" class="product-image">
                              <img class="img-fluid" style="width: 40%; height: auto; margin: auto;" src="{{asset('storage/product_images/thumbnail/')}}/{{$item['image']}}" alt="{{$item['image']}}">
                           </a>
                        </figure>
                        <h2 class="product-title">
                           <a href="{{route('frontend.productDetail', ['slug' => $item['slug']])}}">
                              {{$item['name']}}
                              @if(count($item['option_arr']) > 0)
                              @foreach($item['option_arr'] as $option)
                              @foreach($option->product_option_values as $option_value)
                              <br>
                              <small>- {{$option->eng_description->name}}: {{$option_value->eng_description->name}}</small>
                              @endforeach
                              @endforeach
                              @endif
                           </a>
                        </h2>
                     </td>
                     <td>$<span id="product-price-{{$key}}">{{setDefaultPriceFormat($item['price'])}}</span></td>
                     <td>
                        <input id="product-quantity-{{$key}}" class="vertical-quantity form-control" type="text" value="{{$item['quantity']}}" min="1">
                     </td>
                     <td>$<span id="product-sub-total-{{$key}}">{{setDefaultPriceFormat($item['price'] * $item['quantity'])}}</span></td>
                  </tr>
                  <tr class="product-action-row" data-id="{{$key}}">
                     <td colspan="4" class="clearfix">
                        @auth('frontend')
                        <div class="float-left">
                           <a href="javascript:void(0);" onclick="addToWishlist(this, '{{route('frontend.addToWishlist', ['id' => $item['slug']])}}')" class="btn-move">Move to Wishlist</a>
                        </div><!-- End .float-left -->
                        @endauth
                        <div class="float-right">
                           <a href="javascript:void(0);" onclick="updateCart(this, '{{route('frontend.update', ['slug' => $key])}}', '{{$key}}')" title="Edit product" class="btn-edit"><span
                                 class="sr-only">Edit</span><i class="icon-pencil"></i></a>
                           <a href="javascript:void(0);" onclick="remove(this, '{{route('frontend.remove' , ['slug' => $key])}}', '{{$key}}')" title="Remove product" class="btn-remove"><span
                                 class="sr-only">Remove</span></a>
                        </div><!-- End .float-right -->
                     </td>
                  </tr>
                  @endforeach
                  @else
                  <tr>
                     <th scope="row" colspan="4" class="text-center">No Data found...</th>
                  </tr>
                  @endif
               </tbody>

               <tfoot>
                  <tr>
                     <td colspan="4" class="clearfix">
                        <div class="float-left">
                           <a href="{{route('frontend.shop')}}" class="btn btn-outline-secondary">Continue Shopping</a>
                        </div><!-- End .float-left -->

                        <div class="float-right">
                           <a href="{{route('frontend.clearCart')}}" class="btn btn-outline-secondary btn-clear-cart">Clear Shopping Cart</a>
                        </div><!-- End .float-right -->
                     </td>
                  </tr>
               </tfoot>
            </table>
         </div><!-- End .cart-table-container -->
      </div><!-- End .col-lg-8 -->

      <div class="col-lg-4">
         <div class="cart-summary">
            <h3>Summary</h3>
            <table class="table table-totals m-0">
               <tbody>
               </tbody>
               <tfoot>
                  <tr>
                     <td>Order Total</td>
                     <td id="order-total">${{setDefaultPriceFormat($order_total)}}</td>
                  </tr>
               </tfoot>
            </table>

            <div class="checkout-methods">
               <a href="{{route('frontend.checkoutView')}}" class="btn btn-block btn-sm btn-primary">Go to Checkout</a>
            </div><!-- End .checkout-methods -->
         </div><!-- End .cart-summary -->
      </div><!-- End .col-lg-4 -->
   </div><!-- End .row -->
</div><!-- End .container -->
@endsection