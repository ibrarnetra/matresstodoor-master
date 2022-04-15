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
                        <li class="breadcrumb-item text-muted">System</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-200 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Localization</li>
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
                                <span class="card-label fw-bolder fs-3 mb-1">{{ $title }}
                                    <div class="spinner-border text-dark ms-3 d-none custom-loader" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </span>

                            </h3>
                            <div class="card-toolbar">
                                @can('Add-Routes')
                                    <button onclick="loadOrdersModal(this, '{{ route('routes.getOrders') }}')"
                                        class="btn btn-sm btn-primary create"><i class="fas fa-plus-circle"></i> Create
                                        New</button>
                                @endcan
                                {{-- <a href="javascript:void(0);" class="btn btn-sm btn-danger delete d-none" onclick="bulkDelete('{{route('routes.bulkDelete')}}')"><i class="fas fa-trash-alt"></i>
                            Delete</a> --}}
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-striped table-sm gy-2 gs-2 align-middle">
                                            <thead>
                                                <tr class="fw-bolder fs-6 text-gray-800">
                                                    <th style="width: 25%">Name</th>
                                                    <th style="width: 24%">Start Location</th>
                                                    <th style="width: 24%">End Location</th>
                                                    <th style="width: 20%">Created By</th>
                                                    <th style="width: 7%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($routes) > 0)
                                                    @php $a = 0 @endphp
                                                    @foreach ($routes as $route)
                                                        @php $a++ @endphp
                                                        <tr>
                                                            <td>{{ $route->name }}</td>
                                                            <td>
                                                                @if ($route->start_location_id != '0')
                                                                    {{ $route->start_location->address }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($route->end_location)
                                                                    {{ $route->end_location }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </td>
                                                            <td>{{ $route->route_created_by->first_name . ' ' . $route->route_created_by->last_name }}
                                                            </td>
                                                            <!--begin::Action=-->
                                                            <td class="text-end">
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
                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a class="menu-link px-3"
                                                                            href="{{ route('routes.detail', ['id' => $route->id]) }}">Detail</a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    @can('Edit-Routes')
                                                                        <!--begin::Menu item-->
                                                                        <div class="menu-item px-3">
                                                                            <a class="menu-link px-3"
                                                                                href="{{ route('routes.edit', ['id' => $route->id]) }}">Edit</a>
                                                                        </div>
                                                                        <!--end::Menu item-->
                                                                    @endcan

                                                                    @can('Delete-Routes')
                                                                        <!--begin::Menu item-->
                                                                        <div class="menu-item px-3">
                                                                            <a class="menu-link px-3" href="javascript:void(0);"
                                                                                onclick="deleteData('{{ route('routes.delete', ['id' => $route->id]) }}', false)">Delete</a>
                                                                        </div>
                                                                        <!--end::Menu item-->
                                                                    @endcan

                                                                    @if (!$route->loading_sheet)
                                                                        <!--begin::Menu item-->
                                                                        <div class="menu-item px-3">
                                                                            <a class="menu-link px-3"
                                                                                href="javascript:void(0);"
                                                                                onclick="generateLoadingSheet(this, '{{ $route->id }}', '{{ route('loading-sheets.store') }}')">Generate
                                                                                Loading Sheet</a>
                                                                        </div>
                                                                        <!--end::Menu item-->
                                                                    @endif


                                                                    {{-- @if ($route->loading_sheet)
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a class="menu-link px-3" href="{{route('loading-sheets.detail', ['id' => $route->loading_sheet->id])}}">Loading Sheet Detail</a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    @endif --}}

                                                                </div>
                                                                <!--end::Menu-->
                                                            </td>
                                                            <!--end::Action=-->
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="7" class="text-center"><strong>No Data
                                                                Found...</strong></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table container-->
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    {{ $routes->links('admin.pagination.pagination') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->

    <div id="order-modal-section"></div>

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
@endsection

@push('page_lvl_js')
    <script src="{{ asset('metronic/') }}/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="{{ asset('/') }}places/index.js"></script>
    <script src="{{ asset('/') }}custom/route.js"></script>
    <script>
        $(document).ready(function() {
           
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
            return {
                destroy: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('orders.dataTable') }}",
                    type: "GET",
                    data: {
                        date_range: "-1",
                        delivery_date_range: "-1",
                        order_status: "-1",
                        sales_rep_id: "-1",
                        customer_id: "-1",
                        team_member_id: "-1",
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
                        data: "date_added",
                        name: "date_added",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "delivery_date",
                        name: "delivery_date",
                        orderable: true,
                        searchable: true,
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
            };
        }
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
