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

<div class="container mb-5">
   <div class="row">
      <div class="col-lg-12">
         <h2 class="mb-2">Your order has been placed!</h2>
         <p>Your order has been successfully processed!</p>
         <p>You can view your order history by going to the <a href="{{route('frontend.dashboard')}}">my account</a> page and by clicking on <a
               href="{{route('frontend.dashboard', ['page' => 'my-orders'])}}">history</a>.</p>
         {{-- <p>If your purchase has an associated download, you can go to the account downloads page to view them.</p> --}}
         <p>Please direct any questions you have to the store owner.</p>
         <p>Thanks for shopping with us online!</p>
      </div>
   </div>
</div>
@endsection