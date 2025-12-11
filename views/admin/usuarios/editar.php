<?php
/**
 * Vista: Formulario de Usuario (Crear/Editar)
 * Este archivo sirve tanto para crear.php como para editar.php
 * 
 * NOTA: Guarda este contenido en DOS archivos:
 * 1. views/admin/usuarios/crear.php
 * 2. views/admin/usuarios/editar.php
 */

// Determinar si es edición o creación
$esEdicion = isset($usuario) && !empty($usuario);
$titulo = $esEdicion ? 'Editar Usuario' : 'Crear Usuario';
$urlAction = $esEdicion 
    ? BASE_URL . '/index.php?module=admin&action=usuario-update'
    : BASE_URL . '/index.php?module=admin&action=usuario-store';

// Obtener errores y valores antiguos
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="container mx-auto px-4 py-8">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-<?= $esEdicion ? 'edit' : 'plus' ?> text-indigo-600 mr-2"></i>
            <?= $titulo ?>
        </h1>
        <p class="text-gray-600 mt-1">
            <?= $esEdicion ? 'Modifica la información del usuario' : 'Completa el formulario para crear un nuevo usuario' ?>
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Formulario Principal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                
                <form action="<?= $urlAction ?>" method="POST" id="formUsuario">
                    <?php if ($esEdicion): ?>
                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                    <?php endif; ?>
                    
                    <!-- Nombre -->
                    <div class="mb-6">
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-gray-400 mr-1"></i>
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            value="<?= $esEdicion ? e($usuario['nombre']) : e($old['nombre'] ?? '') ?>"
                            required
                            maxlength="100"
                            class="w-full px-4 py-3 border <?= isset($errors['nombre']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Juan Pérez"
                        >
                        <?php if (isset($errors['nombre'])): ?>
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <?= e($errors['nombre']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope text-gray-400 mr-1"></i>
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?= $esEdicion ? e($usuario['email']) : e($old['email'] ?? '') ?>"
                            required
                            maxlength="100"
                            class="w-full px-4 py-3 border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="usuario@ejemplo.com"
                        >
                        <?php if (isset($errors['email'])): ?>
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <?= e($errors['email']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            Contraseña 
                            <?php if (!$esEdicion): ?>
                                <span class="text-red-500">*</span>
                            <?php else: ?>
                                <span class="text-gray-500 text-xs">(dejar en blanco para no cambiar)</span>
                            <?php endif; ?>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                <?= !$esEdicion ? 'required' : '' ?>
                                minlength="6"
                                class="w-full px-4 py-3 border <?= isset($errors['password']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Mínimo 6 caracteres"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('password')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <?= e($errors['password']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-6">
                        <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            Confirmar Contraseña 
                            <?php if (!$esEdicion): ?>
                                <span class="text-red-500">*</span>
                            <?php endif; ?>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password_confirm" 
                                name="password_confirm" 
                                <?= !$esEdicion ? 'required' : '' ?>
                                minlength="6"
                                class="w-full px-4 py-3 border <?= isset($errors['password_confirm']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Confirma la contraseña"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('password_confirm')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <i class="fas fa-eye" id="password_confirm-icon"></i>
                            </button>
                        </div>
                        <?php if (isset($errors['password_confirm'])): ?>
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <?= e($errors['password_confirm']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Rol -->
                    <div class="mb-6">
                        <label for="rol_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tag text-gray-400 mr-1"></i>
                            Rol <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="rol_id" 
                            name="rol_id" 
                            required
                            class="w-full px-4 py-3 border <?= isset($errors['rol']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">Seleccionar rol...</option>
                            <?php foreach ($roles as $rol): ?>
                                <option 
                                    value="<?= $rol['id'] ?>"
                                    <?= ($esEdicion && $usuario['rol_id'] == $rol['id']) || (!$esEdicion && ($old['rol_id'] ?? '') == $rol['id']) ? 'selected' : '' ?>
                                >
                                    <?= e($rol['nombre']) ?>
                                    <?php if ($rol['descripcion']): ?>
                                        - <?= e($rol['descripcion']) ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['rol'])): ?>
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <?= e($errors['rol']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Estado -->
                    <div class="mb-6">
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="estado" 
                                <?= ($esEdicion && $usuario['estado'] == 1) || (!$esEdicion) ? 'checked' : '' ?>
                                class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <span class="ml-3 text-sm font-medium text-gray-700">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                Usuario Activo
                            </span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">
                            Los usuarios inactivos no podrán iniciar sesión en el sistema
                        </p>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4 pt-4 border-t">
                        <button 
                            type="submit"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition"
                        >
                            <i class="fas fa-save mr-2"></i>
                            <?= $esEdicion ? 'Guardar Cambios' : 'Crear Usuario' ?>
                        </button>
                        <a 
                            href="<?= BASE_URL ?>/index.php?module=admin&action=usuarios"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold transition text-center"
                        >
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                        <button 
                            type="reset"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-3 rounded-lg font-semibold transition"
                        >
                            <i class="fas fa-redo mr-2"></i>
                            Limpiar
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- Panel Lateral: Ayuda -->
        <div class="lg:col-span-1">
            
            <!-- Información del Usuario (solo en edición) -->
            <?php if ($esEdicion): ?>
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-md p-6 text-white mb-6">
                    <h3 class="text-lg font-semibold mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Información Actual
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-indigo-100">Creado el:</p>
                            <p class="font-semibold"><?= formatDate($usuario['fecha_creacion']) ?></p>
                        </div>
                        <?php if ($usuario['ultima_sesion']): ?>
                            <div>
                                <p class="text-indigo-100">Última sesión:</p>
                                <p class="font-semibold"><?= formatDateTime($usuario['ultima_sesion']) ?></p>
                            </div>
                        <?php endif; ?>
                        <div>
                            <p class="text-indigo-100">Estado actual:</p>
                            <p class="font-semibold">
                                <?= $usuario['estado'] == 1 ? '✓ Activo' : '✗ Inactivo' ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Ayuda -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">
                    <i class="fas fa-question-circle mr-2"></i>
                    Ayuda
                </h3>
                <ul class="space-y-3 text-sm text-blue-800">
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                        <span>El nombre debe tener mínimo 3 caracteres</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                        <span>El email debe ser único en el sistema</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                        <span>La contraseña debe tener mínimo 6 caracteres</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-blue-600 mr-2 mt-1"></i>
                        <span>Los usuarios inactivos no pueden iniciar sesión</span>
                    </li>
                    <?php if ($esEdicion): ?>
                        <li class="flex items-start">
                            <i class="fas fa-info text-blue-600 mr-2 mt-1"></i>
                            <span>Deja la contraseña en blanco si no quieres cambiarla</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Roles -->
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-purple-900 mb-4">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Roles del Sistema
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="bg-white rounded-lg p-3">
                        <p class="font-semibold text-purple-900">Administrador</p>
                        <p class="text-purple-700">Control total del sistema</p>
                    </div>
                    <div class="bg-white rounded-lg p-3">
                        <p class="font-semibold text-blue-900">Operador</p>
                        <p class="text-blue-700">Gestión de inventario</p>
                    </div>
                    <div class="bg-white rounded-lg p-3">
                        <p class="font-semibold text-green-900">Cliente</p>
                        <p class="text-green-700">Realiza compras</p>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
// Toggle visibilidad de contraseña
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Validación del formulario
document.getElementById('formUsuario').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;
    
    // Solo validar si se ingresó contraseña (en caso de edición)
    if (password || passwordConfirm) {
        if (password !== passwordConfirm) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            return false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 6 caracteres');
            return false;
        }
    }
    
    // En modo creación, la contraseña es obligatoria
    <?php if (!$esEdicion): ?>
    if (!password) {
        e.preventDefault();
        alert('La contraseña es obligatoria');
        return false;
    }
    <?php endif; ?>
});
</script>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>