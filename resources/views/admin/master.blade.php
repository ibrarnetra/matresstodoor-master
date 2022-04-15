<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->

	<head>
		<meta charset="utf-8" />
		@yield('meta')
		<link rel="canonical" href="{{URL::to('/')}}" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/config_favicons/'.getFavicon())}}">
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Vendor Stylesheets(used by this page)-->
		@stack('page_lvl_css')
		<!--end::Page Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link rel="stylesheet" type="text/css" href="{{asset('metronic/')}}/plugins/global/plugins.bundle.css" />
		<link rel="stylesheet" type="text/css" href="{{asset('metronic/')}}/css/style.bundle.css" />
		<!--end::Global Stylesheets Bundle-->
		<link rel="stylesheet" type="text/css" href="{{asset('/')}}custom/select2.min.css" />
		<link rel="stylesheet" type="text/css" href="{{asset('/')}}custom/daterangepicker.css" />
		<link rel="stylesheet" type="text/css" href="{{asset('/')}}custom/custom.css" />
		<link rel="stylesheet" type="text/css" href="{{asset('/')}}custom/flatpickr.min.css" />
		<style>
			.dataTables_scrollBody {
		     min-height: 200px !important;
	      }
		  .table-responsive{
			min-height: 250px !important;
		}
	
		#generic-datatable_length{
			padding-top:30px !important;
		}
		#generic-datatable_info{
			padding-top:30px !important;
		}

		.dataTables_paginate{
			padding-top:30px !important;
		}
			
	    </style>
		<script src="https://maps.googleapis.com/maps/api/js?key={{getConstant('GOOGLE_MAP_API')}}&libraries=places"></script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->

	<body id="kt_body" class="page-loading-enabled page-loading header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed"
		style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
		<!--begin::loader-->
		<div class="page-loader flex-column" id="page-loader">
			<span class="spinner-border text-primary" role="status"></span>
			<span class="text-muted fs-6 fw-bold mt-5">Loading...</span>
		</div>
		<!--end::Loader-->
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Aside-->
				@include('admin.common.sidebar')
				<!--end::Aside-->
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					<!--begin::Header-->
					@include('admin.common.header')
					<!--end::Header-->
					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						@yield('content')
					</div>
					<!--end::Content-->
					<!--begin::GenericModal-->
					<div class="generic-modal-div"></div>
					<!--end::GenericModal-->
					<!--begin::Footer-->
					@include('admin.common.footer')
					<!--end::Footer-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->

		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<!--begin::Svg Icon | path: icons/duotone/Navigation/Up-2.svg-->
			<span class="svg-icon">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<rect fill="#000000" opacity="0.5" x="11" y="10" width="2" height="10" rx="1" />
						<path
							d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
							fill="#000000" fill-rule="nonzero" />
					</g>
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Scrolltop-->
		<!--end::Main-->
		<!--begin::Javascript-->
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="{{asset('metronic/')}}/plugins/global/plugins.bundle.js"></script>
		<script src="{{asset('metronic/')}}/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		{{--GLOBAL--}}
		<script>
			const BASE_URL = '{{URL::to('/')}}/admin/';
		   const CSRF_TOKEN = '{{csrf_token()}}';
		</script>

		<script src="{{asset('/')}}custom/jquery-3.6.0.min.js"></script>
		<script src="{{asset('/')}}custom/select2.min.js"></script>
		<script src="{{asset('/')}}custom/daterangepicker.min.js"></script>
		<script src="{{asset('/')}}custom/ckeditor.js"></script>
		<script src="{{asset('/')}}custom/flatpickr.js"></script>
		<!--begin::Common(used by all page)-->
		<script src="{{asset('/')}}custom/common.js"></script>
		<script src="{{asset('/')}}custom/customValidation/main.js"></script>
		<!--end::Common-->
		<!--begin::Page Vendors Javascript(used by this page)-->
		@stack('page_lvl_js')
		<!--end::Page Vendors Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->

</html>