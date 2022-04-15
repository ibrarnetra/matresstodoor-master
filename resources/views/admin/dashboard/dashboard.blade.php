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
            <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Dashboard
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                <!--end::Separator-->
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
        {{-- filters --}}
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">Filters
                            <div class="spinner-border spinner-border-sm text-dark ms-5 d-none custom-loader" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </h2>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm custom-date-picker" id="from" placeholder="From" aria-label="From" autocomplete="off">
                                    <span class="input-group-text">-</span>
                                    <input type="text" class="form-control form-control-sm custom-date-picker" id="to" placeholder="To" aria-label="To" autocomplete="off">
                                </div>
                            </div>

                            @if (Auth::guard('web')->user()->hasRole("Super Admin") ||
                            Auth::guard('web')->user()->hasRole("Office Admin"))
                            <div class="col-md-4">
                                <select id="sales-rep" class="form-select form-select-solid">
                                    <option value="-1" selected>All Sales Rep</option>
                                    @if (count($sales_reps) > 0)
                                    @foreach ($sales_reps as $sales_rep)
                                    <option value="{{$sales_rep->id}}">{{$sales_rep->first_name . " ". $sales_rep->last_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @endif

                            @if (Auth::guard('web')->user()->hasRole("Super Admin") ||
                            Auth::guard('web')->user()->hasRole("Office Admin") ||
                            Auth::guard('web')->user()->hasRole("Delivery Manager"))
                            <div class="col-md-4">
                                <select id="delivery-rep" class="form-select form-select-solid">
                                    <option value="-1" selected>All Delivery Rep</option>
                                    @if (count($delivery_reps) > 0)
                                    @foreach ($delivery_reps as $delivery_rep)
                                    <option value="{{$delivery_rep->id}}">{{$delivery_rep->first_name . " ". $delivery_rep->last_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @endif
                        </div>
                        <div class="row mt-6">
                            @if (Auth::guard('web')->user()->hasRole("Super Admin") ||
                            Auth::guard('web')->user()->hasRole("Office Admin") ||
                            Auth::guard('web')->user()->hasRole("Delivery Manager"))
                            <div class="col-md-4">
                                <select id="country-id" class="form-select form-select-solid">
                                    <option value="-1" selected>All Country</option>
                                    @if (count($countries) > 0)
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id}}">{{ $country->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @endif
                            <div class="col text-end">
                                <button class="btn btn-sm btn-info" onclick="applyFilters()">Filter</button>
                                <button class="btn btn-sm btn-primary" onclick="clearFilters()">Clear</button>
                            </div>
                        </div>
                        

                    </div>
                </div>
            </div>
        </div>

        {{-- payment link summary --}}
        {{-- <div class="row mb-5">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Payment Link Summary By Store</h5>
                    </div>
                </div>
            </div>
        </div>

        <div id="payment_link_summary_stats"></div> --}}

        {{-- store sale summary --}}
        @if (Auth::guard('web')->user()->hasRole("Super Admin"))
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Sale Summary</h5>
                    </div>
                </div>
            </div>
        </div>

        <div id="total_sale_summary"></div>

        @endif
        @if (Auth::guard('web')->user()->hasRole("Super Admin")  )
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Store Sale Summary</h5>
                    </div>
                </div>
            </div>
        </div>

        <div id="store_sale_summary_stats"></div>

        {{-- route summary --}}
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Route Summary Report</h5>
                    </div>
                </div>
            </div>
        </div>

        <div id="route_summary_stats"></div>
        @endif
        @if (Auth::guard('web')->user()->hasRole("Super Admin") ||
        Auth::guard('web')->user()->hasRole("Office Admin") || Auth::guard('web')->user()->hasRole("Dispatch Manager") )

         {{-- route summary --}}
        

        <div id="product_inventory_summaries"></div>
        @endcan

        {{-- sale rep report --}}
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Sale Rep Report</h5>
                    </div>
                </div>
            </div>
        </div>

        <div id="sales_rep_stats"></div>
    </div>
</div>

<input type="hidden" id="dashboard-ajax-url" value="{{route('dashboard.getData')}}">
<!--end::Post-->
@endsection

@push('page_lvl_js')
<script>
    customDatePickerConfig = {
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: true,
        autoApply: true,
        startDate: dateYesterday,
        locale: {
            format: "YYYY-MM-DD",
            separator: "-",
        },
    }

    $(document).ready(function () {
        ajaxLoadDashData($("#dashboard-ajax-url").val());
    });

    function clearFilters() {
        $(".custom-date-picker").val("");
        $("#sales-rep").val($("#sales-rep option:first").val());
        $("#team-member").val($("#team-member option:first").val());
        $("#country-id").val($("#country-id option:first").val());
        ajaxLoadDashData($("#dashboard-ajax-url").val());
    }
    
    function applyFilters() {
        ajaxLoadDashData($("#dashboard-ajax-url").val());
    }

    function ajaxLoadDashData(url){
        let date_range = "-1";
        let sales_rep_id = $("#sales-rep option:selected").val();
        let delivery_rep_id = $("#delivery-rep option:selected").val();
        let team_member_id = $("#team-member option:selected").val();
        let country_id = $("#country-id option:selected").val();
        let sDate =
            $("#from").val() !== undefined && $("#from").val() !== ""
                ? $("#from").val()
                : "-1";
        let eDate =
            $("#to").val() !== undefined && $("#to").val() !== ""
                ? $("#to").val()
                : "-1";
        // DEFINE DATE RANGE
        if (sDate !== "-1" && eDate !== "-1") {
            date_range = sDate + " to " + eDate;
        }

        $(".custom-loader").removeClass("d-none");
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: CSRF_TOKEN,
                date_range: date_range,
                sales_rep_id: sales_rep_id,
                team_member_id: team_member_id,
                delivery_rep_id: delivery_rep_id,
                country_id: country_id
            },
            dataType: "JSON",
            success: function (res) {
                toastr.success("Success", "");

                $("#sales_rep_stats").html(res.data.sale_rep_stats);
                $("#route_summary_stats").html(res.data.route_summary_stats);
                $("#store_sale_summary_stats").html(res.data.store_sale_summary_stats);
                $("#product_inventory_summaries").html(res.data.product_option_quantity_summaries);
                $("#payment_link_summary_stats").html(res.data.payment_link_summary_stats);
                $("#total_sale_summary").html(res.data.total_sale_rep_stats);

                $(".custom-loader").addClass("d-none");
            },
            error: function (err) {
                console.log("🚀 ~ file: dashboard.blade.php ~ line 189 ~ ajaxLoadDashData ~ err", err)
            }
        });
    }
    function orderDetail(id)
    {
        let date_range = "-1";
        let sDate =
            $("#from").val() !== undefined && $("#from").val() !== ""
                ? $("#from").val()
                : "-1";
        let eDate =
            $("#to").val() !== undefined && $("#to").val() !== ""
                ? $("#to").val()
                : "-1";
        // DEFINE DATE RANGE
        if (sDate !== "-1" && eDate !== "-1") {
            date_range = sDate + " to " + eDate;
        }
        let storeId = id;
        let route = "{{route('orders.index')}}"+"?store_id="+storeId+"&&order_date="+date_range ;
        window.location = route;
     
    }
</script>
@endpush