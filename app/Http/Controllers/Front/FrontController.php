<?php

namespace App\Http\Controllers\Front;

use App\Booking;
use App\BookingTime;
use App\BusinessService;
use App\Category;
use App\CompanySetting;
use App\Coupon;
use App\Helper\Reply;
use App\Http\Requests\ApplyCoupon\ApplyRequest;
use App\Http\Requests\Front\CartPageRequest;
use App\Http\Requests\StoreFrontBooking;
use App\Location;
use App\Media;
use App\Notifications\BookingConfirmation;
use App\Notifications\NewBooking;
use App\Notifications\NewUser;
use App\PaymentGatewayCredentials;
use App\TaxSetting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\ContactRequest;
use App\Language;
use App\Notifications\NewContact;
use App\Page;
use App\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class FrontController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (request()->hasCookie('appointo_language_code')) {
            App::setLocale(decrypt(request()->cookie('appointo_language_code'), false));
        }
    }

    public function index()
    {
        $couponData = json_decode(request()->cookie('couponData'), true);
        if ($couponData) {
            setcookie("couponData", "", time() - 3600);
        }

        if (request()->ajax()) {
            $location = Location::where('id', request()->location)->first();

            $categories = Category::active()
                ->with([
                    'services' => function ($query) use ($location) {
                        if ($location !== null) {
                            $query->active()->where('location_id', $location->id);
                        } else {
                            $query->active();
                        }
                    }
                ])
                ->get();


            $services = BusinessService::active()->with('category');

            if ($location !== null) {
                $services = $services->where('location_id', $location->id);
            }

            $services = $services->get();

            return Reply::dataOnly(['categories' => $categories, 'services' => $services]);
        } else {
            $categories = Category::active()->with(['services' => function ($query) {
                $query->active();
            }])->get();
            $services = BusinessService::active()->get();
        }

        $images = Media::select('id', 'file_name')->latest()->get();

        return view('front.index', compact('categories', 'services', 'images'));
    }

    public function addOrUpdateProduct(Request $request)
    {
        $newProduct = [
            "servicePrice" => $request->servicePrice,
            "serviceName" => $request->serviceName
        ];

        $products = [];
        $serviceQuantity = $request->serviceQuantity ?? 1;

        if (!$request->hasCookie('products')) {
            $newProduct = Arr::add($newProduct, 'serviceQuantity', $serviceQuantity);
            $products = Arr::add($products, $request->serviceId, $newProduct);

            return response([
                'status' => 'success',
                'message' => __('messages.front.success.productAddedToCart'),
                'productsCount' => sizeof($products)
            ])->cookie('products', json_encode($products));
        }

        $products = json_decode($request->cookie('products'), true);

        if (!array_key_exists($request->serviceId, $products)) {
            $newProduct = Arr::add($newProduct, 'serviceQuantity', $serviceQuantity);
            $products = Arr::add($products, $request->serviceId, $newProduct);

            return response([
                'status' => 'success',
                'message' => __('messages.front.success.productAddedToCart'),
                'productsCount' => sizeof($products)
            ])->cookie('products', json_encode($products));
        } else {
            if ($request->serviceQuantity) {
                $products[$request->serviceId]['serviceQuantity'] = $request->serviceQuantity;
            } else {
                $products[$request->serviceId]['serviceQuantity'] += 1;
            }
        }


        return response([
            'status' => 'success',
            'message' => __('messages.front.success.cartUpdated'),
            'productsCount' => sizeof($products)
        ])->cookie('products', json_encode($products));
    }

    public function bookingPage(Request $request)
    {
        $bookingDetails = [];

        if ($request->hasCookie('bookingDetails')) {
            $bookingDetails = json_decode($request->cookie('bookingDetails'), true);
        }

        if ($request->ajax()) {
            return Reply::dataOnly(['status' => 'success', 'productsCount' => $this->productsCount]);
        }

        $locale = App::getLocale();

        return view('front.booking_page', compact('bookingDetails', 'locale'));
    }

    public function addBookingDetails(CartPageRequest $request)
    {
        $expireTime = Carbon::parse($request->bookingDate . ' ' . $request->bookingTime, $this->settings->timezone);
        $cookieTime = Carbon::now()->setTimezone($this->settings->timezone)->diffInMinutes($expireTime);

        return response(Reply::dataOnly(['status' => 'success']))->cookie('bookingDetails', json_encode(['bookingDate' => $request->bookingDate, 'bookingTime' => $request->bookingTime]), $cookieTime);
    }

    public function cartPage(Request $request)
    {
        $products       = json_decode($request->cookie('products'), true);
        $bookingDetails = json_decode($request->cookie('bookingDetails'), true);
        $couponData     = json_decode($request->cookie('couponData'), true);
        $tax = TaxSetting::active()->first();

        return view('front.cart_page', compact('products', 'bookingDetails', 'tax', 'couponData'));
    }

    public function deleteProduct(Request $request, $id)
    {
        $products = json_decode($request->cookie('products'), true);

        if ($id != 'all') {
           Arr::forget($products, $id);
        } else {

            return response(Reply::successWithData(__('messages.front.success.cartCleared'), ['action' => 'redirect', 'url' => route('front.cartPage'), 'productsCount' => sizeof($products)]))
                ->withCookie(Cookie::forget('bookingDetails'))
                ->withCookie(Cookie::forget('products'))
                ->withCookie(Cookie::forget('couponData'));
        }

        if (sizeof($products) > 0) {
            setcookie("products", "", time() - 3600);
            return response(Reply::successWithData(__('messages.front.success.productDeleted'), ['productsCount' => sizeof($products), 'products' => $products]))->cookie('products', json_encode($products));
        }

        return response(Reply::successWithData(__('messages.front.success.cartCleared'), ['action' => 'redirect', 'url' => route('front.cartPage'), 'productsCount' => sizeof($products)]))->withCookie(Cookie::forget('bookingDetails'))->withCookie(Cookie::forget('products'))->withCookie(Cookie::forget('couponData'));
    }

    public function updateCart(Request $request)
    {
        return response(Reply::success(__('messages.front.success.cartUpdated')))->cookie('products', json_encode($request->products));
    }

    public function checkoutPage()
    {
        $bookingDetails = request()->hasCookie('bookingDetails') ? json_decode(request()->cookie('bookingDetails'), true) : [];
        $couponData     = request()->hasCookie('couponData') ? json_decode(request()->cookie('couponData'), true) : [];
        $totalAmount    = array_reduce(json_decode(request()->cookie('products'), true), function ($sum, $item) {
            $sum += $item['servicePrice'] * $item['serviceQuantity'];
            return $sum;
        }, 0);
        $tax = TaxSetting::active()->first();

        if ($tax) {
            $totalAmount += ($tax->percent / 100) * $totalAmount;
        }

        if ($couponData) {
            $totalAmount -= $couponData['applyAmount'];
        }

        $totalAmount = round($totalAmount, 2);

        return view('front.checkout_page', compact('totalAmount', 'bookingDetails'));
    }

    public function paymentFail(Request $request, $bookingId = null)
    {
        $credentials = PaymentGatewayCredentials::first();
        if ($bookingId == null) {
            $booking = Booking::where([
                'user_id' => $this->user->id
            ])
                ->latest()
                ->first();
        } else {
            $booking = Booking::where(['id' => $bookingId, 'user_id' => $this->user->id])->first();
        }

        $setting = CompanySetting::with('currency')->first();
        $user = $this->user;

        return view('front.payment', compact('credentials', 'booking', 'user', 'setting'));
    }

    public function paymentSuccess(Request $request, $bookingId = null)
    {
        $credentials = PaymentGatewayCredentials::first();
        if ($bookingId == null) {
            $booking = Booking::where([
                'user_id' => $this->user->id
            ])
                ->latest()
                ->first();
        } else {
            $booking = Booking::where(['id' => $bookingId, 'user_id' => $this->user->id])->first();
        }

        $setting = CompanySetting::with('currency')->first();
        $user = $this->user;

        if ($booking->payment_status !== 'completed'){
            $booking->payment_status = 'completed';
            $booking->save();
        }

        return view('front.payment', compact('credentials', 'booking', 'user', 'setting'));
    }

    public function paymentGateway(Request $request)
    {
        $credentials = PaymentGatewayCredentials::first();
        $booking = Booking::where([
            'user_id' => $this->user->id
        ])
            ->latest()
            ->first();

        $setting = CompanySetting::with('currency')->first();
        $frontThemeSetting = $this->frontThemeSettings;
        $user = $this->user;

        if ($booking->payment_status == 'completed') {
            return redirect(route('front.index'));
        }

        return view('front.payment-gateway', compact('credentials', 'booking', 'user', 'setting', 'frontThemeSetting'));
    }

    public function offlinePayment($bookingId = null)
    {
        if ($bookingId == null) {
            $booking = Booking::where([
                'user_id' => $this->user->id
            ])
                ->latest()
                ->first();
        } else {
            $booking = Booking::where(['id' => $bookingId, 'user_id' => $this->user->id])->first();
        }

        if (!$booking || $booking->payment_status == 'completed') {
            return redirect()->route('front.index');
        }

        $booking->payment_status = 'pending';
        $booking->save();

        $admins = User::allAdministrators()->get();

        Notification::send($admins, new NewBooking($booking));
        $user = User::findOrFail($booking->user_id);
        $user->notify(new BookingConfirmation($booking));

        return view('front.booking_success');
    }

    public function bookingSlots(Request $request)
    {

        $bookingDate = Carbon::createFromFormat('Y-m-d', $request->bookingDate);
        $day = $bookingDate->format('l');
        $bookingTime = BookingTime::where('day', strtolower($day))->first();

        //check if multiple booking allowed
        $bookings = Booking::select('id', 'date_time')->where(DB::raw('DATE(date_time)'), $bookingDate->format('Y-m-d'));
        if ($bookingTime->multiple_booking == 'no') {
            $bookings = $bookings->get();
        }
        else {
            $bookings = $bookings->whereRaw('DAYOFWEEK(date_time) = '.($bookingDate->dayOfWeek + 1))->get();
        }

        $variables = compact('bookingTime', 'bookings');

        if ($bookingTime->status == 'enabled') {
            if ($bookingDate->day == Carbon::today()->day) {
                $startTime = Carbon::createFromFormat($this->settings->time_format, $bookingTime->utc_start_time);
                while ($startTime->lessThanOrEqualTo(Carbon::now())) {
                    $startTime = $startTime->addMinutes($bookingTime->slot_duration);
                }
            } else {
                $startTime = Carbon::createFromFormat($this->settings->time_format, $bookingTime->utc_start_time);
            }
            $endTime = Carbon::createFromFormat($this->settings->time_format, $bookingTime->utc_end_time);

            $startTime->setTimezone($this->settings->timezone);
            $endTime->setTimezone($this->settings->timezone);

            $startTime->setDate($bookingDate->year, $bookingDate->month, $bookingDate->day);
            $endTime->setDate($bookingDate->year, $bookingDate->month, $bookingDate->day);

            $variables = compact('startTime', 'endTime', 'bookingTime', 'bookings');
        }
        $view = view('front.booking_slots', $variables)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function saveBooking(StoreFrontBooking $request)
    {

        if ($this->user) {
            $user = $this->user;
        } else {
            $user = User::firstOrNew(['email' => $request->email]);
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->mobile = $request->phone;
            $user->calling_code = $request->calling_code;
            $user->password = '123456';
            $user->save();

            $user->attachRole(Role::where('name', 'customer')->withoutGlobalScopes()->first()->id);

            Auth::loginUsingId($user->id);
            $this->user = $user;

            if ($this->smsSettings->nexmo_status == 'active' && !$user->mobile_verified) {
                // verify user mobile number
                return response(Reply::redirect(route('front.checkoutPage'), __('messages.front.success.userCreated')));
            }

            $user->notify(new NewUser('123456'));
        }

        // get products and bookingDetails
        $products       = json_decode($request->cookie('products'), true);
        $bookingDetails = json_decode($request->cookie('bookingDetails'), true);

        if (is_null($products) || is_null($bookingDetails)) {
            return response(Reply::redirect(route('front.index')));
        }

        // Get Applied Coupon Details
        $couponData     = request()->hasCookie('couponData') ? json_decode(request()->cookie('couponData'), true) : [];

        // get bookings and bookingTime as per bookingDetails date
        $bookingDate = Carbon::createFromFormat('Y-m-d', $bookingDetails['bookingDate']);
        $day = $bookingDate->format('l');
        $bookingTime = BookingTime::where('day', strtolower($day))->first();

        $bookings = Booking::select('id', 'date_time')->where(DB::raw('DATE(date_time)'), $bookingDate->format('Y-m-d'))->whereRaw('DAYOFWEEK(date_time) = '.($bookingDate->dayOfWeek + 1))->get();


        if ($bookingTime->max_booking != 0 && $bookings->count() > $bookingTime->max_booking) {
            return response(Reply::redirect(route('front.bookingPage')))->withCookie(Cookie::forget('bookingDetails'));
        }

        $tax = TaxSetting::active()->first();
        $originalAmount = $taxAmount = $amountToPay = $discountAmount = $couponDiscountAmount = 0;

        $bookingItems = array();

        foreach ($products as $key => $product) {
            $amount = ($product['serviceQuantity'] * $product['servicePrice']);

            $bookingItems[] = [
                "business_service_id" => $key,
                "quantity" => $product['serviceQuantity'],
                "unit_price" => $product['servicePrice'],
                "amount" => $amount
            ];

            $originalAmount = ($originalAmount + $amount);
        }

        if (!is_null($tax) && $tax->percent > 0) {
            $taxAmount = (($tax->percent / 100) * $originalAmount);
        }

        $amountToPay = ($originalAmount + $taxAmount);

        if ($couponData) {
            $amountToPay -= $couponData['applyAmount'];
            $couponDiscountAmount = $couponData['applyAmount'];
        }

        $amountToPay = round($amountToPay, 2);

        $booking = new Booking();
        $booking->user_id = $user->id;
        $booking->date_time = Carbon::createFromFormat('Y-m-d', $bookingDetails['bookingDate'])->format('Y-m-d') . ' ' . Carbon::createFromFormat('H:i:s', $bookingDetails['bookingTime'])->format('H:i:s');
        $booking->status = 'pending';
        $booking->payment_gateway = 'cash';
        $booking->original_amount = $originalAmount;
        $booking->discount = $discountAmount;
        $booking->discount_percent = '0';
        $booking->payment_status = 'pending';
        $booking->additional_notes = $request->additional_notes;
        $booking->source = 'online';
        if (!is_null($tax)) {
            $booking->tax_name = $tax->tax_name;
            $booking->tax_percent = $tax->percent;
            $booking->tax_amount = $taxAmount;
        }
        if (sizeof($couponData) > 0 && !is_null($couponData)) {
            $booking->coupon_id = $couponData[0]['id'];
            $booking->coupon_discount = $couponDiscountAmount;
            $coupon = Coupon::findOrFail($couponData[0]['id']);
            $coupon->used_time = ($coupon->used_time + 1);
            $coupon->save();
        }
        $booking->amount_to_pay = $amountToPay;
        $booking->save();

        /* Assign Suggested User To Booking */
        if (!empty($_COOKIE['user_id']) && $_COOKIE['user_id']!=0) {
            $booking->users()->attach($_COOKIE['user_id']);
            setcookie("user_id", "", time() - 3600);
        }

        foreach ($bookingItems as $key => $bookingItem) {
            $bookingItems[$key]['booking_id'] = $booking->id;
        }

        DB::table('booking_items')->insert($bookingItems);

        return response(Reply::redirect(route('front.payment-gateway'), __('messages.front.success.bookingCreated')))->withCookie(Cookie::forget('bookingDetails'))->withCookie(Cookie::forget('couponData'))->withCookie(Cookie::forget('products'));
    }

    public function searchServices(Request $request)
    {
        $services = [];
        if ($request->search_term !== null) {
            $location = Location::where('id', request()->location)->first();

            $categories = Category::active()
                ->where('name', 'LIKE', '%' . strtolower($request->search_term) . '%')
                ->with(['services' => function ($q) use ($location) {
                    if ($location !== null) {
                        $q->active()->where('location_id', $location->id);
                    } else {
                        $q->active();
                    }
                }])->get();
            if ($categories->count() > 0) {
                foreach ($categories as $category) {
                    foreach ($category->services as $service) {
                        $services[] = $service;
                    }
                }
            }

            if ($location !== null) {
                $filteredServices = BusinessService::active()->where('name', 'LIKE', '%' . strtolower($request->search_term) . '%')->where('location_id', $location->id)->get();
            } else {
                $filteredServices = BusinessService::active()->where('name', 'LIKE', '%' . strtolower($request->search_term) . '%')->get();
            }

            foreach ($filteredServices as $service) {
                $services[] = $service;
            }

            $services = collect(array_unique($services));
        } else {
            $services = collect($services);
        }

        return view('front.search_page', compact('services'));
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return view('front.page', compact('page'));
    }

    public function contact(ContactRequest $request)
    {
        $users = User::select('id', 'email', 'name')->allAdministrators()->get();
        Notification::send($users, new NewContact());
        return Reply::success(__('messages.front.success.emailSent'));
    }

    public function serviceDetail(Request $request, $categorySlug, $serviceSlug)
    {
        $service = BusinessService::where('slug', $serviceSlug)->first();

        $products = json_decode($request->cookie('products'), true) ?: [];
        $reqProduct = array_filter($products, function ($product) use ($service) {
            return $product['serviceName'] == $service->name;
        });

        return view('front.service_detail', compact('service', 'reqProduct'));
    }

    public function changeLanguage($code)
    {
        $language = Language::where('language_code', $code)->first();

        if (!$language) {
            return Reply::error('invalid language code');
        }

        return response(Reply::success(__('messages.languageChangedSuccessfully')))->withCookie(cookie('appointo_language_code', $code));
    }

    /**
     * @param Request $request
     * @return $this|array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function applyCoupon(ApplyRequest $request)
    {
        $couponTitle = strtolower($request->coupon);
        $products    = json_decode($request->cookie('products'), true);
        $tax         = TaxSetting::active()->first();

        $productAmount = 0;

        if(!$products){
            return Reply::error(__('messages.coupon.addProduct'));
        }

        foreach ($products as $product){
            $productAmount += $product['servicePrice'] * $product['serviceQuantity'];
        }

        if($tax==null)
        {
            $percentAmount = 0;
        }
        else
        {
            $percentAmount = ($tax->percent / 100) * $productAmount;
        }

        $totalAmount   = ($productAmount + $percentAmount);

        $currentDate = Carbon::now()->format('Y-m-d H:i:s');

        $couponData = Coupon::where('coupons.start_date_time', '<=', $currentDate)
            ->where(function ($query) use($currentDate) {
                $query->whereNull('coupons.end_date_time')
                    ->orWhere('coupons.end_date_time', '>=', $currentDate);
            })
            ->where('coupons.status', 'active')
            ->where('coupons.title', $couponTitle)
            ->first();

        if (!is_null($couponData)  && $couponData->minimum_purchase_amount != 0 && $couponData->minimum_purchase_amount != null && $productAmount < $couponData->minimum_purchase_amount)
        {
            return Reply::error(__('messages.coupon.minimumAmount').' '.$this->settings->currency->currency_symbol.$couponData->minimum_purchase_amount);
        }

        if (!is_null($couponData) && $couponData->used_time >= $couponData->uses_limit && $couponData->uses_limit != null && $couponData->uses_limit != 0)
        {
            return Reply::error(__('messages.coupon.usedMaximun'));
        }

        if (!is_null($couponData)) {
            $days = json_decode($couponData->days);
            $currentDay = Carbon::now()->format('l');
            if (in_array($currentDay, $days)) {
                if (!is_null($couponData->percent) && $couponData->percent != 0) {
                    $percentAmnt = round(($couponData->percent / 100) * $totalAmount, 2);
                    if (!is_null($couponData->amount) && $percentAmnt >= $couponData->amount) {
                        $percentAmnt = $couponData->amount;
                    }
                    return response(Reply::dataOnly(['amount' => $percentAmnt,'couponData' => $couponData]))->cookie('couponData',json_encode([$couponData,'applyAmount' => $percentAmnt]));
                } elseif (!is_null($couponData->amount) && (is_null($couponData->percent) || $couponData->percent == 0)) {
                    return response(Reply::dataOnly(['amount' => $couponData->amount,'couponData' => $couponData]))->cookie('couponData', json_encode([$couponData,'applyAmount' => $couponData->amount]));
                }
            } else {
                return response(
                    Reply::error(__('messages.coupon.notValidToday',
                        ['day' => __('app.'.strtolower($currentDay))]
                    )
                ));
            }
        }
        return Reply::error(__('messages.coupon.notMatched'));
    }


    /**
     * @param Request $request
     * @return $this|array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updateCoupon(Request $request)
    {
        $couponTitle = strtolower($request->coupon);
        $products    = json_decode($request->cookie('products'), true);
        $tax         = TaxSetting::active()->first();

        $productAmount = 0;


        foreach ($products as $product){
            $productAmount += $product['servicePrice'] * $product['serviceQuantity'];
        }

        $percentAmount = ($tax->percent / 100) * $productAmount;
        $totalAmount   = ($productAmount + $percentAmount);

        $currentDate = Carbon::now()->format('Y-m-d H:i:s');

        $couponData = Coupon::where('coupons.start_date_time', '<=', $currentDate)
            ->where(function ($query) use($currentDate) {
                $query->whereNull('coupons.end_date_time')
                    ->orWhere('coupons.end_date_time', '>=', $currentDate);
            })
            ->where('coupons.status', 'active')
            ->where('coupons.title', $couponTitle)
            ->first();

        if (!is_null($couponData)  && $couponData->minimum_purchase_amount != 0 && $couponData->minimum_purchase_amount != null && $productAmount < $couponData->minimum_purchase_amount)
        {
            return Reply::errorWithoutMessage();        }

        if (!is_null($couponData) && $couponData->used_time >= $couponData->uses_limit && $couponData->uses_limit != null && $couponData->uses_limit != 0)
        {
            return Reply::errorWithoutMessage();
        }

        if (!is_null($couponData) && $productAmount > 0) {
            $days = json_decode($couponData->days);
            $currentDay = Carbon::now()->format('l');
            if (in_array($currentDay, $days)) {
                if (!is_null($couponData->percent) && $couponData->percent != 0) {
                    $percentAmnt = round(($couponData->percent / 100) * $totalAmount, 2);
                    if (!is_null($couponData->amount) && $percentAmnt >= $couponData->amount) {
                        $percentAmnt = $couponData->amount;
                    }
                    return response(Reply::dataOnly(['amount' => $percentAmnt,'couponData' => $couponData]))->cookie('couponData',json_encode([$couponData,'applyAmount' => $percentAmnt]));
                } elseif (!is_null($couponData->amount) && (is_null($couponData->percent) || $couponData->percent == 0)) {
                    return response(Reply::dataOnly(['amount' => $couponData->amount,'couponData' => $couponData]))->cookie('couponData', json_encode([$couponData,'applyAmount' => $couponData->amount]));
                }
            } else {
                return Reply::errorWithoutMessage();
            }
        }
        return Reply::errorWithoutMessage();
    }

    /**
     * @param Request $request
     * @return $this|array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function removeCoupon(Request $request)
    {
        return response(Reply::dataOnly([]))->withCookie(Cookie::forget('couponData'));
    }


    public function checkUserAvailability(Request $request)
    {
        $all_users_array = [];
        $assigned_user_list_array = [];
        $product_ids_of_cart_elements = [];
        $multiTaskingUser = CompanySetting::first()->multi_task_user;

        $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $request->date, $this->settings->timezone)->setTimezone('UTC');

        $all_users = User::select('id', 'name')->with('roles')->whereHas('roles', function($q){
            $q->where('name', '<>', 'customer');
        })->get();
        foreach ($all_users as $key => $value)
        {
            $all_users_array[] = $value->id;
        }

        $assigned_users_list =  Booking::with('users')
        ->where('date_time' , $dateTime)
        ->get();
        foreach ($assigned_users_list as $key => $value)
        {
            foreach ($value->users as $key1 => $value1)
            {
                $assigned_user_list_array[] = $value1->id;
            }
        }

        $free_user_list = array_diff($all_users_array, $assigned_user_list_array);


        /* GET PRODUCT IDS OF SEVICES */
         $products = json_decode($request->cookie('products'), true);
         foreach($products as $key => $product)
         {
             $product_ids_of_cart_elements[] = $key;
         }


         $suggested_user_when_some_are_assigned = User::with('services')
         ->whereIn('id', $free_user_list)
        ->whereHas('services', function($q)use($product_ids_of_cart_elements){
            $q->whereIn('id', $product_ids_of_cart_elements);
        })
        ->pluck('id')
        ->first();

        $suggested_user_from_all_user_list = User::with('services')
        ->whereHas('services', function($q)use($product_ids_of_cart_elements){
            $q->whereIn('id', $product_ids_of_cart_elements);
        })
        ->pluck('id')
        ->first();

        if($suggested_user_when_some_are_assigned==null)
        {
            if($suggested_user_from_all_user_list==null)
            {
                return response(Reply::dataOnly(['continue_booking'=>'yes']));
            }
            if($multiTaskingUser==null)
            {
                /* block booking here */
                return response(Reply::dataOnly(['continue_booking'=>'no']));
            }
            /* return user from suggested_user_from_all_user_list */
            setcookie("user_id", $suggested_user_from_all_user_list, time() + (86400 * 30), "/");
            return response(Reply::dataOnly(['continue_booking'=>'yes', 'user_id'=>$suggested_user_from_all_user_list]));
        }
        /* return user from suggested_user_when_some_are_assigned */
        setcookie("user_id", $suggested_user_when_some_are_assigned, time() + (86400 * 30), "/");
        return response(Reply::dataOnly(['continue_booking'=>'yes', 'user_id'=>$suggested_user_when_some_are_assigned]));
    }




} /* End of main class */
