<?php
/**
 * Vista: Detalle de Autoparte (Público)
 * Cumple con requisito 9: Detalle con imagen, costo, unidades y comentarios
 * 
 * @author Grupo 1SF131
 */

$pageTitle = htmlspecialchars($autoparte['nombre']) . ' - AutoPartes Pro';
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="<?= BASE_URL ?>" class="hover:text-indigo-600"><i class="fas fa-home"></i></a></li>
            <li><i class="fas fa-chevron-right text-xs text-gray-400"></i></li>
            <li><a href="<?= BASE_URL ?>/index.php?module=publico&action=catalogo" class="hover:text-indigo-600">Catálogo</a></li>
            <?php if ($autoparte['categoria_nombre']): ?>
            <li><i class="fas fa-chevron-right text-xs text-gray-400"></i></li>
            <li><a href="<?= BASE_URL ?>/index.php?module=publico&action=categoria&id=<?= $autoparte['categoria_id'] ?>" class="hover:text-indigo-600"><?= htmlspecialchars($autoparte['categoria_nombre']) ?></a></li>
            <?php endif; ?>
            <li><i class="fas fa-chevron-right text-xs text-gray-400"></i></li>
            <li class="text-indigo-600 font-medium truncate max-w-xs"><?= htmlspecialchars($autoparte['nombre']) ?></li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Columna de imagen -->
        <div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="relative">
                    <?php if ($autoparte['imagen_grande']): ?>
                        <img src="<?= UPLOADS_URL . '/' . htmlspecialchars($autoparte['imagen_grande']) ?>" 
                             alt="<?= htmlspecialchars($autoparte['nombre']) ?>"
                             class="w-full h-96 object-contain bg-gray-100 cursor-zoom-in"
                             id="imagen-principal"
                             onclick="abrirModal()">
                    <?php elseif ($autoparte['imagen_thumb']): ?>
                        <img src="<?= UPLOADS_URL . '/' . htmlspecialchars($autoparte['imagen_thumb']) ?>" 
                             alt="<?= htmlspecialchars($autoparte['nombre']) ?>"
                             class="w-full h-96 object-contain bg-gray-100">
                    <?php else: ?>
                        <div class="w-full h-96 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <i class="fas fa-car text-gray-400 text-8xl"></i>
                        </div>
                    <?php endif; ?>
                    
                    <span class="absolute top-4 left-4 bg-indigo-600 text-white text-sm font-bold px-3 py-1 rounded-full">
                        <?= htmlspecialchars($autoparte['categoria_nombre'] ?? 'Sin categoría') ?>
                    </span>
                </div>
                
                <?php if ($autoparte['imagen_thumb'] && $autoparte['imagen_grande']): ?>
                <div class="p-4 bg-gray-50 flex gap-2">
                    <img src="<?= UPLOADS_URL . '/' . htmlspecialchars($autoparte['imagen_thumb']) ?>" 
                         class="w-20 h-20 object-cover rounded-lg cursor-pointer border-2 border-transparent hover:border-indigo-500 transition"
                         onclick="cambiarImagen('<?= UPLOADS_URL . '/' . htmlspecialchars($autoparte['imagen_thumb']) ?>')">
                    <img src="<?= UPLOADS_URL . '/' . htmlspecialchars($autoparte['imagen_grande']) ?>" 
                         class="w-20 h-20 object-cover rounded-lg cursor-pointer border-2 border-transparent hover:border-indigo-500 transition"
                         onclick="cambiarImagen('<?= UPLOADS_URL . '/' . htmlspecialchars($autoparte['imagen_grande']) ?>')">
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Columna de información -->
        <div class="space-y-6">
            <!-- Título -->
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-3"><?= htmlspecialchars($autoparte['nombre']) ?></h1>
                <div class="flex flex-wrap gap-2">
                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-car mr-1"></i><?= htmlspecialchars($autoparte['marca']) ?>
                    </span>
                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                        <?= htmlspecialchars($autoparte['modelo']) ?>
                    </span>
                    <span class="bg-gray-800 text-white px-3 py-1 rounded-full text-sm font-medium">
                        <?= $autoparte['anio'] ?>
                    </span>
                </div>
            </div>

            <!-- Precio y Stock -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Precio</p>
                        <p class="text-4xl font-bold text-indigo-600">$<?= number_format($autoparte['precio'], 2) ?></p>
                        <p class="text-xs text-gray-500">ITBMS no incluido</p>
                    </div>
                    <div class="text-right">
                        <?php if ($autoparte['stock'] > 0): ?>
                            <div class="text-green-600">
                                <i class="fas fa-check-circle text-3xl"></i>
                                <p class="font-bold">Disponible</p>
                                <p class="text-sm"><?= $autoparte['stock'] ?> en stock</p>
                            </div>
                        <?php else: ?>
                            <div class="text-red-600">
                                <i class="fas fa-times-circle text-3xl"></i>
                                <p class="font-bold">Agotado</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Agregar al carrito -->
            <?php if ($autoparte['stock'] > 0): ?>
            <div class="bg-white rounded-xl shadow-md p-6">
                <?php if (isAuthenticated()): ?>
                    <form id="form-carrito" class="space-y-4">
                        <input type="hidden" name="autoparte_id" value="<?= $autoparte['id'] ?>">
                        <div class="flex items-center gap-4">
                            <label class="text-gray-700 font-medium">Cantidad:</label>
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button type="button" onclick="cambiarCantidad(-1)" class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="<?= $autoparte['stock'] ?>"
                                       class="w-16 text-center border-0 focus:ring-0">
                                <button type="button" onclick="cambiarCantidad(1)" class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-cart-plus mr-2 text-xl"></i>Agregar al Carrito
                        </button>
                    </form>
                    <div id="mensaje-carrito" class="mt-3"></div>
                <?php else: ?>
                    <div class="text-center">
                        <p class="text-gray-600 mb-4">
                            <i class="fas fa-info-circle text-indigo-500 mr-2"></i>
                            Inicia sesión para agregar al carrito
                        </p>
                        <a href="<?= BASE_URL ?>/index.php?module=auth&action=login" 
                           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 text-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-2"></i>
                <p class="text-red-700 font-medium">Este producto no está disponible actualmente</p>
            </div>
            <?php endif; ?>

            <!-- Descripción -->
            <?php if ($autoparte['descripcion']): ?>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-3">
                    <i class="fas fa-info-circle text-indigo-500 mr-2"></i>Descripción
                </h2>
                <p class="text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($autoparte['descripcion'])) ?></p>
            </div>
            <?php endif; ?>

            <!-- Especificaciones -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-3 border-b">
                    <h2 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-list text-indigo-500 mr-2"></i>Especificaciones
                    </h2>
                </div>
                <table class="w-full">
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-3 bg-gray-50 font-medium text-gray-700">Marca del vehículo</td>
                            <td class="px-6 py-3"><?= htmlspecialchars($autoparte['marca']) ?></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 bg-gray-50 font-medium text-gray-700">Modelo</td>
                            <td class="px-6 py-3"><?= htmlspecialchars($autoparte['modelo']) ?></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 bg-gray-50 font-medium text-gray-700">Año</td>
                            <td class="px-6 py-3"><?= $autoparte['anio'] ?></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 bg-gray-50 font-medium text-gray-700">Categoría</td>
                            <td class="px-6 py-3"><?= htmlspecialchars($autoparte['categoria_nombre'] ?? 'N/A') ?></td>
                        </tr>
                        <?php if ($autoparte['seccion_nombre']): ?>
                        <tr>
                            <td class="px-6 py-3 bg-gray-50 font-medium text-gray-700">Ubicación</td>
                            <td class="px-6 py-3"><i class="fas fa-map-marker-alt text-red-500 mr-1"></i><?= htmlspecialchars($autoparte['seccion_nombre']) ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="px-6 py-3 bg-gray-50 font-medium text-gray-700">ID Producto</td>
                            <td class="px-6 py-3">#<?= $autoparte['id'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Compartir -->
            <div class="flex items-center gap-3">
                <span class="text-gray-500">Compartir:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL ?? BASE_URL) ?>" target="_blank" class="w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?text=<?= urlencode($autoparte['nombre']) ?>" target="_blank" class="w-10 h-10 bg-sky-500 hover:bg-sky-600 text-white rounded-full flex items-center justify-center transition">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://wa.me/?text=<?= urlencode($autoparte['nombre'] . ' - ' . (SITE_URL ?? BASE_URL)) ?>" target="_blank" class="w-10 h-10 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center transition">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Comentarios -->
    <div class="mt-12">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-comments text-indigo-500 mr-2"></i>Comentarios y Opiniones
                    <span class="ml-2 bg-indigo-100 text-indigo-600 text-sm px-2 py-1 rounded-full"><?= count($comentarios) ?></span>
                </h2>
            </div>
            <div class="p-6">
                <!-- Formulario -->
                <?php if (isAuthenticated()): ?>
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800 mb-4">Deja tu opinión</h3>
                    <form id="form-comentario" class="space-y-4">
                        <input type="hidden" name="autoparte_id" value="<?= $autoparte['id'] ?>">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tu calificación</label>
                            <div class="flex gap-1 rating-stars">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="calificacion" value="<?= $i ?>" id="star<?= $i ?>" class="hidden" <?= $i == 5 ? 'checked' : '' ?>>
                                <label for="star<?= $i ?>" class="text-2xl cursor-pointer text-gray-300 hover:text-yellow-400 transition"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tu comentario</label>
                            <textarea name="comentario" rows="3" required minlength="10"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none"
                                      placeholder="Escribe tu opinión sobre este producto..."></textarea>
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                            <i class="fas fa-paper-plane mr-2"></i>Enviar Comentario
                        </button>
                    </form>
                    <div id="mensaje-comentario" class="mt-3"></div>
                </div>
                <?php else: ?>
                <div class="mb-8 pb-8 border-b border-gray-200 text-center bg-gray-50 rounded-lg py-6">
                    <p class="text-gray-600 mb-3">
                        <i class="fas fa-info-circle text-indigo-500 mr-2"></i>
                        Inicia sesión para dejar un comentario
                    </p>
                    <a href="<?= BASE_URL ?>/index.php?module=auth&action=login" class="text-indigo-600 hover:text-indigo-800 font-medium">
                        Iniciar Sesión <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <?php endif; ?>

                <!-- Lista de comentarios -->
                <?php if (empty($comentarios)): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="far fa-comment-dots text-5xl mb-3 opacity-50"></i>
                    <p>Aún no hay comentarios. ¡Sé el primero en opinar!</p>
                </div>
                <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($comentarios as $comentario): ?>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold"><?= strtoupper(substr($comentario['usuario_nombre'], 0, 1)) ?></span>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-gray-800"><?= htmlspecialchars($comentario['usuario_nombre']) ?></p>
                                    <div class="flex text-yellow-400 text-sm">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= $comentario['calificacion'] ? '' : 'text-gray-300' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500"><?= date('d/m/Y', strtotime($comentario['fecha_creacion'])) ?></span>
                            </div>
                            <p class="text-gray-600"><?= nl2br(htmlspecialchars($comentario['comentario'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Productos relacionados -->
    <?php if (!empty($relacionadas)): ?>
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-th-large text-indigo-500 mr-2"></i>Productos Relacionados
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($relacionadas as $rel): ?>
            <a href="<?= BASE_URL ?>/index.php?module=publico&action=detalle&id=<?= $rel['id'] ?>" 
               class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all overflow-hidden group">
                <?php if ($rel['imagen_thumb']): ?>
                    <img src="<?= UPLOADS_URL . '/' . htmlspecialchars($rel['imagen_thumb']) ?>" 
                         alt="<?= htmlspecialchars($rel['nombre']) ?>"
                         class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300">
                <?php else: ?>
                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-car text-gray-400 text-3xl"></i>
                    </div>
                <?php endif; ?>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 group-hover:text-indigo-600 transition line-clamp-1"><?= htmlspecialchars($rel['nombre']) ?></h3>
                    <p class="text-lg font-bold text-indigo-600 mt-2">$<?= number_format($rel['precio'], 2) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal de imagen -->
<div id="modal-imagen" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4" onclick="cerrarModal()">
    <div class="max-w-4xl max-h-full">
        <img src="<?= UPLOADS_URL . '/' . htmlspecialchars($autoparte['imagen_grande'] ?? $autoparte['imagen_thumb'] ?? '') ?>" 
             alt="<?= htmlspecialchars($autoparte['nombre']) ?>" class="max-w-full max-h-[90vh] object-contain">
    </div>
    <button class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300">&times;</button>
</div>

<style>
.rating-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.rating-stars input:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #fbbf24 !important;
}
</style>

<script>
function cambiarCantidad(delta) {
    const input = document.getElementById('cantidad');
    let valor = parseInt(input.value) + delta;
    if (valor < 1) valor = 1;
    if (valor > parseInt(input.max)) valor = parseInt(input.max);
    input.value = valor;
}

function cambiarImagen(src) {
    document.getElementById('imagen-principal').src = src;
}

function abrirModal() {
    document.getElementById('modal-imagen').classList.remove('hidden');
    document.getElementById('modal-imagen').classList.add('flex');
}

function cerrarModal() {
    document.getElementById('modal-imagen').classList.add('hidden');
    document.getElementById('modal-imagen').classList.remove('flex');
}

// Carrito
document.getElementById('form-carrito')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('<?= BASE_URL ?>/index.php?module=carrito&action=agregar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const msg = document.getElementById('mensaje-carrito');
        if (data.success) {
            msg.innerHTML = '<div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg"><i class="fas fa-check-circle mr-2"></i>' + data.message + '</div>';
            if (document.getElementById('cart-count')) {
                document.getElementById('cart-count').textContent = data.total_items;
            }
        } else {
            msg.innerHTML = '<div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg"><i class="fas fa-exclamation-circle mr-2"></i>' + data.message + '</div>';
        }
    });
});

// Comentarios
document.getElementById('form-comentario')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('<?= BASE_URL ?>/index.php?module=publico&action=agregar-comentario', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const msg = document.getElementById('mensaje-comentario');
        if (data.success) {
            msg.innerHTML = '<div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg"><i class="fas fa-check-circle mr-2"></i>' + data.message + '</div>';
            this.querySelector('textarea').value = '';
        } else {
            msg.innerHTML = '<div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg"><i class="fas fa-exclamation-circle mr-2"></i>' + data.message + '</div>';
        }
    });
});
</script>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>