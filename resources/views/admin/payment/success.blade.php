<!DOCTYPE html>
<html lang="en">
   <!--begin::Head-->

   <head>
      <meta charset="utf-8" />
      <title>{{getConstant('APP_NAME')}} | {{$title}}</title>
      <meta name="description" content="{{$title}}" />
      <meta name="keywords" content="{{$title}}" />
      <link rel="canonical" href="{{URL::to('/')}}" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/config_favicons/'.getFavicon())}}">
      <!--begin::Fonts-->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
      <!--end::Fonts-->
      <!--begin::Global Stylesheets Bundle(used by all pages)-->
      <link href="{{asset('metronic/')}}/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
      <link href="{{asset('metronic/')}}/css/style.bundle.css" rel="stylesheet" type="text/css" />
      <link href="{{asset('/')}}custom/custom.css" rel="stylesheet" type="text/css" />
      <!--end::Global Stylesheets Bundle-->
   </head>
   <!--end::Head-->
   <!--begin::Body-->

   <body id="kt_body" class="bg-body">
      <!--begin::Main-->
      <div class="d-flex flex-column flex-root">
         <!--begin::Authentication - Sign-in -->
         <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            style="background-size1: 100% 50%; background-image: url({{asset('metronic/')}}/media/misc/development-hd.png)">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
               <!--begin::Wrapper-->
               <div class="w-lg-800px bg-white rounded shadow-lg p-10 p-lg-15 mx-auto">
                  <h2 class="mb-3">Success, your payment has been received!</h2>
                  <p>Your order has been successfully processed!</p>
                  <p>You can view your order history by going to the <a href="{{route('frontend.dashboard')}}">my account</a> page and by clicking on <a
                        href="{{route('frontend.dashboard', ['page' => 'my-orders'])}}">history</a>.</p>
                  <p>Please direct any questions you have to the store owner.</p>
                  <p class="m-0">Thanks for shopping with us online!</p>
               </div>
            </div>
            <!--end::Wrapper-->
         </div>
         <!--end::Content-->
      </div>
      <!--end::Authentication - Sign-in-->
      </div>
      <!--end::Main-->
      <!--begin::Javascript-->
      <!--begin::Global Javascript Bundle(used by all pages)-->
      <script src="{{asset('metronic/')}}/plugins/global/plugins.bundle.js"></script>
      <script src="{{asset('metronic/')}}/js/scripts.bundle.js"></script>
      <!--end::Global Javascript Bundle-->
      <!--begin::Page Custom Javascript(used by this page)-->
      <script src="{{asset('metronic/')}}/js/custom/authentication/sign-in/general.js"></script>
      <!--end::Page Custom Javascript-->
      <!--begin::Common(used by all page)-->
      <script src="{{asset('/')}}custom/common.js"></script>
      <!--end::Common-->
      <script>
         $(document).ready(function () {
            generateCreditCardExpiration();
         });
      </script>
      <!--end::Javascript-->
   </body>
   <!--end::Body-->

</html>