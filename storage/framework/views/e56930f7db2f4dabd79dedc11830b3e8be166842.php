<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="card-title">
        <?php echo app('translator')->getFromJson('modules.module.todos.todoList'); ?>
    </h3>
    <a href="<?php echo e(route('admin.todo-items.index')); ?>" class="btn btn-sm btn-custom">
        <?php echo app('translator')->getFromJson('modules.module.todos.viewAll'); ?>
    </a>
</div>

<div id="upper-box" class="todo-box mb-3">
    <div class="todo-title">
        <h5><?php echo app('translator')->getFromJson('modules.module.todos.pendingTasks'); ?></h5>
        <a href="javascript:showNewTodoForm();" class="btn btn-sm btn-add"><i class="fa fa-plus"></i></a>
    </div>
    <ul class="list-group px-3 py-2" id="pending-tasks">
        <?php $__empty_1 = true; $__currentLoopData = $pendingTodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li data-id="<?php echo e($todo->id); ?>" data-position="<?php echo e($todo->position); ?>" class="draggable list-group-item">
                <div class="handle">
                    <i class="fa fa-bars"></i>
                </div>
                <div class="d-flex">
                    <span class="mb-2">
                        <input data-id="<?php echo e($todo->id); ?>" type="checkbox" name="status" id="status-<?php echo e($todo->id); ?>">
                        <label for="status-<?php echo e($todo->id); ?>"><?php echo e($todo->title); ?></label>
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <p class="mb-0"><?php echo e($todo->created_at->format($dateFormat)); ?></p>
                    <div class="text-right">
                        <a href="javascript:showUpdateTodoForm('<?php echo e($todo->id); ?>');" class="btn btn-edit"><i class="fa fa-pencil-square-o"></i></a>
                        <a href="javascript:deleteTodoItem('<?php echo e($todo->id); ?>');" class="btn btn-delete"><i class="fa fa-trash-o"></i></a>
                    </div>
                </div>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="list-group-item" id="no-pending-task">
                <h6 class="card-title">
                    <?php echo app('translator')->getFromJson('modules.module.todos.noPendingTasks'); ?>
                </h6>
            </li>
        <?php endif; ?>
    </ul>
</div>
<div id="lower-box" class="todo-box">
    <div class="todo-title">
        <h5><?php echo app('translator')->getFromJson('modules.module.todos.completedTasks'); ?></h5>
    </div>
    <ul class="list-group px-3 py-2" id="completed-tasks">
        <?php $__empty_1 = true; $__currentLoopData = $completedTodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li data-id="<?php echo e($todo->id); ?>" data-position="<?php echo e($todo->position); ?>" class="draggable list-group-item">
                <div class="handle">
                    <i class="fa fa-bars"></i>
                </div>
                <div class="d-flex">
                    <span class="mb-2">
                        <input data-id="<?php echo e($todo->id); ?>" checked type="checkbox" name="status" id="status-<?php echo e($todo->id); ?>">
                        <label for="status-<?php echo e($todo->id); ?>"><?php echo e($todo->title); ?></label>
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <p class="mb-0"><?php echo e($todo->created_at->format($dateFormat)); ?></p>
                    <div class="text-right">
                        <a href="javascript:showUpdateTodoForm('<?php echo e($todo->id); ?>');" class="btn btn-edit"><i class="fa fa-pencil-square-o"></i></a>
                        <a href="javascript:deleteTodoItem('<?php echo e($todo->id); ?>');" class="btn btn-delete"><i class="fa fa-trash-o"></i></a>
                    </div>
                </div>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="list-group-item" id="no-completed-task">
                <h6 class="card-title">
                    <?php echo app('translator')->getFromJson('modules.module.todos.noCompletedTasks'); ?>
                </h6>
            </li>
        <?php endif; ?>
    </ul>
</div>

<?php /**PATH /Applications/AMPPS/www/booking/resources/views/partials/todo_items_list.blade.php ENDPATH**/ ?>