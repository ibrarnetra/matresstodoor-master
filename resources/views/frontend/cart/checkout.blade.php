@extends('frontend.master')

@section('meta')
    @include('frontend.common.meta')
@endsection

@section('content')
    @push('page_lvl_css')
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
        <link rel="stylesheet" type="text/css" href="assets/css/demo.css">
    @endpush
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}"><i class="icon-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ strtoupper($title) }}
                </li>
            </ol>
        </div><!-- End .container -->
    </nav>

    <div class="container mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <h1>{{ $title }}</h1>
                </div>
            </div>
        </div>

        @if (isset($cart) && count($cart) > 0)
            @if (session('error'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! implode('', $errors->all('<span>:message</span>')) !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="accordion" id="accordionExample">
                                {{-- CHECKOUT OPTIONS --}}
                                <div class="card m-0">
                                    <div class="card-header" id="checkout-options-heading">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link accordion-heading-font-size p-0" type="button"
                                                data-toggle="collapse" data-target="#checkout-options" aria-expanded="true"
                                                aria-controls="checkout-options" @auth('frontend') disabled @endauth>
                                                Step 1: Checkout Options
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="checkout-options" data-step="1" @guest('frontend') class="collapse show"
                                            @endguest @auth('frontend') class="collapse disabled" @endauth
                                            aria-labelledby="checkout-options-heading" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <h3>New Customer</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="">Checkout Options</label>
                                                                    @auth('frontend')
                                                                        <input type="hidden" name="account_type" value="auth"
                                                                            form="checkout-form">
                                                                    @endauth
                                                                    <div class="custom-control custom-radio m-3">
                                                                        <input class="custom-control-input" type="radio"
                                                                            onclick="toggleRegisterGuest()" name="account_type"
                                                                            value="register" id="registered-user"
                                                                            @guest('frontend') required @endguest
                                                                            form="checkout-form">
                                                                        <label class="custom-control-label"
                                                                            for="registered-user">
                                                                            Register Account
                                                                        </label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio m-3">
                                                                        <input class="custom-control-input" type="radio"
                                                                            onclick="toggleRegisterGuest()" name="account_type"
                                                                            id="guest-user" value="guest" @guest('frontend')
                                                                            checked @endguest form="checkout-form">
                                                                        <label class="custom-control-label" for="guest-user">
                                                                            Guest Checkout
                                                                        </label>
                                                                    </div>
                                                                    <p>By creating an account you will be able to shop faster,
                                                                        be up to date on an order's status, and keep track of
                                                                        the orders you have previously made.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <button class="btn btn-primary" type="button"
                                                                    onclick="moveToNextStep(this, 1)">Continue</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <h3>Returning Customer</h3>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="email">E-mail</label>
                                                                    <input class="form-control my-control" type="email"
                                                                        name="email" form="login-form">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="password">Password</label>
                                                                    <input class="form-control my-control" type="password"
                                                                        name="password" form="login-form">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group d-flex justify-content-between">
                                                                    <a href="javascript:void(0);">Forgot Password?</a>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        form="login-form">Login</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- BILLING DETAILS --}}
                                    <div class="card m-0">
                                        <div class="card-header" id="billing-detail-heading">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link accordion-heading-font-size p-0" type="button"
                                                    data-toggle="collapse" data-target="#billing-detail" aria-expanded="true"
                                                    aria-controls="billing-detail" @guest('frontend') disabled @endguest>
                                                    Step 2: Billing Details
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="billing-detail" data-step="2" @guest('frontend') class="collapse"
                                                @endguest @auth('frontend') class="collapse show" @endauth
                                                aria-labelledby="billing-detail-heading" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div @guest('frontend') class="row" @endguest @auth('frontend')
                                                    class="row d-none" @endauth id="guest-shipping">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <h3>Your Personal Details</h3>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group required-field">
                                                                    <label for="first_name">First Name</label>
                                                                    <input class="form-control my-control" type="text"
                                                                        name="first_name" id="first_name"
                                                                        onchange="fillDeliveryAddress(this)"
                                                                        onkeyup="fillDeliveryAddress(this)"
                                                                        placeholder="First Name" value="{{ old('first_name') }}"
                                                                        @guest('frontend') required form="checkout-form"
                                                                        @endguest data-error="#first_name_error">
                                                                </div>
                                                                <small class="text-red" id="first_name_error"></small>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group required-field">
                                                                    <label for="last_name">Last Name</label>
                                                                    <input class="form-control my-control" type="text"
                                                                        name="last_name" id="last_name"
                                                                        onchange="fillDeliveryAddress(this)"
                                                                        onkeyup="fillDeliveryAddress(this)"
                                                                        placeholder="Last Name" value="{{ old('last_name') }}"
                                                                        @guest('frontend') required form="checkout-form"
                                                                        @endguest>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group required-field">
                                                                    <label for="email">E-Mail</label>
                                                                    <input class="form-control my-control" type="email"
                                                                        name="email" id="email" placeholder="E-Mail"
                                                                        value="{{ old('email') }}" @guest('frontend') required
                                                                        form="checkout-form" @endguest>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group required-field">
                                                                    <label for="telephone">Mobile (xxx-xxx-xxxx)</label>
                                                                    <input class="form-control my-control" type="tel"
                                                                        name="telephone" id="telephone"
                                                                        placeholder="xxx-xxx-xxxx"
                                                                        pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                                                        value="{{ old('telephone') }}" @guest('frontend')
                                                                        required form="checkout-form" @endguest
                                                                        onblur="autofillAddressMobile(this)">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="register-account-div" class="d-none">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <h3>Your Password</h3>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group required-field">
                                                                        <label for="register_password">Password</label>
                                                                        <input class="form-control my-control" type="password"
                                                                            name="password" id="register_password"
                                                                            placeholder="Password"
                                                                            value="{{ old('password') }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group required-field">
                                                                        <label for="register_password_confirmation">Password
                                                                            Confirm</label>
                                                                        <input class="form-control my-control" type="password"
                                                                            name="password_confirmation"
                                                                            id="register_password_confirmation"
                                                                            placeholder="Password Confirm">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="same-as-billing" value="true" checked />
                                                                    <label class="form-check-label ml-2 mr-5"
                                                                        for="same-as-billing">
                                                                        My delivery and billing addresses are the same.
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <h3>Your Address</h3>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="telephone">Mobile (xxx-xxx-xxxx)</label>
                                                                    <input type="tel" placeholder="xxx-xxx-xxxx"
                                                                        pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                                                        class="form-control my-control" name="telephone"
                                                                        id="address_telephone"
                                                                        onchange="fillDeliveryAddress(this)"
                                                                        onblur="fillDeliveryAddress(this)"
                                                                        value="{{ old('telephone') }}" @guest('frontend')
                                                                        form="checkout-form" @endguest autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input type="hidden" name="lat" id="lat" @guest('frontend')
                                                                    form="checkout-form" @endguest>
                                                                <input type="hidden" name="lng" id="lng" @guest('frontend')
                                                                    form="checkout-form" @endguest>
                                                                <div class="form-group required-field">
                                                                    <label for="address_1">Address 1</label>
                                                                    <input class="form-control my-control" type="text"
                                                                        name="address_1" id="address_1"
                                                                        onchange="fillDeliveryAddress(this)"
                                                                        onkeyup="fillDeliveryAddress(this)"
                                                                        placeholder="Address 1" value="{{ old('address_1') }}"
                                                                        @guest('frontend') required form="checkout-form"
                                                                        @endguest autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="address_2">Address 2</label>
                                                                    <input class="form-control my-control" type="text"
                                                                        name="address_2" id="address_2"
                                                                        onchange="fillDeliveryAddress(this)"
                                                                        onkeyup="fillDeliveryAddress(this)"
                                                                        placeholder="Address 2" value="{{ old('address_2') }}"
                                                                        @guest('frontend') form="checkout-form" @endguest>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group required-field">
                                                                    <label for="city">City</label>
                                                                    <input class="form-control my-control" type="text"
                                                                        name="city" id="city"
                                                                        onchange="fillDeliveryAddress(this)"
                                                                        onkeyup="fillDeliveryAddress(this)" placeholder="City"
                                                                        value="{{ old('city') }}" @guest('frontend') required
                                                                        form="checkout-form" @endguest>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group required-field">
                                                                    <label for="postcode">Postcode</label>
                                                                    <input class="form-control my-control" type="text"
                                                                        name="postcode" id="postcode"
                                                                        onchange="fillDeliveryAddress(this)"
                                                                        onkeyup="fillDeliveryAddress(this)"
                                                                        placeholder="Post Code" value="{{ old('postcode') }}"
                                                                        @guest('frontend') form="checkout-form" required
                                                                        @endguest>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group required-field">
                                                                    <label for="country_id">Country</label>
                                                                    <select class="form-control my-control country"
                                                                        onchange="getZones(this, '{{ route('zones.getZones') }}')"
                                                                        name="country_id" id="country_id" @guest('frontend')
                                                                        required form="checkout-form" @endguest>
                                                                        <option value="" disabled selected>-- Select Country --
                                                                        </option>
                                                                        @if (count($countries) > 0)
                                                                            @foreach ($countries as $country)
                                                                                <option value="{{ $country->id }}"
                                                                                    @if ($country->name == 'Canada')
                                                                                    selected
                                                                            @endif>
                                                                            {!! $country->name !!}
                                                                            </option>
                                                                        @endforeach
            @endif
            </select>
        </div>
        </div>
        <div class="col-md-12">
            <div class="form-group required-field">
                <label for="zone_id">Region / State</label>
                <select class="form-control my-control zone" name="zone_id" id="zone_id" onchange="fillDeliveryAddress(this)"
                    @guest('frontend') required form="checkout-form" @endguest>
                    <option value="" disabled selected>-- Select State --</option>
                    @if (count($zones) > 0)
                        @foreach ($zones as $zone)
                            <option value="{{ $zone->id }}">
                                {!! $zone->name !!}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        </div>

        </div>
        </div>
        <div @guest('frontend') class="row d-none" @endguest @auth('frontend') class="row" @endauth
            id="auth-shipping">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="custom-control custom-radio m-0">
                                <input class="custom-control-input" type="radio" id="billing-existing-address"
                                    name="billing_address_selection" onclick="toggleExistingNewAddress('billing')"
                                    value="existing" checked @auth('frontend') form="checkout-form" @endauth>
                                <label class="custom-control-label" for="billing-existing-address">
                                    I want to use an existing address
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="billing-existing-address-div">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <select class="form-control my-control" name="auth_billing_shipping_address"
                                    id="auth_billing_shipping_address">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="custom-control custom-radio m-0">
                                <input class="custom-control-input" type="radio" id="billing-new-address"
                                    name="billing_address_selection" onclick="toggleExistingNewAddress('billing')" value="new"
                                    @auth('frontend') form="checkout-form" @endauth>
                                <label class="custom-control-label" for="billing-new-address">
                                    I want to use a new address
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="billing-new-address-div" class="d-none">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required-field">
                                <label class="form-label" for="auth_billing_first_name">First Name</label>
                                <input type="text" class="form-control my-control" id="auth_billing_first_name"
                                    name="auth_billing_first_name" value="{{ old('auth_billing_first_name') }}"
                                    placeholder="First Name" @auth('frontend') required form="checkout-form" @endauth>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group required-field">
                                <label class="form-label" for="auth_billing_last_name">Last Name</label>
                                <input type="text" class="form-control my-control" id="auth_billing_last_name"
                                    name="auth_billing_last_name" value="{{ old('auth_billing_last_name') }}"
                                    placeholder="Last Name" @auth('frontend') required form="checkout-form" @endauth>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="auth_billing_telephone">Mobile (xxx-xxx-xxxx)</label>
                                <input type="tel" placeholder="xxx-xxx-xxxx" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                    class="form-control my-control" name="auth_billing_telephone" id="auth_billing_telephone"
                                    value="{{ old('auth_billing_telephone') }}" @auth('frontend') form="checkout-form" @endauth
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="auth_billing_lat" id="auth_billing_lat" @auth('frontend')
                                form="checkout-form" @endauth>
                            <input type="hidden" name="auth_billing_lng" id="auth_billing_lng" @auth('frontend')
                                form="checkout-form" @endauth>
                            <div class="form-group required-field">
                                <label class="form-label" for="auth_billing_address_1">Address 1 </label>
                                <input type="text" id="auth_billing_address_1" class="form-control my-control"
                                    name="auth_billing_address_1" value="{{ old('auth_billing_address_1') }}"
                                    placeholder="Address 1" @auth('frontend') required form="checkout-form" @endauth
                                    autocomplete="off">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="auth_billing_address_2">Address 2 </label>
                                <input type="text" id="auth_billing_address_2" class="form-control my-control"
                                    name="auth_billing_address_2" value="{{ old('auth_billing_address_2') }}"
                                    placeholder="Address 2" @auth('frontend') form="checkout-form" @endauth>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required-field">
                                <label class="form-label" for="auth_billing_city">City </label>
                                <input type="text" class="form-control my-control" name="auth_billing_city"
                                    id="auth_billing_city" value="{{ old('auth_billing_city') }}" placeholder="City"
                                    @auth('frontend') required form="checkout-form" @endauth>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required-field">
                                <label class="form-label" for="auth_billing_postcode">Postcode </label>
                                <input type="text" class="form-control my-control" name="auth_billing_postcode"
                                    id="auth_billing_postcode" value="{{ old('auth_billing_postcode') }}"
                                    placeholder="Postcode" @auth('frontend') form="checkout-form" required @endauth>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group required-field">
                                <label class="form-label" for="auth_billing_country_id">Country </label>
                                <select class="form-select my-control country" name="auth_billing_country_id"
                                    id="auth_billing_country_id" style="width: 100%;" data-zone="0"
                                    onchange="getZones(this, '{{ route('zones.getZones') }}')" @auth('frontend') required
                                    form="checkout-form" @endauth>
                                    <option value="" disabled selected>-- Select Country --</option>
                                    @if (count($countries) > 0)
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" @if ($country->name == 'Canada')
                                                selected
                                        @endif>
                                        {!! $country->name !!}
                                        </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group required-field">
                                <label class="form-label" for="auth_billing_zone_id">Region / State </label>
                                <select class="form-select my-control zone" name="auth_billing_zone_id"
                                    id="auth_billing_zone_id" style="width: 100%;" @auth('frontend') required
                                    form="checkout-form" @endauth>
                                    <option value="" disabled selected>-- Select State --</option>
                                    @if (count($zones) > 0)
                                        @foreach ($zones as $zone)
                                            <option value="{{ $zone->id }}">
                                                {!! $zone->name !!}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-right">
                <div class="form-check">
                    <span class="d-none" id="privacy-div">
                        <input class="form-check-input" style="margin-top: 14px;" type="checkbox" id="privacy-policy"
                            name="privacy-policy" value="1" form="checkout-form" />
                        <label class="form-check-label ml-2 mr-5" for="privacy-policy">
                            I have read and agree to the <a class="text-primary" style="cursor: pointer;" type="button"
                                data-toggle="modal" data-target="#privacyPolicy">Privacy
                                Policy</a>
                        </label>
                    </span>
                    {{-- @guest('frontend') <button type="button" class="btn btn-primary" onclick="moveToPreviousStep(this, 1)">Previous</button> @endguest --}}
                    <button type="button" class="btn btn-primary" onclick="moveToNextStep(this, 2)">Continue</button>
                </div>
            </div>
        </div>
        </div>
        </div>
        </div>

        {{-- DELIVEREY ADDRESS --}}
        <div class="card m-0">
            <div class="card-header" id="delivery-addresses-heading">
                <h5 class="mb-0">
                    <button type="button" class="btn btn-link accordion-heading-font-size p-0" type="button"
                        data-toggle="collapse" data-target="#delivery-addresses" aria-expanded="true"
                        aria-controls="delivery-addresses" disabled>
                        Step 3: Delivery Addresses
                    </button>
                </h5>
            </div>

            <div id="delivery-addresses" data-step="3" class="collapse" aria-labelledby="delivery-addresses-heading"
                data-parent="#accordionExample">
                <div class="card-body">
                    <div @guest('frontend') class="row" @endguest @auth('frontend') class="row d-none"
                    @endauth>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="delivery_first_name">First Name</label>
                                    <input type="text" class="form-control my-control" id="delivery_first_name"
                                        name="delivery_first_name" value="{{ old('delivery_first_name') }}"
                                        placeholder="First Name" @guest('frontend') required form="checkout-form" @endguest>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="delivery_last_name">Last Name</label>
                                    <input type="text" class="form-control my-control" id="delivery_last_name"
                                        name="delivery_last_name" value="{{ old('delivery_last_name') }}"
                                        placeholder="Last Name" @guest('frontend') required form="checkout-form" @endguest>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="delivery_telephone">Mobile (xxx-xxx-xxxx)</label>
                                    <input type="tel" placeholder="xxx-xxx-xxxx" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                        class="form-control my-control" id="delivery_telephone" name="delivery_telephone"
                                        value="{{ old('delivery_telephone') }}" @guest('frontend') form="checkout-form"
                                        @endguest>
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="delivery_lat" id="delivery_lat" @guest('frontend')
                                    form="checkout-form" @endguest>
                                <input type="hidden" name="delivery_lng" id="delivery_lng" @guest('frontend')
                                    form="checkout-form" @endguest>
                                <div class="form-group required-field">
                                    <label class="form-label" for="delivery_address_1">Address 1 </label>
                                    <input type="text" id="delivery_address_1" class="form-control my-control"
                                        name="delivery_address_1" value="{{ old('delivery_address_1') }}"
                                        placeholder="Address 1" @guest('frontend') required form="checkout-form" @endguest
                                        autocomplete="off">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="delivery_address_2">Address 2 </label>
                                    <input type="text" id="delivery_address_2" class="form-control my-control"
                                        name="delivery_address_2" value="{{ old('delivery_address_2') }}"
                                        placeholder="Address 2" @guest('frontend') form="checkout-form" @endguest>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="delivery_city">City </label>
                                    <input type="text" class="form-control my-control" name="delivery_city"
                                        id="delivery_city" value="{{ old('delivery_city') }}" placeholder="City"
                                        @guest('frontend') required form="checkout-form" @endguest>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="delivery_postcode">Postcode </label>
                                    <input type="text" class="form-control my-control" name="delivery_postcode"
                                        id="delivery_postcode" value="{{ old('delivery_postcode') }}"
                                        placeholder="Postcode" @guest('frontend') form="checkout-form" required @endguest>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="delivery_country_id">Country </label>
                                    <select class="form-select my-control country" name="delivery_country_id"
                                        id="delivery_country_id" style="width: 100%;" data-zone="0"
                                        onchange="getZones(this, '{{ route('zones.getZones') }}')" @guest('frontend')
                                        required form="checkout-form" @endguest>
                                        <option value="" disabled selected>-- Select Country --</option>
                                        @if (count($countries) > 0)
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}" @if ($country->name == 'Canada')
                                                    selected
                                            @endif>
                                            {!! $country->name !!}
                                            </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="delivery_zone_id">Region / State </label>
                                    <select class="form-select my-control zone" name="delivery_zone_id"
                                        id="delivery_zone_id" style="width: 100%;" @guest('frontend') required
                                        form="checkout-form" @endguest>
                                        <option value="" disabled selected>-- Select State --</option>
                                        @if (count($zones) > 0)
                                            @foreach ($zones as $zone)
                                                <option value="{{ $zone->id }}">
                                                    {!! $zone->name !!}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div @guest('frontend') class="row d-none" @endguest @auth('frontend') class="row"
                @endauth>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="custom-control custom-radio m-0">
                                    <input class="custom-control-input" type="radio" id="delivery-existing-address"
                                        name="delivery_address_selection" onclick="toggleExistingNewAddress('delivery')"
                                        value="existing" checked @auth('frontend') form="checkout-form" @endauth>
                                    <label class="custom-control-label" for="delivery-existing-address">
                                        I want to use an existing address
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="delivery-existing-address-div">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control my-control" name="auth_delivery_shipping_address"
                                        id="auth_delivery_shipping_address">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="custom-control custom-radio m-0">
                                    <input class="custom-control-input" type="radio" id="delivery-new-address"
                                        name="delivery_address_selection" onclick="toggleExistingNewAddress('delivery')"
                                        value="new" @auth('frontend') form="checkout-form" @endauth>
                                    <label class="custom-control-label" for="delivery-new-address">
                                        I want to use a new address
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="delivery-new-address-div" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="auth_delivery_first_name">First Name</label>
                                    <input type="text" class="form-control my-control" id="auth_delivery_first_name"
                                        name="auth_delivery_first_name" value="{{ old('auth_delivery_first_name') }}"
                                        placeholder="First Name" @auth('frontend') required form="checkout-form"
                                        @endauth>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="auth_delivery_last_name">Last Name</label>
                                    <input type="text" class="form-control my-control" id="auth_delivery_last_name"
                                        name="auth_delivery_last_name" value="{{ old('auth_delivery_last_name') }}"
                                        placeholder="Last Name" @auth('frontend') required form="checkout-form"
                                        @endauth>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="auth_delivery_telephone">Mobile
                                        (xxx-xxx-xxxx)</label>
                                    <input type="tel" placeholder="xxx-xxx-xxxx" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                        class="form-control my-control" id="auth_delivery_telephone"
                                        name="auth_delivery_telephone" value="{{ old('auth_delivery_last_name') }}"
                                        @auth('frontend') form="checkout-form" @endauth>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="auth_delivery_lat" id="auth_delivery_lat" @auth('frontend')
                                    form="checkout-form" @endauth>
                                <input type="hidden" name="auth_delivery_lng" id="auth_delivery_lng" @auth('frontend')
                                    form="checkout-form" @endauth>
                                <div class="form-group required-field">
                                    <label class="form-label" for="auth_delivery_address_1">Address 1 </label>
                                    <input type="text" id="auth_delivery_address_1" class="form-control my-control"
                                        name="auth_delivery_address_1" value="{{ old('auth_delivery_address_1') }}"
                                        placeholder="Address 1" @auth('frontend') required form="checkout-form" @endauth
                                        autocomplete="off">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="auth_delivery_address_2">Address 2 </label>
                                    <input type="text" id="auth_delivery_address_2" class="form-control my-control"
                                        name="auth_delivery_address_2" value="{{ old('auth_delivery_address_2') }}"
                                        placeholder="Address 2" @auth('frontend') form="checkout-form" @endauth>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="auth_delivery_city">City </label>
                                    <input type="text" class="form-control my-control" name="auth_delivery_city"
                                        id="auth_delivery_city" value="{{ old('auth_delivery_city') }}"
                                        placeholder="City" @auth('frontend') required form="checkout-form" @endauth>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="auth_delivery_postcode">Postcode </label>
                                    <input type="text" class="form-control my-control" name="auth_delivery_postcode"
                                        id="auth_delivery_postcode" value="{{ old('auth_delivery_postcode') }}"
                                        placeholder="Postcode" @auth('frontend') form="checkout-form" required @endauth>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="auth_delivery_country_id">Country </label>
                                    <select class="form-select my-control country" name="auth_delivery_country_id"
                                        id="auth_delivery_country_id" style="width: 100%;" data-zone="0"
                                        onchange="getZones(this, '{{ route('zones.getZones') }}')" @auth('frontend')
                                        required form="checkout-form" @endauth>
                                        <option value="" disabled selected>-- Select Country --</option>
                                        @if (count($countries) > 0)
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}" @if ($country->name == 'Canada')
                                                    selected
                                            @endif>
                                            {!! $country->name !!}
                                            </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group required-field">
                                    <label class="form-label" for="auth_delivery_zone_id">Region / State </label>
                                    <select class="form-select my-control zone" name="auth_delivery_zone_id"
                                        id="auth_delivery_zone_id" style="width: 100%;" @auth('frontend') required
                                        form="checkout-form" @endauth>
                                        <option value="" disabled selected>-- Select State --</option>
                                        @if (count($zones) > 0)
                                            @foreach ($zones as $zone)
                                                <option value="{{ $zone->id }}">
                                                    {!! $zone->name !!}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    {{-- <button type="button" class="btn btn-primary" onclick="moveToPreviousStep(this, 2)">Previous</button> --}}
                    <button type="button" class="btn btn-primary" onclick="moveToNextStep(this, 3)">Continue</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DELIVEREY METHOD --}}
<div class="card m-0">
    <div class="card-header" id="delivery-methods-heading">
        <h5 class="mb-0">
            <button type="button" class="btn btn-link accordion-heading-font-size p-0" type="button"
                data-toggle="collapse" data-target="#delivery-methods" aria-expanded="true"
                aria-controls="delivery-methods" disabled>
                Step 4: Delivery Methods
            </button>
        </h5>
    </div>

    <div id="delivery-methods" data-step="4" class="collapse" aria-labelledby="delivery-methods-heading"
        data-parent="#accordionExample">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p>Please select the preferred shipping method to use on this order.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="custom-control custom-radio m-0">
                                    <input class="custom-control-input" type="radio" name="shipping_method_id"
                                        id="flat-rate" value="1" checked form="checkout-form">
                                    <label class="custom-control-label" for="flat-rate">
                                        Flat Rate - $0.00
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    {{-- <button type="button" class="btn btn-primary" onclick="moveToPreviousStep(this, 3)">Previous</button> --}}
                    <button type="button" class="btn btn-primary" onclick="moveToNextStep(this, 4)">Continue</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- PAYMENT METHOD --}}
<div class="card m-0">
    <div class="card-header" id="payment-methods-heading">
        <h5 class="mb-0">
            <button type="button" class="btn btn-link accordion-heading-font-size p-0" type="button"
                data-toggle="collapse" data-target="#payment-methods" aria-expanded="true"
                aria-controls="payment-methods" disabled>
                Step 5: Payment Methods
            </button>
        </h5>
    </div>

    <div id="payment-methods" data-step="5" class="collapse" aria-labelledby="payment-methods-heading"
        data-parent="#accordionExample">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p>Please select the preferred payment method to use on this order.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach ($payment_methods as $k => $payment_method)
                            @if ($payment_method->code == 'COC' || $payment_method->code == 'p-link' || $payment_method->code == 'COD')
                                @continue
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="custom-control custom-radio m-0">
                                        <input class="custom-control-input payment_method_id" type="radio"
                                            name="payment_method_id" form="checkout-form"
                                            data-code="{{ $payment_method->code }}" value="{{ $payment_method->id }}"
                                            @if (old('payment_method_id'))
                                        @if (old('payment_method_id') == $payment_method->id)
                                            checked
                                        @endif
                        @endif
                        @if ($k == 0) required @endif onclick="showHideAuthorize(this)"
                        id="{{ $payment_method->eng_description->name }}">
                        <label class="custom-control-label" for="{{ $payment_method->eng_description->name }}"
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
            </div>
            @endforeach
        </div>

        <div class="d-none" id="authorize-div">
            {{-- <div class="row">
                                       <div class="col-md-6">
                                          <div class="form-group required-field">
                                             <label class="form-label">Credit Card Number</label>
                                             <input type="number" min="0" class="form-control my-control" id="card_number" name="card_number" value="{{old('card_number')}}"
                                                placeholder="Credit Card Number" form="checkout-form">
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <div class="form-group required-field">
                                             <label class="form-label">Card CVV</label>
                                             <input type="number" min="0" class="form-control my-control" id="card_cvv" name="card_cvv" value="{{old('card_cvv')}}" placeholder="Card CVV"
                                                form="checkout-form">
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <div class="form-group required-field">
                                             <label class="form-label">Card Expiration Month</label>
                                             <select class="form-control my-control" id="card_exp_month" name="card_exp_month" form="checkout-form">
                                             </select>
                                          </div>
                                       </div>

                                       <div class="col-md-6">
                                          <div class="form-group required-field">
                                             <label class="form-label">Card Expiration Year</label>
                                             <select class="form-control my-control" id="card_exp_year" name="card_exp_year" form="checkout-form">
                                             </select>
                                          </div>
                                       </div>
                                    </div> --}}
            <div class="row">
                <div class="creditCardForm">
                    <div class="payment">
                        <form>
                            <div class="form-group owner">
                                <label for="owner">Owner</label>
                                <input type="text" class="form-control" id="owner">
                            </div>
                            <div class="form-group CVV">
                                <label for="cvv">CVV</label>
                                <input type="text" class="form-control" id="cvv" name="card_cvv"
                                    value="{{ old('card_cvv') }}" placeholder="Card CVV" form="checkout-form">
                            </div>
                            <div class="form-group" id="card-number-field">
                                <label for="cardNumber">Card Number</label>
                                <input type="text" class="form-control" id="cardNumber" name="card_number"
                                    value="{{ old('card_number') }}" form="checkout-form">
                            </div>
                            <div class="form-group" id="expiration-date">
                                <label>Expiration Date</label>
                                <select id="cardExpMonth" name="card_exp_month" name="card_exp_month"
                                    form="checkout-form">
                                    <option value="01">January</option>
                                    <option value="02">February </option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                                <select id="card_exp_year" name="card_exp_year" form="checkout-form">

                                </select>
                            </div>

                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <div class="form-check">
            <input class="form-check-input" style="margin-top: 14px;" type="checkbox" id="terms-and-conditions"
                name="terms-and-conditions" value="1" form="checkout-form" required />
            <label class="form-check-label ml-2 mr-5" for="terms-and-conditions">
                I have read and agree to the <a class="text-primary" style="cursor: pointer;" type="button"
                    data-toggle="modal" data-target="#termsAndConditions">Terms &
                    Conditions</a>
            </label>
            {{-- <button type="button" class="btn btn-primary" onclick="moveToPreviousStep(this, 4)">Previous</button> --}}
            <button type="button" class="btn btn-primary" onclick="moveToNextStep(this, 5)">Continue</button>
        </div>
    </div>
</div>
</div>
</div>
</div>

{{-- CONFIRM ORDER --}}
<div class="card m-0">
    <div class="card-header" id="confirm-order-heading">
        <h5 class="mb-0">
            <button class="btn btn-link accordion-heading-font-size p-0" type="button" data-toggle="collapse"
                data-target="#confirm-order" aria-expanded="true" aria-controls="confirm-order" disabled>
                Step 6: Confirm Order
            </button>
        </h5>
    </div>

    <div id="confirm-order" data-step="6" class="collapse" aria-labelledby="confirm-order-heading"
        data-parent="#accordionExample">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 40%%;">Product</th>
                                    <th class="text-right" style="width: 20%;">Unit Price</th>
                                    <th class="text-right" style="width: 20%;">Quantity</th>
                                    <th class="text-right" style="width: 20%;">Unit Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($cart) && count($cart) > 0)
                                    @foreach ($cart as $key => $item)
                                        <tr data-id="{{ $key }}">
                                            <td style="width: 40%%; display: flex; align-items: center;">
                                                <figure class="product-image-container">
                                                    <a href="{{ route('frontend.productDetail', ['slug' => $item['slug']]) }}"
                                                        class="product-image">
                                                        <img class="img-fluid"
                                                            style="width: 40%; height: auto; margin: auto;" @if ($item['image'])
                                                        src="{{ asset('storage/product_images/thumbnail/' . $item['image']) }}"
                                                    alt="{{ $item['image'] }}" @else
                                                        src="{{ asset('frontend_assets/images/600_600.png') }}"
                                                        alt="600_600.png"
                                    @endif>
                                    </a>
                                    </figure>
                                    <h2 class="product-title">
                                        <a href="{{ route('frontend.productDetail', ['slug' => $item['slug']]) }}">
                                            {{ $item['name'] }}
                                        </a>
                                        @if (count($item['option_arr']) > 0)
                                            @foreach ($item['option_arr'] as $option)
                                                @foreach ($option->product_option_values as $option_value)
                                                    <br>
                                                    <small>- {{ $option->eng_description->name }}:
                                                        {{ $option_value->eng_description->name }}</small>
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </h2>
                                    </td>
                                    <td class="text-right" style="width: 20%;">$<span
                                            id="product-price-{{ $key }}">{{ setDefaultPriceFormat($item['price']) }}</span>
                                    </td>
                                    <td class="text-right" style="width: 20%;">
                                        {{ $item['quantity'] }}
                                    </td>
                                    <td class="text-right" style="width: 20%;">$<span
                                            id="product-sub-total-{{ $key }}">{{ setDefaultPriceFormat($item['price'] * $item['quantity']) }}</span>
                                    </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <th scope="row" colspan="4" class="text-center">No Data found...</th>
                                </tr>
                                @endif
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th scope="row" colspan="3" class="text-right">
                                        Sub-Total:
                                    </th>
                                    <th scope="row" class="text-right">$<span
                                            id="order-sub-total">{{ setDefaultPriceFormat($order_total) }}</span></th>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="3" class="text-right">
                                        Tax:
                                    </th>
                                    <th scope="row" class="text-right">$<span id="order-tax">0.00</span></th>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="3" class="text-right">
                                        Grand-Total:
                                    </th>
                                    <th scope="row" class="text-right">$<span
                                            id="order-grand-total">{{ setDefaultPriceFormat($order_total) }}</span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    {{-- <button type="button" class="btn btn-primary" onclick="moveToPreviousStep(this, 5)">Previous</button> --}}
                    <button class="btn btn-primary" type="submit" id="confirm-order" form="checkout-form">Confirm
                        Order</button>
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

<form method="post" action="{{ route('frontend.checkout') }}" id="checkout-form" autocomplete="off">
    @csrf
</form>
<form method="post" action="{{ route('frontend.handleSignIn') }}" id="login-form" autocomplete="off">
    @csrf
</form>
@else
<div class="row">
    <div class="col-md-12">
        <a href="{{ route('frontend.shop') }}" class="btn btn-outline-secondary">Continue Shopping</a>
    </div>
</div>
@endif

<div class="modal fade" id="termsAndConditions" tabindex="-1" role="dialog"
    aria-labelledby="termsAndConditionsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header p-0 p-3 custom-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! getCmsContent('terms-and-conditions')['content'] !!}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="privacyPolicy" tabindex="-1" role="dialog" aria-labelledby="privacyPolicyLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header p-0 p-3 custom-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! getCmsContent('privacy-policy')['content'] !!}
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="auth_type" @auth('frontend') value="auth" @endauth @guest('frontend') value="guest" @endguest>
</div>
@endsection

@push('page_lvl_js')
<script src="{{ asset('/') }}places/index.js"></script>
<script src="{{ asset('frontend_assets/') }}/custom/checkout.js"></script>


<script src="{{ asset('assets/js/script.js') }}"></script>
<script>
    $(document).ready(function() {
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
                    $('#cardExpMonth option[value="' + value + '"]').attr("disabled", false);
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
                    $('#cardExpMonth option[value="' + value + '"]').attr("disabled", true);
                }

            }
            if (current_month < 10) {
                current_month = "0" + current_month;
            }
            $('#cardExpMonth option[value="' + current_month + '"]').attr('selected', 'selected');


        }


    });
    @auth('frontend')
        $(document).ready(function () {
        fillShippingAddress('{{ Auth::guard('frontend')->user()->id }}', '{{ route('customers.getCustomerAddresses') }}');
        toggleExistingNewAddress("billing");
        toggleExistingNewAddress("delivery");
        });
    @endauth

    @guest('frontend')
        $("#zone_id").on("change", function () {
        getApplicableTaxClass(
        '{{ route('tax-classes.getApplicableTaxClass') }}',
        $("#country_id option:selected").val(),
        $("#zone_id option:selected").val()
        );
        });
    @endguest

    @auth('frontend')
        $("#auth_billing_shipping_address").on("change", function () {
        if($(this).is("#auth_billing_shipping_address")){
        let dataCountry = $("option:selected", this).attr("data-country");
        let dataZone = $("option:selected", this).attr("data-zone");
        $("#auth_billing_country_id").val(dataCountry);
        $("#auth_billing_country_id").attr("data-zone", dataZone);
        $("#auth_billing_country_id").trigger("change");
        }
        });
    
        $("#auth_billing_zone_id").on("change", function () {
        getApplicableTaxClass(
        '{{ route('tax-classes.getApplicableTaxClass') }}',
        $("#auth_billing_country_id option:selected").val(),
        $("#auth_billing_zone_id option:selected").val()
        );
        });
    @endauth
</script>
@endpush
