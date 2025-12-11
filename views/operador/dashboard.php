<?php
/**
 * Vista: Dashboard del Operador
 * Panel principal para operadores de inventario
 */

$pageTitle = 'Panel de Operador';

require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="container mx-auto px-4 py-8">
    
    <!-- Header del Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-warehouse text-blue-600 mr-2"></i>
            Panel de Operador
        </h1>
        <p class="text-gray-600">
            Bienvenido, <span class="font-semibold"><?= e($_SESSION['usuario_nombre']) ?></span>
            <span class="mx-2">•</span>
            <span class="text-sm"><?= date('l, d \d\e F \d\e Y') ?></span>
        </p>
    </div>

    <!-- Cards de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Total Inventario -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Inventario Total</p>
                    <h3 class="text-4xl font-bold"><?= $totalAutopartes ?></h3>
                    <p class="text-blue-100 text-xs mt-2">
                        <i class="fas fa-box mr-1"></i>
                        Autopartes activas
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-warehouse text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Alertas de Stock -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-1">Alertas Stock</p>
                    <h3 class="text-4xl font-bold"><?= $alertasStock ?></h3>
                    <p class="text-red-100 text-xs mt-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Requieren atención
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-exclamation-circle text-3xl"></i>
                </div>
            </div>
            <?php if ($alertasStock > 0): ?>
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=inventario-bajo" 
                   class="block mt-4 text-center bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg text-sm font-semibold transition">
                    Ver Alertas →
                </a>
            <?php endif; ?>
        </div>

        <!-- Ventas del Día -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Ventas Hoy</p>
                    <h3 class="text-4xl font-bold"><?= $ventasHoy ?></h3>
                    <p class="text-green-100 text-xs mt-2">
                        <i class="fas fa-shopping-cart mr-1"></i>
                        Transacciones
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Grid de 2 Columnas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <!-- Stock Bajo -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-orange-500 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Alertas de Stock Bajo
                    <span class="ml-auto bg-white text-red-600 px-3 py-1 rounded-full text-sm font-bold">
                        <?= count($stockBajo) ?>
                    </span>
                </h3>
            </div>
            
            <div class="p-6">
                <?php if (!empty($stockBajo)): ?>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <?php foreach ($stockBajo as $item): ?>
                            <div class="flex items-center space-x-4 p-3 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                <?php if ($item['thumbnail']): ?>
                                    <img src="<?= UPLOADS_URL ?>/thumbs/<?= e($item['thumbnail']) ?>" 
                                         alt="<?= e($item['nombre']) ?>"
                                         class="w-12 h-12 object-cover rounded-lg">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-gray-500"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">
                                        <?= e($item['nombre']) ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <?= e($item['marca']) ?> <?= e($item['modelo']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?= e($item['categoria']) ?>
                                    </p>
                                </div>
                                
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold
                                        <?= $item['stock'] <= 2 ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                        <i class="fas fa-box mr-1"></i>
                                        <?= $item['stock'] ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="<?= BASE_URL ?>/index.php?module=admin&action=inventario" 
                           class="text-red-600 hover:text-red-800 font-semibold text-sm flex items-center justify-center">
                            Ver todo el inventario
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-check-circle text-5xl text-green-500 mb-3"></i>
                        <p class="font-semibold">¡Todo bien!</p>
                        <p class="text-sm">No hay productos con stock bajo</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Autopartes Recientes -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    Autopartes Agregadas Recientemente
                </h3>
            </div>
            
            <div class="p-6">
                <?php if (!empty($autopartesRecientes)): ?>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <?php foreach ($autopartesRecientes as $autoparte): ?>
                            <div class="flex items-center space-x-4 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <?php if ($autoparte['thumbnail']): ?>
                                    <img src="<?= UPLOADS_URL ?>/thumbs/<?= e($autoparte['thumbnail']) ?>" 
                                         alt="<?= e($autoparte['nombre']) ?>"
                                         class="w-12 h-12 object-cover rounded-lg">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-300 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-gray-500"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">
                                        <?= e($autoparte['nombre']) ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <?= e($autoparte['marca']) ?> <?= e($autoparte['modelo']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <?= formatDateTime($autoparte['fecha_creacion']) ?>
                                    </p>
                                </div>
                                
                                <div class="text-right">
                                    <span class="text-sm font-semibold text-gray-700">
                                        Stock: <?= $autoparte['stock'] ?>
                                    </span>
                                    <p class="text-xs text-blue-600">
                                        <?= e($autoparte['categoria']) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box-open text-5xl opacity-50 mb-3"></i>
                        <p>No hay autopartes recientes</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Accesos Rápidos -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-semibold text-white mb-6">
            <i class="fas fa-bolt mr-2"></i>
            Accesos Rápidos
        </h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?= BASE_URL ?>/index.php?module=admin&action=inventario" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-list text-3xl mb-2"></i>
                <p class="font-semibold">Ver Inventario</p>
            </a>
            
            <a href="<?= BASE_URL ?>/index.php?module=admin&action=inventario-agregar" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-plus-circle text-3xl mb-2"></i>
                <p class="font-semibold">Agregar Autoparte</p>
            </a>
            
            <a href="<?= BASE_URL ?>/index.php?module=admin&action=categorias" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-tags text-3xl mb-2"></i>
                <p class="font-semibold">Categorías</p>
            </a>
            
            <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-eye text-3xl mb-2"></i>
                <p class="font-semibold">Ver Catálogo</p>
            </a>
        </div>
    </div>

</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>