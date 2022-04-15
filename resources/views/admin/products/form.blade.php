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
                        <li class="breadcrumb-item text-muted">Catalog</li>
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

            <div class="row @if (!$errors->any()) d-none @endif" id="error-div">
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
                            <span id="error-span">
                                {!! implode('', $errors->all('<span>:message</span>')) !!}
                            </span>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-1 mb-5 pb-3 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">{{ $title }}</span>
                            </h3>
                        </div>

                        <div class="row row-special d-none">
                            <div class="col-md-3 mb-5">
                                <div class="controls">
                                    <select class="form-select form-select-solid" name="customer_group_id">
                                        @foreach ($customer_groups as $customer_group)
                                            <option value="{{ $customer_group->id }}">
                                                {{ $customer_group->eng_description->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mb-5">
                                <div class="controls">
                                    <input type="number" min="0" step="0.1" value="0" name="priority"
                                        class="form-control form-control-solid">
                                </div>
                            </div>
                            <div class="col-md-2 mb-5">
                                <div class="controls">
                                    <input type="number" min="0" step="0.1" value="0" name="price"
                                        class="form-control form-control-solid">
                                </div>
                            </div>
                            <div class="col-md-2 mb-5">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-solid special-datepicker"
                                        name="date_start" autocomplete="off" placeholder="yyyy-mm-dd">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-5">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-solid special-datepicker"
                                        name="date_end" autocomplete="off" placeholder="yyyy-mm-dd">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                            </div>
                            <div class="col-md-1 mb-5">
                                <div class="controls">
                                    <button class="btn btn-danger btn-sm del_button" type="button"><i
                                            class="fas fa-minus-circle"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="row row-sample d-none">
                            <div class="col-md-5 mb-5">
                                <div class="controls">
                                    <select name="attribute_id" class="form-select form-select-solid">
                                        @if (count($attribute_groups) > 0)
                                            @foreach ($attribute_groups as $attribute_group)
                                                <optgroup label="{{ $attribute_group->eng_description->name }}">
                                                    @if (count($attribute_group->attributes) > 0)
                                                        @foreach ($attribute_group->attributes as $attribute)
                                                            <option value="{{ $attribute->id }}">
                                                                {{ $attribute->eng_description->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-5">
                                <div class="controls">
                                    <textarea name="attribute_text" rows="3"
                                        class="form-control form-control-solid"></textarea>
                                </div>
                            </div>
                            <div class="col-md-1 mb-5">
                                <div class="controls">
                                    <button class="btn btn-danger btn-sm  del_button" type="button"><i
                                            class="fas fa-minus-circle"></i></button>
                                </div>
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                    <form method="POST" novalidate enctype="multipart/form-data" @if ($type == 'create') action="{{ route('products.store') }}" @else
                            action="{{ route('products.update', ['id' => $id]) }}"
                            @endif onsubmit="return validateForm(this);">
                            <div class="card-body pt-0">
                                @csrf
                                <input type="hidden" name="type" id="type" value="{{ $type }}">

                                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#general">General</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#data" role="tab">Data</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#links" role="tab">Links</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#attribute"
                                            role="tab">Attribute</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#option" role="tab">Option</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#special"
                                            role="tab">Discount</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#image" role="tab">Image</a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content tabcontent-border mt-3">
                                    <div class="tab-pane active" id="general" role="tabpanel">
                                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                                            @foreach ($languages as $language)
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab"
                                                        href="#{{ $language->code }}">{{ $language->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content">
                                            @foreach ($languages as $language)
                                                <div class="tab-pane" id="{{ $language->code }}" role="tabpanel">
                                                    <div class="row mt-8">
                                                        <div class="mb-5 col-md-12">
                                                            <label class="form-label required"
                                                                for="product_description[{{ $language->code }}][name]">Name</label>
                                                            <input type="text"
                                                                class="form-control form-control-solid @error('product_description.' . $language->code . '.name') is-invalid @enderror"
                                                                id="product_description[{{ $language->code }}][name]"
                                                                name="product_description[{{ $language->code }}][name]"
                                                                placeholder="Name" @if ($type == 'create')
                                                            value="{{ old('product_description.' . $language->code . '.name') }}"
                                                        @else
                                                            value="{{ old('product_description.' . $language->code . '.name') ?: $modal['product_description']['' . $language->code . '']['name'] }}"
                                            @endif
                                            required>

                                            <div class="invalid-feedback">
                                                @error('product_description.' . $language->code . '.name')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-5 col-md-12">
                                            <label class="form-label required"
                                                for="product_description[{{ $language->code }}][description]">Description</label>
                                            <textarea
                                                class="form-control form-control-solid @error('product_description.' . $language->code . '.description') is-invalid @enderror"
                                                name="product_description[{{ $language->code }}][description]"
                                                placeholder="Description"
                                                id="product_description[{{ $language->code }}][description]" rows="3"
                                                required>@if ($type == 'create'){{ old('product_description.' . $language->code . '.description') }}@else{{ old('product_description.' . $language->code . '.description') ?: $modal['product_description']['' . $language->code . '']['description'] }}@endif</textarea>

                                            <div class="invalid-feedback">
                                                @error('product_description.' . $language->code . '.description')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-5 col-md-12">
                                            <label class="form-label required"
                                                for="product_description[{{ $language->code }}][short_description]">Short
                                                Description</label>
                                            <textarea
                                                class="form-control form-control-solid @error('product_description.' . $language->code . '.short_description') is-invalid @enderror"
                                                name="product_description[{{ $language->code }}][short_description]"
                                                placeholder="Short Description"
                                                id="product_description[{{ $language->code }}][short_description]" rows="3"
                                                required>@if ($type == 'create'){{ old('product_description.' . $language->code . '.short_description') }}@else{{ old('product_description.' . $language->code . '.short_description') ?: $modal['product_description']['' . $language->code . '']['short_description'] }}@endif</textarea>

                                            <div class="invalid-feedback">
                                                @error('product_description.' . $language->code . '.short_description')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label required"
                                                for="product_description[{{ $language->code }}][meta_title]">Meta
                                                Title</label>
                                            <input type="text" required
                                                class="form-control form-control-solid @error('product_description.' . $language->code . '.meta_title') is-invalid @enderror"
                                                id="product_description[{{ $language->code }}][meta_title]"
                                                placeholder="Meta Title"
                                                name="product_description[{{ $language->code }}][meta_title]" @if ($type == 'create')
                                        value="{{ old('product_description.' . $language->code . '.meta_title') }}" @else
                                            value="{{ old('product_description.' . $language->code . '.meta_title') ?: $modal['product_description']['' . $language->code . '']['meta_title'] }}"
                                            @endif>

                                            <div class="invalid-feedback">
                                                @error('product_description.' . $language->code . '.meta_title')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-5 col-md-6">
                                            <label class="form-label"
                                                for="product_description[{{ $language->code }}][meta_description]">Meta
                                                Description
                                            </label>
                                            <textarea
                                                class="form-control form-control-solid @error('product_description.' . $language->code . '.meta_description') is-invalid @enderror"
                                                name="product_description[{{ $language->code }}][meta_description]"
                                                placeholder="Meta Description"
                                                id="product_description[{{ $language->code }}][meta_description]"
                                                rows="3">@if ($type == 'create'){{ old('product_description.' . $language->code . '.meta_description') }}@else{{ old('product_description.' . $language->code . '.meta_description') ?: $modal['product_description']['' . $language->code . '']['meta_description'] }}@endif</textarea>

                                            <div class="invalid-feedback">
                                                @error('product_description.' . $language->code . '.meta_description')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label"
                                                for="product_description[{{ $language->code }}][meta_keyword]">Meta
                                                Keyword</label>
                                            <input type="text"
                                                class="form-control form-control-solid @error('product_description.' . $language->code . '.meta_keyword') is-invalid @enderror"
                                                placeholder="Meta Keyword"
                                                id="product_description[{{ $language->code }}][meta_keyword]"
                                                name="product_description[{{ $language->code }}][meta_keyword]"
                                                @if ($type == 'create')
                                            value="{{ old('product_description.' . $language->code . '.meta_keyword') }}"
                                        @else
                                            value="{{ old('product_description.' . $language->code . '.meta_keyword') ?: $modal['product_description']['' . $language->code . '']['meta_keyword'] }}"
                                            @endif>

                                            <div class="invalid-feedback">
                                                @error('product_description.' . $language->code . '.meta_keyword')
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @endforeach
                            </div>
                    </div>

                    <div class="tab-pane" id="data" role="tabpanel">
                        <div class="row">
                            <div class="mb-5 col-md-3">
                                <label class="form-label" for="model">Model </label>
                                <input type="text"
                                    class="form-control form-control-solid @error('model') is-invalid @enderror" id="model"
                                    name="model" placeholder="Model" @if ($type == 'create') value="{{ old('model') }}" @else value="{{ old('model') ?: $modal['model'] }}" @endif>

                                @error('model')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-3">
                                <label class="form-label" for="sku" data-bs-toggle="tooltip"
                                    data-bs-custom-class="tooltip-dark" data-bs-placement="top"
                                    title="Stock Keeping Unit">SKU <i class="fas fa-info-circle"></i></label>
                                <input type="text"
                                    class="form-control form-control-solid @error('sku') is-invalid @enderror" id="sku"
                                    name="sku" placeholder="SKU" @if ($type == 'create')
                            value="{{ old('sku') }}" @else value="{{ old('sku') ?: $modal['sku'] }}" @endif>

                                @error('sku')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-3">
                                <label class="form-label required" for="price">Price</label>
                                <input type="number" min="1" step="0.1" placeholder="Price"
                                    class="form-control form-control-solid @error('price') is-invalid @enderror" id="price"
                                    name="price" @if ($type == 'create') value="{{ old('price') }}" @else value="{{ old('price') ?: $modal['price'] }}" @endif required>

                                <div class="invalid-feedback">
                                    @error('price')
                                        <strong>{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-5 col-md-3">
                                <label class="form-label required" for="quantity">Quantity</label>
                                <input type="number" min="0"
                                    class="form-control form-control-solid @error('quantity') is-invalid @enderror"
                                    id="quantity" name="quantity" required @if ($type == 'create') value="{{ old('quantity') ?: 0 }}" @else value="{{ old('quantity') ?: $modal['quantity'] }}" @endif>

                                <div class="invalid-feedback">
                                    @error('quantity')
                                        <strong>{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-5 col-md-3">
                                <label class="form-label required" for="minimum" data-bs-toggle="tooltip"
                                    data-bs-custom-class="tooltip-dark" data-bs-placement="top"
                                    title="Force a minimum ordered amount">Minimum Quantity <i
                                        class="fas fa-info-circle"></i></label>
                                <input type="number"
                                    class="form-control form-control-solid @error('minimum') is-invalid @enderror"
                                    id="minimum" name="minimum" min="1" required placeholder="Minimum Quantity"
                                    @if ($type == 'create') value="{{ old('minimum') ?: '1' }}" @else value="{{ old('minimum') ?: $modal['minimum'] }}" @endif>

                                <div class="invalid-feedback">
                                    @error('minimum')
                                        <strong>{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-5 col-md-3">
                                <label class="form-label" for="length_class_id">Length Class</label>
                                <select
                                    class="form-select form-select-solid  @error('length_class_id') is-invalid @enderror"
                                    name="length_class_id" id="length_class_id">
                                    <option value="0" disabled selected>Select Length Class</option>
                                    @if (count($length_classes) > 0)
                                        @foreach ($length_classes as $length_class)
                                            <option value="{{ $length_class->id }}" @if ($type == 'edit' && $modal['length_class_id'] == $length_class->id)
                                                selected
                                        @endif>{{ $length_class->eng_description->title }}</option>
                                    @endforeach
                                    @endif
                                </select>

                                @error('length_class_id')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-3">
                                <label class="form-label" for="length">Length</label>
                                <input type="number" min="0" step="0.1"
                                    class="form-control form-control-solid @error('length') is-invalid @enderror"
                                    id="length" name="length" placeholder="Length" @if ($type == 'create') value="{{ old('length') }}" @else value="{{ old('length') ?: $modal['length'] }}" @endif>

                                @error('length')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-3">
                                <label class="form-label" for="width">Width</label>
                                <input type="number" min="0" placeholder="Width" step="0.1"
                                    class="form-control form-control-solid @error('width') is-invalid @enderror" id="width"
                                    name="width" @if ($type == 'create') value="{{ old('width') }}" @else value="{{ old('width') ?: $modal['width'] }}" @endif>

                                @error('width')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-3">
                                <label class="form-label" for="height">Height</label>
                                <input type="number" min="0" placeholder="Height" step="0.1"
                                    class="form-control form-control-solid @error('height') is-invalid @enderror"
                                    id="height" name="height" @if ($type == 'create') value="{{ old('height') }}" @else value="{{ old('height') ?: $modal['height'] }}" @endif>

                                @error('height')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                        </div>
                        <div class="row">
                            <div class="mb-5 col-md-3">
                                <label class="form-label" for="weight_class_id">Weight Class</label>
                                <select
                                    class="form-select form-select-solid  @error('weight_class_id') is-invalid @enderror"
                                    name="weight_class_id" id="weight_class_id">
                                    <option value="0" disabled selected>Select Weight Class</option>
                                    @if (count($weight_classes) > 0)
                                        @foreach ($weight_classes as $weight_class)
                                            <option value="{{ $weight_class->id }}" @if ($type == 'edit' && $modal['weight_class_id'] == $weight_class->id)
                                                selected
                                        @endif>{{ $weight_class->eng_description->title }}</option>
                                    @endforeach
                                    @endif
                                </select>

                                @error('weight_class_id')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-3">
                                <label class="form-label" for="weight">Weight</label>
                                <input type="number" min="0" step="0.1"
                                    class="form-control form-control-solid @error('weight') is-invalid @enderror"
                                    id="weight" name="weight" placeholder="Weight" @if ($type == 'create') value="{{ old('weight') }}" @else value="{{ old('weight') ?: $modal['weight'] }}" @endif>

                                @error('weight')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-3">
                                <label class="form-label" for="tax_class_id">Tax Class</label>
                                <select class="form-select form-select-solid  @error('tax_class_id') is-invalid @enderror"
                                    name="tax_class_id" id="tax_class_id">
                                    <option value="0" disabled selected>Select Tax Class</option>
                                    @if (count($tax_classes) > 0)
                                        @foreach ($tax_classes as $tax_class)
                                            <option value="{{ $tax_class->id }}" @if ($type == 'edit' && $modal['tax_class_id'] == $tax_class->id)
                                                selected
                                        @endif>{{ $tax_class->title }}</option>
                                    @endforeach
                                    @endif
                                </select>

                                @error('tax_class_id')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-5 col-md-3">
                                <label class="form-label" for="sort_order">Sort Order</label>
                                <input type="number" min="1"
                                    class="form-control form-control-solid @error('sort_order') is-invalid @enderror"
                                    id="sort_order" name="sort_order" @if ($type == 'create') value="{{ old('sort_order') ?: '1' }}" @else value="{{ old('sort_order') ?: $modal['sort_order'] }}" @endif>

                                @error('sort_order')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-5 col-md-4">
                                <label class="form-label required" for="subtract">Subtract Stock</label>
                                <select class="form-select form-select-solid @error('subtract') is-invalid @enderror"
                                    name="subtract" id="subtract" required>
                                    <option value="1" @if ($type == 'edit' && $modal['subtract'] == '1') selected @endif>
                                        Yes
                                    </option>
                                    <option value="0" @if ($type == 'edit' && $modal['subtract'] == '0') selected @endif>
                                        No
                                    </option>
                                </select>

                                <div class="invalid-feedback">
                                    @error('subtract')
                                        <strong>{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-5 col-md-4">
                                <label class="form-label required" for="stock_status_id" data-bs-toggle="tooltip"
                                    data-bs-custom-class="tooltip-dark" data-bs-placement="top"
                                    title="Status shown when a product is out of stock">Out Of Stock Status <i
                                        class="fas fa-info-circle"></i></label>
                                <select
                                    class="form-select form-select-solid @error('stock_status_id') is-invalid @enderror"
                                    name="stock_status_id" id="stock_status_id" required>
                                    @if (count($stock_statuses) > 0)
                                        @foreach ($stock_statuses as $stock_status)
                                            <option value="{{ $stock_status->id }}" @if ($type == 'edit' && $modal['stock_status_id'] == $stock_status->id)
                                                selected
                                        @endif>{{ $stock_status->name }}</option>
                                    @endforeach
                                    @endif
                                </select>

                                <div class="invalid-feedback">
                                    @error('stock_status_id')
                                        <strong>{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-5 col-md-4">
                                <label class="form-label required" for="date_available">Date Available</label>
                                <div class="input-group">
                                    <input class="form-control form-control-solid" name="date_available" autocomplete="off"
                                        id="date_available" placeholder="Pick date" @if ($type == 'edit')
                                    value="{{ $modal['date_available'] }}" @endif required>
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>

                                <div class="invalid-feedback">
                                    @error('date_available')
                                        <strong>{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row">
                                        <div class="mb-5 col-md-2">
                                            <label class="form-label required">Requires Shipping </label>
                                            <div class="form-check form-check-solid form-check-inline">
                                                <input class="form-check-input" type="radio" id="yes" name="shipping" value="1" @if ($type == 'create') checked @endif @if ($type == 'edit' && $modal['shipping'] == '1') checked @endif />
                                                <label class="form-check-label" for="yes">
                                                    Yes
                                                </label>
                                            </div>
                                            <div class="form-check form-check-solid form-check-inline">
                                                <input class="form-check-input" type="radio" id="no" name="shipping" value="0" @if ($type == 'edit' && $modal['shipping'] == '0') checked @endif />
                                                <label class="form-check-label" for="no">
                                                    No
                                                </label>
                                            </div>

                                            @error('shipping')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div> --}}
                    </div>

                    <div class="tab-pane" id="links" role="tabpanel">
                        <div class="row">
                            <div class="mb-5 col-md-6">
                                <label class="form-label" for="">Manufacturers </label>
                                <select
                                    class="form-select form-select-solid @error('manufacturer_id') is-invalid @enderror"
                                    name="manufacturer_id" id="manufacturer_id">
                                    <option value="0" selected disabled>Select Manufacturer</option>
                                    @foreach ($manufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}" @if ($type == 'edit' && $modal['manufacturer_id'] == $manufacturer->id) selected @endif>
                                            {{ $manufacturer->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('manufacturer_id')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-5 col-md-12">
                                <label for="" class="form-label required">Store</label>
                                @error('stores')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="row">
                                    @foreach ($stores as $store)
                                        <div class="col-md-3 mb-4">
                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" name="stores[]"
                                                    value="{{ $store->id }}" id="stores-{{ $store->id }}"
                                                    @if ($type == 'edit')
                                                @if (in_array($store->id, $modal['stores']))
                                                    checked
                                                @endif
                                    @else checked @endif />
                                    <label class="form-check-label" for="stores-{{ $store->id }}">
                                        {{ $store->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-5 col-md-6">
                        <label class="form-label" for="">Categories </label>
                        <select class="form-select form-select-solid category @error('categories') is-invalid @enderror"
                            name="categories[]" id="categories" multiple style="width: 100%;">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if ($type == 'edit')
                                    @if (in_array($category->id, $modal['categories']))
                                        selected
                                    @endif
                            @endif>{{ $category->eng_description->name }}</option>
                            @endforeach
                        </select>

                        @error('categories')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    <div class="mb-5 col-md-6">
                        <label class="form-label" for="">Related Products </label>
                        <select class="form-select form-select-solid related @error('related') is-invalid @enderror"
                            name="related[]" multiple id="related" style="width: 100%;">
                        </select>

                        @error('related')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="attribute" role="tabpanel">
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6" role="tablist">
                    @foreach ($languages as $language)
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab"
                                href="#attribute-{{ $language->code }}">{{ $language->name }}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach ($languages as $language)
                        <div class="tab-pane" id="attribute-{{ $language->code }}" role="tabpanel">

                            <div id="item_container_{{ $language->code }}" class="mt-4">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="form-label">Attribute </label>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Text </label>
                                    </div>
                                </div>

                                @if ($type == 'edit')
                                    @if (count($modal['attribute']))
                                        @if (count($modal['attribute'][$language->code]['attribute_id']) > 0)
                                            @foreach ($modal['attribute'][$language->code]['attribute_id'] as $key => $attribute_id)
                                                <div class="row row-{{ $key }}">
                                                    <div class="col-md-5 mb-2">
                                                        <div class="controls">
                                                            <select
                                                                name="attribute[{{ $language->code }}][{{ $key }}][attribute_id]"
                                                                class="form-control form-control-solid">
                                                                @if (count($attribute_groups) > 0)
                                                                    @foreach ($attribute_groups as $attribute_group)
                                                                        <optgroup
                                                                            label="{{ $attribute_group->eng_description->name }}">
                                                                            @if (count($attribute_group->attributes) > 0)
                                                                                @foreach ($attribute_group->attributes as $attribute)
                                                                                    <option value="{{ $attribute->id }}"
                                                                                        @if ($modal['attribute'][$language->code]['attribute_id'][$key] == $attribute->id)
                                                                                        selected
                                                                                @endif
                                                                                >{{ $attribute->eng_description->name }}
                                                                                </option>
                                                                            @endforeach
                                                                    @endif
                                                                    </optgroup>
                                                                @endforeach
                                            @endif
                                            </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="controls">
                                <textarea name="attribute[{{ $language->code }}][{{ $key }}][attribute_text]"
                                    rows="3" class="form-control form-control-solid">@if ($modal['attribute'][$language->code]['attribute_text'][$key]){{ $modal['attribute'][$language->code]['attribute_text'][$key] }}@endif</textarea>
                            </div>
                        </div>
                        <div class="col-md-1 mb-2">
                            <div class="controls">
                                <button class="btn btn-danger btn-sm  del_button" type="button"><i
                                        class="fas fa-minus-circle"></i>
                                </button>
                            </div>
                        </div>
                </div>
                @endforeach
                @endif
                @endif
                @endif
            </div>

            <div class="row">
                <div class="offset-md-11 col-md-1 text-left">
                    <div class="controls">
                        <button class="btn btn-primary btn-sm  add_new_button" type="button"
                            onclick="addNewRow(['en','fr'], ['attribute_id','attribute_text'], 'attribute')">
                            <i class="fas fa-plus-circle"></i></button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    </div>

    <div class="tab-pane" id="option" role="tabpanel">
        <div class="row">
            <div class="mb-5 col-md-12">
                <label class="form-label" for="option_id">Option </label>
                <select class="form-select form-select-solid" id="option_id" style="width: 100%;">
                    <option value="" selected disabled>Select Option</option>
                    @foreach ($options as $option)
                        <option value="{{ $option->id }}" data-type="{{ $option->type }}">
                            {{ $option->eng_description->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="mb-5 col-md-12">
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6" id="option-nav-tabs" role="tablist">
                    @if ($type == 'edit' && count($modal['options']) > 0)
                        @foreach ($modal['options'] as $key => $option)
                            <li class="nav-item" data-id="{{ $key }}-{{ $option->option_id }}">
                                <a class="nav-link option-nav-link @if ($key == 0) active @endif" data-toggle="tab"
                                    href="#option-{{ $key }}-{{ $option->option_id }}" role="tab">
                                    {{ $option->eng_description->name }}
                                    <button class="ms -4 btn btn-xs btn-danger btn-sm"
                                        style="padding: 2px; border-radius: 2px;" type="button"
                                        onclick="delTab(this , '{{ $option->id }}')">
                                        <i class="fas fa-minus-circle text-white" style="padding: 2px;"></i>
                                    </button>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
                <div class="tab-content tabcontent-border" id="option-tab-content">
                    @if ($type == 'edit' && count($modal['options']) > 0)
                        @foreach ($modal['options'] as $key => $option)
                            @if (($option->value == '' || is_null($option->value)) && count($option->product_option_values) > 0)
                                <div class="tab-pane option-tab-pane @if ($key == 0) active @endif"
                                    id="option-{{ $key }}-{{ $option->option_id }}" role="tabpanel">
                                    <div class="row mt-8">
                                        <div class="mb-5 col-md-3">
                                            <label class="form-label"
                                                for="option[{{ $key }}-{{ $option->option_id }}][required]">Required</label>
                                            <input type="hidden"
                                                name="option[{{ $key }}-{{ $option->option_id }}][option_id]"
                                                value="{{ $option->option_id }}">
                                            <select class="form-select form-select-solid"
                                                name="option[{{ $key }}-{{ $option->option_id }}][required]">
                                                <option value="1" @if ($option->required == '1') selected
                            @endif>
                            Yes
                            </option>
                            <option value="0" @if ($option->required == '0')
                                selected
                        @endif>
                        No
                        </option>
                        </select>
                </div>
            </div>

            <div id="container-{{ $key }}-{{ $option->option_id }}">
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label" for="option_value_id">Option Value
                            <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="quantity">Quantity <span
                                class="text-danger">*</span></label>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label" for="subtract">Subtract Stock <span
                                class="text-danger">*</span></label>
                    </div>

                    <div class="col-md-2">
                        <label>Price <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-2">
                        <label>Weight <span class="text-danger">*</span>
                        </label>
                    </div>
                </div>

                @if (count($option->product_option_values) > 0)
                    @foreach ($option->product_option_values as $product_option_val_key => $product_option_val_value)
                        @if (!is_null($product_option_val_value->eng_description))
                            <div class="row row-{{ $product_option_val_key }}"
                                data-id="row-{{ $product_option_val_key }}">
                                <div class="col-md-2 mb-2">
                                    <div class="controls">
                                        <select class="form-select form-select-solid" required
                                            name="option[{{ $key }}-{{ $option->option_id }}][option_value][{{ $product_option_val_key }}][option_value_id]">
                                            <option
                                                value="{{ $product_option_val_value->eng_description->option_value_id }}">
                                                {{ $product_option_val_value->eng_description->name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <div class="controls">
                                        <input class="form-control form-control-solid" type="number" min="0"
                                            value="{{ $product_option_val_value->quantity }}"
                                            name="option[{{ $key }}-{{ $option->option_id }}][option_value][{{ $product_option_val_key }}][quantity]">
                                    </div>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <div class="controls">
                                        <select class="form-select form-select-solid" required
                                            name="option[{{ $key }}-{{ $option->option_id }}][option_value][{{ $product_option_val_key }}][subtract]">
                                            <option value="1" @if ($product_option_val_value->subtract == '1') selected @endif>
                                                Yes
                                            </option>
                                            <option value="0" @if ($product_option_val_value->subtract == '0') selected @endif>
                                                No
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <div class="controls">
                                        <select class="form-select form-select-solid" required
                                            name="option[{{ $key }}-{{ $option->option_id }}][option_value][{{ $product_option_val_key }}][price_prefix]">
                                            <option value="+" @if ($product_option_val_value->price_prefix == '+') selected @endif>
                                                +
                                            </option>
                                            <option value="-" @if ($product_option_val_value->price_prefix == '-') selected @endif>
                                                -
                                            </option>
                                        </select>
                                        <input class="form-control form-control-solid " type="number" min="0" step="0.1"
                                            value="{{ $product_option_val_value->price }}"
                                            name="option[{{ $key }}-{{ $option->option_id }}][option_value][{{ $product_option_val_key }}][price]">
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <div class="controls">
                                        <select class="form-select form-select-solid" required
                                            name="option[{{ $key }}-{{ $option->option_id }}][option_value][{{ $product_option_val_key }}][weight_prefix]">
                                            <option value="+" @if ($product_option_val_value->weight_prefix == '+') selected @endif>
                                                +
                                            </option>
                                            <option value="-" @if ($product_option_val_value->weight_prefix == '-') selected @endif>
                                                -
                                            </option>
                                        </select>
                                        <input class="form-control form-control-solid " type="number" min="0" step="0.1"
                                            value="{{ $product_option_val_value->weight }}"
                                            name="option[{{ $key }}-{{ $option->option_id }}][option_value][{{ $product_option_val_key }}][weight]">
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <div class="controls">
                                        <button class="btn btn-danger btn-sm del_button" type="button"><i
                                                class="fas fa-minus-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>

            <div class="row">
                <div class="offset-md-11 col-md-1 text-left">
                    <div class="controls">
                        <button class="btn btn-primary btn-sm " type="button"
                            data-id="{{ $key }}-{{ $option->option_id }}" onclick="getOptionValues(this)">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="tab-pane option-tab-pane @if ($key == 0) active @endif"
            id="option-{{ $key }}-{{ $option->option_id }}" role="tabpanel">
            <div class="row mt-8">
                <div class="mb-5 col-md-6">
                    <label class="form-label"
                        for="option[{{ $key }}-{{ $option->option_id }}][required]">Required</label>
                    <input type="hidden" name="option[{{ $key }}-{{ $option->option_id }}][option_id]"
                        value="{{ $option->option_id }}">
                    <select class="form-select form-select-solid "
                        name="option[{{ $key }}-{{ $option->option_id }}][required]">
                        <option value="1" @if ($option->required == '1')
                            selected
                            @endif>
                            Yes
                        </option>
                        <option value="0" @if ($option->required == '0')
                            selected
                            @endif>
                            No
                        </option>
                    </select>
                </div>
                <div class="mb-5 col-md-6">
                    <label class="form-label"
                        for="option[{{ $key }}-{{ $option->option_id }}][value]">Option
                        Value</label>
                    <input type="text" class="form-control form-control-solid" value="{{ $option->value }}"
                        name="option[{{ $key }}-{{ $option->option_id }}][value]">
                </div>
            </div>
        </div>
        @endif
        @endforeach
        @endif
    </div>
    </div>
    </div>
    </div>

    <div class="tab-pane" id="special" role="tabpanel">
        <div id="special-container">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                        data-bs-placement="top" title="Required if added">Customer Group <i
                            class="fas fa-info-circle"></i></label>
                </div>
                <div class="col-md-2">
                    <label class="form-label" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                        data-bs-placement="top" title="Required if added">Priority <i
                            class="fas fa-info-circle"></i></label>
                </div>
                <div class="col-md-2">
                    <label class="form-label" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                        data-bs-placement="top" title="Required if added">Price <i class="fas fa-info-circle"></i></label>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date Start </label>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date End </label>
                </div>
            </div>

            @if ($type == 'edit' && count($modal['specials']) > 0)
                @foreach ($modal['specials'] as $key => $special)
                    <div class="row row-{{ $key }}">
                        <div class="col-md-3 mb-2">
                            <div class="controls">
                                <select class="form-select form-select-solid"
                                    name="special[{{ $key }}][customer_group_id]">
                                    @foreach ($customer_groups as $customer_group)
                                        <option value="{{ $customer_group->id }}" @if ($special->customer_group_id == $customer_group->id) selected @endif>
                                            {{ $customer_group->eng_description->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="controls">
                                <input type="number" min="0" step="0.1" value="{{ $special->priority ?: 0 }}"
                                    name="special[{{ $key }}][priority]"
                                    class="form-control form-control-solid">
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="controls">
                                <input type="number" min="0" step="0.1" value="{{ $special->price ?: 0 }}"
                                    name="special[{{ $key }}][price]" class="form-control form-control-solid">
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-solid"
                                    id="special-start-date-{{ $key + 1 }}" autocomplete="off"
                                    name="special[{{ $key }}][date_start]" @if (!is_null($special->date_start))
                                value="{{ $special->date_start }}"
                @endif>
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
        </div>
    </div>
    <div class="col-md-2 mb-2">
        <div class="input-group">
            <input type="text" class="form-control form-control-solid" id="special-end-date-{{ $key + 1 }}"
                autocomplete="off" name="special[{{ $key }}][date_end]" @if (!is_null($special->date_end))
            value="{{ $special->date_end }}"
            @endif>
            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
        </div>
    </div>
    <div class="col-md-1 mb-2">
        <div class="controls">
            <button class="btn btn-danger btn-sm  del_button" type="button"><i class="fas fa-minus-circle"></i></button>
        </div>
    </div>
    </div>
    @endforeach
    @endif
    </div>

    <div class="row">
        <div class="offset-md-11 col-md-1 text-left">
            <div class="controls">
                <button class="btn btn-primary btn-sm " type="button"
                    onclick="addNewSpecial(['customer_group_id','priority','price','date_start','date_end',], 'special')">
                    <i class="fas fa-plus-circle"></i></button>
            </div>
        </div>
    </div>

    </div>

    <div class="tab-pane" id="image" role="tabpanel">
        <div class="row">
            <div class="mb-5 col-md-6">
                <label class="form-label @if ($type == 'create') required @endif" for="image-field">Image</label>
                <input type="file" multiple class="form-control form-control-solid @error('image') is-invalid @enderror"
                    id="image-field" name="image[]" @if ($type == 'create')
                required
                @endif accept="image/*">

                <div class="invalid-feedback">
                    @error('image')
                        <strong>{{ $message }}</strong>
                    @enderror
                </div>
            </div>
        </div>

        <input type="hidden" name="is_clone" value="{{ $is_clone }}">
        @if ($type == 'edit' && !$is_clone)
            @if (count($modal['original_images']))
                <div class="row">
                    @foreach ($modal['original_images'] as $original_image)
                        <div class="mb-5 col-md-1" style="position: relative;">
                            <img class="img-fluid" width="80"
                                src="{{ asset('storage/product_images/' . $original_image->image) }}"
                                alt="{{ $original_image->image }}">
                            <a class="btn btn-danger btn-sm delImg"
                                onclick="deleteImage(this, '{{ route('products.deleteImage', ['id' => $id, 'image' => $original_image->image, 'type' => 'original']) }}')"
                                style="position: absolute;
                                                                                left: 75px;
                                                                                top: -10px;
                                                                                padding: 2px;
                                                                                border-radius: 2px;"
                                data-id="{{ $original_image->image }}">
                                <i class="fas fa-minus-circle text-white" style="padding: 2px;"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="row">
            <div class="mb-5 col-md-6">
                <label class="form-label @if ($type == 'create') required @endif" for="thumbnail-field">Thumbnail</label>
                <input type="file" multiple
                    class="form-control form-control-solid @error('thumbnail') is-invalid @enderror" id="thumbnail-field"
                    name="thumbnail" @if ($type == 'create')
                required @endif accept="image/*">

                <div class="invalid-feedback">
                    @error('thumbnail')
                        <strong>{{ $message }}</strong>
                    @enderror
                </div>
            </div>
        </div>

        @if ($type == 'edit' && !$is_clone)
            @if ($modal['thumbnail_image'])
                <div class="row">
                    <div class="mb-5 col-md-1" style="position: relative;">
                        <img class="img-fluid" width="80"
                            src="{{ asset('storage/product_images/thumbnail/' . $modal['thumbnail_image']->image) }}"
                            alt="{{ $modal['thumbnail_image']->image }}">
                        <a class="btn btn-danger btn-sm delImg"
                            onclick="deleteImage(this, '{{ route('products.deleteImage', ['id' => $id, 'image' => $modal['thumbnail_image']->image, 'type' => 'thumbnail']) }}')"
                            style="position: absolute;
                                                                                                                            left: 75px;
                                                                                                                            top: -10px;
                                                                                                                            padding: 2px;
                                                                                                                            border-radius: 2px;"
                            data-id="{{ $modal['thumbnail_image']->image }}">
                            <i class="fas fa-minus-circle text-white" style="padding: 2px;"></i>
                        </a>
                    </div>
                </div>
            @endif
        @endif
    </div>
    </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-md-12 text-start">
                <button type="submit" class="btn btn-success">Submit</button>
                <a class="btn btn-secondary" href="{{ route('products.index') }}">Cancel</a>
            </div>
        </div>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>
    <!--end::Container-->
    <input type="hidden" value="{{ route('categories.search') }}" id="category_url">
    <input type="hidden" value="{{ route('products.search') }}" id="related_url">
    </div>
    <!--end::Post-->
@endsection

@push('page_lvl_js')
    <script src="{{ asset('/') }}custom/products.js"></script>
    <script>
        @if ($type == 'edit')
            @if (count($modal['related']) > 0)
                @foreach ($modal['related'] as $related)
                    $('.related').append('<option value="{{ $related->id }}" selected="selected">{{ $related->name }}</option>');
                @endforeach
            @endif
            $('.related').trigger('change');
        @endif

        let startDate = endDate = dateAvailability = dateYesterday;
        @if ($type == 'edit' && count($modal['specials']) > 0)
            @foreach ($modal['specials'] as $key => $special)
                startDate = '{{ $special->date_start }}';
                endDate = '{{ $special->date_end }}';
            
                initCustomDatePicker($("#special-start-date-{{ $key + 1 }}"), {
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 2000,
                autoApply: true,
                startDate: startDate,
                locale: {
                format: "YYYY-MM-DD",
                separator: "-",
                },
                });
            
                initCustomDatePicker($("#special-end-date-{{ $key + 1 }}"), {
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 2000,
                autoApply: true,
                startDate: endDate,
                locale: {
                format: "YYYY-MM-DD",
                separator: "-",
                },
                });
            @endforeach
        @endif

        @if ($type == 'edit' && isset($modal['date_available']) && !is_null($modal['date_available']) && $modal['date_available'] != '')
            dateAvailability = '{{ $modal['date_available'] }}';
        @endif

        initCustomDatePicker($("#date_available"), {
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            startDate: dateAvailability,
            locale: {
                format: "YYYY-MM-DD",
                separator: "-",
            },
        });
    </script>
@endpush
