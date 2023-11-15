<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->getFromJson('app.add'); ?> <?php echo app('translator')->getFromJson('app.category'); ?></h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form role="form" id="createForm"  class="ajax-form" method="POST">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="redirect_url" value="<?php echo e(url()->previous()); ?>">

                        <div class="row">
                            <div class="col-md">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.category'); ?> <?php echo app('translator')->getFromJson('app.name'); ?></label>
                                    <input type="text" name="name" id="name" class="form-control form-control-lg" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.category'); ?> <?php echo app('translator')->getFromJson('app.slug'); ?></label>
                                    <input type="text" name="slug" id="slug" class="form-control form-control-lg" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1"><?php echo app('translator')->getFromJson('app.image'); ?></label>
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="file" id="input-file-now" name="image" accept=".png,.jpg,.jpeg" class="dropify"
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

        function createSlug(value) {
            value = value.replace(/\s\s+/g, ' ');
            let slug = value.split(' ').join('-').toLowerCase();
            slug = slug.replace(/--+/g, '-');
            $('#slug').val(slug);
        }

        $('#name').keyup(function(e) {
            createSlug($(this).val());
        });

        $('#slug').keyup(function(e) {
            createSlug($(this).val());
        });

        $('#save-form').click(function () {

            $.easyAjax({
                url: '<?php echo e(route('admin.categories.store')); ?>',
                container: '#createForm',
                type: "POST",
                redirect: true,
                file:true,
                data: $('#createForm').serialize()
            })
        });

    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/AMPPS/www/booking/resources/views/admin/category/create.blade.php ENDPATH**/ ?>