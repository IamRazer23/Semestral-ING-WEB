<?php
/**
 * Vista: Dashboard del Cliente
 * Panel principal para clientes autenticados
 */

$pageTitle = 'Mi Panel - Cliente';

require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="container mx-auto px-4 py-8">
    
    <!-- Header del Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-tachometer-alt text-green-600 mr-2"></i>
            Bienvenido, <?= e($_SESSION['usuario_nombre']) ?>
        </h1>
        <p class="text-gray-600">
            Este es tu panel personal donde puedes gestionar tus compras y preferencias
        </p>
    </div>

    <!-- Cards de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Total de Compras -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Mis Compras</p>
                    <h3 class="text-4xl font-bold"><?= $totalCompras ?></h3>
                    <p class="text-blue-100 text-xs mt-2">
                        <i class="fas fa-shopping-bag mr-1"></i>
                        Órdenes realizadas
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-shopping-cart text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Gastado -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Gastado</p>
                    <h3 class="text-4xl font-bold"><?= formatCurrency($totalGastado) ?></h3>
                    <p class="text-green-100 text-xs mt-2">
                        <i class="fas fa-dollar-sign mr-1"></i>
                        En todas tus compras
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-wallet text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Items en Carrito -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Mi Carrito</p>
                    <h3 class="text-4xl font-bold"><?= $itemsCarrito ?></h3>
                    <p class="text-purple-100 text-xs mt-2">
                        <i class="fas fa-box mr-1"></i>
                        Items pendientes
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-shopping-basket text-3xl"></i>
                </div>
            </div>
            <?php if ($itemsCarrito > 0): ?>
                <a href="<?= BASE_URL ?>/index.php?module=cliente&action=carrito" 
                   class="block mt-4 text-center bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg text-sm font-semibold transition">
                    Ver Carrito →
                </a>
            <?php endif; ?>
        </div>

    </div>

    <!-- Grid de 2 Columnas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <!-- Últimas Compras -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-history mr-2"></i>
                    Mis Últimas Compras
                </h3>
            </div>
            
            <div class="p-6">
                <?php if (!empty($ultimasCompras)): ?>
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        <?php foreach ($ultimasCompras as $compra): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">
                                        Orden #<?= str_pad($compra['id'], 6, '0', STR_PAD_LEFT) ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-shopping-cart text-xs mr-1"></i>
                                        <?= $compra['total_items'] ?> items
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-calendar text-xs mr-1"></i>
                                        <?= formatDate($compra['fecha_venta']) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">
                                        <?= formatCurrency($compra['total']) ?>
                                    </p>
                                    <a href="<?= BASE_URL ?>/index.php?module=cliente&action=ver-compra&id=<?= $compra['id'] ?>" 
                                       class="text-xs text-blue-600 hover:text-blue-800">
                                        Ver detalle →
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="<?= BASE_URL ?>/index.php?module=cliente&action=mis-compras" 
                           class="text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center justify-center">
                            Ver todas mis compras
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-shopping-bag text-5xl opacity-50 mb-3"></i>
                        <p class="font-semibold">No has realizado compras aún</p>
                        <p class="text-sm mt-2">¡Explora nuestro catálogo!</p>
                        <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo" 
                           class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition">
                            <i class="fas fa-search mr-2"></i>
                            Explorar Catálogo
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Autopartes Favoritas -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-star mr-2"></i>
                    Mis Autopartes Favoritas
                </h3>
            </div>
            
            <div class="p-6">
                <?php if (!empty($autopartesTop)): ?>
                    <div class="space-y-4">
                        <?php foreach ($autopartesTop as $autoparte): ?>
                            <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-purple-50 transition-colors">
                                <?php if ($autoparte['thumbnail']): ?>
                                    <img src="<?= UPLOADS_URL ?>/thumbs/<?= e($autoparte['thumbnail']) ?>" 
                                         alt="<?= e($autoparte['nombre']) ?>"
                                         class="w-16 h-16 object-cover rounded-lg">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-300 rounded-lg flex items-center justify-center">
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
                                    <p class="text-xs text-purple-600">
                                        <i class="fas fa-shopping-cart mr-1"></i>
                                        Comprado <?= $autoparte['total_comprado'] ?> veces
                                    </p>
                                </div>
                                
                                <a href="<?= BASE_URL ?>/index.php?module=public&action=detalle&id=<?= $autoparte['id'] ?>" 
                                   class="text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-heart text-5xl opacity-50 mb-3"></i>
                        <p class="font-semibold">Aún no tienes favoritos</p>
                        <p class="text-sm mt-2">Tus compras frecuentes aparecerán aquí</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Accesos Rápidos -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-semibold text-white mb-6">
            <i class="fas fa-bolt mr-2"></i>
            Accesos Rápidos
        </h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?= BASE_URL ?>/index.php?module=public&action=catalogo" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-search text-3xl mb-2"></i>
                <p class="font-semibold">Buscar Autopartes</p>
            </a>
            
            <a href="<?= BASE_URL ?>/index.php?module=cliente&action=carrito" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                <p class="font-semibold">Mi Carrito</p>
                <?php if ($itemsCarrito > 0): ?>
                    <span class="inline-block bg-red-500 text-white text-xs px-2 py-1 rounded-full mt-1">
                        <?= $itemsCarrito ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="<?= BASE_URL ?>/index.php?module=cliente&action=mis-compras" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-receipt text-3xl mb-2"></i>
                <p class="font-semibold">Mis Compras</p>
            </a>
            
            <a href="<?= BASE_URL ?>/index.php?module=cliente&action=perfil" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-user-cog text-3xl mb-2"></i>
                <p class="font-semibold">Mi Perfil</p>
            </a>
        </div>
    </div>

    <!-- Ayuda -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">¿Necesitas Ayuda?</h3>
                <p class="text-blue-800 text-sm mb-3">
                    Estamos aquí para ayudarte con cualquier pregunta sobre tus compras o nuestro inventario.
                </p>
                <div class="flex gap-3 flex-wrap">
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=contacto" 
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-envelope mr-2"></i>
                        Contactar Soporte
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?module=public&action=faq" 
                       class="inline-block bg-white hover:bg-gray-100 text-blue-600 px-4 py-2 rounded-lg text-sm font-semibold transition border border-blue-600">
                        <i class="fas fa-question-circle mr-2"></i>
                        Preguntas Frecuentes
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>