<?php
/**
 * Vista: Lista de Usuarios
 * Muestra todos los usuarios del sistema con filtros y acciones
 */

require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="container mx-auto px-4 py-8">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-users text-indigo-600 mr-2"></i>
                Gestión de Usuarios
            </h1>
            <p class="text-gray-600 mt-1">Administra todos los usuarios del sistema</p>
        </div>
        
        <?php if (hasPermission('usuarios', 'crear')): ?>
            <a href="<?= BASE_URL ?>/index.php?module=admin&action=usuario-crear" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition inline-flex items-center">
                <i class="fas fa-user-plus mr-2"></i>
                Nuevo Usuario
            </a>
        <?php endif; ?>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Total Usuarios</p>
                    <p class="text-2xl font-bold text-blue-700"><?= $totalUsuarios ?></p>
                </div>
                <i class="fas fa-users text-blue-400 text-3xl"></i>
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Activos</p>
                    <p class="text-2xl font-bold text-green-700"><?= $totalActivos ?></p>
                </div>
                <i class="fas fa-check-circle text-green-400 text-3xl"></i>
            </div>
        </div>
        
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium">Inactivos</p>
                    <p class="text-2xl font-bold text-red-700"><?= $totalInactivos ?></p>
                </div>
                <i class="fas fa-ban text-red-400 text-3xl"></i>
            </div>
        </div>
        
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">Por Rol</p>
                    <div class="text-xs text-purple-700 mt-1">
                        <?php foreach ($statsRoles as $rolNombre => $count): ?>
                            <div><?= e($rolNombre) ?>: <?= $count ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <i class="fas fa-user-tag text-purple-400 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="<?= BASE_URL ?>/index.php" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="module" value="admin">
            <input type="hidden" name="action" value="usuarios">
            
            <!-- Búsqueda -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search text-gray-400 mr-1"></i>
                    Buscar
                </label>
                <input 
                    type="text" 
                    name="buscar" 
                    value="<?= e($filtros['buscar']) ?>"
                    placeholder="Nombre o email..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
            </div>
            
            <!-- Rol -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-tag text-gray-400 mr-1"></i>
                    Rol
                </label>
                <select 
                    name="rol" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">Todos los roles</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= $rol['id'] ?>" <?= $filtros['rol_id'] == $rol['id'] ? 'selected' : '' ?>>
                            <?= e($rol['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Estado -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                    Estado
                </label>
                <select 
                    name="estado" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">Todos</option>
                    <option value="1" <?= $filtros['estado'] === '1' ? 'selected' : '' ?>>Activos</option>
                    <option value="0" <?= $filtros['estado'] === '0' ? 'selected' : '' ?>>Inactivos</option>
                </select>
            </div>
            
            <!-- Botones -->
            <div class="md:col-span-4 flex gap-2">
                <button 
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition"
                >
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
                <a 
                    href="<?= BASE_URL ?>/index.php?module=admin&action=usuarios"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold transition"
                >
                    <i class="fas fa-redo mr-2"></i>
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <?php if (!empty($usuarios)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuario
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rol
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Registro
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Última Sesión
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Usuario -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-semibold text-lg">
                                                    <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= e($usuario['nombre']) ?>
                                                <?php if ($usuario['id'] == $_SESSION['usuario_id']): ?>
                                                    <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Tú</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= e($usuario['email']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Rol -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php
                                        switch($usuario['rol_id']) {
                                            case ROL_ADMINISTRADOR:
                                                echo 'bg-purple-100 text-purple-800';
                                                break;
                                            case ROL_OPERADOR:
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            case ROL_CLIENTE:
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                        }
                                        ?>
                                    ">
                                        <i class="fas fa-user-tag mr-1"></i>
                                        <?= e($usuario['rol_nombre']) ?>
                                    </span>
                                </td>
                                
                                <!-- Estado -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?= $usuario['estado'] == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>
                                    ">
                                        <i class="fas fa-<?= $usuario['estado'] == 1 ? 'check-circle' : 'ban' ?> mr-1"></i>
                                        <?= $usuario['estado'] == 1 ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                
                                <!-- Fecha Registro -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <i class="fas fa-calendar text-gray-400 mr-1"></i>
                                    <?= formatDate($usuario['fecha_creacion']) ?>
                                </td>
                                
                                <!-- Última Sesión -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php if ($usuario['ultima_sesion']): ?>
                                        <i class="fas fa-clock text-gray-400 mr-1"></i>
                                        <?= formatDateTime($usuario['ultima_sesion']) ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">Nunca</span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <!-- Ver Detalle -->
                                        <button 
                                            onclick="verDetalle(<?= $usuario['id'] ?>)"
                                            class="text-gray-600 hover:text-gray-900"
                                            title="Ver detalle"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <!-- Editar -->
                                        <?php if (hasPermission('usuarios', 'actualizar') && $usuario['id'] != $_SESSION['usuario_id']): ?>
                                            <a 
                                                href="<?= BASE_URL ?>/index.php?module=admin&action=usuario-editar&id=<?= $usuario['id'] ?>"
                                                class="text-indigo-600 hover:text-indigo-900"
                                                title="Editar"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <!-- Activar/Desactivar -->
                                        <?php if (hasPermission('usuarios', 'eliminar') && $usuario['id'] != $_SESSION['usuario_id']): ?>
                                            <?php if ($usuario['estado'] == 1): ?>
                                                <button 
                                                    onclick="toggleEstado(<?= $usuario['id'] ?>, 0)"
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Desactivar"
                                                >
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            <?php else: ?>
                                                <button 
                                                    onclick="toggleEstado(<?= $usuario['id'] ?>, 1)"
                                                    class="text-green-600 hover:text-green-900"
                                                    title="Activar"
                                                >
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Estado Vacío -->
            <div class="text-center py-12">
                <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No hay usuarios</h3>
                <p class="text-gray-500 mb-6">
                    <?php if (!empty($filtros['buscar']) || !empty($filtros['rol_id']) || $filtros['estado'] !== ''): ?>
                        No se encontraron usuarios con los filtros seleccionados
                    <?php else: ?>
                        Aún no hay usuarios registrados en el sistema
                    <?php endif; ?>
                </p>
                <?php if (hasPermission('usuarios', 'crear')): ?>
                    <a href="<?= BASE_URL ?>/index.php?module=admin&action=usuario-crear" 
                       class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-user-plus mr-2"></i>
                        Crear Primer Usuario
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- Modal de Detalle -->
<div id="modalDetalle" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Detalle del Usuario</h3>
                <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="contenidoDetalle">
                <!-- Se llena con AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
// Toggle Estado (Activar/Desactivar)
function toggleEstado(id, nuevoEstado) {
    const accion = nuevoEstado == 1 ? 'activar' : 'desactivar';
    const mensaje = nuevoEstado == 1 ? '¿Activar este usuario?' : '¿Desactivar este usuario?';
    
    if (!confirm(mensaje)) return;
    
    const url = '<?= BASE_URL ?>/index.php?module=admin&action=usuario-' + accion;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'id=' + id
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
}

// Ver Detalle
function verDetalle(id) {
    const modal = document.getElementById('modalDetalle');
    const contenido = document.getElementById('contenidoDetalle');
    
    contenido.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-4xl text-indigo-600"></i></div>';
    modal.classList.remove('hidden');
    
    fetch('<?= BASE_URL ?>/index.php?module=admin&action=usuario-detalle&id=' + id, {
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const u = data.usuario;
            contenido.innerHTML = `
                <div class="space-y-4">
                    <div class="flex items-center space-x-4 pb-4 border-b">
                        <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-bold text-2xl">${u.nombre.charAt(0).toUpperCase()}</span>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800">${u.nombre}</h4>
                            <p class="text-gray-600">${u.email}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Rol</p>
                            <p class="font-semibold text-gray-800">${u.rol_nombre}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Estado</p>
                            <p class="font-semibold ${u.estado == 1 ? 'text-green-600' : 'text-red-600'}">
                                ${u.estado == 1 ? 'Activo' : 'Inactivo'}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fecha de Registro</p>
                            <p class="font-semibold text-gray-800">${new Date(u.fecha_creacion).toLocaleDateString()}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Última Sesión</p>
                            <p class="font-semibold text-gray-800">${u.ultima_sesion ? new Date(u.ultima_sesion).toLocaleString() : 'Nunca'}</p>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t flex gap-2">
                        <a href="<?= BASE_URL ?>/index.php?module=admin&action=usuario-editar&id=${u.id}" 
                           class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-center font-semibold transition">
                            <i class="fas fa-edit mr-2"></i>Editar
                        </a>
                        <button onclick="cerrarModal()" 
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold transition">
                            Cerrar
                        </button>
                    </div>
                </div>
            `;
        } else {
            contenido.innerHTML = '<p class="text-red-600">Error: ' + data.message + '</p>';
        }
    })
    .catch(error => {
        contenido.innerHTML = '<p class="text-red-600">Error al cargar el detalle</p>';
    });
}

// Cerrar Modal
function cerrarModal() {
    document.getElementById('modalDetalle').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalDetalle').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});
</script>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>