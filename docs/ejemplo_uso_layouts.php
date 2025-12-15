<?php
/**
 * EJEMPLO DE CÓMO USAR LOS LAYOUTS EN TUS VISTAS
 * 
 * Archivo de ejemplo: views/admin/dashboard.php
 * 
 * Los layouts se incluyen automáticamente desde el header.php
 * basándose en el rol del usuario autenticado
 */

// ============================================
// OPCIÓN 1: Vista Simple (sin datos adicionales)
// ============================================

// Definir título de la página (opcional)
$pageTitle = 'Dashboard - Panel de Administración';

// Incluir header (esto incluye automáticamente el menú según el rol)
require_once VIEWS_PATH . '/layouts/header.php';
?>

<!-- Tu contenido aquí -->
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Cards de estadísticas, etc -->
    </div>
</div>

<?php
// Incluir footer
require_once VIEWS_PATH . '/layouts/footer.php';
?>


<?php
// ============================================
// OPCIÓN 2: Vista con Breadcrumbs y Scripts Personalizados
// ============================================

// Configurar variables antes del header
$pageTitle = 'Gestión de Inventario';

// Breadcrumbs para navegación
$breadcrumbs = [
    ['text' => 'Administración', 'url' => BASE_URL . '/index.php?module=admin&action=dashboard'],
    ['text' => 'Inventario', 'url' => BASE_URL . '/index.php?module=admin&action=inventario']
];

// Scripts personalizados (se ejecutarán al final)
$customScripts = '
<script>
    // Tu JavaScript personalizado aquí
    console.log("Vista cargada");
</script>
';

// Estilos personalizados (se incluirán en el head)
$customStyles = '
    .custom-class {
        background: linear-gradient(to right, #667eea 0%, #764ba2 100%);
    }
';

// Incluir header
require_once VIEWS_PATH . '/layouts/header.php';
?>

<!-- Tu contenido aquí -->
<div class="container mx-auto px-4 py-8">
    <h1>Mi Vista Personalizada</h1>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>


<?php
// ============================================
// OPCIÓN 3: Vista SIN el menú de navegación
// ============================================

$pageTitle = 'Página sin menú';
$hideSearch = true; // Ocultar barra de búsqueda también

// NO incluir header automáticamente, crear uno personalizado
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Tu contenido sin menú -->
    <div class="min-h-screen bg-gray-100">
        <h1>Página independiente</h1>
    </div>
</body>
</html>


<?php
// ============================================
// ESTRUCTURA DE UN CONTROLADOR COMPLETO
// ============================================

class AdminController {
    
    /**
     * Muestra el dashboard del administrador
     */
    public function dashboard() {
        // Verificar permisos (ya hecho en index.php, pero double-check)
        if (!hasRole(ROL_ADMINISTRADOR)) {
            setFlashMessage(MSG_ERROR, 'Acceso denegado');
            redirect('/index.php?module=auth&action=login');
        }
        
        // Obtener datos necesarios
        require_once MODELS_PATH . '/Usuario.php';
        require_once MODELS_PATH . '/Autoparte.php';
        require_once MODELS_PATH . '/Venta.php';
        
        $usuarioModel = new Usuario();
        $autoparteModel = new Autoparte();
        $ventaModel = new Venta();
        
        // Estadísticas rápidas
        $totalUsuarios = $usuarioModel->contarTodos(['estado' => 1]);
        $totalAutopartes = $autoparteModel->contarTodos(['estado' => 1]);
        $totalVentasHoy = $ventaModel->contarVentasHoy();
        $ventasDelMes = $ventaModel->obtenerVentasDelMes();
        
        // Configurar variables para la vista
        $pageTitle = 'Dashboard Administrativo';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'url' => '']
        ];
        
        // Incluir la vista
        require_once VIEWS_PATH . '/admin/dashboard.php';
    }
    
    /**
     * Muestra el listado de usuarios
     */
    public function usuarios() {
        // Verificar permisos específicos
        if (!hasPermission('usuarios', 'leer')) {
            setFlashMessage(MSG_ERROR, 'No tienes permiso para ver usuarios');
            redirect('/index.php?module=admin&action=dashboard');
        }
        
        // Obtener filtros de búsqueda
        $filtros = [
            'buscar' => $_GET['buscar'] ?? '',
            'rol_id' => $_GET['rol'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];
        
        // Obtener usuarios
        require_once MODELS_PATH . '/Usuario.php';
        $usuarioModel = new Usuario();
        $usuarios = $usuarioModel->obtenerTodos($filtros);
        
        // Obtener roles para el filtro
        require_once MODELS_PATH . '/Rol.php';
        $rolModel = new Rol();
        $roles = $rolModel->obtenerTodos();
        
        // Variables para la vista
        $pageTitle = 'Gestión de Usuarios';
        $breadcrumbs = [
            ['text' => 'Dashboard', 'url' => BASE_URL . '/index.php?module=admin&action=dashboard'],
            ['text' => 'Usuarios', 'url' => '']
        ];
        
        // Script personalizado para búsqueda AJAX (opcional)
        $customScripts = '
        <script>
            // Búsqueda en tiempo real
            document.getElementById("buscar")?.addEventListener("input", function(e) {
                // Implementar búsqueda AJAX aquí
            });
        </script>
        ';
        
        require_once VIEWS_PATH . '/admin/usuarios/index.php';
    }
}


// ============================================
// EJEMPLO DE VISTA COMPLETA CON TODO
// ============================================
// Archivo: views/admin/usuarios/index.php
?>

<?php
$pageTitle = 'Gestión de Usuarios - Sistema AutoPartes';
$breadcrumbs = [
    ['text' => 'Dashboard', 'url' => BASE_URL . '/index.php?module=admin&action=dashboard'],
    ['text' => 'Usuarios', 'url' => '']
];

$customScripts = '
<script src="' . ASSETS_URL . '/js/usuarios.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar funciones específicas de usuarios
        initUsuariosTable();
    });
</script>
';

require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header de la página -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-users text-indigo-600 mr-2"></i>
                Gestión de Usuarios
            </h1>
            <p class="text-gray-600">Administra los usuarios del sistema</p>
        </div>
        
        <?php if (hasPermission('usuarios', 'crear')): ?>
        <div class="mt-4 md:mt-0">
            <a href="<?= BASE_URL ?>/index.php?module=admin&action=usuario-crear" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center space-x-2 shadow-md hover:shadow-lg transition-all">
                <i class="fas fa-plus"></i>
                <span>Nuevo Usuario</span>
            </a>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="module" value="admin">
            <input type="hidden" name="action" value="usuarios">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="buscar" placeholder="Nombre o email..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                       value="<?= isset($filtros['buscar']) ? e($filtros['buscar']) : '' ?>">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                <select name="rol" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= $rol['id'] ?>" <?= (isset($filtros['rol_id']) && $filtros['rol_id'] == $rol['id']) ? 'selected' : '' ?>>
                            <?= e($rol['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    <option value="1" <?= (isset($filtros['estado']) && $filtros['estado'] == '1') ? 'selected' : '' ?>>Activos</option>
                    <option value="0" <?= (isset($filtros['estado']) && $filtros['estado'] == '0') ? 'selected' : '' ?>>Inactivos</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
            </div>
        </form>
    </div>
    
    <!-- Tabla de usuarios -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registro</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($usuarios as $usuario): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-semibold">
                                        <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900"><?= e($usuario['nombre']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= e($usuario['email']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            <?= e($usuario['rol_nombre']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($usuario['estado'] == 1): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Activo
                            </span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactivo
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= formatDate($usuario['fecha_creacion']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <?php if (hasPermission('usuarios', 'actualizar')): ?>
                        <a href="<?= BASE_URL ?>/index.php?module=admin&action=usuario-editar&id=<?= $usuario['id'] ?>" 
                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (hasPermission('usuarios', 'eliminar')): ?>
                        <button onclick="toggleEstado(<?= $usuario['id'] ?>, <?= $usuario['estado'] ?>)" 
                                class="<?= $usuario['estado'] == 1 ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' ?>">
                            <i class="fas fa-<?= $usuario['estado'] == 1 ? 'ban' : 'check-circle' ?>"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>