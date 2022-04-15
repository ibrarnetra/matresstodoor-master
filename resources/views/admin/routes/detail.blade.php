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
            @if ($errors->any())
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row w-100 p-5">
                            <!--begin::Icon-->
                            <!--begin::Svg Icon | path: icons/duotone/Interface/Comment.svg-->
                            <span class="svg-icon svg-icon-2hx svg-icon-danger me-4 mb-5 mb-sm-0">
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
                                {!! implode('', $errors->all('<span>:message</span>')) !!}
                            </div>
                            <!--end::Content-->
                            <!--begin::Close-->
                            <button type="button"
                                class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                                data-bs-dismiss="alert">
                                <!--begin::Svg Icon | path: icons/duotone/Interface/Close-Square.svg-->
                                <span class="svg-icon svg-icon-1 svg-icon-danger">
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

            @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Dispatch Manager') ||
    Auth::guard('web')->user()->hasRole('Office Admin'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header border-1 mb-5 pb-3 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1"><i class="fas fa-truck-loading"></i>
                                        &nbsp;Delivery Rep (Assigned To:
                                        @if ($route->assigned_to == '0')
                                            N/A
                                        @else
                                            {{ $route->route_assigned_to->first_name . ' ' . $route->route_assigned_to->last_name }}
                                        @endif)
                                    </span>
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="{{ route('routes.assignDeliveryRep') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="route_id" value="{{ $route->id }}">
                                            @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Dispatch Manager') ||
    Auth::guard('web')->user()->hasRole('Office Admin'))
                                                <div class="row">
                                                    <div class="mb-5 col-md-6">
                                                        <label class="form-label required" for="delivery_rep_id">Delivery
                                                            Rep</label>
                                                        <select class="form-select form-select-solid" id="delivery_rep_id"
                                                            name="delivery_rep_id" required>
                                                            <option value="0">Unassigned</option>
                                                            @if (count($delivery_reps) > 0)
                                                                @foreach ($delivery_reps as $delivery_rep)
                                                                    <option value="{{ $delivery_rep->id }}" @if ($route->assigned_to == $delivery_rep->id)
                                                                        selected
                                                                @endif
                                                                >{{ $delivery_rep->first_name . ' ' . $delivery_rep->last_name }}
                                                                </option>
                                                            @endforeach
                                            @endif
                                            </select>
                                            @error('delivery_rep_id')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                    </div>
                                </div>
            @endif

            <div class="row">
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-success btn-sm">Update Route</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    @endif

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header border-1 mb-5 pb-3 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1"><i class="fas fa-route"></i>
                            &nbsp;{{ $route->name }}</span>
                    </h3>
                    <div class="card-toolbar w-60px">
                        {{-- <div class="spinner-border text-dark me-5 d-none custom-loader" role="status">
                        <span class="visually-hidden">Loading...</span>
                     </div> --}}
                        <!--begin::Menu-->
                        <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                            <span class="btn btn-sm btn-primary">
                                Actions
                            </span>
                            <!--end::Svg Icon-->
                        </button>
                        <!--begin::Menu 2-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-7 menu-state-bg-light-primary fw-bold w-300px"
                            data-kt-menu="true">
                            <!--begin::Menu item-->

                            <!--end::Menu separator-->
                            <!--begin::Menu item-->
                            @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Dispatch Manager') ||
    Auth::guard('web')->user()->hasRole('Office Admin') ||
    Auth::guard('web')->user()->hasRole('Delivery Manager'))
                                <div class="menu-item px-3">

                                    <a href="{{ route('routes.getRouteSummary', ['route_id' => $id]) }}"
                                        class="menu-link px-3">
                                        Cash Summary
                                    </a>

                                </div>
                            @endif
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
    Auth::guard('web')->user()->hasRole('Dispatch Manager') ||
    Auth::guard('web')->user()->hasRole('Office Admin') ||
    Auth::guard('web')->user()->hasRole('Delivery Manager'))
                                <div class="menu-item px-3">

                                    <a href="javascript:void(0)" class="menu-link px-3" data-bs-toggle="modal"
                                        data-bs-target="#optimize-route-modal">
                                        Generate Optimized Routes
                                    </a>

                                </div>
                            @endif
                            <div class="menu-item px-3">
                                <a class="menu-link px-3" href="javascript:void(0);"
                                    onclick="generateLoadingSheet(this, '{{ $route->id }}', '{{ route('loading-sheets.store') }}')">Refresh
                                    Loading Sheet</a>
                            </div>

                            <div class="menu-item px-3">
                                <a class="menu-link px-3"
                                    href="{{ route('route.loading-sheets.detail', ['route_id' => $route->id]) }}">
                                    View Loading Sheet</a>
                            </div>
                            <div class="menu-item px-3">
                                <a class="menu-link px-3"
                                    href="{{ route('loading-sheets-route.inventory', ['route_id' => $route->id]) }}">
                                    <i class='far fa-inventory'></i> Truck Inventory</a>
                            </div>
                            
                        </div>




                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                           
                            @if(!(Auth::guard('web')->user()->hasRole('Super Admin') ||
                            Auth::guard('web')->user()->hasRole('Office Admin') ||
                            Auth::guard('web')->user()->hasRole('Dispatch Manager')))
                            @if($route->loading_status == 0)
                            <div class="alert alert-dismissible bg-light-warning d-flex flex-column flex-sm-row w-100 p-5">
                                Please go to loading sheet and  mark it loading done to view the orders
                            </div>
                            @endif
                            @endif
                            <div class="table-responsive">
                                <table class="table  gy-7 gs-7">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="text-center min-w-150px" style="width: 10%;">Sort Order</th>
                                            <th class="min-w-100px">Order #</th>
                                            <th class="min-w-150px">Customer Name</th>
                                            <th class="min-w-150px">Mobile #</th>
                                            <th class="min-w-300px">Address</th>
                                            <th class="min-w-150px">Collected Amount</th>
                                            <th class="text-center min-w-150px" style="width: 10%;">Order Status</th>
                                            <th class="text-center min-w-200px" style="width: 10%;">Route Order Status</th>
                                            <th class="text-center min-w-100px" style="width: 10%;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      

                                        @if (count($route->route_locations) > 0)
                                        @if(Auth::guard('web')->user()->hasRole('Super Admin') ||
                                        Auth::guard('web')->user()->hasRole('Office Admin') ||
                                        Auth::guard('web')->user()->hasRole('Dispatch Manager'))
                                            @foreach ($route->route_locations as $location)
                                                @if ($location->order)
                                                    @php
                                                        $total_amount = setDefaultPriceFormat($location->order->total);
                                                        /**
                                                         * check to see whether there was a payment entry on `payments` table
                                                         * if there was a `payment` then the `remaining amount` is the amount `payable`
                                                         * if there was no `payment` then the `order total` is the amount `payable`
                                                         */
                                                        [$payment_exists, $remaining_amount] = getRemainingAmountFromPayments($location->order->id);
                                                        $remaining_amount = $payment_exists ? setDefaultPriceFormat($remaining_amount) : $total_amount;
                                                    @endphp

                                                    <tr data-payment-method="{{ $location->order->payment_method_code }}"
                                                        data-payment-type="{{ $location->order->payment_type }}"
                                                        data-remaining-amount="{{ $remaining_amount }}"
                                                        data-total-amount="{{ $total_amount }}"
                                                        data-order-status-id="{{ $location->order->order_status_id }}"
                                                        data-order-status="{{ $location->order->order_status->name }}"
                                                        class="{{($location->order->custom_order == 'No' || $location->order->order_status_id == '16' || $location->order->order_status_id == '20')?$location->order->order_status->color_class:'special_order_color'}}">
                                                        <td class="text-center">{{ $location->sort_order }}</td>
                                                        <td>{{ $location->order_id }}</td>
                                                        <td>
                                                            {{ $location->order->first_name . ' ' . $location->order->last_name }}
                                                        </td>
                                                        <td>
                                                            {{ $location->order->telephone }}
                                                        </td>
                                                        <td>
                                                            {{ $location->order->shipping_address_1 }}
                                                            <input type="hidden" class="address"
                                                                value="{{ $location->order->shipping_address_1 }}">
                                                        </td>
                                                        <td>{{ isset($location->order->total_collect_amount) ? '$' . setDefaultPriceFormat($location->order->total_collect_amount) : '$' . setDefaultPriceFormat('0') }}
                                                        </td>
                                                      
                                                        <td class="text-center">{{$location->order->order_status->name}}</td>
                                                        <td class="text-center">
                                                            {{ isset($location->route_order_status) ? $location->route_order_status->name : $location->order->order_status->name }}
                                                        </td>
                                                        <!--begin::Action=-->
                                                        <td class="text-center">
                                                            <a href="#"
                                                                class="btn btn-light btn-active-light-primary btn-sm d-flex"
                                                                data-kt-menu-trigger="click"
                                                                data-kt-menu-placement="bottom-end">Actions
                                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                                <span class="svg-icon svg-icon-5 m-0 ms-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                                        <path
                                                                            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                            fill="black" />
                                                                    </svg>
                                                                </span>
                                                                <!--end::Svg Icon-->
                                                            </a>
                                                            <!--begin::Menu-->
                                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-2"
                                                                data-kt-menu="true">
                                                                @if(!($location->order->order_status_id == "20" || $location->order->order_status_id == "16"))
                                                                <div class="menu-item px-3">
                                                                    <a class="menu-link px-3" href="javascript:void(0);"
                                                                        onclick="loadUpdateOrderModal(this, '{{ $location->order->id }}', '{{ route('routes.updateOrder', ['order_id' => $location->order->id]) }}', 'order')">Update
                                                                        Order</a>
                                                                </div>
                                                                @endif
                                                                <div class="menu-item px-3">
                                                                    <a href="javascript:void(0);"
                                                                        class="menu-link px-3"" onclick="
                                                                        getOrderSummary(this, '{{ route('orders.orderSummary', ['id' => $location->order->id]) }}'
                                                                        )">
                                                                        Order Summary
                                                                    </a>
                                                                </div>

                                                                {{-- <div class="menu-item px-3">
                                             <a class="menu-link px-3" href="javascript:void(0);"
                                                onclick="loadUpdateOrderModal(this, '{{$location->order->id}}', '{{route('routes.updateOrder', ['order_id' => $location->order->id])}}', 'payment')">Update
                                                Payment</a>
                                          </div> --}}

                                                                <div class="menu-item px-3">
                                                                    <a href="javascript:void(0);" class="menu-link px-3"
                                                                        onclick="deleteData('{{ route('route-locations.delete', ['id' => $location->id]) }}', false)">
                                                                        Delete
                                                                    </a>
                                                                </div>

                                                                <div class="menu-item px-3">
                                                                    <a class="menu-link px-3" href="javascript:void(0);"
                                                                        id="copy-address"
                                                                        onclick="copySingleAddress(this)">Copy Address</a>
                                                                </div>
                                                            </div>
                                                            <!--end::Menu-->
                                                        </td>
                                                        <!--end::Action=-->
                                                    </tr>
                                                @endif
                                            @endforeach
                                            @elseif($route->loading_status == 1)
                                            @foreach ($route->route_locations as $location)
                                            @if ($location->order)
                                                @php
                                                    $total_amount = setDefaultPriceFormat($location->order->total);
                                                    /**
                                                     * check to see whether there was a payment entry on `payments` table
                                                     * if there was a `payment` then the `remaining amount` is the amount `payable`
                                                     * if there was no `payment` then the `order total` is the amount `payable`
                                                     */
                                                    [$payment_exists, $remaining_amount] = getRemainingAmountFromPayments($location->order->id);
                                                    $remaining_amount = $payment_exists ? setDefaultPriceFormat($remaining_amount) : $total_amount;
                                                @endphp

                                                <tr data-payment-method="{{ $location->order->payment_method_code }}"
                                                    data-payment-type="{{ $location->order->payment_type }}"
                                                    data-remaining-amount="{{ $remaining_amount }}"
                                                    data-total-amount="{{ $total_amount }}"
                                                    data-order-status-id="{{ $location->order->order_status_id }}"
                                                    data-order-status="{{ $location->order->order_status->name }}"
                                                    class="{{($location->order->custom_order == 'No' || $location->order->order_status_id == '16' || $location->order->order_status_id == '20')?$location->order->order_status->color_class:'special_order_color'}}">
                                                    <td class="text-center">{{ $location->sort_order }}</td>
                                                    <td>{{ $location->order_id }}</td>
                                                    <td>
                                                        {{ $location->order->first_name . ' ' . $location->order->last_name }}
                                                    </td>
                                                    <td>
                                                        {{ $location->order->telephone }}
                                                    </td>
                                                    <td>
                                                        {{ $location->order->shipping_address_1 }}
                                                        <input type="hidden" class="address"
                                                            value="{{ $location->order->shipping_address_1 }}">
                                                    </td>
                                                    <td>{{ isset($location->order->total_collect_amount) ? '$' . setDefaultPriceFormat($location->order->total_collect_amount) : '$' . setDefaultPriceFormat('0') }}
                                                    </td>
                                                  
                                                    <td class="text-center">{{$location->order->order_status->name}}</td>
                                                    <td class="text-center">
                                                        {{ isset($location->route_order_status) ? $location->route_order_status->name : $location->order->order_status->name }}
                                                    </td>
                                                    <!--begin::Action=-->
                                                    <td class="text-center">
                                                        <a href="#"
                                                            class="btn btn-light btn-active-light-primary btn-sm d-flex"
                                                            data-kt-menu-trigger="click"
                                                            data-kt-menu-placement="bottom-end">Actions
                                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                            <span class="svg-icon svg-icon-5 m-0 ms-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path
                                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                        fill="black" />
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->
                                                        </a>
                                                        <!--begin::Menu-->
                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-2"
                                                            data-kt-menu="true">
                                                            @if(!($location->order->order_status_id == "20" || $location->order->order_status_id == "16"))
                                                            <div class="menu-item px-3">
                                                                <a class="menu-link px-3" href="javascript:void(0);"
                                                                    onclick="loadUpdateOrderModal(this, '{{ $location->order->id }}', '{{ route('routes.updateOrder', ['order_id' => $location->order->id]) }}', 'order')">Update
                                                                    Order</a>
                                                            </div>
                                                            @endif

                                                            <div class="menu-item px-3">
                                                                <a href="javascript:void(0);"
                                                                    class="menu-link px-3"" onclick="
                                                                    getOrderSummary(this, '{{ route('orders.orderSummary', ['id' => $location->order->id]) }}'
                                                                    )">
                                                                    Order Summary
                                                                </a>
                                                            </div>

                                                            {{-- <div class="menu-item px-3">
                                         <a class="menu-link px-3" href="javascript:void(0);"
                                            onclick="loadUpdateOrderModal(this, '{{$location->order->id}}', '{{route('routes.updateOrder', ['order_id' => $location->order->id])}}', 'payment')">Update
                                            Payment</a>
                                      </div> --}}

                                                            <div class="menu-item px-3">
                                                                <a href="javascript:void(0);" class="menu-link px-3"
                                                                    onclick="deleteData('{{ route('route-locations.delete', ['id' => $location->id]) }}', false)">
                                                                    Delete
                                                                </a>
                                                            </div>

                                                            <div class="menu-item px-3">
                                                                <a class="menu-link px-3" href="javascript:void(0);"
                                                                    id="copy-address"
                                                                    onclick="copySingleAddress(this)">Copy Address</a>
                                                            </div>
                                                        </div>
                                                        <!--end::Menu-->
                                                    </td>
                                                    <!--end::Action=-->
                                                </tr>
                                            @endif
                                        @endforeach

                                            @endif
                                        @else
                                            <tr>
                                                <td colspan="10" class="text-center"><strong>No Data Found...</strong>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
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
                    <h2 class="modal-title"><i class="fas fa-sync-alt"></i> &nbsp;Update Order</h2>

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
                                        x="0" y="7" width="16" height="2" rx="1">
                                    </rect>
                                </g>
                            </svg>
                        </span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form action="{{ route('order-histories.store') }}" method="POST"
                        onsubmit="return addOrderHistory(this);">
                        <div id="order-detail"></div>

                        <div class="row">
                            <div class="col-md-12">

                                @csrf
                                <input type="hidden" name="route_id" value="{{ $route->id }}">
                                <input type="hidden" name="order_id" id="order-id">
                                <input type="hidden" id="order-payment-method" name="payment_method">
                                <input type="hidden" id="order-payment-type">
                                <input type="hidden" id="order-remaining-amount">
                                <input type="hidden" id="order-total-amount" name="total_amount">
                                <div class="row">
                                    <div class="mb-5 col-md-6">
                                        <label class="form-label required" for="order_status_id">Order Status </label>
                                        <select class="form-select form-select-solid" id="order_status_id"
                                            name="order_status_id" required onchange="handleRemoveOrderVisibility(this)">
                                            <option value="" selected disabled>-- Select Order Status --</option>
                                            @foreach ($order_statuses as $order_status)
                                                @if (Auth::guard('web')->user()->hasRole('Delivery Rep') && !in_array($order_status->name, ['Postpone', 'Done', 'Canceled', 'Partially Done'])) {
                                                    @continue
                                                @endif
                                                <option value="{{ $order_status->id }}">{{ $order_status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-5 col-md-6">
                                        <label for="comment" class="form-label">Comment </label>
                                        <textarea
                                            class="form-control form-control-solid @error('comment') is-invalid @enderror"
                                            name="comment" id="comment" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="offset-md-2 col-md-8 offset-md-2 d-none" id="partial-done-table">

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-5 col-md-6">
                                        <label for="" class="form-label">Notify Customer</label>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" name="notify" value="1"
                                                        id="notify" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">



                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-end">
                                        <div class="form-check form-switch form-check-custom form-check-solid d-inline me-5"
                                            id="remove-order-div">
                                            <input type="hidden" name="route_id" value="{{ $id }}">
                                            <input class="form-check-input" type="checkbox" value="1" name="is_removable"
                                                id="is_removable" />
                                            <label class="form-check-label" for="is_removable">
                                                Remove Order
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm">Update Order</button>
                                    </div>
                                </div>
                    </form>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    </div>
    <div class="modal fade" tabindex="-1" id="update-payment">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title"><i class="fas fa-sync-alt"></i> &nbsp;Update Payment</h2>

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
                                        x="0" y="7" width="16" height="2" rx="1">
                                    </rect>
                                </g>
                            </svg>
                        </span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div id="order-detail"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('order-histories.store') }}" method="POST"
                                onsubmit="return addOrderHistory(this, 'payment');">
                                @csrf
                                <input type="hidden" name="route_id" value="{{ $route->id }}">
                                <input type="hidden" name="order_id" id="order-id">
                                <input type="hidden" id="order-payment-method" name="payment_method">
                                <input type="hidden" id="order-payment-type">
                                <input type="hidden" id="order-remaining-amount">
                                <input type="hidden" id="order-total-amount" name="total_amount">
                                <div class="row">
                                    <div class="mb-5 col-md-6">
                                        <label class="form-label required" for="order_status">Order Status </label>
                                        <input type="hidden" id="order-status-id" name="order_status_id">
                                        <input type="hidden" id="payment-received" name="payment_received" value="false">
                                        <input class="form-control form-control-solid" type="text" id="order-status"
                                            name="order_status" readonly>
                                    </div>

                                    <div class="mb-5 col-md-6 d-none" id="payment-mode-section">
                                        <label for="payment_mode" class="form-label">Payment Mode</label>
                                        <select class="form-select form-select-solid" aria-label="payment_mode"
                                            id="payment_mode" name="payment_mode" onchange="hideShowBillSection()">
                                            <option value="online transfer">Online Transfer</option>
                                            <option value="cash">Cash</option>
                                            <option value="card">Card (Credit/Debit)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row d-none bills-section">
                                    <div class="mb-5 offset-md-6 col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 mb-5">
                                                <label for="hundred" class="form-label">100's</label>
                                                <input class="form-control form-control-solid form-control-sm"
                                                    type="number" placeholder="100" id="hundred" name="bills[hundred]"
                                                    value="{{ old('hundred') }}">
                                            </div>
                                            <div class="col-md-6 mb-5">
                                                <label for="fifty" class="form-label">50's</label>
                                                <input class="form-control form-control-solid form-control-sm"
                                                    type="number" placeholder="50" id="fifty" name="bills[fifty]"
                                                    value="{{ old('fifty') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-5">
                                                <label for="twenty" class="form-label">20's</label>
                                                <input class="form-control form-control-solid form-control-sm"
                                                    type="number" placeholder="20" id="twenty" name="bills[twenty]"
                                                    value="{{ old('twenty') }}">
                                            </div>
                                            <div class="col-md-6 mb-5">
                                                <label for="ten" class="form-label">10's</label>
                                                <input class="form-control form-control-solid form-control-sm"
                                                    type="number" placeholder="10" id="ten" name="bills[ten]"
                                                    value="{{ old('ten') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-5">
                                                <label for="five" class="form-label">5's</label>
                                                <input class="form-control form-control-solid form-control-sm"
                                                    type="number" placeholder="5" id="five" name="bills[five]"
                                                    value="{{ old('five') }}">
                                            </div>
                                            <div class="col-md-6 mb-5">
                                                <label for="two" class="form-label">2's</label>
                                                <input class="form-control form-control-solid form-control-sm"
                                                    type="number" placeholder="2" id="two" name="bills[two]"
                                                    value="{{ old('two') }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-5">
                                                <label for="one" class="form-label">1's</label>
                                                <input class="form-control form-control-solid form-control-sm"
                                                    type="number" placeholder="1" id="one" name="bills[one]"
                                                    value="{{ old('one') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-end">
                                        <button type="submit" id="update-payment-button"
                                            class="btn btn-success btn-sm">Update Payment</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" id="optimize-route-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title"><i class="fas fa-route"></i> &nbsp;Route Optimization</h2>

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
                                        x="0" y="7" width="16" height="2" rx="1">
                                    </rect>
                                </g>
                            </svg>
                        </span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3>
                                        Unoptimized Routes
                                        <div class="spinner-border text-dark ms-5 d-none custom-loader" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </h3>
                                </div>
                                <div class="col-md-6 text-end ">
                                    <button class="btn btn-sm btn-info" onclick="copyToClipboard()" id="copy"><i
                                            class="far fa-copy"></i> Copy Addresses</button>
                                </div>
                            </div>

                            <div class="table-responsive mt-5">
                                <div class="row">
                                    <div class="mb-5 col-md-6">

                                        <label class="form-label" for="start_location_id">Start Location</label>
                                        <select
                                            class="form-select form-select-solid @error('start_location_id') is-invalid @enderror"
                                            name="start_location_id" id="start_location_id">
                                            <option value="" selected disabled>Select Store</option>
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id }}" @if ($route->start_location->id == $store->id)selected @endif>
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
                                    <div class="mb-5 col-md-6">
                                        <label class="form-label" for="end_location">End Location</label>
                                        <input
                                            class="form-control form-control-solid @error('end_location') is-invalid @enderror"
                                            name="end_location" value="{{ $route->end_location }}" id="end_location">
                                        @error('end_location')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <table
                                    class="table table-sm table-row-bordered table-striped table-row-gray-300 border gs-3 gy-3"
                                    id="unoptimized-routes">
                                    <thead>
                                        <tr class="fw-bolder fs-6 text-gray-800">
                                            <th style="width: 3%">
                                                <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                    <input class="form-check-input multi-assign-checkbox" checked
                                                        type="checkbox" data-kt-check="true"
                                                        data-kt-check-target="#unoptimized-routes .form-check-input" />
                                                </div>
                                            </th>
                                            <th style="width: 8%">Order #</th>
                                            <th style="width: 15%">Customer Name</th>
                                            <th style="width: 59%">Address</th>
                                            <th class="text-center" style="width: 8%">Sort Order</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $addresses = $route->start_location->address . "\n";
                                        @endphp
                                        @if (count($route->route_locations) > 0)
                                            @foreach ($route->route_locations as $location)
                                                @if ($location->order)
                                                    @php
                                                        $total_amount = setDefaultPriceFormat($location->order->total);
                                                        /**
                                                         * check to see whether there was a payment entry on `payments` table
                                                         * if there was a `payment` then the `remaining amount` is the amount `payable`
                                                         * if there was no `payment` then the `order total` is the amount `payable`
                                                         */
                                                        [$payment_exists, $remaining_amount] = getRemainingAmountFromPayments($location->order->id);
                                                        $remaining_amount = $payment_exists ? setDefaultPriceFormat($remaining_amount) : $total_amount;
                                                        $addresses .= $location->order->shipping_address_1 . "\n";
                                                    @endphp
                                                    <tr data-payment-method="{{ $location->order->payment_method_code }}"
                                                        data-payment-type="{{ $location->order->payment_type }}"
                                                        data-remaining-amount="{{ $remaining_amount }}"
                                                        data-total-amount="{{ $total_amount }}">
                                                        <td>
                                                            <div
                                                                class="form-check form-check-sm form-check-custom form-check-solid">
                                                                <input class="form-check-input multi-assign-checkbox"
                                                                    checked type="checkbox" name="id"
                                                                    value="{{ $location->order->id }}" />
                                                            </div>
                                                        </td>
                                                        <td>{{ $location->order_id }}</td>
                                                        <td>
                                                            {{ $location->order->first_name . ' ' . $location->order->last_name }}
                                                        </td>
                                                        <td class="address">
                                                            {{ $location->order->shipping_address_1 }}</td>
                                                        <td class="text-center">{{ $location->sort_order }}</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle"
                                                                    type="button" id="action" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <ul class="dropdown-menu" aria-labelledby="action">

                                                                    <li>
                                                                        <a href="javascript:void(0);"
                                                                            class="dropdown-item"
                                                                            onclick="removeAddress(this, '{{ route('route-locations.delete', ['id' => $location->id]) }}')">
                                                                            <i class="far fa-trash-alt me-2"></i> Delete
                                                                        </a>
                                                                    </li>
                                                                    @if ($location->order->shipping_lat == '0.0000' && $location->order->shipping_lng == '0.0000')
                                                                        <li>
                                                                            <a href="javascript:void(0);"
                                                                                class="dropdown-item"
                                                                                onclick="getLatLng(this, '{{ route('orders.getLatLng', ['id' => $location->order->id]) }}')">
                                                                                <i class="fas fa-street-view"></i> Get
                                                                                Lat/Lng
                                                                            </a>
                                                                        </li>

                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10" class="text-center"><strong>No Data Found...</strong>
                                                </td>
                                            </tr>
                                        @endif
                                        <input type="hidden" value="{{ $addresses }}" id="addresses">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h3>Optimized Routes</h3>
                            <div class="table-responsive mt-5" id="optimization-res-div">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button class="btn btn-sm btn-success"
                                onclick="loadOptimizationModal('{{ route('routes.getOptimizedRoutes', ['id' => $route->id]) }}')">Optimize
                                Routes</button>
                            <button class="btn btn-sm btn-success"
                                onclick="updateRoutes('{{ route('routes.optimizeRoutes', ['id' => $id]) }}')">Update
                                Routes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <div class="modal fade" tabindex="-1" id="route-order-cash">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title"><i class="fas fa-sync-alt"></i> &nbsp;Route Order Cash Summary</h2>

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
                                        x="0" y="7" width="16" height="2" rx="1">
                                    </rect>
                                </g>
                            </svg>
                        </span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div id="route-order-cash-summary"></div>


                </div>
            </div>
        </div>
    </div>

    <div id="summary"></div>

    <div class="modal fade" tabindex="-1" id="order-summary">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">



                <div id="route-order-summary"></div>



            </div>
        </div>
    </div>
    </div>
    <!--end::Post-->
@endsection

@push('page_lvl_js')
    <script src="{{ asset('/') }}custom/route.js"></script>
    <script>
        function changeTab(el, id) {
            if (id == 'tab-order-summary') {
                $("#" + id).addClass('active');
                $('#tab-order-comment').removeClass('active');
                $('.route-order-comment').addClass('d-none');
                $('.route-order-cash').removeClass('d-none');

            } else {
                $("#" + id).addClass('active');
                $('#tab-order-summary').removeClass('active');
                $('.route-order-comment').removeClass('d-none');
                $('.route-order-cash').addClass('d-none');
            }

        }

        var tableBody = document.querySelector('.table-responsive');
        var curDown = false
        var oldScrollLeft = 0;
        var curXPos = 0;
        // var oldScrollTop = 0;

        if (tableBody) {

            tableBody.addEventListener("mousemove", function(e) {
                if (curDown === true) {

                    $('.table-responsive').scrollLeft(oldScrollLeft + (curXPos - e.pageX));


                }
            })
            tableBody.addEventListener("mousedown", function(e) {

                curDown = false;
                if (e.which == 1 || e.which == 3) {
                    curDown = true;
                    // curYPos = e.pageY;

                    oldScrollLeft = $('.table-responsive').scrollLeft();
                    curXPos = e.pageX;
                } else {
                    curDown = false;
                }
                // oldScrollTop = tableBody.scrollTop;
            })
            tableBody.addEventListener("mouseup", function(e) {
                curDown = false;
            })
        }
    </script>
@endpush
