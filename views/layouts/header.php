<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Inventario de Autopartes - Encuentra las piezas que necesitas">
    <meta name="author" content="Grupo 1SF131">
    
    <title><?= $pageTitle ?? 'Sistema de Autopartes' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Personalizado (si existe) -->
    <?php if (file_exists(ROOT_PATH . '/public/css/custom.css')): ?>
        <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/custom.css">
    <?php endif; ?>
    
    <!-- Estilos adicionales específicos de la página -->
    <?php if (isset($customStyles)): ?>
        <style><?= $customStyles ?></style>
    <?php endif; ?>
    
    <style>
        /* Animaciones personalizadas */
        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .animate-slide-down {
            animation: slideDown 0.3s ease-out;
        }
        
        /* Dropdown hover effect */
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        
        /* Smooth transitions */
        * {
            transition: all 0.2s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    
    <!-- Top Bar (opcional - información de contacto) -->
    <div class="bg-gray-800 text-white text-xs py-2 hidden md:block">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span><i class="fas fa-phone mr-1"></i> +507 6123-4567</span>
                <span><i class="fas fa-envelope mr-1"></i> info@autopartes.com</span>
            </div>
            <div class="flex items-center space-x-4">
                <span><i class="fas fa-clock mr-1"></i> Lun-Vie: 8:00 AM - 6:00 PM</span>
            </div>
        </div>
    </div>

    <!-- Header Principal -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                
                <!-- Logo y nombre -->
                <div class="flex items-center space-x-3">
                    <a href="<?= BASE_URL ?>/index.php" class="flex items-center space-x-3 group">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-car-side text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">
                                AutoPartes Pro
                            </h1>
                            <p class="text-xs text-gray-500">Sistema de Inventario</p>
                        </div>
                    </a>
                </div>

                <!-- Barra de búsqueda (solo en páginas públicas) -->
                <?php if (!isset($hideSearch) || !$hideSearch): ?>
                <div class="hidden lg:flex flex-1 max-w-xl mx-8">
                    <form action="<?= BASE_URL ?>/index.php" method="GET" class="w-full">
                        <input type="hidden" name="module" value="public">
                        <input type="hidden" name="action" value="buscar">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="q"
                                placeholder="Buscar autopartes (marca, modelo, año)..." 
                                class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                value="<?= isset($_GET['q']) ? e($_GET['q']) : '' ?>"
                            >
                            <button 
                                type="submit"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-md"
                            >
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <!-- Menú de usuario / navegación -->
                <nav class="flex items-center space-x-4">
                    
                    <?php if (isAuthenticated()): ?>
                        <!-- Usuario autenticado -->
                        
                        <?php if (hasRole(ROL_CLIENTE)): ?>
                            <!-- Carrito (solo para clientes) -->
                            <a href="<?= BASE_URL ?>/index.php?module=cliente&action=carrito" 
                               class="relative group">
                                <div class="relative">
                                    <i class="fas fa-shopping-cart text-gray-600 group-hover:text-indigo-600 text-xl"></i>
                                    <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                        0
                                    </span>
                                </div>
                                <span class="hidden md:inline-block ml-2 text-sm text-gray-600 group-hover:text-indigo-600">
                                    Carrito
                                </span>
                            </a>
                        <?php endif; ?>

                        <!-- Dropdown de usuario -->
                        <div class="relative dropdown">
                            <button class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100">
                                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">
                                        <?= strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)) ?>
                                    </span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-semibold text-gray-800">
                                        <?= e($_SESSION['usuario_nombre']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?= e($_SESSION['rol_nombre']) ?>
                                    </p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </button>
                            
                            <!-- Menú desplegable -->
                            <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 animate-slide-down">
                                
                                <?php
                                // Determinar el módulo según el rol
                                $userModule = 'cliente';
                                if (hasRole(ROL_ADMINISTRADOR)) $userModule = 'admin';
                                elseif (hasRole(ROL_OPERADOR)) $userModule = 'operador';
                                ?>
                                
                                <a href="<?= BASE_URL ?>/index.php?module=<?= $userModule ?>&action=dashboard" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    <i class="fas fa-tachometer-alt mr-2 w-4"></i>
                                    Dashboard
                                </a>
                                
                                <?php if (hasRole(ROL_CLIENTE)): ?>
                                <a href="<?= BASE_URL ?>/index.php?module=cliente&action=mis-compras" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    <i class="fas fa-shopping-bag mr-2 w-4"></i>
                                    Mis Compras
                                </a>
                                <?php endif; ?>
                                
                                <a href="<?= BASE_URL ?>/index.php?module=<?= $userModule ?>&action=perfil" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    <i class="fas fa-user mr-2 w-4"></i>
                                    Mi Perfil
                                </a>
                                
                                <hr class="my-2 border-gray-200">
                                
                                <a href="<?= BASE_URL ?>/index.php?module=auth&action=logout" 
                                   class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2 w-4"></i>
                                    Cerrar Sesión
                                </a>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Usuario no autenticado -->
                        <a href="<?= BASE_URL ?>/index.php?module=auth&action=login" 
                           class="hidden md:flex items-center space-x-2 text-gray-600 hover:text-indigo-600">
                            <i class="fas fa-sign-in-alt"></i>
                            <span class="text-sm font-medium">Iniciar Sesión</span>
                        </a>
                        
                        <a href="<?= BASE_URL ?>/index.php?module=auth&action=register" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-user-plus mr-2"></i>
                            Registrarse
                        </a>
                    <?php endif; ?>

                    <!-- Botón menú móvil -->
                    <button 
                        id="mobile-menu-button"
                        class="lg:hidden text-gray-600 hover:text-indigo-600"
                        onclick="toggleMobileMenu()"
                    >
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Menú móvil -->
        <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-200 animate-slide-down">
            <div class="container mx-auto px-4 py-4 space-y-3">
                
                <!-- Búsqueda móvil -->
                <form action="<?= BASE_URL ?>/index.php" method="GET">
                    <input type="hidden" name="module" value="public">
                    <input type="hidden" name="action" value="buscar">
                    <input 
                        type="text" 
                        name="q"
                        placeholder="Buscar..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                    >
                </form>

                <?php if (!isAuthenticated()): ?>
                    <a href="<?= BASE_URL ?>/index.php?module=auth&action=login" 
                       class="block text-center py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Iniciar Sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Menú de navegación horizontal (según el rol) -->
    <?php
    // Incluir el menú apropiado según el rol
    if (isAuthenticated()) {
        if (hasRole(ROL_ADMINISTRADOR)) {
            include_once VIEWS_PATH . '/layouts/menu_admin.php';
        } elseif (hasRole(ROL_OPERADOR)) {
            include_once VIEWS_PATH . '/layouts/menu_operador.php';
        } elseif (hasRole(ROL_CLIENTE)) {
            include_once VIEWS_PATH . '/layouts/menu_cliente.php';
        }
    } else {
        include_once VIEWS_PATH . '/layouts/menu_public.php';
    }
    ?>

    <!-- Contenedor principal -->
    <main class="flex-1">
        
        <?php
        // Mostrar mensaje flash global
        $flash = getFlashMessage();
        if ($flash):
            $alertConfig = [
                'success' => ['bg' => 'bg-green-100', 'border' => 'border-green-400', 'text' => 'text-green-700', 'icon' => 'check-circle'],
                'error' => ['bg' => 'bg-red-100', 'border' => 'border-red-400', 'text' => 'text-red-700', 'icon' => 'exclamation-circle'],
                'warning' => ['bg' => 'bg-yellow-100', 'border' => 'border-yellow-400', 'text' => 'text-yellow-700', 'icon' => 'exclamation-triangle'],
                'info' => ['bg' => 'bg-blue-100', 'border' => 'border-blue-400', 'text' => 'text-blue-700', 'icon' => 'info-circle']
            ];
            $config = $alertConfig[$flash['type']] ?? $alertConfig['info'];
        ?>
            <div class="container mx-auto px-4 mt-4">
                <div class="<?= $config['bg'] ?> border <?= $config['border'] ?> <?= $config['text'] ?> px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-<?= $config['icon'] ?> mr-3"></i>
                        <span><?= e($flash['message']) ?></span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-xl leading-none hover:opacity-75">
                        ×
                    </button>
                </div>
            </div>
        <?php endif; ?>

    <script>
        // Toggle mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Cerrar menú móvil al hacer clic fuera
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('mobile-menu-button');
            
            if (!menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Cargar contador del carrito (si es cliente)
        <?php if (isAuthenticated() && hasRole(ROL_CLIENTE)): ?>
        function updateCartCount() {
            fetch('<?= BASE_URL ?>/index.php?module=cliente&action=cart_count', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.count;
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        // Actualizar contador al cargar la página
        document.addEventListener('DOMContentLoaded', updateCartCount);
        <?php endif; ?>
    </script>