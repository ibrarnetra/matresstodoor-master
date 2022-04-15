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
            <div class="d-flex align-items-center py-1">
                <a href="{{ route('orders.generateInvoice', ['id' => $id]) }}" target="_blank"
                    class="btn btn-success btn-sm me-2">
                    <i class="far fa-file-alt"></i> Generate Invoice
                </a>
                @can('Edit-Orders')
                    <a href="{{ route('orders.edit', ['id' => $id]) }}" class="btn btn-warning btn-sm me-2">
                        <i class="far fa-edit"></i> Edit Order
                    </a>
                @endcan
                @can('Add-Orders')
                    <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i>
                        Create New</a>
                @endcan
            </div>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header border-1">
                                    <h3 class="card-title">
                                        <span class="card-label fw-bolder fs-3"><i class="fas fa-shopping-cart"></i>
                                            &nbsp;Order Details</span>
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <p class="card-text"><i class="fas fa-receipt"></i> &nbsp;<span
                                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top"
                                        title="Invoice Number">{{ $order->invoice_no }}</span>
                                </p>
                                    @if (isset($order->store_name) && $order->store_name != '' && !is_null($order->store_name))
                                        <p class="card-text"><i class="fas fa-store-alt"></i>
                                            &nbsp; <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                                data-bs-placement="top" title="Store">{{ $order->store_name }}</span></p>
                                    @endif
                                    <p class="card-text"><i class="fas fa-calendar-alt"></i>
                                        &nbsp; <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                            data-bs-placement="top"
                                            title="Order Date">{{ date(getConstant('DATE_FORMAT'), strtotime($order->created_at)) }}</span>
                                    </p>
                                    @if ($order->payment_method_id != 0)
                                        <p class="card-text"><i class="far fa-credit-card"></i>
                                            &nbsp; <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                                data-bs-placement="top"
                                                title="Payment Method">{{ $order->payment_method }}
                                                ({{ $order->payment_method_code }})</span></p>
                                    @endif
                                    @if ($order->shipping_method_id != 0)
                                        <p class="card-text"><i class="fas fa-truck"></i>
                                            &nbsp; <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                                data-bs-placement="top"
                                                title="Shipping Method">{{ $order->shipping_method }}
                                                ({{ $order->shipping_method_code }})</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header border-1">
                                    <h3 class="card-title">
                                        <span class="card-label fw-bolder fs-3"><i class="fas fa-user"></i>
                                            &nbsp;Customer Details</span>
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <p class="card-text"><i class="fas fa-user"></i> &nbsp;
                                        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                            data-bs-placement="top" title="Customer">
                                            {{ $order->first_name . ' ' . $order->last_name }}
                                        </span>
                                    </p>
                                    @if ($order->customer_group_id != 0)
                                        <p class="card-text"><i class="fas fa-user-friends"></i>
                                            &nbsp; <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                                data-bs-placement="top"
                                                title="Customer Group">{{ $order->customer_group->eng_description->name }}</span>
                                        </p>
                                    @endif
                                    <p class="card-text"><i class="fas fa-envelope"></i>
                                        &nbsp; <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                            data-bs-placement="top" title="Email">{{ $order->email }}</span></p>
                                    <p class="card-text"><i class="fas fa-phone-alt"></i>
                                        &nbsp; <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                            data-bs-placement="top" title="Telephone">{{ $order->telephone }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header border-1">
                                    <h3 class="card-title">
                                        <span class="card-label fw-bolder fs-3"><i class="fas fa-route"></i>
                                            &nbsp;Route Detail</span>
                                    </h3>
                                </div>

                                <div class="card-body">
                                    @if (isset($order->route_locations) && count($order->route_locations)>0)
                                     @foreach($order->route_locations as $route_location)
                                     @if(isset($route_location->route) && $route_location->route)

                                    <p class="card-text"><i class="fas fa-route"></i>
                                        &nbsp; <a style="color:#181C32" href="{{ route('routes.detail', ['id' => $route_location->route->id]) }}" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                            data-bs-placement="top"
                                            title="{{$route_location->route->name}}">{{ $route_location->route->name }}</a>
                                    </p>
                                    @endif
                                    @endforeach
                                @endif
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $currency_sign = '$';
                        if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != '') {
                            $currency_sign = $order->currency->symbol_left;
                        }
                        
                    @endphp

                    <div class="row mt-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header border-1">
                                    <h3 class="card-title">
                                        <span class="card-label fw-bolder fs-3"><i class="fas fa-info-circle"></i>
                                            &nbsp;Order # {{ $order->id }}</span>
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-sm table-row-bordered table-row-gray-300 border gs-3 gy-3">
                                                    <thead>
                                                        <tr class="fw-bolder fs-6 text-gray-800">
                                                            <th style="width: 50%">Payment Address</th>
                                                            <th style="width: 50%">Shipping Address</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <p>{{ $order->payment_first_name . ' ' . $order->payment_last_name }}
                                                                </p>
                                                                <p>{{ $order->payment_address_1 }}</p>
                                                                @if (isset($order->payment_postcode) && !is_null($order->payment_postcode) && $order->payment_postcode == '')
                                                                    <p>{{ $order->payment_postcode }}</p>
                                                                @endif
                                                                <p>{{ $order->payment_country }}</p>
                                                                <p>{{ $order->payment_zone }}</p>
                                                                <p>{{ $order->payment_city }}</p>
                                                            </td>
                                                            <td>
                                                                <p>{{ $order->shipping_first_name . ' ' . $order->shipping_last_name }}
                                                                </p>
                                                                <p>{{ $order->shipping_address_1 }}</p>
                                                                @if (isset($order->shipping_postcode) && !is_null($order->shipping_postcode) && $order->shipping_postcode == '')
                                                                    <p>{{ $order->shipping_postcode }}</p>
                                                                @endif
                                                                <p>{{ $order->shipping_country }}</p>
                                                                <p>{{ $order->shipping_zone }}</p>
                                                                <p>{{ $order->shipping_city }}</p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-sm table-row-bordered table-column-bordered table-row-gray-300 border gs-3 gy-3">
                                                    <thead>
                                                        <tr class="fw-bolder fs-6 text-gray-800">
                                                            <th style="width: 40%;">Product</th>
                                                            <th style="width: 20%;">Model</th>
                                                            <th style="width: 10%; text-align: right;">Qty</th>
                                                            <th style="width: 10%; text-align: right;">Return Qty</th>
                                                            <th style="width: 15%; text-align: right;">Unit Price</th>
                                                            <th style="width: 15%; text-align: right;">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($order->order_products as $product)
                                                            <tr>
                                                                <td>
                                                                    {{ $product->name }}
                                                                    @if (count($product->order_options) > 0)
                                                                        @foreach ($product->order_options as $option)
                                                                            <br>
                                                                            - {{ $option->name }}:
                                                                            {{ $option->value }}
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                <td>{{ $product->product->model }}</td>
                                                                <td class="text-end">{{ $product->quantity }}</td>
                                                                <td class="text-center">{{ $product->return_quantity }}</td>
                                                                <td class="text-end">
                                                                    @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($product->price) }}
                                                                </td>
                                                                <td class="text-end">
                                                                    @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($product->total) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @foreach ($order->order_totals as $total)

                                                            <tr>
                                                                @if ($total->code == 'shipping')
                                                                    <td colspan="4"
                                                                        class="fw-bolder fs-6 text-gray-800 text-end">
                                                                        Shipping ({{ $total->title }})</td>
                                                                    <td class="fw-bolder fs-6 text-gray-800 text-end">
                                                                        @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($total->value) }}
                                                                    </td>
                                                                @elseif ($total->code == "payment_method")
                                                                    <td colspan="4"
                                                                        class="fw-bolder fs-6 text-gray-800 text-end">
                                                                        Payment Method</td>
                                                                    <td class="fw-bolder fs-6 text-gray-800 text-end">
                                                                        {{ $total->title }}</td>
                                                                @elseif ($total->code == "payment_type")
                                                                    <td colspan="4"
                                                                        class="fw-bolder fs-6 text-gray-800 text-end">
                                                                        Payment Type</td>
                                                                    <td class="fw-bolder fs-6 text-gray-800 text-end">
                                                                        {{ $total->title }}</td>
                                                                @elseif ($total->code == "payment_mode")
                                                                    <td colspan="4"
                                                                        class="fw-bolder fs-6 text-gray-800 text-end">
                                                                        Payment Mode</td>
                                                                    <td class="fw-bolder fs-6 text-gray-800 text-end">
                                                                        {{ $total->title }}</td>
                                                                @elseif ($total->code == "discount")
                                                                    <td colspan="4"
                                                                        class="fw-bolder fs-6 text-gray-800 text-end">
                                                                        Discount ({{ $total->title }})</td>
                                                                    <td class="fw-bolder fs-6 text-gray-800 text-end">
                                                                        @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($total->value) }}
                                                                    </td>
                                                                @else
                                                                    @if ($total->code == 'grand_total')
                                                                        @php $grand_total = $total->value; @endphp
                                                                    @endif
                                                                    @if ($total->code != 'paid_amount' && $total->code != 'remaining_amount')
                                                                        <td colspan="4"
                                                                            class="fw-bolder fs-6 text-gray-800 text-end">
                                                                            {{ $total->title }}</td>
                                                                        <td class="fw-bolder fs-6 text-gray-800 text-end">
                                                                            @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ setDefaultPriceFormat($total->value) }}
                                                                        </td>
                                                                    @endif
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                        @php
                                                            $total_paid_amount = '0.00';
                                                            $total_remaining_amount = '0.00';
                                                            $total_return_amount = '0.00';
                                                            foreach ($order->payments as $payment) {
                                                                $total_paid_amount += $payment->paid_amount;
                                                                $total_return_amount += $payment->return_amount;
                                                            }
                                                            if (isset($grand_total)) {
                                                                $total_remaining_amount = $grand_total - $total_paid_amount - $total_return_amount;
                                                            }
                                                        @endphp

                                                        <tr>
                                                            <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">
                                                                Paid Amount</td>
                                                            <td class="fw-bolder fs-6 text-gray-800 text-end">

                                                                @if (isset($total_paid_amount))

                                                                    {{ $currency_sign }}{{ setDefaultPriceFormat($total_paid_amount) }}
                                                                @else
                                                                    {{ $currency_sign }}0.00
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">
                                                                Remaining Amount</td>
                                                            <td class="fw-bolder fs-6 text-gray-800 text-end">

                                                                @if (isset($total_remaining_amount))
                                                                    {{ $currency_sign }}{{ setDefaultPriceFormat($total_remaining_amount) }}
                                                                @else
                                                                    {{ $currency_sign }}0.00
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">
                                                                Return Amount</td>
                                                            <td class="fw-bolder fs-6 text-gray-800 text-end">

                                                                @if (isset($total_return_amount))
                                                                    {{ $currency_sign }}{{ setDefaultPriceFormat($total_return_amount) }}
                                                                @else
                                                                    {{ $currency_sign }}0.00
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">
                                                                Delivery Date</td>
                                                            <td class="fw-bolder fs-6 text-gray-800 text-end">
                                                                @if ($order->delivery_date)
                                                                    {{ date(getConstant('DATE_FORMAT'),strtotime($order->delivery_date)) }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" class="fw-bolder fs-6 text-gray-800 text-end">
                                                                Custom Order:</td>
                                                            <td class="fw-bolder fs-6 text-gray-800 text-end">

                                                                {{ $order->custom_order }}

                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="5" class="fs-6 text-gray-800 text-start"><span
                                                                    class="fw-bolder">Customer Notes:</span>
                                                                @if ($order->customer_notes)
                                                                    {{ $order->customer_notes }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header border-1">
                                    <h3 class="card-title">
                                        <span class="card-label fw-bolder fs-3"><i class="far fa-money-bill-alt"></i>
                                            &nbsp;Cash Summary</span>
                                    </h3>
                                </div>

                                <div class="card-body">
                                    @if (count($order->payments))
                                        <div class="table-responsive">
                                            <table
                                                class="table table-sm table-row-bordered table-striped table-row-gray-300 border gs-3 gy-3">
                                                <thead>
                                                    <tr class="fw-bolder fs-6 text-gray-800">
                                                        <th>Payment Method</th>
                                                        <th>Payment type</th>
                                                        <th>Payment Mode</th>
                                                        <th>Paid Amount</th>
                                                        <th>Remaining Amount</th>
                                                        <th>Date</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($order->payments as $payment)

                                                        <tr>
                                                            <td>{{ $payment->method }}</td>
                                                            <td>{{ $payment->type }}</td>
                                                            <td>{{ $payment->mode }}</td>
                                                            <td> @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ $payment->paid_amount }}</td>
                                                            <td> @if (isset($order->currency->symbol_left) && !is_null($order->currency->symbol_left) && $order->currency->symbol_left != ''){{ $order->currency->symbol_left }}@else $@endif{{ $payment->remaining_amount }}
                                                            </td>
                                                            <td>{{ date(getConstant('DATE_FORMAT'), strtotime($payment->updated_at)) }}
                                                            </td>
                                                        </tr>
                                                        @if (count($payment->orderBills) > 0)
                                                            <tr class="fw-bolder fs-6 text-gray-800">

                                                                @foreach ($payment->orderBills as $bill)
                                                                    <th
                                                                        style="width: {{ setDefaultPriceFormat(100 / count($order->order_bills)) }}%">
                                                                        {{ ucfirst($bill->bill_type) }}</th>
                                                                @endforeach

                                                            </tr>
                                                            @foreach ($payment->orderBills as $bill)
                                                                <td
                                                                    style="width: {{ setDefaultPriceFormat(100 / count($order->order_bills)) }}%">
                                                                    {{ $bill->notes }}</td>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header border-1">
                                    <h3 class="card-title">
                                        <span class="card-label fw-bolder fs-3"><i class="far fa-comment-dots"></i>
                                            &nbsp;Order History</span>
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="tab"
                                                        href="#history">History</a>
                                                </li>
                                            </ul>

                                            <div class="tab-content tabcontent-border mt-3">
                                                <div class="tab-pane active" id="history" role="history">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table table-sm table-row-bordered table-striped table-row-gray-300 border gs-3 gy-3">
                                                                    <thead>
                                                                        <tr class="fw-bolder fs-6 text-gray-800">
                                                                            <th style="width: 15%">Date Added</th>
                                                                            <th style="width: 35%">Comment</th>
                                                                            <th style="width: 10%; text-align: center;">
                                                                                Notified</th>
                                                                            <th style="width: 10%; text-align: center;">
                                                                                Status</th>
                                                                            <th style="width: 10%; text-align: center;">
                                                                                Delivery Date</th>
                                                                            <th style="width: 20%; text-align: center;">
                                                                                Updated By</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($order->order_histories as $order_history)
                                                                            <tr>
                                                                                <td>{{ date(getConstant('DATE_FORMAT'), strtotime($order_history->created_at)) }}
                                                                                </td>
                                                                                <td>
                                                                                    @if ($order_history->comment == '')
                                                                                        N/A
                                                                                    @else
                                                                                        {{ $order_history->comment }}
                                                                                    @endif
                                                                                </td>
                                                                                <td style="text-align: center;">
                                                                                    @if ($order_history->notify == 0)
                                                                                        No
                                                                                    @else
                                                                                        Yes
                                                                                    @endif
                                                                                </td>
                                                                                <td style="text-align: center;">
                                                                                    {{ $order_history->order_status->name }}
                                                                                </td>
                                                                                <td style="text-align: center;">
                                                                                    @if ($order_history->delivery_date)
                                                                                        {{ date(getConstant('DATE_FORMAT'), strtotime($order_history->delivery_date)) }}
                                                                                    @else
                                                                                        @if ($order->delivery_date)
                                                                                            {{ date(getConstant('DATE_FORMAT'), strtotime($order->delivery_date)) }}
                                                                                        @else
                                                                                            N/A
                                                                                        @endif
                                                                                    @endif
                                                                                </td>
                                                                                <td style="text-align: center;">
                                                                                    @if ($order_history->generated_by)
                                                                                        {{ $order_history->generated_by->first_name . ' ' . $order_history->generated_by->last_name }}
                                                                                        ({{ $order_history->generated_by->roles[0]->name }})
                                                                                    @else
                                                                                        N/A
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-5">
                                                        <div class="col-md-12">
                                                            <form action="{{ route('order-histories.store') }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="order_id"
                                                                    value="{{ $id }}">
                                                                <h2>Add Order History</h2>
                                                                <hr>
                                                                <div class="row mt-10">
                                                                    <div class="mb-5 col-md-6">
                                                                        <label class="form-label required"
                                                                            for="order_status_id">Order Status </label>
                                                                        <select class="form-select form-select-solid"
                                                                            id="order_status_id" name="order_status_id"
                                                                            required>
                                                                            <option value="" selected disabled>-- Select
                                                                                Order Status --</option>
                                                                            @foreach ($order_statuses as $order_status)
                                                                                @if (Auth::guard('web')->user()->hasRole('Delivery Rep') &&
        !in_array($order_status->name, ['Postpone', 'Done', 'Canceled'])) {
                                                                                    @continue
                                                                                @endif
                                                                                <option value="{{ $order_status->id }}">
                                                                                    {{ $order_status->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('order_status_id')
                                                                            <div class="invalid-feedback">
                                                                                <strong>{{ $message }}</strong>
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <div class="mb-5 col-md-6">
                                                                        <label for="comment" class="form-label">Comment
                                                                        </label>
                                                                        <textarea
                                                                            class="form-control form-control-solid @error('comment') is-invalid @enderror"
                                                                            name="comment" id="comment" rows="3"></textarea>

                                                                        @error('comment')
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="mb-5 col-md-6">
                                                                        <label for="" class="form-label">Notify
                                                                            Customer</label>
                                                                        <div class="row mt-2">
                                                                            <div class="col-md-12">
                                                                                <div
                                                                                    class="form-check form-switch form-check-custom form-check-solid">
                                                                                    <input class="form-check-input"
                                                                                        type="checkbox" name="notify"
                                                                                        value="1" id="notify" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div
                                                                        class="mb-5 col-md-6 @if (!Auth::guard('web')->user()->hasRole('Super Admin') &&
    !Auth::guard('web')->user()->hasRole('Dispatch Manager') &&
    !Auth::guard('web')->user()->hasRole('Office Admin')) d-none @endif">
                                                                        <label class="form-label"
                                                                            for="delivery_date">Delivery Date </label>
                                                                        <div class="input-group">
                                                                            <input
                                                                                class="form-control form-control-solid form-control-sm"
                                                                                name="delivery_date" autocomplete="off"
                                                                                id="delivery_date" placeholder="Pick date"
                                                                                @if (!Auth::guard('web')->user()->hasRole('Super Admin') &&
        !Auth::guard('web')->user()->hasRole('Dispatch Manager') &&
        !Auth::guard('web')->user()->hasRole('Office Admin'))
                                                                            value="{{ $order->delivery_date }}"
                                                                            @endif>
                                                                            <span
                                                                                class="input-group-text delivery-date-icon"><i
                                                                                    class="fas fa-calendar-alt"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12 text-end">
                                                                        <button type="submit"
                                                                            class="btn btn-success btn-sm">Update
                                                                            Order</button>
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
                            </div>
                        </div>
                    </div>

                    @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
        Auth::guard('web')->user()->hasRole('Office Admin') ||
        Auth::guard('web')->user()->hasRole('Dispatch Manager'))
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header border-1">
                                        <h3 class="card-title">
                                            <span class="card-label fw-bolder fs-3"><i class="fas fa-truck-loading"></i>
                                                &nbsp;Dispatch Management</span>
                                        </h3>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab"
                                                            href="#history">History</a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content tabcontent-border mt-3">
                                                    <div class="tab-pane active" id="history" role="history">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table
                                                                        class="table table-sm table-row-bordered table-striped table-row-gray-300 border gs-3 gy-3">
                                                                        <thead>
                                                                            <tr class="fw-bolder fs-6 text-gray-800">
                                                                                <th style="width: 15%">Date Added</th>
                                                                                <th style="width: 55%">Comment</th>
                                                                                <th style="width: 15%; text-align: center;">
                                                                                    Assigned To</th>
                                                                                <th style="width: 15%; text-align: center;">
                                                                                    Assigned By</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if (count($order->order_management_comments) > 0)
                                                                                @foreach ($order->order_management_comments as $order_management_comment)
                                                                                    <tr>
                                                                                        <td>{{ date(getConstant('DATE_FORMAT'), strtotime($order_management_comment->created_at)) }}
                                                                                        </td>
                                                                                        <td>{{ $order_management_comment->comment }}
                                                                                        </td>
                                                                                        <td style="text-align: center;">
                                                                                            @if ($order_management_comment->dispatcher)
                                                                                                {{ $order_management_comment->dispatcher->first_name . ' ' . $order_management_comment->dispatcher->last_name }}
                                                                                            @else
                                                                                                Unassigned
                                                                                            @endif
                                                                                        </td>
                                                                                        <td style="text-align: center;">
                                                                                            {{ $order_management_comment->assignee->first_name . ' ' . $order_management_comment->assignee->last_name }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            @else
                                                                                <th scope="row" colspan="4"
                                                                                    class="fw-bolder text-center">No data
                                                                                    found...</th>
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-5">
                                                            <div class="col-md-12">
                                                                <form
                                                                    action="{{ route('order-management-comments.store') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="order_id"
                                                                        value="{{ $id }}">
                                                                    <h2>Add Order Manager</h2>
                                                                    <hr>
                                                                    <input type="hidden" name="commented_by"
                                                                        value="{{ Auth::guard('web')->user()->id }}">

                                                                    @if (Auth::guard('web')->user()->hasRole('Super Admin') ||
        Auth::guard('web')->user()->hasRole('Office Admin'))
                                                                        <div class="row mt-10">
                                                                            <div class="mb-5 col-md-6">
                                                                                <label class="form-label required"
                                                                                    for="dispatch_manager_id">Disptach
                                                                                    Manager </label>
                                                                                <select
                                                                                    class="form-select form-select-solid"
                                                                                    id="dispatch_manager_id"
                                                                                    name="dispatch_manager_id" required>
                                                                                    <option value="0">Unassigned</option>
                                                                                    @if (count($dispatch_managers) > 0)
                                                                                        @foreach ($dispatch_managers as $dispatch_manager)
                                                                                            <option
                                                                                                value="{{ $dispatch_manager->id }}"
                                                                                                @if ($order->assigned_to == $dispatch_manager->id)
                                                                                                selected
                                                                                        @endif
                                                                                        >{{ $dispatch_manager->first_name . ' ' . $dispatch_manager->last_name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                    @endif
                                                                    </select>
                                                                    @error('dispatch_manager_id')
                                                                        <div class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </div>
                                                                    @enderror
                                                            </div>
                                                        </div>
                    @endif

                    @if (Auth::guard('web')->user()->hasRole('Dispatch Manager'))
                        <input type="hidden" name="dispatch_manager_id" value="{{ Auth::guard('web')->user()->id }}">
                    @endif

                    <div class="row">
                        <div class="mb-5 col-md-12">
                            <label for="dispatch_comment" class="form-label required">Comment </label>
                            <textarea
                                class="form-control form-control-solid @error('dispatch_comment') is-invalid @enderror"
                                name="dispatch_comment" id="dispatch_comment" rows="3" required></textarea>

                            @error('dispatch_comment')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-success btn-sm">Add Order Manager</button>
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
    </div>
    </div>
    </div>
    @endif

    </div>
    </div>
    </div>
    <!--end::Container-->
    </div>
    <!--end::Post-->
@endsection

@push('page_lvl_js')
    <script>
        $(document).ready(function() {
            initCustomDatePicker($("#delivery_date"), {
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: dateYesterday,
                startDate: dateYesterday,
                locale: {
                    format: "YYYY-MM-DD",
                    separator: "-",
                },
            });
        });

        $("#delivery_date").on("apply.daterangepicker", function(ev, picker) {
            $(this).val(picker.startDate.format("YYYY-MM-DD"));
        });

        $("#delivery_date").on("cancel.daterangepicker", function(ev, picker) {
            $(this).val("");
        });

        $('.delivery-date-icon').click(function() {
            $("#delivery_date").focus();
        });

      
    </script>
@endpush
