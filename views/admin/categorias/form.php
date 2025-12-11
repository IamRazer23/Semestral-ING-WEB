<?php
/**
 * Vista: Formulario de Categoría (Crear/Editar)
 * Formulario unificado para crear y editar categorías
 */

// Obtener errores y valores antiguos si existen
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

// Determinar si es edición o creación
$esEdicion = isset($categoria) && $categoria;
$titulo = $esEdicion ? 'Editar Categoría' : 'Nueva Categoría';
$urlAction = $esEdicion ? 
    BASE_URL . '/index.php?module=admin&action=categoria-update' : 
    BASE_URL . '/index.php?module=admin&action=categoria-store';

// Scripts personalizados
$customScripts = '
<script>
// Preview de imagen
function previewImage(input) {
    const preview = document.getElementById("preview-imagen");
    const previewContainer = document.getElementById("preview-container");
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove("hidden");
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Limpiar imagen
function limpiarImagen() {
    document.getElementById("imagen").value = "";
    document.getElementById("preview-container").classList.add("hidden");
}

// Validar formulario antes de enviar
document.getElementById("form-categoria")?.addEventListener("submit", function(e) {
    const nombre = document.getElementById("nombre").value.trim();
    
    if (nombre.length < 3) {
        e.preventDefault();
        alert("El nombre debe tener al menos 3 caracteres");
        return false;
    }
    
    if (nombre.length > 100) {
        e.preventDefault();
        alert("El nombre no debe exceder 100 caracteres");
        return false;
    }
});

// Contador de caracteres para descripción
const descripcionTextarea = document.getElementById("descripcion");
const contador = document.getElementById("contador-caracteres");

if (descripcionTextarea && contador) {
    descripcionTextarea.addEventListener("input", function() {
        const actual = this.value.length;
        const maximo = 500;
        contador.textContent = `${actual}/${maximo}`;
        
        if (actual > maximo) {
            contador.classList.add("text-red-600");
        } else {
            contador.classList.remove("text-red-600");
        }
    });
    
    // Ejecutar al cargar
    descripcionTextarea.dispatchEvent(new Event("input"));
}
</script>
';

// Incluir header
require_once VIEWS_PATH . '/layouts/header.php';
?>

<div class="container mx-auto px-4 py-8 max-w-4xl">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-<?= $esEdicion ? 'edit' : 'plus-circle' ?> text-indigo-600 mr-2"></i>
            <?= $titulo ?>
        </h1>
        <p class="text-gray-600">
            <?= $esEdicion ? 'Modifica los datos de la categoría' : 'Completa el formulario para crear una nueva categoría' ?>
        </p>
    </div>

    <!-- Estadísticas (solo en edición) -->
    <?php if ($esEdicion && isset($estadisticas)): ?>
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-6 mb-6 text-white">
            <h3 class="text-lg font-semibold mb-4">
                <i class="fas fa-chart-bar mr-2"></i>
                Estadísticas de la Categoría
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold"><?= $estadisticas['total_autopartes'] ?></p>
                    <p class="text-sm opacity-90">Total Autopartes</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold"><?= $estadisticas['autopartes_activas'] ?></p>
                    <p class="text-sm opacity-90">Activas</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold"><?= $estadisticas['con_stock'] ?></p>
                    <p class="text-sm opacity-90">Con Stock</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold"><?= $estadisticas['stock_total'] ?></p>
                    <p class="text-sm opacity-90">Stock Total</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <form id="form-categoria" action="<?= $urlAction ?>" method="POST" enctype="multipart/form-data" class="p-8">
            
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id" value="<?= $categoria['id'] ?>">
            <?php endif; ?>

            <!-- Nombre -->
            <div class="mb-6">
                <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-tag text-indigo-600 mr-1"></i>
                    Nombre de la Categoría
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nombre" 
                       name="nombre" 
                       required
                       maxlength="100"
                       class="w-full px-4 py-3 border <?= isset($errors['nombre']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                       placeholder="Ej: Motor, Carrocería, Frenos..."
                       value="<?= $esEdicion ? e($categoria['nombre']) : (e($old['nombre'] ?? '')) ?>">
                
                <?php if (isset($errors['nombre'])): ?>
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= e($errors['nombre']) ?>
                    </p>
                <?php endif; ?>
                <p class="mt-1 text-xs text-gray-500">
                    Mínimo 3 caracteres, máximo 100
                </p>
            </div>

            <!-- Descripción -->
            <div class="mb-6">
                <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left text-indigo-600 mr-1"></i>
                    Descripción
                </label>
                <textarea id="descripcion" 
                          name="descripcion" 
                          rows="4"
                          maxlength="500"
                          class="w-full px-4 py-3 border <?= isset($errors['descripcion']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                          placeholder="Describe brevemente esta categoría..."><?= $esEdicion ? e($categoria['descripcion']) : (e($old['descripcion'] ?? '')) ?></textarea>
                
                <?php if (isset($errors['descripcion'])): ?>
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= e($errors['descripcion']) ?>
                    </p>
                <?php endif; ?>
                <div class="flex justify-between items-center mt-1">
                    <p class="text-xs text-gray-500">Opcional, máximo 500 caracteres</p>
                    <p id="contador-caracteres" class="text-xs text-gray-500 font-medium">0/500</p>
                </div>
            </div>

            <!-- Imagen -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-image text-indigo-600 mr-1"></i>
                    Imagen de la Categoría
                </label>
                
                <!-- Preview de imagen actual (en edición) -->
                <?php if ($esEdicion && $categoria['imagen']): ?>
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Imagen actual:</p>
                        <img src="<?= UPLOADS_URL ?>/categories/<?= e($categoria['imagen']) ?>" 
                             alt="<?= e($categoria['nombre']) ?>"
                             class="h-32 w-auto object-cover rounded-lg shadow-md">
                    </div>
                <?php endif; ?>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 transition">
                    <input type="file" 
                           id="imagen" 
                           name="imagen" 
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           onchange="previewImage(this)"
                           class="hidden">
                    
                    <label for="imagen" class="cursor-pointer">
                        <div class="space-y-2">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            <p class="text-sm text-gray-600">
                                <span class="text-indigo-600 font-semibold">Haz clic para subir</span>
                                o arrastra una imagen aquí
                            </p>
                            <p class="text-xs text-gray-500">
                                JPG, PNG o WEBP (máx. 2MB)
                            </p>
                        </div>
                    </label>
                </div>
                
                <!-- Preview de nueva imagen -->
                <div id="preview-container" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Vista previa:</p>
                            <img id="preview-imagen" 
                                 src="" 
                                 alt="Preview"
                                 class="h-32 w-auto object-cover rounded-lg shadow-md">
                        </div>
                        <button type="button" 
                                onclick="limpiarImagen()"
                                class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times-circle text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <?php if (isset($errors['imagen'])): ?>
                    <p class="mt-2 text-sm text-red-600">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= e($errors['imagen']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Estado -->
            <div class="mb-8">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" 
                           name="estado" 
                           value="1"
                           <?= ($esEdicion && $categoria['estado'] == 1) || (!$esEdicion) ? 'checked' : '' ?>
                           class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="text-sm font-medium text-gray-700">
                        <i class="fas fa-toggle-on text-green-500 mr-1"></i>
                        Categoría activa
                    </span>
                </label>
                <p class="ml-8 mt-1 text-xs text-gray-500">
                    Las categorías inactivas no aparecerán en el catálogo público
                </p>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="<?= BASE_URL ?>/index.php?module=admin&action=categorias" 
                   class="inline-flex items-center space-x-2 text-gray-600 hover:text-gray-800 font-medium">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver a la lista</span>
                </a>
                
                <div class="flex gap-3">
                    <button type="reset" 
                            class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-redo mr-2"></i>
                        Limpiar
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                        <i class="fas fa-<?= $esEdicion ? 'save' : 'plus-circle' ?> mr-2"></i>
                        <?= $esEdicion ? 'Guardar Cambios' : 'Crear Categoría' ?>
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- Ayuda -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-blue-800 mb-1">Consejos</h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• El nombre debe ser único y descriptivo</li>
                    <li>• La imagen es opcional pero ayuda a identificar la categoría</li>
                    <li>• Las categorías inactivas no se mostrarán en el catálogo público</li>
                    <?php if ($esEdicion && isset($estadisticas) && $estadisticas['total_autopartes'] > 0): ?>
                        <li class="text-orange-700 font-semibold">
                            • No puedes eliminar esta categoría porque tiene <?= $estadisticas['total_autopartes'] ?> autopartes asociadas
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

</div>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>