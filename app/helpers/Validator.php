<?php
/**
 * Validator
 * Contiene funciones de validación para formularios y entradas de usuario:
 * - Validación de longitud
 * - Validación numérica
 * - Validación requerida
 * - Errores acumulados
 */

class Validator
{
    private $errors = [];

    // Validar campo requerido
    public function required($field, $value)
    {
        if (trim($value) === "") {
            $this->errors[$field] = "El campo $field es obligatorio.";
        }
    }

    // Validar longitud mínima
    public function minLength($field, $value, $min)
    {
        if (strlen(trim($value)) < $min) {
            $this->errors[$field] = "El campo $field debe tener al menos $min caracteres.";
        }
    }

    // Validar longitud máxima
    public function maxLength($field, $value, $max)
    {
        if (strlen(trim($value)) > $max) {
            $this->errors[$field] = "El campo $field no puede exceder los $max caracteres.";
        }
    }

    // Validar que sea un número
    public function numeric($field, $value)
    {
        if (!is_numeric($value)) {
            $this->errors[$field] = "El campo $field debe ser un número.";
        }
    }

    // Validar email
    public function email($field, $value)
    {
        if (!Security::isEmail($value)) {
            $this->errors[$field] = "Debe ingresar un correo válido.";
        }
    }

    // Verificar si hay errores
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    // Obtener lista de errores
    public function errors()
    {
        return $this->errors;
    }
}
