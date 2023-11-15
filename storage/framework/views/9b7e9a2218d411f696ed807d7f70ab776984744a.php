<?php $__env->startSection('content'); ?>
    <section class="section">
        <section class="service-detail sp-80">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4><?php echo e($service->name); ?></h4>
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <?php if($service->image && sizeof($service->image) > 1): ?>
                            <div id="banner-slider" class="carousel slide detail-image mb-30" data-ride="carousel">
                                <ul class="carousel-indicators">
                                    <?php $count = 0 ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $service->image; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <li data-target="#banner-slider" data-slide-to="<?php echo e($count); ?>" <?php if($service->service_image_url == asset_url('service/'.$service->id.'/'.$image)): ?> class="active" <?php endif; ?>></li>
                                        <?php $count++ ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <li data-target="#banner-slider" data-slide-to="0" class="active"></li>
                                        <li data-target="#banner-slider" data-slide-to="1"></li>
                                        <li data-target="#banner-slider" data-slide-to="2"></li>
                                        <li data-target="#banner-slider" data-slide-to="3"></li>
                                    <?php endif; ?>
                                </ul>
                                <div class="carousel-inner">
                                    <?php $count = 0 ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $service->image; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="carousel-item <?php echo e($service->service_image_url == asset_url('service/'.$service->id.'/'.$image) ? 'active' : ''); ?>">
                                            <a href="javascript:void(0);">
                                                <img class="img-fluid" src="<?php echo e(asset('user-uploads/service/'.$service->id.'/'.$image)); ?>" alt="carousel image">
                                            </a>
                                        </div>
                                        <?php $count++ ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="carousel-item active">
                                            <a href="javascript:void(0);">
                                                <img src="assets/img/banner.jpg" alt="Los Angeles">
                                            </a>
                                        </div>
                                        <div class="carousel-item">
                                            <a href="javascript:void(0);">
                                                <img src="assets/img/banner.jpg" alt="Chicago">
                                            </a>
                                        </div>
                                        <div class="carousel-item">
                                            <a href="javascript:void(0);">
                                                <img src="assets/img/banner.jpg" alt="Los Angeles">
                                            </a>
                                        </div>
                                        <div class="carousel-item">
                                            <a href="javascript:void(0);">
                                                <img src="assets/img/banner.jpg" alt="Chicago">
                                            </a>
                                        </div>

                                    <?php endif; ?>

                                </div>
                                <div class="banner-controls">
                                    <a class="carousel-control-prev" href="#banner-slider" data-slide="prev">
                                        <span class="fa fa-angle-left"></span>
                                    </a>
                                    <a class="carousel-control-next" href="#banner-slider" data-slide="next">
                                        <span class="fa fa-angle-right"></span>
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="detail-image mb-30">
                                <img src="<?php echo e($service->service_image_url); ?>" alt="service">
                            </div>
                        <?php endif; ?>
                        <div class="content">
                            <?php echo $service->description; ?>

                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 mb-60">
                        <div class="detail-info mb-5">
                            <ul>
                                <li>
                                    <span>
                                        <?php echo app('translator')->getFromJson('app.service'); ?> <?php echo app('translator')->getFromJson('app.name'); ?>
                                    </span>
                                    <span>
                                        <?php echo e($service->name); ?>

                                    </span>
                                </li>
                                <li>
                                    <span>
                                        <?php echo app('translator')->getFromJson('app.price'); ?>
                                    </span>
                                    <span>
                                        <?php echo e($settings->currency->currency_symbol.' '.$service->price); ?>

                                    </span>
                                </li>
                                <li>
                                    <span>
                                        <?php echo app('translator')->getFromJson('app.time'); ?>
                                    </span>
                                    <span>
                                        <?php echo e($service->time); ?>  <?php echo app('translator')->getFromJson('app.'.$service->time_type); ?>
                                    </span>
                                </li>
                                <li>
                                    <span>
                                       <?php echo app('translator')->getFromJson('app.discount'); ?>
                                    </span>
                                    <span>
                                        <?php switch($service->discount_type):
                                            case ('percent'): ?>
                                                <?php echo e($service->discount.' %'); ?>

                                            <?php break; ?>
                                            <?php case ('fixed'): ?>
                                                <?php echo e($settings->currency->currency_symbol.' '.$service->discount); ?>

                                            <?php break; ?>
                                        <?php endswitch; ?>
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <ul class="add-qty">
                            <li>
                                <span class="text-capitalize mb-2 d-block"><?php echo app('translator')->getFromJson('app.add'); ?> <?php echo app('translator')->getFromJson('app.quantity'); ?></span>
                                <div class="qty-wrap">
                                    <div class="qty-elements">
                                        <a class="decrement_qty" href="javascript:void(0)" onclick="decreaseQuantity(this)">-</a>
                                    </div>
                                    <?php
                                        $product = current($reqProduct);
                                    ?>
                                    <input name="qty" value="<?php echo e(sizeof($reqProduct) > 0 ? $product['serviceQuantity'] : 1); ?>" size="4" title="Quantity" class="input-text qty" data-id="<?php echo e($service->id); ?>" data-price="<?php echo e($service->price); ?>" autocomplete="none" />
                                    <div class="qty-elements">
                                        <a class="increment_qty" href="javascript:void(0)" onclick="increaseQuantity(this)">+</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="update <?php if(sizeof($reqProduct) == 0): ?> hide <?php endif; ?>">
                                    <div class="row">
                                        <div class="col-md mb-2">
                                            <a href="javascript:void(0)" class="btn btn-custom update-cart"><?php echo app('translator')->getFromJson('front.buttons.updateCart'); ?></a>
                                        </div>
                                        <div class="col-md">
                                            <a href="javascript:void(0)" onclick="deleteProduct(this)" class="btn btn-custom-danger">
                                                <?php echo app('translator')->getFromJson('front.table.deleteProduct'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="add <?php if(sizeof($reqProduct) > 0): ?> hide <?php endif; ?>">
                                    <div class="row">
                                        <div class="col">
                                            <a href="javascript:void(0)" class="btn btn-custom add-to-cart"><?php echo app('translator')->getFromJson('front.addItem'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        <div class="navigation mt-4">
                            <a href="<?php echo e(route('front.index')); ?>" class="btn btn-custom btn-dark">
                                <i class="fa fa-angle-left mr-2"></i><?php echo app('translator')->getFromJson('front.navigation.goBack'); ?>
                            </a>
                            <a href="javascript:;" class="btn btn-custom btn-dark" onclick="goToPage('GET', '<?php echo e(route('front.bookingPage')); ?>');"><?php echo app('translator')->getFromJson('front.selectBookingTime'); ?> <i class="fa fa-angle-right ml-2"></i> </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('footer-script'); ?>
    <script>
        function increaseQuantity(ele) {
            var input = $(ele).parent().siblings('input');
            var currentValue = input.val();

            input.val(parseInt(currentValue) + 1);
        }

        function decreaseQuantity(ele) {
            var input = $(ele).parent().siblings('input');
            var currentValue = input.val();

            if (currentValue > 1) {
                input.val(parseInt(currentValue) - 1);
            }
        }

        $('input.qty').on('blur', function () {
            if ($(this).val() == '' || $(this).val() == 0) {
                $(this).val(1);
            }
        });

        function deleteProduct(ele) {
            let key = $('input.qty').data('id');

            var url = '<?php echo e(route('front.deleteFrontProduct', ':id')); ?>';
            url = url.replace(':id', key);

            $.easyAjax({
                url: url,
                type: 'POST',
                data: {_token: $("meta[name='csrf-token']").attr('content')},
                redirect: false,
                success: function (response) {
                    $('.cart-badge').text(response.productsCount);
                    $(ele).parents('.update').addClass('hide').siblings('.add').removeClass('hide')
                    $('input.qty').val(1);
                }
            })
        }

        // add items to cart
        $('body').on('click', '.add-to-cart, .update-cart', function () {
            let serviceId = '<?php echo e($service->id); ?>';
            let servicePrice = '<?php echo e($service->price); ?>';
            let serviceName = '<?php echo e($service->name); ?>';
            let serviceQuantity = $('.qty').val();
            let $this = $(this);

            var data = {serviceId, servicePrice, serviceName, serviceQuantity, _token: $("meta[name='csrf-token']").attr('content')};

            $.easyAjax({
                url: '<?php echo e(route('front.addOrUpdateProduct')); ?>',
                type: 'POST',
                data: data,
                success: function (response) {
                    $('.cart-badge').text(response.productsCount);
                    let addButton = $this.parents('.add');

                    if (addButton.length > 0) {
                        addButton.addClass('hide').siblings('.update').removeClass('hide');
                    }
                }
            })
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH G:\Pekerjaan\Simetris\salon\salon\resources\views/front/service_detail.blade.php ENDPATH**/ ?>