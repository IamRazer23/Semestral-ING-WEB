<!-- Menú Horizontal Público -->
<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="container mx-auto px-4">
        <ul class="flex items-center justify-center space-x-1 overflow-x-auto scrollbar-hide py-1">
            
            <!-- Inicio -->
            <li>
                <a href="<?= BASE_URL ?>/index.php" 
                   class="flex items-center space-x-2 px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors whitespace-nowrap <?= (!isset($_GET['module']) || $_GET['module'] == 'public' && (!isset($_GET['action']) || $_GET['action'] == 'home')) ? 'text-indigo-600 bg-indigo-50' : '' ?>">
                    <i class="fas fa-home"></i>
                    <span class="font-medium">Inicio</span>
                </a>
            </li>

            <!-- Catálogo -->
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo" 
                   class="flex items-center space-x-2 px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'catalogo') ? 'text-indigo-600 bg-indigo-50' : '' ?>">
                    <i class="fas fa-th-large"></i>
                    <span class="font-medium">Catálogo</span>
                </a>
            </li>

            <!-- Categorías (Dropdown) -->
            <li class="relative dropdown group">
                <button class="flex items-center space-x-2 px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors whitespace-nowrap">
                    <i class="fas fa-list"></i>
                    <span class="font-medium">Categorías</span>
                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                </button>
                
                <!-- Submenu de categorías -->
                <div class="dropdown-menu hidden group-hover:block absolute left-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&categoria=motor" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-cog mr-3 w-5 text-indigo-500"></i>
                        <div>
                            <div class="font-medium">Motor</div>
                            <div class="text-xs text-gray-500">Piezas del motor</div>
                        </div>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&categoria=carroceria" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-car mr-3 w-5 text-blue-500"></i>
                        <div>
                            <div class="font-medium">Carrocería</div>
                            <div class="text-xs text-gray-500">Puertas, cofres, parachoques</div>
                        </div>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&categoria=vidrios" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-window-maximize mr-3 w-5 text-cyan-500"></i>
                        <div>
                            <div class="font-medium">Vidrios</div>
                            <div class="text-xs text-gray-500">Parabrisas y ventanas</div>
                        </div>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&categoria=electrico" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-bolt mr-3 w-5 text-yellow-500"></i>
                        <div>
                            <div class="font-medium">Eléctrico</div>
                            <div class="text-xs text-gray-500">Componentes eléctricos</div>
                        </div>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&categoria=interior" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-couch mr-3 w-5 text-purple-500"></i>
                        <div>
                            <div class="font-medium">Interior</div>
                            <div class="text-xs text-gray-500">Asientos y accesorios</div>
                        </div>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&categoria=suspension" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-compress-arrows-alt mr-3 w-5 text-orange-500"></i>
                        <div>
                            <div class="font-medium">Suspensión</div>
                            <div class="text-xs text-gray-500">Amortiguadores y muelles</div>
                        </div>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&categoria=frenos" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-circle mr-3 w-5 text-red-500"></i>
                        <div>
                            <div class="font-medium">Frenos</div>
                            <div class="text-xs text-gray-500">Sistema de frenado</div>
                        </div>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&categoria=transmision" 
                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-cogs mr-3 w-5 text-green-500"></i>
                        <div>
                            <div class="font-medium">Transmisión</div>
                            <div class="text-xs text-gray-500">Cajas de cambio</div>
                        </div>
                    </a>
                    
                    <div class="border-t border-gray-200 mt-2 pt-2">
                        <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo" 
                           class="block px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 font-medium">
                            <i class="fas fa-th mr-2"></i>
                            Ver todas las categorías
                        </a>
                    </div>
                </div>
            </li>

            <!-- Marcas populares -->
            <li class="relative dropdown group">
                <button class="flex items-center space-x-2 px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors whitespace-nowrap">
                    <i class="fas fa-car-side"></i>
                    <span class="font-medium">Marcas</span>
                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                </button>
                
                <!-- Submenu de marcas -->
                <div class="dropdown-menu hidden group-hover:block absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&marca=toyota" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-circle text-xs mr-2 text-gray-400"></i>
                        Toyota
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&marca=honda" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-circle text-xs mr-2 text-gray-400"></i>
                        Honda
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&marca=nissan" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-circle text-xs mr-2 text-gray-400"></i>
                        Nissan
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&marca=ford" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-circle text-xs mr-2 text-gray-400"></i>
                        Ford
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo&marca=chevrolet" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-circle text-xs mr-2 text-gray-400"></i>
                        Chevrolet
                    </a>
                    <div class="border-t border-gray-200 mt-2 pt-2">
                        <a href="<?= BASE_URL ?>/index.php?module=public&action=marcas" 
                           class="block px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 font-medium">
                            <i class="fas fa-list mr-2"></i>
                            Ver todas las marcas
                        </a>
                    </div>
                </div>
            </li>

            <!-- Ofertas -->
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=public&action=ofertas" 
                   class="flex items-center space-x-2 px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'ofertas') ? 'text-indigo-600 bg-indigo-50' : '' ?>">
                    <i class="fas fa-tags"></i>
                    <span class="font-medium">Ofertas</span>
                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">
                        Nuevo
                    </span>
                </a>
            </li>

            <!-- Sobre Nosotros -->
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=public&action=sobre-nosotros" 
                   class="flex items-center space-x-2 px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'sobre-nosotros') ? 'text-indigo-600 bg-indigo-50' : '' ?>">
                    <i class="fas fa-info-circle"></i>
                    <span class="font-medium">Nosotros</span>
                </a>
            </li>

            <!-- Contacto -->
            <li>
                <a href="<?= BASE_URL ?>/index.php?module=public&action=contacto" 
                   class="flex items-center space-x-2 px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors whitespace-nowrap <?= (isset($_GET['action']) && $_GET['action'] == 'contacto') ? 'text-indigo-600 bg-indigo-50' : '' ?>">
                    <i class="fas fa-envelope"></i>
                    <span class="font-medium">Contacto</span>
                </a>
            </li>

        </ul>
    </div>
</nav>

<!-- Breadcrumb (opcional para páginas públicas) -->
<?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
<div class="bg-gray-50 border-b border-gray-200">
    <div class="container mx-auto px-4 py-3">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="<?= BASE_URL ?>/index.php" class="text-gray-500 hover:text-indigo-600">
                <i class="fas fa-home"></i>
            </a>
            <?php foreach ($breadcrumbs as $index => $crumb): ?>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <?php if ($index === count($breadcrumbs) - 1): ?>
                    <span class="text-gray-700 font-medium"><?= e($crumb['text']) ?></span>
                <?php else: ?>
                    <a href="<?= e($crumb['url']) ?>" class="text-gray-500 hover:text-indigo-600">
                        <?= e($crumb['text']) ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
    </div>
</div>
<?php endif; ?>

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