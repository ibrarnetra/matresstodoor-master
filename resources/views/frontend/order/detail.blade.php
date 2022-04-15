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
            {{strtoupper($title)}}</li>
      </ol>
   </div>
</nav>

<div class="container mb-5">
   <div class="row">
      <div class="col-md-12">
         <div class="row">
            <div class="col-md-4">
               <div class="card">
                  <div class="card-header border-1">
                     <h3 class="card-title">
                        <span><i class="fas fa-shopping-cart"></i> &nbsp;Order Details</span>
                     </h3>
                  </div>

                  <div class="card-body">
                     @if (isset($order->store_name) && $order->store_name != "" && !is_null($order->store_name))
                     <p class="card-text"><i class="fas fa-store-alt"></i>
                        &nbsp; <span title="Store">{{$order->store_name}}</span></p>
                     @endif
                     <p class="card-text"><i class="fas fa-calendar-alt"></i>
                        &nbsp; <span title="Order Date">{{date('Y-m-d', strtotime($order->created_at))}}</span>
                     </p>
                     @if ($order->payment_method_id != 0)
                     <p class="card-text"><i class="far fa-credit-card"></i>
                        &nbsp; <span title="Payment Method">{{$order->payment_method}}
                           ({{$order->payment_method_code}})</span></p>
                     @endif
                     @if ($order->shipping_method_id != 0)
                     <p class="card-text"><i class="fas fa-truck"></i>
                        &nbsp; <span title="Shipping Method">{{$order->shipping_method}}
                           ({{$order->shipping_method_code}})</span></p>
                     @endif
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="card">
                  <div class="card-header border-1">
                     <h3 class="card-title">
                        <span><i class="fas fa-user"></i> &nbsp;Customer Details</span>
                     </h3>
                  </div>

                  <div class="card-body">
                     <p class="card-text"><i class="fas fa-user"></i> &nbsp;
                        <span title="Customer">
                           {{$order->first_name . ' ' . $order->last_name}}
                        </span>
                     </p>
                     @if ($order->customer_group_id != 0)
                     <p class="card-text"><i class="fas fa-user-friends"></i>
                        &nbsp; <span title="Customer Group">{{$order->customer_group->eng_description->name}}</span>
                     </p>
                     @endif
                     <p class="card-text"><i class="fas fa-envelope"></i>
                        &nbsp; <span title="Email">{{$order->email}}</span></p>
                     <p class="card-text"><i class="fas fa-phone"></i>
                        &nbsp; <span title="Telephone">{{$order->telephone}}</span></p>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="card">
                  <div class="card-header border-1">
                     <h3 class="card-title">
                        <span><i class="fas fa-cog"></i> &nbsp;Options</span>
                     </h3>
                  </div>

                  <div class="card-body">
                     <p class="card-text"><i class="fas fa-receipt"></i> &nbsp;<span title="Invoice Number">{{$order->invoice_no}}</span></p>
                  </div>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-header border-1">
                     <h3 class="card-title">
                        <span><i class="fas fa-info-circle"></i> &nbsp;Order # 1</span>
                     </h3>
                  </div>

                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="table-responsive">
                              <table class="table">
                                 <thead>
                                    <tr>
                                       <th style="width: 50%">Payment Address</th>
                                       <th style="width: 50%">Shipping Address</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <td>
                                          <p>{{$order->payment_first_name .' '. $order->payment_last_name}}</p>
                                          <p>{{$order->payment_address_1}}</p>
                                          @if (isset($order->payment_postcode) && !is_null($order->payment_postcode) && $order->payment_postcode == "")
                                          <p>{{$order->payment_postcode}}</p>
                                          @endif
                                          <p>{{$order->payment_country}}</p>
                                          <p>{{$order->payment_zone}}</p>
                                          <p>{{$order->payment_city}}</p>
                                       </td>
                                       <td>
                                          <p>{{$order->shipping_first_name .' '. $order->shipping_last_name}}</p>
                                          <p>{{$order->shipping_address_1}}</p>
                                          @if (isset($order->shipping_postcode) && !is_null($order->shipping_postcode) && $order->shipping_postcode == "")
                                          <p>{{$order->shipping_postcode}}</p>
                                          @endif
                                          <p>{{$order->shipping_country}}</p>
                                          <p>{{$order->shipping_zone}}</p>
                                          <p>{{$order->shipping_city}}</p>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="table-responsive">
                              <table class="table table-striped">
                                 <thead>
                                    <tr>
                                       <th style="width: 40%;">Product</th>
                                       <th style="width: 20%;">Model</th>
                                       <th style="width: 10%; text-align: right;">Qty</th>
                                       <th style="width: 15%; text-align: right;">Unit Price</th>
                                       <th style="width: 15%; text-align: right;">Total</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach ($order->order_products as $product)
                                    <tr>
                                       <td style="width: 40%;">
                                          {{$product->name}}
                                          @if(count($product->order_options) > 0)
                                          @foreach($product->order_options as $option)
                                          <br>
                                          - {{$option->name}}: {{$option->value}}
                                          @endforeach
                                          @endif
                                       </td>
                                       <td style="width: 20%;">{{$product->product->model}}</td>
                                       <td style="width: 10%; text-align: right;">{{$product->quantity}}</td>
                                       <td style="width: 15%; text-align: right;">
                                          @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left !=
                                          ""){{$order->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($product->price)}}
                                       </td>
                                       <td style="width: 15%; text-align: right;">
                                          @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left !=
                                          ""){{$order->currency->symbol_left}}@else $@endif{{setDefaultPriceFormat($product->total)}}
                                       </td>
                                    </tr>
                                    @endforeach
                                    @foreach ($order->order_totals as $total)
                                    <tr>
                                       @if ($total->code == "shipping")
                                       <th scope="row" colspan="4" class="text-right">Shipping ({{$total->title}})</th>
                                       @else
                                       <th scope="row" colspan="4" class="text-right">{{$total->title}}</th>
                                       @endif
                                       <th scope="row" class="text-right">@if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) &&
                                          $order->currency->symbol_left
                                          !=
                                          ""){{$order->currency->symbol_left}}@else $@endif{{$total->value}}</th>
                                    </tr>
                                    @endforeach
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-header border-1">
                     <h3 class="card-title">
                        <span><i class="far fa-comment-dots"></i> &nbsp;Order History</span>
                     </h3>
                  </div>

                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="table-responsive">
                              <table class="table table-striped">
                                 <thead>
                                    <tr>
                                       <th style="width: 15%">Date Added</th>
                                       <th style="width: 55%">Comment</th>
                                       <th style="width: 15%; text-align: center;">Customer Notified</th>
                                       <th style="width: 15%; text-align: center;">Status</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach ($order->order_histories as $order_history)
                                    <tr>
                                       <td>{{date('Y-m-d', strtotime($order_history->created_at))}}</td>
                                       <td>
                                          @if ($order_history->comment == "")
                                          N/A
                                          @else
                                          {{$order_history->comment}}
                                          @endif
                                       </td>
                                       <td style="text-align: center;">
                                          @if ($order_history->notify == 0)
                                          No
                                          @else
                                          Yes
                                          @endif
                                       </td>
                                       <td style="text-align: center;">{{$order_history->order_status->name}}</td>
                                    </tr>
                                    @endforeach
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-12 text-right">
               <a href="{{route('frontend.dashboard', ['page' => 'my-orders'])}}" class="btn btn-primary btn-sm" type="button">My Orders</a>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection