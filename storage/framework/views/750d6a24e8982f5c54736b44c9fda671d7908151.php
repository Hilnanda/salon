<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->getFromJson('app.add'); ?> <?php echo app('translator')->getFromJson('app.service'); ?></h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form role="form" id="createForm" class="ajax-form" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md">
                                <!-- text input -->
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.service'); ?> <?php echo app('translator')->getFromJson('app.name'); ?></label>
                                    <input type="text" name="name" id="name" class="form-control form-control-lg" <?php if(!empty($service)): ?> value="<?php echo e($service->name); ?>" <?php endif; ?> autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.service'); ?> <?php echo app('translator')->getFromJson('app.slug'); ?></label>
                                    <input type="text" name="slug" id="slug" class="form-control form-control-lg" <?php if(!empty($service)): ?> value="<?php echo e($service->slug); ?>" <?php endif; ?> autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.service'); ?> <?php echo app('translator')->getFromJson('app.description'); ?></label>
                                    <textarea name="description" id="description" cols="30" class="form-control-lg form-control" rows="4"><?php echo e(!empty($service) ? $service->description : ''); ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.price'); ?></label>
                                    <input onkeypress="return isNumberKey(event)" type="number" step="0.01" min="0" name="price" id="price" class="form-control form-control-lg" <?php if(!empty($service)): ?> value="<?php echo e($service->price); ?>" <?php endif; ?>/>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('modules.services.discount'); ?></label>
                                    <div class="input-group">
                                        <input onkeypress="return isNumberKey(event)" type="number" max="100" class="form-control form-control-lg" name="discount" id="discount" min="0" <?php if(!empty($service)): ?> value="<?php echo e($service->discount); ?>" <?php endif; ?>>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary dropdown-toggle" id="discount-type-select" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?php if(!empty($service)): ?>
                                                    <?php if($service->discount_type == 'percent'): ?>
                                                        <?php echo app('translator')->getFromJson('modules.services.percent'); ?>
                                                    <?php else: ?>
                                                        <?php echo app('translator')->getFromJson('modules.services.fixed'); ?>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                        <?php echo app('translator')->getFromJson('modules.services.percent'); ?>
                                                <?php endif; ?>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item discount_type" data-type="percent" href="javascript:;"><?php echo app('translator')->getFromJson('modules.services.percent'); ?></a>
                                                <a class="dropdown-item discount_type" data-type="fixed" href="javascript:;"><?php echo app('translator')->getFromJson('modules.services.fixed'); ?></a>
                                            </div>
                                        </div>

                                        <input type="hidden" id="discount-type" name="discount_type" value="percent">

                                    </div>

                                </div>
                            </div>

                            <div class="col-md-3 offset-md-1">
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('modules.services.discountedPrice'); ?></label>
                                    <p class="form-control-static" id="discounted-price" style="font-size: 1.5rem">--</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.location'); ?></label>
                                    <div class="input-group">
                                        <select name="location_id" id="location_id" class="form-control form-control-lg">
                                            <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($location->id); ?>" <?php if(!empty($service) && $service->location->id == $location->id): ?>
                                                    selected
                                                <?php endif; ?>><?php echo e($location->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-success" onclick="javascript: location = '<?php echo e(route('admin.locations.create')); ?>';" type="button"><i class="fa fa-plus"></i> <?php echo app('translator')->getFromJson('app.add'); ?></button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('app.category'); ?></label>
                                    <div class="input-group">
                                        <select name="category_id" id="category_id" class="form-control form-control-lg">
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category->id); ?>" <?php if(!empty($service) && $service->category->id == $category->id): ?>
                                                    selected
                                                <?php endif; ?>><?php echo e($category->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-success" id="add-category" type="button"><i class="fa fa-plus"></i> <?php echo app('translator')->getFromJson('app.add'); ?></button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label><?php echo app('translator')->getFromJson('modules.services.time'); ?></label>
                                    <div class="input-group">
                                        <input onkeypress="return isNumberKey(event)" type="number" class="form-control form-control-lg" name="time" <?php if(!empty($service)): ?> value="<?php echo e($service->time); ?>" <?php endif; ?>>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary dropdown-toggle" id="time-type-select" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?php if(!empty($service)): ?>
                                                    <?php switch($service->time_type):
                                                        case ('minutes'): ?>
                                                            <?php echo app('translator')->getFromJson('app.minutes'); ?>
                                                            <?php break; ?>
                                                        <?php case ('hours'): ?>
                                                            <?php echo app('translator')->getFromJson('app.hours'); ?>
                                                            <?php break; ?>
                                                        <?php case ('days'): ?>
                                                            <?php echo app('translator')->getFromJson('app.days'); ?>
                                                            <?php break; ?>
                                                        <?php default: ?>
                                                    <?php endswitch; ?>
                                                <?php else: ?>
                                                    <?php echo app('translator')->getFromJson('app.minutes'); ?>
                                                <?php endif; ?>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item time_type" data-type="minutes" href="javascript:;"><?php echo app('translator')->getFromJson('app.minutes'); ?></a>
                                                <a class="dropdown-item time_type" data-type="hours" href="javascript:;"><?php echo app('translator')->getFromJson('app.hours'); ?></a>
                                                <a class="dropdown-item time_type" data-type="days" href="javascript:;"><?php echo app('translator')->getFromJson('app.days'); ?></a>
                                            </div>
                                        </div>

                                        <input type="hidden" id="time-type" name="time_type" value="minutes">

                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="button" class="btn btn-block btn-outline-info btn-sm col-md-2 select-image-button" style="margin-bottom: 10px;display: none "><i class="fa fa-upload"></i> File Select Or Upload</button>
                                <div id="file-upload-box" >
                                    <div class="row" id="file-dropzone">
                                        <div class="col-md-12">
                                            <div class="dropzone"
                                                    id="file-upload-dropzone">
                                                <?php echo e(csrf_field()); ?>

                                                <div class="fallback">
                                                    <input name="file" type="file" multiple/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="text-danger"><?php echo app('translator')->getFromJson('modules.theme.recommendedResolutionNote'); ?></h6>
                                <h6 class="text-danger"><?php echo app('translator')->getFromJson('modules.businessServices.defaultImageNotice'); ?></h6>

                                <input type="hidden" name="serviceID" id="serviceID">

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

        $(function () {
            $('#description').summernote({
                dialogsInBody: true,
                height: 300,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['fontsize', ['fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ["view", ["fullscreen"]]
                ]
            })

            <?php if(!empty($service)): ?>
                $('#discount-type').val('<?php echo e($service->discount_type); ?>');
                $('#time-type').val('<?php echo e($service->time_type); ?>');
            <?php endif; ?>

            calculateDiscountedPrice();
        })

        Dropzone.autoDiscover = false;
        //Dropzone class
        myDropzone = new Dropzone("#file-upload-dropzone", {
            url: "<?php echo e(route('admin.business-services.storeImages')); ?>",
            headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
            paramName: "file",
            maxFilesize: 10,
            maxFiles: 10,
            acceptedFiles: "image/*",
            autoProcessQueue: false,
            uploadMultiple: true,
            addRemoveLinks:true,
            parallelUploads:10,
            init: function () {
                myDropzone = this;
            },
            dictDefaultMessage: "<?php echo app('translator')->getFromJson('app.dropzone.defaultMessage'); ?>",
            dictRemoveFile: "<?php echo app('translator')->getFromJson('app.dropzone.removeFile'); ?>"
        });

        myDropzone.on('sending', function(file, xhr, formData) {
            var id = $('#serviceID').val();
            formData.append('service_id', id);
        });

        myDropzone.on('completemultiple', function () {
            var msgs = "<?php echo app('translator')->getFromJson('messages.createdSuccessfully'); ?>";
            $.showToastr(msgs, 'success');
            window.location.href = '<?php echo e(route('admin.business-services.index')); ?>'
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

        $('.time_type').click(function () {
            var type = $(this).data('type');

            $('#time-type-select').html(type);
            $('#time-type').val(type);
        });


        $('.discount_type').click(function () {
            var type = $(this).data('type');

            $('#discount-type-select').html(type);
            $('#discount-type').val(type);
            calculateDiscountedPrice();
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '<?php echo e(route('admin.business-services.store')); ?>',
                container: '#createForm',
                type: "POST",
                redirect: true,
                file:true,
                data: $('#createForm').serialize(),
                success: function (response) {
                    if (myDropzone.getQueuedFiles().length > 0) {
                        serviceID = response.serviceID;
                        $('#serviceID').val(response.serviceID);
                        myDropzone.processQueue();
                    }
                    else{
                        var msgs = "<?php echo app('translator')->getFromJson('messages.createdSuccessfully'); ?>";
                        $.showToastr(msgs, 'success');
                        window.location.href = '<?php echo e(route('admin.business-services.index')); ?>'
                    }
                }
            })
        });

        $('#add-category').click(function () {
            window.location = '<?php echo e(route("admin.categories.create")); ?>';
        });

        $('#discount, #price').keyup(function () {
            calculateDiscountedPrice();
        });

        $('#discount, #price').change(function () {
            calculateDiscountedPrice();
        });

        $('#discount, #price').on('wheel', function () {
            calculateDiscountedPrice();
        });

        function calculateDiscountedPrice() {
            var price = $('#price').val();
            var discount = $('#discount').val();
            var discountType = $('#discount-type').val();

            if (discountType == 'percent') {
                if(discount > 100){
                    $('#discount').val(100);
                    discount = 100;
                }
            }
            else {
                if (parseInt(discount) > parseInt(price)) {
                    $('#discount').val(price);
                    discount = price;
                }
            }

            var discountedPrice = price;

            if(discount >= 0 && discount >= '' && price != '' && price > 0){
                if(discountType == 'percent'){
                    discountedPrice = parseFloat(price)-(parseFloat(price)*(parseFloat(discount)/100));
                }
                else{
                    discountedPrice = parseFloat(price)-parseFloat(discount);
                }
            }
            if(discount != '' && price != '' && price > 0){
                $('#discounted-price').html(discountedPrice.toFixed(2));
            }
            else {
                $('#discounted-price').html('--');
            }
        }


        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
            return true;
        }


    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/AMPPS/www/booking/resources/views/admin/business_service/create.blade.php ENDPATH**/ ?>