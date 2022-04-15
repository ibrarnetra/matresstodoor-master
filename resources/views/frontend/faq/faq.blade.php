@extends('frontend.master')

@section('meta')
@include('frontend.common.meta')
@endsection

@push('page_lvl_css')
<link rel="stylesheet" href="{{asset('frontend_assets/')}}/faq/style.css">
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
   <div class="row pb-4">
      <div class="col mb-4 mb-lg-0">
         <h2>Frequently Asked Questions </h2>
         <div id="accordion" class="accordion accordion-modern-status accordion-modern-status-primary">
            @if (count($faqs) > 0)
            @foreach ($faqs as $key => $faq)
            <div class="card card-default m-0 mb-1">
               <div class="card-header" id="heading{{$key}}">
                  <h4 class="card-title mb-0">
                     <a class="accordion-toggle text-color-dark @if($key != 0) collapsed @endif" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true"
                        aria-controls="collapse{{$key}}">
                        {!! $faq->eng_description->question !!}
                     </a>
                  </h4>
               </div>

               <div id="collapse{{$key}}" class="collapse @if($key == 0) show @endif" aria-labelledby="heading{{$key}}" data-parent="#accordion">
                  <div class="card-body">
                     {!! $faq->eng_description->answer !!}
                  </div>
               </div>
            </div>
            @endforeach
            @endif
         </div>
      </div>
   </div>
</div>
@endsection

@push('page_lvl_js')
<script src="{{asset('frontend_assets/')}}/faq/main.js"></script>
@endpush