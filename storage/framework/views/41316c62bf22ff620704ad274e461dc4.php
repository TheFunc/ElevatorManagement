<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>电梯管理系统 - 用户登录</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#165DFF',
                        secondary: '#4080FF',
                        light: '#E8F3FF',
                        dark: '#0E42D2',
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-primary to-secondary min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-building-4-line text-4xl text-primary"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">电梯管理系统</h1>
                <p class="text-gray-500 mt-2">请登录您的账户</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('login')); ?>" method="POST" class="space-y-5">
                <?php echo csrf_field(); ?>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">用户名</label>
                    <div class="relative">
                        <i class="ri-user-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="name" value="<?php echo e(old('name')); ?>" 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                            placeholder="请输入用户名">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">密码</label>
                    <div class="relative">
                        <i class="ri-lock-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" name="password" id="password"
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                            placeholder="请输入密码">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i id="passwordIcon" class="ri-eye-line"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>

                            class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <label for="remember" class="ml-2 text-sm text-gray-600">记住密码（自动登录）</label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-dark text-white font-medium py-3 rounded-lg transition-colors">
                    <i class="ri-login-box-line mr-2"></i>登 录
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-500">
                <!-- <p>默认管理员账号: <span class="font-medium text-gray-700">admin</span> / <span class="font-medium text-gray-700">admin</span></p> -->
            </div>
        </div>
    </div>

    <script>
    function togglePassword() {
        const password = document.getElementById('password');
        const icon = document.getElementById('passwordIcon');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.className = 'ri-eye-off-line';
        } else {
            password.type = 'password';
            icon.className = 'ri-eye-line';
        }
    }
    </script>
</body>
</html><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/auth/login.blade.php ENDPATH**/ ?>