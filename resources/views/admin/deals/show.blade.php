<div class="modal-header">
    <h4 class="modal-title">@lang('menu.deal') @lang('app.detail')</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="row">
                <div class="col-12">
                    <img src="{{$deal->user_image_url}}" class="img img-responsive img-thumbnail" width="100%">
                </div>

                <div class="col-md-12">
                    <br>
                    <h6 class="text-uppercase">@lang('app.title')</h6>
                    <p>{{ $deal->title }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-uppercase">@lang('app.StartTime')</h6>
                    <p>{{ $deal->StartDate }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-uppercase">@lang('app.endTime')</h6>
                    <p>{{ $deal->EndDate }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-uppercase">@lang('app.appliedBeweenTime')</h6>
                    <p>{{ $deal->applied_between_time }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-uppercase">@lang('app.usesTime')</h6>
                    <p>
                        @if($deal->uses_limit > 0)
                        {{ $deal->uses_limit }}
                        @else
                            @lang('app.infinite')
                        @endif
                    </p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-uppercase">@lang('app.dealUsedTime')</h6>
                    <p>
                        @if($deal->used_time !='')
                        {{ $deal->used_time }}
                        @else
                            0
                        @endif
                    </p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-uppercase">@lang('app.originalPrice')</h6>
                    <p>{{ $deal->original_amount }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-uppercase">@lang('app.dealPrice')</h6>
                    <p>{{ $deal->deal_amount }}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-uppercase">@lang('app.dayForApply')</h6>
                    <p>
                        @if(sizeof($days) == 7)
                            @lang('app.allDays')
                        @else
                            @forelse($days as $day)
                                <span style="margin-left: 20px"> @lang('app.'. strtolower($day)) </span>
                            @empty
                            @endforelse
                        @endif
                    </p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-uppercase">@lang('app.location')</h6>
                    <p>
                        @if(sizeof($locations)>0)
                            @forelse($locations as $location)
                                <span style="margin-left: 20px">{{$location->name}}</span>
                            @empty
                            @endforelse
                        @else
                            --
                        @endif
                    </p>
                </div>

                @if(!is_null($deal->description))
                    <div class="col-md-12">
                        <h6 class="text-uppercase">@lang('app.description')</h6>
                        <p>{!! $deal->description !!} </p>
                    </div>
                @endif

            </div>
    </div>
</div>
