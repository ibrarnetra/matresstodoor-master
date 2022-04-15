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
                    <li class="breadcrumb-item text-muted">Sales</li>
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

                    <div class="row row-sample d-none">
                        <div class="col-md-4 mb-5">
                            <input type="text" required name="option_value_name" class="form-control form-control-solid">
                        </div>
                        <div class="col-md-4 mb-5">
                            <input type="file" name="image" class="form-control form-control-solid" accept="image/*">
                        </div>
                        <div class="col-md-3 mb-5">
                            <input type="number" min="1" name="sort_order" class="form-control form-control-solid">
                        </div>
                        <div class="col-md-1 mb-5 text-end">
                            <button class="btn btn-sm btn-danger del_button" type="button"><i class="fas fa-minus-circle"></i></button>
                        </div>
                    </div>
                    <!--end::Header-->

                    <div class="card-body pt-0">
                        <form action="{{route('orders.addToCart')}}" method="POST" id="add-to-cart">
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
                                                Purchase Summary
                                            </h3>
                                        </div>
                                    
                                    </div>
                                    <!--end::Nav-->
                                  
                                    <!--begin::Form-->
                                    <form class="form" id="multi_step_form" novalidate method="POST" enctype="multipart/form-data" @if($type=="create" ) action="{{route('purchases.store')}}" @else
                                        action="{{route('purchases.update', ['id' => $id])}}" @endif onsubmit="return handleOrderSubmit(this);">
                                        @csrf
                                        <input type="hidden" name="is_clone" value="{{$is_clone}}">
                                        <!--begin::Group-->
                                        <div class="mb-5">
                                            <!--begin::Step 1-->
                                            <div class="flex-column current" data-kt-stepper-element="content">
                                                <!--begin::Input group-->
                                                <div class="row">
                                                    {{-- <div class="col-md-4 mb-5">
                                                        <div class="fv-row">
                                                            <label class="form-label required" for="currency_id">Currency</label>
                                                            <select class="form-select form-select-solid @error('currency_id') is-invalid @enderror" id="currency_id" name="currency_id"
                                                                onchange="setCurrency(this)" required>
                                                                @if (count($currencies) > 0)
                                                                @foreach ($currencies as $currency)
                                                                <option value="{{$currency->id}}" @if (isset($currency->symbol_left) &&
                                                                    !is_null($currency->symbol_left)) data-symbol="{{$currency->symbol_left}}" @else data-symbol="$" @endif
                                                                    data-code={{$currency->code}}
                                                                    data-value="{{$currency->value}}" @if ($type=='edit' && $modal['currency_id']==$currency->id)
                                                                    selected @endif>{{$currency->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" name="currency_code" id="currency_code" @if ($type=='edit' && isset($modal['currency_code']))
                                                                value="{{$modal['currency_code']}}" @endif>
                                                            <input type="hidden" name="currency_value" id="currency_value" @if ($type=='edit' && isset($modal['currency_value']))
                                                                value="{{$modal['currency_value']}}" @endif>
                                                            @error('currency_id')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                    </div> --}}
                                                  
                                                    {{-- <div class="col-md-4 mb-5">
                                                        <div class="fv-row">
                                                            <label class="form-label required" for="customer_group_id" class="form-label">Customer Group</label>
                                                            <select class="form-select form-select-solid @error('customer_group_id') is-invalid @enderror" aria-label="customer_group_id"
                                                                id="customer_group_id" name="customer_group_id">
                                                                @if(count($customer_groups) > 0)
                                                                @foreach($customer_groups as $customer_group)
                                                                <option value="{{$customer_group->id}}" @if ($type=='edit' && $modal['customer_group_id']==$customer_group->id)
                                                                    selected @endif>
                                                                    {{$customer_group->eng_description->name}}
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
                                                    </div> --}}
                                                  
                                                    {{-- <div class="col-md-4 mb-5">
                                                        <div class="fv-row">
                                                            <label class="form-label required" for="store_id" class="form-label">Store</label>
                                                            <select class="form-select form-select-solid @error('store_id') is-invalid @enderror" aria-label="store_id"
                                                                id="store_id" name="store_id">
                                                                @if(count($stores) > 0)
                                                                @foreach($stores as $store)
                                                                <option value="{{$store->id}}" @if ($type=='edit' && $modal['store_id']==$store->id)
                                                                    selected @endif>
                                                                  {{$store->name}}
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
                                                    </div> --}}
                                                </div>
                                                <!--end::Input group-->

                                                <!--begin::Input group-->
                                                {{-- <div class="row">
                                                    <label class="form-label required col-md-3 col-form-label" for="customer_id" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                                        data-bs-placement="top" title="Customer needs to selected before proceeding to other tabs.">Customer <i class="fas fa-info-circle"></i></label>
                                                    <div class="col-md-9 text-end">
                                                        <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomer">
                                                            <i class="fas fa-plus-circle"></i> Add Customer
                                                        </button>
                                                    </div>
                                                </div> --}}

                                                <div class="row">
                                                    <div class="col-md-4 mb-5">
                                                        <div class="fv-row">
                                                         
                                                            <label class="form-label required" for="currency_id">Currency</label>
                                                            <select class="form-select form-select-solid @error('currency_id') is-invalid @enderror" id="currency_id" name="currency_id"
                                                                onchange="setCurrency(this)" required>
                                                                @if (count($currencies) > 0)
                                                                @foreach ($currencies as $currency)
                                                                <option value="{{$currency->id}}" @if (isset($currency->symbol_left) &&
                                                                    !is_null($currency->symbol_left)) data-symbol="{{$currency->symbol_left}}" @else data-symbol="$" @endif
                                                                    data-code={{$currency->code}}
                                                                    data-value="{{$currency->value}}" @if ($type=='edit' && $modal['currency_id']==$currency->id)
                                                                    selected @endif>{{$currency->title}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <input type="hidden" name="currency_code" id="currency_code" @if ($type=='edit' && isset($modal['currency_code']))
                                                                value="{{$modal['currency_code']}}" @endif>
                                                            <input type="hidden" name="currency_value" id="currency_value" @if ($type=='edit' && isset($modal['currency_value']))
                                                                value="{{$modal['currency_value']}}" @endif>
                                                            @error('currency_id')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-5">
                                                        
                                                        <div class="fv-row">
                                                            <label class="form-label required" for="warehouse_id">Warehouse</label>
                                                            <select class="form-select form-select-solid warehouse @error('warehouse_id') is-invalid @enderror" id="warehouse_id" name="warehouse_id"
                                                                required>
                                                              
                                                                @if ($type=='edit' && isset($modal['warehouse_id']))
                                                                <option value="{{$modal['warehouse_id']}}" selected>{{$modal->warehouse->name}}</option>
                                                                @endif
                                                                @foreach($warehouses as $warehouse)
                                                                <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('warehouse_id')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="mb-5 col-md-4">
                                                        <label class="form-label required" for="name">Purchase Date</label>
                                                        <input class="form-control form-control-solid form-control-sm" name="purchase_date" autocomplete="off"
                                                        id="purchase_date" placeholder="Pick date" @if($type=="edit" ) value="{{$modal['purchase_date']}}" @endif>
                                                   
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-5">
                                                        <div class="fv-row">
                                                           
                                                            <label class="form-label required">Serial No</label>
                                                            <input type="text"  class="form-control form-control-solid @error('serial_no') is-invalid @enderror" name="serial_no"
                                                                id="serial_no" placeholder="Serial No"  @if($type=="edit" ) value="{{$modal['serial_no']}}" @endif required>
                                                          
                                                            @error('serial_no')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="mb-5 col-md-6">
                                                        <label class="form-label" for="name">Vehicle No.</label>
                                                        <input type="text"  class="form-control form-control-solid @error('vehicle_no') is-invalid @enderror" name="vehicle_no"
                                                        id="vehicle_no" placeholder="Vehicle No" @if($type=="edit" ) value="{{$modal['vehicle_no']}}" @endif>
                                                   
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="name">Purchase Remarks</label>
                                                        <textarea id="remarks" class="form-control form-control-sm form-control-solid" name="remarks"
                                                         rows="3">@if ($type =='create'){{old('remarks')}}@else{{$modal['remarks']}}@endif</textarea>
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
                                                                <table class="table table-sm table-row-bordered table-column-bordered table-row-gray-300 border gs-6 gy-3">
                                                                    <thead>
                                                                        <tr class="fw-bolder fs-6 text-gray-800">
                                                                            <th style="width: 70%;">Product</th>
                                                                            <th style="width: 25%;" class="text-center">Quantity</th>
                                                                            {{-- <th style="width: 12%;" class="text-end">Unit Price</th>
                                                                            <th style="width: 12%;" class="text-end">Total</th> --}}
                                                                            <th style="width: 5%;">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="purchase-cart-item">
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr class="fs-6 text-gray-800">
                                                                         
                                                                            <td colspan="5" class="fw-bolder text-center">
                                                                                Total Unit/Quantity: <span id="unit-by-quantity"></span>
                                                                            </td>
                                                                        </tr>
                                                                        
                                                                        {{-- <tr class="fs-6 text-gray-800">
                                                                            <td colspan="3" class="fw-bolder text-end">
                                                                                Sub-Total:
                                                                            </td>
                                                                            <td class="fw-bolder text-end" id="sub-total"></td>
                                                                            <td></td>
                                                                        </tr> --}}
                                                                        {{-- <tr class="fs-6 text-gray-800">
                                                                            <td colspan="3" class="fw-bolder text-end">
                                                                                Grand-Total:
                                                                            </td>
                                                                            <td class="fw-bolder text-end" id="grand-total">

                                                                            </td>
                                                                            <td></td>
                                                                        </tr> --}}
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
                                                        <input type="hidden" form="add-to-cart" name="index" id="index" value="0">
                                                        <div class="mb-5 col-md-6">
                                                            <div class="row mb-2">
                                                                <div class="col-md-6">
                                                                    <label class="form-label" for="">Choose Product </label>
                                                                </div>
                                                                <div class="col-md-6 text-end">
                                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add-admin-product">Create
                                                                        Product</button>
                                                                </div>
                                                            </div>
                                                            <select class="form-select from-select-solid product @error('product') is-invalid @enderror" name="product" id="product" form="add-to-cart"
                                                                style="width: 100%;">
                                                            </select>
                                                            @error('product')
                                                            <div class="invalid-feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-5 col-md-6">
                                                            <label class="form-label mb-6" for="product_qty">Quantity</label>
                                                            <input type="number" min="1" value="1" class="form-control form-control-solid @error('product_qty') is-invalid @enderror" name="product_qty"
                                                                id="product_qty" form="add-to-cart">
                                                            @error('product_qty')
                                                            <div class="invalid-feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                            @enderror
                                                        </div>
                                                        {{-- <div class="mb-5 col-md-6"> --}}
                                                            {{-- <label class="form-label mb-6" for="purchase_unit_price">Unit Price</label> --}}
                                                            <input type="hidden"  value="0" class="form-control form-control-solid @error('purchase_unit_price') is-invalid @enderror" name="purchase_unit_price"
                                                                id="purchase_unit_price" form="add-to-cart">
                                                            @error('purchase_unit_price')
                                                            <div class="invalid-feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                            @enderror
                                                        {{-- </div> --}}
                                                    </div>
                                                    <div id="product-options"></div>
                                                </div>

                                                <div class="fv-row">
                                                    <div class="row">
                                                        <div class="col-md-12 text-end">
                                                            <button class="btn btn-light-primary" type="button" onclick="submitFrom()">
                                                                <input type="hidden" id="option_type_order">
                                                                Add to Purchase
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <!--begin::Step 2-->

                                          

                                          
                                          
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
                                                <a href="{{route('orders.index')}}" class="btn btn-light-dark me-2">
                                                    Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary" data-kt-stepper-action="submit">
                                                    <span class="indicator-label">
                                                        Submit
                                                    </span>
                                                    <span class="indicator-progress">
                                                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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

    {{-- <div class="modal fade" tabindex="-1" id="addCustomer">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Add Customer
                        <div class="spinner-border spinner-border-sm text-dark ms-5 d-none custom-loader" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </h2>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">
                                    <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1"></rect>
                                    <rect fill="#000000" opacity="0.5" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)" x="0" y="7" width="16" height="2"
                                        rx="1"></rect>
                                </g>
                            </svg>
                        </span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form method="POST" action="{{route('customers.ajaxSubmit')}}" onsubmit="return ajaxCustomerCreate(this, event.preventDefault());">
                        @csrf
                        <h2 class="mb-5">Customer Detail</h2>
                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label for="address_customer_group_id" class="form-label required">Customer Group</label>
                                <select class="form-select form-select-solid @error('customer_group_id') is-invalid @enderror" aria-label="customer_group_id" id="address_customer_group_id"
                                    name="customer_group_id" required>
                                    @if(count($customer_groups) > 0)
                                    @foreach($customer_groups as $customer_group)
                                    <option value="{{$customer_group->id}}">
                                        {{$customer_group->eng_description->name}}
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
                                <input type="text" class="form-control form-control-solid @error('first_name') is-invalid @enderror" id="first_name" name="first_name" required
                                    onkeyup="fillCustomerData(this)">

                                @error('first_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="last_name">Last Name</label>
                                <input type="text" class="form-control form-control-solid @error('last_name') is-invalid @enderror" id="last_name" name="last_name" required
                                    onkeyup="fillCustomerData(this)">

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
                                <input type="email" class="form-control form-control-solid @error('email') is-invalid @enderror" id="email" name="email" required>

                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="telephone">Mobile (xxx-xxx-xxxx)</label>
                                <input type="tel" class="form-control form-control-solid @error('telephone') is-invalid @enderror" id="telephone" name="telephone" required placeholder="xxx-xxx-xxxx"
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
                                <input type="text" class="form-control form-control-solid" id="address_first_name" name="address[1][first_name]" required>
                            </div>
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="address_last_name">Last Name</label>
                                <input type="text" class="form-control form-control-solid" id="address_last_name" name="address[1][last_name]" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <div class="fv-row">
                                    <label class="form-label" for="address[1][telephone]">Mobile (xxx-xxx-xxxx)</label>
                                    <input type="tel" placeholder="xxx-xxx-xxxx" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" class="form-control form-control-solid" name="address[1][telephone]"
                                        id="address_telephone">

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <input type="hidden" name="address[1][lat]" id="lat">
                            <input type="hidden" name="address[1][lng]" id="lng">
                            <div class="mb-5 col-md-12">
                                <label for="address_1" class="form-label required">Address 1 </label>
                                <input type="text" class="form-control form-control-solid" name="address[1][address_1]" id="address_1" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-5 col-md-12">
                                <label for="address_2" class="form-label">Address 2 </label>
                                <input type="text" class="form-control form-control-solid" name="address[1][address_2]" id="address_2">
                            </div>
                        </div>

                        <div class="row">
                            {{-- <div class="mb-5 col-md-6">
                                <label class="form-label" for="company">Company</label>
                                <input type="text" class="form-control form-control-solid" id="company" name="address[1][company]">
                            </div> --}}
                            {{-- <div class="mb-5 col-md-6">
                                <label class="form-label required" for="city">City</label>
                                <input type="text" class="form-control form-control-solid" id="city" name="address[1][city]" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label required">Country</label>
                                <select class="form-select form-select-solid country" name="address[1][country_id]" id="country_id" data-id="modal-country"
                                    onchange="getZones(this, '{{route('zones.getZones')}}')" required style="width: 100%;">
                                    <option value="" disabled selected>-- Select Country --</option>
                                    @if (count($countries) > 0)
                                    @foreach ($countries as $country)
                                    <option value="{{$country->id}}" @if ($country->name == "Canada")
                                        selected
                                        @endif>{!! $country->name !!}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-5 col-md-6">
                                <label class="form-label required">Region / State</label>
                                <select class="form-select form-select-solid zone" name="address[1][zone_id]" id="zone_id" data-id="modal-zone" required style="width: 100%;">
                                    <option value="" disabled selected>-- Select State --</option>
                                    @if (count($zones) > 0)
                                    @foreach ($zones as $zone)
                                    <option value="{{$zone->id}}" @if ($zone->name == "Ontario")
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
                                <input type="text" class="form-control form-control-solid" id="postcode" name="address[1][postcode]" required>
                            </div>
                            <div class="mb-5 col-md-6">
                                <label class="form-label">Default Address </label>
                                <div class="form-check form-check-solid">
                                    <input class="form-check-input" type="radio" checked name="address[1][is_default]" value="1" />
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
        </div> --}}
    {{-- </div> --}} 

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
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">
                                    <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1"></rect>
                                    <rect fill="#000000" opacity="0.5" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)" x="0" y="7" width="16" height="2"
                                        rx="1"></rect>
                                </g>
                            </svg>
                        </span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <form method="POST" action="{{route('products.createProductForAdminPanel')}}" onsubmit="return createProductForAdminPanel(this, event.preventDefault());">
                        @csrf
                        <input type="hidden" id="product_type" name="product_type" value="admin">
                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="name">Name</label>
                                <input type="text" class="form-control form-control-solid" id="name" name="name" required>
                            </div>
                            <div class="mb-5 col-md-6">
                                <label class="form-label required" for="price">Price</label>
                                <input type="number" step="0.01" min="1" class="form-control form-control-solid" id="price" name="price" required>
                            </div>
                        </div>
                      
                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label" for="category_id">Category</label>
                                <select class="form-select form-select-solid @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
                                    <option value="" selected disabled>Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{$category->id}}">
                                        {{$category->eng_description->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                         
                            <div class="mb-5 col-md-6">
                                <label class="form-label" for="manufacturer_id">Manufacturer</label>
                                <select class="form-select form-select-solid @error('manufacturer_id') is-invalid @enderror" name="manufacturer_id" id="manufacturer_id">
                                    <option value="" selected disabled>Select Manufacturer</option>
                                    @foreach($manufacturers as $manufacturer)
                                    <option value="{{$manufacturer->id}}">
                                        {{$manufacturer->name}}
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


<input type="hidden" id="customer_search" value="{{route('customers.search')}}">
<input type="hidden" id="coupon_search" value="{{route('coupons.search')}}">
<input type="hidden" id="voucher_search" value="{{route('vouchers.search')}}">
<input type="hidden" id="customers_addresses" value="{{route('customers.getCustomerAddresses')}}">
<input type="hidden" id="product_search" value="{{route('products.search')}}">
<input type="hidden" id="add_to_cart" value="{{route('purchases.addToCart')}}">
<input type="hidden" id="validate_purchase_qty" value="{{route('purchases.validatePurchaseQty')}}">
<input type="hidden" id="clear_cart" value="{{route('orders.clearCart')}}">
@endsection

@push('page_lvl_js')
<script src="{{asset('/')}}custom/purchases.js"></script>
<script src="{{asset('/')}}places/index.js"></script>
<script>
    // Elements
	var stepperEl;
	var form;
    // Variables
    var stepperObj;
    var validations = [];
    var fv;

    $(document).ready(function () {
        stepperEl = document.querySelector('#multi_step');
        form = stepperEl.querySelector('#multi_step_form');

        @if($type == "edit")
            generateCartForEdit('{{$id}}');
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
        
        let PurchaseStartDate = dateYesterday;
        @if($type == 'edit' && isset($modal["purchase_date"]) && !is_null($modal["purchase_date"]) && $modal["purchase_date"]!= "")
        PurchaseStartDate = '{{$modal["purchase_date"]}}';
        @endif

        initCustomDatePicker($("#purchase_date"), {
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 2000,
            autoApply: true,
            minDate: dateYesterday,
            startDate: PurchaseStartDate,
            locale: {
                format: "YYYY-MM-DD",
                separator: "-",
            },
        });
    });

    var initStepper = function () {
        // Initialize Stepper
        stepperObj = new KTStepper(stepperEl);


        // Validation before going to next page
        stepperObj.on("kt.stepper.next", function (stepper) {
			// Validate form before change stepper step
			var validator = validations[stepperObj.getCurrentStepIndex() - 1]; // get validator for currnt step
			if (validator) {
				validator.validate().then(function (status) {
					if (status == 'Valid') {
                        let isTaxApplicable = isCartValid = true;
                        // runs on step Cart = step-2
                        if(stepperObj.getCurrentStepIndex() === 2){
                            isCartValid = validateCart('{{route("orders.isCartValid")}}', $("#customer_id option:selected").val()); // check if cart has products for a specific user
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
                        if(stepperObj.getCurrentStepIndex() === 3){
                            isTaxApplicable = getApplicableTaxClass('{{route("tax-classes.getApplicableTaxClass")}}', $("#shipping_country_id option:selected").val(), $("#shipping_zone_id option:selected").val()); // check if there is a valid tax class applicable
                            getUncalOrderTotal('{{route("orders.getUncalOrderTotal")}}', $("#customer_id option:selected").val()); // get order total for showing on Payment / Shipping Method step
                        }
                        // true && true === true
                        if(isTaxApplicable && isCartValid){
                            stepperObj.goNext();
                        }
					}
				});
			}
        });

        // Handle previous step
        stepperObj.on("kt.stepper.previous", function (stepper) {
            stepperObj.goPrevious(); // go previous step
        });

        // This event fires after current step change.
        stepperObj.on("kt.stepper.changed", function(e) {
            // Returns the current step index number as integer.
            let currentStep = stepperObj.getCurrentStepIndex();
            if(currentStep === 5){
                getCartTotal('{{route('orders.cartTotal')}}');
            }
        });
    };

    var initValidation = function () {
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
                    warehouse_id: {
                        validators: {
                            notEmpty: {
                                message: "The warehouse field is required",
                            },
                        },
                    },
                    serial_no: {
                        validators: {
                            notEmpty: {
                                message: "The Serial No field is required",
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
    
        // Step 4
    };

    $("#purchase_date").on("apply.daterangepicker", function (ev, picker) {
        $(this).val(picker.startDate.format("YYYY-MM-DD"));
    });

    $("#purchase_date").on("cancel.daterangepicker", function (ev, picker) {
        $(this).val("");
    });

    $('.purchase-date-icon').click(function() {
       $("#purchase_date").focus();
    });
</script>
@endpush