<?php

namespace App\Http\Controllers\Admin;


use App\Coupon;
use App\CouponUser;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Coupon\StoreRequest;
use App\Http\Requests\Coupon\UpdateRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Location;

class CouponController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        view()->share('pageTitle', __('menu.coupons'));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {
        return view('admin.coupons.index');
    }

    public function data()
    {
        $coupon = Coupon::all();

        return \datatables()->of($coupon)
            ->addColumn('action', function ($row) {
                $action = '';
                if ($this->user->is_admin) {
                    $action.= '<a href="' . route('admin.coupons.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                      data-toggle="tooltip" data-original-title="'.__('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
                    $action.= '<a href="javascript:;" data-row-id="' . $row->id . '" class="btn btn-info btn-circle view-coupon"
                      data-toggle="tooltip" data-original-title="'.__('app.view').'"><i class="fa fa-search" aria-hidden="true"></i></a> ';

                    $action.= ' <a href="javascript:;" class="btn btn-danger btn-circle delete-row"
                        data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="'.__('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }

                return $action;
            })

            ->editColumn('title', function ($row) {
                return strtoupper($row->title);
            })
            ->editColumn('start_date_time', function ($row) {
                return $row->start_date_time->format($this->settings->date_format.' '.$this->settings->time_format);
            })
            ->editColumn('end_date_time', function ($row) {
                if($row->end_date_time){
                    return $row->end_date_time->format($this->settings->date_format.' '.$this->settings->time_format);
                }
                return '-';
            })
            ->editColumn('amount', function ($row) {
                if($row->amount && is_null($row->percent)){
                    return $row->amount;
                }
                elseif(is_null($row->amount) && !is_null($row->percent)){
                    return $row->percent.'%';
                }
                elseif(!is_null($row->amount) && !is_null($row->percent)){
                    return __('app.maxAmountOrPercent', ['percent' => $row->percent, 'maxAmount' => $row->amount]);
                }
            })
            ->editColumn('status', function ($row) {
                if($row->status == 'active'){
                    return '<label class="badge badge-success">'.__("app.active").'</label>';
                }
                elseif($row->status == 'inactive'){
                    return '<label class="badge badge-danger">'.__("app.inactive").'</label>';
                }
                elseif($row->status == 'expire'){
                    return '<label class="badge badge-danger">'.__("app.expire").'</label>';
                }
            })

            ->addIndexColumn()
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->days = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];
        return view('admin.coupons.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        if(!$request->has('days')){
            return Reply::error( __('messages.coupon.selectDay'));
        }

        $startDate = Carbon::createFromFormat('Y-m-d H:i a', $request->start_time)->format('Y-m-d H:i:s');

        $coupon = new Coupon();

        $coupon->title                   = strtolower($request->title);
        $coupon->start_date_time         = $startDate;
        $coupon->uses_limit              = $request->uses_time;
        $coupon->amount                  = $request->amount;
        $coupon->percent                 = $request->percent;
        $coupon->minimum_purchase_amount = ($request->minimum_purchase_amount) ? $request->minimum_purchase_amount : 0;
        $coupon->days                    = json_encode($request->days);
        $coupon->description             =  $request->description;
        $coupon->status                  =  $request->status;

        if($request->end_time){
            $coupon->end_date_time       = Carbon::createFromFormat('Y-m-d H:i a', $request->end_time)->format('Y-m-d H:i:s');
        }

        $coupon->save();

        return Reply::redirect(route('admin.coupons.index'), __('messages.createdSuccessfully'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->coupon = Coupon::findOrFail($id);

        if($this->coupon->days){
            $this->days = json_decode($this->coupon->days);
        }

        return view('admin.coupons.show', $this->data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
       $this->days = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];

        $this->coupon       = Coupon::with('customers')->findOrFail($id);
        $this->selectedDays = json_decode($this->coupon->days);

        return view('admin.coupons.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
     {
         if(!$request->has('days')){
             return Reply::error( __('messages.coupon.selectDay'));
         }

        $startDate = Carbon::createFromFormat('Y-m-d H:i a', $request->start_time)->format('Y-m-d H:i:s');

        $coupon = Coupon::findOrFail($id);

        $coupon->title                   = strtolower($request->title);
        $coupon->start_date_time         = $startDate;
        $coupon->uses_limit              = $request->uses_time;
        $coupon->amount                  = $request->amount;
        $coupon->percent                 = $request->percent;
        $coupon->minimum_purchase_amount = ($request->minimum_purchase_amount) ? $request->minimum_purchase_amount : 0;
        $coupon->days                    = json_encode($request->days);
        $coupon->status                  =  $request->status;
        $coupon->description             =  $request->description;

        if($request->end_time){
            $coupon->end_date_time       = Carbon::createFromFormat('Y-m-d H:i a', $request->end_time)->format('Y-m-d H:i:s');
        }

        $coupon->save();

        return Reply::redirect(route('admin.coupons.index'), __('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return Reply::success(__('messages.recordDeleted'));
    }

}
