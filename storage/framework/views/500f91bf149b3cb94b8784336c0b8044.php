

<?php $__env->startSection('content'); ?>
<div class="card mx-auto" style="max-width: 400px;">
    <div class="card-body">
        <h3 class="card-title mb-4">Registro</h3>

        <form method="POST" action="<?php echo e(url('/register')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Nombre completo" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
            </div>
            <div class="mb-3">
                <input type="text" name="phone" class="form-control" placeholder="Teléfono">
            </div>
            <div class="mb-3">
                <select name="role" class="form-control" required>
                    <option value="user">Usuario</option>
                    <option value="company">Empresa Recolectora</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar Contraseña" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="whatsapp" class="form-check-input" id="whatsappCheck">
                <label class="form-check-label" for="whatsappCheck">Recibir notificaciones por WhatsApp</label>
            </div>
            <button type="submit" class="btn btn-success w-100">Registrarse</button>
        </form>

        <div class="text-center mt-3">
            ¿Ya tienes cuenta? <a href="<?php echo e(route('login')); ?>">Iniciar sesión</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ardi-mi\resources\views/auth/register.blade.php ENDPATH**/ ?>