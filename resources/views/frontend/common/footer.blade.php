<footer class="footer" style="background-color: #27194E;">
    <div class="row custom-footer-container">
        <div class="col-md-12 col-sm-6 col-lg-12">
            <div class="row">
                <div class="col-md-2 col-sm-6 col-6">
                    <p class="text-white"><strong>Products</strong></p>
                    <ul class="list-unstyled m-0">
                        @if (count(getLimitedCategories()) > 0)
                        @foreach (getLimitedCategories() as $key => $value)
                        <li @if ($key!=0) class="pt-3" @endif>
                            <a class="text-white" href="{{route('frontend.shop',['category' => $value->slug])}}">{{$value->eng_description->name}}</a>
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </div>

                <div class="col-md-2 col-sm-6 col-6">
                    <p class="text-white"><strong>Shop by Size</strong></p>
                    <ul class="list-unstyled m-0">
                        @if (count(getProductOptions()) > 0)
                        @foreach (getProductOptions() as $key => $option_value)
                        @if ($key > 5)
                        @break
                        @endif
                        <li @if ($key!=0) class="pt-3" @endif>
                            <a class="text-white" href="{{route('frontend.shop', ['variant' => $option_value->option_value_id])}}">{{$option_value->option_value->eng_description->name}}</a>
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </div>

                <div class="col-md-2 col-sm-6 col-6 mt-sm-2">
                    <p class="text-white"><strong>Support</strong></p>
                    <ul class="list-unstyled m-0">
                        <li><a class="text-white" href="{{route('frontend.aboutUs')}}">About Us</a></li>
                        <li class="pt-3"><a class="text-white" href="{{route('frontend.salesCancellationPolicy')}}">Sales and Cancellation Policy</a></li>
                        <li class="pt-3"><a class="text-white" href="{{route('frontend.shippingUnboxing')}}">Shipping and Unboxing</a></li>
                        <li class="pt-3"><a class="text-white" href="{{route('frontend.warranties')}}">Warranties</a></li>
                        <li class="pt-3"><a class="text-white" href="{{route('frontend.stores')}}">Stores</a></li>
                        <li class="pt-3"><a class="text-white" href="{{route('frontend.showContactUs')}}">Contact Us</a></li>
                    </ul>
                </div>

                <div class="col-md-2 col-sm-6 col-6">
                    <ul class="list-unstyled m-0">
                        <li class="custom-class pt-5"><a class="text-white" href="{{route('frontend.faq')}}">FAQ</a></li>
                        <li class="pt-3"><a class="text-white" href="{{route('frontend.home')}}">Media Blogs</a></li>
                        <li class="pt-3"><a class="text-white" href="{{route('frontend.home')}}">Career</a></li>
                        <li class="pt-3"><a class="text-white" href="{{route('frontend.home')}}">Refer and Earn Program</a></li>
                    </ul>
                </div>

                <div class="col-md-4 col-sm-12 col-12 mt-sm-2">
                    <p class="text-white"><strong>Sign up our Newsletter</strong></p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary text-white" type="button" onclick="initNewsletter(this, '{{route('frontend.newsletter')}}')">Submit</button>
                        </div>
                    </div>

                    <div class="social-container mb-2">
                        @if (getSettingsByKey('config_facebook_link'))
                        <a href="{{getSettingsByKey('config_facebook_link')}}" target="_blank" class="social-icon text-white" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if (getSettingsByKey('config_twitter_link'))
                        <a href="{{getSettingsByKey('config_twitter_link')}}" target="_blank" class="social-icon text-white" title="Twitter"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if (getSettingsByKey('config_youtube_link'))
                        <a href="{{getSettingsByKey('config_youtube_link')}}" target="_blank" class="social-icon text-white" title="Youtube"><i class="fab fa-youtube"></i></a>
                        @endif
                        @if (getSettingsByKey('config_instagram_link'))
                        <a href="{{getSettingsByKey('config_instagram_link')}}" target="_blank" class="social-icon text-white" title="Instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if (getSettingsByKey('config_linkedin_link'))
                        <a href="{{getSettingsByKey('config_linkedin_link')}}" target="_blank" class="social-icon text-white" title="Linkedin"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                        @if (getSettingsByKey('config_pinterest_link'))
                        <a href="{{getSettingsByKey('config_pinterest_link')}}" target="_blank" class="social-icon text-white" title="Pinterest"><i class="fab fa-pinterest-p"></i></a>
                        @endif
                    </div>

                    <a class="text-white" href="tel:+19058634566">
                        +1 (905) 863-4566
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row py-3" style="background-color: #3d2672 !important;">
        <div class="col-md-12 col-sm-12 col-lg-12 text-center">
            <div class="mr-5 d-inline-block">
                <p class="m-0 text-white">CopyRight © Mattress To Door Powered by <a href="https://www.netraclos.com" style="color:white" target="_blank">NetraClos Inc.</a> </p>
            </div>
            <div class="my-2 d-inline-block">
                <a class="text-white" href="{{route('frontend.privacyPolicy')}}">Privacy Policy</a>
            </div>
            <div class="ml-5 d-inline-block">
                <a class="text-white" href="{{route('frontend.termsAndConditions')}}">Terms and Conditions</a>
            </div>
        </div>
    </div>
</footer>