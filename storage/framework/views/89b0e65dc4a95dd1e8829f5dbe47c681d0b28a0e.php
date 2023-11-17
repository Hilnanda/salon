<div class="col-md-2 text-center mt-3 border-right">
    <h6 class="text-uppercase"><?php echo app('translator')->getFromJson('app.completed'); ?></h6>
    <p><?php echo e($completedBookings); ?></p>
</div>

<div class="col-md-2 text-center mt-3 border-right">
    <h6 class="text-uppercase"><?php echo app('translator')->getFromJson('app.approved'); ?></h6>
    <p><?php echo e($approvedBookings); ?></p>
</div>

<div class="col-md-2 text-center mt-3 border-right">
    <h6 class="text-uppercase"><?php echo app('translator')->getFromJson('app.in progress'); ?></h6>
    <p><?php echo e($inProgressBookings); ?></p>
</div>

<div class="col-md-2 text-center mt-3 border-right">
    <h6 class="text-uppercase"><?php echo app('translator')->getFromJson('app.pending'); ?></h6>
    <p><?php echo e($pendingBookings); ?></p>
</div>

<div class="col-md-2 text-center mt-3 border-right">
    <h6 class="text-uppercase"><?php echo app('translator')->getFromJson('app.canceled'); ?></h6>
    <p><?php echo e($canceledBookings); ?></p>
</div>

<div class="col-md-2 text-center mt-3">
    <h6 class="text-uppercase"><?php echo app('translator')->getFromJson('modules.booking.earning'); ?></h6>
    <p><?php echo e($settings->currency->currency_symbol); ?><?php echo e(round($earning, 2)); ?></p>
</div>
<?php /**PATH G:\Pekerjaan\Simetris\salon\salon\resources\views/partials/customer_stats.blade.php ENDPATH**/ ?>