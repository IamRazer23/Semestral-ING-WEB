<?php
/**
 * Vista Dashboard Administrativo
 * Panel principal con estadísticas y accesos rápidos
 */

// Configuración de la página
$pageTitle = 'Dashboard Administrativo - Sistema AutoPartes';

// Scripts personalizados
$customScripts = '
<script>
    // Actualizar estadísticas en tiempo real cada 30 segundos
    setInterval(actualizarEstadisticas, 30000);
    
    function actualizarEstadisticas() {
        // Ventas del día
        fetch("' . BASE_URL . '/index.php?module=admin&action=getEstadisticas&tipo=ventas_hoy", {
            headers: {"X-Requested-With": "XMLHttpRequest"}
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("ventas-hoy-count").textContent = data.data.total;
                document.getElementById("ventas-hoy-ingresos").textContent = "$" + parseFloat(data.data.ingresos).toFixed(2);
            }
        });
    }
</script>
';

// Incluir header
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="container mx-auto px-4 py-8">
    
    <!-- Header del Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-tachometer-alt text-indigo-600 mr-2"></i>
            Dashboard Administrativo
        </h1>
        <p class="text-gray-600">
            Bienvenido, <span class="font-semibold"><?= e($_SESSION['usuario_nombre']) ?></span>
            <span class="mx-2">•</span>
            <span class="text-sm"><?= date('l, d \d\e F \d\e Y') ?></span>
        </p>
    </div>

    <!-- ===== CARDS DE ESTADÍSTICAS PRINCIPALES ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card: Total Usuarios -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Usuarios</p>
                    <h3 class="text-4xl font-bold"><?= $totalUsuarios ?></h3>
                    <p class="text-blue-100 text-xs mt-2">
                        <i class="fas fa-users mr-1"></i>
                        Usuarios activos
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Card: Total Autopartes -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Inventario</p>
                    <h3 class="text-4xl font-bold"><?= $totalAutopartes ?></h3>
                    <p class="text-green-100 text-xs mt-2">
                        <i class="fas fa-warehouse mr-1"></i>
                        Autopartes activas
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-car text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Card: Ventas del Día -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Ventas Hoy</p>
                    <h3 class="text-4xl font-bold" id="ventas-hoy-count"><?= $ventasHoy['total_ventas'] ?></h3>
                    <p class="text-purple-100 text-xs mt-2" id="ventas-hoy-ingresos">
                        <i class="fas fa-dollar-sign mr-1"></i>
                        <?= formatCurrency($ventasHoy['total_ingresos']) ?>
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-shopping-bag text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Card: Ventas del Mes -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-1">Ventas del Mes</p>
                    <h3 class="text-4xl font-bold"><?= $ventasMes['total_ventas'] ?></h3>
                    <p class="text-orange-100 text-xs mt-2">
                        <i class="fas fa-dollar-sign mr-1"></i>
                        <?= formatCurrency($ventasMes['total_ingresos']) ?>
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- ===== COMPARACIÓN Y GRÁFICOS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Comparación Mensual -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-bar text-indigo-600 mr-2"></i>
                Comparación Mensual
            </h3>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Mes Actual</span>
                        <span class="text-lg font-bold text-gray-800">
                            <?= formatCurrency($comparacionMes['mes_actual']) ?>
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Mes Anterior</span>
                        <span class="text-lg font-bold text-gray-800">
                            <?= formatCurrency($comparacionMes['mes_anterior']) ?>
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <?php 
                        $porcentajeAncho = $comparacionMes['mes_actual'] > 0 
                            ? ($comparacionMes['mes_anterior'] / $comparacionMes['mes_actual']) * 100 
                            : 0;
                        ?>
                        <div class="bg-gray-400 h-2 rounded-full" style="width: <?= min($porcentajeAncho, 100) ?>%"></div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-center">
                        <?php if ($porcentajeCambio >= 0): ?>
                            <i class="fas fa-arrow-up text-green-500 text-xl mr-2"></i>
                            <span class="text-2xl font-bold text-green-500">
                                +<?= number_format($porcentajeCambio, 1) ?>%
                            </span>
                        <?php else: ?>
                            <i class="fas fa-arrow-down text-red-500 text-xl mr-2"></i>
                            <span class="text-2xl font-bold text-red-500">
                                <?= number_format($porcentajeCambio, 1) ?>%
                            </span>
                        <?php endif; ?>
                    </div>
                    <p class="text-center text-sm text-gray-500 mt-1">
                        vs mes anterior
                    </p>
                </div>
            </div>
        </div>

        <!-- Ventas de la Semana (Gráfico Simple) -->
        <div class="bg-white rounded-xl shadow-md p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-line text-indigo-600 mr-2"></i>
                Ventas de los Últimos 7 Días
            </h3>
            
            <div class="space-y-3">
                <?php foreach ($ventasSemana as $venta): ?>
                    <?php 
                    $maxIngresos = max(array_column($ventasSemana, 'ingresos'));
                    $porcentaje = $maxIngresos > 0 ? ($venta['ingresos'] / $maxIngresos) * 100 : 0;
                    ?>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">
                                <?= date('D d/m', strtotime($venta['fecha'])) ?>
                            </span>
                            <div class="text-right">
                                <span class="text-sm font-semibold text-gray-800">
                                    <?= formatCurrency($venta['ingresos']) ?>
                                </span>
                                <span class="text-xs text-gray-500 ml-2">
                                    (<?= $venta['total_ventas'] ?> ventas)
                                </span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full transition-all duration-500" 
                                 style="width: <?= $porcentaje ?>%">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($ventasSemana)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-chart-line text-4xl mb-2 opacity-50"></i>
                        <p>No hay ventas registradas en los últimos 7 días</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- ===== SECCIÓN: ALERTAS Y TABLAS ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <!-- Stock Bajo -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Alertas de Stock Bajo
                    <span class="ml-auto bg-white text-orange-600 px-3 py-1 rounded-full text-sm font-bold">
                        <?= count($stockBajo) ?>
                    </span>
                </h3>
            </div>
            
            <div class="p-6">
                <?php if (!empty($stockBajo)): ?>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <?php foreach ($stockBajo as $item): ?>
                            <div class="flex items-center space-x-4 p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
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
                        <a href="<?= BASE_URL ?>/index.php?module=admin&action=inventario-bajo" 
                           class="text-orange-600 hover:text-orange-800 font-semibold text-sm flex items-center justify-center">
                            Ver todo el inventario bajo
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

        <!-- Últimas Ventas -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Últimas Ventas
                </h3>
            </div>
            
            <div class="p-6">
                <?php if (!empty($ultimasVentas)): ?>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <?php foreach ($ultimasVentas as $venta): ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-indigo-50 transition-colors">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">
                                        <?= e($venta['cliente']) ?>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-shopping-cart text-xs mr-1"></i>
                                        <?= $venta['total_items'] ?> items
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-clock text-xs mr-1"></i>
                                        <?= formatDateTime($venta['fecha_venta']) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-indigo-600">
                                        <?= formatCurrency($venta['total']) ?>
                                    </p>
                                    <a href="<?= BASE_URL ?>/index.php?module=admin&action=venta-detalle&id=<?= $venta['id'] ?>" 
                                       class="text-xs text-gray-500 hover:text-indigo-600">
                                        Ver detalle →
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="<?= BASE_URL ?>/index.php?module=admin&action=ventas" 
                           class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm flex items-center justify-center">
                            Ver todas las ventas
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-shopping-bag text-5xl opacity-50 mb-3"></i>
                        <p>No hay ventas registradas</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- ===== SECCIÓN: TOP 5 Y USUARIOS RECIENTES ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Top 5 Autopartes Más Vendidas -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                Top 5 Más Vendidas
            </h3>
            
            <?php if (!empty($autopartesTop)): ?>
                <div class="space-y-3">
                    <?php foreach ($autopartesTop as $index => $autoparte): ?>
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm
                                <?= $index === 0 ? 'bg-yellow-100 text-yellow-700' : 
                                    ($index === 1 ? 'bg-gray-100 text-gray-700' : 
                                    ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-gray-50 text-gray-600')) ?>">
                                <?= $index + 1 ?>
                            </div>
                            
                            <?php if ($autoparte['thumbnail']): ?>
                                <img src="<?= UPLOADS_URL ?>/thumbs/<?= e($autoparte['thumbnail']) ?>" 
                                     alt="<?= e($autoparte['nombre']) ?>"
                                     class="w-10 h-10 object-cover rounded">
                            <?php else: ?>
                                <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 text-sm truncate">
                                    <?= e($autoparte['nombre']) ?>
                                </p>
                                <p class="text-xs text-gray-500">
                                    <?= $autoparte['total_vendido'] ?> vendidos
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-500 py-4 text-sm">
                    No hay datos disponibles
                </p>
            <?php endif; ?>
        </div>

        <!-- Top 5 Categorías -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-tags text-indigo-600 mr-2"></i>
                Categorías Populares
            </h3>
            
            <?php if (!empty($categoriasTop)): ?>
                <div class="space-y-4">
                    <?php 
                    $maxVentas = max(array_column($categoriasTop, 'total_piezas'));
                    foreach ($categoriasTop as $categoria): 
                        $porcentaje = $maxVentas > 0 ? ($categoria['total_piezas'] / $maxVentas) * 100 : 0;
                    ?>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">
                                    <?= e($categoria['nombre']) ?>
                                </span>
                                <span class="text-sm font-bold text-indigo-600">
                                    <?= $categoria['total_piezas'] ?>
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" 
                                     style="width: <?= $porcentaje ?>%">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-500 py-4 text-sm">
                    No hay datos disponibles
                </p>
            <?php endif; ?>
        </div>

        <!-- Usuarios Recientes -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-plus text-green-600 mr-2"></i>
                Usuarios Recientes
            </h3>
            
            <?php if (!empty($usuariosRecientes)): ?>
                <div class="space-y-3">
                    <?php foreach ($usuariosRecientes as $usuario): ?>
                        <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold">
                                <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 text-sm truncate">
                                    <?= e($usuario['nombre']) ?>
                                </p>
                                <p class="text-xs text-gray-500">
                                    <?= e($usuario['rol']) ?>
                                    <span class="mx-1">•</span>
                                    <?= formatDate($usuario['fecha_creacion']) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-500 py-4 text-sm">
                    No hay usuarios recientes
                </p>
            <?php endif; ?>
        </div>

    </div>

    <!-- ===== ACCESOS RÁPIDOS ===== -->
    <div class="mt-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-semibold text-white mb-6">
            <i class="fas fa-bolt mr-2"></i>
            Accesos Rápidos
        </h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?= BASE_URL ?>/index.php?module=admin&action=usuarios" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-users text-3xl mb-2"></i>
                <p class="font-semibold">Usuarios</p>
            </a>
            
            <a href="<?= BASE_URL ?>/index.php?module=admin&action=inventario-agregar" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-plus-circle text-3xl mb-2"></i>
                <p class="font-semibold">Nueva Autoparte</p>
            </a>
            
            <a href="<?= BASE_URL ?>/index.php?module=admin&action=estadisticas" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-chart-bar text-3xl mb-2"></i>
                <p class="font-semibold">Estadísticas</p>
            </a>
            
            <a href="<?= BASE_URL ?>/index.php?module=admin&action=reporte-inventario" 
               class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg p-4 text-center transition-all transform hover:scale-105">
                <i class="fas fa-file-excel text-3xl mb-2"></i>
                <p class="font-semibold">Exportar Excel</p>
            </a>
        </div>
    </div>

</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>