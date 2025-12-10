<?php
/**
 * Security
 * Contiene utilidades de sanitización y seguridad general.
 * - Protección contra XSS
 * - Tokens CSRF
 * - Sanitización de texto
 */

class Security
{
    /* ---------------------- XSS CLEAN ---------------------- */
    // Limpia texto de posibles etiquetas dañinas
    public static function clean($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    // Limpia array completo
    public static function cleanArray($arr)
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $arr[$key] = self::cleanArray($value);
            } else {
                $arr[$key] = self::clean($value);
            }
        }
        return $arr;
    }

    /* ---------------------- CSRF TOKEN ---------------------- */

    // Generar token CSRF seguro
    public static function generateToken()
    {
        SessionHelper::start();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    // Validar token CSRF enviado en formularios
    public static function validateToken($token)
    {
        SessionHelper::start();

        return isset($_SESSION['csrf_token']) &&
               hash_equals($_SESSION['csrf_token'], $token);
    }

    /* ------------------ FILTROS Y VALIDACIÓN ------------------ */

    // Normalizar email
    public static function filterEmail($email)
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    // Validar email
    public static function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
