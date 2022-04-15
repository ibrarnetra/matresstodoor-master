@extends('frontend.master')

@section('meta')
@include('frontend.common.meta')
@endsection

@push('page_lvl_css')
<style>
   ul {
      list-style-type: disc;
      margin-left: 30px;
   }

   h1,
   h2,
   h3,
   h4,
   h5,
   h6 {
      color: #212529;
      margin: 0 0 16px 0 !important;
   }

   p {
      color: #777 !important;
      line-height: 26px !important;
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

<div class="container mb-5">
   <div class="row">
      <div class="col-lg-12">
         {!! $cmsData->content !!}
      </div>
   </div>
</div>
@endsection