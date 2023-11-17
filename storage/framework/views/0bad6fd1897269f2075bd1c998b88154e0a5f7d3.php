<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->getFromJson('menu.profile'); ?></h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div id="verify-mobile">
                        <?php echo $__env->make('partials.admin_verify_phone', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <form role="form" id="createForm"  class="ajax-form" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.name'); ?></label>
                                    <input type="text" class="form-control" name="name" value="<?php echo e(ucwords($user->name)); ?>">
                                </div>

                                <!-- text input -->
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.email'); ?></label>
                                    <input type="email" class="form-control" name="email" value="<?php echo e($user->email); ?>">
                                </div>

                                <!-- text input -->
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.password'); ?></label>
                                    <input type="password" class="form-control" name="password">
                                    <span class="help-block"><?php echo app('translator')->getFromJson('messages.leaveBlank'); ?></span>
                                </div>

                                <?php if($smsSettings->nexmo_status == 'deactive'): ?>
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label><?php echo app('translator')->getFromJson('app.mobile'); ?></label>
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
                                                <input type="text" class="form-control" name="mobile" value="<?php echo e($user->mobile); ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <label for="exampleInputPassword1"><?php echo app('translator')->getFromJson('app.image'); ?></label>
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="file" id="input-file-now" name="image" accept=".png,.jpg,.jpeg" data-default-file="<?php echo e($user->user_image_url); ?>" class="dropify"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="button" id="save-form" class="btn btn-success btn-light-round"><i
                                                class="fa fa-check"></i> <?php echo app('translator')->getFromJson('app.save'); ?></button>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('footer-js'); ?>

    <script>
        $('.dropify').dropify({
            messages: {
                default: '<?php echo app('translator')->getFromJson("app.dragDrop"); ?>',
                replace: '<?php echo app('translator')->getFromJson("app.dragDropReplace"); ?>',
                remove: '<?php echo app('translator')->getFromJson("app.remove"); ?>',
                error: '<?php echo app('translator')->getFromJson('app.largeFile'); ?>'
            }
        });

        $('body').on('click', '#change-mobile', function () {
            $.easyAjax({
                url: "<?php echo e(route('changeMobile')); ?>",
                container: '#verify-mobile',
                type: "GET",
                success: function (response) {
                    $('#verify-mobile').html(response.view);
                    $('.select2').select2();
                }
            })
        });

        $('#save-form').click(function () {

            $.easyAjax({
                url: '<?php echo e(route('admin.profile.store')); ?>',
                container: '#createForm',
                type: "POST",
                redirect: true,
                file:true,
                data: $('#createForm').serialize()
            })
        });
    </script>

    <script>
        var x = '';

        function clearLocalStorage() {
            localStorage.removeItem('otp_expiry');
            localStorage.removeItem('otp_attempts');
        }

        function checkSessionAndRemove() {
            $.easyAjax({
                url: '<?php echo e(route('removeSession')); ?>',
                type: 'GET',
                data: {'sessions': ['verify:request_id']}
            })
        }

        function startCounter(deadline) {
            x = setInterval(function() {
                var now = new Date().getTime();
                var t = deadline - now;

                var days = Math.floor(t / (1000 * 60 * 60 * 24));
                var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60));
                var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((t % (1000 * 60)) / 1000);

                $('#demo').html('Time Left: '+minutes + ":" + seconds);
                $('.attempts_left').html(`${localStorage.getItem('otp_attempts')} attempts left`);

                if (t <= 0) {
                    clearInterval(x);
                    clearLocalStorage();
                    checkSessionAndRemove();
                    location.href = '<?php echo e(route('admin.profile.index')); ?>'
                }
            }, 1000);
        }

        if (localStorage.getItem('otp_expiry') !== null) {
            let localExpiryTime = localStorage.getItem('otp_expiry');
            let now = new Date().getTime();

            if (localExpiryTime - now < 0) {
                clearLocalStorage();
                checkSessionAndRemove();
            }
            else {
                $('#otp').focus().select();
                startCounter(localStorage.getItem('otp_expiry'));
            }
        }

        function sendOTPRequest() {
            $.easyAjax({
                url: '<?php echo e(route('sendOtpCode.account')); ?>',
                type: 'POST',
                container: '#request-otp-form',
                messagePosition: 'inline',
                data: $('#request-otp-form').serialize(),
                success: function (response) {
                    if (response.status == 'success') {
                        localStorage.setItem('otp_attempts', 3);

                        $('#verify-mobile').html(response.view);
                        $('.attempts_left').html(`3 attempts left`);

                        let html = `<div class="alert alert-info mb-0" role="alert">
                            <?php echo app('translator')->getFromJson('messages.info.verifyAlert'); ?>
                            <a href="<?php echo e(route('admin.profile.index')); ?>" class="btn btn-warning">
                                <?php echo app('translator')->getFromJson('menu.profile'); ?>
                            </a>
                        </div>`;

                        $('#verify-mobile-info').html(html);
                        $('#otp').focus();

                        var now = new Date().getTime();
                        var deadline = new Date(now + parseInt('<?php echo e(config('nexmo.settings.pin_expiry')); ?>')*1000).getTime();

                        localStorage.setItem('otp_expiry', deadline);
                        // intialize countdown
                        startCounter(deadline);
                    }
                    if (response.status == 'fail') {
                        $('#mobile').focus();
                    }
                }
            });
        }

        function sendVerifyRequest() {
            $.easyAjax({
                url: '<?php echo e(route('verifyOtpCode.account')); ?>',
                type: 'POST',
                container: '#verify-otp-form',
                messagePosition: 'inline',
                data: $('#verify-otp-form').serialize(),
                success: function (response) {
                    if (response.status == 'success') {
                        clearLocalStorage();

                        $('#verify-mobile').html(response.view);
                        $('#verify-mobile-info').html('');

                        // select2 reinitialize
                        $('.select2').select2();
                    }
                    if (response.status == 'fail') {
                        // show number of attempts left
                        let currentAttempts = localStorage.getItem('otp_attempts');

                        if (currentAttempts == 1) {
                            clearLocalStorage();
                        }
                        else {
                            currentAttempts -= 1;

                            $('.attempts_left').html(`${currentAttempts} attempts left`);
                            $('#otp').focus().select();
                            localStorage.setItem('otp_attempts', currentAttempts);
                        }

                        if (Object.keys(response.data).length > 0) {
                            $('#verify-mobile').html(response.data.view);

                            // select2 reinitialize
                            $('.select2').select2();

                            clearInterval(x);
                        }
                    }
                }
            });
        }

        $('body').on('submit', '#request-otp-form', function (e) {
            e.preventDefault();
            sendOTPRequest();
        })

        $('body').on('click', '#request-otp', function () {
            sendOTPRequest();
        })

        $('body').on('submit', '#verify-otp-form', function (e) {
            e.preventDefault();
            sendVerifyRequest();
        })

        $('body').on('click', '#verify-otp', function() {
            sendVerifyRequest();
        })
    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH G:\Pekerjaan\Simetris\salon\salon\resources\views/admin/profile/index.blade.php ENDPATH**/ ?>