<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta charset="utf-8" />
    <title>{{ getConstant('APP_NAME') }} | {{ $title }}</title>
    <meta name="description" content="{{ $title }}" />
    <meta name="keywords" content="{{ $title }}" />
    <link rel="canonical" href="{{ URL::to('/') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/config_favicons/' . getFavicon()) }}">
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('metronic/') }}/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/') }}/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}custom/custom.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="bg-body">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            style="background-size1: 100% 50%; background-image: url({{ asset('metronic/') }}/media/misc/development-hd.png)">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">

                <!--begin::Wrapper-->
                <div class="w-lg-800px bg-white rounded shadow-lg p-10 p-lg-15 mx-auto">
                    <!--begin::Form-->
                    <form class="form w-100" method="POST" action="{{ route('payments.store') }}">
                        @csrf
                        <input type="hidden" name="encrypted_id" value="{{ $encrypted_id }}">
                        <!--begin::Heading-->
                        <div class="text-start mb-10">
                            <!--begin::Title-->
                            <h1 class="text-dark mb-3">Payment Details</h1>
                            <!--end::Title-->
                        </div>

                        @if ($errors->any())
                            <div class="row">
                                <div class="col-md-12">
                                    <div
                                        class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row w-100 p-5">
                                        <!--begin::Icon-->
                                        <!--begin::Svg Icon | path: icons/duotone/Interface/Comment.svg-->
                                        <span class="svg-icon svg-icon-2hx svg-icon-danger me-4 mb-5 mb-sm-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
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

                        @if (session('success'))
                            <div class="row">
                                <div class="col-md-12">
                                    <div
                                        class="alert alert-dismissible bg-light-success d-flex flex-column flex-sm-row w-100 p-5">
                                        <!--begin::Icon-->
                                        <!--begin::Svg Icon | path: icons/duotone/Interface/Comment.svg-->
                                        <span class="svg-icon svg-icon-2hx svg-icon-success me-4 mb-5 mb-sm-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
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

                        <!--begin::Input group-->
                        <div class="row mb-10">
                            <!--begin::Col-->
                            <div class="col-md-12 fv-row mb-10">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-bold form-label mb-2">Payment</label>
                                <!--end::Label-->
                                <!--begin::Row-->
                                <div class="row fv-row d-flex">
                                    @php
                                        $starting_amount = 50;
                                        $counter = 0;
                                    @endphp
                                    @while ($starting_amount < $order_total)
                                        <div class="col-md-2 mb-5">
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input class="form-check-input" @if ($counter == 0) required @endif @if (old('paid_amount') == $starting_amount) checked @endif type="radio"
                                                    name="paid_amount" value="{{ $starting_amount }}"
                                                    id="{{ $starting_amount }}" />
                                                <label class="form-check-label"
                                                    for="{{ $starting_amount }}">${{ setDefaultPriceFormat($starting_amount) }}</label>
                                            </div>
                                        </div>
                                        @php
                                            $starting_amount += $starting_amount;
                                            $counter += 1;
                                        @endphp
                                    @endwhile
                                    <div class="col-md-2">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" name="paid_amount"
                                                value="{{ $order_total }}" id="{{ $order_total }}"
                                                @if (old('paid_amount') == $order_total) checked @endif />
                                            <label class="form-check-label"
                                                for="{{ $order_total }}">${{ setDefaultPriceFormat($order_total) }}</label>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Row-->

                                @error('paid_amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required">Name On Card</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                        title="Specify a card holder's name"></i>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control form-control-solid" placeholder="Name On Card"
                                    name="card_name" value="{{ old('card_name') }}" required />

                                @error('card_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-bold form-label mb-2">Card Number</label>
                                <!--end::Label-->
                                <!--begin::Input wrapper-->
                                <div class="position-relative">
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" placeholder="Card Number"
                                        name="card_number" value="{{ old('card_number') }}" required />
                                    <!--end::Input-->
                                    <!--begin::Card logos-->
                                    <div class="position-absolute translate-middle-y top-50 end-0 me-5">
                                        <img src="{{ asset('metronic/') }}/media/svg/card-logos/visa.svg" alt=""
                                            class="h-25px" />
                                        <img src="{{ asset('metronic/') }}/media/svg/card-logos/mastercard.svg" alt=""
                                            class="h-25px" />
                                        <img src="{{ asset('metronic/') }}/media/svg/card-logos/american-express.svg"
                                            alt="" class="h-25px" />
                                    </div>
                                    <!--end::Card logos-->
                                </div>
                                <!--end::Input wrapper-->
                                @error('card_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-10">
                                <!--begin::Col-->
                                <div class="col-md-8">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-bold form-label mb-2">Expiration Date</label>
                                    <!--end::Label-->
                                    <!--begin::Row-->
                                    <div class="row fv-row">
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <select name="card_exp_month" id="card_exp_month"
                                                class="form-select form-select-solid" required>
                                            </select>

                                            @error('card_exp_month')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <select name="card_exp_year" id="card_exp_year"
                                                class="form-select form-select-solid" required>
                                            </select>

                                            @error('card_exp_year')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-4">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">CVV</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                            title="Enter a card CVV code"></i>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input wrapper-->
                                    <div class="position-relative">
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid" minlength="3"
                                            maxlength="4" placeholder="CVV" name="card_cvv"
                                            value="{{ old('card_cvv') }}" required />
                                        <!--end::Input-->
                                        <!--begin::CVV icon-->
                                        <div class="position-absolute translate-middle-y top-50 end-0 me-3">
                                            <!--begin::Svg Icon | path: icons/duotune/finance/fin002.svg-->
                                            <span class="svg-icon svg-icon-2hx">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M22 7H2V11H22V7Z" fill="black" />
                                                    <path opacity="0.3"
                                                        d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19ZM14 14C14 13.4 13.6 13 13 13H5C4.4 13 4 13.4 4 14C4 14.6 4.4 15 5 15H13C13.6 15 14 14.6 14 14ZM16 15.5C16 16.3 16.7 17 17.5 17H18.5C19.3 17 20 16.3 20 15.5C20 14.7 19.3 14 18.5 14H17.5C16.7 14 16 14.7 16 15.5Z"
                                                        fill="black" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </div>
                                        <!--end::CVV icon-->
                                    </div>
                                    <!--end::Input wrapper-->

                                    @error('card_cvv')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <div class="d-flex flex-column fv-row">

                                <div class="col-md-12 text-end">
                                    <div class="form-check form-check-custom form-check-solid d-inline-block me-5">
                                        <input class="form-check-input" type="checkbox" id="terms-and-conditions"
                                            name="terms-and-conditions" value="1" required />
                                        <label class="form-check-label" for="terms-and-conditions">
                                            I have read and agree to the <a class="text-primary"
                                                style="cursor: pointer;" type="button" data-bs-toggle="modal"
                                                data-bs-target="#termsAndConditions">Terms &
                                                Conditions</a>
                                        </label>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--end::Input group-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->

            <div class="modal fade" tabindex="-1" id="termsAndConditions" role="dialog"
                aria-labelledby="termsAndConditionsLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="termsAndConditionsLabel">
                                {{ getCmsContent('terms-and-conditions')['title'] }}</h5>

                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                                aria-label="Close">
                                <span class="svg-icon svg-icon-2x">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
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
                            {!! getCmsContent('terms-and-conditions')['content'] !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Main-->
        <!--begin::Javascript-->
        <!--begin::Global Javascript Bundle(used by all pages)-->
        <script src="{{ asset('metronic/') }}/plugins/global/plugins.bundle.js"></script>
        <script src="{{ asset('metronic/') }}/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Page Custom Javascript(used by this page)-->
        <script src="{{ asset('metronic/') }}/js/custom/authentication/sign-in/general.js"></script>
        <!--end::Page Custom Javascript-->
        <!--begin::Common(used by all page)-->
        <script src="{{ asset('/') }}custom/common.js"></script>
        <!--end::Common-->
        <script>
            $(document).ready(function() {
                generateCreditCardExpiration();
                let current_year = new Date().getFullYear();
                let selected_year = $('#card_exp_year').val();
                let current_month = new Date().getMonth() + 1;
                disableMonthOption();


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
        </script>
        <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
