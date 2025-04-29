

<?php $__env->startSection('content'); ?>
<div class="card mx-auto" style="max-width: 400px;">
    <div class="card-body">
        <h3 class="card-title mb-4">Iniciar Sesión</h3>

        <form method="POST" action="<?php echo e(url('/login')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Ingresar</button>
        </form>

        <div class="text-center mt-3">
            ¿No tienes cuenta? <a href="<?php echo e(route('register')); ?>">Regístrate</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ardi-mi\resources\views/auth/login.blade.php ENDPATH**/ ?>