<?php $__env->startSection('content'); ?>
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
    <div class="row">
        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->getFromJson('app.add'); ?> <?php echo app('translator')->getFromJson('menu.employee'); ?></h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form role="form" id="createForm"  class="ajax-form" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.name'); ?></label>
                                    <input type="text" class="form-control form-control-lg" name="name" value="" autocomplete="off">
                                </div>

                                <!-- text input -->
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.email'); ?></label>
                                    <input type="email" class="form-control form-control-lg" name="email" value="" autocomplete="off">
                                </div>

                                <!-- text input -->
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.password'); ?></label>
                                    <input type="password" class="form-control form-control-lg" name="password">
                                    
                                </div>

                                <!-- text input -->
                                

                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.employeeGroup'); ?></label>
                                    <div class="input-group">
                                        <select name="group_id" id="group_id" class="form-control form-control-lg select2">
                                            <option value="0"><?php echo app('translator')->getFromJson('app.selectEmployeeGroup'); ?></option>
                                            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-success" id="add-group" type="button"><i class="fa fa-plus"></i> <?php echo app('translator')->getFromJson('app.add'); ?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.assignRole'); ?></label>
                                    <select name="role_id" id="role_id" class="form-control form-control-lg select2">
                                        <option value="0"><?php echo app('translator')->getFromJson('app.selectEmployeeRole'); ?></option>
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->id); ?>"><?php echo e($role->display_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.assignServices'); ?></label>
                                    <select name="business_service_id[]" id="business_service_id" class="form-control form-control-lg select2" multiple="multiple" style="width: 100%">
                                        <option value="0"><?php echo app('translator')->getFromJson('app.selectServices'); ?></option>
                                        <?php $__currentLoopData = $business_services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($business_service->id); ?>"><?php echo e($business_service->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1"><?php echo app('translator')->getFromJson('app.image'); ?></label>
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="file" id="input-file-now" name="image" accept=".png,.jpg,.jpeg" data-default-file="<?php echo e(asset('img/default-avatar-user.png')); ?>" class="dropify"
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

        $("#business_service_id").select2({
            placeholder: "Select Services",
            allowClear: true
        });


        $('.dropify').dropify({
            messages: {
                default: '<?php echo app('translator')->getFromJson("app.dragDrop"); ?>',
                replace: '<?php echo app('translator')->getFromJson("app.dragDropReplace"); ?>',
                remove: '<?php echo app('translator')->getFromJson("app.remove"); ?>',
                error: '<?php echo app('translator')->getFromJson('app.largeFile'); ?>'
            }
        });
        $('#add-group').click(function () {
            window.location = '<?php echo e(route("admin.employee-group.create")); ?>';
        })
        $('#save-form').click(function () {

            $.easyAjax({
                url: '<?php echo e(route('admin.employee.store')); ?>',
                container: '#createForm',
                type: "POST",
                redirect: true,
                file:true
            })
        });

    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH G:\Pekerjaan\Simetris\salon\salon\resources\views/admin/employees/create.blade.php ENDPATH**/ ?>