@extends('admin.master')

@section('meta')
    @include('admin.common.meta')
@endsection

@push('page_lvl_css')
    <link href="{{ asset('metronic/') }}/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet"
        type="text/css" />
@endpush

@section('content')
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">{{ $title }}
                    <!--begin::Separator-->
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <!--end::Separator-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard.index') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Sales</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ $title }}</li>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
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
                            <button type="button"
                                class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                                data-bs-dismiss="alert">
                                <!--begin::Svg Icon | path: icons/duotone/Interface/Close-Square.svg-->
                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none">
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
                                <span class="card-label fw-bolder fs-3 mb-1">{{ $title }}</span>
                            </h3>
                            <div class="card-toolbar">
                                @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Office Admin') ||
    Auth::guard('web')->user()->hasRole('Dispatch Manager'))
                                @endif
                                <a href="{{ route('orders.exportExcel') }}" class="btn btn-success btn-sm me-2"
                                    id="export" target="_blank"><i class="fas fa-file-export"></i> Export Order</a>
                                @can('Add-Orders')
                                    <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm me-2"><i
                                            class="fas fa-plus-circle"></i> Create Order</a>
                                @endcan

                                @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Office Admin'))
                                    <div class="dropdown d-none me-2" id="multi-dispatch">
                                        <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button"
                                            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-tasks"></i> Disptach Manager
                                        </a>

                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0);"
                                                    onclick="bulkAssignAndUnassign('{{ route('orders.assignUnassignOrder', ['dispatch_manager_id' => '0']) }}')">Unassign</a>
                                            </li>
                                            @if (count($dispatch_managers))
                                                @foreach ($dispatch_managers as $dispatch_manager)
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="bulkAssignAndUnassign('{{ route('orders.assignUnassignOrder', ['dispatch_manager_id' => $dispatch_manager->id]) }}')">{{ $dispatch_manager->first_name . ' ' . $dispatch_manager->last_name }}</a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                                @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Dispatch Manager') ||
    Auth::guard('web')->user()->hasRole('Office Admin') ||
    Auth::guard('web')->user()->hasRole('Delivery Manager'))
                                    <button type="button" class="btn btn btn-info btn-sm" id="assign-route"
                                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="Select Order(s) to generate Route."
                                        onclick="loadRouteOrdersModal('{{ route('routes.checkOrdersRoutes') }}')">
                                        <i class="fas fa-route"></i> Create Route
                                    </button>
                                @endif
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-0">
                            <div class="mb-10 row">
                                <div class="col-md-12">
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control form-control-sm custom-date-picker"
                                                    id="from" placeholder="Order From" aria-label="Order From"
                                                    @if (isset($order_from) && $order_from != '-1') value="{{ $order_from }}" @endif autocomplete="off">
                                                <span class="input-group-text">-</span>
                                                <input type="text" class="form-control form-control-sm custom-date-picker"
                                                    id="to" placeholder="Order To" aria-label="Order To"
                                                    @if (isset($order_to) && $order_to != '-1') value="{{ $order_to }}" @endif autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control form-control-sm custom-date-picker"
                                                    id="delivery-from" placeholder="Delivery From"
                                                    aria-label="Delivery From" autocomplete="off">
                                                <span class="input-group-text">-</span>
                                                <input type="text" class="form-control form-control-sm custom-date-picker"
                                                    id="delivery-to" placeholder="Delivery To" aria-label="Delivery To"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control form-control-sm custom-date-picker"
                                                    id="delivered-from" placeholder="Delivered From"
                                                    aria-label="Delivered From" autocomplete="off">
                                                <span class="input-group-text">-</span>
                                                <input type="text" class="form-control form-control-sm custom-date-picker"
                                                    id="delivered-to" placeholder="Delivered To" aria-label="Delivered To"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                   


                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-4">
                                            <select id="order-status" class="form-select form-select-solid">
                                                <option value="-1" selected>All Order Statuses</option>
                                                @if (count($order_statuses) > 0)
                                                    @foreach ($order_statuses as $order_status)
                                                        <option value="{{ $order_status->id }}">
                                                            {{ $order_status->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Office Admin') ||
    Auth::guard('web')->user()->hasRole('Dispatch Manager'))
                                                <select id="sales-rep" class="form-control select2">
                                                    <option></option>
                                                    @if (count($sales_reps) > 0)
                                                        @foreach ($sales_reps as $sales_rep)
                                                            <option value="{{ $sales_rep->id }}">
                                                                {{ $sales_rep->first_name . ' ' . $sales_rep->last_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Office Admin') ||
    Auth::guard('web')->user()->hasRole('Dispatch Manager'))
                                                <select id="store-id" class="form-select form-select-solid">
                                                    <option value="-1" selected>All Store</option>
                                                    @if (count($stores) > 0)
                                                        @foreach ($stores as $store)
                                                            <option value="{{ $store->id }}" @if (isset($store_id) && $store->id == $store_id) selected @endif>
                                                                {{ $store->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            @endif
                                        </div>
                                      
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-4">
                                            <select class="form-select form-select-solid customer" id="customer-id">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select form-select-solid order" id="order-id">
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control city" id="city-id" placeholder="Search City Name"
                                                name="city-id">

                                        </div>
                                       


                                        @if (count(Auth::guard('web')->user()->team_members) > 0)
                                            <div class="col-md-3 text-end">
                                            @else <div class="col-md-3 text-end">
                                        @endif


                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            {{-- <select class="form-select form-select-solid" id="custom-order">
                                                <option value="-1" selected>Please select order type</option>
                                                <option value="Yes">Special Order</option>
                                                <option value="No">Normal Order</option>
                                            </select> --}}
                                            <input type="tel" class="form-control" id="telephone" name="telephone"
                                                placeholder="Mobile(xxx-xxx-xxxx)" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">

                                        </div>
                                        <div class="col-md-4">
                                            <select id="payment-method-id" class="form-select form-select-solid">
                                                <option value="-1" selected>All Payment Method</option>
                                                @if (count($payment_methods) > 0)
                                                    @foreach ($payment_methods as $payment_method)
                                                        <option value="{{ $payment_method->id }}">
                                                            {{ $payment_method->eng_description->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select id="country-id" class="form-select form-select-solid">
                                                <option value="-1" selected>All Countries</option>
                                                @if (count($countries) > 0)
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}">
                                                            {{ $country->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                     

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-3 mt-2">
                                            <label class="form-label fw-bolder">Tax Applied:
                                            </label>
                                            <div class="form-check form-check-solid form-check-inline">
                                                <input class="form-check-input tax-apply" type="radio" id="tax-apply"
                                                    name="tax-apply" value="Y" />
                                                <label class="form-check-label" for="tax-apply">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="form-check form-check-solid form-check-inline">
                                                <input class="form-check-input tax-apply" type="radio" id="tax-apply"
                                                    name="tax-apply" value="N" />
                                                <label class="form-check-label" for="tax-apply">
                                                    No
                                                </label>
                                            </div>


                                        </div>
                                      
                                        <div class="col-md-3 mt-2">
                                            <label class="form-label fw-bolder">Special Order:
                                            </label>
                                            <div class="form-check form-check-solid form-check-inline ms-2">
                                                <input class="form-check-input multi-dispatch-checkbox" type="checkbox"
                                                    id="custom_order" name="custom_order" value="Yes" />
                                                <label class="form-check-label" for="custom_order">
                                                </label>
                                            </div>
                                        </div>
                                        @if (count(Auth::guard('web')->user()->team_members) > 0)

                                            <div class="col-md-3">
                                                <select id="team-member" class="form-select form-select-solid">
                                                    <option value="-1" selected>Select Team Member</option>
                                                    <option value="{{ Auth::guard('web')->user()->id }}">Only Me</option>
                                                    @foreach (Auth::guard('web')->user()->team_members as $team_member)
                                                        <option value="{{ $team_member->id }}">
                                                            {{ $team_member->first_name . ' ' . $team_member->last_name }}
                                                            ` </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <div class="col-md-3"></div>
                                        @endif

                                        <div class="col-md-3 text-end">
                                            <button class="btn btn-sm btn-info me-3"
                                                onclick="applyFilters()">Filter</button>
                                            <button class="btn btn-sm btn-primary" onclick="clearFilters()">Clear</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!--begin::Table-->
                                <table id="generic-datatable"
                                    class="table stripe row-border order-column  table-sm gy-2 gs-2 align-middle">
                                    <thead>
                                        <tr class="fw-bolder fs-6 text-gray-800">
                                            <th class="min-w-20px">
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                    <input class="form-check-input multi-dispatch-checkbox"
                                                        id="order-check-all" type="checkbox" data-kt-check="true"
                                                        data-kt-check-target="#generic-datatable .form-check-input" />
                                                </div>
                                            </th>
                                            <th class="min-w-100px">Order #</th>
                                            <th class="min-w-150px">Customer</th>
                                            <th class="min-w-150px">Telephone</th>
                                            <th class="min-w-150px">City</th>
                                            <th class="min-w-350px">Address</th>
                                            <th class="min-w-150px">Amount Due</th>
                                            <th class="min-w-150px">Payment Method</th>
                                            <th class="min-w-150px">Date Added</th>
                                            <th class="min-w-150px">Total</th>
                                            <th class="min-w-150px">Delivery Date</th>
                                            <th class="min-w-150px">Delivered Date</th>
                                            <th class="min-w-350px">Order Items</th>
                                            <th class="min-w-150px">Comments</th>
                                            <th class="min-w-150px">Order Type</th>
                                            <th class="min-w-150px">Status</th>
                                            <th class="min-w-150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Container-->
    <div class="modal fade" tabindex="-1" id="add-route">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Add Route</h2>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                    fill="#000000">
                                    <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1"></rect>
                                    <rect fill="#000000" opacity="0.5"
                                        transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                        x="0" y="7" width="16" height="2" rx="1"></rect>
                                </g>
                            </svg>
                        </span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form method="POST" action="{{ route('routes.store') }}" onsubmit="return routeCreation(this);"
                        autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="name">Name</label>
                                <input type="text"
                                    class="form-control form-control-solid @error('name') is-invalid @enderror" id="name"
                                    name="name" required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="start_location_id">Start Location</label>
                                <select
                                    class="form-select form-select-solid @error('start_location_id') is-invalid @enderror"
                                    name="start_location_id" id="start_location_id" required>
                                    <option value="" selected disabled>Select Store</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}">
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('start_location_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-5 col-md-8">
                                <label class="form-label" for="end_location">End Location</label>
                                <input class="form-control form-control-solid @error('end_location') is-invalid @enderror"
                                    name="end_location" id="end_location">
                                @error('end_location')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-5 col-md-4">
                                <label class="form-label required" for="end_location">Start Date</label>
                                <div class="input-group">
                                    <input class="form-control form-control-solid" name="dispatch_date" autocomplete="off"
                                        id="dispatch_date" placeholder="Pick date">
                                    <span class="input-group-text dispatch-date-icon"><i
                                            class="fas fa-calendar-alt"></i></span>
                                </div>
                            </div>
                        </div>

                        <div id="order-routes"></div>

                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--end::Post-->
    <input type="hidden" id="customer_search" value="{{ route('customers.search') }}">
    <input type="hidden" id="order_search" value="{{ route('orders.orderSearch') }}">
@endsection

@push('page_lvl_js')
    <script src="{{ asset('metronic/') }}/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="{{ asset('/') }}places/index.js"></script>
    <script>
        let element = $("#generic-datatable");

        $(document).ready(function() {
            initSelect2Ajax($(".customer"), $("#customer_search").val());
            initSelect2Ajax($(".order"), $("#order_search").val());
            initDataTable(element, setConfigOptions());



            initAutocompleteFields(
                document.getElementById("end_location"),
                "",
                "",
                "",
                "",
                "",
                ""
            );
            initCustomDatePicker($("#dispatch_date"), {
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 2000,
                autoApply: true,
                minDate: new Date(),
                locale: {
                    format: "YYYY-MM-DD",
                    separator: "-",
                },
            });
        });

        function setConfigOptions() {
            let dateRange = "-1";
            let deliveryDateRange = "-1";
            let deliveredDateRange = "-1";
            let orderStatus = $("#order-status option:selected").val() !== undefined && $("#order-status option:selected")
                .val() !== '' ? $("#order-status option:selected").val() : '-1';
            let saleRepId = $("#sales-rep option:selected").val() !== undefined && $("#sales-rep option:selected").val() !==
                '' ? $("#sales-rep option:selected").val() : '-1';
            let storeId = $("#store-id option:selected").val() !== undefined && $("#store-id option:selected").val() !==
                '' ? $("#store-id option:selected").val() : '-1';
            let teamMemberId = $("#team-member option:selected").val() !== undefined && $("#team-member option:selected")
                .val() !== '' ? $("#team-member option:selected").val() : '-1';
            let customerId = $("#customer-id option:selected").val() !== undefined && $("#customer-id option:selected")
                .val() !== "" && $("#customer-id option:selected").val() !== NaN ?
                $("#customer-id option:selected").val() :
                "-1";
            let countryId = $("#country-id option:selected").val() !== undefined && $("#country-id option:selected")
                .val() !== "" && $("#country-id option:selected").val() !== NaN ?
                $("#country-id option:selected").val() :
                "-1";
            let paymentMethodId = $("#payment-method-id option:selected").val() !== undefined && $(
                    "#payment-method-id option:selected").val() !==
                '' ? $("#payment-method-id option:selected").val() : '-1';
            let orderId = $("#order-id option:selected").val() !== undefined && $("#order-id option:selected")
                .val() !== "" && $("#order-id option:selected").val() !== NaN ?
                $("#order-id option:selected").val() :
                "-1";
            let cityId = $('#city-id').val() !== undefined && $('#city-id')
                .val() !== "" && $('#city-id').val() !== NaN ?
                $('#city-id').val() :
                "-1";

            let customOrder = $("#custom_order:checked").val() !== undefined && $("#custom_order:checked")
                .val() !== "" && $("#custom_order:checked").val() !== NaN ?
                $("#custom_order:checked").val() :
                "-1";
            let taxApplied = $(".tax-apply:checked").val() !== undefined && $(".tax-apply:checked")
                .val() !== "" && $(".tax-apply:checked").val() !== NaN ?
                $(".tax-apply:checked").val() :
                "-1";
            let telephone = $('#telephone').val() !== undefined && $('#telephone')
                .val() !== "" && $('#telephone').val() !== NaN ?
                $('#telephone').val() :
                "-1";
            // set order start date and end date
            let sDate =
                $("#from").val() !== undefined && $("#from").val() !== "" && $("#from").val() !== NaN ?
                $("#from").val() :
                "-1";
            let eDate =
                $("#to").val() !== undefined && $("#to").val() !== "" && $("#to").val() !== NaN ?
                $("#to").val() :
                "-1";
            // set order delivery start date and end date
            let sDeliveryDate =
                $("#delivery-from").val() !== undefined && $("#delivery-from").val() !== "" && $("#delivery-from").val() !==
                NaN ?
                $("#delivery-from").val() :
                "-1";
            let eDeliveryDate =
                $("#delivery-to").val() !== undefined && $("#delivery-to").val() !== "" && $("#delivery-to").val() !== NaN ?
                $("#delivery-to").val() :
                "-1";

                 // set order delivery start date and end date
            let sDeliveredDate =
                $("#delivered-from").val() !== undefined && $("#delivered-from").val() !== "" && $("#delivered-from").val() !==
                NaN ?
                $("#delivered-from").val() :
                "-1";
            let eDeliveredDate =
                $("#delivered-to").val() !== undefined && $("#delivered-to").val() !== "" && $("#delivered-to").val() !== NaN ?
                $("#delivered-to").val() :
                "-1";
            // DEFINE DATE RANGE
            if (sDate !== "-1" && eDate !== "-1") {
                dateRange = sDate + " to " + eDate;
            }
            if (sDeliveryDate !== "-1" && eDeliveryDate !== "-1") {
                deliveryDateRange = sDeliveryDate + " to " + eDeliveryDate;
            }
            if (sDeliveredDate !== "-1" && eDeliveredDate !== "-1") {
                deliveredDateRange = sDeliveredDate + " to " + eDeliveredDate;
            }
            // SET EXPORT URL
            $("#export").attr("href", '{{ route('orders.exportExcel') }}' + "?date_range=" + dateRange +
                "&delivery_date_range=" + deliveryDateRange + "&order_status=" + orderStatus + "&customer_id=" +
                customerId + "&sales_rep_id=" + saleRepId + "&store_id=" + storeId + "&order_id=" + orderId +
                "&city_id=" + cityId + "&custom_order=" + customOrder + "&tax_apply=" + taxApplied + "&telephone=" +
                telephone + "&payment_method_id=" + paymentMethodId + "&team_member_id=" + teamMemberId  + "&country_id=" + countryId + "&delivered_date_range=" + deliveredDateRange);
            @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Office Admin') ||
    Auth::guard('web')->user()->hasRole('Dispatch Manager'))
            @endif
            // DATATABLE CONFIGURATION
            return {
                destroy: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                scrollCollapse: true,
                paging: true,
                ajax: {
                    url: "{{ route('orders.dataTable') }}",
                    type: "GET",
                    data: {
                        date_range: dateRange,
                        delivery_date_range: deliveryDateRange,
                        order_status: orderStatus,
                        sales_rep_id: saleRepId,
                        customer_id: customerId,
                        team_member_id: teamMemberId,
                        store_id: storeId,
                        order_id: orderId,
                        city_id: cityId,
                        payment_method_id: paymentMethodId,
                        custom_order: customOrder,
                        tax_apply: taxApplied,
                        telephone: telephone,
                        country_id: countryId,
                        delivered_date_range: deliveredDateRange
                    },
                },

                order: [
                    [1, "desc"]
                ],
                columns: [{
                        data: "checkbox",
                        name: "checkbox",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "id",
                        name: "id",
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: "customer_name",
                        name: "customer_name",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "customer_telephone",
                        name: "customer_telephone",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "customer_city",
                        name: "customer_city",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "customer_address",
                        name: "customer_address",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "amount_due",
                        name: "amount_due",
                        orderable: true,
                        searchable: true,
                        className: "text-end",
                        render: function(data, type, full, meta) {
                            return "$" + setDefaultPriceFormat(data);
                        },
                    },
                    {
                        data: "payment_method",
                        name: "payment_method",
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            let html = row.payment_method;
                            if (row.payment_method_code === "p-link" && row.payment_link_status === "1") {
                                html += " <input type=\"hidden\" class=\"payment-link\" value=\"" + row
                                    .payment_link + "\" />";
                            }
                            return html;
                        },
                    },
                    {
                        data: "date_added",
                        name: "date_added",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "total",
                        name: "total",
                        orderable: true,
                        searchable: true,
                        className: "text-end",
                        render: function(data, type, full, meta) {
                            return "$" + setDefaultPriceFormat(data);
                        },
                    },
                    {
                        data: "delivery_date",
                        name: "delivery_date",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "delivered_date",
                        name: "delivered_date",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "order_item",
                        name: "order_item",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "order_comment",
                        name: "order_comment",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "custom_order",
                        name: "custom_order",
                        orderable: true,
                        searchable: true,
                        render: function(data, type, full, meta) {
                            if (data == 'No') {
                                return "Normal";
                            } else {
                                return "Special";
                            }
                        },
                    },
                    {
                        data: "order_status",
                        name: "order_status",
                        orderable: false,
                        searchable: false,
                    },

                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
                createdRow: function(row, data, index) {
                    // add color to tr according status
                    if (data.color_class) {
                        $(row).addClass(data.color_class);
                    }

                },
                initComplete: function() {
                    var tableBody = document.querySelector('.dataTables_scrollBody');
                    var headerTable = document.querySelector('.dataTables_scrollHead');
                    var curDown = false
                    var oldScrollLeft = 0;
                    // var oldScrollTop = 0;
                    var curXPos = 0;
                    // var curYPos = 0;
                    if (tableBody) {
                        tableBody.addEventListener("mousemove", function(e) {
                            if (curDown === true) {
                                tableBody.scrollLeft = oldScrollLeft + (curXPos - e.pageX);
                                headerTable.scrollLeft = oldScrollLeft + (curXPos - e.pageX);
                                //tableBody.scrollTop = oldScrollTop + (curYPos - e.pageY);
                            }
                        })
                        tableBody.addEventListener("mousedown", function(e) {
                            curDown = false;
                            if (e.which == 1 || e.which == 3) {
                                curDown = true;
                                // curYPos = e.pageY;
                                curXPos = e.pageX;
                                oldScrollLeft = tableBody.scrollLeft;
                            } else {
                                curDown = false;
                            }
                            // oldScrollTop = tableBody.scrollTop;
                        })
                        tableBody.addEventListener("mouseup", function(e) {
                            curDown = false;
                        })
                        tableBody.addEventListener("scroll", function(e) {
                            headerTable.scrollLeft = tableBody.scrollLeft;
                        })
                    }
                },
            };
        }

        function clearFilters() {
            $(".custom-date-picker").val("");
            $("#order-status").val($("#order-status option:first").val());
            $("#sales-rep").val($("#sales-rep option:first").val());
            $("#store-id").val($("#store-id option:first").val());
            $("#team-member").val($("#team-member option:first").val());
            $("#payment-method-id").val($("#payment-method-id option:first").val());
            $('#customer-id').val(null).trigger('change');
            $('#order-id').val(null).trigger('change');
            $('#city-id').val("");
            $('#telephone').val("");
            //clear custom order filter
            $("#custom_order").prop("checked", false);
            $(".tax-apply").prop("checked", false);
            initDataTable(element, setConfigOptions());
            jQuery(document).ready(function() {
                KTSelect2.init();
            })
        }

        function applyFilters() {
            initDataTable(element, setConfigOptions());
        }



        // Initialization


        // $(document).on("change", "#order-check-all", function() {
        //     $('.multi-dispatch-checkbox').not(this).prop('checked', this.checked);
        // });
        // $(document).on("change", ".multi-dispatch-checkbox", function() {
        //     $(this).prop('checked', this.checked);
        // });

        @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Office Admin') ||
    Auth::guard('web')->user()->hasRole('Disptach Manager'))
            $(document).on("click",".multi-dispatch-checkbox", function () {
            let toggle = false;
            setTimeout(() => {
            $(document).find("input[name=id]").each(function (i, ele) {
            if ($(ele).is(":checked")) {
            toggle = true;
            }
            });
            if (toggle) {
            $("#multi-dispatch").removeClass("d-none");
            } else {
            $("#multi-dispatch").addClass("d-none");
            }
            }, 100);
            });
        @endif

        @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Office Admin'))
            function bulkAssignAndUnassign(url) {
            let order_id = [];
            $(document)
            .find("input[name=id]")
            .each(function (i, ele) {
            if ($(ele).is(":checked")) {
            order_id.push($(ele).val());
            }
            });
            $.ajax({
            url: url,
            type: "POST",
            data: {
            order_id: order_id,
            _token: CSRF_TOKEN,
            },
            dataType: "JSON",
            success: function (res) {
            if (res.status) {
            toastr.success(res.data, "");
            } else {
            toastr.error(res.data, "");
            }
            $("#generic-datatable").DataTable().ajax.reload();
            },
            error: function (err) {
            console.log(
            "ðŸš€ ~ file: common.js ~ line 159 ~ bulkDelete ~ err",
            err
            );
            },
            });
            }
        @endif

        function copyPaymentLink(dis) {
            initCopyToClipBoard(
                "#copy-payment-link",
                false,
                "",
                $(dis).closest("tr").find(".payment-link").val()
            );
        }
        var KTSelect2 = function() {
            // Private functions
            var demos = function() {
                // basic



                // basic
                $('#sales-rep').select2({
                    placeholder: "Select a User",
                });


                // loading data from array




            }



            // Public functions
            return {
                init: function() {
                    demos();
                }
            };
        }();

        // Initialization
        jQuery(document).ready(function() {
            KTSelect2.init();
        });
        $("#dispatch_date").on("apply.daterangepicker", function(ev, picker) {
            $(this).val(picker.startDate.format("YYYY-MM-DD"));
        });

        $("#dispatch_date").on("cancel.daterangepicker", function(ev, picker) {
            $(this).val("");
        });

        $('.dispatch-date-icon').click(function() {
            $("#dispatch_date").focus();
        });
    </script>
    <script src="{{ asset('/') }}custom/routeGeneration.js"></script>
@endpush
