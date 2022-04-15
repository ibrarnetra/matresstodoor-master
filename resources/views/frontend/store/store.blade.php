<!DOCTYPE html>
<html lang="en">

   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <title>{{$meta_title}}</title>
      <meta name="keywords" content="{{$meta_keyword}}">
      <meta name="description" content="{{$meta_description}}">
      <meta name="author" content="{{$meta_title}}">

      <meta property="og:title" content="{{$meta_title}}">
      <meta property="og:description" content="{{$meta_description}}">
      <meta property="og:image" content="{{$meta_image}}">
      <meta property="og:url" content="{{$meta_url}}">
      <meta name="twitter:card" content="summary_large_image">

      <!-- Favicon -->
      <link rel="shortcut icon" type="image/x-icon" href="{{asset('frontend_assets/')}}/custom/icons/favicon.png">


      <script type="text/javascript">
         WebFontConfig = {
            google: { families: [ 'Open+Sans:300,400,600,700,800','Poppins:300,400,500,600,700' ] }
        };
        (function(d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = '{{asset('frontend_assets/')}}/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
      </script>

      <!-- Plugins CSS File -->
      <link rel="stylesheet" href="{{asset('frontend_assets/')}}/css/bootstrap.min.css">

      <!-- Main CSS File -->
      <link rel="stylesheet" href="{{asset('frontend_assets/')}}/css/style.min.css">
      <link rel="stylesheet" type="text/css" href="{{asset('frontend_assets/')}}/vendor/fontawesome-free/css/all.min.css">
      <link rel="stylesheet" type="text/css" href="{{asset('/')}}custom/select2.min.css" />
      <link rel="stylesheet" href="{{asset('frontend_assets/')}}/custom/custom.css">
      <link rel="stylesheet" href="{{asset('frontend_assets/')}}/custom/sweetalert2.min.css">
      <link rel="stylesheet" type="text/css" href="{{asset('/')}}custom/daterangepicker.css" />
      <link rel="stylesheet" href="{{asset('frontend_assets/')}}/store/style.css">
   </head>

   <body>
      <div class="page-wrapper">
         @include('frontend.common.header')


         <main class="main">
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
               <div class="container">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="{{route('frontend.home')}}"><i class="icon-home"></i></a></li>
                     <li class="breadcrumb-item active" aria-current="page">
                        {{strtoupper($title)}}
                     </li>
                  </ol>
               </div><!-- End .container -->
            </nav>

            </nav>
            <div class="container">
               <div class="row">
                  <div class="col-md-4">
                     <ul>
                        @foreach ($stores as $key => $store)
                        <li class="mb-3">
                           <h2 class="m-0 mb-2 custom-store-heading">{{$store['name']}}</h2>

                           <a href="http://maps.google.com/?q={{$store['address']}}" target="_blank">
                              <div class="d-flex justify-content-start mb-1">
                                 <i class="fas fa-map-marker-alt custom-social-icon"></i>&nbsp;&nbsp;&nbsp;
                                 <p class="m-0 mt-1">{{$store['address']}}</p>
                              </div>
                           </a>

                           <a href="tel:+1{{sanitizeTelephone($store['telephone'])}}">
                              <div class="d-flex justify-content-start mb-1">
                                 <i class="fas fa-phone custom-social-icon"></i>&nbsp;&nbsp;&nbsp;
                                 <p class="m-0 mt-1">{{"+1 " .$store['telephone']}}</p>
                              </div>
                           </a>

                           <a href="mailto:{{$store['email']}}">
                              <div class="d-flex justify-content-start">
                                 <i class="fa fa-envelope custom-social-icon"></i>&nbsp;&nbsp;&nbsp;
                                 <p class="m-0 mt-1">{{$store['email']}}</p>
                              </div>
                           </a>
                        </li>
                        @endforeach
                     </ul>
                  </div>
                  <div class="col-md-8">
                     <div id="map"></div><!-- End #map -->
                  </div>
               </div>
            </div><!-- End .container -->

         </main><!-- End .main -->

         @include('frontend.common.footer')
         <input type="hidden" id="mini-cart-url" value="{{route('frontend.miniCart')}}">
      </div><!-- End .page-wrapper -->

      <div class="mobile-menu-overlay"></div><!-- End .mobil-menu-overlay -->

      <div class="mobile-menu-container">
         <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="icon-retweet"></i></span>
            <nav class="mobile-nav">
               <ul class="mobile-menu">
                  <li class="active"><a href="{{route('frontend.home')}}">Home</a></li>
                  <li>
                     <a href="{{route('frontend.whyUs')}}">Why Us?</a>
                  </li>
                  <li><a href="javascript:void(0);">Categories</a>
                     <ul>
                        @if (count(getLimitedCategories()) > 0)
                        @foreach (getLimitedCategories() as $key => $value)
                        @if ($key > 3)
                        @break
                        @endif
                        <li>
                           <a href="{{route('frontend.shop',['category' => $value->slug])}}">{{$value->eng_description->name}}</a>
                           @if (count($value->children) > 0)
                           <ul>
                              @foreach ($value->children as $child)
                              <li>
                                 <a href="{{route('frontend.shop',['category' => $child->slug])}}" class="mega-menu-link">{{$child->eng_description->name}}</a>
                              </li>
                              @endforeach
                           </ul>
                           @endif
                        </li>
                        @endforeach
                        @endif
                     </ul>
                  </li>
                  <li>
                     <a href="{{route('frontend.shop', ['type' => 'discounted'])}}">Hot deals</a>
                  </li>

                  <li><a href="javascript:void(0);">Account</a>
                     <ul>
                        @guest('frontend')
                        <li>
                           <a href="{{route('frontend.signIn')}}">
                              Login
                           </a>
                        </li>
                        @endguest
                        @auth('frontend')
                        <li>
                           <a href="{{route('frontend.dashboard')}}">
                              Dashboard
                           </a>
                        </li>
                        <li><a href="{{route('frontend.wishlist')}}">Wishlist</a></li>
                        <li><a href="{{route('frontend.logout')}}">Logout</a></li>
                        @endauth
                     </ul>
                  </li>
                  <li>
                     <a href="javascript:void(0);">Support</a>
                     <ul>
                        <li><a href="{{route('frontend.aboutUs')}}" class="mega-menu-link">About Us</a></li>
                        <li><a href="{{route('frontend.termsAndConditions')}}" class="mega-menu-link">Terms and Conditions</a></li>
                        <li><a href="{{route('frontend.privacyPolicy')}}" class="mega-menu-link">Privacy Policy</a></li>
                        <li><a href="{{route('frontend.salesCancellationPolicy')}}" class="mega-menu-link">Sales and Cancellation Policy</a></li>
                        <li><a href="{{route('frontend.shippingUnboxing')}}" class="mega-menu-link">Shipping and Unboxing</a></li>
                        <li><a href="{{route('frontend.warranties')}}" class="mega-menu-link">Warranties</a></li>
                        <li><a href="{{route('frontend.stores')}}" class="mega-menu-link">Stores</a></li>
                        <li><a href="{{route('frontend.showContactUs')}}" class="mega-menu-link">Contact Us</a></li>
                        <li><a href="{{route('frontend.faq')}}" class="mega-menu-link">FAQ</a></li>
                        <li><a href="{{route('frontend.home')}}" class="mega-menu-link">Media Blogs</a></li>
                        <li><a href="{{route('frontend.home')}}" class="mega-menu-link">Career</a></li>
                        <li><a href="{{route('frontend.home')}}" class="mega-menu-link">Refer and Earn Program</a></li>
                     </ul>
                  </li>
               </ul>
            </nav><!-- End .mobile-nav -->
         </div><!-- End .mobile-menu-wrapper -->
      </div><!-- End .mobile-menu-container -->

      <a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>

      <script>
         const BASE_URL = '{{URL::to('/')}}';
         const CSRF_TOKEN = '{{csrf_token()}}';
      </script>
      <!-- Plugins JS File -->
      <script src="{{asset('frontend_assets/')}}/js/jquery.min.js"></script>
      <script src="{{asset('frontend_assets/')}}/js/bootstrap.bundle.min.js"></script>
      <script src="{{asset('frontend_assets/')}}/js/plugins.min.js"></script>
      <script src="{{asset('frontend_assets/')}}/js/plugins/isotope-docs.min.js"></script>
      <script src="{{asset('/')}}custom/select2.min.js"></script>
      <script src="{{asset('frontend_assets/')}}/custom/moment.min.js"></script>
      <script src="{{asset('/')}}custom/daterangepicker.min.js"></script>
      <!-- Main JS File -->
      <script src="{{asset('frontend_assets/')}}/js/main.min.js"></script>
      <script src="{{asset('frontend_assets/')}}/custom/common.js"></script>
      <!-- Google Map-->
      <script src="{{asset('frontend_assets/')}}/js/map.js"></script>
      <!-- jQuery Validation Plugin -->
      <script src="{{asset('/')}}custom/jqueryValidation/jquery.validate.min.js"></script>
      <script src="{{asset('/')}}custom/jqueryValidation/additional-methods.min.js"></script>

      <script>
         let locations = {!! json_encode($stores) !!};
      </script>
      <script src="{{asset('frontend_assets/')}}/store/main.js"></script>
      <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
      <script src="https://maps.googleapis.com/maps/api/js?key={{getConstant('GOOGLE_MAP_API')}}&callback=initMap&libraries=&v=weekly" async></script>
   </body>

</html>