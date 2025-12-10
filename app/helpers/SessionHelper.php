<?php
/**
 * SessionHelper
 * Encapsula funciones comunes de manejo de sesión.
 * Permite proteger, iniciar y gestionar datos de sesión de manera ordenada.
 */

class SessionHelper
{
    // Iniciar sesión si aún no está iniciada
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Guardar un valor en la sesión
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    // Obtener valor de la sesión
    public static function get($key)
    {
        self::start();
        return $_SESSION[$key] ?? null;
    }

    // Eliminar un valor
    public static function remove($key)
    {
        self::start();
        if (isset($_SESSION[$key])) unset($_SESSION[$key]);
    }

    // Destruir sesión completa
    public static function destroy()
    {
        self::start();
        session_destroy();
    }

    // Verificar si usuario está logueado
    public static function isLogged()
    {
        self::start();
        return isset($_SESSION['usuario']);
    }

    // Verificar rol admin
    public static function isAdmin()
    {
        self::start();
        return isset($_SESSION['usuario']) && 
               ($_SESSION['usuario']['rol'] ?? '') === 'admin';
    }
}
