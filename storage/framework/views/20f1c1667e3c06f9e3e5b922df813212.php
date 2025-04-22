

<?php $__env->startSection('content'); ?>
<div class="container" id="login-page">
    <div class="header">
        <!--<img src="<?php echo e(asset('images/logo.png')); ?>" alt="logo" class="logo">-->
        <span>ARDI-MI ♻️</span>
    </div>
    <h1>Gestión de Residuos</h1>

    <div id="form-section">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>
            <input type="email" id="loginEmail" name="email" placeholder="Correo electrónico" required />
            <input type="password" id="loginPassword" name="password" placeholder="Contraseña" required />

            <div class="whatsapp-option">
                <input type="checkbox" id="whatsappNotification" name="whatsapp_notification">
                <label for="whatsappNotification">Recibir notificaciones por WhatsApp</label>
            </div>

            <button type="submit">Ingresar</button>
        </form>

        <div class="login-options">
            <a href="<?php echo e(route('password.request')); ?>">¿Olvidaste tu contraseña?</a>
            <a href="<?php echo e(route('register')); ?>">¿Todavía no tienes una cuenta? Regístrate</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ardi-mi\resources\views/auth/login.blade.php ENDPATH**/ ?>