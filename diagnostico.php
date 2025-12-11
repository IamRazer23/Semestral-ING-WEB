<?php
/**
 * Diagnóstico de Carga de Archivos
 * Muestra exactamente qué se está cargando y en qué orden
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                🔍 Diagnóstico de Carga de Archivos
            </h1>

            <?php
            echo "<div class='space-y-4'>";
            
            // 1. Verificar que exista IErrorHandler.php
            echo "<h2 class='text-xl font-bold mt-4 mb-2'>1️⃣ Verificando IErrorHandler.php</h2>";
            
            $interfacePath = __DIR__ . '/core/interfaces/IErrorHandler.php';
            echo "<div class='bg-gray-50 p-4 rounded'>";
            echo "<p class='font-mono text-sm text-gray-700 mb-2'>Ruta: $interfacePath</p>";
            
            if (file_exists($interfacePath)) {
                echo "<p class='text-green-600'>✅ El archivo existe</p>";
                echo "<p class='text-sm text-gray-600'>Tamaño: " . filesize($interfacePath) . " bytes</p>";
                
                // Intentar cargarlo
                try {
                    require_once $interfacePath;
                    echo "<p class='text-green-600'>✅ Se cargó correctamente</p>";
                    
                    if (interface_exists('IErrorHandler')) {
                        echo "<p class='text-green-600'>✅ Interface IErrorHandler está disponible</p>";
                        
                        // Mostrar métodos de la interfaz
                        $reflection = new ReflectionClass('IErrorHandler');
                        $methods = $reflection->getMethods();
                        echo "<details class='mt-2'>";
                        echo "<summary class='cursor-pointer text-blue-600'>Ver métodos de la interface</summary>";
                        echo "<ul class='list-disc list-inside mt-2 text-sm'>";
                        foreach ($methods as $method) {
                            echo "<li>" . $method->getName() . "</li>";
                        }
                        echo "</ul>";
                        echo "</details>";
                    } else {
                        echo "<p class='text-red-600'>❌ Interface IErrorHandler NO está disponible después de cargar</p>";
                    }
                } catch (Exception $e) {
                    echo "<p class='text-red-600'>❌ Error al cargar: " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p class='text-red-600'>❌ El archivo NO existe</p>";
            }
            echo "</div>";
            
            // 2. Verificar ErrorHandler.php
            echo "<h2 class='text-xl font-bold mt-6 mb-2'>2️⃣ Verificando ErrorHandler.php</h2>";
            
            $errorHandlerPath = __DIR__ . '/core/ErrorHandler.php';
            echo "<div class='bg-gray-50 p-4 rounded'>";
            echo "<p class='font-mono text-sm text-gray-700 mb-2'>Ruta: $errorHandlerPath</p>";
            
            if (file_exists($errorHandlerPath)) {
                echo "<p class='text-green-600'>✅ El archivo existe</p>";
                echo "<p class='text-sm text-gray-600'>Tamaño: " . filesize($errorHandlerPath) . " bytes</p>";
                
                // Mostrar primeras líneas del archivo
                $content = file_get_contents($errorHandlerPath);
                $lines = explode("\n", $content);
                $firstLines = array_slice($lines, 0, 20);
                
                echo "<details class='mt-2'>";
                echo "<summary class='cursor-pointer text-blue-600'>Ver primeras 20 líneas</summary>";
                echo "<pre class='bg-gray-800 text-green-400 p-4 rounded mt-2 text-xs overflow-x-auto'>";
                echo htmlspecialchars(implode("\n", $firstLines));
                echo "</pre>";
                echo "</details>";
                
                // Intentar cargarlo solo si la interfaz está disponible
                if (interface_exists('IErrorHandler')) {
                    try {
                        require_once $errorHandlerPath;
                        echo "<p class='text-green-600 mt-2'>✅ Se cargó correctamente</p>";
                        
                        if (class_exists('ErrorHandler')) {
                            echo "<p class='text-green-600'>✅ Clase ErrorHandler está disponible</p>";
                        }
                    } catch (Exception $e) {
                        echo "<p class='text-red-600 mt-2'>❌ Error al cargar: " . $e->getMessage() . "</p>";
                    }
                } else {
                    echo "<p class='text-orange-600 mt-2'>⚠️ No se puede cargar porque IErrorHandler no está disponible</p>";
                }
            } else {
                echo "<p class='text-red-600'>❌ El archivo NO existe</p>";
            }
            echo "</div>";
            
            // 3. Verificar config.php
            echo "<h2 class='text-xl font-bold mt-6 mb-2'>3️⃣ Verificando config.php</h2>";
            
            $configPath = __DIR__ . '/config/config.php';
            echo "<div class='bg-gray-50 p-4 rounded'>";
            echo "<p class='font-mono text-sm text-gray-700 mb-2'>Ruta: $configPath</p>";
            
            if (file_exists($configPath)) {
                echo "<p class='text-green-600'>✅ El archivo existe</p>";
                echo "<p class='text-sm text-gray-600'>Tamaño: " . filesize($configPath) . " bytes</p>";
                
                // Buscar las líneas donde se carga ErrorHandler
                $content = file_get_contents($configPath);
                $lines = explode("\n", $content);
                
                $relevantLines = [];
                foreach ($lines as $index => $line) {
                    if (stripos($line, 'IErrorHandler') !== false || 
                        stripos($line, 'ErrorHandler') !== false) {
                        $relevantLines[] = ($index + 1) . ": " . $line;
                    }
                }
                
                if (!empty($relevantLines)) {
                    echo "<details class='mt-2'>";
                    echo "<summary class='cursor-pointer text-blue-600'>Ver líneas relacionadas con ErrorHandler</summary>";
                    echo "<pre class='bg-gray-800 text-green-400 p-4 rounded mt-2 text-xs overflow-x-auto'>";
                    echo htmlspecialchars(implode("\n", $relevantLines));
                    echo "</pre>";
                    echo "</details>";
                }
            } else {
                echo "<p class='text-red-600'>❌ El archivo NO existe</p>";
            }
            echo "</div>";
            
            // 4. Probar carga completa
            echo "<h2 class='text-xl font-bold mt-6 mb-2'>4️⃣ Probando Carga Completa</h2>";
            
            echo "<div class='bg-gray-50 p-4 rounded'>";
            echo "<p class='text-sm text-gray-700 mb-2'>Intentando cargar config.php...</p>";
            
            try {
                // Limpiar todo lo cargado hasta ahora
                $loadedClasses = get_declared_classes();
                $loadedInterfaces = get_declared_interfaces();
                
                echo "<p class='text-blue-600'>Clases cargadas hasta ahora: " . count($loadedClasses) . "</p>";
                echo "<p class='text-blue-600'>Interfaces cargadas hasta ahora: " . count($loadedInterfaces) . "</p>";
                
                // Intentar incluir config.php si aún no está cargado
                if (!defined('BASE_URL')) {
                    require_once $configPath;
                    echo "<p class='text-green-600 mt-2'>✅ config.php se cargó correctamente</p>";
                } else {
                    echo "<p class='text-blue-600 mt-2'>ℹ️ config.php ya estaba cargado</p>";
                }
                
                // Verificar constantes
                if (defined('BASE_URL')) {
                    echo "<p class='text-green-600'>✅ Constantes definidas (BASE_URL: " . BASE_URL . ")</p>";
                }
                
                // Verificar clases y funciones
                if (class_exists('Database')) {
                    echo "<p class='text-green-600'>✅ Clase Database disponible</p>";
                }
                if (class_exists('Validator')) {
                    echo "<p class='text-green-600'>✅ Clase Validator disponible</p>";
                }
                if (class_exists('ErrorHandler')) {
                    echo "<p class='text-green-600'>✅ Clase ErrorHandler disponible</p>";
                }
                if (function_exists('redirect')) {
                    echo "<p class='text-green-600'>✅ Función redirect() disponible</p>";
                }
                
            } catch (Exception $e) {
                echo "<p class='text-red-600 mt-2'>❌ Error: " . $e->getMessage() . "</p>";
                echo "<pre class='bg-red-50 p-2 rounded mt-2 text-xs'>" . $e->getTraceAsString() . "</pre>";
            }
            echo "</div>";
            
            // 5. Información del sistema
            echo "<h2 class='text-xl font-bold mt-6 mb-2'>5️⃣ Información del Sistema</h2>";
            echo "<div class='bg-gray-50 p-4 rounded'>";
            echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
            echo "<p><strong>Sistema Operativo:</strong> " . PHP_OS . "</p>";
            echo "<p><strong>Directorio actual:</strong> " . __DIR__ . "</p>";
            echo "<p><strong>Include Path:</strong> " . get_include_path() . "</p>";
            echo "</div>";
            
            echo "</div>";
            ?>

            <div class="mt-8 p-6 bg-blue-50 rounded-lg">
                <h3 class="font-bold text-lg mb-3">💡 Interpretación de Resultados</h3>
                <ul class="list-disc list-inside space-y-2 text-sm text-gray-700">
                    <li><strong>✅ Todo verde:</strong> Los archivos se están cargando correctamente</li>
                    <li><strong>❌ Errores rojos:</strong> Indica qué archivo está causando el problema</li>
                    <li><strong>⚠️ Advertencias naranjas:</strong> Posibles problemas de orden de carga</li>
                </ul>
            </div>

            <div class="mt-6 flex gap-4">
                <button onclick="location.reload()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold">
                    🔄 Recargar Diagnóstico
                </button>
                <a href="verificacion-simple.php" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold inline-block">
                    🔍 Ver Verificación Simple
                </a>
            </div>

        </div>
    </div>
</body>
</html>