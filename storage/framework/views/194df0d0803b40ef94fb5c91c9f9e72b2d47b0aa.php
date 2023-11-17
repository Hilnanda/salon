<?php if($smsSettings->nexmo_status == 'active'): ?>
    <?php if(!$user->mobile_verified && !session()->has('verify:request_id')): ?>
        <form method="POST" class="ajax-form" id="request-otp-form">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="mobile"><?php echo app('translator')->getFromJson('app.mobile'); ?></label>
                    </div>
                    <div class="col-md-10">
                        <div class="form-row">
                            <div class="col-sm-2">
                                <select name="calling_code" id="calling_code" class="form-control select2">
                                    <?php $__currentLoopData = $calling_codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value['dial_code']); ?>"
                                        <?php if($user->calling_code): ?>
                                            <?php echo e($user->calling_code == $value['dial_code'] ? 'selected' : ''); ?>

                                        <?php endif; ?>><?php echo e($value['dial_code'] . ' - ' . $value['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo e($user->mobile); ?>" autofocus />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="request-otp" class="btn btn-primary w-100"><?php echo app('translator')->getFromJson('app.requestOTP'); ?></button>
                    </div>
                </div>
            </div>
        </form>
    <?php elseif(session()->has('verify:request_id')): ?>
        <form method="POST" class="ajax-form" id="verify-otp-form">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="otp"><?php echo app('translator')->getFromJson('app.otp'); ?></label>
                    </div>
                    <div class="col-md-10">
                        <input type="text" name="otp" id="otp" class="form-control" placeholder="Enter OTP" autofocus autocomplete="off" />
                        <span>
                            <label class="text-danger mx-3" id="demo"></label>
                            <span class="attempts_left"></span>
                        </span>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="verify-otp" class="btn btn-primary w-100"><?php echo app('translator')->getFromJson('app.verifyMobile'); ?></button>
                    </div>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="row">
            <div class="col-md-12">
                <label for="mobile"><?php echo app('translator')->getFromJson('app.mobile'); ?></label>
            </div>
            <div class="col-md-10">
                <input type="text" readonly class="form-control" value="<?php echo e($user->mobile); ?>" />
                <span class="text-success ml-2">
                    <?php echo app('translator')->getFromJson('app.verified'); ?>
                </span>
            </div>
            <div class="col-md-2">
                <button type="button" id="change-mobile" class="btn btn-primary w-100"><?php echo app('translator')->getFromJson('app.changeMobileNumber'); ?></button>
            </div>
        </div>

    <?php endif; ?>
<?php endif; ?>
<?php /**PATH G:\Pekerjaan\Simetris\salon\salon\resources\views/partials/admin_verify_phone.blade.php ENDPATH**/ ?>