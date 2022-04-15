<!DOCTYPE html>
<html lang="en">

   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>{{$meta_title}}</title>
      <meta name="keywords" content="{{$meta_keyword}}" />
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
      <style>
         h4 {
            font-size: 1.4em !important;
            font-weight: 600 !important;
            line-height: 27px !important;
            margin: 0 0 16px 0 !important;
         }

         h1,
         h2,
         h3,
         h4,
         h5,
         h6 {
            color: #212529;
            font-weight: 200;
            letter-spacing: -0.05em;
            margin: 0;
            -webkit-font-smoothing: antialiased;
         }

      </style>
      <script type="text/javascript">
         var onloadCallback = function() {
                 grecaptcha.render('html_element', {
                   'sitekey' : '{{getConstant('reCAPTCHA_SITEKEY')}}'
                 });
               };
      </script>
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
                        Contact us</li>
                  </ol>
               </div><!-- End .container -->
            </nav>

            <div class="container">
               @if (session('success'))
               <div class="row">
                  <div class="offset-md-6 col-md-6">
                     <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{session('success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                  </div>
               </div>
               @endif

               @if (session('error'))
               <div class="row">
                  <div class="offset-md-6 col-md-6">
                     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{session('error')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                  </div>
               </div>
               @endif

               <div class="row">
                  <div class="col-md-6">
                     <h4>Contact Us</h4>
                     <p style="margin-bottom: 5px;">Our Office hours are 11 am – 7:30 pm Monday to Sunday</p>
                     <p class="m-0 mb-2">Or you can Email us anytime !</p>

                     <p class="custom-contact-paragraph">For General Inquiries</p>
                     <p style="margin-bottom: 12px;"><a href="tel:+19058634566"><i class="fas fa-phone custom-social-icon"></i>&nbsp;&nbsp;&nbsp;+1 (905) 863-4566</a></p>
                     <p class="m-0 mb-2"><a href="mailto:info@mattresstodoor.ca"><i class="fa fa-envelope custom-social-icon"></i>&nbsp;&nbsp;&nbsp;info@mattresstodoor.ca</a></p>

                     <p class="custom-contact-paragraph">For Returns</p>
                     <p style="margin-bottom: 12px;"><a href="tel:+19058634566"><i class="fas fa-phone custom-social-icon"></i>&nbsp;&nbsp;&nbsp;+1 (905) 863-4566</a></p>
                     <p class="m-0 mb-2"><a href="mailto:info@mattresstodoor.ca"><i class="fa fa-envelope custom-social-icon"></i>&nbsp;&nbsp;&nbsp;info@mattresstodoor.ca</a></p>

                     <p class="custom-contact-paragraph">For Shipping Concerns</p>
                     <p style="margin-bottom: 12px;"><a href="tel:+19058634566"><i class="fas fa-phone custom-social-icon"></i>&nbsp;&nbsp;&nbsp;+1 (905) 863-4566</a></p>
                     <p class="m-0 mb-2"><a href="mailto:info@mattresstodoor.ca"><i class="fa fa-envelope custom-social-icon"></i>&nbsp;&nbsp;&nbsp;info@mattresstodoor.ca</a></p>

                     <p class="custom-contact-paragraph">For Warranty Claims</p>
                     <p style="margin-bottom: 12px;"><a href="tel:+19058634566"><i class="fas fa-phone custom-social-icon"></i>&nbsp;&nbsp;&nbsp;+1 (905) 863-4566</a></p>
                     <p class="m-0 mb-2"><a href="mailto:info@mattresstodoor.ca"><i class="fa fa-envelope custom-social-icon"></i>&nbsp;&nbsp;&nbsp;info@mattresstodoor.ca</a></p>
                  </div>
                  <div class="col-md-6">
                     <h4>Write Us</h4>
                     <form action="{{route('frontend.handleContactUs')}}" method="POST" id="form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="captcha">
                        <div class="form-group required-field">
                           <label for="name">Name</label>
                           <input type="text" class="form-control custom-form-control @error('name') is-invalid @enderror" id="name" name="name" required data-error="#name_error" autocomplete="off">
                           <div class="invalid-feedback" id="name_error">
                              @error('name')
                              {{ $message }}
                              @enderror
                           </div>
                        </div><!-- End .form-group -->

                        <div class="form-group required-field">
                           <label for="email">Email</label>
                           <input type="email" class="form-control custom-form-control @error('email') is-invalid @enderror" id="email" name="email" required data-error="#email_error"
                              autocomplete="off">
                           <div class="invalid-feedback" id="email_error">
                              @error('email')
                              {{ $message }}
                              @enderror
                           </div>
                        </div><!-- End .form-group -->

                        <div class="form-group required-field">
                           <label for="enquiry">Enquiry</label>
                           <textarea cols="30" rows="1" id="enquiry" class="form-control custom-form-control @error('enquiry') is-invalid @enderror" name="enquiry" required data-error="#enquiry_error"
                              autocomplete="off"></textarea>
                           <div class="invalid-feedback" id="enquiry_error">
                              @error('enquiry')
                              {{ $message }}
                              @enderror
                           </div>
                        </div><!-- End .form-group -->

                        <div id="html_element"></div>

                        <div class="form-footer">
                           <button type="submit" class="btn btn-primary">Submit</button>

                           <div class="invalid-feedback" id="captcha_error">
                              @error('captcha')
                              {{ $message }}
                              @enderror
                           </div>
                        </div>
                     </form>
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
      <!-- jQuery Validation Plugin -->
      <script src="{{asset('/')}}custom/jqueryValidation/jquery.validate.min.js"></script>
      <script src="{{asset('/')}}custom/jqueryValidation/additional-methods.min.js"></script>
      <!-- reCAPTCHA v2 checkbox -->
      <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
      </script>
      <script>
         $("#form").validate({
               errorClass: "is-invalid",
               rules: {
                  name: {
                     required: true,
                  },
                  email: {
                     required: true,
                     email: true,
                  },
                  enquiry: {
                     required: true,
                  },
               },
               messages: {
                  name: {
                     required: "The name field is required."
                  },
                  email: {
                     required: "The email field is required."
                  },
                  enquiry: {
                     required: "The enquiry field is required."
                  },
               },
               errorPlacement: function (error, element) {
                  let placement = $(element).data('error');
                  if (placement) {
                     $(placement).html(error.text());
                  }
               },
               success: function (label) {
                  $(label).remove();
               },
               submitHandler: function(form, event) {
                  let token = grecaptcha.getResponse();
                  if (token) {
                     $("#captcha_error").css("display", "none");
                     $("#captcha_error").html("");
                     $("input[name='captcha']").val(token);
                     form.submit();
                  } else {
                     $("#captcha_error").html('Captcha error! try again later or contact site admin.');
                     $("#captcha_error").css("display", "block");
                     event.preventDefault();
                  }
               }
            });
      </script>
   </body>

</html>