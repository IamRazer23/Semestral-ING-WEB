<?php
/**
 * Interface IErrorHandler
 * Define el contrato para manejo de errores
 * Cumple con requisito 14: Utilizar Interfaces para control de errores
 * 
 * @author Grupo 1SF131
 * @version 1.0
 */

interface IErrorHandler {
    
    /**
     * Maneja un error genérico
     * 
     * @param string $message Mensaje de error
     * @param int $code Código de error
     * @param string $file Archivo donde ocurrió
     * @param int $line Línea donde ocurrió
     * @return void
     */
    public function handleError($message, $code = 0, $file = '', $line = 0);
    
    /**
     * Maneja una excepción
     * 
     * @param Exception $exception
     * @return void
     */
    public function handleException($exception);
    
    /**
     * Registra un error en log
     * 
     * @param string $message
     * @param string $level (info, warning, error, critical)
     * @return void
     */
    public function logError($message, $level = 'error');
    
    /**
     * Obtiene el último error registrado
     * 
     * @return array|null
     */
    public function getLastError();
    
    /**
     * Limpia los errores almacenados
     * 
     * @return void
     */
    public function clearErrors();
}
?>