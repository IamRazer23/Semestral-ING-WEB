<?php
/**
 * Verificación Simple del Sistema
 * Este archivo NO carga ninguna dependencia, solo verifica que existan
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación Simple</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                🔍 Verificación Simple de Estructura
            </h1>

            <?php
            $baseDir = __DIR__;
            $errors = [];
            $warnings = [];
            $success = [];

            // 1. Verificar carpetas principales
            echo "<h2 class='text-xl font-bold mt-6 mb-4'>📁 Verificando Carpetas</h2>";
            echo "<div class='space-y-2'>";
            
            $folders = [
                'config',
                'core',
                'core/interfaces',
                'models',
                'controllers',
                'views',
                'views/layouts',
                'views/auth',
                'views/admin',
                'views/admin/categorias',
                'public',
                'public/uploads',
                'public/uploads/categories',
                'logs',
                'database'
            ];

            foreach ($folders as $folder) {
                $path = $baseDir . '/' . $folder;
                $exists = file_exists($path) && is_dir($path);
                
                if ($exists) {
                    echo "<div class='flex items-center text-green-600'>";
                    echo "<span class='text-2xl mr-2'>✅</span>";
                    echo "<span>$folder</span>";
                    echo "</div>";
                    $success[] = $folder;
                } else {
                    echo "<div class='flex items-center text-red-600'>";
                    echo "<span class='text-2xl mr-2'>❌</span>";
                    echo "<span>$folder - <strong>NO EXISTE</strong></span>";
                    echo "</div>";
                    $errors[] = "Carpeta faltante: $folder";
                }
            }
            
            echo "</div>";

            // 2. Verificar archivos críticos
            echo "<h2 class='text-xl font-bold mt-6 mb-4'>📄 Verificando Archivos Core</h2>";
            echo "<div class='space-y-2'>";
            
            $coreFiles = [
                'index.php' => 'Enrutador principal',
                'config/config.php' => 'Configuración del sistema',
                'config/Database.php' => 'Clase de conexión',
                'core/interfaces/IErrorHandler.php' => 'Interface de errores',
                'core/ErrorHandler.php' => 'Manejador de errores',
                'core/Validator.php' => 'Validador de datos',
                'database/schema.sql' => 'Script de base de datos'
            ];

            foreach ($coreFiles as $file => $description) {
                $path = $baseDir . '/' . $file;
                $exists = file_exists($path);
                
                if ($exists) {
                    $size = filesize($path);
                    echo "<div class='flex items-center justify-between text-green-600'>";
                    echo "<div class='flex items-center'>";
                    echo "<span class='text-2xl mr-2'>✅</span>";
                    echo "<span>$file</span>";
                    echo "</div>";
                    echo "<span class='text-sm text-gray-500'>" . number_format($size) . " bytes</span>";
                    echo "</div>";
                    $success[] = $file;
                } else {
                    echo "<div class='flex items-center text-red-600'>";
                    echo "<span class='text-2xl mr-2'>❌</span>";
                    echo "<span>$file - <strong>NO EXISTE</strong></span>";
                    echo "</div>";
                    $errors[] = "Archivo faltante: $file ($description)";
                }
            }
            
            echo "</div>";

            // 3. Verificar modelos
            echo "<h2 class='text-xl font-bold mt-6 mb-4'>🗂️ Verificando Modelos</h2>";
            echo "<div class='space-y-2'>";
            
            $models = [
                'models/Usuario.php',
                'models/Categoria.php'
            ];

            foreach ($models as $file) {
                $path = $baseDir . '/' . $file;
                $exists = file_exists($path);
                
                if ($exists) {
                    echo "<div class='flex items-center text-green-600'>";
                    echo "<span class='text-2xl mr-2'>✅</span>";
                    echo "<span>$file</span>";
                    echo "</div>";
                    $success[] = $file;
                } else {
                    echo "<div class='flex items-center text-orange-600'>";
                    echo "<span class='text-2xl mr-2'>⚠️</span>";
                    echo "<span>$file - Faltante</span>";
                    echo "</div>";
                    $warnings[] = "Modelo faltante: $file";
                }
            }
            
            echo "</div>";

            // 4. Verificar controladores
            echo "<h2 class='text-xl font-bold mt-6 mb-4'>🎮 Verificando Controladores</h2>";
            echo "<div class='space-y-2'>";
            
            $controllers = [
                'controllers/AuthController.php',
                'controllers/AdminController.php',
                'controllers/CategoriaController.php',
                'controllers/OperadorController.php',
                'controllers/ClienteController.php',
                'controllers/PublicController.php'
            ];

            foreach ($controllers as $file) {
                $path = $baseDir . '/' . $file;
                $exists = file_exists($path);
                
                if ($exists) {
                    echo "<div class='flex items-center text-green-600'>";
                    echo "<span class='text-2xl mr-2'>✅</span>";
                    echo "<span>$file</span>";
                    echo "</div>";
                    $success[] = $file;
                } else {
                    echo "<div class='flex items-center text-orange-600'>";
                    echo "<span class='text-2xl mr-2'>⚠️</span>";
                    echo "<span>$file - Faltante</span>";
                    echo "</div>";
                    $warnings[] = "Controlador faltante: $file";
                }
            }
            
            echo "</div>";

            // 5. Verificar vistas principales
            echo "<h2 class='text-xl font-bold mt-6 mb-4'>👁️ Verificando Vistas</h2>";
            echo "<div class='space-y-2'>";
            
            $views = [
                'views/layouts/header.php',
                'views/layouts/footer.php',
                'views/layouts/menu_admin.php',
                'views/auth/login.php',
                'views/auth/register.php',
                'views/admin/dashboard.php',
                'views/admin/categorias/index.php',
                'views/admin/categorias/form.php'
            ];

            foreach ($views as $file) {
                $path = $baseDir . '/' . $file;
                $exists = file_exists($path);
                
                if ($exists) {
                    echo "<div class='flex items-center text-green-600'>";
                    echo "<span class='text-2xl mr-2'>✅</span>";
                    echo "<span>$file</span>";
                    echo "</div>";
                    $success[] = $file;
                } else {
                    echo "<div class='flex items-center text-orange-600'>";
                    echo "<span class='text-2xl mr-2'>⚠️</span>";
                    echo "<span>$file - Faltante</span>";
                    echo "</div>";
                    $warnings[] = "Vista faltante: $file";
                }
            }
            
            echo "</div>";

            // 6. Verificar permisos de carpetas
            echo "<h2 class='text-xl font-bold mt-6 mb-4'>🔐 Verificando Permisos</h2>";
            echo "<div class='space-y-2'>";
            
            $writableFolders = [
                'public/uploads',
                'public/uploads/categories',
                'logs'
            ];

            foreach ($writableFolders as $folder) {
                $path = $baseDir . '/' . $folder;
                $exists = file_exists($path);
                $writable = $exists && is_writable($path);
                
                if ($writable) {
                    echo "<div class='flex items-center text-green-600'>";
                    echo "<span class='text-2xl mr-2'>✅</span>";
                    echo "<span>$folder - Escribible</span>";
                    echo "</div>";
                } else if ($exists) {
                    echo "<div class='flex items-center text-orange-600'>";
                    echo "<span class='text-2xl mr-2'>⚠️</span>";
                    echo "<span>$folder - Sin permisos de escritura</span>";
                    echo "</div>";
                    $warnings[] = "Carpeta sin permisos: $folder";
                } else {
                    echo "<div class='flex items-center text-red-600'>";
                    echo "<span class='text-2xl mr-2'>❌</span>";
                    echo "<span>$folder - No existe</span>";
                    echo "</div>";
                    $errors[] = "Carpeta faltante: $folder";
                }
            }
            
            echo "</div>";

            // 7. Resumen
            echo "<div class='mt-8 p-6 bg-gray-50 rounded-lg'>";
            echo "<h2 class='text-xl font-bold mb-4'>📊 Resumen</h2>";
            echo "<div class='grid grid-cols-3 gap-4'>";
            
            echo "<div class='text-center'>";
            echo "<div class='text-4xl text-green-600 font-bold'>" . count($success) . "</div>";
            echo "<div class='text-sm text-gray-600'>Archivos OK</div>";
            echo "</div>";
            
            echo "<div class='text-center'>";
            echo "<div class='text-4xl text-orange-600 font-bold'>" . count($warnings) . "</div>";
            echo "<div class='text-sm text-gray-600'>Advertencias</div>";
            echo "</div>";
            
            echo "<div class='text-center'>";
            echo "<div class='text-4xl text-red-600 font-bold'>" . count($errors) . "</div>";
            echo "<div class='text-sm text-gray-600'>Errores Críticos</div>";
            echo "</div>";
            
            echo "</div>";
            echo "</div>";

            // 8. Estado final y recomendaciones
            if (count($errors) == 0) {
                echo "<div class='mt-6 bg-green-100 border-l-4 border-green-500 p-4'>";
                echo "<div class='flex'>";
                echo "<div class='text-3xl mr-4'>✅</div>";
                echo "<div>";
                echo "<h3 class='text-lg font-bold text-green-800'>¡Estructura Completa!</h3>";
                echo "<p class='text-green-700'>Todos los archivos críticos están presentes.</p>";
                
                if (count($warnings) > 0) {
                    echo "<p class='text-orange-700 mt-2'>Hay " . count($warnings) . " advertencias menores que puedes revisar.</p>";
                }
                
                echo "</div>";
                echo "</div>";
                echo "</div>";
                
                echo "<div class='mt-6'>";
                echo "<a href='verificacion.php' class='bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold inline-block mr-4'>";
                echo "🔍 Verificación Completa (con BD)";
                echo "</a>";
                echo "<a href='index.php' class='bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold inline-block'>";
                echo "🚀 Ir al Sistema";
                echo "</a>";
                echo "</div>";
            } else {
                echo "<div class='mt-6 bg-red-100 border-l-4 border-red-500 p-4'>";
                echo "<div class='flex'>";
                echo "<div class='text-3xl mr-4'>❌</div>";
                echo "<div>";
                echo "<h3 class='text-lg font-bold text-red-800'>Faltan Archivos Críticos</h3>";
                echo "<p class='text-red-700'>Debes crear/copiar " . count($errors) . " archivos antes de continuar.</p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                
                echo "<div class='mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6'>";
                echo "<h3 class='font-bold text-lg mb-3 text-yellow-900'>📋 Archivos Faltantes:</h3>";
                echo "<ul class='list-disc list-inside space-y-1 text-yellow-800'>";
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
                echo "</div>";
            }
            ?>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="font-bold text-lg mb-3">💡 Instrucciones</h3>
                <div class="space-y-2 text-sm text-gray-700">
                    <p><strong>Si faltan archivos:</strong></p>
                    <ol class="list-decimal list-inside ml-4 space-y-1">
                        <li>Verifica que hayas copiado TODOS los archivos</li>
                        <li>Respeta la estructura de carpetas exacta</li>
                        <li>El archivo <code>core/interfaces/IErrorHandler.php</code> es CRÍTICO</li>
                        <li>Recarga esta página después de copiar archivos</li>
                    </ol>
                    
                    <p class="mt-4"><strong>Si todo está OK:</strong></p>
                    <ol class="list-decimal list-inside ml-4 space-y-1">
                        <li>Ejecuta la verificación completa (incluye BD)</li>
                        <li>Configura la base de datos</li>
                        <li>Prueba el sistema</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>
</body>
</html>