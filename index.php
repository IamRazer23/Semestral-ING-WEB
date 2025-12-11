<?php
/**
 * Punto de entrada principal del sistema
 * Enrutador simple para el proyecto
 * 
 * @author Grupo 1SF131
 * @version 1.0
 */

// Cargar configuración (que a su vez carga las clases core)
$configFile = __DIR__ . '/config/config.php';
if (!file_exists($configFile)) {
    die('Error: No se encuentra el archivo config/config.php');
}
require_once $configFile;

// Obtener la acción de la URL
$action = $_GET['action'] ?? 'home';
$module = $_GET['module'] ?? 'public';

// Si está autenticado y trata de ir al login, redirigir al dashboard
if ($action === 'login' && isAuthenticated()) {
    switch ($_SESSION['rol_id']) {
        case ROL_ADMINISTRADOR:
            redirect('/admin/dashboard');
            break;
        case ROL_OPERADOR:
            redirect('/operador/dashboard');
            break;
        case ROL_CLIENTE:
            redirect('/cliente/dashboard');
            break;
    }
}

// Enrutamiento básico
switch ($module) {
    case 'auth':
        require_once CONTROLLERS_PATH . '/AuthController.php';
        $controller = new AuthController();
        
        switch ($action) {
            case 'login':
                $controller->login();
                break;
            case 'do_login':
                $controller->doLogin();
                break;
            case 'register':
                $controller->register();
                break;
            case 'do_register':
                $controller->doRegister();
                break;
            case 'logout':
                $controller->logout();
                break;
            default:
                $controller->login();
        }
        break;
        
    case 'admin':
        // Verificar si es administrador
        if (!hasRole(ROL_ADMINISTRADOR)) {
            setFlashMessage(MSG_ERROR, 'Acceso denegado');
            redirect('/index.php?module=auth&action=login');
        }
        
        require_once CONTROLLERS_PATH . '/AdminController.php';
        $controller = new AdminController();
        
        switch ($action) {
            case 'dashboard':
                $controller->dashboard();
                break;
                
            case 'getEstadisticas':
                $controller->getEstadisticas();
                break;
                
            case 'getGraficoVentas':
                $controller->getGraficoVentas();
                break;
                
            case 'configuracion':
                $controller->configuracion();
                break;
                
            // === RUTAS DE USUARIOS ===
            case 'usuarios':
                require_once CONTROLLERS_PATH . '/UsuarioController.php';
                $usuarioController = new UsuarioController();
                $usuarioController->index();
                break;
                
            // === RUTAS DE CATEGORÍAS ===
            case 'categorias':
                require_once CONTROLLERS_PATH . '/CategoriaController.php';
                $categoriaController = new CategoriaController();
                $categoriaController->index();
                break;
                
            case 'categoria-crear':
                require_once CONTROLLERS_PATH . '/CategoriaController.php';
                $categoriaController = new CategoriaController();
                $categoriaController->crear();
                break;
                
            case 'categoria-store':
                require_once CONTROLLERS_PATH . '/CategoriaController.php';
                $categoriaController = new CategoriaController();
                $categoriaController->store();
                break;
                
            case 'categoria-editar':
                require_once CONTROLLERS_PATH . '/CategoriaController.php';
                $categoriaController = new CategoriaController();
                $categoriaController->editar();
                break;
                
            case 'categoria-update':
                require_once CONTROLLERS_PATH . '/CategoriaController.php';
                $categoriaController = new CategoriaController();
                $categoriaController->update();
                break;
                
            case 'categoria-eliminar':
                require_once CONTROLLERS_PATH . '/CategoriaController.php';
                $categoriaController = new CategoriaController();
                $categoriaController->eliminar();
                break;
                
            case 'categoria-activar':
                require_once CONTROLLERS_PATH . '/CategoriaController.php';
                $categoriaController = new CategoriaController();
                $categoriaController->activar();
                break;
                
            case 'categoria-detalle':
                require_once CONTROLLERS_PATH . '/CategoriaController.php';
                $categoriaController = new CategoriaController();
                $categoriaController->detalle();
                break;
                
            // Agregar más casos según necesites
            default:
                $controller->dashboard();
        }
        break;
        
    case 'operador':
        // Verificar si es operador o admin
        if (!hasRole(ROL_OPERADOR) && !hasRole(ROL_ADMINISTRADOR)) {
            setFlashMessage(MSG_ERROR, 'Acceso denegado');
            redirect('/index.php?module=auth&action=login');
        }
        
        require_once CONTROLLERS_PATH . '/OperadorController.php';
        $controller = new OperadorController();
        
        switch ($action) {
            case 'dashboard':
                $controller->dashboard();
                break;
            // Agregar más casos
            default:
                $controller->dashboard();
        }
        break;
        
    case 'cliente':
        // Verificar si es cliente autenticado
        if (!hasRole(ROL_CLIENTE)) {
            setFlashMessage(MSG_ERROR, 'Debes iniciar sesión');
            redirect('/index.php?module=auth&action=login');
        }
        
        require_once CONTROLLERS_PATH . '/ClienteController.php';
        $controller = new ClienteController();
        
        switch ($action) {
            case 'dashboard':
                $controller->dashboard();
                break;
            case 'carrito':
                $controller->carrito();
                break;
            // Agregar más casos
            default:
                $controller->dashboard();
        }
        break;
        
    case 'public':
    default:
        // Parte pública (catálogo)
        require_once CONTROLLERS_PATH . '/PublicController.php';
        $controller = new PublicController();
        
        switch ($action) {
            case 'catalogo':
                $controller->catalogo();
                break;
            case 'detalle':
                $controller->detalle();
                break;
            case 'buscar':
                $controller->buscar();
                break;
            case 'home':
            default:
                $controller->home();
        }
        break;
}
?>