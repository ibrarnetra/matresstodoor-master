<?php

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Filesystem\Filesystem;
use Elibyy\TCPDF\Facades\TCPDF;
use Carbon\Carbon;
use App\Routexl\Routexl;
use App\Models\Admin\WebNotification;
use App\Models\Admin\Subscriber;
use App\Models\Admin\Store;
use App\Models\Admin\Slider;
use App\Models\Admin\Setting;
use App\Models\Admin\ProductOptionValue;
use App\Models\Admin\Product;
use App\Models\Admin\Payment;
use App\Models\Admin\Page;
use App\Models\Admin\Manufacturer;
use App\Models\Admin\Category;
use App\Models\Admin\UserDiscount;
use Illuminate\Support\Facades\Auth;

### GET CONSTANT BY KEY ###
function getConstant($key)
{
    return Config::get('constants.' . $key);
}

### GET CURRENT DATETIME ACCORDING TO GMT +05:00 ###
function getCurrentDateTime()
{
    return Carbon::now(env('APP_TIMEZONE'))->format(getConstant('DATETIME_DB_FORMAT'));
}

### GET CURRENT DATE ACCORDING TO GMT +05:00 ###
function getCurrentDate()
{
    return Carbon::now(env('APP_TIMEZONE'))->format(getConstant('DATE_DB_FORMAT'));
}

### CAPITALIZE ALL CHARACTERS ###
function capAll($val)
{
    return ucwords(strtolower($val));
}

### CHECK IF VARIABLE ISSET OR IS NULL ###
function validateVal($val)
{
    return (isset($val) && !is_null($val)) ? $val : "";
}

### CHECK IF VARIABLE ISSET OR IS NULL ###
function validateValAndNull($val)
{
    return (isset($val) && !is_null($val)) ? $val : null;
}

### UPLOAD/SAVE IMAGE ###
function saveImage(UploadedFile $file, $folder, $type = 'image')
{
    $file_name_with_extension = $file->getClientOriginalName();

    //get file extension
    $extension = $file->getClientOriginalExtension();

    //file_name to store
    $file_name_to_store = getCurrentDate() . '_' . uniqid() . '.' . $extension;

    ### ORIGINAL FILE ###
    Storage::put('public/' . $folder . '/' . $file_name_to_store, fopen($file, 'r+'));

    ### THUMBNAIL FILE ###
    Storage::put('public/' . $folder . '/150x150/' . $file_name_to_store, fopen($file, 'r+'));

    ### THUMBNAIL FILE RESIZE ###
    $thumbnail_img_path = storage_path('app/public/' . $folder . '/150x150/' . $file_name_to_store);
    $thumbnail_img = Image::make($thumbnail_img_path)->resize(150, 150, function ($constraint) {
        $constraint->aspectRatio();
    });
    $thumbnail_img->save($thumbnail_img_path);

    return $file_name_to_store;
}

### REMOVE EXISTING FILES IN storage/app/public/pdf
function deleteExistingPdf()
{
    $file = new Filesystem;
    $file->cleanDirectory('storage/app/public/pdf');
}

### GENERIC PDF GENERATION ###
function generatePDF($content, $title)
{
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf::SetFont('Times', '', 11, '', false);
    $pdf::SetTitle($title);
    $pdf::setPrintHeader(false);
    $pdf::AddPage('P', "A4");
    $pdf::writeHTML($content, true, false, true, false, '');

    $pdf::setFooterCallback(function ($pdf) {
        // Position at 15 mm from bottom
        $pdf->SetY(-15);
        // Set font
        $pdf->SetFont('helvetica', 'I', 8);
        // Page number
        $pdf->Cell(0, 10, 'Page ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    });

    ob_end_clean();
    $pdf::Output($title, 'I');
}

### GET LIMITED CATEGORIES ###
function getLimitedCategories()
{
    return (new Category())->_getLimitedCategoriesForFrontend();
}

### GET ALL CATEGORIES ###
function getAllCategories()
{
    return (new Category())->_getAllCategoriesForFrontend();
}

### SYNC AND GENERATE SLUGS ###
function syncForSlugs($val)
{
    if ($val == 'products') {
        $items = Product::select('id', 'slug')->with([
            'eng_description' => function ($q) {
                $q->select('product_id', 'language_id', 'name');
            }
        ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();

        if (count($items) > 0) {
            foreach ($items as $key => $value) {
                Product::where('id', $value->id)->update([
                    'slug' => Str::slug($value->eng_description->name) . '-' . $value->id,
                ]);
            }
        }
        return "Successfully synced for products";
    }

    if ($val == 'categories') {
        $items = Category::select('id', 'slug')->with([
            'eng_description' => function ($q) {
                $q->select('category_id', 'language_id', 'name');
            }
        ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();

        if (count($items) > 0) {
            foreach ($items as $key => $value) {
                Category::where('id', $value->id)->update([
                    'slug' => Str::slug($value->eng_description->name) . '-' . $value->id,
                ]);
            }
        }
        return "Successfully synced for categories";
    }

    if ($val == 'manufacturers') {
        $items = Manufacturer::select('id', 'slug', 'name')
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();

        if (count($items) > 0) {
            foreach ($items as $key => $value) {
                Manufacturer::where('id', $value->id)->update([
                    'slug' => Str::slug($value->name) . '-' . $value->id,
                ]);
            }
        }
        return "Successfully synced for manufacturers";
    }
}

### MAKE A ERROR RESPONSE ###
function generateValidErrorResponse($errors)
{
    if (count($errors) > 0) {
        $arr = [];
        foreach ($errors as $k => $v) {
            $arr[$k] = $v[0];
        }
        return $arr;
    } else {
        return new stdClass();
    }
}

### PLUCK FIRST ERROR MSG ###
function pluckErrorMsg($errors)
{
    $error = '';
    foreach ($errors as $k => $v) {
        $error = $v[0];
        break;
    }
    return $error;
}

### SUCCESS RESPONSE ###
function sendResponse($res)
{
    return response()->json($res, 200);
}

### DEFAULT PRICE FORMAT ###
function setDefaultPriceFormat($price)
{
    return number_format((float)round($price, 0), 2, '.', '');
}

/**
 * A PHP function that will generate a secure random password.
 * 
 * @param int $length The length that you want your random password to be.
 * @return string The random password.
 */
function random_password($length)
{
    //A list of characters that can be used in our
    //random password.
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&*_';
    //Create a blank string.
    $password = '';
    //Get the index of the last character in our $characters string.
    $characterListLength = mb_strlen($characters, '8bit') - 1;
    //Loop from 1 to the $length that was specified.
    foreach (range(1, $length) as $i) {
        $password .= $characters[random_int(0, $characterListLength)];
    }
    return $password;
}

### GET URI SEGMENT ###
function getURISegment()
{
    $class = "d-none";
    $segment = Request::segment(1);

    if ($segment == "home" || $segment == "") {
        $class = "";
    }

    return $class;
}

### MAX CHAR LENGTH ###
function maxCharLen($x, $length)
{
    if (strlen($x) <= $length) {
        return $x;
    } else {
        $y = substr($x, 0, $length);
        return $y . "...";
    }
}

### GET ALL MANUFACTURERS ###
function getAllManufacturers()
{
    return (new Manufacturer())->_getAllManufacturersForFrontend();
}

### GET CMS DATA ###
function getCmsContent($uri)
{
    $cms_data = (new Page('en'))->getCmsPage($uri);
    $res = ['title' => '', 'content' => ''];
    if ($uri == "terms-and-conditions") {
        $res['title'] = ($cms_data) ? $cms_data->title : "Terms and Conditions";
        $res['content'] = ($cms_data) ? $cms_data->content : "Terms and Conditions";
    }
    if ($uri == "privacy-policy") {
        $res['title'] = ($cms_data) ? $cms_data->title : "Privacy Policy";
        $res['content'] = ($cms_data) ? $cms_data->content : "Privacy Policy";
    }
    return $res;
}

/**
 * hit routeXL api to get distances based on lat lng
 */
function initRouteXL($locations)
{
    return (new Routexl())->tour($locations);
}

/**
 * Get Latitude & Longitude from an address
 * ref:https://stackoverflow.com/questions/23212681/php-get-latitude-longitude-from-an-address
 */
function getLatLngByGoogleMapApi($address)
{
    $url = "https://maps.google.com/maps/api/geocode/json?key=" . getConstant('GOOGLE_MAP_API') . "&address=" . urlencode($address);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $responseJson = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($responseJson);

    $lat  = "0.0000";
    $lng = "0.0000";
    $res = ['lat' => $lat, 'lng' => $lng];
    if ($response->status == 'OK') {
        $res['lat'] = $response->results[0]->geometry->location->lat;
        $res['lng'] = $response->results[0]->geometry->location->lng;
    }
    return $res;
}

/**
 * ref:https://github.com/gaffling/PHP-Grab-Google-Reviews
 * 💬 Get Google-Reviews with PHP
 * This is a tiny but usefull way to grab the 5 most relevant reviews from Google with cURL and with the use of an API Key**
 * How to get the needed Google API Key:
 * - use: https://developers.google.com/maps/documentation/places/web-service/get-api-key
 * - and follow the easy explaned steps
 * How to find the needed Placec ID:
 * - use: [https://developers.google.com/maps/documentation/places/web-service/place-id]
 * - and do a search for the wanted business name
 */
function phpGrabGoogleReviews()
{
    $options = array(
        'googlemaps_free_apikey' => getConstant('GOOGLE_MAP_API'), // Google API Key
        'google_maps_review_cid' => getConstant('GOOGLE_MAPS_REVIEW_CID'), // Google Placec ID of the Business
        'cache_data_xdays_local' => 30,       // every x day the reviews are loaded from google (save API traffic)
        'your_language_for_tran' => 'en',     // give you language for auto translate reviews
        'show_not_more_than_max' => 5,        // (0-5) only show first x reviews
        'show_only_if_with_text' => true,    // true = show only reviews that have text
        'show_only_if_greater_x' => 3,        // (0-4) only show reviews with more than x stars
        'sort_reviews_by_a_data' => 'rating', // sort by 'time' or by 'rating' (newest/best first)
        'show_cname_as_headline' => true,     // true = show customer name as headline
        'show_stars_in_headline' => false,     // true = show customer stars after name in headline
        'show_author_avatar_img' => true,     // true = show the author avatar image (rounded)
        'show_blank_star_till_5' => true,     // false = don't show always 5 stars e.g. ⭐⭐⭐☆☆
        'show_txt_of_the_review' => true,     // true = show the text of each review
        'show_author_of_reviews' => true,     // true = show the author of each review
        'show_age_of_the_review' => false,     // true = show the age of each review
        'dateformat_for_the_age' => 'Y.m.d',  // see https://www.php.net/manual/en/datetime.format.php
        'show_rule_after_review' => true,     // false = don't show <hr> Tag after each review (and before first)
        'add_schemaorg_metadata' => true,     // add schemo.org data to loop back your rating to SERP
    );

    if (file_exists(public_path('reviews.json')) and strtotime(filemtime(public_path('reviews.json'))) < strtotime('-' . $options['cache_data_xdays_local'] . ' days')) {
        $result = file_get_contents(public_path('reviews.json'));
    } else {
        $url = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . $options['google_maps_review_cid'] . '&key=' . $options['googlemaps_free_apikey'];
        if (function_exists('curl_version')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if (isset($options['your_language_for_tran']) and !empty($options['your_language_for_tran'])) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Language: ' . $options['your_language_for_tran']));
            }
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $arrContextOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ),
                'http' => array(
                    'method' => 'GET',
                    'header' => 'Accept-language: ' . $options['your_language_for_tran'] . "\r\n" .
                        "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36\r\n"
                )
            );
            $result = file_get_contents($url, false, stream_context_create($arrContextOptions));
        }
        $fp = fopen(public_path('reviews.json'), 'w');
        fwrite($fp, $result);
        fclose($fp);
    }
    $data = json_decode($result, true);
    // echo '<pre>';
    // var_dump($data);
    // echo '</pre>'; // DEV & DEBUG
    $reviews = $data['result']['reviews'];

    if (!empty($reviews)) {
        if (isset($options['sort_reviews_by_a_data']) and $options['sort_reviews_by_a_data'] == 'rating') {
            array_multisort(array_map(function ($element) {
                return $element['rating'];
            }, $reviews), SORT_DESC, $reviews);
        } else if (isset($options['sort_reviews_by_a_data']) and $options['sort_reviews_by_a_data'] == 'time') {
            array_multisort(array_map(function ($element) {
                return $element['time'];
            }, $reviews), SORT_DESC, $reviews);
        }
    } else {
        $reviews = [];
    }
    return $reviews;
}

/**
 * calculate discounted price to show sale tag and off percentage tag
 */
function calculateDiscountPercentage($original_price, $discounted_price)
{
    return setDefaultPriceFormat(((($original_price - $discounted_price) / $original_price) * 100));
}

/**
 * Set site settings to session and fetch them using key
 */
function getSettingsByKey($key)
{
    if (is_null(session()->get(getConstant('APP_SETTINGS')))) {
        $settings = Setting::all()->toArray();
        session()->put(getConstant('APP_SETTINGS'), $settings);
    } else {
        $settings = session()->get(getConstant('APP_SETTINGS'));
    }

    $val = "";
    $res = array_search_multi_dim($settings, 'key', $key);
    if ($res['type'] != "boolean") {
        $val = $settings[$res['index']]['value'];
    }
    return $val;
}

/**
 * multi dimensional search
 */
function array_search_multi_dim($array, $column, $key)
{
    return ['index' => (array_search($key, array_column($array, $column))), 'type' => gettype((array_search($key, array_column($array, $column))))];
}

/**
 * get website logo
 */
function getWebsiteLogo()
{
    $val = getSettingsByKey("config_logo");
    if (file_exists(storage_path('app/public/config_logos/' . $val))) {
        $logo = $val;
    } else {
        $logo = "";
    }
    return $logo;
}

/**
 * get fav icon
 */
function getFavicon()
{
    $val = getSettingsByKey("config_favicon");
    if (file_exists(storage_path('app/public/config_favicons/' . $val))) {
        $logo = $val;
    } else {
        $logo = "";
    }
    return $logo;
}

/**
 * get google analytics
 */
function getGoogleAnalytics()
{
    return getSettingsByKey("config_google_analytics");
}

/**
 * get remaining amount from `payments` table
 */
function getRemainingAmountFromPayments($order_id)
{
    $payment = Payment::where('order_id', $order_id)->orderBy('id', 'DESC')->first();
    $remaining_amount = ($payment) ? $payment->remaining_amount : "0.00";
    $payment_exists = ($payment) ? true : false;
    return [$payment_exists, $remaining_amount];
}

/**
 * get slider using slug 
 */
function getSliderWithSlides($slug)
{
    return Slider::select('id', 'slug', 'name', 'sort_order', 'status', 'is_deleted')->with([
        'slides' => function ($q) {
            $q->select('id', 'slider_id', 'image', 'sort_order')
                ->orderBy('sort_order', 'ASC');
        }
    ])
        ->where('is_deleted', getConstant('IS_NOT_DELETED'))
        ->where('status', getConstant('IS_STATUS_ACTIVE'))
        ->where('slug', $slug)
        ->first();
}

### SANITIZE TELEPHONE ###
function sanitizeTelephone($string)
{
    $string = str_replace('-', '', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

/**
 * get product options
 */
function getProductOptions()
{
    return (new ProductOptionValue())->_getProductionOptions();
}

/**
 * carbon parse datetime to given format
 */
function formatGivenDateTime($datetime, $format)
{
    return Carbon::parse($datetime)->format($format);
}

/**
 * fetch all web notifications
 */
function getAllWebNotifications()
{
    return WebNotification::where('status', getConstant('IS_STATUS_ACTIVE'))
        ->where('is_deleted', getConstant('IS_NOT_DELETED'))
        ->orderBy('sort_order', 'ASC')
        ->get();
}

/**
 * check if user is subscribed to newsletter
 */
function isSubbedToNewsletter($email)
{
    $subbed = Subscriber::where('email', $email)
        ->where('is_subscribed', getConstant('SUBSCRIBED'))
        ->first();

    return ($subbed) ? true : false;
}

/**
 * Avg Rating Calculator
 */
function getAvgRating($approved_reviews_count, $approved_reviews)
{
    $reviews_total = 0;
    $avg_rating = 0;

    if ($approved_reviews_count) {
        foreach ($approved_reviews as $review) {
            $reviews_total += $review->rating;
        }
        $avg_rating = round(($reviews_total / $approved_reviews_count), 2);
    }

    return $avg_rating;
}

/**
 * get stores
 */
function getAllStores()
{
    return Store::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
}


/**
 * PayBright AutoCapture (Auth + Capture)
 */
function payBrightAutoCapture()
{
    $api_key = (getConstant('PAYBRIGHT_ENV') == "PRODUCTION") ? env("PAYBRIGHT_PRODUCTION_API_KEY") : env("PAYBRIGHT_SANDBOX_API_KEY");
    $api_token = (getConstant('PAYBRIGHT_ENV') == "PRODUCTION") ? env("PAYBRIGHT_PRODUCTION_API_TOKEN") : env("PAYBRIGHT_SANDBOX_API_TOKEN");
    $api_base_url = (getConstant('PAYBRIGHT_ENV') == "PRODUCTION") ? env("PAYBRIGHT_PRODUCTION_ENDPOINT") : env("PAYBRIGHT_SANDBOX_ENDPOINT");

    $body = array(
        'x_account_id' => $api_key,
        'x_amount' => '1100.00',
        'x_currency' => 'CAD',
        'x_customer_billing_address1' => '22 Viewcrest Cir',
        'x_customer_billing_city' => 'Etobicoke',
        'x_customer_billing_country' => 'CA',
        'x_customer_billing_phone' => '5196152481',
        'x_customer_billing_state' => 'ON',
        'x_customer_billing_zip' => 'M9W7G5',
        'x_customer_email' => 'test@paybright.com',
        'x_customer_first_name' => 'Jamie',
        'x_customer_last_name' => 'Testhet',
        'x_customer_phone' => '6139876543',
        'x_customer_shipping_address1' => '22 Viewcrest Cir',
        'x_customer_shipping_city' => 'Etobicoke',
        'x_customer_shipping_country' => 'CA',
        'x_customer_shipping_first_name' => 'Jamie',
        'x_customer_shipping_last_name' => 'Testhet',
        'x_customer_shipping_phone' => '6139876543',
        'x_customer_shipping_state' => 'ON',
        'x_customer_shipping_zip' => 'M9W7G5',
        'x_reference' => '2194779',
        'x_shop_country' => 'CA',
        'x_shop_name' => 'Paybright Test Store',
        'x_test' => 'true',
        'x_url_callback' => 'http://127.0.0.1:8000/ping/1',
        'x_url_cancel' => 'http://127.0.0.1:8000/',
        'x_url_complete' => 'http://127.0.0.1:8000/orders/1/done'
    );

    $bodyString = http_build_query($body);

    // signature creation for Auth API
    $signatureString = '';

    foreach (explode('&', $bodyString) as $chunk) {
        $param = explode("=", $chunk);
        if ($param && $param[1] != '') {
            $signatureString = $signatureString . urldecode($param[0]) . urldecode($param[1]);
        }
    }

    $pb_sig = hash_hmac('sha256', $signatureString, $api_token); // api token used here for creating signature
    $bodyString = $bodyString . '&x_signature=' . $pb_sig; // signature added to the API body

    // return json_encode($bodyString);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $LogFileHandle = fopen(storage_path('logs/curL.log'), "a+");
    curl_setopt($ch, CURLOPT_STDERR, $LogFileHandle);
    curl_setopt($ch, CURLOPT_URL, $api_base_url . "CheckOut/ApplicationForm.aspx");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyString);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    return curl_exec($ch);
}


/**
 * reCAPTCHA v3 validate token
 */
function validateCaptchaToken($token)
{
    $secretKey = getConstant("reCAPTCHA_SECRET");
    $res = ['status' => false, 'data' => ""];

    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array('secret' => $secretKey, 'response' => $token);

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $responseKeys = json_decode($response, true);
    header('Content-type: application/json');

    if ($responseKeys["success"]) {
        $res['status'] = true;
        $res['data'] = $responseKeys["success"];
    }
    else{
        $res['status'] = false;
    }

    return $res;
}

function getUserDiscount()
{
    if (Auth::guard('web')->user()->hasRole('Super Admin'))
    {
        return "9999999";
    }
    else{
        $user_discount = UserDiscount::where('user_id',Auth::guard('web')->user()->id)->first();
        if($user_discount)
        {
          return $user_discount->allowed_discount;
        }
        else{
            return "0.00";
        }
    }
}
