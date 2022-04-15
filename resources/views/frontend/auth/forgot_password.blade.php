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

   <div class="row justify-content-center">
      <div class="col-md-6 col-sm-12 col-lg-6">
         <form action="{{route('frontend.handleForgotPassword')}}" method="post">
            @csrf
            <div class="card">
               <div class="card-header text-center">
                  Forgot Your Password?
               </div>
               <div class="card-body">
                  <p>Enter the e-mail address associated with your account. Click submit to have a new password e-mailed to you.</p>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="form-group required-field">
                           <label for="email">Email</label>
                           <input id="email" class="form-control my-control" type="email" name="email" required>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">Reset Password</button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection