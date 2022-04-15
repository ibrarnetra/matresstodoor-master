@extends('admin.master')

@section('meta')
@include('admin.common.meta')
@endsection

@section('content')
<!--begin::Toolbar-->
<div class="toolbar" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
            class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <!--begin::Title-->
            <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">{{$title}}
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <!--end::Separator-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{route('dashboard.index')}}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">System</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">User Management</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-200 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{$title}}</li>
                </ul>
                <!--end::Breadcrumb-->
            </h1>
            <!--end::Title-->
        </div>
        <!--end::Page title-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->


<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container">
        @if($errors->any())
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row w-100 p-5">
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotone/Interface/Comment.svg-->
                    <span class="svg-icon svg-icon-2hx svg-icon-danger me-4 mb-5 mb-sm-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.25" fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.69477 2.48932C4.00472 2.74648 2.66565 3.98488 2.37546 5.66957C2.17321 6.84372 2 8.33525 2 10C2 11.6647 2.17321 13.1563 2.37546 14.3304C2.62456 15.7766 3.64656 16.8939 5 17.344V20.7476C5 21.5219 5.84211 22.0024 6.50873 21.6085L12.6241 17.9949C14.8384 17.9586 16.8238 17.7361 18.3052 17.5107C19.9953 17.2535 21.3344 16.0151 21.6245 14.3304C21.8268 13.1563 22 11.6647 22 10C22 8.33525 21.8268 6.84372 21.6245 5.66957C21.3344 3.98488 19.9953 2.74648 18.3052 2.48932C16.6859 2.24293 14.4644 2 12 2C9.53559 2 7.31411 2.24293 5.69477 2.48932Z"
                                fill="#191213" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7 7C6.44772 7 6 7.44772 6 8C6 8.55228 6.44772 9 7 9H17C17.5523 9 18 8.55228 18 8C18 7.44772 17.5523 7 17 7H7ZM7 11C6.44772 11 6 11.4477 6 12C6 12.5523 6.44772 13 7 13H11C11.5523 13 12 12.5523 12 12C12 11.4477 11.5523 11 11 11H7Z"
                                fill="#121319" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <!--end::Icon-->
                    <!--begin::Content-->
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <span class="fw-bolder">Note</span>
                        {!! implode('', $errors->all('<span>:message</span>')) !!}
                    </div>
                    <!--end::Content-->
                    <!--begin::Close-->
                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <!--begin::Svg Icon | path: icons/duotone/Interface/Close-Square.svg-->
                        <span class="svg-icon svg-icon-1 svg-icon-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.25" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M2.36899 6.54184C2.65912 4.34504 4.34504 2.65912 6.54184 2.36899C8.05208 2.16953 9.94127 2 12 2C14.0587 2 15.9479 2.16953 17.4582 2.36899C19.655 2.65912 21.3409 4.34504 21.631 6.54184C21.8305 8.05208 22 9.94127 22 12C22 14.0587 21.8305 15.9479 21.631 17.4582C21.3409 19.655 19.655 21.3409 17.4582 21.631C15.9479 21.8305 14.0587 22 12 22C9.94127 22 8.05208 21.8305 6.54184 21.631C4.34504 21.3409 2.65912 19.655 2.36899 17.4582C2.16953 15.9479 2 14.0587 2 12C2 9.94127 2.16953 8.05208 2.36899 6.54184Z"
                                    fill="#12131A" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.29289 8.29289C8.68342 7.90237 9.31658 7.90237 9.70711 8.29289L12 10.5858L14.2929 8.29289C14.6834 7.90237 15.3166 7.90237 15.7071 8.29289C16.0976 8.68342 16.0976 9.31658 15.7071 9.70711L13.4142 12L15.7071 14.2929C16.0976 14.6834 16.0976 15.3166 15.7071 15.7071C15.3166 16.0976 14.6834 16.0976 14.2929 15.7071L12 13.4142L9.70711 15.7071C9.31658 16.0976 8.68342 16.0976 8.29289 15.7071C7.90237 15.3166 7.90237 14.6834 8.29289 14.2929L10.5858 12L8.29289 9.70711C7.90237 9.31658 7.90237 8.68342 8.29289 8.29289Z"
                                    fill="#12131A" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </button>
                    <!--end::Close-->
                </div>
            </div>
        </div>
        @endif

        {{-- form --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-5 mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-1 mb-5 pb-3 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">{{$title}}</span>
                        </h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <form method="POST" @if($type=="create" ) action="{{route('users.store')}}" @else action="{{route('users.update', ['id' => $id])}}" @endif>
                        <div class="card-body pt-0">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-5">
                                    <label for="first_name" class="required form-label">First Name</label>
                                    <input type="text" class="form-control form-control-solid @error('first_name') is-invalid @enderror" name="first_name" id="first_name" placeholder="First Name"
                                        autocomplete="off" required @if($type=="create" ) value="{{ old('first_name') }}" @else value="{{ old('first_name') ?: $modal->first_name}}" @endif>
                                    @error('first_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-5">
                                    <label for="last_name" class="required form-label">Last Name</label>
                                    <input type="text" class="form-control form-control-solid @error('last_name') is-invalid @enderror" name="last_name" id="last_name" placeholder="Last Name"
                                        autocomplete="off" required @if($type=="create" ) value="{{ old('last_name') }}" @else value="{{ old('last_name') ?: $modal->last_name}}" @endif>
                                    @error('last_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-5">
                                    <label for="telephone" class=" form-label">Mobile (xxx-xxx-xxxx)</label>
                                    <input type="tel" class="form-control form-control-solid @error('telephone') is-invalid @enderror" name="telephone" id="telephone" placeholder="xxx-xxx-xxxx"
                                        pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" autocomplete="off" @if($type=="create" ) value="{{ old('telephone') }}" @else
                                        value="{{ old('telephone') ?: $modal->telephone}}" @endif>
                                    @error('telephone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-5">
                                    <label for="email" class="required form-label">Email</label>
                                    <input type="email" class="form-control form-control-solid @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" autocomplete="off"
                                        required @if($type=="create" ) value="{{ old('email') }}" @else value="{{ old('email') ?: $modal->email}}" @endif>
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-5">
                                    <label for="password" class="@if($type=='create') required @endif form-label" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="top"
                                        title="Minimum Characters 8.">Password <i class="fas fa-info-circle"></i></label>
                                    <input type="password" minlength="8" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password"
                                        autocomplete="off" @if($type=="create" ) required @endif>
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-5">
                                    <label for="role" class="required form-label">Assign Role</label>
                                    <select class="form-select form-select-solid" aria-label="role" name="role" id="role" required>
                                        @if (isset($_GET['type']))

                                        @if ($_GET['type'] == "User")
                                        <option value="User">User</option>
                                        @else
                                        <option value="Supervisor">Supervisor</option>
                                        @endif

                                        @else

                                        @if(count($roles) > 0)
                                        @foreach($roles as $role)
                                        <option value="{{$role->name}}" @if($type=="edit" && count($modal->roles) > 0 &&
                                            $modal->roles[0]->name == $role->name) selected @endif>
                                            {{$role->name}}
                                        </option>
                                        @endforeach
                                        @endif

                                        @endif
                                    </select>
                                    @error('role')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                              
                                <div class="col-md-4 mb-5">
                                    <label for="allowed_discount" class="form-label">Allowed Discount</label>
                                    <input type="text" pattern="[0-9]+([,\.][0-9]+)?"   class="form-control @error('allowed_discount') is-invalid @enderror" name="allowed_discount" id="allowed_discount"
                                    onkeypress="return isNumberKey(this, event);" @if($type=="create" ) value="{{ old('allowed')?:0.00 }}" @else value="{{ isset($modal->user_discount) && $modal->user_discount?$modal->user_discount->allowed_discount:0.00}}" @endif>
                                    @error('allowed_discount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                    <div class="col-md-4 mb-5">
                                    <label for="country_id" class=" form-label">Country</label>
                                    <select class="form-select form-select-solid country" aria-label="country_id" name="country_id" id="country_id"
                                        onchange="getZones(this, '{{route('zones.getZones')}}')" style="width: 100%;">
                                        <option value="" selected disabled>-- Select Country --</option>
                                        @if (count($countries) > 0)
                                        @foreach ($countries as $country)
                                        <option value="{{$country->id}}" @if ($type=='create' && $country->name == 'Canada')
                                            selected
                                            @endif
                                            @if ($type=='edit' && $modal->country_id == $country->id)
                                            selected
                                            @endif>{!! $country->name !!}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @error('country_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-5">
                                    <label for="state_id" class=" form-label">State</label>
                                    <select class="form-select form-select-solid zone" aria-label="state_id" name="state_id" id="state_id" style="width: 100%;">
                                        <option value="" selected disabled>-- Select State --</option>
                                        @if (count($zones) > 0)
                                        @foreach ($zones as $zone)
                                        <option value="{{$zone->id}}" @if ($type=="create" && $zone->name == "Ontario")
                                            selected
                                            @endif @if ($type=='edit' && $modal->state_id == $zone->id)
                                            selected
                                            @endif>{!! $zone->name !!}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @error('state_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                              

                            </div>

                            <div class="row">
                             
                                <div class="col-md-4 mb-5">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control form-control-solid @error('city') is-invalid @enderror" name="city" id="city" placeholder="City" autocomplete="off"
                                        @if($type=="create" ) value="{{ old('city') }}" @else value="{{ old('city') ?: $modal->city}}" @endif>
                                    @error('city')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-8 mb-5">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea type="text" class="form-control form-control-solid @error('address') is-invalid @enderror" name="address" id="address" placeholder="Address"
                                        autocomplete="off">@if($type=="create" ){{ old('address') }} @else{{ old('address') ?: $modal->address}}@endif</textarea>
                                    @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-5">
                                    <label for="zip_code" class="form-label">Zip Code</label>
                                    <input type="text" class="form-control form-control-solid @error('zip_code') is-invalid @enderror" name="zip_code" id="zip_code" placeholder="Zip Code"
                                        autocomplete="off" @if($type=="create" ) value="{{ old('zip_code') }}" @else value="{{ old('zip_code') ?: $modal->zip_code}}" @endif>
                                    @error('zip_code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <a class="btn btn-secondary" href="{{route('users.index')}}">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
@endsection
@push('page_lvl_js')
<script>
function isNumberKey(txt, evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46) {
      //Check if the text already contains the . character
      if (txt.value.indexOf('.') === -1) {
        return true;
      } else {
        return false;
      }
    } else {
      if (charCode > 31 &&
        (charCode < 48 || charCode > 57))
        return false;
    }
    return true;
  }
  </script>
  @endpush