@extends('admin.master')

@section('meta')
@include('admin.common.meta')
@endsection

@push('page_lvl_css')
<link href="{{asset('metronic/')}}/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endpush

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
                    <li class="breadcrumb-item text-muted">Localization</li>
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

<!--begin::Post-->
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

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-5 mb-xl-8">
                    <!--begin::Header-->
                    <div class="card-header border-1 mb-5 pb-3 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">{{$title}}</span>
                        </h3>
                        <div class="card-toolbar">
                            @can('Add-Weight-Classes')
                            <a href="{{route('weight-classes.create')}}" class="btn btn-sm btn-primary create"><i class="fas fa-plus-circle"></i> Create New</a>
                            @endcan
                            {{-- <a href="javascript:void(0);" class="btn btn-sm btn-danger delete d-none" onclick="bulkDelete('{{route('weight-classes.bulkDelete')}}')"><i
                                class="fas fa-trash-alt"></i>
                            Delete</a> --}}
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-0">
                        <!--begin::Table container-->
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table id="generic-datatable" class="table table-striped table-sm gy-2 gs-2 align-middle">
                                <thead>
                                    <tr class="fw-bolder fs-6 text-gray-800">
                                        <th style="width: 5%">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input delete-checkbox" type="checkbox" data-kt-check="true" data-kt-check-target="#generic-datatable .form-check-input" />
                                            </div>
                                        </th>
                                        <th style="width: 55%">Name</th>
                                        <th style="width: 10%">Unit</th>
                                        <th style="width: 10%">Value</th>
                                        <th style="width: 10%">Status</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($weight_classes) > 0)
                                    @php $a = 0 @endphp
                                    @foreach ($weight_classes as $weight_class)
                                    @php $a++ @endphp
                                    <tr>
                                        <!--begin::Checkbox-->
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input delete-checkbox" type="checkbox" name="id" value="{{$weight_class->id}}" />
                                            </div>
                                        </td>
                                        <!--end::Checkbox-->
                                        <td>{{$weight_class->eng_description->title}}</td>
                                        <td>{{$weight_class->eng_description->unit}}</td>
                                        <td>{{$weight_class->value}}</td>
                                        <td>
                                            <a href="javascript:void(0);" class="badge badge-light-{{$weight_class->status == " 1" ? "success" : "danger"}}" data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="{{$weight_class->status == " 1" ? "Active" : "Inactive"}}"
                                                onclick="updateStatus('{{route('weight-classes.update-status', ['id' => $weight_class->id])}}', '{{$weight_class->status}}', false)">
                                                {{$weight_class->status == "1" ? "Active" : "Inactive"}}
                                            </a>
                                        </td>
                                        <td>
                                            @can('Edit-Weight-Classes')
                                            <a href="{{route('weight-classes.edit', ['id' => $weight_class->id])}}" class="btn btn-icon btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip"
                                                data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            @endcan

                                            {{-- @can('Delete-Weight-Classes')
                                            <a href="javascript:void(0);" class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                                onclick="deleteData('{{route('weight-classes.delete', ['id' => $weight_class->id])}}', false)" data-bs-toggle="tooltip"
                                            data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Delete">
                                            <i class="far fa-trash-alt"></i>
                                            </a>
                                            @endcan --}}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6" class="text-center"><strong>No Data Found...</strong></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Table container-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
@endsection