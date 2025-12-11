<?php
/**
 * Vista: Página de Inicio Pública
 * Landing page del sistema de inventario
 * 
 * @author Grupo 1SF131
 */

// Incluir header público
require_once VIEWS_PATH . '/layouts/header.php';
?>

<!-- Hero Section -->
<section class="hero-section py-5" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-6 text-white">
                <h1 class="display-4 fw-bold mb-4">
                    Encuentra la Autoparte que Necesitas
                </h1>
                <p class="lead mb-4">
                    El inventario más completo de autopartes usadas y nuevas. 
                    Piezas de calidad para todas las marcas y modelos.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>/index.php?module=publico&action=catalogo" class="btn btn-warning btn-lg">
                        <i class="fas fa-search me-2"></i>Explorar Catálogo
                    </a>
                    <?php if (!isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/index.php?module=auth&action=registro" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Registrarse
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0 text-center">
                <i class="fas fa-car-crash text-warning" style="font-size: 12rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Estadísticas -->
<section class="py-4 bg-warning">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-cogs fa-2x me-3"></i>
                    <div class="text-start">
                        <h3 class="mb-0 fw-bold"><?= number_format($totalAutopartes ?? 0) ?>+</h3>
                        <small>Autopartes Disponibles</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-tags fa-2x me-3"></i>
                    <div class="text-start">
                        <h3 class="mb-0 fw-bold"><?= $totalCategorias ?? 0 ?></h3>
                        <small>Categorías</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-car fa-2x me-3"></i>
                    <div class="text-start">
                        <h3 class="mb-0 fw-bold"><?= $totalMarcas ?? 0 ?>+</h3>
                        <small>Marcas de Vehículos</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Búsqueda rápida -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">
                            <i class="fas fa-search text-primary me-2"></i>Búsqueda Rápida
                        </h4>
                        <form action="<?= BASE_URL ?>/index.php" method="GET">
                            <input type="hidden" name="module" value="publico">
                            <input type="hidden" name="action" value="catalogo">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <input type="text" class="form-control form-control-lg" name="buscar" 
                                           placeholder="¿Qué autoparte buscas?">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select form-select-lg" name="categoria">
                                        <option value="">Todas las categorías</option>
                                        <?php foreach ($categorias ?? [] as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-search me-1"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categorías -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Categorías de Autopartes</h2>
            <p class="text-muted">Explora nuestro inventario por categoría</p>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-4">
            <?php 
            $iconos = [
                'Motor' => 'fa-cog',
                'Carrocería' => 'fa-car-side',
                'Vidrios' => 'fa-border-all',
                'Eléctrico' => 'fa-bolt',
                'Interior' => 'fa-couch',
                'Suspensión' => 'fa-compress-arrows-alt',
                'Frenos' => 'fa-circle-notch',
                'Transmisión' => 'fa-cogs',
                'default' => 'fa-wrench'
            ];
            
            foreach ($categorias ?? [] as $cat): 
                $icono = $iconos[$cat['nombre']] ?? $iconos['default'];
            ?>
            <div class="col">
                <a href="<?= BASE_URL ?>/index.php?module=publico&action=categoria&id=<?= $cat['id'] ?>" 
                   class="card h-100 text-decoration-none categoria-card">
                    <div class="card-body text-center py-4">
                        <i class="fas <?= $icono ?> fa-3x text-primary mb-3"></i>
                        <h6 class="card-title mb-1"><?= htmlspecialchars($cat['nombre']) ?></h6>
                        <small class="text-muted"><?= $cat['total_autopartes'] ?? 0 ?> productos</small>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/index.php?module=publico&action=catalogo" class="btn btn-outline-primary">
                Ver Todas las Categorías <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- Productos destacados -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Autopartes Recientes</h2>
                <p class="text-muted mb-0">Las últimas piezas agregadas a nuestro inventario</p>
            </div>
            <a href="<?= BASE_URL ?>/index.php?module=publico&action=catalogo" class="btn btn-outline-primary">
                Ver Todo <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($destacadas ?? [] as $autoparte): ?>
            <div class="col">
                <div class="card h-100 shadow-sm producto-card">
                    <a href="<?= BASE_URL ?>/index.php?module=publico&action=detalle&id=<?= $autoparte['id'] ?>">
                        <?php if ($autoparte['imagen_thumb']): ?>
                            <img src="<?= UPLOADS_URL . '/' . htmlspecialchars($autoparte['imagen_thumb']) ?>" 
                                 class="card-img-top" alt="<?= htmlspecialchars($autoparte['nombre']) ?>"
                                 style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-4x text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                    
                    <span class="badge bg-success position-absolute" style="top: 10px; left: 10px;">Nuevo</span>
                    
                    <div class="card-body">
                        <h6 class="card-title">
                            <a href="<?= BASE_URL ?>/index.php?module=publico&action=detalle&id=<?= $autoparte['id'] ?>" 
                               class="text-decoration-none text-dark">
                                <?= htmlspecialchars($autoparte['nombre']) ?>
                            </a>
                        </h6>
                        <p class="card-text text-muted small mb-2">
                            <?= htmlspecialchars($autoparte['marca']) ?> <?= htmlspecialchars($autoparte['modelo']) ?> 
                            (<?= $autoparte['anio'] ?>)
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-primary mb-0">$<?= number_format($autoparte['precio'], 2) ?></h5>
                            <?php if ($autoparte['stock'] > 0): ?>
                                <span class="badge bg-success"><i class="fas fa-check"></i> En stock</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Agotado</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <a href="<?= BASE_URL ?>/index.php?module=publico&action=detalle&id=<?= $autoparte['id'] ?>" 
                           class="btn btn-outline-primary w-100">
                            <i class="fas fa-eye me-1"></i> Ver Detalle
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Por qué elegirnos -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">¿Por qué Elegirnos?</h2>
            <p class="text-muted">Tu mejor opción en autopartes</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body py-5">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-check-double fa-2x"></i>
                        </div>
                        <h5>Calidad Garantizada</h5>
                        <p class="text-muted mb-0">Todas nuestras piezas son revisadas antes de ser publicadas.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body py-5">
                        <div class="rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                        <h5>Mejores Precios</h5>
                        <p class="text-muted mb-0">Precios competitivos en todas nuestras autopartes.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body py-5">
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-headset fa-2x"></i>
                        </div>
                        <h5>Atención Personalizada</h5>
                        <p class="text-muted mb-0">Te ayudamos a encontrar la pieza exacta que necesitas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5" style="background: linear-gradient(135deg, #e94560 0%, #c73659 100%);">
    <div class="container text-center text-white">
        <h2 class="fw-bold mb-3">¿No encuentras lo que buscas?</h2>
        <p class="lead mb-4">Contáctanos y te ayudamos a encontrar la autoparte que necesitas</p>
        <a href="#" class="btn btn-light btn-lg">
            <i class="fab fa-whatsapp me-2"></i>Contáctanos por WhatsApp
        </a>
    </div>
</section>

<style>
.categoria-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
}
.categoria-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.producto-card {
    transition: transform 0.2s;
}
.producto-card:hover {
    transform: translateY(-5px);
}
</style>

<?php
// Incluir footer público
php require_once VIEWS_PATH . '/layouts/footer.php';
?>
