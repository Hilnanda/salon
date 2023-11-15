<div class="modal-header">
    <h4 class="modal-title"><?php echo app('translator')->getFromJson('app.createNew'); ?> <?php echo app('translator')->getFromJson('app.language'); ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <form role="form" id="createLangForm" class="ajax-form" method="POST">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-12">
                <!-- text input -->
                <div class="form-group">
                    <label><?php echo app('translator')->getFromJson('app.language'); ?> <?php echo app('translator')->getFromJson('app.name'); ?></label>
                    <input type="text" name="language_name" id="language_name" class="form-control form-control-lg" value="">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label><?php echo app('translator')->getFromJson('app.language'); ?> <?php echo app('translator')->getFromJson('app.code'); ?></label>
                    <input type="text" name="language_code" id="language_code" class="form-control form-control-lg" value="">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label><?php echo app('translator')->getFromJson('app.language'); ?> <?php echo app('translator')->getFromJson('app.status'); ?></label>

                    <select name="status" id="lang-status" class="form-control form-control-lg">
                        <option value="enabled"><?php echo app('translator')->getFromJson('app.enabled'); ?></option>
                        <option value="disabled"><?php echo app('translator')->getFromJson('app.disabled'); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="button" id="save-form" class="btn btn-success btn-light-round"><i
                    class="fa fa-check"></i> <?php echo app('translator')->getFromJson('app.save'); ?></button>
        </div>
    </form>
</div>

<script>
    $('#save-form').click(function () {
        const form = $('#createLangForm');

        $.easyAjax({
            url: '<?php echo e(route('admin.language-settings.store')); ?>',
            container: '#createLangForm',
            type: "POST",
            redirect: true,
            data: form.serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    $('#application-modal').modal('hide');
                    langTable._fnDraw();
                    if ($('#lang-status').val() == 'enabled') {
                        location.reload();
                    }
                }
            }
        })
    });
</script>
<?php /**PATH /Applications/AMPPS/www/booking/resources/views/admin/language/create.blade.php ENDPATH**/ ?>