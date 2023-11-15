@extends('layouts.master')

@push('head-css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">

<style>
    .collapse.in{
        display: block;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #999;
    }
    .select2-dropdown .select2-search__field:focus, .select2-search--inline .select2-search__field:focus {
        border: 0px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        margin: 0 13px;
    }
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #cfd1da;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__clear {
        cursor: pointer;
        float: right;
        font-weight: bold;
        margin-top: 8px;
        margin-right: 15px;
    }
</style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">@lang('app.add') @lang('menu.deal')</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form role="form" id="createForm"  class="ajax-form" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.title') </label>
                                    <input type="text" class="form-control" name="title" value="" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.StartTime')</label>
                                    <input type="text" class="form-control dateTimePicker" id="start_time" name="start_date_time" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.endTime')</label>
                                    <input type="text" class="form-control dateTimePicker" id="end_time"  name="end_date_time" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group time-picker">
                                    <label>@lang('modules.settings.openTime')</label>
                                    <input type="text" class="form-control" id="open_time" name="open_time" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group time-picker">
                                    <label>@lang('modules.settings.closeTime')</label>
                                    <input type="text" class="form-control" id="close_time"  name="close_time" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.originalPrice')</label>
                                    <input type="number" class="form-control checkAmount" name="original_amount" id="original_amount">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.dealPrice')</label>
                                    <input type="number" class="form-control checkAmount" name="discount_amount" id="discount_amount">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.usesTime')</label>
                                    <input type="number" class="form-control" name="uses_time">
                                    <span class="help-block">@lang('messages.howManyTimeUserCanUse')</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">@lang('app.status')</label>
                                    <select name="status" class="form-control">
                                        <option value="active"> @lang('app.active') </option>
                                        <option value="inactive"> @lang('app.inactive') </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <label style="margin-left: 10px">@lang('app.dayForApply') </label>
                                    @forelse($days as $day)
                                        <div class="form-group" style="margin-left: 20px">
                                            <label class="">
                                                <div class="icheckbox_flat-green" aria-checked="false" aria-disabled="false" style="position: relative; margin-right: 5px">
                                                    <input type="checkbox" checked value="{{$day}}" name="days[]" class="flat-red columnCheck"  style="position: absolute; opacity: 0;">
                                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                </div>
                                                @lang('app.'. strtolower($day))
                                            </label>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('app.location')</label>
                                    <select name="locations[]" id="locations" class="form-control form-control-lg select2" multiple="multiple" style="width: 100%">
                                        <option value="">@lang('app.selectLocation')</option>
                                        @foreach($locations as $locations)
                                            <option value="{{ $locations->id }}">{{ $locations->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('app.description')</label>
                                    <textarea name="description" id="description" cols="30" class="form-control-lg form-control" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">@lang('app.image')</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="file" id="input-file-now" name="feature_image" accept=".png,.jpg,.jpeg" data-default-file="{{ asset('img/no-image.jpg')  }}" class="dropify"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="button" id="save-form" class="btn btn-success btn-light-round">
                                    <i class="fa fa-check"></i> @lang('app.save')
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@push('footer-js')
<script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>

    <script>
        $('.dropify').dropify({
            messages: {
                default: '@lang("app.dragDrop")',
                replace: '@lang("app.dragDropReplace")',
                remove: '@lang("app.remove")',
                error: '@lang('app.largeFile')'
            }
        });

        $('.time-picker').datetimepicker({
            format: '{{ $time_picker_format }}',
            allowInputToggle: true,
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
            },
            useCurrent: false,
        });

        $(function () {
            $('.dateTimePicker').datetimepicker({
                format: 'Y-M-D hh:mm A',
                locale: '{{ $settings->locale }}',
                icons: {
                    time: "fa fa-clock-o",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
                    previous: "fa fa-angle-double-left",
                    next: "fa fa-angle-double-right",
                },
                useCurrent: false,
            });



            $('#description').summernote({
                dialogsInBody: true,
                height: 300,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['fontsize', ['fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ["view", ["fullscreen"]]
                ]
            });
        });


        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.deals.store')}}',
                container: '#createForm',
                type: "POST",
                redirect: true,
                data:$('#createForm').serialize(),
                file:true
            })
        });

        $('.checkAmount').keyup(function () {
            var original_amount = $('#original_amount').val();
            var discount_amount = $('#discount_amount').val();
            if(original_amount!='' && discount_amount!='' && Number(discount_amount) > Number(original_amount))
            {
                $('#discount_amount').focus();
                $('#discount_amount').val('');
            }
        });

    </script>

@endpush
