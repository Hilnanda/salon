<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <h6>@lang('app.dateRange')</h6>
                        <div id="reportrange" class="form-group"
                            style="background: #fff; cursor: pointer; padding: 15px 20px; border: 1px solid #ccc; width: 100%">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                            <input type="hidden" id="start-date">
                            <input type="hidden" id="end-date">
                        </div>
                    </div>
                </div>
                <!-- Custom Tabs -->
                <div class="card">
                    <div class="card-header d-flex p-0">
                        <h3 class="card-title p-3">@lang('menu.earningReport')</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div id="earning-graph-container">
                                    <canvas id="earningChart" style="height: 400px !important"></canvas>
                                </div>

                                <hr>
                                <div class="table-responsive">

                                    <table id="earningTable" class="table" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('app.booking') #</th>
                                                <th>@lang('app.customer')</th>
                                                <th>@lang('app.amount')</th>
                                                <th>@lang('app.paid_on')</th>
                                            </tr>
                                        </thead>
                                    </table>

                                </div>

                            </div>
                            <!-- /myTable -->
                        </div>
                        <!-- /.carmyTable -->
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
        $(function() {
            var start = moment().subtract(90, 'days');
            var end = moment();

            function renderTranslatedNames() {
                @foreach($labels as $key => $label)
                    $(`.daterangepicker li[data-range-key='{{ $key }}']`).html("@lang('app.daterangepicker.'.$label)");
                @endforeach
            }

            function cb(start, end) {
                $('#reportrange span').html(start.format('{{ $date_picker_format }}') + ' - ' + end.format('{{ $date_picker_format }}'));
                $('#start-date').val(start.format('{{ $date_picker_format }}'));
                $('#end-date').val(end.format('{{ $date_picker_format }}'));

                chartRequest(
                    '{{ route("admin.reports.earningReportChart") }}',
                    {
                        startDate: $('#start-date').val(),
                        endDate: $('#end-date').val()
                    },
                    'earningChart',
                    'earning-graph-container',
                    '@lang("app.amount")'
                );
                renderTable(
                    'earningTable',
                    '{!! route('admin.reports.earningTable') !!}', {
                        "startDate": $('#start-date').val(),
                        "endDate": $('#end-date').val()
                    },
                    [
                        { data: 'user_id', name: 'user_id' },
                        { data: 'amount_to_pay', name: 'amount_to_pay' },
                        { data: 'date_time', name: 'date_time' }
                    ]
                );
            }

            moment.locale('{{ $settings->locale }}');

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                locale: {
                    format: "MM/DD/YYYY",
                    separator: " - ",
                    applyLabel: "@lang('app.apply')",
                    cancelLabel: "@lang('app.cancel')",
                    customRangeLabel: "@lang('app.daterangepicker.custom')"
                },
                ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            },
            cb);

            cb(start, end);

            renderTranslatedNames();
        });
    </script>
@endpush
