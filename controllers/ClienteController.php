<?php
/**
 * Controlador Cliente
 * Maneja las funcionalidades del cliente (compras, carrito, historial)
 * 
 * @author Grupo 1SF131
 * @version 1.0
 */

// Cargar dependencias necesarias
if (!class_exists('Database')) {
    require_once __DIR__ . '/../config/Database.php';
}

class ClienteController {
    
    private $usuarioId;
    
    public function __construct() {
        // Verificar que sea cliente
        if (!hasRole(ROL_CLIENTE)) {
            setFlashMessage(MSG_ERROR, 'Acceso denegado');
            redirect('/index.php?module=auth&action=login');
        }
        
        $this->usuarioId = $_SESSION['usuario_id'];
    }
    
    /**
     * Dashboard del cliente
     */
    public function dashboard() {
        try {
            $db = Database::getInstance();
            
            // Total de compras del cliente
            $queryTotalCompras = "SELECT COUNT(*) as total FROM ventas WHERE usuario_id = :usuario_id";
            $totalCompras = $db->fetchOne($queryTotalCompras, [':usuario_id' => $this->usuarioId])['total'];
            
            // Total gastado
            $queryTotalGastado = "SELECT COALESCE(SUM(total), 0) as total FROM ventas WHERE usuario_id = :usuario_id";
            $totalGastado = $db->fetchOne($queryTotalGastado, [':usuario_id' => $this->usuarioId])['total'];
            
            // Items en el carrito
            $queryCarrito = "SELECT COUNT(*) as total FROM carrito WHERE usuario_id = :usuario_id";
            $itemsCarrito = $db->fetchOne($queryCarrito, [':usuario_id' => $this->usuarioId])['total'];
            
            // Últimas compras
            $queryUltimasCompras = "SELECT 
                v.id, v.total, v.fecha_venta,
                COUNT(dv.id) as total_items
                FROM ventas v
                LEFT JOIN detalle_venta dv ON v.id = dv.venta_id
                WHERE v.usuario_id = :usuario_id
                GROUP BY v.id
                ORDER BY v.fecha_venta DESC
                LIMIT 5";
            $ultimasCompras = $db->fetchAll($queryUltimasCompras, [':usuario_id' => $this->usuarioId]);
            
            // Autopartes más compradas
            $queryAutopartesTop = "SELECT 
                a.id, a.nombre, a.marca, a.modelo, a.thumbnail,
                SUM(dv.cantidad) as total_comprado
                FROM autopartes a
                INNER JOIN detalle_venta dv ON a.id = dv.autoparte_id
                INNER JOIN ventas v ON dv.venta_id = v.id
                WHERE v.usuario_id = :usuario_id
                GROUP BY a.id
                ORDER BY total_comprado DESC
                LIMIT 5";
            $autopartesTop = $db->fetchAll($queryAutopartesTop, [':usuario_id' => $this->usuarioId]);
            
            // Variables para la vista
            $pageTitle = 'Mi Panel - Cliente';
            
            // Incluir la vista
            require_once VIEWS_PATH . '/cliente/dashboard.php';
            
        } catch (Exception $e) {
            setFlashMessage(MSG_ERROR, 'Error al cargar el panel');
            redirect('/index.php?module=auth&action=login');
        }
    }
    
    /**
     * Obtiene el contador del carrito (AJAX)
     */
    public function cart_count() {
        try {
            $db = Database::getInstance();
            
            $query = "SELECT COALESCE(SUM(cantidad), 0) as total FROM carrito WHERE usuario_id = :usuario_id";
            $result = $db->fetchOne($query, [':usuario_id' => $this->usuarioId]);
            
            jsonResponse([
                'success' => true,
                'count' => (int)$result['total']
            ]);
            
        } catch (Exception $e) {
            jsonResponse([
                'success' => false,
                'count' => 0
            ]);
        }
    }
    
    /**
     * Vista del carrito
     */
    public function carrito() {
        $pageTitle = 'Mi Carrito de Compras';
        
        // TODO: Implementar vista del carrito
        require_once VIEWS_PATH . '/cliente/carrito.php';
    }
}
?>