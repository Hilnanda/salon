<style>
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
<form action="" id="update-form" class="ajax-form">
    <?php echo method_field('PUT'); ?>
    <?php echo csrf_field(); ?>
<div class="row mt-2 mb-3">
    <div class="col-md-4 border-right"> <strong><?php echo app('translator')->getFromJson('app.name'); ?></strong> <br>
        <p class="text-muted"><i class="icon-user"></i> <?php echo e(ucwords($booking->user->name)); ?></p>
    </div>
    <div class="col-md-4 border-right"> <strong><?php echo app('translator')->getFromJson('app.email'); ?></strong> <br>
        <p class="text-muted"><i class="icon-email"></i> <?php echo e($booking->user->email ?? '--'); ?></p>
    </div>
    <div class="col-md-4"> <strong><?php echo app('translator')->getFromJson('app.mobile'); ?></strong> <br>
        <p class="text-muted"><i class="icon-mobile"></i> <?php echo e($booking->user->mobile ? $booking->user->formatted_mobile : '--'); ?></p>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-4"> <strong><?php echo app('translator')->getFromJson('app.booking'); ?> <?php echo app('translator')->getFromJson('app.date'); ?></strong> <br>
        <div class="form-group">
            <input type="text" class="form-control datepicker" name="booking_date" value="<?php echo e($booking->date_time->format($settings->date_format)); ?>">
        </div>
    </div>
    <div class="col-sm-4"> <strong><?php echo app('translator')->getFromJson('app.booking'); ?> <?php echo app('translator')->getFromJson('app.time'); ?></strong> <br>
        <div class="form-group">
            <div class="input-group date time-picker">
                <input type="text" class="form-control" name="booking_time" value="<?php echo e($booking->date_time->format($settings->time_format)); ?>">
                <span class="input-group-append input-group-addon">
                                <button type="button" class="btn btn-default"><span class="fa fa-clock-o"></span></button>
                            </span>
            </div>
        </div>
    </div>
    <div class="col-sm-4"> <strong><?php echo app('translator')->getFromJson('app.booking'); ?> <?php echo app('translator')->getFromJson('app.status'); ?></strong> <br>
        <div class="form-group">
            <select name="status" id="booking-status" class="form-control">
                <option value="completed" <?php if($booking->status == 'completed'): ?> selected <?php endif; ?>><?php echo app('translator')->getFromJson('app.completed'); ?></option>
                <option value="pending" <?php if($booking->status == 'pending'): ?> selected <?php endif; ?>><?php echo app('translator')->getFromJson('app.pending'); ?></option>
                <option value="approved" <?php if($booking->status == 'approved'): ?> selected <?php endif; ?>><?php echo app('translator')->getFromJson('app.approved'); ?></option>
                <option value="in progress" <?php if($booking->status == 'in progress'): ?> selected <?php endif; ?>><?php echo app('translator')->getFromJson('app.in progress'); ?></option>
                <option value="canceled" <?php if($booking->status == 'canceled'): ?> selected <?php endif; ?>><?php echo app('translator')->getFromJson('app.canceled'); ?></option>
            </select>
        </div>
    </div>
</div>
    <hr>
<div class="row">
    <div class="col-sm-12"> <strong><?php echo app('translator')->getFromJson('menu.employee'); ?></strong> <br>
        <div class="form-group">
            <select name="employee_id[]" id="employee_id" class="form-control" multiple="multiple" style="width: 100%">
                <option value=""> <?php echo app('translator')->getFromJson('app.selectEmployee'); ?> </option>
                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option
                            <?php if(in_array($employee->id, $selected_booking_user)): ?> selected <?php endif; ?>
                    value="<?php echo e($employee->id); ?>"><?php echo e($employee->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-md-12 mb-2">
        <div class="dropdown">
            <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown">
               <i class="fa fa-plus"></i> <?php echo app('translator')->getFromJson('app.add'); ?> <?php echo app('translator')->getFromJson('app.item'); ?>
            </button>
            <div class="dropdown-menu">
                <?php $__currentLoopData = $businessServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a class="dropdown-item add-item"
                       data-price="<?php echo e($service->discounted_price); ?>"
                       data-service-id="<?php echo e($service->id); ?>" href="javascript:;"><?php echo e(ucwords($service->name)); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <table class="table table-condensed" id="cart-table">
            <thead class="bg-secondary">
            <tr>
                <th><?php echo app('translator')->getFromJson('app.item'); ?></th>
                <th><?php echo app('translator')->getFromJson('app.unitPrice'); ?></th>
                <th width="120"><?php echo app('translator')->getFromJson('app.quantity'); ?></th>
                <th class="text-right"><?php echo app('translator')->getFromJson('app.amount'); ?></th>
                <th><i class="icon-settings"></i></th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $booking->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><input type="hidden" name="cart_services[]" value="<?php echo e($item->business_service_id); ?>">
                        <?php echo e(ucwords($item->businessService->name)); ?></td>
                    <td><input type="hidden" name="cart_prices[]" class="cart-price-<?php echo e($item->business_service_id); ?>" value="<?php echo e(number_format((float)$item->unit_price, 2, '.', '')); ?>"><?php echo e($settings->currency->currency_symbol.number_format((float)$item->unit_price, 2, '.', '')); ?></td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-default quantity-minus" data-service-id="<?php echo e($item->business_service_id); ?>"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="text" readonly name="cart_quantity[]" data-service-id="<?php echo e($item->business_service_id); ?>" class="form-control cart-service-<?php echo e($item->business_service_id); ?>" value="<?php echo e($item->quantity); ?>">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default quantity-plus" id="btn<?php echo e($item->business_service_id); ?>" data-service-id="<?php echo e($item->business_service_id); ?>"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </td>
                    <td class="text-right cart-subtotal-<?php echo e($item->business_service_id); ?>"><?php echo e($settings->currency->currency_symbol.number_format((float)($item->businessService->discounted_price  * $item->quantity), 2, '.', '')); ?></td>
                    <td>
                        <a href="javascript:;" class="btn btn-danger btn-sm btn-circle delete-cart-row"><i class="fa fa-times" aria-hidden="true"></i></a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

        </table>
    </div>
    <div class="col-md-6 border-top">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-condensed">
                    <tr class="h6">
                        <td class="border-top-0"><?php echo app('translator')->getFromJson('modules.booking.paymentMethod'); ?></td>
                        <td class="border-top-0 "><i class="fa fa-money"></i> <?php echo e($booking->payment_gateway); ?></td>
                    </tr>
                    <tr class="h6">
                        <td><?php echo app('translator')->getFromJson('modules.booking.paymentStatus'); ?></td>
                        <td><div class="form-group">
                                <select name="payment_status" id="payment-status" class="form-control select2">
                                    <option value="completed" <?php if($booking->payment_status == 'completed'): ?> selected <?php endif; ?>><?php echo app('translator')->getFromJson('app.completed'); ?></option>
                                    <option value="pending" <?php if($booking->payment_status == 'pending'): ?> selected <?php endif; ?>><?php echo app('translator')->getFromJson('app.pending'); ?></option>
                                </select>
                            </div></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 border-top">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-condensed">
                    <tr class="h6">
                        <td class="border-top-0 text-right w-50"><?php echo app('translator')->getFromJson('app.subTotal'); ?></td>
                        <td class="border-top-0" id="cart-sub-total"><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->original_amount, 2, '.', '')); ?></td>
                    </tr>

                    <tr class="h6">
                        <td class="text-right"><?php echo app('translator')->getFromJson('app.discount'); ?></td>
                        <td><input type="number" id="cart-discount" name="cart_discount" class="form-control" step=".01" min="0" value="<?php echo e($booking->discount_percent); ?>"></td>
                    </tr>

                    <?php if($booking->tax_amount > 0): ?>
                    <tr class="h6">
                        <input type="hidden" id="cart-tax" name="cart_tax" value="<?php echo e($booking->tax_percent); ?>">
                        <td class="text-right"><?php echo e($booking->tax_name.' ('.$booking->tax_percent.'%)'); ?></td>
                        <td id="cart-tax-amount"><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->tax_amount, 2, '.', '')); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($booking->coupon_discount > 0): ?>
                        <tr class="h6">
                            <input type="hidden" id="coupon_id" name="coupon_id" value="<?php echo e($booking->coupon_id); ?>">
                            <input type="hidden" id="coupon_amount" name="coupon_amount" value="<?php echo e($booking->coupon_discount); ?>">
                            <td class="text-right"><?php echo app('translator')->getFromJson('app.couponDiscount'); ?> (<?php echo e($booking->coupon->title); ?>)</td>
                            <td id="couponAmount"><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->coupon_discount, 2, '.', '')); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr class="h5">
                        <td class="text-right"><?php echo app('translator')->getFromJson('app.total'); ?></td>
                        <td id="cart-total"><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->amount_to_pay, 2, '.', '')); ?>

                            <input type="hidden"  id="cart-total-input">
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div class="mt-2">
                <button class="btn btn-outline-danger delete-row" data-row-id="<?php echo e($booking->id); ?>" type="button"><i class="fa fa-times"></i> <?php echo app('translator')->getFromJson('app.delete'); ?> <?php echo app('translator')->getFromJson('app.booking'); ?></button>
            </div>
            <div class="mt-2">
                <button type="button" id="update-booking" data-booking-id="<?php echo e($booking->id); ?>" class="btn btn-success"><i class="fa fa-check"></i> <?php echo app('translator')->getFromJson('app.update'); ?></button>
                <div id="cart-item-error" class="invalid-feedback"></div>
            </div>
        </div>
    </div>
</div>
</form>

<script>
    var couponAmount = 0;
    var couponCodeValue = '';
//    var couponApplied = false;

    $("#employee_id").select2({
        placeholder: "Select Employee",
        allowClear: true
    });


    $('.datepicker').datetimepicker({
        format: '<?php echo e($date_picker_format); ?>',
        locale: '<?php echo e($settings->locale); ?>',
        allowInputToggle: true,
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }
    });

    $('.time-picker').datetimepicker({
        format: '<?php echo e($time_picker_format); ?>',
        locale: '<?php echo e($settings->locale); ?>',
        allowInputToggle: true,
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }
    });

    $("#cart-table").on('change', "input[name='cart_quantity[]']", function () {
        let serviceId = $(this).data('service-id');
        let qty = $(this).val();

        updateCartQuantity(serviceId, qty);
    });

    $('#cart-table').on('click', '.quantity-minus', function () {
        let serviceId = $(this).data('service-id');

        let qty = $('.cart-service-'+serviceId).val();
        qty = parseInt(qty)-1;

        if(qty < 1){
            qty = 1;
        }
        $('.cart-service-'+serviceId).val(qty);

        updateCartQuantity(serviceId, qty);
    });

    $('#cart-table').on('click', '.quantity-plus', function () {
        let serviceId = $(this).data('service-id');

        let qty = $('.cart-service-'+serviceId).val();
        qty = parseInt(qty)+1;

        $('.cart-service-'+serviceId).val(qty);

        updateCartQuantity(serviceId, qty);
    });

    function updateCartQuantity(serviceId, qty) {

        let servicePrice = $('.cart-price-'+serviceId).val();

        let subTotal = (parseFloat(servicePrice) * parseInt(qty));

        $('.cart-subtotal-'+serviceId).html("<?php echo e($settings->currency->currency_symbol); ?>"+subTotal.toFixed(2));

        calculateTotal();
        updateCoupon ();
    }


    $('#cart-table').on('click', '.delete-cart-row', function () {
        $(this).closest('tr').remove();
        calculateTotal();
        updateCoupon ();
    });

    $('#cart-discount').keyup(function () {
        if ($(this).val() == '') {
            $(this).val(0);
        }
        if ($(this).val() > 100) {
            $(this).val(100);
        }
        calculateTotal();
        updateCoupon ();
    });

    $('#cart-tax').change(function () {
        calculateTotal();
        updateCoupon ();
    });

    function calculateTotal() {
        let cartTotal = 0;
        let cartSubTotal = 0;
        let cartDiscount = $('#cart-discount').val();
        let cartTax = $('#cart-tax').val();
        let discount = 0;
        let tax = 0;

        $("input[name='cart_prices[]']").each(function( index ) {
            let servicePrice = $(this).val();
            let qty = $("input[name='cart_quantity[]']").eq(index).val();
            cartSubTotal = (cartSubTotal + (parseFloat(servicePrice) * parseInt(qty)));
        });

        $("#cart-sub-total").html("<?php echo e($settings->currency->currency_symbol); ?>"+cartSubTotal.toFixed(2));

        if(parseFloat(cartDiscount) > 0){
            if(cartDiscount > 100) cartDiscount = 100;

            discount = ((parseFloat(cartDiscount)/100)*cartSubTotal);

        }
        cartSubTotal = (parseFloat(cartSubTotal) - discount).toFixed(2);

        if(parseFloat(cartTax) > 0){
            tax = ((parseFloat(cartTax)/100)*cartSubTotal);
            $('#cart-tax-amount').html("<?php echo e($settings->currency->currency_symbol); ?>"+tax.toFixed(2));
        }

        cartTotal = (parseFloat(cartSubTotal) + tax);

        couponAmount = $('#coupon_amount').val();
        if(couponAmount)
        {
            if(cartTotal>couponAmount)
            {
                cartTotal =  (cartTotal - couponAmount);
            }
            else
            {
                cartTotal = 0;
            }
        }

        cartTotal =  parseFloat(cartTotal).toFixed(2);

        $("#cart-total-input").val(cartTotal);

        $("#cart-total").html("<?php echo e($settings->currency->currency_symbol); ?>"+cartTotal);
        $("#payment-modal-total").html("<?php echo e($settings->currency->currency_symbol); ?>"+cartTotal);
    }

    $('.add-item').click(function () {
        let serviceId = $(this).data('service-id');
        let serviceName = $(this).html();
        let servicePrice = parseFloat($(this).data('price')).toFixed(2);
        let serviceItems = $('#cart-table tbody tr td:first-child input[type="hidden"]');
        let serviceItemsCount = 0;

        let item = '<tr>\n' +
            '                    <td><input type="hidden" name="cart_services[]" value="'+serviceId+'">\n' +
            '                        '+serviceName+'</td>\n' +
            '                    <td><input type="hidden" name="cart_prices[]" class="cart-price-'+serviceId+'" value="'+servicePrice+'"><?php echo e($settings->currency->currency_symbol); ?>'+servicePrice+'</td>\n' +
            '                    <td>\n' +
            '                        <div class="input-group">\n' +
            '                            <div class="input-group-prepend">\n' +
            '                                <button type="button" class="btn btn-default quantity-minus" data-service-id="'+serviceId+'"><i class="fa fa-minus"></i></button>\n' +
            '                            </div>\n' +
            '                            <input type="text" readonly name="cart_quantity[]" data-service-id="'+serviceId+'" class="form-control cart-service-'+serviceId+'" value="1">\n' +
            '                            <div class="input-group-append">\n' +
            '                                <button type="button" class="btn btn-default quantity-plus" id="btn'+serviceId+'" data-service-id="'+serviceId+'"><i class="fa fa-plus"></i></button>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                    </td>\n' +
            '                    <td class="text-right cart-subtotal-'+serviceId+'"><?php echo e($settings->currency->currency_symbol); ?>'+servicePrice+'</td>\n' +
            '                    <td>\n' +
            '                        <a href="javascript:;" class="btn btn-danger btn-sm btn-circle delete-cart-row"><i class="fa fa-times" aria-hidden="true"></i></a>\n' +
            '                    </td>\n' +
            '                </tr>';


        serviceItems.each(function()
        {
            if(this.value==serviceId)
            {
                serviceItemsCount += 1;
            }
        });

        if(serviceItemsCount>0)
        {
            $('#btn'+serviceId).click();
        }
        else
        {
            $('#cart-table tbody').append(item);
        }

        calculateTotal();
        updateCoupon ();

    });

    // Update coupon during change discount
    function updateCoupon () {

        let cartTotal = 0;
        let cartSubTotal = 0;
        let cartDiscount = $('#cart-discount').val();
        let cartTax = $('#cart-tax').val();
        let discount = 0;
        let tax = 0;

        $("input[name='cart_prices[]']").each(function( index ) {
            let servicePrice = $(this).val();
            let qty = $("input[name='cart_quantity[]']").eq(index).val();
            cartSubTotal = (cartSubTotal + (parseFloat(servicePrice) * parseInt(qty)));
        });

        $("#cart-sub-total").html("<?php echo e($settings->currency->currency_symbol); ?>"+cartSubTotal.toFixed(2));

        if(parseFloat(cartDiscount) > 0){
            if(cartDiscount > 100) cartDiscount = 100;

            discount = ((parseFloat(cartDiscount)/100)*cartSubTotal);

        }
        cartSubTotal = (parseFloat(cartSubTotal) - discount).toFixed(2);

        if(parseFloat(cartTax) > 0){
            tax = ((parseFloat(cartTax)/100)*cartSubTotal);
            $('#cart-tax-amount').html("<?php echo e($settings->currency->currency_symbol); ?>"+tax.toFixed(2));
        }

        cartTotal = (parseFloat(cartSubTotal) + tax).toFixed(2);


        <?php if($booking->coupon_id): ?>

            cartSubTotal   = 0;
            var cart_discount  = $('#cart-discount').val();
            var cartServices   = [];
            var coupon_id      = <?php echo e($booking->coupon_id); ?>;

            $("input[name='cart_prices[]']").each(function( index ) {
                let servicePrice = $(this).val();
                let qty = $("input[name='cart_quantity[]']").eq(index).val();
                cartServices = (cartSubTotal + (parseFloat(servicePrice) * parseInt(qty)));
            });

            if(cartServices === undefined || cartServices === "" || cartServices === null ||
                cartServices.length <= 0){
                return false;
            }

            var currencySymbol = '<?php echo e($settings->currency->currency_symbol); ?>';
            var token = '<?php echo e(csrf_token()); ?>';

            $.easyAjax({
                url: '<?php echo e(route('admin.bookings.update-coupon')); ?>',
                type: 'POST',
                data: {'_token':token,'coupon_id':coupon_id, 'cart_discount': cart_discount, 'cart_services': cartServices},
                success: function (response) {
                    if(response.status != 'fail'){
                        couponAmount = response.amount;
                        if(couponAmount>cartTotal)
                        {
                            couponAmount = cartTotal;
                        }
                        $('#couponAmount').html(currencySymbol+couponAmount);
                        $('#coupon_amount').val(couponAmount);
                        calculateTotal();
                    }
                }
            });

        <?php endif; ?>
    }

</script>
<?php /**PATH G:\Pekerjaan\Simetris\salon\salon\resources\views/admin/booking/edit.blade.php ENDPATH**/ ?>