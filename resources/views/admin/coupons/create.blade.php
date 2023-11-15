@extends('layouts.master')

@push('head-css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">

<style>
    .collapse.in{
        display: block;
    }
</style>

@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">@lang('app.add') @lang('menu.coupon')</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form role="form" id="createForm"  class="ajax-form" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.coupon') @lang('app.code') </label>
                                    <input type="text" class="form-control" name="title" value="" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.StartTime')</label>
                                    <input type="text" class="form-control" id="start_time" name="start_time" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.endTime')</label>
                                    <input type="text" class="form-control" id="end_time"  name="end_time" autocomplete="off">
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
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.amount')</label>
                                    <input type="number" class="form-control" name="amount">
                                    <span class="help-block">@lang('messages.coupon.forAmountDiscountOrMaximumDiscount')</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.percent')</label>
                                    <input type="number" class="form-control" name="percent">
                                    <span class="help-block">@lang('messages.coupon.forPercentDiscount')</span>
                                </div>
                             </div>
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>@lang('app.minimumPurchaseAmount')</label>
                                    <input type="number" class="form-control" name="minimum_purchase_amount">
                                    <span class="help-block">@lang('messages.coupon.keepBlankForwithoutMinimumAmount')</span>
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
                                <div class="form-group">
                                    <label for="name">@lang('app.description')</label>
                                    <textarea name="description" id="description" cols="30" class="form-control-lg form-control" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="button" id="save-form" class="btn btn-success btn-light-round"><i
                                            class="fa fa-check"></i> @lang('app.save')</button>
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
<script src="{{ asset('assets/js/collaps.js') }}"></script>
<script src="{{ asset('assets/js/transition.js') }}"></script>



    <script>
        $(function () {
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
            $('#customers').select2();
        })

        $('#end_time').datetimepicker({
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
        $('#start_time').datetimepicker({
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
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
        })
        $('.dropify').dropify({
            messages: {
                default: '@lang("app.dragDrop")',
                replace: '@lang("app.dragDropReplace")',
                remove: '@lang("app.remove")',
                error: '@lang('app.largeFile')'
            }
        });
        $('#save-form').click(function () {

            $.easyAjax({
                url: '{{route('admin.coupons.store')}}',
                container: '#createForm',
                type: "POST",
                redirect: true,
                data:$('#createForm').serialize(),
            })
        });

    </script>

@endpush
