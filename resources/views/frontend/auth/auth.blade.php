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

<div class="page-header">
   <div class="container">
      <h1>Login and Create Account</h1>
   </div><!-- End .container -->
</div><!-- End .page-header -->

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
      <div class="col-md-6">
         <div class="heading">
            <h2 class="title">Login</h2>
            <p>If you have an account with us, please log in.</p>
         </div><!-- End .heading -->

         @if (session('error'))
         <div class="mb-1" style="color: red;">
            {{ session('error') }}
         </div>
         @endif

         <form action="{{route('frontend.handleSignIn')}}" method="POST" autocomplete="off">
            @csrf
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" required value="{{old('email')}}" autocomplete="off">
            @error('email')
            <div class="invalid-feedback mb-1">
               {{ $message }}
            </div>
            @enderror

            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required value="{{old('password')}}"
               autocomplete="off">
            @error('password')
            <div class="invalid-feedback mb-1">
               {{ $message }}
            </div>
            @enderror

            <div class="form-footer">
               <button type="submit" class="btn btn-primary">LOGIN</button>
               <a href="{{route('frontend.forgotPassword')}}" class="forget-pass"> Forgot your password?</a>
            </div><!-- End .form-footer -->
         </form>
      </div><!-- End .col-md-6 -->

      <div class="col-md-6">
         <div class="heading">
            <h2 class="title">Create An Account</h2>
            <p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and
               more.</p>
         </div><!-- End .heading -->

         <form action="{{route('frontend.handleSignUp')}}" method="POST" autocomplete="off">
            @csrf
            <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name" required autocomplete="off"
               value="{{old('first_name')}}">
            @error('first_name')
            <div class="invalid-feedback mb-1">
               {{ $message }}
            </div>
            @enderror

            <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last Name" required autocomplete="off"
               value="{{old('last_name')}}">
            @error('last_name')
            <div class="invalid-feedback mb-1">
               {{ $message }}
            </div>
            @enderror

            <input type="tel" id="telephone" name="telephone" class="form-control @error('telephone') is-invalid @enderror" placeholder="Mobile (xxx-xxx-xxxx)" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
               required autocomplete="off" value="{{old('telephone')}}">
            @error('telephone')
            <div class="invalid-feedback mb-1">
               {{ $message }}
            </div>
            @enderror

            <h2 class="title mb-2">Login information</h2>
            <input type="email" id="register_email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="E-mail" required autocomplete="off" value="{{old('email')}}">
            @error('email')
            <div class="invalid-feedback mb-1">
               {{ $message }}
            </div>
            @enderror

            <input type="password" minlength="8" id="register_password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="off"
               value="{{old('password')}}">
            @error('password')
            <div class="invalid-feedback mb-1">
               {{ $message }}
            </div>
            @enderror

            <input type="password" minlength="8" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
               placeholder="Confirm Password">
            @error('password_confirmation')
            <div class="invalid-feedback mb-1">
               {{ $message }}
            </div>
            @enderror

            <div class="custom-control custom-checkbox">
               <input type="checkbox" class="custom-control-input" id="subscribe" name="subscribe" value="1">
               <label class="custom-control-label" for="subscribe">Sign up our Newsletter</label>
            </div>

            <div class="form-footer">
               <button type="submit" class="btn btn-primary">Create Account</button>
            </div><!-- End .form-footer -->
         </form>
      </div><!-- End .col-md-6 -->
   </div><!-- End .row -->
</div><!-- End .container -->
@endsection