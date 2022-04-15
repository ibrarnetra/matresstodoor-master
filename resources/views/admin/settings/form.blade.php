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
        @if (session('success'))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-dismissible bg-light-success d-flex flex-column flex-sm-row w-100 p-5">
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotone/Interface/Comment.svg-->
                    <span class="svg-icon svg-icon-2hx svg-icon-success me-4 mb-5 mb-sm-0">
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
                        <span>{{ session('success') }}</span>
                    </div>
                    <!--end::Content-->
                    <!--begin::Close-->
                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <!--begin::Svg Icon | path: icons/duotone/Interface/Close-Square.svg-->
                        <span class="svg-icon svg-icon-1 svg-icon-success">
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
                    <form method="POST" enctype="multipart/form-data" @if($type=="create" ) action="{{route('settings.store')}}" @else action="{{route('settings.update')}}" @endif>
                        <div class="card-body pt-0">
                            @csrf
                            <input type="hidden" name="type" id="type" value="{{$type}}">

                            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#general">General</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#option">Options</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#social">Socials</a>
                                </li>
                            </ul>

                            <div class="tab-content tabcontent-border mt-3">
                                <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="row">
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label" for="config_country_id">Country </label>
                                            <select class="form-select form-select-solid country @error('config_country_id') is-invalid @enderror" name="data[config_country_id]" id="config_country_id"
                                                style="width: 100%;" data-zone="0" onchange="getZones(this, '{{route('zones.getZones')}}')">
                                                <option value="" disabled selected>-- Select Country --</option>
                                                @if (count($countries) > 0)
                                                @foreach ($countries as $country)
                                                <option value="{{$country->id}}" @if ($type=="create" && $country->name == "Canada")
                                                    selected
                                                    @endif @if (($type=="edit" && isset($modal['config_country_id'])) && $modal['config_country_id']==$country->id) selected @endif>
                                                    {!! $country->name !!}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('config_country_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label" for="config_zone_id">Region / State </label>
                                            <select class="form-select form-select-solid zone @error('config_zone_id') is-invalid @enderror" name="data[config_zone_id]" id="config_zone_id"
                                                style="width: 100%;">
                                                <option value="" disabled selected>-- Select Zone --</option>
                                                @if (count($zones) > 0)
                                                @foreach ($zones as $zone)
                                                <option value="{{$zone->id}}" @if ($type=="create" && $zone->name == "Ontario")
                                                    selected
                                                    @endif @if ($type=="edit" && isset($modal['config_zone_id']) && $modal['config_zone_id']==$zone->id) selected
                                                    @endif>
                                                    {!! $zone->name !!}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('config_zone_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-5 col-md-6">
                                            <label for=" config_logo" class="form-label">Website Logo (192x35)</label>
                                            <input type="file" class="form-control form-control-solid @error('config_logo') is-invalid @enderror" id="config_logo" name="data[config_logo]"
                                                accept="image/*">

                                            @error('config_logo')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        @if($type == "edit" && isset($modal['config_logo']))
                                        <div class="mb-5 col-md-6">
                                            <input type="hidden" name="old_config_logo" value="{{$modal['config_logo']}}">
                                            <img class="img-fluid" width="80" src="{{asset('storage/config_logos/'.$modal['config_logo'])}}" alt="{{$modal['config_logo']}}">
                                        </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="mb-5 col-md-6">
                                            <label for=" config_favicon" class="form-label">Favicon (32x32)</label>
                                            <input type="file" class="form-control form-control-solid @error('config_favicon') is-invalid @enderror" id="config_favicon" name="data[config_favicon]"
                                                accept="image/*">

                                            @error('config_favicon')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        @if($type == "edit" && isset($modal['config_favicon']))
                                        <div class="mb-5 col-md-6">
                                            <input type="hidden" name="old_config_favicon" value="{{$modal['config_favicon']}}">
                                            <img class="img-fluid" width="80" src="{{asset('storage/config_favicons/'.$modal['config_favicon'])}}" alt="{{$modal['config_favicon']}}">
                                        </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label required" for="config_meta_title">Meta Title</label>
                                            <input type="text" class="form-control form-control-solid @error('config_meta_title') is-invalid @enderror" id="config_meta_title"
                                                name="data[config_meta_title]" placeholder="Meta Title" required @if($type=="create" ) value="{{ old('config_meta_title') }}" @else
                                                value="{{ old('config_meta_title') ?: (isset($modal['config_meta_title']) ? $modal['config_meta_title'] : "") }}" @endif>

                                            @error('config_meta_title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-5 col-md-6">
                                            <label class="form-label" for="config_meta_keyword">Meta Keyword</label>
                                            <input type="text" class="form-control form-control-solid @error('config_meta_keyword') is-invalid @enderror" id="config_meta_keyword"
                                                name="data[config_meta_keyword]" placeholder="Meta Keyword" @if($type=="create" ) value="{{ old('config_meta_keyword') }}" @else
                                                value="{{ old('config_meta_keyword') ?: (isset($modal['config_meta_keyword']) ? $modal['config_meta_keyword'] : "") }}" @endif>

                                            @error('config_meta_keyword')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-5 col-md-12">
                                            <label class="form-label" for="config_meta_description">Meta Description</label>
                                            <textarea class="form-control form-control-solid @error('config_meta_description') is-invalid @enderror" name="data[config_meta_description]"
                                                id="config_meta_description" rows="5"
                                                placeholder="Meta Description">@if($type=="create" ){{ old('config_meta_description') }}@else{{ old('config_meta_description') ?: (isset($modal['config_meta_description']) ? $modal['config_meta_description'] : "") }}@endif</textarea>

                                            @error('config_meta_description')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-5 col-md-12">
                                            <label class="form-label" for="config_google_analytics">Google Analytics</label>
                                            <textarea class="form-control form-control-solid @error('config_google_analytics') is-invalid @enderror" name="data[config_google_analytics]"
                                                id="config_google_analytics" rows="5"
                                                placeholder="Google Analytics">@if($type=="create" ){{ old('config_google_analytics') }}@else{{ old('config_google_analytics') ?: (isset($modal['config_google_analytics']) ? $modal['config_google_analytics'] : "") }}@endif</textarea>

                                            @error('config_google_analytics')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="option" role="tabpanel">
                                    <div class="mb-5">
                                        <h3>Taxes</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" style="display: block;">Display Prices With Tax</label>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_tax_yes" name="data[config_tax]" value="1" @if($type=="create" ) checked @endif
                                                        @if($type=="edit" && isset($modal['config_tax']) && $modal['config_tax']=="1" ) checked @endif />
                                                    <label class="form-check-label" for="config_tax_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_tax_no" name="data[config_tax]" value="0" @if($type=="edit" && isset($modal['config_tax'])
                                                        && $modal['config_tax']=="0" ) checked @endif />
                                                    <label class="form-check-label" for="config_tax_no">
                                                        No
                                                    </label>
                                                </div>

                                                @error('config_tax')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" for="config_tax_default">Use Store Tax Address</label>
                                                <select class="form-select form-select-solid  @error('config_tax_default') is-invalid @enderror" name="data[config_tax_default]"
                                                    id="config_tax_default">
                                                    <option value="0" disabled selected>Select Tax Class</option>
                                                    @if(count($tax_classes) > 0)
                                                    @foreach($tax_classes as $tax_class)
                                                    <option value="{{$tax_class->id}}" @if($type=="edit" && isset($modal['config_tax_default']) && $modal['config_tax_default']==$tax_class->id)
                                                        selected
                                                        @endif>{{$tax_class->title}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>

                                                @error('config_tax_default')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <h3>Account</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" for="config_tax_customer">Use Customer Tax Address</label>
                                                <select class="form-select form-select-solid  @error('config_tax_customer') is-invalid @enderror" name="data[config_tax_customer]"
                                                    id="config_tax_customer">
                                                    <option value="0" disabled selected>Select Tax Class</option>
                                                    @if(count($tax_classes) > 0)
                                                    @foreach($tax_classes as $tax_class)
                                                    <option value="{{$tax_class->id}}" @if($type=="edit" && isset($modal['config_tax_customer']) && $modal['config_tax_customer']==$tax_class->id)
                                                        selected
                                                        @endif>{{$tax_class->title}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>

                                                @error('config_tax_customer')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-5 col-md-6">
                                                <label for="config_customer_group_id" class="form-label">Customer Group</label>
                                                <select class="form-select form-select-solid @error('config_customer_group_id') is-invalid @enderror" aria-label="config_customer_group_id"
                                                    id="config_customer_group_id" name="data[config_customer_group_id]">
                                                    @if(count($customer_groups) > 0)
                                                    @foreach($customer_groups as $customer_group)
                                                    <option value="{{$customer_group->id}}" @if(isset($modal) && isset($modal['config_customer_group_id']) &&
                                                        $modal['config_customer_group_id']==$customer_group->id)
                                                        selected
                                                        @endif>
                                                        {{$customer_group->eng_description->name}}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>

                                                @error('config_customer_group_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" style="display: block;">Login Display Prices</label>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_customer_price_yes" name="data[config_customer_price]" value="1" @if($type=="create" )
                                                        checked @endif @if($type=="edit" && isset($modal['config_customer_price']) && $modal['config_customer_price']=="1" ) checked @endif />
                                                    <label class="form-check-label" for="config_customer_price_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_customer_price_no" name="data[config_customer_price]" value="0" @if($type=="edit" &&
                                                        isset($modal['config_customer_price']) && $modal['config_customer_price']=="0" ) checked @endif />
                                                    <label class="form-check-label" for="config_customer_price_no">
                                                        No
                                                    </label>
                                                </div>

                                                @error('config_customer_price')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <h3>Checkout</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" for="config_invoice_prefix">Invoice Prefix</label>
                                                <input type="text" class="form-control form-control-solid @error('config_invoice_prefix') is-invalid @enderror" id="config_invoice_prefix"
                                                    name="data[config_invoice_prefix]" placeholder="Invoice Prefix" @if($type=="create" ) value="{{ old('config_invoice_prefix') }}" @else
                                                    value="{{ old('config_invoice_prefix') ?: (isset($modal['config_invoice_prefix']) ? $modal['config_invoice_prefix'] : "") }}" @endif>

                                                @error('config_invoice_prefix')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" style="display: block;">Display Weight on Cart Page</label>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_cart_weight_yes" name="data[config_cart_weight]" value="1" @if($type=="create" ) checked
                                                        @endif @if($type=="edit" && isset($modal['config_cart_weight']) && $modal['config_cart_weight']=="1" ) checked @endif />
                                                    <label class="form-check-label" for="config_cart_weight_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_cart_weight_no" name="data[config_cart_weight]" value="0" @if($type=="edit" &&
                                                        isset($modal['config_cart_weight']) && $modal['config_cart_weight']=="0" ) checked @endif />
                                                    <label class="form-check-label" for="config_cart_weight_no">
                                                        No
                                                    </label>
                                                </div>

                                                @error('config_cart_weight')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" style="display: block;">Guest Checkout</label>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_checkout_guest_yes" name="data[config_checkout_guest]" value="1" @if($type=="create" )
                                                        checked @endif @if($type=="edit" && isset($modal['config_checkout_guest']) && $modal['config_checkout_guest']=="1" ) checked @endif />
                                                    <label class="form-check-label" for="config_checkout_guest_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_checkout_guest_no" name="data[config_checkout_guest]" value="0" @if($type=="edit" &&
                                                        isset($modal['config_checkout_guest']) && $modal['config_checkout_guest']=="0" ) checked @endif />
                                                    <label class="form-check-label" for="config_checkout_guest_no">
                                                        No
                                                    </label>
                                                </div>

                                                @error('config_checkout_guest')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" for="config_order_status_id">Order Status </label>
                                                <select class="form-select form-select-solid" id="config_order_status_id" name="data[config_order_status_id]">
                                                    @foreach($order_statuses as $order_status)
                                                    <option value="{{$order_status->id}}" @if($type=="create" && $order_status->name == "Pending")
                                                        selected @endif @if($type=="edit" && isset($modal['config_order_status_id']) && $modal['config_order_status_id'] == $order_status->id)
                                                        selected @endif>{{$order_status->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('config_order_status_id')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <h3>Stock</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" style="display: block;">Display Stock</label>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_stock_display_yes" name="data[config_stock_display]" value="1" @if($type=="create" ) checked
                                                        @endif @if($type=="edit" && isset($modal['config_stock_display']) && $modal['config_stock_display']=="1" ) checked @endif />
                                                    <label class="form-check-label" for="config_stock_display_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_stock_display_no" name="data[config_stock_display]" value="0" @if($type=="edit" &&
                                                        isset($modal['config_stock_display']) && $modal['config_stock_display']=="0" ) checked @endif />
                                                    <label class="form-check-label" for="config_stock_display_no">
                                                        No
                                                    </label>
                                                </div>

                                                @error('config_stock_display')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" style="display: block;">Stock Checkout</label>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_stock_checkout_yes" name="data[config_stock_checkout]" value="1" @if($type=="create" )
                                                        checked @endif @if($type=="edit" && isset($modal['config_stock_checkout']) && $modal['config_stock_checkout']=="1" ) checked @endif />
                                                    <label class="form-check-label" for="config_stock_checkout_yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-solid form-check-inline">
                                                    <input class="form-check-input" type="radio" id="config_stock_checkout_no" name="data[config_stock_checkout]" value="0" @if($type=="edit" &&
                                                        isset($modal['config_stock_checkout']) && $modal['config_stock_checkout']=="0" ) checked @endif />
                                                    <label class="form-check-label" for="config_stock_checkout_no">
                                                        No
                                                    </label>
                                                </div>

                                                @error('config_stock_checkout')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h3>Misc.</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" for="config_currency">Currency</label>
                                                <select class="form-select form-select-solid @error('config_currency') is-invalid @enderror" id="config_currency" name="data[config_currency]">
                                                    <option value="" selected disabled>-- Select Currency --</option>
                                                    @if (count($currencies) > 0)
                                                    @foreach ($currencies as $currency)
                                                    <option value="{{$currency->code}}" @if ($type=="edit" && isset($modal['config_currency']) && $modal['config_currency']==$currency->code) selected
                                                        @endif>{{$currency->title}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                @error('config_currency')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" for="config_length_class_id">Length Class</label>
                                                <select class="form-select form-select-solid  @error('config_length_class_id') is-invalid @enderror" name="config_length_class_id"
                                                    id="config_length_class_id">
                                                    <option value="0" disabled selected>Select Length Class</option>
                                                    @if(count($length_classes) > 0)
                                                    @foreach($length_classes as $length_class)
                                                    <option value="{{$length_class->id}}" @if (($type=="edit" && isset($modal['config_length_class_id'])) &&
                                                        $modal['config_length_class_id']==$length_class->id)
                                                        selected
                                                        @endif>{{$length_class->eng_description->title}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>

                                                @error('config_length_class_id')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="mb-5 col-md-6">
                                                <label class="form-label" for="config_weight_class_id">Weight Class</label>
                                                <select class="form-select form-select-solid  @error('config_weight_class_id') is-invalid @enderror" name="config_weight_class_id"
                                                    id="config_weight_class_id">
                                                    <option value="0" disabled selected>Select Weight Class</option>
                                                    @if(count($weight_classes) > 0)
                                                    @foreach($weight_classes as $weight_class)
                                                    <option value="{{$weight_class->id}}" @if (($type=="edit" && isset($modal['config_weight_class_id'])) &&
                                                        $modal['config_weight_class_id']==$weight_class->
                                                        id)
                                                        selected
                                                        @endif>{{$weight_class->eng_description->title}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>

                                                @error('config_weight_class_id')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="social" role="tabpanel">
                                    <div class="row">
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label" for="config_facebook_link">Facebook</label>
                                            <input type="text" class="form-control form-control-solid @error('config_facebook_link') is-invalid @enderror" id="config_facebook_link"
                                                name="data[config_facebook_link]" placeholder="Facebook" @if($type=="create" ) value="{{ old('config_facebook_link') }}" @else
                                                value="{{ old('config_facebook_link') ?: (isset($modal['config_facebook_link']) ? $modal['config_facebook_link'] : "") }}" @endif>

                                            @error('config_facebook_link')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-5 col-md-6">
                                            <label class="form-label" for="config_twitter_link">Twitter</label>
                                            <input type="text" class="form-control form-control-solid @error('config_twitter_link') is-invalid @enderror" id="config_twitter_link"
                                                name="data[config_twitter_link]" placeholder="Twitter" @if($type=="create" ) value="{{ old('config_twitter_link') }}" @else
                                                value="{{ old('config_twitter_link') ?: (isset($modal['config_twitter_link']) ? $modal['config_twitter_link'] : "") }}" @endif>

                                            @error('config_twitter_link')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label" for="config_instagram_link">Instagram</label>
                                            <input type="text" class="form-control form-control-solid @error('config_instagram_link') is-invalid @enderror" id="config_instagram_link"
                                                name="data[config_instagram_link]" placeholder="Instagram" @if($type=="create" ) value="{{ old('config_instagram_link') }}" @else
                                                value="{{ old('config_instagram_link') ?: (isset($modal['config_instagram_link']) ? $modal['config_instagram_link'] : "") }}" @endif>

                                            @error('config_instagram_link')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-5 col-md-6">
                                            <label class="form-label" for="config_pinterest_link">Pinterest</label>
                                            <input type="text" class="form-control form-control-solid @error('config_pinterest_link') is-invalid @enderror" id="config_pinterest_link"
                                                name="data[config_pinterest_link]" placeholder="Pinterest" @if($type=="create" ) value="{{ old('config_pinterest_link') }}" @else
                                                value="{{ old('config_pinterest_link') ?: (isset($modal['config_pinterest_link']) ? $modal['config_pinterest_link'] : "") }}" @endif>

                                            @error('config_pinterest_link')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label" for="config_linkedin_link">Linkedin</label>
                                            <input type="text" class="form-control form-control-solid @error('config_linkedin_link') is-invalid @enderror" id="config_linkedin_link"
                                                name="data[config_linkedin_link]" placeholder="Linkedin" @if($type=="create" ) value="{{ old('config_linkedin_link') }}" @else
                                                value="{{ old('config_linkedin_link') ?: (isset($modal['config_linkedin_link']) ? $modal['config_linkedin_link'] : "") }}" @endif>

                                            @error('config_linkedin_link')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class="mb-5 col-md-6">
                                            <label class="form-label" for="config_youtube_link">Youtube</label>
                                            <input type="text" class="form-control form-control-solid @error('config_youtube_link') is-invalid @enderror" id="config_youtube_link"
                                                name="data[config_youtube_link]" placeholder="Youtube" @if($type=="create" ) value="{{ old('config_youtube_link') }}" @else
                                                value="{{ old('config_youtube_link') ?: (isset($modal['config_youtube_link']) ? $modal['config_youtube_link'] : "") }}" @endif>

                                            @error('config_youtube_link')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <a class="btn btn-secondary" href="{{route('dashboard.index')}}">Cancel</a>
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
<script src="{{asset('/')}}custom/settings.js"></script>
@endpush