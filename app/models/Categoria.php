<?php

class Categoria extends Model
{
    private $table = "categorias";

    public function crear($nombre)
    {
        try {
            $this->query(
                "INSERT INTO {$this->table} (nombre, activo) VALUES (:nombre, 1)",
                ['nombre' => $nombre]
            );
            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarActivas()
    {
        try {
            return $this->query("SELECT * FROM {$this->table} WHERE activo = 1")->fetchAll();

        } catch (PDOException $e) {
            return [];
        }
    }
}
