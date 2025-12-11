<?php
/**
 * Controlador Administrador
 * Maneja las funcionalidades del panel administrativo
 * 
 * @author Grupo 1SF131
 * @version 1.0
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Usuario.php';

class AdminController {
    
    private $usuarioModel;
    
    public function __construct() {
        // Verificar que el usuario sea administrador
        if (!hasRole(ROL_ADMINISTRADOR)) {
            setFlashMessage(MSG_ERROR, 'Acceso denegado');
            redirect('/index.php?module=auth&action=login');
        }
        
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Dashboard principal del administrador
     */
    public function dashboard() {
        try {
            $db = Database::getInstance();
            
            // ===== ESTADÍSTICAS GENERALES =====
            
            // Total de usuarios activos
            $totalUsuarios = $this->usuarioModel->contarTodos(['estado' => 1]);
            
            // Total de autopartes en inventario
            $queryAutopartes = "SELECT COUNT(*) as total FROM autopartes WHERE estado = 1";
            $totalAutopartes = $db->fetchOne($queryAutopartes)['total'];
            
            // Total de categorías
            $queryCategorias = "SELECT COUNT(*) as total FROM categorias WHERE estado = 1";
            $totalCategorias = $db->fetchOne($queryCategorias)['total'];
            
            // Ventas del día
            $queryVentasHoy = "SELECT 
                COUNT(*) as total_ventas,
                COALESCE(SUM(total), 0) as total_ingresos
                FROM ventas 
                WHERE DATE(fecha_venta) = CURDATE()";
            $ventasHoy = $db->fetchOne($queryVentasHoy);
            
            // Ventas del mes actual
            $queryVentasMes = "SELECT 
                COUNT(*) as total_ventas,
                COALESCE(SUM(total), 0) as total_ingresos
                FROM ventas 
                WHERE YEAR(fecha_venta) = YEAR(CURDATE())
                AND MONTH(fecha_venta) = MONTH(CURDATE())";
            $ventasMes = $db->fetchOne($queryVentasMes);
            
            // Total de ventas acumuladas
            $queryVentasTotal = "SELECT 
                COUNT(*) as total_ventas,
                COALESCE(SUM(total), 0) as total_ingresos
                FROM ventas";
            $ventasTotal = $db->fetchOne($queryVentasTotal);
            
            // ===== AUTOPARTES CON STOCK BAJO =====
            $queryStockBajo = "SELECT 
                a.id, a.nombre, a.marca, a.modelo, a.stock, 
                a.thumbnail, c.nombre as categoria
                FROM autopartes a
                INNER JOIN categorias c ON a.categoria_id = c.id
                WHERE a.stock <= 5 AND a.estado = 1
                ORDER BY a.stock ASC
                LIMIT 10";
            $stockBajo = $db->fetchAll($queryStockBajo);
            
            // ===== ÚLTIMAS VENTAS =====
            $queryUltimasVentas = "SELECT 
                v.id, v.total, v.fecha_venta,
                u.nombre as cliente,
                COUNT(dv.id) as total_items
                FROM ventas v
                INNER JOIN usuarios u ON v.usuario_id = u.id
                LEFT JOIN detalle_venta dv ON v.id = dv.venta_id
                GROUP BY v.id
                ORDER BY v.fecha_venta DESC
                LIMIT 10";
            $ultimasVentas = $db->fetchAll($queryUltimasVentas);
            
            // ===== USUARIOS RECIENTES =====
            $queryUsuariosRecientes = "SELECT 
                u.id, u.nombre, u.email, u.fecha_creacion,
                r.nombre as rol
                FROM usuarios u
                INNER JOIN roles r ON u.rol_id = r.id
                WHERE u.estado = 1
                ORDER BY u.fecha_creacion DESC
                LIMIT 5";
            $usuariosRecientes = $db->fetchAll($queryUsuariosRecientes);
            
            // ===== CATEGORÍAS MÁS VENDIDAS =====
            $queryCategoriasTop = "SELECT 
                c.id, c.nombre,
                COUNT(dv.id) as total_ventas,
                SUM(dv.cantidad) as total_piezas
                FROM categorias c
                INNER JOIN autopartes a ON c.id = a.categoria_id
                INNER JOIN detalle_venta dv ON a.id = dv.autoparte_id
                GROUP BY c.id
                ORDER BY total_piezas DESC
                LIMIT 5";
            $categoriasTop = $db->fetchAll($queryCategoriasTop);
            
            // ===== VENTAS DE LOS ÚLTIMOS 7 DÍAS =====
            $queryVentasSemana = "SELECT 
                DATE(fecha_venta) as fecha,
                COUNT(*) as total_ventas,
                SUM(total) as ingresos
                FROM ventas
                WHERE fecha_venta >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(fecha_venta)
                ORDER BY fecha ASC";
            $ventasSemana = $db->fetchAll($queryVentasSemana);
            
            // ===== AUTOPARTES MÁS VENDIDAS =====
            $queryAutopartesTop = "SELECT 
                a.id, a.nombre, a.marca, a.modelo, a.thumbnail,
                SUM(dv.cantidad) as total_vendido
                FROM autopartes a
                INNER JOIN detalle_venta dv ON a.id = dv.autoparte_id
                GROUP BY a.id
                ORDER BY total_vendido DESC
                LIMIT 5";
            $autopartesTop = $db->fetchAll($queryAutopartesTop);
            
            // ===== COMPARACIÓN MES ACTUAL VS ANTERIOR =====
            $queryComparacionMes = "SELECT 
                (SELECT COALESCE(SUM(total), 0) 
                 FROM ventas 
                 WHERE YEAR(fecha_venta) = YEAR(CURDATE())
                 AND MONTH(fecha_venta) = MONTH(CURDATE())) as mes_actual,
                (SELECT COALESCE(SUM(total), 0) 
                 FROM ventas 
                 WHERE YEAR(fecha_venta) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
                 AND MONTH(fecha_venta) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))) as mes_anterior";
            $comparacionMes = $db->fetchOne($queryComparacionMes);
            
            // Calcular porcentaje de cambio
            $porcentajeCambio = 0;
            if ($comparacionMes['mes_anterior'] > 0) {
                $porcentajeCambio = (($comparacionMes['mes_actual'] - $comparacionMes['mes_anterior']) 
                                    / $comparacionMes['mes_anterior']) * 100;
            }
            
            // Variables para la vista
            $pageTitle = 'Dashboard Administrativo - Sistema AutoPartes';
            
            // Incluir la vista
            require_once VIEWS_PATH . '/admin/dashboard.php';
            
        } catch (Exception $e) {
            setFlashMessage(MSG_ERROR, 'Error al cargar el dashboard');
            redirect('/index.php?module=auth&action=login');
        }
    }
    
    /**
     * Obtiene estadísticas en tiempo real (AJAX)
     */
    public function getEstadisticas() {
        try {
            $db = Database::getInstance();
            
            $tipo = $_GET['tipo'] ?? 'general';
            
            switch ($tipo) {
                case 'ventas_hoy':
                    $query = "SELECT 
                        COUNT(*) as total,
                        COALESCE(SUM(total), 0) as ingresos
                        FROM ventas 
                        WHERE DATE(fecha_venta) = CURDATE()";
                    $data = $db->fetchOne($query);
                    break;
                    
                case 'stock_bajo':
                    $query = "SELECT COUNT(*) as total 
                             FROM autopartes 
                             WHERE stock <= 5 AND estado = 1";
                    $data = $db->fetchOne($query);
                    break;
                    
                case 'usuarios_activos':
                    $data = ['total' => $this->usuarioModel->contarTodos(['estado' => 1])];
                    break;
                    
                default:
                    $data = ['error' => 'Tipo de estadística no válido'];
            }
            
            jsonResponse(['success' => true, 'data' => $data]);
            
        } catch (Exception $e) {
            jsonResponse(['success' => false, 'message' => 'Error al obtener estadísticas']);
        }
    }
    
    /**
     * Obtiene datos para gráficos (AJAX)
     */
    public function getGraficoVentas() {
        try {
            $db = Database::getInstance();
            $periodo = $_GET['periodo'] ?? '7dias';
            
            switch ($periodo) {
                case '7dias':
                    $query = "SELECT 
                        DATE_FORMAT(fecha_venta, '%d/%m') as label,
                        COUNT(*) as ventas,
                        SUM(total) as ingresos
                        FROM ventas
                        WHERE fecha_venta >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                        GROUP BY DATE(fecha_venta)
                        ORDER BY fecha_venta ASC";
                    break;
                    
                case '30dias':
                    $query = "SELECT 
                        DATE_FORMAT(fecha_venta, '%d/%m') as label,
                        COUNT(*) as ventas,
                        SUM(total) as ingresos
                        FROM ventas
                        WHERE fecha_venta >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                        GROUP BY DATE(fecha_venta)
                        ORDER BY fecha_venta ASC";
                    break;
                    
                case '12meses':
                    $query = "SELECT 
                        DATE_FORMAT(fecha_venta, '%b %Y') as label,
                        COUNT(*) as ventas,
                        SUM(total) as ingresos
                        FROM ventas
                        WHERE fecha_venta >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                        GROUP BY YEAR(fecha_venta), MONTH(fecha_venta)
                        ORDER BY fecha_venta ASC";
                    break;
                    
                default:
                    jsonResponse(['success' => false, 'message' => 'Período no válido']);
                    return;
            }
            
            $data = $db->fetchAll($query);
            
            // Preparar datos para el gráfico
            $labels = [];
            $ventas = [];
            $ingresos = [];
            
            foreach ($data as $row) {
                $labels[] = $row['label'];
                $ventas[] = (int)$row['ventas'];
                $ingresos[] = (float)$row['ingresos'];
            }
            
            jsonResponse([
                'success' => true,
                'labels' => $labels,
                'ventas' => $ventas,
                'ingresos' => $ingresos
            ]);
            
        } catch (Exception $e) {
            jsonResponse(['success' => false, 'message' => 'Error al obtener datos del gráfico']);
        }
    }
    
    /**
     * Configuración del sistema (placeholder)
     */
    public function configuracion() {
        $pageTitle = 'Configuración del Sistema';
        
        // TODO: Implementar configuración
        require_once VIEWS_PATH . '/admin/configuracion.php';
    }
}
?>