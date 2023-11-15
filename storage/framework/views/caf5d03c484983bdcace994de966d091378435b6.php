<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="row">
        <?php if($category->services->count() > 0): ?>
        <div class="col-md-12 mt-2">
            <h5><?php echo e(ucfirst($category->name)); ?></h5>
        </div>
        <?php endif; ?>
        <?php $__currentLoopData = $category->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <img height="100em" class="card-img-top" src="<?php echo e($service->service_image_url); ?>">
                    <div class="card-body p-2">
                        <p class="font-weight-normal"><?php echo e(ucwords($service->name)); ?></p>
                        <?php echo ($service->discount > 0) ? "<s class='h6 text-danger'>".$settings->currency->currency_symbol.$service->price."</s> ".$settings->currency->currency_symbol.$service->discounted_price : $settings->currency->currency_symbol.$service->price; ?>

                    </div>
                    <div class="card-footer p-1">
                        <a href="javascript:;"
                           data-service-price="<?php echo e($service->discounted_price); ?>"
                           data-service-id="<?php echo e($service->id); ?>"
                           data-service-name="<?php echo e(ucwords($service->name)); ?>"
                           class="btn btn-block btn-dark add-to-cart"><i class="fa fa-plus"></i> <?php echo app('translator')->getFromJson('app.add'); ?></a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/AMPPS/www/booking/resources/views/admin/pos/filtered_services.blade.php ENDPATH**/ ?>