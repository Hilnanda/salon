<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(ucfirst($settings->company_name)); ?></title>

    <link rel="icon" href="<?php echo e(asset('favicon/favicon.ico')); ?>" type="image/x-icon" />
    <link type="text/css" rel="stylesheet" href="<?php echo e(asset('assets/css/front-styles.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
    <link type="text/css" rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
    <link type="text/css" rel="stylesheet" href="<?php echo e(asset('assets/css/select2.min.css')); ?>">
    <link type="text/css" rel="stylesheet" href="<?php echo e(asset('assets/css/responsive.css')); ?>">
    <link type="text/css" rel="stylesheet" href="<?php echo e(asset('front-assets/css/helper.css')); ?>">

    <style>
        .float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:40px;
	right:40px;
	background-color:#25d366;
	color:#FFF;
	border-radius:50px;
	text-align:center;
  font-size:30px;
	box-shadow: 2px 2px 3px #999;
  z-index:100;
}

.my-float{
	margin-top:16px;
}
        :root {
            --primary-color: <?php echo e($frontThemeSettings->primary_color); ?>;
            --dark-primary-color: <?php echo e($frontThemeSettings->primary_color); ?>;
        }

        <?php echo $frontThemeSettings->custom_css; ?>

    </style>
</head>


<body>

    <?php echo $__env->make('sections.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo $__env->make('sections.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <a href="https://api.whatsapp.com/send?phone=0895348096044" class="float" target="_blank">
    <i class="fa fa-whatsapp my-float"></i>
    </a>

    
    <div class="modal fade bs-modal-lg in" id="application-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo app('translator')->getFromJson('app.cancel'); ?></button>
                    <button type="button" class="btn btn-success"><i class="fa fa-check"></i> <?php echo app('translator')->getFromJson('app.save'); ?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    

    <script src="<?php echo e(asset('assets/js/front-scripts.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/select2.min.js')); ?>"></script>
    <script>
        $(function() {
            toastr.options = {
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": true
            };
        });

        function makeSingular(time, type) {
            singular = '';
            plural = '';

            if (time == 1) {
                switch (type) {
                    case 'minutes':
                        singular = "<?php echo app('translator')->getFromJson('app.minute'); ?>";
                        break;
                    case 'hours':
                        singular = "<?php echo app('translator')->getFromJson('app.hour'); ?>";
                        break;
                    case 'days':
                        singular = "<?php echo app('translator')->getFromJson('app.day'); ?>";
                        break;
                    default:
                        break;
                }
                return singular;
            }
            else {
                switch (type) {
                    case 'minutes':
                        plural = "<?php echo app('translator')->getFromJson('app.minutes'); ?>";
                        break;
                    case 'hours':
                        plural = "<?php echo app('translator')->getFromJson('app.hours'); ?>";
                        break;
                    case 'days':
                        plural = "<?php echo app('translator')->getFromJson('app.days'); ?>";
                        break;
                    default:
                        break;
                }
                return plural;
            }
        }

        function goToPage(method, pageUrl, data = null) {
            var options = {
                url: pageUrl,
                type: method,
                // container: 'section.section'
                success: function (response) {
                    if (response.status !== 'fail') {
                        window.location.href = pageUrl
                    }
                }
            };

            if (data) {
                options.data = data
            }

            $.easyAjax(options)
        }

        var LightenColor = function(color, percent) {
            var num = parseInt(color.replace('#',''),16),
                amt = Math.round(2.55 * percent),
                R = (num >> 16) + amt,
                B = (num >> 8 & 0x00FF) + amt,
                G = (num & 0x0000FF) + amt;

            return (0x1000000 + (R<255?R<1?0:R:255)*0x10000 + (B<255?B<1?0:B:255)*0x100 + (G<255?G<1?0:G:255)).toString(16).slice(1);
        };

        var DarkenColor = function(color, percent) {
            var num = parseInt(color.replace('#',''),16),
                amt = Math.round(2.55 * percent),
                R = (num >> 16) - amt,
                B = (num >> 8 & 0x00FF) - amt,
                G = (num & 0x0000FF) - amt;

            return (0x1000000 + (R<255?R<1?0:R:255)*0x10000 + (B<255?B<1?0:B:255)*0x100 + (G<255?G<1?0:G:255)).toString(16).slice(1);
        };

        var primaryColor = getComputedStyle(document.documentElement)
            .getPropertyValue('--primary-color');

        document.documentElement.style.setProperty('--dark-primary-color', '#'+DarkenColor(primaryColor, 15));
    </script>

    <?php echo $__env->yieldPushContent('footer-script'); ?>

</body>

</html>
<?php /**PATH G:\Pekerjaan\Simetris\salon\salon\resources\views/layouts/front.blade.php ENDPATH**/ ?>