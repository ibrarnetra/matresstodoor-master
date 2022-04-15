<div id="kt_header" style="" class="header align-items-stretch">
    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <!--begin::Aside mobile toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
            <div class="btn btn-icon btn-active-color-white" id="kt_aside_mobile_toggle">
                <i class="bi bi-list fs-1"></i>
            </div>
        </div>
        <!--end::Aside mobile toggle-->
        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="{{route('dashboard.index')}}" class="d-lg-none">
                <img alt="Logo" src="{{asset('metronic/')}}/media/logos/logo-compact.svg" class="h-15px" />
            </a>
        </div>
        <!--end::Mobile logo-->
        <!--begin::Wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <!--begin::Navbar-->
            <div class="d-flex align-items-stretch" id="kt_header_nav">
                <!--begin::Menu wrapper-->
                <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
                    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true"
                    data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                    <!--begin::Menu-->
                    <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch"
                        id="#kt_header_menu" data-kt-menu="true">
                        <div class="menu-item me-lg-1">
                            <a class="menu-link py-3 {{(isset($active) && $active =='dashboard') ? 'active' : ''}}" href="{{route('dashboard.index')}}">
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </div>
                    </div>
                    <!--end::Menu-->
                </div>
                <!--end::Menu wrapper-->
            </div>
            <!--end::Navbar-->
            <!--begin::User-->
            <div class="d-flex align-items-center ms-lg-5" id="kt_header_user_menu_toggle">
                <!--begin::User info-->
                <div class="btn btn-active-light d-flex align-items-center bg-hover-light py-2 px-2 px-md-3" data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                    data-kt-menu-placement="bottom-end">
                    <!--begin::Name-->
                    <div class="d-none d-md-flex flex-column align-items-end justify-content-center me-2">
                        <span class="text-muted fs-base fw-bolder lh-1">{{Auth::guard('web')->user()->first_name . " " . Auth::guard('web')->user()->last_name}}</span>
                    </div>
                    <!--end::Name-->
                    <!--begin::Symbol-->
                    <div class="symbol symbol-30px symbol-md-40px">
                        <img src="{{asset('metronic/')}}/media/avatars/300x300.png" alt="image" />
                    </div>
                    <!--end::Symbol-->
                </div>
                <!--end::User info-->
                <!--begin::Menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-350px" data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="{{asset('metronic/')}}/media/avatars/300x300.png" />
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bolder d-flex align-items-center fs-5">{{Auth::guard('web')->user()->first_name . " " . Auth::guard('web')->user()->last_name}}</div>
                                <a href="javascript:void(0);" class="fw-bold text-muted text-hover-primary fs-7">{{Auth::guard('web')->user()->email}}</a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <div class="menu-item px-5">
                        <a href="{{route('admin.myProfile')}}" class="menu-link px-5">My Profile</a>
                    </div>
                    <div class="menu-item px-5">
                        <a href="{{route('admin.changePassword')}}" class="menu-link px-5">Change Password</a>
                    </div>
                    <div class="menu-item px-5">
                        <a href="{{route('admin.logout')}}" class="menu-link px-5">Sign Out</a>
                    </div>
                </div>
                <!--end::Menu-->
            </div>
            <!--end::User -->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Container-->
</div>