<style>
    /* Gaya CSS untuk membuat border kotak */
    .container {
            display: flex;
            justify-content: center;
        }

        .border-box {
            width: 270px;
            /* height: 300px; */
            border: 2px solid #333;
            padding: 10px;
            box-sizing: border-box;
        }
        .line {
            width: 100%;
            border-top: 2px solid #333;
            /* margin: 20px auto; */
        }

        .hidden-fitur {
            display: none;
        }
        
</style>

<div class="row" >
    
    <div class="col-md-12 text-right mt-2 mb-2">
        <?php if($user->can('update_booking')): ?>
        <button class="btn btn-sm btn-outline-primary edit-booking" data-booking-id="<?php echo e($booking->id); ?>" type="button"><i class="fa fa-edit"></i> <?php echo app('translator')->getFromJson('app.edit'); ?></button>
        <?php endif; ?>
        <?php if($user->roles()->withoutGlobalScopes()->first()->hasPermission('delete_booking')): ?>
        <button class="btn btn-sm btn-outline-danger delete-row" data-row-id="<?php echo e($booking->id); ?>" type="button"><i class="fa fa-times"></i> <?php echo app('translator')->getFromJson('app.delete'); ?> <?php echo app('translator')->getFromJson('app.booking'); ?></button>
        <?php endif; ?>
        <?php if($booking->status == 'pending'): ?>
            <?php if($user->roles()->withoutGlobalScopes()->first()->hasPermission('create_booking') && $booking->date_time->greaterThanOrEqualTo(\Carbon\Carbon::now())): ?>
            <a href="javascript:;" data-booking-id="<?php echo e($booking->id); ?>" class="btn btn-outline-dark btn-sm send-reminder"><i class="fa fa-send"></i> <?php echo app('translator')->getFromJson('modules.booking.sendReminder'); ?></a>
            <?php endif; ?>
            <?php if($user->roles()->withoutGlobalScopes()->first()->hasPermission('update_booking')): ?>
            <button class="btn btn-sm btn-outline-danger cancel-row" data-row-id="<?php echo e($booking->id); ?>" type="button"><i class="fa fa-times"></i> <?php echo app('translator')->getFromJson('modules.booking.requestCancellation'); ?></button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <div class="container">
        <!-- Kotak dengan Border di Tengah -->
        <div class="border-box">
            <!-- Isi konten di sini -->
            <div class="row" >
                <div class="col-md-12" style="text-align: center">
                    <img src="<?php echo e(asset('user-uploads/logo/logo.png')); ?>"  height="150em" width="150em">
                    <div class="row">
                        <div class="col-md-6" style="text-align: left">
                            <b><?php echo e($booking->date_time->format($settings->date_format)); ?></b>
                        </div>
                        <div class="col-md-6">
                            <b><?php echo e($booking->date_time->format($settings->time_format)); ?></b>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-12">
                    <table>
                        <tr>
                            <td>Nama Kasir </td>
                            <td> : </td>
                            <td>
                                <?php if(count($booking->users)>0): ?>
                                <?php $__currentLoopData = $booking->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($user->name); ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Cabang</td>
                            <td> : </td>
                            <td>Sukabumi</td>
                        </tr>
                    </table>
                    <hr class="line">
                    
                </div>
                <div class="col-md-6" style="text-align: center"><b>Layanan</b></div>
                <div class="col-md-6" style="text-align: center"><b>Harga</b></div>
                <div class="col-md-12">
                    <hr class="line">
                </div>
                <div class="col-md-12">
                    <table style="border-collapse: collapse;width: 100%;">
                        <?php $__currentLoopData = $booking->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="text-align: left"><?php echo e(ucwords($item->businessService->name)); ?></td>
                            <td style="text-align: right" style="text-align: center"><?php echo e($settings->currency->currency_symbol.number_format((float)$item->unit_price, 2)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>
                
                <div class="col-md-12">
                    <hr class="line">
                    <table style="border-collapse: collapse;width: 100%;">
                        <tr>
                            <td style="text-align: right">TOTAL</td>
                            <td style="text-align: right"><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->original_amount, 2)); ?></td>
                        </tr>
                        <?php if($booking->discount > 0): ?>
                        <tr>
                            <td style="text-align: right"><?php echo app('translator')->getFromJson('app.discount'); ?></td>
                            <td style="text-align: right"><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->discount, 2)); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($booking->tax_amount > 0): ?>
                        <tr>
                            <td style="text-align: right"><?php echo e($booking->tax_name.' ('.$booking->tax_percent.'%)'); ?></td>
                            <td style="text-align: right"><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->tax_amount, 2, '.', '')); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($booking->coupon_discount > 0): ?>
                        <tr>
                            <td style="text-align: right" ><?php echo app('translator')->getFromJson('app.couponDiscount'); ?> (<a href="javascript:;" onclick="showCoupon();" class="show-coupon"><?php echo e($booking->coupon->title); ?></a>)</td>
                            <td style="text-align: right"><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->coupon_discount, 2, '.', '')); ?></td>
                        </tr>
                        <?php endif; ?> 
                        
                    </table>
                    <hr class="line">
                    <table style="border-collapse: collapse;width: 100%">
                        <tr>
                            <td style="text-align: right"><b>Grand Total</b></td>
                            <td style="text-align: right"><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->amount_to_pay, 2)); ?></td>
                        </tr>
                    </table>
                    <hr class="line">
                    <p>TERIMAKASIH TELAH MENGGUNAKAN JASA KAMI. SEHAT SELALU YA KAK..</p>
                </div>
                
            </div>
        </div>
    </div>
    

</div>

<div class="row hidden-fitur" style="margin-top: 30px">
    <div style="text-align: center" class="col-md-12">
        <h3><b>DETAIL INVOICE</b></h3>
    </div>
    <div class="col-md-6 border-right"> <strong><?php echo app('translator')->getFromJson('app.email'); ?></strong> <br>
        <p class="text-muted"><i class="icon-email"></i> <?php echo e($booking->user->email ?? '--'); ?></p>
    </div>
    <div class="col-md-6"> <strong><?php echo app('translator')->getFromJson('app.mobile'); ?></strong> <br>
        <p class="text-muted"><i class="icon-mobile"></i> <?php echo e($booking->user->mobile ? $booking->user->formatted_mobile : '--'); ?></p>
    </div>
</div>

<div class="row hidden-fitur">
    <div class="col-sm-4 border-right"> <strong><?php echo app('translator')->getFromJson('app.booking'); ?> <?php echo app('translator')->getFromJson('app.date'); ?></strong> <br>
        <p class="text-primary"><i class="icon-calendar"></i> <?php echo e($booking->date_time->format($settings->date_format)); ?></p>
    </div>
    <div class="col-sm-4 border-right"> <strong><?php echo app('translator')->getFromJson('app.booking'); ?> <?php echo app('translator')->getFromJson('app.time'); ?></strong> <br>
        <p class="text-primary"><i class="icon-alarm-clock"></i> <?php echo e($booking->date_time->format($settings->time_format)); ?> </p>
    </div>
    <div class="col-sm-4"> <strong><?php echo app('translator')->getFromJson('app.booking'); ?> <?php echo app('translator')->getFromJson('app.status'); ?></strong> <br>
        <span class="text-uppercase small border
        <?php if($booking->status == 'completed'): ?> border-success text-success <?php endif; ?>
        <?php if($booking->status == 'pending'): ?> border-warning text-warning <?php endif; ?>
        <?php if($booking->status == 'approved'): ?> border-info text-info <?php endif; ?>
        <?php if($booking->status == 'in progress'): ?> border-primary text-primary <?php endif; ?>
        <?php if($booking->status == 'canceled'): ?> border-danger text-danger <?php endif; ?>
         badge-pill"><?php echo e(__('app.'.$booking->status)); ?></span>
    </div>
</div>


<?php if(count($booking->users)>0): ?>
<div class="row hidden-fitur">
    <div class="col-sm-12"> <strong><?php echo app('translator')->getFromJson('menu.employee'); ?> </strong> <br>
        <p class="text-primary" style="margin: 0.2em">
            <?php $__currentLoopData = $booking->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <i class="icon-user"></i> <?php echo e($user->name); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </p>
    </div>
</div>

<?php endif; ?>

<div class="row hidden-fitur">
    <div class="col-md-12">
        <table class="table table-condensed">
            <thead class="bg-secondary">
            <tr>
                <th>#</th>
                <th><?php echo app('translator')->getFromJson('app.item'); ?></th>
                <th><?php echo app('translator')->getFromJson('app.unitPrice'); ?></th>
                <th><?php echo app('translator')->getFromJson('app.quantity'); ?></th>
                <th class="text-right"><?php echo app('translator')->getFromJson('app.amount'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $booking->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key+1); ?>.</td>
                    <td><?php echo e(ucwords($item->businessService->name)); ?></td>
                    <td><?php echo e($settings->currency->currency_symbol.number_format((float)$item->unit_price, 2, '.', '')); ?></td>
                    <td>x<?php echo e($item->quantity); ?></td>
                    <td class="text-right"><?php echo e($settings->currency->currency_symbol.number_format((float)($item->businessService->discounted_price  * $item->quantity), 2, '.', '')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

        </table>
    </div>
    <div class="col-md-7 border-top">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-condensed">
                    <tr class="h6">
                        <td class="border-top-0"><?php echo app('translator')->getFromJson('modules.booking.paymentMethod'); ?></td>
                        <td class="border-top-0 "><i class="fa fa-money"></i> <?php echo e($booking->payment_gateway); ?></td>
                    </tr>
                    <tr class="h6">
                        <td><?php echo app('translator')->getFromJson('modules.booking.paymentStatus'); ?></td>
                        <td>
                            <?php if($booking->payment_status == 'completed'): ?>
                                <span class="text-success  font-weight-normal"><i class="fa fa-check-circle"></i> <?php echo e(__('app.'.$booking->payment_status)); ?></span></td>
                            <?php endif; ?>
                            <?php if($booking->payment_status == 'pending'): ?>
                                <span class="text-warning font-weight-normal"><i class="fa fa-times-circle"></i> <?php echo e(__('app.'.$booking->payment_status)); ?></span></td>
                            <?php endif; ?>
                    </tr>

                    <?php if($commonCondition): ?>
                    <tr>
                        <td colspan="2">
                            <div class="payment-type">
                                <h5><?php echo app('translator')->getFromJson('front.paymentMethod'); ?></h5>
                                <div class="payments text-center">
                                    <?php if($credentials->stripe_status == 'active'): ?>
                                    <a href="javascript:;" id="stripePaymentButton" data-bookingId="<?php echo e($booking->id); ?>" class="btn btn-custom btn-blue mb-2"><i class="fa fa-cc-stripe mr-2"></i><?php echo app('translator')->getFromJson('front.buttons.stripe'); ?></a>
                                    <?php endif; ?>
                                    <?php if($credentials->paypal_status == 'active'): ?>
                                    <a href="<?php echo e(route('front.paypal', $booking->id)); ?>" class="btn btn-custom btn-blue mb-2"><i class="fa fa-paypal mr-2"></i><?php echo app('translator')->getFromJson('front.buttons.paypal'); ?></a>
                                    <?php endif; ?>
                                    <?php if($credentials->razorpay_status == 'active'): ?>
                                    <a href="javascript:startRazorPayPayment();" class="btn btn-custom btn-blue mb-2"><i class="fa fa-card mr-2"></i><?php echo app('translator')->getFromJson('front.buttons.razorpay'); ?></a>
                                    <?php endif; ?>
                                    <?php if($credentials->offline_payment == 1): ?>
                                    <a href="<?php echo e(route('front.offline-payment', $booking->id)); ?>" class="btn btn-custom btn-blue mb-2"><i class="fa fa-money mr-2"></i><?php echo app('translator')->getFromJson('app.offline'); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if($booking->status == 'completed'): ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('admin.bookings.download', $booking->id)); ?>" class="btn btn-success btn-sm"><i class="fa fa-download"></i> <?php echo app('translator')->getFromJson('app.download'); ?> <?php echo app('translator')->getFromJson('app.receipt'); ?></a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5 border-top amountDetail">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-condensed">
                    <tr class="h6">
                        <td class="border-top-0 text-right"><?php echo app('translator')->getFromJson('app.subTotal'); ?></td>
                        <td class="border-top-0"><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->original_amount, 2, '.', '')); ?></td>
                    </tr>
                    <?php if($booking->discount > 0): ?>
                    <tr class="h6">
                        <td class="text-right"><?php echo app('translator')->getFromJson('app.discount'); ?></td>
                        <td><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->discount, 2, '.', '')); ?></td>
                    </tr>
                    <?php endif; ?>

                    <?php if($booking->tax_amount > 0): ?>
                    <tr class="h6">
                        <td class="text-right"><?php echo e($booking->tax_name.' ('.$booking->tax_percent.'%)'); ?></td>
                        <td><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->tax_amount, 2, '.', '')); ?></td>
                    </tr>
                    <?php endif; ?>

                    <?php if($booking->coupon_discount > 0): ?>
                    <tr class="h6">
                        <td class="text-right" ><?php echo app('translator')->getFromJson('app.couponDiscount'); ?> (<a href="javascript:;" onclick="showCoupon();" class="show-coupon"><?php echo e($booking->coupon->title); ?></a>)</td>
                        <td><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->coupon_discount, 2, '.', '')); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr class="h5">
                        <td class="text-right"><?php echo app('translator')->getFromJson('app.total'); ?></td>
                        <td><?php echo e($settings->currency->currency_symbol.number_format((float)$booking->amount_to_pay, 2, '.', '')); ?></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>

    <?php if(!is_null($booking->additional_notes)): ?>
    <div class="col-md-12 font-italic">
        <h4 class="text-info"><?php echo app('translator')->getFromJson('modules.booking.customerMessage'); ?></h4>
        <p class="text-lg">
            <?php echo $booking->additional_notes; ?>

        </p>
    </div>
    <?php endif; ?>
    
    <div class="modal fade bs-modal-lg in" id="coupon-detail-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title"><?php echo app('translator')->getFromJson('app.coupon'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo app('translator')->getFromJson('app.close'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    
</div>



<script>
    
    <?php if($booking->coupon_discount > 0): ?>
        function showCoupon () {
            var url = '<?php echo e(route('admin.coupons.show', $booking->coupon_id)); ?>';
            $('#modelHeading').html('Show Coupon');
            $.ajaxModal('#coupon-detail-modal', url);
        }
    <?php endif; ?>
</script>
<?php if($credentials->stripe_status == 'active' && $commonCondition): ?>
    <script>
        var token_triggered = false;
        var handler = StripeCheckout.configure({
            key: '<?php echo e($credentials->stripe_client_id); ?>',
            image: '<?php echo e($settings->logo_url); ?>',
            locale: 'auto',
            closed: function(data) {
                if (!token_triggered) {
                    $.easyUnblockUI('.statusSection');
                } else {
                    $.easyBlockUI('.statusSection');
                }
            },
            token: function(token) {
                token_triggered = true;
                // You can access the token ID with `token.id`.
                // Get the token ID to your server-side code for use.
                $.easyAjax({
                    url: '<?php echo e(route('front.stripe', $booking->id)); ?>',
                    container: '#invoice_container',
                    type: "POST",
                    redirect: true,
                    data: {token: token, "_token" : "<?php echo e(csrf_token()); ?>"}
                })
            }
        });

        document.getElementById('stripePaymentButton').addEventListener('click', function(e) {
            // Open Checkout with further options:
            handler.open({
                name: '<?php echo e($setting->company_name); ?>',
                amount: <?php echo e($booking->amount_to_pay * 100); ?>,
                currency: '<?php echo e($setting->currency->currency_code); ?>',
                email: "<?php echo e($user->email); ?>"
            });
            $.easyBlockUI('.statusSection');
            e.preventDefault();
        });

        // Close Checkout on page navigation:
        window.addEventListener('popstate', function() {
            alert('hello');
            handler.close();
        });
    </script>
<?php endif; ?>

<?php if($credentials->razorpay_status == 'active' && $commonCondition): ?>
    <script>
        var options = {
            "key": "<?php echo e($credentials->razorpay_key); ?>", // Enter the Key ID generated from the Dashboard
            "amount": "<?php echo e($booking->amount_to_pay * 100); ?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise or INR 500.
            "currency": "INR",
            "name": "<?php echo e($booking->user->name); ?>",
            "description": "<?php echo app('translator')->getFromJson('app.booking'); ?> <?php echo app('translator')->getFromJson('front.headings.payment'); ?>",
            "image": "<?php echo e($settings->logo_url); ?>",
            "handler": function (response){
                confirmRazorPayPayment(response.razorpay_payment_id, '<?php echo e($booking->id); ?>', response);
            },
            "prefill": {
                "email": "<?php echo e($booking->user->email); ?>",
                "contact": "<?php echo e($booking->user->mobile); ?>"
            },
            "notes": {
                "booking_id": "<?php echo e($booking->id); ?>"
            },
            "theme": {
                "color": "<?php echo e($frontThemeSettings->primary_color); ?>"
            }
        };
        var rzp1 = new Razorpay(options);

        function startRazorPayPayment() {
            rzp1.open();
        }

        function confirmRazorPayPayment(paymentId, bookingId, response) {
            $.easyAjax({
                url: '<?php echo e(route('front.razorpay')); ?>',
                type: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    payment_id: paymentId,
                    booking_id: bookingId,
                    response: response
                },
                container: 'body',
                redirect: true
            });
        }
        
    </script>
    
<?php endif; ?>
<?php /**PATH G:\Pekerjaan\Simetris\salon\salon\resources\views/admin/booking/show.blade.php ENDPATH**/ ?>