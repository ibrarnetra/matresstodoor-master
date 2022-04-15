<!DOCTYPE html>
<html lang="en">

   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      @yield('meta')
      <!-- Favicon -->
      <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/config_favicons/'.getFavicon())}}">
      <script type=" text/javascript">
         WebFontConfig={ google: { families: [ 'Open+Sans:300,400,600,700,800' ,'Poppins:300,400,500,600,700' ] } }; (function(d) { var wf=d.createElement('script'),
         s=d.scripts[0]; wf.src='{{asset("frontend_assets/")}}/js/webfont.js' ; wf.async=true; s.parentNode.insertBefore(wf, s); })(document); 
      </script> <!-- Plugins CSS File -->
      {{-- <link href="//db.onlinewebfonts.com/c/fd811cbfe623f3e2be52b981f67a2102?family=Calibre+Regular" rel="stylesheet" type="text/css"/> --}}
     
      <link rel="stylesheet" type="text/css" href="{{asset('/')}}/custom/calibre/calibre.css" />
      <link rel="stylesheet" href="{{asset('frontend_assets/')}}/css/bootstrap.min.css">
      <!-- Main CSS File -->
      <link rel="stylesheet" href="{{asset('frontend_assets/')}}/css/style.min.css">
      <link rel="stylesheet" type="text/css" href="{{asset('frontend_assets/')}}/vendor/fontawesome-free/css/all.min.css">
      <link rel="stylesheet" type="text/css" href="{{asset('/')}}custom/select2.min.css" />
      <link rel="stylesheet" href="{{asset('frontend_assets/')}}/custom/custom.css">
      <link rel="stylesheet" href="{{asset('frontend_assets/')}}/custom/sweetalert2.min.css">
      <link rel="stylesheet" type="text/css" href="{{asset('/')}}custom/daterangepicker.css" />
      <script src="https://maps.googleapis.com/maps/api/js?key={{getConstant('GOOGLE_MAP_API')}}&libraries=places"></script>
      <!-- Dynamic head script -->
      {!! getGoogleAnalytics() !!}
      @stack('page_lvl_css')
   </head>

   <body>
      <div class="page-wrapper">
         @include('frontend.common.header')

         <main class="home main">
            @yield('content')
         </main>

         @include('frontend.common.footer')

         {{-- add to cart success modal --}}
         <div class="modal fade" id="addCartModal" tabindex="-1" role="dialog" aria-labelledby="addCartModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-body add-cart-box text-center">
                     <p id="addToCartRes">Successfully added item to cart.</p>
                     <h4 id="productTitle"></h4>
                     <img src="" id="productImage" alt="adding cart image" style="width:60px; margin-bottom: 20px !important;">
                     <div class="btn-actions">
                        <a href="{{route('frontend.cart')}}"><button class="btn-primary">Go to Cart Page</button></a>
                        <a href="#"><button class="btn-primary" data-dismiss="modal">Continue</button></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         {{-- quick view modal --}}
         <div class="ajax-quick-view"></div>

         <!-- info-modal-1 -->
         <div class="modal fade" id="info-modal-1" tabindex="-1" role="dialog" aria-labelledby="info-modal-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header p-0 p-3 custom-modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-12">
                           @if (isset($info_modals['info-modal-1']) && !is_null($info_modals['info-modal-1']))
                           {!! $info_modals['info-modal-1'] !!}
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- info-modal-1 -->

         <!-- info-modal-2 -->
         <div class="modal fade" id="info-modal-2" tabindex="-1" role="dialog" aria-labelledby="info-modal-2" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header p-0 p-3 custom-modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-12">
                           @if (isset($info_modals['info-modal-2']) && !is_null($info_modals['info-modal-2']))
                           {!! $info_modals['info-modal-2'] !!}
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- info-modal-2 -->

         <!-- info-modal-3 -->
         <div class="modal fade" id="info-modal-3" tabindex="-1" role="dialog" aria-labelledby="info-modal-3" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header p-0 p-3 custom-modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-12">
                           @if (isset($info_modals['info-modal-3']) && !is_null($info_modals['info-modal-3']))
                           {!! $info_modals['info-modal-3'] !!}
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- info-modal-3 -->

         <input type="hidden" id="mini-cart-url" value="{{route('frontend.miniCart')}}">
      </div>

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
      <script src="{{asset('frontend_assets/')}}/js/main.js"></script>
      <script src="{{asset('frontend_assets/')}}/custom/common.js"></script>
      <script src="{{asset('frontend_assets/')}}/custom/sweetalert2.min.js"></script>
      {{-- <!-- jQuery Validation Plugin -->
      <script src="{{asset('/')}}custom/jqueryValidation/jquery.validate.min.js"></script>
      <script src="{{asset('/')}}custom/jqueryValidation/additional-methods.min.js"></script> --}}
      {{--Lazy Load--}}
      <script src="{{asset('/')}}jquery_lazy/jquery.lazy.min.js"></script>
      <script src="{{asset('/')}}jquery_lazy/jquery.lazy.plugins.min.js"></script>
      {{--Newsletter--}}
      <script src="{{asset('frontend_assets/')}}/js/newsletter/main.js"></script>
      <script src="{{asset('assets/js/jquery.payform.min.js')}}" charset="utf-8"></script>
      <script>
         // ref : http://jquery.eisbehr.de/lazy/#installation
         const instance = $('.lazy').lazy({
             chainable: false, // tell lazy to return its own instance
             placeholder: "data:image/gif;base64,R0lGODlhEALAPQAPzl5uLr9Nrl8e7...",
             threshold: 0,
             onError: function (element) {
                 element.attr("src", "{{asset('frontend_assets/images/600_600.png')}}");
             },
         });
      </script>

      @stack('page_lvl_js')
   </body>

</html>