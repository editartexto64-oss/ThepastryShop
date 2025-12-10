<?php

class Usuario extends Model
{
    private $table = "usuarios";

    // Crear usuario
    public function crear($data)
    {
        try {
            $sql = "INSERT INTO {$this->table} 
                    (nombres, apellidos, correo, contrasena, telefono, direccion, activo)
                    VALUES (:nombres, :apellidos, :correo, :contrasena, :telefono, :direccion, :activo)";

            $this->query($sql, $data);
            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            return false;
        }
    }

    // Buscar por correo
    public function buscarPorCorreo($correo)
    {
        try {
            return $this->query(
                "SELECT * FROM {$this->table} WHERE correo = :correo LIMIT 1",
                ['correo' => $correo]
            )->fetch();

        } catch (PDOException $e) {
            return null;
        }
    }

    // Obtener todos
    public function listar()
    {
        try {
            return $this->query("SELECT * FROM {$this->table}")->fetchAll();

        } catch (PDOException $e) {
            return [];
        }
    }
}
