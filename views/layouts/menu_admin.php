<!-- Menú Horizontal Administrador -->
<nav class="bg-gradient-to-r from-indigo-700 to-indigo-600 border-b border-indigo-800 shadow-lg">
    <div class="container mx-auto px-4">
        <ul class="flex items-center space-x-1 overflow-x-auto scrollbar-hide py-1">
            
            <!-- Dashboard -->
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=dashboard" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-indigo-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'dashboard') ? 'bg-indigo-800' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>

            <!-- Usuarios -->
            <?php if (hasPermission('usuarios', 'leer')): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=usuarios" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-indigo-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'usuarios') ? 'bg-indigo-800' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span class="font-medium">Usuarios</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Roles y Permisos -->
            <?php if (hasPermission('roles', 'leer')): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=roles" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-indigo-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'roles') ? 'bg-indigo-800' : '' ?>">
                    <i class="fas fa-user-shield"></i>
                    <span class="font-medium">Roles</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Categorías -->
            <?php if (hasPermission('categorias', 'leer')): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=categorias" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-indigo-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'categorias') ? 'bg-indigo-800' : '' ?>">
                    <i class="fas fa-tags"></i>
                    <span class="font-medium">Categorías</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Inventario -->
            <?php if (hasPermission('inventario', 'leer')): ?>
            <li class="relative dropdown group">
                <button class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-indigo-800 rounded-lg transition-colors whitespace-nowrap">
                    <i class="fas fa-warehouse"></i>
                    <span class="font-medium">Inventario</span>
                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                </button>
                
                <!-- Submenu -->
                <div class="dropdown-menu hidden group-hover:block absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                    <a href="<?= BASE_URL ?>/index.php?module=admin&action=inventario" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-list mr-2 w-4"></i>
                        Ver Inventario
                    </a>
                    <?php if (hasPermission('inventario', 'crear')): ?>
                    <a href="<?= BASE_URL ?>/index.php?module=admin&action=inventario-agregar" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-plus mr-2 w-4"></i>
                        Agregar Autoparte
                    </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/index.php?module=admin&action=inventario-bajo" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-exclamation-triangle mr-2 w-4 text-yellow-500"></i>
                        Stock Bajo
                    </a>
                </div>
            </li>
            <?php endif; ?>

            <!-- Ventas -->
            <?php if (hasPermission('ventas', 'leer')): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=ventas" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-indigo-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'ventas') ? 'bg-indigo-800' : '' ?>">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="font-medium">Ventas</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Comentarios -->
            <?php if (hasPermission('comentarios', 'leer')): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=comentarios" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-indigo-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'comentarios') ? 'bg-indigo-800' : '' ?>">
                    <i class="fas fa-comments"></i>
                    <span class="font-medium">Comentarios</span>
                    <?php
                    // Contar comentarios pendientes (puedes implementar esto)
                    $pendientes = 0; // Implementar lógica
                    if ($pendientes > 0):
                    ?>
                        <span class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5 ml-1">
                            <?= $pendientes ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endif; ?>

            <!-- Reportes -->
            <?php if (hasPermission('reportes', 'leer')): ?>
            <li class="relative dropdown group">
                <button class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-indigo-800 rounded-lg transition-colors whitespace-nowrap">
                    <i class="fas fa-file-excel"></i>
                    <span class="font-medium">Reportes</span>
                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                </button>
                
                <!-- Submenu -->
                <div class="dropdown-menu hidden group-hover:block absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                    <a href="<?= BASE_URL ?>/index.php?module=admin&action=reporte-inventario" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-file-excel mr-2 w-4 text-green-600"></i>
                        Inventario (Excel)
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?module=admin&action=reporte-ventas" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-file-excel mr-2 w-4 text-green-600"></i>
                        Ventas (Excel)
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?module=admin&action=reporte-vendido" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-file-excel mr-2 w-4 text-green-600"></i>
                        Piezas Vendidas
                    </a>
                </div>
            </li>
            <?php endif; ?>

            <!-- Estadísticas -->
            <?php if (hasPermission('estadisticas', 'leer')): ?>
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=estadisticas" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-indigo-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'estadisticas') ? 'bg-indigo-800' : '' ?>">
                    <i class="fas fa-chart-line"></i>
                    <span class="font-medium">Estadísticas</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Configuración -->
            <li class="ml-auto">
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=configuracion" 
                   class="flex items-center space-x-2 px-4 py-3 text-white hover:bg-indigo-800 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'configuracion') ? 'bg-indigo-800' : '' ?>">
                    <i class="fas fa-cog"></i>
                    <span class="font-medium">Configuración</span>
                </a>
            </li>

        </ul>
    </div>
</nav>

<style>
    /* Ocultar scrollbar pero mantener funcionalidad */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Mejora para dropdown en hover */
    .dropdown:hover .dropdown-menu {
        display: block;
        animation: slideDown 0.2s ease-out;
    }
</style>