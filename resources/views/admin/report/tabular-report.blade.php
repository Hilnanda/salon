<div class="row">

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="card">
                    <div class="card-header d-flex p-3">
                            <div class="container-fluid">
                                <form id="formFilter" action="#">
                                <div class="row">
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="email" class="font-weight-bold">@lang('report.bookingBetweenDate')</label>
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                <div class="calendar">
                                                <input type="text" class="form-control " id="from_date" placeholder="@lang('app.choose') @lang('report.fromDate')" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="calendar">
                                                    <input type="text" class="form-control " id="to_date" placeholder="@lang('app.choose') @lang('report.toDate')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="email" class="font-weight-bold">@lang('app.customer')</label>
                                        <select name="customer_id" id="customer_id" class="form-control select2" style="width:100%">
                                            <option selected value="">@lang('modules.booking.selectCustomer'): @lang('app.viewAll')</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ ucwords($customer->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="email" class="font-weight-bold">@lang('app.service')</label>
                                        <select name="service_id" id="service_id" class="form-control select2" style="width:100%">
                                            <option selected value="">@lang('app.filter') @lang('app.service'): @lang('app.viewAll')</option>
                                            @foreach ($services as $service)
                                                <option value="{{$service->id}}">{{$service->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="email" class="font-weight-bold">@lang('app.employee')</label>
                                        <select name="employee_id" id="employee_id" class="form-control select2" style="width:100%">
                                            <option selected value="">@lang('app.filter') @lang('app.employee'): @lang('app.viewAll')</option>
                                            @foreach ($staffs as $staff)
                                                <option value="{{$staff->id}}">{{$staff->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="email" class="font-weight-bold">@lang('report.bookingStatus')</label>
                                        <select style="width:100%" selected name="booking_status" id="booking_status" class="form-control select2">
                                            <option value="">@lang('app.filter') @lang('app.status'): @lang('app.viewAll')</option>
                                            <option value="completed">@lang('app.completed')</option>
                                            <option value="pending">@lang('app.pending')</option>
                                            <option value="approved">@lang('app.approved')</option>
                                            <option value="in progress">@lang('app.in progress')</option>
                                            <option value="canceled">@lang('app.canceled')</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 text-right">
                                    <button type="button" id="filter" class="btn btn-primary"><i class="fa fa-filter"></i> @lang('app.filter')</button>
                                    <button type="reset" id="resetbtn" class="btn btn-danger"><i class="fa fa-times"></i> @lang('app.reset')</button>
                                </div>
                            </div>
                            </form>
                            </div>


                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="table-responsive">
                                    <table id="tabularTable" class="table" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('app.service') #</th>
                                                <th>@lang('app.customer')</th>
                                                <th>@lang('report.bookingDate')</th>
                                                <th>@lang('report.bookingTime')</th>
                                                <th>@lang('app.serviceName')</th>
                                                <th>@lang('app.employee')</th>
                                                <th>@lang('app.status')</th>
                                                <th>@lang('app.amount')</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- ./card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </div>
</div>

@push('footer-js')
<script>

    $(function()
    {

        // $('.calendardatePicker').on('click', function () {
        //     $(this).find('input').datepicker('show');
        // });

        $('.calendar input').datepicker({
            format: "yyyy-mm-dd",
            language: "{{ $settings->locale }}",
            autoclose: true,
            todayHighlight: true,
        });

        function tab_report(from_date, to_date, customer_id, service_id, employee_id, booking_status)
        {
            renderTable(
                'tabularTable',
                '{!! route('admin.reports.tabularTable') !!}',
                {
                    from_date: from_date,
                    to_date: to_date,
                    customer_id : customer_id,
                    service_id : service_id,
                    employee_id : employee_id,
                    booking_status : booking_status
                },
                [
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'booking_date', name: 'booking_date' },
                    { data: 'booking_time', name: 'booking_time' },
                    { data: 'service_name', name: 'service_name' },
                    { data: 'employee_name', name: 'employee_name' },
                    { data: 'booking_status', name: 'booking_status' },
                    { data: 'amount', name: 'amount' },
                ]
                );
            }

            tab_report($("#from_date").val(), $('#to_date').val(), $('#customer_id').val(), $('#service_id').val(), $('#employee_id').val(), $('#booking_status').val());

            $('#filter').click(function()
            {
                if( ($("#from_date").val()!='' && $('#to_date').val()=='') || ($("#from_date").val()=='' && $('#to_date').val()!=''))
                {
                    if($("#from_date").val()==''){
                        $('#from_date').focus();
                    }
                    else{
                        $('#to_date').focus();
                    }
                    return toastr.error('@lang("report.invalidDateSelection")');
                }
                tab_report($("#from_date").val(), $('#to_date').val(), $('#customer_id').val(), $('#service_id').val(), $('#employee_id').val(), $('#booking_status').val());
            });
            $('#resetbtn').click(function()
            {
                $("#formFilter").trigger("reset");
                $("#customer_id").val('').trigger('change');
                $("#service_id").val('').trigger('change');
                $("#employee_id").val('').trigger('change');
                $("#booking_status").val('').trigger('change');
                tab_report($("#from_date").val(), $('#to_date').val(), $('#customer_id').val(), $('#service_id').val(), $('#employee_id').val(), $('#booking_status').val());
            });

        });

        </script>
@endpush
