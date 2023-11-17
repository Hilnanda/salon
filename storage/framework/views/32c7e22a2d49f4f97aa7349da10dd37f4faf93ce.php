<?php $__env->startPush('styles'); ?>
<style>
    .coupons-base-content .fa-tag{
        font-size: 20px;
        color: #222;
    }
    .coupons-base-content p{
        color: #3289da;
        font-size: 11px;
    }
    .remove-button{
        margin-bottom: 4px;
        margin-left: 3px;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button
    {
    -webkit-appearance: none;
    margin: 0;
    }

    /* Firefox */
    input[type=number]
    {
    -moz-appearance: textfield;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <section class="section">
        <section class="cart-area sp-80">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="all-title">
                            <h3 class="sec-title">
                                <?php echo app('translator')->getFromJson('front.headings.bookingDetails'); ?>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-12 mb-30">
                        <div class="shopping-cart-table">
                            <table class="table table-responsive-md">
                                <thead>
                                <tr>
                                    <th><?php echo app('translator')->getFromJson('front.table.headings.serviceName'); ?></th>
                                    <th>Duration</th>
                                    <th><?php echo app('translator')->getFromJson('front.table.headings.unitPrice'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('front.table.headings.quantity'); ?></th>
                                    <th><?php echo app('translator')->getFromJson('front.table.headings.subTotal'); ?></th>
                                    <?php if(!is_null($products)): ?>
                                        <th>&nbsp;</th>
                                    <?php endif; ?>
                                </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php if(!is_null($products)): ?>
                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr id="<?php echo e($key); ?>">
                                                <td><?php echo e($product['serviceName']); ?></td>
                                                <td><?php echo e($product['serviceTime'] ?? '-'); ?></td>
                                                <td><?php echo e($settings->currency->currency_symbol.$product['servicePrice']); ?></td>
                                                <td>
                                                    <div class="qty-wrap">
                                                        <div class="qty-elements">
                                                            <a class="decrement_qty" href="javascript:void(0)" onclick="decreaseQuantity(this)">-</a>
                                                        </div>
                                                        <input onkeypress="return isNumberKey(event)" type="number" name="qty" value="<?php echo e($product['serviceQuantity']); ?>" title="Quantity"
                                                            class="input-text qty" data-id="<?php echo e($key); ?>" data-price="<?php echo e($product['servicePrice']); ?>" autocomplete="none" />
                                                        <div class="qty-elements">
                                                            <a class="increment_qty" href="javascript:void(0)" onclick="increaseQuantity(this)">+</a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="sub-total"><?php echo e($settings->currency->currency_symbol); ?><span><?php echo e($product['serviceQuantity'] * $product['servicePrice']); ?></span></td>
                                                <td>
                                                    <a title="<?php echo app('translator')->getFromJson('front.table.deleteProduct'); ?>" href="javascript:;" onclick="deleteProduct(this, '<?php echo e($key); ?>')" class="delete-btn">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-danger"><?php echo app('translator')->getFromJson('front.table.emptyMessage'); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <ul class="cart-buttons">
                                            <li>
                                            </li>
                                            <li>
                                                <a href="<?php echo e(route('front.index')); ?>" class="btn btn-custom btn-blue"><?php echo app('translator')->getFromJson('front.buttons.continueBooking'); ?></a>
                                                <?php if(!is_null($products)): ?>
                                                    <a href="javascript:;" onclick="deleteProduct(this, 'all')" class="btn btn-custom btn-blue"><?php echo app('translator')->getFromJson('front.buttons.clearCart'); ?></a>
                                                <?php endif; ?>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 mb-30">
                        <div class="cart-block">
                            <div class="final-cart">
                                <h5><?php echo app('translator')->getFromJson('front.summary.cart.heading.cartTotal'); ?></h5>
                                <div class="mx-3">
                                <div class="input-group" id="applyCouponBox"
                                     <?php if(is_null($couponData)): ?>
                                        style="border-radius: 4px;overflow: hidden;" <?php else: ?> style="display: none" <?php endif; ?> >
                                    <input type="text" name="coupon"class="form-control" placeholder="<?php echo app('translator')->getFromJson('front.summary.cart.applyCoupon'); ?>" id="coupon" style="border: 0;">
                                    <div class="input-group-prepend">
                                         <button id="" onclick="applyCoupon();" type="button" class="btn btn-sm input-group-text" style="font-size:13px; border:0; color:#000"><?php echo app('translator')->getFromJson('front.summary.cart.applyCoupon'); ?></button>
                                     </div>
                                </div>
                                </div>
                            
                                <div class=" py-3 border-bottom" id="removeCouponBox"  <?php if(is_null($couponData)): ?> style="display: none" <?php endif; ?>>
                                    <h6  class="clearfix text-white"><?php echo app('translator')->getFromJson('app.coupons'); ?></h6>

                                    <div class="coupons-base-content justify-content-between d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3 ">
                                                <i class="fa fa-tag text-white"></i>
                                            </div>
                                            <div>
                                                <span class="coupons-name mb-0 text-uppercase" id="couponCode" >
                                                    <?php if(!is_null($couponData)): ?>
                                                        <?php echo e($couponData[0]['title']); ?>

                                                    <?php endif; ?>
                                                </span>
                                                <p class="mb-0 text-success savetext">
                                                    <?php echo app('translator')->getFromJson('app.youSaved'); ?> <?php echo e($settings->currency->currency_symbol); ?>

                                                    <span id="couponCodeAmonut">
                                                        <?php if(!is_null($couponData)): ?>
                                                            <?php echo e($couponData['applyAmount']); ?>

                                                        <?php endif; ?>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="button" onclick="removeCoupon();" class="btn btn-sm btn-danger remove-button"> <?php echo app('translator')->getFromJson('app.remove'); ?> </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart-value">
                                    <ul>
                                        <li>
                                            <span>
                                                <?php echo app('translator')->getFromJson('front.summary.cart.subTotal'); ?>
                                            </span>
                                            <span id="sub-total">
                                            </span>
                                        </li>
                                        <?php if(!is_null($tax)): ?>
                                            <li>
                                                <span>
                                                    <?php echo e($tax->tax_name); ?> (<?php echo e($tax->percent); ?>%):
                                                </span>
                                                <span id="tax">
                                                </span>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(!is_null($couponData)): ?>
                                            <li id="couponDiscountBox">
                                                <span>
                                                    <?php echo app('translator')->getFromJson('app.discount'); ?> (<?php echo e($couponData[0]['title']); ?>):
                                                </span>
                                                <span id="couponDiscoiunt">
                                                    -<?php echo e($settings->currency->currency_symbol); ?><?php echo e($couponData['applyAmount']); ?>

                                                </span>
                                            </li>
                                        <?php endif; ?>
                                        <li id="totalAmountBox">
                                            <span>
                                                <?php echo app('translator')->getFromJson('front.summary.cart.totalAmount'); ?>:
                                            </span>
                                            <span id="total">
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                        
                        <?php if($bookingDetails): ?>
                        <div class="cart-block" style="margin-top: 20px">
                            <div class="final-cart">
                                <h5>Perkiraan Waktu</h5>
                                
                            
                                

                                <div class="cart-value">
                                    <ul>
                                        <li>
                                            <span>
                                                Mulai
                                            </span>
                                            <span id="sub-total">
                                                <?php echo e(!is_null($bookingDetails) ? $bookingDetails['bookingTime'] : 0); ?>

                                            </span>
                                        </li>
                                        <li id="totalAmountBox">
                                            <span>
                                                Selesai
                                            </span>
                                            <span id="total">
                                                <?php echo e($totalWaktu ? $totalWaktu.':00' : 0); ?>

                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if(!is_null($products)): ?>
                    <div class="row">
                        <div class="col-12 text-right">
                            <div class="navigation">
                                <a href="<?php echo e(route('front.bookingPage')); ?>" class="btn btn-custom btn-dark"><i class="fa fa-angle-left mr-2"></i><?php echo app('translator')->getFromJson('front.navigation.goBack'); ?></a>
                                <a href="<?php echo e(route('front.checkoutPage')); ?>" class="btn btn-custom btn-dark">
                                    <?php echo e(!is_null($bookingDetails) ? __('front.navigation.toCheckout') : __('front.selectBookingTime')); ?>

                                    <i class="fa fa-angle-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('footer-script'); ?>
<script src="<?php echo e(asset('assets/js/cookie.js')); ?>"></script>
    <script>

        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
            return true;
        }

        $(function () {
            var couponCode = '';
            calculateTotal();
        });

        var cartUpdate;
        var couponAmount = 0;
        var couponApplied = false;
        var products = <?php echo json_encode($products); ?>;

        <?php if(!is_null($couponData) && $couponData['applyAmount']): ?>
            couponAmount = '<?php echo e($couponData['applyAmount']); ?>';
            couponCode = '<?php echo e($couponData[0]['title']); ?>';
            couponApplied = true;
        <?php endif; ?>

        function calculateTotal()
        {
            let cartTotal = tax = totalAmount = 0.00;

            $('.sub-total>span').each(function () {
                cartTotal += parseFloat($(this).text());
            });

            $('#sub-total').text('<?php echo e($settings->currency->currency_symbol); ?>'+cartTotal.toFixed(2));

            // calculate and display tax
            <?php if(!is_null($tax)): ?>
                let taxPercent = parseFloat('<?php echo e($tax->percent); ?>');
                tax = (taxPercent * cartTotal)/100;

                $('#tax').text('<?php echo e($settings->currency->currency_symbol); ?>'+tax.toFixed(2));
            <?php endif; ?>

            totalAmount = cartTotal + tax;

            if(couponAmount)
            {
                if(totalAmount>=couponAmount)
                {
                    totalAmount = totalAmount - couponAmount;
                }
                else
                {
                    totalAmount = 0;
                }
            }

            $('#total').text('<?php echo e($settings->currency->currency_symbol); ?>'+totalAmount.toFixed(2));
        }

        function increaseQuantity(ele)
        {
            var input = $(ele).parent().siblings('input');
            var currentValue = input.val();
            if(currentValue>0)
            {
                input.val(parseInt(currentValue) + 1);
                input.trigger('keyup');
            }
        }

        function decreaseQuantity(ele) {
            var input = $(ele).parent().siblings('input');
            var currentValue = input.val();

            if (currentValue > 1) {
                input.val(parseInt(currentValue) - 1);
                input.trigger('keyup');
            }
        }

        function deleteProduct(ele, key) {
            var url = '<?php echo e(route('front.deleteProduct', ':id')); ?>';
            url = url.replace(':id', key);

            $.easyAjax({
                url: url,
                type: 'POST',
                data: {_token: $("meta[name='csrf-token']").attr('content')},
                redirect: false,
                success: function (response) {
                    if (response.status == 'success') {
                        if (response.action == "redirect") {
                            var message = "";
                            if (typeof response.message != "undefined") {
                                message += response.message;
                            }

                            $.showToastr(message, "success", {
                                positionClass: "toast-top-right"
                            });

                            setTimeout(function () {
                                window.location.href = response.url;
                            }, 1000);

                        }
                        else {
                            updateCoupon ();
                            $(ele).parents(`tr#${key}`).remove();
                            calculateTotal();
                            $('.cart-badge').text(response.productsCount);
                            products = response.products;
                        }
                    }
                }
            })
        }


        function updateCart() {
            let data = {};
            $('input.qty').each(function ()
            {
                const serviceId = $(this).data('id');
                products[serviceId].serviceQuantity = parseInt($(this).val());
            });
            data.products = products;
            data._token = '<?php echo e(csrf_token()); ?>';
            if($('input.qty').val()>=0 && $('input.qty').val()!='')
            {
                $.easyAjax({
                    url: '<?php echo e(route('front.updateCart')); ?>',
                    type: 'POST',
                    data: data,
                    container: '.section',
                    success:function(response){
                        updateCoupon ();
                    }
                })
            }

        }
        function removeCoupon () {
            $.easyAjax({
                url: '<?php echo e(route('front.remove-coupon')); ?>',
                type: 'GET',
                success: function (response) {
                    couponApplied = false;
                    $('#coupon').val('');
                    $('#coupon_amount').val(0);
                    couponAmount = 0;
                    calculateTotal();
                    $('#couponDiscountBox').remove();
                    $('#removeCouponBox').hide();
                    $('#applyCouponBox').show();

                }
            })


        }

        function applyCoupon ()
        {
            let cartTotal = tax = totalAmount = 0.00;

            $('.sub-total>span').each(function () {
                cartTotal += parseFloat($(this).text());
            });

            $('#sub-total').text('<?php echo e($settings->currency->currency_symbol); ?>'+cartTotal.toFixed(2));

            // calculate and display tax
            <?php if(!is_null($tax)): ?>
                let taxPercent = parseFloat('<?php echo e($tax->percent); ?>');
                tax = (taxPercent * cartTotal)/100;
                $('#tax').text('<?php echo e($settings->currency->currency_symbol); ?>'+tax.toFixed(2));
            <?php endif; ?>

            totalAmount = cartTotal + tax;

           var couponVal = $('#coupon').val();
           if((couponVal === undefined || couponVal === "" || couponVal === null)){
               return $.showToastr("<?php echo app('translator')->getFromJson('errors.coupon.required'); ?>", 'error');
           }else{
               var currencySymbol = '<?php echo e($settings->currency->currency_symbol); ?>';
               $.easyAjax({
                   url: '<?php echo e(route('front.apply-coupon')); ?>',
                   type: 'GET',
                   data: {'coupon':couponVal},
                   success: function (response) {
                       if(response.status != 'fail'){
                           couponApplied = true;
                           couponCode = couponVal;
                           couponAmount = response.amount;
                           if(couponAmount>totalAmount)
                           {
                                couponAmount = totalAmount;
                           }
                            calculateTotal();
                           $('#couponDiscountBox').remove();
                           var discountElement = '<li id="couponDiscountBox">'+
                               '<span>'+
                               "<?php echo app('translator')->getFromJson('app.discount'); ?> ("+response.couponData.title+'):'+
                               '</span>'+
                               '<span id="discountCoupon">-'+currencySymbol +couponAmount+
                               '</span>'+
                               '</li>';
                           $(discountElement).insertBefore( "#totalAmountBox" );

                           $('#applyCouponBox').hide();
                           $('#removeCouponBox').show();

                           $('#couponCodeAmonut').html(couponAmount);
                           $('#couponCode').html(response.couponData.title);
                       }
                       else{
                           removeCoupon ();
                       }

                   }
               })
           }

        }

        function updateCoupon () {

            let cartTotal = tax = totalAmount = 0.00;

            $('.sub-total>span').each(function () {
                cartTotal += parseFloat($(this).text());
            });

            $('#sub-total').text('<?php echo e($settings->currency->currency_symbol); ?>'+cartTotal.toFixed(2));

            // calculate and display tax
            <?php if(!is_null($tax)): ?>
                let taxPercent = parseFloat('<?php echo e($tax->percent); ?>');
                tax = (taxPercent * cartTotal)/100;
                $('#tax').text('<?php echo e($settings->currency->currency_symbol); ?>'+tax.toFixed(2));
            <?php endif; ?>

            totalAmount = cartTotal + tax;

            if (couponApplied){
                var currencySymbol = '<?php echo e($settings->currency->currency_symbol); ?>';
                $.easyAjax({
                    url: '<?php echo e(route('front.update-coupon')); ?>',
                    type: 'GET',
                    data: {'coupon': couponCode},
                    success: function (response) {
                        if (response.status != 'fail') {
                            couponAmount = response.amount;
                            if(couponAmount>totalAmount)
                            {
                                couponAmount = totalAmount;
                            }
                            calculateTotal();
                            $('#couponDiscountBox').remove();
                            var discountElement = '<li id="couponDiscountBox">' +
                                '<span>' +
                                "<?php echo app('translator')->getFromJson('app.discount'); ?> (" + response.couponData.title + '):' +
                                '</span>' +
                                '<span id="discountCoupon">-' + currencySymbol + couponAmount +
                                '</span>' +
                                '</li>';
                            $(discountElement).insertBefore("#totalAmountBox");

                            $('#applyCouponBox').hide();
                            $('#removeCouponBox').show();

                            $('#couponCodeAmonut').html(couponAmount);
                            $('#couponCode').html(response.couponData.title);
                        }
                        else {
                            removeCoupon();
                        }

                    }
                })

            }
        }

        $('input.qty').on('keyup', function () {
            const id = $(this).data('id');
            const price = $(this).data('price');
            const quantity = $(this).val();
            let subTotal = 0;

            if (quantity<0)
            {
                $(this).val(0);
            }

            clearTimeout(cartUpdate);

            if (quantity == '' || quantity == 0) {
                subTotal = price * 1;
            }
            else {
                subTotal = price * quantity;
            }

            $(`tr#${id}`).find('.sub-total>span').text(subTotal.toFixed(2));
            calculateTotal();

            cartUpdate = setTimeout(() => {
                updateCart();
            }, 500);
        });

        $('input.qty').on('blur', function () {
            if ($(this).val() == '' || $(this).val() == 0) {
                $(this).val(1);
            }
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH G:\Pekerjaan\Simetris\salon\salon\resources\views/front/cart_page.blade.php ENDPATH**/ ?>