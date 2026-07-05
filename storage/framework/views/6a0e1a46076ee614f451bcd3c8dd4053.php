<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="<?php echo e(__('Pagination Navigation')); ?>">
        <div class="flex gap-2 items-center justify-between sm:hidden">
            <?php if($paginator->onFirstPage()): ?>
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-not-allowed leading-5 rounded-lg">
                    <i class="ri-arrow-left-s-line mr-1 text-lg"></i>上一页
                </span>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary bg-white border border-gray-200 leading-5 rounded-lg hover:bg-light hover:border-primary/30 transition-all duration-200">
                    <i class="ri-arrow-left-s-line mr-1 text-lg"></i>上一页
                </a>
            <?php endif; ?>

            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary bg-white border border-gray-200 leading-5 rounded-lg hover:bg-light hover:border-primary/30 transition-all duration-200">
                    下一页<i class="ri-arrow-right-s-line ml-1 text-lg"></i>
                </a>
            <?php else: ?>
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-not-allowed leading-5 rounded-lg">
                    下一页<i class="ri-arrow-right-s-line ml-1 text-lg"></i>
                </span>
            <?php endif; ?>
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:gap-2 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500 leading-5">
                    共 <span class="font-semibold text-gray-700"><?php echo e($paginator->total()); ?></span> 条记录，
                    第 <span class="font-semibold text-gray-700"><?php echo e($paginator->currentPage()); ?></span> / <span class="font-semibold text-gray-700"><?php echo e($paginator->lastPage()); ?></span> 页
                </p>
            </div>

            <div>
                <span class="inline-flex rtl:flex-row-reverse shadow-sm rounded-lg overflow-hidden border border-gray-200 divide-x divide-gray-200">
                    
                    <?php if($paginator->onFirstPage()): ?>
                        <span aria-disabled="true" aria-label="<?php echo e(__('pagination.previous')); ?>">
                            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 cursor-not-allowed leading-5" aria-hidden="true">
                                <i class="ri-arrow-left-s-line text-lg"></i>
                            </span>
                        </span>
                    <?php else: ?>
                        <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white leading-5 hover:bg-light hover:text-primary transition-all duration-200" aria-label="<?php echo e(__('pagination.previous')); ?>">
                            <i class="ri-arrow-left-s-line text-lg"></i>
                        </a>
                    <?php endif; ?>

                    
                    <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if(is_string($element)): ?>
                            <span aria-disabled="true">
                                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white leading-5"><?php echo e($element); ?></span>
                            </span>
                        <?php endif; ?>

                        
                        <?php if(is_array($element)): ?>
                            <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == $paginator->currentPage()): ?>
                                    <span aria-current="page">
                                        <span class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-primary leading-5"><?php echo e($page); ?></span>
                                    </span>
                                <?php else: ?>
                                    <a href="<?php echo e($url); ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white leading-5 hover:bg-light hover:text-primary transition-all duration-200" aria-label="<?php echo e(__('Go to page :page', ['page' => $page])); ?>">
                                        <?php echo e($page); ?>

                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <?php if($paginator->hasMorePages()): ?>
                        <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white leading-5 hover:bg-light hover:text-primary transition-all duration-200" aria-label="<?php echo e(__('pagination.next')); ?>">
                            <i class="ri-arrow-right-s-line text-lg"></i>
                        </a>
                    <?php else: ?>
                        <span aria-disabled="true" aria-label="<?php echo e(__('pagination.next')); ?>">
                            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-gray-50 cursor-not-allowed leading-5" aria-hidden="true">
                                <i class="ri-arrow-right-s-line text-lg"></i>
                            </span>
                        </span>
                    <?php endif; ?>
                </span>
            </div>
        </div>
    </nav>
<?php endif; ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/vendor/pagination/tailwind.blade.php ENDPATH**/ ?>