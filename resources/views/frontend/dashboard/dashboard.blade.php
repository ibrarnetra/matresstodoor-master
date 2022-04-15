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
      @if (!isset($_GET['page']))
      <div class="col-lg-9 order-lg-last dashboard-content">
         <h2>My Dashboard</h2>
         <div class="alert alert-success" role="alert">
            Hello, <strong>{{getConstant('APP_NAME')}} customer!</strong> From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account
            information. Select a
            link below to view or edit information.
         </div><!-- End .alert -->

         <h3>Account Information</h3>

         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-header">
                     Contact Information
                     <a href="{{route('frontend.dashboard' ,['page' => 'account-information'])}}" class="card-edit">Edit</a>
                  </div><!-- End .card-header -->

                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-2">
                           <p>
                              <strong>Full name</strong>
                           </p>
                        </div>
                        <div class="col-md-10">
                           <p>{{Auth::guard('frontend')->user()->first_name .' '. Auth::guard('frontend')->user()->last_name}}</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-2">
                           <p>
                              <strong>E-mail</strong>
                           </p>
                        </div>
                        <div class="col-md-10">
                           <p>{{Auth::guard('frontend')->user()->email}}</p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-2">
                           <p>
                              <strong>Mobile</strong>
                           </p>
                        </div>
                        <div class="col-md-10">
                           <p>{{Auth::guard('frontend')->user()->telephone}}</p>
                        </div>
                     </div>
                  </div><!-- End .card-body -->
               </div><!-- End .card -->
            </div><!-- End .col-md-6 -->
         </div><!-- End .row -->

         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-header">
                     Address Book
                     <a href="{{route('frontend.dashboard' ,['page' => 'address-book'])}}" class="card-edit">Edit</a>
                  </div><!-- End .card-header -->

                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-6">
                           <h4 class="">Default Billing Address</h4>
                           @if (isset(Auth::guard('frontend')->user()->default_address) && !is_null(Auth::guard('frontend')->user()->default_address))
                           <address>
                              {{Auth::guard('frontend')->user()->default_address->first_name .' '. Auth::guard('frontend')->user()->default_address->last_name}} <br>
                              {!! Auth::guard('frontend')->user()->default_address->country->name !!} <br>
                              {!! Auth::guard('frontend')->user()->default_address->zone->name !!} <br>
                              {{Auth::guard('frontend')->user()->default_address->city}} <br>
                              @if (Auth::guard('frontend')->user()->default_address->postcode != '')
                              {{Auth::guard('frontend')->user()->default_address->postcode}} <br>
                              @endif
                           </address>
                           @else
                           <address>
                              You have not set a default shipping address.<br>
                           </address>
                           @endif
                        </div>
                        <div class="col-md-6">
                           <h4 class="">Default Shipping Address</h4>
                           @if (isset(Auth::guard('frontend')->user()->default_address) && !is_null(Auth::guard('frontend')->user()->default_address))
                           <address>
                              {{Auth::guard('frontend')->user()->default_address->first_name .' '. Auth::guard('frontend')->user()->default_address->last_name}} <br>
                              {!! Auth::guard('frontend')->user()->default_address->country->name !!} <br>
                              {!! Auth::guard('frontend')->user()->default_address->zone->name !!} <br>
                              {{Auth::guard('frontend')->user()->default_address->city}} <br>
                              @if (Auth::guard('frontend')->user()->default_address->postcode != '')
                              {{Auth::guard('frontend')->user()->default_address->postcode}} <br>
                              @endif
                           </address>
                           @else
                           <address>
                              You have not set a default shipping address.<br>
                           </address>
                           @endif
                        </div>
                     </div>
                  </div><!-- End .card-body -->
               </div><!-- End .card -->
            </div>
         </div>

         @php
         $is_subbed = isSubbedToNewsletter(Auth::guard('frontend')->user()->email);
         @endphp
         <div class="row">
            <div class="col-md-12">
               <div class="custom-control custom-checkbox m-0 mb-2">
                  <input type="checkbox" class="custom-control-input" @if ($is_subbed) checked @endif id="subscribe" name="subscribe" value="1"
                     onclick="subUnsubToNewsletter(this, '{{route('frontend.subUnsubToNewsletter')}}')">
                  <label class="custom-control-label" for="subscribe">
                     Subscribe to Newsletter
                  </label>
               </div>
            </div>
         </div>
      </div><!-- End .col-lg-9 -->
      @endif

      @if (isset($_GET['page']) && $_GET['page'] == 'account-information')
      <div class="col-lg-9 order-lg-last dashboard-content">
         <h2>Edit Account Information</h2>

         <form action="{{route('frontend.handleUpdate', ['id' => Auth::guard('frontend')->user()->id])}}" method="POST" autocomplete="off">
            @csrf
            <div class="row">
               <div class="col-sm-12">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group required-field">
                           <label for="first_name">First Name</label>
                           <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{Auth::guard('frontend')->user()->first_name}}"
                              required autocomplete="off">
                        </div><!-- End .form-group -->
                        @error('first_name')
                        <div class="invalid-feedback mb-1">
                           {{ $message }}
                        </div>
                        @enderror

                     </div><!-- End .col-md-4 -->
                     <div class="col-md-6">
                        <div class="form-group required-field">
                           <label for="last_name">Last Name</label>
                           <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{Auth::guard('frontend')->user()->last_name}}"
                              required autocomplete="off">
                        </div><!-- End .form-group -->
                        @error('last_name')
                        <div class="invalid-feedback mb-1">
                           {{ $message }}
                        </div>
                        @enderror

                     </div><!-- End .col-md-4 -->
                  </div><!-- End .row -->
               </div><!-- End .col-sm-11 -->
            </div><!-- End .row -->

            <div class="form-group required-field">
               <label for="email">Email</label>
               <input type="email" class="form-control @error('first_name') is-invalid @enderror" id="email" name="email" value="{{Auth::guard('frontend')->user()->email}}" required
                  autocomplete="off">
               @error('email')
               <div class="invalid-feedback mb-1">
                  {{ $message }}
               </div>
               @enderror
            </div><!-- End .form-group -->

            <div class="form-group required-field">
               <label for="telephone">Moblie (xxx-xxx-xxxx)</label>
               <input type="tel" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{Auth::guard('frontend')->user()->telephone}}"
                  placeholder="xxx-xxx-xxxx" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required autocomplete="off">
               @error('telephone')
               <div class="invalid-feedback mb-1">
                  {{ $message }}
               </div>
               @enderror
            </div><!-- End .form-group -->

            <div class="mb-2"></div><!-- margin -->

            <div class="custom-control custom-checkbox">
               <input type="checkbox" class="custom-control-input" id="change-pass-checkbox" value="1">
               <label class="custom-control-label" for="change-pass-checkbox">Change Password</label>
            </div><!-- End .custom-checkbox -->

            <div id="account-chage-pass">
               <h3 class="mb-2">Change Password</h3>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group required-field">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                     </div><!-- End .form-group -->
                  </div><!-- End .col-md-6 -->
               </div><!-- End .row -->
            </div><!-- End #account-chage-pass -->

            <div class="required text-right">* Required Field</div>
            <div class="form-footer">
               <a href="{{route('frontend.dashboard')}}"><i class="icon-angle-double-left"></i>Back</a>

               <div class="form-footer-right">
                  <button type="submit" class="btn btn-primary">Save</button>
               </div>
            </div><!-- End .form-footer -->
         </form>
      </div>
      @endif

      @if (isset($_GET['page']) && $_GET['page'] == 'address-book')
      <div class="col-lg-9 order-lg-last dashboard-content">
         <div class="row">
            <div class="col-md-12">
               <h3>Address Book Entries</h3>
               <div class="row">
                  <div class="col-md-12">
                     <div class="table-responsive">
                        <table class="table table-striped table-lg">
                           <thead class="thead-dark">
                              <tr>
                                 <th class="py-4 px-3" scope="col" style="width: 85%;">Address</th>
                                 <th class="py-4 px-3" scope="col" style="width: 15%;">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @if (count(Auth::guard("frontend")->user()->addresses) > 0)
                              @foreach (Auth::guard("frontend")->user()->addresses as $address)
                              <tr>
                                 <td style="width: 85%;">
                                    <address>
                                       {{$address->first_name .' '. $address->last_name}} <br>
                                       {{$address->city}} <br>
                                       {!! $address->zone->name !!} <br>
                                       @if ($address->postcode != '')
                                       {{$address->postcode}} <br>
                                       @endif
                                       {!! $address->country->name !!}
                                    </address>
                                 </td>
                                 <td style="width: 15%;">
                                    <a href="{{route('addresses.edit', ['id' => $address->id])}}" class="btn btn-sm p-2" style="min-width: 30%;" type="button" title="Edit">
                                       <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{route('addresses.delete', ['id' => $address->id])}}" class="btn btn-sm p-2" style="min-width: 30%;" type="button" title="Delete">
                                       <i class="fas fa-trash-alt"></i>
                                    </a>
                                 </td>
                              </tr>
                              @endforeach
                              @else
                              <tr>
                                 <th scope="row" colspan="2" class="text-center">No data found...</th>
                              </tr>
                              @endif
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-12 d-flex justify-content-between">
                     <a href="{{route('frontend.dashboard')}}" class="btn btn-sm btn-secondary">Back</a>
                     <a href="{{route('addresses.create')}}" class="btn btn-sm btn-primary">New Address</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      @endif

      @if (isset($_GET['page']) && $_GET['page'] == 'my-orders')
      <div class="col-lg-9 order-lg-last dashboard-content">
         <div class="row">
            <div class="col-md-12">
               <div class="table-responsive">
                  <table class="table table-striped table-lg">
                     <thead class="thead-dark">
                        <tr>
                           <th class="py-4 px-3" scope="col" style="width: 20%;">Invoice #</th>
                           <th class="py-4 px-3" scope="col" style="width: 20%;">Customer</th>
                           <th class="py-4 px-3" scope="col" style="width: 15%; text-align: right;">No. of Products</th>
                           <th class="py-4 px-3" scope="col" style="width: 15%; text-align: right;">Total</th>
                           <th class="py-4 px-3" scope="col" style="width: 15%; text-align: center;">Date Added</th>
                           <th class="py-4 px-3" scope="col" style="width: 15%; text-align: center;">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if (count($orders) > 0)
                        @foreach ($orders as $key => $order)
                        <tr>
                           <td class="py-3 px-3" scope="row" style="width: 20%;">{{$order->invoice_no}}</td>
                           <td class="py-3 px-3" style="width: 20%;">{{$order->first_name . ' ' . $order->last_name}}</td>
                           <td class="py-3 px-3" style="width: 15%; text-align: center;">{{$order->order_products_count}}</td>
                           <td class="py-3 px-3" style="width: 15%; text-align: right;">{{$order->total}}</td>
                           <td class="py-3 px-3" style="width: 15%; text-align: center;">{{date('Y-m-d', strtotime($order->created_at))}}</td>
                           <td class="py-3 px-3" style="width: 10%; text-align: center;">
                              <a href="{{route('frontend.orderDetail' , ['id' => $order->id])}}" class="btn btn-sm p-2" style="min-width: 30%;" type="button" title="Order Detail"><i
                                    class="fas fa-eye"></i></a>
                           </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                           <th scope="row" colspan="6" class="text-center">No Data found...</th>
                        </tr>
                        @endif
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
      @endif

      <aside class="sidebar col-lg-3">
         <div class="widget widget-dashboard">
            <h3 class="widget-title">My Account</h3>

            <ul class="list">
               <li @if (!isset($_GET['page'])) class="active" @endif><a href="{{route('frontend.dashboard')}}">Account Dashboard</a></li>
               <li @if (isset($_GET['page']) && $_GET['page']=='account-information' ) class="active" @endif><a href="{{route('frontend.dashboard', ['page' => 'account-information'])}}">Account
                     Information</a></li>
               <li @if (isset($_GET['page']) && $_GET['page']=='address-book' ) class="active" @endif><a href="{{route('frontend.dashboard', ['page' => 'address-book'])}}">Address Book</a></li>
               <li @if (isset($_GET['page']) && $_GET['page']=='my-orders' ) class="active" @endif><a href="{{route('frontend.dashboard', ['page' => 'my-orders'])}}">My Orders</a></li>
               {{-- <li @if (isset($_GET['page']) && $_GET['page']=='my-wishlist' ) class="active" @endif><a href="{{route('frontend.dashboard', ['page' => 'my-wishlist'])}}">My Wishlist</a></li> --}}
               <li><a href="{{route('frontend.logout')}}">Logout</a></li>
            </ul>
         </div><!-- End .widget -->
      </aside><!-- End .col-lg-3 -->
   </div><!-- End .row -->
</div>

@endsection

@push('page_lvl_js')
<script src="{{asset('frontend_assets/')}}/custom/auth.js"></script>
@endpush