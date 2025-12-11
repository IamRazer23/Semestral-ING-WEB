<?php
/**
 * Controlador Público
 * Maneja las páginas públicas (catálogo, detalles, búsqueda)
 * 
 * @author Grupo 1SF131
 * @version 1.0
 */

// Asegurarse de que Database esté disponible
require_once __DIR__ . '/../config/Database.php';

class PublicController {
    
    /**
     * Página de inicio
     */
    public function home() {
        try {
            $db = Database::getInstance();
            
            // Categorías disponibles
            $queryCategorias = "SELECT id, nombre, descripcion, imagen 
                               FROM categorias 
                               WHERE estado = 1 
                               ORDER BY nombre ASC";
            $categorias = $db->fetchAll($queryCategorias);
            
            // Autopartes destacadas (más recientes)
            $queryDestacadas = "SELECT 
                a.id, a.nombre, a.marca, a.modelo, a.precio, a.thumbnail,
                c.nombre as categoria
                FROM autopartes a
                INNER JOIN categorias c ON a.categoria_id = c.id
                WHERE a.estado = 1 AND a.stock > 0
                ORDER BY a.fecha_creacion DESC
                LIMIT 8";
            $destacadas = $db->fetchAll($queryDestacadas);
            
            // Variables para la vista
            $pageTitle = 'Inicio - AutoPartes Pro';
            
            // Incluir la vista
            require_once VIEWS_PATH . '/public/home.php';
            
        } catch (Exception $e) {
            setFlashMessage(MSG_ERROR, 'Error al cargar la página');
            require_once VIEWS_PATH . '/public/home.php';
        }
    }
    
    /**
     * Catálogo de autopartes
     */
    public function catalogo() {
        try {
            $db = Database::getInstance();
            
            // Filtros de búsqueda
            $filtros = [
                'categoria' => $_GET['categoria'] ?? '',
                'marca' => $_GET['marca'] ?? '',
                'buscar' => $_GET['q'] ?? '',
                'orden' => $_GET['orden'] ?? 'recientes'
            ];
            
            // Paginación
            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $porPagina = ITEMS_PER_PAGE;
            $offset = ($pagina - 1) * $porPagina;
            
            // Construir query
            $query = "SELECT 
                a.id, a.nombre, a.marca, a.modelo, a.anio, a.precio, a.stock, a.thumbnail,
                c.nombre as categoria, c.id as categoria_id
                FROM autopartes a
                INNER JOIN categorias c ON a.categoria_id = c.id
                WHERE a.estado = 1 AND a.stock > 0";
            
            $params = [];
            
            // Aplicar filtros
            if (!empty($filtros['categoria'])) {
                $query .= " AND c.nombre = :categoria";
                $params[':categoria'] = $filtros['categoria'];
            }
            
            if (!empty($filtros['marca'])) {
                $query .= " AND a.marca LIKE :marca";
                $params[':marca'] = '%' . $filtros['marca'] . '%';
            }
            
            if (!empty($filtros['buscar'])) {
                $query .= " AND (a.nombre LIKE :buscar OR a.marca LIKE :buscar OR a.modelo LIKE :buscar)";
                $params[':buscar'] = '%' . $filtros['buscar'] . '%';
            }
            
            // Ordenamiento
            switch ($filtros['orden']) {
                case 'precio_asc':
                    $query .= " ORDER BY a.precio ASC";
                    break;
                case 'precio_desc':
                    $query .= " ORDER BY a.precio DESC";
                    break;
                case 'nombre':
                    $query .= " ORDER BY a.nombre ASC";
                    break;
                case 'recientes':
                default:
                    $query .= " ORDER BY a.fecha_creacion DESC";
            }
            
            // Contar total para paginación
            $queryCount = str_replace('SELECT a.id, a.nombre, a.marca, a.modelo, a.anio, a.precio, a.stock, a.thumbnail, c.nombre as categoria, c.id as categoria_id', 
                                     'SELECT COUNT(*) as total', $query);
            $queryCount = preg_replace('/ORDER BY.*/', '', $queryCount);
            
            $totalItems = $db->fetchOne($queryCount, $params)['total'];
            $totalPaginas = ceil($totalItems / $porPagina);
            
            // Aplicar límite y offset
            $query .= " LIMIT :limit OFFSET :offset";
            $stmt = $db->getConnection()->prepare($query);
            
            // Bind de parámetros
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            $autopartes = $stmt->fetchAll();
            
            // Obtener todas las categorías para el filtro
            $queryCategorias = "SELECT DISTINCT nombre FROM categorias WHERE estado = 1 ORDER BY nombre";
            $categorias = $db->fetchAll($queryCategorias);
            
            // Obtener marcas únicas
            $queryMarcas = "SELECT DISTINCT marca FROM autopartes WHERE estado = 1 AND stock > 0 ORDER BY marca";
            $marcas = $db->fetchAll($queryMarcas);
            
            // Variables para la vista
            $pageTitle = 'Catálogo de Autopartes';
            $breadcrumbs = [
                ['text' => 'Inicio', 'url' => BASE_URL . '/index.php'],
                ['text' => 'Catálogo', 'url' => '']
            ];
            
            // Incluir la vista
            require_once VIEWS_PATH . '/public/catalogo.php';
            
        } catch (Exception $e) {
            setFlashMessage(MSG_ERROR, 'Error al cargar el catálogo');
            redirect('/index.php');
        }
    }
    
    /**
     * Detalle de una autoparte
     */
    public function detalle() {
        try {
            $id = $_GET['id'] ?? 0;
            
            if (!$id) {
                setFlashMessage(MSG_ERROR, 'Autoparte no encontrada');
                redirect('/index.php?module=public&action=catalogo');
            }
            
            $db = Database::getInstance();
            
            // Obtener autoparte
            $query = "SELECT 
                a.*, c.nombre as categoria, c.id as categoria_id
                FROM autopartes a
                INNER JOIN categorias c ON a.categoria_id = c.id
                WHERE a.id = :id AND a.estado = 1";
            
            $autoparte = $db->fetchOne($query, [':id' => $id]);
            
            if (!$autoparte) {
                setFlashMessage(MSG_ERROR, 'Autoparte no encontrada');
                redirect('/index.php?module=public&action=catalogo');
            }
            
            // Obtener comentarios publicados
            $queryComentarios = "SELECT 
                co.*, u.nombre as usuario_nombre
                FROM comentarios co
                LEFT JOIN usuarios u ON co.usuario_id = u.id
                WHERE co.autoparte_id = :id AND co.publicar = 1
                ORDER BY co.fecha_creacion DESC";
            $comentarios = $db->fetchAll($queryComentarios, [':id' => $id]);
            
            // Autopartes relacionadas (misma categoría)
            $queryRelacionadas = "SELECT 
                a.id, a.nombre, a.marca, a.modelo, a.precio, a.thumbnail
                FROM autopartes a
                WHERE a.categoria_id = :categoria_id 
                AND a.id != :id 
                AND a.estado = 1 
                AND a.stock > 0
                ORDER BY RAND()
                LIMIT 4";
            $relacionadas = $db->fetchAll($queryRelacionadas, [
                ':categoria_id' => $autoparte['categoria_id'],
                ':id' => $id
            ]);
            
            // Variables para la vista
            $pageTitle = $autoparte['nombre'] . ' - ' . $autoparte['marca'];
            $breadcrumbs = [
                ['text' => 'Inicio', 'url' => BASE_URL . '/index.php'],
                ['text' => 'Catálogo', 'url' => BASE_URL . '/index.php?module=public&action=catalogo'],
                ['text' => $autoparte['nombre'], 'url' => '']
            ];
            
            // Incluir la vista
            require_once VIEWS_PATH . '/public/detalle.php';
            
        } catch (Exception $e) {
            setFlashMessage(MSG_ERROR, 'Error al cargar el detalle');
            redirect('/index.php?module=public&action=catalogo');
        }
    }
    
    /**
     * Búsqueda de autopartes
     */
    public function buscar() {
        // Redirigir al catálogo con el parámetro de búsqueda
        $q = $_GET['q'] ?? '';
        redirect('/index.php?module=public&action=catalogo&q=' . urlencode($q));
    }
}
?>