<!-- Menú Horizontal Operador -->
<nav class="bg-gradient-to-r from-blue-700 to-blue-600 border-b border-blue-800 shadow-lg">
    <div class="container mx-auto px-4">
        <ul class="flex items-center space-x-1 overflow-x-auto scrollbar-hide py-1">
            
            <!-- Dashboard -->
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=operador&action=dashboard" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-blue-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'dashboard') ? 'bg-blue-800' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <!-- Inventario -->
            <?php if (hasPermission('inventario', 'leer')): ?>
            <li class="relative dropdown group">
                <button class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-blue-800 rounded-lg transition-colors whitespace-nowrap">
                    <i class="fas fa-warehouse"></i>
                    <span class="font-medium">Inventario</span>
                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                </button>
                
                <!-- Submenu -->
                <div class="dropdown-menu hidden group-hover:block absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                    <a href="<?= BASE_URL ?>/index.php?module=operador&action=inventario" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                        <i class="fas fa-list mr-2 w-4"></i>
                        Ver Inventario
                    </a>
                    <?php if (hasPermission('inventario', 'crear')): ?>
                    <a href="<?= BASE_URL ?>/index.php?module=operador&action=inventario-agregar" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                        <i class="fas fa-plus mr-2 w-4"></i>
                        Agregar Autoparte
                    </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/index.php?module=operador&action=inventario-buscar" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                        <i class="fas fa-search mr-2 w-4"></i>
                        Buscar Piezas
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?module=operador&action=inventario-bajo" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                        <i class="fas fa-exclamation-triangle mr-2 w-4 text-yellow-500"></i>
                        Stock Bajo
                    </a>
                </div>
            </li>
            <?php endif; ?>

            <!-- Categorías (solo lectura) -->
            <?php if (hasPermission('categorias', 'leer')): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=operador&action=categorias" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-blue-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'categorias') ? 'bg-blue-800' : '' ?>">
                    <i class="fas fa-tags"></i>
                    <span class="font-medium">Categorías</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Ventas -->
            <?php if (hasPermission('ventas', 'leer')): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=operador&action=ventas" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-blue-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'ventas') ? 'bg-blue-800' : '' ?>">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="font-medium">Ventas</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Comentarios -->
            <?php if (hasPermission('comentarios', 'leer')): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=operador&action=comentarios" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-blue-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'comentarios') ? 'bg-blue-800' : '' ?>">
                    <i class="fas fa-comments"></i>
                    <span class="font-medium">Comentarios</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Ver catálogo público -->
            <li class="ml-auto">
                <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-blue-800 rounded-lg transition-colors whitespace-nowrap"
                   target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span class="font-medium">Ver Catálogo</span>
                </a>
            </li>

        </ul>
    </div>
</nav>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    .dropdown:hover .dropdown-menu {
        display: block;
        animation: slideDown 0.2s ease-out;
    }
</style>