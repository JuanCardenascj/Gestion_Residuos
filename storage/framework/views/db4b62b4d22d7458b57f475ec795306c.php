

<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Recuperar Contraseña</h1>
        <form method="POST" action="<?php echo e(route('password.email')); ?>">
            <?php echo csrf_field(); ?>
            <input type="email" name="email" required placeholder="Correo electrónico">
            <button type="submit">Enviar enlace</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ardi-mi\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>