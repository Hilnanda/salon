<div class="modal-header">
    <h4 class="modal-title"><?php echo app('translator')->getFromJson('app.edit'); ?> <?php echo app('translator')->getFromJson('app.language'); ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <form role="form" id="editLangForm"  class="ajax-form" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="id" value="<?php echo e($language->id); ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label><?php echo app('translator')->getFromJson('app.language'); ?> <?php echo app('translator')->getFromJson('app.name'); ?></label>
                    <input type="text" name="language_name" id="language_name" class="form-control form-control-lg" value="<?php echo e($language->language_name); ?>">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label><?php echo app('translator')->getFromJson('app.language'); ?> <?php echo app('translator')->getFromJson('app.code'); ?></label>
                    <input type="text" name="language_code" id="language_code" class="form-control form-control-lg" value="<?php echo e($language->language_code); ?>">
                </div>
            </div>
            <?php if($language->language_code !== $settings->locale): ?>
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?php echo app('translator')->getFromJson('app.language'); ?> <?php echo app('translator')->getFromJson('app.status'); ?></label>

                        <select name="status" id="lang-status" class="form-control form-control-lg">
                            <option <?php if($language->status == 'enabled'): ?>
                                selected
                            <?php endif; ?> value="enabled"><?php echo app('translator')->getFromJson('app.enabled'); ?></option>
                            <option <?php if($language->status == 'disabled'): ?>
                                selected
                            <?php endif; ?> value="disabled"><?php echo app('translator')->getFromJson('app.disabled'); ?></option>
                        </select>
                    </div>
                </div>
            <?php else: ?>
                <input id="lang-status" type="hidden" name="status" value="enabled">
            <?php endif; ?>
        </div>
        <div class="form-group">
            <button type="button" id="save-form" class="btn btn-success btn-light-round"><i
                        class="fa fa-check"></i> <?php echo app('translator')->getFromJson('app.save'); ?></button>
        </div>
    </form>
</div>

<script>
    $('#save-form').click(function () {
        const form = $('#editLangForm');

        $.easyAjax({
            url: '<?php echo e(route('admin.language-settings.update', $language->id)); ?>',
            container: '#editLangForm',
            type: "PUT",
            redirect: true,
            data: form.serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    $('#application-modal').modal('hide');
                    langTable._fnDraw();

                    if ($('#lang-status').val() !== '<?php echo e($language->status); ?>' || ($('#language_code').val() !== '<?php echo e($language->language_code); ?>' && '<?php echo e($language->language_code); ?>' === '<?php echo e($settings->locale); ?>')) {
                        location.reload();
                    }
                }
            }
        })
    });
</script>
<?php /**PATH /Applications/AMPPS/www/booking/resources/views/admin/language/edit.blade.php ENDPATH**/ ?>