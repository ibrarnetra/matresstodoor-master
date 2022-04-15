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
      <div class="col-md-12">
         <div class="card">
            <div class="card-body">
               <h3 class="card-title">{{$title}}</h5>
                  <div class="row mt-2">
                     <div class="col-md-12">
                        <form class="m-0 p-0" method="POST" @if ($type=="create" ) action="{{route('addresses.store')}}" @else action="{{route('addresses.update', ['id' => $id])}}" @endif
                           autocomplete="off">
                           @csrf
                           <input type="hidden" name="customer_id" value="{{Auth::guard('frontend')->user()->id}}">
                           <input type="hidden" name="type" id="type" value="{{$type}}">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group required-field">
                                    <label for="first_name">First Name</label>
                                    <input class="form-control my-control" type="text" name="first_name" id="first_name" placeholder="First Name" @if ($type=="create" ) value="{{old('first_name')}}"
                                       @else value="{{old('first_name') ?: $modal['first_name']}}" @endif required autocomplete="off">
                                 </div>
                                 <small class="text-red" id="first_name_error"></small>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group required-field">
                                    <label for="last_name">Last Name</label>
                                    <input class="form-control my-control" type="text" name="last_name" id="last_name" placeholder="Last Name" @if ($type=="create" ) value="{{old('last_name')}}" @else
                                       value="{{old('last_name') ?: $modal['last_name']}}" @endif required autocomplete="off">
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label for="telephone">Mobile (xxx-xxx-xxxx)</label>
                                    <input type="tel" placeholder="xxx-xxx-xxxx" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" autocomplete="off" class="form-control my-control" name="telephone" id="telephone"
                                       @if ($type=="create" ) value="{{old('telephone')}}" @else value="{{old('telephone') ?: $modal['telephone']}}" @endif>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-md-12">
                                 <input type="hidden" name="lat" id="lat" @if ($type=="create" ) value="{{old('lat')}}" @else value="{{old('lat') ?: $modal['lat']}}" @endif>
                                 <input type="hidden" name="lng" id="lng" @if ($type=="create" ) value="{{old('lng')}}" @else value="{{old('lng') ?: $modal['lng']}}" @endif>
                                 <div class="form-group required-field">
                                    <label for="address_1">Address 1</label>
                                    <input class="form-control my-control" type="text" name="address_1" id="address_1" placeholder="Address 1" @if ($type=="create" ) value="{{old('address_1')}}" @else
                                       value="{{old('address_1') ?: $modal['address_1']}}" @endif required autocomplete="off">
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <label for="address_2">Address 2</label>
                                    <input class="form-control my-control" type="text" name="address_2" id="address_2" placeholder="Address 2" @if ($type=="create" ) value="{{old('address_2')}}" @else
                                       value="{{old('address_2') ?: $modal['address_2']}}" @endif autocomplete="off">
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group required-field">
                                    <label for="city">City</label>
                                    <input class="form-control my-control" type="text" name="city" id="city" placeholder="City" @if ($type=="create" ) value="{{old('city')}}" @else
                                       value="{{old('city') ?: $modal['city']}}" @endif required autocomplete="off">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group required-field">
                                    <label for="postcode">Postcode</label>
                                    <input class="form-control my-control" type="text" name="postcode" id="postcode" placeholder="Postcode" @if ($type=="create" ) value="{{old('postcode')}}" @else
                                       value="{{old('postcode') ?: $modal['postcode']}}" @endif required autocomplete="off">
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group required-field">
                                    <label for="country_id">Country</label>
                                    <select class="form-control my-control country" onchange="getZones(this, '{{route('zones.getZones')}}')" name="country_id" @if ($type=="edit" )
                                       data-zone="{{$modal['zone_id']}}" @endif id="country_id" required>
                                       <option value="" disabled selected>-- Select Country --</option>
                                       @if (count($countries) > 0)
                                       @foreach ($countries as $country)
                                       <option value="{{$country->id}}" @if ($type=="create" && $country->name == 'Canada')
                                          selected
                                          @endif
                                          @if ($type=="edit" && $country->id == $modal['country_id'])
                                          selected
                                          @endif>
                                          {!! $country->name !!}
                                       </option>
                                       @endforeach
                                       @endif
                                    </select>
                                 </div>

                              </div>
                              <div class="col-md-6">
                                 <div class="form-group required-field">
                                    <label for="zone_id">Region / State</label>
                                    <select class="form-control my-control zone" name="zone_id" id="zone_id" required>
                                       <option value="" disabled selected>-- Select State --</option>
                                       @if (count($zones) > 0)
                                       @foreach ($zones as $zone)
                                       <option value="{{$zone->id}}">
                                          {!! $zone->name !!}
                                       </option>
                                       @endforeach
                                       @endif
                                    </select>
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              {{-- <div class="col-md-6">
                                 <div class="form-group">
                                    <label for="company">Company</label>
                                    <input class="form-control my-control" type="text" name="company" id="company" placeholder="Company" @if ($type=="create" ) value="{{old('company')}}" @else
                              value="{{old('company') ?: $modal['company']}}" @endif autocomplete="off">
                           </div>
                     </div> --}}
                     <div class="form-group col-md-6">
                        <label class="form-label">Default Address </label>
                        <div class="custom-control custom-radio mt-1">
                           <input @if ($type=="edit" && Auth::guard('frontend')->user()->address_id ==$id) checked @endif type="radio" id="is_default" name="is_default"
                           class="custom-control-input" value="1">
                           <label class="custom-control-label" for="is_default">Is Default</label>
                        </div>
                     </div>
                  </div>

                  <div class="row">
                     <div class="col-md-12 d-flex justify-content-between">
                        <a href="{{route('frontend.dashboard', ['page' => 'address-book'])}}" class="btn btn-sm btn-secondary">Back</a>
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                     </div>
                  </div>
                  </form>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
</div>

@endsection

@push('page_lvl_js')
<script src="{{asset('/')}}places/index.js"></script>

<script>
   initAutocompleteFields(
      document.getElementById("address_1"),
      document.getElementById("city"),
      document.getElementById("postcode"),
      document.getElementById("country_id"),
      document.getElementById("zone_id"),
      document.getElementById("lat"),
      document.getElementById("lng")
   );

   @if($type == "edit")
      $('.country').trigger('change');
   @endif
</script>
@endpush