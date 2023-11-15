<?php if($bookingTime->status == 'enabled'): ?>
    <?php if($bookingTime->multiple_booking === 'yes' && $bookingTime->max_booking != 0 && $bookings->count() >= $bookingTime->max_booking): ?>
        <div class="alert alert-custom mt-3">
            <?php echo app('translator')->getFromJson('front.maxBookingLimitReached'); ?>
        </div>
    <?php else: ?>
        <ul class="time-slots px-1 py-1 px-md-5 py-md-5">
            <?php $slot_count = 1; $check_remaining_booking_slots = 0; ?>
            <?php for($d = $startTime;$d < $endTime;$d->addMinutes($bookingTime->slot_duration)): ?>
                <?php $slotAvailable = 1; ?>
                <?php if($bookingTime->multiple_booking === 'no' && $bookings->count() > 0): ?>
                    <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($booking->date_time->format($settings->time_format) == $d->format($settings->time_format)): ?>
                            <?php $slotAvailable = 0; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                <?php if($slotAvailable == 1): ?>
                    <?php $check_remaining_booking_slots++; ?>
                    <li>
                        <label class="custom-control custom-radio">
                        <input id="radio<?php echo e($slot_count); ?>" onclick="checkUserAvailability('<?php echo e($d); ?>', <?php echo e($slot_count); ?>, '<?php echo e($d->format($settings->time_format)); ?>')" type="radio" value="<?php echo e($d->format('H:i:s')); ?>" class="custom-control-input" name="booking_time">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description"><?php echo e($d->format($settings->time_format)); ?></span>
                        </label>
                    </li>
                <?php endif; ?>
                <?php $slot_count++; ?>
            <?php endfor; ?>
        </ul>
        <div class="alert alert-custom mt-3" id="no_emp_avl_msg" style="display: none">
            <?php echo app('translator')->getFromJson('front.noEmployeeAvailableAt'); ?> <span id="timeSpan"><span>.
        </div>

        <?php if($slot_count==1 || $check_remaining_booking_slots==0): ?>
            <div class="alert alert-custom mt-3">
                <?php echo app('translator')->getFromJson('front.bookingSlotNotAvailable'); ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-custom mt-3">
        <?php echo app('translator')->getFromJson('front.bookingSlotNotAvailable'); ?>
    </div>
<?php endif; ?>
<?php /**PATH G:\Pekerjaan\Simetris\salon\salon\resources\views/front/booking_slots.blade.php ENDPATH**/ ?>