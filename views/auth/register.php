<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema Autopartes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-2xl">
        <!-- Logo y título -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-full mb-4">
                <i class="fas fa-car-side text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Crear Cuenta</h1>
            <p class="text-gray-600 mt-2">Completa el formulario para registrarte</p>
        </div>

        <!-- Formulario de Registro -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            
            <?php
            // Mostrar mensaje flash
            $flash = getFlashMessage();
            if ($flash):
                $alertColors = [
                    'success' => 'bg-green-100 border-green-400 text-green-700',
                    'error' => 'bg-red-100 border-red-400 text-red-700',
                    'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
                    'info' => 'bg-blue-100 border-blue-400 text-blue-700'
                ];
                $colorClass = $alertColors[$flash['type']] ?? $alertColors['info'];
            ?>
                <div class="<?= $colorClass ?> border px-4 py-3 rounded-lg mb-6" role="alert">
                    <span class="block sm:inline"><?= e($flash['message']) ?></span>
                </div>
            <?php endif; ?>

            <?php
            // Mostrar errores de validación
            $errors = $_SESSION['errors'] ?? [];
            $old = $_SESSION['old'] ?? [];
            ?>

            <form action="<?= BASE_URL ?>/index.php?module=auth&action=do_register" method="POST" class="space-y-6" id="registerForm">
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Nombre Completo -->
                    <div class="md:col-span-2">
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-gray-400 mr-2"></i>
                            Nombre Completo
                        </label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            required
                            class="w-full px-4 py-3 border <?= isset($errors['nombre']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                            placeholder="Juan Pérez"
                            value="<?= isset($old['nombre']) ? e($old['nombre']) : '' ?>"
                        >
                        <?php if (isset($errors['nombre'])): ?>
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <?= e($errors['nombre']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                            Correo Electrónico
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            class="w-full px-4 py-3 border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                            placeholder="tu@email.com"
                            value="<?= isset($old['email']) ? e($old['email']) : '' ?>"
                        >
                        <?php if (isset($errors['email'])): ?>
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <?= e($errors['email']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            Contraseña
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                minlength="6"
                                class="w-full px-4 py-3 border <?= isset($errors['password']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                placeholder="••••••••"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('password', 'eye-icon-1')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <i class="fas fa-eye" id="eye-icon-1"></i>
                            </button>
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <?= e($errors['password']) ?>
                            </p>
                        <?php endif; ?>
                        <p class="mt-1 text-xs text-gray-500">Mínimo 6 caracteres</p>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            Confirmar Contraseña
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password_confirm" 
                                name="password_confirm" 
                                required
                                minlength="6"
                                class="w-full px-4 py-3 border <?= isset($errors['password_confirm']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                placeholder="••••••••"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('password_confirm', 'eye-icon-2')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <i class="fas fa-eye" id="eye-icon-2"></i>
                            </button>
                        </div>
                        <?php if (isset($errors['password_confirm'])): ?>
                            <p class="mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <?= e($errors['password_confirm']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Indicador de fortaleza de contraseña -->
                <div id="password-strength" class="hidden">
                    <div class="flex items-center space-x-2">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div id="strength-bar" class="h-full transition-all duration-300"></div>
                        </div>
                        <span id="strength-text" class="text-sm font-medium"></span>
                    </div>
                </div>

                <!-- Términos y condiciones -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            type="checkbox" 
                            id="terminos" 
                            name="terminos"
                            required
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        >
                    </div>
                    <label for="terminos" class="ml-3 text-sm text-gray-600">
                        Acepto los 
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 font-semibold">términos y condiciones</a>
                        y la 
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 font-semibold">política de privacidad</a>
                    </label>
                </div>

                <!-- Botón de submit -->
                <button 
                    type="submit" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                >
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear Cuenta
                </button>
            </form>

            <!-- Divisor -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">O</span>
                </div>
            </div>

            <!-- Link a login -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    ¿Ya tienes cuenta? 
                    <a href="<?= BASE_URL ?>/index.php?module=auth&action=login" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>

            <!-- Acceso público -->
            <div class="mt-4 text-center">
                <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo" class="text-sm text-gray-500 hover:text-gray-700">
                    <i class="fas fa-home mr-1"></i>
                    Ver catálogo público
                </a>
            </div>
        </div>

        <!-- Beneficios de registrarse -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white bg-opacity-80 rounded-lg p-4 text-center">
                <div class="text-indigo-600 text-2xl mb-2">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm">Compras Rápidas</h3>
                <p class="text-xs text-gray-600 mt-1">Carrito de compras y checkout simplificado</p>
            </div>
            
            <div class="bg-white bg-opacity-80 rounded-lg p-4 text-center">
                <div class="text-indigo-600 text-2xl mb-2">
                    <i class="fas fa-history"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm">Historial</h3>
                <p class="text-xs text-gray-600 mt-1">Accede a tu historial de compras</p>
            </div>
            
            <div class="bg-white bg-opacity-80 rounded-lg p-4 text-center">
                <div class="text-indigo-600 text-2xl mb-2">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-sm">Notificaciones</h3>
                <p class="text-xs text-gray-600 mt-1">Recibe alertas de nuevos productos</p>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthIndicator = document.getElementById('password-strength');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            
            if (password.length === 0) {
                strengthIndicator.classList.add('hidden');
                return;
            }
            
            strengthIndicator.classList.remove('hidden');
            
            let strength = 0;
            
            // Calcular fortaleza
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Actualizar barra y texto
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-lime-500', 'bg-green-500'];
            const texts = ['Muy débil', 'Débil', 'Aceptable', 'Fuerte', 'Muy fuerte'];
            const widths = ['20%', '40%', '60%', '80%', '100%'];
            
            strengthBar.className = 'h-full transition-all duration-300 ' + colors[strength];
            strengthBar.style.width = widths[strength];
            strengthText.textContent = texts[strength];
            strengthText.className = 'text-sm font-medium ' + colors[strength].replace('bg-', 'text-');
        });

        // Validación de confirmación de contraseña en tiempo real
        const passwordConfirm = document.getElementById('password_confirm');
        
        passwordConfirm.addEventListener('input', function() {
            if (this.value && this.value !== passwordInput.value) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });

        // Validación antes de enviar
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            
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
        });

        // Limpiar datos antiguos de la sesión
        <?php 
        if (isset($_SESSION['old'])) {
            unset($_SESSION['old']);
        }
        if (isset($_SESSION['errors'])) {
            unset($_SESSION['errors']);
        }
        ?>
    </script>
</body>
</html>