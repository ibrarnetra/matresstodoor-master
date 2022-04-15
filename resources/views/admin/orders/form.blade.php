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


    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
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

            {{-- form --}}

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-1 mb-5 pb-3 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">{{ $title }}</span>
                            </h3>
                        </div>

                        <div class="row row-sample d-none">
                            <div class="col-md-4 mb-5">
                                <input type="text" required name="option_value_name"
                                    class="form-control form-control-solid">
                            </div>
                            <div class="col-md-4 mb-5">
                                <input type="file" name="image" class="form-control form-control-solid" accept="image/*">
                            </div>
                            <div class="col-md-3 mb-5">
                                <input type="number" min="1" name="sort_order" class="form-control form-control-solid">
                            </div>
                            <div class="col-md-1 mb-5 text-end">
                                <button class="btn btn-sm btn-danger del_button" type="button"><i
                                        class="fas fa-minus-circle"></i></button>
                            </div>
                        </div>
                        <!--end::Header-->

                        <div class="card-body pt-0">
                            <form action="{{ route('orders.addToCart') }}" method="POST" id="add-to-cart">
                                @csrf
                            </form>

                            <div class="row">
                                <div class="col-md-12">
                                    <!--begin::Stepper-->
                                    <div class="stepper stepper-links" id="multi_step">
                                        <!--begin::Nav-->
                                        <div class="stepper-nav mb-5 custom-stepper-nav">
                                            <!--begin::Step 1-->
                                            <div class="stepper-item current ms-0" data-kt-stepper-element="nav">
                                                <h3 class="stepper-title">
                                                    General
                                                </h3>
                                            </div>
                                            <!--end::Step 1-->

                                            <!--begin::Step 2-->
                                            <div class="stepper-item" data-kt-stepper-element="nav">
                                                <h3 class="stepper-title">
                                                    Cart
                                                </h3>
                                            </div>
                                            <!--end::Step 2-->

                                            <!--begin::Step 3-->
                                            <div class="stepper-item" data-kt-stepper-element="nav">
                                                <h3 class="stepper-title">
                                                    Shipping Address
                                                </h3>
                                            </div>
                                            <!--end::Step 3-->

                                            <!--begin::Step 4-->
                                            <div class="stepper-item" data-kt-stepper-element="nav">
                                                <h3 class="stepper-title">
                                                    Payment / Shipping Method
                                                </h3>
                                            </div>
                                            <!--end::Step 4-->

                                            <!--begin::Step 5-->
                                            <div class="stepper-item" data-kt-stepper-element="nav">
                                                <h3 class="stepper-title">
                                                    Order Confirmation
                                                </h3>
                                            </div>
                                            <!--end::Step 5-->
                                        </div>
                                        <!--end::Nav-->

                                        <!--begin::Form-->
                                        <form class="form" id="multi_step_form" onsubmit="return
                                        handleOrderSubmit(this);" novalidate method="POST"
                                            enctype="multipart/form-data" @if ($type == 'create') action="{{ route('orders.store') }}"
                                        @else
                                            action="{{ route('orders.update', ['id' => $id]) }}" @endif >
                                            @csrf
                                            <input type="hidden" name="is_clone" value="{{ $is_clone }}">
                                            <!--begin::Group-->
                                            <div class="mb-5">
                                                <!--begin::Step 1-->
                                                <div class="flex-column current" data-kt-stepper-element="content">
                                                    <!--begin::Input group-->
                                                    <div class="row">
                                                        <div class="col-md-4 mb-5">
                                                            <div class="fv-row">
                                                                <label class="form-label required"
                                                                    for="currency_id">Currency</label>
                                                                <select
                                                                    class="form-select form-select-solid @error('currency_id') is-invalid @enderror"
                                                                    id="currency_id" name="currency_id"
                                                                    onchange="setCurrency(this)" required>
                                                                    @if (count($currencies) > 0)
                                                                        @foreach ($currencies as $currency)
                                                                            <option value="{{ $currency->id }}"
                                                                                @if (isset($currency->symbol_left) && !is_null($currency->symbol_left)) data-symbol="{{ $currency->symbol_left }}" @else data-symbol="$" @endif
                                                                                data-code={{ $currency->code }}
                                                                                data-value="{{ $currency->value }}"
                                                                                @if ($type == 'edit' && $modal['currency_id'] == $currency->id)
                                                                                selected
                                                                        @endif
                                                                        >{{ $currency->title }}
                                                                        </option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                                <input type="hidden" name="currency_code" id="currency_code"
                                                                    @if ($type == 'edit' && isset($modal['currency_code']))
                                                                value="{{ $modal['currency_code'] }}" @endif>
                                                                <input type="hidden" name="currency_value"
                                                                    id="currency_value" @if ($type == 'edit' && isset($modal['currency_value']))
                                                                value="{{ $modal['currency_value'] }}" @endif>
                                                                @error('currency_id')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-5">
                                                            <div class="fv-row">
                                                                <label class="form-label required" for="customer_group_id"
                                                                    class="form-label">Customer Group</label>
                                                                <select
                                                                    class="form-select form-select-solid @error('customer_group_id') is-invalid @enderror"
                                                                    aria-label="customer_group_id" id="customer_group_id"
                                                                    name="customer_group_id">
                                                                    @if (count($customer_groups) > 0)
                                                                        @foreach ($customer_groups as $customer_group)
                                                                            <option value="{{ $customer_group->id }}"
                                                                                @if ($type == 'edit' && $modal['customer_group_id'] == $customer_group->id)
                                                                                selected
                                                                        @endif>
                                                                        {{ $customer_group->eng_description->name }}
                                                                        </option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>

                                                                @error('customer_group_id')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-5">
                                                            <div class="fv-row">
                                                                <label class="form-label required" for="store_id"
                                                                    class="form-label">Store</label>
                                                                <select
                                                                    class="form-select form-select-solid @error('store_id') is-invalid @enderror"
                                                                    aria-label="store_id" id="store_id" name="store_id">
                                                                    <option value="" @if ($type == 'add')selected @endif>Please Select
                                                                        Store</option>
                                                                    @if (count($stores) > 0)
                                                                        @foreach ($stores as $store)
                                                                            <option value="{{ $store->id }}"
                                                                                @if ($type == 'edit' && $modal['store_id'] == $store->id)
                                                                                selected
                                                                        @endif>
                                                                        {{ $store->name }}
                                                                        </option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>

                                                                @error('store_id')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Input group-->

                                                    <!--begin::Input group-->
                                                    <div class="row">
                                                        <label class="form-label required col-md-3 col-form-label"
                                                            for="customer_id" data-bs-toggle="tooltip"
                                                            data-bs-custom-class="tooltip-dark" data-bs-placement="top"
                                                            title="Customer needs to selected before proceeding to other tabs.">Customer
                                                            <i class="fas fa-info-circle"></i></label>
                                                        <div class="col-md-9 text-end">
                                                            <button type="button" class="btn btn-light-primary btn-sm"
                                                                data-bs-toggle="modal" data-bs-target="#addCustomer">
                                                                <i class="fas fa-plus-circle"></i> Add Customer
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 mb-5">
                                                            <div class="fv-row">
                                                                <select
                                                                    class="form-select form-select-solid customer @error('customer_id') is-invalid @enderror"
                                                                    id="customer_id" name="customer_id" required>
                                                                    @if ($type == 'edit' && isset($modal['customer_id']))
                                                                        <option value="{{ $modal['customer_id'] }}"
                                                                            selected>
                                                                            {{ $modal['first_name'] . ' ' . $modal['last_name'] }}
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                                @error('customer_id')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Input group-->
                                                </div>
                                                <!--begin::Step 1-->

                                                <!--begin::Step 2-->
                                                <div class="flex-column" data-kt-stepper-element="content">
                                                    <!--begin::Input group-->
                                                    <div class="fv-row">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table
                                                                        class="table table-sm table-row-bordered table-column-bordered table-row-gray-300 border gs-6 gy-3">
                                                                        <thead>
                                                                            <tr class="fw-bolder fs-6 text-gray-800">
                                                                                <th style="width: 56%;">Product</th>
                                                                                <th style="width: 15%;"
                                                                                    class="text-center">Quantity</th>
                                                                                <th style="width: 12%;"
                                                                                    class="text-end">Unit Price</th>
                                                                                <th style="width: 12%;"
                                                                                    class="text-end">Total</th>
                                                                                <th style="width: 5%;">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="cart-item">
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr class="fs-6 text-gray-800">
                                                                                <td colspan="5"
                                                                                    class="fw-bolder text-center">
                                                                                    Total Unit/Quantity: <span
                                                                                        id="unit-by-quantity"></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fs-6 text-gray-800">
                                                                                <td colspan="3" class="fw-bolder text-end">
                                                                                    Sub-Total:
                                                                                </td>
                                                                                <td class="fw-bolder text-end"
                                                                                    id="sub-total"></td>
                                                                                <td></td>
                                                                            </tr>
                                                                            <tr class="fs-6 text-gray-800">
                                                                                <td colspan="3" class="fw-bolder text-end">
                                                                                    Grand-Total:
                                                                                </td>
                                                                                <td class="fw-bolder text-end"
                                                                                    id="grand-total">

                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <h4>Add Product(s)</h4>
                                                        <hr>
                                                    </div>
                                                    <!--end::Input group-->

                                                    <div class="fv-row">
                                                        <div class="row">
                                                            <input type="hidden" form="add-to-cart" name="index" id="index"
                                                                value="0">
                                                            <div class="mb-5 col-md-6">
                                                                <div class="row mb-2">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label" for="">Choose Product
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-md-6 text-end">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-primary"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#add-admin-product">Create
                                                                            Product</button>
                                                                    </div>
                                                                </div>
                                                                <select
                                                                    class="form-select from-select-solid product @error('product') is-invalid @enderror"
                                                                    name="product" id="product" form="add-to-cart"
                                                                    style="width: 100%;">
                                                                </select>
                                                                @error('product')
                                                                    <div class="invalid-feedback">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-5 col-md-6">
                                                                <label class="form-label mb-6"
                                                                    for="product_qty">Quantity</label>
                                                                <input type="number" min="1" value="1"
                                                                    class="form-control form-control-solid @error('product_qty') is-invalid @enderror"
                                                                    name="product_qty" id="product_qty" form="add-to-cart">
                                                                @error('product_qty')
                                                                    <div class="invalid-feedback">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div id="product-options"></div>
                                                    </div>

                                                    <div class="fv-row">
                                                        <div class="row">
                                                            <div class="col-md-12 text-end">
                                                                <button class="btn btn-light-primary" type="button"
                                                                    onclick="submitFrom()">
                                                                    <input type="hidden" id="option_type_order">
                                                                    Add to Cart
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--begin::Step 2-->

                                                <!--begin::Step 3-->
                                                <div class="flex-column" data-kt-stepper-element="content">
                                                    <!--begin::Input group-->
                                                    <div class="row d-none" id="shipping-address-div">
                                                        <div class="mb-5 col-md-12">
                                                            <div class="fv-row">
                                                                <label class="form-label" for="shipping_address">Choose
                                                                    address</label>
                                                                <select
                                                                    class="form-select form-select-solid @error('shipping_address') is-invalid @enderror"
                                                                    name="shipping_address" id="shipping_address">
                                                                    <option value="0">-- None --</option>
                                                                </select>

                                                                @error('shipping_address')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="mb-5 col-md-6">
                                                            <div class="fv-row">
                                                                <label class="form-label required"
                                                                    for="shipping_first_name">First Name</label>
                                                                <input type="text"
                                                                    class="form-control form-control-solid @error('shipping_first_name') is-invalid @enderror"
                                                                    id="shipping_first_name" name="shipping_first_name"
                                                                    placeholder="First Name" required @if ($type == 'create')
                                                            value="{{ old('shipping_first_name') }}" @else
                                                                value="{{ old('shipping_first_name') ?: $modal['shipping_first_name'] }}"
                                                                @endif>

                                                                @error('shipping_first_name')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="mb-5 col-md-6">
                                                            <div class="fv-row">
                                                                <label class="form-label required"
                                                                    for="shipping_last_name">Last Name</label>
                                                                <input type="text"
                                                                    class="form-control form-control-solid @error('shipping_last_name') is-invalid @enderror"
                                                                    id="shipping_last_name" name="shipping_last_name"
                                                                    placeholder="Last Name" required @if ($type == 'create')
                                                            value="{{ old('shipping_last_name') }}" @else
                                                                value="{{ old('shipping_last_name') ?: $modal['shipping_last_name'] }}"
                                                                @endif>

                                                                @error('shipping_last_name')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="mb-5 col-md-6">
                                                            <div class="fv-row">
                                                                <label class="form-label"
                                                                    for="shipping_telephone">Mobile (xxx-xxx-xxxx) </label>
                                                                <input type="tel" placeholder="xxx-xxx-xxxx"
                                                                    pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                                                    class="form-control form-control-solid @error('shipping_telephone') is-invalid @enderror"
                                                                    name="shipping_telephone" id="shipping_telephone"
                                                                    @if ($type == 'create')
                                                            value="{{ old('shipping_telephone') }}" @else
                                                                value="{{ old('shipping_telephone') ?: $modal['shipping_telephone'] }}"
                                                                @endif>
                                                                @error('shipping_telephone')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="mb-5 col-md-12">
                                                            <div class="fv-row">
                                                                <input type="hidden" name="shipping_lat" id="shipping_lat"
                                                                    @if ($type == 'create') value="{{ old('shipping_lat') }}" @endif @if ($type == 'edit') value="{{ old('shipping_lat') ?: $modal['shipping_lat'] }}" @endif>
                                                                <input type="hidden" name="shipping_lng" id="shipping_lng"
                                                                    @if ($type == 'create') value="{{ old('shipping_lng') }}" @endif @if ($type == 'edit') value="{{ old('shipping_lng') ?: $modal['shipping_lng'] }}" @endif>
                                                                <label class="form-label required"
                                                                    for="shipping_address_1">Address 1 </label>
                                                                <input type="text" id="shipping_address_1"
                                                                    class="form-control form-control-solid @error('shipping_address_1') is-invalid @enderror"
                                                                    name="shipping_address_1" placeholder="Address 1"
                                                                    @if ($type == 'edit' && isset($modal['shipping_address_1']))
                                                                value="{{ $modal['shipping_address_1'] }}" @endif>
                                                                @error('shipping_address_1')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="mb-5 col-md-12">
                                                            <div class="fv-row">
                                                                <label class="form-label"
                                                                    for="shipping_address_2">Address 2 </label>
                                                                <input type="text" id="shipping_address_2"
                                                                    class="form-control form-control-solid @error('shipping_address_2') is-invalid @enderror"
                                                                    name="shipping_address_2" placeholder="Address 2"
                                                                    @if ($type == 'edit' && isset($modal['shipping_address_2']))
                                                                value="{{ $modal['shipping_address_2'] }}" @endif>
                                                                @error('shipping_address_2')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="mb-5 col-md-6">
                                                            <div class="fv-row">
                                                                <label class="form-label required" for="shipping_city">City
                                                                </label>
                                                                <input type="text"
                                                                    class="form-control form-control-solid @error('shipping_city') is-invalid @enderror"
                                                                    name="shipping_city" id="shipping_city"
                                                                    placeholder="City" @if ($type == 'create')
                                                            value="{{ old('shipping_city') }}" @else
                                                                value="{{ old('shipping_city') ?: $modal['shipping_city'] }}"
                                                                @endif>
                                                                @error('shipping_city')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="mb-5 col-md-6">
                                                            <div class="fv-row">
                                                                <label class="form-label required"
                                                                    for="shipping_postcode">Postcode </label>
                                                                <input type="text"
                                                                    class="form-control form-control-solid @error('shipping_postcode') is-invalid @enderror"
                                                                    name="shipping_postcode" id="shipping_postcode"
                                                                    placeholder="Postcode" @if ($type == 'create')
                                                            value="{{ old('shipping_postcode') }}" @else
                                                                value="{{ old('shipping_postcode') ?: $modal['shipping_postcode'] }}"
                                                                @endif>
                                                                @error('shipping_postcode')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="mb-5 col-md-6">
                                                            <div class="fv-row">
                                                                <label class="form-label required"
                                                                    for="shipping_country_id">Country </label>
                                                                <input type="hidden" name="shipping_country"
                                                                    id="shipping_country" @if ($type == 'edit' && isset($modal['shipping_country']))
                                                                value="{{ $modal['shipping_country'] }}" @endif>
                                                                <select
                                                                    class="form-select form-select-solid country @error('shipping_country_id') is-invalid @enderror"
                                                                    name="shipping_country_id" id="shipping_country_id"
                                                                    style="width: 100%;" data-zone="0"
                                                                    onchange="getZones(this, '{{ route('zones.getZones') }}')">
                                                                    <option value="" disabled selected>-- Select Country --
                                                                    </option>
                                                                    @if (count($countries) > 0)
                                                                        @foreach ($countries as $country)
                                                                            <option value="{{ $country->id }}"
                                                                                @if ($type == 'create' && $country->name == 'Canada')
                                                                                selected
                                                                                @endif @if ($type == 'edit' && $modal['shipping_country_id'] == $country->id)
                                                                                    selected
                                                                                @endif
                                                                                >{!! $country->name !!}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                @error('shipping_country_id')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="mb-5 col-md-6">
                                                            <div class="fv-row">
                                                                <label class="form-label required"
                                                                    for="shipping_zone_id">Region / State </label>
                                                                <input type="hidden" name="shipping_zone" id="shipping_zone"
                                                                    @if ($type == 'edit' && isset($modal['shipping_zone']))
                                                                value="{{ $modal['shipping_zone'] }}" @endif>
                                                                <select
                                                                    class="form-select form-select-solid zone @error('shipping_zone_id') is-invalid @enderror"
                                                                    name="shipping_zone_id" id="shipping_zone_id"
                                                                    style="width: 100%;">
                                                                    <option value="" disabled selected>-- Select Zone --
                                                                    </option>
                                                                    @if (count($zones) > 0)
                                                                        @foreach ($zones as $zone)
                                                                            <option value="{{ $zone->id }}"
                                                                                @if ($type == 'create' && $zone->name == 'Ontario')
                                                                                selected
                                                                        @endif
                                                                        @if ($type == 'edit' && $modal['shipping_zone_id'] == $zone->id)
                                                                            selected
                                                                        @endif>{!! $zone->name !!}
                                                                        </option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                                @error('shipping_zone_id')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Input group-->
                                                </div>
                                                <!--begin::Step 3-->

                                                <!--begin::Step 4-->
                                                <div class="flex-column" data-kt-stepper-element="content">
                                                    <!--begin::Input group-->
                                                    <div class="row d-none">
                                                        <div class="col-md-3 mb-5">
                                                            <div class="fv-row">
                                                                <label class="form-label">Order Total </label>
                                                                <input class="form-control form-control-solid" type="text"
                                                                    id="uncal-order-total" readonly>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 mb-5">
                                                            <div class="fv-row">
                                                                <label class="form-label required">Shipping Method </label>
                                                                <input type="hidden" id="shipping_method"
                                                                    name="shipping_method" @if ($type == 'edit' && isset($modal['shipping_method']))
                                                                value="{{ $modal['shipping_method'] }}" @endif>
                                                                <input type="hidden" id="shipping_method_cost"
                                                                    name="shipping_method_cost" @if ($type == 'edit' && isset($modal['shipping_method_cost']))
                                                                value="{{ $modal['shipping_method_cost'] }}"
                                                                @endif>
                                                                <input type="hidden" id="shipping_method_code"
                                                                    name="shipping_method_code" @if ($type == 'edit' && isset($modal['shipping_method_code']))
                                                                value="{{ $modal['shipping_method_code'] }}"
                                                                @endif>

                                                                <div class="row">
                                                                    @foreach ($shipping_methods as $k => $shipping_method)
                                                                        <div class="col-md-12">
                                                                            <div
                                                                                class="form-check form-check-custom form-check-solid check_payment_method">
                                                                                <input class="form-check-input"
                                                                                    type="radio" name="shipping_method_id"
                                                                                    value="{{ $shipping_method->id }}"
                                                                                    id="{{ $shipping_method->eng_description->name }}"
                                                                                    data-cost="{{ $shipping_method->cost }}"
                                                                                    data-code="{{ $shipping_method->code }}"
                                                                                    data-name="{{ $shipping_method->eng_description->name }}"
                                                                                {{-- @if ($type == 'create')
                                                                            {{ old('shipping_method_id') == $shipping_method->id ? "checked" : "" }} @else @endif @if ($type == 'edit' && $modal['shipping_method_id'] == $shipping_method->id) checked @endif @if ($k == 0)
                                                                        required
                                                                        @endif --}}
                                                                                    onclick="setShippingMethod(this)" />
                                                                                <label class="form-check-label"
                                                                                    style="width: 100%;"
                                                                                    for="{{ $shipping_method->eng_description->name }}">
                                                                                    {{ $shipping_method->eng_description->name }}
                                                                                    +${{ $shipping_method->cost }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Input group-->

                                                    <!--begin::Input group-->
                                                    <div class="row">
                                                        <div class="col-md-12 mb-5">
                                                            <div class="fv-row">
                                                                <label class="form-label required">Payment Method </label>
                                                                <input type="hidden" name="payment_method"
                                                                    id="payment_method" @if ($type == 'edit' && isset($modal['payment_method']))
                                                                value="{{ $modal['payment_method'] }}" @endif>
                                                                <input type="hidden" name="payment_method_code"
                                                                    id="payment_method_code" @if ($type == 'edit' && isset($modal['payment_method_code']))
                                                                value="{{ $modal['payment_method_code'] }}" @endif>
                                                                <div class="row">
                                                                    @foreach ($payment_methods as $k => $payment_method)
                                                                        <div class="col-md-3">
                                                                            <div
                                                                                class="form-check form-check-custom form-check-solid">
                                                                                <input
                                                                                    class="form-check-input  check-payment-method"
                                                                                    type="radio" @if ($type == 'create')
                                                                                {{ old('payment_method_id') == $payment_method->id ? 'checked' : '' }}
                                                                                @endif @if ($type == 'edit' && $modal['payment_method_id'] == $payment_method->id) checked @endif @if ($k == 0)
                                                                                    required
                                                                                @endif
                                                                                name="payment_method_id"
                                                                                id="{{ $payment_method->eng_description->name }}"
                                                                                data-code="{{ $payment_method->code }}"
                                                                                value="{{ $payment_method->id }}"
                                                                                onclick="showHideAuthorize(this)" />
                                                                                <label class="form-check-label"
                                                                                    for="{{ $payment_method->eng_description->name }}"
                                                                                    @if ($payment_method->code == 'authorize')
                                                                                    title="Authorize.net"
                                                                    @endif>
                                                                    @if ($payment_method->code == 'authorize')
                                                                        <i class="fab fa-cc-visa fa-3x"></i>
                                                                        <i class="fab fa-cc-mastercard fa-3x"></i>
                                                                        <i class="fab fa-cc-amex fa-3x"></i>
                                                                    @else
                                                                        {{ $payment_method->eng_description->name }}
                                                                    @endif
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div @if ($type == 'edit' && $modal['payment_method_code'] == 'authorize') class="fv-row" @else class="fv-row d-none" @endif id="authorize-div">
                                                <div class="row">
                                                    <div class="col-md-6 mb-5">
                                                        <div class="fv-row">
                                                            <label class="form-label required">Credit Card Number</label>
                                                            <input type="number" min="0"
                                                                class="form-control form-control-solid @error('card_number') is-invalid @enderror"
                                                                name="card_number" id="card_number"
                                                                placeholder="Credit Card Number"
                                                                value="{{ old('card_number') }}">
                                                            @error('card_number')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-5">
                                                        <div class="fv-row">
                                                            <label class="form-label required">Card CVV</label>
                                                            <input type="number" min="0"
                                                                class="form-control form-control-solid @error('card_cvv') is-invalid @enderror"
                                                                name="card_cvv" id="card_cvv" placeholder="Card CVV"
                                                                value="{{ old('card_cvv') }}">
                                                            @error('card_cvv')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-5">
                                                        <div class="fv-row">
                                                            <label class="form-label required">Card Expiration
                                                                Month</label>
                                                            <select
                                                                class="form-select form-select-solid @error('card_exp_month') is-invalid @enderror"
                                                                name="card_exp_month" id="card_exp_month">
                                                            </select>
                                                            @error('card_exp_month')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-5">
                                                        <div class="fv-row">
                                                            <label class="form-label required">Card Expiration Year</label>
                                                            <select
                                                                class="form-select form-select-solid @error('card_exp_year') is-invalid @enderror"
                                                                name="card_exp_year" id="card_exp_year">
                                                            </select>
                                                            @error('card_exp_year')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Input group-->
                                    </div>
                                    <!--begin::Step 4-->

                                    <!--begin::Step 5-->
                                    <div class="flex-column" data-kt-stepper-element="content">
                                        <!--begin::Input group-->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-sm table-row-bordered table-column-bordered table-row-gray-300 border gs-6 gy-3">
                                                        <thead>
                                                            <tr class="fw-bolder fs-6 text-gray-800">
                                                                <th style="width: 56%;">Product</th>
                                                                <th style="width: 15%;" class="text-center">Quantity
                                                                </th>
                                                                <th style="width: 12%;" class="text-end">Unit Price
                                                                </th>
                                                                <th style="width: 17%;" class="text-end">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="cart-total">
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="fs-6 text-gray-800">
                                                                <td colspan="5" class="fw-bolder text-center">
                                                                    Total Unit/Quantity: <span
                                                                        id="order-unit-by-quantity"></span>
                                                                </td>
                                                            </tr>
                                                            <tr class="fs-6 text-gray-800">
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <label class="form-label fw-bolder">Additional Charges:
                                                                    </label>
                                                                    <div
                                                                        class="form-check form-check-solid form-check-inline ms-2">
                                                                        <input class="form-check-input" type="radio"
                                                                            id="extra-charges-yes" name="extra-charges"
                                                                            value="Y" onclick="handleExtraCharges(this)"
                                                                            @if ($type == 'edit' && isset($modal['extra_charge_amount']) && $modal['extra_charge_amount'] != '0') checked @endif />
                                                                        <label class="form-check-label"
                                                                            for="extra-charges-yes">
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                    <div
                                                                        class="form-check form-check-solid form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                            id="extra-charges-no" name="extra-charges"
                                                                            value="N" onclick="handleExtraCharges(this)"
                                                                            @if ($type == 'create') checked @endif @if ($type == 'edit' && isset($modal['extra_charge_amount']) && $modal['extra_charge_amount'] == '0') checked @endif />
                                                                        <label class="form-check-label"
                                                                            for="extra-charges-no">
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <input type="number" step="0.01"
                                                                        class="form-control form-control-sm form-control-solid"
                                                                        @if ($type == 'create') value="0"
                                                                    disabled @endif @if ($type == 'edit')
                                                                        @if (isset($modal['extra_charge_amount']) && $modal['extra_charge_amount'] == '0')
                                                                            disabled
                                                                            value="{{ $modal['extra_charge_amount'] }}"
                                                                        @else
                                                                            value="{{ $modal['extra_charge_amount'] }}"
                                                                        @endif
                                                                    @endif
                                                                    id="input-extra-charge-amount"
                                                                    name="extra_charge_amount" min="0">
                                                                </td>
                                                            </tr>
                                                            <tr class="fs-6 text-gray-800">
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <label class="form-label fw-bolder"
                                                                        for="customer_notes">Customer Notes: </label>
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <textarea id="customer_notes"
                                                                        class="form-control form-control-sm form-control-solid"
                                                                        name="customer_notes"
                                                                        rows="3">@if ($type == 'create'){{ old('customer_notes') }}@else{{ $modal['customer_notes'] }}@endif</textarea>
                                                                </td>
                                                            </tr>
                                                            <tr class="fs-6 text-gray-800">
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    Shipping Method <span
                                                                        id="order-shipping-method-text"></span>:
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end"
                                                                    id="order-shipping-method"></td>
                                                            </tr>
                                                            <tr class="fs-6 text-gray-800">
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <label class="form-label fw-bolder">Apply Discount:
                                                                    </label>
                                                                    <div
                                                                        class="form-check form-check-solid form-check-inline ms-2">
                                                                        <input class="form-check-input" type="radio"
                                                                            id="apply-discount-yes" name="apply-discount"
                                                                            value="Y" onclick="handleDiscount(this)"
                                                                            @if ($type == 'edit' && isset($modal['discount_amount']) && $modal['discount_amount'] != '0') checked @endif />
                                                                        <label class="form-check-label"
                                                                            for="apply-discount-yes">
                                                                            Yes
                                                                        </label>
                                                                    </div>
                                                                    <div
                                                                        class="form-check form-check-solid form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                            id="apply-discount-no" name="apply-discount"
                                                                            value="N" onclick="handleDiscount(this)"
                                                                            @if ($type == 'create') checked @endif @if ($type == 'edit' && isset($modal['discount_amount']) && $modal['discount_amount'] == '0') checked @endif />
                                                                        <label class="form-check-label"
                                                                            for="apply-discount-no">
                                                                            No
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <input type="number"
                                                                        class="form-control form-control-sm form-control-solid"
                                                                        @if ($type == 'create') value="0"
                                                                    disabled
                                                                    @endif @if ($type == 'edit')
                                                                        @if (isset($modal['discount_amount']) && $modal['discount_amount'] == '0')
                                                                            disabled
                                                                        value="{{ $modal['discount_amount'] }}" @else
                                                                            value="{{ $modal['discount_amount'] }}"
                                                                        @endif
                                                                    @endif
                                                                    id="input-discount-amount" name="discount_amount"
                                                                    min="0" max="{{getUserDiscount()}}" onkeyup="checkUserDiscount(this,'{{getUserDiscount()}}')" >
                                                                </td>
                                                            </tr>
                                                            <tr class="fs-6 text-gray-800">
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    Sub-Total:
                                                                    <input type="hidden" id="input-sub-total"
                                                                        name="sub_total" value="">
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end"
                                                                    id="order-sub-total"></td>
                                                            </tr>
                                                            <tr class="fs-6 text-gray-800">
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    Payment Method:
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end"
                                                                    id="order-payment-method"></td>
                                                            </tr>
                                                            <tr @if ($type == 'create') class="fs-6 text-gray-800 coc-div" @endif @if ($type == 'edit' && $modal['payment_method_code'] != 'COC')
                                                            class="fs-6 text-gray-800 d-none coc-div" @else
                                                                class="fs-6 text-gray-800 coc-div" @endif>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    Payment Type:
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <select
                                                                        class="form-select form-select-solid @error('payment_type') is-invalid @enderror"
                                                                        aria-label="payment_type" id="payment_type"
                                                                        name="payment_type"
                                                                        onchange="hideShowAmountSection(this)">
                                                                        <option value="full" @if ($type == 'create' && old('paid_amount') == 'full') selected @endif @if ($type == 'edit' && $modal['payment_type'] == 'full') selected @endif>Full
                                                                            Payment</option>
                                                                        <option value="partial" @if ($type == 'create' && old('paid_amount') == 'partial') selected @endif @if ($type == 'edit' && $modal['payment_type'] == 'partial') selected @endif>
                                                                            Partial
                                                                            Payment
                                                                        </option>
                                                                    </select>
                                                                    @error('payment_type')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                            <tr @if ($type == 'create') class="fs-6 text-gray-800 coc-div" @endif @if ($type == 'edit' && $modal['payment_method_code'] != 'COC')
                                                            class="fs-6 text-gray-800 d-none coc-div" @else
                                                                class="fs-6 text-gray-800 coc-div" @endif>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    Mode of Payment:
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <select
                                                                        class="form-select form-select-solid @error('payment_mode') is-invalid @enderror"
                                                                        aria-label="payment_mode" id="payment_mode"
                                                                        name="payment_mode"
                                                                        onchange="hideShowBillSection()">
                                                                        <option value="" selected disabled>--Select payment mode--</option>
                                                                        <option @if ($type == 'create' && old('payment_mode') == 'online transfer') selected @endif @if ($type == 'edit' && $modal['payment_mode'] == 'online transfer') selected @endif
                                                                            value="online transfer">
                                                                            Online Transfer
                                                                        </option>
                                                                        <option value="cash" @if ($type == 'create' && old('payment_mode') == 'cash') selected @endif @if ($type == 'edit' && $modal['payment_mode'] == 'cash') selected @endif>
                                                                            Cash
                                                                        </option>
                                                                        <option value="card" @if ($type == 'create' && old('payment_mode') == 'card') selected @endif @if ($type == 'edit' && $modal['payment_mode'] == 'card') selected @endif>
                                                                            Card (Credit/Debit)
                                                                        </option>
                                                                    </select>
                                                                    @error('payment_mode')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                            <tr @if ($type == 'create') class="fs-6 text-gray-800 bills-div d-none" @endif @if ($type == 'edit' && $modal['payment_mode'] != 'cash')
                                                            class="fs-6 text-gray-800 d-none bills-div" @else
                                                                class="fs-6 text-gray-800 bills-div" @endif>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    Number of Bills:
                                                                </td>
                                                                <td colspan="2">
                                                                    <table class="table table-borderless m-0 p-0">
                                                                        <tr>
                                                                            <td>
                                                                                <label for="hundred"
                                                                                    class="form-label">100's</label>
                                                                                <input
                                                                                    class="form-control form-control-solid form-control-sm"
                                                                                    type="number" placeholder="100"
                                                                                    id="hundred" name="bills[hundred]"
                                                                                    @if ($type == 'create') value="{{ old('hundred') }}" @endif @if ($type == 'edit')
                                                                                value="{{ old('hundred') ?: (isset($order_bills['hundred']) ? $order_bills['hundred'] : 0) }}"
                                                                                @endif>
                                                                            </td>
                                                                            <td>
                                                                                <label for="fifty"
                                                                                    class="form-label">50's</label>
                                                                                <input
                                                                                    class="form-control form-control-solid form-control-sm"
                                                                                    type="number" placeholder="50"
                                                                                    id="fifty" name="bills[fifty]"
                                                                                    @if ($type == 'create') value="{{ old('fifty') }}" @endif @if ($type == 'edit')
                                                                                value="{{ old('fifty') ?: (isset($order_bills['fifty']) ? $order_bills['fifty'] : 0) }}"
                                                                                @endif>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <label for="twenty"
                                                                                    class="form-label">20's</label>
                                                                                <input
                                                                                    class="form-control form-control-solid form-control-sm"
                                                                                    type="number" placeholder="20"
                                                                                    id="twenty" name="bills[twenty]"
                                                                                    @if ($type == 'create') value="{{ old('twenty') }}" @endif @if ($type == 'edit')
                                                                                value="{{ old('twenty') ?: (isset($order_bills['twenty']) ? $order_bills['twenty'] : 0) }}"
                                                                                @endif>
                                                                            </td>
                                                                            <td>
                                                                                <label for="ten"
                                                                                    class="form-label">10's</label>
                                                                                <input
                                                                                    class="form-control form-control-solid form-control-sm"
                                                                                    type="number" placeholder="10" id="ten"
                                                                                    name="bills[ten]"
                                                                                    @if ($type == 'create') value="{{ old('ten') }}" @endif @if ($type == 'edit')
                                                                                value="{{ old('ten') ?: (isset($order_bills['ten']) ? $order_bills['ten'] : 0) }}"
                                                                                @endif>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <label for="five"
                                                                                    class="form-label">5's</label>
                                                                                <input
                                                                                    class="form-control form-control-solid form-control-sm"
                                                                                    type="number" placeholder="5" id="five"
                                                                                    name="bills[five]"
                                                                                    @if ($type == 'create') value="{{ old('five') }}" @endif @if ($type == 'edit')
                                                                                value="{{ old('five') ?: (isset($order_bills['five']) ? $order_bills['five'] : 0) }}"
                                                                                @endif>
                                                                            </td>
                                                                            <td>
                                                                                <label for="two"
                                                                                    class="form-label">2's</label>
                                                                                <input
                                                                                    class="form-control form-control-solid form-control-sm"
                                                                                    type="number" placeholder="2" id="two"
                                                                                    name="bills[two]"
                                                                                    @if ($type == 'create') value="{{ old('two') }}" @endif @if ($type == 'edit')
                                                                                value="{{ old('two') ?: (isset($order_bills['two']) ? $order_bills['two'] : 0) }}"
                                                                                @endif>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <label for="one"
                                                                                    class="form-label">1's</label>
                                                                                <input
                                                                                    class="form-control form-control-solid form-control-sm"
                                                                                    type="number" placeholder="1" id="one"
                                                                                    name="bills[one]"
                                                                                    @if ($type == 'create') value="{{ old('one') }}" @endif @if ($type == 'edit')
                                                                                value="{{ old('one') ?: (isset($order_bills['one']) ? $order_bills['one'] : 0) }}"
                                                                                @endif>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr @if ($type == 'create') class="fs-6 text-gray-800 d-none partial-div" @endif @if ($type == 'edit' && $modal['payment_type'] != 'partial')
                                                            class="fs-6 text-gray-800 d-none partial-div" @else
                                                                class="fs-6 text-gray-800 partial-div"
                                                                @endif>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    Paid Amount:
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <input
                                                                        class="form-control form-control-solid form-control-sm @error('paid_amount') is-invalid @enderror"
                                                                        type="number" min="1" step="0.01" name="paid_amount"
                                                                        id="paid_amount" placeholder="Paid Amount"
                                                                        @if ($type == 'create')
                                                                    value="{{ old('paid_amount') ?: 0 }}" @endif
                                                                    @if ($type == 'edit' && isset($modal['paid_amount']))
                                                                        value="{{ $modal['paid_amount'] }}"
                                                                    @endif>
                                                                    @error('paid_amount')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                            <tr @if ($type == 'create') class="fs-6 text-gray-800 d-none partial-div" @endif @if ($type == 'edit' && $modal['payment_type'] != 'partial')
                                                            class="fs-6 text-gray-800 d-none partial-div" @else
                                                                class="fs-6 text-gray-800 partial-div"
                                                                @endif>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    Remaining Amount:
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <input
                                                                        class="form-control form-control-solid form-control-sm @error('remaining_amount') is-invalid @enderror"
                                                                        type="text" readonly id="remaining_amount"
                                                                        name="remaining_amount" @if ($type == 'create')
                                                                    value="{{ old('remaining_amount') ?: 0 }}"
                                                                    @endif @if ($type == 'edit' && isset($modal['remaining_amount'])) value="{{ $modal['remaining_amount'] }}" @endif>
                                                                    @error('remaining_amount')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                            <tr class="fs-6 text-gray-800">
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <input type="hidden" id="temp_grand_total">
                                                                    <input type="hidden" id="input-grand-total"
                                                                        name="grand_total" @if ($type == 'edit' && isset($modal['total']))
                                                                    value="{{ $modal['total'] }}" @endif>
                                                                    Grand-Total:
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end"
                                                                    id="order-grand-total"></td>
                                                            </tr>
                                                            <tr
                                                                class="fs-6 text-gray-800 @if (!Auth::guard('web')->user()->hasRole('Super Admin') &&
    !Auth::guard('web')->user()->hasRole('Dispatch Manager') &&
    !Auth::guard('web')->user()->hasRole('Office Admin') &&
    $type == 'edit') d-none @endif">
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <label class="form-label fw-bolder"
                                                                        for="delivery_date">Delivery Date</label>
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    <div class="input-group">
                                                                        <input
                                                                            class="form-control form-control-solid form-control-sm"
                                                                            name="delivery_date" autocomplete="off"
                                                                            id="delivery_date" placeholder="Pick date"
                                                                            @if ($type == 'edit') value="{{ $modal['delivery_date'] }}" @endif>
                                                                        <span
                                                                            class="input-group-text delivery-date-icon"><i
                                                                                class="fas fa-calendar-alt"></i></span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="fs-6 text-gray-800">
                                                                <td colspan="2" class="fw-bolder text-end">
                                                                    
                                                                    Custom Order:
                                                                </td>
                                                                <td colspan="2" class="fw-bolder text-start">
                                                                    <div
                                                                    class="form-check form-check-solid form-check-inline ms-2">
                                                                    <input class="form-check-input" type="radio"
                                                                        id="custom-order-yes" name="custom_order"
                                                                        value="Yes" 
                                                                        @if ($type == 'edit' && isset($modal['custom_order']) && $modal['custom_order'] == 'Yes') checked @endif />
                                                                    <label class="form-check-label"
                                                                        for="custom-order-yes">
                                                                        Yes
                                                                    </label>
                                                                </div>
                                                                <div
                                                                    class="form-check form-check-solid form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                        id="custom-order-no" name="custom_order"
                                                                        value="No" 
                                                                        @if ($type == 'create') checked @endif @if ($type == 'edit' && isset($modal['custom_order']) && $modal['custom_order'] == 'No') checked @endif />
                                                                    <label class="form-check-label"
                                                                        for="custom-order-no">
                                                                        No
                                                                    </label>
                                                                </div>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="fv-row">
                                                <div class="mb-5 col-md-12">
                                                    <label class="form-label" for="comment">Comment </label>
                                                    <input
                                                        class="form-control form-control-solid @error('comment') is-invalid @enderror"
                                                        name="comment" id="comment" @if ($type == 'create')
                                                    value="{{ old('comment') }}" @endif
                                                    @if ($type == 'edit') value="{{ $modal['comment'] }}" @endif />

                                                    @error('comment')
                                                        <div class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--begin::Step 5-->
                                </div>
                                <!--end::Group-->

                                <!--begin::Actions-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="me-2">
                                        <button type="button" class="btn btn-light-dark" data-kt-stepper-action="previous">
                                            Back
                                        </button>
                                    </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Wrapper-->
                                    <div>
                                        <a href="{{ route('orders.index') }}" class="btn btn-light-dark me-2">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary" data-kt-stepper-action="submit">
                                            <span class="indicator-label">
                                                Submit
                                            </span>
                                            <span class="indicator-progress">
                                                Please wait... <span
                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                        <button type="button" class="btn btn-light-primary" data-kt-stepper-action="next">
                                            Next
                                        </button>
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Actions-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end::Stepper-->
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    </div>
    <!--end::Container-->

    <div class="modal fade" tabindex="-1" id="addCustomer">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Add Customer
                        <div class="spinner-border spinner-border-sm text-dark ms-5 d-none custom-loader" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </h2>

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
                    <form method="POST" action="{{ route('customers.ajaxSubmit') }}"
                        onsubmit="return ajaxCustomerCreate(this, event.preventDefault());">
                        @csrf
                        <h2 class="mb-5">Customer Detail</h2>
                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label for="address_customer_group_id" class="form-label required">Customer Group</label>
                                <select
                                    class="form-select form-select-solid @error('customer_group_id') is-invalid @enderror"
                                    aria-label="customer_group_id" id="address_customer_group_id" name="customer_group_id"
                                    required>
                                    @if (count($customer_groups) > 0)
                                        @foreach ($customer_groups as $customer_group)
                                            <option value="{{ $customer_group->id }}">
                                                {{ $customer_group->eng_description->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>

                                @error('customer_group_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="first_name">First Name</label>
                                <input type="text"
                                    class="form-control form-control-solid @error('first_name') is-invalid @enderror"
                                    id="first_name" name="first_name" required onkeyup="fillCustomerData(this)">

                                @error('first_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="last_name">Last Name</label>
                                <input type="text"
                                    class="form-control form-control-solid @error('last_name') is-invalid @enderror"
                                    id="last_name" name="last_name" required onkeyup="fillCustomerData(this)">

                                @error('last_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="email">Email</label>
                                <input type="email"
                                    class="form-control form-control-solid @error('email') is-invalid @enderror" id="email"
                                    name="email" required>

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="telephone">Mobile (xxx-xxx-xxxx)</label>
                                <input type="tel"
                                    class="form-control form-control-solid @error('telephone') is-invalid @enderror"
                                    id="telephone" name="telephone" required placeholder="xxx-xxx-xxxx"
                                    pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" onblur="fillCustomerData(this)">

                                @error('telephone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <h2 class="mb-5">Customer Address</h2>
                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="address_first_name">First Name</label>
                                <input type="text" class="form-control form-control-solid" id="address_first_name"
                                    name="address[1][first_name]" required>
                            </div>
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="address_last_name">Last Name</label>
                                <input type="text" class="form-control form-control-solid" id="address_last_name"
                                    name="address[1][last_name]" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <div class="fv-row">
                                    <label class="form-label" for="address[1][telephone]">Mobile (xxx-xxx-xxxx)</label>
                                    <input type="tel" placeholder="xxx-xxx-xxxx" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                        class="form-control form-control-solid" name="address[1][telephone]"
                                        id="address_telephone">

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <input type="hidden" name="address[1][lat]" id="lat">
                            <input type="hidden" name="address[1][lng]" id="lng">
                            <div class="mb-5 col-md-12">
                                <label for="address_1" class="form-label required">Address 1 </label>
                                <input type="text" class="form-control form-control-solid" name="address[1][address_1]"
                                    id="address_1" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-5 col-md-12">
                                <label for="address_2" class="form-label">Address 2 </label>
                                <input type="text" class="form-control form-control-solid" name="address[1][address_2]"
                                    id="address_2">
                            </div>
                        </div>

                        <div class="row">
                            {{-- <div class="mb-5 col-md-6">
                                <label class="form-label" for="company">Company</label>
                                <input type="text" class="form-control form-control-solid" id="company" name="address[1][company]">
                            </div> --}}
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="city">City</label>
                                <input type="text" class="form-control form-control-solid" id="city"
                                    name="address[1][city]" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label required">Country</label>
                                <select class="form-select form-select-solid country" name="address[1][country_id]"
                                    id="country_id" data-id="modal-country"
                                    onchange="getZones(this, '{{ route('zones.getZones') }}')" required
                                    style="width: 100%;">
                                    <option value="" disabled selected>-- Select Country --</option>
                                    @if (count($countries) > 0)
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" @if ($country->name == 'Canada')
                                                selected
                                        @endif>{!! $country->name !!}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-5 col-md-6">
                                <label class="form-label required">Region / State</label>
                                <select class="form-select form-select-solid zone" name="address[1][zone_id]" id="zone_id"
                                    data-id="modal-zone" required style="width: 100%;">
                                    <option value="" disabled selected>-- Select State --</option>
                                    @if (count($zones) > 0)
                                        @foreach ($zones as $zone)
                                            <option value="{{ $zone->id }}" @if ($zone->name == 'Ontario')
                                                selected
                                        @endif>{!! $zone->name !!}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                        </div>

                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="postcode">Postcode</label>
                                <input type="text" class="form-control form-control-solid" id="postcode"
                                    name="address[1][postcode]" required>
                            </div>
                            <div class="mb-5 col-md-6">
                                <label class="form-label">Default Address </label>
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" type="radio" checked name="address[1][is_default]"
                                        value="1" />
                                </div>
                            </div>
                        </div>

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

    <div class="modal fade" tabindex="-1" id="add-admin-product">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Create Product for Admin Panel
                        <div class="spinner-border spinner-border-sm text-dark ms-5 d-none custom-loader" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </h2>

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
                    <form method="POST" action="{{ route('products.createProductForAdminPanel') }}"
                        onsubmit="return createProductForAdminPanel(this, event.preventDefault());">
                        @csrf
                        <input type="hidden" id="product_type" name="product_type" value="admin">
                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="name">Name</label>
                                <input type="text" class="form-control form-control-solid" id="name" name="name" required>
                            </div>
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="price">Price</label>
                                <input type="number" step="0.01" min="1" class="form-control form-control-solid" id="price"
                                    name="price" required>
                            </div>

                        </div>
                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label" for="category_id">Category</label>
                                <select class="form-select form-select-solid @error('category_id') is-invalid @enderror"
                                    name="category_id" id="category_id">
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->eng_description->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-5 col-md-6">
                                <label class="form-label" for="manufacturer_id">Manufacturer</label>
                                <select
                                    class="form-select form-select-solid @error('manufacturer_id') is-invalid @enderror"
                                    name="manufacturer_id" id="manufacturer_id">
                                    <option value="" selected disabled>Select Manufacturer</option>
                                    @foreach ($manufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}">
                                            {{ $manufacturer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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

    <input type="hidden" id="customer_search" value="{{ route('customers.search') }}">
    <input type="hidden" id="coupon_search" value="{{ route('coupons.search') }}">
    <input type="hidden" id="voucher_search" value="{{ route('vouchers.search') }}">
    <input type="hidden" id="customers_addresses" value="{{ route('customers.getCustomerAddresses') }}">
    <input type="hidden" id="product_search" value="{{ route('products.search') }}">
    <input type="hidden" id="add_to_cart" value="{{ route('orders.addToCart') }}">
    <input type="hidden" id="validate_purchase_qty" value="{{ route('orders.validatePurchaseQty') }}">
    <input type="hidden" id="clear_cart" value="{{ route('orders.clearCart') }}">
@endsection

@push('page_lvl_js')
    <script src="{{ asset('/') }}custom/orders.js"></script>
    <script src="{{ asset('/') }}places/index.js"></script>
    <script>
        // Elements
        var stepperEl;
        var form;
        // Variables
        var stepperObj;
        var validations = [];
        var fv;

        $(document).ready(function() {
            stepperEl = document.querySelector('#multi_step');
            form = stepperEl.querySelector('#multi_step_form');

            @if ($type == 'edit')
                generateCartForEdit('{{ $id }}');
                setShippingMethod($("input:radio[name=shipping_method_id]:checked"));
                setPaymentMethod($("input:radio[name=payment_method_id]:checked"));
            @endif

            initStepper();
            initValidation();

            initAutocompleteFields(
                document.getElementById("address_1"),
                document.getElementById("city"),
                document.getElementById("postcode"),
                document.getElementById("country_id"),
                document.getElementById("zone_id"),
                document.getElementById("lat"),
                document.getElementById("lng"),
            );

            let deliveryStartDate = dateYesterday;
            @if ($type == 'edit' && isset($modal['delivery_date']) && !is_null($modal['delivery_date']) && $modal['delivery_date'] != '')
                deliveryStartDate = '{{ $modal['delivery_date'] }}';
            @endif

            initCustomDatePicker($("#delivery_date"), {
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 2000,
                autoApply: true,
                minDate: dateYesterday,
                startDate: deliveryStartDate,
                locale: {
                    format: "YYYY-MM-DD",
                    separator: "-",
                },
            });
        });

        var initStepper = function() {
            // Initialize Stepper
            stepperObj = new KTStepper(stepperEl);

            // Validation before going to next page
            stepperObj.on("kt.stepper.next", function(stepper) {
                // Validate form before change stepper step
                var validator = validations[stepperObj.getCurrentStepIndex() -
                    1]; // get validator for currnt step
                if (validator) {
                    validator.validate().then(function(status) {
                        if (status == 'Valid') {
                            let isTaxApplicable = isCartValid = true;
                            // runs on step Cart = step-2
                            if (stepperObj.getCurrentStepIndex() === 2) {
                                isCartValid = validateCart('{{ route('orders.isCartValid') }}', $(
                                        "#customer_id option:selected")
                                    .val()); // check if cart has products for a specific user
                                initAutocompleteFields(
                                    document.getElementById("shipping_address_1"),
                                    document.getElementById("shipping_city"),
                                    document.getElementById("shipping_postcode"),
                                    document.getElementById("shipping_country_id"),
                                    document.getElementById("shipping_zone_id"),
                                    document.getElementById("shipping_lat"),
                                    document.getElementById("shipping_lng"),
                                );
                            }
                            // runs on step Shipping Address = step-3
                            if (stepperObj.getCurrentStepIndex() === 3) {
                                isTaxApplicable = getApplicableTaxClass(
                                    '{{ route('tax-classes.getApplicableTaxClass') }}', $(
                                        "#shipping_country_id option:selected").val(), $(
                                        "#shipping_zone_id option:selected").val()
                                ); // check if there is a valid tax class applicable
                                getUncalOrderTotal('{{ route('orders.getUncalOrderTotal') }}', $(
                                        "#customer_id option:selected")
                                    .val()
                                    ); // get order total for showing on Payment / Shipping Method step
                            }
                            // true && true === true
                            if (isTaxApplicable && isCartValid) {
                                stepperObj.goNext();
                            }
                        }
                    });
                }
            });

            // Handle previous step
            stepperObj.on("kt.stepper.previous", function(stepper) {
                stepperObj.goPrevious(); // go previous step
            });

            // This event fires after current step change.
            stepperObj.on("kt.stepper.changed", function(e) {
                // Returns the current step index number as integer.
                let currentStep = stepperObj.getCurrentStepIndex();
                if (currentStep === 5) {
                    getCartTotal('{{ route('orders.cartTotal') }}');
                }
            });
        };

        var initValidation = function() {
            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            // Step 1
            validations.push(
                FormValidation.formValidation(form, {
                    fields: {
                        // store_id: {
                        //     validators: {
                        //         notEmpty: {
                        //             message: "The store field is required",
                        //         },
                        //     },
                        // },
                        currency_id: {
                            validators: {
                                notEmpty: {
                                    message: "The currency field is required",
                                },
                            },
                        },
                        customer_group_id: {
                            validators: {
                                notEmpty: {
                                    message: "The customer group field is required",
                                },
                            },
                        },
                        store_id: {
                            validators: {
                                notEmpty: {
                                    message: "The store field is required",
                                },
                            },
                        },
                        customer_id: {
                            validators: {
                                notEmpty: {
                                    message: "The customer field is required",
                                },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: "",
                        }),
                    },
                })
            );

            // Step 2
            validations.push(
                FormValidation.formValidation(form, {
                    fields: {
                        account_teaproductm_size: {
                            validators: {
                                notEmpty: {
                                    message: "The product field is required",
                                },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        // Bootstrap Framework Integration
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: "",
                        }),
                    },
                })
            );

            // // Step 3
            validations.push(
                FormValidation.formValidation(form, {
                    fields: {
                        shipping_first_name: {
                            validators: {
                                notEmpty: {
                                    message: "The first name field is required",
                                },
                            },
                        },
                        shipping_last_name: {
                            validators: {
                                notEmpty: {
                                    message: "The last name field is required",
                                },
                            },
                        },
                        shipping_address_1: {
                            validators: {
                                notEmpty: {
                                    message: "The address 1 field is required",
                                },
                            },
                        },
                        shipping_city: {
                            validators: {
                                notEmpty: {
                                    message: "The city field is required",
                                },
                            },
                        },
                        shipping_postcode: {
                            validators: {
                                notEmpty: {
                                    message: "The postcode field is required",
                                },
                            },
                        },
                        shipping_country_id: {
                            validators: {
                                notEmpty: {
                                    message: "The country field is required",
                                },
                            },
                        },
                        shipping_zone_id: {
                            validators: {
                                notEmpty: {
                                    message: "The zone field is required",
                                },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        // Bootstrap Framework Integration
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: "",
                        }),
                    },
                })
            );

            // Step 4
            fv = FormValidation.formValidation(form, {
                fields: {
                    shipping_method_id: {
                        validators: {
                            notEmpty: {
                                message: "The shipping method field is required",
                            },
                        },
                    },
                    payment_method_id: {
                        validators: {
                            notEmpty: {
                                message: "The payment method field is required",
                            },
                        },
                    },
                    // payment_type: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: "The payment type field is required",
                    //         },
                    //     },
                    // },
                    // paid_amount: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: "The paid amount field is required",
                    //         },
                    //     },
                    // },
                    // card_number: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: "The credit card number field is required",
                    //         },
                    //     },
                    // },
                    // card_exp: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: "The card expiration field is required",
                    //         },
                    //     },
                    // },
                    // card_cvv: {
                    //     validators: {
                    //         notEmpty: {
                    //             message: "The card cvv field is required",
                    //         },
                    //     },
                    // },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: "",
                    }),
                },
            })
            validations.push(fv);
        };

        $("#delivery_date").on("apply.daterangepicker", function(ev, picker) {
            $(this).val(picker.startDate.format("YYYY-MM-DD"));
        });

        $("#delivery_date").on("cancel.daterangepicker", function(ev, picker) {
            $(this).val("");
        });

        $('.delivery-date-icon').click(function() {
            $("#delivery_date").focus();
        });
        $(document).ready(function() {
            let current_year = new Date().getFullYear();
            let selected_year = $('#card_exp_year').val();
            let current_month = new Date().getMonth() + 1;
            disableMonthOption();
            $('.check-payment-method').change(function(e) {
                let mode = $('#payment_method_code').val();
                if (mode != 'COC') {
                    $('.partial-div').addClass('d-none');
                } else {
                    let payment_type = $("#payment_type option:selected").val();
                    if(payment_type == 'partial')
                    {
                    $('.partial-div').removeClass('d-none');
                    }
                    else{
                        $('.partial-div').addClass('d-none');
                    }
                }
            });


            $('#card_exp_year').on('change', function() {
                if (this.value == current_year) {
                    disableMonthOption();
                } else {
                    for (let i = 1; i < current_month; i++) {
                        let value = i;
                        if (value < 10) {
                            value = "0" + value;
                        }
                        $('#card_exp_month option[value="' + value + '"]').attr("disabled", false);
                    }
                }
            });
            $("#multi_step_form").submit(function(e){
                e.preventDefault();
                let valid = handleOrderSubmit(this);
                
                    
                
            })

            function disableMonthOption() {

                if (current_year == selected_year) {
                    for (let i = 1; i < current_month; i++) {
                        let value = i;
                        if (value < 10) {
                            value = "0" + value;
                        }
                        $('#card_exp_month option[value="' + value + '"]').attr("disabled", true);
                    }

                }
                if (current_month < 10) {
                    current_month = "0" + current_month;
                }
                $('#card_exp_month option[value="' + current_month + '"]').attr('selected', 'selected');

            }

           

        });
        function checkUserDiscount(ele, max_allowed_discount)
            {
                let value = $(ele).val();
                value = parseFloat(value);
                max_allowed_discount = parseFloat(max_allowed_discount);
                if(value>max_allowed_discount)
                {
                    $(ele).val("0");
                    if(max_allowed_discount>0)
                    {
                    toastr.error("Your max discount limit is $"+ max_allowed_discount);
                    }
                    else{
                     toastr.error("You are not authorized to give discount");
                    }

                }
            }

    </script>
@endpush
