<?php
/**
 * Vista: Lista de Categorías
 * Muestra todas las categorías con filtros y acciones
 */

// Scripts personalizados
$customScripts = '
<script>
// Función para eliminar/desactivar categoría
function toggleEstadoCategoria(id, estadoActual) {
    const accion = estadoActual == 1 ? "desactivar" : "activar";
    const endpoint = estadoActual == 1 ? "categoria-eliminar" : "categoria-activar";
    
    if (!confirm(`¿Estás seguro de ${accion} esta categoría?`)) {
        return;
    }
    
    fetch("' . BASE_URL . '/index.php?module=admin&action=" + endpoint, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: "id=" + id
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || "Error al procesar la solicitud");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Error al procesar la solicitud");
    });
}

// Función para ver detalle de categoría
function verDetalle(id) {
    fetch("' . BASE_URL . '/index.php?module=admin&action=categoria-detalle&id=" + id, {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            mostrarModalDetalle(data.categoria, data.estadisticas);
        } else {
            alert(data.message || "Error al obtener información");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Error al obtener información");
    });
}

// Mostrar modal con detalle
function mostrarModalDetalle(categoria, stats) {
    const modalHTML = `
        <div id="modal-detalle" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl max-w-lg w-full p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-2xl font-bold text-gray-800">${categoria.nombre}</h3>
                    <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                ${categoria.imagen ? `
                    <img src="' . UPLOADS_URL . '/categories/${categoria.imagen}" 
                         alt="${categoria.nombre}"
                         class="w-full h-48 object-cover rounded-lg mb-4">
                ` : ""}
                
                <p class="text-gray-600 mb-4">${categoria.descripcion || "Sin descripción"}</p>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600">Total Autopartes</p>
                        <p class="text-2xl font-bold text-blue-600">${stats.total_autopartes}</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600">Con Stock</p>
                        <p class="text-2xl font-bold text-green-600">${stats.con_stock}</p>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600">Stock Total</p>
                        <p class="text-2xl font-bold text-purple-600">${stats.stock_total}</p>
                    </div>
                    <div class="bg-orange-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600">Estado</p>
                        <p class="text-2xl font-bold ${categoria.estado == 1 ? "text-green-600" : "text-red-600"}">
                            ${categoria.estado == 1 ? "Activa" : "Inactiva"}
                        </p>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <a href="' . BASE_URL . '/index.php?module=admin&action=categoria-editar&id=${categoria.id}"
                       class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg text-center">
                        <i class="fas fa-edit mr-2"></i>Editar
                    </a>
                    <button onclick="cerrarModal()" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-lg">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML("beforeend", modalHTML);
}

function cerrarModal() {
    const modal = document.getElementById("modal-detalle");
    if (modal) {
        modal.remove();
    }
}
</script>
';

// Incluir header
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="container mx-auto px-4 py-8">
    
    <!-- Header de la página -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-tags text-indigo-600 mr-2"></i>
                Gestión de Categorías
            </h1>
            <p class="text-gray-600">Administra las categorías de autopartes del sistema</p>
        </div>
        
        <?php if (hasPermission('categorias', 'crear')): ?>
        <div class="mt-4 md:mt-0">
            <a href="<?= BASE_URL ?>/index.php?module=admin&action=categoria-crear" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center space-x-2 shadow-md hover:shadow-lg transition-all">
                <i class="fas fa-plus-circle"></i>
                <span>Nueva Categoría</span>
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="module" value="admin">
            <input type="hidden" name="action" value="categorias">
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search text-gray-400 mr-1"></i>
                    Buscar
                </label>
                <input type="text" 
                       name="buscar" 
                       placeholder="Nombre o descripción..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       value="<?= isset($filtros['buscar']) ? e($filtros['buscar']) : '' ?>">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter text-gray-400 mr-1"></i>
                    Estado
                </label>
                <select name="estado" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos</option>
                    <option value="1" <?= (isset($filtros['estado']) && $filtros['estado'] == '1') ? 'selected' : '' ?>>
                        Activas
                    </option>
                    <option value="0" <?= (isset($filtros['estado']) && $filtros['estado'] == '0') ? 'selected' : '' ?>>
                        Inactivas
                    </option>
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="flex-1 bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=categorias"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <?php
        $totalCategorias = count($categorias);
        $activas = count(array_filter($categorias, fn($c) => $c['estado'] == 1));
        $inactivas = $totalCategorias - $activas;
        $conAutopartes = count(array_filter($categorias, fn($c) => $c['total_autopartes'] > 0));
        ?>
        
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Total</p>
                    <p class="text-3xl font-bold"><?= $totalCategorias ?></p>
                </div>
                <i class="fas fa-tags text-4xl opacity-20"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">Activas</p>
                    <p class="text-3xl font-bold"><?= $activas ?></p>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-20"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm mb-1">Inactivas</p>
                    <p class="text-3xl font-bold"><?= $inactivas ?></p>
                </div>
                <i class="fas fa-ban text-4xl opacity-20"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm mb-1">Con Autopartes</p>
                    <p class="text-3xl font-bold"><?= $conAutopartes ?></p>
                </div>
                <i class="fas fa-car text-4xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Grid de Categorías -->
    <?php if (empty($categorias)): ?>
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-tags text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay categorías</h3>
            <p class="text-gray-500 mb-6">
                <?php if (!empty($filtros['buscar']) || !empty($filtros['estado'])): ?>
                    No se encontraron categorías con los filtros aplicados
                <?php else: ?>
                    Comienza creando tu primera categoría
                <?php endif; ?>
            </p>
            <?php if (hasPermission('categorias', 'crear')): ?>
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=categoria-crear" 
                   class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold">
                    <i class="fas fa-plus-circle"></i>
                    <span>Nueva Categoría</span>
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($categorias as $categoria): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Imagen -->
                    <div class="h-40 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center relative">
                        <?php if ($categoria['imagen']): ?>
                            <img src="<?= UPLOADS_URL ?>/categories/<?= e($categoria['imagen']) ?>" 
                                 alt="<?= e($categoria['nombre']) ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-tags text-6xl text-indigo-300"></i>
                        <?php endif; ?>
                        
                        <!-- Badge de estado -->
                        <div class="absolute top-2 right-2">
                            <?php if ($categoria['estado'] == 1): ?>
                                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                    <i class="fas fa-check mr-1"></i>Activa
                                </span>
                            <?php else: ?>
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                    <i class="fas fa-ban mr-1"></i>Inactiva
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-800 mb-2 truncate">
                            <?= e($categoria['nombre']) ?>
                        </h3>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                            <?= e($categoria['descripcion'] ?: 'Sin descripción') ?>
                        </p>
                        
                        <!-- Estadísticas -->
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-indigo-600">
                                    <?= $categoria['total_autopartes'] ?>
                                </p>
                                <p class="text-xs text-gray-500">Autopartes</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Creada</p>
                                <p class="text-sm font-semibold text-gray-700">
                                    <?= formatDate($categoria['fecha_creacion']) ?>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Acciones -->
                        <div class="flex gap-2">
                            <button onclick="verDetalle(<?= $categoria['id'] ?>)"
                                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Ver
                            </button>
                            
                            <?php if (hasPermission('categorias', 'actualizar')): ?>
                            <a href="<?= BASE_URL ?>/index.php?module=admin&action=categoria-editar&id=<?= $categoria['id'] ?>"
                               class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-3 rounded-lg text-sm font-medium text-center transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Editar
                            </a>
                            <?php endif; ?>
                            
                            <?php if (hasPermission('categorias', 'eliminar')): ?>
                            <button onclick="toggleEstadoCategoria(<?= $categoria['id'] ?>, <?= $categoria['estado'] ?>)"
                                    class="bg-<?= $categoria['estado'] == 1 ? 'red' : 'green' ?>-600 hover:bg-<?= $categoria['estado'] == 1 ? 'red' : 'green' ?>-700 text-white py-2 px-3 rounded-lg text-sm transition-colors">
                                <i class="fas fa-<?= $categoria['estado'] == 1 ? 'ban' : 'check' ?>"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>