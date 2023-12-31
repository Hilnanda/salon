<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\Reply;
use App\Booking;
use App\BusinessService;
use App\Category;
use Illuminate\Support\Facades\DB;
use App\Payment;
use App\Role;
use App\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Arr;

class ReportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        view()->share('pageTitle', __('menu.reports'));
    }

    public function index() {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $labels = [
            'Today' => 'today',
            'Yesterday' => 'yesterday',
            'Last 7 Days' => 'lastWeek',
            'Last 30 Days' => 'lastThirtyDays',
            'This Month' => 'thisMonth',
            'Last Month' => 'lastMonth'
        ];


        $customers = User::all();
        $staffs = User::select('id', 'name')->with('roles')->whereHas('roles', function($q){
            $q->where('name', '<>', 'customer');
        })->get();
        $status = \request('status');
        $services = BusinessService::select('id', 'name')->get();

        return view('admin.report.layout', compact(['labels', 'customers', 'staffs', 'services', 'status']));
    }

    public function earningReportChart(Request $request) {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $payments = Payment::where('status', 'completed')
            ->whereDate('paid_on', '>=', Carbon::createFromFormat($this->settings->date_format, $request->startDate))
            ->whereDate('paid_on', '<=', Carbon::createFromFormat($this->settings->date_format, $request->endDate))
            ->groupBy('year', 'month')
            ->orderBy('amount', 'ASC')
            ->get(
                [
                    DB::raw('DATE_FORMAT(paid_on,"%D-%M-%Y") as pay_date'),
                    DB::raw('DATE_FORMAT(paid_on,"%M/%y") as date'),
                    DB::raw('YEAR(paid_on) year, MONTH(paid_on) month'),
                    DB::raw('sum(amount) as total')
                ]
            );

        $graphData = [];
            foreach($payments as $key2=>$payment){
                $payments[$key2]->total = $payment->total;
                $graphData[] = $payment;
            }

        usort(
            $graphData, function ($a, $b) {
                $t1 = strtotime($a->pay_date);
                $t2 = strtotime($b->pay_date);
                return $t1 - $t2;
            }
        );

        $labels = [];
        foreach($graphData as $gData){
            $labels[] = $gData->date;
        }

        $earnings = [];
        foreach($graphData as $gData){
            $earnings[] = round($gData->total, 2);
        }

        return Reply::dataOnly(['labels' => $labels, 'data' => $earnings, 'status' => 'success']);
    }

    public function earningTable(Request $request){
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $bookings = Booking::where('payment_status', 'completed')
            ->with('completedPayment')
            ->whereHas('completedPayment', function ($query) use ($request) {
                $query->whereDate('paid_on', '>=', Carbon::createFromFormat($this->settings->date_format, $request->startDate))
                ->whereDate('paid_on', '<=', Carbon::createFromFormat($this->settings->date_format, $request->endDate));
            })
            ->get();

        return \datatables()->of($bookings)
            ->editColumn('user_id', function ($row) {
                return ucwords($row->user->name);
            })
            ->editColumn('amount_to_pay', function ($row) {
                return number_format((float)$row->amount_to_pay, 2, '.', '');
            })
            ->editColumn('date_time', function ($row) {
                return $row->completedPayment->paid_on->format($this->settings->date_format);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'image', 'status'])
            ->toJson();
    }

    public function salesReportChart(Request $request)
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $labels = [];
        $sales = [];
        $servicesArr = [];

        $services = BusinessService::select('id', 'slug', 'name')->orderBy('name')->get();

        $bookings = Booking::with('items')->whereMonth('date_time', $request->month)->whereYear('date_time', $request->year)->where('payment_status', 'completed')->get();

        foreach ($services as $service) {
            $servicesArr = Arr::add($servicesArr, $service->id, ['name' => $service->name, 'sales' => 0]);
            $labels[] = $service->name;
        }

        foreach ($bookings as $booking) {
            foreach ($booking->items as $item) {
                $servicesArr[$item->business_service_id]['sales'] += $item->quantity;
            }
        }

        foreach ($servicesArr as $service) {
            $sales[] = $service['sales'];
        }

        return Reply::dataOnly(['labels' => $labels, 'data' => $sales, 'status' => 'success']);
    }

    public function salesTable(Request $request)
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $services = BusinessService::with('bookingItems', 'bookingItems.booking')
        ->whereHas('bookingItems.booking', function ($q) use ($request) {
            $q->whereMonth('date_time', $request->month)
            ->whereYear('date_time', $request->year)
            ->where('payment_status', 'completed');
        })->orderBy('name')
        ->get();

        // make booking items collection
        $items = [];
        foreach ($services as $service) {
            $bookingItems = $service->bookingItems()->whereHas('booking', function ($q) use ($request) {
                $q->whereMonth('date_time', $request->month)
                ->whereYear('date_time', $request->year)
                ->where('payment_status', 'completed');
            })->get();

            foreach ($bookingItems as $bookingItem) {
                $items[] = $bookingItem;
            }
        }

        return \datatables()->of(collect($items))
            ->editColumn('service_name', function ($row) {
                return ucwords($row->businessService->name);
            })
            ->editColumn('customer_name', function ($row) {
                return $row->booking->user->name;
            })
            ->editColumn('sales', function ($row) {
                return $row->quantity;
            })
            ->editColumn('amount', function ($row) {
                $taxAmount = $row->booking->tax_percent ? ($row->quantity * $row->unit_price * $row->booking->tax_percent / 100) : 0;
                $discountAmount = ($row->quantity * $row->unit_price * $row->booking->discount_percent / 100);

                $finalAmount = ($row->quantity * $row->unit_price) + $taxAmount - $discountAmount;
                return number_format((float) $finalAmount, 2, '.', '');
            })
            ->editColumn('paid_on', function ($row) {
                return $row->booking->completedPayment->paid_on->format($this->settings->date_format);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'image', 'status'])
            ->toJson();
    }

    public function tabularTable(Request $request)
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $items = [];
        $bookings = Booking::with('users', 'items', 'user');

        if($request->from_date && $request->to_date){
            $bookings->whereDate('date_time', '>=', $request->from_date)->whereDate('date_time', '<=', $request->to_date);
        }
        if($request->customer_id){
            $bookings->where('user_id', $request->customer_id);
        }
        if($request->service_id){
            $bookings->whereHas('items.businessService', function ($q) use ($request) {
                $q->where('id', $request->service_id);
            });
        }
        if($request->employee_id){
            $bookings->whereHas('users', function ($q) use ($request) {
                $q->where('id', $request->employee_id);
            });
        }
        if($request->booking_status){
            $bookings->where('status', $request->booking_status);
        }

        $bookings = $bookings->orderBy('id', 'desc')->get();
        foreach ($bookings as $booking)
        {
             $items[] = $booking;
        }

        return \datatables()->of(collect($items))
            ->editColumn('service_name', function ($row) {
                $booking_items = '<ol>';
                foreach($row->items as $item)
                {
                    $booking_items .= '<li>'.$item->businessService->name.' <b> X'.$item->quantity.'</b></li>';
                }
                $booking_items .= '</ol>';
                return $booking_items;
            })
            ->editColumn('customer_name', function ($row) {
                return '<i class="icon-user"></i> '.$row->user->name;
            })
            ->editColumn('employee_name', function ($row) {
                $booking_users = '';
                foreach($row->users as $user)
                {
                    $booking_users .= '<i class="icon-user"></i> '. ucfirst($user->name).' &nbsp;&nbsp;';
                }
                return $booking_users;
            })
            ->editColumn('amount', function ($row) {
                return $row->amount_to_pay;
            })
            ->editColumn('booking_time', function ($row) {
                return $row->date_time->format('h:i A');
            })
            ->editColumn('booking_source', function ($row) {
                return $row->source;
            })
            ->editColumn('booking_status', function ($row) {
                if($row->status=='approved')
                {
                    return '<span class="text-uppercase small border border-info text-info badge-pill">'.$row->status.'</span>';
                }
                elseif($row->status=='completed')
                {
                    return '<span class="text-uppercase small border border-success text-success badge-pill">'.$row->status.'</span>';
                }
                elseif($row->status=='pending')
                {
                    return '<span class="text-uppercase small border border-warning text-warning badge-pill">'.$row->status.'</span>';
                }
                elseif($row->status=='in progress')
                {
                    return '<span class="text-uppercase small border border-primary text-primary badge-pill">'.$row->status.'</span>';
                }
                return '<span class="text-uppercase small border border-danger text-danger badge-pill">'.$row->status.'</span>';

            })
            ->editColumn('booking_date', function ($row) {
                return $row->date_time->format($this->settings->date_format);
            })
            ->addIndexColumn()
            ->rawColumns(['employee_name', 'customer_name', 'booking_status', 'service_name'])
            ->make(true);
    }

    public function userTypeChart()
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $data = [];

        $record = Role::withoutGlobalScopes()
        ->withCount('users')
        ->groupBy('display_name')
        ->get();

        foreach($record as $row)
        {
            $data['label'][] = $row->display_name;
            $data['data'][] = $row->users_count;
        }
        return Reply::dataOnly(['data' => $data]);

    }

    public function serviceTypeChart()
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $data = [];

        $record = Category::withCount('services')->get();
        foreach($record as $row)
        {
            $data['label'][] = $row->name;
            $data['data'][] = (int) $row->services_count;
        }
        return Reply::dataOnly(['data' => $data]);

    }

    public function bookingSourceChart()
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $data = [];

        $record = Booking::groupBy('source')
                ->get([
                    'source as booking_source',
                    DB::raw('(select count(bookings.source) from `bookings` where source=booking_source) as countSource')
                ]);

        foreach($record as $row)
        {
            $data['label'][] = $row->booking_source;
            $data['data'][] = $row->countSource;
        }

        return Reply::dataOnly(['data' => $data]);

    }

    public function bookingPerDayChart(Request $request)
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $data = [];

        $record = Booking::select('id', 'date_time', 'source', DB::raw('count(*) as total'))->whereDate('date_time', Carbon::createFromFormat('Y-m-d', $request->booking_date))
        ->groupBy('source')
        ->get();

        foreach($record as $row)
        {
            $data['label'][] = $row->source;
            $data['data'][] = (int) $row->total;
        }
        return Reply::dataOnly(['data' => $data]);

    }

    public function bookingPerMonthChart(Request $request)
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $data = [];
        $check_month = [];
        $day_array = [];
        $no_of_days = Carbon::createFromFormat('Y-m', $request->booking_month)->daysInMonth;

        for ($i=1; $i <= $no_of_days ; $i++)
        {
            array_push($day_array, $i);
        }

        $record = Booking::select('id','date_time', DB::raw('DATE(date_time) as date'), DB::raw('count(*) as total'))
        ->whereMonth('date_time', Carbon::createFromFormat('Y-m', $request->booking_month))
        ->whereYear('date_time', Carbon::createFromFormat('Y-m', $request->booking_month))
        ->groupBy('date')
        ->get();

        foreach ($day_array as $key1 => $value)
        {
            /* if month is availble in table */
            foreach($record as $key2 => $row)
            {
                if(in_array(Carbon::parse($row->date)->format('d'), $day_array) && $day_array[$key1]==Carbon::parse($row->date)->format('d'))
                {
                    array_push($check_month, Carbon::parse($row->date)->format('d'));
                    $data['label'][] = $day_array[$key1];
                    $data['data'][] = (int) $row->total;
                }
            }
            /* if month is not availble in table */
            if(in_array($day_array[$key1], $day_array) && !in_array($day_array[$key1], $check_month))
            {
                $data['label'][] = $day_array[$key1];
                $data['data'][] = 0;

            }
        }
        return Reply::dataOnly(['data' => $data]);
    }

    public function bookingPerYearChart(Request $request)
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $data = [];
        $check_month = [];
        $months_array = [1,2,3,4,5,6,7,8,9,10,11,12];

        $record = Booking::whereYear('date_time', $request->booking_year)
        ->groupBy('year','month')
        ->get(
            [
                DB::raw('COUNT(id) as `total_bookings`'),
                DB::raw('YEAR(date_time) year, MONTH(date_time) month')
            ]
        );

        foreach ($months_array as $key1 => $value)
        {
            /* if month is available on table */
            foreach($record as $key2 => $row)
            {
                if(in_array($row->month, $months_array) && $row->month==$months_array[$key1])
                {
                    array_push($check_month, $row->month);
                    $data['label'][] = DateTime::createFromFormat('m', $row->month)->format('M');
                    $data['data'][] = (int) $row->total_bookings;
                }
            }
            /* if month is not available on table */
            if(in_array($months_array[$key1], $months_array) && !in_array($months_array[$key1], $check_month))
            {
                $data['label'][] = DateTime::createFromFormat('m', $months_array[$key1])->format('M');
                $data['data'][] = 0;
            }
        }
        return Reply::dataOnly(['data' => $data]);
    }

    public function paymentPerDayChart(Request $request)
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $data = [];

        $record = Payment::select('id', 'paid_on', 'gateway', DB::raw('sum(amount) as total'))
        ->where('status', 'completed')
        ->whereDate('paid_on', Carbon::createFromFormat($this->settings->date_format, $request->payment_date))
        ->groupBy('gateway')->get();

        foreach($record as $row)
        {
            $data['label'][] = $row->gateway;
            $data['data'][] = (int) $row->total;
        }
        return Reply::dataOnly(['data' => $data]);
    }

    public function paymentPerMonthChart(Request $request)
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $data = [];
        $check_month = [];
        $no_of_days = Carbon::createFromFormat('Y-m', $request->payment_month)->daysInMonth;
        $day_array = [];

        for ($i=1; $i<=$no_of_days; $i++)
        {
            array_push($day_array, $i);
        }

        $record = Payment::select('paid_on', DB::raw('DATE(paid_on) as date'),  DB::raw('sum(amount) as total'))
        ->where('status', 'completed')
        ->whereMonth('paid_on', Carbon::createFromFormat('Y-m', $request->payment_month))
        ->whereYear('paid_on', Carbon::createFromFormat('Y-m', $request->payment_month))
        ->groupBy('date')
        ->get();

        foreach ($day_array as $key1 => $value)
        {
            /* if day is available in table */
            foreach($record as $key2 => $row)
            {

                if(in_array(Carbon::parse($row->paid_on)->format('d'), $day_array) && $day_array[$key1]==Carbon::parse($row->paid_on)->format('d'))
                {
                    array_push($check_month, Carbon::parse($row->paid_on)->format('d'));
                    $data['label'][] = $day_array[$key1];
                    $data['data'][] = (int) $row->total;
                }
            }
            /* if day is not available in table */
            if(in_array($day_array[$key1], $day_array) && !in_array($day_array[$key1], $check_month))
            {
                $data['label'][] = $day_array[$key1];
                $data['data'][] = 0;

            }
        }
        return Reply::dataOnly(['data' => $data]);

    }

    public function paymentPerYearChart(Request $request)
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_report'), 403);

        $data = [];
        $check_month = [];
        $months_array = [1,2,3,4,5,6,7,8,9,10,11,12];

        $record = Payment::whereYear('paid_on', $request->payment_year)
        ->where('status', 'completed')
        ->groupBy('year','month')
        ->get(
            [
                DB::raw('SUM(amount) as `total_amount`'),
                DB::raw('YEAR(paid_on) year, MONTH(paid_on) month')
            ]
        );

        foreach ($months_array as $key1 => $value)
        {
            /* if month is availble on table */
            foreach($record as $key2 => $row)
            {
                if(in_array($row->month, $months_array) && $row->month==$months_array[$key1])
                {
                    array_push($check_month, $row->month);
                    $data['label'][] = DateTime::createFromFormat('m', $row->month)->format('M');
                    $data['data'][] = (int) $row->total_amount;
                }
            }
            /* if month is not available in table */
            if(in_array($months_array[$key1], $months_array) && !in_array($months_array[$key1], $check_month))
            {
                $data['label'][] = DateTime::createFromFormat('m', $months_array[$key1])->format('M');
                $data['data'][] = 0;
            }
        }
        return Reply::dataOnly(['data' => $data]);
    }

} /* end of class */
