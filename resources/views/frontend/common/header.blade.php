@php
$notifications = getAllWebNotifications();
$notifications_exist = count($notifications);
@endphp

@if ($notifications_exist)
@foreach ($notifications as $key => $value)
<div class="{{getURISegment()}}" style="background-color: #27194E;">
   <div class="row py-4">
      <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center custom-notification-container">
         {!! $value->notification !!}
      </div>
   </div>
</div>
@endforeach
@endif

<header class="header">
   <!-- End .header-top -->
   <div class="header-bottom sticky-header">
      <div class="container">
         <div class="row row-sm">
            <div class="col-lg-2 header-left">
               <button class="mobile-menu-toggler" type="button">
                  <i class="icon-menu"></i>
               </button>
               <a href="{{route('frontend.home')}}">
                  <img class="header-logo" src="{{asset('storage/config_logos/'.getWebsiteLogo())}}" alt="Mattress To Door">
               </a>
            </div>

            <div class="col-lg-8 header-center header-menu-items">
               <nav class="main-nav">
                  <ul class="menu">
                     <li>
                        <a href="{{route('frontend.whyUs')}}">Why Us?</a>
                     </li>
                     @if (count(getLimitedCategories()) > 0)
                     @foreach (getLimitedCategories() as $key => $value)
                     @if ($key > 3)
                     @break
                     @endif
                     <li>
                        <a href="{{route('frontend.shop',['category' => $value->slug])}}">{{$value->eng_description->name}}</a>
                        @if (count($value->children) > 0)
                        <div class="megamenu megamenu-fixed-width">
                           <div class="row">
                              @foreach ($value->children as $child)
                              <div class="col-md-6">
                                 <a href="{{route('frontend.shop',['category' => $child->slug])}}" class="mega-menu-link">{{$child->eng_description->name}}</a>
                              </div>
                              @endforeach
                           </div>
                        </div>
                        @endif
                     </li>
                     @endforeach
                     @endif
                     <li>
                        <a class="blink_me text-red" href="{{route('frontend.shop', ['type' => 'discounted'])}}">Hot deals</a>
                     </li>
                     <li>
                        <a href="javascript:void(0);">Support</a>
                        <div class="megamenu megamenu-fixed-width">
                           <div class="row">
                              <div class="col-md-6">
                                 <a href="{{route('frontend.showContactUs')}}" class="mega-menu-link">Contact Us</a>
                              </div>
                              <div class="col-md-6">
                                 <a href="{{route('frontend.stores')}}" class="mega-menu-link">Stores</a>
                              </div>
                              <div class="col-md-6">
                                 <a href="{{route('frontend.termsAndConditions')}}" class="mega-menu-link">Terms and Conditions</a>
                              </div>
                              <div class="col-md-6">
                                 <a href="{{route('frontend.salesCancellationPolicy')}}" class="mega-menu-link">Sales and Cancellation Policy</a>
                              </div>
                              <div class="col-md-6">
                                 <a href="{{route('frontend.privacyPolicy')}}" class="mega-menu-link">Privacy Policy</a>
                              </div>
                              <div class="col-md-6">
                                 <a href="{{route('frontend.faq')}}" class="mega-menu-link">FAQ</a>
                              </div>
                              <div class="col-md-6">
                                 <a href="{{route('frontend.warranties')}}" class="mega-menu-link">Warranties</a>
                              </div>
                              <div class="col-md-6">
                                 <a href="{{route('frontend.shippingUnboxing')}}" class="mega-menu-link">Shipping and Unboxing</a>
                              </div>
                              <div class="col-md-6">
                                 <a href="{{route('frontend.home')}}" class="mega-menu-link">Career</a>
                              </div>
                              <div class="col-md-6">
                                 <a href="{{route('frontend.home')}}" class="mega-menu-link">Media Blogs</a>
                              </div>
                              <div class="col-md-6">
                                 <a href="{{route('frontend.home')}}" class="mega-menu-link">Refer and Earn Program</a>
                              </div>
                           </div>
                        </div>
                     </li>
                  </ul>
               </nav>
            </div>

            <div class="col-lg-2 header-right">
               <a @guest('frontend') href="{{route('frontend.signIn')}}" @endguest @auth('frontend') href="{{route('frontend.dashboard')}}" @endauth>
                  <div class="header-user">
                     <i class="icon-user-2"></i>
                  </div>
               </a>

               <div class="header-search">
                  <a href="#" class="search-toggle" role="button">
                     <i class="icon-search-3"></i>
                  </a>
                  <form action="{{route('frontend.shop')}}" method="get">
                     <div class="header-search-wrapper">
                        <input type="search" class="form-control" name="q" id="q" placeholder="Search...">
                        <div class="select-custom">
                           <select id="category" name="category">
                              <option value="">All Categories</option>
                              @if (count(getAllCategories()) > 0)
                              @foreach (getAllCategories() as $parent)
                              <option value="{{$parent->slug}}">{{$parent->eng_description->name}}</option>
                              @if (count($parent->children) > 0)
                              @foreach ($parent->children as $child)
                              <option value="{{$child->slug}}">-{{$child->eng_description->name}}</option>
                              @endforeach
                              @endif
                              @endforeach
                              @endif
                           </select>
                        </div><!-- End .select-custom -->
                        <button class="btn" type="submit"><i class="icon-search"></i></button>
                     </div><!-- End .header-search-wrapper -->
                  </form>
               </div>

               @auth('frontend')
               <a href="{{route('frontend.wishlist')}}" class="porto-icon">
                  <i class="icon icon-wishlist-2"></i>
               </a>
               @endauth

               <div class="dropdown cart-dropdown">
                  <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                     <img class="custom-cart" src="{{asset('frontend_assets/')}}/custom/icons/shopping-cart.png" alt="shopping-cart.png">
                     <span class="cart-count d-none"></span>
                  </a>
                  <div id="mini-cart"></div>
               </div>
            </div>
         </div>
      </div>
      <!-- End .header-bottom -->
   </div>

   <div class="container sub-header-container {{getURISegment()}}">
      <div class="row py-4">
         <div class="offset-md-3 col-md-6 px-0 offset-lg-2 col-lg-8 col-sm-12">
            <div class="row custom-row">
               <div class="col-md-4 col-lg-4 col-sm-12 text-right px-0 custom-col">
                  <a href="javascript:void(0);" data-toggle="modal" data-target="#info-modal-1">
                     <i class="fas fa-shipping-fast pr-2"></i>
                     <span>Free, no-contact delivery*</span>
                  </a>
               </div>
               <div class="col-md-4 col-lg-4 col-sm-12 text-center custom-col">
                  <a href="javascript:void(0);" data-toggle="modal" data-target="#info-modal-2">
                     <i class="fas fa-moon pr-2"></i>
                     <span>100-night risk-free trial*</span>
                  </a>
               </div>
               <div class="col-md-4 col-lg-4 col-sm-12 text-left px-0 custom-col">
                  <a href="javascript:void(0);" data-toggle="modal" data-target="#info-modal-3">
                     <i class="fas fa-calendar-day pr-2"></i>
                     <span>10-year limited warranty*</span>
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- End .header-bottom -->
</header>