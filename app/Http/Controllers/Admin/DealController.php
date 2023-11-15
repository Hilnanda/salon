<?php

namespace App\Http\Controllers\Admin;

use App\Deal;
use App\Helper\Files;
use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Deal\StoreRequest;
use App\Http\Requests\Deal\UpdateRequest;
use App\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        parent::__construct();
        view()->share('pageTitle', __('menu.deals'));

    }


    public function index()
    {
        abort_if(!$this->user->roles()->withoutGlobalScopes()->first()->hasPermission('read_location'), 403);

        if(request()->ajax())
        {
            $deals = Deal::get();

            return datatables()->of($deals)
                ->addColumn('action', function ($row) {
                    $action = '';

                    $action.= '<a href="' . route('admin.deals.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                    data-toggle="tooltip" data-original-title="'.__('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';

                    $action.= ' <a href="javascript:;" data-row-id="' . $row->id . '" class="btn btn-info btn-circle view-deal"
                      data-toggle="tooltip" data-original-title="'.__('app.view').'"><i class="fa fa-search" aria-hidden="true"></i></a> ';

                    $action.= ' <a href="javascript:;" class="btn btn-danger btn-circle delete-row"
                    data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="'.__('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>';

                    return $action;
                })
                ->addColumn('image', function ($row) {
                    return '<img src="'.$row->user_image_url.'" class="img" height="65em" width="65em"/> ';
                })
                ->editColumn('title', function ($row) {
                    return ucfirst($row->title);
                })
                ->editColumn('start_date_time', function ($row) {
                    return Carbon::parse($row->start_date_time)->format($this->settings->date_format.' '.$this->settings->time_format);

                })
                ->editColumn('end_date_time', function ($row) {
                    return Carbon::parse($row->end_date_time)->format($this->settings->date_format.' '.$this->settings->time_format);
                })
                ->editColumn('original_amount', function ($row) {
                    return $row->original_amount;
                })
                ->editColumn('deal_amount', function ($row) {
                    return $row->deal_amount;
                })
                ->editColumn('status', function ($row) {
                    if($row->status == 'active'){
                        return '<label class="badge badge-success">'.__("app.active").'</label>';
                    }
                    elseif($row->status == 'inactive'){
                        return '<label class="badge badge-danger">'.__("app.inactive").'</label>';
                    }
                })
                ->addColumn('deal_locations', function ($row) {
                    $deal_locations = '<ol>';
                    foreach ($row->locations as $key => $location)
                    {
                        $deal_locations .= '<li>'.$location->name.'</li>';
                    }
                    $deal_locations .= '</ol>';
                    return $deal_locations;
                })
                ->addIndexColumn()
                ->rawColumns(['action', 'image', 'status', 'deal_locations'])
                ->toJson();
        }
        return view('admin.deals.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        $locations = Location::all();
        return view('admin.deals.create', compact('days', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        if(!$request->has('days')){
            return Reply::error( __('messages.coupon.selectDay'));
        }
        $startDate = Carbon::createFromFormat('Y-m-d H:i a', $request->start_date_time)->format('Y-m-d H:i:s');
        $endDate = Carbon::createFromFormat('Y-m-d H:i a', $request->end_date_time)->format('Y-m-d H:i:s');
        $startTime = Carbon::createFromFormat($this->settings->time_format, $request->open_time)->format('H:i:s');
        $EndTime  = Carbon::createFromFormat($this->settings->time_format, $request->close_time)->format('H:i:s');

        $deal = new Deal();
        $deal->title                   = strtolower($request->title);
        $deal->start_date_time         = $startDate;
        $deal->end_date_time           = $endDate;
        $deal->open_time               = $startTime;
        $deal->close_time              = $EndTime;
        $deal->original_amount         = $request->original_amount;
        $deal->deal_amount             = $request->discount_amount;
        $deal->uses_limit              = $request->uses_time;
        $deal->status                  = $request->status;
        $deal->days                    = json_encode($request->days);
        $deal->description             = $request->description;

        if ($request->hasFile('feature_image')) {
            $deal->image = Files::upload($request->feature_image,'deal');
        }

        $deal->save();

        $locations = $request->locations;
        if($locations)
        {
            $assignedLocation   = array();
            foreach ($locations as $key => $location)
            {
                $assignedLocation[] = $locations[$key];
            }
            $deal->locations()->attach($assignedLocation);
        }
        return Reply::redirect(route('admin.deals.index'), __('messages.createdSuccessfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deal = Deal::findOrFail($id);
        if($deal->days){
            $days = json_decode($deal->days);
        }
        $locations = $deal->locations;
        return view('admin.deals.show', compact('deal', 'days', 'locations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        $selectedLocations = [];
        $all_locations = Location::all();
        $deal = Deal::with('locations')->findOrFail($id);
        $selectedDays = json_decode($deal->days);

        foreach($deal->locations as $location){
            $selectedLocations[] = $location->id;
        }

        return view('admin.deals.edit', compact('days', 'deal', 'selectedLocations', 'selectedDays', 'all_locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        if(!$request->has('days')){
            return Reply::error( __('messages.coupon.selectDay'));
        }
        $startDate = Carbon::createFromFormat('Y-m-d H:i a', $request->start_date_time)->format('Y-m-d H:i:s');
        $endDate = Carbon::createFromFormat('Y-m-d H:i a', $request->end_date_time)->format('Y-m-d H:i:s');
        $startTime = Carbon::createFromFormat($this->settings->time_format, $request->open_time)->format('H:i:s');
        $EndTime  = Carbon::createFromFormat($this->settings->time_format, $request->close_time)->format('H:i:s');

        $deal = Deal::findOrFail($id);
        $deal->title                   = strtolower($request->title);
        $deal->start_date_time         = $startDate;
        $deal->end_date_time           = $endDate;
        $deal->open_time               = $startTime;
        $deal->close_time              = $EndTime;
        $deal->original_amount         = $request->original_amount;
        $deal->deal_amount             = $request->discount_amount;
        $deal->uses_limit              = $request->uses_time;
        $deal->status                  = $request->status;
        $deal->days                    = json_encode($request->days);
        $deal->description             = $request->description;

        if ($request->hasFile('feature_image')) {
            $deal->image = Files::upload($request->feature_image,'deal');
        }

        $deal->save();

        $locations = $request->locations;
        if($locations){
            $assignedLocation   = array();
            foreach ($locations as $key => $location){
                $assignedLocation[] = $locations[$key];
            }
            $deal->locations()->sync($assignedLocation);
        }
        else{
            $deal->locations()->detach();
        }

        return Reply::redirect(route('admin.deals.index'), __('messages.createdSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon = Deal::findOrFail($id);
        $coupon->delete();

        return Reply::success(__('messages.recordDeleted'));
    }
}
